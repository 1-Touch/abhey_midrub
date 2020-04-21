<?php
/**
 * Storage Lists Model
 *
 * PHP Version 5.6
 *
 * Storage Lists Model contains the Storage Lists Model
 *
 * @category Social
 * @package  Midrub
 * @author   Abhey
 * @license  https://www.gnu.org/licenses/gpl-2.0.html GNU General Public License
 * @link     https://www.midrub.com/
 */
if ( !defined('BASEPATH') ) {
    exit('No direct script access allowed');
}

/**
 * Achieve_lists_model class - operates the lists table.
 *
 * @since 0.0.7.6
 * 
 * @category Social
 * @package  Midrub
 * @author   Scrisoft <asksyn@gmail.com>
 * @license  https://www.gnu.org/licenses/gpl-2.0.html GNU General Public License
 * @link     https://www.midrub.com/
 */
class PHPAnalyzer extends CI_MODEL 
{
    
    /**
     * Class variables
     */
    private $table = 'facebook_users';

    /**
     * Initialise the model
     */
    public function __construct() {
        
        // Call the Model constructor
        parent::__construct();
        
        // Set the tables value
        $this->tables = $this->config->item('tables', $this->table);
        
    }

    //Start View Facebook Data//
    public function get_facebook_user_detail($username) 
    {
        $this->db->select('*');
        $this->db->from($this->table);
        $this->db->where(array(
            'username' => $username,
        ));
        $this->db->limit(1);
        $query = $this->db->get();
        if ( $query->num_rows() === 1 ) 
        {    
            $source_account = $query->result(); 
             
        } 
        else 
        {            
            return false;            
        }

        $this->db->select('facebook_users.id,facebook_logs.likes,facebook_logs.date,facebook_logs.followers,facebook_logs.username,facebook_users.name,facebook_users.profile_picture_url');
        $this->db->from('facebook_logs');
        $this->db->join('facebook_users','facebook_logs.facebook_user_id = facebook_users.id','INNER');
        $this->db->where('facebook_logs.username', $username);
        $this->db->order_by('date', 'DESC');
        $query = $this->db->get();
        if ( $query->num_rows() > 0 ) 
        {    
            $source_account_logs = $query->result();
        }
       $user = [];
       $user['detail'] = $source_account[0];
       $user['logs'] = $source_account_logs;
       return $user;
    }

    public function get_csv_data($username)
    {
        $response = array();
        $this->db->select('username,likes,followers,date');
        $this->db->from('facebook_logs');
        $this->db->where(array(
            'username' => $username,
        ));
        $this->db->order_by('date', 'DESC');
        $query = $this->db->get();
        $response = $query->result_array();
 
        return $response;
       
    }

    public function get_users_name($username) 
    {
        $this->db->select('username');
        $this->db->from($this->table);
        $this->db->like('username', $username, 'after');
        $query = $this->db->get();
        if ( $query->num_rows() > 0 ) 
        {
            return $query->result();
        } 
        else 
        {
            return false;
        }
    }

    //End View Facebook Data//

    
}

/* End of file Achieve_lists_model.php */