<?php
    defined('BASEPATH') OR exit('No direct script access allowed');

    class Board extends MY_Controller{
        public function __construct(){
            parent::__construct();
        }

        //게시판 목록
        public function board_list(){
            $limit_per_page = isset($_GET['limit_per_page']) && is_numeric($_GET['limit_per_page']) ? (int)$_GET['limit_per_page'] : 10;    //한 페이지당 게시글 수

            $total_rows = $this->Board_model->count_all_boards();   //전체 게시글 수

            $current_page = isset($_GET['current_page']) && is_numeric($_GET['current_page']) && $_GET['current_page'] > 0 ? (int)$_GET['current_page'] : 1;    //현재 페이지
            $total_pages = ceil($total_rows / $limit_per_page);     //전체 페이지
            
            $limit_page = 5;    //표시될 페이지 수       
            $end_page = ceil($current_page/$limit_page) * $limit_page;      //마지막 페이지 번호
            if($total_pages < $end_page){
                $end_page = $total_pages;
            }
            $start_page = ($end_page - $limit_page) + 1;    //시작 페이지 번호
            if($start_page < 1){
                $start_page = 1;
            }
            $prev = ($current_page == 1) ? false : true;
            $next = ($current_page == $total_pages) ? false : true;

            $offset = ($current_page - 1) * $limit_per_page;    //게시글 시작

            $keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';   //검색키워드
            if($keyword !== '') {
                $current_category = isset($_GET['category_idx']) ? trim($_GET['category_idx']) : 'all';
                $data['board'] = $this->Board_model->search($keyword, $limit_per_page, $offset, $current_category);       
            } else {
                $current_category = isset($_GET['category_idx']) ? trim($_GET['category_idx']) : 'all';
                if($current_category != 'all'){
                    $data['board'] = $this->Board_model->get_board_list_category($limit_per_page, $offset, $current_category);
                }else{
                    $data['board'] = $this->Board_model->get_board_list($limit_per_page, $offset);
                }
            }

            $data['total_pages'] = $total_pages;
            $data['current_page'] = $current_page;
            $data['limit_per_page'] = $limit_per_page;
            $data['start_page'] = $start_page;
            $data['end_page'] = $end_page;
            $data['prev'] = $prev;
            $data['next'] = $next;
            $data['keyword'] = $keyword;
            
            //카테고리 종류
            $data['categories'] = $this->Category_model->get_category_list();
            $data['current_category'] = $current_category;

            $this->load->view('board_list_view', $data);            
        }

        //게시글 작성 페이지
        public function insert_view(){
            $u_num = $this->session->userdata('u_num');
            if ( !$u_num ){
                redirect('board/board_list');
                exit;
            }
            
            $u_id = $this->Board_model->get_user_id($u_num);
            $data['u_num'] = $u_num;
            $data['u_id'] = $u_id;
            $data['categories'] = $this->Category_model->get_category_list();

            $this->load->view('board_insert_view', $data);
        }

        //게시글 작성
        public function insert(){
            $u_num = $_POST['u_num'];
            $b_title = $_POST['b_title'];
            $b_content = $_POST['b_content'];
            $category_idx = $_POST['category_idx'];

            $data = $this->Board_model->insert_board($u_num, $b_title, $b_content, $category_idx);
            redirect('board/board_list');
        }

        //게시글 상세
        public function board_detail($b_num){
            $limit_per_page = isset($_GET['limit_per_page']) && is_numeric($_GET['limit_per_page']) ? (int)$_GET['limit_per_page'] : 10;        
            $keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';

            $this->load->driver('cache', array('adapter' => 'file'));

            $comment_per_page = 15; // 한 페이지에 15개씩
            $comment_limit_page = 5; // 하단에 페이지 번호 5개씩
            $comment_current_page = $this->input->get('comment_current_page', TRUE) ? (int)$this->input->get('comment_current_page', TRUE) : 1;

            $cache_id = 'comment_count_for_board_'.$b_num;
            $comments_ttl = 3600;

            $total_comment_rows = $this->cache->get($cache_id);
            if(!$total_comment_rows){
                $total_comment_rows = $this->Comment_model->count_comments_by_board($b_num);
                $this->cache->save($cache_id, $total_comment_rows, $comments_ttl);
            }

            $comment_offset = ($comment_current_page - 1) * $comment_per_page;
            $total_comment_pages = ceil($total_comment_rows / $comment_per_page); 
    
            $end_page = ceil($comment_current_page / $comment_limit_page) * $comment_limit_page;
            if($total_comment_pages < $end_page){
                $end_page = $total_comment_pages;
            }
            
            $start_page = ($end_page - $comment_limit_page) + 1;
            if($start_page < 1){
                $start_page = 1;
            }

            $prev = ($comment_current_page == 1) ? false : true;
            $next = ($comment_current_page == $total_comment_pages) ? false : true;
            
            $data['comment_total_pages'] = $total_comment_pages;
            $data['comment_current_page'] = $comment_current_page;
            $data['comment_start_page'] = $start_page;
            $data['comment_end_page'] = $end_page;
            $data['comment_prev'] = $prev;
            $data['comment_next'] = $next;
            $data['comments'] = $this->Comment_model->get_comments($b_num, $comment_per_page, $comment_offset);
            $data['limit_per_page'] = $limit_per_page;
            $data['keyword'] = $keyword;         
            $data['board'] = $this->Board_model->get_board_detail($b_num);

            $this->load->view('board_detail_view', $data);
        }

        //게시글 삭제
        public function delete($b_num){
            $this->Board_model->delete($b_num);
            redirect('board/board_list');
        }

        //게시글 수정 폼
        public function update_view($b_num){
            $limit_per_page = isset($_GET['limit_per_page']) && is_numeric($_GET['limit_per_page']) ? (int)$_GET['limit_per_page'] : 10; 
            $data['limit_per_page'] = $limit_per_page;
            $keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
            $data['keyword'] = $keyword;

            $data['board'] = $this->Board_model->get_board_detail($b_num);
            $data['categories'] = $this->Category_model->get_category_list();

            $this->load->view('board_update_view', $data);
        }
        
        //게시글 수정
        public function update(){
            $b_num = $_POST['b_num'];
            $b_title = $_POST['b_title'];
            $b_content = $_POST['b_content'];
            $category_idx = $_POST['category_idx'];

            $data = $this->Board_model->update($b_num, $b_title, $b_content, $category_idx);
            redirect('board/board_list');
        }

    }
?>