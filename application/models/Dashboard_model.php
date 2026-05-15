<?php
class Dashboard_model extends CI_Model {

    /* ================= TOTALS ================= */

    public function child_total()
    {
        return $this->db->count_all('child_health_master');
    }

    public function opd_total()
    {
        return $this->db->count_all('opd_mnch_master');
    }

    public function total_patients()
    {
        return $this->child_total() + $this->opd_total();
    }



    /* ================= GENDER (FIXED) ================= */

    public function gender_combined()
    {
        // 🔥 IMPORTANT:
        // Replace 11 with your ACTUAL gender question_id from questions table

        $gender_question_id = 11;

        $sql = "
            SELECT gender, SUM(total) total
            FROM (

                /* CHILD */
                SELECT gender, COUNT(*) total
                FROM child_health_master
                WHERE gender IS NOT NULL
                GROUP BY gender

                UNION ALL

                /* OPD — FROM DETAIL */
                SELECT answer AS gender, COUNT(*) total
                FROM opd_mnch_detail
                WHERE question_id = {$gender_question_id}
                AND answer <> ''
                GROUP BY answer

            ) x
            GROUP BY gender
            ORDER BY total DESC
        ";

        return $this->db->query($sql)->result();
    }



    /* ================= MONTHLY ================= */

    public function monthly_comparison()
    {
        $sql = "
        SELECT month,
               SUM(child) child,
               SUM(opd) opd
        FROM (

            SELECT DATE_FORMAT(form_date,'%Y-%m') month,
                   COUNT(*) child,
                   0 opd
            FROM child_health_master
            GROUP BY month

            UNION ALL

            SELECT DATE_FORMAT(form_date,'%Y-%m') month,
                   0 child,
                   COUNT(*) opd
            FROM opd_mnch_master
            GROUP BY month

        ) x
        GROUP BY month
        ORDER BY month ASC
        ";

        return $this->db->query($sql)->result();
    }



    /* ================= DISTRICT ================= */

    public function district_comparison()
    {
        $sql = "
        SELECT district,
               SUM(child) child,
               SUM(opd) opd
        FROM (

            SELECT district, COUNT(*) child, 0 opd
            FROM child_health_master
            GROUP BY district

            UNION ALL

            SELECT district, 0 child, COUNT(*) opd
            FROM opd_mnch_master
            GROUP BY district

        ) x
        GROUP BY district
        ORDER BY (child + opd) DESC
        LIMIT 10
        ";

        return $this->db->query($sql)->result();
    }



    /* ================= CLIENT TYPE ================= */

    public function client_type_combined()
    {
        $sql = "
        SELECT client_type, SUM(total) total
        FROM (

            SELECT client_type, COUNT(*) total
            FROM child_health_master
            GROUP BY client_type

            UNION ALL

            SELECT client_type, COUNT(*) total
            FROM opd_mnch_master
            GROUP BY client_type

        ) x
        GROUP BY client_type
        ORDER BY total DESC
        ";

        return $this->db->query($sql)->result();
    }



    /* ================= TOP DIAGNOSIS ================= */

    public function top_diagnosis()
    {
        return $this->db
            ->select('answer, COUNT(*) total')
            ->from('opd_mnch_detail')
            ->where('option_id IS NOT NULL', NULL, FALSE)
            ->where('answer <>', '')
            ->group_by('answer')
            ->order_by('total','DESC')
            ->limit(8)
            ->get()
            ->result();
    }
    
