<?php
/**
 * Popular Videos Template
 *
 * This file contains the Stream's Popular Videos template 
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
 * Popular_videos contains the Stream's Popular Videos template 
 * 
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.6
 */
class Popular_videos implements MidrubBaseUserAppsCollectionStreamInterfaces\Stream {
    
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
        
        $get_setup = (new MidrubBaseUserAppsCollectionStreamHelpers\Streams_setup)->streams_setup_get($stream->stream_id, 'category');
        
        $category = '';
        
        if ( $get_setup ) {
            
            $category = '&videoCategoryId=' . $get_setup[0]->setup_option;
            
        }
        
        $videos = json_decode(get('https://www.googleapis.com/youtube/v3/videos?part=snippet%2CcontentDetails%2Cstatistics&chart=mostPopular' . $category . '&maxResults=10&access_token=' . $token), true);

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
                
                $ids[] = $sub['id'];

                $all_videos .= '<li class="row"' . $border_bottom_color . '>
                                    <div class="col-xl-12">
                                        <div class="stream-post full-width">
                                            <strong>
                                                <a href="https://www.youtube.com/watch?v=' . $sub['id'] . '" target="_blank"' . $links_color . '>
                                                    ' . $sub['snippet']['title'] . '
                                                </a>
                                            </strong>
                                            <p>
                                            <iframe style="width: 100%;" height="315" src="https://www.youtube.com/embed/' . $sub['id'] . '" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                            </p>
                                            <p' . $paragraph_color . ' data-type="stream-item-content">
                                                ' . preg_replace('@(https?://([-\w\.]+[-\w])+(:\d+)?(/([\w/_\.#-~]*(\?\S+)?)?)?)@', '<a href="$1"' . $links_color . '>$1</a>', $sub['snippet']['description']) . '
                                            </p>
                                            <div class="stream-post-footer">
                                                <span' . $icons_color . '>
                                                    <i class="fab fa-youtube"></i> ' . $sub['statistics']['viewCount'] . '
                                                </span>
                                                <span' . $icons_color . '>
                                                    <i class="far fa-thumbs-up"></i> ' . $sub['statistics']['likeCount'] . '    
                                                </span>
                                                <span' . $icons_color . '>
                                                    <i class="far fa-comments"></i> ' . $sub['statistics']['commentCount'] . '    
                                                </span>                                                
                                            </div>
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
        
        $token = '';
        
        if ( $network_details[0]->secret ) {
            
            $response = json_decode(post('https://www.googleapis.com/oauth2/v4/token', array('client_id' => get_option('youtube_client_id'), 'client_secret' => get_option('youtube_client_secret'), 'refresh_token' => $network_details[0]->secret, 'grant_type' => 'refresh_token')), true);
            
            if ( $response ) {
            
                $token = $response['access_token'];

            }
            
        }
        
        $get_setup = (new MidrubBaseUserAppsCollectionStreamHelpers\Streams_setup)->streams_setup_get($stream[0]->stream_id, 'category');
        
        $videos = json_decode(get('https://www.googleapis.com/youtube/v3/videoCategories?part=snippet&regionCode=US&access_token=' . $token), true);
        
        $categories = '';
        $selected = $this->CI->lang->line('all_categories');
        
        if ( $videos['items'] ) {
            
            foreach ( $videos['items'] as $video ) {
                
                if ( ( (int)$video['id'] === 18 ) || ( (int)$video['id'] === 24 ) || ( (int)$video['id'] === 29 ) || ( (int)$video['id'] === 30 ) || ( (int)$video['id'] === 31 ) || ( (int)$video['id'] === 32 ) || ( (int)$video['id'] === 36 ) || ( (int)$video['id'] === 37 ) || ( (int)$video['id'] === 38 ) || ( (int)$video['id'] === 34 ) || ( (int)$video['id'] === 39 ) || ( (int)$video['id'] === 35 ) ) {
                    continue;
                }
                
                if ( @$get_setup[0]->setup_option === $video['id'] ) {
                    $selected = $video['snippet']['title'];
                }
                
                $categories .= '<a class="dropdown-item" data-id="' . $video['id'] . '" href="#"><i class="fab fa-youtube"></i> ' . $video['snippet']['title'] . '</a>';
                
            }
            
        }
        
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
            
            
            $setup_data .= '<div class="dropdown">'
                                . '<a class="btn btn-secondary btn-md dropdown-toggle" data-type="category" data-stream="' . $stream[0]->stream_id . '" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">'
                                    . '<i class="fab fa-youtube"></i> ' . $selected . '</a>'
                                . '<div class="dropdown-menu dropdown-menu-action stream-select-setup-list" aria-labelledby="dropdownMenuLink" x-placement="bottom-start" style="position: absolute; transform: translate3d(15px, 48px, 0px); top: 0px; left: 0px; will-change: transform;">'
                                    . '<a class="dropdown-item" data-id="0" href="#"><i class="fab fa-youtube"></i> ' . $this->CI->lang->line('all_categories') . '</a>'
                                    . $categories
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
            'displayed_name' => $this->CI->lang->line('popular_videos'),
            'icon' => '<i class="icon-social-youtube"></i>',
            'description' => $this->CI->lang->line('popular_videos_description'),
            'parent' => 'youtube'
        );
        
    }
    
}

/* End of file Popular_videos.php */
