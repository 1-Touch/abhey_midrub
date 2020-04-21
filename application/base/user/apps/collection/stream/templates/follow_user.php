<?php
/**
 * Follow User
 *
 * This file contains the Stream's Follow User template 
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
 * Class Follow_user contains the Stream's Follow User template 
 * 
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.6
 */
class Follow_user implements MidrubBaseUserAppsCollectionStreamInterfaces\Stream {
    
    /**
     * Class variables
     *
     * @since 0.0.7.6
     */
    protected $CI, $fb, $app_id, $app_secret, $fb_url = 'https://graph.facebook.com/v3.2/';

    /**
     * Initialise the Class
     *
     * @since 0.0.7.6
     */
    public function __construct() {
        
        // Get codeigniter object instance
        $this->CI =& get_instance();
        
        // Get the Facebook App ID
        $this->app_id = get_option('instagram_insights_app_id');
        
        // Get the Facebook App Secret
        $this->app_secret = get_option('instagram_insights_app_secret');
        
        // Load the Vendor dependencies
        require_once FCPATH . 'vendor/autoload.php';
        
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
        
        // Load the Facebook Class
        $this->fb = new \Facebook\Facebook(
            array(
                'app_id' => $this->app_id,
                'app_secret' => $this->app_secret,
                'default_graph_version' => 'v3.0',
                'default_access_token' => '{access-token}',
            )
        );
        
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
        
        $network_details = $this->CI->stream_networks_model->get_network_data('instagram_insights', $this->CI->user_id, $stream->network_id);
        
        $get_setup = (new MidrubBaseUserAppsCollectionStreamHelpers\Streams_setup)->streams_setup_get($stream->stream_id, 'follow_user');
        
        $all_medias = '';
        
        $ids = array();

        $content = '';
        
        if ( $get_setup ) {
            
            $username = $get_setup[0]->setup_option;
        
            $response = $this->fb->get(
                '/' . $network_details[0]->net_id . '/?fields=business_discovery.username(' . $username . '){followers_count,media_count,media{caption,media_url,media_type,comments_count,like_count,permalink}}',
                $network_details[0]->token
            );

            $graphNode = $response->getGraphNode();

            if ( $graphNode->asArray() ) {

                $actions = $this->CI->stream_history_model->get_item_actions($stream->stream_id);

                $all_actions = array();

                if ( $actions ) {

                    foreach ( $actions as $action ) {

                        $all_actions[$action->type . '-' . $action->id] = $action->value;

                    }

                }

                $medias = $graphNode->asArray();

                foreach ( $medias['business_discovery']['media'] as $media ) {

                    if ( $media['media_type'] !== 'IMAGE' ) {
                        if ( isset($media['media_url']) ) {
                            $content = '<p><video controls><source src="' . $media['media_url'] . '" type="video/mp4"></video></p>';
                        }
                    } else {
                        $content = '<p data-type="stream-item-media"><img src="' . $media['media_url'] . '"></p>';
                    }

                    $ids[] = $media['id'];

                    $caption = '';

                    if (isset($media['caption'])) {

                        $caption = '<p' . $paragraph_color . ' data-type="stream-item-content">'
                                . preg_replace('/(?<!\S)#([0-9a-zA-Z]+)/', '<a href="https://www.instagram.com/explore/tags/$1/" target="_blank"' . $links_color . '>#$1</a>', preg_replace('@(https?://([-\w\.]+[-\w])+(:\d+)?(/([\w/_\.#-~]*(\?\S+)?)?)?)@', '<a href="$1" target="_blank">$1</a>', $media['caption']))
                                . '</p>';
                    }

                    $all_medias .= '<li class="row"' . $border_bottom_color . '>
                                    <div class="col-xl-12">
                                        <div class="stream-post full-width">
                                            ' . $content . '
                                            ' . $caption . '
                                            <p>
                                                <a href="' . $media['permalink'] . '" target="_blank"' . $links_color . '>
                                                    ' . $media['permalink'] . '
                                                </a>
                                            </p>
                                            <div class="stream-post-footer">
                                                <span' . $icons_color . '>
                                                    <i class="far fa-comments"></i> ' . $media['comments_count'] . '
                                                </span>
                                                <span' . $icons_color . '>
                                                    <i class="icon-heart"></i> ' . $media['like_count'] . '    
                                                </span>
                                                <a href="#stream-item-share" data-toggle="modal" class="stream-item-share" data-stream="' . $stream->stream_id . '" data-type="share" data-id="' . $media['id'] . '"' . $icons_color . '>
                                                    <i class="icon-paper-plane"></i>
                                                </a> 
                                            </div>
                                        </div>
                                    </div>
                                </li>';

                }

            } else {

                $all_medias .= '<li class="row"' . $border_bottom_color . '>'
                                . '<div class="col-xl-12">'
                                    . '<div>'
                                        . '<p>' . $this->CI->lang->line('no_results_found') . '</p>'
                                    . '</div>'
                                . '</div>'
                            . '</li>';

            }
            
        } else {

            $all_medias .= '<li class="row"' . $border_bottom_color . '>'
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
            'header' => '<i class="icon-social-instagram"></i>'
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
                            . $all_medias
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
        
        // Check if data was submitted
        if ($this->CI->input->post()) {
            
            // Add form validation
            $this->CI->form_validation->set_rules('stream-template', 'Template Name', 'trim|required');
            $this->CI->form_validation->set_rules('username', 'Username', 'trim|required');
            $this->CI->form_validation->set_rules('stream-id', 'Stream ID', 'trim|required');
            
            // Get data
            $template_name = $this->CI->input->post('stream-template');
            $username = $this->CI->input->post('username');
            $stream_id = $this->CI->input->post('stream-id');
            
            if ( $this->CI->form_validation->run() !== false ) {
                
                $stream = $this->CI->stream_tabs_model->verify_stream_owner( $this->CI->user_id, $stream_id );
                
                $network_details = $this->CI->stream_networks_model->get_network_data('instagram_insights', $this->CI->user_id, $stream[0]->network_id);
                
                $response = json_decode(get($this->fb_url . $network_details[0]->net_id . '/?fields=business_discovery.username(' . $username . ')&access_token=' . $network_details[0]->token), true);
                
                if ( !isset($response['id']) ) {

                    $data = array(
                        'success' => FALSE,
                        'message' => $this->CI->lang->line('valid_instagram_business')
                    );

                    echo json_encode($data);
                    exit();                        

                }
                
                // Verify if stream's id is of the current user 
                if ( $this->CI->stream_tabs_model->verify_stream_owner( $this->CI->user_id, $stream_id ) ) {
                    
                    // Get the list with stream's setups
                    $get_setup = (new MidrubBaseUserAppsCollectionStreamHelpers\Streams_setup)->streams_setup_get($stream_id, 'username');
                    
                    if ( $get_setup ) {
                    
                        // Save the stream's setup
                        $stream_setup = $this->CI->stream_setup_model->stream_update_setup($get_setup[0]->setup_id, $template_name, $username);
                        
                    } else {
                        
                        // Save the stream's setup
                        $stream_setup = (new MidrubBaseUserAppsCollectionStreamHelpers\Streams_setup)->streams_setup_save($stream_id, $template_name, $username);
                        
                    }
                    
                    // Verify if the hashtag was saved
                    if ( $stream_setup ) {
                        
                        $stream = $this->CI->stream_tabs_model->verify_stream_owner( $this->CI->user_id, $stream_id );
                        
                        $this->stream_get_setup( $stream );
                        exit();
                        
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
     * The public method stream_get_setup gets the stream's setup
     * 
     * @param object $stream contains the stream's configuration
     * 
     * @since 0.0.7.6
     * 
     * @return void
     */ 
    public function stream_get_setup( $stream ) {
        
        $get_setup = (new MidrubBaseUserAppsCollectionStreamHelpers\Streams_setup)->streams_setup_get($stream[0]->stream_id, 'follow_user');
        
        $setup_data = '';
        
        if ( $get_setup ) {
            
            $setup_data .= '<div class="row">'
                            . '<div class="col-xl-12">'
                                . '<div class="row clean connected-account">'
                                    . '<div class="col-xl-8 col-8">'
                                        . '<p>'
                                            . '<i class="icon-user-following"></i> ' . $this->CI->lang->line('followed_business')
                                        . '</p>'
                                    . '</div>'
                                    . '<div class="col-xl-4 col-4 text-right">'
                                        . $get_setup[0]->setup_option
                                    . '</div>'
                                . '</div>'                    
                            . '</div>'    
                        . '</div>';
            
        }
        
        $setup_data .= form_open('user/app/stream', array('class' => 'stream-update-stream-setup', 'data-csrf' => $this->CI->security->get_csrf_token_name()))
                        . '<div class="row stream-setup-hastags-list">'
                            . '<div class="col-xl-9 col-sm-9 col-7">'
                                . '<div class="input-group stream-setup-hastags-list-save">'
                                    . '<div class="input-group-prepend">'
                                        . '<i class="icon-user-follow"></i>'
                                    . '</div>'
                                    . '<input type="text" name="username" class="form-control input-group stream-setup-hastags-list-enter-hashtag" placeholder="' . $this->CI->lang->line('enter_a_user') . '">'
                                . '</div>'
                            . '</div>'
                            . '<div class="col-xl-3 col-sm-3 col-5 text-right">'
                                . '<button type="submit" class="btn btn-success">'
                                    . '<i class="far fa-save"></i> ' . $this->CI->lang->line('save')
                                . '</button>'
                            . '</div>'
                        . '</div>';
        
        $setup_data .= '<input type="hidden" name="stream-template" value="follow_user">'
                        . '<input type="hidden" name="stream-id" value="' . $stream[0]->stream_id . '">'
                    . form_close();
        
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
        $this->CI->lang->load( 'stream_instructions', $this->CI->config->item('language'), FALSE, TRUE, MIDRUB_BASE_USER_APPS_STREAM );
        
        // Get Active Accounts
        $active_accounts = (new MidrubBaseUserAppsCollectionStreamHelpers\Accounts)->get_active_accounts($this->CI->stream_networks_model->get_accounts_by_network( $this->CI->user_id, 'instagram_insights', 1 ), 1);

        // Get Expired Accounts
        $expired_accounts = (new MidrubBaseUserAppsCollectionStreamHelpers\Accounts)->get_active_accounts($this->CI->stream_networks_model->get_accounts_by_network( $this->CI->user_id, 'instagram_insights', 0 ), 0);        
        
        $icon = '';
        
        $get_icon = (new MidrubBaseUserAppsCollectionStreamHelpers\Accounts)->get_network_info( 'instagram_insights' );
        
        if ( $get_icon ) {
            
            $icon = $get_icon['icon'];
            
        }
        
        return array (
            'active_accounts' => $active_accounts,
            'expired_accounts' => $expired_accounts,
            'network' => 'instagram_insights',
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
            'displayed_name' => $this->CI->lang->line('follow_user'),
            'icon' => '<i class="icon-social-instagram"></i>',
            'description' => $this->CI->lang->line('follow_user_description'),
            'parent' => 'instagram_insights'
        );
        
    }
    
}

/* End of file Follow_user.php */
