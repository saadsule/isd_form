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
    <h1 class="header-title">OPD MNCH Form</h1>
</div>

<?php
$rec = isset($record) ? $record : null;
$details = isset($details) ? $details : array();
?>

<form method="post"
action="<?= isset($is_edit) && $is_edit 
? base_url('forms/update_opd_mnch/'.$record->id)
: base_url('forms/save_opd_mnch'); ?>">

<div class="card">
<div class="card-body">

<!-- HEADER -->
<div class="form-header d-flex justify-content-between align-items-center flex-wrap">

<div>
    <h2 style="font-weight:800;margin:0;">
        OPD / MNCH Patient Form
    </h2>

    <small style="color:gray;">
        Maternal, Newborn & Child Health Record
    </small>
</div>

<div class="d-flex flex-wrap">
    <img src="<?= base_url('assets/images/logo/kp_logo.png'); ?>" style="height:60px;margin-right:10px;">
    <img src="<?= base_url('assets/images/logo/integral_global.png') ?>" style="height:55px;margin-right:10px;">
    <img src="<?= base_url('assets/images/logo/dsi_logo.png') ?>" style="height:55px;margin-right:10px;">
    <img src="<?= base_url('assets/images/logo/pf.png') ?>" style="height:55px;">
</div>

</div>


<!-- ================= BASIC INFO ================= -->

<div class="card mb-4 form-section">
<div class="card-body">

<h4 class="section-title">📋 Basic Information</h4>

<!-- Visit Type -->
<div class="form-group row align-items-center">
    <!-- Center: Visit Type -->
    <div class="col-md-4">
        <label class="form-label font-weight-bold d-block">Visit Type *</label>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="visit_type" value="OPD"
            <?= ($rec && $rec->visit_type=='OPD')?'checked':'' ?> required>
            <label class="form-check-label fw-bold">OPD</label>
        </div>

        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="visit_type" value="MNCH"
            <?= ($rec && $rec->visit_type=='MNCH')?'checked':'' ?> required>
            <label class="form-check-label fw-bold">MNCH</label>
        </div>
    </div>

    <!-- Right: QR Code -->
    <div class="col-md-4 ms-auto">
        <label class="form-label font-weight-bold d-block">QR Code# *</label>
        <input type="text" 
               id="qr_input"
               name="qr_code" 
               class="form-control"
               value="<?= isset($rec->qr_code) ? $rec->qr_code : '' ?>" 
               required>
    </div>
    <div class="col-sm-4">
        <label class="form-label font-weight-bold d-block">&nbsp;</label>
        <div id="qr_preview"
            style="padding:5px; border:1px solid #ddd; border-radius:6px; display:inline-block;">
       </div>
    </div>
</div>

<div class="form-row">
    <!-- Date -->
    <div class="col-sm-4 mb-3">
        <label class="form-label font-weight-bold d-block">Date *</label>
        <input type="date" name="form_date" class="form-control"
               value="<?= $rec ? $rec->form_date : '' ?>" required
               id="form_date">
    </div>

    <!-- ANC Card -->
    <div class="col-sm-4 mb-3">
        <label class="form-label font-weight-bold d-block">ANC Card# (In case of MNCH) *</label>
        <input type="text" name="anc_card_no" class="form-control"
               value="<?= $rec ? htmlspecialchars($rec->anc_card_no):'' ?>" required>
    </div>

    <!-- Client Type -->
    <div class="col-sm-4 mb-3">
        <label class="form-label font-weight-bold d-block">Client Type *</label>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="client_type" value="New"
                   <?= ($rec && $rec->client_type=='New')?'checked':'' ?> required>
            <label class="form-check-label">New Client</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="client_type" value="Followup"
                   <?= ($rec && $rec->client_type=='Followup')?'checked':'' ?> required>
            <label class="form-check-label">Follow-up</label>
        </div>
    </div>
</div>

<div class="form-group row">
<label class="col-sm-2 col-form-label">District *</label>
<div class="col-sm-4">
<select name="district" id="district" class="form-control" required>

<?php foreach($districts as $district){ ?>
<option value="<?= $district->district_id ?>"
<?= ($rec && $rec->district==$district->district_id)?'selected':'' ?>>
<?= $district->district_name ?>
</option>
<?php } ?>

</select>
</div>

<label class="col-sm-2 col-form-label">UC *</label>
<div class="col-sm-4">
<select name="uc" id="uc" class="form-control" required>

<?php if(isset($ucs)){ foreach($ucs as $u){ ?>
<option value="<?= $u->pk_id ?>"
<?= ($rec && $rec->uc==$u->pk_id)?'selected':'' ?>>
<?= $u->uc ?>
</option>
<?php }} ?>

</select>
</div>
</div>

<div class="form-group row" id="facility-field">
    <label class="col-sm-2 col-form-label">Facility *</label>
    <div class="col-sm-4">
        <select name="facility_id" id="facility" class="form-control" required>
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
<label class="col-sm-2 col-form-label">HF/Village *</label>
<div class="col-sm-4">
<input type="text" name="village" class="form-control"
value="<?= $rec ? htmlspecialchars($rec->village):'' ?>" required>
</div>

