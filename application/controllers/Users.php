<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends CI_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->model('Users_model');
        $this->load->library('session');
        $this->load->helper('url');

        // Check if user is logged in
        if(!$this->session->userdata('logged_in')){
            redirect('auth');
        }
    }

    // List all users
    public function index(){
        $data['users'] = $this->Users_model->get_all_users();
        $data['main_content'] = $this->load->view('users/list', $data, TRUE);
        $this->load->view('layout/main', $data);
    }

    // Add new user
    public function add(){
        $current_role = $this->session->userdata('role');

        if(!in_array($current_role, [3,4])){
            $this->session->set_flashdata('error','You are not allowed to add users.');
            redirect('users');
        }

        if($this->input->server('REQUEST_METHOD') === 'POST'){
            $post = $this->input->post();

            $data = [
                'full_name'   => trim($post['full_name']),
                'username'    => trim($post['username']),
                'password'    => md5(trim($post['password'])), // Encrypt password
                'province_id' => 3,
                'district_id' => 94,
                'role'        => 1, // default role
                'status'      => 1,
                'created_at'  => date('Y-m-d H:i:s')
            ];

            $this->Users_model->insert_user($data);

            $this->session->set_flashdata('success','User added successfully.');
            redirect('users');
        }

        // Load provinces/districts for select dropdown
        $data['provinces'] = $this->db->get('provinces')->result();
        $data['districts'] = $this->db->get('districts')->result();

        $data['main_content'] = $this->load->view('users/add', $data, TRUE);
        $this->load->view('layout/main', $data);
    }

    // Edit user
    public function edit($id){
        $user = $this->Users_model->get_user_by_id($id);

        if(!$user){
            $this->session->set_flashdata('error','User not found.');
            redirect('users');
        }

        // Access control: only allow editing role 1 users
        if($user->role != 1){
            $this->session->set_flashdata('error','You are not allowed to edit this user.');
            redirect('users');
        }

        if($this->input->server('REQUEST_METHOD') === 'POST'){
            $post = $this->input->post();

            $data = [
                'full_name'   => trim($post['full_name']),
                'username'    => trim($post['username']),
                'province_id' => 3,
                'district_id' => 94,
                'status'      => $post['status']
            ];

            if(!empty($post['password'])){
                $data['password'] = md5(trim($post['password']));
            }

            $this->Users_model->update_user($id, $data);

            $this->session->set_flashdata('success','User updated successfully.');
            redirect('users');
        }

        $data['user'] = $user;
        $data['provinces'] = $this->db->get('provinces')->result();
        $data['districts'] = $this->db->get('districts')->result();

        $data['main_content'] = $this->load->view('users/edit', $data, TRUE);
        $this->load->view('layout/main', $data);
    }

    // Delete user
    public function delete($id){
        $current_role = $this->session->userdata('role');
        if(!in_array($current_role, [3,4])){
            $this->session->set_flashdata('error','You are not allowed to delete users.');
            redirect('users');
        }

        $user = $this->Users_model->get_user_by_id($id);
        if(!$user || $user->role != 1){
            $this->session->set_flashdata('error','You are not allowed to delete this user.');
            redirect('users');
        }

        $this->Users_model->delete_user($id);
        $this->session->set_flashdata('success','User deleted successfully.');
        redirect('users');
    }

}