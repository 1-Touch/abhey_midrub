<?php
/**
 * Tabs Helpers
 *
 * This file contains the class Tabs
 * with methods to process the tabs data
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
 * Tabs class provides the methods to process the tabs data
 * 
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.5
*/
class Tabs {
    
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
     * The public method stream_load_tabs loads all available tabs
     * 
     * @since 0.0.7.5
     * 
     * @return array with tabs or false
     */ 
    public function stream_load_tabs() {
                
        $tabs = $this->CI->stream_tabs_model->all_user_tabs( $this->CI->user_id );

        if ( $tabs ) {
            
            // Get stream
            $stream = $this->stream_load_tab_streams($tabs[0]->tab_id);

            $data = array(
                'success' => TRUE,
                'tabs' => $tabs,
                'new_stream' => $this->CI->lang->line('new_stream'),
                'settings' => $this->CI->lang->line('settings'),
                'tab_streams' => $stream
            );

            return $data;  

        } else {
            
            $new_stream = $this->CI->stream_tabs_model->save_stream_tab( $this->CI->user_id, 'Home', 'icon-home' );

            if ( $new_stream ) {
                
                $tabs = $this->CI->stream_tabs_model->all_user_tabs( $this->CI->user_id );

                if ( $tabs ) {

                    // Get stream
                    $stream = $this->stream_load_tab_streams($tabs[0]->tab_id);
                    
                    $data = array(
                        'success' => TRUE,
                        'tabs' => $tabs,
                        'new_stream' => $this->CI->lang->line('new_stream'),
                        'settings' => $this->CI->lang->line('settings'),
                        'tab_streams' => $stream
                    );

                    return $data;  

                }
                
            }                   

        }
        
    }

    /**
     * The public method stream_create_new_stream_tab creates new stream's tab
     * 
     * @since 0.0.7.5
     * 
     * @return void
     */ 
    public function stream_create_new_stream_tab() {
        
        
        // Check if data was submitted
        if ($this->CI->input->post()) {
            
            // Add form validation
            $this->CI->form_validation->set_rules('tab_icon', 'Tab Icon', 'trim|required');
            $this->CI->form_validation->set_rules('tab_name', 'Tab Name', 'trim');
            
            // Get data
            $tab_icon = $this->CI->input->post('tab_icon');
            $tab_name = $this->CI->input->post('tab_name');
            
            if ( $this->CI->form_validation->run() == false ) {
                
                $data = array(
                    'success' => FALSE,
                    'message' => $this->CI->lang->line('tab_name_too_short')
                );

                echo json_encode($data);   
                
            } else {
                
                // Get all tabs
                $all_tabs = $this->CI->stream_tabs_model->all_user_tabs( $this->CI->user_id );
                
                $tabs = 0;

                if ( $all_tabs ) {
                    
                    if ( count($all_tabs) >= plan_feature('stream_tabs_limit') ) {
                        
                        $data = array(
                            'success' => FALSE,
                            'message' => $this->CI->lang->line('reached_maximum_number_tabs')
                        );

                        echo json_encode($data);
                        exit();
                        
                    }

                }
                
                $new_tab_stream = $this->CI->stream_tabs_model->save_stream_tab( $this->CI->user_id, $tab_name, $tab_icon );
                
                if ( $new_tab_stream ) {
                    
                    $data = array(
                        'success' => TRUE,
                        'message' => $this->CI->lang->line('tab_was_created'),
                        'tab_id' => $new_tab_stream,
                        'tab_name' => $tab_name,
                        'tab_icon' => $tab_icon,
                        'new_stream' => $this->CI->lang->line('new_stream'),
                        'settings' => $this->CI->lang->line('settings')
                    );

                    echo json_encode($data);  
                    
                } else {
                    
                    $data = array(
                        'success' => FALSE,
                        'message' => $this->CI->lang->line('tab_was_not_created')
                    );

                    echo json_encode($data);                     
                    
                }
                
            }
            
        }
        
    }
    
