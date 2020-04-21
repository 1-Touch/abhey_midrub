<?php
/**
 * Employees Helpers
 *
 * This file contains the class employees
 * with methods to process the employees data
 *
 * @author Brijesh
 * @package Midrub
 * @since 0.0.7.6
 */

// Define the page namespace
namespace MidrubBase\User\Apps\Collection\PHPAnalyzer\Helpers;

defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * Employees class provides the methods to process the employees data
 * 
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.6
*/
class PHPAnalyzer {
    
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
        
        // Load the lists model
        $this->CI->load->ext_model( MIDRUB_BASE_USER_APPS_PHPANALYZER . 'models/', 'PHPAnalyzer', 'phpanalyzer' );
        
    }
    
    /**
     * The public method achieve_create_new_employee creates a new employee
     * 
     * @since 0.0.7.5
     * 
     * @return void
     */ 
    //Start View Facebook Settings//
    public function phpanalyzer_user() 
    {
        $username = 'Facebook';

        $user= $this->CI->phpanalyzer->get_facebook_user_detail($username);
         
        $data = array(
                'success' => TRUE,
                'user' => $user['detail']
            );

        echo json_encode($data); 
        
        
    }

    public function exportCSV($username)
    { 
        //$username = $this->CI->input->get('username', TRUE);
        $username = 'Facebook';
        $user_detail = $this->facebook->get_csv_data($username);

        $filename = 'users_'.date('Ymd').'.csv'; 
        header("Content-Description: File Transfer"); 
        header("Content-Disposition: attachment; filename=$filename"); 
        header("Content-Type: application/csv; ");

        // get data 


        // file creation 
        $file = fopen('php://output', 'w');

        $header = array("Username","Likes","Followers","Date"); 
        fputcsv($file, $header);
        foreach ($user_detail as $key=>$line)
        {
            fputcsv($file,$line); 
        }
        fclose($file); 
        exit; 
    }

    public function phpanalyzer_user_ajax() 
    {
        // Check if the session exists and if the login user is admin
        $this->check_session($this->user_role, 1);
        $username = $this->input->post('username');
        $user_detail = $this->facebook->get_users_name($username);
        
        $user_list = "<ul>";
        if(isset($user_detail)) 
        { 
            foreach($user_detail as $list) 
            {
                $user_list .= '<li>'.$list->username.'</li>';
            }
        } 
        else 
        {
            $user_list .= '<li>No User Found</li>';
        }
        $user_list .= '</ul>';
        echo $user_list;
    }
    
    
}

