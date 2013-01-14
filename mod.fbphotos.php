<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once('fbphotos_base.php');

class Fbphotos extends Fbphotos_base {
    
    public $return_data = "";
    
    // Constructor
    public function __construct()
    {
        parent::__construct();      
    }

    function get_photos()
    {
        $albums = unserialize( $this->get_setting_value( 'facebook_albums' ) );
    }

}

?>