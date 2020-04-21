<?php
/**
 * Start Helper
 *
 * This file contains the class Start
 * with methods to process when the Stream's page loads
 *
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.
 */

// Define the page namespace
namespace MidrubBase\User\Apps\Collection\Stream\Helpers;

// Constants
defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * Start class provides the methods to process the methods when the Stream's page loads
 * 
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.5
*/
class Start {
    
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
        
    }

    /**
     * The public method stream_connect_tab_steams displays streams by tab
     *
     * @since 0.0.7.5
     * 
     * @return void
     */
    public function stream_connect_tab_steams() {
        
        // Get tab_id's input
        $tab_id = $this->CI->input->get('tab_id');
        
        // Get streams by Tab's ID
        $streams = $this->stream_load_tab_streams($tab_id);
        
        // Verify if cache exists
        if ( file_exists(MIDRUB_BASE_USER_APPS_STREAM . 'cache/backup-' . $tab_id . '.html') ) {
            unlink(MIDRUB_BASE_USER_APPS_STREAM . 'cache/backup-' . $tab_id . '.html');
        }
        
        // Save cache
        file_put_contents( MIDRUB_BASE_USER_APPS_STREAM . 'cache/backup-' . $tab_id . '.html', $streams);

        $data = array(
            'success' => TRUE,
            'tab_streams' => $streams,
            'tab_id' => $tab_id
        );

        echo json_encode($data);
        
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

                    if ( file_exists( MIDRUB_BASE_USER_APPS_STREAM . 'templates/' . strtolower($ids[$e]->template) . '.php' ) ) {

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
    
}

