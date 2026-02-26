<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reports_model extends CI_Model {

    // -------------------------------
    // 1) Data Entry Status
    // -------------------------------
    public function get_data_entry_status()
    {
        $sql = "
        SELECT 
            u.username as user,
            u.full_name as full_name,

            -- Child Health
            COALESCE(ch_new.new_count,0) as ch_new,
            COALESCE(ch_follow.follow_count,0) as ch_follow,
            COALESCE(ch_fixed.fixed_count,0) as ch_fixed,
            COALESCE(ch_outreach.outreach_count,0) as ch_outreach,

            -- OPD / MNCH
            COALESCE(opd_new.new_count,0) as opd_new,
            COALESCE(opd_follow.follow_count,0) as opd_follow,
            COALESCE(opd_count.opd_count,0) as opd,
            COALESCE(opd_count.mnch_count,0) as mnch

        FROM users u

        -- Child Health: New
        LEFT JOIN (
            SELECT created_by, COUNT(*) as new_count 
            FROM child_health_master 
            WHERE client_type='New' 
            GROUP BY created_by
        ) ch_new ON ch_new.created_by = u.user_id

        -- Child Health: Followup
        LEFT JOIN (
            SELECT created_by, COUNT(*) as follow_count 
            FROM child_health_master 
            WHERE client_type='Followup' 
            GROUP BY created_by
        ) ch_follow ON ch_follow.created_by = u.user_id

        -- Child Health: Fixed
        LEFT JOIN (
            SELECT created_by, COUNT(*) as fixed_count 
            FROM child_health_master 
            WHERE visit_type='Fixed Site' 
            GROUP BY created_by
        ) ch_fixed ON ch_fixed.created_by = u.user_id

        -- Child Health: Outreach
        LEFT JOIN (
            SELECT created_by, COUNT(*) as outreach_count 
            FROM child_health_master 
            WHERE visit_type='Outreach' 
            GROUP BY created_by
        ) ch_outreach ON ch_outreach.created_by = u.user_id

        -- OPD / MNCH totals by client type
        LEFT JOIN (
            SELECT created_by, COUNT(*) as new_count 
            FROM opd_mnch_master 
            WHERE client_type='New' 
            GROUP BY created_by
        ) opd_new ON opd_new.created_by = u.user_id

        LEFT JOIN (
            SELECT created_by, COUNT(*) as follow_count 
            FROM opd_mnch_master 
            WHERE client_type='Followup' 
            GROUP BY created_by
        ) opd_follow ON opd_follow.created_by = u.user_id

        -- OPD / MNCH totals by visit type
        LEFT JOIN (
            SELECT created_by, 
                   SUM(CASE WHEN visit_type='OPD' THEN 1 ELSE 0 END) as opd_count,
                   SUM(CASE WHEN visit_type='MNCH' THEN 1 ELSE 0 END) as mnch_count
            FROM opd_mnch_master
            GROUP BY created_by
        ) opd_count ON opd_count.created_by = u.user_id

        WHERE u.role = 1
        
        ORDER BY u.username
        ";

        $query = $this->db->query($sql);
        return $query->result();
    }

    public function get_uc_wise_report()
    {
        // Subquery for Child Health totals per UC (role = 1 only)
        $sub_ch = $this->db
            ->select('ch.uc as uc_id, COUNT(*) as total', false)
            ->from('child_health_master ch')
            ->join('users u', 'u.user_id = ch.created_by')
            ->where('u.role', 1)
            ->group_by('ch.uc')
            ->get_compiled_select();

        // Subquery for OPD/MNCH totals per UC (role = 1 only)
        $sub_op = $this->db
            ->select('op.uc as uc_id, COUNT(*) as total', false)
            ->from('opd_mnch_master op')
            ->join('users u', 'u.user_id = op.created_by')
            ->where('u.role', 1)
            ->group_by('op.uc')
            ->get_compiled_select();

        // Main query
        $this->db->select('uc.uc as uc_name,
            COALESCE(ch.total,0) as child_health_total,
            COALESCE(op.total,0) as opd_total
        ');
        $this->db->from('uc');

        $this->db->join("($sub_ch) ch", 'ch.uc_id = uc.pk_id', 'left');
        $this->db->join("($sub_op) op", 'op.uc_id = uc.pk_id', 'left');

        $this->db->order_by('uc.uc', 'ASC');

        return $this->db->get()->result();
    }
    
    public function get_date_wise_progress()
    {
        // Fixed start date
        $start_date = '2026-02-17';
        $end_date = date('Y-m-d'); // today

        // Child Health data
        $this->db->select('created_by, DATE(created_at) as entry_date, COUNT(*) as total_forms');
        $this->db->from('child_health_master');
        $this->db->where('DATE(created_at) >=', $start_date);
        $this->db->where('DATE(created_at) <=', $end_date);
        $this->db->group_by(['created_by', 'entry_date']);
        $child = $this->db->get()->result_array();

        // OPD/MNCH data
        $this->db->select('created_by, DATE(created_at) as entry_date, COUNT(*) as total_forms');
        $this->db->from('opd_mnch_master');
        $this->db->where('DATE(created_at) >=', $start_date);
        $this->db->where('DATE(created_at) <=', $end_date);
        $this->db->group_by(['created_by', 'entry_date']);
        $opd = $this->db->get()->result_array();

        // Merge both arrays into a single structure
        $progress = [];
        foreach(array_merge($child, $opd) as $row){
            $progress[$row['created_by']][$row['entry_date']] = 
                isset($progress[$row['created_by']][$row['entry_date']]) 
                ? $progress[$row['created_by']][$row['entry_date']] + $row['total_forms'] 
                : $row['total_forms'];
        }

        // Get all data entry users
        $users = $this->db->select('user_id, username, full_name')
                          ->from('users')
                          ->where('role', 1)
                          ->get()
                          ->result_array();

        // Get all dates in range
        $dates = [];
        $current = strtotime($start_date);
        $end = strtotime($end_date);
        while ($current <= $end) {
            $dates[] = date('Y-m-d', $current);
            $current = strtotime('+1 day', $current);
        }

        return [
            'users' => $users,
            'dates' => $dates,
            'progress' => $progress
        ];
    }
    
    public function get_date_wise_form_progress()
    {
        // Fixed start and end date (1 Feb 2026 to today)
        $start_date = '2026-02-17';
        $end_date = date('Y-m-d');

        // Child Health
        $this->db->select('DATE(created_at) as entry_date, COUNT(*) as total_forms');
        $this->db->from('child_health_master');
        $this->db->where('DATE(created_at) >=', $start_date);
        $this->db->where('DATE(created_at) <=', $end_date);
        $this->db->group_by('entry_date');
        $child = $this->db->get()->result_array();

        // OPD/MNCH
        $this->db->select('DATE(created_at) as entry_date, COUNT(*) as total_forms');
        $this->db->from('opd_mnch_master');
        $this->db->where('DATE(created_at) >=', $start_date);
        $this->db->where('DATE(created_at) <=', $end_date);
        $this->db->group_by('entry_date');
        $opd = $this->db->get()->result_array();

        // Initialize progress arrays
        $progress = [
            'child_health' => [],
            'opd_mnch' => []
        ];

        foreach($child as $row){
            $progress['child_health'][$row['entry_date']] = $row['total_forms'];
        }

        foreach($opd as $row){
            $progress['opd_mnch'][$row['entry_date']] = $row['total_forms'];
        }

        // All dates in the range
        $dates = [];
        $current = strtotime($start_date);
        $end = strtotime($end_date);
        while($current <= $end){
            $dates[] = date('Y-m-d', $current);
            $current = strtotime('+1 day', $current);
        }

        return [
            'dates' => $dates,
            'progress' => $progress
        ];
    }
    
    // Get all UC names
    public function get_ucs()
    {
        $this->db->select('uc as uc_name, pk_id'); // Adjust column name if different
        $this->db->from('uc');
        $this->db->order_by('uc_name', 'ASC');
        $query = $this->db->get();
        return $query->result();
    }
    
    public function get_child_health_data($filters)
    {
        // Step 1: Get all active questions for 'chf' in order
        $questions = $this->db
            ->where('status', 1)
            ->where('form_type', 'chf')
            ->order_by('q_order', 'ASC')
            ->get('questions')
            ->result_array();

        $select_questions = [];
        $question_labels = [];

        foreach ($questions as $q) {
            $qid = $q['question_id'];
            $label = $q['q_order'] . '. ' . strtoupper($q['q_text']);
            $question_labels[$qid] = $label;

            // Conditional aggregation: group answers for this question
            $select_questions[] = "GROUP_CONCAT(
                CASE WHEN chd.question_id = {$qid} THEN chd.answer END
                SEPARATOR ', '
            ) AS `Q{$qid}`";
        }

        // Step 2: Main query
        $this->db->select("
            chm.master_id,
            chm.form_date,
            chm.qr_code,
            chm.client_type,
            'North Waziristan' AS district,
            u.uc AS uc,
            f.facility_name,
            chm.village,
            chm.vaccinator_name,
            chm.patient_name,
            chm.guardian_name,
            chm.dob,
            chm.age_year,
            chm.age_month,
            chm.age_day,
            chm.gender,
            chm.marital_status,
            chm.pregnancy_status,
            chm.disability,
            chm.play_learning_kit,
            chm.nutrition_package,
            chm.created_at,
            chm.visit_type,
            chm.created_by,
            chm.age_group,
            ".implode(',', $select_questions)."
        ");
        $this->db->from('child_health_master chm');
        $this->db->join('child_health_detail chd', 'chd.master_id = chm.master_id', 'left');
        $this->db->join('uc u', 'u.pk_id = chm.uc', 'left');
        $this->db->join('facilities f','f.id = chm.facility_id','left');

        if(!empty($filters['uc'])) {
            $this->db->where_in('chm.uc', $filters['uc']);
        }
        if(!empty($filters['start']) && !empty($filters['end'])) {
            $this->db->where('chm.form_date >=', $filters['start']);
            $this->db->where('chm.form_date <=', $filters['end']);
        }

        $this->db->group_by('chm.master_id');

        $this->db->order_by('u.pk_id', 'ASC');
        $this->db->order_by('chm.form_date', 'ASC');
        
        $result = $this->db->get()->result_array();

        return [
            'data' => $result,
            'questions' => $question_labels
        ];
    }

    public function get_opd_mnch_data($filters)
    {
        // Step 1: Get all active questions for 'opd' in order
        $questions = $this->db
            ->where('status', 1)
            ->where('form_type', 'opd')
            ->order_by('q_order', 'ASC')
            ->get('questions')
            ->result_array();

        $select_questions = [];
        $question_labels = [];

        foreach ($questions as $q) {
            $qid = $q['question_id'];
            $label = $q['q_order'] . '. ' . strtoupper($q['q_text']);
            $question_labels[$qid] = $label;

            $select_questions[] = "GROUP_CONCAT(
                CASE WHEN omd.question_id = {$qid} THEN omd.answer END
                SEPARATOR ', '
            ) AS `Q{$qid}`";
        }

        // Step 2: Main query
        $this->db->select("
            omm.id,
            omm.form_date,
            omm.anc_card_no,
            omm.client_type,
            'North Waziristan' AS district,
            u.uc AS uc,
            omm.village,
            omm.lhv_name,
            omm.patient_name,
            omm.guardian_name,
            omm.disability,
            omm.age_group,
            omm.marital_status,
            omm.pregnancy_status,
            omm.notes,
            omm.created_at,
            omm.visit_type,
            omm.created_by,
            f.facility_name,
            omm.qr_code,
            ".implode(',', $select_questions)."
        ");

        $this->db->from('opd_mnch_master omm');
        $this->db->join('opd_mnch_detail omd', 'omd.master_id = omm.id', 'left');
        $this->db->join('uc u', 'u.pk_id = omm.uc', 'left');
        $this->db->join('facilities f', 'f.id = omm.facility_id', 'left');

        if (!empty($filters['uc'])) {
            $this->db->where_in('omm.uc', $filters['uc']);
        }
        if (!empty($filters['start']) && !empty($filters['end'])) {
            $this->db->where('omm.form_date >=', $filters['start']);
            $this->db->where('omm.form_date <=', $filters['end']);
        }

        $this->db->group_by('omm.id');

        $this->db->order_by('u.pk_id', 'ASC');
        $this->db->order_by('chm.form_date', 'ASC');
        
        $result = $this->db->get()->result_array();

        return [
            'data' => $result,
            'questions' => $question_labels
        ];
    }
    
}
