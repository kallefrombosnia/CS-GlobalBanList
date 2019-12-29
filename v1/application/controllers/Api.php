<?php
defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

class Api extends REST_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index_get(){
        return $this->set_response($this->api_model->generateOutput(false, array('Error: nepoznata metoda'), false));
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
                'resource' => $this->input->get('resource')
            );

            if($this->api_model->banAdd($data)){
                return $this->set_response($this->api_model->generateOutput(true, array(), true));
            }else{
                return $this->set_response($this->api_model->generateOutput(false, array('Error: desio se problem prilikom upisivanja bana'), false));
            }

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
            
            if($this->api_model->isAdmin($this->input->get('apikey'))){

                $boolValue = $this->api_model->banDelete($type, $id);
                return $this->set_response($this->api_model->generateOutput(true, array(), $boolValue));

            }else{
                return $this->set_response($this->api_model->generateOutput(false, array('Error: nisi admin'), false));
            }
             
        } else {
            return $this->set_response($this->api_model->generateOutput(false, array('Error: apikey potreban'), false));
        }

    }

    public function version_get($type = null){

        if ($this->api_model->checkAuth($this->input->get('apikey'))) {

            $typelist = array('api', 'server');

            if (!in_array($type, $typelist)) {
                return $this->set_response($this->api_model->generateOutput(false, array('Error: type nije validan'), false));
            }

            $version = $this->api_model->version($type);

            if($version === false){
                return $this->set_response($this->api_model->generateOutput(false, array('Error: pogresan izbor'), false));
            }else{
                return $this->set_response($this->api_model->generateOutput(true, array(), $version));
            }
            

        } else {
            return $this->set_response($this->api_model->generateOutput(false, array('Error: apikey potreban'), false));
        }

    }
    
    public function serveradd_post(){

        if ($this->api_model->checkAuth($this->input->post('apikey'))) {
            
            if($this->api_model->isAdmin($this->input->post('apikey'))){
               
                $data = array(
                    'server_address' => $this->input->post('address'),
                    'apikey' => $this->input->post('apikey')
                );

                if(!$this->api_model->serverExists($data['server_address'])){

                    $serverInfo = $this->api_model->addServer($data);
                    return $this->set_response($this->api_model->generateOutput(true, array(), $serverInfo));

                }else{
                    return $this->set_response($this->api_model->generateOutput(false, array('Error: server vec postoji'), false));
                }
        
            }else{
                return $this->set_response($this->api_model->generateOutput(false, array('Error: nisi admin'), false));
            }
             
        } else {
            return $this->set_response($this->api_model->generateOutput(false, array('Error: apikey potreban'), false));
        }
        

    }

    public function test_get(){
        if ($this->api_model->checkAuth($this->input->get('apikey'))) {
            
            if($this->api_model->isAdmin($this->input->get('apikey'))){

            
                return $this->set_response($this->api_model->generateOutput(true, array(), true));

            }else{
                return $this->set_response($this->api_model->generateOutput(false, array('Error: nisi admin'), false));
            }

             
        } else {
            return $this->set_response($this->api_model->generateOutput(false, array('Error: apikey potreban'), false));
        }
    }

 

}
