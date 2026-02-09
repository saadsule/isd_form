<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Questions extends CI_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->model('Questions_model');
        $this->load->helper(['url', 'form']);
        $this->load->library('session');
    }

    // List all questions
    public function index() {
        $data['questions'] = $this->Questions_model->get_all_questions();
        $this->load->view('questions/list', $data);
    }

    // Add new question
    public function add() {
        if($this->input->post()) {
            $question_data = [
                'form_type' => $this->input->post('form_type'),
                'q_section' => $this->input->post('q_section'),
                'q_num' => $this->input->post('q_num'),
                'q_text' => $this->input->post('q_text'),
                'q_order' => $this->input->post('q_order'),
                'q_type' => $this->input->post('q_type'),
                'status' => 1,
                'created_at' => date('Y-m-d H:i:s')
            ];

            $options = $this->input->post('options'); // array of ['text' => '', 'order' => '']

            $this->Questions_model->insert_question($question_data, $options);
            $this->session->set_flashdata('success', 'Question added successfully');
            redirect('questions');
        }
        $this->load->view('questions/add_edit');
    }

    // Edit question
    public function edit($id) {
        $data['question'] = $this->Questions_model->get_question($id);
        if(!$data['question']) {
            show_404();
        }

        if($this->input->post()) {
            $question_data = [
                'form_type' => $this->input->post('form_type'),
                'q_section' => $this->input->post('q_section'),
                'q_num' => $this->input->post('q_num'),
                'q_text' => $this->input->post('q_text'),
                'q_order' => $this->input->post('q_order'),
                'q_type' => $this->input->post('q_type')
            ];

            $options = $this->input->post('options'); // array with ['option_id','text','order']

            $this->Questions_model->update_question($id, $question_data, $options);
            $this->session->set_flashdata('success', 'Question updated successfully');
            redirect('questions');
        }

        $this->load->view('questions/add_edit', $data);
    }

    // Delete question
    public function delete($id) {
        $this->Questions_model->delete_question($id);
        $this->session->set_flashdata('success', 'Question deleted successfully');
        redirect('questions');
    }
}
