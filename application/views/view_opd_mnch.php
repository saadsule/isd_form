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

/* Header Logos */
.form-logos img{
    height:55px;
    margin-right:10px;
}

/* Question number */
.q-num{
    color:#28a745;
    font-weight:800;
    margin-right:6px;
}
</style>

<div class="page-container">
<div class="main-content">

<div class="page-header mb-4">
    <h2 class="header-title">OPD / MNCH Form</h2>
</div>

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

<!-- ================= BASIC INFORMATION ================= -->
<div class="card mb-4 form-section">
<div class="card-body">
<h4 class="section-title">📋 Basic Information</h4>
<table class="table table-bordered">
<tr>
    <th>Date</th>
    <td><?= !empty($master->form_date) ? $master->form_date : '-' ?></td>
    <th>ANC Card #</th>
    <td><?= !empty($master->anc_card_no) ? $master->anc_card_no : '-' ?></td>
</tr>
<tr>
    <th>Visit Type</th>
    <td><?= !empty($master->visit_type) ? $master->visit_type : '-' ?></td>
    <th>Client Type</th>
    <td><?= !empty($master->client_type) ? $master->client_type : '-' ?></td>
</tr>
<tr>
    <th>District</th>
    <td><?= !empty($master->district) ? $master->district : '-' ?></td>
    <th>UC</th>
    <td><?= !empty($master->uc) ? $master->uc : '-' ?></td>
</tr>
<tr>
    <th>Village</th>
    <td><?= !empty($master->village) ? $master->village : '-' ?></td>
    <th>LHV Name</th>
    <td><?= !empty($master->lhv_name) ? $master->lhv_name : '-' ?></td>
</tr>
<tr>
    <th>Patient</th>
    <td><?= !empty($master->patient_name) ? $master->patient_name : '-' ?></td>
    <th>Guardian</th>
    <td><?= !empty($master->guardian_name) ? $master->guardian_name : '-' ?></td>
</tr>
</table>
</div>
</div>

<!-- ================= PATIENT STATUS ================= -->
<div class="card mb-4 form-section">
<div class="card-body">
<h4 class="section-title">👤 Patient Status</h4>
<table class="table table-bordered">
<tr>
    <th>Age Group</th>
    <td><?= !empty($master->age_group) ? $master->age_group : '-' ?></td>
    <th>Marital Status</th>
    <td><?= !empty($master->marital_status) ? $master->marital_status : '-' ?></td>
</tr>
<tr>
    <th>Pregnancy</th>
    <td><?= !empty($master->pregnancy_status) ? $master->pregnancy_status : '-' ?></td>
    <th>Disability</th>
    <td><?= !empty($master->disability) ? $master->disability : '-' ?></td>
</tr>
</table>
</div>
</div>

<!-- ================= DYNAMIC QUESTIONS ================= -->
<?php
$sections = array();
foreach($questions as $q){
    $sections[$q->q_section][] = $q;
}
?>

<?php foreach($sections as $section => $qs): ?>
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
    echo !empty($q->answer) ? $q->answer : '-';
}else{
    $answers = array();
    if(!empty($q->options)){
        foreach($q->options as $opt){
            if((isset($opt->selected_option) && $opt->selected_option == $opt->option_id)
               || (isset($opt->answer) && $opt->answer == $opt->option_id)){
                $answers[] = $opt->option_text;
            }
        }
    }
    echo !empty($answers) ? implode(', ', $answers) : '-';
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