    /**
     * The public method stream_delete_tab_streams deletes tab's stream
     * 
     * @since 0.0.7.5
     * 
     * @return void
     */ 
    public function stream_delete_tab_streams() {
        
        // Get tab_id's input
        $tab_id = $this->CI->input->get('tab_id');
        
        if ( $tab_id ) {
            
            $delete_tab_stream = $this->CI->stream_tabs_model->delete_stream_tab( $tab_id, $this->CI->user_id );

            if ( $delete_tab_stream ) {

                $data = array(
                    'success' => TRUE,
                    'message' => $this->CI->lang->line('tab_was_deleted'),
                );

                echo json_encode($data);  

            } else {

                $data = array(
                    'success' => FALSE,
                    'message' => $this->CI->lang->line('tab_was_not_deleted')
                );

                echo json_encode($data);                     

            }
            
        } else {
            
            $data = array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('error_occurred')
            );

            echo json_encode($data);  
            
        }
        
    }
    
    /**
     * The public method stream_load_tab_streams loads tab's streams
     * 
     * @param integer $tab_id contains the tab's ID
     * 
     * @since 0.0.7.5
     * 
     * @return html with streams
     */ 
    public function stream_load_tab_streams($tab_id) {
        
        if ( file_exists(MIDRUB_BASE_USER_APPS_STREAM . 'cache/backup-' . $tab_id . '.html') ) {
            return file_get_contents(MIDRUB_BASE_USER_APPS_STREAM . 'cache/backup-' . $tab_id . '.html');
        }
            
        $tab_streams = $this->CI->stream_tabs_model->stream_load_tab_streams( $this->CI->user_id, $tab_id );
        
        $streams = '';

        if ( $tab_streams ) {
            
            $ids = array();
            
            foreach ( $tab_streams as $tab_stream ) {
                
                $ids[$tab_stream->stream_order] = $tab_stream;
                
            }
            
            for ( $e = 0; $e < 4; $e++ ) {
                
                if ( !isset($ids[$e]) ) {
                    
                    $streams .= '<div class="col-xl-3">'
                                    . '<div class="col-xl-12 stream-single stream-cover">'
                                    . '</div>'
                                . '</div>';
                    
                } else {

                    if ( file_exists( MIDRUB_BASE_USER_APPS_STREAM . 'templates/' . $ids[$e]->template . '.php' ) ) {

                        $array = array(
                            'MidrubBase',
                            'User',
                            'Apps',
                            'Collection',
                            'Stream',
                            'Templates',
                            $ids[$e]->template
                        );
                        
                        $cl = implode('\\',$array);
                        
                        if ( ( get_option( strtolower( $ids[$e]->network ) ) && plan_feature(strtolower($ids[$e]->network) ) ) || ( $ids[$e]->network === 'miscellaneous' ) ) {
                            
                            $stream_data = (new $cl())->template_content($ids[$e], TRUE);
                            
                            $background_color = '';

                            if ( $ids[$e]->background_color ) {
                                $background_color = ' style="background-color: ' . $ids[$e]->background_color . '"';
                            } 
                            
                            $header_text_color = '';
                            $border_bottom = '';

                            if ( $ids[$e]->header_text_color ) {

                                if ( $ids[$e]->border_color ) {
                                    $border_bottom = 'border-bottom-color: ' . $ids[$e]->border_color . ';';
                                }

                                $header_text_color = ' style="color: ' . $ids[$e]->header_text_color . ';' . $border_bottom . '"';

                            } else if ( $ids[$e]->border_color ) {
                                $header_text_color = ' style="border-bottom-color: ' . $ids[$e]->border_color . ';"';             
                            }                            

                            $content = '<div class="panel panel-default"' . $background_color . '>'
                                            . '<div class="panel-heading"' . $header_text_color . '>'
                                                . $stream_data['stream']['header']
                                            . '</div>'
                                            . '<div class="panel-body">'
                                                . $stream_data['stream']['content']
                                            . '</div>'
                                            . '<div class="panel-footer">'
                                                . $stream_data['stream']['footer']
                                            . '</div>'                
                                        . '</div>';
                            
                            $streams .= '<div class="col-xl-3">'
                                            . '<div class="col-xl-12 stream-single" data-stream="' . $ids[$e]->stream_id . '">'
                                                . $content
                                            . '</div>'
                                        . '</div>';
                        
                        }

                    }
                    
                }
                
            }
            

        } else {
            
            for ( $e = 0; $e < 4; $e++ ) {
                    
                $streams .= '<div class="col-xl-3">'
                                . '<div class="col-xl-12 stream-single stream-cover">'
                                . '</div>'
                            . '</div>';
                
            }
            
        }
        
        return $streams;
        
    }
    
    /**
     * The public method stream_tab_refresh updaes the Tab's refresh interval
     * 
     * @since 0.0.7.5
     * 
     * @return html with streams
     */ 
    public function stream_tab_refresh() {
        
        // Get tab_id's input
        $tab_id = $this->CI->input->get('tab_id');
        
        // Get the interval
        $interval = $this->CI->input->get('interval');
        
        if ( ($interval === 0) || ($interval != 15) || ($interval != 30) || ($interval != 45) || ($interval != 60) ) {
        
            if ( $this->CI->stream_tabs_model->stream_load_tab_streams($this->CI->user_id, $tab_id) ) {

                if ( $this->CI->stream_tabs_model->update_tab_field($tab_id, 'refresh', $interval) ) {

                    $data = array(
                        'success' => TRUE,
                        'message' => $this->CI->lang->line('mm2')
                    );

                    echo json_encode($data);
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
