<?php
class Auth_model extends CI_Model {

    public function get_user($username){

        return $this->db
                    ->where('username', $username)
                    ->where('status', 1)
                    ->limit(1)
                    ->get('users')
                    ->row();
    }

}
