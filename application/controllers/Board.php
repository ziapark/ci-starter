<?php
    class Board extends CI_Controller{
        public function __construct(){
            parent::__construct();
            $this->load->model('Board_model');
            $this->load->model('Comment_model');
            $this->load->model('Category_model');
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

            $data['limit_per_page'] = $limit_per_page;
            $data['keyword'] = $keyword;         
            $data['board'] = $this->Board_model->get_board_detail($b_num);
            $data['comments'] = $this->Comment_model->get_comments($b_num);

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