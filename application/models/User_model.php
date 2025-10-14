<?php
    class User_model extends CI_Model{
        public $u_id;
        public $u_pw;
        public $u_name;

        public function __construct()
        {
            parent::__construct();
        }

        public function sign()
        {
            $this->u_id = $_POST['u_id'];
            $this->u_pw = $_POST['u_pw'];
            $this->u_name = $_POST['u_name'];

            $this->db->insert('ci_board', $this);
        }
    }   