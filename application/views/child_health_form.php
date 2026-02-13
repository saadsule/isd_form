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
    <h1 class="header-title">Child Health Form</h1>
</div>

<form method="post" action="<?= base_url('forms/save_child_health') ?>">

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

<div class="mb-4">
<strong>Visit Type</strong>
<div class="mt-2">
    <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" name="visit_type" value="Fixed Site" required>
        <label class="form-check-label">Fixed Site</label>
    </div>
    <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" name="visit_type" value="Outreach" required>
        <label class="form-check-label">Outreach</label>
    </div>
</div>
</div>

<div class="form-group row">
    <label class="col-sm-2 col-form-label">Date *</label>
    <div class="col-sm-4">
        <input type="date" name="form_date" class="form-control" required>
    </div>

    <label class="col-sm-2 col-form-label">QR Code *</label>
    <div class="col-sm-4">
        <input type="text" name="qr_code" class="form-control" required>
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
    <label class="col-sm-2 col-form-label">HF/Village *</label>
    <div class="col-sm-4">
        <input type="text" name="village" class="form-control" required>
    </div>

    <label class="col-sm-2 col-form-label">Vaccinator *</label>
    <div class="col-sm-4">
        <input type="text" name="vaccinator_name" class="form-control" required>
    </div>
</div>

<div class="form-group row">
    <label class="col-sm-2 col-form-label">Patient Name *</label>
    <div class="col-sm-4">
        <input type="text" name="patient_name" class="form-control" required>
    </div>

    <label class="col-sm-2 col-form-label">Father/Husband *</label>
    <div class="col-sm-4">
        <input type="text" name="guardian_name" class="form-control" required>
    </div>
</div>

</div>
</div>


<!-- ================= DEMOGRAPHICS ================= -->
<div class="card mb-4 form-section">
<div class="card-body">

<h4 class="section-title">👶 Demographics</h4>

<div class="form-group row">
    <label class="col-sm-2 col-form-label">Date of Birth *</label>
    <div class="col-sm-4">
        <input type="date" name="dob" class="form-control" required>
    </div>

    <label class="col-sm-2 col-form-label">Gender *</label>
    <div class="col-sm-4">
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="gender" value="Male" required>
            <label class="form-check-label">Male</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="gender" value="Female" required>
            <label class="form-check-label">Female</label>
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
class="form-control">

<?php else: ?>

<?php foreach($options as $opt): ?>

<div class="form-check">
<input class="form-check-input"
type="<?= $q->q_type; ?>"
name="question[<?= $q->question_id ?>]<?= ($q->q_type=='checkbox') ? '[]' : '' ?>"
value="<?= $opt->option_id; ?>">

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
💾 Save Child Health Record
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
