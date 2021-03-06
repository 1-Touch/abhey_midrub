<?php
/**
 * Posts by Tag
 *
 * This file contains the Stream's Posts by Tag template 
 *
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.7
 */

// Define the page namespace
namespace MidrubBase\User\Apps\Collection\Stream\Templates;

// Constants
defined('BASEPATH') OR exit('No direct script access allowed');

// Define the namespaces to use
use MidrubBase\User\Apps\Collection\Stream\Interfaces as MidrubBaseUserAppsCollectionStreamInterfaces;
use MidrubBase\User\Apps\Collection\Stream\Helpers as MidrubBaseUserAppsCollectionStreamHelpers;

/*
 * Class Posts_by_tag contains the Stream's Posts by Tag template 
 * 
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.7
 */
class Posts_by_tag implements MidrubBaseUserAppsCollectionStreamInterfaces\Stream {
    
    /**
     * Class variables
     */
    protected $CI, $client, $request, $consumerKey, $consumerSecret;

    /**
     * Initialise the Class
     *
     * @since 0.0.7.7
     */
    public function __construct() {
        
        // Get codeigniter object instance
        $this->CI =& get_instance();
        
        // Get Tumblr's consumer_key
        $this->consumerKey = get_option('tumblr_consumer_key');
        
        // Get Tumblr's consumer_secret
        $this->consumerSecret = get_option('tumblr_consumer_secret');
        
        // Load the Vendor dependencies
        require_once FCPATH . 'vendor/autoload.php';
        
    }
    
    /**
     * The public method stream_process_action processes the stream's item action
     * 
     * @param object $stream contains the stream's configuration
     * 
     * @since 0.0.7.7
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
     * @since 0.0.7.7
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
     * @since 0.0.7.7
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
        
        $network_details = $this->CI->stream_networks_model->get_network_data('tumblr', $this->CI->user_id, $stream->network_id);
        
        // Call the Tumblr class
        $client = new \Tumblr\API\Client($this->consumerKey, $this->consumerSecret, $network_details[0]->token, $network_details[0]->secret);
        
        $get_setup = (new MidrubBaseUserAppsCollectionStreamHelpers\Streams_setup)->streams_setup_get($stream->stream_id, 'posts_by_tag');
        
        if ( $get_setup ) {
            
            // Get popular posts
            $popular_posts = $client->getTaggedPosts($get_setup[0]->setup_option, array('limit' => 10));
            
        } else {
            
            // Get popular posts
            $popular_posts = $client->getTaggedPosts('social', array('limit' => 10));
            
        }
        
        $all_posts = '';
        
        if ( $popular_posts ) {
            
            $actions = $this->CI->stream_history_model->get_item_actions($stream->stream_id);
            
            $all_actions = array();
            
            if ( $actions ) {
                
                foreach ( $actions as $action ) {
                    
                    $all_actions[$action->type . '-' . $action->id] = $action->value;
                    
                }
                
            }
            
            $ids = array();
            
            foreach ( $popular_posts as $post ) {
                
                $ids[] = $post->id;
                
                $media = '';
                
                if ( $post->type === 'photo' ) {
                    
                    if ( @$post->photos[0]->alt_sizes[1]->url ) {
                    
                        $media = '<p data-type="stream-item-media"><a href="' . $post->post_url . '" target="_blank"><img src="' . $post->photos[0]->alt_sizes[1]->url . '"></a></p>';
                    
                    }
                    
                }
                
                $caption = '';
                
                if ( isset( $post->caption ) ) {

                    $caption = '<p' . $paragraph_color . ' data-type="stream-item-content">'
                            . preg_replace('/(?<!\S)#([0-9a-zA-Z]+)/', '<a href="https://www.instagram.com/explore/tags/$1/" target="_blank"' . $links_color . '>#$1</a>', preg_replace('@(https?://([-\w\.]+[-\w])+(:\d+)?(/([\w/_\.#-~]*(\?\S+)?)?)?)@', '<a href="$1" target="_blank">$1</a>', strip_tags($post->caption)))
                            . '</p>';
                }

                $all_posts .= '<li class="row"' . $border_bottom_color . '>
                                <div class="col-xl-12">
                                    <div class="stream-post full-width">
                                        <p>
                                            <a href="' . $post->post_url . '" target="_blank"' . $links_color . '>
                                                ' . $post->summary . '
                                            </a>
                                        </p>
                                        ' . $media . '
                                        ' . $caption . '
                                        <div class="stream-post-footer">
                                            <a href="#stream-item-share" data-toggle="modal" class="stream-item-share" data-stream="' . $stream->stream_id . '" data-type="share" data-id="' . $post->id . '"' . $icons_color . '>
                                                <i class="icon-paper-plane"></i>
                                            </a> 
                                        </div>
                                    </div>
                                </div>
                            </li>';
                
            }
            
        } else {
            
            $all_posts .= '<li class="row"' . $border_bottom_color . '>'
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
            'header' => '<i class="fab fa-tumblr-square" style="color: #529ecc;"></i>'
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
                            . $all_posts
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
     * @since 0.0.7.7
     * 
     * @return array with stream's item content
     */ 
    public function template_content_single( $stream ) {
        
    }
    
