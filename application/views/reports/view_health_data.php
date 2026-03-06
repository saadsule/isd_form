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
                                <?php foreach($ucs as $u): ?>
                                    <option value="<?= $u->pk_id ?>" <?= (isset($filters['uc']) && in_array($u->pk_id,$filters['uc'])) ? 'selected' : '' ?>>
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
                            <select name="form_type" class="form-control">
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
                            <small class="text-danger error-message" data-for="form_type[]"></small>
                        </div>
                    </div>

                    <!-- Date Range Picker -->
                    <div class="col-md-5">
                        <label>Select Date Range *</label>
                        <div class="d-flex align-items-center m-b-15">
                            <input type="text" class="form-control datepicker-input" name="start" placeholder="From" 
                                   autocomplete="off"
                                   value="<?= isset($filters['start']) ? $filters['start'] : '' ?>" required="">
                            <span class="p-h-10">to</span>
                            <input type="text" class="form-control datepicker-input" name="end" placeholder="To" 
                                   autocomplete="off"
                                   value="<?= isset($filters['end']) ? $filters['end'] : '' ?>" required="">
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="col-md-12 mt-2 m-b-15 d-flex justify-content-end">
                        <button type="submit" class="btn btn-success btn-sm">
                            View Data
                        </button>
                        <a href="<?= site_url('reports/view_health_data') ?>" class="btn btn-secondary btn-sm ml-2">
                            Clear
                        </a>
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
    
   document.getElementById("exportBtn").addEventListener("click", function () {

        var table = document.querySelector("table").cloneNode(true);

        // Style cells
        var cells = table.querySelectorAll("th, td");
        cells.forEach(function(cell) {
            cell.style.border = "1px solid #999";
            cell.style.padding = "8px";
            cell.style.textAlign = "center";
            cell.style.fontFamily = "Calibri, Arial, sans-serif";
            cell.style.fontSize = "13px";
        });

        // Light blue header
        var headers = table.querySelectorAll("th");
        headers.forEach(function(th) {
            th.style.backgroundColor = "#D9EAF7";
            th.style.color = "#000";
            th.style.fontWeight = "bold";
            th.style.fontSize = "14px";
            th.style.padding = "10px";
            th.style.border = "1px solid #7FA6C7";
        });

        // Alternate row colors
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
            <!-- Freeze first 2 rows -->
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
    
</script>