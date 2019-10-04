<?php
defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

class Api extends REST_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function bans_get()
    {
        if ($this->api_model->checkAuth($this->input->get('apikey'))) {

            $bans = $this->api_model->getAllBans();
            return $this->set_response($this->api_model->generateOutput(true, array(), $bans));
        } else {
            return $this->set_response($this->api_model->generateOutput(false, array('Error: apikey potreban'), false));
        }
    }

    public function banview_get($type = null, $id = null)
    {
        if ($this->api_model->checkAuth($this->input->get('apikey'))) {

            $typelist = array('nick', 'steamid', 'ip');

            if (empty($id)) {
                return $this->set_response($this->api_model->generateOutput(false, array('Error: id bana nije specificiran'), false));
            }

            if (!in_array($type, $typelist)) {
                return $this->set_response($this->api_model->generateOutput(false, array('Error: type nije validan'), false));
            }

            $ban = $this->api_model->getBan($id, $type);
            $this->set_response($this->api_model->generateOutput(true, array(), $ban));
        } else {
            return $this->set_response($this->api_model->generateOutput(false, array('Error: apikey potreban'), false));
        }

    }

    public function checkplayer_get($type = null, $id = null)
    {
        if ($this->api_model->checkAuth($this->input->get('apikey'))) {

            $typelist = array('nick', 'steamid', 'ip');

            if (empty($id)) {
                return $this->set_response($this->api_model->generateOutput(false, array('Error: id bana nije specificiran'), false));
            }

            if (!in_array($type, $typelist)) {
                return $this->set_response($this->api_model->generateOutput(false, array('Error: type nije validan'), false));
            }

            $boolValue = $this->api_model->checkPlayerForBan($id, $type);
            return $this->set_response($this->api_model->generateOutput(true, array(), $boolValue));

        } else {
            return $this->set_response($this->api_model->generateOutput(false, array('Error: apikey potreban'), false));
        }

    }

    public function banadd_get(){

        if ($this->api_model->checkAuth($this->input->get('apikey'))) {

            $data = array(
                'nick' => $this->input->get('nick'),
                'steamid' => $this->input->get('steamid'),
                'ip' => $this->input->get('ip'),
                'server_ip' => $this->input->get('server_ip'),
            );

            $this->api_model->banAdd($data);

        } else {
            return $this->set_response($this->api_model->generateOutput(false, array('Error: apikey potreban'), false));
        }

    }

    public function bandelete_get($type = null, $id = null){

        if ($this->api_model->checkAuth($this->input->get('apikey'))) {

            $typelist = array('nick', 'steamid', 'ip');

            if (empty($id)) {
                return $this->set_response($this->api_model->generateOutput(false, array('Error: id bana nije specificiran'), false));
            }

            if (!in_array($type, $typelist)) {
                return $this->set_response($this->api_model->generateOutput(false, array('Error: type nije validan'), false));
            }
            
            $boolValue = $this->api_model->banDelete($type, $id);
            return $this->set_response($this->api_model->generateOutput(true, array(), $boolValue));

        } else {
            return $this->set_response($this->api_model->generateOutput(false, array('Error: apikey potreban'), false));
        }

    }

}
