<?php
/**
 * Page Posts Template
 *
 * This file contains the Stream's Page Posts template 
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
 * Page_posts contains the Stream's Page_posts template 
 * 
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.6
 */
class Page_posts implements MidrubBaseUserAppsCollectionStreamInterfaces\Stream {
    
    /**
     * Class variables
     *
     * @since 0.0.7.6
     */
    protected $CI, $fb, $app_id, $app_secret;

    /**
     * Initialise the Class
     *
     * @since 0.0.7.6
     */
    public function __construct() {
        
        // Get codeigniter object instance
        $this->CI =& get_instance();
        
        // Get the Facebook App ID
        $this->app_id = get_option('facebook_pages_app_id');
        
        // Get the Facebook App Secret
        $this->app_secret = get_option('facebook_pages_app_secret');
        
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
                'default_graph_version' => MIDRUB_STREAM_FACEBOOK_GRAPH_VERSION,
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
        
        $network_details = $this->CI->stream_networks_model->get_network_data('facebook_pages', $this->CI->user_id, $stream->network_id);
        
        $response = json_decode(get(MIDRUB_STREAM_FACEBOOK_GRAPH_URL . $network_details[0]->net_id . '/feed?fields=created_time,message,attachments,id,from,shares&limit=10&access_token=' . $network_details[0]->secret), true);

        $all_ratings = '';
        
        $ids = array();
        
        if ( !empty($response['data']) ) {
         
            $actions = $this->CI->stream_history_model->get_item_actions($stream->stream_id);
            
            $all_actions = array();
            
            if ( $actions ) {
                
                foreach ( $actions as $action ) {
                    
                    $all_actions[$action->type . '-' . $action->id] = $action->value;
                    
                }
                
            }
            
            foreach ( $response['data'] as $post ) {

                $post_data = $this->fb->get(
                    '/' . $post['id'] . '/comments?fields=from,attachment,message,created_time,comment_count&limit=10',
                    $network_details[0]->secret
                );
                
                $graphPost = $post_data->getGraphEdge();
                
                $comments = $graphPost->asArray();
                
                $all_comments = '';
                
                if ( !$comments ) {
                    
                    $comments = 0;
                    
                } else {
                    
                    $all_comments = '<ul>';
                    
                    foreach ( $comments as $comment ) {
                        
                        $ids[] = $comment['id'];
                        
                        $media = '';
                        
                        if ( isset($comment['attachment']['media']['image']['src']) ) {
                            
                            $media = '<p data-type="stream-item-media"><img src="' . $comment['attachment']['media']['image']['src'] . '"></p>';
                            
                        }
                        
                        $replies_data = $this->fb->get(
                            '/' . $comment['id'] . '/comments?fields=from,attachment,message,created_time,comment_count&limit=10',
                            $network_details[0]->secret
                        );     
                        
                        $graphReplies = $replies_data->getGraphEdge();

                        $replies = $graphReplies->asArray();

                        $all_replies = '';

                        if ( $replies ) {

                            foreach ( $replies as $reply ) {
                                
                                $ids[] = $reply['id'];

                                $rmedia = '';

                                if ( isset($reply['attachment']['media']['image']['src']) ) {

                                    $rmedia = '<p data-type="stream-item-media"><img src="' . $reply['attachment']['media']['image']['src'] . '"></p>';

                                }

                                $from_name = $network_details[0]->user_name;
                                $from_id = $network_details[0]->net_id;
                
                                if ( isset($reply['from']['id']) ) {
                                    $from_id = $reply['from']['id'];
                                    $from_name = $reply['from']['name'];
                
                                }
                                
                                $all_replies .= '<li class="row"' . $border_bottom_color . '>
                                                <div class="col-xl-12">
                                                    <img src="https://graph.facebook.com/' . $from_id . '/picture?type=square&access_token=' . $network_details[0]->secret . '" alt="User Avatar" class="img-circle">
                                                    <div class="stream-post">
                                                        <strong>
                                                            <a href="https://www.facebook.com/' . $from_id . '" target="_blank"' . $links_color . '>
                                                                ' . $from_name . '
                                                            </a>
                                                        </strong>
                                                        <small' . $icons_color . '>
                                                            ' . '<i class="icon-clock"></i>' . calculate_time(strtotime($reply['created_time']->format('Y-m-d H:i:s')), time()) . '
                                                        </small>
                                                        <p' . $paragraph_color . ' data-type="stream-item-content">
                                                            ' . preg_replace('@(https?://([-\w\.]+[-\w])+(:\d+)?(/([\w/_\.#-~]*(\?\S+)?)?)?)@', '<a href="$1"' . $links_color . '>$1</a>', $reply['message']) . '
                                                        </p>
                                                        ' . $rmedia . '
                                                        <div class="stream-post-footer">
                                                            <a href="#" data-network="page_posts" class="stream-item-react" data-stream="' . $stream->stream_id . '" data-type="reply" data-id="' . $comment['id'] . '"' . $icons_color . '>
                                                                <i class="far fa-comments"></i>
                                                                ' . $reply['comment_count'] . '
                                                            </a> 
                                                            <a href="#stream-item-share" data-toggle="modal" class="stream-item-share" data-stream="' . $stream->stream_id . '" data-type="share" data-id="' . $reply['id'] . '"' . $icons_color . '>
                                                                <i class="icon-paper-plane"></i>
                                                            </a> 
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>';

                            }
                            
                            $all_replies = '<div class="col-xl-12"><ul>'
                                            . $all_replies
                                        . '</ul></div>';

                        }
                        
                        $from_name = $network_details[0]->user_name;
                        $from_id = $network_details[0]->net_id;
        
                        if ( isset($comment['from']['id']) ) {
                            $from_id = $comment['from']['id'];
                            $from_name = $comment['from']['name'];
                        }
                        
                        $all_comments .= '<li class="row"' . $border_bottom_color . '>
                                        <div class="col-xl-12">
                                            <img src="https://graph.facebook.com/' . $from_id . '/picture?type=square&access_token=' . $network_details[0]->secret . '" alt="User Avatar" class="img-circle">
                                            <div class="stream-post">
                                                <strong>
                                                    <a href="https://www.facebook.com/' . $from_id . '" target="_blank"' . $links_color . '>
                                                        ' . $from_name . '
                                                    </a>
                                                </strong>
                                                <small>
                                                    ' . '<i class="icon-clock"></i>' . calculate_time(strtotime($comment['created_time']->format('Y-m-d H:i:s')), time()) . '
                                                </small>
                                                <p' . $paragraph_color . ' data-type="stream-item-content">
                                                    ' . preg_replace('@(https?://([-\w\.]+[-\w])+(:\d+)?(/([\w/_\.#-~]*(\?\S+)?)?)?)@', '<a href="$1">$1</a>', $comment['message']) . '
                                                </p>
                                                ' . $media . '
                                                <div class="stream-post-footer">
                                                    <a href="#" data-network="page_posts" class="stream-item-react" data-stream="' . $stream->stream_id . '" data-type="reply" data-id="' . $comment['id'] . '">
                                                        <i class="far fa-comments"></i>
                                                        ' . $comment['comment_count'] . '
                                                    </a>  
                                                    <a href="#stream-item-share" data-toggle="modal" class="stream-item-share" data-stream="' . $stream->stream_id . '" data-type="share" data-id="' . $comment['id'] . '"' . $icons_color . '>
                                                        <i class="icon-paper-plane"></i>
                                                    </a> 
                                                </div>
                                            </div>
                                        </div>'
                                        . $all_replies
                                    . '</li>';
                        
                    }
                    
                    $all_comments .= '</ul>';
                    
                    $comments = count($comments);
                    
                    $all_comments = '<div class="col-xl-12">'
                                    . $all_comments
                                . '</div>';
                    
                }
                
                $ids[] = $post['id'];
                
                $message = '';
                
                $shares = 0;
                
                if ( isset( $post['shares']['count'] ) ) {
                    $shares = $post['shares']['count'];
                }
                
                if ( isset($post['message']) ) {
                    
                    $message = '<p' . $paragraph_color . ' data-type="stream-item-content">'
                                . preg_replace('@(https?://([-\w\.]+[-\w])+(:\d+)?(/([\w/_\.#-~]*(\?\S+)?)?)?)@', '<a href="$1">$1</a>', $post['message'])
                            . '</p>';
                }

                if ( !isset($post['from']) ) {

                    $post['from'] = array(
                        'id' => $network_details[0]->net_id,
                        'name' => $network_details[0]->user_name
                    );

                }

                $all_ratings .= '<li class="row"' . $border_bottom_color . '>
                                    <div class="col-xl-12">
                                        <img src="https://graph.facebook.com/' . $post['from']['id'] . '/picture?type=square&access_token=' . $network_details[0]->secret . '" alt="User Avatar" class="img-circle">
                                        <div class="stream-post">
                                            <strong>
                                                <a href="https://www.facebook.com/' . $post['from']['id'] . '" target="_blank"' . $links_color . '>
                                                    ' . $post['from']['name'] . '
                                                </a>
                                            </strong>
                                            <small>
                                                ' . '<i class="icon-clock"></i>' . calculate_time(strtotime($post['created_time']), time()) . '
                                            </small>
                                            ' . $message . '  
                                            <div class="stream-post-footer">
                                                <a href="#stream-item-react" data-toggle="modal" data-network="page_posts" class="stream-item-react" data-stream="' . $stream->stream_id . '" data-type="comment" data-id="' . $post['id'] . '">
                                                    <i class="far fa-comments"></i>
                                                    ' . $comments . '
                                                </a>   
                                                <span>
                                                    <i class="icon-share"></i>
                                                    ' . $shares . '
                                                </span> 
                                                <a href="#stream-item-share" data-toggle="modal" class="stream-item-share" data-stream="' . $stream->stream_id . '" data-type="share" data-id="' . $post['id'] . '"' . $icons_color . '>
                                                    <i class="icon-paper-plane"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    ' . $all_comments . '
                                </li>';
                
            }
            
        } else {
            
            $all_ratings .= '<li class="row"' . $border_bottom_color . '>'
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
            'header' => '<i class="icon-social-facebook"></i>'
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
                            . $all_ratings
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
        
        // Load the Facebook Class
        $this->fb = new \Facebook\Facebook(
            array(
                'app_id' => $this->app_id,
                'app_secret' => $this->app_secret,
                'default_graph_version' => MIDRUB_STREAM_FACEBOOK_GRAPH_VERSION,
                'default_access_token' => '{access-token}',
            )
        );
        
        // Check if data was submitted
        if ($this->CI->input->post()) {
            
            // Add form validation
            $this->CI->form_validation->set_rules('type', 'Type', 'trim|required');
            $this->CI->form_validation->set_rules('id', 'ID', 'trim|required');
            
            // Get data
            $type = $this->CI->input->post('type');
            $id = $this->CI->input->post('id');
            
            
            if ( $this->CI->form_validation->run() !== false ) {
                
                $network_details = $this->CI->stream_networks_model->get_network_data('facebook_pages', $this->CI->user_id, $stream[0]->network_id);
                
                if ( $network_details ) {
                    
                    switch ( $type ) {
                        
                        case 'comment': 
                        
                             $post_data = $this->fb->get(
                                '/' . $id . '?fields=from,message',
                                $network_details[0]->secret
                            );

                            $graphPost = $post_data->getGraphNode();

                            if ( $graphPost->asArray() ) {
                                
                                $post = $graphPost->asArray();
                                
                                $post_data = '<div class="col-xl-12 stream-simgle-item-template">'
                                                . '<img src="https://graph.facebook.com/' . $post['from']['id'] . '/picture?type=square&access_token=' . $network_details[0]->secret . '" alt="User Avatar" class="img-circle">'
                                                . '<div class="stream-post">'
                                                    . '<strong>'
                                                        . '<a href="https://www.facebook.com/' . $post['from']['id'] . '" target="_blank">'
                                                            . $post['from']['name']
                                                        . '</a>'
                                                    . '</strong>'
                                                    . '<p>'
                                                        . $post['message']
                                                    . '</p>'
                                                . '</div>'
                                            . '</div>';

                                $data = array(
                                    'success' => TRUE,
                                    'content' => $post_data,
                                    'form' => form_open('user/app/stream', ['class' => 'stream-send-react', 'data-csrf' => $this->CI->security->get_csrf_token_name()])
                                                . '<div class="form-group">'
                                                    . '<textarea name="comment" class="form-control" rows="3"></textarea>'
                                                . '</div>'
                                                . '<input type="hidden" name="id" value="' .  $post['id'] . '">'
                                                . '<input type="hidden" name="stream_id" value="' .  $stream[0]->stream_id . '">'
                                                . '<input type="hidden" name="type" value="comment">'                                    
                                                . '<button type="submit" class="btn btn-primary">' . $this->CI->lang->line('submit') . '</button>'
                                            . form_close(),
                                    'menu_text' => $this->CI->lang->line('post')
                                );

                                echo json_encode($data);  
                                exit();
                                
                            }                    
                            
                            break;
                            
                        case 'reply': 
                        
                            $comment = $this->fb->get(
                                '/' . $id . '?fields=message,attachment,from',
                                $network_details[0]->secret
                            );

                            $graphComment = $comment->getGraphNode();

                            if ( $graphComment->asArray() ) {
                                
                                $com = $graphComment->asArray();
                                
                                $media = '';

                                if ( isset($com['attachment']['media']['image']['src']) ) {

                                    $media = '<p><img src="' . $com['attachment']['media']['image']['src'] . '"></p>';

                                }

                                $from_name = $network_details[0]->user_name;
                                $from_id = $network_details[0]->net_id;
                
                                if ( isset($com['from']['id']) ) {
                                    $from_id = $com['from']['id'];
                                    $from_name = $com['from']['name'];
                
                                }
                                
                                $com_data = '<div class="col-xl-12 stream-simgle-item-template">'
                                            . '<img src="https://graph.facebook.com/' . $from_id . '/picture?type=square&access_token=' . $network_details[0]->secret . '" alt="User Avatar" class="img-circle">'
                                            . '<div class="stream-post">'
                                                . '<strong>'
                                                    . '<a href="https://www.facebook.com/' . $from_id . '" target="_blank">'
                                                        . $from_name
                                                    . '</a>'
                                                . '</strong>'
                                                . '<p>'
                                                    . $com['message']
                                                . '</p>'
                                                . $media
                                            . '</div>'
                                        . '</div>';

                                $data = array(
                                    'success' => TRUE,
                                    'content' => $com_data,
                                    'form' => form_open('user/app/stream', ['class' => 'stream-send-react', 'data-csrf' => $this->CI->security->get_csrf_token_name()])
                                                . '<div class="form-group">'
                                                    . '<textarea name="comment" class="form-control" rows="3"></textarea>'
                                                . '</div>'
                                                . '<input type="hidden" name="id" value="' .  $com['id'] . '">'
                                                . '<input type="hidden" name="stream_id" value="' .  $stream[0]->stream_id . '">'
                                                . '<input type="hidden" name="type" value="reply">'                                    
                                                . '<button type="submit" class="btn btn-primary">' . $this->CI->lang->line('submit') . '</button>'
                                            . form_close(),
                                    'menu_text' => $this->CI->lang->line('comment')
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
        
        $network_details = $this->CI->stream_networks_model->get_network_data('facebook_pages', $this->CI->user_id, $stream[0]->network_id);
        
        $setup_data = '';
        
        if ( $network_details ) {
            
            $icon = '';

            $get_icon = (new MidrubBaseUserAppsCollectionStreamHelpers\Accounts)->get_network_info( 'facebook_pages' );

            if ( $get_icon ) {

                $icon = $get_icon['icon'];

            }
            
            $setup_data = '<div class="row">'
                            . '<div class="col-xl-12">'
                                . '<div class="row clean connected-account">'
                                    . '<div class="col-xl-8 col-8">'
                                        . '<p>'
                                            . '<i class="icon-user-following"></i> ' . $this->CI->lang->line('connected_page')
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
        $this->CI->lang->load( 'stream_instructions', $this->CI->config->item('language'), FALSE, TRUE, MIDRUB_BASE_USER_APPS_STREAM );
        
        // Get Active Accounts
        $active_accounts = (new MidrubBaseUserAppsCollectionStreamHelpers\Accounts)->get_active_accounts($this->CI->stream_networks_model->get_accounts_by_network( $this->CI->user_id, 'facebook_pages', 1 ), 1);

        // Get Expired Accounts
        $expired_accounts = (new MidrubBaseUserAppsCollectionStreamHelpers\Accounts)->get_active_accounts($this->CI->stream_networks_model->get_accounts_by_network( $this->CI->user_id, 'facebook_pages', 0 ), 0);        
        
        $icon = '';
        
        $get_icon = (new MidrubBaseUserAppsCollectionStreamHelpers\Accounts)->get_network_info( 'facebook_pages' );
        
        if ( $get_icon ) {
            
            $icon = $get_icon['icon'];
            
        }
        
        $connect_button_text = (isset($get_icon['custom_connect']))?$get_icon['custom_connect']:$this->CI->lang->line('new_account');
        
        return array (
            'active_accounts' => $active_accounts,
            'expired_accounts' => $expired_accounts,
            'network' => 'facebook_pages',
            'instructions' => $this->CI->lang->line('associated_social_account'),
            'new_account' => $icon . $connect_button_text,
            'placeholder' => $this->CI->lang->line('search_pages'),
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
        
        // Load the Facebook Class
        $this->fb = new \Facebook\Facebook(
            array(
                'app_id' => $this->app_id,
                'app_secret' => $this->app_secret,
                'default_graph_version' => MIDRUB_STREAM_FACEBOOK_GRAPH_VERSION,
                'default_access_token' => '{access-token}',
            )
        );
        
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
                
                $network_details = $this->CI->stream_networks_model->get_network_data('facebook_pages', $this->CI->user_id, $stream[0]->network_id);
                
                if ( $network_details ) {
                
                    switch ( $type ) {

                        case 'comment':

                            try {

                                $response = $this->fb->post(
                                    '/' . $id . '/comments',
                                    array('message' => $comment),
                                    $network_details[0]->secret
                                );

                            } catch (Facebook\Exceptions\FacebookResponseException $e) {

                                echo 'Graph returned an error: ' . $e->getMessage();
                                exit;

                            } catch (Facebook\Exceptions\FacebookSDKException $e) {

                                echo 'Facebook SDK returned an error: ' . $e->getMessage();
                                exit;

                            }
                            
                            $graphNode = $response->getGraphNode();

                            if ( $graphNode->asArray() ) {
                                
                                $status = $graphNode->asArray();
                                
                                if ( isset($status['id']) ) {
                                    
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

                            break;
                            
                        case 'reply':

                            try {

                                $response = $this->fb->post(
                                    '/' . $id . '/comments',
                                    array('message' => $comment),
                                    $network_details[0]->secret
                                );

                            } catch (Facebook\Exceptions\FacebookResponseException $e) {

                                echo 'Graph returned an error: ' . $e->getMessage();
                                exit;

                            } catch (Facebook\Exceptions\FacebookSDKException $e) {

                                echo 'Facebook SDK returned an error: ' . $e->getMessage();
                                exit;

                            }
                            
                            $graphNode = $response->getGraphNode();

                            if ( $graphNode->asArray() ) {
                                
                                $status = $graphNode->asArray();
                                
                                if ( isset($status['id']) ) {
                                    
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
            'displayed_name' => $this->CI->lang->line('page_posts'),
            'icon' => '<i class="icon-social-facebook"></i>',
            'description' => $this->CI->lang->line('display_facebook_page_posts_description'),
            'parent' => 'facebook_pages'
        );
        
    }
    
}

/* End of file Page_posts.php */
