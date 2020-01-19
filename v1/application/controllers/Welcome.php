<?php
defined('BASEPATH') or exit('No direct script access allowed');


class Welcome extends CI_Controller{
    
    public function index(){
      
        if($this->welcome->checkLoginSession()){
            $this->load->view('templates/header');
            $this->load->view('templates/navbar');
            $this->load->view('index');
            $this->load->view('templates/footer');
        }else{
            echo 'aaa';
            redirect('/login');
        }
        
    }

}