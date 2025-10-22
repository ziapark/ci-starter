<?php
    class Comment_model extends CI_Model{

        //답글 작성
        public function insert_comment($b_num, $c_content, $u_num, $c_depth, $c_parent){
            $sort_path ='';
            $new_comment_id = 0;

            if($c_parent > 0){
                $query = $this->db->query('
                    select sort_path
                    from comment
                    where c_num = ?
                    limit 1
                ', [$c_parent]);
                $parent = $query->row();

                if(!$parent || empty($parent->sort_path)){
                    return false;
                }
                $parent_sort_path = $parent->sort_path;

                $this->db->query('
                    insert into comment (b_num, c_content, u_num, c_depth, c_parent)
                    values (?, ?, ?, ?, ?)
                ', [$b_num, $c_content, $u_num, $c_depth, $c_parent]);
                $new_comment_id = $this->db->insert_id();

                $sort_path = $parent_sort_path.'/'.str_pad($new_comment_id, 10, '0');
            }else{
                $this->db->query('
                    insert into comment (b_num, c_content, u_num, c_depth, c_parent)
                    values (?, ?, ?, 0, NULL)
                ', [$b_num, $c_content, $u_num]);
                $new_comment_id = $this->db->insert_id();

                $sort_path = str_pad($new_comment_id, 10, '0');
            }

            $this->db->query('
                update comment set sort_path = ? where c_num = ?
            ', [$sort_path, $new_comment_id]);
            
            return $new_comment_id;
        }

        //댓글 정보
        public function get_comments($b_num){
            $query = $this->db->query('
                select c.*, u.u_id
                from comment c
                join user u
                on c.u_num = u.u_num
                where c.b_num = ?
                order by c.sort_path
            ', [$b_num]);

            $result = $query->result();
            return $result;
        }

        //댓글 삭제 시 b_num을 찾기 위한 함수
        public function get_b_num_by_c_num($c_num){
            $query = $this->db->query('
                select b_num
                from comment
                where c_num = ?
            ', [$c_num]);

            $row = $query->row();
            return $row ? $row->b_num : false;
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