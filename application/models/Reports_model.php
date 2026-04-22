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
    
    public function get_date_wise_progress($start_date = '2026-04-01', $end_date = null)
    {
        if (!$end_date) {
            $end_date = date('Y-m-d');
        }

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

        // Merge both arrays
        $progress = [];
        foreach (array_merge($child, $opd) as $row) {
            $uid  = $row['created_by'];
            $date = $row['entry_date'];
            $progress[$uid][$date] = isset($progress[$uid][$date])
                ? $progress[$uid][$date] + $row['total_forms']
                : $row['total_forms'];
        }

        // Get users
        if ($this->session->userdata('role') != '2') {
            $this->db->where('user_id >', 14);
        }
        $users = $this->db->select('user_id, username, full_name')
                          ->from('users')
                          ->where('role', 1)
                          ->get()
                          ->result_array();

        // Build date range array
        $dates   = [];
        $current = strtotime($start_date);
        $end     = strtotime($end_date);
        while ($current <= $end) {
            $dates[]  = date('Y-m-d', $current);
            $current  = strtotime('+1 day', $current);
        }

        return [
            'users'     => $users,
            'dates'     => $dates,
            'progress'  => $progress,
            'from_date' => $start_date,
            'to_date'   => $end_date,
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

        // 2. Build unique vaccine columns by option_text from DB
        $unique_vaccines = array();
        foreach ($questions as $q) {
            $qid     = (int) $q['question_id'];
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

        //
        $db_to_display = array(
            'bcg'       => 'BCG',
            'hep'       => 'Hepatitis B0',
            'opv 0'     => 'OPV 0',
            'opv i'     => 'OPV 1',
            'opv ii'    => 'OPV 2',
            'opv iii'   => 'OPV 3',
            'pcv i'     => 'PCV 1',
            'pcv ii'    => 'PCV 2',
            'pcv iii'   => 'PCV 3',
            'pcv'       => 'PCV',
            'penta i'   => 'Penta 1',
            'penta ii'  => 'Penta 2',
            'penta iii' => 'Penta 3',
            'rota i'    => 'Rota 1',
            'rota ii'   => 'Rota 2',
            'ipv i'     => 'IPV',
            'ipv ii'    => 'IPV 2',
            'mr i'      => 'MR 1',
            'mr ii'     => 'MR 2',
            'tcv'       => 'TCV',
        );

        // normalize: lowercase + trim only (keep spaces for exact matching)
        $normalize = function($str) {
            return strtolower(trim($str));
        };

        // 4. Merge DB vaccines into display-label buckets
        $merged_vaccines = array();
        foreach ($unique_vaccines as $col => $info) {
            $norm          = $normalize($info['label']);
            $display_label = isset($db_to_display[$norm]) ? $db_to_display[$norm] : $info['label'];
            $display_key   = 'vac_' . preg_replace('/[^a-z0-9_]/', '', strtolower(str_replace(' ', '_', $display_label)));

            if (!isset($merged_vaccines[$display_key])) {
                $merged_vaccines[$display_key] = array('label' => $display_label, 'oids' => array());
            }
            $merged_vaccines[$display_key]['oids'] = array_unique(array_merge(
                $merged_vaccines[$display_key]['oids'],
                $info['oids']
            ));
        }

        // 5. Hardcoded display order
        //    Hepatitis B0 — not in DB => 0
        //    DTP Booster  — not in DB => 0
        //    PCV (plain)  — in DB     => real data, shown after DTP Booster
        $antigen_order = array(
            'BCG',
            'Hepatitis B0',
            'OPV 0',   'OPV 1',   'OPV 2',   'OPV 3',
            'PCV 1',   'PCV 2',   'PCV 3',
            'Penta 1', 'Penta 2', 'Penta 3',
            'Rota 1',  'Rota 2',
            'IPV',     'IPV 2',
            'MR 1',    'MR 2',
            'TCV',
            'DTP Booster',
            'PCV',
        );

        $order_map = array();
        foreach ($antigen_order as $i => $display_label) {
            $display_key             = 'vac_' . preg_replace('/[^a-z0-9_]/', '', strtolower(str_replace(' ', '_', $display_label)));
            $order_map[$display_key] = $i;

            // Inject if completely missing from DB — column shows 0
            if (!isset($merged_vaccines[$display_key])) {
                $merged_vaccines[$display_key] = array('label' => $display_label, 'oids' => array());
            }
        }

        // 6. Sort: hardcoded order first, unrecognized DB antigens at end
        uksort($merged_vaccines, function($a, $b) use ($order_map) {
            $pos_a = isset($order_map[$a]) ? $order_map[$a] : 999;
            $pos_b = isset($order_map[$b]) ? $order_map[$b] : 999;
            return $pos_a - $pos_b;
        });

        // 7. Build SELECT snippets
        $vaccine_selects = array();
        foreach ($merged_vaccines as $col => $info) {
            if (!empty($info['oids'])) {
                $oid_list          = implode(',', $info['oids']);
                $vaccine_selects[] = "COUNT(DISTINCT CASE WHEN chd.option_id IN ({$oid_list}) THEN chm.master_id END) AS `{$col}`";
            } else {
                $vaccine_selects[] = "0 AS `{$col}`";
            }
        }
        $vaccine_sql = !empty($vaccine_selects) ? ', ' . implode(', ', $vaccine_selects) : '';

        // 8. Main query
        $sql = "
            SELECT
                IFNULL(u.uc, '')                                                                 AS uc,
                IFNULL(chm.age_group, '')                                                        AS age_group,
                COUNT(DISTINCT chm.master_id)                                                    AS children_enrolled,
                COUNT(DISTINCT CASE WHEN chd.question_id IN (5,6,7) THEN chm.master_id END)     AS children_vaccinated
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

        // 9. Grand Total per row
        foreach ($result as &$row) {
            $grand = 0;
            foreach ($merged_vaccines as $col => $info) {
                $grand += (int) (isset($row[$col]) ? $row[$col] : 0);
            }
            $row['grand_total'] = $grand;
        }
        unset($row);

        return array(
            'data'    => $result,
            'options' => $merged_vaccines,
        );
    }
    
    public function get_age_antigens_mismatch_report($filter_type = null)
    {
        // Get current user's role and ID from session
        $current_user_id = $this->session->userdata('user_id');
        $current_role    = $this->session->userdata('role');

        // Build role-based WHERE clause addition
        $role_filter = ($current_role == 1) ? "AND chm.created_by = {$current_user_id}" : "";

        $sql = "
            SELECT
                chm.master_id,
                chm.qr_code,
                chm.patient_name,
                chm.guardian_name,
                chm.dob,
                chm.age_year,
                chm.age_month,
                chm.age_group,
                chm.vaccinator_name,
                chm.village,
                chm.form_date,
                chm.created_at,
                chm.verification_status,
                u.full_name AS data_entry_user,
                chm.created_by,
                CASE
                    WHEN EXISTS (SELECT 1 FROM child_health_detail d WHERE d.master_id = chm.master_id AND d.question_id = 5)
                         AND chm.age_group != '<1 year' THEN 'Type 1'
                    WHEN EXISTS (SELECT 1 FROM child_health_detail d WHERE d.master_id = chm.master_id AND d.question_id = 6)
                         AND chm.age_group != '1-2 year' THEN 'Type 2'
                    WHEN EXISTS (SELECT 1 FROM child_health_detail d WHERE d.master_id = chm.master_id AND d.question_id = 7)
                         AND chm.age_group != '2-5 year' THEN 'Type 3'
                END AS mismatch_type,
                CASE
                    WHEN EXISTS (SELECT 1 FROM child_health_detail d WHERE d.master_id = chm.master_id AND d.question_id = 5)
                         AND chm.age_group != '<1 year' THEN '<1 year'
                    WHEN EXISTS (SELECT 1 FROM child_health_detail d WHERE d.master_id = chm.master_id AND d.question_id = 6)
                         AND chm.age_group != '1-2 year' THEN '1-2 year'
                    WHEN EXISTS (SELECT 1 FROM child_health_detail d WHERE d.master_id = chm.master_id AND d.question_id = 7)
                         AND chm.age_group != '2-5 year' THEN '2-5 year'
                END AS expected_age_group,
                CASE
                    WHEN EXISTS (SELECT 1 FROM child_health_detail d WHERE d.master_id = chm.master_id AND d.question_id = 5)
                         AND chm.age_group != '<1 year' THEN 'Q18'
                    WHEN EXISTS (SELECT 1 FROM child_health_detail d WHERE d.master_id = chm.master_id AND d.question_id = 6)
                         AND chm.age_group != '1-2 year' THEN 'Q19'
                    WHEN EXISTS (SELECT 1 FROM child_health_detail d WHERE d.master_id = chm.master_id AND d.question_id = 7)
                         AND chm.age_group != '2-5 year' THEN 'Q20'
                END AS question_answered
            FROM child_health_master chm
            LEFT JOIN users u ON chm.created_by = u.user_id
            WHERE
                (
                    (
                        EXISTS (SELECT 1 FROM child_health_detail d WHERE d.master_id = chm.master_id AND d.question_id = 5)
                        AND chm.age_group != '<1 year'
                    )
                    OR (
                        EXISTS (SELECT 1 FROM child_health_detail d WHERE d.master_id = chm.master_id AND d.question_id = 6)
                        AND chm.age_group != '1-2 year'
                    )
                    OR (
                        EXISTS (SELECT 1 FROM child_health_detail d WHERE d.master_id = chm.master_id AND d.question_id = 7)
                        AND chm.age_group != '2-5 year'
                    )
                )
                {$role_filter}
            ORDER BY chm.created_at DESC
        ";

        $all_records = $this->db->query($sql)->result_array();

        $type1 = 0;
        $type2 = 0;
        $type3 = 0;

        foreach ($all_records as $r) {
            if ($r['mismatch_type'] === 'Type 1') $type1++;
            elseif ($r['mismatch_type'] === 'Type 2') $type2++;
            elseif ($r['mismatch_type'] === 'Type 3') $type3++;
        }

        if ($filter_type) {
            $records = array();
            foreach ($all_records as $r) {
                if ($r['mismatch_type'] === $filter_type) $records[] = $r;
            }
        } else {
            $records = $all_records;
        }

        return array(
            'records' => $records,
            'summary' => array(
                'total_mismatches' => count($all_records),
                'type_1_count'     => $type1,
                'type_2_count'     => $type2,
                'type_3_count'     => $type3,
            )
        );
    }

    public function get_underage_married_records()
    {
        $current_user_id = $this->session->userdata('user_id');
        $current_role    = $this->session->userdata('role');

        $this->db->select('
            chm.master_id, chm.qr_code, chm.patient_name, chm.guardian_name,
            chm.dob, chm.age_year, chm.age_month, chm.age_group,
            chm.gender, chm.marital_status, chm.pregnancy_status,
            chm.vaccinator_name, chm.village, chm.form_date, chm.created_at,
            chm.verification_status,
            chm.created_by,
            u.full_name AS data_entry_user
        ');
        $this->db->from('child_health_master chm');
        $this->db->join('users u', 'chm.created_by = u.user_id', 'left');
        $this->db->where('chm.age_year <', 13);
        $this->db->where('chm.marital_status', 'Married');

        if ($current_role == 1) {
            $this->db->where('chm.created_by', $current_user_id);
        }

        $this->db->order_by('chm.created_at', 'DESC');
        return $this->db->get()->result_array();
    }

    public function get_pregnancy_anomaly_records($filter = null)
    {
        $current_user_id = $this->session->userdata('user_id');
        $current_role    = $this->session->userdata('role');

        $this->db->select('
            chm.master_id, chm.qr_code, chm.patient_name, chm.guardian_name,
            chm.dob, chm.age_year, chm.age_month, chm.age_group,
            chm.gender, chm.marital_status, chm.pregnancy_status,
            chm.vaccinator_name, chm.village, chm.form_date, chm.created_at,
            chm.verification_status,
            chm.created_by,
            u.full_name AS data_entry_user
        ');
        $this->db->from('child_health_master chm');
        $this->db->join('users u', 'chm.created_by = u.user_id', 'left');
        $this->db->where('chm.pregnancy_status', 'Pregnant');
        $this->db->group_start();
        $this->db->where('chm.gender !=', 'Female');
        $this->db->or_where('chm.age_year <', 13);
        $this->db->or_where('chm.marital_status', 'Un-Married');
        $this->db->group_end();

        if ($current_role == 1) {
            $this->db->where('chm.created_by', $current_user_id);
        }

        $this->db->order_by('chm.created_at', 'DESC');
        $all = $this->db->get()->result_array();
        $male_count      = 0;
        $underage_count  = 0;
        $unmarried_count = 0;
        foreach ($all as $r) {
            if ($r['gender'] !== 'Female')        { $male_count++; }
            if ((int)$r['age_year'] < 13)          { $underage_count++; }
            if ($r['marital_status'] === 'Un-Married') { $unmarried_count++; }
        }
        if ($filter) {
            $records = array();
            foreach ($all as $r) {
                $match = false;
                if ($filter === 'male'      && $r['gender'] !== 'Female')        { $match = true; }
                if ($filter === 'underage'  && (int)$r['age_year'] < 13)         { $match = true; }
                if ($filter === 'Un-Married' && $r['marital_status'] === 'Un-Married') { $match = true; }
                if ($match) { $records[] = $r; }
            }
        } else {
            $records = $all;
        }
        return array(
            'records' => $records,
            'summary' => array(
                'total'     => count($all),
                'male'      => $male_count,
                'underage'  => $underage_count,
                'Un-Married' => $unmarried_count,
            )
        );
    }

    public function get_possible_duplicates()
    {
        $current_user_id = $this->session->userdata('user_id');
        $current_role    = $this->session->userdata('role');
        
        $this->db->select('
            chm.master_id, chm.qr_code, chm.form_date, chm.patient_name,
            chm.guardian_name, chm.dob, chm.age_year, chm.age_month, chm.age_day,
            chm.gender, chm.age_group, chm.vaccinator_name, chm.village,
            chm.district, chm.created_at, chm.created_by,
            u.full_name AS data_entry_user
        ');
        $this->db->from('child_health_master chm');
        $this->db->join('users u', 'chm.created_by = u.user_id', 'left');

        // ── Exclude non-duplicate-eligible QR codes ──
        $this->db->where('chm.qr_code NOT LIKE', '%Supplementary%');
        $this->db->where('chm.qr_code NOT LIKE', '%NA%');
        $this->db->where('chm.qr_code NOT LIKE', '%N/A%');

        if ($current_role == 1) {
            $this->db->where('chm.created_by', $current_user_id);
        }
        
        $this->db->order_by('chm.qr_code', 'ASC');
        $this->db->order_by('chm.patient_name', 'ASC');
        $this->db->order_by('chm.form_date', 'ASC');
        
        $all = $this->db->get()->result_array();

        /* ── Group by qr_code + patient_name + form_date ── */
        $groups = array();
        foreach ($all as $row) {
            // ── PHP-level safety net: skip excluded QR codes ──
            $qr = trim($row['qr_code']);
            if (
                stripos($qr, 'Supplementary') !== false ||
                stripos($qr, 'NA')            !== false ||
                stripos($qr, 'N/A')           !== false
            ) {
                continue;
            }

            $key = strtolower($qr)
                 . '||' . strtolower(trim($row['patient_name']))
                 . '||' . $row['form_date'];
            $groups[$key][] = $row;
        }

        /* ── Keep only groups with more than 1 record ── */
        $duplicates = array();
        foreach ($groups as $key => $rows) {
            if (count($rows) > 1) {
                foreach ($rows as $r) {
                    $duplicates[] = $r;
                }
            }
        }
        
        return $duplicates;
    }
    
}
