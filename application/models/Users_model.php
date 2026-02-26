<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users_model extends CI_Model {

    public function get_all_users(){
        return $this->db->where_in('role', [1, 4])->get('users')->result();
    }

    public function get_user_by_id($id){
        return $this->db->where('user_id', $id)->get('users')->row();
    }

    public function insert_user($data){
        return $this->db->insert('users', $data);
    }

    public function update_user($id, $data){
        return $this->db->where('user_id', $id)->update('users', $data);
    }

    public function delete_user($id){
        return $this->db->where('user_id', $id)->delete('users');
    }

}