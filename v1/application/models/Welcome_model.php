<?php

defined('BASEPATH') OR exit('No direct script access allowed');


class Welcome_model extends CI_Model{

   public function checkLoginSession(){
       return $this->session->has_userdata('id') ? true : false;
   }
}