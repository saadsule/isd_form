<?php 
// Default start and end dates
$default_start = isset($filters['start']) ? $filters['start'] : '2025-12-01';
$default_end   = isset($filters['end']) ? $filters['end'] : date('Y-m-d');
?>
<style>
/* Make table ultra compact */
.compact-table {
    font-size: 11px;              /* Smaller font */
}

.compact-table th,
.compact-table td {
    padding: 4px 6px !important;  /* Reduce cell spacing */
    vertical-align: middle;
    white-space: nowrap;          /* Prevent line breaks */
}

/* Make header slightly bold but compact */
.compact-table th {
    font-weight: 600;
}

/* Reduce card padding */
.card-body {
    padding: 8px !important;
}
</style>
<div class="page-container">
    <div class="main-content">

        <!-- Page Header -->
        <div class="page-header">
            <h2 class="header-title">View Raw Data</h2>
        </div>

        <!-- FILTER FORM -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="get" action="<?= site_url('reports/view_health_data') ?>" id="filterForm" class="row g-3">

    <!-- UC Multiple Select -->
    <div class="col-md-4">
        <label>Select UC(s) *</label>
        <div class="m-b-15">
            <select class="select2" name="uc[]" multiple="multiple" style="width:100%">
                <?php
                // Select all by default on first load, otherwise use filtered values
                $selected_ucs = isset($filters['uc'])
                    ? (is_array($filters['uc']) ? $filters['uc'] : [$filters['uc']])
                    : array_map(function($u){ return $u->pk_id; }, $ucs); // select all by default
                foreach($ucs as $u): ?>
                    <option value="<?= $u->pk_id ?>" <?= in_array($u->pk_id, $selected_ucs) ? 'selected' : '' ?>>
                        <?= $u->uc_name ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <small class="text-danger error-message" data-for="uc[]"></small>
        </div>
    </div>

    <!-- Form Type -->
    <div class="col-md-3">
        <label>Select Form Type *</label>
        <div class="m-b-15">
            <select name="form_type" id="form_type" class="form-control">
                <?php
                $form_type = [
                    'chf' => 'Child Health Form',
                    'opd' => 'OPD/MNCH Form'
                ];
                foreach($form_type as $value => $label){
                    $selected = (isset($filters['form_type']) && $filters['form_type'] == $value) ? 'selected' : '';
                    echo "<option value='{$value}' {$selected}>{$label}</option>";
                }
                ?>
            </select>
        </div>
    </div>

    <!-- Date Range Picker -->
    <div class="col-md-5">
        <label>Select Date Range *</label>
        <div class="d-flex align-items-center m-b-15">
            <input type="text" class="form-control datepicker-input" name="start" placeholder="From" autocomplete="off" value="<?= $default_start ?>">
            <span class="p-h-10">to</span>
            <input type="text" class="form-control datepicker-input" name="end" placeholder="To" autocomplete="off" value="<?= $default_end ?>">
        </div>
    </div>

    <!-- CHF Fields -->
    <div id="chf_fields" class="row w-100" style="margin-left:5px !important;">

        <div class="col-md-4">
            <label>Select Gender *</label>
            <div class="m-b-15">
                <select class="select2" name="gender[]" multiple="multiple" style="width:100%">
                    <?php
                    $genders = ['Male', 'Female'];
                    $selected_genders = isset($filters['gender'])
                        ? (is_array($filters['gender']) ? $filters['gender'] : [$filters['gender']])
                        : $genders; // select all by default
                    foreach($genders as $g): ?>
                        <option value="<?= $g ?>" <?= in_array($g, $selected_genders) ? 'selected' : '' ?>>
                            <?= $g ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <small class="text-danger error-message" data-for="gender[]"></small>
            </div>
        </div>

        <div class="col-md-4">
            <label>Select Age Group *</label>
            <div class="m-b-15">
                <select class="select2" name="age_group[]" multiple="multiple" style="width:100%">
                    <?php
                    $age_groups = ['<1 Year', '1-2 Year', '2-5 Year', '5-15 Year', '15-49 Year'];
                    $selected_age_groups = isset($filters['age_group'])
                        ? (is_array($filters['age_group']) ? $filters['age_group'] : [$filters['age_group']])
                        : $age_groups; // select all by default
                    foreach($age_groups as $ag): ?>
                        <option value="<?= $ag ?>" <?= in_array($ag, $selected_age_groups) ? 'selected' : '' ?>>
                            <?= $ag ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <small class="text-danger error-message" data-for="age_group[]"></small>
            </div>
        </div>

        <div class="col-md-4">
            <label>Visit Type *</label>
            <div class="m-b-15">
                <?php
                $visit_types = ['Outreach', 'Fixed Site'];
                $selected_visit_types = isset($filters['visit_type']) ? (is_array($filters['visit_type']) ? $filters['visit_type'] : [$filters['visit_type']]) : $visit_types;
                ?>
                <select class="select2" name="visit_type[]" multiple="multiple" style="width:100%">
                    <?php foreach($visit_types as $vt): ?>
                        <option value="<?= $vt ?>" <?= in_array($vt, $selected_visit_types) ? 'selected' : '' ?>><?= $vt ?></option>
                    <?php endforeach; ?>
                </select>
                <small class="text-danger error-message" data-for="visit_type[]"></small>
            </div>
        </div>
    </div>

    <!-- OPD Fields -->
    <div id="opd_fields" class="row w-100" style="margin-left:5px !important;">

        <div class="col-md-4">
            <label>Select Age Group *</label>
            <div class="m-b-15">
                <select class="select2" name="age_group[]" multiple="multiple" style="width:100%">
                    <?php
                    $age_groups = ['0-14 Y', '15-49', '50 Y +'];
                    $selected_age_groups = isset($filters['age_group'])
                        ? (is_array($filters['age_group']) ? $filters['age_group'] : [$filters['age_group']])
                        : $age_groups; // select all by default
                    foreach($age_groups as $ag): ?>
                        <option value="<?= $ag ?>" <?= in_array($ag, $selected_age_groups) ? 'selected' : '' ?>>
                            <?= $ag ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <small class="text-danger error-message" data-for="age_group[]"></small>
            </div>
        </div>

        <div class="col-md-4">
            <label>Visit Type *</label>
            <div class="m-b-15">
                <?php
                $visit_types = ['OPD', 'MNCH'];
                $selected_visit_types = isset($filters['visit_type']) ? (is_array($filters['visit_type']) ? $filters['visit_type'] : [$filters['visit_type']]) : $visit_types;
                ?>
                <select class="select2" name="visit_type[]" multiple="multiple" style="width:100%">
                    <?php foreach($visit_types as $vt): ?>
                        <option value="<?= $vt ?>" <?= in_array($vt, $selected_visit_types) ? 'selected' : '' ?>><?= $vt ?></option>
                    <?php endforeach; ?>
                </select>
                <small class="text-danger error-message" data-for="visit_type[]"></small>
            </div>
        </div>
    </div>

    <!-- Buttons -->
    <div class="col-md-12 mt-2 m-b-15 d-flex justify-content-end">
        <button type="submit" class="btn btn-success btn-sm">View Data</button>
        <a href="<?= site_url('reports/view_health_data') ?>" class="btn btn-secondary btn-sm ml-2">Clear</a>
    </div>

