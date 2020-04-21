<?php
/**
 * My Tweets Template
 *
 * This file contains the Stream's My Tweets template 
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
use Abraham\TwitterOAuth\TwitterOAuth;

/*
 * My_tweets contains the Stream's My_tweets template 
 * 
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.5
 */
class My_tweets implements MidrubBaseUserAppsCollectionStreamInterfaces\Stream {
    
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
                
                if ( $network_details ) {
                    
                    switch( $item_type ) {
                        
                        case 'retweet':
                            
                            $action = $this->CI->stream_history_model->get_item_actions($stream_id, $item_id, 1);
                            
                            if ( @$action[0]->value === 'retweeted' ) {
                                
                                $connection = new TwitterOAuth($this->twitter_key, $this->twitter_secret, $network_details[0]->token, $network_details[0]->secret);

                                $status = $connection->post('statuses/unretweet', array('id' => $item_id));

                                if ( $status ) {

                                    $this->CI->stream_history_model->update_stream_item_action($action[0]->history_id, 'unretweeted');

                                    $data = array(
                                        'success' => TRUE,
                                        'message' => $this->CI->lang->line('tweet_was_unretweeted'),
                                        'tab_id' => $stream[0]->tab_id
                                    );

                                    echo json_encode($data);  
                                    exit();

                                } else {

                                    $data = array(
                                        'success' => FALSE,
                                        'message' => $this->CI->lang->line('tweet_was_not_unretweeted')
                                    );

                                    echo json_encode($data);
                                    exit();

                                }
                                
                            } else {
                    
                                $connection = new TwitterOAuth($this->twitter_key, $this->twitter_secret, $network_details[0]->token, $network_details[0]->secret);

                                $status = $connection->post('statuses/retweet', array('id' => $item_id));

                                if ( @$status->id ) {
                                    
                                    if ( @$action[0]->value === 'retweeted' ) {
                                        
                                        $this->CI->stream_history_model->update_stream_item_action($action[0]->history_id, 'retweeted');
                                        
                                    } else {

                                        $this->CI->stream_history_model->save_stream_item_action($stream_id, $item_id, 1, 'retweeted');
                                        
                                    }

                                    $data = array(
                                        'success' => TRUE,
                                        'message' => $this->CI->lang->line('tweet_was_retweeted'),
                                        'tab_id' => $stream[0]->tab_id
                                    );

                                    echo json_encode($data);  
                                    exit();

                                } else {

                                    $data = array(
                                        'success' => FALSE,
                                        'message' => $this->CI->lang->line('tweet_was_not_retweeted')
                                    );

                                    echo json_encode($data);
                                    exit();

                                }
                            
                            }
                        
                        break;
                        
                        case 'favorites':
                            
                            $action = $this->CI->stream_history_model->get_item_actions($stream_id, $item_id, 2);
                            
                            if ( @$action[0]->value === 'favorited' ) {
                                
                                $connection = new TwitterOAuth($this->twitter_key, $this->twitter_secret, $network_details[0]->token, $network_details[0]->secret);

                                $status = $connection->post('favorites/destroy', array('id' => $item_id));

                                if ( $status ) {

                                    $this->CI->stream_history_model->update_stream_item_action($action[0]->history_id, 'unfavorited');

                                    $data = array(
                                        'success' => TRUE,
                                        'message' => $this->CI->lang->line('tweet_was_unretweeted'),
                                        'tab_id' => $stream[0]->tab_id
                                    );

                                    echo json_encode($data);  
                                    exit();

                                } else {

                                    $data = array(
                                        'success' => FALSE,
                                        'message' => $this->CI->lang->line('tweet_was_not_unretweeted')
                                    );

                                    echo json_encode($data);
                                    exit();

                                }
                                
                            } else {
                    
                                $connection = new TwitterOAuth($this->twitter_key, $this->twitter_secret, $network_details[0]->token, $network_details[0]->secret);

                                $status = $connection->post('favorites/create', array('id' => $item_id));

                                if ( @$status->id ) {
                                    
                                    if ( @$action[0]->value === 'unfavorited' ) {
                                        
                                        $this->CI->stream_history_model->update_stream_item_action($action[0]->history_id, 'favorited');
                                        
                                    } else {

                                        $this->CI->stream_history_model->save_stream_item_action($stream_id, $item_id, 2, 'favorited');
                                        
                                    }

                                    $data = array(
                                        'success' => TRUE,
                                        'message' => $this->CI->lang->line('tweet_was_favorited'),
                                        'tab_id' => $stream[0]->tab_id
                                    );

                                    echo json_encode($data);  
                                    exit();

                                } else {

                                    $data = array(
                                        'success' => FALSE,
                                        'message' => $this->CI->lang->line('tweet_was_not_favorited')
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
        
        $get_setup = (new MidrubBaseUserAppsCollectionStreamHelpers\Streams_setup)->streams_setup_get($stream->stream_id, 'my_tweets');
        
        $words = 'popular';
        
        if ( $get_setup ) {
            
            $words = array();
                    
            foreach ( $get_setup as $set ) {
                
                $words[] = $set->setup_option;
                
            }
            
            $words = urlencode(implode('+', $words));
            
        }
        
        $tweets = $connection->get('statuses/user_timeline',
            array(
                'screen_name' => $network_details[0]->user_name,
                'count' => 10
            )
        );

        $all_tweets = '';
        
        $ids = array();
        
        if ( $tweets ) {
            
            $actions = $this->CI->stream_history_model->get_item_actions($stream->stream_id);
            
            $all_actions = array();
            
            if ( $actions ) {
                
                foreach ( $actions as $action ) {
                    
                    $all_actions[$action->type . '-' . $action->id] = $action->value;
                    
                }
                
            }
            
            foreach ( $tweets as $status ) {
                
                $images = '';
                
                if ( @$status->entities->media[0] ) {
                    
                    $images .= '<p data-type="stream-item-media">'
                                . '<img src="' . $status->entities->media[0]->media_url_https . '">'
                            . '</p>';
                    
                }
                
                $hashtags = '';
                
                if ( @$status->entities->hashtags[0] ) {
                    
                    $hashtags .= '<p>';
                    
                    foreach ( $status->entities->hashtags as $hashtag ) {
                    
                        $hashtags .= '<a href="https://twitter.com/hashtag/' . $hashtag->text . '" target="_blank">#' . $hashtag->text . '</a> ';
                    
                    }
                    
                    $hashtags .= '</p>';
                    
                }    
                
                $retweeted = '';
                
                if ( @$all_actions['1-' . $status->id] === 'retweeted' ) {
                    $retweeted = ' action-done';
                }
                
                $favorited = '';
                
                if ( @$all_actions['2-' . $status->id] === 'favorited' ) {
                    $favorited = ' action-liked';
                }
                
                $ids[] = $status->id;

                $all_tweets .= '<li class="row"' . $border_bottom_color . '>
                                <div class="col-xl-12">
                                    <img src="' . $status->user->profile_image_url_https . '" alt="User Avatar" class="img-circle">
                                    <div class="stream-post">
                                        <strong>
                                            <a href="https://twitter.com/' . $status->user->screen_name . '" target="_blank"' . $links_color . '>
                                                ' . $status->user->screen_name . '
                                            </a>
                                        </strong>
                                        <small' . $icons_color . '>
                                            ' . '<i class="icon-clock"></i>' . calculate_time(strtotime($status->created_at), time()) . '
                                        </small>
                                        <p' . $paragraph_color . ' data-type="stream-item-content">
                                            ' . preg_replace('@(https?://([-\w\.]+[-\w])+(:\d+)?(/([\w/_\.#-~]*(\?\S+)?)?)?)@', '<a href="$1"' . $links_color . '>$1</a>', $status->text) . '
                                        </p>
                                        ' . $images . '
                                        ' . $hashtags . '    
                                        <div class="stream-post-footer">
                                            <a href="#" class="stream-item-action' . $retweeted . '" data-stream="' . $stream->stream_id . '" data-type="retweet" data-id="' . $status->id . '"' . $icons_color . '>
                                                <i class="fas fa-retweet"></i>
                                                ' . $status->retweet_count . '
                                            </a>
                                            <a href="#" class="stream-item-action' . $favorited . '" data-stream="' . $stream->stream_id . '" data-type="favorites" data-id="' . $status->id . '"' . $icons_color . '>
                                                <i class="icon-heart"></i>
                                                ' . $status->favorite_count . '
                                            </a>
                                            <a href="#stream-item-share" data-toggle="modal" class="stream-item-share" data-stream="' . $stream->stream_id . '" data-type="share" data-id="' . $status->id . '"' . $icons_color . '>
                                                <i class="icon-paper-plane"></i>
                                            </a> 
                                        </div>
                                    </div>
                                </div>
                            </li>';
                
            }
            
        } else {
            
            $all_tweets .= '<li class="row"' . $border_bottom_color . '>'
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
                            . $all_tweets
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
        $this->CI->lang->load( 'stream_instructions', $this->CI->config->item('language'), FALSE, TRUE, MIDRUB_BASE_USER_APPS_STREAM);
        
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
            'displayed_name' => $this->CI->lang->line('my_tweets'),
            'icon' => '<i class="fab fa-twitter"></i>',
            'description' => $this->CI->lang->line('display_my_tweets'),
            'parent' => 'twitter'
        );
        
    }
    
}

/* End of file My_tweets.php */
