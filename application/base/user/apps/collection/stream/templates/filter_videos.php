<?php
/**
 * Filter Videos Template
 *
 * This file contains the Stream's Filter Videos template 
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
 * Filter_videos contains the Stream's Filter Videos template 
 * 
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.6
 */
class Filter_videos implements MidrubBaseUserAppsCollectionStreamInterfaces\Stream {
    
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
                        
                        case 'category':
                            
                            $get_setup = (new MidrubBaseUserAppsCollectionStreamHelpers\Streams_setup)->streams_setup_get($stream_id, 'category');
                                    
                            if ( $get_setup ) {

                                // Save the stream's setup
                                $stream_setup = $this->CI->stream_setup_model->stream_update_setup($get_setup[0]->setup_id, 'category', $item_id);

                            } else {

                                // Save the stream's setup
                                $stream_setup = (new MidrubBaseUserAppsCollectionStreamHelpers\Streams_setup)->streams_setup_save($stream_id, 'category', $item_id);

                            }
                            
                            if ( $stream_setup ) {
                                
                                $data = array(
                                    'success' => TRUE,
                                    'message' => $this->CI->lang->line('your_option_was_saved'),
                                    'tab_id' => $stream[0]->tab_id
                                );

                                echo json_encode($data);                                 
                                
                            } else {
                                
                                $data = array(
                                    'success' => FALSE,
                                    'message' => $this->CI->lang->line('your_option_was_not_saved')
                                );

                                echo json_encode($data);                                  
                                
                            }
                            
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
        
        $token = '';
        
        if ( $network_details[0]->secret ) {
            
            $response = json_decode(post('https://www.googleapis.com/oauth2/v4/token', array('client_id' => get_option('youtube_client_id'), 'client_secret' => get_option('youtube_client_secret'), 'refresh_token' => $network_details[0]->secret, 'grant_type' => 'refresh_token')), true);
            
            if ( $response ) {
            
                $token = $response['access_token'];

            }
            
        }
        
        $get_setup = (new MidrubBaseUserAppsCollectionStreamHelpers\Streams_setup)->streams_setup_get($stream->stream_id, 'filter_videos');
        
        $words = 'news';
        
        if ( $get_setup ) {
            
            $words = array();
                    
            foreach ( $get_setup as $set ) {
                
                $words[] = $set->setup_option;
                
            }
            
            $words = urlencode(implode('+', $words));
            
        }
        $videos = json_decode(get('https://www.googleapis.com/youtube/v3/search?part=snippet&maxResults=10&publishedAfter=' . date('Y-m-d') . 'T00%3A00%3A00Z&q=' . $words . '&maxResults=10&access_token=' . $token), true);

        $all_videos = '';
        
        $ids = array();
  
