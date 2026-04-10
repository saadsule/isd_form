<?php if($this->session->flashdata('success')): ?>
    <div id="flash-msg" class="flash-msg flash-success">
        <i class="anticon anticon-file-done"></i>
        <?= $this->session->flashdata('success') ?>
    </div>
<?php elseif($this->session->flashdata('error')): ?>
    <div id="flash-msg" class="flash-msg flash-error">
        <i class="anticon anticon-close-circle"></i>
        <?= $this->session->flashdata('error') ?>
    </div>
<?php endif; ?>

<style>
/* ── Flash ── */
.flash-msg {
    position: fixed; top: 20%; left: 50%;
    transform: translateX(-50%); z-index: 9999;
    padding: 18px 35px; font-size: 18px; border-radius: 12px;
    box-shadow: 0 6px 20px rgba(0,0,0,.25);
    display: flex; align-items: center; gap: 12px;
    color: #fff; font-weight: 600;
    animation: fadeIn 0.5s ease-in-out;
}
.flash-success { background: #28a745; }
.flash-error   { background: #dc3545; }
@keyframes fadeIn {
    from { opacity:0; transform:translateX(-50%) translateY(-20px); }
    to   { opacity:1; transform:translateX(-50%) translateY(0);     }
}
.flash-msg .anticon { font-size: 22px; }

/* ── Page ── */
.page-container { background: #f4f6f9; }
.card { border: none; border-radius: 14px; box-shadow: 0 6px 18px rgba(0,0,0,.06); }
.form-header { background: white; padding: 22px; border-radius: 14px; margin-bottom: 25px; }
.form-section { border-left: 7px solid #28a745; transition: .2s; }
.form-section:hover { transform: translateY(-2px); }
.section-title { font-weight: 800; color: #2c3e50; margin-bottom: 25px; }
.col-form-label { font-weight: 600; color: #34495e; }
.form-control { border-radius: 10px; height: 45px; }
.form-check-label { font-weight: 500; }
.q-num { color: #28a745; font-weight: 800; margin-right: 6px; }
.save-btn {
    padding: 16px 70px; font-size: 20px; border-radius: 14px;
    font-weight: 700; box-shadow: 0 6px 16px rgba(40,167,69,.25);
}
.form-check-input:focus {
    outline: 3px solid rgb(63 135 245 / 20%) !important;
    outline-offset: 2px; box-shadow: 0 0 0 4px rgb(63 135 245 / 20%);
}

/* ── QR Search Card (outside form) ── */
#qr-search-card {
    background: #fff;
    border-radius: 14px;
    padding: 24px 28px 20px;
    margin-bottom: 22px;
    box-shadow: 0 4px 16px rgba(0,90,200,.08);
    border-left: 6px solid #007bff;
    position: relative;
}
#qr-search-card::before {
    content: '';
    position: absolute;
    top: 0; right: 0;
    width: 120px; height: 100%;
    background: linear-gradient(135deg, transparent 60%, rgba(0,123,255,.04));
    border-radius: 0 14px 14px 0;
    pointer-events: none;
}
#qr-search-card h5 {
    font-weight: 800; color: #1a3a5c;
    margin-bottom: 3px; font-size: 16px;
    display: flex; align-items: center; gap: 8px;
}
#qr-search-card small { color: #6c757d; font-size: 13px; }

#qr_external_input {
    border-radius: 10px 0 0 10px !important;
    height: 46px; font-size: 15px;
    border: 2px solid #007bff; border-right: none;
    transition: border-color .2s; font-weight: 500;
}
#qr_external_input:focus {
    border-color: #0056b3;
    box-shadow: 0 0 0 3px rgba(0,123,255,.12);
}
#qr_confirm_btn {
    background: #007bff !important; color: #fff !important;
    border: 2px solid #007bff !important;
    border-radius: 0 10px 10px 0 !important;
    font-weight: 700; font-size: 14px;
    padding: 0 22px; cursor: pointer;
    transition: background .2s;
    display: flex; align-items: center; gap: 6px;
}
#qr_confirm_btn:hover { background: #0056b3 !important; border-color: #0056b3 !important; }

/* QR Status Badge */
.qr-status-badge {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 6px 14px; border-radius: 20px; font-size: 13px;
    font-weight: 600; margin-top: 8px; animation: slideIn .3s ease-out;
    cursor: default; transition: all .2s ease;
}
.qr-status-badge.clickable { cursor: pointer; }
.qr-status-badge.clickable:hover { transform: translateY(-1px); box-shadow: 0 2px 8px rgba(0,0,0,.12); }
.qr-status-unique  { background:#d4edda; color:#155724; border:1px solid #c3e6cb; }
.qr-status-exists  { background:#d1ecf1; color:#0c5460; border:1px solid #bee5eb; }
.qr-status-checking{ background:#fff3cd; color:#856404; border:1px solid #ffeeba; }
.qr-status-error   { background:#f8d7da; color:#721c24; border:1px solid #f5c6cb; }

/* QR Spinner */
.qr-spinner {
    display: inline-block; width: 13px; height: 13px;
    border: 2px solid #ffc107; border-top-color: transparent;
    border-radius: 50%; animation: spin 1s linear infinite;
}
@keyframes spin { to { transform: rotate(360deg); } }

/* Records hint list */
#qr-records-list {
    margin-top: 14px; border-radius: 10px; overflow: hidden;
    border: 1px solid #b8d0f7; display: none;
    box-shadow: 0 2px 12px rgba(0,90,200,.08);
}
#qr-records-list .qr-list-header {
    background: linear-gradient(90deg, #0069d9, #17a2b8);
    color: #fff; padding: 10px 16px;
    font-size: 13px; font-weight: 700;
    display: flex; align-items: center; gap: 8px;
}
.qr-hint-row {
    display: flex; align-items: center; gap: 14px;
    padding: 11px 16px; border-bottom: 1px solid #e9ecef;
    cursor: pointer; transition: background .15s; background: #fff;
    font-size: 13px;
}
.qr-hint-row:last-child { border-bottom: none; }
.qr-hint-row:hover { background: #f0f6ff; }
.qr-hint-row.selected { background: #d4edda; border-left: 4px solid #28a745; }
.qr-hint-row .badge-date {
    background: #e8f0fe; color: #1a3a5c; border-radius: 6px;
    padding: 3px 10px; font-size: 12px; font-weight: 700;
    white-space: nowrap; flex-shrink: 0; border: 1px solid #b8d0f7;
}
.qr-hint-row .patient-name { font-weight: 700; color: #1a3a5c; font-size: 14px; }
.qr-hint-row .patient-meta { color: #6c757d; font-size: 12px; margin-top: 1px; }
.qr-hint-row .load-arrow {
    margin-left: auto; font-size: 12px; color: #007bff;
    font-weight: 600; white-space: nowrap; flex-shrink: 0;
}

/* Locked field */
.field-locked {
    background-color: #f0f4f8 !important; border-color: #cdd5df !important;
    color: #555 !important; cursor: not-allowed !important;
}
.field-locked:focus {
    box-shadow: none !important; border-color: #cdd5df !important;
    background-color: #f0f4f8 !important;
}
input[type="radio"].field-locked,
input[type="checkbox"].field-locked { cursor: not-allowed !important; pointer-events: none; }

/* Lock indicator */
.lock-indicator {
    background: #fffbea; border-left: 4px solid #ffc107;
    border-radius: 4px; margin-top: 15px; padding: 12px 15px;
    animation: slideDown .3s ease-out; font-size: 14px;
}
@keyframes slideIn  { from{opacity:0;transform:translateX(-10px)} to{opacity:1;transform:translateX(0)} }
@keyframes slideDown{ from{opacity:0;transform:translateY(-10px)} to{opacity:1;transform:translateY(0)} }

/* QR preview box */
#qr_preview_external {
    padding: 5px; border: 1px solid #ddd;
    border-radius: 6px; display: inline-block;
}

/* Form reveal animation */
#main-form-wrapper {
    animation: formReveal .4s ease-out;
}
@keyframes formReveal {
    from { opacity:0; transform:translateY(16px); }
    to   { opacity:1; transform:translateY(0);    }
}
</style>

<div class="page-container">
<div class="main-content">

<div class="page-header">
    <h1 class="header-title">Child Health Form</h1>
</div>

<!-- ====================================================== -->
<!-- QR CODE SEARCH — OUTSIDE THE FORM -->
<!-- ====================================================== -->
<div id="qr-search-card">
    <h5>🔍 Patient QR Code Lookup</h5>
    <small>Enter the patient's QR code to load their previous record, or start a new one.</small>

    <div class="form-group row mt-3 mb-1">
        <div class="col-sm-5">
            <div class="input-group">
                <input type="text"
                       id="qr_external_input"
                       class="form-control"
                       placeholder="Scan or type QR code…"
                       autocomplete="off">
                <div class="input-group-append">
                    <span class="input-group-text" style="background:#007bff;color:#fff;border-color:#007bff;border-radius:0 10px 10px 0;cursor:pointer;" id="qr_confirm_btn">
                        ✓ Confirm
                    </span>
                </div>
            </div>
            <div id="qr_status_area"></div>
        </div>
    </div>

    <!-- Inline records hint -->
    <div id="qr-records-list">
        <div class="qr-list-header" id="qr-list-title">Previous records found — click a row to load:</div>
        <div id="qr-records-body"></div>
    </div>
</div>

<!-- ====================================================== -->
<!-- MAIN FORM — hidden until QR is resolved -->
<!-- ====================================================== -->
<div id="main-form-wrapper" style="display:none;">

<?php
$rec     = isset($record)  ? $record  : null;
$details = isset($details) ? $details : array();
?>

<form method="post"
      id="chf-form"
      action="<?= isset($is_edit) && $is_edit
          ? base_url('forms/update_child_health/'.$record->master_id)
          : base_url('forms/save_child_health') ?>">

<!-- Hidden QR field that mirrors the external input -->
<input type="hidden" name="qr_code" id="qr_code_hidden"
       value="<?= isset($rec->qr_code) ? $rec->qr_code : '' ?>">

<div class="card">
<div class="card-body">

<!-- HEADER -->
<div class="form-header d-flex justify-content-between align-items-center flex-wrap">
    <div>
        <h2 style="font-weight:800;margin:0;">Child Health Record Form</h2>
        <small style="color:gray;">Child Vaccination &amp; Monitoring Record</small>
    </div>
    <div class="d-flex flex-wrap">
        <img src="<?= base_url('assets/images/logo/kp_logo.png') ?>"       style="height:60px;margin-right:10px;">
        <img src="<?= base_url('assets/images/logo/integral_global.png') ?>" style="height:55px;margin-right:10px;">
        <img src="<?= base_url('assets/images/logo/dsi_logo.png') ?>"       style="height:55px;margin-right:10px;">
        <img src="<?= base_url('assets/images/logo/pf.png') ?>"             style="height:55px;">
    </div>
</div>

<!-- ================= BASIC INFORMATION ================= -->
<div class="card mb-4 form-section">
<div class="card-body">
<h4 class="section-title">📋 Basic Information</h4>

<!-- Visit Type -->
<div class="mb-4 text-center">
    <div class="mt-2 d-flex justify-content-center">
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="visit_type" value="Fixed Site" required
                <?= (isset($rec->visit_type) && $rec->visit_type=='Fixed Site') ? 'checked' : '' ?>>
            <label class="form-check-label">Fixed Site</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="visit_type" value="Outreach" required
                <?= (isset($rec->visit_type) && $rec->visit_type=='Outreach') ? 'checked' : '' ?>>
            <label class="form-check-label">Outreach</label>
        </div>
    </div>
</div>

<!-- Row 1: Date | QR Code # | QR Preview | Client Type  (mirrors original layout) -->
<div class="form-group row align-items-end">

    <div class="col-sm-3">
        <label class="form-label font-weight-bold d-block">1. Date *</label>
        <input type="date" name="form_date" class="form-control"
               value="<?= isset($rec->form_date) ? $rec->form_date : '' ?>"
               required id="form_date_2">
    </div>

    <div class="col-sm-3">
        <label class="form-label font-weight-bold d-block">2. QR Code #</label>
        <div id="qr_value_display"
             style="height:45px;display:flex;align-items:center;
                    font-size:13px;font-weight:700;color:#1a3a5c;
                    background:#eef4ff;border:1px solid #b8d0f7;
                    border-radius:10px;padding:0 12px;
                    letter-spacing:.4px;overflow:hidden;white-space:nowrap;text-overflow:ellipsis;">
            —
        </div>
    </div>

    <div class="col-sm-2 d-flex align-items-end" style="padding-bottom:2px;">
        <div id="qr_preview_form"
             style="padding:4px;border:1px solid #dee2e6;border-radius:8px;
                    background:#fff;display:inline-block;line-height:0;min-width:50px;min-height:50px;">
        </div>
    </div>

    <div class="col-sm-4">
        <label class="form-label font-weight-bold d-block">Client Type *</label>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="client_type" value="New" required
                <?= (isset($rec->client_type) && $rec->client_type=='New') ? 'checked' : '' ?>>
            <label class="form-check-label fw-normal">New Client</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="client_type" value="Followup" required
                <?= (isset($rec->client_type) && $rec->client_type=='Followup') ? 'checked' : '' ?>>
            <label class="form-check-label fw-normal">Follow-up</label>
        </div>
    </div>

</div>

<div class="form-group row">
    <label class="col-sm-2 col-form-label">3. District *</label>
    <div class="col-sm-4">
        <select name="district" id="district" class="form-control" required>
            <?php foreach($districts as $district): ?>
            <option value="<?= $district->district_id ?>"
                <?= (isset($rec->district) && $rec->district==$district->district_id)?'selected':'' ?>>
                <?= $district->district_name ?>
            </option>
            <?php endforeach; ?>
        </select>
    </div>
    <label class="col-sm-2 col-form-label">4. UC *</label>
    <div class="col-sm-4">
        <select name="uc" id="uc" class="form-control" required>
            <option value="">Select UC</option>
        </select>
    </div>
</div>

<div class="form-group row" id="facility-field">
    <label class="col-sm-2 col-form-label">Facility</label>
    <div class="col-sm-4">
        <select name="facility_id" id="facility" class="form-control">
            <option value="">Select Facility</option>
            <?php if(isset($facilities) && $facilities): ?>
                <?php foreach($facilities as $f): ?>
                    <option value="<?= $f->id ?>"
                        <?= (isset($rec->facility_id) && $rec->facility_id==$f->id)?'selected':'' ?>>
                        <?= htmlspecialchars($f->facility_name) ?>
                    </option>
                <?php endforeach; ?>
            <?php endif; ?>
        </select>
    </div>
</div>

<div class="form-group row">
    <label class="col-sm-2 col-form-label">5. HF/Village *</label>
    <div class="col-sm-4">
        <input type="text" name="village" class="form-control"
               value="<?= isset($rec->village) ? $rec->village : '' ?>" required>
    </div>
    <label class="col-sm-2 col-form-label">6. Vaccinator name *</label>
    <div class="col-sm-4">
        <input type="text" name="vaccinator_name" class="form-control"
               value="<?= isset($rec->vaccinator_name) ? $rec->vaccinator_name : '' ?>" required>
    </div>
</div>

<div class="form-group row">
    <label class="col-sm-2 col-form-label">7. Patient's Name *</label>
    <div class="col-sm-4">
        <input type="text" name="patient_name" class="form-control"
               value="<?= isset($rec->patient_name) ? $rec->patient_name : '' ?>" required>
    </div>
    <label class="col-sm-2 col-form-label">8. Father/Husband's Name *</label>
    <div class="col-sm-4">
        <input type="text" name="guardian_name" class="form-control"
               value="<?= isset($rec->guardian_name) ? $rec->guardian_name : '' ?>" required>
    </div>
</div>

</div><!-- /card-body basic -->
</div><!-- /card basic -->

<!-- ================= DEMOGRAPHICS ================= -->
<div class="card mb-4 form-section">
<div class="card-body">
<h4 class="section-title">👶 Demographics</h4>

<div class="form-group row">
    <label class="col-sm-2 col-form-label">9. Date of Birth</label>
    <div class="col-sm-4">
        <input type="date" id="dob" name="dob" class="form-control"
               value="<?= isset($rec->dob) ? $rec->dob : '' ?>"
               max="<?= date('Y-m-d', strtotime('-1 day')) ?>">
    </div>
</div>

<div class="form-group row">
    <label class="col-sm-2 col-form-label">10. Age *</label>
    <div class="col-sm-2">
        <input type="number" id="age_year"  name="age_year"  class="form-control" placeholder="Y"
               value="<?= isset($rec->age_year)  ? $rec->age_year  : '' ?>" required>
    </div>
    <div class="col-sm-2">
        <input type="number" id="age_month" name="age_month" class="form-control" placeholder="M"
               value="<?= isset($rec->age_month) ? $rec->age_month : '' ?>" required>
    </div>
    <div class="col-sm-2">
        <input type="number" id="age_day"   name="age_day"   class="form-control" placeholder="D"
               value="<?= isset($rec->age_day)   ? $rec->age_day   : '' ?>" required>
    </div>
</div>

<div class="form-group row">
    <label class="col-sm-2 col-form-label">Age Group *</label>
    <div class="col-sm-10">
        <?php
        $selected_group = isset($rec->age_group) ? $rec->age_group : '';
        foreach(['<1 Year','1-2 Year','2-5 Year','5-15 Year','15-49 Year'] as $group): ?>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="age_group" value="<?= $group ?>" required
                    <?= ($selected_group==$group)?'checked':'' ?>>
                <label class="form-check-label"><?= $group ?></label>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<hr>

<div class="form-group row">
    <label class="col-sm-2 col-form-label">11. Gender *</label>
    <div class="col-sm-4">
        <?php $gender = isset($rec->gender) ? $rec->gender : ''; ?>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="gender" value="Male"
                <?= ($gender=='Male')?'checked':'' ?> required>
            <label class="form-check-label">Male</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="gender" value="Female"
                <?= ($gender=='Female')?'checked':'' ?> required>
            <label class="form-check-label">Female</label>
        </div>
    </div>
    <label class="col-sm-2 col-form-label">12. Marital Status</label>
    <div class="col-sm-4">
        <?php $marital = isset($rec->marital_status) ? $rec->marital_status : ''; ?>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="marital_status" value="Married"
                <?= ($marital=='Married')?'checked':'' ?>>
            <label class="form-check-label">Married</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="marital_status" value="Un-Married"
                <?= ($marital=='Un-Married')?'checked':'' ?>>
            <label class="form-check-label">Un-Married</label>
        </div>
    </div>
</div>

<div class="form-group row">
    <label class="col-sm-2 col-form-label">13. Pregnancy Status</label>
    <div class="col-sm-4">
        <?php $preg = isset($rec->pregnancy_status) ? $rec->pregnancy_status : ''; ?>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="pregnancy_status" value="Pregnant"
                <?= ($preg=='Pregnant')?'checked':'' ?>>
            <label class="form-check-label">Pregnant</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="pregnancy_status" value="Non-Pregnant"
                <?= ($preg=='Non-Pregnant')?'checked':'' ?>>
            <label class="form-check-label">Non-Pregnant</label>
        </div>
    </div>
    <label class="col-sm-2 col-form-label">14. Disability</label>
    <div class="col-sm-4">
        <?php $dis = isset($rec->disability) ? $rec->disability : ''; ?>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="disability" value="Yes"
                <?= ($dis=='Yes')?'checked':'' ?>>
            <label class="form-check-label">Yes</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="disability" value="No"
                <?= ($dis=='No')?'checked':'' ?>>
            <label class="form-check-label">No</label>
        </div>
    </div>
</div>

<div class="form-group row">
    <label class="col-sm-2 col-form-label">15. Play &amp; Learning Kit</label>
    <div class="col-sm-4">
        <?php $kit = isset($rec->play_learning_kit) ? $rec->play_learning_kit : ''; ?>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="play_learning_kit" value="Yes"
                <?= ($kit=='Yes')?'checked':'' ?>>
            <label class="form-check-label">Yes</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="play_learning_kit" value="No"
                <?= ($kit=='No')?'checked':'' ?>>
            <label class="form-check-label">No</label>
        </div>
    </div>
    <label class="col-sm-2 col-form-label">16. Nutrition Package</label>
    <div class="col-sm-4">
        <?php $nut = isset($rec->nutrition_package) ? $rec->nutrition_package : ''; ?>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="nutrition_package" value="Yes"
                <?= ($nut=='Yes')?'checked':'' ?>>
            <label class="form-check-label">Yes</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="nutrition_package" value="No"
                <?= ($nut=='No')?'checked':'' ?>>
            <label class="form-check-label">No</label>
        </div>
    </div>
</div>

</div><!-- /card-body demographics -->
</div><!-- /card demographics -->

<!-- ================= DYNAMIC QUESTIONS ================= -->
<?php
$sections = [];
foreach($questions as $q){
    $sections[$q->q_section][] = $q;
}
?>

<?php foreach($sections as $section_name => $section_questions): ?>
<div class="card mb-4 form-section">
<div class="card-body">
<h4 class="section-title">🩺 <?= htmlspecialchars($section_name) ?></h4>

<?php foreach($section_questions as $q): ?>
<div class="form-group row">

<?php if(isset($q->q_text) && !empty($q->q_text)): ?>
    <label class="col-sm-5 col-form-label">
        <span class="q-num"><?= $q->q_num ?></span>
        <?= htmlspecialchars($q->q_text) ?>
    </label>
<?php endif; ?>

<div class="<?= (in_array($q->question_id,[5,6,7]) && $q->q_type=='checkbox') ? 'col-sm-12' : 'col-sm-7' ?>">

<?php $options = isset($q->options) ? $q->options : []; ?>

<?php if($q->q_type === 'text'): ?>

    <input type="text"
           name="question[<?= $q->question_id ?>][0]"
           class="form-control"
           value="<?= isset($rec->question[$q->question_id][0]) ? $rec->question[$q->question_id][0] : '' ?>">

<?php elseif(in_array($q->question_id,[5,6,7]) && $q->q_type=='checkbox'): ?>

    <?php
    usort($options, function($a,$b){ return $a->option_order - $b->option_order; });
    $colDist = [5=>[3,4,4,4,3], 6=>[4,4,3,3,1], 7=>[3,2,3,3,1]];
    $distribution = $colDist[$q->question_id];
    $optIdx = 0;
    ?>
    <div class="row">
    <?php foreach($distribution as $colCount): ?>
        <div class="col-md-2 mb-2">
        <?php for($i=0;$i<$colCount;$i++): ?>
            <?php if(isset($options[$optIdx])): $opt=$options[$optIdx]; ?>
            <div class="form-check">
                <input class="form-check-input" type="checkbox"
                       name="question[<?= $q->question_id ?>][]"
                       value="<?= $opt->option_id ?>"
                       <?= (isset($rec->question[$q->question_id]) && in_array($opt->option_id,(array)$rec->question[$q->question_id]))?'checked':'' ?>>
                <label class="form-check-label"><?= htmlspecialchars($opt->option_text) ?></label>
            </div>
            <?php $optIdx++; endif; ?>
        <?php endfor; ?>
        </div>
    <?php endforeach; ?>
    </div>

<?php else: ?>

    <?php foreach($options as $opt): ?>
    <div class="form-check">
        <input class="form-check-input"
               type="<?= $q->q_type ?>"
               name="question[<?= $q->question_id ?>]<?= ($q->q_type=='checkbox')?'[]':'' ?>"
               value="<?= $opt->option_id ?>"
               <?= (isset($rec->question[$q->question_id]) && in_array($opt->option_id,(array)$rec->question[$q->question_id]))?'checked':'' ?>>
        <label class="form-check-label"><?= htmlspecialchars($opt->option_text) ?></label>
    </div>
    <?php endforeach; ?>

<?php endif; ?>

</div>
</div>
<?php endforeach; ?>

</div>
</div>
<?php endforeach; ?>

<!-- Submit -->
<div class="text-center mb-5">
    <button type="submit" class="btn btn-success save-btn">
        <?= (isset($is_edit) && $is_edit) ? 'Update Record' : 'Save Patient Record' ?>
    </button>
</div>

</div><!-- /card-body main -->
</div><!-- /card main -->

</form>
</div><!-- /#main-form-wrapper -->

<!-- =========================================================== -->
<!-- JAVASCRIPT -->
<!-- =========================================================== -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function () {

    /* ── Config ── */
    const LOCKED_FIELDS = [
        'patient_name','guardian_name','dob',
        'age_year','age_month','age_day',
        'age_group','gender','marital_status',
        'pregnancy_status','disability'
    ];

    /* ── State ── */
    let state = {
        currentQR   : '',
        lastRecords : null,
        lastCheckTime: null
    };

    /* ── Edit-mode: if form already has a QR (edit mode), show form straight away ── */
    <?php if(isset($is_edit) && $is_edit): ?>
    var editQR = "<?= isset($rec->qr_code) ? $rec->qr_code : '' ?>";
    if(editQR !== ''){
        $('#qr_external_input').val(editQR);
        revealForm();
        generateQRPreview(editQR);
    }
    <?php endif; ?>

    /* ─────────────────────────────────────────────
       QR preview generator — renders inside Basic Info
    ───────────────────────────────────────────── */
    function generateQRPreview(value){
        $('#qr_preview_form').html('');
        $('#qr_value_display').text(value && value.trim() !== '' ? value : '—');
        if(value && value.trim() !== ''){
            new QRCode(document.getElementById('qr_preview_form'),{
                text: value, width: 90, height: 90
            });
        }
    }

    /* ─────────────────────────────────────────────
       Confirm button OR Enter key triggers lookup
    ───────────────────────────────────────────── */
    $('#qr_confirm_btn').on('click', function(){ triggerQRCheck(); });

    $('#qr_external_input').on('keydown', function(e){
        if(e.key === 'Enter'){ e.preventDefault(); triggerQRCheck(); }
    });

    function triggerQRCheck(){
        var qrValue = $('#qr_external_input').val().trim();
        setStatus('');

        if(qrValue === ''){
            setStatus('<span class="qr-status-badge qr-status-error">⚠ Please enter a QR code first</span>');
            return;
        }

        /* same QR within 5s → use cache */
        if(qrValue === state.currentQR && state.lastCheckTime){
            var diff = Date.now() - state.lastCheckTime;
            if(diff < 5000){
                if(state.lastRecords && state.lastRecords.length > 0){
                    showRecordsList(state.lastRecords);
                } else {
                    showFormAsNew(qrValue);
                }
                return;
            }
        }

        state.currentQR = qrValue;
        setStatus('<span class="qr-status-badge qr-status-checking"><span class="qr-spinner"></span> Checking…</span>');

        $.ajax({
            url      : "<?= base_url('forms/check_qr_code') ?>",
            type     : 'POST',
            data     : { qr_code: qrValue },
            dataType : 'json',
            timeout  : 10000,
            success  : function(resp){
                state.lastCheckTime = Date.now();
                if(resp.success && resp.count > 0){
                    state.lastRecords = resp.records;
                    setStatus(
                        '<span class="qr-status-badge qr-status-exists clickable" id="qr_reopen_list">' +
                        '📌 ' + resp.count + ' existing record' + (resp.count>1?'s':'') + ' found — select below or start new' +
                        '</span>'
                    );
                    $('#qr_reopen_list').on('click', function(){
                        showRecordsList(state.lastRecords);
                    });
                    showRecordsList(resp.records);
                } else {
                    state.lastRecords = null;
                    showFormAsNew(qrValue);
                }
            },
            error: function(){
                setStatus('<span class="qr-status-badge qr-status-error">⚠ Error checking QR — please try again</span>');
            }
        });
    }

    /* ─────────────────────────────────────────────
       Status area helper
    ───────────────────────────────────────────── */
    function setStatus(html){ $('#qr_status_area').html(html); }

    /* ─────────────────────────────────────────────
       Show form for a BRAND-NEW QR
    ───────────────────────────────────────────── */
    function showFormAsNew(qrValue){
        setStatus('<span class="qr-status-badge qr-status-unique">✓ New record — all fields are open</span>');
        $('#qr-records-list').hide();
        $('#qr_code_hidden').val(qrValue);

        /* ── Clear any values previously filled from an old record ── */
        clearLockedFields();

        unlockAllLockedFields();
        removeLockIndicator();
        revealForm();
        generateQRPreview(qrValue);
    }

    /* ─────────────────────────────────────────────
       Clear all personal-info fields (text + radios)
    ───────────────────────────────────────────── */
    function clearLockedFields(){
        /* Text / number inputs */
        ['patient_name','guardian_name','dob','age_year','age_month','age_day','village']
            .forEach(function(f){ $('[name="'+f+'"]').val(''); });

        /* Radio groups */
        ['age_group','gender','marital_status','pregnancy_status','disability',
         'play_learning_kit','nutrition_package']
            .forEach(function(f){ $('[name="'+f+'"]').prop('checked', false); });

        /* Reset UC select and client_type */
        $('#uc').val('');
        $('[name="client_type"]').prop('checked', false);
    }

    /* ─────────────────────────────────────────────
       Reveal form wrapper
    ───────────────────────────────────────────── */
    function revealForm(){
        var $w = $('#main-form-wrapper');
        if($w.is(':hidden')){
            $w.slideDown(300);
        }
    }

    /* ─────────────────────────────────────────────
       Show inline records list (hint rows)
    ───────────────────────────────────────────── */
    function showRecordsList(records){
        if(!records || records.length === 0){
            $('#qr-records-list').hide();
            return;
        }

        var html = '';
        $.each(records, function(i, rec){
            var age = rec.age_year+'Y '+rec.age_month+'M '+rec.age_day+'D';
            html +=
                '<div class="qr-hint-row" data-master-id="'+esc(rec.master_id)+'">' +
                '  <span class="badge-date">'+esc(rec.form_date)+'</span>' +
                '  <div>' +
                '    <div class="patient-name">'+esc(rec.patient_name)+'</div>' +
                '    <div class="patient-meta">'+esc(rec.guardian_name)+' &nbsp;|&nbsp; '+age+' &nbsp;|&nbsp; '+esc(rec.gender)+'</div>' +
                '  </div>' +
                '  <span class="load-arrow">Load this record →</span>' +
                '</div>';
        });

        /* "Start as new" option at the bottom */
        html +=
            '<div class="qr-hint-row" id="qr_new_row" style="background:#f8f9fa;border-top:2px solid #dee2e6;">' +
            '  <span style="color:#28a745;font-weight:700;">＋</span>' +
            '  <div>' +
            '    <div style="font-weight:700;color:#28a745;">Start a new record with this QR</div>' +
            '    <div style="font-size:12px;color:#6c757d;">All fields will be open for entry</div>' +
            '  </div>' +
            '  <span style="margin-left:auto;font-size:12px;color:#28a745;">Click to start →</span>' +
            '</div>';

        $('#qr-records-body').html(html);
        $('#qr-list-title').text(records.length + ' previous record(s) found — click a row to load or start new:');
        $('#qr-records-list').slideDown(200);

        /* bind row clicks */
        $('.qr-hint-row[data-master-id]').off('click').on('click', function(){
            var mid = $(this).data('master-id');
            $('.qr-hint-row').removeClass('selected');
            $(this).addClass('selected');
            fetchAndPopulateRecord(mid);
        });

        $('#qr_new_row').off('click').on('click', function(){
            showFormAsNew(state.currentQR);
        });
    }

    /* ─────────────────────────────────────────────
       Fetch full record and populate + lock
    ───────────────────────────────────────────── */
    function fetchAndPopulateRecord(masterId){
        setStatus('<span class="qr-status-badge qr-status-checking"><span class="qr-spinner"></span> Loading record…</span>');

        $.ajax({
            url      : "<?= base_url('forms/get_child_master_ajax') ?>",
            type     : 'POST',
            data     : { master_id: masterId },
            dataType : 'json',
            timeout  : 10000,
            success  : function(resp){
                if(!resp.success || !resp.data){
                    setStatus('<span class="qr-status-badge qr-status-error">⚠ Could not load record</span>');
                    return;
                }

                var rec = resp.data;

                /* Populate */
                populateFields(rec);
                autoSelectAgeGroup(rec.age_year);
                $('input[name="client_type"][value="Followup"]').prop('checked', true);

                /* Lock ONLY fields that have a value */
                lockFieldsWithValues(rec);

                /* Mirror QR into hidden field */
                $('#qr_code_hidden').val(state.currentQR);
                generateQRPreview(state.currentQR);

                setStatus(
                    '<span class="qr-status-badge qr-status-unique">✓ Record loaded — locked fields shown in grey, empty fields are editable</span>'
                );

                revealForm();

                /* Smooth scroll to form */
                $('html,body').animate({ scrollTop: $('#main-form-wrapper').offset().top - 20 }, 400);
            },
            error: function(){
                setStatus('<span class="qr-status-badge qr-status-error">⚠ Failed to load record</span>');
            }
        });
    }

    /* ─────────────────────────────────────────────
       Populate form fields from record object
    ───────────────────────────────────────────── */
    function populateFields(rec){
        if(rec.patient_name)    $('[name="patient_name"]').val(esc(rec.patient_name));
        if(rec.guardian_name)   $('[name="guardian_name"]').val(esc(rec.guardian_name));
        if(rec.dob)             $('[name="dob"]').val(rec.dob);
        if(rec.age_year  !== '' && rec.age_year  !== null) $('[name="age_year"]').val(rec.age_year);
        if(rec.age_month !== '' && rec.age_month !== null) $('[name="age_month"]').val(rec.age_month);
        if(rec.age_day   !== '' && rec.age_day   !== null) $('[name="age_day"]').val(rec.age_day);
        if(rec.village)         $('[name="village"]').val(esc(rec.village));

        if(rec.gender)            $('[name="gender"][value="'+esc(rec.gender)+'"]').prop('checked',true);
        if(rec.marital_status)    $('[name="marital_status"][value="'+esc(rec.marital_status)+'"]').prop('checked',true);
        if(rec.pregnancy_status)  $('[name="pregnancy_status"][value="'+esc(rec.pregnancy_status)+'"]').prop('checked',true);
        if(rec.disability)        $('[name="disability"][value="'+esc(rec.disability)+'"]').prop('checked',true);
        if(rec.play_learning_kit) $('[name="play_learning_kit"][value="'+esc(rec.play_learning_kit)+'"]').prop('checked',true);
        if(rec.nutrition_package) $('[name="nutrition_package"][value="'+esc(rec.nutrition_package)+'"]').prop('checked',true);

        /* UC: needs AJAX to load options first, then select the saved value */
        if(rec.uc){
            var districtId = $('#district').val();
            $.ajax({
                url: "<?= base_url('forms/get_uc_by_district') ?>",
                type: 'POST', data: { district_id: districtId }, dataType: 'json',
                success: function(data){
                    $('#uc').html('<option value="">Select UC</option>');
                    $.each(data, function(i,o){
                        $('#uc').append('<option value="'+o.pk_id+'">'+o.uc+'</option>');
                    });
                    $('#uc').val(rec.uc);
                }
            });
        }
    }

    /* ─────────────────────────────────────────────
       Lock only fields that have a real value
    ───────────────────────────────────────────── */
    function lockFieldsWithValues(rec){
        unlockAllLockedFields();
        removeLockIndicator();

        var lockedCount = 0;

        /* Text / number inputs */
        var textMap = {
            patient_name  : rec.patient_name,
            guardian_name : rec.guardian_name,
            dob           : rec.dob,
            age_year      : rec.age_year,
            age_month     : rec.age_month,
            age_day       : rec.age_day,
            village       : rec.village
        };

        /* ── TEXT / NUMBER fields: use readonly (disabled won't submit!) ── */
        $.each(textMap, function(fname, fval){
            if(fval !== '' && fval !== null && fval !== undefined){
                $('[name="'+fname+'"]')
                    .prop('readonly', true)
                    .addClass('field-locked');
                lockedCount++;
            }
        });

        /* ── RADIO/SELECT groups: disabled won't submit, so we add a hidden input ── */
        var radioMap = {
            age_group        : rec.age_group,
            gender           : rec.gender,
            marital_status   : rec.marital_status,
            pregnancy_status : rec.pregnancy_status,
            disability       : rec.disability,
            play_learning_kit: rec.play_learning_kit,
            nutrition_package: rec.nutrition_package,
            uc               : rec.uc
        };

        /* age_group can be derived from age_year if not stored */
        if(!rec.age_group && rec.age_year !== '' && rec.age_year !== null){
            radioMap.age_group = getAgeGroupFromYear(parseInt(rec.age_year));
        }

        $.each(radioMap, function(fname, fval){
            if(fval && fval !== ''){
                /* 1. Visually lock all radio options for this group */
                $('[name="'+fname+'"]')
                    .prop('disabled', true)
                    .addClass('field-locked');

                /* 2. Inject a hidden input so the value is still submitted */
                $('<input>')
                    .attr({ type:'hidden', name: fname, value: fval, id: 'hidden_lock_'+fname })
                    .appendTo('#chf-form');

                lockedCount++;
            }
        });

        if(lockedCount > 0){
            var indicator = $(
                '<div id="lock-indicator" class="lock-indicator">' +
                '<strong>🔒 ' + lockedCount + ' field(s) locked</strong> from previous record. ' +
                'Grey fields are pre-filled and locked. All other fields are open for this visit.' +
                '</div>'
            );
            $('#qr-search-card').append(indicator);
        }
    }

    /* ─────────────────────────────────────────────
       Unlock: remove readonly, re-enable radios,
       and remove any injected hidden inputs
    ───────────────────────────────────────────── */
    function unlockAllLockedFields(){
        /* Text / number → remove readonly */
        ['patient_name','guardian_name','dob','age_year','age_month','age_day','village']
            .forEach(function(fname){
                $('[name="'+fname+'"]').prop('readonly', false).removeClass('field-locked');
            });

        /* Radios/selects → re-enable and remove hidden mirrors */
        ['age_group','gender','marital_status','pregnancy_status','disability',
         'play_learning_kit','nutrition_package','uc']
            .forEach(function(fname){
                $('[name="'+fname+'"]').prop('disabled', false).removeClass('field-locked');
                $('#hidden_lock_'+fname).remove();
            });
    }

    function removeLockIndicator(){
        $('#lock-indicator').fadeOut('fast', function(){ $(this).remove(); });
    }

    /* ─────────────────────────────────────────────
       Age group helpers
    ───────────────────────────────────────────── */
    function getAgeGroupFromYear(y){
        if(y === 0)              return '<1 Year';
        if(y >= 1  && y < 2)    return '1-2 Year';
        if(y >= 2  && y < 5)    return '2-5 Year';
        if(y >= 5  && y < 15)   return '5-15 Year';
        if(y >= 15)              return '15-49 Year';
        return '';
    }

    function autoSelectAgeGroup(years){
        var group = getAgeGroupFromYear(parseInt(years));
        if(group){
            $('[name="age_group"][value="'+group+'"]').prop('checked', true);
        }
    }

    /* ─────────────────────────────────────────────
       XSS-safe display helper
    ───────────────────────────────────────────── */
    function esc(str){
        if(typeof str !== 'string') return String(str === null ? '' : str);
        var d = document.createElement('div');
        d.textContent = str;
        return d.innerHTML;
    }

    /* ─────────────────────────────────────────────
       Flash message auto-hide
    ───────────────────────────────────────────── */
    setTimeout(function(){ $('#flash-msg').fadeOut('slow'); }, 3000);

    /* ─────────────────────────────────────────────
       District → UC → Facility chained dropdowns
    ───────────────────────────────────────────── */
    var selected_facility = <?= isset($rec->facility_id) ? json_encode($rec->facility_id) : '""' ?>;
    var selected_uc       = "<?= isset($rec->uc) ? $rec->uc : '' ?>";

    function loadUC(district_id, sel_uc){
        $.ajax({
            url:'<?= base_url("forms/get_uc_by_district") ?>', type:'POST',
            data:{ district_id: district_id }, dataType:'json',
            success: function(data){
                $('#uc').html('<option value="">Select UC</option>');
                $.each(data, function(i,o){ $('#uc').append('<option value="'+o.pk_id+'">'+o.uc+'</option>'); });
                if(sel_uc){ $('#uc').val(sel_uc); loadFacilities(sel_uc, selected_facility); }
            }
        });
    }

    function loadFacilities(uc_id, sel_fac){
        if(!uc_id){ $('#facility').html('<option value="">Select Facility</option>'); return; }
        $.ajax({
            url:'<?= base_url("forms/get_facilities_by_uc") ?>', type:'POST',
            data:{ uc_id: uc_id }, dataType:'json',
            success: function(data){
                $('#facility').html('<option value="">Select Facility</option>');
                $.each(data, function(i,f){
                    $('#facility').append('<option value="'+f.id+'"'+(f.id==sel_fac?' selected':'')+'>'+f.facility_name+'</option>');
                });
            }
        });
    }

    var init_district = $('#district').val();
    if(init_district){ loadUC(init_district, selected_uc); }

    $('#uc').on('change', function(){ loadFacilities($(this).val(), ''); });

    /* ─────────────────────────────────────────────
       Facility field visibility based on visit_type
    ───────────────────────────────────────────── */
    function toggleFacility(){
        $('input[name="visit_type"]:checked').val() === 'Fixed Site'
            ? $('#facility-field').show()
            : $('#facility-field').hide();
    }
    toggleFacility();
    $('input[name="visit_type"]').on('change', toggleFacility);

    /* ─────────────────────────────────────────────
       DOB → auto-calculate age
    ───────────────────────────────────────────── */
    $('#dob').on('change', function(){
        if(!this.value) return;
        var dob   = new Date(this.value);
        var today = new Date();
        var y = today.getFullYear() - dob.getFullYear();
        var m = today.getMonth()    - dob.getMonth();
        var d = today.getDate()     - dob.getDate();
        if(d < 0){ m--; d += new Date(today.getFullYear(), today.getMonth(), 0).getDate(); }
        if(m < 0){ y--; m += 12; }
        $('#age_year').val(y); $('#age_month').val(m); $('#age_day').val(d);
        autoSelectAgeGroup(y);
    });

    /* ─────────────────────────────────────────────
       Form date min/max
    ───────────────────────────────────────────── */
    var fd = document.getElementById('form_date_2');
    if(fd){
        var minD = new Date('2025-12-15');
        var maxD = new Date(); maxD.setDate(maxD.getDate()-1);
        fd.min = minD.toISOString().split('T')[0];
        fd.max = maxD.toISOString().split('T')[0];
        fd.addEventListener('input', function(){
            var v = new Date(this.value);
            if(v < minD) this.value = fd.min;
            else if(v > maxD) this.value = fd.max;
        });
    }

});
</script>

</div><!-- /.main-content -->