<?php
/**
 * Follow Ebay Store Template
 *
 * This file contains the Stream's Follow Ebay Store template 
 *
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.8.0
 */

// Define the page namespace
namespace MidrubBase\User\Apps\Collection\Stream\Templates;

// Constants
defined('BASEPATH') OR exit('No direct script access allowed');

// Define the namespaces to use
use MidrubBase\User\Apps\Collection\Stream\Interfaces as MidrubBaseUserAppsCollectionStreamInterfaces;
use MidrubBase\User\Apps\Collection\Stream\Helpers as MidrubBaseUserAppsCollectionStreamHelpers;

/*
 * Follow_a_store contains the Stream's Follow Ebay Store template 
 * 
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.8.0
 */
class Follow_a_store implements MidrubBaseUserAppsCollectionStreamInterfaces\Stream {
    
    /**
     * Class variables
     *
     * @since 0.0.8.0
     */
    protected
            $CI, $api_url = 'https://api.ebay.com/', $redirect_uri, $client_id, $client_secret;

    /**
     * Initialise the Class
     *
     * @since 0.0.8.0
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
     * @since 0.0.8.0
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

                // Get network details
                $network_details = $this->CI->stream_networks_model->get_network_data('ebay', $this->CI->user_id, $stream[0]->network_id);

                // Verify if user has permissions to manage the stream
                if ($network_details) {

                    switch ($item_type) {

                        case 'ebay_website':

                            // Get stream's setup
                            $get_setup = (new MidrubBaseUserAppsCollectionStreamHelpers\Streams_setup)->streams_setup_get($stream_id, 'ebay_website');

                            if ($get_setup) {

                                // Save the stream's setup
                                $stream_setup = $this->CI->stream_setup_model->stream_update_setup($get_setup[0]->setup_id, 'ebay_website', $item_id);
                            } else {

                                // Save the stream's setup
                                $stream_setup = (new MidrubBaseUserAppsCollectionStreamHelpers\Streams_setup)->streams_setup_save($stream_id, 'ebay_website', $item_id);
                            }

                            if ($stream_setup) {

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
     * @since 0.0.8.0
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
     * @since 0.0.8.0
     * 
     * @return array with tempate's content
     */ 
    public function template_content( $stream, $return = FALSE ) {
        
        // Get the template's info
        $template_info = $this->template_info();
        
        // Default value for custom template variables
        $border_bottom_color = '';
        $paragraph_color = '';
        $icons_color = '';
        $links_color = '';
        
        // Verify if user has changed the border's color
        if ( $stream->border_color ) {
            $border_bottom_color = ' style="border-bottom-color: ' . $stream->border_color . '"';
        } 
        
        // Verify if user has changed the text color
        if ( $stream->item_text_color ) {
            $paragraph_color = ' style="color: ' . $stream->item_text_color . '"';
        }
        
        // Verify if user has changed the icons color
        if ( $stream->icons_color ) {
            $icons_color = ' style="color: ' . $stream->icons_color . '"';
        }

        // Verify if user has changed the link color
        if ( $stream->links_color ) {
            $links_color = ' style="color: ' . $stream->links_color . '"';
        }

        // Default store
        $store = 'xxx';

        // Get the stream's setup
        $get_setup = (new MidrubBaseUserAppsCollectionStreamHelpers\Streams_setup)->streams_setup_get($stream->stream_id, 'ebay_store');

        // Verify if setup exists
        if ( $get_setup ) {

            // Set store
            $store = $get_setup[0]->setup_option;

        }

        // Default keywords
        $keywords = 'xxx';

        // Get the stream's setup
        $get_setup = (new MidrubBaseUserAppsCollectionStreamHelpers\Streams_setup)->streams_setup_get($stream->stream_id, 'ebay_keywords');

        // Verify if setup exists
        if ( $get_setup ) {

            // Set keywords
            $keywords = $get_setup[0]->setup_option;

        }

        // Prepare xml
        $xml = '<?xml version="1.0" encoding="UTF-8"?>'
            . '<findItemsIneBayStoresRequest xmlns="http://www.ebay.com/marketplace/search/v1/services">'
                . '<keywords>' . $keywords . '</keywords>'
                . '<storeName>' . $store . '</storeName>'
                . '<outputSelector>StoreInfo</outputSelector>'
                . '<sortOrder>StartTimeNewest</sortOrder>'
                . '<paginationInput>'
                    . '<entriesPerPage>10</entriesPerPage>'
                . '</paginationInput>'
            . '</findItemsIneBayStoresRequest>';

        // Prepare header's params
        $header = array(
            'X-EBAY-SOA-SECURITY-APPNAME:' . get_option('ebay_app_id'),
            'X-EBAY-SOA-OPERATION-NAME:' . 'findItemsIneBayStores'
        );

        // Prepare curl
        $connection = curl_init();
        curl_setopt($connection, CURLOPT_URL, 'https://svcs.ebay.com/services/search/FindingService/v1');
        curl_setopt($connection, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($connection, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($connection, CURLOPT_HTTPHEADER, $header);
        curl_setopt($connection, CURLOPT_POST, 1);
        curl_setopt($connection, CURLOPT_POSTFIELDS, $xml);
        curl_setopt($connection, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($connection);
        curl_close($connection);

        // All items variable
        $all_items = '';

        // Items variable
        $items = (object) array();

        // Ids array for items count
        $ids = array();

        // Verified if response exists
        if ($response) {

            // Decode response
            $xml = simplexml_load_string($response);

            // Verify if response is correct
            if ( $xml->searchResult ) {
               
                // Set items
                $items = $xml->searchResult;

            }

        }

        // Verify if the items object is not empty
        if ( property_exists($items, 'item') ) {
            
            // Get last actions
            $actions = $this->CI->stream_history_model->get_item_actions($stream->stream_id);
            
            // All actions array
            $all_actions = array();
            
            // If actions exists add the to the all_actions array
            if ( $actions ) {
                
                // List all actions
                foreach ( $actions as $action ) {
                    
                    // Add action to the all_actions array
                    $all_actions[$action->type . '-' . $action->id] = $action->value;
                    
                }
                
            }
            
            // List all items
            foreach ( $items->item as $item ) {

                // Set id
                $ids[] = (string)$item->itemId[0];

                // Image variable
                $image = '';

                // Verify if image exists
                if ( $item->galleryURL[0] ) {
                    $image = '<p data-type="stream-item-media"><img src="' . $item->galleryURL[0] . '"></p>';
                }

                // Create the item's content
                $all_items .= '<li class="row"' . $border_bottom_color . '>
                                <div class="col-xl-12">
                                    <div class="stream-post full-width">
                                        <small class="clean"' . $icons_color . '>
                                            ' . '<i class="icon-clock"></i>' . calculate_time(strtotime($item->listingInfo->startTime[0]), time()) . '
                                        </small>
                                        ' . $image . '
                                        <p' . $paragraph_color . ' data-type="stream-item-content">
                                            <a href="' . htmlspecialchars($item->viewItemURL[0]) . '" target="_blank"' . $links_color . '>
                                                ' . $item->title[0] . '
                                            </a>
                                        </p>   
                                        <div class="stream-post-footer"> 
                                            <a href="#stream-item-share" data-toggle="modal" class="stream-item-share" data-stream="' . $stream->stream_id . '" data-type="share" data-id="' . $item->itemId[0] . '"' . $icons_color . '>
                                                <i class="icon-paper-plane"></i>
                                            </a> 
                                        </div>
                                    </div>
                                </div>
                            </li>';
                
            }
            
        } else {

            // Default error message
            $error =  $this->CI->lang->line('no_results_found');

            // Verify if error message exists
            if ( @$xml->errorMessage->error->message[0] ) {
                $error = (string) $xml->errorMessage->error->message[0];
            }
            
            // If no items display this
            $all_items .= '<li class="row"' . $border_bottom_color . '>'
                            . '<div class="col-xl-12">'
                                . '<div>'
                                    . '<p>' . $error . '</p>'
                                . '</div>'
                            . '</div>'
                        . '</li>';
            
        }
        
        // Active variable 
        $active = '';

        // Active check
        $active_check = 0;

        // Sound variable
        $sound = '';
        
        // Get the stream's cronology
        $cronology = $this->CI->stream_history_model->get_stream_cronology($stream->stream_id);
        
        // Verify if the cronology
        if ( !$cronology ) {
        
            // Update the Stream's cronology
            $this->CI->stream_history_model->update_stream_cronology($stream->stream_id, serialize($ids));
            
        } else {
            
            // Verify if there are new items
            if ( $cronology[0]->value !== serialize($ids) ) {
                
                // Save new items ids
                $this->CI->stream_history_model->update_stream_cronology($stream->stream_id, serialize($ids));
                
                // Update the Stream's status
                if ( $this->CI->stream_tabs_model->update_stream_field($stream->stream_id, 'new_event', 1) ) {

                    $active_check++;
                    
                    // If sound exists will play one
                    if ( file_exists(FCPATH . '/assets/sounds/' . $stream->alert_sound . '.mp3') ) {

                        // Call the mp3
                        $sound = '<iframe src="' . base_url('/assets/sounds/' . $stream->alert_sound . '.mp3') . '" class="d-none" allow="autoplay" id="audio"></iframe>';
                        
                    }
                    
                }
                
            }
            
        }
        
        // Verify if the stream has new items
        if ( $active_check ) {
        
            $active = ' stream-mark-seen-item-active';
            
        } else if ( $stream->new_event ) {
            
            $active = ' stream-mark-seen-item-active';
            
        }
        
        $stream_data = array (
            'header' => '<i style="color: #febf2c;" class="fab fa-ebay"></i>'
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
                            . $all_items
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
     * @since 0.0.8.0
     * 
     * @return array with stream's item content
     */ 
    public function template_content_single( $stream ) {

    }
    
    /**
     * The public method stream_update_setup saves stream's setup
     * 
     * @since 0.0.8.0
     * 
     * @return void
     */ 
    public function stream_update_setup() {
       
        // Check if data was submitted
        if ($this->CI->input->post()) {
            
            // Add form validation
            $this->CI->form_validation->set_rules('store', 'Store', 'trim|required');
            $this->CI->form_validation->set_rules('keywords', 'Keywords', 'trim|required');
            $this->CI->form_validation->set_rules('stream-id', 'Stream ID', 'trim|required');
            
            // Get data
            $store = $this->CI->input->post('store');
            $keywords = $this->CI->input->post('keywords');
            $stream_id = $this->CI->input->post('stream-id');

            // Verify if submitted form data is correct
            if ( $this->CI->form_validation->run() !== false ) {
                
                // Get stream owner
                $stream = $this->CI->stream_tabs_model->verify_stream_owner( $this->CI->user_id, $stream_id );
                
                // Verify if stream's id is of the current user 
                if ( $this->CI->stream_tabs_model->verify_stream_owner( $this->CI->user_id, $stream_id ) ) {
                    
                    // Get the list with stream's setups
                    $get_setup = (new MidrubBaseUserAppsCollectionStreamHelpers\Streams_setup)->streams_setup_get($stream_id, 'ebay_store');

                    // Verify if store exists
                    if ( $store ) {

                        // Verify if setup exists
                        if ($get_setup) {

                            // Save the stream's setup
                            $this->CI->stream_setup_model->stream_update_setup($get_setup[0]->setup_id, 'ebay_store', $store);

                        } else {

                            // Save the stream's setup
                           (new MidrubBaseUserAppsCollectionStreamHelpers\Streams_setup)->streams_setup_save($stream_id, 'ebay_store', $store);

                        }

                    }

                    // Verify if keywords exists
                    if ( $keywords ) {

                        // Verify if setup exists
                        if ($get_setup) {

                            // Save the stream's setup
                            $this->CI->stream_setup_model->stream_update_setup($get_setup[0]->setup_id, 'ebay_keywords', $keywords);

                        } else {

                            // Save the stream's setup
                           (new MidrubBaseUserAppsCollectionStreamHelpers\Streams_setup)->streams_setup_save($stream_id, 'ebay_keywords', $keywords);

                        }

                    }

                    // Get stream's data
                    $stream = $this->CI->stream_tabs_model->verify_stream_owner($this->CI->user_id, $stream_id);

                    // Reload stream's setup
                    $this->stream_get_setup($stream);
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
     * @since 0.0.8.0
     * 
     * @return void
     */ 
    public function stream_get_setup( $stream ) {
        
        // Get network's account
        $network_details = $this->CI->stream_networks_model->get_network_data('ebay', $this->CI->user_id, $stream[0]->network_id);
        
        // Default setup data
        $setup_data = '';
        
        // Verify if network's account exists
        if ( $network_details ) {
            
            // Default icon value
            $icon = '';

            // Get network's icon
            $get_icon = (new MidrubBaseUserAppsCollectionStreamHelpers\Accounts)->get_network_info( 'ebay' );

            // Verify if icon exists
            if ( $get_icon ) {

                // Set icon
                $icon = $get_icon['icon'];

            }
            
            // Display data
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
                            . '<div class="col-xl-12">'
                                . '<div class="input-group stream-setup-hastags-list-save">'
                                    . '<div class="input-group-prepend">'
                                        . '<i class="fab fa-slack-hash"></i>'
                                    . '</div>'
                                    . '<input type="text" name="store" class="form-control input-group stream-setup-hastags-list-enter-keywords" maxlength="100" placeholder="' . $this->CI->lang->line('enter_a_store_name') . '" required>'
                                . '</div>'
                            . '</div>'
                        . '</div>';

        $words = '<li class="no-records-found">'
                . $this->CI->lang->line('no_store_found')
            . '</li>';
                    
        $get_setup = (new MidrubBaseUserAppsCollectionStreamHelpers\Streams_setup)->streams_setup_get($stream[0]->stream_id, 'ebay_store');
        
        if ( $get_setup ) {
            
            $words = '';
            
            foreach ( $get_setup as $setup ) {
                
                $words .= '<li>'
                                . '<a href="#" class="delete-stream-setup" data-id="' . $setup->setup_id . '" data-template="follow_a_store" data-stream-id="' . $stream[0]->stream_id . '">'
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
                        . '<div class="row stream-setup-hastags-list">'
                            . '<div class="col-12">'
                                . '<div class="input-group stream-setup-hastags-list-save">'
                                    . '<div class="input-group-prepend">'
                                        . '<i class="fab fa-slack-hash"></i>'
                                    . '</div>'
                                    . '<input type="text" name="keywords" class="form-control input-group stream-setup-hastags-list-enter-keywords" maxlength="50" placeholder="' . $this->CI->lang->line('enter_the_keywords') . '" required>'
                                . '</div>'
                            . '</div>'
                        . '</div>';

        $words = '<li class="no-records-found">'
                . $this->CI->lang->line('no_keywords_found')
            . '</li>';
                    
        $get_setup = (new MidrubBaseUserAppsCollectionStreamHelpers\Streams_setup)->streams_setup_get($stream[0]->stream_id, 'ebay_keywords');
        
        if ( $get_setup ) {
            
            $words = '';
            
            foreach ( $get_setup as $setup ) {
                
                $words .= '<li>'
                                . '<a href="#" class="delete-stream-setup" data-id="' . $setup->setup_id . '" data-template="follow_a_store" data-stream-id="' . $stream[0]->stream_id . '">'
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
                        . '</div>';

        $setup_data .= '<div class="row">'
                    . '<div class="col-12">'
                        . '<button type="submit" class="btn btn-success">'
                            . '<i class="far fa-save"></i> ' . $this->CI->lang->line('save')
                        . '</button>'
                    . '</div>'
                . '</div>';                        
        
        $setup_data .= '<input type="hidden" name="stream-template" value="follow_a_store">'
                        . '<input type="hidden" name="stream-id" value="' . $stream[0]->stream_id . '">'
                        . '<input type="hidden" name="slug" value="ebay_keywords">'
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
     * @since 0.0.8.0
     * 
     * @return void
     */ 
    public function stream_delete_setup() {
        
        // Check if data was submitted
        if ($this->CI->input->post()) {
            
            // Add form validation
            $this->CI->form_validation->set_rules('setup_id', 'Setup ID', 'trim|required');
            $this->CI->form_validation->set_rules('stream_id', 'Stream ID', 'trim|required');
            
            // Get data
            $setup_id = $this->CI->input->post('setup_id');
            $stream_id = $this->CI->input->post('stream_id');
            
            if ( $this->CI->form_validation->run() !== false ) {
                
                // Verify if stream's id is of the current user 
                if ( $this->CI->stream_tabs_model->verify_stream_owner( $this->CI->user_id, $stream_id ) ) {

                    // Delete the keywords
                    (new MidrubBaseUserAppsCollectionStreamHelpers\Streams_setup)->streams_setup_delete($setup_id, $stream_id, 'ebay_keywords');

                    // Delete the store
                    (new MidrubBaseUserAppsCollectionStreamHelpers\Streams_setup)->streams_setup_delete($setup_id, $stream_id, 'ebay_store');

                    $stream = $this->CI->stream_tabs_model->verify_stream_owner($this->CI->user_id, $stream_id);

                    $this->stream_get_setup($stream);
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
     * The public method template_connect contains the template's connection settings
     * 
     * @since 0.0.8.0
     * 
     * @return array with settings
     */ 
    public function template_connect() {
        
        // Load language
        $this->CI->lang->load( 'stream_instructions', $this->CI->config->item('language'), FALSE, TRUE, MIDRUB_BASE_USER_APPS_STREAM );
        
        // Get Active Accounts
        $active_accounts = (new MidrubBaseUserAppsCollectionStreamHelpers\Accounts)->get_active_accounts($this->CI->stream_networks_model->get_accounts_by_network( $this->CI->user_id, 'ebay', 1 ), 1);   
        
        // Default network's value
        $icon = '';
        
        // Get network's icon
        $get_icon = (new MidrubBaseUserAppsCollectionStreamHelpers\Accounts)->get_network_info( 'ebay' );
        
        // Verify if the network exists
        if ( $get_icon ) {
            
            // Set network
            $icon = $get_icon['icon'];
            
        }
        
        // Connection array
        return array (
            'active_accounts' => $active_accounts,
            'expired_accounts' => '',
            'network' => 'ebay',
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
     * @since 0.0.8.0
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
     * @since 0.0.8.0
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
     * @since 0.0.8.0
     * 
     * @return void
     */ 
    public function stream_extra($type, $data) {
        
    }
    
    /**
     * The public method template_info contains the template's information
     * 
     * @since 0.0.8.0
     * 
     * @return array with tempate's information
     */ 
    public function template_info() {
        
        return array (
            'displayed_name' => $this->CI->lang->line('follow_a_store'),
            'icon' => '<i class="fab fa-ebay"></i>',
            'description' => $this->CI->lang->line('follow_a_store_description'),
            'parent' => 'ebay'
        );
        
    }
    
}

/* End of file follow_a_store.php */
