<?php
/**
 * Stream Setup Model
 *
 * PHP Version 5.6
 *
 * stream_setup_model file contains the Stream Setup Model
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
 * Stream_setup_model class - operates the stream_tabs_streams_setup table.
 *
 * @since 0.0.7.5
 * 
 * @category Social
 * @package  Midrub
 * @author   Scrisoft <asksyn@gmail.com>
 * @license  https://www.gnu.org/licenses/gpl-2.0.html GNU General Public License
 * @link     https://www.midrub.com/
 */
class Stream_setup_model extends CI_MODEL {
    
    /**
     * Class variables
     */
    private $table = 'stream_tabs_streams_setup';

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
     * The public method stream_save_setup saves new setup data
     * 
     * @param integer $stream_id contains the stream's id
     * @param string $template contains the template's name
     * @param string $setup_option contains the first option value
     * @param string $setup_extra contains the extra or section option value
     * 
     * @since 0.0.7.5
     * 
     * @return integer with last inserted id
     */
    public function stream_save_setup($stream_id, $template, $setup_option, $setup_extra=NULL) {
        
        $data = array(
            'stream_id' => $stream_id,
            'template' => strtolower($template),
            'setup_option' => $setup_option
        );
        
        if ( $setup_extra ) {
            $data['setup_extra'] = $setup_extra;
            
        }
        
        $this->db->insert($this->table, $data);
        
        if ( $this->db->affected_rows() ) {
            
            return $this->db->insert_id();
            
        } else {
            
            return false;
            
        }
        
    }
    
    /**
     * The public method stream_update_setup updates the setup data
     * 
     * @param integer $setup_id contains the setup's id
     * @param string $template contains the template's name
     * @param string $setup_option contains the first option value
     * @param string $setup_extra contains the extra or section option value
     * 
     * @since 0.0.7.5
     * 
     * @return integer with last inserted id
     */
    public function stream_update_setup($setup_id, $template, $setup_option, $setup_extra=NULL) {
        
        $data = array(
            'template' => strtolower($template),
            'setup_option' => $setup_option
        );
        
        if ( $setup_extra ) {
            $data['setup_extra'] = $setup_extra;
            
        }
        
        $this->db->where('setup_id', $setup_id);
        $this->db->update($this->table, $data);
        
        if ( $this->db->affected_rows() ) {
            
            return true;
            
        } else {
            
            return false;
            
        }
        
    }
    
    /**
     * The public method stream_get_setup gets the stream's setup
     *
     * @param integer $stream_id contains the stream's id
     * @param string $template contains the template's name
     * @param string $setup_option contains the first option value
     * 
     * @return object with stream't setup data
     */
    public function stream_get_setup($stream_id, $template, $setup_option=NULL) {
        
        $params = array(
            'stream_id' => $stream_id,
            'template' => strtolower($template)
        );
        
        $this->db->select('*');
        $this->db->from($this->table);
        $this->db->where($params);
        $query = $this->db->get();
        
        if ( $query->num_rows() > 0 ) {
            
            return $query->result();
            
        } else {
            
            return false;
            
        }
        
    }
    
    /**
     * The public method streams_setup_delete deletes the setup's data
     * 
     * @param integer $setup_id contains the setup's id
     * @param integer $stream_id contains the stream's id
     * @param string $template contains the template's name
     * 
     * @since 0.0.7.5
     * 
     * @return boolean true or false 
     */ 
    public function streams_delete_setup($setup_id, $stream_id, $template) {
        
        $data = array(
            'setup_id' => $setup_id,
            'stream_id' => $stream_id,
            'template' => strtolower($template)
        );
        
        $this->db->delete($this->table, $data);
        
        if ( $this->db->affected_rows() ) {
            
            return true;
            
        } else {
            
            return false;
            
        }
        
    } 
 
}

/* End of file Stream_setup_model.php */