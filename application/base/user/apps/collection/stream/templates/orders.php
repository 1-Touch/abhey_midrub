<?php
/**
 * Orders Ebay Template
 *
 * This file contains the Stream's Orders Ebay template 
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
 * Orders contains the Stream's Orders template 
 * 
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.8.0
 */
class Orders implements MidrubBaseUserAppsCollectionStreamInterfaces\Stream {
    
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
        
        // Display the error message
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

        // Default value for $orders
        $orders = array();
        
        // Get account's data based on network's id and user's id
        $network_details = $this->CI->stream_networks_model->get_network_data('ebay', $this->CI->user_id, $stream->network_id);

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
        curl_close ($curl);

        // Verify if access token exists
        if ( isset($curl_response['access_token']) ) {

            // Prepare body params
            $params = array(
                'filter' => urlencode('creationdate:[' . date("Y-m-d\TH:i:s.000\Z", (time() - 2592000)) . '..]'), // 2592000 means 30 days
                'limit' => 10,
                'offset' => 0
            );

            // Prepare header's params
            $header = array(
                'Authorization:Bearer ' . $curl_response['access_token'],
                'Accept:application/json',
                'Content-Type:application/json',
            );

            // Init Curl
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $this->api_url . 'sell/fulfillment/v1/order' . '?' . urldecode(http_build_query($params)));
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            $response = json_decode(curl_exec($curl), true);
            curl_close($curl);

