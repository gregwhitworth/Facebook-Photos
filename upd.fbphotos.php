<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	/** ----------------------------------------------- 
	 *  Fbphotos Update
	 *  -----------------------------------------------
	 *  This will install, update and un-install the
	 *  module.
	 *  
	 *  @package Fbphotos
	 *  @author  Greg Whitworth
	*/ 

	class FBPhotos_upd {

		var $version = '1.0';

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
				'module_name'	=> 'Fbphotos',
				'module_version'	=> '1.1',
				'has_cp_backend'	=> 'y',
				'has_publish_fields' => 'n'
			);

			$this->EE->db->insert('modules', $data);

			$this->EE->load->dbforge();

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

			// Create Facebook Settings Table
			$fields = array(
		        'id' => array(
		            'type' => 'int',
		            'constraint' => '10',
		            'unsigned' => TRUE,
		            'auto_increment'=> TRUE
		        ),
		        'photo_url' => array(
		            'type' => 'varchar',
		            'constraint' => '300'
		        ),
		        'photo_caption' => array(
		            'type' => 'varchar',
		            'constraint' => '1000'
		        )
		    );

			$this->EE->dbforge->add_field( $fields );
			$this->EE->dbforge->add_key('id');

			$this->EE->dbforge->create_table('fb_photo_images');

			// POPULATE Settings
			// =============================================
			$data = array(
				'setting_name'  => 'facebook_id',
				'setting_value' => 'insert something here'
			);
			$this->EE->db->insert('fb_photo_settings', $data);

			$data = array(
				'setting_name'  => 'facebook_sync',
				'setting_value' => "true"
			);
			$this->EE->db->insert('fb_photo_settings', $data);

			$albums_default = serialize( array( 'checked' => array('default'), 'unchecked' => array('default') ) );
			$data = array(
				'setting_name'  => 'facebook_albums',
				'setting_value' => $albums_default
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
			$query = $this->EE->db->get_where('modules', array('module_name' => 'Fbphotos'));

			$this->EE->db->where('module_name', 'Fbphotos');
			$this->EE->db->delete('modules');

			$this->EE->db->where('class', 'Fbphotos');
			$this->EE->db->delete('actions');

			$this->EE->load->dbforge();

			$this->EE->dbforge->drop_table('fb_photo_settings');
			$this->EE->dbforge->drop_table('fb_photo_images');

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