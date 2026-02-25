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

        $safe = function($v) { return (int)$v; };

        $total = $safe($r['yes_count']) + $safe($r['no_count']);

        $sunburst = [];

        // ROOT
        $sunburst[] = [
            'id' => 'root',
            'parent' => '',
            'name' => 'Child Vaccination Status',
            'value' => $total
        ];

        // Q17.2 - Yes / No
        $sunburst[] = ['id'=>'yes','parent'=>'root','name'=>'Yes (Vaccinated)','value'=>$safe($r['yes_count'])];
        $sunburst[] = ['id'=>'no','parent'=>'root','name'=>'No (Not Vaccinated)','value'=>$safe($r['no_count'])];

        // Q17.3 – Under No
        $sunburst[] = ['parent'=>'no','name'=>'Fully Immunized','value'=>$safe($r['fully_immunized'])];
        $sunburst[] = ['parent'=>'no','name'=>'Vaccine Not Due','value'=>$safe($r['vaccine_not_due'])];
        $sunburst[] = ['parent'=>'no','name'=>'Child Unwell','value'=>$safe($r['child_unwell'])];

        // Add Refusal node with ID so subtypes can attach
        $sunburst[] = ['id'=>'no_refusal','parent'=>'no','name'=>'Refusal','value'=>$safe($r['refusal_reason'])];

        // Only if there are refusals, attach Q17.4 subtypes under No → Refusal
        if ($safe($r['refusal_reason']) > 0) {
            $sunburst[] = ['id'=>'refusal_type','parent'=>'no_refusal','name'=>'Type of Refusal','value'=> 
                $safe($r['demand_refusal']) + $safe($r['misconception_refusal']) + $safe($r['religious_refusal'])
            ];
            $sunburst[] = ['parent'=>'refusal_type','name'=>'Demand Refusal','value'=>$safe($r['demand_refusal'])];
            $sunburst[] = ['parent'=>'refusal_type','name'=>'Misconception Refusal','value'=>$safe($r['misconception_refusal'])];
            $sunburst[] = ['parent'=>'refusal_type','name'=>'Religious Refusal','value'=>$safe($r['religious_refusal'])];
        }

        return $sunburst;
    }
    
    public function get_antigen_heatmap($filters)
    {
        $this->db->from('child_health_master m');
        $this->db->join(
            'child_health_detail d',
            'm.master_id = d.master_id AND d.question_id IN (5,6,7)',
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

        $this->db->select("
            d.question_id,
            d.answer AS antigen,
            COUNT(*) AS total
        ", false);

        $this->db->group_by(['d.question_id', 'd.answer']);

        $result = $this->db->get()->result_array();

        // Question labels
        $questions = [
            5 => 'Antigens < 1 Year',
            6 => 'Antigens 1–2 Year',
            7 => 'Antigens 2–5 Year'
        ];

        $questionList = array_values($questions);
        $antigenList = [];
        $dataMap = [];

        foreach ($result as $row) {

            if (!$row['antigen']) continue;

            $qLabel = $questions[$row['question_id']];
            $antigen = $row['antigen'];

            $antigenList[$antigen] = true;
            $dataMap[$qLabel][$antigen] = (int)$row['total'];
        }

        $antigenList = array_values(array_keys($antigenList));

        $data = [];

        foreach ($questionList as $y => $q) {
            foreach ($antigenList as $x => $antigen) {

                $value = isset($dataMap[$q][$antigen])
                    ? $dataMap[$q][$antigen]
                    : 0;

                $data[] = [$x, $y, $value];
            }
        }

        return [
            'categoriesX' => $antigenList,
            'categoriesY' => $questionList,
            'data' => $data
        ];
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

        $totalForms = $this->db->count_all_results();

        $noneCount = $totalForms - $totalRecords;

        if ($noneCount > 0) {
            $data[] = [
                'name' => 'None',
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

        $totalForms = $this->db->count_all_results();

        $noneCount = $totalForms - $totalRecords;

        if ($noneCount > 0) {
            $data[] = [
                'name' => 'None',
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

        $totalForms = $this->db->count_all_results();

        $noneCount = $totalForms - $totalRecords;

        if ($noneCount > 0) {
            $data[] = [
                'name' => 'None',
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

        $totalForms = $this->db->count_all_results();

        $noneCount = $totalForms - $totalRecords;

        if ($noneCount > 0) {
            $data[] = [
                'name' => 'None',
                'y'    => $noneCount
            ];
        }

        return $data;
    }
    
    public function get_gender_age_data($filters = [])
    {
        $age_groups = ['<1 Year','1-2 Year','2-5 Year','5-15 Year','15-49 Year'];

        // Initialize data with 0 for all combinations
        $gender_age_data = [
            'Male'   => array_fill(0, count($age_groups), 0),
            'Female' => array_fill(0, count($age_groups), 0)
        ];

        // Get raw counts from DB
        $this->db->select("
            CASE
                WHEN age_year < 1 THEN '<1 Year'
                WHEN age_year >= 1 AND age_year < 2 THEN '1-2 Year'
                WHEN age_year >= 2 AND age_year < 5 THEN '2-5 Year'
                WHEN age_year >= 5 AND age_year < 15 THEN '5-15 Year'
                WHEN age_year >= 15 AND age_year <= 49 THEN '15-49 Year'
                ELSE 'Other'
            END AS age_group,
            gender,
            COUNT(*) AS total
        ");
        $this->db->from('child_health_master m');

        // Apply filters
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

        $this->db->group_by(['age_group', 'gender']);
        $this->db->order_by("FIELD(age_group, '<1 Year','1-2 Year','2-5 Year','5-15 Year','15-49 Year')");
        $query = $this->db->get();
        $result = $query->result_array();

        // Fill data into 0-initialized array
        foreach ($result as $row) {
            if (in_array($row['age_group'], $age_groups)) {
                $index = array_search($row['age_group'], $age_groups);
                $gender_age_data[$row['gender']][$index] = (int)$row['total'];
            }
        }

        // Return both age_groups and series ready for Highcharts
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

        $colors = ["#7cb5ec", "#90ed7d", "#f7a35c", "#8085e9", "#f15c80", "#f49c42"];

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
                    'name' => 'None',
                    'value' => $noneCount,
                    'color' => '#d3d3d3'
                ];
            }
        }

        return $data;
    }
    
}
