<?php
    class Comment extends CI_Controller{
        public function __construct()
        {
            parent::__construct();
            $this->load->model('Board_model');
            $this->load->model('Comment_model');
        }

        //댓글 작성
        public function insert_comment()
        {
            $b_num = $_POST['b_num'];
            $c_content = $_POST['c_content'];
            $u_num = $this->session->userdata('u_num');
            $c_depth = $_POST['c_depth'];
            $c_parent = $_POST['c_parent'];

            $result = $this->Comment_model->insert_comment($b_num, $c_content, $u_num, $c_depth, $c_parent);

            if ($result) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'message' => '댓글 등록에 실패했습니다']);
            }
        }


    }
?>