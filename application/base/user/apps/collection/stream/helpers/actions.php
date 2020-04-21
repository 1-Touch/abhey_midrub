<?php
/**
 * Actions Helpers
 *
 * This file contains the class Tabs
 * with methods to process stream's items actions
 *
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.5
 */

// Define the page namespace
namespace MidrubBase\User\Apps\Collection\Stream\Helpers;

defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * Actions class provides the methods to process the stream's items actions
 * 
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.5
*/
class Actions {
    
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
     * The public method stream_item_action_link process the link request
     * 
     * @since 0.0.7.5
     * 
     * @return void
     */ 
    public function stream_item_action_link() {
                
        // Check if data was submitted
        if ($this->CI->input->post()) {
            
            // Add form validation
            $this->CI->form_validation->set_rules('stream_id', 'Stream ID', 'trim|numeric|required');
            
            // Get data
            $stream_id = $this->CI->input->post('stream_id');
            
            if ( $this->CI->form_validation->run() !== false ) {
                
                $stream = $this->CI->stream_tabs_model->verify_stream_owner( $this->CI->user_id, $stream_id );

                // Verify if stream's id is of the current user 
                if ( $stream ) {

                    // Verify if template exists
                    if ( file_exists( MIDRUB_BASE_USER_APPS_STREAM . 'templates/' . strtolower($stream[0]->template) . '.php' ) ) {

                        // Create an array
                        $array = array(
                            'MidrubBase',
                            'User',
                            'Apps',
                            'Collection',
                            'Stream',
                            'Templates',
                            $stream[0]->template
                        );       

                        // Implode the array above
                        $cl = implode('\\',$array);

                        // Process the template's item's action
                        (new $cl())->stream_process_action( $stream );
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
    
}

