<?php
    if(!defined('BASEPATH')){
        exit('No direct script access allowed');
    }

    class BoardList extends CI_Controller{
        function __construct(){
            parent::__construct();
            $this->load->database();
            $this->load->model('board_m');
        }

        public function index(){
            $this->list();
        }

        public function __remap($method){
            $this->load->view('header_v');
            if(method_exists($this, $method)){
                $this->{"{$method}"}();
            }
        }

        public function lists(){
            $data['list'] = $this->board_m->get_list($this->uri->segment(3));
            $this->load->view('board/list_v', $data);
        }
    }
?>