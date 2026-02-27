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
    public function user_list(){
        $data['users'] = $this->Users_model->get_all_users();
        $data['main_content'] = $this->load->view('users/list', $data, TRUE);
        $this->load->view('layout/main', $data);
    }

    // Add new user
    public function add(){
        $current_role = $this->session->userdata('role');

        if($current_role != 3){
            $this->session->set_flashdata('error','You are not allowed to add users.');
            redirect('users');
        }

        if($this->input->server('REQUEST_METHOD') === 'POST'){
            $post = $this->input->post();
            $username = trim($post['username']);

            //  Check username
            if($this->Users_model->username_exists($username)){
                $this->session->set_flashdata('error','Username already exists. Please choose another.');
                redirect('users/add');
            }
            
            $data = [
                'full_name'   => trim($post['full_name']),
                'username'    => trim($post['username']),
                'password'    => md5(trim($post['password'])), // Encrypt password
                'province_id' => 3,
                'district_id' => 94,
                'role'        => $post['role'], // select from dropdown (1 or 4)
                'status'      => $post['status'],
                'created_at'  => date('Y-m-d H:i:s')
            ];

            $this->Users_model->insert_user($data);

            $this->session->set_flashdata('success','User added successfully.');
            redirect('users');
        }

        // Role options
        $data['roles'] = [
            1 => 'Data Entry',
            4 => 'Monitor Data'
        ];

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

        // Only allow editing role 1 or 4 users
        if(!in_array($user->role, [1,4])){
            $this->session->set_flashdata('error','You are not allowed to edit this user.');
            redirect('users');
        }

        if($this->input->server('REQUEST_METHOD') === 'POST'){
            $post = $this->input->post();
            $username = trim($post['username']);

            // Check username (exclude current user id)
            if($this->Users_model->username_exists($username, $id)){
                $this->session->set_flashdata('error','Username already exists. Please choose another.');
                redirect('users/edit/'.$id);
            }

            $data = [
                'full_name'   => trim($post['full_name']),
                'username'    => trim($post['username']),
                'province_id' => 3,
                'district_id' => 94,
                'status'      => $post['status'],
                'role'        => $post['role'] // updated role from dropdown
            ];

            if(!empty($post['password'])){
                $data['password'] = md5(trim($post['password']));
            }

            $this->Users_model->update_user($id, $data);

            $this->session->set_flashdata('success','User updated successfully.');
            redirect('users');
        }

        $data['user'] = $user;
        $data['roles'] = [
            1 => 'Data Entry',
            4 => 'Monitor Data'
        ];

        $data['main_content'] = $this->load->view('users/edit', $data, TRUE);
        $this->load->view('layout/main', $data);
    }

    // Delete user
    public function delete($id){
        $current_role = $this->session->userdata('role');

        // Only role 3 can delete users
        if($current_role != 3){
            $this->session->set_flashdata('error','You are not allowed to delete users.');
            redirect('users');
        }

        $user = $this->Users_model->get_user_by_id($id);

        // Only allow deleting users with role 1 or 4
        if(!$user || !in_array($user->role, [1,4])){
            $this->session->set_flashdata('error','You are not allowed to delete this user.');
            redirect('users');
        }

        $this->Users_model->delete_user($id);
        $this->session->set_flashdata('success','User deleted successfully.');
        redirect('users');
    }

}