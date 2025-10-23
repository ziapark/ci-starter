<?php
class User_model extends CI_Model {
    
    // 아이디 중복 검사
    public function is_id_exists($u_id) {
        $query = $this->db->get_where('user', array('u_id' => $u_id));
        
        return $query->row();
    }

    // 회원가입
    public function sign($u_id, $u_pw, $u_name) {
        $data = array(
            'u_id'   => $u_id,
            'u_pw'   => $u_pw,
            'u_name' => $u_name
        );
        
        return $this->db->insert('user', $data);
    }

    // 로그인
    public function login($u_id, $u_pw) {
        $query = $this->db->get_where('user', array('u_id' => $u_id));
        $user = $query->row();

        if ($user && $u_pw === $user->u_pw) {
            return $user;
        } else {
            return false;
        }
    }
}