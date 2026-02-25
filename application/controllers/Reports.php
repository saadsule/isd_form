<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Include PHPExcel manually
require_once(APPPATH.'phpoffice/PHPExcel.php');
require_once(APPPATH.'phpoffice/PHPExcel/Writer/Excel2007.php');

class Reports extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Reports_model'); // Load the model
        $this->load->helper('url');
    }

    public function data_entry_status()
    {
        $data['report'] = $this->Reports_model->get_data_entry_status();
        $data['main_content'] = $this->load->view('reports/data_entry_status', $data, TRUE);
        $this->load->view('layout/main', $data);
    }
    
    public function uc_wise_report()
    {
        $data['report'] = $this->Reports_model->get_uc_wise_report();
        $data['main_content'] = $this->load->view('reports/uc_wise_report', $data, TRUE);
        $this->load->view('layout/main', $data);
    }
    
    public function date_wise_progress()
    {
        $data['report'] = $this->Reports_model->get_date_wise_progress();
        $data['main_content'] = $this->load->view('reports/date_wise_progress', $data, TRUE);
        $this->load->view('layout/main', $data);
    }
    
    public function date_wise_form_progress()
    {
        // Get report data from model
        $data['report'] = $this->Reports_model->get_date_wise_form_progress();

        // Load view
        $data['main_content'] = $this->load->view('reports/date_wise_form_progress', $data, TRUE);
        $this->load->view('layout/main', $data);
    }
    
    public function export_health_data_excel()
    {
        $this->load->model('Reports_model');

        $filters = [
            'uc' => $this->input->get('uc'),
            'start' => $this->input->get('start'),
            'end' => $this->input->get('end'),
            'form_type' => $this->input->get('form_type'),
        ];

        $form_types = isset($filters['form_type']) ? $filters['form_type'] : array();
        if(empty($form_types)){
            show_error("Please select form type(s)");
            return;
        }

        $objPHPExcel = new PHPExcel();
        $sheet = $objPHPExcel->setActiveSheetIndex(0);
        $rowIndex = 1;

        foreach($form_types as $form_type){
            if($form_type == 'chf'){
                $data = $this->Reports_model->get_child_health_data($filters);
                $headers = ['master_id','form_date','qr_code','client_type','district','uc','facility_id','village'];
            } elseif($form_type == 'opd'){
                $data = $this->Reports_model->get_opd_mnch_data($filters);
                $headers = ['id','form_date','anc_card_no','client_type','district','uc','village','lhv_name'];
            }

            // Headers
            $col = 'A';
            foreach($headers as $h){
                $sheet->setCellValue($col.$rowIndex, $h);
                $col++;
            }
            $rowIndex++;

            // Rows
            foreach($data as $row){
                $col = 'A';
                foreach($headers as $h){
                    $sheet->setCellValue($col.$rowIndex, isset($row[$h]) ? $row[$h] : '');
                    $col++;
                }
                $rowIndex++;
            }
        }

        // Export Excel
        $filename = 'health_data_'.date('Y-m-d_H-i-s').'.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$filename.'"');
        header('Cache-Control: max-age=0');

        $writer = new PHPExcel_Writer_Excel2007($objPHPExcel);
        $writer->save('php://output');
        exit;
    }

    public function export_health_data()
    {
        $data['page_title'] = "Export Health Data";

        // Load UCs for filter dropdown
        $data['ucs'] = $this->Reports_model->get_ucs();

//        $data['export_excel'] = $this->Reports_model->get_excel_data($filters);
        // Load the main content view
        $data['main_content'] = $this->load->view('reports/export_health_data', $data, TRUE);
        $this->load->view('layout/main', $data);
    }

}
