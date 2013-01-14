<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Fbphotos {
    
    public $return_data = "";
    
    // Constructor
    public function __construct()
    {
        $this->EE =& get_instance();        
    }

    function get_photos()
    {
        $query = $this->EE->db->get_where( 'exp_fb_photo_settings', array('setting_name' => 'facebook_albums' ) );
        $results = $query->row( 'exp_fb_photo_settings' );
        $albums = unserialize( $results->setting_value );
    }

}

?>