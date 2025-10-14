<?php
    class Board_model extends CI_Model{
        function get_board_list(){
            $query = $this->db->query('
                select * from board;
            ');

            $result = $query->result();
            
            return $result;
        }
    }
?>