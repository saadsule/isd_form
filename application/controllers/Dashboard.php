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
        
        // Fetch totals
        $data['summary'] = $this->Dashboard_model->get_facility_summary_by_district($district_name);

        // Page title
        $data['page_title'] = "Facilities Map";

        // Load view into main layout (consistent with dashboard)
        $data['main_content'] = $this->load->view('dashboard/map_view', $data, TRUE);
        $this->load->view('layout/main', $data);
    }
    
    public function outreach()
    {
        $data['page_title'] = "Outreach Dashboard";

        $filters = [
            'uc'          => $this->input->get('uc'),
            'start'       => $this->input->get('start'),
            'end'         => $this->input->get('end'),
            'age_group'   => $this->input->get('age_group'),
            'gender'      => $this->input->get('gender'),
            'client_type' => $this->input->get('client_type'),
            'vaccination_history' => $this->input->get('vaccination_history'),
            'antigens' => $this->input->get('antigens'),
            'antigens_1_2_years' => $this->input->get('antigens_1_2_years'),
            'antigens_2_5_years' => $this->input->get('antigens_2_5_years'),
        ];

        $data['filters'] = $filters;

        $data['ucs'] = $this->Dashboard_model->get_ucs();

        // ✅ Check if ANY filter is selected
        $isFilterApplied = false;

        foreach ($filters as $value) {
            if (!empty($value)) {
                $isFilterApplied = true;
                break;
            }
        }

        $data['isFilterApplied'] = $isFilterApplied;
        
        // Check individual plot selections
        $data['showPlot1'] = !empty($filters['client_type']);
        $data['showPlot2'] = !empty($filters['vaccination_history']);
        $data['showPlot3'] = !empty($filters['antigens']);
        $data['showPlot4'] = !empty($filters['antigens_1_2_years']);
        $data['showPlot5'] = !empty($filters['antigens_2_5_years']);

        if ($data['showPlot1']) {
            $data['graph_data'] = $this->Dashboard_model->get_outreach_graph($filters);
        } else {
            $data['graph_data'] = [];
        }

        if ($data['showPlot2']) {
            $data['plot2_data'] = $this->Dashboard_model->get_vaccination_history_graph($filters);
        } else {
            $data['plot2_data'] = [];
        }

        if ($data['showPlot3']) {
            $data['plot3_data'] = $this->Dashboard_model->get_antigen_under1_graph($filters);
        } else {
            $data['plot3_data'] = [];
        }

        if ($data['showPlot4']) {
            $data['plot4_data'] = $this->Dashboard_model->get_antigen_1_2_graph($filters);
        } else {
            $data['plot4_data'] = [];
        }

        if ($data['showPlot5']) {
            $data['plot5_data'] = $this->Dashboard_model->get_antigen_2_5_graph($filters);
        } else {
            $data['plot5_data'] = [];
        }

        $data['main_content'] = $this->load->view('dashboard/outreach_view', $data, TRUE);
        $this->load->view('layout/main', $data);
    }
    
    public function fixedsite()
    {
        $data['page_title'] = "Fixed Site Dashboard";

        $filters = [
            'uc'          => $this->input->get('uc'),
            'start'       => $this->input->get('start'),
            'end'         => $this->input->get('end'),
            'age_group'   => $this->input->get('age_group'),
            'gender'      => $this->input->get('gender'),
            'client_type' => $this->input->get('client_type'),
            'vaccination_history' => $this->input->get('vaccination_history'),
            'antigens' => $this->input->get('antigens'),
            'antigens_1_2_years' => $this->input->get('antigens_1_2_years'),
            'antigens_2_5_years' => $this->input->get('antigens_2_5_years'),
        ];

        $data['filters'] = $filters;

        $data['ucs'] = $this->Dashboard_model->get_ucs();

        // ✅ Check if ANY filter is selected
        $isFilterApplied = false;

        foreach ($filters as $value) {
            if (!empty($value)) {
                $isFilterApplied = true;
                break;
            }
        }

        $data['isFilterApplied'] = $isFilterApplied;
        
        // Check individual plot selections
        $data['showPlot1'] = !empty($filters['client_type']);
        $data['showPlot2'] = !empty($filters['vaccination_history']);
        $data['showPlot3'] = !empty($filters['antigens']);
        $data['showPlot4'] = !empty($filters['antigens_1_2_years']);
        $data['showPlot5'] = !empty($filters['antigens_2_5_years']);

        if ($data['showPlot1']) {
            $data['graph_data'] = $this->Dashboard_model->get_fixedsite_graph($filters);
        } else {
            $data['graph_data'] = [];
        }

        if ($data['showPlot2']) {
            $data['plot2_data'] = $this->Dashboard_model->get_vaccination_history_graph_fixedsite($filters);
        } else {
            $data['plot2_data'] = [];
        }

        if ($data['showPlot3']) {
            $data['plot3_data'] = $this->Dashboard_model->get_antigen_under1_graph_fixedsite($filters);
        } else {
            $data['plot3_data'] = [];
        }

        if ($data['showPlot4']) {
            $data['plot4_data'] = $this->Dashboard_model->get_antigen_1_2_graph_fixedsite($filters);
        } else {
            $data['plot4_data'] = [];
        }

        if ($data['showPlot5']) {
            $data['plot5_data'] = $this->Dashboard_model->get_antigen_2_5_graph_fixedsite($filters);
        } else {
            $data['plot5_data'] = [];
        }

        $data['main_content'] = $this->load->view('dashboard/fixedsite_view', $data, TRUE);
        $this->load->view('layout/main', $data);
    }
    
    public function child_health()
    {
        $data['page_title'] = "Child Health Dashboard";

        // Get filters from GET
        $filters = [
            'uc'        => $this->input->get('uc'),
            'start'     => $this->input->get('start'),
            'end'       => $this->input->get('end'),
            'visit_type'=> $this->input->get('visit_type'),
        ];
        $data['filters'] = $filters;

        // Load UCs for filter dropdown
        $data['ucs'] = $this->Dashboard_model->get_ucs();

        // Load the main content view
        $data['main_content'] = $this->load->view('dashboard/child_health', $data, TRUE);
        $this->load->view('layout/main', $data);
    }
    
    public function get_child_health_ajax()
    {
        $filters = $this->input->post();

        // cards
        $summary = $this->Dashboard_model->get_summary_cards($filters);

        // Pie chart: Visit Type
        $visit_counts = $this->Dashboard_model->get_visit_type_counts($filters);

        // Pie chart: Client Type
        $client_counts = $this->Dashboard_model->get_client_type_counts($filters);
        
        $gender_counts  = $this->Dashboard_model->get_gender_counts($filters);
        
        $age_group_counts = $this->Dashboard_model->get_age_group_counts($filters);
        
        $q171_counts = $this->Dashboard_model->get_q171_counts($filters);
        
        $sunburstData = $this->Dashboard_model->get_sunburst_q17($filters);
        
        $heatmap = $this->Dashboard_model->get_antigen_heatmap($filters);
        
        $q21_counts = $this->Dashboard_model->get_q21_counts($filters);
        
        $q22_counts = $this->Dashboard_model->get_q22_counts($filters);
        
        $q23_counts = $this->Dashboard_model->get_q23_counts($filters);
        
        $q24_counts = $this->Dashboard_model->get_q24_counts($filters);
        
        $gender_age = $this->Dashboard_model->get_gender_age_data($filters);
        
        $flameData = $this->Dashboard_model->get_flame_q25($filters);

        echo json_encode([
            'catchment_population' => $summary['catchment_population'],
            'total_ucs'            => $summary['total_ucs'],
            'date_range'           => $summary['date_range'],
            'outreach'             => $visit_counts['Outreach'],
            'fixed'                => $visit_counts['Fixed'],
            'new_client'           => $client_counts['New'],
            'followup_client'      => $client_counts['Followup'],
            'gender'               => $gender_counts,
            'age_group'            => $age_group_counts,
            'q171'                 => $q171_counts,
            //  sunburst data
            'sunburst'             => $sunburstData['sunburst'],
            'yes_count'            => $sunburstData['yes_count'],
            'no_count'             => $sunburstData['no_count'],
            
            'heatmap'              => $heatmap,
            'q21'                  => $q21_counts,
            'q22'                  => $q22_counts,
            'q23'                  => $q23_counts,
            'q24'                  => $q24_counts,
            'gender_age'           => $gender_age,
            'flame_q25'            => $flameData,
        ]);
    }

}