    /**
     * The public method stream_update_setup saves stream's setup
     * 
     * @since 0.0.7.7
     * 
     * @return void
     */ 
    public function stream_update_setup() {
        
        // Check if data was submitted
        if ($this->CI->input->post()) {
            
            // Add form validation
            $this->CI->form_validation->set_rules('stream-template', 'Template Name', 'trim|required');
            $this->CI->form_validation->set_rules('hashtag', 'Hashtag', 'trim|required');
            $this->CI->form_validation->set_rules('stream-id', 'Stream ID', 'trim|required');
            
            // Get data
            $template_name = $this->CI->input->post('stream-template');
            $hashtag = $this->CI->input->post('hashtag');
            $stream_id = $this->CI->input->post('stream-id');
            
            
            if ( $this->CI->form_validation->run() !== false ) {
                    
                // Get the list with stream's setups
                $get_setup = (new MidrubBaseUserAppsCollectionStreamHelpers\Streams_setup)->streams_setup_get($stream_id, 'posts_by_tag');

                if ( $get_setup ) {

                    // Save the stream's setup
                    $stream_setup = $this->CI->stream_setup_model->stream_update_setup($get_setup[0]->setup_id, $template_name, $hashtag);

                } else {

                    // Save the stream's setup
                    $stream_setup = (new MidrubBaseUserAppsCollectionStreamHelpers\Streams_setup)->streams_setup_save($stream_id, $template_name, $hashtag);

                }

                // Verify if the hashtag was saved
                if ( $stream_setup ) {

                    $stream = $this->CI->stream_tabs_model->verify_stream_owner( $this->CI->user_id, $stream_id );

                    $this->stream_get_setup( $stream );
                    exit();

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
     * @since 0.0.7.7
     * 
     * @return void
     */ 
    public function stream_get_setup( $stream ) {
        
        $get_setup = (new MidrubBaseUserAppsCollectionStreamHelpers\Streams_setup)->streams_setup_get($stream[0]->stream_id, 'posts_by_tag');
        
        $setup_data = '';
        
        if ( $get_setup ) {
            
            $setup_data .= '<div class="row">'
                            . '<div class="col-xl-12">'
                                . '<div class="row clean connected-account">'
                                    . '<div class="col-xl-8 col-8">'
                                        . '<p>'
                                            . '<i class="fas fa-hashtag"></i> ' . $this->CI->lang->line('hashtag')
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
                                        . '<i class="fab fa-slack-hash"></i>'
                                    . '</div>'
                                    . '<input type="text" name="hashtag" class="form-control input-group stream-setup-hastags-list-enter-hashtag" placeholder="' . $this->CI->lang->line('enter_a_hashtag') . '">'
                                . '</div>'
                            . '</div>'
                            . '<div class="col-xl-3 col-sm-3 col-5 text-right">'
                                . '<button type="submit" class="btn btn-success">'
                                    . '<i class="far fa-save"></i> ' . $this->CI->lang->line('save')
                                . '</button>'
                            . '</div>'
                        . '</div>';
        
        $setup_data .= '<input type="hidden" name="stream-template" value="posts_by_tag">'
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
     * @since 0.0.7.7
     * 
     * @return void
     */ 
    public function stream_delete_setup() {
    }
    
    /**
     * The public method template_connect contains the template's connection settings
     * 
     * @since 0.0.7.7
     * 
     * @return array with settings
     */ 
    public function template_connect() {
        
        // Load language
        $this->CI->lang->load( 'stream_instructions', $this->CI->config->item('language'), FALSE, TRUE, MIDRUB_BASE_USER_APPS_STREAM );
        
        // Get Active Accounts
        $active_accounts = (new MidrubBaseUserAppsCollectionStreamHelpers\Accounts)->get_active_accounts($this->CI->stream_networks_model->get_accounts_by_network( $this->CI->user_id, 'tumblr', 1 ), 1);

        // Get Expired Accounts
        $expired_accounts = (new MidrubBaseUserAppsCollectionStreamHelpers\Accounts)->get_active_accounts($this->CI->stream_networks_model->get_accounts_by_network( $this->CI->user_id, 'tumblr', 0 ), 0);        
        
        $icon = '';
        
        $get_icon = (new MidrubBaseUserAppsCollectionStreamHelpers\Accounts)->get_network_info( 'tumblr' );
        
        if ( $get_icon ) {
            
            $icon = $get_icon['icon'];
            
        }
        
        return array (
            'active_accounts' => $active_accounts,
            'expired_accounts' => $expired_accounts,
            'network' => 'tumblr',
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
     * @since 0.0.7.7
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
     * @since 0.0.7.7
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
     * @since 0.0.7.7
     * 
     * @return void
     */ 
    public function stream_extra($type, $data) {
        
    }
    
    /**
     * The public method template_info contains the template's information
     * 
     * @since 0.0.7.7
     * 
     * @return array with tempate's information
     */ 
    public function template_info() {
        
        return array (
            'displayed_name' => $this->CI->lang->line('display_posts_by_tag'),
            'icon' => '<i class="fab fa-tumblr-square"></i>',
            'description' => $this->CI->lang->line('display_posts_by_tag_description'),
            'parent' => 'tumblr'
        );
        
    }
    
}

/* End of file Posts_by_tag.php */
