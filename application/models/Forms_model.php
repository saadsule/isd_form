<?php
defined('BASEPATH') OR exit('No direct script access allowed');

    class Forms_model extends CI_Model {

    public function get_child_master($id)
    {
        $this->db->select("
            ch.*,
            d.district_name AS district_name,
            u.uc AS uc_name,
            f.facility_name
        ");
        $this->db->from('child_health_master ch');
        $this->db->join('districts d', 'd.district_id = ch.district', 'left');
        $this->db->join('uc u', 'u.pk_id = ch.uc', 'left');
        $this->db->join('facilities f', 'f.id = ch.facility_id', 'left');
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
            ch.visit_type,
            ch.created_by
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
            op.created_at,
            op.created_by
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
            ->select("op.*, d.district_name, u.uc AS uc_name, f.facility_name")
            ->from('opd_mnch_master op')
            ->join('districts d','d.district_id = op.district','left')
            ->join('uc u','u.pk_id = op.uc','left')
            ->join('facilities f','f.id = op.facility_id','left')
            ->where('op.id', $master_id)
            ->get()
            ->row();

        // QUESTIONS with OPTIONS
        $questions = $this->db
            ->select("q.question_id, q.q_section, q.q_num, q.q_text, q.q_type, q.q_order,
                      o.option_id, o.option_text, d.answer, d.option_id AS selected_option")
            ->from('questions q')
            ->join('question_options o','o.question_id = q.question_id AND o.status=1','left')
            ->join('opd_mnch_detail d','d.question_id = q.question_id AND d.master_id = '.$this->db->escape($master_id),'left')
            ->where('q.form_type','opd')
            ->where('q.status',1)
            ->order_by('q.q_order','ASC')
            ->get()
            ->result();

        // Structure: section -> question -> options
        $structured = [];
        foreach($questions as $q){
            $qid = $q->question_id;
            if(!isset($structured[$q->q_section][$qid])){
                $structured[$q->q_section][$qid] = $q;
                $structured[$q->q_section][$qid]->options = [];
            }

            // Attach options to question
            if($q->option_id){
                $structured[$q->q_section][$qid]->options[] = $q;
            }
        }

        return [
            'master' => $master,
            'questions' => $structured
        ];
    }
    
    public function get_opd_master($master_id)
    {
        $this->db->select("
            op.*,
            d.district_name AS district_name,
            u.uc AS uc_name
        ");
        $this->db->from('opd_mnch_master op');
        $this->db->join('districts d', 'd.district_id = op.district', 'left');
        $this->db->join('uc u', 'u.pk_id = op.uc', 'left');
        $this->db->where('op.id', $master_id);

        return $this->db->get()->row();
    }
    
    public function get_opd_details($master_id)
    {
        $rows = $this->db
            ->where('master_id', $master_id)
            ->get('opd_mnch_detail')
            ->result();

        $answers = [];

        foreach($rows as $r)
        {
            // If option_id exists, use it; otherwise use answer
            $answers[$r->question_id][] = isset($r->option_id) && $r->option_id != '' 
                ? $r->option_id 
                : $r->answer;
        }

        return $answers;
    }
    
    public function get_child_details($master_id)
    {
        $rows = $this->db
            ->where('master_id', $master_id)
            ->get('child_health_detail')
            ->result();

        $answers = [];

        foreach($rows as $r)
        {
            // If option_id exists, use it; otherwise use answer
            $answers[$r->question_id][] = isset($r->option_id) && $r->option_id != '' 
                ? $r->option_id 
                : $r->answer;
        }

        return $answers;
    }

}
