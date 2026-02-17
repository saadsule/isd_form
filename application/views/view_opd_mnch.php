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


<?php
$status = isset($master->verification_status) ? $master->verification_status : 'Pending';

$status_color = array(
    'Pending'  => 'warning',
    'Verified' => 'success',
    'Reported' => 'danger'
);

$badge_color = isset($status_color[$status]) ? $status_color[$status] : 'secondary';
?>

<div class="page-container">
<div class="main-content">

<div class="page-header mb-4 d-flex justify-content-between align-items-center">
    <h2 class="header-title">OPD / MNCH Form
        <span class="badge badge-<?php echo $badge_color; ?>" style="font-size:14px;">
            <?php echo $status; ?>
        </span>
    </h2>
    <!-- Print Button -->
    <div>
        <button class="btn btn-primary" onclick="printForm();">
            <i class="anticon anticon-printer"></i> Print
        </button>
    </div>
</div>
    
<?php if($this->session->userdata('role') == 2 && ($master->verification_status == 'Pending' || $master->verification_status == 'Reported')): ?>

<div class="mb-3 text-right">

    <!-- VERIFY BUTTON -->
    <form method="post" action="<?= base_url('forms/verify_opd_mnch/'.$master->id) ?>" style="display:inline;">
        <button type="submit" class="btn btn-success">
            <i class="fa fa-check"></i> Verify
        </button>
    </form>

    <!-- REPORT BUTTON -->
    <button class="btn btn-danger" data-toggle="modal" data-target="#reportModal">
        <i class="fa fa-flag"></i> Report
    </button>

</div>

<?php endif; ?>
    
<div id="printable-area">
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
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="section-title mb-0">📋 Basic Information</h4>

    <?php if(!empty($master->qr_code)): ?>
        <img 
        src="https://api.qrserver.com/v1/create-qr-code/?size=80x80&data=<?= urlencode($master->qr_code) ?>" 
        alt="QR Code">
    <?php endif; ?>
</div>
<table class="table table-bordered">
<tr>
    <th>Date</th>
    <td><?= !empty($master->form_date) ? $master->form_date : '-' ?></td>
    <th>ANC Card # (In case of MNCH)</th>
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
    <td><?= !empty($master->district_name) ? $master->district_name : '-' ?></td>
    <th>UC</th>
    <td><?= !empty($master->uc_name) ? $master->uc_name : '-' ?></td>
</tr>
<tr>
    <th>HF/Village</th>
    <td><?= !empty($master->village) ? $master->village : '-' ?></td>
    <th>HT/ LHV Name</th>
    <td><?= !empty($master->lhv_name) ? $master->lhv_name : '-' ?></td>
</tr>
<tr>
    <th>Patient’s name</th>
    <td><?= !empty($master->patient_name) ? $master->patient_name : '-' ?></td>
    <th>Father/ Husband’s name</th>
    <td><?= !empty($master->guardian_name) ? $master->guardian_name : '-' ?></td>
</tr>
<tr>
    <th>Facility</th>
    <td><?= !empty($master->facility_name) ? $master->facility_name : '-' ?></td>
    <th>QR Code#</th>
    <td><?= $master->qr_code ?: '-' ?></td>
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
    <th>Any Disability</th>
    <td><?= !empty($master->disability) ? $master->disability : '-' ?></td>
</tr>
<tr>
    <th>Marital Status</th>
    <td><?= !empty($master->marital_status) ? $master->marital_status : '-' ?></td>    
    <th>Pregnancy Status</th>
    <td><?= !empty($master->pregnancy_status) ? $master->pregnancy_status : '-' ?></td>
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
                        echo !empty($q->answer) ? htmlspecialchars($q->answer) : '-';
                    } else {
                        $answers = [];
                        if(!empty($q->options)){
                            foreach($q->options as $opt){
                                if(
                                    (isset($opt->selected_option) && $opt->selected_option == $opt->option_id)
                                    || (isset($opt->answer) && $opt->answer == $opt->option_id)
                                ){
                                    $answers[] = $opt->option_text;
                                }
                            }
                        }

                        if(!empty($answers)){
                            if(count($answers) == 1){
                                // Single answer → show in one line
                                echo htmlspecialchars($answers[0]);
                            } else {
                                // Multiple answers → show each on separate line with tick
                                foreach($answers as $ans){
                                    echo '<div><i class="fa fa-check text-success mr-1"></i> ' . htmlspecialchars($ans) . '</div>';
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
            <form method="post" action="<?= base_url('forms/report_opd_mnch/'.$master->id) ?>">
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
