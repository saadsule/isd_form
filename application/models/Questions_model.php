<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Questions_model extends CI_Model {

    // Fetch all questions
    public function get_all_questions($form_type = null) {
        if($form_type) {
            $this->db->where('form_type', $form_type);
        }
        $this->db->order_by('q_order', 'ASC');
        return $this->db->get('questions')->result();
    }

    // Get single question by ID
    public function get_question($question_id) {
        $question = $this->db->get_where('questions', ['question_id' => $question_id])->row();
        if($question) {
            $question->options = $this->db->get_where('question_options', ['question_id' => $question_id, 'status' => 1])->result();
        }
        return $question;
    }

    // Insert question
    public function insert_question($data, $options = []) {
        $this->db->insert('questions', $data);
        $question_id = $this->db->insert_id();

        if(!empty($options)) {
            foreach($options as $key => $opt) {
                $this->db->insert('question_options', [
                    'question_id' => $question_id,
                    'option_text' => $opt['text'],
                    'option_order' => $opt['order'],
                    'status' => 1
                ]);
            }
        }
        return $question_id;
    }

    // Update question
    public function update_question($question_id, $data, $options = []) {
        $this->db->where('question_id', $question_id)->update('questions', $data);

        // Update options
        if(!empty($options)) {
            foreach($options as $opt_id => $opt) {
                if(isset($opt['option_id'])) {
                    // update existing
                    $this->db->where('option_id', $opt['option_id'])->update('question_options', [
                        'option_text' => $opt['text'],
                        'option_order' => $opt['order'],
                        'status' => 1
                    ]);
                } else {
                    // insert new
                    $this->db->insert('question_options', [
                        'question_id' => $question_id,
                        'option_text' => $opt['text'],
                        'option_order' => $opt['order'],
                        'status' => 1
                    ]);
                }
            }
        }
    }

    // Delete question
    public function delete_question($question_id) {
        $this->db->where('question_id', $question_id)->delete('questions');
        $this->db->where('question_id', $question_id)->delete('question_options');
    }
    
    // Fetch all questions for a given form type
    public function get_all_questions_by_form_type($form_type)
    {
        // Get all active questions for the form type, ordered by section and order
        $this->db->where('form_type', $form_type);
        $this->db->where('status', 1);
        $this->db->order_by('q_section', 'ASC'); // optional: alphabetical sections
        $this->db->order_by('q_order', 'ASC');   // question order
        $questions = $this->db->get('questions')->result();

        // Fetch options for each question
        foreach($questions as $q) {
            $q->options = $this->db->get_where('question_options', [
                'question_id' => $q->question_id,
                'status' => 1
            ])->result();
        }

        return $questions;
    }
}
