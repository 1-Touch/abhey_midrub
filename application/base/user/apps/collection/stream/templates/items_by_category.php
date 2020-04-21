<?php
/**
 * Items By Ebay Category Template
 *
 * This file contains the Stream's Items By Ebay Category template 
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
 * Items_by_category contains the Stream's Items By Ebay Category template 
 * 
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.8.0
 */
class Items_by_category implements MidrubBaseUserAppsCollectionStreamInterfaces\Stream {
    
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

                                // Get stream's setup
                                $get_setup = (new MidrubBaseUserAppsCollectionStreamHelpers\Streams_setup)->streams_setup_get($stream_id, 'ebay_category');

                                if ($get_setup) {

                                    // Deletes the category
                                    $this->CI->stream_setup_model->streams_delete_setup($get_setup[0]->setup_id, $stream_id, 'ebay_category');

                                }

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

                        case 'ebay_category':

                            // Get account's data based on network's id and user's id
                            $network_details = $this->CI->stream_networks_model->get_network_data('ebay', $this->CI->user_id, $stream[0]->network_id);

                            // First of all the access token should be refreshed and below is prepeared the post's fields
                            $post_fields = "grant_type=refresh_token&refresh_token=" . $network_details[0]->secret . "&scope=https://api.ebay.com/oauth/api_scope/sell.marketing.readonly https://api.ebay.com/oauth/api_scope/sell.marketing https://api.ebay.com/oauth/api_scope/sell.inventory.readonly https://api.ebay.com/oauth/api_scope/sell.inventory https://api.ebay.com/oauth/api_scope/sell.account.readonly https://api.ebay.com/oauth/api_scope/sell.account https://api.ebay.com/oauth/api_scope/sell.fulfillment.readonly https://api.ebay.com/oauth/api_scope/sell.fulfillment https://api.ebay.com/oauth/api_scope/sell.analytics.readonly";

                            // Prepare header's params
                            $header = array(
                                "Content-Type: application/x-www-form-urlencoded",
                                "Authorization: Basic " . base64_encode(get_option('ebay_app_id') . ':' . get_option('ebay_cert_id'))
                            );

                            // Refresh token
                            $curl = curl_init();
                            curl_setopt($curl, CURLOPT_URL, 'https://api.ebay.com/identity/v1/oauth2/token');
                            curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
                            curl_setopt($curl, CURLOPT_POST, true);
                            curl_setopt($curl, CURLOPT_POSTFIELDS, $post_fields);
                            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
                            $curl_response = json_decode(curl_exec($curl), true);
                            curl_close($curl);

