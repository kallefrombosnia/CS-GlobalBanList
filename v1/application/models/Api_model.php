<?php

defined('BASEPATH') OR exit('No direct script access allowed');


class Api_model extends CI_Model

{
    /*
   function __call($method, $arguments){
	
        if(method_exists($this, $method)) {
            if($this->api_model->checkAuth($this->input->get('apikey'))){
				return ('good');
				return call_user_func_array(array($this,$method),$arguments);
			}
			echo('bad');
			return $this->set_response($this->api_model->generateOutput(false, array('Error: apikey potreban'), false));
        }
        return ('good');
    }
    */
    
    public function generateOutput($success, $errors, $data) {
		return array('success' => $success, 'errors' => $errors, 'data' => $data);
    }
    
    public function checkAuth($apikey = null){
        if($this->config->item('auth')){
            if(!is_null($apikey)){
                return true;
            }
            return false;
        }
    }


    public function getAllBans(){

        $query = $this->db->query("SELECT * FROM `banlist`");
        return $query->result_array();

    }

    public function getBan($id, $type){

        $query = $this->db->query("SELECT * FROM `banlist` WHERE `$type`=$id LIMIT 1");

        return $query->result_array();

    }

    public function checkPlayerForBan($id, $type){

        $query = $this->db->query("SELECT 1 FROM `banlist` WHERE `$type`=$id LIMIT 1");

        return $query->num_rows() > 0 ? true : false;

    }


    public function banAdd($data){

        $this->db->set('created_at', 'NOW()', FALSE);
        $this->db->set('updated_at', 'NOW()', FALSE);

        return $this->db->insert('banlist', $data);
    }

    public function banDelete($type, $id){

        $this->db->where($type, $id);
        $this->db->delete('banlist');

        if($this->checkPlayerForBan($id, $type)){
            return false;
        }else{
            return true;
        };

    }
}
