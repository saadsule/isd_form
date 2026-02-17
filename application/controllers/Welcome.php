<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

    public function __construct()
    {
        parent::__construct();

        // Check if user is logged in
        if (!$this->session->userdata('user_id')) {
            redirect('auth/login');
        }
    }
    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     * 		http://example.com/index.php/welcome
     *	- or -
     * 		http://example.com/index.php/welcome/index
     *	- or -
     * Since this controller is set as the default controller in
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/welcome/<method_name>
     * @see https://codeigniter.com/userguide3/general/urls.html
     */
    public function index()
    {
        // Load model if needed
        $this->load->model('Forms_model');

        // Totals
        $data['total_forms'] = $this->Forms_model->get_total_forms();
        $data['child_health_total'] = $this->Forms_model->get_child_health_total();
        $data['opd_total'] = $this->Forms_model->get_opd_total();
        $data['today_total'] = $this->Forms_model->get_today_total();

        $data['page_title'] = "Welcome";        
        $data['main_content'] = $this->load->view('home', $data, TRUE);
        $this->load->view('layout/main', $data);
    }
}
