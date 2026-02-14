<?php

class Location_model extends CI_Model {

    public function get_districts()
    {
        return $this->db->get('districts')->result();
    }

    public function get_uc_by_district($district_id)
    {
        return $this->db->where('district_id', $district_id)
                        ->get('uc')
                        ->result();
    }
    
    public function get_facilities_by_uc($uc_id)
    {
        return $this->db
                    ->where('uc_id', $uc_id)
                    ->get('facilities')
                    ->result();
    }

}
