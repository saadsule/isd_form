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
    
    public function map_view()
    {
        // Default district
        $district_name = 'North Waziristan';

        // Fetch facilities
        $data['facilities'] = $this->Dashboard_model->get_facilities_by_district($district_name);

        // Page title
        $data['page_title'] = "Facilities Map";

        // Load view into main layout (consistent with dashboard)
        $data['main_content'] = $this->load->view('dashboard/map_view', $data, TRUE);
        $this->load->view('layout/main', $data);
    }
    
    public function outreach()
    {
        $data['page_title'] = "Outreach Dashboard";

        // Get filter inputs
        $filters = [
            'uc'         => $this->input->get('uc'),
            'start'       => $this->input->get('start'),
            'end'         => $this->input->get('end'),
            'age_group'  => $this->input->get('age_group'),
            'gender'     => $this->input->get('gender'),
            'client_type' => $this->input->get('client_type')
        ];

        $data['filters'] = $filters;

        // Load UC list from table
        $data['ucs'] = $this->Dashboard_model->get_ucs(); // New function to fetch UC names

        // Fetch outreach graph data based on filters
        $data['graph_data'] = $this->Dashboard_model->get_outreach_graph($filters);

        $data['main_content'] = $this->load->view('dashboard/outreach_view', $data, TRUE);
        $this->load->view('layout/main', $data);
    }

}
