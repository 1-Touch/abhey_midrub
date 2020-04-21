<?php
/**
 * Location Reviews Template
 *
 * This file contains the Stream's Location Reviews template 
 *
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.6
 */

// Define the page namespace
namespace MidrubBase\User\Apps\Collection\Stream\Templates;

// Constants
defined('BASEPATH') OR exit('No direct script access allowed');

// Define the namespaces to use
use MidrubBase\User\Apps\Collection\Stream\Interfaces as MidrubBaseUserAppsCollectionStreamInterfaces;
use MidrubBase\User\Apps\Collection\Stream\Helpers as MidrubBaseUserAppsCollectionStreamHelpers;

/*
 * Location_reviews contains the Stream's Location Reviews template 
 * 
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.6
 */
class Location_reviews implements MidrubBaseUserAppsCollectionStreamInterfaces\Stream {
    
    /**
     * Class variables
     *
     * @since 0.0.7.4
     */
    protected
            $CI, $client, $appName, $clientId, $clientSecret, $apiKey, $gmbService, $scriptUri;

    /**
     * Initialise the Class
     *
     * @since 0.0.7.5
     */
    public function __construct() {
        
        // Get codeigniter object instance
        $this->CI =& get_instance();
        
        // Get the Google's client_id
        $this->clientId = get_option('google_my_business_client_id');

        // Get the Google's client_secret
        $this->clientSecret = get_option('google_my_business_client_secret');

        // Get the Google's api key
        $this->apiKey = get_option('google_my_business_api_key');

        // Get the Google's application name
        $this->appName = get_option('google_my_business_application_name');
        
        // Require the vendor autoload
        include_once FCPATH . 'vendor/autoload.php';
        
        // Google My Business Callback
        $this->scriptUri = base_url() . 'user/callback/google_my_business';
        
    }
    
    /**
     * The public method stream_process_action processes the stream's item action
     * 
     * @param object $stream contains the stream's configuration
     * 
     * @since 0.0.7.5
     * 
     * @return void
     */ 
    public function stream_process_action($stream) {
        
    }
    
    /**
     * The public method stream_colors_change changes stream's colors
     * 
     * @param integer $stream_id contains the stream's ID
     * @param string $name contains the stream's Name
     * @param string $value contains the Stream's Value
     * 
     * @since 0.0.7.5
     * 
     * @return void
     */ 
    public function stream_colors_change($stream_id, $name, $value) {
        
        // Set allowed names
        $allowed_name = array(
            'header_text_color',
            'item_text_color',
            'links_color',
            'icons_color',
            'background_color',
            'border_color'
        );
        
        // Verify if name is in array
        if ( in_array($name, $allowed_name) && !preg_match('/[^A-Za-z0-9.#\\-$]/', $value) ) {

            if ( $this->CI->stream_tabs_model->update_stream_field($stream_id, $name, $value) ) {
                
                $data = array(
                    'success' => TRUE,
                    'stream_id' => $stream_id,
                    'name' => $name,
                    'value' => $value
                );

                echo json_encode($data);  
                exit();
                
            }

        }
        
        $data = array(
            'success' => FALSE,
            'message' => $this->CI->lang->line('error_occurred')
        );

        echo json_encode($data);  
        
    }
    
