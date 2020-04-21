<?php
class Migration_Add_Facebook_Users extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field(
           array(
              'id' => array(
                 'type' => 'INT',
                 'constraint' => 11,
                 'unsigned' => true,
                 'auto_increment' => true
              ),
              'username' => array(
                 'type' => 'VARCHAR',
                 'constraint' => '64',
              ),
              'name' => array(
                'type' => 'VARCHAR',
                'constraint' => '128',
             ),
             'profile_picture_url' => array(
                'type' => 'VARCHAR',
                'constraint' => '512',
             ),
             'likes' => array(
                'type' => 'INT',
                'constraint' => '11',
             ),
             'followers' => array(
                'type' => 'INt',
                'constraint' => '11',
             ),
             'details' => array(
                'type' => 'LONGTEXT',
             ),
             'is_verified' => array(
                'type' => 'INT',
                'constraint' => '11',
             ),
             'added_date' => array(
                'type' => 'DATETIME',
             ),
             'last_check_date' => array(
                'type' => 'DATETIME',
             ),
             'last_successful_check_date' => array(
                'type' => 'DATETIME',
             ),
             'is_demo' => array(
                'type' => 'INT',
                'constraint' => '11',
             ),
             'is_private' => array(
                'type' => 'INT',
                'constraint' => '11',
             ),
             'is_featured' => array(
                'type' => 'INT',
                'constraint' => '11',
             ),
              
           )
        );

        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('add_facebook_users');
    }

    public function down()
    {
        $this->dbforge->drop_table('add_facebook_users');
    }
}
?>