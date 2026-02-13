<?php
defined('BASEPATH') OR exit('No direct script access allowed');

    class Forms_model extends CI_Model {


    public function get_child_master($id)
    {
        $this->db->select("
            ch.*,
            d.district_name AS district_name,
            u.uc AS uc_name
        ");
        $this->db->from('child_health_master ch');
        $this->db->join('districts d', 'd.district_id = ch.district', 'left');
        $this->db->join('uc u', 'u.pk_id = ch.uc', 'left');
        $this->db->where('ch.master_id', $id);

        return $this->db->get()->row();
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
            ch.master_id,
            ch.form_date,
            ch.patient_name,
            ch.guardian_name,
            d.district_name AS district,
            u.uc AS uc,
            ch.village,
            ch.gender,
            ch.client_type,
            ch.created_at,
            ch.visit_type
        ");
        $this->db->from('child_health_master ch');
        $this->db->join('districts d', 'd.district_id = ch.district', 'left');
        $this->db->join('uc u', 'u.pk_id = ch.uc', 'left');
        $this->db->order_by('ch.master_id', 'DESC');

        return $this->db->get()->result();
    }
    
    public function get_opd_records()
    {
        $this->db->select("
            op.id,
            op.form_date,
            op.patient_name,
            op.guardian_name,
            d.district_name AS district,
            u.uc AS uc,
            op.village,
            op.client_type,
            op.visit_type,
            op.created_at
        ");
        $this->db->from('opd_mnch_master op');
        $this->db->join('districts d', 'd.district_id = op.district', 'left');
        $this->db->join('uc u', 'u.pk_id = op.uc', 'left');
        $this->db->order_by('op.id', 'DESC');

        return $this->db->get()->result();
    }
    
    public function get_opd_detail($master_id)
    {
        // MASTER
        $master = $this->db
            ->select("
                op.*,
                d.district_name AS district_name,
                u.uc AS uc_name
            ")
            ->from('opd_mnch_master op')
            ->join('districts d', 'd.district_id = op.district', 'left')
            ->join('uc u', 'u.pk_id = op.uc', 'left')
            ->where('op.id', $master_id)
            ->get()
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