<label class="col-sm-2 col-form-label">HT/LHV Name *</label>
<div class="col-sm-4">
<input type="text" name="lhv_name" class="form-control"
value="<?= $rec ? htmlspecialchars($rec->lhv_name):'' ?>" required>
</div>
</div>


<div class="form-group row">
<label class="col-sm-2 col-form-label">Patients  Name *</label>
<div class="col-sm-4">
<input type="text" name="patient_name" class="form-control"
value="<?= $rec ? htmlspecialchars($rec->patient_name):'' ?>" required>
</div>

<label class="col-sm-2 col-form-label">Father/ Husband’s name *</label>
<div class="col-sm-4">
<input type="text" name="guardian_name" class="form-control"
value="<?= $rec ? htmlspecialchars($rec->guardian_name):'' ?>" required>
</div>
</div>

</div>
</div>



<!-- ================= PATIENT STATUS ================= -->

<div class="card mb-4 form-section">
<div class="card-body">

<h4 class="section-title">👩‍⚕️ Patient Status</h4>

<div class="form-group row">

<label class="col-sm-2 col-form-label">Age Group *</label>
<div class="col-sm-4">

<?php $ages=['0-14 Y','15-49 Y','50 Y +']; foreach($ages as $age){ ?>
<div class="form-check">
<input class="form-check-input" type="radio" name="age_group"
value="<?= $age ?>"
<?= ($rec && $rec->age_group==$age)?'checked':'' ?> required>
<label class="form-check-label"><?= $age ?></label>
</div>
<?php } ?>

</div>

<label class="col-sm-2 col-form-label">Any Disability *</label>
<div class="col-sm-4">

<?php $dis=['Yes','No']; foreach($dis as $d){ ?>
<div class="form-check">
<input class="form-check-input" type="radio" name="disability"
value="<?= $d ?>"
<?= ($rec && $rec->disability==$d)?'checked':'' ?> required>
<label class="form-check-label"><?= $d ?></label>
</div>
<?php } ?>

</div>
</div>



<div class="form-group row">
    
<label class="col-sm-2 col-form-label">Marital Status *</label>
<div class="col-sm-4">

<?php $maritals=['Married','Unmarried']; foreach($maritals as $m){ ?>
<div class="form-check">
<input class="form-check-input" type="radio" name="marital_status"
value="<?= $m ?>"
<?= ($rec && $rec->marital_status==$m)?'checked':'' ?> required>
<label class="form-check-label"><?= $m ?></label>
</div>
<?php } ?>

</div>

<label class="col-sm-2 col-form-label">Pregnancy Status *</label>
<div class="col-sm-4">

<?php $preg=['Pregnant','Non-Pregnant']; foreach($preg as $p){ ?>
<div class="form-check">
<input class="form-check-input" type="radio" name="pregnancy_status"
value="<?= $p ?>"
<?= ($rec && $rec->pregnancy_status==$p)?'checked':'' ?> required>
<label class="form-check-label"><?= $p ?></label>
</div>
<?php } ?>

</div>

</div>

</div>
</div>



<!-- ================= DYNAMIC QUESTIONS ================= -->

<?php
$sections = array();
foreach($questions as $q){
    $sections[$q->q_section][] = $q;
}
?>

<?php foreach($sections as $section_name => $section_questions): ?>

<div class="card mb-4 form-section">
<div class="card-body">

<h4 class="section-title">🩺 <?= htmlspecialchars($section_name); ?></h4>

<?php foreach($section_questions as $q): ?>

<div class="form-group row">

<label class="col-sm-5 col-form-label">
<span class="q-num"><?= $q->q_num; ?></span>
<?= htmlspecialchars($q->q_text); ?>
</label>

<div class="col-sm-7">

<?php $options = isset($q->options) ? $q->options : array(); ?>

<?php if($q->q_type == 'text'): ?>

<input type="text"
name="question[<?= $q->question_id; ?>][0]"
class="form-control"
value="<?= isset($details[$q->question_id][0]) 
? htmlspecialchars($details[$q->question_id][0]) : '' ?>">

<?php else: ?>

<?php foreach($options as $opt): 

$checked = (
isset($details[$q->question_id]) &&
in_array($opt->option_id,$details[$q->question_id])
) ? 'checked' : '';
?>

<div class="form-check">
<input class="form-check-input"
type="<?= $q->q_type; ?>"
name="question[<?= $q->question_id; ?>]<?= ($q->q_type=='checkbox')?'[]':''; ?>"
value="<?= $opt->option_id; ?>"
<?= $checked ?>>

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
                    $('#facility').append(
                        '<option value="'+f.id+'" '+selected+'>'+f.facility_name+'</option>'
                    );
                });
            }
        });
    }

    // When UC changes
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

    const dateInput = document.getElementById('form_date');
    
    // Set min and max dates
    const minDate = new Date('2025-12-15');
    const maxDate = new Date();
    maxDate.setDate(maxDate.getDate() - 1); // yesterday

    // Set min and max attributes for date picker
    dateInput.min = minDate.toISOString().split('T')[0];
    dateInput.max = maxDate.toISOString().split('T')[0];

    dateInput.addEventListener('input', function() {
        const inputDate = new Date(this.value);

        if (inputDate < minDate) {
            this.value = dateInput.min; // auto-correct to min
        } 
        else if (inputDate > maxDate) {
            this.value = dateInput.max; // auto-correct to max
        }
    });
</script> 
