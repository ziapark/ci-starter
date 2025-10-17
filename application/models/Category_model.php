<?php
    class Category_model extends CI_Model{

        //카테고리 종류
        public function get_category_list(){
            $query = $this->db->query('
                select *
                from category
                order by created_at desc;
            ');

            $result = $query->result();
            return $result;
        }
    }
?>