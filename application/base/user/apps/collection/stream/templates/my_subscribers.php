<?php
/**
 * My Subscribers Template
 *
 * This file contains the Stream's My Subscribers template 
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
 * My_subscribers contains the Stream's My Subscribers template 
 * 
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.6
 */
class My_subscribers implements MidrubBaseUserAppsCollectionStreamInterfaces\Stream {
    
    /**
     * Class variables
     *
     * @since 0.0.7.6
     */
    protected $CI;

    /**
     * Initialise the Class
     *
     * @since 0.0.7.6
     */
    public function __construct() {
        
        // Get codeigniter object instance
        $this->CI =& get_instance();
        
    }
    
    /**
     * The public method stream_process_action processes the stream's item action
     * 
     * @param object $stream contains the stream's configuration
     * 
     * @since 0.0.7.6
     * 
     * @return void
     */ 
    public function stream_process_action($stream) {
        
        // Get the Gmail's client_id
        $clientId = get_option('youtube_client_id');
        
        // Get the Gmail's client_secret
        $clientSecret = get_option('youtube_client_secret');
        
        // Get the Gmail's api key
        $apiKey = get_option('youtube_api_key');
        
        // Get the Gmail's application name
        $appName = get_option('youtube_google_application_name');
        
        // Require the  vendor's libraries
        require_once FCPATH . 'vendor/autoload.php';
        require_once FCPATH . 'vendor/google/src/Google_Client.php';
        require_once FCPATH . 'vendor/google/src/contrib/Google_YouTubeService.php';
        
        // Gmail Callback
        $scriptUri = base_url() . 'user/callback/gmail';
        
        $params = array_filter(array());

        // Check if data was submitted
        if ($this->CI->input->post()) {
            
            // Add form validation
            $this->CI->form_validation->set_rules('stream_id', 'Template Name', 'trim|required');
            $this->CI->form_validation->set_rules('item_id', 'Item ID', 'trim|required');
            $this->CI->form_validation->set_rules('item_type', 'Item Type', 'trim|required');
            
            // Get data
            $stream_id = $this->CI->input->post('stream_id');
            $item_id = $this->CI->input->post('item_id');
            $item_type = $this->CI->input->post('item_type');
            
            
            if ( $this->CI->form_validation->run() !== false ) {
                
                $network_details = $this->CI->stream_networks_model->get_network_data('youtube', $this->CI->user_id, $stream[0]->network_id);
                
                if ( $network_details ) {
                    
                    switch( $item_type ) {
                        
                        case 'subscribe':
                            
                            $action = $this->CI->stream_history_model->get_item_actions($stream_id, $item_id, 1);
                            
                            if ( $action ) {
                                
                                if ( $action[0]->created > (time() - 3600) ) {
                                    
                                    $data = array(
                                        'success' => FALSE,
                                        'message' => $this->CI->lang->line('you_have_to_wait_a_hour')
                                    );

                                    echo json_encode($data);
                                    exit();
                                    
                                }
                            
                                // Call the class Google_Client
                                $client = new \Google_Client();

                                // Name of the google application
                                $client->setApplicationName($appName);

                                // Set the client_id
                                $client->setClientId($clientId);

                                // Set the client_secret
                                $client->setClientSecret($clientSecret);

                                // Redirects to same url
                                $client->setRedirectUri($scriptUri);

                                // Set the api key
                                $client->setDeveloperKey($apiKey);

                                // Refresh token
                                $client->refreshToken($network_details[0]->secret);

                                // Get access token
                                $newtoken = $client->getAccessToken();

                                // Set access token
                                $client->setAccessToken($newtoken);

                                // Call the Youtube Services
                                $service = new \Google_YouTubeService($client);
                                
                                try {
                                
                                    $response = $service->subscriptions->delete(
                                        $action[0]->value,
                                        array()
                                    );
                                    
                                    $this->CI->stream_history_model->delete_stream_history($action[0]->history_id);
                                    
                                    $data = array(
                                        'success' => TRUE,
                                        'message' => $this->CI->lang->line('you_have_unsubscribed_successfully'),
                                        'tab_id' => $stream[0]->tab_id
                                    );

                                    echo json_encode($data);  
                                    exit();
     
                                } catch (Exception $ex) {

                                    $data = array(
                                        'success' => FALSE,
                                        'message' => $this->CI->lang->line('you_have_not_unsubscribed_successfully')
                                    );

                                    echo json_encode($data);
                                    exit();
                                    
                                }
                                
                            } else {
                                
                                // Call the class Google_Client
                                $client = new \Google_Client();

                                // Name of the google application
                                $client->setApplicationName($appName);

                                // Set the client_id
                                $client->setClientId($clientId);

                                // Set the client_secret
                                $client->setClientSecret($clientSecret);

                                // Redirects to same url
                                $client->setRedirectUri($scriptUri);

                                // Set the api key
                                $client->setDeveloperKey($apiKey);

                                // Refresh token
                                $client->refreshToken($network_details[0]->secret);

                                // Get access token
                                $newtoken = $client->getAccessToken();

                                // Set access token
                                $client->setAccessToken($newtoken);

                                $propertyObject = createResource(array('snippet.resourceId.kind' => 'youtube#channel',
                                    'snippet.resourceId.channelId' => $item_id));

                                $resource = new \Google_Service_YouTube_Subscription($propertyObject);

                                // Call the Youtube Services
                                $service = new \Google_YouTubeService($client);

                                $response = @$service->subscriptions->insert('snippet', $resource, array());
                                
                                if ( $response['id'] ) {
                                    
                                    $this->CI->stream_history_model->save_stream_item_action($stream_id, $item_id, 1, $response['id']);

                                    $data = array(
                                        'success' => TRUE,
                                        'message' => $this->CI->lang->line('you_have_subscribed_successfully'),
                                        'tab_id' => $stream[0]->tab_id
                                    );

                                    echo json_encode($data);  
                                    exit();

                                } else {

                                    $data = array(
                                        'success' => FALSE,
                                        'message' => $this->CI->lang->line('you_have_not_subscribed_successfully')
                                    );

                                    echo json_encode($data);
                                    exit();

                                }
                                
                            }

                            break;
                    
                    }
                    
                }
                
            }
            
            $data = array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('error_occurred')
            );

            echo json_encode($data);
            
        }
        
    }
    
    /**
     * The public method stream_colors_change changes stream's colors
     * 
     * @param integer $stream_id contains the stream's ID
     * @param string $name contains the stream's Name
     * @param string $value contains the Stream's Value
     * 
     * @since 0.0.7.6
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
     * @since 0.0.7.6
     * 
     * @return array with tempate's content
     */ 
    public function template_content( $stream, $return = FALSE ) {
        
        // Get the template's info
        $template_info = $this->template_info();
        
        $border_bottom_color = '';
        $paragraph_color = '';
        $links_color = '';
        $icons_color = '';
        
        if ( $stream->border_color ) {
            $border_bottom_color = ' style="border-bottom-color: ' . $stream->border_color . '"';
        } 
        
        if ( $stream->item_text_color ) {
            $paragraph_color = ' style="color: ' . $stream->item_text_color . '"';
        }     
        
        if ( $stream->links_color ) {
            $links_color = ' style="color: ' . $stream->links_color . '"';
        }
        
        if ( $stream->icons_color ) {
            $icons_color = ' style="color: ' . $stream->icons_color . '"';
        } 
        
        $network_details = $this->CI->stream_networks_model->get_network_data('youtube', $this->CI->user_id, $stream->network_id);
        
        $params = array(
            'part' => 'subscriberSnippet',
            'maxResults' => 10,
            'mySubscribers' => 'true',
            'fields' => 'etag%2CeventId%2Citems%2Ckind%2CnextPageToken%2CpageInfo%2CprevPageToken%2CtokenPagination%2CvisitorId'
        );
        
        $token = '';
        
        if ( $network_details[0]->secret ) {
            
            $response = json_decode(post('https://www.googleapis.com/oauth2/v4/token', array('client_id' => get_option('youtube_client_id'), 'client_secret' => get_option('youtube_client_secret'), 'refresh_token' => $network_details[0]->secret, 'grant_type' => 'refresh_token')), true);
            
            if ( $response ) {
            
                $token = $response['access_token'];

            }
            
        }
        
        $subscribers = json_decode(get('https://www.googleapis.com/youtube/v3/subscriptions' . '?' . urldecode(http_build_query($params)) . '&access_token=' . $token), true);
        
        $get_setup = (new MidrubBaseUserAppsCollectionStreamHelpers\Streams_setup)->streams_setup_get($stream->stream_id, 'my_subscribers');

        $all_users = '';
        
        $ids = array();
  
        if ( $subscribers['items'] ) {
            
            $actions = $this->CI->stream_history_model->get_item_actions($stream->stream_id);
            
            $all_actions = array();
            
            if ( $actions ) {
                
                foreach ( $actions as $action ) {
                    
                    $all_actions[$action->type . '-' . $action->id] = $action->value;
                    
                }
                
            }

            foreach ( $subscribers['items'] as $sub ) {
                
                $ids[] = $sub['id'];
                
                $button = '<button type="button" class="btn btn-dark btn-subscribe mt-1 mb-2 stream-item-action" data-stream="' . $stream->stream_id . '" data-type="subscribe" data-id="' . $sub['subscriberSnippet']['channelId'] . '">'
                                . '<i class="icon-user-following"></i> ' . $this->CI->lang->line('subscribe')
                            . '</button>';
                
                if ( isset($all_actions['1-' . $sub['subscriberSnippet']['channelId']]) ) {
                    
                    $button = '<button type="button" class="btn btn-dark btn-subscribe mt-1 mb-2 stream-item-action" data-stream="' . $stream->stream_id . '" data-type="subscribe" data-id="' . $sub['subscriberSnippet']['channelId'] . '">'
                                    . '<i class="icon-user-unfollow"></i> ' . $this->CI->lang->line('unsubscribe')
                                . '</button>';
                
                }

                $all_users .= '<li class="row"' . $border_bottom_color . '>
                                    <div class="col-xl-12">
                                        <img src="' . $sub['subscriberSnippet']['thumbnails']['default']['url'] . '" alt="User Avatar" class="img-circle">
                                        <div class="stream-post">
                                            <strong>
                                                <a href="https://www.youtube.com/channel/' . $sub['subscriberSnippet']['channelId'] . '" target="_blank"' . $links_color . '>
                                                    ' . $sub['subscriberSnippet']['title'] . '
                                                </a>
                                            </strong>
                                            <p>
                                                ' . $button . '
                                            </p>
                                        </div>
                                    </div>
                            </li>';
                
            }
            
        } else {
            
            $all_users .= '<li class="row"' . $border_bottom_color . '>'
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
            'header' => '<i style="color: #ca3737;" class="icon-social-youtube"></i>'
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
                            . $all_users
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
     * @since 0.0.7.6
     * 
     * @return array with stream's item content
     */ 
    public function template_content_single( $stream ) {
        
    }
    
    /**
     * The public method stream_update_setup saves stream's setup
     * 
     * @since 0.0.7.6
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
     * @since 0.0.7.6
     * 
     * @return void
     */ 
    public function stream_get_setup( $stream ) {
        
        $network_details = $this->CI->stream_networks_model->get_network_data('youtube', $this->CI->user_id, $stream[0]->network_id);
        
        $setup_data = '';
        
        if ( $network_details ) {
            
            $icon = '';

            $get_icon = (new MidrubBaseUserAppsCollectionStreamHelpers\Accounts)->get_network_info( 'youtube' );

            if ( $get_icon ) {

                $icon = $get_icon['icon'];

            }
            
            $setup_data = '<div class="row">'
                            . '<div class="col-xl-12">'
                                . '<div class="row clean connected-account">'
                                    . '<div class="col-xl-8 col-8">'
                                        . '<p>'
                                            . '<i class="icon-user-following"></i> ' . $this->CI->lang->line('connected_channel')
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
     * @since 0.0.7.6
     * 
     * @return void
     */ 
    public function stream_delete_setup() {
        
    }
    
    /**
     * The public method template_connect contains the template's connection settings
     * 
     * @since 0.0.7.6
     * 
     * @return array with settings
     */ 
    public function template_connect() {
        
        // Load language
        $this->CI->lang->load( 'stream_instructions', $this->CI->config->item('language'), FALSE, TRUE, MIDRUB_BASE_USER_APPS_STREAM);
        
        // Get Active Accounts
        $active_accounts = (new MidrubBaseUserAppsCollectionStreamHelpers\Accounts)->get_active_accounts($this->CI->stream_networks_model->get_accounts_by_network( $this->CI->user_id, 'youtube', 1 ), 1);   
        
        $icon = '';
        
        $get_icon = (new MidrubBaseUserAppsCollectionStreamHelpers\Accounts)->get_network_info( 'youtube' );
        
        if ( $get_icon ) {
            
            $icon = $get_icon['icon'];
            
        }
        
        return array (
            'active_accounts' => $active_accounts,
            'expired_accounts' => '',
            'network' => 'youtube',
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
     * @since 0.0.7.6
     * 
     * @return void
     */ 
    public function stream_post($stream) {
        
    }
    
    /**
     * The public method stream_delete processes the deletion actions
     * 
     * @param object $stream contains the stream's configuration
     * 
     * @since 0.0.7.6
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
     * @since 0.0.7.6
     * 
     * @return void
     */ 
    public function stream_extra($type, $data) {
        
    }
    
    /**
     * The public method template_info contains the template's information
     * 
     * @since 0.0.7.6
     * 
     * @return array with tempate's information
     */ 
    public function template_info() {
        
        return array (
            'displayed_name' => $this->CI->lang->line('my_subscribers'),
            'icon' => '<i class="icon-social-youtube"></i>',
            'description' => $this->CI->lang->line('my_subscribers_description'),
            'parent' => 'youtube'
        );
        
    }
    
}

/* End of file My_subscribers.php */
