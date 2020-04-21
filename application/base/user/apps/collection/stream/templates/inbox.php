<?php
/**
 * Inbox Template
 *
 * This file contains the Stream's Inbox template 
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
 * Inbox contains the Stream's Inbox template 
 * 
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.6
 */
class Inbox implements MidrubBaseUserAppsCollectionStreamInterfaces\Stream {
    
    /**
     * Class variables
     *
     * @since 0.0.7.4
     */
    protected
            $CI, $clientId, $clientSecret, $apiKey, $appName, $scriptUri;

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
        
        $network_details = $this->CI->stream_networks_model->get_network_data('gmail', $this->CI->user_id, $stream->network_id);
        
        $token = '';
        
        if ( $network_details[0]->secret ) {
            
            $response = json_decode(post('https://www.googleapis.com/oauth2/v4/token', array('client_id' => get_option('gmail_client_id'), 'client_secret' => get_option('gmail_client_secret'), 'refresh_token' => $network_details[0]->secret, 'grant_type' => 'refresh_token')), true);

            if ( isset($response['access_token']) ) {
            
                $token = $response['access_token'];

            }
            
        }
        
        $get_setup = (new MidrubBaseUserAppsCollectionStreamHelpers\Streams_setup)->streams_setup_get($stream->stream_id, 'inbox'); 
        
        $inbox = 'labelIds=INBOX&';

        if ( $get_setup ) {
            
            $inbox = 'includeSpamTrash=true&labelIds=UNREAD&';
            
        }

        $response = json_decode(get('https://www.googleapis.com/gmail/v1/users/' . $network_details[0]->net_id . '/messages?' . $inbox . 'maxResults=10&access_token=' . $token), true);

        $all_messages = '';
        
        $ids = array();
        
