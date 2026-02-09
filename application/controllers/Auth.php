<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->model('Auth_model');
        $this->load->library('session');
        $this->load->helper('url');
    }

    public function index(){
        $this->load->view('auth/login');
    }

    public function login(){

        $username = trim($this->input->post('username'));
        $password = trim($this->input->post('password'));

        $user = $this->Auth_model->get_user($username);

        if($user && md5($password) === $user->password){

            // regenerate session (security)
            $this->session->sess_regenerate(TRUE);

            $session_data = [
                'user_id' => $user->user_id,
                'username' => $user->username,
                'province_id' => $user->province_id,
                'district_id' => $user->district_id,
                'role' => $user->role,
                'logged_in' => TRUE
            ];

            $this->session->set_userdata($session_data);

            redirect(base_url());

        }else{

            $this->session->set_flashdata('error','Invalid Username or Password');
            redirect('auth');

        }
    }

    public function logout(){

        $this->session->sess_destroy();
        redirect('auth');

    }
}
