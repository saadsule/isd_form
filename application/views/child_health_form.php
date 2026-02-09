<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Child Health Form</title>

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

/* SECTION BOX (USED EVERYWHERE NOW) */
.section{
    border:1.8px solid #000;
    padding:18px;
    margin-bottom:18px;
}

/* SECTION TITLE */
.section-title{
    font-weight:800;
    font-size:16px;
    border-bottom:2px solid #000;
    padding-bottom:6px;
    margin-bottom:15px;
}

/* LABEL */
.label{
    font-weight:700;
    font-size:13px;
}

/* INPUT LOOK */
.form-control-sm{
    border:1px solid #999;
}

/* CHECKBOX */
.form-check{
    margin-right:18px;
}

.checkbox-group{
    display:flex;
    flex-wrap:wrap;
}

/* BACK BAR */
.topbar{
    background:#fff;
    padding:10px 20px;
    border-bottom:1px solid #ddd;
    box-shadow:0 2px 5px rgba(0,0,0,.04);
    position:sticky;
    top:0;
    z-index:999;
}

/* SUBMIT */
.submit-btn{
    padding:12px 60px;
    font-size:18px;
    font-weight:700;
    border-radius:6px;
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

<!-- TOP BAR -->
<div class="topbar">
<a href="<?= base_url() ?>" class="btn btn-secondary btn-sm">
← Back to Home
</a>
</div>

<form method="post" action="<?= base_url('forms/save_child_health') ?>">

<div class="form-page">

<!-- HEADER -->
<table class="header-table">
<tr>
<td style="width:20%">
<img src="<?= base_url('assets/images/logo/kp_logo.png') ?>" style="max-height:65px;">
</td>

<td style="width:60%">
<h1 class="title-main">CHILD HEALTH FORM</h1>
<p class="title-sub">Fixed Site Outreach</p>
</td>

<td style="width:20%; text-align:right;">
<img src="<?= base_url('assets/images/logo/pf.png') ?>" style="max-height:65px;">
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
<span class="label">2. QR Code#:</span>
<input type="text" name="qr_code" class="form-control form-control-sm">
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
<div class="col-md-6">
<span class="label">3. District:</span>
<input type="text" name="district" class="form-control form-control-sm">
</div>

<div class="col-md-6">
<span class="label">4. UC:</span>
<input type="text" name="uc" class="form-control form-control-sm">
</div>
</div>

<div class="row mt-2">
<div class="col-md-6">
<span class="label">5. HF/Village:</span>
<input type="text" name="village" class="form-control form-control-sm">
</div>

<div class="col-md-6">
<span class="label">6. Vaccinator Name:</span>
<input type="text" name="vaccinator_name" class="form-control form-control-sm">
</div>
</div>

<div class="row mt-2">
<div class="col-md-6">
<span class="label">7. Patient Name:</span>
<input type="text" name="patient_name" class="form-control form-control-sm">
</div>

<div class="col-md-6">
<span class="label">8. Father/Husband Name:</span>
<input type="text" name="guardian_name" class="form-control form-control-sm">
</div>
</div>
</div>

<!-- AGE / GENDER -->
<div class="section d-flex p-0">
<div class="p-3 border-right" style="flex:1;">
<div class="mb-2">
<span class="label">1. Date of Birth:</span><br>
<input type="date" name="dob" class="form-control form-control-sm" style="width:200px;">
</div>

<div class="mb-2">
<span class="label">2. Age:</span><br>
Years <input type="number" name="age_year" style="width:60px;">
Months <input type="number" name="age_month" style="width:60px;">
Days <input type="number" name="age_day" style="width:60px;">
</div>

<label class="mr-3"><input type="checkbox" name="age_group[]" value="<1 Year"> &lt;1 Year</label>
<label class="mr-3"><input type="checkbox" name="age_group[]" value="1-2 Year"> 1-2 Year</label>
<label class="mr-3"><input type="checkbox" name="age_group[]" value="2-5 Year"> 2-5 Year</label>
<label><input type="checkbox" name="age_group[]" value="15-49 Year"> 15-49 Year</label>
</div>

<div class="p-3" style="flex:1.5;">
<div class="row">
<div class="col-md-6">
<span class="label">11. Gender:</span><br>
<input type="radio" name="gender" value="Male"> Male
<input type="radio" name="gender" value="Female"> Female
</div>

<div class="col-md-6">
<span class="label">12. Marital Status:</span><br>
<input type="radio" name="marital_status" value="Married"> Married
<input type="radio" name="marital_status" value="Unmarried"> Un-Married
</div>
</div>

<hr>

<span class="label">13. Pregnancy Status:</span>
<input type="radio" name="pregnancy_status" value="Pregnant"> Pregnant
<input type="radio" name="pregnancy_status" value="Non-Pregnant"> Non-Pregnant
<br>

<span class="label">14. Disability:</span>
<input type="radio" name="disability" value="Yes"> Yes
<input type="radio" name="disability" value="No"> No
<br>

<span class="label">15. Play & Learning Kit:</span>
<input type="radio" name="play_learning_kit" value="Yes"> Yes
<input type="radio" name="play_learning_kit" value="No"> No
<br>

<span class="label">16. Nutrition Package:</span>
<input type="radio" name="nutrition_package" value="Yes"> Yes
<input type="radio" name="nutrition_package" value="No"> No
</div>
</div>

<!-- ================= DYNAMIC SECTION ================= -->
<?php
$sections = [];
foreach($questions as $q){
    $sections[$q->q_section][] = $q;
}
?>

<?php foreach($sections as $section_name => $section_questions): ?>
<div class="section">
    <div class="section-title"><?= htmlspecialchars($section_name) ?></div>
    <div class="row">
        <?php $col = count($section_questions) == 1 ? 'col-md-12' : 'col-md-6'; ?>
        
        <?php foreach($section_questions as $q): ?>
        <div class="<?= $col ?> mb-3">
            <div class="label mb-1"><?= htmlspecialchars($q->q_num.' '.$q->q_text) ?></div>

            <?php $options = isset($q->options) ? $q->options : array(); ?>

            <?php if($q->q_type === 'text'): ?>
                <?php if(!empty($options)): ?>
                    <div class="d-flex flex-wrap">
                        <?php foreach($options as $opt): ?>
                            <div class="mr-3 mb-2 d-flex align-items-center">
                                <small class="mr-2"><?= htmlspecialchars($opt->option_text) ?></small>
                                <input type="text"
                                       name="question[<?= $q->question_id ?>][<?= $opt->option_id ?>]"
                                       class="form-control form-control-sm"
                                       style="width:90px;">
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <input type="text" name="question[<?= $q->question_id ?>][0]" class="form-control form-control-sm">
                <?php endif; ?>

            <?php elseif(in_array($q->q_type,['checkbox','radio'])): ?>
                <div class="checkbox-group">
                    <?php foreach($options as $opt): ?>
                        <div class="form-check">
                            <input class="form-check-input"
                                   type="<?= $q->q_type ?>"
                                   name="question[<?= $q->question_id ?>]<?= $q->q_type=='checkbox'?'[]':'' ?>"
                                   value="<?= $opt->option_id ?>"> <!-- 🔥 Use option_id -->
                            <label class="form-check-label"><?= htmlspecialchars($opt->option_text) ?></label>
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
