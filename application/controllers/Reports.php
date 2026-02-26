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
    
//    public function export_health_data_excel()
//    {
//        $this->load->model('Reports_model');
//
//        $filters = [
//            'uc' => $this->input->get('uc'),
//            'start' => $this->input->get('start'),
//            'end' => $this->input->get('end'),
//            'form_type' => $this->input->get('form_type'),
//        ];
//
//        // Single select form type
//        $form_type = !empty($filters['form_type']) ? $filters['form_type'] : null;
//
//        if(empty($form_type)){
//            show_error("Please select form type");
//            return;
//        }
//
//        $objPHPExcel = new PHPExcel();
//        $sheet = $objPHPExcel->setActiveSheetIndex(0);
//        $sheet->setRightToLeft(false); // Ensure LTR
//        $rowIndex = 1;
//
//        // =========================
//        // DATA & HEADERS
//        // =========================
//        $startDate = !empty($filters['start']) ? $filters['start'] : 'N/A';
//        $endDate   = !empty($filters['end']) ? $filters['end'] : 'N/A';
//        $dateRange = "From: $startDate To: $endDate";
//
//        if($form_type == 'chf'){
//            $reportTitle = "Child Health Report - North Waziristan ($dateRange)";
//            $data = $this->Reports_model->get_child_health_data($filters);
//            // All fields from child_health_master
//            $headers = [
//                'visit_type','form_date','qr_code','client_type','district','uc','facility_name','village',
//                'vaccinator_name','patient_name','guardian_name','dob','age_group',
//                'gender','marital_status','pregnancy_status','disability','play_learning_kit','nutrition_package',
//                'created_at','details'
//            ];
//        } elseif($form_type == 'opd'){
//            $reportTitle = "OPD/MNCH Report - North Waziristan ($dateRange)";
//            $data = $this->Reports_model->get_opd_mnch_data($filters);
//            // All fields from opd_mnch_master
//            $headers = [
//                'visit_type','form_date','qr_code','anc_card_no','client_type','age_group','district','uc','facility_name','village','lhv_name',
//                'patient_name','guardian_name','disability','marital_status','pregnancy_status',
//                'notes','created_at','details'
//            ];
//        } else {
//            show_error("Invalid form type selected");
//            return;
//        }
//
//        // Last column
//        $lastColumnIndex = count($headers) - 1;
//        $lastColumn = PHPExcel_Cell::stringFromColumnIndex($lastColumnIndex);
//
//        // =========================
//        // MERGED HEADING
//        // =========================
//        $sheet->mergeCells("A{$rowIndex}:{$lastColumn}{$rowIndex}");
//        $sheet->setCellValue("A{$rowIndex}", $reportTitle);
//
//        $sheet->getStyle("A{$rowIndex}")->applyFromArray([
//            'font' => ['bold' => true, 'size' => 16, 'color' => ['rgb' => 'FFFFFF']],
//            'alignment' => [
//                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
//                'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER
//            ],
//            'fill' => [
//                'type' => PHPExcel_Style_Fill::FILL_SOLID,
//                'startcolor' => ['rgb' => '1F4E78'] // Dark Blue
//            ]
//        ]);
//
//        $sheet->getRowDimension($rowIndex)->setRowHeight(30);
//        $rowIndex += 2;
//
//        // =========================
//        // TABLE HEADER
//        // =========================
//        $colIndex = 0;
//        foreach($headers as $h){
//            $sheet->setCellValueByColumnAndRow($colIndex, $rowIndex, strtoupper(str_replace('_',' ',$h)));
//            $colIndex++;
//        }
//
//        $headerRange = "A{$rowIndex}:{$lastColumn}{$rowIndex}";
//        $sheet->getStyle($headerRange)->applyFromArray([
//            'font' => ['bold' => true],
//            'alignment' => ['horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER],
//            'fill' => ['type' => PHPExcel_Style_Fill::FILL_SOLID, 'startcolor' => ['rgb' => 'D9E1F2']],
//            'borders' => ['allborders' => ['style' => PHPExcel_Style_Border::BORDER_THIN]]
//        ]);
//
//        $rowIndex++;
//
//        // =========================
//        // DATA ROWS
//        // =========================
//        foreach($data as $row){
//            $colIndex = 0;
//            foreach($headers as $h){
//                $sheet->setCellValueByColumnAndRow($colIndex, $rowIndex, isset($row[$h]) ? $row[$h] : '');
//                $colIndex++;
//            }
//            $rowIndex++;
//        }
//
//        $rowIndex += 3; // spacing before next report
//
//        // =========================
//        // AUTO SIZE COLUMNS
//        // =========================
//        foreach(range('A', $lastColumn) as $columnID){
//            $sheet->getColumnDimension($columnID)->setAutoSize(true);
//        }
//
//        // =========================
//        // SAFE EXPORT
//        // =========================
//        if (ob_get_length()) {
//            ob_end_clean();
//        }
//
//        // =========================
//        // DYNAMIC FILENAME MATCHING HEADING
//        // =========================
//        $filename = strtoupper($form_type).'_North_Waziristan_Report_'.$startDate.'_to_'.$endDate.'.xlsx';
//
//        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
//        header('Content-Disposition: attachment;filename="'.$filename.'"');
//        header('Cache-Control: max-age=0');
//
//        $writer = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
//        $writer->save('php://output');
//        exit;
//    }

    public function export_health_data_excel()
{
    $this->load->model('Reports_model');

    $filters = [
        'uc' => $this->input->get('uc'),
        'start' => $this->input->get('start'),
        'end' => $this->input->get('end'),
        'form_type' => $this->input->get('form_type'),
    ];

    $form_type = !empty($filters['form_type']) ? $filters['form_type'] : null;
    if (empty($form_type)) {
        show_error("Please select form type");
        return;
    }

    $objPHPExcel = new PHPExcel();
    $sheet = $objPHPExcel->setActiveSheetIndex(0);
    $sheet->setRightToLeft(false); // LTR
    $rowIndex = 1;

    $startDate = !empty($filters['start']) ? $filters['start'] : 'N/A';
    $endDate = !empty($filters['end']) ? $filters['end'] : 'N/A';
    $dateRange = "From: $startDate To: $endDate";

    if ($form_type == 'chf') {
        $reportTitle = "Child Health Report - North Waziristan ($dateRange)";
        $result = $this->Reports_model->get_child_health_data($filters);
    } elseif ($form_type == 'opd') {
        $reportTitle = "OPD/MNCH Report - North Waziristan ($dateRange)";
        $result = $this->Reports_model->get_opd_mnch_data($filters);
    } else {
        show_error("Invalid form type selected");
        return;
    }

    $data = $result['data'];
    $question_labels = $result['questions'];

    // Base headers
    if ($form_type == 'chf') {
        $headers = [
            'visit_type','form_date','qr_code','client_type','district','uc','facility_name','village',
            'vaccinator_name','patient_name','guardian_name','dob','age_group',
            'gender','marital_status','pregnancy_status','disability','play_learning_kit','nutrition_package',
            'created_at'
        ];
    } else { // opd
        $headers = [
            'visit_type','form_date','qr_code','anc_card_no','client_type','age_group','district','uc','facility_name','village',
            'lhv_name','patient_name','guardian_name','disability','marital_status','pregnancy_status',
            'notes','created_at'
        ];
    }

    // Add dynamic question headers
    foreach ($question_labels as $qid => $label) {
        $headers[] = $label;
    }

    $lastColumnIndex = count($headers) - 1;
    $lastColumn = PHPExcel_Cell::stringFromColumnIndex($lastColumnIndex);

    // Heading
    $sheet->mergeCells("A{$rowIndex}:{$lastColumn}{$rowIndex}");
    $sheet->setCellValue("A{$rowIndex}", $reportTitle);
    $sheet->getStyle("A{$rowIndex}")->applyFromArray([
        'font' => ['bold' => true, 'size' => 16, 'color' => ['rgb' => 'FFFFFF']],
        'alignment' => ['horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT, 'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER],
        'fill' => ['type' => PHPExcel_Style_Fill::FILL_SOLID, 'startcolor' => ['rgb' => '1F4E78']]
    ]);
    $sheet->getRowDimension($rowIndex)->setRowHeight(30);
    $rowIndex += 2;

    // Table header
    $colIndex = 0;
    foreach ($headers as $h) {
        $sheet->setCellValueByColumnAndRow($colIndex, $rowIndex, strtoupper(str_replace('_',' ',$h)));
        $colIndex++;
    }

    $sheet->getStyle("A{$rowIndex}:{$lastColumn}{$rowIndex}")->applyFromArray([
        'font'=>['bold'=>true],
        'alignment'=>['horizontal'=>PHPExcel_Style_Alignment::HORIZONTAL_CENTER],
        'fill'=>['type'=>PHPExcel_Style_Fill::FILL_SOLID,'startcolor'=>['rgb'=>'D9E1F2']],
        'borders'=>['allborders'=>['style'=>PHPExcel_Style_Border::BORDER_THIN]]
    ]);

    $rowIndex++;

    // Data rows
    foreach ($data as $row) {
        $colIndex = 0;
        foreach ($headers as $h) {
            if (in_array($h, $question_labels)) {
                $qid = array_search($h, $question_labels);
                $sheet->setCellValueByColumnAndRow($colIndex, $rowIndex, isset($row['Q'.$qid]) ? $row['Q'.$qid] : '');
            } else {
                $sheet->setCellValueByColumnAndRow($colIndex, $rowIndex, isset($row[$h]) ? $row[$h] : '');
            }
            $colIndex++;
        }
        $rowIndex++;
    }

    // Auto-size columns
    foreach (range('A', $lastColumn) as $columnID) {
        $sheet->getColumnDimension($columnID)->setAutoSize(true);
    }

    if (ob_get_length()) { ob_end_clean(); }

    $filename = strtoupper($form_type) . '_North_Waziristan_Report_' . $startDate . '_to_' . $endDate . '.xlsx';
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="'.$filename.'"');
    header('Cache-Control: max-age=0');

    $writer = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
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
