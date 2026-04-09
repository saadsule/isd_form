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

.flash-msg {
    position: fixed;
    top: 20%;
    left: 50%;
    transform: translateX(-50%);
    z-index: 9999;
    padding: 18px 35px;
    font-size: 18px;
    border-radius: 12px;
    box-shadow: 0 6px 20px rgba(0,0,0,.25);
    display: flex;
    align-items: center;
    gap: 12px;
    color: #fff;
    font-weight: 600;
    animation: fadeIn 0.5s ease-in-out;
}

.flash-success {
    background: #28a745; /* green */
}

.flash-error {
    background: #dc3545; /* red */
}

/* Fade in animation */
@keyframes fadeIn {
    from { opacity: 0; transform: translateX(-50%) translateY(-20px); }
    to { opacity: 1; transform: translateX(-50%) translateY(0); }
}

/* Optional: make icon slightly bigger */
.flash-msg .anticon {
    font-size: 22px;
}   
    
/* Page spacing */
.page-container{
    background:#f4f6f9;
}

/* Main Card */
.card{
    border:none;
    border-radius:14px;
    box-shadow:0 6px 18px rgba(0,0,0,.06);
}

/* Header */
.form-header{
    background:white;
    padding:22px;
    border-radius:14px;
    margin-bottom:25px;
}

/* Section Card */
.form-section{
    border-left:7px solid #28a745;
    transition:.2s;
}

.form-section:hover{
    transform:translateY(-2px);
}

/* Titles */
.section-title{
    font-weight:800;
    color:#2c3e50;
    margin-bottom:25px;
}

/* Labels */
.col-form-label{
    font-weight:600;
    color:#34495e;
}

/* Inputs */
.form-control{
    border-radius:10px;
    height:45px;
}

/* Radio / Checkbox */
.form-check-label{
    font-weight:500;
}

/* Question Numbers */
.q-num{
    color:#28a745;
    font-weight:800;
    margin-right:6px;
}

/* Submit Button */
.save-btn{
    padding:16px 70px;
    font-size:20px;
    border-radius:14px;
    font-weight:700;
    box-shadow:0 6px 16px rgba(40,167,69,.25);
}

/* Focus for radio & checkbox */
.form-check-input:focus{
    outline: 3px solid rgb(63 135 245 / 20%) !important;
    outline-offset: 2px;
    box-shadow: 0 0 0 4px rgb(63 135 245 / 20%);
}

</style>


<div class="page-container">
<div class="main-content">

<div class="page-header">
    <h1 class="header-title">Child Health Form</h1>
</div>

<?php
$rec = isset($record) ? $record : null;
$details = isset($details) ? $details : array();
?>
    
<form method="post"
    action="<?= isset($is_edit) && $is_edit 
    ? base_url('forms/update_child_health/'.$record->master_id)
    : base_url('forms/save_child_health'); ?>">

<div class="card">
<div class="card-body">

<!-- HEADER -->
<div class="form-header d-flex justify-content-between align-items-center flex-wrap">

<div>
    <h2 style="font-weight:800;margin:0;">
        Child Health Record Form
    </h2>
    <small style="color:gray;">
        Child Vaccination & Monitoring Record
    </small>
</div>

<div class="d-flex flex-wrap">
    <img src="<?= base_url('assets/images/logo/kp_logo.png') ?>" style="height:60px;margin-right:10px;">
    <img src="<?= base_url('assets/images/logo/integral_global.png') ?>" style="height:55px;margin-right:10px;">
    <img src="<?= base_url('assets/images/logo/dsi_logo.png') ?>" style="height:55px;margin-right:10px;">
    <img src="<?= base_url('assets/images/logo/pf.png') ?>" style="height:55px;">
</div>

</div>

<!-- ================= BASIC INFORMATION ================= -->
<div class="card mb-4 form-section">
<div class="card-body">

<h4 class="section-title">📋 Basic Information</h4>

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


<div class="form-group row">
    <div class="col-sm-3">
        <label class="form-label font-weight-bold d-block">1. Date *</label>
        <input type="date" name="form_date" class="form-control"
            value="<?= isset($rec->form_date) ? $rec->form_date : '' ?>" 
            required
            id="form_date_2">
    </div>

    <div class="col-sm-3">
        <label class="form-label font-weight-bold d-block">2. QR Code# *</label>
        <input type="text" 
               id="qr_input"
               name="qr_code" 
               class="form-control"
               value="<?= isset($rec->qr_code) ? $rec->qr_code : '' ?>" 
               required>
    </div>

    <div class="col-sm-2">
        <label class="form-label font-weight-bold d-block">&nbsp;</label>
        <div id="qr_preview"
            style="padding:5px; border:1px solid #ddd; border-radius:6px; display:inline-block;">
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
<?php foreach($districts as $district){ ?>
<option value="<?= $district->district_id ?>"
<?= (isset($rec->district) && $rec->district==$district->district_id)?'selected':'' ?>>
<?= $district->district_name ?>
</option>
<?php } ?>
</select>
</div>

