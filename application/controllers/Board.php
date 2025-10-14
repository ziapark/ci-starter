<?php
    class Board extends CI_Controller{
        public function __construct(){
            parent::__construct();
            $this->load->model('Board_model');
        }

        public function view(){
            $data['board'] = $this->Board_model->get_board_list();
            $this->load->view('board_list_view', $data);
        }
    }
?>