<?php
/**
 * Connection Helpers
 *
 * This file contains the class Connection
 * with methods to process stream's connection process
 *
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.5
 */

// Define the page namespace
namespace MidrubBase\User\Apps\Collection\Stream\Helpers;

defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * Connection class provides the methods to process the stream's connection process
 * 
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.5
*/
class Connection {
    
    /**
     * Class variables
     *
     * @since 0.0.7.0
     */
    protected $CI;

    /**
     * Initialise the Class
     *
     * @since 0.0.7.0
     */
    public function __construct() {
        
        // Get codeigniter object instance
        $this->CI =& get_instance();
        
        // Load models
        $this->CI->load->ext_model( MIDRUB_BASE_USER_APPS_STREAM . 'models/', 'Stream_history_model', 'stream_history_model' );
        
    }
    
    /**
     * The public method stream_template_load loads the stream's template
     * 
     * @since 0.0.7.5
     * 
     * @return void
     */ 
    public function stream_template_load() {
        
        // Check if data was submitted
        if ($this->CI->input->post()) {
            
            // Add form validation
            $this->CI->form_validation->set_rules('template_name', 'Template Name', 'trim|required');
            $this->CI->form_validation->set_rules('tab_id', 'Tab ID', 'trim|numeric|required');
            $this->CI->form_validation->set_rules('network_id', 'Network ID', 'trim|numeric|required');
            
            // Get data
            $template_name = $this->CI->input->post('template_name');
            $tab_id = $this->CI->input->post('tab_id');
            $network_id = $this->CI->input->post('network_id');
            
            if ( $this->CI->form_validation->run() !== false ) {
                
                if ( $this->stream_calculate_tab_steams($tab_id) ) {
                    
                    $data = array(
                        'success' => FALSE,
                        'message' => $this->CI->lang->line('reached_maximum_number_tab_streams')
                    );

                    echo json_encode($data);
                    exit();
                    
                }
                
                // Verify if the user is owner of the tab
                $tab = $this->CI->stream_tabs_model->all_user_tabs( $this->CI->user_id, $tab_id );

                if ( $tab ) {
        
                    // Verify if template exists
                    if ( file_exists( MIDRUB_BASE_USER_APPS_STREAM . 'templates/' . strtolower($template_name) . '.php' ) ) {

                        // Create an array
                        $array = array(
                            'MidrubBase',
                            'User',
                            'Apps',
                            'Collection',
                            'Stream',
                            'Templates',
                            $template_name
                        );       

                        // Implode the array above
                        $cl = implode('\\',$array);
                        
                        // Get template's info
                        $template_info = (new $cl())->template_info();
                        
                        // Verify if user can use this network
                        if ( get_option( strtolower($template_info['parent'] ) ) & plan_feature(strtolower($template_info['parent']) ) ) {
                            
                            // Verify if the user is the owner of the network_id
                            $network_details = $this->CI->stream_networks_model->get_network_data(strtolower($template_info['parent']), $this->CI->user_id, $network_id);

                            if ($network_details) {

                                // Verify if template was saved
                                $saved = $this->CI->stream_tabs_model->save_tab_stream($tab_id, $template_name, $template_info['parent'], $network_id);

                                if ($saved) {

                                    // Get template's content
                                    (new $cl())->template_content($saved);
                                    exit();
                                }
                                
                            }
                            
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
        
    }
    
    /**
     * The public method stream_template_load loads the steam's template
     * 
     * @since 0.0.7.5
     * 
     * @return void
     */ 
    public function stream_save_new_stream_with_url() {
        
        // Check if data was submitted
        if ($this->CI->input->post()) {
            
            // Add form validation
            $this->CI->form_validation->set_rules('template_name', 'Template Name', 'trim|required');
            $this->CI->form_validation->set_rules('network', 'Network', 'trim|required');
            $this->CI->form_validation->set_rules('tab_id', 'Tab ID', 'trim|numeric|required');
            $this->CI->form_validation->set_rules('input_url', 'Input URL', 'trim|required');
            
            // Get data
            $template_name = $this->CI->input->post('template_name');
            $network = $this->CI->input->post('network');
            $tab_id = $this->CI->input->post('tab_id');
            $input_url = $this->CI->input->post('input_url');
            
            if ( $this->CI->form_validation->run() !== false ) {
                
                if ( $this->stream_calculate_tab_steams($tab_id) ) {
                    
                    $data = array(
                        'success' => FALSE,
                        'message' => $this->CI->lang->line('reached_maximum_number_tab_streams')
                    );

                    echo json_encode($data);
                    exit();
                    
                }
                
                // Verify if the user is owner of the tab
                $tab = $this->CI->stream_tabs_model->all_user_tabs( $this->CI->user_id, $tab_id );

                if ( $tab ) {
        
                    // Verify if template exists
                    if ( file_exists( MIDRUB_BASE_USER_APPS_STREAM . 'templates/' . strtolower($template_name) . '.php' ) ) {

                        // Create an array
                        $array = array(
                            'MidrubBase',
                            'User',
                            'Apps',
                            'Collection',
                            'Stream',
                            'Templates',
                            $template_name
                        );       

                        // Implode the array above
                        $cl = implode('\\',$array);
                        
                        // Verify if submitted data meets the stream's template requirements
                        (new $cl())->stream_extra('check_stream_connection', array(
                                'template_name' => $template_name,
                                'network' => $network,
                                'tab_id' => $tab_id,
                                'input_url' => $input_url
                            )
                        );
                        exit();
                        
                    }
                    
                }
                
            }
            
            $data = array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('please_enter_valid_url')
            );

            echo json_encode($data);  
            
        }
        
    }
    
    /**
     * The public method stream_calculate_tab_steams verifies if the user has reached the maximum number of streams per tab
     * 
     * @param integer $tab_id contains the tab's ID
     * 
     * @since 0.0.7.5
     * 
     * @return boolean true or false
     */ 
    public function stream_calculate_tab_steams($tab_id) {
        
        // Get all streams
        $tab_streams = $this->CI->stream_tabs_model->stream_load_tab_streams( $this->CI->user_id, $tab_id );
        
        if ( $tab_streams ) {
            
            if ( count($tab_streams) > 3 ) {
                
                return true;
                
            } else {
                
                return false;
                
            }
            
        } else {
            
            return false;
            
        }
        
    }
    
}

