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
        $limit = intval( $this->EE->TMPL->fetch_param('limit') );

        $variables = array();

        if( is_null( $limit ) || empty( $limit )  )
        {
            $limit = 50;
        }

        $i = 0;
        foreach( $albums as $album )
        {
            $photo_data = $this->get_facebook_graph_data( $album, 'photos' );
            foreach( $photo_data->data as $photo ) {
                if( $i === $limit )
                {
                    break;
                }
                else {
                    $data[] = array(
                        "source" => $photo->images[5]->source, 
                        "name" => @$photo->name 
                    );
                }
                $i++;
            }
        }

        return $this->EE->TMPL->parse_variables($this->EE->TMPL->tagdata, $data);
    }

}

?>