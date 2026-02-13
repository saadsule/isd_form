<style>

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

<form method="post" action="<?php echo base_url('forms/save_opd_mnch'); ?>">

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
<div class="mb-4">
<strong>Visit Type</strong>

<div class="mt-2">
    <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" name="visit_type" value="OPD" required>
        <label class="form-check-label">OPD</label>
    </div>

    <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" name="visit_type" value="MNCH" required>
        <label class="form-check-label">MNCH</label>
    </div>
</div>
</div>


<div class="form-group row">
<label class="col-sm-2 col-form-label">Date *</label>
<div class="col-sm-4">
<input type="date" name="form_date" class="form-control" required>
</div>

<label class="col-sm-2 col-form-label">ANC Card# *</label>
<div class="col-sm-4">
<input type="text" name="anc_card_no" class="form-control" required>
</div>
</div>


<div class="form-group row">
<label class="col-sm-2 col-form-label">Client Type *</label>
<div class="col-sm-10">

<div class="form-check form-check-inline">
<input class="form-check-input" type="radio" name="client_type" value="New" required>
<label class="form-check-label">New Client</label>
</div>

<div class="form-check form-check-inline">
<input class="form-check-input" type="radio" name="client_type" value="Followup" required>
<label class="form-check-label">Follow-up</label>
</div>

</div>
</div>

<div class="form-group row">
    <label class="col-sm-2 col-form-label">District *</label>
    <div class="col-sm-4">
        <select name="district" id="district" class="form-control" required>
            <?php foreach($districts as $district){ ?>
                <option value="<?= $district->district_id ?>" selected>
                    <?= $district->district_name ?>
                </option>
            <?php } ?>
        </select>
    </div>

    <label class="col-sm-2 col-form-label">UC *</label>
    <div class="col-sm-4">
        <select name="uc" id="uc" class="form-control" required>
            <option value="">Select UC</option>
        </select>
    </div>
</div>

<div class="form-group row">
<label class="col-sm-2 col-form-label">Village *</label>
<div class="col-sm-4">
<input type="text" name="village" class="form-control" required>
</div>

<label class="col-sm-2 col-form-label">LHV Name *</label>
<div class="col-sm-4">
<input type="text" name="lhv_name" class="form-control" required>
</div>
</div>


<div class="form-group row">
<label class="col-sm-2 col-form-label">Patient Name *</label>
<div class="col-sm-4">
<input type="text" name="patient_name" class="form-control" required>
</div>

<label class="col-sm-2 col-form-label">Guardian Name *</label>
<div class="col-sm-4">
<input type="text" name="guardian_name" class="form-control" required>
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

<div class="form-check">
<input class="form-check-input" type="radio" name="age_group" value="<1" required>
<label class="form-check-label">&lt;1</label>
</div>

<div class="form-check">
<input class="form-check-input" type="radio" name="age_group" value="1-5" required>
<label class="form-check-label">1-5</label>
</div>

<div class="form-check">
<input class="form-check-input" type="radio" name="age_group" value="15-49" required>
<label class="form-check-label">15-49</label>
</div>

</div>


<label class="col-sm-2 col-form-label">Marital Status *</label>
<div class="col-sm-4">

<div class="form-check">
<input class="form-check-input" type="radio" name="marital_status" value="Married" required>
<label class="form-check-label">Married</label>
</div>

<div class="form-check">
<input class="form-check-input" type="radio" name="marital_status" value="Unmarried" required>
<label class="form-check-label">Unmarried</label>
</div>

</div>
</div>



<div class="form-group row">

<label class="col-sm-2 col-form-label">Pregnancy *</label>
<div class="col-sm-4">

<div class="form-check">
<input class="form-check-input" type="radio" name="pregnancy_status" value="Pregnant" required>
<label class="form-check-label">Pregnant</label>
</div>

<div class="form-check">
<input class="form-check-input" type="radio" name="pregnancy_status" value="Non-Pregnant" required>
<label class="form-check-label">Non-Pregnant</label>
</div>

</div>


<label class="col-sm-2 col-form-label">Disability *</label>
<div class="col-sm-4">

<div class="form-check">
<input class="form-check-input" type="radio" name="disability" value="Yes" required>
<label class="form-check-label">Yes</label>
</div>

<div class="form-check">
<input class="form-check-input" type="radio" name="disability" value="No" required>
<label class="form-check-label">No</label>
</div>

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

<h4 class="section-title">🩺 <?php echo htmlspecialchars($section_name); ?></h4>

<?php foreach($section_questions as $q): ?>

<div class="form-group row">

<label class="col-sm-5 col-form-label">
<span class="q-num"><?php echo $q->q_num; ?></span>
<?php echo htmlspecialchars($q->q_text); ?>
</label>

<div class="col-sm-7">

<?php $options = isset($q->options) ? $q->options : array(); ?>

<?php if($q->q_type == 'text'): ?>

<input type="text"
name="question[<?php echo $q->question_id; ?>][0]"
class="form-control">

<?php else: ?>

<?php foreach($options as $opt): ?>

<div class="form-check">
<input class="form-check-input"
type="<?php echo $q->q_type; ?>"
name="question[<?php echo $q->question_id; ?>]<?php if($q->q_type=='checkbox') echo '[]'; ?>"
value="<?php echo $opt->option_id; ?>">

<label class="form-check-label">
<?php echo htmlspecialchars($opt->option_text); ?>
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
💾 Save Patient Record
</button>
</div>

</div>
</div>

</form>

</div>
    
<!-- Load jQuery first -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>    
<script>
$(document).ready(function() {

    function loadUC(district_id) {
        $.ajax({
            url: "<?= base_url('forms/get_uc_by_district') ?>",
            type: "POST",
            data: { district_id: district_id },
            dataType: "json",
            success: function(data){
                $('#uc').empty();
                $('#uc').append('<option value="">Select UC</option>'); // keep placeholder for UC
                $.each(data, function(i, obj){
                    $('#uc').append('<option value="'+obj.pk_id+'">'+obj.uc+'</option>');
                });
            }
        });
    }

    // Load UCs for the only district on page load
    var district_id = $('#district').val();
    if(district_id) {
        loadUC(district_id);
    }

});
</script> 
