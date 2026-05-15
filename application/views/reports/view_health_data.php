<?php 
$default_start = isset($filters['start']) ? $filters['start'] : '2025-12-01';
$default_end   = isset($filters['end'])   ? $filters['end']   : date('Y-m-d');
?>
<style>
.card-body { padding: 8px !important; }
</style>

<div class="page-container">
    <div class="main-content">

        <!-- Page Header -->
        <div class="page-header">
            <h2 class="header-title">View Raw Data</h2>
        </div>

        <!-- NOTICE -->
        <div class="alert alert-info d-flex align-items-start mb-3" role="alert">            
            <div>
                <i class="anticon anticon-info-circle mr-2 mt-1" style="font-size:16px;"></i>
                <strong>Notice:</strong> Due to computer slowdowns and based on suggestions discussed in the meeting held on 14 May,
                browser data view has been removed. Please select filters and submit to directly download the Excel file.
                Go to <strong>Downloads</strong> and find the generated Excel file there.
            </div>
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
                                $selected_ucs = isset($filters['uc'])
                                    ? (is_array($filters['uc']) ? $filters['uc'] : [$filters['uc']])
                                    : array_map(function($u){ return $u->pk_id; }, $ucs);
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
                                $form_types = ['chf' => 'Child Health Form', 'opd' => 'OPD/MNCH Form'];
                                foreach($form_types as $value => $label):
                                    $selected = (isset($filters['form_type']) && $filters['form_type'] == $value) ? 'selected' : '';
                                ?>
                                    <option value="<?= $value ?>" <?= $selected ?>><?= $label ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <!-- Date Range -->
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
                                    $genders = ['Male','Female'];
                                    $selected_genders = isset($filters['gender'])
                                        ? (is_array($filters['gender']) ? $filters['gender'] : [$filters['gender']])
                                        : $genders;
                                    foreach($genders as $g): ?>
                                        <option value="<?= $g ?>" <?= in_array($g, $selected_genders) ? 'selected' : '' ?>><?= $g ?></option>
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
                                    $age_groups = ['<1 Year','1-2 Year','2-5 Year','5-15 Year','15-49 Year'];
                                    $selected_age_groups = isset($filters['age_group'])
                                        ? (is_array($filters['age_group']) ? $filters['age_group'] : [$filters['age_group']])
                                        : $age_groups;
                                    foreach($age_groups as $ag): ?>
                                        <option value="<?= $ag ?>" <?= in_array($ag, $selected_age_groups) ? 'selected' : '' ?>><?= $ag ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <small class="text-danger error-message" data-for="age_group[]"></small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label>Visit Type *</label>
                            <div class="m-b-15">
                                <?php
                                $visit_types = ['Outreach','Fixed Site'];
                                $selected_visit_types = isset($filters['visit_type'])
                                    ? (is_array($filters['visit_type']) ? $filters['visit_type'] : [$filters['visit_type']])
                                    : $visit_types;
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
                                    $age_groups = ['0-14 Y','15-49','50 Y +'];
                                    $selected_age_groups = isset($filters['age_group'])
                                        ? (is_array($filters['age_group']) ? $filters['age_group'] : [$filters['age_group']])
                                        : $age_groups;
                                    foreach($age_groups as $ag): ?>
                                        <option value="<?= $ag ?>" <?= in_array($ag, $selected_age_groups) ? 'selected' : '' ?>><?= $ag ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <small class="text-danger error-message" data-for="age_group[]"></small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label>Visit Type *</label>
                            <div class="m-b-15">
                                <?php
                                $visit_types = ['OPD','MNCH'];
                                $selected_visit_types = isset($filters['visit_type'])
                                    ? (is_array($filters['visit_type']) ? $filters['visit_type'] : [$filters['visit_type']])
                                    : $visit_types;
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

                    <!-- Data Mode -->
                    <div class="col-md-12 mt-1">
                        <label class="mr-3">Data Mode:</label>
                        <?php $data_mode = isset($filters['data_mode']) ? $filters['data_mode'] : 'unique'; ?>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="data_mode" id="mode_unique" value="unique" <?= $data_mode == 'unique' ? 'checked' : '' ?>>
                            <label class="form-check-label" for="mode_unique">Unique QR Only</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="data_mode" id="mode_all" value="all" <?= $data_mode == 'all' ? 'checked' : '' ?>>
                            <label class="form-check-label" for="mode_all">All Records</label>
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="col-md-12 mt-2 m-b-15 d-flex justify-content-end">
                        <button type="submit" class="btn btn-success btn-sm">
                            <i class="anticon anticon-download mr-1"></i> Download Excel
                        </button>
                        <a href="<?= site_url('reports/view_health_data') ?>" class="btn btn-secondary btn-sm ml-2">Clear</a>
                    </div>

                </form>
            </div>
        </div>

    </div><!-- /.main-content -->

<!-- JS -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="<?= base_url('assets/vendors/select2/select2.min.js') ?>"></script>
<script src="<?= base_url('assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.js') ?>"></script>
<script>
(function($){
    // Select2 & Datepicker init
    $('#filterForm .select2').select2({ placeholder: "Select an option", allowClear: true, width: '100%' });
    $('#filterForm .datepicker-input').datepicker({ format: 'yyyy-mm-dd', autoclose: true, todayHighlight: true });

    // CHF / OPD toggle
    function toggleFormFields(){
        var type = $('#form_type').val();
        if(type === 'chf'){ $('#chf_fields').show(); $('#opd_fields').hide(); }
        else              { $('#chf_fields').hide(); $('#opd_fields').show(); }
    }
    toggleFormFields();
    $('#form_type').on('change', toggleFormFields);

    // Validation + submit
    $('#filterForm').on('submit', function(e){
        e.preventDefault();
        var valid = true;
        $('#filterForm select:visible').each(function(){
            var val = $(this).val();
            if(!val || (Array.isArray(val) && val.length === 0)){ valid = false; }
        });
        if(!valid){ alert("Please select all required fields!"); return false; }

        // filtered=1 flag add karo — controller Excel generate karega
        var action   = $('#filterForm').attr('action');
        var formData = $('#filterForm').serialize() + '&filtered=1';
        window.location.href = action + '?' + formData;
    });
})(jQuery);
</script>