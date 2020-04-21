<?php
/**
 * RSS Reader Template
 *
 * This file contains the Stream's  template 
 *
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.5
 */

// Define the page namespace
namespace MidrubBase\User\Apps\Collection\Stream\Templates;

// Constants
defined('BASEPATH') OR exit('No direct script access allowed');

// Define the namespaces to use
use MidrubBase\User\Apps\Collection\Stream\Interfaces as MidrubBaseUserAppsCollectionStreamInterfaces;
use MidrubBase\User\Apps\Collection\Stream\Helpers as MidrubBaseUserAppsCollectionStreamHelpers;

/*
 * Rss_reader contains the Stream's RSS Reader template 
 * 
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.5
 */
class Rss_reader implements MidrubBaseUserAppsCollectionStreamInterfaces\Stream {
    
    /**
     * Class variables
     *
     * @since 0.0.7.5
     */
    protected $CI;

    /**
     * Initialise the Class
     *
     * @since 0.0.7.5
     */
    public function __construct() {
        
        // Get codeigniter object instance
        $this->CI =& get_instance();
        
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
        
        // Load RSS Helper
        $this->CI->load->helper('fifth_helper');
        
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
        
        $get_setup = (new MidrubBaseUserAppsCollectionStreamHelpers\Streams_setup)->streams_setup_get($stream->stream_id, 'rss_reader');
        
        $all_posts = '';
        
        $ids = array();

        if ( $get_setup ) {
            
            $url = $get_setup[0]->setup_option;
            
            $get_feed = parse_rss_feed( $url );
            
            if ( $get_feed ) {
            
                $actions = $this->CI->stream_history_model->get_item_actions($stream->stream_id);

                for ( $p = 0; $p < count($get_feed['title']); $p++ ) {
                    
                    $image = '';

                    if ( isset($get_feed['show'][$p]) ) {

                        $image .= '<p data-type="stream-item-media">'
                                    . '<img src="' . $get_feed['show'][$p] . '">'
                                . '</p>';

                    }
                    
                    $description = '';
                    
                    if ( isset($get_feed['description'][$p]) ) {

                        if ( strlen($get_feed['description'][$p]) > 5 ) {
                            
                            $description .= '<p' . $paragraph_color . ' data-type="stream-item-content">'
                                                . preg_replace('~(?:(https?)://([^\s<]+)|(www\.[^\s<]+?\.[^\s<]+))(?<![\.,:])~i', '<a href="http$2://$4" target="_blank" title="$0"' . $links_color . '>$0</a>', $get_feed['description'][$p])
                                            . '</p>';
                        
                        }

                    }                    

                    $ids[] = $get_feed['url'][$p];

                    $all_posts .= '<li class="row"' . $border_bottom_color . '>
                                    <div class="col-xl-12">
                                        <div class="stream-post full-width">
                                            <strong>
                                                <a href="' . $get_feed['url'][$p] . '" target="_blank"' . $links_color . ' data-type="stream-item-title">
                                                    ' . $get_feed['title'][$p] . '
                                                </a>
                                            </strong>
                                            ' . $description . '
                                            ' . $image . '
                                            <div class="stream-post-footer">
                                                <a href="#stream-item-share" data-toggle="modal" class="stream-item-share" data-stream="' . $stream->stream_id . '" data-type="share" data-id="' . $get_feed['url'][$p] . '"' . $icons_color . '>
                                                    <i class="icon-paper-plane"></i>
                                                </a> 
                                            </div>
                                        </div>
                                    </div>
                                </li>';

                }
            
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
            'header' => '<i class="icon-feed"></i>'
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
        
        // Load RSS Helper
        $this->CI->load->helper('fifth_helper');
        
        // Check if data was submitted
        if ($this->CI->input->post()) {
            
            // Add form validation
            $this->CI->form_validation->set_rules('stream-template', 'Template Name', 'trim|required');
            $this->CI->form_validation->set_rules('stream-rss-url', 'RSS URL', 'trim|required');
            $this->CI->form_validation->set_rules('stream-id', 'Stream ID', 'trim|required');
            
            // Get data
            $template_name = $this->CI->input->post('stream-template');
            $rss_url = $this->CI->input->post('stream-rss-url');
            $stream_id = $this->CI->input->post('stream-id');
            
            
            if ( $this->CI->form_validation->run() !== false ) {
                
                $get_url = parse_rss_feed( $rss_url );

                if ( !$get_url ) {

                    $data = array(
                        'success' => FALSE,
                        'message' => $this->CI->lang->line('rss_feed_not_supported')
                    );

                    echo json_encode($data);
                    exit();                        

                }
                
                // Verify if stream's id is of the current user 
                if ( $this->CI->stream_tabs_model->verify_stream_owner( $this->CI->user_id, $stream_id ) ) {
                    
                    // Get the list with stream's setups
                    $get_setup = (new MidrubBaseUserAppsCollectionStreamHelpers\Streams_setup)->streams_setup_get($stream_id, 'rss_reader');
                    
                    if ( $get_setup ) {
                    
                        // Save the stream's setup
                        $stream_setup = $this->CI->stream_setup_model->stream_update_setup($get_setup[0]->setup_id, $template_name, $rss_url);
                        
                    } else {
                        
                        // Save the stream's setup
                        $stream_setup = (new MidrubBaseUserAppsCollectionStreamHelpers\Streams_setup)->streams_setup_save($stream_id, $template_name, $rss_url);
                        
                    }
                    
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
     * @since 0.0.7.5
     * 
     * @return void
     */ 
    public function stream_get_setup( $stream ) {
        
        $get_setup = (new MidrubBaseUserAppsCollectionStreamHelpers\Streams_setup)->streams_setup_get($stream[0]->stream_id, 'rss_reader');
        
        $setup_data = '';
        
        if ( $get_setup ) {
            
            
            
            $setup_data = '<div class="row">'
                            . '<div class="col-xl-12">'
                                . '<div class="row clean connected-account">'
                                    . '<div class="col-xl-8 col-8">'
                                        . '<p>'
                                            . '<i class="icon-user-following"></i> ' . $this->CI->lang->line('connected_account')
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
                                        . '<i class="fas fa-rss"></i>'
                                    . '</div>'
                                    . '<input type="text" name="stream-rss-url" class="form-control input-group stream-setup-hastags-list-enter-hashtag" placeholder="' . $this->CI->lang->line('enter_your_rss_url') . '">'
                                . '</div>'
                            . '</div>'
                            . '<div class="col-xl-3 col-sm-3 col-5 text-right">'
                                . '<button type="submit" class="btn btn-success">'
                                    . '<i class="far fa-save"></i> ' . $this->CI->lang->line('save')
                                . '</button>'
                            . '</div>'
                        . '</div>';
        
        $setup_data .= '<input type="hidden" name="stream-template" value="rss_reader">'
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
        
        return array (
            'instructions' => $this->CI->lang->line('enter_stream_url'),
            'network' => 'miscellaneous',
            'save' => '<i class="far fa-save"></i>' . $this->CI->lang->line('save'),
            'placeholder' => $this->CI->lang->line('enter_your_rss_url'),
            'icon' => '<i class="fas fa-rss"></i>',
            'type' => 2
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
        
        // Load RSS Helper
        $this->CI->load->helper('fifth_helper');
        
        switch ( $type ) {
            
            case 'check_stream_connection':
                
                if ( filter_var($data['input_url'], FILTER_VALIDATE_URL) !== FALSE ) {
                    
                    $get_url = parse_rss_feed( $data['input_url'] );
                    
                    if ( $get_url ) {
                        
                        $template_info = $this->template_info();

                        // Verify if template was saved
                        $stream = $this->CI->stream_tabs_model->save_tab_stream($data['tab_id'], $data['template_name'], $template_info['parent'], '');

                        if ($stream) {
                            
                            // Save the stream's setup
                            $stream_setup = (new MidrubBaseUserAppsCollectionStreamHelpers\Streams_setup)->streams_setup_save($stream->stream_id, $data['template_name'], $data['input_url']);
                            
                            // Get template's content
                            $this->template_content($stream);
                            exit();
                            
                        } else {
                            
                             $data = array(
                                'success' => FALSE,
                                'message' => $this->CI->lang->line('selected_stream_not_connected')
                            );

                            echo json_encode($data);
                            exit();
                            
                        }
                        
                    } else {
                        
                        $data = array(
                            'success' => FALSE,
                            'message' => $this->CI->lang->line('rss_feed_not_supported')
                        );

                        echo json_encode($data);
                        exit();                        
                        
                    }
                    
                }
                
                break;
            
        }
        
        $data = array(
            'success' => FALSE,
            'message' => $this->CI->lang->line('error_occurred')
        );

        echo json_encode($data); 
        
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
            'displayed_name' => $this->CI->lang->line('rss_reader'),
            'icon' => '<i class="icon-feed"></i>',
            'description' => $this->CI->lang->line('read_your_favorited_websites'),
            'parent' => 'miscellaneous'
        );
        
    }
    
}

/* End of file Rss_reader.php */