<label class="col-sm-2 col-form-label">4. UC *</label>
<div class="col-sm-4">
<select name="uc" id="uc" class="form-control" required>
<option value="">Select UC</option>
<!-- Options will be loaded via AJAX -->
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
                        <?= (isset($rec->facility_id) && $rec->facility_id == $f->id)?'selected':'' ?>>
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
    <label class="col-sm-2 col-form-label">7. Patient’s Name *</label>
    <div class="col-sm-4">
        <input type="text" name="patient_name" class="form-control"
            value="<?= isset($rec->patient_name) ? $rec->patient_name : '' ?>" required>
    </div>

    <label class="col-sm-2 col-form-label">8. Father/ Husband’s name *</label>
    <div class="col-sm-4">
        <input type="text" name="guardian_name" class="form-control"
            value="<?= isset($rec->guardian_name) ? $rec->guardian_name : '' ?>" required>
    </div>
</div>

</div>
</div>

<!-- ================= DEMOGRAPHICS ================= -->
<div class="card mb-4 form-section">
<div class="card-body">

<h4 class="section-title">👶 Demographics</h4>

<!-- ROW 1 -->
<div class="form-group row">

    <!-- Date of Birth -->
    <label class="col-sm-2 col-form-label">9. Date of Birth </label>
    <div class="col-sm-4">
        <input type="date" 
               id="dob"
               name="dob" 
               class="form-control"
               value="<?= isset($rec->dob) ? $rec->dob : '' ?>"
               max="<?= date('Y-m-d', strtotime('-1 day')) ?>">
    </div>

</div>

<div class="form-group row">
    <!-- Age -->
    <label class="col-sm-2 col-form-label">10. Age *</label>
    <div class="col-sm-2">
        <input type="number" id="age_year" name="age_year" class="form-control"
            placeholder="Y"
            value="<?= isset($rec->age_year) ? $rec->age_year : '' ?>" required>
    </div>
    <div class="col-sm-2">
        <input type="number" id="age_month" name="age_month" class="form-control"
            placeholder="M"
            value="<?= isset($rec->age_month) ? $rec->age_month : '' ?>" required>
    </div>
    <div class="col-sm-2">
        <input type="number" id="age_day" name="age_day" class="form-control"
            placeholder="D"
            value="<?= isset($rec->age_day) ? $rec->age_day : '' ?>" required>
    </div>
</div>

<!-- AGE GROUP RADIO -->
<div class="form-group row">
    
    <label class="col-sm-2 col-form-label">Age Group *</label>
    <div class="col-sm-10">

        <?php
        $selected_group = isset($rec->age_group) ? $rec->age_group : '';
        $groups = ['<1 Year','1-2 Year','2-5 Year','5-15 Year','15-49 Year'];
        foreach($groups as $group): ?>
            <div class="form-check form-check-inline">
                <input class="form-check-input"
                       type="radio"
                       name="age_group"
                       value="<?= $group ?>"
                       required
                       <?= ($selected_group == $group) ? 'checked' : '' ?>>
                <label class="form-check-label"><?= $group ?></label>
            </div>
        <?php endforeach; ?>

    </div>
</div>

<hr>

<!-- ROW 2 -->
<div class="form-group row">

    <!-- Gender -->
    <label class="col-sm-2 col-form-label">11. Gender *</label>
    <div class="col-sm-4">
        <?php $gender = isset($rec->gender) ? $rec->gender : ''; ?>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="gender" value="Male"
                <?= ($gender=='Male') ? 'checked' : '' ?> required>
            <label class="form-check-label">Male</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="gender" value="Female"
                <?= ($gender=='Female') ? 'checked' : '' ?> required>
            <label class="form-check-label">Female</label>
        </div>
    </div>

    <!-- Marital Status -->
    <label class="col-sm-2 col-form-label">12. Marital Status</label>
    <div class="col-sm-4">
        <?php $marital = isset($rec->marital_status) ? $rec->marital_status : ''; ?>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="marital_status" value="Married"
                <?= ($marital=='Married') ? 'checked' : '' ?>>
            <label class="form-check-label">Married</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="marital_status" value="Un-Married"
                <?= ($marital=='Un-Married') ? 'checked' : '' ?>>
            <label class="form-check-label">Un-Married</label>
        </div>
    </div>

