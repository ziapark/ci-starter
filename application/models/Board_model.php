<?php
    class Board_model extends CI_Model{

        //게시글 목록
        public function get_board_list($limit_per_page, $offset){
            $query = $this->db->query('
                select board.*, user.u_id
                from board
                join user on board.u_num = user.u_num
                order by board.b_date desc
                limit ? offset ?;
            ', [$limit_per_page, $offset]);

            $result = $query->result();
            return $result;
        }

        //전체 게시글 수
        public function count_all_boards(){
            return $this->db->count_all('board');
        }

        //사용자 아이디
        public function get_user_id($u_num){
            $query = $this->db->query('
                select u_id
                from user
                where u_num = ?
            ', [$u_num]);
            $row = $query->row();

            return $row ? $row->u_id : null;
        }

        //게시글 등록
        public function insert_board($u_num, $b_title, $b_content){  
            $result = $this->db->query('
                insert into board
                (u_num, b_title, b_content)
                values (?, ?, ?) 
            ', [$u_num, $b_title, $b_content]);

            return $result;
        }

        //게시글 정보
        public function get_board_detail($b_num){
            $query = $this->db->query('
                select board.*, user.u_id
                from board
                join user on board.u_num = user.u_num
                where b_num=?', [$b_num]);
            $row = $query->row();

            return $row;
    
        }

        //게시글 삭제
        public function delete($b_num){
            $query = $this->db->query('
                delete from board 
                where b_num=?
            ', [$b_num]);
            return ;
        }

        //게시글 수정
        public function update($b_num, $b_title, $b_content){
            $query = $this->db->query('
                update board
                set b_title=?, b_content=?
                where b_num=?', [$b_title, $b_content, $b_num]);
            return ;
        }

        //키워드 검색
        public function search($keyword, $limit_per_page, $offset){
            $like = "%{$keyword}%";

            $query = $this->db->query("
                select board.*, user.u_id
                from board
                join user on board.u_num = user.u_num
                where board.b_title like ?
                order by board.b_date desc limit ? offset ?", [$like, $limit_per_page, $offset]);
            
            $result = $query->result();
            return $result;
        }
    }
?>