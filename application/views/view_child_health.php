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

<?php
$status = isset($form->verification_status) ? $form->verification_status : 'Pending';

$status_color = array(
    'Pending'  => 'warning',
    'Verified' => 'success',
    'Reported' => 'danger'
);

$badge_color = isset($status_color[$status]) ? $status_color[$status] : 'secondary';
?>

<div class="page-container">
<div class="main-content">

<div id="printable-area">
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
<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">

    <!-- Left: Section Title -->
    <h4 class="section-title mb-0">📋 Basic Information</h4>

    <!-- Right: Status, Buttons, QR -->
    <div class="d-flex align-items-center flex-wrap">

        <!-- Status Badge -->
        <?php if($this->session->userdata('role') == 2  || $status!='Pending'): ?>    
            <span class="badge badge-<?php echo $badge_color; ?> mr-2" style="font-size:14px;">
                <?php echo $status; ?>
            </span>
        <?php endif; ?>

        <!-- Print Button -->
        <button class="btn btn-primary mr-2" onclick="printForm();" style="padding: 0.375rem 0.75rem; height: 32px;">
            <i class="anticon anticon-printer"></i> Print
        </button>

        <!-- Verify & Report Buttons -->
        <?php if($this->session->userdata('role') == 2 && ($form->verification_status == 'Pending' || $form->verification_status == 'Reported')): ?>
            <form method="post" action="<?= base_url('forms/verify/'.$form->master_id) ?>" style="display:inline;">
                <button type="submit" class="btn btn-success mr-2" style="padding: 0.375rem 0.75rem; height: 32px;">
                    <i class="fa fa-check"></i> Verify
                </button>
            </form>

            <button class="btn btn-danger mr-2" data-toggle="modal" data-target="#reportModal" style="padding: 0.375rem 0.75rem; height: 32px;">
                <i class="fa fa-flag"></i> Report
            </button>
        <?php endif; ?>

        <!-- QR Code -->
        <?php if(!empty($form->qr_code)): ?>
            <img 
                src="https://api.qrserver.com/v1/create-qr-code/?size=80x80&data=<?= urlencode($form->qr_code) ?>" 
                alt="QR Code"
                class="ml-2"
            >
        <?php endif; ?>

    </div>

</div>
<table class="table table-bordered">
<tr>
    <th>1. Date</th>
    <td><?= $form->form_date ?: '-' ?></td>
    <th>2. QR Code#</th>
    <td><?= $form->qr_code ?: '-' ?></td>
</tr>
<tr>
    <th>Client Type</th>
    <td><?= $form->client_type ?: '-' ?></td>
    <th>Visit Type</th>
    <td><?= $form->visit_type ?: '-' ?></td>
</tr>
<tr>
    <th>3. District</th>
    <td><?= $form->district_name ?: '-' ?></td>
    <th>4. UC</th>
    <td><?= $form->uc_name ?: '-' ?></td>
</tr>
<tr>
    <th>5. HF/Village</th>
    <td><?= $form->village ?: '-' ?></td>
    <th>6. Vaccinator name</th>
    <td><?= $form->vaccinator_name ?: '-' ?></td>
</tr>
<tr>
    <th>7. Patient’s name</th>
    <td><?= $form->patient_name ?: '-' ?></td>
    <th>8. Father/ Husband’s name</th>
    <td><?= $form->guardian_name ?: '-' ?></td>
</tr>
<?php if(isset($form->visit_type) && $form->visit_type == 'Fixed Site') { ?>
<tr>
    <th>Facility Name</th>
    <td><?= $form->facility_name ?: '-' ?></td>
</tr>
<?php } ?>
</table>
</div>
</div>

<!-- ================= DEMOGRAPHICS ================= -->
<div class="card mb-4 form-section">
    <div class="card-body">
    <h4 class="section-title">👶 Demographics</h4>

    <table class="table table-bordered">

    <?php
        if(!empty($form->dob) && $form->dob != '0000-00-00'){
            $dob = new DateTime($form->dob);
            $today = new DateTime();
            $age = $today->diff($dob); // DateInterval object
            $age_display = $age->y . 'Y ' . $age->m . 'M ' . $age->d . 'D';
        } else {
            $age_display = '-';
        }
    ?>
    <tr>
        <th>9. DOB</th>
        <td><?= !empty($form->dob) ? $form->dob : '-' ?></td>
        <th>10. Age</th>
        <td><?= $age_display ?></td>
    </tr>

    <tr>
        <th>Age Group</th>
        <td><?= $form->age_group ?: '-' ?></td>

        <th>11. Gender</th>
        <td><?= $form->gender ?: '-' ?></td>
    </tr>

    <tr>
        <th>12. Marital Status</th>
        <td><?= $form->marital_status ?: '-' ?></td>

        <th>13. Pregnancy Status</th>
        <td><?= $form->pregnancy_status ?: '-' ?></td>
    </tr>

    <tr>
        <th>14. Disability</th>
        <td><?= $form->disability ?: '-' ?></td>

        <th>15. Play & Learning Kit</th>
        <td><?= $form->play_learning_kit ?: '-' ?></td>
    </tr>

    <tr>
        <th>16. Nutrition Package</th>
        <td><?= $form->nutrition_package ?: '-' ?></td>

        <th></th>
        <td></td>
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
    if(!empty($answers)){
        if(count($answers) === 1){
            // single answer stays inline
            echo htmlspecialchars($answers[0]);
        } else {
            // multiple answers → show each in new line with tick
            foreach($answers as $ans){
                echo '<div><i class="fa fa-check text-success mr-1"></i>' . htmlspecialchars($ans) . '</div>';
            }
        }
    } else {
        echo '-';
    }
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
</div>
    
<div class="modal fade" id="reportModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" action="<?= base_url('forms/report/'.$form->master_id) ?>">
                <div class="modal-header">
                    <h5 class="modal-title">Report Form</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <label>Reason for Reporting:</label>
                    <textarea name="report_reason" class="form-control" required></textarea>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-danger">Submit Report</button>
                </div>
            </form>
        </div>
    </div>
</div>

    
<script>
    function printForm() {
        var printContents = document.getElementById('printable-area').innerHTML;
        var originalContents = document.body.innerHTML;

        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
    }
</script>
