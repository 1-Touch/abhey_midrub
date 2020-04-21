<?php
/**
 * Followers Template
 *
 * This file contains the Stream's Followers template 
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
use Abraham\TwitterOAuth\TwitterOAuth;

/*
 * Followers contains the Stream's Followers template 
 * 
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.5
 */
class Followers implements MidrubBaseUserAppsCollectionStreamInterfaces\Stream {
    
    /**
     * Class variables
     *
     * @since 0.0.7.5
     */
    protected $CI, $twitter_key, $twitter_secret;

    /**
     * Initialise the Class
     *
     * @since 0.0.7.5
     */
    public function __construct() {
        
        // Get codeigniter object instance
        $this->CI =& get_instance();
        
        // Get the Twitter app_id
        $this->twitter_key = get_option('twitter_app_id');
        
        // Get the Twitter app_secret
        $this->twitter_secret = get_option('twitter_app_secret');
        
        // Require the vendor autoload
        include_once FCPATH . 'vendor/autoload.php';
        
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
                
                $network_details = $this->CI->stream_networks_model->get_network_data('twitter', $this->CI->user_id, $stream[0]->network_id);

                $connection = new TwitterOAuth($this->twitter_key, $this->twitter_secret, $network_details[0]->token, $network_details[0]->secret);

                if ( $network_details ) {
                    
                    switch( $item_type ) {
                        
                        case 'unfollow':
                            
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
                                
                                $response = $connection->post('friendships/destroy', array('user_id' => $item_id, 'follow' => 'false'));

                                if ( @$response->id ) {
                                    
                                    $this->CI->stream_history_model->delete_stream_history($action[0]->history_id);

                                    $data = array(
                                        'success' => TRUE,
                                        'message' => $this->CI->lang->line('you_are_not_following'),
                                        'tab_id' => $stream[0]->tab_id
                                    );

                                    echo json_encode($data);  
                                    exit();

                                }
                                
                            } else {
                                
                                $response = $connection->post('friendships/destroy', array('user_id' => $item_id, 'follow' => 'false'));

                                if ( @$response->id ) {

                                    $data = array(
                                        'success' => TRUE,
                                        'message' => $this->CI->lang->line('you_are_not_following'),
                                        'tab_id' => $stream[0]->tab_id
                                    );

                                    echo json_encode($data);  
                                    exit();

                                }
                                
                            }

                            break;
                            
                        case 'follow':
                            
                            $action = $this->CI->stream_history_model->get_item_actions($stream_id, $item_id, 1);
 
                            if ( !$action ) {
                                
                                $response = $connection->post('friendships/create', array('user_id' => $item_id, 'follow' => 'true'));

                                if ( @$response->id ) {
                                
                                    $this->CI->stream_history_model->save_stream_item_action($stream_id, $item_id, 1, $response->id);

                                    $data = array(
                                        'success' => TRUE,
                                        'message' => $this->CI->lang->line('you_are_following_now'),
                                        'tab_id' => $stream[0]->tab_id
                                    );

                                    echo json_encode($data);  
                                    exit();

                                } else {

                                    $data = array(
                                        'success' => FALSE,
                                        'message' => $this->CI->lang->line('you_are_not_following')
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
        
        $network_details = $this->CI->stream_networks_model->get_network_data('twitter', $this->CI->user_id, $stream->network_id);
        
        $connection = new TwitterOAuth($this->twitter_key, $this->twitter_secret, $network_details[0]->token, $network_details[0]->secret);
        
        $get_setup = (new MidrubBaseUserAppsCollectionStreamHelpers\Streams_setup)->streams_setup_get($stream->stream_id, 'followers');
        
        $followers = $connection->get('followers/list',
            array(
                'user_id' => $network_details[0]->net_id,
                'include_user_entities' => true,
                'count' => 10
            )
        );

        $all_followers = '';
        
        $ids = array();
        
        if ( $followers ) {
            
            $actions = $this->CI->stream_history_model->get_item_actions($stream->stream_id);
            
            $all_actions = array();
            
            if ( $actions ) {
                
                foreach ( $actions as $action ) {
                    
                    $all_actions[$action->type . '-' . $action->id] = $action->value;
                    
                }
                
            }
            
            foreach ( $followers->users as $follower ) {
                
                $ids[] = $follower->id;
                
                $button = '<button type="button" class="btn btn-dark btn-subscribe mt-1 mb-2 stream-item-action" data-stream="' . $stream->stream_id . '" data-type="follow" data-id="' . $follower->id . '">'
                                . '<i class="icon-user-following"></i> ' . $this->CI->lang->line('follow')
                            . '</button>';
                
                if ( $follower->following ) {
                    
                    $button = '<button type="button" class="btn btn-dark btn-subscribe mt-1 mb-2 stream-item-action" data-stream="' . $stream->stream_id . '" data-type="unfollow" data-id="' . $follower->id . '">'
                                    . '<i class="icon-user-unfollow"></i> ' . $this->CI->lang->line('unfollow')
                                . '</button>';
                
                }

                $all_followers .= '<li class="row"' . $border_bottom_color . '>
                                <div class="col-xl-12">
                                    <img src="' . $follower->profile_image_url_https . '" alt="User Avatar" class="img-circle">
                                    <div class="stream-post">
                                        <strong>
                                            <a href="https://twitter.com/' . $follower->screen_name . '" target="_blank"' . $links_color . '>
                                                ' . $follower->screen_name . '
                                            </a>
                                        </strong>
                                        <p>
                                            ' . $button . '
                                        </p>
                                        <div class="stream-post-footer">  
                                            <span' . $icons_color . '>
                                                <i class="fas fa-users"></i> ' . $follower->followers_count . '
                                            </span>
                                            <span' . $icons_color . '>
                                                <i class="fas fa-user-friends"></i> ' . $follower->friends_count . '    
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </li>';
                
            }
            
        } else {
            
            $all_followers .= '<li class="row"' . $border_bottom_color . '>'
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
            'header' => '<i class="icon-social-twitter"></i>'
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
                            . $all_followers
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
        
        $network_details = $this->CI->stream_networks_model->get_network_data('twitter', $this->CI->user_id, $stream[0]->network_id);
        
        $setup_data = '';
        
        if ( $network_details ) {
            
            $icon = '';

            $get_icon = (new MidrubBaseUserAppsCollectionStreamHelpers\Accounts)->get_network_info( 'twitter' );

            if ( $get_icon ) {

                $icon = $get_icon['icon'];

            }
            
            $setup_data = '<div class="row">'
                            . '<div class="col-xl-12">'
                                . '<div class="row clean connected-account">'
                                    . '<div class="col-xl-8 col-8">'
                                        . '<p>'
                                            . '<i class="icon-user-following"></i> ' . $this->CI->lang->line('connected_account')
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
        $active_accounts = (new MidrubBaseUserAppsCollectionStreamHelpers\Accounts)->get_active_accounts($this->CI->stream_networks_model->get_accounts_by_network( $this->CI->user_id, 'twitter', 1 ), 1);

        // Get Expired Accounts
        $expired_accounts = (new MidrubBaseUserAppsCollectionStreamHelpers\Accounts)->get_active_accounts($this->CI->stream_networks_model->get_accounts_by_network( $this->CI->user_id, 'twitter', 0 ), 0);        
        
        $icon = '';
        
        $get_icon = (new MidrubBaseUserAppsCollectionStreamHelpers\Accounts)->get_network_info( 'twitter' );
        
        if ( $get_icon ) {
            
            $icon = $get_icon['icon'];
            
        }
        
        return array (
            'active_accounts' => $active_accounts,
            'expired_accounts' => $expired_accounts,
            'network' => 'twitter',
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
            'displayed_name' => $this->CI->lang->line('followers'),
            'icon' => '<i class="fab fa-twitter"></i>',
            'description' => $this->CI->lang->line('followers_description'),
            'parent' => 'twitter'
        );
        
    }
    
}

/* End of file Followers.php */