    public function get_facilities_by_district($district_name)
    {
        $this->db->select('
            f.facility_name,
            f.latitude,
            f.longitude,
            u.uc as uc_name,
            f.isd_status,
            f.requirement_type,
            f.catchment_population,
            f.previuos_health_facility
        ');

        $this->db->from('facilities f');
        $this->db->join('districts dist', 'dist.district_id = f.district_id', 'left');
        $this->db->join('uc u', 'u.pk_id = f.uc_id', 'left'); // ✅ UC join

        $this->db->where('dist.district_name', $district_name);

        $query = $this->db->get();
        $facilities = $query->result();

        // Digitized focus logic
        foreach ($facilities as $facility) {
            $facility->digitized_focus = ($facility->requirement_type === 'Functional') ? 'Yes' : 'No';
        }

        return $facilities;
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

    public function get_facility_summary_by_district($district_name)
    {
        $this->db->select('
            COUNT(id) as total_facilities,
            SUM(catchment_population) as total_population
        ');
        $this->db->from('facilities');
        $this->db->where('district_id', '94');

        return $this->db->get()->row();
    }
    
    public function get_today_stats()
{
    // OPD total
    $opd_total = $this->db->where('visit_type', 'OPD')
                           ->count_all_results('opd_mnch_master');

    // MNCH total
    $mnch_total = $this->db->where('visit_type', 'MNCH')
                            ->count_all_results('opd_mnch_master');

    // Child Health Outreach
    $ch_outreach = $this->db->where('visit_type', 'Outreach')
                             ->count_all_results('child_health_master');

    // Child Health Fixed Site
    $ch_fixed = $this->db->where('visit_type', 'Fixed Site')
                          ->count_all_results('child_health_master');

    // Child Health total
    $ch_total = $ch_outreach + $ch_fixed;

    // Vaccinated children (distinct QR codes with vaccination detail)
    $this->db->select('COUNT(DISTINCT m.qr_code) as total');
    $this->db->from('child_health_master m');
    $this->db->join('child_health_detail d',
        'm.master_id = d.master_id AND d.question_id IN (5,6,7)', 'inner');
    $vaccinated       = $this->db->get()->row();
    $vaccinated_total = $vaccinated ? (int)$vaccinated->total : 0;

    // Age group counts — child health
    $this->db->select('age_group, COUNT(*) as total');
    $this->db->from('child_health_master');
    $this->db->where('age_group !=', '');
    $this->db->group_by('age_group');
    $age_rows   = $this->db->get()->result_array();
    $age_groups = array();
    foreach ($age_rows as $row) {
        $age_groups[$row['age_group']] = (int)$row['total'];
    }

    return array(
        'opd_total'    => $opd_total,
        'mnch_total'   => $mnch_total,
        'ch_total'     => $ch_total,
        'ch_outreach'  => $ch_outreach,
        'ch_fixed'     => $ch_fixed,
        'vaccinated'   => $vaccinated_total,
        'age_groups'   => $age_groups
    );
}
    
    // Get outreach graph data
    public function get_outreach_graph($filters)
    {
        $this->db->select("
            DATE(form_date) as form_date,
            gender,
            age_group,
            client_type,
            visit_type,
            COUNT(*) as total
        ");
        $this->db->from('child_health_master');

        // UC filter
        if (!empty($filters['uc'])) {
            $this->db->where_in('uc', $filters['uc']);
        }

        // Date range filter
        if (!empty($filters['start'])) {
            $this->db->where('DATE(form_date) >=', $filters['start']);
        }

        if (!empty($filters['end'])) {
            $this->db->where('DATE(form_date) <=', $filters['end']);
        }

        // Gender filter
        if (!empty($filters['gender'])) {
            $this->db->where_in('gender', $filters['gender']);
        }

        // Age group filter
        if (!empty($filters['age_group'])) {
            $this->db->where_in('age_group', $filters['age_group']);
        }

        // Client type filter
        if (!empty($filters['client_type'])) {
            $this->db->where_in('client_type', $filters['client_type']);
        }

        // Visit type filter (only Outreach)
        $this->db->where('visit_type', 'Outreach');
        
        // 🔥 DAILY GROUPING
        $this->db->group_by([
            "DATE(form_date)",
            "gender",
            "age_group",
            "client_type"
        ]);

        $this->db->order_by('DATE(form_date)', 'ASC');

        $query = $this->db->get();
        return $query->result();
    }
    
    public function get_vaccination_history_graph($filters)
    {
        $this->db->select("
            d.question_id,
            DATE(m.form_date) as form_date,
            d.option_id,
            d.answer,
            COUNT(d.detail_id) as total,
            m.gender,
            m.age_group
        ");

        $this->db->from('child_health_detail d');
        $this->db->join('questions q', 'q.question_id = d.question_id');
        $this->db->join('child_health_master m', 'm.master_id = d.master_id');

        $this->db->where_in('d.question_id', [1,2,3,4]);

        if (!empty($filters['vaccination_history'])) {
            $this->db->where_in('d.option_id', $filters['vaccination_history']);
        }

        if (!empty($filters['uc'])) {
            $this->db->where_in('m.uc', $filters['uc']);
        }

        if (!empty($filters['start'])) {
            $this->db->where('DATE(m.form_date) >=', $filters['start']);
        }

        if (!empty($filters['end'])) {
            $this->db->where('DATE(m.form_date) <=', $filters['end']);
        }
        
        // Gender filter
        if (!empty($filters['gender'])) {
            $this->db->where_in('gender', $filters['gender']);
        }

        // Age group filter
        if (!empty($filters['age_group'])) {
            $this->db->where_in('age_group', $filters['age_group']);
        }

        $this->db->where('m.visit_type', 'Outreach');

        $this->db->group_by([
            'd.question_id',
            'd.option_id',
            'd.answer',
            'DATE(m.form_date)',
            'm.gender',
            'm.age_group'
        ]);

        $this->db->order_by('d.option_id', 'ASC');
        $this->db->order_by('DATE(m.form_date)', 'ASC');
        
        // Get the SQL query string without executing
//    $query_string = $this->db->get_compiled_select();
//    echo $query_string;
        
        return $this->db->get()->result();
    }
    
    public function get_antigen_under1_graph($filters)
    {
        $this->db->select("
            DATE(m.form_date) as form_date,
            d.option_id,
            COUNT(*) as total,
            m.gender,
            m.age_group
        ");

        $this->db->from('child_health_detail d');
        $this->db->join('child_health_master m', 'm.master_id = d.master_id');

        // ✅ Question ID 5 (Antigens < 1 Year)
        $this->db->where('d.question_id', 5);

        // Selected antigen filters
        if (!empty($filters['antigens'])) {
            $this->db->where_in('d.option_id', $filters['antigens']);
        }

        // UC filter
        if (!empty($filters['uc'])) {
            $this->db->where_in('m.uc', $filters['uc']);
        }

        // Date filter
        if (!empty($filters['start'])) {
            $this->db->where('DATE(m.form_date) >=', $filters['start']);
        }

        if (!empty($filters['end'])) {
            $this->db->where('DATE(m.form_date) <=', $filters['end']);
        }
        
        // Gender filter
        if (!empty($filters['gender'])) {
            $this->db->where_in('gender', $filters['gender']);
        }

        // Age group filter
        if (!empty($filters['age_group'])) {
            $this->db->where_in('age_group', $filters['age_group']);
        }

        // Outreach only
        $this->db->where('m.visit_type', 'Outreach');

        $this->db->group_by([
            'DATE(m.form_date)',
            'd.option_id',
            'm.gender',
            'm.age_group'
        ]);

        $this->db->order_by('DATE(m.form_date)', 'ASC');

        return $this->db->get()->result();
    }
    
    public function get_antigen_1_2_graph($filters)
    {
        $this->db->select("
            DATE(m.form_date) as form_date,
            d.option_id,
            COUNT(*) as total,
            m.gender,
            m.age_group
        ");

        $this->db->from('child_health_detail d');
        $this->db->join('child_health_master m', 'm.master_id = d.master_id');

        // ✅ Question ID 6 (1–2 Years)
        $this->db->where('d.question_id', 6);

        // Selected antigen filters
        if (!empty($filters['antigens_1_2_years'])) {
            $this->db->where_in('d.option_id', $filters['antigens_1_2_years']);
        }

        // UC filter
        if (!empty($filters['uc'])) {
            $this->db->where_in('m.uc', $filters['uc']);
        }

        // Date filter
        if (!empty($filters['start'])) {
            $this->db->where('DATE(m.form_date) >=', $filters['start']);
        }

        if (!empty($filters['end'])) {
            $this->db->where('DATE(m.form_date) <=', $filters['end']);
        }
        
        // Gender filter
        if (!empty($filters['gender'])) {
            $this->db->where_in('gender', $filters['gender']);
        }

        // Age group filter
        if (!empty($filters['age_group'])) {
            $this->db->where_in('age_group', $filters['age_group']);
        }

        // Outreach only
        $this->db->where('m.visit_type', 'Outreach');

        $this->db->group_by([
            'DATE(m.form_date)',
            'd.option_id',
            'm.gender',
            'm.age_group'
        ]);

        $this->db->order_by('DATE(m.form_date)', 'ASC');

        return $this->db->get()->result();
    }
    
    public function get_antigen_2_5_graph($filters)
    {
        $this->db->select("
            DATE(m.form_date) as form_date,
            d.option_id,
            COUNT(*) as total,
            m.gender,
            m.age_group
        ");

        $this->db->from('child_health_detail d');
        $this->db->join('child_health_master m', 'm.master_id = d.master_id');

        // ✅ Question ID 7 (2–5 Years)
        $this->db->where('d.question_id', 7);

        // Selected antigen filters
        if (!empty($filters['antigens_2_5_years'])) {
            $this->db->where_in('d.option_id', $filters['antigens_2_5_years']);
        }

        // UC filter
        if (!empty($filters['uc'])) {
            $this->db->where_in('m.uc', $filters['uc']);
        }

        // Date filter
        if (!empty($filters['start'])) {
            $this->db->where('DATE(m.form_date) >=', $filters['start']);
        }

        if (!empty($filters['end'])) {
            $this->db->where('DATE(m.form_date) <=', $filters['end']);
        }
        
        // Gender filter
        if (!empty($filters['gender'])) {
            $this->db->where_in('gender', $filters['gender']);
        }

        // Age group filter
        if (!empty($filters['age_group'])) {
            $this->db->where_in('age_group', $filters['age_group']);
        }

        // Outreach only
        $this->db->where('m.visit_type', 'Outreach');

        $this->db->group_by([
            'DATE(m.form_date)',
            'd.option_id',
            'm.gender',
            'm.age_group'
        ]);

        $this->db->order_by('DATE(m.form_date)', 'ASC');

        return $this->db->get()->result();
    }
    
    public function get_summary_cards($filters)
    {
        $this->db->select('
            COUNT(DISTINCT uc_id) AS total_ucs,
            SUM(catchment_population) AS catchment_population
        ');
        $this->db->from('facilities');

        if (!empty($filters['uc'])) {
            $this->db->where_in('uc_id', $filters['uc']);
        }

        $result = $this->db->get()->row();

        return [
            'catchment_population' => isset($result->catchment_population) ? $result->catchment_population : 0,
            'total_ucs'            => isset($result->total_ucs) ? $result->total_ucs : 0,
            'date_range'           => (!empty($filters['start']) ? $filters['start'] : '-') 
                                    . ' to ' 
                                    . (!empty($filters['end']) ? $filters['end'] : '-')
        ];
    }

    public function get_visit_type_counts($filters)
    {
        $this->db->select('visit_type, COUNT(master_id) as total');
        $this->db->from('child_health_master');

        if (!empty($filters['uc'])) {
            $this->db->where_in('uc', $filters['uc']);
        }

        if (!empty($filters['start'])) {
            $this->db->where('DATE(form_date) >=', $filters['start']);
        }

        if (!empty($filters['end'])) {
            $this->db->where('DATE(form_date) <=', $filters['end']);
        }
        
        if (!empty($filters['visit_type'])) {
            $this->db->where_in('visit_type', $filters['visit_type']);
        }

        $this->db->group_by('visit_type');

        $result = $this->db->get()->result();

        $outreach = 0;
        $fixed    = 0;

        foreach($result as $row){
            if($row->visit_type == 'Outreach') $outreach = $row->total;
            if($row->visit_type == 'Fixed Site') $fixed = $row->total;
        }

        return [
            'Outreach' => $outreach,
            'Fixed'    => $fixed
        ];
    }
    
    public function get_client_type_counts($filters)
    {
        $this->db->select('client_type, COUNT(master_id) as total');
        $this->db->from('child_health_master');

        // Apply filters
        if (!empty($filters['uc'])) {
            $this->db->where_in('uc', $filters['uc']);
        }

        if (!empty($filters['start'])) {
            $this->db->where('DATE(form_date) >=', $filters['start']);
        }

        if (!empty($filters['end'])) {
            $this->db->where('DATE(form_date) <=', $filters['end']);
        }

        if (!empty($filters['visit_type'])) {
            $this->db->where_in('visit_type', $filters['visit_type']);
        }
        
        if (!empty($filters['age_group'])) {
            $this->db->where_in('age_group', $filters['age_group']);
        }

        $this->db->group_by('client_type');
        $result = $this->db->get()->result();

        // Initialize counts
        $newClient = 0;
        $followUp = 0;

        foreach ($result as $row) {
            if (strtolower($row->client_type) == 'new') $newClient = $row->total;
            if (strtolower($row->client_type) == 'followup') $followUp = $row->total;
        }

        return [
            'New'      => $newClient,
            'Followup' => $followUp
        ];
    }
    
    public function get_gender_counts($filters)
    {
        $this->db->select('gender, COUNT(master_id) as total');
        $this->db->from('child_health_master');

        // Apply filters
        if (!empty($filters['uc'])) {
            $this->db->where_in('uc', $filters['uc']);
        }

        if (!empty($filters['start'])) {
            $this->db->where('DATE(form_date) >=', $filters['start']);
        }

        if (!empty($filters['end'])) {
            $this->db->where('DATE(form_date) <=', $filters['end']);
        }

        if (!empty($filters['visit_type'])) {
            $this->db->where_in('visit_type', $filters['visit_type']);
        }
        
        if (!empty($filters['age_group'])) {
            $this->db->where_in('age_group', $filters['age_group']);
        }

        $this->db->group_by('gender');
        $result = $this->db->get()->result();

        // Initialize counts
        $male = 0;
        $female = 0;
        $other = 0;

        foreach ($result as $row) {
            $g = strtolower($row->gender);
            if ($g == 'male') $male = $row->total;
            elseif ($g == 'female') $female = $row->total;
            else $other += $row->total; // For any other or unknown gender
        }

        return [
            'Male'   => $male,
            'Female' => $female,
            'Other'  => $other
        ];
    }
    
    public function get_age_group_counts($filters)
    {
        $this->db->select('age_group, COUNT(master_id) as total');
        $this->db->from('child_health_master');

        // Apply filters
        if (!empty($filters['uc'])) {
            $this->db->where_in('uc', $filters['uc']);
        }
        if (!empty($filters['start'])) {
            $this->db->where('DATE(form_date) >=', $filters['start']);
        }
        if (!empty($filters['end'])) {
            $this->db->where('DATE(form_date) <=', $filters['end']);
        }
        if (!empty($filters['visit_type'])) {
            $this->db->where_in('visit_type', $filters['visit_type']);
        }
        
        if (!empty($filters['age_group'])) {
            $this->db->where_in('age_group', $filters['age_group']);
        }

        $this->db->group_by('age_group');
        $result = $this->db->get()->result();

        // Initialize default age groups with 0
        $ageGroups = [];
        if (!empty($filters['age_group'])) {
            foreach ($filters['age_group'] as $ag) {
                $ageGroups[$ag] = 0;
            }
        } else {
            $ageGroups = [
                '<1 Year'    => 0,
                '1-2 Year'   => 0,
                '2-5 Year'   => 0,
                '5-15 Year'  => 0,
                '15-49 Year' => 0
            ];
        }

        foreach ($result as $row) {
            if (isset($ageGroups[$row->age_group])) {
                $ageGroups[$row->age_group] = $row->total;
            }
        }

        return $ageGroups;
    }
    
    public function get_q171_counts($filters)
    {
        $this->db->from('child_health_master m');
        $this->db->join('child_health_detail d', 'm.master_id = d.master_id AND d.question_id = 1', 'left');

        // Apply filters dynamically
        if (!empty($filters['uc'])) {
            $this->db->where_in('m.uc', $filters['uc']);
        }
        if (!empty($filters['start'])) {
            $this->db->where('DATE(m.form_date) >=', $filters['start']);
        }
        if (!empty($filters['end'])) {
            $this->db->where('DATE(m.form_date) <=', $filters['end']);
        }
        if (!empty($filters['visit_type'])) {
            $this->db->where_in('m.visit_type', $filters['visit_type']);
        }
        if (!empty($filters['age_group'])) {
            $this->db->where_in('m.age_group', $filters['age_group']);
        }
        
        // Select Yes / No / None counts
        $this->db->select("
            SUM(CASE WHEN d.answer = 'Yes' THEN 1 ELSE 0 END) AS Yes,
            SUM(CASE WHEN d.answer = 'No' THEN 1 ELSE 0 END) AS No,
            SUM(CASE WHEN d.answer IS NULL OR d.answer NOT IN ('Yes','No') THEN 1 ELSE 0 END) AS None
        ", false);

        // Build query but don't execute yet
        $query = $this->db->get_compiled_select();

        $result = $this->db->query($query)->row_array();
        return $result;
    }
    
    public function get_sunburst_q17($filters)
    {
        $this->db->from('child_health_master m');
        $this->db->join(
            'child_health_detail d',
            'm.master_id = d.master_id AND d.question_id IN (2,3,4)',
            'left'
        );

        if (!empty($filters['uc'])) $this->db->where_in('m.uc', $filters['uc']);
        if (!empty($filters['start'])) $this->db->where('DATE(m.form_date) >=', $filters['start']);
        if (!empty($filters['end'])) $this->db->where('DATE(m.form_date) <=', $filters['end']);
        if (!empty($filters['visit_type'])) $this->db->where_in('m.visit_type', $filters['visit_type']);
        if (!empty($filters['age_group'])) {
            $this->db->where_in('m.age_group', $filters['age_group']);
        }

        $this->db->select("
            SUM(CASE WHEN d.question_id=2 AND d.answer='Yes' THEN 1 ELSE 0 END) AS yes_count,
            SUM(CASE WHEN d.question_id=2 AND d.answer='No' THEN 1 ELSE 0 END) AS no_count,

            SUM(CASE WHEN d.question_id=3 AND d.answer='17.2.1.  Fully immunized as per Age' THEN 1 ELSE 0 END) AS fully_immunized,
            SUM(CASE WHEN d.question_id=3 AND d.answer='17.2.2.  Vaccine not due' THEN 1 ELSE 0 END) AS vaccine_not_due,
            SUM(CASE WHEN d.question_id=3 AND d.answer='17.2.3.  Child is unwell' THEN 1 ELSE 0 END) AS child_unwell,
            SUM(CASE WHEN d.question_id=3 AND d.answer='17.2.4.  Refusal' THEN 1 ELSE 0 END) AS refusal_reason,

            SUM(CASE WHEN d.question_id=4 AND d.answer='Demand Refusal' THEN 1 ELSE 0 END) AS demand_refusal,
            SUM(CASE WHEN d.question_id=4 AND d.answer='Misconception Refusal' THEN 1 ELSE 0 END) AS misconception_refusal,
            SUM(CASE WHEN d.question_id=4 AND d.answer='Religious Refusal' THEN 1 ELSE 0 END) AS religious_refusal
        ", false);

        $r = $this->db->get()->row_array();

        $safe = function($v) {
            return (int)$v;
        };

        $yesCount = $safe($r['yes_count']);
        $noCount  = $safe($r['no_count']);
        
        $q3Total =
            $safe($r['fully_immunized']) +
            $safe($r['vaccine_not_due']) +
            $safe($r['child_unwell']) +
            $safe($r['refusal_reason']);

        $q3_na = max(0, $noCount - $q3Total);

        $refusalTotal = $safe($r['refusal_reason']);

        $q4Total =
            $safe($r['demand_refusal']) +
            $safe($r['misconception_refusal']) +
            $safe($r['religious_refusal']);

        $q4_na = max(0, $refusalTotal - $q4Total);

        $sunburst = [];

        // Root: Not Vaccinated
        $sunburst[] = [
            'id'     => 'no',
            'parent' => '',
            'name'   => 'Not Vaccinated',
            'value'  => $noCount,
            'color'  => '#f7a35c'
        ];

        // Q3 Options
        $sunburst[] = [
            'parent' => 'no',
            'name'   => 'Fully Immunized',
            'value'  => $safe($r['fully_immunized']),
            'color'  => '#7cb5ec'
        ];

        $sunburst[] = [
            'parent' => 'no',
            'name'   => 'Vaccine Not Due',
            'value'  => $safe($r['vaccine_not_due']),
            'color'  => '#90ed7d'
        ];

        $sunburst[] = [
            'parent' => 'no',
            'name'   => 'Child Unwell',
            'value'  => $safe($r['child_unwell']),
            'color'  => '#8085e9'
        ];

        if ($refusalTotal > 0) {

            $sunburst[] = [
                'id'     => 'refusal',
                'parent' => 'no',
                'name'   => 'Refusal',
                'value'  => $refusalTotal,
                'color'  => '#f15c80'
            ];

            $sunburst[] = [
                'id'     => 'yes',
                'parent' => 'refusal',
                'name'   => 'Yes',
                'value'  => $q4Total,
                'color'  => '#DE4436'
            ];
            
            $sunburst[] = [
                'parent' => 'yes',
                'name'   => 'Demand Refusal',
                'value'  => $safe($r['demand_refusal']),
                'color'  => '#f49c42'
            ];

            $sunburst[] = [
                'parent' => 'yes',
                'name'   => 'Misconception Refusal',
                'value'  => $safe($r['misconception_refusal']),
                'color'  => '#f7a35c'
            ];

            $sunburst[] = [
                'parent' => 'yes',
                'name'   => 'Religious Refusal',
                'value'  => $safe($r['religious_refusal']),
                'color'  => '#434348'
            ];

            // N/A under Refusal
            if ($q4_na > 0) {
                $sunburst[] = [
                    'parent' => 'refusal',
                    'name'   => 'N/A',
                    'value'  => $q4_na,
                    'color'  => '#cccccc'
                ];
            }
        }
        
        if ($q3_na > 0) {
            $sunburst[] = [
                'parent' => 'no',
                'name'   => 'N/A',
                'value'  => $q3_na,
                'color'  => '#cccccc'
            ];
        }

        return [
            'sunburst'  => $sunburst,
            'yes_count' => $yesCount,
            'no_count'  => $noCount
        ];
    }
    
    public function get_antigen_heatmap($filters)
    {
        // ── Step 1: Load ALL options for Q5, Q6, Q7 in correct order ─────────
        $all_options = $this->db->query("
            SELECT question_id, option_id, option_text, option_order
            FROM question_options
            WHERE question_id IN (5, 6, 7)
            ORDER BY question_id ASC, option_order ASC
        ")->result_array();

        // Question labels
        $questions = array(
            5 => 'Antigens < 1 Year',
            6 => 'Antigens 1-2 Year',
            7 => 'Antigens 2-5 Year',
        );
        $questionList = array_values($questions);

        // first order Q5 then Q6 then Q7
        $antigenOrder = array();
        $sortKey = 1;
        foreach ([5, 6, 7] as $qid) {
            foreach ($all_options as $opt) {
                if ((int)$opt['question_id'] !== $qid) continue;
                $name = trim($opt['option_text']);
                if ($name === '' || isset($antigenOrder[$name])) continue;
                $antigenOrder[$name] = $sortKey++;
            }
        }
        asort($antigenOrder);
        $antigenList = array_keys($antigenOrder);

        // ── Step 2: Build dataMap — initialize all cells to 0 ────────────────
        $dataMap = array();
        foreach ($questions as $qLabel) {
            $dataMap[$qLabel] = array();
            foreach ($antigenList as $antigen) {
                $dataMap[$qLabel][$antigen] = 0;
            }
        }

        // ── Step 3: Query actual counts using option_text ─────────────────────
        $this->db->select("
            d.question_id,
            qo.option_text AS antigen,
            COUNT(*) AS total
        ", false);
        $this->db->from('child_health_master m');
        $this->db->join(
            'child_health_detail d',
            'm.master_id = d.master_id AND d.question_id IN (5,6,7)',
            'left'
        );
        $this->db->join(
            'question_options qo',
            'qo.option_id = d.option_id AND qo.question_id = d.question_id',
            'left'
        );

        if (!empty($filters['uc'])) {
            $this->db->where_in('m.uc', $filters['uc']);
        }
        if (!empty($filters['start'])) {
            $this->db->where('DATE(m.form_date) >=', $filters['start']);
        }
        if (!empty($filters['end'])) {
            $this->db->where('DATE(m.form_date) <=', $filters['end']);
        }
        if (!empty($filters['visit_type'])) {
            $this->db->where_in('m.visit_type', $filters['visit_type']);
        }
        if (!empty($filters['age_group'])) {
            $this->db->where_in('m.age_group', $filters['age_group']);
        }

        $this->db->where('d.option_id IS NOT NULL');
        $this->db->group_by(array('d.question_id', 'qo.option_text'));

        $result = $this->db->get()->result_array();

        // ── Step 4: Fill dataMap with actual counts ───────────────────────────
        foreach ($result as $row) {
            $qid    = (int)$row['question_id'];
            $antigen = trim($row['antigen']);
            $total   = (int)$row['total'];

            if (!isset($questions[$qid])) continue;
            $qLabel = $questions[$qid];

            if (isset($dataMap[$qLabel][$antigen])) {
                $dataMap[$qLabel][$antigen] = $total;
            }
        }

        // ── Step 5: Build Highcharts data array ──────────────────────────────
        $data = array();
        foreach ($questionList as $y => $q) {
            foreach ($antigenList as $x => $antigen) {
                $value = isset($dataMap[$q][$antigen]) ? $dataMap[$q][$antigen] : 0;
                $data[] = array($x, $y, $value);
            }
        }

        return array(
            'categoriesX' => $antigenList,
            'categoriesY' => $questionList,
            'data'        => $data,
        );
    }

    public function get_q21_counts($filters)
    {
        $this->db->from('child_health_master m');
        $this->db->join(
            'child_health_detail d',
            'm.master_id = d.master_id AND d.question_id = 8',
            'left'
        );

        // ✅ Filters
        if (!empty($filters['uc'])) {
            $this->db->where_in('m.uc', $filters['uc']);
        }

        if (!empty($filters['start'])) {
            $this->db->where('DATE(m.form_date) >=', $filters['start']);
        }

        if (!empty($filters['end'])) {
            $this->db->where('DATE(m.form_date) <=', $filters['end']);
        }

        if (!empty($filters['visit_type'])) {
            $this->db->where_in('m.visit_type', $filters['visit_type']);
        }
        
        if (!empty($filters['age_group'])) {
            $this->db->where_in('m.age_group', $filters['age_group']);
        }

        $this->db->select("
            d.answer,
            COUNT(d.answer) as total
        ", false);

        $this->db->group_by('d.answer');

        $result = $this->db->get()->result_array();

        $data = [];
        $totalRecords = 0;

        foreach ($result as $row) {

            if ($row['answer'] == NULL || $row['answer'] == '') {
                continue;
            }

            $data[] = [
                'name' => $row['answer'],
                'y'    => (int)$row['total']
            ];

            $totalRecords += (int)$row['total'];
        }

        // 🔹 Calculate None (if no answer selected)
        $this->db->reset_query();
        $this->db->from('child_health_master m');

        if (!empty($filters['uc'])) {
            $this->db->where_in('m.uc', $filters['uc']);
        }

        if (!empty($filters['start'])) {
            $this->db->where('DATE(m.form_date) >=', $filters['start']);
        }

        if (!empty($filters['end'])) {
            $this->db->where('DATE(m.form_date) <=', $filters['end']);
        }

        if (!empty($filters['visit_type'])) {
            $this->db->where_in('m.visit_type', $filters['visit_type']);
        }
        
        if (!empty($filters['age_group'])) {
            $this->db->where_in('m.age_group', $filters['age_group']);
        }

        $totalForms = $this->db->count_all_results();

        $noneCount = $totalForms - $totalRecords;

        if ($noneCount > 0) {
            $data[] = [
                'name' => 'N/A',
                'y'    => $noneCount
            ];
        }

        return $data;
    }
    
    public function get_q22_counts($filters)
    {
        $this->db->from('child_health_master m');
        $this->db->join(
            'child_health_detail d',
            'm.master_id = d.master_id AND d.question_id = 9',
            'left'
        );

        // ✅ Filters
        if (!empty($filters['uc'])) {
            $this->db->where_in('m.uc', $filters['uc']);
        }

        if (!empty($filters['start'])) {
            $this->db->where('DATE(m.form_date) >=', $filters['start']);
        }

        if (!empty($filters['end'])) {
            $this->db->where('DATE(m.form_date) <=', $filters['end']);
        }

        if (!empty($filters['visit_type'])) {
            $this->db->where_in('m.visit_type', $filters['visit_type']);
        }
        
        if (!empty($filters['age_group'])) {
            $this->db->where_in('m.age_group', $filters['age_group']);
        }

        $this->db->select("
            d.answer,
            COUNT(d.answer) as total
        ", false);

        $this->db->group_by('d.answer');

        $result = $this->db->get()->result_array();

        $data = [];
        $totalRecords = 0;

        foreach ($result as $row) {

            if ($row['answer'] == NULL || $row['answer'] == '') {
                continue;
            }

            $data[] = [
                'name' => $row['answer'],
                'y'    => (int)$row['total']
            ];

            $totalRecords += (int)$row['total'];
        }

        // 🔹 Calculate None (if no answer selected)
        $this->db->reset_query();
        $this->db->from('child_health_master m');

        if (!empty($filters['uc'])) {
            $this->db->where_in('m.uc', $filters['uc']);
        }

        if (!empty($filters['start'])) {
            $this->db->where('DATE(m.form_date) >=', $filters['start']);
        }

        if (!empty($filters['end'])) {
            $this->db->where('DATE(m.form_date) <=', $filters['end']);
        }

        if (!empty($filters['visit_type'])) {
            $this->db->where_in('m.visit_type', $filters['visit_type']);
        }
        
        if (!empty($filters['age_group'])) {
            $this->db->where_in('m.age_group', $filters['age_group']);
        }

        $totalForms = $this->db->count_all_results();

        $noneCount = $totalForms - $totalRecords;

        if ($noneCount > 0) {
            $data[] = [
                'name' => 'N/A',
                'y'    => $noneCount
            ];
        }

        return $data;
    }
    
    public function get_q23_counts($filters)
    {
        $this->db->from('child_health_master m');
        $this->db->join(
            'child_health_detail d',
            'm.master_id = d.master_id AND d.question_id = 10',
            'left'
        );

        // ✅ Filters
        if (!empty($filters['uc'])) {
            $this->db->where_in('m.uc', $filters['uc']);
        }

        if (!empty($filters['start'])) {
            $this->db->where('DATE(m.form_date) >=', $filters['start']);
        }

        if (!empty($filters['end'])) {
            $this->db->where('DATE(m.form_date) <=', $filters['end']);
        }

        if (!empty($filters['visit_type'])) {
            $this->db->where_in('m.visit_type', $filters['visit_type']);
        }
        
        if (!empty($filters['age_group'])) {
            $this->db->where_in('m.age_group', $filters['age_group']);
        }

        $this->db->select("
            d.answer,
            COUNT(d.answer) as total
        ", false);

        $this->db->group_by('d.answer');

        $result = $this->db->get()->result_array();

        $data = [];
        $totalRecords = 0;

        foreach ($result as $row) {

            if ($row['answer'] == NULL || $row['answer'] == '') {
                continue;
            }

            $data[] = [
                'name' => $row['answer'],
                'y'    => (int)$row['total']
            ];

            $totalRecords += (int)$row['total'];
        }

        // 🔹 Calculate None (if no answer selected)
        $this->db->reset_query();
        $this->db->from('child_health_master m');

        if (!empty($filters['uc'])) {
            $this->db->where_in('m.uc', $filters['uc']);
        }

        if (!empty($filters['start'])) {
            $this->db->where('DATE(m.form_date) >=', $filters['start']);
        }

        if (!empty($filters['end'])) {
            $this->db->where('DATE(m.form_date) <=', $filters['end']);
        }

        if (!empty($filters['visit_type'])) {
            $this->db->where_in('m.visit_type', $filters['visit_type']);
        }
        
        if (!empty($filters['age_group'])) {
            $this->db->where_in('m.age_group', $filters['age_group']);
        }

        $totalForms = $this->db->count_all_results();

        $noneCount = $totalForms - $totalRecords;

        if ($noneCount > 0) {
            $data[] = [
                'name' => 'N/A',
                'y'    => $noneCount
            ];
        }

        return $data;
    }
    
    public function get_q24_counts($filters)
    {
        $this->db->from('child_health_master m');
        $this->db->join(
            'child_health_detail d',
            'm.master_id = d.master_id AND d.question_id = 11',
            'left'
        );

        // ✅ Filters
        if (!empty($filters['uc'])) {
            $this->db->where_in('m.uc', $filters['uc']);
        }

        if (!empty($filters['start'])) {
            $this->db->where('DATE(m.form_date) >=', $filters['start']);
        }

        if (!empty($filters['end'])) {
            $this->db->where('DATE(m.form_date) <=', $filters['end']);
        }

        if (!empty($filters['visit_type'])) {
            $this->db->where_in('m.visit_type', $filters['visit_type']);
        }
        
        if (!empty($filters['age_group'])) {
            $this->db->where_in('m.age_group', $filters['age_group']);
        }

        $this->db->select("
            d.answer,
            COUNT(d.answer) as total
        ", false);

        $this->db->group_by('d.answer');

        $result = $this->db->get()->result_array();

        $data = [];
        $totalRecords = 0;

        foreach ($result as $row) {

            if ($row['answer'] == NULL || $row['answer'] == '') {
                continue;
            }

            $data[] = [
                'name' => $row['answer'],
                'y'    => (int)$row['total']
            ];

            $totalRecords += (int)$row['total'];
        }

        // 🔹 Calculate None (if no answer selected)
        $this->db->reset_query();
        $this->db->from('child_health_master m');

        if (!empty($filters['uc'])) {
            $this->db->where_in('m.uc', $filters['uc']);
        }

        if (!empty($filters['start'])) {
            $this->db->where('DATE(m.form_date) >=', $filters['start']);
        }

        if (!empty($filters['end'])) {
            $this->db->where('DATE(m.form_date) <=', $filters['end']);
        }

        if (!empty($filters['visit_type'])) {
            $this->db->where_in('m.visit_type', $filters['visit_type']);
        }
        
        if (!empty($filters['age_group'])) {
            $this->db->where_in('m.age_group', $filters['age_group']);
        }

        $totalForms = $this->db->count_all_results();

        $noneCount = $totalForms - $totalRecords;

        if ($noneCount > 0) {
            $data[] = [
                'name' => 'N/A',
                'y'    => $noneCount
            ];
        }

        return $data;
    }
    
    public function get_gender_age_data($filters = [])
    {
        $age_groups = !empty($filters['age_group']) 
            ? $filters['age_group'] 
            : ['<1 Year','1-2 Year','2-5 Year','5-15 Year','15-49 Year'];

        $gender_age_data = [
            'Male'   => array_fill(0, count($age_groups), 0),
            'Female' => array_fill(0, count($age_groups), 0)
        ];

        $this->db->select("m.age_group, m.gender, COUNT(*) AS total", false);
        $this->db->from('child_health_master m');

        if (!empty($filters['uc'])) {
            $this->db->where_in('m.uc', $filters['uc']);
        }
        if (!empty($filters['start'])) {
            $this->db->where('DATE(m.form_date) >=', $filters['start']);
        }
        if (!empty($filters['end'])) {
            $this->db->where('DATE(m.form_date) <=', $filters['end']);
        }
        if (!empty($filters['visit_type'])) {
            $this->db->where_in('m.visit_type', $filters['visit_type']);
        }
        if (!empty($filters['age_group'])) {
            $this->db->where_in('m.age_group', $filters['age_group']);
        }

        $this->db->group_by(['m.age_group', 'm.gender']);
        $this->db->order_by("FIELD(m.age_group, '<1 Year','1-2 Year','2-5 Year','5-15 Year','15-49 Year')");

        $result = $this->db->get()->result_array();

        foreach ($result as $row) {
            if (in_array($row['age_group'], $age_groups)) {
                $index = array_search($row['age_group'], $age_groups);
                if (isset($gender_age_data[$row['gender']][$index])) {
                    $gender_age_data[$row['gender']][$index] = (int)$row['total'];
                }
            }
        }

        return [
            'age_groups' => $age_groups,
            'series'     => $gender_age_data
        ];
    }
    
    public function get_flame_q25($filters)
    {
        /*
        ==========================================
        1️⃣ Get Total Forms Count (IMPORTANT)
        ==========================================
        */

        $this->db->from('child_health_master m');

        if (!empty($filters['uc'])) $this->db->where_in('m.uc', $filters['uc']);
        if (!empty($filters['start'])) $this->db->where('DATE(m.form_date) >=', $filters['start']);
        if (!empty($filters['end'])) $this->db->where('DATE(m.form_date) <=', $filters['end']);
        if (!empty($filters['visit_type'])) $this->db->where_in('m.visit_type', $filters['visit_type']);
        if (!empty($filters['age_group'])) {
            $this->db->where_in('m.age_group', $filters['age_group']);
        }

        $totalForms = $this->db->count_all_results();


        /*
        ==========================================
        2️⃣ Get Answer Counts
        ==========================================
        */

        $this->db->from('child_health_master m');
        $this->db->join(
            'child_health_detail d',
            'm.master_id = d.master_id AND d.question_id IN (12,13,15,16,17)',
            'left'
        );

        if (!empty($filters['uc'])) $this->db->where_in('m.uc', $filters['uc']);
        if (!empty($filters['start'])) $this->db->where('DATE(m.form_date) >=', $filters['start']);
        if (!empty($filters['end'])) $this->db->where('DATE(m.form_date) <=', $filters['end']);
        if (!empty($filters['visit_type'])) $this->db->where_in('m.visit_type', $filters['visit_type']);
        if (!empty($filters['age_group'])) {
            $this->db->where_in('m.age_group', $filters['age_group']);
        }

        $this->db->select("
            SUM(CASE WHEN d.question_id = 12 AND d.answer = 'Yes' THEN 1 ELSE 0 END) AS q121_Yes,
            SUM(CASE WHEN d.question_id = 12 AND d.answer = 'No' THEN 1 ELSE 0 END) AS q121_No,

            SUM(CASE WHEN d.question_id = 13 AND d.answer = '1st' THEN 1 ELSE 0 END) AS q131_1st,
            SUM(CASE WHEN d.question_id = 13 AND d.answer = '2nd' THEN 1 ELSE 0 END) AS q131_2nd,
            SUM(CASE WHEN d.question_id = 13 AND d.answer = '3rd' THEN 1 ELSE 0 END) AS q131_3rd,
            SUM(CASE WHEN d.question_id = 13 AND d.answer = '4th' THEN 1 ELSE 0 END) AS q131_4th,
            SUM(CASE WHEN d.question_id = 13 AND d.answer = '5th' THEN 1 ELSE 0 END) AS q131_5th,

            SUM(CASE WHEN d.question_id = 15 AND d.answer = 'Yes' THEN 1 ELSE 0 END) AS q151_Yes,
            SUM(CASE WHEN d.question_id = 15 AND d.answer = 'No' THEN 1 ELSE 0 END) AS q151_No,

            SUM(CASE WHEN d.question_id = 16 AND d.answer = 'Yes' THEN 1 ELSE 0 END) AS q161_Yes,
            SUM(CASE WHEN d.question_id = 16 AND d.answer = 'No' THEN 1 ELSE 0 END) AS q161_No,

            SUM(CASE WHEN d.question_id = 17 AND d.answer = 'Yes' THEN 1 ELSE 0 END) AS q171_Yes,
            SUM(CASE WHEN d.question_id = 17 AND d.answer = 'No' THEN 1 ELSE 0 END) AS q171_No
        ", false);

        $result = $this->db->get()->row_array();


        /*
        ==========================================
        3️⃣ Map Questions
        ==========================================
        */

        $questions = [
            'q121' => 'Tetanus Vaccine Administered',
            'q131' => 'Dose Given',
            'q151' => 'Refused TT',
            'q161' => 'Complete TT Schedule',
            'q171' => 'Dose Not Due'
        ];

        $options = [
            'q121' => ['Yes','No'],
            'q131' => ['1st','2nd','3rd','4th','5th'],
            'q151' => ['Yes','No'],
            'q161' => ['Yes','No'],
            'q171' => ['Yes','No']
        ];

        $colors = [
            '#1f77b4',
            '#ff7f0e',
            '#2ca02c',
            '#d62728',
            '#9467bd',
            '#8c564b',
            '#e377c2',
            '#7f7f7f',
            '#bcbd22',
            '#17becf'
        ];

        $data = [];

        /*
        ==========================================
        4️⃣ Build Clean Output With REAL None
        ==========================================
        */

        foreach ($questions as $qkey => $qname) {

            $totalAnswered = 0;

            // Add selected options
            foreach ($options[$qkey] as $idx => $opt) {

                $key = $qkey . '_' . $opt;
                $value = isset($result[$key]) ? (int)$result[$key] : 0;

                $totalAnswered += $value;

                $data[] = [
                    'question' => $qname,
                    'name' => $opt,
                    'value' => $value,
                    'color' => $colors[$idx % count($colors)]
                ];
            }

            // Calculate NONE properly
            $noneCount = $totalForms - $totalAnswered;

            if ($noneCount > 0) {
                $data[] = [
                    'question' => $qname,
                    'name' => 'N/A',
                    'value' => $noneCount,
                    'color' => '#d3d3d3'
                ];
            }
        }

        return $data;
    }
    
//    For Fixed Site Dashboard
    // Get Fixed Site graph data
    public function get_fixedsite_graph($filters)
    {
        $this->db->select("
            DATE(form_date) as form_date,
            gender,
            age_group,
            client_type,
            visit_type,
            COUNT(*) as total
        ");
        $this->db->from('child_health_master');

        // UC filter
        if (!empty($filters['uc'])) {
            $this->db->where_in('uc', $filters['uc']);
        }

        // Date range filter
        if (!empty($filters['start'])) {
            $this->db->where('DATE(form_date) >=', $filters['start']);
        }

        if (!empty($filters['end'])) {
            $this->db->where('DATE(form_date) <=', $filters['end']);
        }

        // Gender filter
        if (!empty($filters['gender'])) {
            $this->db->where_in('gender', $filters['gender']);
        }

        // Age group filter
        if (!empty($filters['age_group'])) {
            $this->db->where_in('age_group', $filters['age_group']);
        }

        // Client type filter
        if (!empty($filters['client_type'])) {
            $this->db->where_in('client_type', $filters['client_type']);
        }

        // Visit type filter (only Fixed Site)
        $this->db->where('visit_type', 'Fixed Site');
        
        // 🔥 DAILY GROUPING
        $this->db->group_by([
            "DATE(form_date)",
            "gender",
            "age_group",
            "client_type"
        ]);

        $this->db->order_by('DATE(form_date)', 'ASC');

        $query = $this->db->get();
        return $query->result();
    }
    
    public function get_vaccination_history_graph_fixedsite($filters)
    {
        $this->db->select("
            d.question_id,
            DATE(m.form_date) as form_date,
            d.option_id,
            d.answer,
            COUNT(d.detail_id) as total,
            m.gender,
            m.age_group
        ");

        $this->db->from('child_health_detail d');
        $this->db->join('questions q', 'q.question_id = d.question_id');
        $this->db->join('child_health_master m', 'm.master_id = d.master_id');

        $this->db->where_in('d.question_id', [1,2,3,4]);

        if (!empty($filters['vaccination_history'])) {
            $this->db->where_in('d.option_id', $filters['vaccination_history']);
        }

        if (!empty($filters['uc'])) {
            $this->db->where_in('m.uc', $filters['uc']);
        }

        if (!empty($filters['start'])) {
            $this->db->where('DATE(m.form_date) >=', $filters['start']);
        }

        if (!empty($filters['end'])) {
            $this->db->where('DATE(m.form_date) <=', $filters['end']);
        }
        
        // Gender filter
        if (!empty($filters['gender'])) {
            $this->db->where_in('gender', $filters['gender']);
        }

        // Age group filter
        if (!empty($filters['age_group'])) {
            $this->db->where_in('age_group', $filters['age_group']);
        }

        $this->db->where('m.visit_type', 'Fixed Site');

        $this->db->group_by([
            'd.question_id',
            'd.option_id',
            'd.answer',
            'DATE(m.form_date)',
            'm.gender',
            'm.age_group'
        ]);

        $this->db->order_by('d.option_id', 'ASC');
        $this->db->order_by('DATE(m.form_date)', 'ASC');
        
        // Get the SQL query string without executing
//    $query_string = $this->db->get_compiled_select();
//    echo $query_string;
        
        return $this->db->get()->result();
    }
    
    public function get_antigen_under1_graph_fixedsite($filters)
    {
        $this->db->select("
            DATE(m.form_date) as form_date,
            d.option_id,
            COUNT(*) as total,
            m.gender,
            m.age_group
        ");

        $this->db->from('child_health_detail d');
        $this->db->join('child_health_master m', 'm.master_id = d.master_id');

        // ✅ Question ID 5 (Antigens < 1 Year)
        $this->db->where('d.question_id', 5);

        // Selected antigen filters
        if (!empty($filters['antigens'])) {
            $this->db->where_in('d.option_id', $filters['antigens']);
        }

        // UC filter
        if (!empty($filters['uc'])) {
            $this->db->where_in('m.uc', $filters['uc']);
        }

        // Date filter
        if (!empty($filters['start'])) {
            $this->db->where('DATE(m.form_date) >=', $filters['start']);
        }

        if (!empty($filters['end'])) {
            $this->db->where('DATE(m.form_date) <=', $filters['end']);
        }
        
        // Gender filter
        if (!empty($filters['gender'])) {
            $this->db->where_in('gender', $filters['gender']);
        }

        // Age group filter
        if (!empty($filters['age_group'])) {
            $this->db->where_in('age_group', $filters['age_group']);
        }

        // Fixed Site only
        $this->db->where('m.visit_type', 'Fixed Site');

        $this->db->group_by([
            'DATE(m.form_date)',
            'd.option_id',
            'm.gender',
            'm.age_group'
        ]);

        $this->db->order_by('DATE(m.form_date)', 'ASC');

        return $this->db->get()->result();
    }
    
    public function get_antigen_1_2_graph_fixedsite($filters)
    {
        $this->db->select("
            DATE(m.form_date) as form_date,
            d.option_id,
            COUNT(*) as total,
            m.gender,
            m.age_group
        ");

        $this->db->from('child_health_detail d');
        $this->db->join('child_health_master m', 'm.master_id = d.master_id');

        // ✅ Question ID 6 (1–2 Years)
        $this->db->where('d.question_id', 6);

        // Selected antigen filters
        if (!empty($filters['antigens_1_2_years'])) {
            $this->db->where_in('d.option_id', $filters['antigens_1_2_years']);
        }

        // UC filter
        if (!empty($filters['uc'])) {
            $this->db->where_in('m.uc', $filters['uc']);
        }

        // Date filter
        if (!empty($filters['start'])) {
            $this->db->where('DATE(m.form_date) >=', $filters['start']);
        }

        if (!empty($filters['end'])) {
            $this->db->where('DATE(m.form_date) <=', $filters['end']);
        }
        
        // Gender filter
        if (!empty($filters['gender'])) {
            $this->db->where_in('gender', $filters['gender']);
        }

        // Age group filter
        if (!empty($filters['age_group'])) {
            $this->db->where_in('age_group', $filters['age_group']);
        }

        // Fixed Site only
        $this->db->where('m.visit_type', 'Fixed Site');

        $this->db->group_by([
            'DATE(m.form_date)',
            'd.option_id',
            'm.gender',
            'm.age_group'
        ]);

        $this->db->order_by('DATE(m.form_date)', 'ASC');

        return $this->db->get()->result();
    }
    
    public function get_antigen_2_5_graph_fixedsite($filters)
    {
        $this->db->select("
            DATE(m.form_date) as form_date,
            d.option_id,
            COUNT(*) as total,
            m.gender,
            m.age_group
        ");

        $this->db->from('child_health_detail d');
        $this->db->join('child_health_master m', 'm.master_id = d.master_id');

        // ✅ Question ID 7 (2–5 Years)
        $this->db->where('d.question_id', 7);

        // Selected antigen filters
        if (!empty($filters['antigens_2_5_years'])) {
            $this->db->where_in('d.option_id', $filters['antigens_2_5_years']);
        }

        // UC filter
        if (!empty($filters['uc'])) {
            $this->db->where_in('m.uc', $filters['uc']);
        }

        // Date filter
        if (!empty($filters['start'])) {
            $this->db->where('DATE(m.form_date) >=', $filters['start']);
        }

        if (!empty($filters['end'])) {
            $this->db->where('DATE(m.form_date) <=', $filters['end']);
        }
        
        // Gender filter
        if (!empty($filters['gender'])) {
            $this->db->where_in('gender', $filters['gender']);
        }

        // Age group filter
        if (!empty($filters['age_group'])) {
            $this->db->where_in('age_group', $filters['age_group']);
        }

        // Fixed Site only
        $this->db->where('m.visit_type', 'Fixed Site');

        $this->db->group_by([
            'DATE(m.form_date)',
            'd.option_id',
            'm.gender',
            'm.age_group'
        ]);

        $this->db->order_by('DATE(m.form_date)', 'ASC');

        return $this->db->get()->result();
    }
    
    //OPD MNCH Main Dashboard
    public function get_visit_type_counts_opd($filters)
    {
        $this->db->select('visit_type, COUNT(id) as total');
        $this->db->from('opd_mnch_master');

        if (!empty($filters['uc'])) {
            $this->db->where_in('uc', $filters['uc']);
        }

        if (!empty($filters['start'])) {
            $this->db->where('DATE(form_date) >=', $filters['start']);
        }

        if (!empty($filters['end'])) {
            $this->db->where('DATE(form_date) <=', $filters['end']);
        }
        
        if (!empty($filters['visit_type'])) {
            $this->db->where_in('visit_type', $filters['visit_type']);
        }

        $this->db->group_by('visit_type');

        $result = $this->db->get()->result();

        $opd = 0;
        $mnch = 0;

        foreach($result as $row){
            if($row->visit_type == 'OPD') $opd = $row->total;
            if($row->visit_type == 'MNCH') $mnch = $row->total;
        }

        return [
            'opd' => $opd,
            'mnch'    => $mnch
        ];
    }
    
    public function get_client_type_counts_opd($filters)
    {
        $this->db->select('client_type, COUNT(id) as total');
        $this->db->from('opd_mnch_master');

        // Apply filters
        if (!empty($filters['uc'])) {
            $this->db->where_in('uc', $filters['uc']);
        }

        if (!empty($filters['start'])) {
            $this->db->where('DATE(form_date) >=', $filters['start']);
        }

        if (!empty($filters['end'])) {
            $this->db->where('DATE(form_date) <=', $filters['end']);
        }

        if (!empty($filters['visit_type'])) {
            $this->db->where_in('visit_type', $filters['visit_type']);
        }

        $this->db->group_by('client_type');
        $result = $this->db->get()->result();

        // Initialize counts
        $newClient = 0;
        $followUp = 0;

        foreach ($result as $row) {
            if (strtolower($row->client_type) == 'new') $newClient = $row->total;
            if (strtolower($row->client_type) == 'followup') $followUp = $row->total;
        }

        return [
            'New'      => $newClient,
            'Followup' => $followUp
        ];
    }
    
    public function get_disability_counts_opd($filters)
    {
        $this->db->select('disability, COUNT(id) as total');
        $this->db->from('opd_mnch_master');

        // Apply filters
        if (!empty($filters['uc'])) {
            $this->db->where_in('uc', $filters['uc']);
        }

        if (!empty($filters['start'])) {
            $this->db->where('DATE(form_date) >=', $filters['start']);
        }

        if (!empty($filters['end'])) {
            $this->db->where('DATE(form_date) <=', $filters['end']);
        }

        if (!empty($filters['visit_type'])) {
            $this->db->where_in('visit_type', $filters['visit_type']);
        }

        $this->db->group_by('disability');
        $result = $this->db->get()->result();

        // Initialize counts
        $yes = 0;
        $no  = 0;

        foreach ($result as $row) {
            if (strtolower($row->disability) == 'yes') $yes = $row->total;
            if (strtolower($row->disability) == 'no')  $no  = $row->total;
        }

        return [
            'Yes' => $yes,
            'No'  => $no
        ];
    }
    
    public function get_marital_type_counts_opd($filters)
    {
        $this->db->select('marital_status, COUNT(id) as total');
        $this->db->from('opd_mnch_master');

        // Apply filters
        if (!empty($filters['uc'])) {
            $this->db->where_in('uc', $filters['uc']);
        }

        if (!empty($filters['start'])) {
            $this->db->where('DATE(form_date) >=', $filters['start']);
        }

        if (!empty($filters['end'])) {
            $this->db->where('DATE(form_date) <=', $filters['end']);
        }

        if (!empty($filters['visit_type'])) {
            $this->db->where_in('visit_type', $filters['visit_type']);
        }

        $this->db->group_by('marital_status');
        $result = $this->db->get()->result();

        // Initialize counts
        $married   = 0;
        $unmarried = 0;

        foreach ($result as $row) {
            if (strtolower($row->marital_status) == 'married')   $married   = $row->total;
            if (strtolower($row->marital_status) == 'unmarried') $unmarried = $row->total;
        }

        return [
            'Married'   => $married,
            'Unmarried' => $unmarried
        ];
    }
    
    public function get_pregnancy_type_counts_opd($filters)
    {
        $this->db->select('pregnancy_status, COUNT(id) as total');
        $this->db->from('opd_mnch_master');

        // Apply filters
        if (!empty($filters['uc'])) {
            $this->db->where_in('uc', $filters['uc']);
        }

        if (!empty($filters['start'])) {
            $this->db->where('DATE(form_date) >=', $filters['start']);
        }

        if (!empty($filters['end'])) {
            $this->db->where('DATE(form_date) <=', $filters['end']);
        }

        if (!empty($filters['visit_type'])) {
            $this->db->where_in('visit_type', $filters['visit_type']);
        }

        $this->db->group_by('pregnancy_status');
        $result = $this->db->get()->result();

        // Initialize counts
        $pregnant     = 0;
        $nonPregnant  = 0;

        foreach ($result as $row) {
            if (strtolower($row->pregnancy_status) == 'pregnant')       $pregnant    = $row->total;
            if (strtolower($row->pregnancy_status) == 'non-pregnant')   $nonPregnant = $row->total;
        }

        return [
            'Pregnant'      => $pregnant,
            'Non-Pregnant'  => $nonPregnant
        ];
    }
    
    public function get_q181_counts($filters)
    {
        $this->db->from('opd_mnch_master m');
        $this->db->join('opd_mnch_detail d', 'm.id = d.master_id AND d.question_id = 18', 'left');

        // Apply filters dynamically
        if (!empty($filters['uc'])) {
            $this->db->where_in('m.uc', $filters['uc']);
        }
        if (!empty($filters['start'])) {
            $this->db->where('DATE(m.form_date) >=', $filters['start']);
        }
        if (!empty($filters['end'])) {
            $this->db->where('DATE(m.form_date) <=', $filters['end']);
        }
        if (!empty($filters['visit_type'])) {
            $this->db->where_in('m.visit_type', $filters['visit_type']);
        }

        // Count each option separately
        $this->db->select("
            SUM(CASE WHEN d.answer = '1st' THEN 1 ELSE 0 END) AS first_option,
            SUM(CASE WHEN d.answer = '2nd' THEN 1 ELSE 0 END) AS second_option,
            SUM(CASE WHEN d.answer = '3rd' THEN 1 ELSE 0 END) AS third_option
        ", false);

        // Execute query
        $query = $this->db->get();
        return $query->row_array();
    }
    
    public function get_q182_counts($filters)
    {
        $this->db->from('opd_mnch_master m');
        $this->db->join('opd_mnch_detail d', 'm.id = d.master_id AND d.question_id = 19', 'left');

        // Apply filters dynamically
        if (!empty($filters['uc'])) {
            $this->db->where_in('m.uc', $filters['uc']);
        }
        if (!empty($filters['start'])) {
            $this->db->where('DATE(m.form_date) >=', $filters['start']);
        }
        if (!empty($filters['end'])) {
            $this->db->where('DATE(m.form_date) <=', $filters['end']);
        }
        if (!empty($filters['visit_type'])) {
            $this->db->where_in('m.visit_type', $filters['visit_type']);
        }

        // Count each option separately
        $this->db->select("
            SUM(CASE WHEN d.answer = '1st' THEN 1 ELSE 0 END) AS first_option,
            SUM(CASE WHEN d.answer = '2nd' THEN 1 ELSE 0 END) AS second_option,
            SUM(CASE WHEN d.answer = '3rd' THEN 1 ELSE 0 END) AS third_option,
            SUM(CASE WHEN d.answer = '4th' THEN 1 ELSE 0 END) AS fourth_option
        ", false);

        // Execute query
        $query = $this->db->get();
        return $query->row_array();
    }
    
    public function get_q184_counts($filters)
    {
        $this->db->select('d.answer, COUNT(d.answer) as total');
        $this->db->from('opd_mnch_master m');
        $this->db->join('opd_mnch_detail d', 'm.id = d.master_id AND d.question_id = 21', 'left');

        // Apply filters dynamically
        if (!empty($filters['uc'])) {
            $this->db->where_in('m.uc', $filters['uc']);
        }
        if (!empty($filters['start'])) {
            $this->db->where('DATE(m.form_date) >=', $filters['start']);
        }
        if (!empty($filters['end'])) {
            $this->db->where('DATE(m.form_date) <=', $filters['end']);
        }
        if (!empty($filters['visit_type'])) {
            $this->db->where_in('m.visit_type', $filters['visit_type']);
        }

        // Group by actual answer text
        $this->db->group_by('d.answer');
        $this->db->order_by('d.option_id', 'ASC'); // optional, largest first

        $query = $this->db->get();
        $result = $query->result_array();

        $counts = [];
        foreach ($result as $row) {
            if (isset($row['answer']) && trim($row['answer']) != '') {
                $counts[$row['answer']] = (int)$row['total'];
            }
        }

        return $counts; 
    }
    
    public function get_sunburst_q19($filters)
    {
        $this->db->from('opd_mnch_master m');
        $this->db->join(
            'opd_mnch_detail d',
            'm.id = d.master_id AND d.question_id IN (24,25)',
            'left'
        );
        if (!empty($filters['uc']))         $this->db->where_in('m.uc', $filters['uc']);
        if (!empty($filters['start']))      $this->db->where('DATE(m.form_date) >=', $filters['start']);
        if (!empty($filters['end']))        $this->db->where('DATE(m.form_date) <=', $filters['end']);
        if (!empty($filters['visit_type'])) $this->db->where_in('m.visit_type', $filters['visit_type']);

        $this->db->select("
            COUNT(DISTINCT m.id) AS total_records,

            SUM(CASE WHEN d.question_id=24 AND d.answer='Yes' THEN 1 ELSE 0 END) AS yes_count,
            SUM(CASE WHEN d.question_id=24 AND d.answer='No'  THEN 1 ELSE 0 END) AS no_count,

            SUM(CASE WHEN d.question_id=25 AND d.answer='1st' THEN 1 ELSE 0 END) AS dose_1st,
            SUM(CASE WHEN d.question_id=25 AND d.answer='2nd' THEN 1 ELSE 0 END) AS dose_2nd,
            SUM(CASE WHEN d.question_id=25 AND d.answer='3rd' THEN 1 ELSE 0 END) AS dose_3rd,
            SUM(CASE WHEN d.question_id=25 AND d.answer='4th' THEN 1 ELSE 0 END) AS dose_4th,
            SUM(CASE WHEN d.question_id=25 AND d.answer='5th' THEN 1 ELSE 0 END) AS dose_5th
        ", false);

        $r = $this->db->get()->row_array();

        $safe = function($v) {
            return (int)$v;
        };

        $totalRecords = $safe($r['total_records']);
        $yesCount     = $safe($r['yes_count']);
        $noCount      = $safe($r['no_count']);

        // Records where Q24 was not answered at all
        $q24_na = max(0, $totalRecords - $yesCount - $noCount);

        $doseTotal =
            $safe($r['dose_1st']) +
            $safe($r['dose_2nd']) +
            $safe($r['dose_3rd']) +
            $safe($r['dose_4th']) +
            $safe($r['dose_5th']);

        // Yes records where no dose was recorded in Q25
        $dose_na = max(0, $yesCount - $doseTotal);

        $sunburst = [];

        // Root: Tetanus Vaccine Administered
        $sunburst[] = [
            'id'     => 'root',
            'parent' => '',
            'name'   => 'Tetanus Vaccine Administered',
            'value'  => $totalRecords,
            'color'  => '#2b908f'
        ];

        // Yes branch
        $sunburst[] = [
            'id'     => 'yes',
            'parent' => 'root',
            'name'   => 'Yes',
            'value'  => $yesCount,
            'color'  => '#7cb5ec'
        ];

        // Dose sub-label under Yes
        $sunburst[] = [
            'id'     => 'dose',
            'parent' => 'yes',
            'name'   => 'In case of Yes, mention Dose',
            'value'  => $doseTotal,
            'color'  => '#5aa0d0'
        ];

        // Dose breakdown
        $sunburst[] = [
            'parent' => 'dose',
            'name'   => '1st',
            'value'  => $safe($r['dose_1st']),
            'color'  => '#90ed7d'
        ];

        $sunburst[] = [
            'parent' => 'dose',
            'name'   => '2nd',
            'value'  => $safe($r['dose_2nd']),
            'color'  => '#f7a35c'
        ];

        $sunburst[] = [
            'parent' => 'dose',
            'name'   => '3rd',
            'value'  => $safe($r['dose_3rd']),
            'color'  => '#8085e9'
        ];

        $sunburst[] = [
            'parent' => 'dose',
            'name'   => '4th',
            'value'  => $safe($r['dose_4th']),
            'color'  => '#f15c80'
        ];

        $sunburst[] = [
            'parent' => 'dose',
            'name'   => '5th',
            'value'  => $safe($r['dose_5th']),
            'color'  => '#e4d354'
        ];

        // Yes but no dose recorded
        if ($dose_na > 0) {
            $sunburst[] = [
                'parent' => 'yes',
                'name'   => 'N/A',
                'value'  => $dose_na,
                'color'  => '#cccccc'
            ];
        }

        // No branch
        $sunburst[] = [
            'id'     => 'no',
            'parent' => 'root',
            'name'   => 'No',
            'value'  => $noCount,
            'color'  => '#f7a35c'
        ];

        // Q24 not answered at all
        if ($q24_na > 0) {
            $sunburst[] = [
                'parent' => 'root',
                'name'   => 'N/A',
                'value'  => $q24_na,
                'color'  => '#cccccc'
            ];
        }

        return [
            'sunburst'      => $sunburst,
            'yes_count'     => $yesCount,
            'no_count'      => $noCount,
            'total_records' => $totalRecords
        ];
    }
    
    public function get_sunburst_q19_1($filters)
    {
        $this->db->from('opd_mnch_master m');
        $this->db->join(
            'opd_mnch_detail d',
            'm.id = d.master_id AND d.question_id IN (26,27,28,29)',
            'left'
        );
        if (!empty($filters['uc']))         $this->db->where_in('m.uc', $filters['uc']);
        if (!empty($filters['start']))      $this->db->where('DATE(m.form_date) >=', $filters['start']);
        if (!empty($filters['end']))        $this->db->where('DATE(m.form_date) <=', $filters['end']);
        if (!empty($filters['visit_type'])) $this->db->where_in('m.visit_type', $filters['visit_type']);

        $this->db->select("
            COUNT(DISTINCT m.id) AS total_records,

            SUM(CASE WHEN d.question_id=26 AND d.answer='Yes' THEN 1 ELSE 0 END) AS q26_yes,
            SUM(CASE WHEN d.question_id=26 AND d.answer='No'  THEN 1 ELSE 0 END) AS q26_no,

            SUM(CASE WHEN d.question_id=27 AND d.answer='Yes' THEN 1 ELSE 0 END) AS q27_yes,
            SUM(CASE WHEN d.question_id=27 AND d.answer='No'  THEN 1 ELSE 0 END) AS q27_no,

            SUM(CASE WHEN d.question_id=28 AND d.answer='Yes' THEN 1 ELSE 0 END) AS q28_yes,
            SUM(CASE WHEN d.question_id=28 AND d.answer='No'  THEN 1 ELSE 0 END) AS q28_no,

            SUM(CASE WHEN d.question_id=29 AND d.answer='Yes' THEN 1 ELSE 0 END) AS q29_yes,
            SUM(CASE WHEN d.question_id=29 AND d.answer='No'  THEN 1 ELSE 0 END) AS q29_no
        ", false);

        $r = $this->db->get()->row_array();

        $safe = function($v) {
            return (int)(isset($v) ? $v : 0);
        };

        $totalRecords = $safe($r['total_records']);

        // Q26 - TD Card Issued (Parent)
        $q26_yes = $safe($r['q26_yes']);
        $q26_no  = $safe($r['q26_no']);
        $q26_na  = max(0, $totalRecords - $q26_yes - $q26_no);

        // Q27 - Refused for TT (Child of Q26 Yes)
        $q27_yes = $safe($r['q27_yes']);
        $q27_no  = $safe($r['q27_no']);
        $q27_na  = max(0, $q26_yes - $q27_yes - $q27_no);

        // Q28 - Complete TT Schedule (Child of Q26 Yes)
        $q28_yes = $safe($r['q28_yes']);
        $q28_no  = $safe($r['q28_no']);
        $q28_na  = max(0, $q26_yes - $q28_yes - $q28_no);

        // Q29 - Dose Not Due (Child of Q26 Yes)
        $q29_yes = $safe($r['q29_yes']);
        $q29_no  = $safe($r['q29_no']);
        $q29_na  = max(0, $q26_yes - $q29_yes - $q29_no);

        $sunburst = [];

        // Root: TD Card Issued (Q26)
        $sunburst[] = [
            'id'     => 'root',
            'parent' => '',
            'name'   => 'TD Card Issued',
            'value'  => $totalRecords,
            'color'  => '#2b908f'
        ];

        // Q26 Yes — parent of Q27, Q28, Q29
        $sunburst[] = [
            'id'     => 'q26_yes',
            'parent' => 'root',
            'name'   => 'Yes',
            'value'  => $q26_yes,
            'color'  => '#7cb5ec'
        ];

        // Q26 No — standalone leaf
        $sunburst[] = [
            'id'     => 'q26_no',
            'parent' => 'root',
            'name'   => 'No',
            'value'  => $q26_no,
            'color'  => '#f7a35c'
        ];

        // Q26 N/A — standalone leaf
        if ($q26_na > 0) {
            $sunburst[] = [
                'parent' => 'root',
                'name'   => 'N/A',
                'value'  => $q26_na,
                'color'  => '#cccccc'
            ];
        }

        // ── Q27: Refused for TT (child of Q26 Yes) ──────────
        $sunburst[] = [
            'id'     => 'q27',
            'parent' => 'q26_yes',
            'name'   => 'Refused for TT',
            'value'  => $q27_yes + $q27_no + $q27_na,
            'color'  => '#f15c80'
        ];
        $sunburst[] = ['parent' => 'q27', 'name' => 'Yes', 'value' => $q27_yes, 'color' => '#90ed7d'];
        $sunburst[] = ['parent' => 'q27', 'name' => 'No',  'value' => $q27_no,  'color' => '#f7a35c'];
        if ($q27_na > 0) {
            $sunburst[] = ['parent' => 'q27', 'name' => 'N/A', 'value' => $q27_na, 'color' => '#cccccc'];
        }

        // ── Q28: Complete TT Schedule (child of Q26 Yes) ────
        $sunburst[] = [
            'id'     => 'q28',
            'parent' => 'q26_yes',
            'name'   => 'Complete TT Schedule',
            'value'  => $q28_yes + $q28_no + $q28_na,
            'color'  => '#8085e9'
        ];
        $sunburst[] = ['parent' => 'q28', 'name' => 'Yes', 'value' => $q28_yes, 'color' => '#90ed7d'];
        $sunburst[] = ['parent' => 'q28', 'name' => 'No',  'value' => $q28_no,  'color' => '#f7a35c'];
        if ($q28_na > 0) {
            $sunburst[] = ['parent' => 'q28', 'name' => 'N/A', 'value' => $q28_na, 'color' => '#cccccc'];
        }

        // ── Q29: Dose Not Due (child of Q26 Yes) ────────────
        $sunburst[] = [
            'id'     => 'q29',
            'parent' => 'q26_yes',
            'name'   => 'Dose Not Due',
            'value'  => $q29_yes + $q29_no + $q29_na,
            'color'  => '#e4d354'
        ];
        $sunburst[] = ['parent' => 'q29', 'name' => 'Yes', 'value' => $q29_yes, 'color' => '#90ed7d'];
        $sunburst[] = ['parent' => 'q29', 'name' => 'No',  'value' => $q29_no,  'color' => '#f7a35c'];
        if ($q29_na > 0) {
            $sunburst[] = ['parent' => 'q29', 'name' => 'N/A', 'value' => $q29_na, 'color' => '#cccccc'];
        }

        return [
            'sunburst'      => $sunburst,
            'total_records' => $totalRecords
        ];
    }
    
    public function get_antigen_heatmap_opd($filters)
    {
        $this->db->from('opd_mnch_master m');
        $this->db->join(
            'opd_mnch_detail d',
            'm.id = d.master_id AND d.question_id = 30',
            'left'
        );
        $this->db->join(
            'question_options qo',
            'qo.option_id = d.option_id AND qo.question_id = d.question_id',
            'left'
        );

        if (!empty($filters['uc']))         $this->db->where_in('m.uc', $filters['uc']);
        if (!empty($filters['start']))      $this->db->where('DATE(m.form_date) >=', $filters['start']);
        if (!empty($filters['end']))        $this->db->where('DATE(m.form_date) <=', $filters['end']);
        if (!empty($filters['visit_type'])) $this->db->where_in('m.visit_type', $filters['visit_type']);

        $this->db->select("
            m.age_group,
            d.answer AS diagnosis,
            d.option_id,
            MIN(qo.option_order) AS option_order,
            COUNT(*) AS total
        ", false);
        $this->db->group_by(array('m.age_group', 'd.answer', 'd.option_id'));
        $this->db->order_by('qo.option_order', 'ASC');

        $result = $this->db->get()->result_array();

        // Fixed age group order
        $ageGroupOrder = array('0-14 Y', '15-49', '50 Y +');

        $diagnosisList = array();
        $dataMap       = array();

        foreach ($result as $row) {
            if (!$row['diagnosis'] || !$row['age_group']) continue;

            $diagnosis = $row['diagnosis'];
            $ageGroup  = $row['age_group'];

            if (!isset($diagnosisList[$row['option_order']])) {
                $diagnosisList[$row['option_order']] = $diagnosis;
            }

            $dataMap[$ageGroup][$diagnosis] = (int)$row['total'];
        }

        // Sort diagnosis by option_order
        ksort($diagnosisList);
        $diagnosisList = array_values($diagnosisList);

        // Only include age groups that have data
        $usedAgeGroups = array();
        foreach ($ageGroupOrder as $ag) {
            if (isset($dataMap[$ag])) {
                $usedAgeGroups[] = $ag;
            }
        }

        $data = array();
        foreach ($usedAgeGroups as $y => $ag) {
            foreach ($diagnosisList as $x => $diagnosis) {
                $value  = isset($dataMap[$ag][$diagnosis]) ? $dataMap[$ag][$diagnosis] : 0;
                $data[] = array($x, $y, $value);
            }
        }

        return array(
            'categoriesX' => $diagnosisList,
            'categoriesY' => $usedAgeGroups,
            'data'        => $data
        );
    }
    
    public function get_trimester_complication_heatmap($filters)
    {
        $this->db->from('opd_mnch_master m');
        $this->db->join(
            'opd_mnch_detail d18',
            'm.id = d18.master_id AND d18.question_id = 18',
            'left'
        );
        $this->db->join(
            'opd_mnch_detail d21',
            'm.id = d21.master_id AND d21.question_id = 21',
            'left'
        );
        $this->db->join(
            'question_options qo18',
            'qo18.option_id = d18.option_id AND qo18.question_id = 18',
            'left'
        );
        $this->db->join(
            'question_options qo21',
            'qo21.option_id = d21.option_id AND qo21.question_id = 21',
            'left'
        );

        if (!empty($filters['uc']))         $this->db->where_in('m.uc', $filters['uc']);
        if (!empty($filters['start']))      $this->db->where('DATE(m.form_date) >=', $filters['start']);
        if (!empty($filters['end']))        $this->db->where('DATE(m.form_date) <=', $filters['end']);
        if (!empty($filters['visit_type'])) $this->db->where_in('m.visit_type', $filters['visit_type']);

        $this->db->select("
            MIN(d18.answer)        AS trimester,
            d18.option_id          AS trimester_option_id,
            MIN(qo18.option_order) AS trimester_order,
            MIN(d21.answer)        AS complication,
            d21.option_id          AS complication_option_id,
            MIN(qo21.option_order) AS complication_order,
            COUNT(DISTINCT m.id)   AS total
        ", false);

        // Group only by option_ids — avoids cartesian text mismatches
        $this->db->group_by(array('d18.option_id', 'd21.option_id'));
        $this->db->order_by('trimester_order',    'ASC');
        $this->db->order_by('complication_order', 'ASC');

        $result = $this->db->get()->result_array();

        $trimesterList    = array();
        $complicationList = array();
        $dataMap          = array();

        foreach ($result as $row) {
            if (!$row['trimester'] || !$row['complication']) continue;

            $trimester    = $row['trimester'];
            $complication = $row['complication'];

            if (!isset($trimesterList[$row['trimester_order']])) {
                $trimesterList[$row['trimester_order']] = $trimester;
            }

            if (!isset($complicationList[$row['complication_order']])) {
                $complicationList[$row['complication_order']] = $complication;
            }

            $dataMap[$trimester][$complication] = (int)$row['total'];
        }

        ksort($trimesterList);
        ksort($complicationList);
        $trimesterList    = array_values($trimesterList);
        $complicationList = array_values($complicationList);

        $data = array();
        foreach ($trimesterList as $y => $trimester) {
            foreach ($complicationList as $x => $complication) {
                $value  = isset($dataMap[$trimester][$complication]) ? $dataMap[$trimester][$complication] : 0;
                $data[] = array($x, $y, $value);
            }
        }

        return array(
            'categoriesX' => $complicationList,
            'categoriesY' => $trimesterList,
            'data'        => $data
        );
    }
    
    public function get_gender_age_data_opd($filters = array())
    {
        $age_groups = array('0-14 Y', '15-49 Y', '50 Y +');

        $this->db->select("
            CASE
                WHEN TRIM(m.age_group) IN ('0-14 Y', '0-14')   THEN '0-14 Y'
                WHEN TRIM(m.age_group) IN ('15-49 Y', '15-49') THEN '15-49 Y'
                WHEN TRIM(m.age_group) IN ('50 Y +', '50 Y+')  THEN '50 Y +'
                ELSE 'Other'
            END AS age_group,
            COUNT(*) AS total
        ");
        $this->db->from('opd_mnch_master m');

        if (!empty($filters['uc']))         $this->db->where_in('m.uc', $filters['uc']);
        if (!empty($filters['start']))      $this->db->where('DATE(m.form_date) >=', $filters['start']);
        if (!empty($filters['end']))        $this->db->where('DATE(m.form_date) <=', $filters['end']);
        if (!empty($filters['visit_type'])) $this->db->where_in('m.visit_type', $filters['visit_type']);

        $this->db->group_by('age_group');
        $this->db->order_by("FIELD(age_group, '0-14 Y', '15-49 Y', '50 Y +')");

        $result = $this->db->get()->result_array();

        $age_data = array_fill(0, count($age_groups), 0);

        foreach ($result as $row) {
            if (in_array($row['age_group'], $age_groups)) {
                $index = array_search($row['age_group'], $age_groups);
                $age_data[$index] = (int)$row['total'];
            }
        }

        return array(
            'age_groups' => $age_groups,
            'data'       => $age_data
        );
    }
    
}