</form>
            </div>
        </div>

        <!-- DATA TABLE -->
        <?php if (!empty($table_data)) : ?>

            <div class="d-flex justify-content-end mb-2">
                <button id="exportBtn" class="btn btn-success btn-sm" style="padding: .15rem 0.5rem !important" title="Export to Excel">
                    <i class="anticon anticon-file-excel"></i>
                </button>
            </div>
            <div class="card">
                <div class="card-body p-2 table-responsive">
                    <table class="table table-bordered table-hover table-sm compact-table mb-0">

<thead class="thead-light">

<!-- Main headers -->
<tr>
<?php foreach ($headers as $h): ?>
    <?php if(strpos($h,'Q')===0): ?>
        <?php 
        $qid = str_replace('Q','',$h);
        $colspan = isset($question_options[$qid]) ? count($question_options[$qid]) : 1;
        ?>
        <th colspan="<?= $colspan ?>" class="text-center">
            <?= isset($question_labels[$qid]) ? $question_labels[$qid] : $h ?>
        </th>
        <?php elseif($h == 'view'): ?>
        <th class="text-center">VIEW</th>
        <?php else: ?>
        <th class="text-center"><?= strtoupper(str_replace('_',' ',$h)) ?></th>
        <?php endif; ?>
<?php endforeach; ?>
</tr>

<!-- Sub headers for question options -->
<tr>
<?php foreach ($headers as $h): ?>
    <?php if(strpos($h,'Q')===0): ?>
        <?php 
        $qid = str_replace('Q','',$h);
        if(isset($question_options[$qid]) && count($question_options[$qid]) > 0):
            foreach($question_options[$qid] as $opt): ?>
                <th class="text-center"><?= $opt['option_text'] ?></th>
            <?php endforeach; 
        else: ?>
            <th></th>
        <?php endif; ?>
    <?php else: ?>
        <th></th>
    <?php endif; ?>
<?php endforeach; ?>
</tr>

</thead>

<tbody>

<?php foreach ($table_data as $row): ?>
<tr>
<?php foreach ($headers as $h): ?>

<?php if(strpos($h,'Q')===0): ?>

    <?php 
    $qid = str_replace('Q','',$h);
    if(isset($question_options[$qid]) && count($question_options[$qid]) > 0):
        foreach($question_options[$qid] as $opt): ?>
            <td class="text-center"><?= isset($row[$opt['column']]) ? $row[$opt['column']] : '' ?></td>
        <?php endforeach; 
    else: ?>
        <td></td>
    <?php endif; ?>

<?php elseif($h == 'view'): ?>

<td class="text-center">

<?php if($filters['form_type'] == 'chf'): ?>

<a style="padding: .1rem 0.4rem;" href="<?= base_url('forms/view_child_health/'.$row['master_id']) ?>"
class="btn btn-success btn-sm" title="View">
<i class="fa fa-eye"></i>
</a>