</div>

<!-- ROW 3 -->
<div class="form-group row">

    <!-- Pregnancy -->
    <label class="col-sm-2 col-form-label">13. Pregnancy Status</label>
    <div class="col-sm-4">
        <?php $preg = isset($rec->pregnancy_status) ? $rec->pregnancy_status : ''; ?>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="pregnancy_status" value="Pregnant"
                <?= ($preg=='Pregnant') ? 'checked' : '' ?>>
            <label class="form-check-label">Pregnant</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="pregnancy_status" value="Non-Pregnant"
                <?= ($preg=='Non-Pregnant') ? 'checked' : '' ?>>
            <label class="form-check-label">Non-Pregnant</label>
        </div>
    </div>

    <!-- Disability -->
    <label class="col-sm-2 col-form-label">14. Disability</label>
    <div class="col-sm-4">
        <?php $dis = isset($rec->disability) ? $rec->disability : ''; ?>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="disability" value="Yes"
                <?= ($dis=='Yes') ? 'checked' : '' ?>>
            <label class="form-check-label">Yes</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="disability" value="No"
                <?= ($dis=='No') ? 'checked' : '' ?>>
            <label class="form-check-label">No</label>
        </div>
    </div>

</div>

<!-- ROW 4 -->
<div class="form-group row">

    <!-- Play & Learning Kit -->
    <label class="col-sm-2 col-form-label">15. Play & Learning Kit</label>
    <div class="col-sm-4">
        <?php $kit = isset($rec->play_learning_kit) ? $rec->play_learning_kit : ''; ?>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="play_learning_kit" value="Yes"
                <?= ($kit=='Yes') ? 'checked' : '' ?>>
            <label class="form-check-label">Yes</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="play_learning_kit" value="No"
                <?= ($kit=='No') ? 'checked' : '' ?>>
            <label class="form-check-label">No</label>
        </div>
    </div>

    <!-- Nutrition Package -->
    <label class="col-sm-2 col-form-label">16. Nutrition Package</label>
    <div class="col-sm-4">
        <?php $nut = isset($rec->nutrition_package) ? $rec->nutrition_package : ''; ?>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="nutrition_package" value="Yes"
                <?= ($nut=='Yes') ? 'checked' : '' ?>>
            <label class="form-check-label">Yes</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="nutrition_package" value="No"
                <?= ($nut=='No') ? 'checked' : '' ?>>
            <label class="form-check-label">No</label>
        </div>
    </div>

</div>

</div>
</div>

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
<?php if(isset($q->q_text) && !empty($q->q_text)) { ?>
    <label class="col-sm-5 col-form-label">
        <span class="q-num"><?= $q->q_num; ?></span>
        <?= htmlspecialchars($q->q_text) ?>
    </label>
<?php } ?>
<div class="<?= (in_array($q->question_id, [5,6,7]) && $q->q_type=='checkbox') ? 'col-sm-12' : 'col-sm-7' ?>">

<?php $options = isset($q->options) ? $q->options : array(); ?>

<?php if($q->q_type === 'text'): ?>

<input type="text"
name="question[<?= $q->question_id ?>][0]"
class="form-control"
value="<?= isset($rec->question[$q->question_id][0]) ? $rec->question[$q->question_id][0] : '' ?>">

<?php else: ?>

<?php if(in_array($q->question_id, [5,6,7]) && $q->q_type=='checkbox'): ?>

<?php
// Sort options by option_order
usort($options, function($a, $b) {
    return $a->option_order - $b->option_order;
});

$columnDistribution = [
    5 => [3,4,4,4,3],
    6 => [4,4,3,3,1],
    7 => [3,2,3,3,1],
];

$distribution = $columnDistribution[$q->question_id];
$optionIndex = 0;
?>

<div class="row">

<?php foreach($distribution as $colCount): ?>

    <div class="col-md-2 mb-2">
        <?php for($i = 0; $i < $colCount; $i++): ?>
            
            <?php if(isset($options[$optionIndex])): 
                $opt = $options[$optionIndex];
            ?>
            
            <div class="form-check">
                <input class="form-check-input"
                    type="checkbox"
                    name="question[<?= $q->question_id ?>][]"
                    value="<?= $opt->option_id; ?>"
                    <?= (isset($rec->question[$q->question_id]) && in_array($opt->option_id, (array)$rec->question[$q->question_id])) ? 'checked' : '' ?>>

                <label class="form-check-label">
                    <?= htmlspecialchars($opt->option_text); ?>
                </label>            
            </div>

            <?php 
                $optionIndex++; 
            endif; ?>

        <?php endfor; ?>
    </div>

<?php endforeach; ?>
</div>

