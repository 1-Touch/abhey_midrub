<?php
/**
 * User Controller
 *
 * This file loads the Stream app in the user panel
 *
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.4
 */

// Define the page namespace
namespace MidrubBase\User\Apps\Collection\Stream\Controllers;

// Constants
defined('BASEPATH') OR exit('No direct script access allowed');

// Define the namespaces to use
use MidrubBase\User\Apps\Collection\Stream\Helpers as MidrubBaseUserAppsCollectionStreamHelpers;

// Require the functions file
require_once MIDRUB_BASE_USER_APPS_STREAM . 'inc/functions.php';

/*
 * User class loads the Stream app loader
 * 
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.4
 */
class User {
    
    /**
     * Class variables
     *
     * @since 0.0.7.4
     */
    protected $CI;

    /**
     * Initialise the Class
     *
     * @since 0.0.7.4
     */
    public function __construct() {
        
        // Get codeigniter object instance
        $this->CI =& get_instance();
        
        // Load models
        $this->CI->load->ext_model( MIDRUB_BASE_USER_APPS_STREAM . 'models/', 'Stream_tabs_model', 'stream_tabs_model' );
        $this->CI->load->ext_model( MIDRUB_BASE_USER_APPS_STREAM . 'models/', 'Stream_networks_model', 'stream_networks_model' );
        $this->CI->load->ext_model( MIDRUB_BASE_USER_APPS_STREAM . 'models/', 'Stream_history_model', 'stream_history_model' );
        
        // Load language
        $this->CI->lang->load( 'stream_user', $this->CI->config->item('language'), FALSE, TRUE, MIDRUB_BASE_USER_APPS_STREAM);
        
    }
    
