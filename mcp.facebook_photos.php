<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Facebook_Photos_mcp {
    
    public $return_data;
    private $base_url;
    private $form_base;
    private $EE;
    
    public function __construct()
    {
        $this->EE =& get_instance();
        $this->_base_url = 'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module=facebook_photos';
        $this->EE->load->helper('text');        
    }

    public function index()
    {
        $this->EE->cp->set_variable('cp_page_title', lang('Facebook Photos : Settings'));
        $this->EE->load->helper('form');
        $this->EE->load->library('table');

        $query = $this->EE->db->get_where( 'fb_photo_settings', array('setting_name' => 'facebook_id') );
        $results = $query->row('fb_photo_settings');

        $facebook_id = $results->setting_value;

        $albums = $this->get_facebook_photo_albums( $facebook_id );
        
        $data = array(
            'form_action'     => $this->_base_url . AMP . 'method=save_settings',
            'facebook_id'     => $results->setting_value,
            'facebook_albums' => $albums
        );

        return $this->EE->load->view('index', $data, TRUE);
    }

    private function get_facebook_photo_albums( $facebook_id )
    {
        $graph_uri = sprintf('http://graph.facebook.com/%s/albums', $facebook_id);
        $result = file_get_contents( $graph_uri );
        $result = json_decode( $result );
        $albums = array();

        foreach( $result->data as $info ) {
            $albums[$info->name] = $info->id;
        }

        return $albums;
    }

    public function save_settings()
    {
        $data = array(
            'setting_value' => $this->EE->input->post('facebook_id')
        );
        $this->EE->db->where('setting_name', 'facebook_id');
        $this->EE->db->update('exp_fb_photo_settings', $data);

        $this->EE->session->set_flashdata('message_success', lang('global_settings_saved'));
        $this->EE->functions->redirect( BASE . AMP . $this->_base_url );
    }

}