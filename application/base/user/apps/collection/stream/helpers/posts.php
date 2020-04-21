<?php
/**
 * Posts Helpers
 *
 * This file contains the class Posts
 * with methods to process the posts data
 *
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.5
 */

// Define the page namespace
namespace MidrubBase\User\Apps\Collection\Stream\Helpers;

defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * Posts class provides the methods to process the posts data
 * 
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.5
*/
class Posts {
    
    /**
     * Class variables
     *
     * @since 0.0.7.5
     */
    protected $CI, $socials = array();

    /**
     * Initialise the Class
     *
     * @since 0.0.7.5
     */
    public function __construct() {
        
        // Get codeigniter object instance
        $this->CI =& get_instance();

        // Load the Stream Posts Model
        $this->CI->load->ext_model( MIDRUB_BASE_USER_APPS_STREAM . 'models/', 'Stream_posts_model', 'stream_posts_model' );
        
    }
    
    /**
     * The public method composer_publish_post publishes a post
     * 
     * @since 0.0.7.4
     * 
     * @return true or false
     */ 
    public function composer_publish_post() {
        
        // Load Main Helper
        $this->CI->load->helper('short_url_helper');
        
        // Check if data was submitted
        if ($this->CI->input->post()) {
            
            // Add form validation
            $this->CI->form_validation->set_rules('post', 'Post', 'trim|required');
            $this->CI->form_validation->set_rules('networks', 'Networks', 'trim');
            $this->CI->form_validation->set_rules('group_id', 'Group ID', 'trim');
            $this->CI->form_validation->set_rules('url', 'Url', 'trim');
            $this->CI->form_validation->set_rules('medias', 'Medias', 'trim');
            $this->CI->form_validation->set_rules('category', 'Category', 'trim');
            $this->CI->form_validation->set_rules('date', 'Date', 'trim');
            $this->CI->form_validation->set_rules('current_date', 'Current Date', 'trim');
            $this->CI->form_validation->set_rules('post_title', 'Post Title', 'trim');
            $this->CI->form_validation->set_rules('publish', 'Publish', 'trim|integer');
            
            // Get data
            $post = str_replace('-', '/', $this->CI->input->post('post'));
            $post = $this->CI->security->xss_clean(base64_decode($post));
            $networks = $this->CI->input->post('networks');
            $group_id = $this->CI->input->post('group_id');
            $url = $this->CI->input->post('url');
            $medias = $this->CI->input->post('medias');
            $category = $this->CI->input->post('category');
            $date = $this->CI->input->post('date');
            $current_date = $this->CI->input->post('current_date');
            $publish = $this->CI->input->post('publish');
            $post_title = $this->CI->input->post('post_title');
            $img = array();
            $video = array();

            // Verify if medias is not empty
            if ( $medias ) {
                
                foreach ( $medias as $media ) {
                    
                    if ( $media['type'] === 'image' ) {
                        
                        $img[] = $media['id'];
                        
                    } else {
                        
                        $video[] = $media['id'];                        
                        
                    }
                    
                }
                
            }
            
            // Serialize media
            $img = serialize($img);
            $video = serialize($video);
            
            // Get number of published posts in this month
            $posts_published = get_user_option('published_posts');
            
            if ( $posts_published ) {
                
                $posts_published = unserialize($posts_published);
                
                $published_limit = plan_feature('publish_posts');
                
                if ( ($posts_published['date'] === date('Y-m')) AND ( $published_limit <= $posts_published['posts']) ) {
                    
                    $data = array(
                        'success' => FALSE,
                        'message' => $this->CI->lang->line('reached_maximum_number_posts')
                    );

                    echo json_encode($data);

                    exit();
                    
                }
                
            }
            
            if ( $this->CI->form_validation->run() === false ) {
                
                $data = array(
                    'success' => FALSE,
                    'message' => $this->CI->lang->line('your_post_too_short')
                );

                echo json_encode($data);
                exit();
                
            } else {
                
                $date = (is_numeric(strtotime($date))) ? strtotime($date) : time();
                
                $current_date = (is_numeric(strtotime($current_date))) ? strtotime($current_date) : time();
                
                // If date is null or has invalid format will be converted to current time or null with strtotime
                if ( $date > $current_date ) {
                    
                    if ( get_user_option('settings_display_groups') ) {
                        
                        if ( !is_numeric($group_id) ) {

                            $data = array(
                                'success' => FALSE,
                                'message' => $this->CI->lang->line('no_group_selected')
                            );

                            echo json_encode($data);

                            exit();

                        }
                        
                    } else {
                    
                        if ( !$networks ) {

                            $data = array(
                                'success' => FALSE,
                                'message' => $this->CI->lang->line('no_accounts_selected')
                            );

                            echo json_encode($data);

                            exit();

                        }
                        
                    }
                    
                    // The post will be scheduled
                    $publish = 2;
                    
                    $d = $date - $current_date;
                    
                    $date = time() + $d;
                    
                } else {
                    
                    $date = time();
                    
                }
                
                if ( is_numeric($group_id) ) {
                    
                    $this->CI->load->ext_model( MIDRUB_BASE_USER_APPS_STREAM . 'models/', 'Stream_lists_model', 'stream_lists_model' );
                    
                    $metas = $this->CI->stream_lists_model->get_lists_meta($this->CI->user_id, $group_id);
                    
                    if ( $metas ) {
                        
                        $category = $group_id;
                        $networks = $metas;
                        
                    } else {
                        
                        $data = array(
                            'success' => FALSE,
                            'message' => $this->CI->lang->line('the_selected_group_empty')
                        );

                        echo json_encode($data); 
                        exit();
                        
                    }
                    
                }
                
                if ( !is_numeric($category) ) {
                    $category = json_encode($category);
                }

                // Try to publish and get the last saved id
                $lastId = $this->CI->stream_posts_model->save_post($this->CI->user_id, $post, $url, $img, $video, $date, $publish, $post_title, $category);

                if ( $networks ) {
                    
                    if ( $lastId ) {
                        
                        $net = '';
                        
                        if ( !is_numeric($category) ) {
                            
                            foreach ($networks as $network => $account) {
                                
                                $post2 = $post;
                                
                                $post_title2 = $post_title;
                                
                                // Check if network exists
                                if (file_exists(MIDRUB_BASE_USER . 'networks/' . strtolower($network) . '.php')) {
                                    
                                    $accounts = json_decode($account);
                                    
                                    if ( $accounts ) {
                                        
                                        foreach ($accounts as $ac_id) {
                                            
                                            if ( (int)$publish === 1 ) {
                                                
                                                if ( get_user_option('use_spintax_posts') ) {
                                                    
                                                    if ( in_array($network, $this->socials) ) {
                                                        
                                                        $post2 = $this->CI->ecl('Deco')->lsd($post2, $this->CI->user_id);
                                                        
                                                        if ( $post_title2 ) {
                                                            
                                                            $post_title2 = $this->CI->ecl('Deco')->lsd($post_title2, $this->CI->user_id);
                                                            
                                                        }
                                                        
                                                    } else {
                                                        
                                                        $this->socials[] = $network;
                                                        
                                                    }
                                                    
                                                }
                                                
                                                $args = array(
                                                    'post' => $post2,
                                                    'title' => $post_title2,
                                                    'network' => $network,
                                                    'account' => $ac_id,
                                                    'url' => $url,
                                                    'img' => get_post_media_array($this->CI->user_id, unserialize($img) ),
                                                    'video' => get_post_media_array($this->CI->user_id, unserialize($video) ),
                                                    'category' => $category,
                                                    'id' => $lastId
                                                );
                                                
                                                $check_pub = stream_publish_post($args);
                                                
                                                if ( $check_pub ) {
                                                    
                                                    if ( $net ) {
                                                        
                                                        if ( !preg_match('/' . $network . '/i', $net) ) {
                                                            
                                                            $net .= ', ' . ucfirst($network);
                                                            
                                                        }
                                                        
                                                    } else {
                                                        
                                                        if ( !preg_match('/' . $network . '/i', $net) ) {
                                                            
                                                            $net .= ucfirst($network);
                                                            
                                                        }
                                                        
                                                    }
                                                    
                                                    if ( $check_pub === true ) {
                                                        $check_pub = 0;
                                                    }
                                                    
                                                    $this->CI->stream_posts_model->save_post_meta($lastId, $ac_id, $network, 1, $this->CI->user_id, $check_pub);
                                                    
                                                } else {
                                                    
                                                    $this->CI->stream_posts_model->save_post_meta($lastId, $ac_id, $network, 2, $this->CI->user_id);
                                                    
                                                }
                                                
                                            } else {
                                                
                                                $net .= ucfirst(str_replace('_', ' ', $network));
                                                $this->CI->stream_posts_model->save_post_meta($lastId, $ac_id, $network, 0, $this->CI->user_id);
                                                
                                            }
                                            
                                        }
                                        
                                    }
                                    
                                }
                                
                            }
                            
                        } else {

                            if ( $networks ) {
                                
                                foreach ($networks as $meta) {
                                    
                                    $post2 = $post;
                                    
                                    $post_title2 = $post_title;
                                    
                                    // Check if network exists
                                    if ( file_exists(MIDRUB_BASE_USER . 'networks/' . strtolower($meta->network_name) . '.php') ) {
                                        
                                        if ( $this->CI->user->get_user_option($this->CI->user_id, 'use_spintax_posts') === 1 ) {
                                            
                                            if ( in_array($meta->network_name, $this->socials) ) {
                                                
                                                $post2 = $this->CI->ecl('Deco')->lsd($post2, $this->CI->user_id);
                                                
                                                if( $post_title2 ) {
                                                    
                                                    $post_title2 = $this->CI->ecl('Deco')->lsd($post_title2, $this->CI->user_id);
                                                    
                                                }
                                                
                                            } else {
                                                
                                                $this->socials[] = $meta->network_name;
                                                
                                            }
                                            
                                        }
                                        
                                        if ( $meta->network_id ) {
                                            
                                            if ( (int)$publish === 1 ) {
                                                
                                                $args = array(
                                                    'post' => $post2,
                                                    'title' => $post_title2,
                                                    'network' => $meta->network_name,
                                                    'account' => $meta->network_id,
                                                    'url' => $url,
                                                    'img' => get_post_media_array($this->CI->user_id, unserialize($img)),
                                                    'video' => get_post_media_array($this->CI->user_id, unserialize($video)),
                                                    'category' => $category,
                                                    'id' => $lastId
                                                );
                                             
                                                $check_pub = stream_publish_post($args);

                                                if ( $check_pub ) {
                                                    
                                                    $net = $this->CI->lang->line('accounts_from_the_selected_groups');
                                                    
                                                    if ( $check_pub === true ) {
                                                        $check_pub = 0;
                                                    }
                                                    
                                                    $this->CI->stream_posts_model->save_post_meta($lastId, $meta->network_id, $meta->network_name, 1, $this->CI->user_id, $check_pub);
                                                    
                                                }
                                                
                                            }
                                            
                                        }
                                        
                                    }
                                    
                                    sleep(1);
                                    
                                }
                                
                            }
                            
                            if ( (int)$publish === 2 ) {
                                
                                $net = $this->CI->lang->line('accounts_from_the_selected_groups');
                                
                            }
                            
                        }
                        
                        if ( $net ) {
                            
                            if ( (int)$publish === 1 ) {
                                
                                // A new post was published successfully in this month
                                set_post_number($this->CI->user_id);
                                
                                $data = array(
                                    'success' => TRUE,
                                    'message' => $this->CI->lang->line('your_post_was_published') . str_replace('_', ' ', $net)
                                );

                                echo json_encode($data);
                                
                            } elseif ( (int)$publish === 2 ) {
                                
                                $data = array(
                                    'success' => TRUE,
                                    'message' => $this->CI->lang->line('your_post_was_scheduled')
                                );

                                echo json_encode($data);
                                
                                // Check if the administrator want to receive a notification about the scheduled post
                                if ( $this->CI->options->check_enabled('enable-scheduled-notifications') ) {
                                    
                                    // Send a notification via email
                                    $args = array(
                                        '[site_name]' => $this->CI->config->item('site_name'),
                                        '[login_address]' => '<a href="' . $this->CI->config->item('login_url') . '">' . $this->CI->config->item('login_url') . '</a>',
                                        '[site_url]' => '<a href="' . $this->CI->config->base_url() . '">' . $this->CI->config->base_url() . '</a>'
                                    );
                                    
                                    // Get the send-password-new-users notification template
                                    $template = $this->CI->notifications->get_template('scheduled-notification', $args);
                                    
                                    if ( $template ) {
                                        
                                        $this->CI->email->from($this->CI->config->item('contact_mail'), $this->CI->config->item('site_name'));
                                        $this->CI->email->to($this->CI->config->item('notification_mail'));
                                        $this->CI->email->subject($template['title']);
                                        $this->CI->email->message($template['body']);
                                        $this->CI->email->send();
                                        
                                    }
                                    
                                }
                                
                            } else {
                                
                                $data = array(
                                    'success' => TRUE,
                                    'message' => $this->CI->lang->line('post_saved_as_draft')
                                );

                                echo json_encode($data); 
                                
                            }
                            
                        } else {
                            
                            if ( (int)$publish === 2 && is_numeric($category) ) {
                                
                                $data = array(
                                    'success' => TRUE,
                                    'message' => $this->CI->lang->line('your_post_was_scheduled')
                                );

                                echo json_encode($data);
                                
                                // Check if the administrator want to receive a notification about the scheduled post
                                if ( $this->CI->options->check_enabled('enable-scheduled-notifications') ) {
                                    
                                    // Send a notification via email
                                    $args = array(
                                        '[site_name]' => $this->CI->config->item('site_name'),
                                        '[login_address]' => '<a href="' . $this->CI->config->item('login_url') . '">' . $this->CI->config->item('login_url') . '</a>',
                                        '[site_url]' => '<a href="' . $this->CI->config->base_url() . '">' . $this->CI->config->base_url() . '</a>'
                                    );
                                    
                                    // Get the send-password-new-users notification template
                                    $template = $this->CI->notifications->get_template('scheduled-notification', $args);
                                    
                                    if ( $template ) {
                                        
                                        $this->CI->email->from($this->CI->config->item('contact_mail'), $this->CI->config->item('site_name'));
                                        $this->CI->email->to($this->CI->config->item('notification_mail'));
                                        $this->CI->email->subject($template['title']);
                                        $this->CI->email->message($template['body']);
                                        $this->CI->email->send();
                                        
                                    }
                                    
                                }
                                
                            } else {
                            
                                $data = array(
                                    'success' => FALSE,
                                    'message' => $this->CI->lang->line('error_occurred')
                                );

                                echo json_encode($data);
                            
                            }
                            
                        }
                        
                    } else {
                        
                        $data = array(
                            'success' => FALSE,
                            'message' => $this->CI->lang->line('your_post_was_not_published')
                        );

                        echo json_encode($data);                         
                        
                    }
                    
                } else {
                    
                    if ( (int)$publish === 0 ) {
                        
                        $data = array(
                            'success' => TRUE,
                            'message' => $this->CI->lang->line('post_saved_as_draft')
                        );

                        echo json_encode($data); 
                        
                    } else {
                        
                        if ( get_user_option('settings_display_groups') ) {

                            if ( !is_numeric($group_id) ) {

                                $data = array(
                                    'success' => FALSE,
                                    'message' => $this->CI->lang->line('no_group_selected')
                                );

                                echo json_encode($data);

                                exit();

                            }

                        } else {

                            $data = array(
                                'success' => FALSE,
                                'message' => $this->CI->lang->line('no_accounts_selected')
                            );

                            echo json_encode($data);
                            
                        }
                        
                    }
                    
                }
                
            }
            
        }
        
    }
    
}

