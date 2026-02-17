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

    public function login()
    {
        if ($this->input->server('REQUEST_METHOD') === 'POST') {

            $username = trim($this->input->post('username'));
            $password = trim($this->input->post('password'));

            $user = $this->Auth_model->get_user($username);

            // Prepare log data
            $log_data = [
                'username' => $username,
                'ip_address' => $this->input->ip_address(),
                'login_time' => date('Y-m-d H:i:s'),
                'user_id' => $user ? $user->user_id : 0,
                'login_success' => 0,
                'remarks' => ''
            ];

            if($user && md5($password) === $user->password){

                // ✅ Successful login
                $log_data['login_success'] = 1;
                $log_data['remarks'] = 'Login successful';

                $this->db->insert('user_access_log', $log_data);
                $log_id = $this->db->insert_id();

                // Regenerate session for security
                $this->session->sess_regenerate(TRUE);

                $session_data = [
                    'user_id' => $user->user_id,
                    'username' => $user->username,
                    'full_name' => $user->full_name,
                    'province_id' => $user->province_id,
                    'district_id' => $user->district_id,
                    'role' => $user->role,
                    'logged_in' => TRUE,
                    'access_log_id' => $log_id
                ];

                $this->session->set_userdata($session_data);

                redirect(base_url('welcome/index'));

            } else {
                // Failed login
                $log_data['remarks'] = 'Invalid ('.$password.')';
                $this->db->insert('user_access_log', $log_data);

                $this->session->set_flashdata('error','Invalid Username or Password');
                redirect('auth');
            }

        } else {
            // GET request — just show login form
            $this->load->view('auth/login');
        }
    }

    public function logout()
    {
        $access_log_id = $this->session->userdata('access_log_id');
        
        if($access_log_id){
            $this->db->where('id', $access_log_id)
                     ->update('user_access_log', ['logout_time' => date('Y-m-d H:i:s')]);
        }

        $this->session->sess_destroy();
        redirect('auth');
    }

}