    /**
     * The public method template_content contains the template's content
     * 
     * @param object $stream contains the stream's configuration
     * @param boolean $return contains the return option
     * 
     * @since 0.0.7.5
     * 
     * @return array with tempate's content
     */ 
    public function template_content( $stream, $return = FALSE ) {
        
        // Get the template's info
        $template_info = $this->template_info();
        
        // Default variables value for customization
        $border_bottom_color = '';
        $paragraph_color = '';
        $links_color = '';
        $icons_color = '';
        
        // Verify if border color exists
        if ( $stream->border_color ) {
            $border_bottom_color = ' style="border-bottom-color: ' . $stream->border_color . '"';
        } 
        
        // Verify if item's text color exists
        if ( $stream->item_text_color ) {
            $paragraph_color = ' style="color: ' . $stream->item_text_color . '"';
        }     
        
        // Verify if links color exists
        if ( $stream->links_color ) {
            $links_color = ' style="color: ' . $stream->links_color . '"';
        }
        
        // Verify if icons colors exists
        if ( $stream->icons_color ) {
            $icons_color = ' style="color: ' . $stream->icons_color . '"';
        } 
        
        // Get network data
        $network_details = $this->CI->stream_networks_model->get_network_data('google_my_business', $this->CI->user_id, $stream->network_id);
        
        // Call the class Google_Client
        $this->client = new \Google_Client();

        // Name of the google application
        $this->client->setApplicationName($this->appName);

        // Set the client_id
        $this->client->setClientId($this->clientId);

        // Set the client_secret
        $this->client->setClientSecret($this->clientSecret);

        // Redirects to same url
        $this->client->setRedirectUri($this->scriptUri);

        // Set the api key
        $this->client->setDeveloperKey($this->apiKey);

        // Get refresh token 
        $this->client->refreshToken($network_details[0]->secret);

        // Decode the response
        $token = $this->client->getAccessToken();

        // Set access token
        $this->client->setAccessToken($token); 

        $this->gmbService = new \Google_Service_MyBusiness($this->client); 
        
        $reviews = $this->gmbService->accounts_locations_reviews;
        
        $listReviewsResponse = $reviews->listAccountsLocationsReviews($network_details[0]->net_id);
        
        $get_setup = (new MidrubBaseUserAppsCollectionStreamHelpers\Streams_setup)->streams_setup_get($stream->stream_id, 'location_reviews');

        $all_reviews = '';
        
        $ids = array();
        
        if ( $listReviewsResponse->getReviews() ) {
            
            $actions = $this->CI->stream_history_model->get_item_actions($stream->stream_id);
            
            $all_actions = array();
            
            if ( $actions ) {
                
                foreach ( $actions as $action ) {
                    
                    $all_actions[$action->type . '-' . $action->id] = $action->value;
                    
                }
                
            }
            
            $C = 0;
            
            foreach ( $listReviewsResponse->getReviews() as $review ) {
                
                $ids[] = $review->reviewId;
                
                $rating = '';
                
                switch($review->starRating) {
                    
                    case 'ONE':
                        
                        $rating = '<i class="fas fa-star" style="margin-left: 10px; color: #e7711b;"></i>';
                        
                        break;
                    
                    case 'TWO':
                        
                        $rating = '<i class="fas fa-star" style="margin-left: 10px; color: #e7711b;"></i><i class="fas fa-star" style="color: #e7711b;"></i>';
                        
                        break;
                    
                    case 'THREE':
                        
                        $rating = '<i class="fas fa-star" style="margin-left: 10px; color: #e7711b;"></i><i class="fas fa-star" style="color: #e7711b;"></i><i class="fas fa-star" style="color: #e7711b;"></i>';
                        
                        break;
                    
                    case 'FOUR':
                        
                        $rating = '<i class="fas fa-star" style="margin-left: 10px; color: #e7711b;"></i><i class="fas fa-star" style="color: #e7711b;"></i><i class="fas fa-star" style="color: #e7711b;"></i><i class="fas fa-star" style="color: #e7711b;"></i>';
                        
                        break;
                    
                    case 'FIVE':
                        
                        $rating = '<i class="fas fa-star" style="margin-left: 10px; color: #e7711b;"></i><i class="fas fa-star" style="color: #e7711b;"></i><i class="fas fa-star" style="color: #e7711b;"></i><i class="fas fa-star" style="color: #e7711b;"></i><i class="fas fa-star" style="color: #e7711b;"></i>';
                        
                        break;
                    
                }
                
                $comment = '';
                
                if ( @$review->reviewReply->comment ) {

                    $comment = '<div class="col-xl-12">'
                                    . '<ul>'
                                        . '<li class="row">'
                                            . '<div class="col-xl-12">'
                                                . '<em>'
                                                    . $review->reviewReply->comment
                                                . '</em>'
                                            . '</div>'
                                        . '</li>'
                                    . '</ul>'
                                . '</div>';

                }

                $all_reviews .= '<li class="row"' . $border_bottom_color . '>
                                <div class="col-xl-12">
                                    <img src="' . $review->reviewer->profilePhotoUrl . '" alt="User Avatar" class="img-circle">
                                    <div class="stream-post">
                                        <strong>
                                            ' . $review->reviewer->displayName . '
                                            ' . $rating . '
                                        </strong><br>
                                        <small class="clean"' . $icons_color . '>
                                            <i class="icon-clock"></i>' . calculate_time(strtotime($review->createTime), time()) . '
                                        </small>
                                        <p' . $paragraph_color . ' data-type="stream-item-content">
                                            ' . preg_replace('@(https?://([-\w\.]+[-\w])+(:\d+)?(/([\w/_\.#-~]*(\?\S+)?)?)?)@', '<a href="$1"' . $links_color . ' target="_blank">$1</a>', $review->comment) . '
                                        </p>
                                        <div class="stream-post-footer">   
                                            <a href="#" data-network="location_reviews" class="stream-item-react" data-stream="' . $stream->stream_id . '" data-type="reply" data-id="' . $review->reviewId . '"' . $icons_color . '>
                                                <i class="fas fa-reply"></i>
                                            </a>  
                                        </div>
                                    </div>
                                </div>
                                ' . $comment . '
                            </li>';
                
                $C++;
                
                if ( $C > 10 ) {
                    break;
                }
                
            }
            
        } else {
            
            $all_reviews .= '<li class="row"' . $border_bottom_color . '>'
                            . '<div class="col-xl-12">'
                                . '<div>'
                                    . '<p>' . $this->CI->lang->line('no_results_found') . '</p>'
                                . '</div>'
                            . '</div>'
                        . '</li>';
            
        }
        
        $active = '';
        $active_check = 0;
        $sound = '';
        
        $cronology = $this->CI->stream_history_model->get_stream_cronology($stream->stream_id);
        
        if ( !$cronology ) {
        
            $this->CI->stream_history_model->update_stream_cronology($stream->stream_id, serialize($ids));
            
        } else {
            
            if ( $cronology[0]->value !== serialize($ids) ) {
                
                $this->CI->stream_history_model->update_stream_cronology($stream->stream_id, serialize($ids));
                
                if ( $this->CI->stream_tabs_model->update_stream_field($stream->stream_id, 'new_event', 1) ) {
                    $active_check++;
                    
                    if ( file_exists(FCPATH . '/assets/sounds/' . $stream->alert_sound . '.mp3') ) {

                        $sound = '<iframe src="' . base_url('/assets/sounds/' . $stream->alert_sound . '.mp3') . '" class="d-none" allow="autoplay" id="audio"></iframe>';
                        
                    }
                    
                }
                
            }
            
        }
        
        if ( $active_check ) {
        
            $active = ' stream-mark-seen-item-active';
            
        } else if ( $stream->new_event ) {
            
            $active = ' stream-mark-seen-item-active';
            
        }
        
        $stream_data = array (
            'header' => '<i class="fab fa-google" style="color: #e1584b;"></i>'
                        . $template_info['displayed_name']
                        . '<div class="float-right">'
                            . '<a href="#" class="stream-mark-seen-item' . $active . '">'
                                . '<i class="icon-bell"></i>'
                            . '</a>'            
                            . '<a href="#stream-settings" class="stream-open-settings" data-toggle="modal">'
                                . '<i class="icon-settings"></i>'
                            . '</a>'                                            
                        . '</div>',
            'content' => '<ul>'
                            . $all_reviews
                        . '</ul>',
            'footer' => $sound
            
        );
        
        $data = array(
            'success' => TRUE,
            'stream' => $stream_data,
            'message' => $this->CI->lang->line('selected_stream_connected'),
            'stream_id' => $stream->stream_id,
            'background_color' => $stream->background_color
        );
        
        if ( $return ) {
            
            return $data;
            
        } else {

            echo json_encode($data);  
        
        }
        
    }
    
    /**
     * The public method template_content_single provides the stream's item content
     * 
     * @param integer $stream contains the stream's configuration
     * 
     * @since 0.0.7.5
     * 
     * @return array with stream's item content
     */ 
    public function template_content_single( $stream ) {
        
        // Check if data was submitted
        if ($this->CI->input->post()) {
            
            // Add form validation
            $this->CI->form_validation->set_rules('type', 'Type', 'trim|required');
            $this->CI->form_validation->set_rules('id', 'ID', 'trim|required');
            
            // Get data
            $type = $this->CI->input->post('type');
            $id = $this->CI->input->post('id');
            
            
            if ( $this->CI->form_validation->run() !== false ) {
                
                $network_details = $this->CI->stream_networks_model->get_network_data('google_my_business', $this->CI->user_id, $stream[0]->network_id);
                
                if ( $network_details ) {
                    
                    switch ( $type ) {
                            
                        case 'reply': 
                            
                            // Call the class Google_Client
                            $this->client = new \Google_Client();

                            // Name of the google application
                            $this->client->setApplicationName($this->appName);

                            // Set the client_id
                            $this->client->setClientId($this->clientId);

                            // Set the client_secret
                            $this->client->setClientSecret($this->clientSecret);

                            // Redirects to same url
                            $this->client->setRedirectUri($this->scriptUri);

                            // Set the api key
                            $this->client->setDeveloperKey($this->apiKey);

                            // Get refresh token 
                            $this->client->refreshToken($network_details[0]->secret);

                            // Decode the response
                            $token = $this->client->getAccessToken();

                            // Set access token
                            $this->client->setAccessToken($token); 

                            $this->gmbService = new \Google_Service_MyBusiness($this->client); 

                            $reviews = $this->gmbService->accounts_locations_reviews;

                            $review = $reviews->get($network_details[0]->net_id . '/reviews/' . $id);

                            $get_setup = (new MidrubBaseUserAppsCollectionStreamHelpers\Streams_setup)->streams_setup_get($stream[0]->stream_id, 'location_reviews');

                            if ( $review ) {
                                
                                $rating = '';

                                switch($review->starRating) {

                                    case 'ONE':

                                        $rating = '<i class="fas fa-star" style="margin-left: 10px; color: #e7711b;"></i>';

                                        break;

                                    case 'TWO':

                                        $rating = '<i class="fas fa-star" style="margin-left: 10px; color: #e7711b;"></i><i class="fas fa-star" style="color: #e7711b;"></i>';

                                        break;

                                    case 'THREE':

                                        $rating = '<i class="fas fa-star" style="margin-left: 10px; color: #e7711b;"></i><i class="fas fa-star" style="color: #e7711b;"></i><i class="fas fa-star" style="color: #e7711b;"></i>';

                                        break;

                                    case 'FOUR':

                                        $rating = '<i class="fas fa-star" style="margin-left: 10px; color: #e7711b;"></i><i class="fas fa-star" style="color: #e7711b;"></i><i class="fas fa-star" style="color: #e7711b;"></i><i class="fas fa-star" style="color: #e7711b;"></i>';

                                        break;

                                    case 'FIVE':

                                        $rating = '<i class="fas fa-star" style="margin-left: 10px; color: #e7711b;"></i><i class="fas fa-star" style="color: #e7711b;"></i><i class="fas fa-star" style="color: #e7711b;"></i><i class="fas fa-star" style="color: #e7711b;"></i><i class="fas fa-star" style="color: #e7711b;"></i>';

                                        break;

                                }
                                
                                $com_data = '<div class="col-xl-12 stream-simgle-item-template">'
                                            . '<img src="' . $review->reviewer->profilePhotoUrl . '" alt="User Avatar" class="img-circle">'
                                            . '<div class="stream-post">'
                                                . '<strong>'
                                                    . $review->reviewer->displayName
                                                    . $rating
                                                . '</strong>'
                                                . '<p>'
                                                    . @$review->reviewReply->comment
                                                . '</p>'
                                            . '</div>'
                                        . '</div>';

                                $data = array(
                                    'success' => TRUE,
                                    'content' => $com_data,
                                    'form' => form_open('user/app/stream', ['class' => 'stream-send-react', 'data-csrf' => $this->CI->security->get_csrf_token_name()])
                                                . '<div class="form-group">'
                                                    . '<textarea name="comment" class="form-control" rows="3"></textarea>'
                                                . '</div>'
                                                . '<input type="hidden" name="id" value="' .  $id . '">'
                                                . '<input type="hidden" name="stream_id" value="' .  $stream[0]->stream_id . '">'
                                                . '<input type="hidden" name="type" value="reply">'                                    
                                                . '<button type="submit" class="btn btn-primary">' . $this->CI->lang->line('submit') . '</button>'
                                            . form_close(),
                                    'menu_text' => $this->CI->lang->line('review')
                                );

                                echo json_encode($data);  
                                exit();
                                
                            }                    
                            
                            break;
                            
                    }
                    

                    
                }
                
            }
            
        }
        
        $data = array(
            'success' => FALSE,
            'message' => $this->CI->lang->line('error_occurred')
        );

        echo json_encode($data); 
        
    }
    
    /**
     * The public method stream_update_setup saves stream's setup
     * 
     * @since 0.0.7.5
     * 
     * @return void
     */ 
    public function stream_update_setup() {
    }
    
    /**
     * The public method stream_get_setup gets the stream's setup
     * 
     * @param object $stream contains the stream's configuration
     * 
     * @since 0.0.7.5
     * 
     * @return void
     */ 
    public function stream_get_setup( $stream ) {
        
        $network_details = $this->CI->stream_networks_model->get_network_data('google_my_business', $this->CI->user_id, $stream[0]->network_id);
        
        $setup_data = '';
        
        if ( $network_details ) {
            
            $icon = '';

            $get_icon = (new MidrubBaseUserAppsCollectionStreamHelpers\Accounts)->get_network_info( 'google_my_business' );

            if ( $get_icon ) {

                $icon = $get_icon['icon'];

            }
            
            $setup_data = '<div class="row">'
                            . '<div class="col-xl-12 mb-3">'
                                . '<div class="row clean connected-account">'
                                    . '<div class="col-xl-8 col-8">'
                                        . '<p>'
                                            . '<i class="icon-location-pin"></i> ' . $this->CI->lang->line('connected_location')
                                        . '</p>'
                                    . '</div>'
                                    . '<div class="col-xl-4 col-4 text-right">'
                                        . $icon . $network_details[0]->user_name
                                    . '</div>'
                                . '</div>'                    
                            . '</div>'    
                        . '</div>';
                      
            
        }
        
        $data = array(
            'success' => TRUE,
            'setup_data' => $setup_data,
            'alert_sound' => $stream[0]->alert_sound,
            'tab_id' => $stream[0]->tab_id,
            'stream' => $stream
        );
        
        if ( !$stream[0]->alert_sound ) {
            $data['message'] = $this->CI->lang->line('select_a_sound');
        } else {
            $data['message'] = '';
        }

        echo json_encode($data); 
        
    }
    
    /**
     * The public method stream_delete_setup deletes stream's setup
     * 
     * @since 0.0.7.5
     * 
     * @return void
     */ 
    public function stream_delete_setup() {
       
    }
    
    /**
     * The public method template_connect contains the template's connection settings
     * 
     * @since 0.0.7.5
     * 
     * @return array with settings
     */ 
    public function template_connect() {
        
        // Load language
        $this->CI->lang->load( 'stream_instructions', $this->CI->config->item('language'), FALSE, TRUE, MIDRUB_BASE_USER_APPS_STREAM );
        
        // Get Active Accounts
        $active_accounts = (new MidrubBaseUserAppsCollectionStreamHelpers\Accounts)->get_active_accounts($this->CI->stream_networks_model->get_accounts_by_network( $this->CI->user_id, 'google_my_business', 1 ), 1);   
        
        $icon = '';
        
        $get_icon = (new MidrubBaseUserAppsCollectionStreamHelpers\Accounts)->get_network_info( 'google_my_business' );
        
        if ( $get_icon ) {
            
            $icon = $get_icon['icon'];
            
        }
        
        return array (
            'active_accounts' => $active_accounts,
            'expired_accounts' => '',
            'network' => 'google_my_business',
            'instructions' => $this->CI->lang->line('associated_social_account'),
            'new_account' => $icon . $this->CI->lang->line('new_account'),
            'placeholder' => $this->CI->lang->line('search_accounts'),
            'type' => 1
        );
        
    }   
    
    /**
     * The public method stream_post processes the post actions
     * 
     * @param object $stream contains the stream's configuration
     * 
     * @since 0.0.7.5
     * 
     * @return void
     */ 
    public function stream_post($stream) {
        
        // Check if data was submitted
        if ($this->CI->input->post()) {
            
            // Add form validation
            $this->CI->form_validation->set_rules('type', 'Type', 'trim|required');
            $this->CI->form_validation->set_rules('id', 'ID', 'trim|required');
            $this->CI->form_validation->set_rules('comment', 'Comment', 'trim|required');
            
            // Get data
            $type = $this->CI->input->post('type');
            $id = $this->CI->input->post('id');
            $comment = $this->CI->input->post('comment');
            
            if ( $this->CI->form_validation->run() !== false ) {
                
                $network_details = $this->CI->stream_networks_model->get_network_data('google_my_business', $this->CI->user_id, $stream[0]->network_id);
                
                if ( $network_details ) {
                
                    switch ( $type ) {
                            
                        case 'reply':

                            // Call the class Google_Client
                            $this->client = new \Google_Client();

                            // Name of the google application
                            $this->client->setApplicationName($this->appName);

                            // Set the client_id
                            $this->client->setClientId($this->clientId);

                            // Set the client_secret
                            $this->client->setClientSecret($this->clientSecret);

                            // Redirects to same url
                            $this->client->setRedirectUri($this->scriptUri);

                            // Set the api key
                            $this->client->setDeveloperKey($this->apiKey);

                            // Get refresh token 
                            $this->client->refreshToken($network_details[0]->secret);

                            // Decode the response
                            $token = $this->client->getAccessToken();

                            // Set access token
                            $this->client->setAccessToken($token); 

                            $this->gmbService = new \Google_Service_MyBusiness($this->client); 

                            $reviews = $this->gmbService->accounts_locations_reviews;
                            
                            $reviewReply = new \Google_Service_Mybusiness_ReviewReply();

                            $reviewReply->setComment($comment);

                            $response = $reviews->updateReply($network_details[0]->net_id . '/reviews/' . $id, $reviewReply);

                            if ( $response->updateTime ) {
                                    
                                $data = array(
                                    'success' => TRUE,
                                    'tab_id' => $stream[0]->tab_id,
                                    'message' => $this->CI->lang->line('comment_was_published')
                                );

                                echo json_encode($data); 

                            } else {

                                $data = array(
                                    'success' => FALSE,
                                    'message' => $this->CI->lang->line('comment_was_not_published')
                                );

                                echo json_encode($data);                                     

                            }

                            exit();

                    }
                
                }
                
            }
            
        }
        
        $data = array(
            'success' => FALSE,
            'message' => $this->CI->lang->line('error_occurred')
        );

        echo json_encode($data);
        
    }
    
    /**
     * The public method stream_delete processes the deletion actions
     * 
     * @param object $stream contains the stream's configuration
     * 
     * @since 0.0.7.5
     * 
     * @return void
     */ 
    public function stream_delete($stream) {
        
    }
    
    /**
     * The public method stream_extra processes the unexpected actions
     * 
     * @param string $type contains the request's type
     * @param array $data contains the request's data
     * 
     * @since 0.0.7.5
     * 
     * @return void
     */ 
    public function stream_extra($type, $data) {
        
    }
    
    /**
     * The public method template_info contains the template's information
     * 
     * @since 0.0.7.5
     * 
     * @return array with tempate's information
     */ 
    public function template_info() {
        
        return array (
            'displayed_name' => $this->CI->lang->line('location_reviews'),
            'icon' => '<i class="fab fa-google"></i>',
            'description' => $this->CI->lang->line('location_reviews_description'),
            'parent' => 'google_my_business'
        );
        
    }
    
}

/* End of file Location_reviews.php */
