<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Forms extends CI_Controller {

    public function __construct()
    {
        parent::__construct();

        // Check if user is logged in
        if (!$this->session->userdata('user_id')) {
            redirect('auth/login');
        }
    }
    
    public function index()
    {
        $data['page_title'] = "Welcome";        
        $data['main_content'] = $this->load->view('home', $data, TRUE);
        $this->load->view('layout/main', $data);
    }
    
    public function child_health($id = null)
    {
        $this->load->model('Questions_model');
        $this->load->model('Location_model');
        $this->load->model('Forms_model');

        // Get all questions for this form type
        $data['questions'] = $this->Questions_model->get_all_questions_by_form_type('chf');

        // Load districts
        $data['districts'] = $this->Location_model->get_districts();

        $data['facilities'] = [];
        // ================= EDIT MODE =================
        if ($id != null) 
        {
            $record = $this->Forms_model->get_child_master($id); 

            // SECURITY CHECK
            if ($record->created_by != $this->session->userdata('user_id') &&
                $this->session->userdata('role') != 'admin') {
                show_error('Unauthorized access', 403);
            }

            // Attach detail answers to the master record
            $record->question = $this->Forms_model->get_child_details($id);

            $data['record'] = $record; // now $rec->question exists in view
            $data['is_edit'] = true;
            $data['page_title'] = "Edit Child Health Form";
            
            // Load facilities for the saved UC
            if(isset($record->uc) && $record->uc != ''){
                $data['facilities'] = $this->Location_model->get_facilities_by_uc($record->uc);
                $selected_facility = $record->facility_id; // saved facility
            }
        } 
        else 
        {
            $data['record'] = null;
            $data['is_edit'] = false;
            $data['page_title'] = "Child Health Form";
        }


        // Load the view
        $data['main_content'] = $this->load->view('child_health_form', $data, TRUE);
        $this->load->view('layout/main', $data);
    }
    
    public function save_child_health()
    {
        $this->load->database();

        // TRANSACTION (VERY IMPORTANT)
        $this->db->trans_start();

        // ---------- In Master table saved this info ----------
        $master = array(

            'visit_type' => $this->input->post('visit_type'),
            'form_date' => $this->input->post('form_date'),
            'qr_code' => $this->input->post('qr_code'),
            'client_type' => $this->input->post('client_type'),

            'district' => $this->input->post('district'),
            'uc' => $this->input->post('uc'),
            'village' => $this->input->post('village'),
            'vaccinator_name' => $this->input->post('vaccinator_name'),

            'patient_name' => $this->input->post('patient_name'),
            'guardian_name' => $this->input->post('guardian_name'),

            'dob' => $this->input->post('dob'),
            'age_year' => $this->input->post('age_year'),
            'age_month' => $this->input->post('age_month'),
            'age_day' => $this->input->post('age_day'),
            'age_group' => $this->input->post('age_group'),

            'gender' => $this->input->post('gender'),
            'marital_status' => $this->input->post('marital_status'),
            'pregnancy_status' => $this->input->post('pregnancy_status'),

            'disability' => $this->input->post('disability'),
            'play_learning_kit' => $this->input->post('play_learning_kit'),
            'nutrition_package' => $this->input->post('nutrition_package'),
            'created_by' => $this->session->userdata('user_id'),
            'facility_id' => $this->input->post('facility_id')
        );

        $this->db->insert('child_health_master', $master);

        $master_id = $this->db->insert_id();

        // ---------- In Detail table saved this info ----------
        $questions = $this->input->post('question');

        if(!empty($questions))
        {
            $batch = [];

            // LOAD OPTIONS ONCE (10x faster)
            $options = $this->db
                ->select('option_id, option_text')
                ->get('question_options')
                ->result();

            $option_map = [];

            foreach($options as $opt)
            {
                $option_map[$opt->option_id] = $opt->option_text;
            }

            foreach($questions as $question_id => $answer)
            {
                // CASE 1 — ARRAY ANSWER
                if(is_array($answer))
                {
                    foreach($answer as $key => $value)
                    {

                        // CASE A — Nested array (text fields with options)
                        if(is_array($value))
                        {
                            foreach($value as $opt_id => $text)
                            {
                                if(trim($text) != '')
                                {
                                    $batch[] = [
                                        'master_id' => $master_id,
                                        'question_id' => $question_id,
                                        'option_id' => is_numeric($opt_id) ? $opt_id : NULL,
                                        'answer' => $text
                                    ];
                                }
                            }
                        }

                        // CASE B — Checkbox / Radio OR text-without-options
                        else
                        {
                            // ✔ If exists in option table → real option
                            if(isset($option_map[$value]))
                            {
                                $batch[] = [
                                    'master_id' => $master_id,
                                    'question_id' => $question_id,
                                    'option_id' => $value,
                                    'answer' => $option_map[$value]
                                ];
                            }
                            else
                            {
                                // ✔ Plain text input
                                if(trim($value) != '')
                                {
                                    $batch[] = [
                                        'master_id' => $master_id,
                                        'question_id' => $question_id,
                                        'option_id' => NULL,
                                        'answer' => $value
                                    ];
                                }
                            }
                        }
                    }
                }

                // CASE 2 — Pure text
                else
                {
                    if(trim($answer) != '')
                    {
                        if(is_numeric($answer)) // ✔ Radio button
                        {
                            $batch[] = [
                                'master_id'  => $master_id,
                                'question_id'=> $question_id,
                                'option_id'  => (int)$answer,
                                'answer' => isset($option_map[(int)$answer]) ? $option_map[(int)$answer] : NULL
                            ];
                        }
                        else // ✔ Pure text input
                        {
                            $batch[] = [
                                'master_id'  => $master_id,
                                'question_id'=> $question_id,
                                'option_id'  => NULL,
                                'answer'     => $answer
                            ];
                        }
                    }
                }
            }

            if(!empty($batch))
            {
                $this->db->insert_batch('child_health_detail', $batch);
            }
        }

        // ---------- Log table ----------
        $log_data = [
            'form_type' => 'child_health_form',      // change for opd/mnch dynamically
            'transaction_type' => 'Insert',
            'user_id' => $this->session->userdata('user_id'),
            'master_id' => $master_id,
            'data_json' => json_encode([
                'master' => $master,
                'details' => $batch
            ])
        ];

        $this->db->insert('form_logs', $log_data);
        
        $this->db->trans_complete();

        // Check if transaction was successful
        if ($this->db->trans_status() === FALSE) {
            $this->session->set_flashdata('error', 'Form could not be saved. Please try again.');
        } else {
            $this->session->set_flashdata('success', 'Form saved successfully!');
        }

        redirect('forms/child_health');
    }
    
    public function update_child_health($master_id)
    {
        $this->load->database();

        // ✅ SECURITY CHECK: Only creator can update
        $this->db->where('master_id', $master_id);
        $this->db->where('created_by', $this->session->userdata('user_id'));
        $check = $this->db->get('child_health_master')->row();

        if (!$check) {
            show_error('Unauthorized access', 403);
        }

        // TRANSACTION
        $this->db->trans_start();

        // ---------- Update Master Table ----------
        $master = array(
            'visit_type' => $this->input->post('visit_type'),
            'form_date' => $this->input->post('form_date'),
            'qr_code' => $this->input->post('qr_code'),
            'client_type' => $this->input->post('client_type'),

            'district' => $this->input->post('district'),
            'uc' => $this->input->post('uc'),
            'village' => $this->input->post('village'),
            'vaccinator_name' => $this->input->post('vaccinator_name'),

            'patient_name' => $this->input->post('patient_name'),
            'guardian_name' => $this->input->post('guardian_name'),

            'dob' => $this->input->post('dob'),
            'age_year' => $this->input->post('age_year'),
            'age_month' => $this->input->post('age_month'),
            'age_day' => $this->input->post('age_day'),
            'age_group' => $this->input->post('age_group'),

            'gender' => $this->input->post('gender'),
            'marital_status' => $this->input->post('marital_status'),
            'pregnancy_status' => $this->input->post('pregnancy_status'),

            'disability' => $this->input->post('disability'),
            'play_learning_kit' => $this->input->post('play_learning_kit'),
            'nutrition_package' => $this->input->post('nutrition_package'),
            'created_by' => $this->session->userdata('user_id'),
            'facility_id' => $this->input->post('facility_id')
        );

        $this->db->where('master_id', $master_id);
        $this->db->update('child_health_master', $master);

        // ---------- Delete previous dynamic answers ----------
        $this->db->where('master_id', $master_id);
        $this->db->delete('child_health_detail');

        // ---------- Insert updated dynamic answers ----------
        $questions = $this->input->post('question');

        if (!empty($questions)) 
        {
            $batch = [];

            // LOAD OPTIONS ONCE
            $options = $this->db->select('option_id, option_text')->get('question_options')->result();
            $option_map = [];
            foreach ($options as $opt) {
                $option_map[$opt->option_id] = $opt->option_text;
            }

            foreach ($questions as $question_id => $answer) 
            {
                // ARRAY ANSWERS
                if (is_array($answer)) 
                {
                    foreach ($answer as $key => $value) 
                    {
                        if (is_array($value)) 
                        {
                            foreach ($value as $opt_id => $text) 
                            {
                                if (trim($text) != '') 
                                {
                                    $batch[] = [
                                        'master_id' => $master_id,
                                        'question_id' => $question_id,
                                        'option_id' => is_numeric($opt_id) ? $opt_id : NULL,
                                        'answer' => $text
                                    ];
                                }
                            }
                        } 
                        else 
                        {
                            if (isset($option_map[$value])) 
                            {
                                $batch[] = [
                                    'master_id' => $master_id,
                                    'question_id' => $question_id,
                                    'option_id' => $value,
                                    'answer' => $option_map[$value]
                                ];
                            } 
                            else 
                            {
                                if (trim($value) != '') 
                                {
                                    $batch[] = [
                                        'master_id' => $master_id,
                                        'question_id' => $question_id,
                                        'option_id' => NULL,
                                        'answer' => $value
                                    ];
                                }
                            }
                        }
                    }
                } 
                else // PURE TEXT
                {
                    if(trim($answer) != '')
                    {
                        if(is_numeric($answer)) // ✔ Radio button
                        {
                            $batch[] = [
                                'master_id'  => $master_id,
                                'question_id'=> $question_id,
                                'option_id'  => (int)$answer,
                                'answer' => isset($option_map[(int)$answer]) ? $option_map[(int)$answer] : NULL
                            ];
                        }
                        else // ✔ Pure text input
                        {
                            $batch[] = [
                                'master_id'  => $master_id,
                                'question_id'=> $question_id,
                                'option_id'  => NULL,
                                'answer'     => $answer
                            ];
                        }
                    }
                }
            }

            if (!empty($batch)) 
            {
                $this->db->insert_batch('child_health_detail', $batch);
            }
        }

        // ---------- Log table ----------
        $log_data = [
            'form_type' => 'child_health_form',
            'transaction_type' => 'Update',
            'user_id' => $this->session->userdata('user_id'),
            'master_id' => $master_id,
            'data_json' => json_encode([
                'master' => $master,
                'details' => isset($batch) ? $batch : []
            ])
        ];

        $this->db->insert('form_logs', $log_data);

        $this->db->trans_complete();

        // Flash message
        if ($this->db->trans_status() === FALSE) {
            $this->session->set_flashdata('error', 'Form could not be updated. Please try again.');
        } else {
            $this->session->set_flashdata('success', 'Form updated successfully!');
        }

        redirect('forms/child_health');
    }

    public function opd_mnch($id = null)
    {
        $this->load->model('Questions_model');
        $this->load->model('Location_model');
        $this->load->model('Forms_model');
        
        $data['questions'] = $this->Questions_model->get_all_questions_by_form_type('opd');
        
        // Load districts
        $data['districts'] = $this->Location_model->get_districts();

        $data['facilities'] = [];
        // ✅ EDIT MODE
        if($id != null)
        {
            $record = $this->Forms_model->get_opd_master($id);

            // SECURITY CHECK
            if(
                $record->created_by != $this->session->userdata('user_id')
                && $this->session->userdata('role') != 'admin'
            ){
                show_error('Unauthorized access',403);
            }

            $data['record'] = $record;
            $data['details'] = $this->Forms_model->get_opd_details($id);

            $data['page_title'] = "Edit OPD MNCH Form";
            $data['is_edit'] = true;
            
            // Load facilities for the saved UC
            if(isset($record->uc) && $record->uc != ''){
                $data['facilities'] = $this->Location_model->get_facilities_by_uc($record->uc);
                $selected_facility = $record->facility_id; // saved facility
            }
        }
        else
        {
            $data['is_edit'] = false;
            $data['page_title'] = "OPD MNCH Form";
        }
        
        $data['page_title'] = "OPD MNCH Form";        
        $data['main_content'] = $this->load->view('opd_mnch_form', $data, TRUE);
        $this->load->view('layout/main', $data);
    }

    public function save_opd_mnch()
    {
        $this->db->trans_start();

        // MASTER
        $master = [

            'visit_type' => $this->input->post('visit_type'),
            'form_date' => $this->input->post('form_date'),
            'anc_card_no' => $this->input->post('anc_card_no'),
            'client_type' => $this->input->post('client_type'),

            'district' => $this->input->post('district'),
            'uc' => $this->input->post('uc'),
            'village' => $this->input->post('village'),
            'lhv_name' => $this->input->post('lhv_name'),

            'patient_name' => $this->input->post('patient_name'),
            'guardian_name' => $this->input->post('guardian_name'),

            'disability' => $this->input->post('disability'),
            'age_group' => $this->input->post('age_group'),
            'marital_status' => $this->input->post('marital_status'),
            'pregnancy_status' => $this->input->post('pregnancy_status'),
            'created_by' => $this->session->userdata('user_id'),
            'facility_id' => $this->input->post('facility_id'),
            'qr_code' => $this->input->post('qr_code'),
                
            'notes' => $this->input->post('notes')
        ];

        $this->db->insert('opd_mnch_master', $master);

        $master_id = $this->db->insert_id();

        // DYNAMIC QUESTIONS
        $questions = $this->input->post('question');

        if(!empty($questions))
        {
            $batch = [];

            // LOAD ALL OPTIONS ONCE (VERY FAST)
            $options = $this->db
                ->select('option_id, option_text')
                ->get('question_options')
                ->result();

            $option_map = [];

            foreach($options as $opt)
            {
                $option_map[$opt->option_id] = $opt->option_text;
            }


            foreach($questions as $question_id => $answer)
            {

                // CASE 1 — ARRAY
                if(is_array($answer))
                {
                    foreach($answer as $key => $value)
                    {

                        // CASE A — Nested array (text fields with options)
                        if(is_array($value))
                        {
                            foreach($value as $opt_id => $text)
                            {
                                if(trim($text) != '')
                                {
                                    $batch[] = [
                                        'master_id'   => $master_id,
                                        'question_id' => $question_id,
                                        'option_id'   => is_numeric($opt_id) ? $opt_id : NULL,
                                        'answer'      => $text
                                    ];
                                }
                            }
                        }

                        // CASE B — Checkbox / Radio OR plain text
                        else
                        {
                            // ✔ If option exists
                            if(isset($option_map[$value]))
                            {
                                $batch[] = [
                                    'master_id'   => $master_id,
                                    'question_id' => $question_id,
                                    'option_id'   => $value,
                                    'answer'      => $option_map[$value]
                                ];
                            }
                            else
                            {
                                // ✔ Text without options
                                if(trim($value) != '')
                                {
                                    $batch[] = [
                                        'master_id'   => $master_id,
                                        'question_id' => $question_id,
                                        'option_id'   => NULL,
                                        'answer'      => $value
                                    ];
                                }
                            }
                        }
                    }
                }

                // CASE 2 — PURE TEXT
                else
                {
                    if(trim($answer) != '')
                    {
                        if(is_numeric($answer)) // ✔ Radio button
                        {
                            $batch[] = [
                                'master_id'  => $master_id,
                                'question_id'=> $question_id,
                                'option_id'  => (int)$answer,
                                'answer' => isset($option_map[(int)$answer]) ? $option_map[(int)$answer] : NULL
                            ];
                        }
                        else // ✔ Pure text input
                        {
                            $batch[] = [
                                'master_id'  => $master_id,
                                'question_id'=> $question_id,
                                'option_id'  => NULL,
                                'answer'     => $answer
                            ];
                        }
                    }
                }
            }

            // SINGLE INSERT
            if(!empty($batch))
            {
                $this->db->insert_batch('opd_mnch_detail', $batch);
            }
        }

        // ---------- Log table ----------
        $log_data = [
            'form_type' => 'opd_mnch_form',      // change for opd/mnch dynamically
            'transaction_type' => 'Insert',
            'user_id' => $this->session->userdata('user_id'),
            'master_id' => $master_id,
            'data_json' => json_encode([
                'master' => $master,
                'details' => $batch
            ])
        ];

        $this->db->insert('form_logs', $log_data);
        
        $this->db->trans_complete();

        // Check if transaction was successful
        if ($this->db->trans_status() === FALSE) {
            $this->session->set_flashdata('error', 'Form could not be saved. Please try again.');
        } else {
            $this->session->set_flashdata('success', 'Form saved successfully!');
        }

        redirect('forms/opd_mnch');
    }
    
    public function update_opd_mnch($master_id)
    {
        $this->db->trans_start();

        // ---------- MASTER UPDATE ----------
        $master = [

            'visit_type' => $this->input->post('visit_type'),
            'form_date' => $this->input->post('form_date'),
            'anc_card_no' => $this->input->post('anc_card_no'),
            'client_type' => $this->input->post('client_type'),

            'district' => $this->input->post('district'),
            'uc' => $this->input->post('uc'),
            'village' => $this->input->post('village'),
            'lhv_name' => $this->input->post('lhv_name'),

            'patient_name' => $this->input->post('patient_name'),
            'guardian_name' => $this->input->post('guardian_name'),

            'disability' => $this->input->post('disability'),
            'age_group' => $this->input->post('age_group'),
            'marital_status' => $this->input->post('marital_status'),
            'pregnancy_status' => $this->input->post('pregnancy_status'),
            'created_by' => $this->session->userdata('user_id'),
            'facility_id' => $this->input->post('facility_id'),
            'qr_code' => $this->input->post('qr_code'),

            'notes' => $this->input->post('notes')
        ];

        $this->db->where('id', $master_id);
        $this->db->where('created_by', $this->session->userdata('user_id'));
        $check = $this->db->get('opd_mnch_master')->row();

        if(!$check){
            show_error('Unauthorized access');
        }

        $this->db->where('id', $master_id);
        $this->db->update('opd_mnch_master', $master);


        // ---------- DELETE OLD DETAILS ----------
        $this->db->where('master_id', $master_id);
        $this->db->delete('opd_mnch_detail');


        // ---------- INSERT UPDATED DETAILS ----------
        $questions = $this->input->post('question');

        $batch = [];

        if(!empty($questions))
        {
            // Load options once
            $options = $this->db
                ->select('option_id, option_text')
                ->get('question_options')
                ->result();

            $option_map = [];

            foreach($options as $opt)
            {
                $option_map[$opt->option_id] = $opt->option_text;
            }

            foreach($questions as $question_id => $answer)
            {

                // CASE 1 — ARRAY
                if(is_array($answer))
                {
                    foreach($answer as $key => $value)
                    {

                        // Nested array
                        if(is_array($value))
                        {
                            foreach($value as $opt_id => $text)
                            {
                                if(trim($text) != '')
                                {
                                    $batch[] = [
                                        'master_id'   => $master_id,
                                        'question_id' => $question_id,
                                        'option_id'   => is_numeric($opt_id) ? $opt_id : NULL,
                                        'answer'      => $text
                                    ];
                                }
                            }
                        }

                        // Checkbox / Radio / Text
                        else
                        {
                            if(isset($option_map[$value]))
                            {
                                $batch[] = [
                                    'master_id'   => $master_id,
                                    'question_id' => $question_id,
                                    'option_id'   => $value,
                                    'answer'      => $option_map[$value]
                                ];
                            }
                            else
                            {
                                if(trim($value) != '')
                                {
                                    $batch[] = [
                                        'master_id'   => $master_id,
                                        'question_id' => $question_id,
                                        'option_id'   => NULL,
                                        'answer'      => $value
                                    ];
                                }
                            }
                        }
                    }
                }

                // PURE TEXT
                else
                {
                    if(trim($answer) != '')
                    {
                        if(is_numeric($answer)) // ✔ Radio button
                        {
                            $batch[] = [
                                'master_id'  => $master_id,
                                'question_id'=> $question_id,
                                'option_id'  => (int)$answer,
                                'answer' => isset($option_map[(int)$answer]) ? $option_map[(int)$answer] : NULL
                            ];
                        }
                        else // ✔ Pure text input
                        {
                            $batch[] = [
                                'master_id'  => $master_id,
                                'question_id'=> $question_id,
                                'option_id'  => NULL,
                                'answer'     => $answer
                            ];
                        }
                    }
                }
            }

            if(!empty($batch))
            {
                $this->db->insert_batch('opd_mnch_detail', $batch);
            }
        }


        // ---------- LOG UPDATE ----------
        $log_data = [
            'form_type' => 'opd_mnch_form',
            'transaction_type' => 'Update',
            'user_id' => $this->session->userdata('user_id'),
            'master_id' => $master_id,
            'data_json' => json_encode([
                'master' => $master,
                'details' => $batch
            ])
        ];

        $this->db->insert('form_logs', $log_data);


        // ---------- COMPLETE TRANSACTION ----------
        $this->db->trans_complete();


        if ($this->db->trans_status() === FALSE)
        {
            $this->session->set_flashdata('error', 'Form could not be updated. Please try again.');
        }
        else
        {
            $this->session->set_flashdata('success', 'Form updated successfully!');
        }

        redirect('forms/opd_report');
    }
    
    public function child_health_report()
    {
        $this->load->model('Forms_model');
        $this->load->model('Location_model');

        $filters = array(
            'from_date'   => $this->input->get('from_date'),
            'to_date'     => $this->input->get('to_date'),
            'uc_id'       => $this->input->get('uc_id'),
            'facility_id' => $this->input->get('facility_id'),
            'search'      => $this->input->get('search'),
            'rejected'    => $this->input->get('rejected')
        );

        // If role 1 and rejected filter is clicked
        if($this->session->userdata('role') == 1 && $filters['rejected'] == 1){
            $filters['created_by'] = $this->session->userdata('user_id');
            $filters['verification_status'] = 'Reported';
        }
        
        $data['filters'] = $filters;

        // get filtered records
        $data['records'] = $this->Forms_model->get_child_records($filters);

        // dropdowns
        $data['ucs'] = $this->Location_model->get_all_uc();
        $data['facilities'] = $this->Location_model->get_all_facilities($filters['uc_id']);

        $data['page_title'] = "Child Health Report";
        $data['main_content'] = $this->load->view('reports/child_health_report', $data, TRUE);
        $this->load->view('layout/main', $data);
    }

    public function view_child_health($id)
    {
        $this->load->model('Forms_model');

        $data['page_title'] = "View Child Health";

        $data['form'] = $this->Forms_model->get_child_master($id);

        $questions = $this->Forms_model
            ->get_child_questions_with_answers($id);

        /**
         BUILD STRUCTURE:
         section -> question -> options
         */
        $structured = [];

        foreach($questions as $q){

            $qid = $q->question_id;

            if(!isset($structured[$q->q_section][$qid])){
                $structured[$q->q_section][$qid] = $q;
                $structured[$q->q_section][$qid]->options = [];
            }

            if($q->option_id){
                $structured[$q->q_section][$qid]
                    ->options[] = $q;
            }
        }

        $data['questions'] = $structured;
        $data['readonly'] = true;

        $data['main_content'] =
            $this->load->view('view_child_health',$data,TRUE);

        $this->load->view('layout/main',$data);
    }
    
    public function opd_report()
    {
        $this->load->model('Forms_model');
        $this->load->model('Location_model');

        $filters = array(
            'from_date'  => $this->input->get('from_date'),
            'to_date'    => $this->input->get('to_date'),
            'uc_id'      => $this->input->get('uc_id'),
            'facility_id'=> $this->input->get('facility_id'),
            'search'     => $this->input->get('search'),
            'rejected'    => $this->input->get('rejected')
        );
        
        // If role 1 and rejected filter is clicked
        if($this->session->userdata('role') == 1 && $filters['rejected'] == 1){
            $filters['created_by'] = $this->session->userdata('user_id');
            $filters['verification_status'] = 'Reported';
        }

        $data['filters'] = $filters;

        $data['records'] = $this->Forms_model->get_opd_records($filters);

        // Load dropdown data
        $data['ucs'] = $this->Location_model->get_all_uc();
        $data['facilities'] = $this->Location_model->get_all_facilities($filters['uc_id']);

        $data['main_content'] =
            $this->load->view('reports/opd_report',$data,TRUE);

        $this->load->view('layout/main',$data);
    }

    public function view_opd_mnch($id)
    {
        $this->load->model('Forms_model');

        $result = $this->Forms_model->get_opd_detail($id);

        $data['master'] = $result['master'];
        $data['questions'] = $result['questions']; // already structured
        $data['readonly'] = true;
        $data['page_title'] = "View OPD MNCH";

        $data['main_content'] = $this->load->view('view_opd_mnch',$data,TRUE);
        $this->load->view('layout/main',$data);
    }

    public function get_uc_by_district()
    {
        $district_id = $this->input->post('district_id');

        $this->load->model('Location_model');
        $ucs = $this->Location_model->get_uc_by_district($district_id);

        echo json_encode($ucs);
    }
    
    public function get_facilities_by_uc()
    {
        $this->load->model('Location_model');
        
        $uc_id = $this->input->post('uc_id');
        $facilities = $this->Location_model->get_facilities_by_uc($uc_id);
        echo json_encode($facilities);
    }
    
    public function verify($id)
    {
        if($this->session->userdata('role') != 2){
            show_error('Unauthorized Access');
        }

        $form = $this->db->get_where('child_health_master', ['master_id' => $id])->row();

        if(!$form){
            show_404();
        }

        // Only block if already verified
        $status = isset($form->verification_status) ? $form->verification_status : 'Pending';
        if($status == 'Verified'){
            show_error('Form already verified.');
        }

        $data = [
            'verification_status' => 'Verified',
            'verified_by' => $this->session->userdata('user_id'),
            'verified_at' => date('Y-m-d H:i:s')
        ];

        $this->db->where('master_id', $id);
        $this->db->update('child_health_master', $data);

        redirect('forms/view_child_health/'.$id);
    }
    
    public function report($id)
    {
        if($this->session->userdata('role') != 2){
            show_error('Unauthorized Access');
        }

        $form = $this->db->get_where('child_health_master', ['master_id' => $id])->row();

        if(!$form){
            show_404();
        }

        $status = isset($form->verification_status) ? $form->verification_status : 'Pending';

        // Only block reporting if already verified
        if($status == 'Verified'){
            show_error('Verified form cannot be reported.');
        }

        $data = [
            'verification_status' => 'Reported',
            'verified_by' => $this->session->userdata('user_id'),
            'verified_at' => date('Y-m-d H:i:s'),
            'report_reason' => $this->input->post('report_reason')
        ];

        $this->db->where('master_id', $id);
        $this->db->update('child_health_master', $data);

        redirect('forms/view_child_health/'.$id);
    }
    
    public function verify_opd_mnch($id)
    {
        if($this->session->userdata('role') != 2){
            show_error('Unauthorized Access');
        }

        // Prevent double processing
        $form = $this->db->get_where('opd_mnch_master', ['id' => $id])->row();

        if(!$form){
            show_404();
        }

        // Only block if already verified
        if($form->verification_status == 'Verified'){
            show_error('Form already verified.');
        }

        $data = [
            'verification_status' => 'Verified',
            'verified_by' => $this->session->userdata('user_id'),
            'verified_at' => date('Y-m-d H:i:s')
        ];

        $this->db->where('id', $id);
        $this->db->update('opd_mnch_master', $data);

        redirect('forms/view_opd_mnch/'.$id);
    }
    
    public function report_opd_mnch($id)
    {
        if($this->session->userdata('role') != 2){
            show_error('Unauthorized Access');
        }

        $form = $this->db->get_where('opd_mnch_master', ['id' => $id])->row();

        if(!$form){
            show_404();
        }

        // Only block if already verified
        $status = isset($form->verification_status) ? $form->verification_status : 'Pending';
        if($status == 'Verified'){
            show_error('Verified form cannot be reported.');
        }

        $data = [
            'verification_status' => 'Reported',
            'verified_by' => $this->session->userdata('user_id'),
            'verified_at' => date('Y-m-d H:i:s'),
            'report_reason' => $this->input->post('report_reason')
        ];

        $this->db->where('id', $id);
        $this->db->update('opd_mnch_master', $data);

        redirect('forms/view_opd_mnch/'.$id);
    }

}
