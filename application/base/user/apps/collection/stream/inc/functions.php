<?php
/**
 * Stream Functions
 *
 * PHP Version 5.6
 *
 * I've created this file to store several generic 
 * functions called in the view's files
 *
 * @category Social
 * @package  Midrub
 * @author   Scrisoft <asksyn@gmail.com>
 * @license  https://www.gnu.org/licenses/gpl-2.0.html GNU General Public License
 * @link     https://www.midrub.com/
 */
if ( !defined('BASEPATH') ) {
    exit('No direct script access allowed');
}

if (!function_exists('addPropertyToResource')) {

    // Add a property to the resource.
    function addPropertyToResource(&$ref, $property, $value) {
        
        $keys = explode(".", $property);
        $is_array = false;

        foreach ($keys as $key) {
            // Convert a name like "snippet.tags[]" to "snippet.tags" and
            // set a boolean variable to handle the value like an array.
            if (substr($key, -2) == "[]") {
                $key = substr($key, 0, -2);
                $is_array = true;
            }
            $ref = &$ref[$key];
        }

        // Set the property value. Make sure array values are handled properly.
        if ($is_array && $value) {
            $ref = $value;
            $ref = explode(",", $value);
        } elseif ($is_array) {
            $ref = array();
        } else {
            $ref = $value;
        }
        
    }

}

if (!function_exists('createResource')) {

    // Build a resource based on a list of properties given as key-value pairs.
    function createResource($properties) {
        $resource = array();
        foreach ($properties as $prop => $value) {
            if ($value) {
                addPropertyToResource($resource, $prop, $value);
            }
        }
        return $resource;
    }

}

if ( !function_exists('stream_publish_post') ) {

    /**
     * The function stream_publish_post publishes a post
     * 
     * @param array $args contains the post's data
     * @param integer $user_id contains the user's ID
     * 
     * @return boolean true or false
     */
    function stream_publish_post($args, $user_id = NULL) {

        // Get codeigniter object instance
        $CI =& get_instance();

        // Load Networks Model
        $CI->load->model('networks');
        
        // Load Main Helper
        $CI->load->helper('short_url_helper');
        
        // Verify if social network class exists
        if ( file_exists(MIDRUB_BASE_USER . 'networks/' . $args['network'] . '.php') ) {
            
            // Create an array
            $array = array(
                'MidrubBase',
                'User',
                'Networks',
                ucfirst($args['network'])
            );

            // Implode the array above
            $cl = implode('\\', $array);

            // Get method
            $get = (new $cl());
            
            // Publish
            $pub = $get->post($args, $user_id);
            
            // Verify if post was published
            if ( $pub ) {
                
                return $pub;
                
            } else {
                
                return false;
                
            }
            
        }
        
    }

}

if ( !function_exists('sami') ) {

    /**
     * The function saves publish status
     * 
     * @return void
     */
    function sami($param, $id, $acc, $net, $user_id = NULL) {
        
        // Get codeigniter object instance
        $CI = get_instance();
        
        // Load the model posts
        $CI->load->model('posts');
        
        // If user is null
        if ( !$user_id ) {
            
            // Get user_id
            $user_id = $CI->user_id;
            
        }
        
        if ( $param ) {
            
            // Saves response in the database
            $CI->posts->upo($id, $param, $acc, $net, $user_id);
            
        }
        
    }

}

if ( !function_exists('set_post_number') ) {

    /**
     * The public method set_post_number adds new post count
     *
     * @param integer $user_id contains user_id
     * 
     * @return boolean true or false
     */ 
    function set_post_number( $user_id ) {
        
        // Get number of published posts
        $posts_published = get_user_option('published_posts', $user_id);
        
        if ( $posts_published ) {
            
            $posts_array = unserialize($posts_published);
            
            if ( $posts_array['date'] === date('Y-m') ) {
                
                $posts = $posts_array['posts'];
                
                $posts++;
                
                // Set new record
                $record = serialize(
                    array(
                        'date' => date('Y-m'),
                        'posts' => $posts
                    )
                );
                
            } else {
                
                // Set new record
                $record = serialize(
                    array(
                        'date' => date('Y-m'),
                        'posts' => 1
                    )
                );  
                
            }
            
        } else {
            
            // Save first post number
            $record = serialize(
                array(
                    'date' => date('Y-m'),
                    'posts' => 1
                )
            );
            
        }
        
        update_user_option($user_id, 'published_posts', $record);
        
    }

}

