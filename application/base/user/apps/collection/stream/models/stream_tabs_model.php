<?php
/**
 * Stream Tabs Model
 *
 * PHP Version 5.6
 *
 * stream_tabs_model file contains the Stream Tabs Model
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
 * Stream_tabs_model class - operates the stream_tabs table.
 *
 * @since 0.0.7.5
 * 
 * @category Social
 * @package  Midrub
 * @author   Scrisoft <asksyn@gmail.com>
 * @license  https://www.gnu.org/licenses/gpl-2.0.html GNU General Public License
 * @link     https://www.midrub.com/
 */
class Stream_tabs_model extends CI_MODEL {
    
    /**
     * Class variables
     */
    private $table = 'stream_tabs';

    /**
     * Initialise the model
     */
    public function __construct() {
        
        // Call the Model constructor
        parent::__construct();
        
        $stream_tabs = $this->db->table_exists('stream_tabs');
        
        if ( !$stream_tabs ) {
            
            $this->db->query('CREATE TABLE IF NOT EXISTS `stream_tabs` (
                              `tab_id` bigint(20) AUTO_INCREMENT PRIMARY KEY,
                              `user_id` bigint(20) NOT NULL,
                              `tab_name` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
                              `tab_icon` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
                              `refresh` smallint(2) NOT NULL,
                              `created` varchar(30) COLLATE utf8_unicode_ci NOT NULL
                            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;');
            
        }
        
        $stream_tabs_streams = $this->db->table_exists('stream_tabs_streams');
        
        if ( !$stream_tabs_streams ) {
            
            $this->db->query('CREATE TABLE IF NOT EXISTS `stream_tabs_streams` (
                              `stream_id` bigint(20) AUTO_INCREMENT PRIMARY KEY,
                              `tab_id` bigint(20) NOT NULL,
                              `template` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
                              `network` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
                              `network_id` bigint(20) NOT NULL,
                              `alert_sound` varchar(250) NOT NULL,
                              `header_text_color` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
                              `item_text_color` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
                              `links_color` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
                              `icons_color` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
                              `background_color` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
                              `border_color` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
                              `stream_order` tinyint(1) NOT NULL,
                              `new_event` tinyint(1) NOT NULL
                            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;');
            
        }
        
        $stream_tabs_streams_setup = $this->db->table_exists('stream_tabs_streams_setup');
        
        if ( !$stream_tabs_streams_setup ) {
            
            $this->db->query('CREATE TABLE IF NOT EXISTS `stream_tabs_streams_setup` (
                              `setup_id` bigint(20) AUTO_INCREMENT PRIMARY KEY,
                              `stream_id` bigint(20) NOT NULL,
                              `template` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
                              `setup_option` TEXT COLLATE utf8_unicode_ci NOT NULL,
                              `setup_extra` TEXT COLLATE utf8_unicode_ci NOT NULL
                            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;');
            
        }        
        
        // Set the tables value
        $this->tables = $this->config->item('tables', $this->table);
        
    }
    
    /**
     * The public method save_stream_tab saves a new stream's tab
     *
     * @param integer $user_id contains user's ID
     * @param string $tab_name contains the tab's name
     * @param string $tab_icon contains the tab's icon
     * 
     * @return integer with last insert id or false
     */
    public function save_stream_tab( $user_id, $tab_name, $tab_icon ) {
        
        $data = array(
            'user_id' => $user_id,
            'tab_name' => $tab_name,
            'tab_icon' => $tab_icon,
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
     * The public method save_tab_stream sets tab's stream
     *
     * @param integer $tab_id contains the Tab's ID
     * @param string $template contains the template's name
     * @param string $network contains the network's name
     * @param integer $network_id contains the network's id
     * 
     * @return integer with last insert id or false
     */
    public function save_tab_stream( $tab_id, $template, $network, $network_id=null ) {
        
        $data = array(
            'tab_id' => $tab_id,
            'template' => $template,
            'network' => $network
        );
        
        if ( $network_id ) {
            
            $data['network_id'] = $network_id;
            
        }
        
        $this->db->insert('stream_tabs_streams', $data);
        
        if ( $this->db->affected_rows() ) {
            
            $insert_id = $this->db->insert_id();
            
            $q = $this->db->get_where('stream_tabs_streams', array('stream_id' => $insert_id));
            return $q->row();
            
        } else {
            
            return false;
            
        }
        
    }    
    
    /**
     * The public method all_user_tabs gets all user's tabs
     *
     * @param integer $user_id contains user's ID
     * @param integer $tab_id contains the Tab's ID
     * 
     * @return object with all user's tabs
     */
    public function all_user_tabs( $user_id, $tab_id=NULL ) {
        
        $args = array(
            'user_id' => $user_id
        );
        
        if ( $tab_id ) {
            
            $args['tab_id'] = $tab_id;
            
        }
        
        // First we check if the post exists
        $this->db->select('*');
        $this->db->from($this->table);
        $this->db->where($args);
        
        $query = $this->db->get();
        
        if ( $query->num_rows() > 0 ) {
            
            return $query->result();
            
        } else {
            
            return false;
            
        }
        
    }
    
    /**
     * The public method stream_load_tab_streams gets all tab's streams
     *
     * @param integer $user_id contains the user's id
     * @param integer $tab_id contains the Tab's ID
     * 
     * @return array with posts or false
     */
    public function stream_load_tab_streams($user_id, $tab_id) {
        
        $this->db->select('*');
        $this->db->from('stream_tabs_streams');
        $this->db->join('stream_tabs', 'stream_tabs_streams.tab_id=stream_tabs.tab_id', 'left');
        $this->db->where( array(
                'stream_tabs_streams.tab_id' => $tab_id,
                'stream_tabs.user_id' => $user_id
            )
        );
        $this->db->order_by('stream_tabs_streams.stream_order', 'asc');
        
        $query = $this->db->get();
        
        if ( $query->num_rows() > 0 ) {
            
            return $query->result();
            
        } else {
            
            return false;
            
        }
        
    }
    
    /**
     * The public method get_last_posts gets last published posts limit by $time
     *
     * @param integer $user_id contains the user's id
     * @param integer $stream_id contains the stream's id
     * 
     * @return object with stream data or false
     */
    public function verify_stream_owner($user_id, $stream_id) {
        
        $this->db->select('*');
        $this->db->from('stream_tabs_streams');
        $this->db->join('stream_tabs', 'stream_tabs_streams.tab_id=stream_tabs.tab_id', 'left');
        $this->db->where( array(
                'stream_tabs_streams.stream_id' => $stream_id,
                'stream_tabs.user_id' => $user_id
            )
        );
        
        $query = $this->db->get();
        
        if ( $query->num_rows() > 0 ) {
            
            return $query->result();
            
        } else {
            
            return false;
            
        }
        
    }
    
    /**
     * The public method update_tab_field updates a tab's field
     *
     * @param integer $tab_id contains the tab's id
     * @param string $name contains the field's name
     * @param string $value contains the field's value
     * 
     * @return boolean true or false
     */
    public function update_tab_field( $tab_id, $name, $value ) {
        
        // Set data
        $data = array(
            $name => $value
        );
        
        $this->db->where('tab_id', $tab_id);
        $this->db->update($this->table, $data);
        
        if ( $this->db->affected_rows() ) {
            
            return true;
            
        } else {
            
            return false;
            
        }
        
    }
    
    /**
     * The public method update_stream_field updates a stream's field
     *
     * @param integer $stream_id contains the stream's id
     * @param string $name contains the field's name
     * @param string $value contains the field's value
     * 
     * @return boolean true or false
     */
    public function update_stream_field( $stream_id, $name, $value ) {
        
        // Set data
        $data = array(
            $name => $value
        );
        
        $this->db->where('stream_id', $stream_id);
        $this->db->update('stream_tabs_streams', $data);
        
        if ( $this->db->affected_rows() ) {
            
            return true;
            
        } else {
            
            return false;
            
        }
        
    }
    
    /**
     * The public method delete_stream_tab delete stream's tab
     *
     * @param integer $tab_id contains the Tab's ID
     * @param integer $user_id contains user's ID
     * 
     * @return boolean true or false
     */
    public function delete_stream_tab( $tab_id, $user_id ) {
        
        $data = array(
            'tab_id' => $tab_id,
            'user_id' => $user_id
        );
        
        $this->db->delete($this->table, $data);
        
        if ( $this->db->affected_rows() ) {
            
            // Verify if cache exists
            if ( file_exists(MIDRUB_BASE_USER_APPS_STREAM . 'cache/backup-' . $tab_id . '.html') ) {
                unlink(MIDRUB_BASE_USER_APPS_STREAM . 'cache/backup-' . $tab_id . '.html');
            }
            
            $this->db->select('*');
            $this->db->from('stream_tabs_streams');
            $this->db->where( array(
                    'tab_id' => $tab_id
                )
            );

            $query = $this->db->get();

            if ( $query->num_rows() > 0 ) {

                $response = $query->result();

                $this->db->delete('stream_tabs_streams', array('stream_id' => $response[0]->stream_id));
                $this->db->delete('stream_tabs_streams_setup', array('stream_id' => $response[0]->stream_id));
                $this->db->delete('stream_history', array('stream_id' => $response[0]->stream_id));

            }
            
            return true;
            
        } else {
            
            return false;
            
        }
        
    }
    
    /**
     * The public method delete_stream_tab delete stream's tab
     *
     * @param integer $stream_id contains the Stream's ID
     * 
     * @return boolean true or false
     */
    public function delete_stream( $stream_id ) {
        
        $data = array(
            'stream_id' => $stream_id
        );
        
        $this->db->delete('stream_tabs_streams', $data);
        
        if ( $this->db->affected_rows() ) {
            
            $this->db->delete('stream_tabs_streams_setup', array('stream_id' => $stream_id));
            $this->db->delete('stream_history', array('stream_id' => $stream_id));
            
            return true;
            
        } else {
            
            return false;
            
        }
        
    }
    
    /**
     * The public method delete_tab_records deletes all tabs records
     * 
     * @param integer $user_id contains user_id
     * 
     * @return void
     */
    public function delete_tab_records( $user_id ) {
        
        $this->db->select('*');
        $this->db->from('stream_tabs');
        $this->db->where( array(
                'user_id' => $user_id
            )
        );
        
        $query = $this->db->get();
        
        if ( $query->num_rows() > 0 ) {
            
            $response = $query->result();
            
            foreach ( $response as $res ) {
                
                $this->db->select('*');
                $this->db->from('stream_tabs_streams');
                $this->db->where( array(
                        'tab_id' => $res->tab_id
                    )
                );

                $query = $this->db->get();

                if ( $query->num_rows() > 0 ) {

                    $streams = $query->result();

                    foreach ( $streams as $stream ) {
                        
                        $this->db->delete('stream_tabs_streams_setup', array('stream_id' => $stream->stream_id));
                        $this->db->delete('stream_history', array('stream_id' => $stream->stream_id));
                        $this->db->delete('stream_cronology', array('stream_id' => $stream->stream_id));

                    }
                    
                    // Verify if cache exists
                    if ( file_exists(MIDRUB_BASE_USER_APPS_STREAM . 'cache/backup-' . $res->tab_id . '.html') ) {
                        unlink(MIDRUB_BASE_USER_APPS_STREAM . 'cache/backup-' . $res->tab_id . '.html');
                    }
                    
                    $this->db->delete('stream_tabs_streams', array('tab_id' => $res->tab_id));

                }                
                
            }
            
            $this->db->delete('stream_tabs', array('user_id' => $user_id));
            
        }
        
    }
 
}

/* End of file Stream_tabs_model.php */