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

        return $query->num_rows() > 0 ? "true" : "false";

    }



    public function insert($data){



        $this->user_name    = $data['name']; // please read the below note

        $this->user_password  = $data['pass'];

        $this->user_type = $data['type'];



        if($this->db->insert('tbl_user',$this))

        {    

            return 'Data is inserted successfully';

        }

            else

        {

            return "Error has occured";

        }

    }



    public function update($id,$data){



        $this->user_name    = $data['name']; // please read the below note

        $this->user_password  = $data['pass'];

        $this->user_type = $data['type'];

        $result = $this->db->update('tbl_user',$this,array('user_id' => $id));

        if($result)

        {

            return "Data is updated successfully";

        }

        else

        {

            return "Error has occurred";

        }

    }



    public function delete($id){



        $result = $this->db->query("delete from `tbl_user` where user_id = $id");

        if($result)

        {

            return "Data is deleted successfully";

        }

        else

        {

            return "Error has occurred";

        }

    }



}
