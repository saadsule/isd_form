<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Forms extends CI_Controller {

    public function index()
    {
        $this->load->view('home');
    }

    public function child_health()
    {
        $this->load->model('Questions_model');
        $data['questions'] = $this->Questions_model->get_all_questions_by_form_type('chf');
        $this->load->view('child_health_form', $data);
    }

    public function opd_mnch()
    {
        $this->load->model('Questions_model');
        $data['questions'] = $this->Questions_model
            ->get_all_questions_by_form_type('opd');

        $this->load->view('opd_mnch_form', $data);
    }

    
    public function save_child_health()
    {
        $this->load->database();

        // TRANSACTION (VERY IMPORTANT)
        $this->db->trans_start();

        // ---------- In Master table saved this info ----------
        $master = array(

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

            'gender' => $this->input->post('gender'),
            'marital_status' => $this->input->post('marital_status'),
            'pregnancy_status' => $this->input->post('pregnancy_status'),

            'disability' => $this->input->post('disability'),
            'play_learning_kit' => $this->input->post('play_learning_kit'),
            'nutrition_package' => $this->input->post('nutrition_package')
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
                        $batch[] = [
                            'master_id' => $master_id,
                            'question_id' => $question_id,
                            'option_id' => NULL,
                            'answer' => $answer
                        ];
                    }
                }
            }


            if(!empty($batch))
            {
                $this->db->insert_batch('child_health_detail', $batch);
            }
        }

        $this->db->trans_complete();

        redirect('forms/child_health');
    }

    public function save_opd_mnch()
    {
        $this->db->trans_start();

        // MASTER
        $master = [

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
                        $batch[] = [
                            'master_id'   => $master_id,
                            'question_id' => $question_id,
                            'option_id'   => NULL,
                            'answer'      => $answer
                        ];
                    }
                }
            }

            // SINGLE INSERT
            if(!empty($batch))
            {
                $this->db->insert_batch('opd_mnch_detail', $batch);
            }
        }

        $this->db->trans_complete();

        redirect('forms/opd_mnch');
    }

}
