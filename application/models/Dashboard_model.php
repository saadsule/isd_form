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

}
