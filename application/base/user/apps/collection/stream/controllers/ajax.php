<?php
/**
 * Ajax Controller
 *
 * This file processes the app's ajax calls
 *
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.5
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
 * Ajaz class processes the app's ajax calls
 * 
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.5
 */
class Ajax {
    
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
        $this->CI->load->ext_model( MIDRUB_BASE_USER_APPS_STREAM . 'models/', 'Stream_tabs_model', 'stream_tabs_model' );
        $this->CI->load->ext_model( MIDRUB_BASE_USER_APPS_STREAM . 'models/', 'Stream_networks_model', 'stream_networks_model' );
        $this->CI->load->ext_model( MIDRUB_BASE_USER_APPS_STREAM . 'models/', 'Stream_history_model', 'stream_history_model' );
        $this->CI->load->ext_model( MIDRUB_BASE_USER_APPS_STREAM . 'models/', 'Stream_lists_model', 'stream_lists_model' );
        
    }
    
    /**
     * The public method stream_tab_refresh updaes the Tab's refresh interval
     * 
     * @since 0.0.7.5
     * 
     * @return void
     */
    public function stream_tab_refresh() {
        
        // Load Stream's Tabs
        (new MidrubBaseUserAppsCollectionStreamHelpers\Tabs)->stream_tab_refresh();
        
    }    
    
    /**
     * The public method stream_create_new_stream_tab creates new stream's tab
     * 
     * @since 0.0.7.5
     * 
     * @return void
     */
    public function stream_create_new_stream_tab() {
        
        // Create a new Stream's Tab
        (new MidrubBaseUserAppsCollectionStreamHelpers\Tabs)->stream_create_new_stream_tab();
        
    }
    
    /**
     * The public method stream_load_connection_settings loads template's connections settings
     * 
     * @since 0.0.7.5
     * 
     * @return void
     */
    public function stream_load_connection_settings() {
        
        // Loads template's connections settings
        (new MidrubBaseUserAppsCollectionStreamHelpers\Template)->stream_load_connection_settings();
        
    }

    /**
     * The public method stream_search_for_social_accounts searches network's accounts
     * 
     * @since 0.0.7.5
     * 
     * @return void
     */
    public function stream_search_for_social_accounts() {
        
        // Search for accounts
        (new MidrubBaseUserAppsCollectionStreamHelpers\Accounts)->search_accounts();
        
    }   
    
    /**
     * The public method stream_connect_new_stream connects a new stream
     * 
     * @since 0.0.7.5
     * 
     * @return void
     */
    public function stream_connect_new_stream() {
        
        // Get template
        (new MidrubBaseUserAppsCollectionStreamHelpers\Connection)->stream_template_load();
        
    }
    
    /**
     * The public method stream_save_new_stream_with_url connects a new stream
     * 
     * @since 0.0.7.5
     * 
     * @return void
     */
    public function stream_save_new_stream_with_url() {
        
        // Get template
        (new MidrubBaseUserAppsCollectionStreamHelpers\Connection)->stream_save_new_stream_with_url();
        
    }    
    
    /**
     * The public method stream_delete_tab_streams deletes stream's tab
     * 
     * @since 0.0.7.5
     * 
     * @return void
     */
    public function stream_delete_tab_streams() {
        
        // Delete tab
        (new MidrubBaseUserAppsCollectionStreamHelpers\Tabs)->stream_delete_tab_streams();
        
    }
    
    /**
     * The public method stream_save_stream_order saves stream's order
     * 
     * @since 0.0.7.5
     * 
     * @return void
     */
    public function stream_save_stream_order() {
        
        // Save order
        (new MidrubBaseUserAppsCollectionStreamHelpers\Template)->stream_save_stream_order();
        
    }
    
    /**
     * The public method stream_get_setup get stream's setup
     * 
     * @since 0.0.7.5
     * 
     * @return void
     */
    public function stream_get_setup() {
        
        // Get stream's setup
        (new MidrubBaseUserAppsCollectionStreamHelpers\Template)->stream_get_setup();
        
    }
    
    /**
     * The public method stream_mark_seen marks stream's setup
     * 
     * @since 0.0.7.5
     * 
     * @return void
     */
    public function stream_mark_seen() {
        
        // Mark stream as seen
        (new MidrubBaseUserAppsCollectionStreamHelpers\Template)->stream_mark_seen();
        
    }    
    
    /**
     * The public method stream_delete_selected_stream deletes a stream
     * 
     * @since 0.0.7.5
     * 
     * @return void
     */
    public function stream_delete_selected_stream() {
        
        // Delete stream by id
        (new MidrubBaseUserAppsCollectionStreamHelpers\Template)->stream_delete_selected_stream();
        
    } 
    
    /**
     * The public method stream_update_setup updates the stream's setup
     * 
     * @since 0.0.7.5
     * 
     * @return void
     */
    public function stream_update_setup() {
        
        // Update stream by id
        (new MidrubBaseUserAppsCollectionStreamHelpers\Template)->stream_update_setup();
        
    }
    
    /**
     * The public method stream_send_react sends a reaction
     * 
     * @since 0.0.7.6
     * 
     * @return void
     */
    public function stream_send_react() {
        
        // Sends reaction
        (new MidrubBaseUserAppsCollectionStreamHelpers\Template)->stream_send_react();
        
    }
    
    /**
     * The public method stream_delete_setup deletes the stream's setup
     * 
     * @since 0.0.7.5
     * 
     * @return void
     */
    public function stream_delete_setup() {
        
        // Delete stream by id
        (new MidrubBaseUserAppsCollectionStreamHelpers\Template)->stream_delete_setup();
        
    }    
    
    /**
     * The public method stream_connect_tab_steams loads the Tab's streams
     * 
     * @since 0.0.7.5
     * 
     * @return void
     */
    public function stream_connect_tab_steams() {
        
        // Get tab's streams
        (new MidrubBaseUserAppsCollectionStreamHelpers\Start)->stream_connect_tab_steams();
        
    }    
    
    /**
     * The public method stream_change_settings_color changes streams colors
     * 
     * @since 0.0.7.5
     * 
     * @return void
     */
    public function stream_change_settings_color() {
        
        // Change stream's colors
        (new MidrubBaseUserAppsCollectionStreamHelpers\Template)->stream_change_settings_color();
        
    }
    
    /**
     * The public method stream_item_action_link process the link request
     * 
     * @since 0.0.7.5
     * 
     * @return void
     */
    public function stream_item_action_link() {
        
        // Process the link request
        (new MidrubBaseUserAppsCollectionStreamHelpers\Actions)->stream_item_action_link();
        
    }  
    
    /**
     * The public method stream_select_sound_alert saves or deletes a sound
     * 
     * @since 0.0.7.5
     * 
     * @return void
     */
    public function stream_select_sound_alert() {
        
        // Save or delete sound alert
        (new MidrubBaseUserAppsCollectionStreamHelpers\Template)->stream_select_sound_alert();
        
    }   
    
    /**
     * The public method stream_get_streams_templates gets streams templates by category
     * 
     * @since 0.0.7.5
     * 
     * @return void
     */
    public function stream_get_streams_templates() {
        
        // Get templates
        (new MidrubBaseUserAppsCollectionStreamHelpers\Categories)->stream_get_streams_templates();
        
    }   
    
    /**
     * The public method stream_template_content_single gets single template
     * 
     * @since 0.0.7.5
     * 
     * @return void
     */
    public function stream_template_content_single() {
        
        // Get template's item
        (new MidrubBaseUserAppsCollectionStreamHelpers\Single)->stream_template_content_single();
        
    }    
    
    /**
     * The public method account_manager_load_networks loads available social networks
     * 
     * @since 0.0.7.5
     * 
     * @return void
     */
    public function account_manager_load_networks() {
        
        // Get available social networks
        (new MidrubBaseUserAppsCollectionStreamHelpers\Account_manager)->load_networks();
        
    }
    
    /**
     * The public method account_manager_get_accounts gets accounts by social network
     * 
     * @since 0.0.7.5
     * 
     * @return void
     */
    public function account_manager_get_accounts() {
        
        // Get accounts by social networks
        (new MidrubBaseUserAppsCollectionStreamHelpers\Account_manager)->get_accounts();
        
    }
    
    /**
     * The public method account_manager_delete_accounts delete an account
     * 
     * @since 0.0.7.5
     * 
     * @return void
     */
    public function account_manager_delete_accounts() {
        
        // Delete accounts
        (new MidrubBaseUserAppsCollectionStreamHelpers\Accounts)->delete_accounts();
        
    }
    
    /**
     * The public method stream_search_accounts gets accounts
     * 
     * @since 0.0.7.5
     * 
     * @return void
     */
    public function stream_search_accounts() {
        
        // Gets accounts
        (new MidrubBaseUserAppsCollectionStreamHelpers\Accounts)->stream_search_accounts();
        
    }
    
    /**
     * The public method stream_search_groups gets groups
     * 
     * @since 0.0.7.5
     * 
     * @return void
     */
    public function stream_search_groups() {
        
        // Gets groups
        (new MidrubBaseUserAppsCollectionStreamHelpers\Accounts)->stream_search_groups();
        
    }   
    
    /**
     * The public method account_manager_search_for_accounts search accounts by key and network
     * 
     * @since 0.0.7.5
     * 
     * @return void
     */
    public function account_manager_search_for_accounts() {
        
        // Search accounts
        (new MidrubBaseUserAppsCollectionStreamHelpers\Accounts)->account_manager_search_for_accounts();
        
    } 
    
    /**
     * The public method accounts_manager_groups_available_accounts gets all available group's accounts
     * 
     * @since 0.0.7.5
     * 
     * @return void
     */
    public function accounts_manager_groups_available_accounts() {
        
        // Gets available group accounts
        (new MidrubBaseUserAppsCollectionStreamHelpers\Groups)->available_group_accounts();
        
    }
    
    /**
     * The public method account_manager_remove_account_from_group removes accounts from a group
     * 
     * @since 0.0.7.5
     * 
     * @return void
     */
    public function account_manager_remove_account_from_group() {
        
        // Remove account
        (new MidrubBaseUserAppsCollectionStreamHelpers\Groups)->remove_account();
        
    }
    
    /**
     * The public method account_manager_add_account_to_group adds account to group
     * 
     * @since 0.0.7.5
     * 
     * @return void
     */
    public function account_manager_add_account_to_group() {
        
        // Add account
        (new MidrubBaseUserAppsCollectionStreamHelpers\Groups)->add_account();
        
    }
    
    /**
     * The public method accounts_manager_groups_delete_group deletes a group
     * 
     * @since 0.0.7.5
     * 
     * @return void
     */
    public function accounts_manager_groups_delete_group() {
        
        // Delete group 
        (new MidrubBaseUserAppsCollectionStreamHelpers\Groups)->delete_group();
        
    }
    
    /**
     * The public method account_manager_create_accounts_group creates a new group
     * 
     * @since 0.0.7.5
     * 
     * @return void
     */
    public function account_manager_create_accounts_group() {
        
        // Create a new group
        (new MidrubBaseUserAppsCollectionStreamHelpers\Groups)->save_group();
        
    }
    
    /**
     * The public method composer_publish_post publishes a post
     * 
     * @since 0.0.7.5
     * 
     * @return void
     */
    public function composer_publish_post() {
        
        // Publish a post
        (new MidrubBaseUserAppsCollectionStreamHelpers\Posts)->composer_publish_post();
        
    }
    
}
