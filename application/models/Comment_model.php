<?php
    class Comment_model extends CI_Model{

        //답글 작성
        public function insert_comment($b_num, $c_content, $u_num, $c_depth, $c_parent){
            $result = $this->db->query('
                insert into comment
                (b_num, c_content, u_num, c_depth, c_parent)
                values (?, ?, ?, ?, ?)
            ', [$b_num, $c_content, $u_num, $c_depth, $c_parent]);

            if($result){
                return $this->db->insert_id();
            }else{
                return $result;
            }       
        }


        //최상위 댓글 정보
        public function get_comments($b_num, $limit_comment, $offset){
            $query = $this->db->query('
                select comment.*, user.u_id
                from comment
                join user on comment.u_num = user.u_num
                where comment.b_num = ? and comment.c_depth = 0
                order by comment.c_date desc
                limit ? offset ?;
            ', [$b_num, $limit_comment, $offset]);

            $result = $query->result();
            return $result;
        }

        //답글 정보
        public function get_reply_list($b_num, $p_num, $b_depth){
            $query = $this->db->query('
                select comment.*, user.u_id
                from comment
                join user on comment.u_num = user.u_num
                where comment.b_num = ? and comment.c_parent = ? and comment.c_depth = ?
                order by comment.c_date;
            ', [$b_num, $p_num, $b_depth]);

            $result = $query->result();
            return $result;
        }

        //최상위 댓글 개수
        public function count_all_comments($b_num){
            $query = $this->db->query('
                select count(*) as cnt
                from comment
                where b_num = ? and c_depth = 0;
            ', [$b_num]);

            return $query->row()->cnt;
        }

        //작성한 답글 정보
        public function get_comment_by_id($new_comment_id){
            $query = $this->db->query('
                select comment.*, user.u_id
                from comment
                join user on comment.u_num = user.u_num
                where comment.c_num = ?
            ', [$new_comment_id]);

            return $query->row();
        }

        //댓글 삭제
        public function delete_comment($c_num){
            $query = $this->db->query('
                delete from comment where c_num = ?
            ', [$c_num]);

            return $query;
        }
    }
?>