<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Dashboard_model');
        
        if (!$this->session->userdata('user_id')) {
            redirect('auth/login');
        }
    }

    public function index()
    {
        $data['child_total'] = $this->Dashboard_model->child_total();
        $data['opd_total']   = $this->Dashboard_model->opd_total();
        $data['patients']    = $this->Dashboard_model->total_patients();
        $data['gender']      = $this->Dashboard_model->gender_combined();

        $data['monthly']     = $this->Dashboard_model->monthly_comparison();
        $data['district']    = $this->Dashboard_model->district_comparison();
        $data['client']      = $this->Dashboard_model->client_type_combined();
        $data['diagnosis']   = $this->Dashboard_model->top_diagnosis();

        $data['page_title'] = "Dashboard";        
        $data['main_content'] = $this->load->view('dashboard', $data, TRUE);
        $this->load->view('layout/main', $data);
    }
}
