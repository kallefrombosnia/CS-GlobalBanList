<?php

defined('BASEPATH') OR exit('No direct script access allowed');


class Api_model extends CI_Model

{
    
    public function generateOutput($success, $errors, $data) {
		return array('success' => $success, 'errors' => $errors, 'data' => $data);
    }
    
    public function checkAuth($apikey = null){

        if($this->config->item('auth')){
            if(!is_null($apikey)){
                $query = $this->db->query("SELECT `id` FROM `access_keys` WHERE access_key= $apikey ");

                if($query->num_rows() > 0 or in_array($apikey,$this->config->item('admin_keys'))){
                    //key exists
                    return true;
                }
                return false;
            }
        }else{
            // disabled in config but still pass function
            return true;
        }     
    }

    public function isAdmin($apikey = null){

            if(!is_null($apikey)){ 

                $query = $this->db->query("SELECT `admin` FROM `access_keys` WHERE access_key= $apikey");
                $data = $query->result_array();
      
                if(@$data[0]['admin'] === '1' or in_array($apikey,$this->config->item('admin_keys'))){
                    //key exists in db or array
                    return true;
                } 

                return false;
            }

            return false;
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
        $this->db->insert('banlist', $data);

        return $this->db->affected_rows() > 0;
    }

    public function banDelete($type, $id){

        $this->db->where($type, $id);
        $this->db->delete('banlist');

        return $this->db->affected_rows() > 0;
    }

    public function version($type){

        $query = $this->db->query("SELECT * FROM `version` ");
        $info = $query->result_array();

        switch ($type) {
            case 'api':           
                return $info[0]['api_version']; 
            case 'server':
                return $info[0]['plugin_version']; 
            default:
                    return false;
            break;
        }
    }

    public function serverExists($ip){
        $query = $this->db->query("SELECT 1 FROM `access_keys` WHERE `server_address`= ".$this->db->escape($ip)." LIMIT 1");

        return $query->num_rows() > 0 ? true : false;
    }

    public function addServer($data){

        // todo: migration for admin who made new record
        $this->db->set('admin', '0', FALSE);
        $this->db->set('banned', '0', FALSE);
        $this->db->set('created_at', 'NOW()', FALSE);
        $this->db->set('updated_at', 'NOW()', FALSE);
        $this->db->set('server_address', $data['server_address']);
        $this->db->set('access_key', $this->generateKey());
        $this->db->insert('access_keys');

        $query = $this->db->query("SELECT * FROM `access_keys` WHERE `server_address`= ".$this->db->escape($data['server_address'])." LIMIT 1");

        return $query->result_array();
    }
    
    private function generateKey(){
        return md5(rand());   
    }
}
