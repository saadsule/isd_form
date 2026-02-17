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

}
