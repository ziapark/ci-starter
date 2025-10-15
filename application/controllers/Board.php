<?php
    class Board extends CI_Controller{
        public function __construct()
        {
            parent::__construct();
            $this->load->model('Board_model');
        }

        //게시판 목록
        public function board_list()
        {
            $data['board'] = $this->Board_model->get_board_list();
            $this->load->view('board_list_view', $data);            
        }

        //게시글 작성 페이지
        public function insert_view()
        {
            $u_num = $this->session->userdata('u_num');
            $u_id = $this->Board_model->get_user_id($u_num);
            $this->load->view('board_insert_view', ['u_num' => $u_num, 'u_id' => $u_id]);
        }

        //게시글 작성
        public function insert()
        {
            $u_num = $_POST['u_num'];
            $b_title = $_POST['b_title'];
            $b_content = $_POST['b_content'];

            $data = $this->Board_model->insert_board($u_num, $b_title, $b_content);
            redirect('board/view/board_list');
        }

        //게시글 상세
        public function board_detail($b_num)
        {
            $data['board'] = $this->Board_model->get_board_detail($b_num);
            $this->load->view('board_detail_view', $data);
        }

        //게시글 삭제
        public function delete($b_num)
        {
            $this->Board_model->delete($b_num);
            redirect('board/view/board_list');
        }

        //게시글 수정
        public function update_view($b_num)
        {
            $data['board'] = $this->Board_model->get_board_detail($b_num);
            $this->load->view('board_update_view', $data);
        }
        
        public function update()
        {
            $b_num = $_POST['b_num'];
            $b_title = $_POST['b_title'];
            $b_content = $_POST['b_content'];

            $data = $this->Board_model->update($b_num, $b_title, $b_content);
            redirect('board/view/board_list');
        }

        //게시글 검색(title)
        public function search()
        {
            $keyword = $_GET['keyword'];

            $data['board'] = $this->Board_model->search($keyword);
            $this->load->view('board_list_view', $data);
        }

    }
?>