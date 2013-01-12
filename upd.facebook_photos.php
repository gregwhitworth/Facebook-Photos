<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	require_once PATH_THIRD."facebook_photos/config.php";

	class Facebook_Photos_upd {

		var $version = '1.0';
		var $module_name = 'Facebook_Photos';

		function __construct()
	    {
	        // Make a local reference to the ExpressionEngine super object
	        $this->EE =& get_instance();
	    }

		// ================================================
		// Installer
		// ================================================
		function install() 
		{

			$data = array(
				'module_name'	=> 'Facebook_Photos',
				'module_version'	=> $this->version,
				'has_cp_backend'	=> 'y',
				'has_publish_fields' => 'n'
			);

			$this->EE->db->insert('modules', $data);

			$this->EE->load->dbforge();

			// Create Album Table
			$fields = array(
		        'id' => array(
		            'type' => 'int',
		            'constraint' => '10',
		            'unsigned' => TRUE,
		            'auto_increment'=> TRUE
		        ),
		        'album_id' => array(
		            'type' => 'int',
		            'constraint' => '50',
		            'unsigned' => TRUE,
		            'null' => FALSE
		        )
		    );

			$this->EE->dbforge->add_field( $fields );
			$this->EE->dbforge->add_key('id');

			$this->EE->dbforge->create_table('fb_photo_albums');

			unset($fields);

			// Create Facebook Settings Table
			$fields = array(
		        'id' => array(
		            'type' => 'int',
		            'constraint' => '10',
		            'unsigned' => TRUE,
		            'auto_increment'=> TRUE
		        ),
		        'setting_name' => array(
		            'type' => 'varchar',
		            'constraint' => '200'
		        ),
		        'setting_value' => array(
		            'type' => 'varchar',
		            'constraint' => '1000'
		        )
		    );

			$this->EE->dbforge->add_field( $fields );
			$this->EE->dbforge->add_key('id');

			$this->EE->dbforge->create_table('fb_photo_settings');

			// POPULATE Settings
			// =============================================
			$data = array(
				'setting_name'  => 'facebook_id',
				'setting_value' => 'insert something here'
			);
			$this->EE->db->insert('fb_photo_settings', $data);

			return true; 
		}

		// ================================================
		// Uninstall
		// ================================================
		function uninstall() 
		{ 
			$this->EE->db->select('module_id');
			$query = $this->EE->db->get_where('modules', array('module_name' => 'Facebook_Photos'));

			$this->EE->db->where('module_name', 'Facebook_Photos');
			$this->EE->db->delete('modules');

			$this->EE->db->where('class', 'Facebook_Photos');
			$this->EE->db->delete('actions');

			$this->EE->load->dbforge();

			$this->EE->dbforge->drop_table('fb_photo_settings');
			$this->EE->dbforge->drop_table('fb_photo_albums');

			return true; 
		}

		// ================================================
		// Update
		// ================================================
		function update($current = '')
		{
		    return FALSE;
		}

	} // END Facebook Photos Class

?>