<?php else: ?>

<?php foreach($options as $opt): ?>

<div class="form-check">
<input class="form-check-input"
type="<?= $q->q_type; ?>"
name="question[<?= $q->question_id ?>]<?= ($q->q_type=='checkbox') ? '[]' : '' ?>"
value="<?= $opt->option_id; ?>"
<?= (isset($rec->question[$q->question_id]) && in_array($opt->option_id, (array)$rec->question[$q->question_id])) ? 'checked' : '' ?>>

<label class="form-check-label">
<?= htmlspecialchars($opt->option_text); ?>
</label>
</div>

<?php endforeach; ?>

<?php endif; ?>

<?php endif; ?>

</div>
</div>

<?php endforeach; ?>

</div>
</div>

<?php endforeach; ?>


<div class="text-center mb-5">
<button type="submit" class="btn btn-success save-btn">
<?= (isset($is_edit) && $is_edit) ? 'Update Record' : 'Save Patient Record' ?>
</button>
</div>

</div>
</div>

</form>
    
<!-- ============================================ -->
<!-- ENHANCED QR CODE LOOKUP - CLICKABLE BADGE -->
<!-- ============================================ -->
 
<style>
    /* Locked field styling */
    .field-locked {
        background-color: #f8f9fa !important;
        border-color: #dee2e6 !important;
        color: #666 !important;
        cursor: not-allowed !important;
        box-shadow: inset 0 1px 3px rgba(0,0,0,0.05);
    }
 
    .field-locked:focus {
        background-color: #f8f9fa !important;
        border-color: #dee2e6 !important;
        box-shadow: inset 0 1px 3px rgba(0,0,0,0.05) !important;
        outline: none !important;
    }
 
    /* Status badge styling */
    .qr-status-badge {
        display: inline-block;
        padding: 8px 14px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 600;
        margin-left: 8px;
        margin-top: 6px;
        animation: slideIn 0.3s ease-out;
        cursor: pointer;
        transition: all 0.2s ease;
    }
 
    .qr-status-badge:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(0,0,0,0.12);
    }
 
    .qr-status-unique {
        background-color: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
        cursor: default;
    }
 
    .qr-status-unique:hover {
        transform: none;
        box-shadow: none;
    }
 
    .qr-status-exists {
        background-color: #d1ecf1;
        color: #0c5460;
        border: 1px solid #bee5eb;
    }
 
    .qr-status-checking {
        background-color: #fff3cd;
        color: #856404;
        border: 1px solid #ffeeba;
        cursor: default;
    }
 
    .qr-status-checking:hover {
        transform: none;
        box-shadow: none;
    }
 
    /* Badge with records found style */
    .qr-status-badge.has-records {
        cursor: pointer;
        user-select: none;
    }
 
    .qr-status-badge.has-records:active {
        transform: translateY(0);
        box-shadow: 0 1px 4px rgba(0,0,0,0.1);
    }
 
    /* Modal styling */
    .modal-header.bg-success {
        background-color: #28a745 !important;
    }
 
    .modal-body {
        padding: 20px;
    }
 
    .table-responsive {
        max-height: 400px;
        overflow-y: auto;
    }
 
    /* Table row highlighting */
    .qr-record-row {
        cursor: pointer;
        transition: all 0.2s ease;
        user-select: none;
    }
 
    .qr-record-row:hover {
        background-color: #e7f3ff;
        transform: translateX(2px);
    }
 
    .qr-record-row.selected {
        background-color: #d4edda;
        font-weight: 500;
        border-left: 4px solid #28a745;
    }
 
    /* Alert styling */
    .lock-indicator {
        background-color: #fffbea;
        border-left: 4px solid #ffc107;
        border-radius: 4px;
        animation: slideDown 0.3s ease-out;
        margin-top: 15px;
        padding: 12px 15px;
    }
 
    /* Animations */
    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateX(-10px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }
 
    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
 
    /* Spinner */
    .qr-spinner {
        display: inline-block;
        width: 14px;
        height: 14px;
        border: 2px solid #f3f3f3;
        border-top: 2px solid #ffc107;
        border-radius: 50%;
        animation: spin 1s linear infinite;
        margin-right: 6px;
        vertical-align: middle;
    }
 
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
 
    /* Badge with icon */
    .badge-icon {
        margin-right: 4px;
    }
</style>
 
<!-- MODAL FOR QR CODE RESULTS -->
<div class="modal fade" id="qrResultModal" tabindex="-1" role="dialog" aria-labelledby="qrModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="qrModalLabel">📋 Previous Records Found</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="qrTableContainer"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-info btn-sm" id="reloadQrBtn" onclick="location.reload()">New Search</button>
            </div>
        </div>
    </div>
