<?php
/**
 * Stream_posts_model Model
 *
 * PHP Version 5.6
 *
 * Stream_posts_model file contains the Stream Posts Model
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

/**
 * Stream_posts_model class - operates the posts table.
 *
 * @since 0.0.7.5
 * 
 * @category Social
 * @package  Midrub
 * @author   Scrisoft <asksyn@gmail.com>
 * @license  https://www.gnu.org/licenses/gpl-2.0.html GNU General Public License
 * @link     https://www.midrub.com/
 */
class Stream_posts_model extends CI_MODEL {
    
    /**
     * Class variables
     */
    private $table = 'posts';

    /**
     * Initialise the model
     */
    public function __construct() {
        
        // Call the Model constructor
        parent::__construct();
        
        // Set the tables value
        $this->tables = $this->config->item('tables', $this->table);
        
    }
    
    /**
     * The public method save_post saves post before send on social networks
     * 
     * @param integer $user_id contains the user_id
     * @param string $post contains the post content
     * @param string $url contains the post's url
     * @param string $img contains the post's image url
     * @param integer $time contains the time when will be published the post
     * @param integer $publish contains a number. If 0 the post will be saved as draft
     * @param string $category contains the category
     * 
     * @return integer with inserted id or false
     */
    public function save_post( $user_id, $post, $url, $img, $video = NULL, $time, $publish, $post_title = NULL, $category = NULL ) {
        
        // Get current ip
        $ip = $this->input->ip_address();
        
        // Decode URL-encoded strings
        $post = rawurldecode($post);
        
        // Set data
        $data = array(
            'user_id' => $user_id,
            'body' => $post,
            'title' => $post_title,
            'url' => $url,
            'img' => $img,
            'sent_time' => $time,
            'ip_address' => $ip,
            'status' => $publish,
            'view' => '1'
        );
        
        if ( $category ) {
            
            $data['category'] = $category;
            
        }
        
        // Verify if video exists
        if ( $video ) {
            
            $data['video'] = $video;
            
        }
        
        // Insert post
        $this->db->insert($this->table, $data);
        
        // Verify if post was saved
        if ( $this->db->affected_rows() ) {
            
            $last_id = $this->db->insert_id();
            
            // Load Activities model
            $this->load->model( 'Activities', 'activities' );
            
            $member_id = 0;
            
            if ( $this->session->userdata( 'member' ) ) {
                
                // Load Team model
                $this->load->model( 'Team', 'team' );
                
                // Get member team info
                $member_info = $this->team->get_member( $user_id, 0, $this->session->userdata( 'member' ) );
                
                if ( $member_info ) {
                    
                    $member_id = $member_info[0]->member_id;
                    
                }
                
            }
            
            $this->activities->save_activity( 'posts', 'posts', $last_id, $user_id, $member_id );
            
            // Return last inserted id
            return $last_id;
            
        } else {
            
            return false;
            
        }
        
    }
    
    /**
     * The public method save_post_meta saves post meta
     *
     * @since 0.0.7.5
     * 
     * @param integer $post_id contains the post_id
     * @param integer $account contains the account where will be published the post
     * @param string $name contains the network's name
     * @param integer $status may be a number 0, 1 or 2
     * @param integer $user_id contains the user_id
     * @param integer $published_id contains the published id
     * 
     * @return void
     */
    public function save_post_meta( $post_id, $account, $name, $status=0, $user_id=0, $published_id=0 ) {
        
        // Get current time
        $time = time();
        
        // Set data
        $data = array(
            'post_id' => $post_id,
            'network_id' => $account,
            'network_name' => $name,
            'sent_time' => $time,
            'status' => $status,
            'published_id' => $published_id
        );
        
        $this->db->insert('posts_meta', $data);
        
    }
    
    /**
     * The public method update_post updates a post
     * 
     * @param integer $user_id contains the user_id
     * @param integer $post_id contains the post's ID
     * @param string $column contains the table's column
     * @param string $value contains the table's column value
     * 
     * @return boolean true or false
     */
    public function update_post( $user_id, $post_id, $column, $value ) {
        
        $params = array(
            'post_id' => $post_id,
            'user_id' => $user_id
        );
        
        $data = array(
            $column => $value
        );
        
        $this->db->where($params);
        $this->db->update($this->table, $data);
        
        // Verify if post was saved
        if ( $this->db->affected_rows() ) {
            
            return true;
            
        } else {
            
            return false;
            
        }
        
    }

}

/* End of file Stream_posts_model.php */