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
    margin-bottom:25px;
}

/* Section Titles */
.section-title{
    font-weight:800;
    color:#2c3e50;
    margin-bottom:20px;
}

/* Table Styling */
.table{
    margin-bottom:0;
    border-radius:10px;
    overflow:hidden;
}
.table th, .table td{
    vertical-align:middle;
    padding:12px 15px;
    font-weight:500;
    color:#34495e;
}
.table th{
    background:#f1f3f6;
}
.table-striped tbody tr:nth-of-type(odd){
    background:#f9f9f9;
}

/* Header Images */
.form-logos img{
    height:55px;
    margin-right:10px;
}

/* Q-num styling */
.q-num{
    color:#28a745;
    font-weight:800;
    margin-right:6px;
}
</style>

<div class="page-container">
<div class="main-content">

<div class="page-header mb-4">
    <h2 class="header-title">Child Health Form</h2>
</div>

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
<table class="table table-bordered">
<tr>
    <th>Date</th>
    <td><?= $form->form_date ?: '-' ?></td>
    <th>QR Code</th>
    <td><?= $form->qr_code ?: '-' ?></td>
</tr>
<tr>
    <th>Client Type</th>
    <td><?= $form->client_type ?: '-' ?></td>
    <th>Visit Type</th>
    <td><?= $form->visit_type ?: '-' ?></td>
</tr>
<tr>
    <th>District</th>
    <td><?= $form->district_name ?: '-' ?></td>
    <th>UC</th>
    <td><?= $form->uc_name ?: '-' ?></td>
</tr>
<tr>
    <th>Village</th>
    <td><?= $form->village ?: '-' ?></td>
    <th>Vaccinator</th>
    <td><?= $form->vaccinator_name ?: '-' ?></td>
</tr>
<tr>
    <th>Patient Name</th>
    <td><?= $form->patient_name ?: '-' ?></td>
    <th>Guardian</th>
    <td><?= $form->guardian_name ?: '-' ?></td>
</tr>
</table>
</div>
</div>

<!-- ================= DEMOGRAPHICS ================= -->
<div class="card mb-4 form-section">
<div class="card-body">
<h4 class="section-title">👶 Demographics</h4>
<table class="table table-bordered">
<tr>
    <th>DOB</th>
    <td><?= $form->dob ?: '-' ?></td>
    <th>Age</th>
    <td>
        <?= $form->age_year ?: '0' ?>Y
        <?= $form->age_month ?: '0' ?>M
        <?= $form->age_day ?: '0' ?>D
    </td>
</tr>
<tr>
    <th>Gender</th>
    <td><?= $form->gender ?: '-' ?></td>
    <th>Marital Status</th>
    <td><?= $form->marital_status ?: '-' ?></td>
</tr>
<tr>
    <th>Pregnancy Status</th>
    <td><?= $form->pregnancy_status ?: '-' ?></td>
    <th>Disability</th>
    <td><?= $form->disability ?: '-' ?></td>
</tr>
<tr>
    <th>Play Kit</th>
    <td><?= $form->play_learning_kit ?: '-' ?></td>
    <th>Nutrition</th>
    <td><?= $form->nutrition_package ?: '-' ?></td>
</tr>
</table>
</div>
</div>

<!-- ================= DYNAMIC QUESTIONS ================= -->
<?php foreach($questions as $section => $qs): ?>
<div class="card mb-4 form-section">
<div class="card-body">
<h4 class="section-title">🩺 <?= htmlspecialchars($section) ?></h4>
<table class="table table-striped">
<?php foreach($qs as $q): ?>
<tr>
<td width="65%">
    <span class="q-num"><?= $q->q_num ?></span>
    <?= htmlspecialchars($q->q_text) ?>
</td>
<td>
<?php
if($q->q_type == 'text'){
    echo $q->answer ?: '-';
}else{
    $answers = [];
    foreach($q->options as $opt){
        if(
            (isset($opt->selected_option) && $opt->selected_option == $opt->option_id)
            || (isset($opt->answer) && $opt->answer == $opt->option_id)
        ){
            $answers[] = $opt->option_text;
        }

    }
    echo $answers ? implode(', ', $answers) : '-';
}
?>
</td>
</tr>
<?php endforeach; ?>
</table>
</div>
</div>
<?php endforeach; ?>

</div>
</div>
</div>
