<?php
    class Comment extends MY_Controller{
        public function __construct(){
            parent::__construct();

            $this->load->driver('cache', array('adapter' => 'file'));
            $this->load->model('Board_model');
            $this->load->model('Comment_model');
        }

        //댓글, 답글 작성
        public function insert_comment(){
            $b_num = $_POST['b_num'];
            $c_content = $_POST['c_content'];
            $u_num = $this->session->userdata('u_num');
            $c_depth = $_POST['c_depth'];
            $c_parent = $_POST['c_parent'];

            if($c_depth == 0){
                $c_parent = null;
            }

            $new_comment_id = $this->Comment_model->insert_comment($b_num, $c_content, $u_num, $c_depth, $c_parent);
        
            if ($new_comment_id) {
                $this->cache->delete('comment_count_for_board_'.$b_num);

                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'message' => '댓글 등록에 실패했습니다']);
            }
        }

        //댓글, 답글 삭제
        public function delete_comment(){
            $c_num = $_POST['c_num'];
            $b_num = $this->Comment_model->get_b_num_by_c_num($c_num);
            if(!$b_num){
                echo json_encode(['success' => false, 'message' => '댓글 삭제에 실패했습니다.']);
                return ;
            }

            $delete_success = $this->Comment_model->delete_comment($c_num);

            if($delete_success){
                $this->cache->delete('comment_count_for_board_'.$b_num);

                echo json_encode(['success' => true]);
            }else{
                echo json_encode(['success' => false, 'message' => '댓글 삭제에 실패했습니다.']);
            }
        }
    }
?>