    /**
     * The public method view loads the app's template
     * 
     * @since 0.0.7.4
     * 
     * @return void
     */
    public function view() {

        // Set the page's title
        set_the_title($this->CI->lang->line('stream'));

        // Set the Stream's styles
        set_css_urls(array('stylesheet', base_url('assets/base/user/apps/collection/stream/styles/css/styles.css?ver=' . MIDRUB_BASE_USER_APPS_STREAM_VERSION), 'text/css', 'all'));

        // Set the EmojioneArea's styles
        set_css_urls(array('stylesheet', base_url('assets/base/user/apps/collection/posts/js/emojionearea-master/dist/emojionearea.min.css?ver=' . MIDRUB_BASE_USER_APPS_STREAM_VERSION), 'text/css', 'all'));

        // Set the Bootsrap Picker's styles
        set_css_urls(array('stylesheet', base_url('assets/base/user/apps/collection/stream/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css?ver=' . MIDRUB_BASE_USER_APPS_STREAM_VERSION), 'text/css', 'all'));  

        // Set jQuery UI's Js
        set_js_urls(array(base_url('assets/js/jquery-ui.min.js?ver=' . MIDRUB_BASE_USER_APPS_STREAM_VERSION)));

        // Set Bootsrap Picker's Js
        set_js_urls(array(base_url('assets/base/user/apps/collection/stream/bootstrap-colorpicker/js/bootstrap-colorpicker.js?ver=' . MIDRUB_BASE_USER_APPS_STREAM_VERSION) ));    
        
        // Set EmojioneArea's Js
        set_js_urls(array(base_url('assets/base/user/apps/collection/posts/js/emojionearea-master/dist/emojionearea.min.js?ver=' . MIDRUB_BASE_USER_APPS_STREAM_VERSION)));   
        
        // Set Stream's Js
        set_js_urls(array(base_url('assets/base/user/apps/collection/stream/js/main.js?ver=' . MIDRUB_BASE_USER_APPS_STREAM_VERSION))); 
        
        // Set Chart's Js
        set_js_urls(array('//www.chartjs.org/dist/2.7.2/Chart.js'));
        set_js_urls(array('//www.chartjs.org/samples/latest/utils.js'));
        
        // Set Media's Js
        set_js_urls(array(base_url('assets/user/js/media.js?ver=' . MIDRUB_BASE_USER_APPS_STREAM_VERSION)));        
        
        // Load language
        $this->CI->lang->load( 'stream_instructions', $this->CI->config->item('language'), FALSE, TRUE, MIDRUB_BASE_USER_APPS_STREAM);
        
        // Load language
        $this->CI->lang->load( 'stream_accounts_manager', $this->CI->config->item('language'), FALSE, TRUE, MIDRUB_BASE_USER_APPS_STREAM);
        
        // Define the categories array
        $categories = array();
        
        // Get all available Stream's templates
        foreach ( glob(MIDRUB_BASE_USER_APPS_STREAM . 'templates/*.php') as $filename ) {

            // Get the class's name
            $className = ucfirst(trim(basename($filename, '.php').PHP_EOL));

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
            
            // Verify if the network's class already was called
            if ( isset($categories[$template_info['parent']]) ) {
                
                $categories[$template_info['parent']]['templates'][] = array(
                    'displayed_name' => $template_info['displayed_name'],
                    'description' => $template_info['description'],
                    'icon' => $template_info['icon'],
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
                        'category_name' => $this->CI->lang->line('miscellaneous'),
                        'category' => 'miscellaneous',
                        'network_info' => array(
                            'color' => '#59c3b3',
                            'icon' => '<i class="icon-puzzle"></i>'
                        ),
                        'count' => 1,
                        'templates' => array(
                            array(
                                'displayed_name' => $template_info['displayed_name'],
                                'description' => $template_info['description'],
                                'icon' => $template_info['icon'],
                                'template_name' => $className
                            )
                        ),
                        'first' => $first
                    );
                    
                } else if ( file_exists(MIDRUB_BASE_USER . 'networks/' . trim($template_info['parent']) . '.php') && get_option($template_info['parent']) & plan_feature($template_info['parent']) ) {

                    $class = ucfirst(trim($template_info['parent']));

                    // Create an array
                    $array = array(
                        'MidrubBase',
                        'User',
                        'Networks',
                        $class
                    );

                    // Implode the array above
                    $cl2 = implode('\\', $array);

                    $get = (new $cl2());

                    $categories[$template_info['parent']] = array(
                        'category_name' => $template_info['parent'],
                        'category' => $template_info['parent'],
                        'network_info' => $get->get_info(),
                        'count' => 1,
                        'templates' => array(
                            array(
                                'displayed_name' => $template_info['displayed_name'],
                                'description' => $template_info['description'],
                                'icon' => $template_info['icon'],
                                'template_name' => $className
                            )
                        ),
                        'first' => $first
                    );

                } else {

                    continue;

                }
            
            }

        }
        
        // Define the accounts list valiable
        $accounts_list = '';

        // Define the groups list variable
        $groups_list = '';

        // Verify if user wants groups instead accounts
        if ( get_user_option('settings_display_groups') ) {

            // Load Lists Model
            $this->CI->load->model('lists');

            // Get the user lists
            $groups_list = $this->CI->lists->get_lists( $this->CI->user_id, 0, 'social', 10 );

        } else {

            // Get accounts list
            $accounts_list = (new MidrubBaseUserAppsCollectionStreamHelpers\Accounts)->list_accounts_for_stream($this->CI->stream_networks_model->get_accounts( $this->CI->user_id, 0, 10 ));             

        }

        // Set views params
        set_user_view(
            $this->CI->load->ext_view(
                MIDRUB_BASE_USER_APPS_STREAM . 'views',
                'main',
                array(
                    'categories' => $categories,
                    'tabs' => (new MidrubBaseUserAppsCollectionStreamHelpers\Tabs)->stream_load_tabs(),
                    'accounts_list' => $accounts_list,
                    'groups_list' => $groups_list
                ),
                true
            )
        );
        
    }
    
}
