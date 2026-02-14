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
            value="<?= isset($rec->form_date) ? $rec->form_date : '' ?>" required>
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
    <label class="col-sm-2 col-form-label">Facility *</label>
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
    <label class="col-sm-2 col-form-label">9. Date of Birth *</label>
    <div class="col-sm-4">
        <input type="date" name="dob" class="form-control"
            value="<?= isset($rec->dob) ? $rec->dob : '' ?>" required>
    </div>

</div>

<div class="form-group row">
    <!-- Age -->
    <label class="col-sm-2 col-form-label">10. Age *</label>
    <div class="col-sm-2">
        <input type="number" name="age_year" class="form-control"
            placeholder="Y"
            value="<?= isset($rec->age_year) ? $rec->age_year : '' ?>" required>
    </div>
    <div class="col-sm-2">
        <input type="number" name="age_month" class="form-control"
            placeholder="M"
            value="<?= isset($rec->age_month) ? $rec->age_month : '' ?>" required>
    </div>
    <div class="col-sm-2">
        <input type="number" name="age_day" class="form-control"
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
        $groups = ['<1 Year','1-2 Year','2-5 Year','15-49 Year'];
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
    <label class="col-sm-2 col-form-label">12. Marital Status *</label>
    <div class="col-sm-4">
        <?php $marital = isset($rec->marital_status) ? $rec->marital_status : ''; ?>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="marital_status" value="Married"
                <?= ($marital=='Married') ? 'checked' : '' ?> required>
            <label class="form-check-label">Married</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="marital_status" value="Un-Married"
                <?= ($marital=='Un-Married') ? 'checked' : '' ?> required>
            <label class="form-check-label">Un-Married</label>
        </div>
    </div>

</div>

<!-- ROW 3 -->
<div class="form-group row">

    <!-- Pregnancy -->
    <label class="col-sm-2 col-form-label">13. Pregnancy Status *</label>
    <div class="col-sm-4">
        <?php $preg = isset($rec->pregnancy_status) ? $rec->pregnancy_status : ''; ?>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="pregnancy_status" value="Pregnant"
                <?= ($preg=='Pregnant') ? 'checked' : '' ?> required>
            <label class="form-check-label">Pregnant</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="pregnancy_status" value="Non-Pregnant"
                <?= ($preg=='Non-Pregnant') ? 'checked' : '' ?> required>
            <label class="form-check-label">Non-Pregnant</label>
        </div>
    </div>

    <!-- Disability -->
    <label class="col-sm-2 col-form-label">14. Disability *</label>
    <div class="col-sm-4">
        <?php $dis = isset($rec->disability) ? $rec->disability : ''; ?>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="disability" value="Yes"
                <?= ($dis=='Yes') ? 'checked' : '' ?> required>
            <label class="form-check-label">Yes</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="disability" value="No"
                <?= ($dis=='No') ? 'checked' : '' ?> required>
            <label class="form-check-label">No</label>
        </div>
    </div>

</div>

<!-- ROW 4 -->
<div class="form-group row">

    <!-- Play & Learning Kit -->
    <label class="col-sm-2 col-form-label">15. Play & Learning Kit *</label>
    <div class="col-sm-4">
        <?php $kit = isset($rec->play_learning_kit) ? $rec->play_learning_kit : ''; ?>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="play_learning_kit" value="Yes"
                <?= ($kit=='Yes') ? 'checked' : '' ?> required>
            <label class="form-check-label">Yes</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="play_learning_kit" value="No"
                <?= ($kit=='No') ? 'checked' : '' ?> required>
            <label class="form-check-label">No</label>
        </div>
    </div>

    <!-- Nutrition Package -->
    <label class="col-sm-2 col-form-label">16. Nutrition Package *</label>
    <div class="col-sm-4">
        <?php $nut = isset($rec->nutrition_package) ? $rec->nutrition_package : ''; ?>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="nutrition_package" value="Yes"
                <?= ($nut=='Yes') ? 'checked' : '' ?> required>
            <label class="form-check-label">Yes</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="nutrition_package" value="No"
                <?= ($nut=='No') ? 'checked' : '' ?> required>
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
<label class="col-sm-5 col-form-label">
    <span class="q-num"><?= $q->q_num; ?></span>
    <?= htmlspecialchars($q->q_text) ?>
</label>

<div class="col-sm-7">

<?php $options = isset($q->options) ? $q->options : array(); ?>

<?php if($q->q_type === 'text'): ?>

<input type="text"
name="question[<?= $q->question_id ?>][0]"
class="form-control"
value="<?= isset($rec->question[$q->question_id][0]) ? $rec->question[$q->question_id][0] : '' ?>">

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

</div>
    
<!-- Load jQuery first -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>    
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
            $('#facility').find('select').attr('required', true); // target the select
        } else {
            $('#facility-field').hide();
            $('#facility').find('select').removeAttr('required'); // remove required
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
</script>
