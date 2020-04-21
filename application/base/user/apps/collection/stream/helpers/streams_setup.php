<?php
/**
 * Stream's Setup Helpers
 *
 * This file contains the class Streams_setup
 * with methods to provide Stream's Setup options
 *
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.5
 */

// Define the page namespace
namespace MidrubBase\User\Apps\Collection\Stream\Helpers;

// Constants
defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * Streams_setup class provides the methods to provide Stream's Setup options
 * 
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.5
*/
class Streams_setup {
    
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
     * The public method streams_setup_save saves new setup data
     * 
     * @param integer $stream_id contains the stream's id
     * @param string $template contains the template's name
     * @param string $setup_option contains the first option value
     * @param string $setup_extra contains the extra or section option value
     * 
     * @since 0.0.7.5
     * 
     * @return integer with last inserted id
     */ 
    public function streams_setup_save($stream_id, $template, $setup_option, $setup_extra=NULL) {
        
        return $this->CI->stream_setup_model->stream_save_setup( $stream_id, $template, $setup_option, $setup_extra );
        
    }
    
    /**
     * The public method streams_setup_get gets setup's data
     * 
     * @param integer $stream_id contains the stream's id
     * @param string $template contains the template's name
     * @param string $setup_option contains the first option value
     * 
     * @since 0.0.7.5
     * 
     * @return object with stream's setup
     */ 
    public function streams_setup_get($stream_id, $template, $setup_option=NULL) {
        
        return $this->CI->stream_setup_model->stream_get_setup( $stream_id, $template, $setup_option );
        
    }  
    
    /**
     * The public method streams_setup_delete deletes the setup's data
     * 
     * @param integer $setup_id contains the setup's id
     * @param integer $stream_id contains the stream's id
     * @param string $template contains the template's name
     * 
     * @since 0.0.7.5
     * 
     * @return boolean true or false
     */ 
    public function streams_setup_delete($setup_id, $stream_id, $template) {
        
        return $this->CI->stream_setup_model->streams_delete_setup( $setup_id, $stream_id, $template );
        
    }    
    
}