                            // Verify if access token exists
                            if (isset($curl_response['access_token'])) {

                                // Default website
                                $website = 0;

                                // Get the stream's setup
                                $get_setup = (new MidrubBaseUserAppsCollectionStreamHelpers\Streams_setup)->streams_setup_get($stream[0]->stream_id, 'ebay_website');

                                // Verify if setup exists
                                if ($get_setup) {

                                    // Set eBay's website
                                    $website = $get_setup[0]->setup_option;
                                }

                                // Create the header
                                $header = array(
                                    'X-EBAY-API-COMPATIBILITY-LEVEL: 967',
                                    'X-EBAY-API-IAF-TOKEN: ' . $curl_response['access_token'],
                                    'X-EBAY-API-CALL-NAME: GetCategories',
                                    'X-EBAY-API-SITEID: ' . $website
                                );

                                // Set body
                                $xml = '<?xml version="1.0" encoding="utf-8"?>'
                                . '<GetCategoriesRequest xmlns="urn:ebay:apis:eBLBaseComponents">'
                                    . '<ErrorLanguage>en_US</ErrorLanguage>'
                                    . '<WarningLevel>High</WarningLevel>'
                                    . '<DetailLevel>ReturnAll</DetailLevel>'
                                    . '<ViewAllNodes>true</ViewAllNodes>'
                                . '</GetCategoriesRequest>';

                                // Prepare curl
                                $connection = curl_init();
                                curl_setopt($connection, CURLOPT_URL, $this->api_url . 'ws/api.dll');
                                curl_setopt($connection, CURLOPT_SSL_VERIFYPEER, 0);
                                curl_setopt($connection, CURLOPT_SSL_VERIFYHOST, 0);
                                curl_setopt($connection, CURLOPT_HTTPHEADER, $header);
                                curl_setopt($connection, CURLOPT_POST, 1);
                                curl_setopt($connection, CURLOPT_POSTFIELDS, $xml);
                                curl_setopt($connection, CURLOPT_RETURNTRANSFER, 1);
                                $response = curl_exec($connection);
                                curl_close($connection);

                                // Verified if response exists
                                if ($response) {

                                    // Decaode the response
                                    $xml = simplexml_load_string($response);

                                    // Verify if categories exists
                                    if ($xml->CategoryArray) {

                                        // All categories array
                                        $all_categories = array();

                                        // List all categories
                                        for ($c = 0; $c < count($xml->CategoryArray->Category); $c++) {

                                            // Verify if category's id is same
                                            if ($item_id === (string) $xml->CategoryArray->Category[$c]->CategoryID[0]) {

                                                // Get stream's setup
                                                $get_setup = (new MidrubBaseUserAppsCollectionStreamHelpers\Streams_setup)->streams_setup_get($stream_id, 'ebay_category');

                                                if ($get_setup) {

                                                    // Save the stream's setup
                                                    $stream_setup = $this->CI->stream_setup_model->stream_update_setup($get_setup[0]->setup_id, 'ebay_category', $item_id, (string) $xml->CategoryArray->Category[$c]->CategoryName[0]);
                                                } else {

                                                    // Save the stream's setup
                                                    $stream_setup = (new MidrubBaseUserAppsCollectionStreamHelpers\Streams_setup)->streams_setup_save($stream_id, 'ebay_category', $item_id, (string) $xml->CategoryArray->Category[$c]->CategoryName[0]);
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

                                }

                            }

                            $data = array(
                                'success' => FALSE,
                                'message' => $this->CI->lang->line('your_option_was_not_saved')
                            );

                            echo json_encode($data);

                            exit();

                        case 'search-categories':

                            // Get account's data based on network's id and user's id
                            $network_details = $this->CI->stream_networks_model->get_network_data('ebay', $this->CI->user_id, $stream[0]->network_id);

                            // First of all the access token should be refreshed and below is prepeared the post's fields
                            $post_fields = "grant_type=refresh_token&refresh_token=" . $network_details[0]->secret . "&scope=https://api.ebay.com/oauth/api_scope/sell.marketing.readonly https://api.ebay.com/oauth/api_scope/sell.marketing https://api.ebay.com/oauth/api_scope/sell.inventory.readonly https://api.ebay.com/oauth/api_scope/sell.inventory https://api.ebay.com/oauth/api_scope/sell.account.readonly https://api.ebay.com/oauth/api_scope/sell.account https://api.ebay.com/oauth/api_scope/sell.fulfillment.readonly https://api.ebay.com/oauth/api_scope/sell.fulfillment https://api.ebay.com/oauth/api_scope/sell.analytics.readonly";

                            // Prepare header's params
                            $header = array(
                                "Content-Type: application/x-www-form-urlencoded",
                                "Authorization: Basic " . base64_encode(get_option('ebay_app_id') . ':' . get_option('ebay_cert_id'))
                            );

                            // Refresh token
                            $curl = curl_init();
                            curl_setopt($curl, CURLOPT_URL, 'https://api.ebay.com/identity/v1/oauth2/token');
                            curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
                            curl_setopt($curl, CURLOPT_POST, true);
                            curl_setopt($curl, CURLOPT_POSTFIELDS, $post_fields);
                            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
                            $curl_response = json_decode(curl_exec($curl), true);
                            curl_close($curl);

                            // Verify if access token exists
                            if (isset($curl_response['access_token'])) {

                                // Default website
                                $website = 0;

                                // Get the stream's setup
                                $get_setup = (new MidrubBaseUserAppsCollectionStreamHelpers\Streams_setup)->streams_setup_get($stream[0]->stream_id, 'ebay_website');

                                // Verify if setup exists
                                if ($get_setup) {

                                    // Set eBay's website
                                    $website = $get_setup[0]->setup_option;
                                }

                                // Create the header
                                $header = array(
                                    'X-EBAY-API-COMPATIBILITY-LEVEL: 967',
                                    'X-EBAY-API-IAF-TOKEN: ' . $curl_response['access_token'],
                                    'X-EBAY-API-CALL-NAME: GetCategories',
                                    'X-EBAY-API-SITEID: ' . $website
                                );

                                // Set body
                                $xml = '<?xml version="1.0" encoding="utf-8"?>'
                                . '<GetCategoriesRequest xmlns="urn:ebay:apis:eBLBaseComponents">'
                                    . '<ErrorLanguage>en_US</ErrorLanguage>'
                                    . '<WarningLevel>High</WarningLevel>'
                                    . '<DetailLevel>ReturnAll</DetailLevel>'
                                    . '<ViewAllNodes>true</ViewAllNodes>'
                                . '</GetCategoriesRequest>';

                                // Prepare curl
                                $connection = curl_init();
                                curl_setopt($connection, CURLOPT_URL, $this->api_url . 'ws/api.dll');
                                curl_setopt($connection, CURLOPT_SSL_VERIFYPEER, 0);
                                curl_setopt($connection, CURLOPT_SSL_VERIFYHOST, 0);
                                curl_setopt($connection, CURLOPT_HTTPHEADER, $header);
                                curl_setopt($connection, CURLOPT_POST, 1);
                                curl_setopt($connection, CURLOPT_POSTFIELDS, $xml);
                                curl_setopt($connection, CURLOPT_RETURNTRANSFER, 1);
                                $response = curl_exec($connection);
                                curl_close($connection);

                                // Verified if response exists
                                if ($response) {

                                    // Decaode the response
                                    $xml = simplexml_load_string($response);

                                    // Verify if categories exists
                                    if ($xml->CategoryArray) {

                                        // All categories array
                                        $all_categories = array();

                                        // List 10 categories
                                        for ($c = 0; $c < count($xml->CategoryArray->Category); $c++) {

                                            // Only 10 results
                                            if ($c > 9) {
                                                break;
                                            }

                                            if (!preg_match("/{$item_id}/i", (string) $xml->CategoryArray->Category[$c]->CategoryName[0])) {
                                                continue;
                                            }

                                            // Add category to list
                                            $all_categories[] = array(
                                                'value' => (string) $xml->CategoryArray->Category[$c]->CategoryID[0],
                                                'text' => (string) $xml->CategoryArray->Category[$c]->CategoryName[0]
                                            );
                                        }

                                        // Verify if categories were found
                                        if ($all_categories) {

                                            // Return categories
                                            $data = array(
                                                'success' => TRUE,
                                                'items' => $all_categories
                                            );

                                            echo json_encode($data);

                                        } else {

                                            // Display error message
                                            $data = array(
                                                'success' => FALSE,
                                                'message' => $this->CI->lang->line('no_results_found')
                                            );

                                            echo json_encode($data);
                                        }

                                    } else {

                                        // Display error message
                                        $data = array(
                                            'success' => FALSE,
                                            'message' => $this->CI->lang->line('no_results_found')
                                        );

                                        echo json_encode($data);

                                    }

                                }

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

        // Default category
        $category = '176984';

        // Get the stream's setup
        $get_setup = (new MidrubBaseUserAppsCollectionStreamHelpers\Streams_setup)->streams_setup_get($stream->stream_id, 'ebay_category');

        // Verify if setup exists
        if ( $get_setup ) {

            // Set category
            $category = $get_setup[0]->setup_option;

        }

        // Default website
        $website = 'EBAY-US';

        // Get the stream's setup
        $get_setup = (new MidrubBaseUserAppsCollectionStreamHelpers\Streams_setup)->streams_setup_get($stream->stream_id, 'ebay_website');

        // Verify if setup exists
        if ( $get_setup ) {

            switch ( $get_setup[0]->setup_option ) {

                case '0':

                    $website = 'EBAY-US';

                    break;

                case '2':

                    $website = 'EBAY-ENCA';

                    break;

                case '3':

                    $website = 'EBAY-GB';

                    break;

                case '15':

                    $website = 'EBAY-AU';

                    break;

                case '16':

                    $website = 'EBAY-AT';

                    break;

                case '23':

                    $website = 'EBAY-FRBE';

                    break;

                case '71':

                    $website = 'EBAY-FR';

                    break;

                case '77':

                    $website = 'EBAY-DE';
                    
                    break;

                case '100':

                    $website = 'EBAY-MOTOR';

                    break;

                case '101':

                    $website = 'EBAY-IT';

                    break;

                case '123':

                    $website = 'EBAY-NLBE';

                    break;

                case '146':

                    $website = 'EBAY-NL';

                    break;

                case '186':

                    $website = 'EBAY-ES';

                    break;

                case '193':

                    $website = 'EBAY-CH';

                    break;

                case '201':

                    $website = 'EBAY-HK';

                    break;

                case '203':

                    $website = 'EBAY-IN';

                    break;

                case '205':

                    $website = 'EBAY-IE';

                    break;

                case '207':

                    $website = 'EBAY-MY';

                    break;

                case '210':

                    $website = 'EBAY-FRCA';

                    break;

                case '211':

                    $website = 'EBAY-PH';

                    break;

                case '212':

                    $website = 'EBAY-PL';

                    break;

                case '216':

                    $website = 'EBAY-SG';

                    break;

            }

        }

        // Prepare xml
        $xml = '<?xml version="1.0" encoding="UTF-8"?>'
            . '<findItemsByCategoryRequest xmlns="http://www.ebay.com/marketplace/search/v1/services">'
            . '<categoryId>' . $category . '</categoryId>'
            . '<sortOrder>StartTimeNewest</sortOrder>'
            . '<outputSelector>PictureURLSuperSize</outputSelector>'
            . '<paginationInput>'
            . '<entriesPerPage>10</entriesPerPage>'
            . '</paginationInput>'
            . '</findItemsByCategoryRequest>';

        // Prepare header's params
        $header = array(
            'X-EBAY-SOA-SECURITY-APPNAME:' . get_option('ebay_app_id'),
            'X-EBAY-SOA-OPERATION-NAME:' . 'findItemsByCategory',
            'X-EBAY-SOA-GLOBAL-ID:' . $website
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
                if ( $item->pictureURLSuperSize[0] ) {
                    $image = '<p data-type="stream-item-media"><img src="' . $item->pictureURLSuperSize[0] . '"></p>';
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
            
            // If no items display this
            $all_items .= '<li class="row"' . $border_bottom_color . '>'
                            . '<div class="col-xl-12">'
                                . '<div>'
                                    . '<p>' . $this->CI->lang->line('no_results_found') . '</p>'
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
        
        // Template data
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
        
        // Prepare the template's content
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

        // Get the stream's setup
        $get_setup = (new MidrubBaseUserAppsCollectionStreamHelpers\Streams_setup)->streams_setup_get($stream[0]->stream_id, 'ebay_website');

        // Default website select
        $website = $this->CI->lang->line('select_ebay_website');

        // Verify if setup exists
        if ( $get_setup ) {

            switch( $get_setup[0]->setup_option ) {

                case '0':
                
                    $website = 'eBay United States';
    
                    break;

                case '2':
                
                    $website = 'eBay Canada (English)';
    
                    break;
    
                case '3':
                
                    $website = 'eBay UK';
    
                    break;
    
                case '15':
                
                    $website = 'eBay Australia';
    
                    break;
    
                case '16':
                
                    $website = 'eBay Austria';
    
                    break;
    
                case '23':
                
                    $website = 'eBay Belgium (French)';
    
                    break;
    
                case '71':
                
                    $website = 'eBay France';
    
                    break;
    
                case '77':
                
                    $website = 'eBay Germany';
    
                    break;
    
                case '100':
                
                    $website = 'eBay Motors';
    
                    break;
    
                case '101':
                
                    $website = 'eBay Italy';
    
                    break;
    
                case '123':
                
                    $website = 'eBay Belgium (Dutch)';
    
                    break;
    
                case '146':
                
                    $website = 'eBay Netherlands';
    
                    break;
    
                case '186':
                
                    $website = 'eBay Spain';
    
                    break;
    
                case '193':
                
                    $website = 'eBay Switzerland';
    
                    break;
    
                case '201':
                
                    $website = 'eBay Hong Kong';
    
                    break;
    
                case '203':
                
                    $website = 'eBay India';
    
                    break;
    
                case '205':
                
                    $website = 'eBay Ireland';
    
                    break;
    
                case '207':
                
                    $website = 'eBay Malaysia';
    
                    break;
    
                case '210':
                
                    $website = 'eBay Canada (French)';
    
                    break;
    
                case '211':
                
                    $website = 'eBay Philippines';
    
                    break;
    
                case '212':
                
                    $website = 'eBay Poland';
    
                    break;
    
                case '216':
                
                    $website = 'eBay Singapore';
    
                    break;
    
            }

        }

        // Set eBay's websites
        $setup_data .= '<div class="dropdown">'
            . '<a class="btn btn-secondary btn-md dropdown-toggle" data-type="ebay_website" data-stream="' . $stream[0]->stream_id . '" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">'
                . $website
            . '</a>'
            . '<div class="dropdown-menu dropdown-menu-action stream-select-setup-list" aria-labelledby="dropdownMenuLink" x-placement="bottom-start" style="position: absolute; transform: translate3d(15px, 48px, 0px); top: 0px; left: 0px; will-change: transform;">'
                . '<a class="dropdown-item" data-id="0" href="#">eBay United States</a>'
                . '<a class="dropdown-item" data-id="2" href="#">eBay Canada (English)</a>'
                . '<a class="dropdown-item" data-id="3" href="#">eBay UK</a>'
                . '<a class="dropdown-item" data-id="15" href="#">eBay Australia</a>'
                . '<a class="dropdown-item" data-id="16" href="#">eBay Austria</a>'
                . '<a class="dropdown-item" data-id="23" href="#">eBay Belgium (French)</a>'
                . '<a class="dropdown-item" data-id="71" href="#">eBay France</a>'
                . '<a class="dropdown-item" data-id="77" href="#">eBay Germany</a>'
                . '<a class="dropdown-item" data-id="100" href="#">eBay Motors</a>'
                . '<a class="dropdown-item" data-id="101" href="#">eBay Italy</a>'
                . '<a class="dropdown-item" data-id="123" href="#">eBay Belgium (Dutch)</a>'
                . '<a class="dropdown-item" data-id="146" href="#">eBay Netherlands</a>'
                . '<a class="dropdown-item" data-id="186" href="#">eBay Spain</a>'
                . '<a class="dropdown-item" data-id="193" href="#">eBay Switzerland</a>'
                . '<a class="dropdown-item" data-id="201" href="#">eBay Hong Kong</a>'
                . '<a class="dropdown-item" data-id="203" href="#">eBay India</a>'
                . '<a class="dropdown-item" data-id="205" href="#">eBay Ireland</a>'
                . '<a class="dropdown-item" data-id="207" href="#">eBay Malaysia</a>'
                . '<a class="dropdown-item" data-id="210" href="#">eBay Canada (French)</a>'
                . '<a class="dropdown-item" data-id="211" href="#">eBay Philippines</a>'
                . '<a class="dropdown-item" data-id="212" href="#">eBay Poland</a>'
                . '<a class="dropdown-item" data-id="216" href="#">eBay Singapore</a>'
            . '</div>'
        . '</div>';

        // Get the stream's setup
        $get_setup = (new MidrubBaseUserAppsCollectionStreamHelpers\Streams_setup)->streams_setup_get($stream[0]->stream_id, 'ebay_category');

        // Default category select
        $category = $this->CI->lang->line('select_category');

        // Verify if setup exists
        if ( $get_setup ) {

            // Set category
            $category = $get_setup[0]->setup_extra;

        }

        // Set eBay's category
        $setup_data .= '<div class="dropdown">'
            . '<a class="btn btn-secondary btn-md dropdown-toggle" data-type="ebay_category" data-stream="' . $stream[0]->stream_id . '" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">'
                . $category
            . '</a>'
            . '<div class="dropdown-menu dropdown-menu-action stream-select-setup-list" aria-labelledby="dropdownMenuLink" x-placement="bottom-start" style="position: absolute; transform: translate3d(15px, 48px, 0px); top: 0px; left: 0px; will-change: transform;">'
                . '<div class="card">'
                    . '<div class="card-head">'
                        . '<input type="text" class="ebay-rss-search-for-categories" placeholder="Search for categories" data-type="search-categories">'
                    . '</div>'
                    . '<div class="card-body">'
                        . '<ul class="list-group ebay-rss-categories-list">'
                            . '<li class="no-results">'
                                . $this->CI->lang->line('no_results_found')
                            . '</li>'
                        . '</ul>'
                    . '</div>'
                 . '</div>'
            . '</div>'
        . '</div>';
        
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
        
        $icon = '';
        
        $get_icon = (new MidrubBaseUserAppsCollectionStreamHelpers\Accounts)->get_network_info( 'ebay' );
        
        if ( $get_icon ) {
            
            $icon = $get_icon['icon'];
            
        }
        
        // Prepare and return connect data
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
        
        // Prepare and return template's information
        return array (
            'displayed_name' => $this->CI->lang->line('items_by_category'),
            'icon' => '<i class="fab fa-ebay"></i>',
            'description' => $this->CI->lang->line('items_by_category_description'),
            'parent' => 'ebay'
        );
        
    }
    
}

/* End of file items_by_category.php */
