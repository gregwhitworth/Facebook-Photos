<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once('fbphotos_base.php');

    /** ----------------------------------------------- 
     *  Fbphotos
     *  -----------------------------------------------
     *  The main module file that translates the tags
     *  supplied and supplies the images.
     *  
     *  @package Fbphotos
     *  @author  Greg Whitworth
    */ 

    class Fbphotos extends Fbphotos_base {
        
        public $return_data = "";
        
        // Constructor
        public function __construct()
        {
            parent::__construct();      
        }

        /** =================================================
        //  Get Middle Key
        //  -------------------------------------------------
        //  Returns the middle key value of an array
        //  -------------------------------------------------
        //  @param array $arr
        //  @return int
        //  ================================================= */
        function get_middle_key( $arr )
        {
            $count = count($arr);
            return floor( $count / 2 );
        }

        /** =================================================
        //  Get Image
        //  -------------------------------------------------
        //  Return the correct image based on supplied image
        //  -------------------------------------------------
        //  @param array $arr
        //  @param string $size
        //  @return str
        //  ================================================= */
        function get_image( $arr, $size )
        {
            $middle = $this->get_middle_key($arr);
            $count = (count($arr) - 1);

            switch( $size )
            {
                case 'xsmall':
                    $image = $arr[ $count ]->source;
                    break;
                case 'small':
                    $image = $arr[ ($count - 1) ]->source;
                    break;
                case 'medium':
                    $image = $arr[ ($middle - 1) ]->source;
                    break;
                case 'large':
                    $image = $arr[1]->source;
                    break;
                case 'xlarge':
                    $image = $arr[0]->source;
                    break;
            }
            return $image;
        }

        /** =================================================
        //  Get Photos
        //  -------------------------------------------------
        //  The workhorse function of the module tag. It will
        //  grab all of the images from each album and build
        //  the array of images to transfer back to the view.
        //  -------------------------------------------------
        //  @return array $data
        //  ================================================= */
        function get_photos()
        {
            $albums     = unserialize( $this->get_setting_value( 'facebook_albums' ) );        
            $limit      = intval( $this->EE->TMPL->fetch_param('limit') );
            $size       = $this->EE->TMPL->fetch_param('size');
            $thumbnail_size  = $this->EE->TMPL->fetch_param('thumbnail');

            $variables = array();

            if( is_null( $limit ) || empty( $limit )  )
            {
                $limit = 50;
            }

            if( is_null( $size ) || empty( $size )  )
            {
                $size = 'medium';
            }

            $i = 0;
            foreach( $albums as $album )
            {
                $photo_data = $this->get_facebook_graph_data( $album, 'photos' );
                foreach( $photo_data->data as $photo ) {
                    $images = $photo->images;

                    if( $i === $limit )
                    {
                        break;
                    }
                    else {
                        if( isset( $thumbnail_size ) && !empty( $thumbnail_size ) )
                        {
                            $data[] = array(
                                "source" => $this->get_image( $images, $size ), 
                                "name" => @$photo->name,
                                "thumbnail" => $this->get_image( $images, $thumbnail_size )
                            );
                        }
                        else {
                            $data[] = array(
                                "source" => $this->get_image( $images, $size ), 
                                "name" => @$photo->name
                            );
                        }

                    }
                    $i++;
                }
            }

            return $this->EE->TMPL->parse_variables($this->EE->TMPL->tagdata, $data);
        }

    }

?>