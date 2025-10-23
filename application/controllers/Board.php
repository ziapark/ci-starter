<?php
    defined('BASEPATH') OR exit('No direct script access allowed');

    class Board extends MY_Controller{
        public function __construct(){
            parent::__construct();
        }

        //게시판 목록
        public function board_list(){
            $limit_per_page = isset($_GET['limit_per_page']) && is_numeric($_GET['limit_per_page']) ? (int)$_GET['limit_per_page'] : 10;    //한 페이지당 게시글 수
            $current_page = isset($_GET['current_page']) && is_numeric($_GET['current_page']) && $_GET['current_page'] > 0 ? (int)$_GET['current_page'] : 1;    //현재 페이지
            $limit_page = 5;    //표시될 페이지 수 
            
            $keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';   //검색키워드
            $current_category = isset($_GET['category_idx']) ? trim($_GET['category_idx']) : 'all';

            if($keyword !== '') {
                $total_rows = $this->Board_model->count_boards_by_keyword($keyword, $current_category);  
            } else {
                if($current_category != 'all'){
                    $total_rows = $this->Board_model->count_boards_by_category($current_category);
                }else{
                    $total_rows = $this->Board_model->count_all_boards();
                }
            }
            $total_rows = (int)$total_rows;

            $pagination = $this->pagination(
                $total_rows,
                $current_page,
                $limit_per_page,
                $limit_page
            );
            
            if($keyword !== '') {
                $data['board'] = $this->Board_model->search($keyword, $limit_per_page, $pagination['offset'], $current_category);
            } else {
                if($current_category != 'all'){
                    $data['board'] = $this->Board_model->get_board_list_category($limit_per_page, $pagination['offset'], $current_category);
                }else{
                    $data['board'] = $this->Board_model->get_board_list($limit_per_page, $pagination['offset']);
                }
            }

            $this->optimizer->setCss('../assets/css/board.css');
            $optimizer_tags = $this->optimizer->makeOptimizerScriptTag();

            $data['css_optimizer'] = $optimizer_tags['css_optimizer'];
            
            $data['total_pages'] = $pagination['total_pages'];
            $data['current_page'] = $pagination['current_page'];
            $data['start_page'] = $pagination['start_page'];
            $data['end_page'] = $pagination['end_page'];
            $data['prev'] = $pagination['prev'];
            $data['next'] = $pagination['next'];
            
            $data['limit_per_page'] = $limit_per_page;
            $data['keyword'] = $keyword;
            
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

            $this->optimizer->setCss('../assets/css/board.css');
            $optimizer_tags = $this->optimizer->makeOptimizerScriptTag();

            $data['css_optimizer'] = $optimizer_tags['css_optimizer'];

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
            $page = $this->input->get('comment_current_page', TRUE);
            $comment_current_page = ($page && (int)$page > 0) ? (int)$page : 1;

            $cache_id = 'comment_count_for_board_'.$b_num;
            $comments_ttl = 3600;

            $total_comment_rows = $this->cache->get($cache_id);
            if(!$total_comment_rows){
                $total_comment_rows = $this->Comment_model->count_comments_by_board($b_num);
                $this->cache->save($cache_id, $total_comment_rows, $comments_ttl);
            }
            $total_comment_rows = (int)$total_comment_rows;

            $pagination = $this->pagination(
                $total_comment_rows,
                $comment_current_page,
                $comment_per_page,
                $comment_limit_page
            );
            
            $this->optimizer->setCss('../assets/css/board.css');
            $optimizer_tags = $this->optimizer->makeOptimizerScriptTag();

            $data['css_optimizer'] = $optimizer_tags['css_optimizer'];

            $data['comment_total_pages'] = $pagination['total_pages'];
            $data['comment_current_page'] = $pagination['current_page'];
            $data['comment_start_page'] = $pagination['start_page'];
            $data['comment_end_page'] = $pagination['end_page'];
            $data['comment_prev'] = $pagination['prev'];
            $data['comment_next'] = $pagination['next'];
            
            $data['comments'] = $this->Comment_model->get_comments($b_num, $comment_per_page, $pagination['offset']);
           
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

            $this->optimizer->setCss('../assets/css/board.css');
            $optimizer_tags = $this->optimizer->makeOptimizerScriptTag();

            $data['css_optimizer'] = $optimizer_tags['css_optimizer'];

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