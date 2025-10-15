<?php
    class Board_model extends CI_Model{

        public function get_board_list()
        {
            $query = $this->db->query('
                select board.*, user.u_id
                from board
                join user on board.u_num = user.u_num
                order by board.b_date desc;
            ');

            $result = $query->result();
            return $result;
        }

        public function get_user_id($u_num)
        {
            $query = $this->db->query('select u_id from user where u_num = ?', [$u_num]);
            $row = $query->row();

            return $row ? $row->u_id : null;
        }

        public function insert_board($u_num, $b_title, $b_content)
        {
            $query = "insert into board(u_num, b_title, b_content) values (?, ?, ?)";
            $this->db->query($query, array($u_num, $b_title, $b_content));

            return ;
        }

        public function get_board_detail($b_num)
        {
            $query = $this->db->query('
                select board.*, user.u_id
                from board
                join user on board.u_num = user.u_num
                where b_num=?', [$b_num]);
            $row = $query->row();

            return $row;
    
        }

        public function delete($b_num)
        {
            $query = $this->db->query('delete from board where b_num=?', [$b_num]);
            return ;
        }

        public function update($b_num, $b_title, $b_content)
        {
            $query = $this->db->query('
                update board
                set b_title=?, b_content=?
                where b_num=?', [$b_title, $b_content, $b_num]);
            return ;
        }

        public function search($keyword)
        {
            $like = "%{$keyword}%";

            $query = $this->db->query("
                select board.*, user.u_id
                from board
                join user on board.u_num = user.u_num
                where board.b_title like ?
                order by board.b_date desc", [$like]);
            
            $result = $query->result();
            return $result;
        }
    }
?>