<?php
// ══════════════════════════════════════════════════════════════════════════
//  views/reports/qr_history_search.php   — UPDATED
// ══════════════════════════════════════════════════════════════════════════

$records  = isset($records)  ? $records  : array();
$qr_code  = isset($qr_code)  ? $qr_code  : '';
$searched = isset($searched) ? $searched : false;
$total    = count($records);

// ── Vaccine field map — ADJUST to your actual column names ────────────────
$vaccine_fields = array(
    'bcg'          => 'BCG',
    'opv_0'        => 'OPV-0',
    'opv_1'        => 'OPV-1',
    'opv_2'        => 'OPV-2',
    'opv_3'        => 'OPV-3',
    'ipv'          => 'IPV',
    'penta_1'      => 'Penta-1',
    'penta_2'      => 'Penta-2',
    'penta_3'      => 'Penta-3',
    'pcv_1'        => 'PCV-1',
    'pcv_2'        => 'PCV-2',
    'pcv_3'        => 'PCV-3',
    'mr_1'         => 'MR-1',
    'mr_2'         => 'MR-2',
    'vitamin_a'    => 'Vit-A',
    'measles'      => 'Measles',
    'typhoid'      => 'Typhoid',
    'yellow_fever' => 'Yellow Fever',
    // add more columns here as needed
);

