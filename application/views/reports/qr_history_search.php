<?php
$records  = isset($records)  ? $records  : array();
$qr_code  = isset($qr_code)  ? $qr_code  : '';
$searched = isset($searched) ? $searched : false;
$total    = count($records);

function age_display_qr($row) {
    $parts = array();
    if (!empty($row['age_year'])  && $row['age_year']  > 0) $parts[] = $row['age_year']  . 'y';
    if (!empty($row['age_month']) && $row['age_month'] > 0) $parts[] = $row['age_month'] . 'm';
    if (!empty($row['age_day'])   && $row['age_day']   > 0) $parts[] = $row['age_day']   . 'd';
    return !empty($parts) ? implode(' ', $parts) : '—';
}
?>

<style>
/* Search box */
.qr-search-box {
    background: #fff;
    border: 1px solid #e3e8ef;
    border-radius: 10px;
    padding: 28px 32px;
    margin-bottom: 24px;
    box-shadow: 0 2px 8px rgba(0,0,0,.04);
}
.qr-search-box .input-group {
    max-width: 520px;
}
.qr-search-box input[type="text"] {
    border-radius: 8px 0 0 8px !important;
    border-right: none;
    font-size: 15px;
    padding: 10px 16px;
    height: 44px;
    border-color: #ced4da;
}
.qr-search-box input[type="text"]:focus {
    box-shadow: none;
    border-color: #2980b9;
}
.qr-search-box .btn-search {
    background: linear-gradient(135deg, #2c3e50 0%, #3d5a80 100%);
    color: #fff;
    border: none;
    border-radius: 0 8px 8px 0 !important;
    padding: 0 22px;
    font-size: 14px;
    font-weight: 600;
    height: 44px;
    letter-spacing: .3px;
}
.qr-search-box .btn-search:hover { opacity: .9; }

/* Patient info card */
.patient-card {
    background: linear-gradient(135deg, #2c3e50 0%, #3d5a80 100%);
    border-radius: 10px;
    padding: 20px 24px;
    margin-bottom: 20px;
    color: #fff;
}
.patient-card .p-name  { font-size: 20px; font-weight: 700; margin-bottom: 4px; }
.patient-card .p-meta  { font-size: 13px; opacity: .8; margin-bottom: 0; }
.patient-card .p-badge {
    display: inline-block;
    background: rgba(255,255,255,.18);
    border-radius: 20px;
    padding: 3px 12px;
    font-size: 12px;
    font-weight: 600;
    margin-top: 8px;
    letter-spacing: .3px;
}
.patient-card .p-stat {
    text-align: center;
    border-left: 1px solid rgba(255,255,255,.2);
    padding-left: 20px;
}
.patient-card .p-stat .num { font-size: 28px; font-weight: 700; }
.patient-card .p-stat .lbl { font-size: 11px; opacity: .75; text-transform: uppercase; letter-spacing: .5px; }

/* Timeline + Table */
.visit-row-reg  { border-left: 4px solid #27ae60 !important; }
.visit-row-fu   { border-left: 4px solid #2980b9 !important; }

.visit-badge {
    display: inline-block;
    padding: 3px 10px;
    border-radius: 20px;
    font-size: .72rem;
    font-weight: 700;
    white-space: nowrap;
}
.badge-reg { background: #eaf6f0; color: #1a7a4a; }
.badge-fu  { background: #e8f4fd; color: #1a5276; }

/* Table styles */
.qr-table thead tr {
    background: linear-gradient(135deg, #2c3e50 0%, #3d5a80 100%);
}
.qr-table thead th {
    color: #fff !important;
    font-size: .70rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: .6px;
    padding: 11px 10px;
    border: none !important;
    white-space: nowrap;
    vertical-align: middle;
}
.qr-table tbody td {
    font-size: .83rem;
    vertical-align: middle;
    padding: 10px 10px;
    border-color: #edf0f4 !important;
}
.qr-table tbody tr:hover { background: #f5f8fc; }

.kit-pill {
    display: inline-block;
    padding: 2px 8px;
    border-radius: 12px;
    font-size: .70rem;
    font-weight: 600;
}
.kit-yes { background: #eaf6f0; color: #1a7a4a; }
.kit-no  { background: #fdf2f2; color: #922b21; }

.vstatus-verified   { background: #eaf6f0; color: #1a7a4a; }
.vstatus-pending    { background: #fef9e7; color: #9a7d0a; }
.vstatus-rejected   { background: #fdf2f2; color: #922b21; }

/* Empty / no result */
.qr-empty {
    text-align: center;
    padding: 60px 20px;
    color: #8a9ab0;
}
.qr-empty i { font-size: 48px; margin-bottom: 16px; display: block; }
</style>

<div class="page-container">
<div class="main-content">

<!-- HEADER -->
<div class="page-header">
    <h2>
        <i class="fa fa-qrcode text-primary"></i>
        QR Code History Search
    </h2>
    <p class="text-muted mb-0">Enter a QR code to view complete visit history for that child</p>
</div>

<!-- SEARCH BOX -->
<div class="qr-search-box">
    <form method="GET" action="<?= current_url() ?>">
        <label style="font-weight:600; font-size:14px; margin-bottom:10px; display:block; color:#2c3e50;">
            <i class="fa fa-search"></i> Search by QR Code
        </label>
        <div class="input-group">
            <input
                type="text"
                name="qr_code"
                class="form-control"
                placeholder="Enter QR Code e.g. CHD-00123"
                value="<?= htmlspecialchars($qr_code) ?>"
                autocomplete="off"
                autofocus
            />
            <div class="input-group-append">
                <button type="submit" class="btn btn-search">
                    <i class="fa fa-search"></i> Search
                </button>
            </div>
        </div>
        <?php if ($searched && $total === 0): ?>
            <small class="text-danger mt-2 d-block">
                <i class="fa fa-exclamation-circle"></i>
                No records found for QR code "<strong><?= htmlspecialchars($qr_code) ?></strong>"
            </small>
        <?php endif; ?>
    </form>
</div>

<?php if ($searched && $total > 0):
    $first = $records[0];
    $fu_count = $total - 1;
    $dob_display = !empty($first['dob']) ? date('d M Y', strtotime($first['dob'])) : '—';
    $age_display = age_display_qr($first);
?>

<!-- PATIENT SUMMARY CARD -->
<div class="patient-card">
    <div class="row align-items-center">
        <div class="col-md-8">
            <div class="p-name"><?= htmlspecialchars($first['patient_name']) ?></div>
            <div class="p-meta">
                <i class="fa fa-user-o"></i> Guardian: <?= htmlspecialchars($first['guardian_name'] ?: '—') ?>
                &nbsp;&nbsp;|&nbsp;&nbsp;
                <i class="fa fa-birthday-cake"></i> DOB: <?= $dob_display ?>
                &nbsp;&nbsp;|&nbsp;&nbsp;
                <i class="fa fa-child"></i> Age: <?= $age_display ?>
                &nbsp;&nbsp;|&nbsp;&nbsp;
                <i class="fa fa-venus-mars"></i> <?= ucfirst($first['gender'] ?: '—') ?>
            </div>
            <div>
                <span class="p-badge"><i class="fa fa-qrcode"></i> <?= htmlspecialchars($first['qr_code']) ?></span>
                <span class="p-badge ml-2"><i class="fa fa-map-marker"></i> <?= htmlspecialchars($first['uc_name'] ?: '—') ?></span>
                <span class="p-badge ml-2"><i class="fa fa-home"></i> <?= htmlspecialchars($first['village'] ?: '—') ?></span>
            </div>
        </div>
        <div class="col-md-4">
            <div class="row">
                <div class="col-6 p-stat">
                    <div class="num"><?= $total ?></div>
                    <div class="lbl">Total Visits</div>
                </div>
                <div class="col-6 p-stat">
                    <div class="num"><?= $fu_count ?></div>
                    <div class="lbl">Follow Ups</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- VISITS TABLE -->
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">
            <i class="fa fa-history text-muted"></i>
            Visit History
            <span class="badge badge-default ml-2"><?= $total ?> visits</span>
        </h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 qr-table">
                <thead>
                    <tr>
                        <th width="40">#</th>
                        <th width="90">Visit Type</th>
                        <th width="110">Form Date</th>
                        <th width="90">Age Group</th>
                        <th width="80">Age</th>
                        <th>Vaccinator</th>
                        <th width="100">UC</th>
                        <th width="90">Village</th>
<!--                        <th width="80">PLK</th>
                        <th width="80">Nutrition</th>
                        <th width="100">Status</th>-->
                        <th width="130">Entered By</th>
                        <th width="60" class="text-center">View</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($records as $i => $row):
                        $is_reg   = ($row['visit_number'] == 1);
                        $row_class = $is_reg ? 'visit-row-reg' : 'visit-row-fu';

                        $vs_raw = isset($row['verification_status']) ? $row['verification_status'] : '';
                        $vs     = strtolower($vs_raw);
                        if ($vs === 'verified')     $vs_class = 'vstatus-verified';
                        elseif ($vs === 'rejected') $vs_class = 'vstatus-rejected';
                        else                        $vs_class = 'vstatus-pending';

                        $plk = strtolower(isset($row['play_learning_kit'])   ? $row['play_learning_kit']   : '');
                        $np  = strtolower(isset($row['nutrition_package'])   ? $row['nutrition_package']   : '');
                        $vs_label = !empty($vs_raw) ? ucfirst($vs_raw) : 'Pending';
                    ?>
                        <tr class="<?= $row_class ?>">
                            <td class="text-muted"><?= $row['visit_number'] ?></td>

                            <td>
                                <?php if ($is_reg): ?>
                                    <span class="visit-badge badge-reg">
                                        <i class="fa fa-plus-circle"></i> Registration
                                    </span>
                                <?php else: ?>
                                    <span class="visit-badge badge-fu">
                                        <i class="fa fa-refresh"></i> Follow Up <?= ($row['visit_number'] - 1) ?>
                                    </span>
                                <?php endif; ?>
                            </td>

                            <td>
                                <strong><?= !empty($row['form_date']) ? date('d M Y', strtotime($row['form_date'])) : '—' ?></strong>
                                <br>
                                <small class="text-muted"><?= !empty($row['created_at']) ? date('d M, h:i A', strtotime($row['created_at'])) : '' ?></small>
                            </td>

                            <td>
                                <span class="badge badge-light"><?= htmlspecialchars(!empty($row['age_group']) ? $row['age_group'] : '—') ?></span>
                            </td>

                            <td><?= age_display_qr($row) ?></td>

                            <td><?= htmlspecialchars(!empty($row['vaccinator_name']) ? $row['vaccinator_name'] : '—') ?></td>

                            <td><?= htmlspecialchars(!empty($row['uc_name'])         ? $row['uc_name']         : '—') ?></td>

                            <td><?= htmlspecialchars(!empty($row['village'])         ? $row['village']         : '—') ?></td>

<!--                            <td>
                                <span class="kit-pill <?= ($plk === 'yes' || $plk === '1') ? 'kit-yes' : 'kit-no' ?>">
                                    <?= ($plk === 'yes' || $plk === '1') ? 'Yes' : 'No' ?>
                                </span>
                            </td>

                            <td>
                                <span class="kit-pill <?= ($np === 'yes' || $np === '1') ? 'kit-yes' : 'kit-no' ?>">
                                    <?= ($np === 'yes' || $np === '1') ? 'Yes' : 'No' ?>
                                </span>
                            </td>

                            <td>
                                <span class="kit-pill <?= $vs_class ?>">
                                    <?= $vs_label ?>
                                </span>
                            </td>-->

                            <td><?= htmlspecialchars(!empty($row['entered_by']) ? $row['entered_by'] : '—') ?></td>

                            <td class="text-center">
                                <a href="<?= base_url('forms/view_child_health/' . $row['master_id']) ?>"
                                   target="_blank" class="btn btn-sm btn-primary">
                                    <i class="fa fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php elseif (!$searched): ?>
<!-- Initial state — no search yet -->
<div class="card">
    <div class="card-body qr-empty">
        <i class="fa fa-qrcode text-muted"></i>
        <h5 class="text-muted">Enter a QR code above to view visit history</h5>
        <p class="text-muted" style="font-size:13px;">
            All registration and follow-up visits for that child will appear here
        </p>
    </div>
</div>
<?php endif; ?>

</div>