        if ( isset($response['messages'][0]) ) {
            
            $actions = $this->CI->stream_history_model->get_item_actions($stream->stream_id);
            
            $all_actions = array();
            
            if ( $actions ) {
                
                foreach ( $actions as $action ) {
                    
                    $all_actions[$action->type . '-' . $action->id] = $action->value;
                    
                }
                
            }
            
            foreach ( $response['messages'] as $mess ) {
                
                $ids[] = $mess['id'];
                
                $dat = json_decode(get('https://www.googleapis.com/gmail/v1/users/' . $network_details[0]->net_id . '/messages/' . $mess['id'] . '?format=full&access_token=' . $token), true);

                $subject = '';
                
                foreach ( $dat['payload']['headers'] as $header ) {
                    
                    if ( $header['name'] === 'Subject' ) {
                        
                        $subject = $header['value'];
                        
                    }
                    
                }

                $all_messages .= '<li class="row"' . $border_bottom_color . '>
                                <div class="col-xl-12">
                                    <div class="stream-post full-width">
                                        <strong>
                                            <a href="#stream-item-react" data-toggle="modal" data-network="inbox" class="stream-item-react" data-stream="' . $stream->stream_id . '" data-type="mail" data-id="' . $mess['id'] . '"' . $links_color . '>
                                                ' . $subject . '
                                            </a>
                                        </strong><br>
                                        <small class="clean"' . $icons_color . '>
                                            <i class="icon-clock"></i>' . calculate_time(($dat['internalDate']/1000), time()) . '
                                        </small>
                                        <p' . $paragraph_color . '>
                                            ' . $dat['snippet'] . '
                                        </p>  
                                        <div class="stream-post-footer">                                                                  
                                        </div>
                                    </div>
                                </div>
                            </li>';
                
            }
            
        } else {
            
            $all_messages .= '<li class="row"' . $border_bottom_color . '>'
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
        
        if ( !$cronology && $ids ) {
        
            $this->CI->stream_history_model->update_stream_cronology($stream->stream_id, serialize($ids));
            
        } else {

            // Verify if ids exists
            if ($ids) {

                if ($cronology[0]->value !== serialize($ids)) {

                    $this->CI->stream_history_model->update_stream_cronology($stream->stream_id, serialize($ids));

                    if ($this->CI->stream_tabs_model->update_stream_field($stream->stream_id, 'new_event', 1)) {
                        $active_check++;

                        if (file_exists(FCPATH . '/assets/sounds/' . $stream->alert_sound . '.mp3')) {

                            $sound = '<iframe src="' . base_url('/assets/sounds/' . $stream->alert_sound . '.mp3') . '" class="d-none" allow="autoplay" id="audio"></iframe>';
                            
                        }

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
            'header' => '<i class="fas fa-at" style="color: #d63d3d;"></i>'
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
                            . $all_messages
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
        
        // Check if data was submitted
        if ($this->CI->input->post()) {
            
            // Add form validation
            $this->CI->form_validation->set_rules('type', 'Type', 'trim|required');
            $this->CI->form_validation->set_rules('id', 'ID', 'trim|required');
            
            // Get data
            $type = $this->CI->input->post('type');
            $id = $this->CI->input->post('id');
            
            if ( $this->CI->form_validation->run() !== false ) {
                
                $network_details = $this->CI->stream_networks_model->get_network_data('gmail', $this->CI->user_id, $stream[0]->network_id);
                
                if ( $network_details ) {
                    
                    switch ( $type ) {
                        
                        case 'mail': 
                            
                            $token = '';

                            if ( $network_details[0]->secret ) {

                                $response = json_decode(post('https://www.googleapis.com/oauth2/v4/token', array('client_id' => get_option('gmail_client_id'), 'client_secret' => get_option('gmail_client_secret'), 'refresh_token' => $network_details[0]->secret, 'grant_type' => 'refresh_token')), true);

                                if ( $response ) {

                                    $token = $response['access_token'];

                                }

                            }
                
                            $response = json_decode(get('https://www.googleapis.com/gmail/v1/users/' . $network_details[0]->net_id . '/messages/' . $id . '?format=full&access_token=' . $token), true);

                            $subject = '';
                            $to = '';
                            $from = '';

                            foreach ( $response['payload']['headers'] as $header ) {

                                if ( $header['name'] === 'Subject' ) {

                                    $subject = '<h5 class="mb-3">' . $header['value'] . '</h5>';

                                } else if ( $header['name'] === 'To' ) {

                                    $to = $header['value'];

                                } else if ( $header['name'] === 'From' ) {

                                    $from = $header['value'];

                                }

                            }
                            
                            $content = base64_decode(str_replace(array('-', '_'), array('+', '/'), $response['payload']['parts'][0]['body']['data']));
                                
                            $post_data = '<div class="col-xl-12 stream-simgle-item-template">'
                                            . $subject
                                            . '<p>'
                                                . '<p>' . implode('</p><p>', array_filter(explode("\n", $content))) . '</p>'
                                            . '</p>'
                                            . '<p>'
                                                . '<p><br><i class="fas fa-envelope"></i> <em>' . str_replace(array('<', '>'), '', $from) . '</em></p>'
                                            . '</p>'                                    
                                        . '</div>';

                            $data = array(
                                'success' => TRUE,
                                'content' => $post_data,
                                'form' => form_open('user/app/stream', ['class' => 'stream-send-react', 'data-csrf' => $this->CI->security->get_csrf_token_name()])
                                            . '<div class="form-group">'
                                                . '<input type="text" name="subject" value="' . strip_tags($subject) . '" class="form-control" placeholder="' . $this->CI->lang->line('subject') . '">'
                                            . '</div>'                                
                                            . '<div class="form-group">'
                                                . '<textarea name="message" class="form-control" rows="3" placeholder="' . $this->CI->lang->line('message') . '"></textarea>'
                                            . '</div>'
                                            . '<input type="hidden" name="to" value="' . base64_encode($to) . '">'
                                            . '<input type="hidden" name="from" value="' . base64_encode($from) . '">'                                
                                            . '<input type="hidden" name="id" value="' . $id . '">'
                                            . '<input type="hidden" name="stream_id" value="' .  $stream[0]->stream_id . '">'
                                            . '<input type="hidden" name="type" value="email">'                                    
                                            . '<button type="submit" class="btn btn-primary">' . $this->CI->lang->line('submit') . '</button>'
                                        . form_close(),
                                'menu_text' => $this->CI->lang->line('email')
                            );

                            echo json_encode($data);  
                            exit();                  
                            
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
     * @since 0.0.7.5
     * 
     * @return void
     */ 
    public function stream_update_setup() {
        
        // Check if data was submitted
        if ($this->CI->input->post()) {
            
            // Add form validation
            $this->CI->form_validation->set_rules('name', 'Name', 'trim|required');
            $this->CI->form_validation->set_rules('stream-template', 'Template Name', 'trim|required');
            $this->CI->form_validation->set_rules('stream-id', 'Stream ID', 'trim|required');
            
            // Get data
            $name = $this->CI->input->post('name');
            $template_name = $this->CI->input->post('stream-template');
            $stream_id = $this->CI->input->post('stream-id');
            
            
            if ( $this->CI->form_validation->run() !== false ) {
                
                $stream = $this->CI->stream_tabs_model->verify_stream_owner( $this->CI->user_id, $stream_id );
                
                // Verify if stream's id is of the current user 
                if ( $stream ) {
                    
                    // Get the list with stream's setups
                    $get_setup = (new MidrubBaseUserAppsCollectionStreamHelpers\Streams_setup)->streams_setup_get($stream_id, 'inbox');
                    
                    // Verify if setup exists
                    if ( $get_setup ) {
                        
                        // Delete stream's setup
                        $stream_setup = (new MidrubBaseUserAppsCollectionStreamHelpers\Streams_setup)->streams_setup_delete($get_setup[0]->setup_id, $stream_id, $template_name);; 
                        
                    } else {
                        
                        // Save the stream's setup
                        $stream_setup = (new MidrubBaseUserAppsCollectionStreamHelpers\Streams_setup)->streams_setup_save($stream_id, $template_name, $name);                         
                        
                    }

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
     * @since 0.0.7.5
     * 
     * @return void
     */ 
    public function stream_get_setup( $stream ) {
        
        $network_details = $this->CI->stream_networks_model->get_network_data('gmail', $this->CI->user_id, $stream[0]->network_id);
        
        $setup_data = '';
        
        if ( $network_details ) {
            
            $icon = '';

            $get_icon = (new MidrubBaseUserAppsCollectionStreamHelpers\Accounts)->get_network_info( 'gmail' );

            if ( $get_icon ) {

                $icon = $get_icon['icon'];

            }
            
            $setup_data = '<div class="row">'
                            . '<div class="col-xl-12 mb-3">'
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
            
            $get_setup = (new MidrubBaseUserAppsCollectionStreamHelpers\Streams_setup)->streams_setup_get($stream[0]->stream_id, 'inbox');
            
            $checked = '';
            
            if ( $get_setup ) {
                $checked = ' checked';
            }
            
            $setup_data .= '<div class="row">'
                            . '<div class="col-xl-12 mb-3">'
                                . '<div class="row clean connected-account">'
                                    . '<div class="col-xl-8 col-8">'
                                        . '<p>'
                                            . '<i class="icon-envelope"></i> ' . $this->CI->lang->line('only_unread')
                                        . '</p>'
                                    . '</div>'
                                    . '<div class="col-xl-4 col-4 text-right">'
                                        . '<div class="checkbox-option pull-right">'
                                            . '<input id="only-unread" name="only-unread" data-template="inbox" class="stream-setup-checkbox" type="checkbox"' . $checked . '>'
                                            . '<label for="only-unread"></label>'
                                        . '</div>'
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
        $active_accounts = (new MidrubBaseUserAppsCollectionStreamHelpers\Accounts)->get_active_accounts($this->CI->stream_networks_model->get_accounts_by_network( $this->CI->user_id, 'gmail', 1 ), 1);   
        
        $icon = '';
        
        $get_icon = (new MidrubBaseUserAppsCollectionStreamHelpers\Accounts)->get_network_info( 'gmail' );
        
        if ( $get_icon ) {
            
            $icon = $get_icon['icon'];
            
        }
        
        return array (
            'active_accounts' => $active_accounts,
            'expired_accounts' => '',
            'network' => 'gmail',
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
        
        // Check if data was submitted
        if ($this->CI->input->post()) {
            
            // Add form validation
            $this->CI->form_validation->set_rules('type', 'Type', 'trim|required');
            $this->CI->form_validation->set_rules('id', 'ID', 'trim|required');
            $this->CI->form_validation->set_rules('subject', 'Subject', 'trim|required');
            $this->CI->form_validation->set_rules('message', 'Message', 'trim|required');
            $this->CI->form_validation->set_rules('to', 'To', 'trim|required');
            $this->CI->form_validation->set_rules('from', 'From', 'trim|required');
            
            // Get data
            $type = $this->CI->input->post('type');
            $id = $this->CI->input->post('id');
            $subject = $this->CI->input->post('subject');
            $message = $this->CI->input->post('message');
            $to = $this->CI->input->post('to');
            $from = $this->CI->input->post('from');

            if ( $this->CI->form_validation->run() !== false ) {
                
                $network_details = $this->CI->stream_networks_model->get_network_data('gmail', $this->CI->user_id, $stream[0]->network_id);
                
                if ( $network_details ) {
                
                    switch ( $type ) {

                        case 'email':
                            
                            // Get the Gmail's client_id
                            $this->clientId = get_option('gmail_client_id');

                            // Get the Gmail's client_secret
                            $this->clientSecret = get_option('gmail_client_secret');

                            // Get the Gmail's api key
                            $this->apiKey = get_option('gmail_api_key');

                            // Get the Gmail's application name
                            $this->appName = get_option('gmail_google_application_name');

                            // Require the  vendor's libraries
                            require_once FCPATH . 'vendor/autoload.php';
                            require_once FCPATH . 'vendor/google/apiclient-services/src/Google/Service/Gmail.php';

                            // Gmail Callback
                            $this->scriptUri = base_url() . 'user/callback/gmail';

                            // Call the class Google_Client
                            $this->client = new \Google_Client();

                            // Name of the google application
                            $this->client->setApplicationName($this->appName);

                            // Set the client_id
                            $this->client->setClientId($this->clientId);

                            // Set the client_secret
                            $this->client->setClientSecret($this->clientSecret);

                            // Redirects to same url
                            $this->client->setRedirectUri($this->scriptUri);

                            // Set the api key
                            $this->client->setDeveloperKey($this->apiKey);
                            
                            $token = '';

                            if ( $network_details[0]->secret ) {

                                $response = json_decode(post('https://www.googleapis.com/oauth2/v4/token', array('client_id' => $this->clientId, 'client_secret' => $this->clientSecret, 'refresh_token' => $network_details[0]->secret, 'grant_type' => 'refresh_token')), true);

                                if ( $response ) {

                                    $token = $response['access_token'];

                                }

                            }

                            // Set access token
                            $this->client->setAccessToken($token);

                            $service = new \Google_Service_Gmail($this->client);
                            
                            $boundary = uniqid(rand(), true);
                            $subjectCharset = $charset = 'utf-8';
                            $strSubject = $subject;

                            $strRawMessage = 'To: ' . base64_decode($from) . "\r\n";
                            $strRawMessage .= 'From: ' . base64_decode($to) . "\r\n";

                            $strRawMessage .= 'Subject: =?' . $subjectCharset . '?B?' . base64_encode($strSubject) . "?=\r\n";
                            $strRawMessage .= 'MIME-Version: 1.0' . "\r\n";
                            $strRawMessage .= 'Content-type: Multipart/Alternative; boundary="' . $boundary . '"' . "\r\n";

                            $strRawMessage .= "\r\n--{$boundary}\r\n";
                            $strRawMessage .= 'Content-Type: text/plain; charset=' . $charset . "\r\n";
                            $strRawMessage .= 'Content-Transfer-Encoding: 7bit' . "\r\n\r\n";
                            $strRawMessage .= "this is a test!" . "\r\n";

                            $strRawMessage .= "--{$boundary}\r\n";
                            $strRawMessage .= 'Content-Type: text/html; charset=' . $charset . "\r\n";
                            $strRawMessage .= 'Content-Transfer-Encoding: quoted-printable' . "\r\n\r\n";
                            $strRawMessage .= $message . "\r\n";

                            //Send Mails
                            //Prepare the message in message/rfc822
                            try {
                                // The message needs to be encoded in Base64URL
                                $mime = rtrim(strtr(base64_encode($strRawMessage), '+/', '-_'), '=');
                                $msg = new \Google_Service_Gmail_Message();
                                $msg->setRaw($mime);
                                $service->users_messages->send('me', $msg);
                                
                                $data = array(
                                    'success' => TRUE,
                                    'message' => $this->CI->lang->line('email_was_sent')
                                );

                                echo json_encode($data);                                
                                
                            } catch (Exception $e) {
                                
                                $data = array(
                                    'success' => FALSE,
                                    'message' => $this->CI->lang->line('email_was_not_sent')
                                );

                                echo json_encode($data);                                
                                
                            }
                            
                            exit();

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
            'displayed_name' => $this->CI->lang->line('inbox'),
            'icon' => '<i class="fas fa-at"></i>',
            'description' => $this->CI->lang->line('inbox_description'),
            'parent' => 'gmail'
        );
        
    }
    
}

/* End of file Inbox.php */
