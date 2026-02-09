<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>OPD MNCH Form</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body{
    background:#f4f6f9;
}

/* MAIN PAPER */
.form-page{
    background:#fff;
    max-width:1100px;
    margin:25px auto;
    padding:25px;
    border:1px solid #bbb;
    box-shadow:0 3px 10px rgba(0,0,0,.05);
}

/* HEADER */
.header-table{
    width:100%;
    border-bottom:3px solid #000;
    margin-bottom:20px;
}

.title-main{
    margin:0;
    font-size:26px;
    font-weight:800;
    text-align:center;
    letter-spacing:1px;
}

.title-sub{
    margin:0;
    text-align:center;
    font-size:14px;
}

/* SECTION */
.section{
    border:1.8px solid #000;
    padding:18px;
    margin-bottom:18px;
}

.section-title{
    font-weight:800;
    font-size:16px;
    border-bottom:2px solid #000;
    padding-bottom:6px;
    margin-bottom:15px;
}

.label{
    font-weight:700;
    font-size:13px;
}

.form-control-sm{
    border:1px solid #999;
}

.checkbox-group{
    display:flex;
    flex-wrap:wrap;
}

.form-check{
    margin-right:20px;
}

.topbar{
    background:#fff;
    padding:10px 20px;
    border-bottom:1px solid #ddd;
    position:sticky;
    top:0;
    z-index:999;
}

.submit-btn{
    padding:12px 60px;
    font-size:18px;
    font-weight:700;
}

/* SUBMIT BUTTON */
.pro-submit-btn{
    background: linear-gradient(135deg,#28a745,#1e7e34);
    color:#fff;
    border:none;
    padding:16px 70px;
    font-size:20px;
    font-weight:700;
    border-radius:10px;
    letter-spacing:.5px;

    box-shadow:0 6px 18px rgba(0,0,0,.15);
    transition: all .25s ease;
}

/* hover */
.pro-submit-btn:hover{
    transform: translateY(-3px);
    box-shadow:0 12px 28px rgba(0,0,0,.22);
}

/* click */
.pro-submit-btn:active{
    transform: translateY(0px);
    box-shadow:0 4px 10px rgba(0,0,0,.18);
}
</style>
</head>
<body>

<div class="topbar">
<a href="<?php echo base_url(); ?>" class="btn btn-secondary btn-sm">
← Back to Home
</a>
</div>

<form method="post" action="<?php echo base_url('forms/save_opd_mnch'); ?>">

<div class="form-page">

<!-- HEADER -->
<table class="header-table">
<tr>

<td style="width:20%">
<img src="<?php echo base_url('assets/images/logo/kp_logo.png'); ?>" style="max-height:65px;">
</td>

<td style="width:60%">
<h1 class="title-main">OPD MNCH FORM</h1>
<p class="title-sub">Maternal, Newborn & Child Health</p>
</td>

<td style="width:20%; text-align:right;">
<img src="<?php echo base_url('assets/images/logo/pf.png'); ?>" style="max-height:65px;">
</td>

</tr>
</table>


<!-- BASIC INFO -->
<div class="section">

<div class="row">

<div class="col-md-3">
<span class="label">1. Date:</span>
<input type="date" name="form_date" class="form-control form-control-sm">
</div>

<div class="col-md-3">
<span class="label">2. ANC Card#:</span>
<input type="text" name="anc_card_no" class="form-control form-control-sm">
</div>

<div class="col-md-6 text-right pt-4">
<label class="mr-3">
<input type="radio" name="client_type" value="New"> New Client
</label>

<label>
<input type="radio" name="client_type" value="Followup"> Follow-up
</label>
</div>

</div>

<hr>

<div class="row">

<div class="col-md-4">
<span class="label">District:</span>
<input type="text" name="district" class="form-control form-control-sm">
</div>

<div class="col-md-4">
<span class="label">UC:</span>
<input type="text" name="uc" class="form-control form-control-sm">
</div>

<div class="col-md-4">
<span class="label">Village:</span>
<input type="text" name="village" class="form-control form-control-sm">
</div>

</div>

<div class="row mt-2">

<div class="col-md-6">
<span class="label">LHV Name:</span>
<input type="text" name="lhv_name" class="form-control form-control-sm">
</div>

<div class="col-md-6">
<span class="label">Patient Name:</span>
<input type="text" name="patient_name" class="form-control form-control-sm">
</div>

</div>

<div class="row mt-2">

<div class="col-md-6">
<span class="label">Guardian Name:</span>
<input type="text" name="guardian_name" class="form-control form-control-sm">
</div>

</div>

</div>


<!-- STATUS BOX -->
<div class="section">

<div class="row">

<div class="col-md-3">
<span class="label">Age Group:</span><br>

<label><input type="radio" name="age_group" value="<1"> &lt;1</label><br>
<label><input type="radio" name="age_group" value="1-5"> 1-5</label><br>
<label><input type="radio" name="age_group" value="15-49"> 15-49</label>

</div>

<div class="col-md-3">
<span class="label">Marital Status:</span><br>

<label><input type="radio" name="marital_status" value="Married"> Married</label><br>
<label><input type="radio" name="marital_status" value="Unmarried"> Unmarried</label>
</div>

<div class="col-md-3">
<span class="label">Pregnancy Status:</span><br>

<label><input type="radio" name="pregnancy_status" value="Pregnant"> Pregnant</label><br>
<label><input type="radio" name="pregnancy_status" value="Non-Pregnant"> Non-Pregnant</label>
</div>

<div class="col-md-3">
<span class="label">Disability:</span><br>

<label><input type="radio" name="disability" value="Yes"> Yes</label><br>
<label><input type="radio" name="disability" value="No"> No</label>

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

<div class="section">

<div class="section-title">
<?php echo htmlspecialchars($section_name); ?>
</div>

<div class="row">

<?php
$col = count($section_questions) == 1 ? 'col-md-12' : 'col-md-6';
?>

<?php foreach($section_questions as $q): ?>

<div class="<?php echo $col; ?> mb-3">

<div class="label mb-1">
<?php echo htmlspecialchars($q->q_num.' '.$q->q_text); ?>
</div>

<?php
$options = isset($q->options) ? $q->options : array();
?>

<!-- TEXT -->
<?php if($q->q_type == 'text'): ?>

<?php if(!empty($options)): ?>

<div class="d-flex flex-wrap">

<?php foreach($options as $opt): ?>

<div class="mr-3 mb-2 d-flex align-items-center">
<small class="mr-2"><?php echo htmlspecialchars($opt->option_text); ?></small>

<input type="text"
name="question[<?php echo $q->question_id; ?>][<?php echo $opt->option_id; ?>]"
class="form-control form-control-sm"
style="width:90px;">
</div>

<?php endforeach; ?>

</div>

<?php else: ?>

<input type="text"
name="question[<?php echo $q->question_id; ?>][0]"
class="form-control form-control-sm">

<?php endif; ?>



<!-- RADIO / CHECKBOX -->
<?php else: ?>

<div class="checkbox-group">

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

</div>

<?php endif; ?>

</div>

<?php endforeach; ?>

</div>
</div>

<?php endforeach; ?>

<div class="text-center my-5">
    <button type="submit" class="pro-submit-btn">
        <span class="btn-text">
            Submit Form
        </span>
    </button>
</div>

</div>
</form>
</body>
</html>