if ( !function_exists('scheduler_time') ) {

    /**
     * The public method scheduler_time returns the scheduler time
     * 
     * @return string with html
     */ 
    function scheduler_time() {

        $scheduler_time = '<select class="midrub-calendar-time-hour">';
        $scheduler_time .= "\n";
            $scheduler_time .= '<option value="01">01</option>';
            $scheduler_time .= "\n";
            $scheduler_time .= '<option value="02">02</option>';
            $scheduler_time .= "\n";
            $scheduler_time .= '<option value="03">03</option>';
            $scheduler_time .= "\n";
            $scheduler_time .= '<option value="04">04</option>';
            $scheduler_time .= "\n";
            $scheduler_time .= '<option value="05">05</option>';
            $scheduler_time .= "\n";
            $scheduler_time .= '<option value="06">06</option>';
            $scheduler_time .= "\n";
            $scheduler_time .= '<option value="07">07</option>';
            $scheduler_time .= "\n";
            $scheduler_time .= '<option value="08" selected>08</option>';
            $scheduler_time .= "\n";
            $scheduler_time .= '<option value="09">09</option>';
            $scheduler_time .= "\n";
            $scheduler_time .= '<option value="10">10</option>';
            $scheduler_time .= "\n";
            $scheduler_time .= '<option value="11">11</option>';
            $scheduler_time .= "\n";
            $scheduler_time .= '<option value="12">12</option>';
            $scheduler_time .= "\n";
        if ( get_user_option('24_hour_format') ) {
            $scheduler_time .= '<option value="13">13</option>';
            $scheduler_time .= "\n";
            $scheduler_time .= '<option value="14">14</option>';
            $scheduler_time .= "\n";
            $scheduler_time .= '<option value="15">15</option>';
            $scheduler_time .= "\n";
            $scheduler_time .= '<option value="16">16</option>';
            $scheduler_time .= "\n";
            $scheduler_time .= '<option value="17">17</option>';
            $scheduler_time .= "\n";
            $scheduler_time .= '<option value="18">18</option>';
            $scheduler_time .= "\n";
            $scheduler_time .= '<option value="19">19</option>';
            $scheduler_time .= "\n";
            $scheduler_time .= '<option value="20">20</option>';
            $scheduler_time .= "\n";
            $scheduler_time .= '<option value="21">21</option>';
            $scheduler_time .= "\n";
            $scheduler_time .= '<option value="22">22</option>';
            $scheduler_time .= "\n";
            $scheduler_time .= '<option value="23">23</option>';
            $scheduler_time .= "\n";
            $scheduler_time .= '<option value="00">00</option>';
            $scheduler_time .= "\n";
        }
        $scheduler_time .= '</select>';
        $scheduler_time .= "\n";
        $scheduler_time .= '<select class = "midrub-calendar-time-minutes">';
        $scheduler_time .= "\n";
            $scheduler_time .= '<option value="00">00</option>';
            $scheduler_time .= "\n";
            $scheduler_time .= '<option value="10">10</option>';
            $scheduler_time .= "\n";
            $scheduler_time .= '<option value="20">20</option>';
            $scheduler_time .= "\n";
            $scheduler_time .= '<option value="30">30</option>';
            $scheduler_time .= "\n";
            $scheduler_time .= '<option value="40">40</option>';
            $scheduler_time .= "\n";
            $scheduler_time .= '<option value="50">50</option>';
            $scheduler_time .= "\n";
        $scheduler_time .= '</select>';
        $scheduler_time .= "\n";

        if ( !get_user_option('24_hour_format') ) {
            $scheduler_time .= '<select class = "midrub-calendar-time-period">';
            $scheduler_time .= "\n";
                $scheduler_time .= '<option value="AM">AM</option>';
                $scheduler_time .= "\n";
                $scheduler_time .= '<option value="PM">PM</option>';
                $scheduler_time .= "\n";
            $scheduler_time .= '</select>';
            $scheduler_time .= "\n";
        }

        return $scheduler_time;
        
    }

}