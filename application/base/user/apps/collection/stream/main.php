<?php
/**
 * Midrub Apps Stream
 *
 * This file loads the Stream app
 *
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.5
 */

// Define the page namespace
namespace MidrubBase\User\Apps\Collection\Stream;

// Define the constants
defined('BASEPATH') OR exit('No direct script access allowed');
defined('MIDRUB_BASE_USER_APPS_STREAM') OR define('MIDRUB_BASE_USER_APPS_STREAM', MIDRUB_BASE_USER . 'apps/collection/stream/');
defined('MIDRUB_BASE_USER_APPS_STREAM_VERSION') OR define('MIDRUB_BASE_USER_APPS_STREAM_VERSION', '0.0.7');
defined('MIDRUB_STREAM_FACEBOOK_GRAPH_VERSION') OR define('MIDRUB_STREAM_FACEBOOK_GRAPH_VERSION', 'v3.3');
defined('MIDRUB_STREAM_FACEBOOK_GRAPH_URL') OR define('MIDRUB_STREAM_FACEBOOK_GRAPH_URL', 'https://graph.facebook.com/' . MIDRUB_STREAM_FACEBOOK_GRAPH_VERSION . '/');

// Define the namespaces to use
use MidrubBase\User\Interfaces as MidrubBaseUserInterfaces;
use MidrubBase\User\Apps\Collection\Stream\Controllers as MidrubBaseUserAppsCollectionStreamControllers;

/*
 * Main class loads the Inbox app loader
 * 
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.5
 */
class Main implements MidrubBaseUserInterfaces\Apps {
    
    /**
     * Class variables
     *
     * @since 0.0.7.5
     */
    protected
            $CI;

    /**
     * Initialise the Class
     *
     * @since 0.0.7.5
     */
    public function __construct() {
        
        // Assign the CodeIgniter super-object
        $this->CI =& get_instance();
        
        // Load language
        $this->CI->lang->load( 'stream_user', $this->CI->config->item('language'), FALSE, TRUE, MIDRUB_BASE_USER_APPS_STREAM);
        
    }

    /**
     * The public method check_availability checks if the app is available
     *
     * @return boolean true or false
     */
    public function check_availability() {

        if ( !get_option('app_stream_enable') || !plan_feature('app_stream') || !team_role_permission('stream') ) {
            return false;
        } else {
            return true;
        }
        
    }
    
    /**
     * The public method user loads the app's main page in the user panel
     * 
     * @since 0.0.7.5
     * 
     * @return void
     */
    public function user() {
        
        // Verify if the app is enabled
        if ( !get_option('app_stream_enable') || !plan_feature('app_stream') ) {
            show_404();
        }
        
        // Instantiate the class
        (new MidrubBaseUserAppsCollectionStreamControllers\User)->view();
        
    }
    
    /**
     * The public method ajax processes the ajax's requests
     * 
     * @since 0.0.7.5
     * 
     * @return void
     */
    public function ajax() {

        // Verify if the app is enabled
        if ( !get_option('app_stream_enable') || !plan_feature('app_stream') ) {
            exit();
        }
        
        // Get action's get input
        $action = $this->CI->input->get('action');

        if ( !$action ) {
            $action = $this->CI->input->post('action');
        }
        
        try {
            
            // Call method if exists
            (new MidrubBaseUserAppsCollectionStreamControllers\Ajax)->$action();
            
        } catch (Exception $ex) {
            
            $data = array(
                'success' => FALSE,
                'message' => $ex->getMessage()
            );
            
            echo json_encode($data);
            
        }
        
    }

    /**
     * The public method rest processes the rest's requests
     * 
     * @param string $endpoint contains the requested endpoint
     * 
     * @since 0.0.7.9
     * 
     * @return void
     */
    public function rest($endpoint) {

    }
    
    /**
     * The public method cron_jobs loads the cron jobs commands
     * 
     * @since 0.0.7.5
     * 
     * @return void
     */
    public function cron_jobs() {
        
    }
    
    /**
     * The public method delete_account is called when user's account is deleted
     * 
     * @param integer $user_id contains the user's ID
     * 
     * @since 0.0.7.5
     * 
     * @return void
     */
    public function delete_account($user_id) {
        
        // Load models
        $this->CI->load->ext_model( MIDRUB_BASE_USER_APPS_STREAM . 'models/', 'Stream_tabs_model', 'stream_tabs_model' );
        
        $this->CI->stream_tabs_model->delete_tab_records( $user_id );
        
    }
    
    /**
     * The public method hooks contains the app's hooks
     *
     * @param string $category contains the hooks category
     *
     * @since 0.0.7.9
     *
     * @return void
     */
    public function load_hooks( $category ) {
        
        // Load and run hooks based on category
        switch ( $category ) {
                
            case 'admin_init':
                
                // Load the admin app's language files
                $this->CI->lang->load('stream_admin', $this->CI->config->item('language'), FALSE, TRUE, MIDRUB_BASE_USER_APPS_STREAM);
                
                // Verify which component is
                if ( ( md_the_component_variable('component') === 'user' ) && ( $this->CI->input->get('app', TRUE) === 'stream' ) ) {
                    
                    // Require the Admin Inc
                    get_the_file(MIDRUB_BASE_USER_APPS_STREAM . 'inc/admin.php');
                    
                } else if ( ( md_the_component_variable('component') === 'user' ) && ( md_the_component_variable('component_display') === 'plans' ) ) {
                    
                    // Require the Plans Inc
                    get_the_file(MIDRUB_BASE_USER_APPS_STREAM . 'inc/plans.php');
                    
                }
                
                break;

            case 'user_init':

                // Verify which component is
                if ( md_the_component_variable('component') === 'team' ) {

                    if ( get_option('app_stream_enable') && plan_feature('app_stream') ) {

                        // Load the app's language files
                        $this->CI->lang->load('stream_member', $this->CI->config->item('language'), FALSE, TRUE, MIDRUB_BASE_USER_APPS_STREAM);

                        // Require the Permissions Inc
                        get_the_file(MIDRUB_BASE_USER_APPS_STREAM . 'inc/members.php');

                    }

                }

                // Load the Stream Model
                $this->CI->load->ext_model(MIDRUB_BASE_USER_APPS_STREAM . 'models/', 'Stream_networks_model', 'stream_networks_model');

                // Add hook in the queue
                add_hook(
                    'delete_network_account',
                    function ($args) {

                        // Delete network's account
                        $this->CI->stream_networks_model->delete_account_records($this->CI->user_id, $args['account_id']);

                    }

                );

                break;
                
        }
        
    }
    
    /**
     * The public method guest contains the app's access for guests
     *
     * @since 0.0.7.9
     *
     * @return void
     */
    public function guest() {
        
        // Display 404 page
        show_404();
        
    }
    
    /**
     * The public method app_info contains the app's info
     * 
     * @since 0.0.7.5
     * 
     * @return array with app's information
     */
    public function app_info() {
        
        // Load the app's language files
        $this->CI->lang->load( 'stream_user', $this->CI->config->item('language'), FALSE, TRUE, MIDRUB_BASE_USER_APPS_STREAM);
        
        // Return app information
        return array(
            'app_name' => $this->CI->lang->line('stream'),
            'app_slug' => 'stream',
            'app_icon' => '<i class="icon-grid"></i>',
            'version' => MIDRUB_BASE_USER_APPS_STREAM_VERSION,
            'min_version' => '0.0.7.9',
            'max_version' => '0.0.7.9'
        );
        
    }

}
