<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	class Fbphotos_base {

	    protected $facebook_id;
	    protected $fb_graph_uri;
	    protected $fb_settings_table;
	    protected $valid_fb_id;
	    protected $data;

	    public function __construct()
	    {  
	        $this->EE =& get_instance();
		    $this->fb_graph_uri = 'http://graph.facebook.com';
		    $this->fb_settings_table = 'fb_photo_settings';
		    $this->facebook_id = $this->get_setting_value( 'facebook_id' );
		    $this->get_facebook_graph_data( $this->facebook_id, '' );  
	    }

	    // =================================================
	    // Get Setting Value
	    // -------------------------------------------------
	    // Will return the selected setting from the 
	    // exp_fb_photo_settings table.
	    // -------------------------------------------------
	    // @return str
	    // =================================================
	    public function get_setting_value( $setting_name )
	    {
	        $query = $this->EE->db->get_where( $this->fb_settings_table, array('setting_name' => $setting_name ) );
	        $results = $query->row( $this->fb_settings_table );
	        return $results->setting_value;
	    }

	    // =================================================
	    // Get Facebook Graph Data
	    // -------------------------------------------------
	    // Does a facebook graph call and returns the object
	    // -------------------------------------------------
	    // @return object
	    // @param string : api_ext
	    // =================================================
	    public function get_facebook_graph_data( $id, $api_ext )
	    {
	        $graph_uri = sprintf( '%s/%s/%s', $this->fb_graph_uri, $id, $api_ext );
	        $result = @file_get_contents( $graph_uri );

	        if( $api_ext == 'albums' && ( !$result || $result == "" ) ) {
	            return $this->data['message'] = 'Your Facebook account is set to private, we can not pull albums from your account.';
	        }
	        elseif( !$result ) // If the facebook ID is bad
	        {
	            $this->valid_fb_id = false;
	            return $this->data['message'] = 'Not a valid Facebook ID';
	        }

	        $this->valid_fb_id = true;
	        return json_decode( $result );
	    }

	}

?>