</div>

</div>
    
<!-- Load jQuery first -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>    

<!-- JAVASCRIPT FOR ENHANCED QR CODE LOGIC -->
<script>
$(document).ready(function() {
    
    // Configuration
    const CONFIG = {
        lockedFields: [
            'patient_name', 'guardian_name', 'dob', 'age_year', 'age_month', 'age_day',
            'age_group', 'gender', 'marital_status', 'pregnancy_status', 'disability'
        ],
        ajaxTimeout: 10000,
        debounceDelay: 500
    };
 
    // State management
    let qrState = {
        currentQR: '',
        selectedMasterId: null,
        isLocked: false,
        lastCheckTime: null,
        lastRecords: null,
        badgeElement: null
    };
 
    /**
     * ============================================
     * QR CODE FOCUSOUT EVENT HANDLER
     * ============================================
     */
    $('#qr_input').on('focusout', function() {
        const qrValue = $(this).val().trim();
 
        // Remove old badge
        removeBadge();
 
        // Validation: Empty QR
        if (qrValue === '') {
            unlockPersonalInfoFields();
            qrState.currentQR = '';
            qrState.selectedMasterId = null;
            qrState.lastRecords = null;
            return;
        }
 
        // Check if same QR (skip re-check within 5 seconds)
        if (qrValue === qrState.currentQR && qrState.lastCheckTime) {
            const timeDiff = Date.now() - qrState.lastCheckTime;
            if (timeDiff < 5000) {
                // Show cached badge
                showCachedBadge();
                return;
            }
        }
 
        qrState.currentQR = qrValue;
        showCheckingBadge();
        checkQRCodeInDatabase(qrValue);
    });
 
    /**
     * ============================================
     * REMOVE BADGE
     * ============================================
     */
    function removeBadge() {
        if (qrState.badgeElement) {
            qrState.badgeElement.remove();
            qrState.badgeElement = null;
        } else {
            $('#qr_input').parent().find('.qr-status-badge').remove();
        }
    }
 
    /**
     * ============================================
     * SHOW CHECKING BADGE
     * ============================================
     */
    function showCheckingBadge() {
        removeBadge();
        const badge = $('<span class="qr-status-badge qr-status-checking">' +
            '<span class="qr-spinner"></span>Checking...' +
            '</span>');
        $('#qr_input').after(badge);
        qrState.badgeElement = badge[0];
    }
 
    /**
     * ============================================
     * SHOW CACHED BADGE
     * ============================================
     */
    function showCachedBadge() {
        removeBadge();
        if (qrState.lastRecords && qrState.lastRecords.length > 0) {
            showExistsBadge(qrState.lastRecords.length, true);
        } else {
            showUniqueBadge();
        }
    }
 
    /**
     * ============================================
     * CHECK QR CODE IN DATABASE
     * ============================================
     */
    function checkQRCodeInDatabase(qrValue) {
        $.ajax({
            url: "<?= base_url('forms/check_qr_code') ?>",
            type: "POST",
            data: { qr_code: qrValue },
            dataType: "json",
            timeout: CONFIG.ajaxTimeout,
            success: function(response) {
                qrState.lastCheckTime = Date.now();
 
                if (response.success && response.count > 0) {
                    // QR EXISTS - Cache records
                    qrState.lastRecords = response.records;
                    showExistsBadge(response.count, true);
                    displayQRResultsModal(response.records);
                    $('#qrResultModal').modal('show');
                } else {
                    // QR DOES NOT EXIST - New record
                    qrState.lastRecords = null;
                    showUniqueBadge();
                    unlockPersonalInfoFields();
                    qrState.selectedMasterId = null;
                    qrState.isLocked = false;
                }
            },
            error: function(xhr, status, error) {
                removeBadge();
                const errorBadge = $('<span class="qr-status-badge qr-status-checking" style="background-color: #f8d7da; color: #721c24; border-color: #f5c6cb;">' +
                    '⚠️ Error checking QR' +
                    '</span>');
                $('#qr_input').after(errorBadge);
                qrState.badgeElement = errorBadge[0];
 
                console.error('QR Check Error:', error);
            }
        });
    }
 
    /**
     * ============================================
     * SHOW UNIQUE BADGE (Not clickable)
     * ============================================
     */
    function showUniqueBadge() {
        removeBadge();
        const badge = $('<span class="qr-status-badge qr-status-unique">' +
            '✓ This is a new record' +
            '</span>');
        $('#qr_input').after(badge);
        qrState.badgeElement = badge[0];
    }
 
    /**
     * ============================================
     * SHOW EXISTS BADGE (Clickable to reopen popup)
     * ============================================
     */
    function showExistsBadge(count, isClickable = false) {
        removeBadge();
        const badge = $('<span class="qr-status-badge qr-status-exists ' + (isClickable ? 'has-records' : '') + '">' +
            '📌 ' + count + ' record' + (count > 1 ? '(s)' : '') + ' found' +
            '</span>');
        
        if (isClickable && qrState.lastRecords) {
            badge.on('click', function(e) {
                e.stopPropagation();
                displayQRResultsModal(qrState.lastRecords);
                $('#qrResultModal').modal('show');
            });
        }
 
        $('#qr_input').after(badge);
        qrState.badgeElement = badge[0];
    }
 
    /**
     * ============================================
     * DISPLAY QR RESULTS IN MODAL
     * ============================================
     */
    function displayQRResultsModal(records) {
        if (!records || records.length === 0) {
            $('#qrTableContainer').html('<div class="alert alert-warning">No records found</div>');
            return;
        }
 
        let html = '<div class="table-responsive">';
        html += '<table class="table table-hover table-sm table-bordered">';
        html += '<thead class="bg-light"><tr>';
        html += '<th width="50px">Select</th>';
        html += '<th>Date</th>';
        html += '<th>Patient Name</th>';
        html += '<th>Father/Husband</th>';
        html += '<th>Age</th>';
        html += '<th>Gender</th>';
        html += '</tr></thead><tbody>';
 
        $.each(records, function(i, rec) {
            const age = rec.age_year + 'Y ' + rec.age_month + 'M ' + rec.age_day + 'D';
            const rowClass = qrState.selectedMasterId === rec.master_id ? 'selected' : '';
 
            html += '<tr class="qr-record-row ' + rowClass + '" data-master-id="' + sanitizeHTML(rec.master_id) + '">';
            html += '<td class="text-center"><input type="radio" name="qr_select" class="qr-select-radio" value="' + sanitizeHTML(rec.master_id) + '"></td>';
            html += '<td>' + sanitizeHTML(rec.form_date) + '</td>';
            html += '<td><strong>' + sanitizeHTML(rec.patient_name) + '</strong></td>';
            html += '<td>' + sanitizeHTML(rec.guardian_name) + '</td>';
            html += '<td>' + age + '</td>';
            html += '<td>' + sanitizeHTML(rec.gender) + '</td>';
            html += '</tr>';
        });
 
        html += '</tbody></table></div>';
        html += '<div class="alert alert-info mt-3 mb-0" style="font-size: 13px;">';
        html += '<strong>💡 Tip:</strong> Click any row to select it, or click the badge again to view these records.';
        html += '</div>';
 
        $('#qrTableContainer').html(html);
        bindQRRecordSelection();
    }
 
    /**
     * ============================================
     * BIND QR RECORD SELECTION
     * ============================================
     */
    function bindQRRecordSelection() {
        $('.qr-record-row').on('click', function() {
            if (event.target.tagName !== 'INPUT') {
                $(this).find('.qr-select-radio').prop('checked', true).change();
            }
        });
 
        $('.qr-select-radio').on('change', function() {
            if ($(this).is(':checked')) {
                const masterId = $(this).val();
                const row = $(this).closest('tr');
 
                $('.qr-record-row').removeClass('selected');
                row.addClass('selected');
 
                fetchAndPopulateRecord(masterId);
 
                setTimeout(() => {
                    $('#qrResultModal').modal('hide');
                }, 500);
            }
        });
    }
 
    /**
     * ============================================
     * FETCH FULL RECORD & POPULATE FORM
     * ============================================
     */
    function fetchAndPopulateRecord(masterId) {
        $.ajax({
            url: "<?= base_url('forms/get_child_master_ajax') ?>",
            type: "POST",
            data: { master_id: masterId },
            dataType: "json",
            timeout: CONFIG.ajaxTimeout,
            success: function(response) {
                if (!response.success || !response.data) {
                    showToast('Error: Could not fetch record details', 'error');
                    return;
                }
 
                const rec = response.data;
 
                // Populate fields
                populateFormFields(rec);
                autoSelectAgeGroup(rec.age_year);
                $('input[name="client_type"][value="Followup"]').prop('checked', true);
 
                // Lock fields
                lockPersonalInfoFields();
 
                qrState.selectedMasterId = masterId;
                qrState.isLocked = true;
 
                showToast('✓ Record loaded! Personal info is locked.', 'success');
            },
            error: function() {
                showToast('Error: Failed to load record details', 'error');
            }
        });
    }
 
    /**
     * ============================================
     * POPULATE FORM FIELDS
     * ============================================
     */
    function populateFormFields(rec) {
        $('input[name="patient_name"]').val(sanitizeHTML(rec.patient_name));
        $('input[name="guardian_name"]').val(sanitizeHTML(rec.guardian_name));
        $('input[name="dob"]').val(rec.dob);
        $('input[name="age_year"]').val(rec.age_year);
        $('input[name="age_month"]').val(rec.age_month);
        $('input[name="age_day"]').val(rec.age_day);
 
        if (rec.gender) {
            $('input[name="gender"][value="' + sanitizeHTML(rec.gender) + '"]').prop('checked', true);
        }
        if (rec.marital_status) {
            $('input[name="marital_status"][value="' + sanitizeHTML(rec.marital_status) + '"]').prop('checked', true);
        }
        if (rec.pregnancy_status) {
            $('input[name="pregnancy_status"][value="' + sanitizeHTML(rec.pregnancy_status) + '"]').prop('checked', true);
        }
        if (rec.disability) {
            $('input[name="disability"][value="' + sanitizeHTML(rec.disability) + '"]').prop('checked', true);
        }
    }
 
    /**
     * ============================================
     * LOCK PERSONAL INFO FIELDS
     * ============================================
     */
    function lockPersonalInfoFields() {
        CONFIG.lockedFields.forEach(fieldName => {
            const fieldElements = $('[name="' + fieldName + '"]');
            fieldElements.each(function() {
                $(this).prop('disabled', true).addClass('field-locked');
            });
        });
 
        removeLockIndicator();
        const indicator = $('<div id="lock-indicator" class="alert lock-indicator">' +
            '<strong>🔒 Personal Information Locked:</strong> These fields are auto-filled from the selected record. ' +
            'Fill remaining fields to complete the form.' +
            '</div>');
        
        $('#qr_input').closest('.form-group').after(indicator);
    }
 
    /**
     * ============================================
     * UNLOCK PERSONAL INFO FIELDS
     * ============================================
     */
    function unlockPersonalInfoFields() {
        CONFIG.lockedFields.forEach(fieldName => {
            const fieldElements = $('[name="' + fieldName + '"]');
            fieldElements.each(function() {
                $(this).prop('disabled', false).removeClass('field-locked');
            });
        });
 
        removeLockIndicator();
    }
 
    /**
     * ============================================
     * REMOVE LOCK INDICATOR
     * ============================================
     */
    function removeLockIndicator() {
        $('#lock-indicator').fadeOut('fast', function() {
            $(this).remove();
        });
    }
 
    /**
     * ============================================
     * AUTO-SELECT AGE GROUP
     * ============================================
     */
    function autoSelectAgeGroup(years) {
        years = parseInt(years);
        let group = '';
 
        if (years === 0) {
            group = '<1 Year';
        } else if (years >= 1 && years < 2) {
            group = '1-2 Year';
        } else if (years >= 2 && years < 5) {
            group = '2-5 Year';
        } else if (years >= 5 && years < 15) {
            group = '5-15 Year';
        } else if (years >= 15) {
            group = '15-49 Year';
        }
 
        if (group !== '') {
            $('input[name="age_group"][value="' + group + '"]').prop('checked', true);
        }
    }
 
    /**
     * ============================================
     * TOAST NOTIFICATION
     * ============================================
     */
    function showToast(message, type = 'info') {
        const bgColor = type === 'success' ? '#28a745' : type === 'error' ? '#dc3545' : '#17a2b8';
 
        const toast = $('<div class="flash-msg flash-' + type + '" style="background: ' + bgColor + ';">' +
            message + '</div>');
 
        $('body').append(toast);
 
        setTimeout(() => {
            toast.fadeOut('slow', function() {
                $(this).remove();
            });
        }, 4000);
    }
 
    /**
     * ============================================
     * SANITIZE HTML (XSS Prevention)
     * ============================================
     */
    function sanitizeHTML(str) {
        if (typeof str !== 'string') return '';
        const div = document.createElement('div');
        div.textContent = str;
        return div.innerHTML;
    }
 
});
</script>

