<div class="page-container">
    <div class="main-content">

        <!-- Page Header -->
        <div class="page-header">
            <h2 class="header-title">Export Child OR OPD/MNCH Form Data</h2>
        </div>

        <!-- FILTERS -->
        <div class="row mb-3">
            <div class="col-12">
                <form method="get" action="<?= site_url('reports/export_health_data_excel') ?>" id="filterForm" class="row g-2 align-items-end">

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
                                $form_type = ['chf','opd'];
                                foreach($form_type as $g){
                                    echo "<option value='{$g}' ".(isset($filters['form_type']) && in_array($g,$filters['form_type']) ? 'selected' : '').">{$g}</option>";
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
                        <button type="submit" class="btn btn-primary">Export in Excel</button>
                        <a href="<?= base_url('reports/export_health_data') ?>" class="btn btn-secondary ml-2">Clear Filters</a>
                    </div>

                </form>
            </div>
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