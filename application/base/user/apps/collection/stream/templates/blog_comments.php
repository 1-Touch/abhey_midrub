<?php
/**
 * Blog Comments Template
 *
 * This file contains the Stream's Blog Comments template 
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
 * Blog_comments contains the Stream's Blog Comments template 
 * 
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.6
 */
class Blog_comments implements MidrubBaseUserAppsCollectionStreamInterfaces\Stream {
    
    /**
     * Class variables
     *
     * @since 0.0.7.4
     */
    protected
            $CI, $clientId, $clientSecret, $apiKey, $appName, $scriptUri;

    /**
     * Initialise the Class
     *
     * @since 0.0.7.5
     */
    public function __construct() {
        
        // Get codeigniter object instance
        $this->CI =& get_instance();
        
        // Get the Blogger's client_id
        $this->clientId = get_option('blogger_client_id');
        
        // Get the Blogger's client_secret
        $this->clientSecret = get_option('blogger_client_secret');
        
        // Get the Blogger's api key
        $this->apiKey = get_option('blogger_api_key');
        
        // Get the Blogger's application name
        $this->appName = get_option('blogger_google_application_name');
        
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
                
                $network_details = $this->CI->stream_networks_model->get_network_data('blogger', $this->CI->user_id, $stream[0]->network_id);
                
                if ( $network_details ) {

                    $dat = explode('-', $item_id);
                    
                    switch( $item_type ) {
                        
                        case 'delete':

                            $token = '';

                            if ( $network_details[0]->secret ) {

                                $response = json_decode(post('https://www.googleapis.com/oauth2/v4/token', array('client_id' => $this->clientId, 'client_secret' => $this->clientSecret, 'refresh_token' => $network_details[0]->secret, 'grant_type' => 'refresh_token')), true);

                                if ( $response ) {

                                    $token = $response['access_token'];

                                }

                            }
                            

                            // Get cURL resource
                            $curl = curl_init();

                            // Set some options to in a useragent
                            curl_setopt_array($curl, array(
                                CURLOPT_RETURNTRANSFER => 1,
                                CURLOPT_CUSTOMREQUEST => 'DELETE',
                                CURLOPT_URL => 'https://www.googleapis.com/blogger/v3/blogs/' . $network_details[0]->net_id . '/posts/' . $dat[1] . '/comments/' . $dat[0] . '?access_token=' . $token,
                                CURLOPT_HEADER => false)
                            );
                            
                            curl_exec($curl);
                            
                            // Close request to clear up some resources
                            curl_close($curl);
                            
                            $response = json_decode(get('https://www.googleapis.com/blogger/v3/blogs/' . $network_details[0]->net_id . '/posts/' . $dat[1] . '/comments/' . $dat[0] . '?access_token=' . $token), true);

                            if ( !$response ) {
                                
                                    $data = array(
                                        'success' => TRUE,
                                        'message' => $this->CI->lang->line('comment_was_deleted'),
                                        'tab_id' => $stream[0]->tab_id
                                    );

                                    echo json_encode($data);  
                                    exit();

                                } else {

                                    $data = array(
                                        'success' => FALSE,
                                        'message' => $this->CI->lang->line('comment_was_not_deleted')
                                    );

                                    echo json_encode($data);
                                    exit();

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
        
        $network_details = $this->CI->stream_networks_model->get_network_data('blogger', $this->CI->user_id, $stream->network_id);
        
        $token = '';
        
        if ( $network_details[0]->secret ) {
            
            $response = json_decode(post('https://www.googleapis.com/oauth2/v4/token', array('client_id' => $this->clientId, 'client_secret' => $this->clientSecret, 'refresh_token' => $network_details[0]->secret, 'grant_type' => 'refresh_token')), true);

            if ( $response ) {
            
                $token = $response['access_token'];

            }
            
        }
        
        $get_setup = (new MidrubBaseUserAppsCollectionStreamHelpers\Streams_setup)->streams_setup_get($stream->stream_id, 'blog_comments'); 

        $response = json_decode(get('https://www.googleapis.com/blogger/v3/blogs/' . $network_details[0]->net_id . '/comments?maxResults=10&access_token=' . $token), true);

        $all_comments = '';
        
        $ids = array();
        
        if ( @$response['items'] ) {
            
            $actions = $this->CI->stream_history_model->get_item_actions($stream->stream_id);
            
            foreach ( $response['items'] as $comment ) {
                
                $ids[] = $comment['id'];

                $all_comments .= '<li class="row"' . $border_bottom_color . '>
                                <div class="col-xl-12">
                                    <img src="' . $comment['author']['image']['url'] . '" alt="User Avatar" class="img-circle">
                                    <div class="stream-post">
                                        <strong>
                                            ' . $comment['author']['displayName'] . '
                                        </strong><br>
                                        <small class="clean"' . $icons_color . '>
                                            <i class="icon-clock"></i>' . calculate_time(strtotime($comment['published']), time()) . '
                                        </small>
                                        <p' . $paragraph_color . '>
                                            ' . preg_replace('@(https?://([-\w\.]+[-\w])+(:\d+)?(/([\w/_\.#-~]*(\?\S+)?)?)?)@', '<a href="$1" target="_blank">$1</a>', $comment['content']) . '
                                        </p>  
                                        <div class="stream-post-footer">
                                            <a href="#" class="stream-item-action" data-stream="' . $stream->stream_id . '" data-type="delete" data-id="' . $comment['id'] . '-' . $comment['post']['id'] . '"' . $icons_color . '>
                                                <i class="icon-trash"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </li>';
                
            }
            
        } else {
            
            $all_comments .= '<li class="row"' . $border_bottom_color . '>'
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
            'header' => '<i class="fa fa-bold" style="color: #fb8f3d;"></i>'
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
                            . $all_comments
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
        
        $network_details = $this->CI->stream_networks_model->get_network_data('blogger', $this->CI->user_id, $stream[0]->network_id);
        
        $setup_data = '';
        
        if ( $network_details ) {
            
            $icon = '';

            $get_icon = (new MidrubBaseUserAppsCollectionStreamHelpers\Accounts)->get_network_info( 'blogger' );

            if ( $get_icon ) {

                $icon = $get_icon['icon'];

            }
            
            $setup_data = '<div class="row">'
                            . '<div class="col-xl-12 mb-3">'
                                . '<div class="row clean connected-account">'
                                    . '<div class="col-xl-8 col-8">'
                                        . '<p>'
                                            . '<i class="icon-user-following"></i> ' . $this->CI->lang->line('blog')
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
        $active_accounts = (new MidrubBaseUserAppsCollectionStreamHelpers\Accounts)->get_active_accounts($this->CI->stream_networks_model->get_accounts_by_network( $this->CI->user_id, 'blogger', 1 ), 1);   
        
        $icon = '';
        
        $get_icon = (new MidrubBaseUserAppsCollectionStreamHelpers\Accounts)->get_network_info( 'blogger' );
        
        if ( $get_icon ) {
            
            $icon = $get_icon['icon'];
            
        }
        
        return array (
            'active_accounts' => $active_accounts,
            'expired_accounts' => '',
            'network' => 'blogger',
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
            'displayed_name' => $this->CI->lang->line('blog_comments'),
            'icon' => '<i class="fa fa-bold"></i>',
            'description' => $this->CI->lang->line('blog_comments_description'),
            'parent' => 'blogger'
        );
        
    }
    
}

/* End of file Blog_comments.php */