<script>
var selected_facility = <?= isset($rec->facility_id) ? json_encode($rec->facility_id) : '""' ?>;
var selected_uc = "<?= isset($rec->uc) ? $rec->uc : '' ?>";
$(document).ready(function() {

    function loadUC(district_id, selected_uc = '') {
        $.ajax({
            url: "<?= base_url('forms/get_uc_by_district') ?>",
            type: "POST",
            data: { district_id: district_id },
            dataType: "json",
            success: function(data){
                $('#uc').empty();
                $('#uc').append('<option value="">Select UC</option>');

                $.each(data, function(i, obj){
                    $('#uc').append('<option value="'+obj.pk_id+'">'+obj.uc+'</option>');
                });

                if(selected_uc){
                    $('#uc').val(selected_uc);

                    // ✅ LOAD FACILITIES AFTER UC IS SET
                    loadFacilities(selected_uc, selected_facility);
                }
            }
        });
    }

    // Load UCs for the only district on page load
    var district_id = $('#district').val();
    if(district_id) {
        loadUC(district_id, selected_uc); // ✅ PASS IT HERE
    }

});
$(document).ready(function(){
    setTimeout(function(){
        $('#flash-msg').fadeOut('slow');
    }, 3000); // 3000ms = 3 seconds
});

$(document).ready(function() {

    function toggleFacilityField() {
        const visitType = $('input[name="visit_type"]:checked').val();
        if (visitType === 'Fixed Site') {
            $('#facility-field').show();
        } else {
            $('#facility-field').hide();
        }
    }

    // Run on page load
    toggleFacilityField();

    // Run whenever the visit_type changes
    $('input[name="visit_type"]').change(function() {
        toggleFacilityField();
    });

});

