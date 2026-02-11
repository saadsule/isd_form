<?php
defined('BASEPATH') OR exit('No direct script access allowed');

    class Forms_model extends CI_Model {


    public function get_child_master($id)
    {
        return $this->db
            ->where('master_id', $id)
            ->get('child_health_master')
            ->row();
    }
    
    public function get_child_questions_with_answers($master_id)
    {
        $this->db->select("
            q.question_id,
            q.q_section,
            q.q_num,
            q.q_text,
            q.q_type,
            q.q_order,
            o.option_id,
            o.option_text,
            d.answer,
            d.option_id as selected_option
        ");

        $this->db->from('questions q');

        $this->db->join(
            'question_options o',
            'o.question_id = q.question_id AND o.status=1',
            'left'
        );

        $this->db->join(
            'child_health_detail d',
            'd.question_id = q.question_id 
             AND d.master_id = '.$this->db->escape($master_id),
            'left'
        );

        $this->db->where('q.form_type','chf');
        $this->db->where('q.status',1);

        $this->db->order_by('q.q_order','ASC');

        return $this->db->get()->result();
    }
    
    public function get_child_records()
    {
        $this->db->select("
            master_id,
            form_date,
            patient_name,
            guardian_name,
            district,
            village,
            gender,
            client_type,
            created_at
        ");

        $this->db->from('child_health_master');

        $this->db->order_by('master_id','DESC');

        return $this->db->get()->result();
    }
    
    public function get_opd_records()
    {
        return $this->db
            ->select("
                id,
                form_date,
                patient_name,
                guardian_name,
                district,
                village,
                client_type,
                visit_type,
                created_at
            ")
            ->from('opd_mnch_master')
            ->order_by('id','DESC')
            ->get()
            ->result();
    }
    
    public function get_opd_detail($master_id)
    {
        // MASTER
        $master = $this->db
            ->where('id',$master_id)
            ->get('opd_mnch_master')
            ->row();

        // QUESTIONS
        $questions = $this->db
            ->where('form_type','opd')
            ->order_by('q_order','ASC')
            ->get('questions')
            ->result();

        // QUESTION IDS
        $question_ids = array_column($questions, 'question_id');

        // OPTIONS
        $options = [];
        if(!empty($question_ids)){
            $options = $this->db
                ->where_in('question_id', $question_ids)
                ->get('question_options')
                ->result();
        }

        // ANSWERS
        $answers = $this->db
            ->where('id', $master_id)
            ->get('opd_mnch_detail')
            ->result();

        // Map options & answers to questions
        foreach($questions as &$q){
            $q->options = [];
            foreach($options as $opt){
                if($opt->question_id == $q->question_id){
                    $opt->selected_option = null;
                    $opt->answer = null;
                    foreach($answers as $ans){
                        if($ans->question_id == $q->question_id){
                            if($ans->option_id == $opt->option_id){
                                $opt->selected_option = $ans->option_id;
                                $opt->answer = $ans->answer;
                            }
                        }
                    }
                    $q->options[] = $opt;
                }
            }
        }

        return [
            'master' => $master,
            'questions' => $questions
        ];
    }

}
