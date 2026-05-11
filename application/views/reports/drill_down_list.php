<?php
// ══════════════════════════════════════════════════════════════════════════
//  views/reports/drill_down_list.php
//  Shows list of children for a clicked matrix cell (registration or FU)
// ══════════════════════════════════════════════════════════════════════════

$records  = isset($records)  ? $records  : array();
$type     = isset($type)     ? $type     : 'reg';
$uc_id    = isset($uc_id)    ? $uc_id    : 0;
$uc_name  = isset($uc_name)  ? $uc_name  : '';
$month    = isset($month)    ? $month    : '';
$total    = count($records);

// ── Vaccine field map: DB column => Display label ─────────────────────────
// ⚠️  ADJUST these keys to match your actual child_health_master column names
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
    // add more as needed
);

// ── PLK / Nutrition question labels ───────────────────────────────────────
// These three questions stay visible in QR history only — shown here as info columns

function fmt_month_dd($ym) {
    return DateTime::createFromFormat('Y-m', $ym)->format('M Y');
}
function age_dd($row) {
    $p = array();
    if (!empty($row['age_year'])  && $row['age_year']  > 0) $p[] = $row['age_year']  . 'y';
    if (!empty($row['age_month']) && $row['age_month'] > 0) $p[] = $row['age_month'] . 'm';
    if (!empty($row['age_day'])   && $row['age_day']   > 0) $p[] = $row['age_day']   . 'd';
    return $p ? implode(' ', $p) : '—';
}

$is_reg        = ($type === 'reg');
$month_display = fmt_month_dd($month);
$type_label    = $is_reg ? 'Registrations' : 'Follow-Ups';
$accent_color  = $is_reg ? '#2980b9' : '#27ae60';
$accent_bg     = $is_reg ? '#e8f4fd' : '#eaf6f0';
$back_url      = base_url('reports/follow_up_status');
?>

