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

        $this->db->group_by('age_group');
        $result = $this->db->get()->result();

        // Initialize default age groups with 0
        $ageGroups = [
            '<1 Year'    => 0,
            '1-2 Year'   => 0,
            '2-5 Year'   => 0,
            '5-15 Year'  => 0,
            '15-49 Year' => 0
        ];

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
        // Query counts from child_health_master + child_health_detail
        $this->db->from('child_health_master m');
        $this->db->join('child_health_detail d', 'm.master_id = d.master_id AND d.question_id IN (2,3,4)', 'left');

        // Apply filters dynamically
        if (!empty($filters['uc'])) $this->db->where_in('m.uc', $filters['uc']);
        if (!empty($filters['start'])) $this->db->where('DATE(m.form_date) >=', $filters['start']);
        if (!empty($filters['end'])) $this->db->where('DATE(m.form_date) <=', $filters['end']);
        if (!empty($filters['visit_type'])) $this->db->where_in('m.visit_type', $filters['visit_type']);

        $this->db->select("
            SUM(CASE WHEN d.question_id=2 AND d.answer='Yes' THEN 1 ELSE 0 END) AS q172_Yes,
            SUM(CASE WHEN d.question_id=2 AND d.answer='No' THEN 1 ELSE 0 END) AS q172_No,
            SUM(CASE WHEN d.question_id=2 AND (d.answer IS NULL OR d.answer NOT IN ('Yes','No')) THEN 1 ELSE 0 END) AS q172_None,

            SUM(CASE WHEN d.question_id=3 AND d.answer='17.2.1. Fully immunized as per Age' THEN 1 ELSE 0 END) AS q173_FullyImmunized,
            SUM(CASE WHEN d.question_id=3 AND d.answer='17.2.2. Vaccine not due' THEN 1 ELSE 0 END) AS q173_VaccineNotDue,
            SUM(CASE WHEN d.question_id=3 AND d.answer='17.2.3. Child is unwell' THEN 1 ELSE 0 END) AS q173_ChildUnwell,
            SUM(CASE WHEN d.question_id=3 AND d.answer='17.2.4. Refusal' THEN 1 ELSE 0 END) AS q173_Refusal,

            SUM(CASE WHEN d.question_id=4 AND d.answer='Demand Refusal' THEN 1 ELSE 0 END) AS q174_DemandRefusal,
            SUM(CASE WHEN d.question_id=4 AND d.answer='Misconception Refusal' THEN 1 ELSE 0 END) AS q174_MisconceptionRefusal,
            SUM(CASE WHEN d.question_id=4 AND d.answer='Religious Refusal' THEN 1 ELSE 0 END) AS q174_ReligiousRefusal
        ", false);

        $result = $this->db->get()->row_array();

        // Helper function for Highcharts values
        $safe = function($v) {
            return ($v > 0) ? (int)$v : 0.1;
        };

        // Build Sunburst data
        $sunburst = [];
        $sunburst[] = ['id'=>'0.0','parent'=>'','name'=>'Total'];

        $counter = 1;

        // --- Q17.2 ---
        $q172_id = '1.'.$counter;
        $q172_children = [
            $safe($result['q172_Yes']),
            $safe($result['q172_No']),
            $safe($result['q172_None'])
        ];
        $q172_value = array_sum($q172_children); // parent value = sum of children
        $sunburst[] = ['id'=>$q172_id,'parent'=>'0.0','name'=>'Q17.2','value'=>$q172_value];
        $counter++;

        foreach(['Yes','No','None'] as $i => $a){
            $sunburst[] = [
                'id'=>'2.'.($counter+$i),
                'parent'=>$q172_id,
                'name'=>$a,
                'value'=>$q172_children[$i]
            ];
        }
        $counter += count($q172_children);

        // --- Q17.3 ---
        $q173_id = '1.'.$counter;
        $q173_children_values = [
            $safe($result['q173_FullyImmunized']),
            $safe($result['q173_VaccineNotDue']),
            $safe($result['q173_ChildUnwell']),
            $safe($result['q173_Refusal'])
        ];
        $q173_value = array_sum($q173_children_values);
        $sunburst[] = ['id'=>$q173_id,'parent'=>'0.0','name'=>'Q17.3','value'=>$q173_value];
        $counter++;

        $q173_labels = ['Fully Immunized','Vaccine Not Due','Child Unwell','Refusal'];
        foreach($q173_labels as $i => $label){
            $sunburst[] = [
                'id'=>'2.'.($counter+$i),
                'parent'=>$q173_id,
                'name'=>$label,
                'value'=>$q173_children_values[$i]
            ];
        }
        $counter += count($q173_labels);

        // --- Q17.4 ---
        $q174_id = '1.'.$counter;
        $q174_children_values = [
            $safe($result['q174_DemandRefusal']),
            $safe($result['q174_MisconceptionRefusal']),
            $safe($result['q174_ReligiousRefusal'])
        ];
        $q174_value = array_sum($q174_children_values);
        $sunburst[] = ['id'=>$q174_id,'parent'=>'0.0','name'=>'Q17.4','value'=>$q174_value];
        $counter++;

        $q174_labels = ['Demand Refusal','Misconception Refusal','Religious Refusal'];
        foreach($q174_labels as $i => $label){
            $sunburst[] = [
                'id'=>'2.'.($counter+$i),
                'parent'=>$q174_id,
                'name'=>$label,
                'value'=>$q174_children_values[$i]
            ];
        }

        return $sunburst;
    }
    
}
