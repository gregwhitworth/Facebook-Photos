<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Fbphotos_mcp {
    
    private $EE;
    private $facebook_id;
    private $fb_graph_uri;
    private $fb_settings_table;
    private $_base_url;
    private $valid_fb_id;
    private $data;
    
    public function __construct()
    {
        $this->EE =& get_instance();
        $this->_base_url = 'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module=fbphotos';    
        $this->fb_graph_uri = 'http://graph.facebook.com';
        $this->fb_settings_table = 'fb_photo_settings';
        $this->facebook_id = $this->get_setting_value( 'facebook_id' );
        $this->get_facebook_graph_data( '' );
    }

    // =================================================
    // Index
    // -------------------------------------------------
    // Runs necessary functions for the index page of 
    // the control panel.
    // -------------------------------------------------
    // @return Index view
    // =================================================
    public function index()
    {
        $this->EE->cp->set_variable('cp_page_title', lang('Facebook Photos : Settings'));
        $this->EE->load->helper('form');
        $this->EE->load->library('table');  

        $this->data = array(
            'form_action'     => $this->_base_url . AMP . 'method=save_settings',
            'facebook_id'     => $this->facebook_id
        );     

        if( $this->valid_fb_id )
        {
            $selected_albums = unserialize( $this->get_setting_value( 'facebook_albums' ) );
            $this->get_facebook_photo_albums( $selected_albums );
        }

        return $this->EE->load->view('index', $this->data, TRUE);
    }

    // =================================================
    // Get Setting Value
    // -------------------------------------------------
    // Will return the selected setting from the 
    // exp_fb_photo_settings table.
    // -------------------------------------------------
    // @return str
    // =================================================
    private function get_setting_value( $setting_name )
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
    private function get_facebook_graph_data( $api_ext )
    {
        $graph_uri = sprintf( '%s/%s/%s', $this->fb_graph_uri, $this->facebook_id, $api_ext );
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
    
    // =================================================
    // Get Facebook Photo Albums
    // -------------------------------------------------
    // Returns an array of album information specific
    // to our needs in the control panel.
    // -------------------------------------------------
    // @return array
    // =================================================
    private function get_facebook_photo_albums( $selected_albums )
    {
        $result = $this->get_facebook_graph_data( 'albums' );  
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

    // =================================================
    // Save Settings
    // -------------------------------------------------
    // Saves the settings from the control panel
    // -------------------------------------------------
    // @return void
    // =================================================
    public function save_settings()
    {
        $post_items = array(
                'facebook_id' => $this->EE->input->post('facebook_id'),
                'facebook_albums' => serialize( $this->EE->input->post('facebook_albums') )
        );

        foreach( $post_items as $key => $value )
        {
            $data = array(
                'setting_value' => $value
            );
            $this->EE->db->where('setting_name', $key);
            $this->EE->db->update( 'exp_' . $this->fb_settings_table , $data);
        }

        $this->EE->functions->redirect( BASE . AMP . $this->_base_url );
    }

}