<?php
    class User_model extends CI_Model{
        
        //아이디 중복 검사
        public function is_id_exists($u_id){
            $query = $this->db->query('
                select * from user where u_id = ?
            ', [$u_id]);

            $row = $query->row();

            return $row;
        }

        //회원가입
        public function sign($u_id, $u_pw, $u_name){
            $query = "insert into user(u_id, u_pw, u_name) values (?, ?, ?)";
            $this->db->query($query, array($u_id, $u_pw, $u_name));

            return ;
        }

        //로그인
        public function login($u_id, $u_pw){
            $sql = "select * from user where u_id=?";
            $query = $this->db->query($sql, array($u_id));
            $user = $query->row();

            if ($user && $u_pw === $user->u_pw) {
                return $user;
            } else {
                return false;
            }        
        }
    }   