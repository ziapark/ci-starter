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

        //댓글 정보
        public function get_comments($b_num){
            $query = $this->db->query('
                with recursive tmp_table as(
                    select
                        c_num,
                        c_content,
                        u_num,
                        c_date,
                        b_num,
                        c_depth,
                        c_parent,
                        cast(lpad(c_num,10, "0") as varchar(2000)) as sort_path
                    from comment
                    where b_num=? and c_depth = 0
                    union all
                    select
                        c.c_num,
                        c.c_content,
                        c.u_num,
                        c.c_date,
                        c.b_num,
                        t.c_depth+1,
                        c.c_parent,
                        cast(concat(t.sort_path, "/", lpad(c.c_num, 10, "0")) as varchar(2000)) as sort_path
                    from tmp_table t
                    inner join comment c
                    on t.c_num = c.c_parent
                )
                select t.*, u.u_id
                from tmp_table t
                join user u
                on t.u_num = u.u_num
                order by sort_path;
            ', [$b_num]);

            $result = $query->result();
            return $result;
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