<?php
// ══════════════════════════════════════════════════════════════════════════
//  views/reports/drill_down_list.php
//  Works for BOTH:
//    - QR-based report  (source=qr,    back → follow_up_status)
//    - Forms-based report (source=forms, back → follow_up_forms)
// ══════════════════════════════════════════════════════════════════════════

$records  = isset($records)  ? $records  : array();
$type     = isset($type)     ? $type     : 'reg';
$uc_id    = isset($uc_id)    ? $uc_id    : 0;
$uc_name  = isset($uc_name)  ? $uc_name  : '';
$month    = isset($month)    ? $month    : '';
$source   = isset($source)   ? $source   : 'qr';
$back_url = isset($back_url) ? $back_url : base_url('reports/follow_up_status');
$total    = count($records);

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
$is_forms      = ($source === 'forms');
$month_display = fmt_month_dd($month);
$type_label    = $is_reg ? 'New Registrations' : 'Follow-Ups';
$accent_color  = $is_reg ? '#2980b9' : '#27ae60';
$source_label  = $is_forms ? 'Based on Forms (client_type)' : 'Based on QR Code';
$source_color  = $is_forms ? '#27ae60' : '#8e44ad';
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

.dd-table thead tr { background: linear-gradient(135deg, #2c3e50 0%, #3d5a80 100%); }
.dd-table thead th {
    color: #fff !important; font-size: .62rem; font-weight: 600;
    text-transform: uppercase; letter-spacing: .4px;
    padding: 8px 6px; border: none !important;
    white-space: nowrap; vertical-align: middle;
}
.dd-table tbody td {
    font-size: .73rem; vertical-align: middle;
    padding: 6px 6px; border-color: #edf0f4 !important;
    word-break: break-word;
}
.dd-table tbody tr:hover { background: #f5f8fc; }

/* FU rows green tint */
.dd-table tbody tr.row-fu { background: #f0faf3; }
.dd-table tbody tr.row-fu:hover { background: #e3f5e9; }

.type-badge {
    display: inline-block; padding: 2px 8px; border-radius: 12px;
    font-size: .65rem; font-weight: 700; white-space: nowrap;
}
.badge-new { background: #d6eaf8; color: #1a5276; }
.badge-fu  { background: #d5f5e3; color: #1e8449; }

/* Vaccinations as wrapped pills */
.vacc-wrap { display: flex; flex-wrap: wrap; gap: 2px; }
.vacc-pill {
    display: inline-block; padding: 1px 5px;
    border-radius: 8px; font-size: .60rem; font-weight: 600;
    background: #e8f4fd; color: #1a5276; white-space: nowrap;
    line-height: 1.6;
}
.vacc-none { color: #bbb; font-size: .68rem; }

.btn-back {
    background: rgba(255,255,255,.15); color: #fff;
    border: 1px solid rgba(255,255,255,.3);
    border-radius: 6px; padding: 4px 14px; font-size: 13px;
    text-decoration: none; transition: background .15s;
}
.btn-back:hover { background: rgba(255,255,255,.25); color: #fff; text-decoration: none; }

.dd-empty { text-align: center; padding: 60px 20px; color: #8a9ab0; }
.dd-empty i { font-size: 48px; margin-bottom: 16px; display: block; }
</style>

<div class="page-container">
<div class="main-content">

<!-- HEADER CARD -->
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
                <span class="dd-badge">
                    <i class="fa fa-map-marker"></i> <?= htmlspecialchars($uc_name) ?>
                </span>
                <span class="dd-badge ml-2">
                    <i class="fa fa-calendar"></i> <?= $month_display ?>
                </span>
                <span class="dd-badge ml-2"
                      style="background:<?= $is_reg ? 'rgba(41,128,185,.4)' : 'rgba(39,174,96,.4)' ?>;">
                    <?= $is_reg ? 'Registrations' : 'Follow-Ups' ?>
                </span>
                <span class="dd-badge ml-2"
                      style="background:<?= $is_forms ? 'rgba(39,174,96,.35)' : 'rgba(142,68,173,.35)' ?>;">
                    <i class="fa <?= $is_forms ? 'fa-wpforms' : 'fa-qrcode' ?>"></i>
                    <?= $source_label ?>
                </span>
            </div>
        </div>
        <div class="col-md-3 text-center" style="border-left:1px solid rgba(255,255,255,.2);">
            <div class="dd-count"><?= $total ?></div>
            <div class="dd-count-lbl">
                <?= $is_forms && !$is_reg ? 'Unique Children' : 'Children Found' ?>
            </div>
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
            <?php if ($is_forms): ?>
                Data source: <strong>client_type</strong> field on form
            <?php else: ?>
                Vaccinations shown are from this specific visit record
            <?php endif; ?>
        </small>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-bordered table-sm mb-0 dd-table" style="table-layout:fixed; width:100%;">
                <thead>
                    <tr>
                        <th style="width:30px">#</th>
                        <th style="width:100px">QR Code</th>
                        <th style="width:100px">Child Name</th>
                        <th style="width:90px">Guardian</th>
                        <th style="width:80px">Age / Group</th>
                        <th style="width:55px">Gender</th>
                        <th style="width:80px">Village</th>
                        <th style="width:85px">Vaccinator</th>
                        <th style="width:75px">Date</th>
                        <?php if ($is_forms): ?>
                        <th style="width:65px">Type</th>
                        <?php endif; ?>
                        <th>Vaccinations</th>
                        <th style="width:45px" class="text-center">View</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($records as $i => $row):
                    $given_vaccines = isset($row['vaccinations']) ? $row['vaccinations'] : array();
                    $client_type    = isset($row['client_type']) ? $row['client_type'] : '';
                    $row_class      = (!$is_reg && !$is_forms) ? '' :
                                     ($client_type === 'Followup' ? 'row-fu' : '');
                ?>
                <tr class="<?= $row_class ?>">
                    <td class="text-muted"><?= $i + 1 ?></td>
                    <td>
                        <a href="<?= base_url('reports/qr_history_search?qr_code=') . urlencode($row['qr_code']) ?>"
                           target="_blank"
                           style="font-size:.68rem; font-weight:600; color:#2980b9;">
                            <?= htmlspecialchars($row['qr_code']) ?>
                        </a>
                    </td>
                    <td>
                        <strong style="font-size:.72rem;"><?= htmlspecialchars($row['patient_name'] ?: '—') ?></strong>
                    </td>
                    <td style="font-size:.70rem;"><?= htmlspecialchars($row['guardian_name'] ?: '—') ?></td>
                    <td>
                        <span style="font-size:.68rem;"><?= age_dd($row) ?></span><br>
                        <span class="badge badge-light" style="font-size:.60rem;">
                            <?= htmlspecialchars($row['age_group'] ?: '—') ?>
                        </span>
                    </td>
                    <td style="font-size:.70rem;"><?= ucfirst($row['gender'] ?: '—') ?></td>
                    <td style="font-size:.70rem;"><?= htmlspecialchars($row['village'] ?: '—') ?></td>
                    <td style="font-size:.70rem;"><?= htmlspecialchars($row['vaccinator_name'] ?: '—') ?></td>
                    <td>
                        <strong style="font-size:.70rem;">
                            <?= !empty($row['form_date']) ? date('d M Y', strtotime($row['form_date'])) : '—' ?>
                        </strong>
                    </td>
                    <?php if ($is_forms): ?>
                    <td>
                        <span class="type-badge <?= $client_type === 'Followup' ? 'badge-fu' : 'badge-new' ?>">
                            <?= $client_type ?: '—' ?>
                        </span>
                    </td>
                    <?php endif; ?>
                    <td>
                        <?php if (!empty($given_vaccines)): ?>
                            <div class="vacc-wrap">
                            <?php foreach ($given_vaccines as $v): ?>
                                <span class="vacc-pill"><?= htmlspecialchars($v) ?></span>
                            <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <span class="vacc-none">—</span>
                        <?php endif; ?>
                    </td>
                    <td class="text-center">
                        <a href="<?= base_url('forms/view_child_health/' . $row['master_id']) ?>"
                           target="_blank" class="btn btn-primary" 
                           style="padding:2px 7px; font-size:.68rem;"
                           title="View full form">
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