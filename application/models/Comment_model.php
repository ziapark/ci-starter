<?php
    class Comment_model extends CI_Model{

        //댓글 작성
        public function insert_comment($b_num, $c_content, $u_num, $c_depth, $c_parent)
        {
            $result = $this->db->query('
                insert into comment
                (b_num, c_content, u_num, c_depth, c_parent)
                values (?, ?, ?, ?, ?)
            ', [$b_num, $c_content, $u_num, $c_depth, $c_parent]);

            return $result;
        }

        //최상위 댓글 정보
        public function get_comments($b_num)
        {
            $query = $this->db->query('
                select comment.*, user.u_id
                from comment
                join user on comment.u_num = user.u_num
                where comment.b_num = ? and comment.c_depth = 0
                order by comment.c_date desc;
            ', [$b_num]);

            $result = $query->result();
            return $result;
        }

        
    }
?>