<?php
/**
 * Init Controller
 *
 * This file loads the Reset Auth Component
 *
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.8
 */

// Define the page namespace
namespace MidrubBase\Auth\Collection\Reset\Controllers;

defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * Init class loads the Reset Component
 * 
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.8
 */
class Init {
    
    /**
     * Class variables
     *
     * @since 0.0.7.8
     */
    protected $CI;

    /**
     * Initialise the Class
     *
     * @since 0.0.7.8
     */
    public function __construct() {
        
        // Get codeigniter object instance
        $this->CI =& get_instance();
        
        // Load the component's language files
        if ( file_exists( MIDRUB_BASE_AUTH_RESET . '/language/' . $this->CI->config->item('language') . '/auth_reset_lang.php' ) ) {
            $this->CI->lang->load( 'auth_reset', $this->CI->config->item('language'), FALSE, TRUE, MIDRUB_BASE_AUTH_RESET . '/' );
        }
        
    }
    
    /**
     * The public method view loads the settings's template
     * 
     * @since 0.0.7.8
     * 
     * @return void
     */
    public function view() {

        // If session exists, redirect user
        if ( md_the_user_session() ) {
            redirect(md_the_user_session()['redirect']);
        }

        // Get component's title
        $title = (md_the_single_content_meta('quick_seo_page_title'))?md_the_single_content_meta('quick_seo_page_title'):$this->CI->lang->line('auth_reset_page_title');

        // Set page's title
        md_set_the_title($title);

        // Set styles
        md_set_css_urls(array('stylesheet', base_url('assets/base/auth/collection/reset/styles/css/styles.css?ver=' . MIDRUB_BASE_AUTH_RESET_VERSION), 'text/css', 'all'));

        // Set javascript links
        md_set_js_urls(array(base_url('assets/base/auth/collection/reset/js/main.js?ver=' . MIDRUB_BASE_AUTH_RESET_VERSION)));

        // Verify if meta description exists
        if ( md_the_single_content_meta('quick_seo_meta_description') ) {

            // Set meta description
            md_set_the_meta_description(md_the_single_content_meta('quick_seo_meta_description'));

        }

        // Verify if meta keywords exists
        if ( md_the_single_content_meta('quick_seo_meta_keywords') ) {

            // Set meta keywors
            md_set_the_meta_keywords(md_the_single_content_meta('quick_seo_meta_keywords'));

        }

        // Making temlate and send data to view.
        $this->CI->template['header'] = $this->CI->load->ext_view(MIDRUB_BASE_AUTH_RESET .  '/views/layout', 'header', array(), true);
        $this->CI->template['body'] = $this->CI->load->ext_view(MIDRUB_BASE_AUTH_RESET .  '/views', 'main', array(), true);
        $this->CI->template['footer'] = $this->CI->load->ext_view(MIDRUB_BASE_AUTH_RESET .  '/views/layout', 'footer', array(), true);
        $this->CI->load->ext_view(MIDRUB_BASE_AUTH_RESET . '/views/layout', 'index', $this->CI->template);
        
    }

}
