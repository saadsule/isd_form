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
        $this->load->model('Forms_model');

        $role = $this->session->userdata('role'); // adjust key to match your session

        $data['total_forms']        = $this->Forms_model->get_total_forms();
        $data['child_health_total'] = $this->Forms_model->get_child_health_total();
        $data['opd_total']          = $this->Forms_model->get_opd_total();
        $data['today_total']        = $this->Forms_model->get_today_total();
        $data['last_7_days_total']  = $this->Forms_model->get_last_7_days_total();
        $data['this_month_total']   = $this->Forms_model->get_this_month_total();
        $data['daily_avg']          = $this->Forms_model->get_daily_avg_last_30();
        $data['top_operators']      = $this->Forms_model->get_top_operators_today(5);
        $data['last_7_days_trend']  = $this->Forms_model->get_last_7_days_trend();

        // Role-based contribution block
        if ($role == 1) {
            // Data entry user → personal stats
            $data['is_personal_view']   = true;
            $data['contrib_label']      = 'My Contribution';
            $data['my_total_forms']     = $this->Forms_model->get_my_total_forms();
            $data['my_today_total']     = $this->Forms_model->get_my_today_total();
            $data['my_last_7_days']     = $this->Forms_model->get_my_last_7_days();
        } else {
            // Manager / Admin → all-users aggregate stats
            $data['is_personal_view']   = false;
            $data['contrib_label']      = 'Overall Summary';
            $data['my_total_forms']     = $data['total_forms'];
            $data['my_today_total']     = $data['today_total'];
            $data['my_last_7_days']     = $data['last_7_days_total'];
        }

        $data['page_title']   = 'Reporting Overview';
        $data['main_content'] = $this->load->view('home', $data, TRUE);
        $this->load->view('layout/main', $data);
    }
    
}
