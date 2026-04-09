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
        $questions = $this->db
            ->where('status', 1)
            ->where('form_type', 'chf')
            ->order_by('q_order', 'ASC')
            ->get('questions')
            ->result_array();

        $select_questions = [];
        $question_labels = [];
        $question_options = [];

        foreach ($questions as $q) {

            $qid = $q['question_id'];
            $label = $q['q_order'] . '. ' . strtoupper($q['q_text']);

            $question_labels[$qid] = $label;

            // get options
            $options = $this->db
                ->where('question_id', $qid)
                ->order_by('option_order','ASC')
                ->get('question_options')
                ->result_array();

            foreach ($options as $opt) {

                $oid = $opt['option_id'];
                $col = "Q{$qid}_{$oid}";

                $question_options[$qid][] = [
                    'option_id'=>$oid,
                    'option_text'=>$opt['option_text'],
                    'column'=>$col
                ];

                $select_questions[] = "
                    MAX(
                        CASE
                        WHEN chd.question_id = {$qid}
                        AND chd.option_id = {$oid}
                        THEN 'Yes'
                        ELSE ''
                        END
                    ) AS `$col`
                ";
            }
        }

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
        
        if(!empty($filters['gender'])) {
            $this->db->where_in('chm.gender', $filters['gender']);
        }
        
        if(!empty($filters['age_group'])) {
            $this->db->where_in('chm.age_group', $filters['age_group']);
        }
        
        if(!empty($filters['visit_type'])) {
            $this->db->where_in('chm.visit_type', $filters['visit_type']);
        }

        if(!empty($filters['start']) && !empty($filters['end'])) {
            $this->db->where('chm.form_date >=', $filters['start']);
            $this->db->where('chm.form_date <=', $filters['end']);
        }

        if(empty($filters['data_mode']) || $filters['data_mode'] == 'unique') {
            $this->db->group_by('chm.qr_code');
        } else {
            $this->db->group_by('chm.master_id');
        }

        $this->db->order_by('chm.form_date', 'DESC');

        $result = $this->db->get()->result_array();

        return [
            'data' => $result,
            'questions' => $question_labels,
            'options' => $question_options
        ];
    }

    public function get_opd_mnch_data($filters)
    {
        // Step 1: Get all active questions for 'opd'
        $questions = $this->db
            ->where('status', 1)
            ->where('form_type', 'opd')
            ->order_by('q_order', 'ASC')
            ->get('questions')
            ->result_array();

        $select_questions = [];
        $question_labels = [];
        $question_options = [];

        foreach ($questions as $q) {
            $qid = $q['question_id'];
            $label = $q['q_order'] . '. ' . strtoupper($q['q_text']);
            $question_labels[$qid] = $label;

            // Get options for this question
            $options = $this->db
                ->where('question_id', $qid)
                ->order_by('option_order', 'ASC')
                ->get('question_options')
                ->result_array();

            foreach ($options as $opt) {
                $oid = $opt['option_id'];
                $col = "Q{$qid}_{$oid}";

                // Keep options info for view
                $question_options[$qid][] = [
                    'option_id' => $oid,
                    'option_text' => $opt['option_text'],
                    'column' => $col
                ];

                // Tick mark column
                $select_questions[] = "
                    MAX(
                        CASE 
                        WHEN omd.question_id = {$qid} 
                        AND omd.option_id = {$oid} 
                        THEN 'Yes'
                        ELSE ''
                        END
                    ) AS `$col`
                ";
            }
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
        
        if(!empty($filters['age_group'])) {
            $this->db->where_in('omm.age_group', $filters['age_group']);
        }
        
        if(!empty($filters['visit_type'])) {
            $this->db->where_in('omm.visit_type', $filters['visit_type']);
        }
        
        if (!empty($filters['start']) && !empty($filters['end'])) {
            $this->db->where('omm.form_date >=', $filters['start']);
            $this->db->where('omm.form_date <=', $filters['end']);
        }

        if(empty($filters['data_mode']) || $filters['data_mode'] == 'unique') {
            $this->db->group_by('omm.qr_code');
        } else {
            $this->db->group_by('omm.id');
        }

        $this->db->order_by('omm.form_date', 'DESC');

        $result = $this->db->get()->result_array();

        return [
            'data' => $result,
            'questions' => $question_labels,
            'options' => $question_options
        ];
    }
    
    public function get_validation_report()
{
    $this->db->select('rv.*, u.full_name,
        CASE 
            WHEN rv.module_name = "child_health" THEN ch.created_by
            WHEN rv.module_name = "opd_mnch" THEN om.created_by
            ELSE NULL
        END as form_created_by', FALSE);
    $this->db->from('record_validation rv');
    $this->db->join('users u', 'u.user_id = rv.user_id', 'left');
    $this->db->join('child_health_master ch', 'rv.master_id = ch.master_id AND rv.module_name = "child_health"', 'left');
    $this->db->join('opd_mnch_master om', 'rv.master_id = om.id AND rv.module_name = "opd_mnch"', 'left');

    $user_role = $this->session->userdata('role');
    $user_id   = $this->session->userdata('user_id');
    if ($user_role == 1) {
        $this->db->group_start();
        $this->db->where('ch.created_by', $user_id);
        $this->db->or_where('om.created_by', $user_id);
        $this->db->group_end();
    }

    $this->db->order_by('rv.id',   'DESC');

    $records = $this->db->get()->result_array();

    // Attach creator name to every row
    foreach ($records as &$row) {
        if (!empty($row['form_created_by'])) {
            $creator = $this->db->get_where('users',
                array('user_id' => $row['form_created_by']))->row();
            $row['creator_name'] = $creator ? $creator->full_name : '-';
        } else {
            $row['creator_name'] = '-';
        }
    }
    unset($row);

    // Last status per unique form (records ordered ASC so last write wins)
    $form_last_status = array();
    foreach ($records as $row) {
        $key = $row['module_name'] . '_' . $row['master_id'];
        $form_last_status[$key] = strtolower($row['validation_status']);
    }

    $verified = 0;
    $reported = 0;
    foreach ($form_last_status as $status) {
        if ($status == 'verified') $verified++;
        if ($status == 'reported') $reported++;
    }

    return array(
        'records'        => $records,
        'verified_total' => $verified,
        'reported_total' => $reported,
        'unique_forms'   => count($form_last_status)
    );
}
    
    public function get_duplicate_qr_report()
    {
        // Child Health duplicates
        $this->db->select('qr_code, COUNT(*) as total, "child_health" as module_name');
        $this->db->from('child_health_master');
        $this->db->group_by('qr_code');
        $this->db->having('COUNT(*) > 1');
        $child_duplicates = $this->db->get()->result_array();

        // OPD MNCH duplicates
        $this->db->select('qr_code, COUNT(*) as total, "opd_mnch" as module_name');
        $this->db->from('opd_mnch_master');
        $this->db->group_by('qr_code');
        $this->db->having('COUNT(*) > 1');
        $opd_duplicates = $this->db->get()->result_array();

        // Merge both results
        $duplicates = array_merge($child_duplicates, $opd_duplicates);

        return $duplicates;
    }
    
    public function get_qr_records($qr_code, $form_type)
    {
        if ($form_type == 'chf') {
            $this->db->select("m.*, u.uc AS uc_name, 'chf' AS form_type");
            $this->db->from('child_health_master m');
            $this->db->join('uc u', 'u.pk_id = m.uc', 'left');
            $this->db->where('m.qr_code', $qr_code);
            return $this->db->get()->result_array();
        }
        if ($form_type == 'opd') {
            $this->db->select("m.*, u.uc AS uc_name, 'opd' AS form_type");
            $this->db->from('opd_mnch_master m');
            $this->db->join('uc u', 'u.pk_id = m.uc', 'left');
            $this->db->where('m.qr_code', $qr_code);
            return $this->db->get()->result_array();
        }
        return array();
    }
    
   public function get_vaccination_simple($filters)
    {
        $this->db->select("
            u.uc AS uc_name,
            u.pk_id AS uc_id,
            DATE_FORMAT(m.form_date, '%Y-%m') AS month,
            COUNT(DISTINCT m.qr_code) AS total
        ");
        $this->db->from('child_health_master m');
        $this->db->join(
            'child_health_detail d',
            'm.master_id = d.master_id AND d.question_id IN (5,6,7)',
            'inner'
        );
        $this->db->join('uc u', 'u.pk_id = m.uc', 'left');

        // Month range filter — start of from_month to end of to_month
        if (!empty($filters['from_month'])) {
            $this->db->where('DATE(m.form_date) >=', $filters['from_month'] . '-01');
        }
        if (!empty($filters['to_month'])) {
            // Last day of to_month
            $this->db->where(
                'DATE(m.form_date) <=',
                date('Y-m-t', strtotime($filters['to_month'] . '-01'))
            );
        }

        $this->db->group_by(array('m.uc', 'month'));
        $this->db->order_by('u.pk_id', 'ASC');
        $this->db->order_by('month',   'ASC');

        return $this->db->get()->result_array();
    }

    public function get_vaccination_qr_by_month($filters)
    {
        $this->db->select("
            m.uc,
            u.uc AS uc_name,
            u.pk_id AS uc_id,
            DATE_FORMAT(m.form_date, '%Y-%m') AS month,
            m.qr_code
        ");
        $this->db->from('child_health_master m');
        $this->db->join(
            'child_health_detail d',
            'm.master_id = d.master_id AND d.question_id IN (5,6,7)',
            'inner'
        );
        $this->db->join('uc u', 'u.pk_id = m.uc', 'left');

        if (!empty($filters['from_month'])) {
            $this->db->where('DATE(m.form_date) >=', $filters['from_month'] . '-01');
        }
        if (!empty($filters['to_month'])) {
            $this->db->where(
                'DATE(m.form_date) <=',
                date('Y-m-t', strtotime($filters['to_month'] . '-01'))
            );
        }

        $this->db->group_by(array('m.uc', 'month', 'm.qr_code'));
        $this->db->order_by('u.pk_id', 'ASC');
        $this->db->order_by('month',   'ASC');

        return $this->db->get()->result_array();
    }
   
    public function get_duplicate_qr_code()
    {
        $sql = "SELECT
                    chm.qr_code,
                    GROUP_CONCAT(chm.master_id ORDER BY chm.master_id SEPARATOR ',')                      AS master_ids,
                    GROUP_CONCAT(chm.patient_name ORDER BY chm.master_id SEPARATOR ',')                   AS names,
                    GROUP_CONCAT(chm.dob ORDER BY chm.master_id SEPARATOR ',')                            AS dobs,
                    GROUP_CONCAT(chm.guardian_name ORDER BY chm.master_id SEPARATOR ',')                  AS guardians,
                    GROUP_CONCAT(u.full_name ORDER BY chm.master_id SEPARATOR ',')                       AS reported_by,
                    GROUP_CONCAT(chm.verification_status ORDER BY chm.master_id SEPARATOR ',')           AS verification_statuses,
                    COUNT(DISTINCT chm.patient_name)                              AS name_count
                FROM child_health_master chm
                INNER JOIN users u ON chm.created_by = u.user_id
                WHERE chm.qr_code NOT LIKE '%Supplementary%'
                AND chm.qr_code NOT LIKE '%NA%'
                AND chm.qr_code NOT LIKE '%N/A%'
                AND chm.verification_status != 'Wrong QR'
                GROUP BY chm.qr_code
                HAVING COUNT(DISTINCT chm.patient_name) > 1
                ORDER BY COUNT(DISTINCT chm.patient_name) DESC";
        $query = $this->db->query($sql);
        return $query->result_array();
    }
    
    public function get_neir_report($filters)
    {
        // 1. Load only question_id 5, 6, 7
        $questions = $this->db
            ->where('status', 1)
            ->where('form_type', 'chf')
            ->where_in('question_id', array(5, 6, 7))
            ->order_by('q_order', 'ASC')
            ->get('questions')
            ->result_array();

        // 2. Build unique vaccine columns by option_text
        $unique_vaccines = array();

        foreach ($questions as $q) {
            $qid = (int) $q['question_id'];

            $options = $this->db
                ->where('question_id', $qid)
                ->order_by('option_order', 'ASC')
                ->get('question_options')
                ->result_array();

            foreach ($options as $opt) {
                $oid   = (int) $opt['option_id'];
                $label = trim($opt['option_text']);
                $key   = 'vac_' . preg_replace('/[^a-z0-9_]/', '', strtolower(str_replace(' ', '_', $label)));

                if (!isset($unique_vaccines[$key])) {
                    $unique_vaccines[$key] = array('label' => $label, 'oids' => array());
                }
                $unique_vaccines[$key]['oids'][] = $oid;
            }
        }

        // 3. Build SELECT snippets
        $vaccine_selects = array();
        foreach ($unique_vaccines as $col => $info) {
            $oid_list          = implode(',', $info['oids']);
            $vaccine_selects[] = "
                COUNT(DISTINCT CASE WHEN chd.option_id IN ({$oid_list}) THEN chm.master_id END) AS `{$col}`
            ";
        }

        $vaccine_sql = !empty($vaccine_selects) ? ', ' . implode(', ', $vaccine_selects) : '';

        // 4. Main query — grouped by UC + age_group only
        $sql = "
            SELECT
                IFNULL(u.uc, '')                                                                    AS uc,
                IFNULL(chm.age_group, '')                                                           AS age_group,
                COUNT(DISTINCT chm.master_id)                                                       AS children_enrolled,
                COUNT(DISTINCT CASE WHEN chd.question_id IN (5,6,7) THEN chm.master_id END)        AS children_vaccinated
                {$vaccine_sql}
            FROM child_health_master chm
            LEFT JOIN child_health_detail chd ON chd.master_id = chm.master_id
            LEFT JOIN uc u                    ON u.pk_id       = chm.uc
            WHERE 1=1
        ";

        $binds = array();

        if (!empty($filters['start']) && !empty($filters['end'])) {
            $sql    .= " AND chm.form_date >= ?";
            $binds[] = $filters['start'];
            $sql    .= " AND chm.form_date <= ?";
            $binds[] = $filters['end'];
        }

        $sql .= "
            GROUP BY
                u.uc,
                chm.age_group
            ORDER BY
                u.uc          ASC,
                chm.age_group ASC
        ";

        $query  = $this->db->query($sql, $binds);
        $result = $query->result_array();

        // 5. Grand Total per row
        foreach ($result as &$row) {
            $grand = 0;
            foreach (array_keys($unique_vaccines) as $col) {
                $grand += (int) (isset($row[$col]) ? $row[$col] : 0);
            }
            $row['grand_total'] = $grand;
        }
        unset($row);

        return array(
            'data'    => $result,
            'options' => $unique_vaccines,
        );
    }
    
}