<?php elseif($filters['form_type'] == 'opd'): ?>

<a style="padding: .1rem 0.4rem;" href="<?= base_url('forms/view_opd_mnch/'.$row['id']) ?>"
class="btn btn-success btn-sm" title="View">
<i class="fa fa-eye"></i>
</a>

<?php endif; ?>

</td>

<?php elseif($h == 'qr_code'): ?>

<td>
<a href="<?= site_url('reports/qr_code_report/'.$row['qr_code'].'/'.$filters['form_type']) ?>" target="_blank">
<?= $row['qr_code'] ?>
</a>
</td>

<?php else: ?>

<td><?= isset($row[$h]) ? $row[$h] : '' ?></td>

<?php endif; ?>

<?php endforeach; ?>
</tr>
<?php endforeach; ?>

</tbody>

</table>
                </div>
            </div>
        <?php elseif(isset($filters['form_type'])): ?>

            <div class="alert alert-warning">
                No data found for selected filters.
            </div>

        <?php endif; ?>

    </div>
</div>


<!-- JS Libraries for this page -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="<?= base_url('assets/vendors/select2/select2.min.js') ?>"></script>
<script src="<?= base_url('assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.js') ?>"></script>

<!-- Page-specific initialization -->
<script>
    (function($){
        $('#filterForm .select2').select2({
            placeholder: "Select an option",
            allowClear: true,
            width: '100%'
        });
        $('#filterForm .datepicker-input').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            todayHighlight: true
        });
    })(jQuery);
    
    var exportBtn = document.getElementById("exportBtn");
    if(exportBtn){
        exportBtn.addEventListener("click", function () {
            var table = document.querySelector("table").cloneNode(true);
            var cells = table.querySelectorAll("th, td");
            cells.forEach(function(cell) {
                cell.style.border = "1px solid #999";
                cell.style.padding = "8px";
                cell.style.textAlign = "center";
                cell.style.fontFamily = "Calibri, Arial, sans-serif";
                cell.style.fontSize = "13px";
            });
            var headers = table.querySelectorAll("th");
            headers.forEach(function(th) {
                th.style.backgroundColor = "#D9EAF7";
                th.style.color = "#000";
                th.style.fontWeight = "bold";
                th.style.fontSize = "14px";
                th.style.padding = "10px";
                th.style.border = "1px solid #7FA6C7";
            });
            var rows = table.querySelectorAll("tr");
            rows.forEach(function(row, index) {
                if(index > 1 && index % 2 === 0){
                    row.style.backgroundColor = "#F7FBFF";
                }
            });
            table.style.borderCollapse = "collapse";
            table.style.width = "100%";
            var html = `
            <html xmlns:o="urn:schemas-microsoft-com:office:office"
            xmlns:x="urn:schemas-microsoft-com:office:excel"
            xmlns="http://www.w3.org/TR/REC-html40">
            <head>
            <!--[if gte mso 9]>
            <xml>
            <x:ExcelWorkbook>
            <x:ExcelWorksheets>
            <x:ExcelWorksheet>
            <x:Name>Report</x:Name>
            <x:WorksheetOptions>
            <x:FreezePanes/>
            <x:FrozenNoSplit/>
            <x:SplitHorizontal>2</x:SplitHorizontal>
            <x:TopRowBottomPane>2</x:TopRowBottomPane>
            <x:ActivePane>2</x:ActivePane>
            </x:WorksheetOptions>
            </x:ExcelWorksheet>
            </x:ExcelWorksheets>
            </x:ExcelWorkbook>
            </xml>
            <![endif]-->
            </head>
            <body>` + table.outerHTML + `</body></html>`;
            var blob = new Blob(["\ufeff", html], {
                type: "application/vnd.ms-excel;charset=utf-8;"
            });
            var link = document.createElement("a");
            link.href = URL.createObjectURL(blob);
            link.download = "report.xls";
            link.click();
        });
    }

    $(document).ready(function(){
        // Toggle CHF / OPD Fields
        function toggleFormFields(){
            var type = $('#form_type').val();
            if(type === 'chf'){
                $('#chf_fields').show();
                $('#opd_fields').hide();
            } else if(type === 'opd'){
                $('#chf_fields').hide();
                $('#opd_fields').show();
            }
        }
        toggleFormFields();
        $('#form_type').on('change', toggleFormFields);

        // Simple Form Validation
        $('#filterForm').on('submit', function(e){
            e.preventDefault();
            let valid = true;
            // Check all visible select fields
            $('#filterForm select:visible').each(function(){
                let val = $(this).val();
                if(!val || (Array.isArray(val) && val.length === 0)){
                    valid = false;
                }
            });
            if(!valid){
                alert("Please select all required fields!");
                return false;
            }
            // Append filtered=1 flag and submit
            let action = $('#filterForm').attr('action') || window.location.pathname;
            let formData = $('#filterForm').serialize() + '&filtered=1';
            window.location.href = action + '?' + formData;
        });

        // Auto-submit on first load (when filtered=1 is not in URL)
        if(!window.location.search.includes('filtered=1')){
            $('#filterForm').submit();
        }
    });
</script>