$(document).ready(function(){

    function loadFacilities(uc_id, selected_facility = ''){
        if(!uc_id){
            $('#facility').html('<option value="">Select Facility</option>');
            return;
        }

        $.ajax({
            url: "<?= base_url('forms/get_facilities_by_uc') ?>",
            type: "POST",
            data: { uc_id: uc_id },
            dataType: "json",
            success: function(data){
                $('#facility').empty();
                $('#facility').append('<option value="">Select Facility</option>');
                $.each(data, function(i, f){
                    let selected = (f.id == selected_facility) ? 'selected' : '';
                    $('#facility').append('<option value="'+f.id+'" '+selected+'>'+f.facility_name+'</option>');
                });
            }
        });
    }

    // When UC changes manually
    $('#uc').change(function(){
        loadFacilities($(this).val());
    });
});

//For QR code
document.addEventListener("DOMContentLoaded", function() {

    var qrInput = document.getElementById("qr_input");
    var qrPreview = document.getElementById("qr_preview");

    var qr = new QRCode(qrPreview, {
        width: 80,
        height: 80
    });

    function generateQR(value) {
        qrPreview.innerHTML = "";
        if(value.trim() !== "") {
            new QRCode(qrPreview, {
                text: value,
                width: 80,
                height: 80
            });
        }
    }

    // Generate on typing
    qrInput.addEventListener("keyup", function() {
        generateQR(this.value);
    });

    // If editing (value already exists)
    if(qrInput.value !== ""){
        generateQR(qrInput.value);
    }

});