<style>
.dd-header-card {
    background: linear-gradient(135deg, #2c3e50 0%, #3d5a80 100%);
    border-radius: 10px; padding: 18px 24px; margin-bottom: 22px; color: #fff;
}
.dd-header-card .dd-title { font-size: 18px; font-weight: 700; margin-bottom: 4px; }
.dd-header-card .dd-meta  { font-size: 13px; opacity: .8; }
.dd-badge {
    display: inline-block; background: rgba(255,255,255,.18);
    border-radius: 20px; padding: 3px 12px; font-size: 12px;
    font-weight: 600; margin-top: 8px; letter-spacing: .3px;
}
.dd-count { font-size: 32px; font-weight: 700; }
.dd-count-lbl { font-size: 11px; opacity: .75; text-transform: uppercase; letter-spacing: .5px; }

/* Table */
.dd-table thead tr { background: linear-gradient(135deg, #2c3e50 0%, #3d5a80 100%); }
.dd-table thead th {
    color: #fff !important; font-size: .68rem; font-weight: 600;
    text-transform: uppercase; letter-spacing: .5px;
    padding: 10px 8px; border: none !important;
    white-space: nowrap; vertical-align: middle;
}
.dd-table tbody td { font-size: .80rem; vertical-align: middle; padding: 8px 8px; border-color: #edf0f4 !important; }
.dd-table tbody tr:hover { background: #f5f8fc; }

.type-badge {
    display: inline-block; padding: 2px 9px; border-radius: 12px;
    font-size: .68rem; font-weight: 700;
}
.vacc-pill {
    display: inline-block; margin: 1px 2px; padding: 1px 6px;
    border-radius: 10px; font-size: .65rem; font-weight: 600;
    background: #e8f4fd; color: #1a5276; white-space: nowrap;
}
.vacc-none { color: #ccc; font-size: .75rem; }

.btn-back {
    background: rgba(255,255,255,.15); color: #fff; border: 1px solid rgba(255,255,255,.3);
    border-radius: 6px; padding: 4px 14px; font-size: 13px; text-decoration: none;
    transition: background .15s;
}
.btn-back:hover { background: rgba(255,255,255,.25); color: #fff; text-decoration: none; }

.dd-empty { text-align: center; padding: 60px 20px; color: #8a9ab0; }
.dd-empty i { font-size: 48px; margin-bottom: 16px; display: block; }
</style>

<div class="page-container">
<div class="main-content">

<!-- BACK + HEADER CARD -->
<div class="dd-header-card">
    <div class="row align-items-center">
        <div class="col-md-9">
            <div class="mb-2">
                <a href="<?= $back_url ?>" class="btn-back">
                    <i class="fa fa-arrow-left"></i> Back to Report
                </a>
            </div>
            <div class="dd-title">
                <i class="fa <?= $is_reg ? 'fa-plus-circle' : 'fa-refresh' ?>"></i>
                <?= $type_label ?> — <?= htmlspecialchars($uc_name) ?>
            </div>
            <div class="dd-meta">Month: <?= $month_display ?></div>
            <div>
                <span class="dd-badge"><i class="fa fa-map-marker"></i> <?= htmlspecialchars($uc_name) ?></span>
                <span class="dd-badge ml-2"><i class="fa fa-calendar"></i> <?= $month_display ?></span>
                <span class="dd-badge ml-2"
                      style="background:<?= $is_reg ? 'rgba(41,128,185,.4)' : 'rgba(39,174,96,.4)' ?>;">
                    <?= $is_reg ? 'Registrations' : 'Follow-Ups' ?>
                </span>
            </div>
        </div>
        <div class="col-md-3 text-center" style="border-left:1px solid rgba(255,255,255,.2);">
            <div class="dd-count"><?= $total ?></div>
            <div class="dd-count-lbl">Children Found</div>
        </div>
    </div>
</div>

<?php if ($total > 0): ?>
<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between">
        <h5 class="card-title mb-0">
            <i class="fa fa-list text-muted"></i>
            Children List
            <span class="badge badge-default ml-2"><?= $total ?> records</span>
        </h5>
        <small class="text-muted">
            <i class="fa fa-info-circle"></i>
            Vaccinations shown are from this specific visit record
        </small>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-bordered mb-0 dd-table">
                <thead>
                    <tr>
                        <th width="35">#</th>
                        <th width="110">QR Code</th>
                        <th>Child Name</th>
                        <th>Guardian</th>
                        <th width="85">Age / Group</th>
                        <th width="70">Gender</th>
                        <th width="90">Village</th>
                        <th>Vaccinator</th>
                        <th width="105">Form Date</th>
                        <th>Vaccinations Given</th>
                        <th width="60" class="text-center">View</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($records as $i => $row):
                    // Collect vaccines given in this visit
                    $given_vaccines = isset($row['vaccinations']) ? $row['vaccinations'] : array();
                ?>
                <tr>
                    <td class="text-muted"><?= $i + 1 ?></td>
                    <td>
                        <a href="<?= base_url('reports/qr_history_search?qr_code=') . urlencode($row['qr_code']) ?>"
                           target="_blank"
                           style="font-size:.75rem; font-weight:600; color:#2980b9;">
                            <?= htmlspecialchars($row['qr_code']) ?>
                        </a>
                    </td>
                    <td>
                        <strong style="font-size:.83rem;"><?= htmlspecialchars($row['patient_name'] ?: '—') ?></strong>
                    </td>
                    <td style="font-size:.80rem;"><?= htmlspecialchars($row['guardian_name'] ?: '—') ?></td>
                    <td>
                        <span style="font-size:.78rem;"><?= age_dd($row) ?></span>
                        <br>
                        <span class="badge badge-light" style="font-size:.68rem;">
                            <?= htmlspecialchars($row['age_group'] ?: '—') ?>
                        </span>
                    </td>
                    <td style="font-size:.80rem;"><?= ucfirst($row['gender'] ?: '—') ?></td>
                    <td style="font-size:.80rem;"><?= htmlspecialchars($row['village'] ?: '—') ?></td>
                    <td style="font-size:.80rem;"><?= htmlspecialchars($row['vaccinator_name'] ?: '—') ?></td>
                    <td>
                        <strong style="font-size:.80rem;">
                            <?= !empty($row['form_date']) ? date('d M Y', strtotime($row['form_date'])) : '—' ?>
                        </strong>
                    </td>
                    <td>
                        <?php if (!empty($given_vaccines)): ?>
                            <?php foreach ($given_vaccines as $v): ?>
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

<?php else: ?>
<div class="card">
    <div class="card-body dd-empty">
        <i class="fa fa-search text-muted"></i>
        <h5 class="text-muted">No records found for this selection</h5>
        <p class="text-muted" style="font-size:13px;">
            <?= htmlspecialchars($uc_name) ?> — <?= $month_display ?> — <?= $type_label ?>
        </p>
        <a href="<?= $back_url ?>" class="btn btn-primary">
            <i class="fa fa-arrow-left"></i> Back to Report
        </a>
    </div>
</div>
<?php endif; ?>

</div>