// ── The three "question" columns always shown (PLK / Nutrition / Status) ──
// They appear as small icons in the visit row

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
    background: #fff; border: 1px solid #e3e8ef; border-radius: 10px;
    padding: 22px 28px; margin-bottom: 20px; box-shadow: 0 2px 8px rgba(0,0,0,.04);
}
.qr-search-box .input-group { max-width: 520px; }
.qr-search-box input[type="text"] {
    border-radius: 8px 0 0 8px !important; border-right: none;
    font-size: 15px; padding: 10px 16px; height: 44px; border-color: #ced4da;
}
.qr-search-box input[type="text"]:focus { box-shadow: none; border-color: #2980b9; }
.qr-search-box .btn-search {
    background: linear-gradient(135deg, #2c3e50 0%, #3d5a80 100%);
    color: #fff; border: none; border-radius: 0 8px 8px 0 !important;
    padding: 0 22px; font-size: 14px; font-weight: 600; height: 44px; letter-spacing: .3px;
}
.qr-search-box .btn-search:hover { opacity: .9; }

/* System note */
.qr-system-note {
    background: #fffbea; border: 1px solid #f0d060; border-radius: 8px;
    padding: 10px 16px; font-size: .80rem; color: #7d6608;
    margin-top: 12px; max-width: 680px;
}
.qr-system-note i { margin-right: 5px; }

/* Patient card */
.patient-card {
    background: linear-gradient(135deg, #2c3e50 0%, #3d5a80 100%);
    border-radius: 10px; padding: 18px 24px; margin-bottom: 18px; color: #fff;
}
.patient-card .p-name  { font-size: 20px; font-weight: 700; margin-bottom: 4px; }
.patient-card .p-meta  { font-size: 12px; opacity: .8; margin-bottom: 0; }
.patient-card .p-badge {
    display: inline-block; background: rgba(255,255,255,.18); border-radius: 20px;
    padding: 3px 12px; font-size: 11px; font-weight: 600; margin-top: 7px; letter-spacing: .3px;
}
.patient-card .p-stat  { text-align: center; border-left: 1px solid rgba(255,255,255,.2); padding-left: 20px; }
.patient-card .p-stat .num { font-size: 28px; font-weight: 700; }
.patient-card .p-stat .lbl { font-size: 11px; opacity: .75; text-transform: uppercase; letter-spacing: .5px; }

/* Visit table */
.visit-row-reg { border-left: 4px solid #27ae60 !important; }
.visit-row-fu  { border-left: 4px solid #2980b9 !important; }

.visit-badge {
    display: inline-block; padding: 2px 8px; border-radius: 20px;
    font-size: .68rem; font-weight: 700; white-space: nowrap;
}
.badge-reg { background: #eaf6f0; color: #1a7a4a; }
.badge-fu  { background: #e8f4fd; color: #1a5276; }

.qr-table thead tr { background: linear-gradient(135deg, #2c3e50 0%, #3d5a80 100%); }
.qr-table thead th {
    color: #fff !important; font-size: .66rem; font-weight: 600;
    text-transform: uppercase; letter-spacing: .5px; padding: 10px 8px;
    border: none !important; white-space: nowrap; vertical-align: middle;
}
.qr-table tbody td { font-size: .79rem; vertical-align: middle; padding: 8px 8px; border-color: #edf0f4 !important; }
.qr-table tbody tr:hover { background: #f5f8fc; }

.vacc-pill {
    display: inline-block; margin: 1px 2px; padding: 1px 6px;
    border-radius: 10px; font-size: .63rem; font-weight: 600;
    background: #e8f4fd; color: #1a5276; white-space: nowrap;
}
.vacc-none { color: #ccc; font-size: .75rem; }

.kit-pill {
    display: inline-block; padding: 1px 6px; border-radius: 10px;
    font-size: .65rem; font-weight: 600; margin-right: 3px;
}
.kit-yes { background: #eaf6f0; color: #1a7a4a; }
.kit-no  { background: #fdf2f2; color: #922b21; }

.qr-empty { text-align: center; padding: 60px 20px; color: #8a9ab0; }
.qr-empty i { font-size: 48px; margin-bottom: 16px; display: block; }
</style>

<div class="page-container">
<div class="main-content">

<!-- HEADER -->
<div class="page-header">
    <h2><i class="fa fa-qrcode text-primary"></i> QR Code History Search</h2>
    <p class="text-muted mb-0">Enter a QR code to view complete visit history for that child</p>
</div>

<!-- SEARCH BOX -->
<div class="qr-search-box">
    <form method="GET" action="<?= current_url() ?>">
        <label style="font-weight:600; font-size:14px; margin-bottom:10px; display:block; color:#2c3e50;">
            <i class="fa fa-search"></i> Search by QR Code
        </label>
        <div class="input-group">
            <input type="text" name="qr_code" class="form-control"
                   placeholder="Enter QR Code e.g. CHD-00123"
                   value="<?= htmlspecialchars($qr_code) ?>"
                   autocomplete="off" autofocus />
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

    <!-- System Note -->
    <div class="qr-system-note">
        <i class="fa fa-info-circle"></i>
        <strong>Note:</strong>
        This report considers a single QR code as a unique child regardless of any technical
        mistake made during digitization.
    </div>
</div>

<?php if ($searched && $total > 0):
    $first      = $records[0];
    $fu_count   = $total - 1;
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
                &nbsp;|&nbsp;
                <i class="fa fa-birthday-cake"></i> DOB: <?= $dob_display ?>
                &nbsp;|&nbsp;
                <i class="fa fa-child"></i> Age: <?= $age_display ?>
                &nbsp;|&nbsp;
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
                        <th width="30">#</th>
                        <th width="100">Visit Type</th>
                        <th width="105">Date</th>
                        <th width="85">Age / Group</th>
                        <th>Vaccinator</th>
                        <th width="95">UC</th>
                        <th width="85">Village</th>
                        <th>Vaccinations Given</th>
                        <th width="55" class="text-center">View</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($records as $i => $row):
                    $is_reg    = ($row['visit_number'] == 1);
                    $row_class = $is_reg ? 'visit-row-reg' : 'visit-row-fu';

                    // Vaccines given this visit
                    $given = isset($row['vaccinations']) ? $row['vaccinations'] : array();

                    $plk = strtolower(isset($row['play_learning_kit'])  ? $row['play_learning_kit']  : '');
                    $np  = strtolower(isset($row['nutrition_package'])  ? $row['nutrition_package']  : '');
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
                        <small class="text-muted" style="font-size:.65rem;">
                            <?= !empty($row['created_at']) ? date('d M, h:i A', strtotime($row['created_at'])) : '' ?>
                        </small>
                    </td>

                    <td>
                        <span style="font-size:.78rem;"><?= age_display_qr($row) ?></span>
                        <br>
                        <span class="badge badge-light" style="font-size:.65rem;">
                            <?= htmlspecialchars(!empty($row['age_group']) ? $row['age_group'] : '—') ?>
                        </span>
                    </td>

                    <td><?= htmlspecialchars(!empty($row['vaccinator_name']) ? $row['vaccinator_name'] : '—') ?></td>
                    <td><?= htmlspecialchars(!empty($row['uc_name'])         ? $row['uc_name']         : '—') ?></td>
                    <td><?= htmlspecialchars(!empty($row['village'])         ? $row['village']         : '—') ?></td>

                    <!-- VACCINATIONS -->
                    <td>
                        <?php if (!empty($given)): ?>
                            <?php foreach ($given as $v): ?>
                                <span class="vacc-pill"><?= $v ?></span>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <span class="vacc-none">—</span>
                        <?php endif; ?>
                    </td>

                    <td class="text-center">
                        <a href="<?= base_url('forms/view_child_health/' . $row['master_id']) ?>"
                           target="_blank" class="btn btn-sm btn-primary" title="View full form">
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