            // Verify if the response has the orders key
            if ( isset($response['orders']) ) {

                // Add value to $orders array
                $orders = $response['orders'];

            }

        }
        
        // Get the Stream's setup
        $get_setup = (new MidrubBaseUserAppsCollectionStreamHelpers\Streams_setup)->streams_setup_get($stream->stream_id, 'orders');

        // All orders variable
        $all_orders = '';

        // Ids array for items count
        $ids = array();

        // Verify if the array $orders is not empty
        if ( $orders ) {
            
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
            
            // List all orders
            foreach ( $orders as $order ) {


                // Create the item's content
                $all_orders .= '<li class="row"' . $border_bottom_color . '>
                                <div class="col-xl-12">
                                    <div class="stream-post full-width">
                                        <small class="clean"' . $icons_color . '>
                                            ' . '<i class="icon-clock"></i>' . calculate_time(strtotime($order["creationDate"]), time()) . '
                                        </small>
                                        <p' . $paragraph_color . ' data-type="stream-item-content">
                                            ' . $order["lineItems"][0]['title'] . '
                                        </p>   
                                        <div class="stream-post-footer"> 
                                            <div class="row">
                                                <div class="col-6">
                                                    <a href="#" data-network="location_reviews" class="stream-item-react theme-color-blue" data-stream="' . $stream->stream_id . '" data-type="order" data-id="' . $order["orderId"] . '"' . $icons_color . '>
                                                        <i class="icon-basket-loaded" style="font-size: initial;"></i>
                                                    </a>
                                                </div>
                                                <div class="col-6 text-right">
                                                    <strong class="theme-color-green">
                                                        ' . $order["pricingSummary"]["total"]["currency"] . ' ' . $order["pricingSummary"]["total"]["value"] . ' 
                                                    </strong>
                                                </div>
                                            </div> 
                                        </div>
                                    </div>
                                </div>
                            </li>';
                
            }
            
        } else {
            
            // If no orders display this
            $all_orders .= '<li class="row"' . $border_bottom_color . '>'
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
                            . $all_orders
                        . '</ul>',
            'footer' => $sound
            
        );
        
        // Prepare data to send back
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

        
        // Check if data was submitted
        if ($this->CI->input->post()) {
            
            // Add form validation
            $this->CI->form_validation->set_rules('type', 'Type', 'trim|required');
            $this->CI->form_validation->set_rules('id', 'ID', 'trim|required');
            
            // Get data
            $type = $this->CI->input->post('type');
            $id = $this->CI->input->post('id');
            
            // Verify if data is correct
            if ( $this->CI->form_validation->run() !== false ) {
                
                // Get network's account
                $network_details = $this->CI->stream_networks_model->get_network_data('ebay', $this->CI->user_id, $stream[0]->network_id);
                
                // Verify if network account exists
                if ( $network_details ) {
                    
                    switch ( $type ) {
                            
                        case 'order':

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

                                // Prepare header's params
                                $header = array(
                                    'Authorization:Bearer ' . $curl_response['access_token'],
                                    'Accept:application/json',
                                    'Content-Type:application/json',
                                );

                                // Init Curl
                                $curl = curl_init();
                                curl_setopt($curl, CURLOPT_URL, $this->api_url . 'sell/fulfillment/v1/order/' . $id );
                                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
                                curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
                                curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
                                curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                                $response = json_decode(curl_exec($curl), true);
                                curl_close($curl);

                                // Verify if the response has the orders key
                                if ( isset($response['orderId']) ) {

                                    $get_setup = (new MidrubBaseUserAppsCollectionStreamHelpers\Streams_setup)->streams_setup_get($stream[0]->stream_id, 'orders');

                                    // Start single item's template
                                    $com_data = '<div class="col-xl-12 stream-simgle-item-template">';

                                    // Set buyer info table start
                                    $com_data .= '<div class="row">'
                                                . '<div class="col-xl-12">'
                                                    . '<div class="table-responsive">'
                                                        . '<table class="table">'
                                                            . '<thead class="thead-dark">'
                                                                . '<tr>'
                                                                    . '<th scope="row" colspan="2">'
                                                                        . 'Shipping Details'
                                                                    . '</th>'
                                                                . '</tr>'
                                                            . '</thead>'
                                                            . '<tbody>';

                                    // Verify if fullname exists
                                    if ( isset($response['fulfillmentStartInstructions'][0]['shippingStep']['shipTo']['fullName']) ) {

                                        // Set fullname
                                        $com_data .= '<tr>'
                                                        . '<td>'
                                                            . 'Name'
                                                        . '</td>'
                                                        . '<td class="text-right order-shipping-details-name">'
                                                            . $response['fulfillmentStartInstructions'][0]['shippingStep']['shipTo']['fullName']
                                                        . '</td>'
                                                    . '</tr>';

                                    }

                                    // Verify if phone exists
                                    if ( isset($response['fulfillmentStartInstructions'][0]['shippingStep']['shipTo']['primaryPhone']['phoneNumber']) ) {

                                        // Set phone
                                        $com_data .= '<tr>'
                                                        . '<td>'
                                                            . 'Phone'
                                                        . '</td>'
                                                        . '<td class="text-right order-shipping-details-phone">'
                                                            . $response['fulfillmentStartInstructions'][0]['shippingStep']['shipTo']['primaryPhone']['phoneNumber']
                                                        . '</td>'
                                                    . '</tr>';

                                    }
                                    
                                    // Verify if street exists
                                    if ( isset($response['fulfillmentStartInstructions'][0]['shippingStep']['shipTo']['contactAddress']['addressLine1']) ) {

                                        // Set street
                                        $com_data .= '<tr>'
                                                        . '<td>'
                                                            . 'Street'
                                                        . '</td>'
                                                        . '<td class="text-right order-shipping-details-street">'
                                                            . $response['fulfillmentStartInstructions'][0]['shippingStep']['shipTo']['contactAddress']['addressLine1']
                                                        . '</td>'
                                                    . '</tr>';

                                    }
                                    
                                    // Verify if city exists
                                    if ( isset($response['fulfillmentStartInstructions'][0]['shippingStep']['shipTo']['contactAddress']['city']) ) {

                                        // Set city
                                        $com_data .= '<tr>'
                                                        . '<td>'
                                                            . 'City'
                                                        . '</td>'
                                                        . '<td class="text-right order-shipping-details-city">'
                                                            . $response['fulfillmentStartInstructions'][0]['shippingStep']['shipTo']['contactAddress']['city']
                                                        . '</td>'
                                                    . '</tr>';

                                    }  
                                    
                                    // Verify if country exists
                                    if ( isset($response['fulfillmentStartInstructions'][0]['shippingStep']['shipTo']['contactAddress']['countryCode']) ) {

                                        // Set country
                                        $com_data .= '<tr>'
                                                        . '<td>'
                                                            . 'Country'
                                                        . '</td>'
                                                        . '<td class="text-right order-shipping-details-country">'
                                                            . $response['fulfillmentStartInstructions'][0]['shippingStep']['shipTo']['contactAddress']['countryCode']
                                                        . '</td>'
                                                    . '</tr>';

                                    }  
                                    
                                    // Verify if state exists
                                    if ( isset($response['fulfillmentStartInstructions'][0]['shippingStep']['shipTo']['contactAddress']['stateOrProvince']) ) {

                                        // Set state
                                        $com_data .= '<tr>'
                                                        . '<td>'
                                                            . 'State'
                                                        . '</td>'
                                                        . '<td class="text-right order-shipping-details-state">'
                                                            . $response['fulfillmentStartInstructions'][0]['shippingStep']['shipTo']['contactAddress']['stateOrProvince']
                                                        . '</td>'
                                                    . '</tr>';

                                    }
                                    
                                    // Verify if postal code exists
                                    if ( isset($response['fulfillmentStartInstructions'][0]['shippingStep']['shipTo']['contactAddress']['postalCode']) ) {

                                        // Set postal code
                                        $com_data .= '<tr>'
                                                        . '<td>'
                                                            . 'Postal Code'
                                                        . '</td>'
                                                        . '<td class="text-right order-shipping-details-postal-code">'
                                                            . $response['fulfillmentStartInstructions'][0]['shippingStep']['shipTo']['contactAddress']['postalCode']
                                                        . '</td>'
                                                    . '</tr>';

                                    } 
                                    
                                    // Verify if email exists
                                    if ( isset($response['fulfillmentStartInstructions'][0]['shippingStep']['shipTo']['email']) ) {

                                        // Set email
                                        $com_data .= '<tr>'
                                                        . '<td>'
                                                            . 'Email'
                                                        . '</td>'
                                                        . '<td class="text-right order-shipping-details-email">'
                                                            . $response['fulfillmentStartInstructions'][0]['shippingStep']['shipTo']['email']
                                                        . '</td>'
                                                    . '</tr>';

                                    }                                     

                                    // Set buyer info table end
                                    $com_data .= '</tbody>'
                                                . '</table>'
                                            . '</div>'
                                        . '</div>'
                                    . '</div>';


                                    // Set order info table start
                                    $com_data .= '<div class="row">'
                                                . '<div class="col-xl-12">'
                                                    . '<div class="table-responsive">'
                                                        . '<table class="table">'
                                                            . '<thead class="thead-dark">'
                                                                . '<tr>'
                                                                    . '<th scope="row" colspan="2">'
                                                                        . 'Price and Products'
                                                                    . '</th>'
                                                                . '</tr>'
                                                            . '</thead>'
                                                            . '<tbody>';

                                    // Verify if items exists
                                    if ( $response['lineItems'] ) {

                                        // List all items
                                        foreach ( $response['lineItems'] as $item ) {
                                            
                                            // Set item's data
                                            $com_data .= '<tr>'
                                                        . '<td>'
                                                            . $item['title']
                                                        . '</td>'
                                                        . '<td class="text-right order-shipping-details-email">'
                                                            . $item['lineItemCost']['currency'] . ' ' . $item['lineItemCost']['value']
                                                        . '</td>'
                                                    . '</tr>';


                                        }

                                    }

                                    // Set order info table end
                                    $com_data .= '</tbody>'
                                                . '</table>'
                                            . '</div>'
                                        . '</div>'
                                    . '</div>';
                                    

                                    // End single item's template
                                    $com_data .= '</div>';
    
                                    $data = array(
                                        'success' => TRUE,
                                        'content' => $com_data,
                                        'menu_text' => 'Order Details'
                                    );
    
                                    echo json_encode($data);
                                    exit();

                                }

                            }
                            
                            break;
                            
                    }
                    

                    
                }
                
            }
            
        }
        
        // Display error
        $data = array(
            'success' => FALSE,
            'message' => $this->CI->lang->line('error_occurred')
        );

        echo json_encode($data); 
        
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
        
        // Default setup value
        $setup_data = '';
        
        // Verify if networks exists
        if ( $network_details ) {
            
            // Default icon value
            $icon = '';

            // Get icon
            $get_icon = (new MidrubBaseUserAppsCollectionStreamHelpers\Accounts)->get_network_info( 'ebay' );

            // Verify if icon exists
            if ( $get_icon ) {
                $icon = $get_icon['icon'];
            }
            
            // Add data
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
        
        // Display setup
        $data = array(
            'success' => TRUE,
            'setup_data' => $setup_data,
            'alert_sound' => $stream[0]->alert_sound,
            'tab_id' => $stream[0]->tab_id,
            'stream' => $stream
        );
        
        // Verify if alert sound exists
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
        
        // Default icon value
        $icon = '';
        
        // Get icon
        $get_icon = (new MidrubBaseUserAppsCollectionStreamHelpers\Accounts)->get_network_info( 'ebay' );
        
        // Verify if icon exists
        if ( $get_icon ) {
            $icon = $get_icon['icon'];
        }
        
        // Return template connect data
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
            'displayed_name' => $this->CI->lang->line('orders'),
            'icon' => '<i class="fab fa-ebay"></i>',
            'description' => $this->CI->lang->line('orders_description'),
            'parent' => 'ebay'
        );
        
    }
    
}

/* End of file orders.php */