        if ( $videos['items'] ) {
            
            $actions = $this->CI->stream_history_model->get_item_actions($stream->stream_id);
            
            $all_actions = array();
            
            if ( $actions ) {
                
                foreach ( $actions as $action ) {
                    
                    $all_actions[$action->type . '-' . $action->id] = $action->value;
                    
                }
                
            }

            foreach ( $videos['items'] as $sub ) {
                
                $ids[] = $sub['id']['videoId'];

                $all_videos .= '<li class="row"' . $border_bottom_color . '>
                                    <div class="col-xl-12">
                                        <div class="stream-post full-width">
                                            <strong>
                                                <a href="https://www.youtube.com/watch?v=' . $sub['id']['videoId'] . '" target="_blank"' . $links_color . '>
                                                    ' . $sub['snippet']['title'] . '
                                                </a>
                                            </strong>
                                            <p>
                                            <iframe style="width: 100%;" height="315" src="https://www.youtube.com/embed/' . $sub['id']['videoId'] . '" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                            </p>
                                            <p' . $paragraph_color . ' data-type="stream-item-content">
                                                ' . preg_replace('@(https?://([-\w\.]+[-\w])+(:\d+)?(/([\w/_\.#-~]*(\?\S+)?)?)?)@', '<a href="$1"' . $links_color . '>$1</a>', $sub['snippet']['description']) . '
                                            </p>
                                        </div>
                                    </div>
                            </li>';
                
            }
            
        } else {
            
            $all_videos .= '<li class="row"' . $border_bottom_color . '>'
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
                            . $all_videos
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
            $this->CI->form_validation->set_rules('stream-word', 'Word', 'trim|required');
            $this->CI->form_validation->set_rules('stream-id', 'Stream ID', 'trim|required');
            
            // Get data
            $template_name = $this->CI->input->post('stream-template');
            $word = $this->CI->input->post('stream-word');
            $stream_id = $this->CI->input->post('stream-id');
            
            
            if ( $this->CI->form_validation->run() !== false ) {
                
                // Verify if stream's id is of the current user 
                if ( $this->CI->stream_tabs_model->verify_stream_owner( $this->CI->user_id, $stream_id ) ) {
                    
                    // Get the list with stream's setups
                    $get_setup = (new MidrubBaseUserAppsCollectionStreamHelpers\Streams_setup)->streams_setup_get($stream_id, 'filter_videos');
                    
                    // Verify if user has added maximum number of allowed words
                    if ( @count($get_setup) > 4 ) {
                        
                        $data = array(
                            'success' => FALSE,
                            'message' => $this->CI->lang->line('reached_maximum_number_words')
                        );

                        echo json_encode($data);
                        exit();
                        
                    }
                    
                    // Save the stream's setup
                    $stream_setup = (new MidrubBaseUserAppsCollectionStreamHelpers\Streams_setup)->streams_setup_save($stream_id, $template_name, $word);
                    
                    // Verify if the word was saved
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
        
        $get_setup = (new MidrubBaseUserAppsCollectionStreamHelpers\Streams_setup)->streams_setup_get($stream[0]->stream_id, 'filter_videos');
        
        $setup_data = '';
        $words = '';
            
        $setup_data .= form_open('user/app/stream', array('class' => 'stream-update-stream-setup', 'data-csrf' => $this->CI->security->get_csrf_token_name()))
                        . '<div class="row stream-setup-hastags-list">'
                            . '<div class="col-xl-9 col-sm-9 col-7">'
                                . '<div class="input-group stream-setup-hastags-list-save">'
                                    . '<div class="input-group-prepend">'
                                        . '<i class="fab fa-slack-hash"></i>'
                                    . '</div>'
                                    . '<input type="text" name="stream-word" class="form-control input-group stream-setup-hastags-list-enter-hashtag" placeholder="' . $this->CI->lang->line('enter_a_word') . '">'
                                . '</div>'
                            . '</div>'
                            . '<div class="col-xl-3 col-sm-3 col-5 text-right">'
                                . '<button type="submit" class="btn btn-success">'
                                    . '<i class="far fa-save"></i> ' . $this->CI->lang->line('save')
                                . '</button>'
                            . '</div>'
                        . '</div>';

        if ( !$get_setup ) {
            
            $words = '<li class="no-records-found">'
                            . $this->CI->lang->line('no_words_found')
                        . '</li>';  
        
        } else {
        
            foreach ( $get_setup as $setup ) {

                $words .= '<li>'
                                . '<a href="#" class="delete-stream-setup" data-id="' . $setup->setup_id . '" data-template="filter_videos" data-stream-id="' . $stream[0]->stream_id . '">'
                                    . '<i class="fab fa-slack-hash"></i>'
                                    . $setup->setup_option
                                    . '<i class="icon-trash"></i>'
                                . '</a>'
                            . '</li>';

            }
        
        }
        
        $setup_data .= '<div class="row">'
                            . '<div class="col-xl-12">'
                                . '<ul class="input-group stream-setup-hastags-full-list">'
                                    . $words
                                . '</ul>'
                            . '</div>'
                        . '</div>'
                        . '<input type="hidden" name="stream-template" value="filter_videos">'
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
        
        // Check if data was submitted
        if ($this->CI->input->post()) {
            
            // Add form validation
            $this->CI->form_validation->set_rules('template_name', 'Template Name', 'trim|required');
            $this->CI->form_validation->set_rules('setup_id', 'Setup ID', 'trim|required');
            $this->CI->form_validation->set_rules('stream_id', 'Stream ID', 'trim|required');
            
            // Get data
            $template_name = $this->CI->input->post('template_name');
            $setup_id = $this->CI->input->post('setup_id');
            $stream_id = $this->CI->input->post('stream_id');
            
            
            if ( $this->CI->form_validation->run() !== false ) {
                
                // Verify if stream's id is of the current user 
                if ( $this->CI->stream_tabs_model->verify_stream_owner( $this->CI->user_id, $stream_id ) ) {
                    
                    // Delete the stream's setup
                    $stream_delete = (new MidrubBaseUserAppsCollectionStreamHelpers\Streams_setup)->streams_setup_delete($setup_id, $stream_id, $template_name);
                    
                    // Verify if the word was saved
                    if ( $stream_delete ) {
                        
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
            'displayed_name' => $this->CI->lang->line('filter_videos'),
            'icon' => '<i class="icon-social-youtube"></i>',
            'description' => $this->CI->lang->line('filter_videos_description'),
            'parent' => 'youtube'
        );
        
    }
    
}

/* End of file Filter_videos.php */
