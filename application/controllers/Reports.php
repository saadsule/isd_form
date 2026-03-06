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
            'uc'        => $this->input->get('uc'),
            'start'     => $this->input->get('start'),
            'end'       => $this->input->get('end'),
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
        $endDate   = !empty($filters['end']) ? $filters['end'] : 'N/A';
        $dateRange = "From: $startDate To: $endDate";

        // Get data and questions
        if ($form_type == 'chf') {
            $reportTitle = "Child Health Report - North Waziristan ($dateRange)";
            $result = $this->Reports_model->get_child_health_data($filters);
        } else {
            $reportTitle = "OPD/MNCH Report - North Waziristan ($dateRange)";
            $result = $this->Reports_model->get_opd_mnch_data($filters);
        }

        $data = $result['data'];
        $question_labels = $result['questions'];
        $question_options = $result['options'];

        // Base headers
        if ($form_type == 'chf') {
            $base_headers = [
                'visit_type','form_date','qr_code','client_type','district','uc','facility_name','village',
                'vaccinator_name','patient_name','guardian_name','dob','age_group',
                'gender','marital_status','pregnancy_status','disability','play_learning_kit','nutrition_package',
                'created_at'
            ];
        } else {
            $base_headers = [
                'visit_type','form_date','qr_code','anc_card_no','client_type','district','uc','facility_name','village',
                'lhv_name','patient_name','guardian_name','age_group','disability','marital_status','pregnancy_status',
            ];
        }

        // Build full headers (used for column count)
        $headers = $base_headers;
        foreach ($question_labels as $qid => $label) {
            if (!empty($question_options[$qid])) {
                foreach ($question_options[$qid] as $opt) {
                    $headers[] = $opt['column'];
                }
            } else {
                $headers[] = $label; // Use actual question label even if no options
            }
        }

        $lastColumnIndex = count($headers) - 1;
        $lastColumn = PHPExcel_Cell::stringFromColumnIndex($lastColumnIndex);

        // Report title
        $sheet->mergeCells("A{$rowIndex}:{$lastColumn}{$rowIndex}");
        $sheet->setCellValue("A{$rowIndex}", $reportTitle);
        $sheet->getStyle("A{$rowIndex}")->applyFromArray([
            'font' => ['bold'=>true, 'size'=>16, 'color'=>['rgb'=>'FFFFFF']],
            'alignment'=>['horizontal'=>PHPExcel_Style_Alignment::HORIZONTAL_LEFT, 'vertical'=>PHPExcel_Style_Alignment::VERTICAL_CENTER],
            'fill'=>['type'=>PHPExcel_Style_Fill::FILL_SOLID,'startcolor'=>['rgb'=>'1F4E78']]
        ]);
        $sheet->getRowDimension($rowIndex)->setRowHeight(30);
        $rowIndex += 2;

        // Table header: two rows
        $colIndex = 0;
        foreach ($base_headers as $h) {
            $sheet->setCellValueByColumnAndRow($colIndex, $rowIndex, strtoupper(str_replace('_',' ',$h)));
            $sheet->mergeCellsByColumnAndRow($colIndex, $rowIndex, $colIndex, $rowIndex+1);
            $colIndex++;
        }

        foreach ($question_labels as $qid => $label) {
            $startCol = $colIndex;

            if (!empty($question_options[$qid])) {
                foreach ($question_options[$qid] as $opt) {
                    $sheet->setCellValueByColumnAndRow($colIndex, $rowIndex+1, $opt['option_text']);
                    $colIndex++;
                }
            } else {
                // No options: leave blank row below
                $sheet->setCellValueByColumnAndRow($colIndex, $rowIndex+1, '');
                $colIndex++;
            }

            // Merge question label horizontally
            $sheet->mergeCellsByColumnAndRow($startCol, $rowIndex, $colIndex-1, $rowIndex);
            $sheet->setCellValueByColumnAndRow($startCol, $rowIndex, $label);
            $sheet->getStyleByColumnAndRow($startCol, $rowIndex, $colIndex-1, $rowIndex)
                  ->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        }

        // Style header rows
        $sheet->getStyle("A{$rowIndex}:{$lastColumn}".($rowIndex+1))->applyFromArray([
            'font'=>['bold'=>true],
            'alignment'=>['horizontal'=>PHPExcel_Style_Alignment::HORIZONTAL_CENTER],
            'fill'=>['type'=>PHPExcel_Style_Fill::FILL_SOLID,'startcolor'=>['rgb'=>'D9E1F2']],
            'borders'=>['allborders'=>['style'=>PHPExcel_Style_Border::BORDER_THIN]]
        ]);

        $rowIndex += 2;

        // Data rows
        foreach ($data as $row) {
            $colIndex = 0;
            foreach ($base_headers as $h) {
                $sheet->setCellValueByColumnAndRow($colIndex, $rowIndex, isset($row[$h]) ? $row[$h] : '');
                $colIndex++;
            }

            foreach ($question_labels as $qid => $label) {
                if (!empty($question_options[$qid])) {
                    foreach ($question_options[$qid] as $opt) {
                        $sheet->setCellValueByColumnAndRow($colIndex, $rowIndex, isset($row[$opt['column']]) ? $row[$opt['column']] : '');
                        $colIndex++;
                    }
                } else {
                    // No options, leave blank cell
                    $sheet->setCellValueByColumnAndRow($colIndex, $rowIndex, '');
                    $colIndex++;
                }
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
    
    public function view_health_data()
    {
        $this->load->model('Reports_model');

        $filters = [
            'uc'        => $this->input->get('uc'),
            'start'     => $this->input->get('start'),
            'end'       => $this->input->get('end'),
            'form_type' => $this->input->get('form_type'),
        ];

        $data['filters'] = $filters;
        $data['ucs'] = $this->Reports_model->get_ucs();
        $data['page_title'] = "View Health Data";

        $data['table_data'] = [];
        $data['headers'] = [];
        $data['question_labels'] = [];

        if (!empty($filters['form_type'])) {

        if ($filters['form_type'] == 'chf') {

            $result = $this->Reports_model->get_child_health_data($filters);

            $data['headers'] = [
                'visit_type','form_date','qr_code','client_type','district','uc','facility_name','village',
                'vaccinator_name','patient_name','guardian_name','dob','age_group',
                'gender','marital_status','pregnancy_status','disability',
                'play_learning_kit','nutrition_package','created_at'
            ];

        } elseif ($filters['form_type'] == 'opd') {

            $result = $this->Reports_model->get_opd_mnch_data($filters);

            $data['headers'] = [
                'visit_type','form_date','qr_code','anc_card_no','client_type','district','uc','facility_name','village',
                'lhv_name','patient_name','guardian_name','age_group','disability',
                'marital_status','pregnancy_status'
            ];

        } else {
            show_error("Invalid form type selected");
            return;
        }

        $data['table_data'] = $result['data'];
        $data['question_labels'] = $result['questions'];
        $data['question_options'] = $result['options'];

        // Add question columns
        foreach ($data['question_labels'] as $qid => $label) {
            $data['headers'][] = 'Q'.$qid;
        }
    }

        $data['main_content'] = $this->load->view('reports/view_health_data', $data, TRUE);
        $this->load->view('layout/main', $data);
    }
    
    public function validation_report()
    {
        $data['report'] = $this->Reports_model->get_validation_report();
        $data['main_content'] = $this->load->view('reports/validation_report', $data, TRUE);
        $this->load->view('layout/main', $data);
    }
    
    public function duplicate_qr_report()
    {
        $data['duplicates'] = $this->Reports_model->get_duplicate_qr_report();
        $data['main_content'] = $this->load->view('reports/duplicate_qr_report', $data, TRUE);
        $this->load->view('layout/main', $data);
    }

}
