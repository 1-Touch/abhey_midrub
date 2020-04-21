<?php
/**
 * Single Helper
 *
 * This file contains the class Single
 * with methods to process single stream's template item
 *
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.6
 */

// Define the page namespace
namespace MidrubBase\User\Apps\Collection\Stream\Helpers;

defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * Single class provides the methods to process single stream's template item
 * 
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.6
*/
class Single {
    
    /**
     * Class variables
     *
     * @since 0.0.7.5
     */
    protected $CI;

    /**
     * Initialise the Class
     *
     * @since 0.0.7.5
     */
    public function __construct() {
        
        // Get codeigniter object instance
        $this->CI =& get_instance();
        
        // Load models
        $this->CI->load->ext_model( MIDRUB_BASE_USER_APPS_STREAM . 'models/', 'Stream_setup_model', 'stream_setup_model' );
        
    }

    /**
     * The public method stream_template_content_single gets stream's template single
     * 
     * @since 0.0.7.6
     * 
     * @return void
     */ 
    public function stream_template_content_single() {
        
        // Check if data was submitted
        if ($this->CI->input->post()) {
            
            // Add form validation
            $this->CI->form_validation->set_rules('stream_id', 'Stream ID', 'trim|required');
            
            // Get data
            $stream_id = $this->CI->input->post('stream_id');
            
            if ( $this->CI->form_validation->run() !== false ) {
                
                $stream = $this->CI->stream_tabs_model->verify_stream_owner( $this->CI->user_id, $stream_id );
        
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

                    // Save Stream's colors
                    (new $cl())->template_content_single($stream);
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
