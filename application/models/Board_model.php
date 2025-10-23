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

        //카테고리별 게시글 목록
        public function get_board_list_category($limit_per_page, $offset, $current_category){
            $query = $this->db->query('
                select board.*, user.u_id, category.category_name
                from board
                join user on board.u_num = user.u_num
                join category on board.category_idx = category.category_idx
                where board.category_idx = ?
                order by board.b_date desc
                limit ? offset ?;
            ', [$current_category, $limit_per_page, $offset]);

            $result = $query->result();
            return $result;
        }

        //전체 게시글 수
        public function count_all_boards(){
            return $this->db->count_all('board');
        }

        //카테고리 별 게시글 수
        public function count_boards_by_category($current_category){
            $query = $this->db->query('
                select count(*) as cnt
                from board
                where category_idx = ?
            ', [$current_category]);

            $row = $query->row();
            return $row ? (int)$row->cnt : 0;
        }

        //키워드 검색시 결과 게시글 수
        public function count_boards_by_keyword($keyword, $current_category){
            $like = "%{$keyword}%";

            if($current_category != 'all'){
                $sql = "
                    select count(*) as cnt
                    from board
                    where b_title like ? and category_idx = ?
                ";
                $params = [$like, $current_category];
            }else{
                $sql = "
                    select count(*) as cnt
                    from board
                    where b_title like ?
                ";
                $params = [$like];
            }

            $query = $this->db->query($sql, $params);
            $row = $query->row();
            return $row ? (int)$row->cnt : 0;
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

        //게시글 작성
        public function insert_board($u_num, $b_title, $b_content, $category_idx){
            $result = $this->db->query('
                insert into board
                (u_num, b_title, b_content, category_idx)
                values (?, ?, ?, ?) 
            ', [$u_num, $b_title, $b_content, $category_idx]);

            return $result;
        }

        //게시글 정보
        public function get_board_detail($b_num){
            $query = $this->db->query('
                select board.*, user.u_id, category.category_name
                from board
                join user on board.u_num = user.u_num
                join category on board.category_idx = category.category_idx
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
        public function update($b_num, $b_title, $b_content, $category_idx){
            $query = $this->db->query('
                update board
                set b_title=?, b_content=?, category_idx=?
                where b_num=?', [$b_title, $b_content, $category_idx, $b_num]);
            return ;
        }

        //키워드 검색
        public function search($keyword, $limit_per_page, $offset, $current_category){
            $like = "%{$keyword}%";

            if($current_category != 'all'){
                $query = $this->db->query("
                    select board.*, user.u_id
                    from board
                    join user on board.u_num = user.u_num
                    where board.b_title like ? and board.category_idx = ?
                    order by board.b_date desc limit ? offset ?
                ", [$like, $current_category, $limit_per_page, $offset]);
            }else{
                $query = $this->db->query("
                    select board.*, user.u_id
                    from board
                    join user on board.u_num = user.u_num
                    where board.b_title like ?
                    order by board.b_date desc limit ? offset ?
                ", [$like, $limit_per_page, $offset]);
            }   

            $result = $query->result();
            return $result;
        }
    }
?>