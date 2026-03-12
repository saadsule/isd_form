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
        'gender'    => $this->input->get('gender'),
        'age_group' => $this->input->get('age_group'),
        'visit_type'=> $this->input->get('visit_type'),
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
        $data['headers'][] = 'view';
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
    
    public function qr_code_report($qr_code, $form_type)
    {
        $this->load->model('Reports_model');

        $data['records'] = $this->Reports_model->get_qr_records($qr_code, $form_type);

        $data['qr_code'] = $qr_code;
        $data['form_type'] = $form_type;
        $data['page_title'] = "QR Code Record";

        $data['main_content'] = $this->load->view('reports/qr_code_report',$data,true);
        $this->load->view('layout/main',$data);
    }
    
    // Simple Version
    public function vaccination_simple()
    {
        $filters         = array();
        $isFilterApplied = FALSE;

        if ($this->input->get('from_month')) {
            $filters['from_month'] = $this->input->get('from_month');
            $filters['to_month']   = $this->input->get('to_month');
            $isFilterApplied       = TRUE;
        }

        $simple_data = array();
        $months      = array();

        if ($isFilterApplied) {
            $simple_raw = $this->Reports_model->get_vaccination_simple($filters);
            foreach ($simple_raw as $row) {
                $simple_data[$row['uc_name']][$row['month']] = (int)$row['total'];
                if (!in_array($row['month'], $months)) {
                    $months[] = $row['month'];
                }
            }
            sort($months);
        }

        $data = array(
            'filters'         => $filters,
            'isFilterApplied' => $isFilterApplied,
            'months'          => $months,
            'simple_data'     => $simple_data
        );

        $data['main_content'] = $this->load->view('reports/vaccination_simple',$data,true);
        $this->load->view('layout/main',$data);
    }

    // Comparison Version
    public function vaccination_comparison()
    {
        $filters         = array();
        $isFilterApplied = FALSE;

        if ($this->input->get('from_month')) {
            $filters['from_month'] = $this->input->get('from_month');
            $filters['to_month']   = $this->input->get('to_month');
            $isFilterApplied       = TRUE;
        }

        $comparison_data = array();
        $months          = array();

        if ($isFilterApplied) {
            $qr_raw    = $this->Reports_model->get_vaccination_qr_by_month($filters);
            $ucMonthQR = array();

            foreach ($qr_raw as $row) {
                $ucMonthQR[$row['uc_name']][$row['month']][$row['qr_code']] = $row['qr_code'];
                if (!in_array($row['month'], $months)) {
                    $months[] = $row['month'];
                }
            }
            sort($months);

            foreach ($ucMonthQR as $uc_name => $monthData) {
                $base_month = $months[0];
                $base_qr    = isset($monthData[$base_month])
                              ? array_values($monthData[$base_month])
                              : array();

                foreach ($months as $i => $month) {
                    $month_qr = isset($monthData[$month])
                                ? array_values($monthData[$month])
                                : array();

                    if ($i == 0) {
                        $comparison_data[$uc_name][$month] = array(
                            'total'    => count($base_qr),
                            'retained' => count($base_qr),
                            'percent'  => 100
                        );
                    } else {
                        $retained   = count(array_intersect($base_qr, $month_qr));
                        $base_count = count($base_qr);
                        $comparison_data[$uc_name][$month] = array(
                            'total'    => count($month_qr),
                            'retained' => $retained,
                            'percent'  => $base_count > 0
                                          ? round(($retained / $base_count) * 100, 1)
                                          : 0
                        );
                    }
                }
            }
        }

        $data = array(
            'filters'         => $filters,
            'isFilterApplied' => $isFilterApplied,
            'months'          => $months,
            'comparison_data' => $comparison_data
        );

        $data['main_content'] = $this->load->view('reports/vaccination_comparison',$data,true);
        $this->load->view('layout/main',$data);
    }
    
    public function vaccination_detail()
    {
        $uc_name    = $this->input->get('uc_name');
        $month      = $this->input->get('month');
        $base_month = $this->input->get('base_month');

        $start = $month . '-01';
        $end   = date('Y-m-t', strtotime($start));

        if (!empty($base_month)) {

            $base_start = $base_month . '-01';
            $base_end   = date('Y-m-t', strtotime($base_start));

            // Get base month QR codes for this UC
            $this->db->select('m.qr_code');
            $this->db->from('child_health_master m');
            $this->db->join('child_health_detail d', 'm.master_id = d.master_id AND d.question_id IN (5,6,7)', 'inner');
            $this->db->join('uc u', 'u.pk_id = m.uc', 'left');
            $this->db->where('u.uc', $uc_name);
            $this->db->where('DATE(m.form_date) >=', $base_start);
            $this->db->where('DATE(m.form_date) <=', $base_end);
            $this->db->group_by('m.qr_code');
            $base_qr_result = $this->db->get()->result_array();
            $base_qr_codes  = array_column($base_qr_result, 'qr_code');

            if (empty($base_qr_codes)) {
                $data = array(
                    'records'     => array(),
                    'uc_name'     => $uc_name,
                    'month'       => $month,
                    'base_month'  => $base_month,
                    'is_retained' => TRUE
                );
                $data['main_content'] = $this->load->view('reports/vaccination_detail', $data, true);
                $this->load->view('layout/main', $data);
                return;
            }

            // Get retained month records with vaccines
            $this->db->select("
                m.qr_code,
                m.patient_name,
                m.guardian_name,
                m.age_group,
                m.gender,
                m.village,
                m.form_date,
                GROUP_CONCAT(DISTINCT qo.option_text ORDER BY qo.option_order ASC SEPARATOR ', ') AS vaccines
            ", false);
            $this->db->from('child_health_master m');
            $this->db->join('child_health_detail d',   'm.master_id = d.master_id AND d.question_id IN (5,6,7)', 'inner');
            $this->db->join('question_options qo',     'qo.option_id = d.option_id AND qo.question_id = d.question_id', 'left');
            $this->db->join('uc u',                    'u.pk_id = m.uc', 'left');
            $this->db->where('u.uc', $uc_name);
            $this->db->where('DATE(m.form_date) >=', $start);
            $this->db->where('DATE(m.form_date) <=', $end);
            $this->db->where_in('m.qr_code', $base_qr_codes);
            $this->db->group_by('m.master_id');
            $this->db->order_by('m.qr_code', 'ASC');
            $this->db->order_by('m.form_date', 'ASC');
            $result = $this->db->get()->result_array();

            $data = array(
                'records'     => $result,
                'uc_name'     => $uc_name,
                'month'       => $month,
                'base_month'  => $base_month,
                'is_retained' => TRUE
            );

        } else {

            // Simple / base month — all vaccinated children with vaccines
            $this->db->select("
                m.qr_code,
                m.patient_name,
                m.guardian_name,
                m.age_group,
                m.gender,
                m.village,
                m.form_date,
                GROUP_CONCAT(DISTINCT qo.option_text ORDER BY qo.option_order ASC SEPARATOR ', ') AS vaccines
            ", false);
            $this->db->from('child_health_master m');
            $this->db->join('child_health_detail d',   'm.master_id = d.master_id AND d.question_id IN (5,6,7)', 'inner');
            $this->db->join('question_options qo',     'qo.option_id = d.option_id AND qo.question_id = d.question_id', 'left');
            $this->db->join('uc u',                    'u.pk_id = m.uc', 'left');
            $this->db->where('u.uc', $uc_name);
            $this->db->where('DATE(m.form_date) >=', $start);
            $this->db->where('DATE(m.form_date) <=', $end);
            $this->db->group_by('m.master_id');
            $this->db->order_by('m.qr_code', 'ASC');
            $this->db->order_by('m.form_date', 'ASC');
            $result = $this->db->get()->result_array();

            $data = array(
                'records'     => $result,
                'uc_name'     => $uc_name,
                'month'       => $month,
                'base_month'  => NULL,
                'is_retained' => FALSE
            );
        }

        $data['main_content'] = $this->load->view('reports/vaccination_detail', $data, true);
        $this->load->view('layout/main', $data);
    }

}
