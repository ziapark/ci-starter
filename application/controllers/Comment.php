<?php
    class Comment extends CI_Controller{
        public function __construct(){
            parent::__construct();
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
                $new_comment_data = $this->Comment_model->get_comment_by_id($new_comment_id);
                echo json_encode([
                    'success' => true,
                    'new_comment' => $new_comment_data
                ]);
            } else {
                echo json_encode(['success' => false, 'message' => '댓글 등록에 실패했습니다']);
            }
        }

        //답글 보기
        public function reply_list(){
            $b_num = $_POST['b_num'];
            $p_num = $_POST['p_num'];
            $b_depth = $_POST['b_depth'];

            $reply_list = $this->Comment_model->get_reply_list($b_num, $p_num, $b_depth);
            
            if (!empty($reply_list)) {
                echo json_encode([
                    'success' => true,


                    'reply_list' => $reply_list
                ]);
            } else {
                echo json_encode(['success' => false, 'message' => '답글이 없습니다.']);
            }
        }

        //댓글 삭제
        public function delete_comment(){
            $c_num = $_POST['c_num'];
            $result = $this->Comment_model->delete_comment($c_num);

            if($result){
                echo json_encode(['success' => true]);
            }else{
                echo json_encode(['success' => false, 'message' => '댓글 삭제에 실패했습니다.']);
            }
        }
    }
?>