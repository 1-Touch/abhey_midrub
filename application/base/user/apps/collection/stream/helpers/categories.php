<?php
/**
 * Categories Helpers
 *
 * This file contains the class Categories
 * with methods to process stream's by category
 *
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.5
 */

// Define the page namespace
namespace MidrubBase\User\Apps\Collection\Stream\Helpers;

defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * Categories class provides the methods to process the stream's by category
 * 
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.5
*/
class Categories {
    
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
     * The public method stream_get_streams_templates gets stream's templates by category
     * 
     * @since 0.0.7.5
     * 
     * @return void
     */ 
    public function stream_get_streams_templates() {
                
        // Check if data was submitted
        if ($this->CI->input->post()) {
            
            // Add form validation
            $this->CI->form_validation->set_rules('category', 'Category', 'trim|required');
            
            // Get data
            $category = $this->CI->input->post('category');
            
            if ( $this->CI->form_validation->run() !== false ) {
                
                // Load language
                $this->CI->lang->load( 'stream_instructions', $this->CI->config->item('language'), FALSE, TRUE, MIDRUB_BASE_USER_APPS_STREAM );

                // Define the categories array
                $categories = array();
                
                // Define the parent variable
                $parent = '';

                // Get all available Stream's templates
                foreach ( glob(MIDRUB_BASE_USER_APPS_STREAM . 'templates/*.php') as $filename ) {

                    // Get the class's name
                    $className = trim(basename($filename, '.php').PHP_EOL);

                    // Create an array
                    $array = array(
                        'MidrubBase',
                        'User',
                        'Apps',
                        'Collection',
                        'Stream',
                        'Templates',
                        $className
                    );       

                    // Implode the array above
                    $cl = implode('\\',$array);

                    // Get template's info
                    $template_info = (new $cl())->template_info();

                    // Verify if parent exists
                    if ( !isset($template_info['parent']) ) {
                        continue;
                    }
                    
                    // Verify if parent meeets the category
                    if ( $template_info['parent'] !== $category ) {
                        continue;
                    } else {
                        $parent = $template_info['parent'];
                    }

                    // Verify if the network's class already was called
                    if ( isset($categories[$template_info['parent']]) ) {

                        $categories[$template_info['parent']]['templates'][] = array(
                            'displayed_name' => $template_info['displayed_name'],
                            'description' => $template_info['description'],
                            'icon' => $template_info['icon'],
                            'color' => $categories[$template_info['parent']]['network_info']['color'],
                            'template_name' => $className
                        );

                        $categories[$template_info['parent']]['count']++;

                    } else {

                        $first = 0;

                        if ( !$categories ) {
                            $first = 1;
                        }
                        


                        // Verify if network class exists
                        if ( $template_info['parent'] === 'miscellaneous' ) {

                            $categories[$template_info['parent']] = array(
                                'category' => $this->CI->lang->line('miscellaneous'),
                                'network_info' => (object) array(
                                    'color' => '#59c3b3',
                                    'icon' => '<i class="icon-puzzle"></i>'
                                ),
                                'count' => 1,
                                'templates' => array(
                                    array(
                                        'displayed_name' => $template_info['displayed_name'],
                                        'description' => $template_info['description'],
                                        'icon' => $template_info['icon'],
                                        'template_name' => $className,
                                        'color' => '#59c3b3'
                                    )
                                ),
                                'first' => $first
                            );

                        } else if ( file_exists(MIDRUB_BASE_USER . 'networks/' . strtolower($template_info['parent']) . '.php') && get_option($template_info['parent']) & plan_feature($template_info['parent']) ) {

                            // Create an array
                            $array = array(
                                'MidrubBase',
                                'User',
                                'Networks',
                                ucfirst($template_info['parent'])
                            );

                            // Implode the array above
                            $cl = implode('\\', $array);

                            // Get network's info
                            $net_info = (new $cl())->get_info();

                            $categories[$template_info['parent']] = array(
                                'category' => $template_info['parent'],
                                'network_info' => $net_info,
                                'count' => 1,
                                'templates' => array(
                                    array(
                                        'displayed_name' => $template_info['displayed_name'],
                                        'description' => $template_info['description'],
                                        'icon' => $template_info['icon'],
                                        'template_name' => $className,
                                        'color' => $net_info['color']
                                    )
                                ),
                                'first' => $first
                            );

                        } else {

                            continue;

                        }

                    }
                    
                }
                
                if ( isset($categories[$parent]['templates']) ) {
                    
                    $data = array(
                        'success' => TRUE,
                        'templates' => $categories[$parent]['templates'],
                        'category' => $category,
                        'connect' => $this->CI->lang->line('connect')
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
        
    }
    
}

