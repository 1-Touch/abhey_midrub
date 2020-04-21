<?php
/**
 * News Feed Template
 *
 * This file contains the Stream's News Feed template 
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
 * News_feed contains the Stream's News Feed template 
 * 
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.6
 */
class News_feed implements MidrubBaseUserAppsCollectionStreamInterfaces\Stream {
    
    /**
     * Class variables
     *
     * @since 0.0.7.4
     */
    protected
            $CI, $url = 'https://api.vk.com/method/', $version='5.92', $redirect_uri, $client_id, $client_secret;

    /**
     * Initialise the Class
     *
     * @since 0.0.7.5
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
        
        $network_details = $this->CI->stream_networks_model->get_network_data('vk', $this->CI->user_id, $stream->network_id);
        
        $search = 'New Year';
        
        $get_setup = (new MidrubBaseUserAppsCollectionStreamHelpers\Streams_setup)->streams_setup_get($stream->stream_id, 'news_feed');
        
        if ( $get_setup ) {
            
            $search = array();
                    
            foreach ( $get_setup as $set ) {
                
                $search[] = $set->setup_option;
                
            }
            
            $search = implode('+', $search);
            
        }
        
        // Set params for getting all posts
        $params = array(
            'q' => urlencode($search),
            'count' => '10',
            'access_token' => $network_details[0]->token,
            'v' => $this->version
        );

        // Get cURL resource
        $curl = curl_init();

        // Set some options to in a useragent
        curl_setopt_array($curl, array(CURLOPT_RETURNTRANSFER => 1, CURLOPT_URL => $this->url . 'newsfeed.search' . '?' . urldecode(http_build_query($params)), CURLOPT_HEADER => false));

        // Get response
        $posts = json_decode(curl_exec($curl), true);

        // Close request to clear up some resources
        curl_close($curl);

        $all_posts = '';
        
        $ids = array();
        
        if ( $posts ) {
            
            $actions = $this->CI->stream_history_model->get_item_actions($stream->stream_id);
            
            $all_actions = array();
            
            if ( $actions ) {
                
                foreach ( $actions as $action ) {
                    
                    $all_actions[$action->type . '-' . $action->id] = $action->value;
                    
                }
                
            }
            
            foreach ( $posts['response']['items'] as $post ) {
                $image = '';

                if ( isset($post['attachments'][0]['link']['photo']['sizes'][0]['url']) ) {
                    $image = '<p data-type="stream-item-media"><img src="' . $post['attachments'][0]['link']['photo']['sizes'][0]['url'] . '"></p>';
                }
                
                if ( isset($post['attachments'][0]['video']['photo_320']) ) {
                    $image = '<p data-type="stream-item-media"><img src="' . $post['attachments'][0]['video']['photo_320'] . '">';
                }
                
                $url = '';
                
                if ( isset($post['attachments'][0]['link']['url']) ) {
                    $url = '<p><a href="' . $post['attachments'][0]['link']['url'] . '" target="_blank">' . $post['attachments'][0]['link']['url'] . '</a></p>';
                }
                
                $ids[] = $post['id'];
                
                // Define default number of likes
                $likes = 0;

                if ( isset($post['likes']) ) {
                    $likes = $post['likes']['count'];
                }
                
                // Define default number of comments
                $comments = 0;

                if ( isset($post['comments']) ) {
                    $comments = $post['comments']['count'];
                }
                
                // Define default number of reposts
                $reposts = 0;

                if ( isset($post['comments']) ) {
                    $reposts = $post['comments']['count'];
                }
                
                // Define default number of views
                $views = 0;

                if ( isset($post['views']) ) {
                    $views = $post['views']['count'];
                }
                
                $user_avatar = '';
                
                // Set params to get the comment data
                $params = array(
                    'user_ids' => $post['from_id'],
                    'fields' => 'photo_100,city,verified',
                    'access_token' => $network_details[0]->token,
                    'v' => $this->version
                );

                // Get cURL resource
                $curl = curl_init();

                // Set some options to in a useragent
                curl_setopt_array($curl, array(CURLOPT_RETURNTRANSFER => 1, CURLOPT_URL => $this->url . 'users.get' . '?' . urldecode(http_build_query($params)), CURLOPT_HEADER => false));

                // Get response
                $response = json_decode(curl_exec($curl), true);

                // Close request to clear up some resources
                curl_close($curl);
                
                if ( $response ) {

                    if ( isset($response['response'][0]['photo_100']) ) {
                        $user_avatar = $response['response'][0]['photo_100'];
                    }
                    
                }
                
                if ( !$user_avatar ) {
                    continue;
                }

                $all_posts .= '<li class="row"' . $border_bottom_color . '>
                                <div class="col-xl-12">
                                    <img src="' . $user_avatar . '" alt="User Avatar" class="img-circle">
                                    <div class="stream-post">
                                        <strong>
                                            <a href="https://vk.com/id' . $post['from_id'] . '" target="_blank"' . $links_color . '>
                                                ' . $response['response'][0]['first_name'] . ' ' . $response['response'][0]['last_name'] . '
                                            </a> 
                                        </strong> 
                                        <small' . $icons_color . '>
                                            ' . '<i class="icon-clock"></i>' . calculate_time(strtotime(date('Y-m-d H:i:s', $post['date'])), time()) . '
                                        </small>
                                        <p' . $paragraph_color . ' data-type="stream-item-content">
                                            ' . preg_replace('@(https?://([-\w\.]+[-\w])+(:\d+)?(/([\w/_\.#-~]*(\?\S+)?)?)?)@', '<a href="$1"' . $links_color . '>$1</a>', $post['text']) . '
                                        </p>
                                        ' . $image . '
                                        ' . $url . '    
                                        <div class="stream-post-footer">  
                                            <span>
                                                <i class="far fa-comments"></i>
                                                ' . $comments . '
                                            </span>   
                                            <span>
                                                <i class="icon-share"></i>
                                                ' . $reposts . '
                                            </span> 
                                            <span>
                                                <i class="far fa-thumbs-up"></i>
                                                ' . $likes . '
                                            </span>  
                                            <span>
                                                <i class="fas fa-tv"></i>
                                                ' . $views . '
                                            </span>  
                                            <a href="#stream-item-share" data-toggle="modal" class="stream-item-share" data-stream="' . $stream->stream_id . '" data-type="share" data-id="' . $post['id'] . '"' . $icons_color . '>
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
            'header' => '<i style="color: #6383a8;" class="fab fa-vk"></i>'
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
                    $get_setup = (new MidrubBaseUserAppsCollectionStreamHelpers\Streams_setup)->streams_setup_get($stream_id, 'news_feed');
                    
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
     * @since 0.0.7.5
     * 
     * @return void
     */ 
    public function stream_get_setup( $stream ) {
        
        $network_details = $this->CI->stream_networks_model->get_network_data('vk', $this->CI->user_id, $stream[0]->network_id);
        
        $setup_data = '';
        
        if ( $network_details ) {
            
            $icon = '';

            $get_icon = (new MidrubBaseUserAppsCollectionStreamHelpers\Accounts)->get_network_info( 'vk' );

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
        
        $words = '<li class="no-records-found">'
                        . $this->CI->lang->line('no_words_found')
                    . '</li>';
                    
        $get_setup = (new MidrubBaseUserAppsCollectionStreamHelpers\Streams_setup)->streams_setup_get($stream[0]->stream_id, 'news_feed');
        
        if ( $get_setup ) {
            
            $words = '';
            
            foreach ( $get_setup as $setup ) {
                
                $words .= '<li>'
                                . '<a href="#" class="delete-stream-setup" data-id="' . $setup->setup_id . '" data-template="news_feed" data-stream-id="' . $stream[0]->stream_id . '">'
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
                        . '<input type="hidden" name="stream-template" value="news_feed">'
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
     * @since 0.0.7.5
     * 
     * @return array with settings
     */ 
    public function template_connect() {
        
        // Load language
        $this->CI->lang->load( 'stream_instructions', $this->CI->config->item('language'), FALSE, TRUE, MIDRUB_BASE_USER_APPS_STREAM );
        
        // Get Active Accounts
        $active_accounts = (new MidrubBaseUserAppsCollectionStreamHelpers\Accounts)->get_active_accounts($this->CI->stream_networks_model->get_accounts_by_network( $this->CI->user_id, 'vk', 1 ), 1);   
        
        $icon = '';
        
        $get_icon = (new MidrubBaseUserAppsCollectionStreamHelpers\Accounts)->get_network_info( 'vk' );
        
        if ( $get_icon ) {
            
            $icon = $get_icon['icon'];
            
        }
        
        return array (
            'active_accounts' => $active_accounts,
            'expired_accounts' => '',
            'network' => 'vk',
            'instructions' => $this->CI->lang->line('associated_social_account'),
            'new_account' => $icon . $this->CI->lang->line('new_account'),
            'display_hidden_content' => 1,
            'hidden_content' => $get_icon['hidden'],
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
            'displayed_name' => $this->CI->lang->line('news_feed'),
            'icon' => '<i class="fab fa-vk"></i>',
            'description' => $this->CI->lang->line('news_feed_description'),
            'parent' => 'vk'
        );
        
    }
    
}

/* End of file News_feed.php */
