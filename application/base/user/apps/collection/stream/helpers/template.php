<?php
/**
 * Template Helpers
 *
 * This file contains the class Template
 * with methods to provide Stream's Templates options
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
 * Template class provides the methods to provide Stream's Templates options
 * 
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.5
*/
class Template {
    
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
        
    }

    /**
     * The public method stream_load_connection_settings provides the templates connection settings
     * 
     * @since 0.0.7.5
     * 
     * @return void
     */ 
    public function stream_load_connection_settings() {
        
        // Get template_name's input
        $template_name = $this->CI->input->get('template_name');
        
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

            // Get template's connect info
            $template_connect = (new $cl())->template_connect();
            
            $data = array(
                'success' => TRUE,
                'connection_rules' => $template_connect
            );

            echo json_encode($data);            
            
        }
        
    }    
    
    /**
     * The public method stream_update_setup updates a stream's setup
     * 
     * @since 0.0.7.5
     * 
     * @return void
     */ 
    public function stream_update_setup() {
        
        // Check if data was submitted
        if ($this->CI->input->post()) {
            
            // Add form validation
            $this->CI->form_validation->set_rules('stream-template', 'Template Name', 'trim|required');
            
            // Get data
            $template_name = $this->CI->input->post('stream-template');
            
            if ( $this->CI->form_validation->run() !== false ) {
        
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

                    // Updates stream's setup
                    (new $cl())->stream_update_setup();
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
     * The public method stream_send_react sends a reaction
     * 
     * @since 0.0.7.5
     * 
     * @return void
     */ 
    public function stream_send_react() {
        
        // Check if data was submitted
        if ($this->CI->input->post()) {
            
            // Add form validation
            $this->CI->form_validation->set_rules('stream_id', 'Stream ID', 'trim|numeric|required');
            
            // Get data
            $stream_id = $this->CI->input->post('stream_id');
            
            if ( $this->CI->form_validation->run() !== false ) {
                
                $stream = $this->CI->stream_tabs_model->verify_stream_owner( $this->CI->user_id, $stream_id );
        
                // Verify if template exists
                if ( file_exists( MIDRUB_BASE_USER_APPS_STREAM . 'templates/' . $stream[0]->template . '.php' ) ) {
                    
                    $template = ucfirst($stream[0]->template);

                    // Create an array
                    $array = array(
                        'MidrubBase',
                        'User',
                        'Apps',
                        'Collection',
                        'Stream',
                        'Templates',
                        $template
                    );       

                    // Implode the array above
                    $cl = implode('\\',$array);

                    // Submit react
                    (new $cl())->stream_post($stream);
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
     * The public method stream_delete_setup deletes the stream's setup
     * 
     * @since 0.0.7.5
     * 
     * @return void
     */ 
    public function stream_delete_setup() {
        
        // Check if data was submitted
        if ($this->CI->input->post()) {
            
            // Add form validation
            $this->CI->form_validation->set_rules('template_name', 'Template Name', 'trim|required');
            
            // Get data
            $template_name = $this->CI->input->post('template_name');
            
            if ( $this->CI->form_validation->run() !== false ) {
        
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

                    // Deletes stream's setup
                    (new $cl())->stream_delete_setup();
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
     * The public method stream_save_stream_order saves stream's order
     * 
     * @since 0.0.7.5
     * 
     * @return void
     */ 
    public function stream_save_stream_order() {
        
        // Get stream_id's input
        $stream_id = $this->CI->input->get('stream_id');
        
        // Get order_id's input
        $order_id = $this->CI->input->get('order_id');  
        
        // Verify if stream's id and order id is correct
        if ( is_numeric($order_id) && ($order_id < 7) && is_numeric($stream_id) ) {
            
            // Verify if stream's id is of the current user 
            if ( $this->CI->stream_tabs_model->verify_stream_owner( $this->CI->user_id, $stream_id ) ) {
                
                $this->CI->stream_tabs_model->update_stream_field($stream_id, 'stream_order', $order_id);
                
            }
            
        }
        
    }
    
    /**
     * The public method stream_get_setup geta the stream's setup from database
     * 
     * @since 0.0.7.5
     * 
     * @return void
     */ 
    public function stream_get_setup() {
        
        // Get stream_id's input
        $stream_id = $this->CI->input->get('stream_id');
        
        $stream = $this->CI->stream_tabs_model->verify_stream_owner( $this->CI->user_id, $stream_id );
        
        // Verify if stream's id is of the current user 
        if ( $stream ) {

            // Verify if template exists
            if ( file_exists( MIDRUB_BASE_USER_APPS_STREAM . 'templates/' . $stream[0]->template . '.php' ) ) {

                $template = ucfirst($stream[0]->template);
                
                // Create an array
                $array = array(
                    'MidrubBase',
                    'User',
                    'Apps',
                    'Collection',
                    'Stream',
                    'Templates',
                    $template
                );       

                // Implode the array above
                $cl = implode('\\',$array);

                // Get template's setup
                (new $cl())->stream_get_setup( $stream );
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
     * The public method stream_mark_seen marks stream as seen
     * 
     * @since 0.0.7.5
     * 
     * @return void
     */ 
    public function stream_mark_seen() {
        
        // Get stream_id's input
        $stream_id = $this->CI->input->get('stream_id');
        
        $stream = $this->CI->stream_tabs_model->verify_stream_owner( $this->CI->user_id, $stream_id );
        
        // Verify if stream's id is of the current user 
        if ( $stream ) {
            
            if ( $this->CI->stream_tabs_model->update_stream_field($stream_id, 'new_event', 0) ) {
                
                $data = array(
                    'success' => TRUE,
                    'tab_id' => $stream[0]->tab_id
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
     * The public method stream_select_sound_alert adds or removes a stream's sound alert
     * 
     * @since 0.0.7.5
     * 
     * @return void
     */ 
    public function stream_select_sound_alert() {
        
        // Check if data was submitted
        if ($this->CI->input->post()) {
            
            // Add form validation
            $this->CI->form_validation->set_rules('stream_id', 'Stream ID', 'trim|required');
            $this->CI->form_validation->set_rules('name', 'Name', 'trim|required');
            
            // Get data
            $stream_id = $this->CI->input->post('stream_id');
            $name = $this->CI->input->post('name');
            
            if ( $this->CI->form_validation->run() !== false ) {
                
                $stream = $this->CI->stream_tabs_model->verify_stream_owner( $this->CI->user_id, $stream_id );
                
                if ( $stream ) {
                    
                    if ( $stream[0]->alert_sound === $name ) {
                        
                        if ( $this->CI->stream_tabs_model->update_stream_field($stream_id, 'alert_sound', '') ) {
                        
                            $data = array(
                                'success' => TRUE,
                                'message' => $this->CI->lang->line('select_a_sound'),
                                'unselect_all' => true
                            );

                            echo json_encode($data);
                            exit();
                        
                        }
                        
                    } else {
                        
                        if ( $this->CI->stream_tabs_model->update_stream_field($stream_id, 'alert_sound', $name) ) {
                            
                            $data = array(
                                'success' => TRUE,
                                'message' => $name,
                                'unselect_all' => false
                            );

                            echo json_encode($data);
                            exit();                            
                            
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
    
    /**
     * The public method stream_delete_selected_stream deletes selected stream
     * 
     * @since 0.0.7.5
     * 
     * @return void
     */ 
    public function stream_delete_selected_stream() {
        
        // Get stream_id's input
        $stream_id = $this->CI->input->get('stream_id');
        
        $owner = $this->CI->stream_tabs_model->verify_stream_owner( $this->CI->user_id, $stream_id );
        
        // Verify if stream's id is of the current user 
        if ( $owner ) {
        
            $deleted = $this->CI->stream_tabs_model->delete_stream( $stream_id );

            // Verify if stream was deleted
            if ( $deleted ) {
                
                $data = array(
                    'success' => TRUE,
                    'message' => $this->CI->lang->line('stream_was_deleted')
                );

                echo json_encode($data);
                exit();

            }
            
        }
        
        $data = array(
            'success' => FALSE,
            'message' => $this->CI->lang->line('tream_was_not_deleted')
        );

        echo json_encode($data); 
        
    } 
    
    /**
     * The public method stream_change_settings_color changes streams colors
     * 
     * @since 0.0.7.5
     * 
     * @return void
     */ 
    public function stream_change_settings_color() {
        
        // Check if data was submitted
        if ($this->CI->input->post()) {
            
            // Add form validation
            $this->CI->form_validation->set_rules('stream_id', 'Stream ID', 'trim|required');
            $this->CI->form_validation->set_rules('name', 'Name', 'trim|required');
            $this->CI->form_validation->set_rules('value', 'Value', 'trim|required');
            
            // Get data
            $stream_id = $this->CI->input->post('stream_id');
            $name = $this->CI->input->post('name');
            $value = $this->CI->input->post('value');
            
            if ( $this->CI->form_validation->run() !== false ) {
                
                $stream = $this->CI->stream_tabs_model->verify_stream_owner( $this->CI->user_id, $stream_id );
        
                // Verify if template exists
                if ( file_exists( MIDRUB_BASE_USER_APPS_STREAM . 'templates/' . $stream[0]->template . '.php' ) ) {
                    
                    $template = ucfirst($stream[0]->template);

                    // Create an array
                    $array = array(
                        'MidrubBase',
                        'User',
                        'Apps',
                        'Collection',
                        'Stream',
                        'Templates',
                        $template
                    );       

                    // Implode the array above
                    $cl = implode('\\',$array);

                    // Save Stream's colors
                    (new $cl())->stream_colors_change($stream_id, $name, $value);
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

