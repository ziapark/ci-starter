<?php
    class User extends CI_Controller{
        public function __construct()
        {
            parent::__construct();
            $this->load->model('User_model');
        }

        //화면출력
        public function view($value)
        {
            if($value === 'login'){
                $this->load->view('user_login_view');
            }else if($value === 'sign'){
                $this->load->view('user_sign_view');
            }       
        }

        //회원가입
        public function sign()
        {
            $u_id = $_POST['u_id'];
            $u_pw = $_POST['u_pw'];
            $u_name = $_POST['u_name'];

            $data['user'] = $this->User_model->sign($u_id, $u_pw, $u_name);
            $this->load->view('user_login_view');
        }

        //로그인
        public function login()
        {
            $u_id = $_POST['u_id'];
            $u_pw = $_POST['u_pw'];
            
            $data = $this->User_model->login($u_id, $u_pw);

            if($data !== false){
                $this->load->library('session');
                $this->session->set_userdata('u_num', $data->u_num);

                redirect('board/board_list');
            }else{
                redirect('user/view/login');
            }
        }

        //로그아웃
        public function logout()
        {
            $this->session->sess_destroy();
            redirect('board/board_list');
        }
    }
?>