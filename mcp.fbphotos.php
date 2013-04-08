<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once('fbphotos_base.php');

    /** ----------------------------------------------- 
     *  Fbphotos MCP
     *  -----------------------------------------------
     *  Sets the control panel in the admin area for
     *  the module.
     *  
     *  @package Fbphotos
     *  @author  Greg Whitworth
    */ 

    class Fbphotos_mcp extends Fbphotos_base {

        private $_base_url;
        
        public function __construct()
        {
            parent::__construct();
            $this->_base_url = 'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module=fbphotos';
        }

        /** =================================================
        //  Index
        //  -------------------------------------------------
        //  Runs necessary functions for the index page of 
        //  the control panel.
        //  -------------------------------------------------
        //  @return Index view
        //  ================================================= */
        public function index()
        {
            $this->EE->cp->set_variable('cp_page_title', lang('Facebook Photos : Settings'));
            $this->EE->load->helper('form');
            $this->EE->load->library('table');  

            $this->data = array(
                'form_action'     => $this->_base_url . AMP . 'method=save_settings',
                'facebook_id'     => $this->facebook_id,
                'facebook_sync'   => $this->facebook_sync
            );   

                $selected_albums = unserialize( parent::get_setting_value( 'facebook_albums' ) );
                $this->get_facebook_photo_albums( $selected_albums );

            return $this->EE->load->view('index', $this->data, TRUE);
        }
        
        /** =================================================
        //  Get Facebook Photo Albums
        //  -------------------------------------------------
        //  Returns an array of album information specific
        //  to our needs in the control panel.
        //  -------------------------------------------------
        //  @return array
        //  ================================================= */
        private function get_facebook_photo_albums( $selected_albums )
        {
            $result = parent::get_facebook_graph_data( $this->facebook_id, 'albums' );  
            $albums = array();

            if( $result )
            {
                foreach( $result->data as $info ) {
                    if( @in_array( $info->id, $selected_albums ))
                    {
                        $albums['checked'][$info->name] = $info->id;
                    }
                    else {
                        $albums['unchecked'][$info->name] = $info->id;
                    }
                }
            }

            return $this->data['facebook_albums'] = $albums;
        }

        /** =================================================
        //  Save Settings
        //  -------------------------------------------------
        //  Saves the settings from the control panel
        //  -------------------------------------------------
        //  @return void
        //  ================================================= */
        public function save_settings()
        {
            $post_items = array(
                    'facebook_id' => $this->EE->input->post('facebook_id'),
                    'facebook_albums' => serialize( $this->EE->input->post('facebook_albums') ),
                    'facebook_sync' => $this->EE->input->post('facebook_sync')
            );            

            foreach( $post_items as $key => $value )
            {
                $data = array(
                    'setting_value' => $value
                );
                $this->EE->db->where('setting_name', $key);
                $this->EE->db->update( 'exp_' . $this->fb_settings_table , $data);
            }

            if($post_items['facebook_sync'] == true)
            {
                $this->EE->db->query('TRUNCATE TABLE ' . $this->EE->db->dbprefix($this->fb_photos_table) . ';');
                foreach( $this->EE->input->post('facebook_albums') as $album )
                {
                    $photo_data = $this->get_facebook_graph_data( $album, 'photos' );
                    foreach( $photo_data->data as $photo ) {
                        $images = $photo->images;
                        $image_data[] = array(
                            "photo_url" => $images[0]->source, 
                            "photo_caption" => @$photo->name
                        );
                    }
                }
                foreach( $image_data as $data )
                {
                    $this->EE->db->insert($this->fb_photos_table, $data);
                }
            }

            $this->EE->functions->redirect( BASE . AMP . $this->_base_url );
        }

    }
