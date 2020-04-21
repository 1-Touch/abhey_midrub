<?php
/**
 * Ajax Controller
 *
 * This file processes the app's ajax calls
 *
 * @author Brijesh
 * @package Midrub
 * @since 0.0.7.6
 */

// Define the page namespace
namespace MidrubBase\User\Apps\Collection\PHPAnalyzer\Controllers;

defined('BASEPATH') OR exit('No direct script access allowed');

// Define the namespaces to use
use MidrubBase\User\Apps\Collection\PHPAnalyzer\Helpers as MidrubBaseUserAppsCollectionPHPAnalyzerHelpers;

/*
 * Ajax class processes the app's ajax calls
 * 
 * @author Brijesh
 * @package Midrub
 * @since 0.0.7.6
 */
class Ajax {
    
    /**
     * Class variables
     *
     * @since 0.0.7.6
     */
    protected $CI;

    /**
     * Initialise the Class
     *
     * @since 0.0.7.6
     */
    public function __construct() {
        
        // Get codeigniter object instance
        $this->CI =& get_instance();

        // Load language
        $this->CI->lang->load( 'phpanalyzer_user', $this->CI->config->item('language'), FALSE, TRUE, MIDRUB_BASE_USER_APPS_PHPANALYZER );
        
    }
    
    /**
     * The public method achieve_create_new_employee creates a new employee
     * 
     * @since 0.0.7.6
     * 
     * @return void
    */
    
    /**
     * The public method get_employees gets all employees
     * 
     * @since 0.0.7.6
     * 
     * @return void
     */
    public function phpanalyzer_user() 
    {
        
       (new MidrubBaseUserAppsCollectionPHPAnalyzerHelpers\PHPAnalyzer)->phpanalyzer_user();
        
    }

    public function exportCSV() {

        (new MidrubBaseUserAppsCollectionPHPAnalyzerHelpers\PHPAnalyzer)->exportCSV();
        
    }

    public function phpanalyzer_user_ajax() {

        (new MidrubBaseUserAppsCollectionPHPAnalyzerHelpers\PHPAnalyzer)->phpanalyzer_user_ajax();
        
    }



    /**
     * The public method get_employee get employee data
     * 
     * @since 0.0.7.6
     * 
     * @return void
     */
    
        
}
