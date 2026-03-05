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
                                    echo "<option value='{$value}' "
                                        .(isset($filters['form_type']) && in_array($value,$filters['form_type']) ? 'selected' : '')
                                        .">{$label}</option>";
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
                        <button type="submit" class="btn btn-success">
                            View Data
                        </button>
                        <a href="<?= site_url('reports/view_health_data') ?>" class="btn btn-secondary ml-2">
                            Clear
                        </a>
                    </div>

                </form>
            </div>
        </div>

        <!-- DATA TABLE -->
        <?php if (!empty($table_data)) : ?>

            <div class="card">
                <div class="card-body p-2 table-responsive">

                    <table class="table table-bordered table-hover table-sm compact-table mb-0">
                        <thead class="thead-light">
                            <tr>
                                <?php foreach ($headers as $h): ?>
                                    <th><?= strtoupper(str_replace('_',' ',$h)) ?></th>
                                <?php endforeach; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($table_data as $row): ?>
                                <tr>
                                    <?php foreach ($headers as $h): ?>
                                        <td>
                                            <?php
                                            if (in_array($h, $question_labels)) {
                                                $qid = array_search($h, $question_labels);
                                                echo isset($row['Q'.$qid]) ? $row['Q'.$qid] : '';
                                            } else {
                                                echo isset($row[$h]) ? $row[$h] : '';
                                            }
                                            ?>
                                        </td>
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
</script>