<?php
/**
 * Stream History Model
 *
 * PHP Version 5.6
 *
 * stream_history_model file contains the Stream History Model
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
 * Stream_history_model class - operates the stream_history table.
 *
 * @since 0.0.7.5
 * 
 * @category Social
 * @package  Midrub
 * @author   Scrisoft <asksyn@gmail.com>
 * @license  https://www.gnu.org/licenses/gpl-2.0.html GNU General Public License
 * @link     https://www.midrub.com/
 */
class Stream_history_model extends CI_MODEL {
    
    /**
     * Class variables
     */
    private $table = 'stream_history';

    /**
     * Initialise the model
     */
    public function __construct() {
        
        // Call the Model constructor
        parent::__construct();
        
        $stream_history = $this->db->table_exists('stream_history');
        
        if ( !$stream_history ) {
            
            $this->db->query('CREATE TABLE IF NOT EXISTS `stream_history` (
                              `history_id` bigint(20) AUTO_INCREMENT PRIMARY KEY,
                              `stream_id` bigint(20) NOT NULL,
                              `id` varchar(250) NOT NULL,
                              `type` TINYINT(1) NOT NULL,
                              `value` TEXT COLLATE utf8_unicode_ci NOT NULL,
                              `created` varchar(30) COLLATE utf8_unicode_ci NOT NULL
                            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;');
            
        }
        
        $stream_cronology = $this->db->table_exists('stream_cronology');
        
        if ( !$stream_cronology ) {
            
            $this->db->query('CREATE TABLE IF NOT EXISTS `stream_cronology` (
                              `cronology_id` bigint(20) AUTO_INCREMENT PRIMARY KEY,
                              `stream_id` bigint(20) NOT NULL,
                              `value` TEXT COLLATE utf8_unicode_ci NOT NULL,
                              `up` TINYINT(1) NOT NULL,
                              `created` varchar(30) COLLATE utf8_unicode_ci NOT NULL
                            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;');
            
        }
        
        // Set the tables value
        $this->tables = $this->config->item('tables', $this->table);
        
    }
    
    /**
     * The public method save_stream_item_action saves the stream's item action
     *
     * @param integer $stream_id contains the Stream's ID
     * @param string $id contains the indentificator
     * @param integer $type contains the action's type
     * @param string $value contains the action's value
     * 
     * @return integer with last insert id or false
     */
    public function save_stream_item_action( $stream_id, $id, $type, $value ) {
        
        $data = array(
            'stream_id' => $stream_id,
            'id' => $id,
            'type' => $type,
            'value' => $value,
            'created' => time()
        );
        
        $this->db->insert($this->table, $data);
        
        if ( $this->db->affected_rows() ) {
            
            $insert_id = $this->db->insert_id();
            return $insert_id;
            
        } else {
            
            return false;
            
        }
        
    }
    
    /**
     * The public method update_stream_cronology saves the stream's cronology
     *
     * @param integer $stream_id contains the Stream's ID
     * @param string $value contains the cronology's value
     * 
     * @return boolean true or false
     */
    public function update_stream_cronology( $stream_id, $value ) {
        
        $where = array(
            'stream_id' => $stream_id
        );  
        
        $this->db->select('*');
        $this->db->from('stream_cronology');
        $this->db->where($where);
        
        $query = $this->db->get();
        
        if ( $query->num_rows() > 0 ) {
            
            // Set data
            $data = array(
                'value' => $value,
                'up' => 1,
                'created' => time()
            );

            $this->db->where($where);
            $this->db->update('stream_cronology', $data);

            if ( $this->db->affected_rows() ) {

                return true;

            } else {

                return false;

            }
            
        } else { 
            
            $data = array(
                'stream_id' => $stream_id,
                'value' => $value,
                'created' => time()
            );

            $this->db->insert('stream_cronology', $data);

            if ( $this->db->affected_rows() ) {

                return true;

            } else {

                return false;

            }
            
        }
        
    }
    
    /**
     * The public method update_stream_item_action updates stream item action
     *
     * @param integer $history_id contains the History's ID
     * @param string $value contains the action's value
     * 
     * @return boolean true or false
     */
    public function update_stream_item_action( $history_id, $value ) {
        
        // Set data
        $data = array(
            'value' => $value
        );
        
        $this->db->where('history_id', $history_id);
        $this->db->update($this->table, $data);
        
        if ( $this->db->affected_rows() ) {
            
            return true;
            
        } else {
            
            return false;
            
        }
        
    }    
    
    /**
     * The public method get_item_actions gets item's action
     *
     * @param integer $stream_id contains the Stream's ID
     * @param string $id contains the indentificator
     * @param integer $type contains the action's type
     * 
     * @return object with history data or false
     */
    public function get_item_actions($stream_id, $id=NULL, $type=NULL) {
        
        $data = array(
            'stream_id' => $stream_id
        );
        
        if ( $id ) {
            $data['id'] = $id;
        }
        
        if ( $type ) {
            $data['type'] = $type;
        }        
        
        $this->db->select('*');
        $this->db->from($this->table);
        $this->db->where($data);
        
        $query = $this->db->get();
        
        if ( $query->num_rows() > 0 ) {
            
            return $query->result();
            
        } else {
            
            return false;
            
        }
        
    }
    
    /**
     * The public method get_stream_cronology gets the stream's cronology
     *
     * @param integer $stream_id contains the Stream's ID
     * 
     * @return object with stream's cronology or false
     */
    public function get_stream_cronology( $stream_id ) {
        
        $where = array(
            'stream_id' => $stream_id
        );  
        
        $this->db->select('*');
        $this->db->from('stream_cronology');
        $this->db->where($where);
        
        $query = $this->db->get();
        
        if ( $query->num_rows() > 0 ) {
            
            return $query->result();
            
        } else { 
            
            return false;
            
        }
        
    }
    
    /**
     * The public method history_id deletes a stream's history
     *
     * @param integer $history_id contains the History's ID
     * @param integer $user_id contains user's ID
     * 
     * @return boolean true or false
     */
    public function delete_stream_history( $history_id ) {
        
        $data = array(
            'history_id' => $history_id
        );
        
        $this->db->delete($this->table, $data);
        
        if ( $this->db->affected_rows() ) {
            
            return true;
            
        } else {
            
            return false;
            
        }
        
    }
 
}

/* End of file Stream_history_model.php */