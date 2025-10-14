<?php
    if(!defined('BASEPATH')){
        exit('No direct script access allowed');
    }

    class Board_model extends CI_Model{
        function __construct(){
            parent::__construct;
        }

        function get_list($table = 'ci_board'){
            $sql="select * from $table order by board_id desc";
            $query = $this->db->query($sql);
            $result =  $query->result();

            return $result;
        }
    }