document.getElementById('dob').addEventListener('change', function () {

    if (!this.value) return; // If no date selected → do nothing

    const dob = new Date(this.value);
    const today = new Date();

    let years = today.getFullYear() - dob.getFullYear();
    let months = today.getMonth() - dob.getMonth();
    let days = today.getDate() - dob.getDate();

    if (days < 0) {
        months--;
        days += new Date(today.getFullYear(), today.getMonth(), 0).getDate();
    }

    if (months < 0) {
        years--;
        months += 12;
    }

    // Set Age fields
    document.getElementById('age_year').value = years;
    document.getElementById('age_month').value = months;
    document.getElementById('age_day').value = days;

    // Auto select Age Group
    let group = '';

    if (years === 0) {
        group = '<1 Year';
    } else if (years >= 1 && years < 2) {
        group = '1-2 Year';
    } else if (years >= 2 && years < 5) {
        group = '2-5 Year';
    } else if (years >= 5 && years < 15) {
        group = '5-15 Year';
    } else if (years >= 15 && years <= 49) {
        group = '15-49 Year';
    }

    if (group !== '') {
        const radios = document.querySelectorAll('input[name="age_group"]');
        radios.forEach(radio => {
            radio.checked = (radio.value === group);
        });
    }
});

    const dateInput2 = document.getElementById('form_date_2');

    // Set minimum date (15 Dec 2025)
    const minDate2 = new Date('2025-12-15');

    // Set maximum date (yesterday)
    const maxDate2 = new Date();
    maxDate2.setDate(maxDate2.getDate() - 1);

    // Apply min & max to date picker
    dateInput2.min = minDate2.toISOString().split('T')[0];
    dateInput2.max = maxDate2.toISOString().split('T')[0];

    dateInput2.addEventListener('input', function () {
        const inputDate = new Date(this.value);

        if (inputDate < minDate2) {
            this.value = dateInput2.min; // auto-correct to min
        } 
        else if (inputDate > maxDate2) {
            this.value = dateInput2.max; // auto-correct to max
        }
    });
</script>
