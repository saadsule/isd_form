<?php
$children     = isset($report_data['children'])  ? $report_data['children']  : array();
$due_vaccines = isset($report_data['due_vaccines']) ? $report_data['due_vaccines'] : array();
$total        = count($children);

function age_mv($row) {
    $p = array();
    if (!empty($row['age_year'])  && $row['age_year']  > 0) $p[] = $row['age_year']  . 'y';
    if (!empty($row['age_month']) && $row['age_month'] > 0) $p[] = $row['age_month'] . 'm';
    if (!empty($row['age_day'])   && $row['age_day']   > 0) $p[] = $row['age_day']   . 'd';
    return $p ? implode(' ', $p) : '—';
}
?>

<style>
.mv-filter-card {
    background: #fff; border: 1px solid #e3e8ef; border-radius: 10px;
    padding: 20px 24px; margin-bottom: 22px; box-shadow: 0 2px 8px rgba(0,0,0,.04);
}
.mv-filter-card select {
    height: 42px; border-radius: 7px; border: 1px solid #ced4da;
    font-size: 14px; padding: 0 12px; width: 100%;
}
.mv-filter-card select:focus { outline: none; border-color: #2980b9; box-shadow: 0 0 0 3px rgba(41,128,185,.15); }
.mv-btn-search {
    background: linear-gradient(135deg, #2c3e50 0%, #3d5a80 100%);
    color: #fff; border: none; border-radius: 7px;
    padding: 0 28px; font-size: 14px; font-weight: 600;
    height: 42px; letter-spacing: .3px; cursor: pointer;
    transition: opacity .15s;
}
.mv-btn-search:hover { opacity: .9; }

.mv-summary-bar {
    background: linear-gradient(135deg, #2c3e50 0%, #3d5a80 100%);
    border-radius: 10px; padding: 16px 24px; margin-bottom: 18px;
    color: #fff; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px;
}
.mv-summary-bar .mv-count { font-size: 28px; font-weight: 700; }
.mv-summary-bar .mv-count-lbl { font-size: 11px; opacity: .75; text-transform: uppercase; letter-spacing: .5px; }
.mv-due-badge {
    display: inline-block; background: rgba(255,255,255,.18);
    border-radius: 20px; padding: 4px 14px; font-size: 12px;
    font-weight: 600; margin: 2px 3px; letter-spacing: .3px;
}

.mv-table thead tr { background: linear-gradient(135deg, #2c3e50 0%, #3d5a80 100%); }
.mv-table thead th {
    color: #fff !important; font-weight: 600;
    text-transform: uppercase; letter-spacing: .5px;
    border: none !important;
    white-space: nowrap; vertical-align: middle;
    font-size: .60rem;
    padding: 8px 5px;
}
.mv-table tbody td { vertical-align: middle; border-color: #edf0f4 !important;font-size: .72rem;padding: 6px 5px; }
.mv-table tbody tr:hover { background: #f5f8fc; }

.vacc-given {
    display: inline-block; margin: 1px 2px; padding: 2px 7px;
    border-radius: 10px; font-size: .65rem; font-weight: 600;
    background: #eaf6f0; color: #1a7a4a; white-space: nowrap;
}
.vacc-missing {
    display: inline-block; margin: 1px 2px; padding: 2px 7px;
    border-radius: 10px; font-size: .65rem; font-weight: 600;
    background: #fdecea; color: #c0392b; white-space: nowrap;
}
.vacc-given, .vacc-missing {
    font-size: .58rem;
    padding: 1px 4px;
    margin: 1px 1px;
}
.mv-empty { text-align: center; padding: 60px 20px; color: #8a9ab0; }
.mv-empty i { font-size: 48px; margin-bottom: 16px; display: block; }
.mv-all-given { text-align: center; padding: 60px 20px; color: #27ae60; }
.mv-all-given i { font-size: 48px; margin-bottom: 16px; display: block; }
</style>

<div class="page-container">
<div class="main-content">

<div class="page-header">
    <h2><i class="fa fa-exclamation-triangle text-warning"></i> Missed Vaccine Report</h2>
    <p class="text-muted mb-0">Children who are old enough but have not received due vaccines</p>
</div>

<!-- FILTER -->
<div class="mv-filter-card">
    <form method="GET" action="<?= current_url() ?>">
        <div class="row align-items-end">
            <div class="col-md-4 mb-2">
                <label style="font-weight:600; font-size:13px; margin-bottom:6px; display:block;">
                    <i class="fa fa-map-marker"></i> UC (Union Council)
                </label>
                <select name="uc_id">
                    <option value="0">— All UCs —</option>
                    <?php foreach ($uc_list as $uc): ?>
                    <option value="<?= $uc['pk_id'] ?>"
                        <?= ($uc_id == $uc['pk_id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($uc['uc']) ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4 mb-2">
                <label style="font-weight:600; font-size:13px; margin-bottom:6px; display:block;">
                    <i class="fa fa-child"></i> Age Group / Visit
                </label>
                <select name="min_age_days" required>
                    <option value="">— Select Age Group —</option>
                    <?php foreach ($age_groups as $ag): ?>
                    <option value="<?= $ag['min_age_days'] ?>"
                        <?= ($min_age_days == $ag['min_age_days']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($ag['visit_label']) ?> — <?= htmlspecialchars($ag['age_label']) ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2 mb-2">
                <button type="submit" class="mv-btn-search w-100">
                    <i class="fa fa-search"></i> Search
                </button>
            </div>
        </div>
    </form>
</div>

<?php if ($searched): ?>

<?php if ($total > 0): ?>

<!-- SUMMARY BAR -->
<div class="mv-summary-bar">
    <div>
        <div class="mv-count"><?= $total ?></div>
        <div class="mv-count-lbl">Children with missing vaccines</div>
    </div>
    <div>
        <div style="font-size:12px; opacity:.8; margin-bottom:6px;">Due vaccines for this age group:</div>
        <?php foreach ($due_vaccines as $dv): ?>
            <span class="mv-due-badge"><i class="fa fa-syringe"></i> <?= htmlspecialchars($dv['vaccine_name']) ?></span>
        <?php endforeach; ?>
    </div>
</div>

<!-- TABLE -->
<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between">
        <h5 class="card-title mb-0">
            <i class="fa fa-list text-muted"></i> Children List
            <span class="badge badge-default ml-2"><?= $total ?> records</span>
        </h5>
        <small class="text-muted">
            <span style="background:#eaf6f0; color:#1a7a4a; padding:2px 8px; border-radius:10px; font-size:.72rem; font-weight:600;">Green = Given</span>
            &nbsp;
            <span style="background:#fdecea; color:#c0392b; padding:2px 8px; border-radius:10px; font-size:.72rem; font-weight:600;">Red = Missing</span>
        </small>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive" style="font-size: .72rem;">
            <table class="table table-hover table-bordered table-sm mb-0 mv-table">
                <thead>
                    <tr>
                        <th width="35">#</th>
                        <th width="110">QR Code</th>
                        <th>Child Name</th>
                        <th>Guardian</th>
                        <th width="80">Age</th>
                        <th width="65">Gender</th>
                        <th width="90">Village</th>
                        <th width="90">UC</th>
                        <th>Vaccinator</th>
                        <th width="100">Reg Date</th>
                        <th>Vaccine Status</th>
                        <th width="55" class="text-center">View</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($children as $i => $child): ?>
                <tr>
                    <td class="text-muted"><?= $i + 1 ?></td>
                    <td>
                        <a href="<?= base_url('reports/qr_history_search?qr_code=') . urlencode($child['qr_code']) ?>"
                           target="_blank"
                           style="font-size:.75rem; font-weight:600; color:#2980b9;">
                            <?= htmlspecialchars($child['qr_code']) ?>
                        </a>
                    </td>
                    <td><strong style="font-size:.83rem;"><?= htmlspecialchars($child['patient_name'] ?: '—') ?></strong></td>
                    <td style="font-size:.80rem;"><?= htmlspecialchars($child['guardian_name'] ?: '—') ?></td>
                    <td style="font-size:.78rem;"><?= age_mv($child) ?></td>
                    <td style="font-size:.80rem;"><?= ucfirst($child['gender'] ?: '—') ?></td>
                    <td style="font-size:.80rem;"><?= htmlspecialchars($child['village'] ?: '—') ?></td>
                    <td style="font-size:.80rem;"><?= htmlspecialchars($child['uc_name'] ?: '—') ?></td>
                    <td style="font-size:.80rem;"><?= htmlspecialchars($child['vaccinator_name'] ?: '—') ?></td>
                    <td><strong style="font-size:.80rem;"><?= !empty($child['form_date']) ? date('d M Y', strtotime($child['form_date'])) : '—' ?></strong></td>
                    <td>
                        <?php foreach ($child['vaccines_given'] as $vg): ?>
                            <span class="vacc-given"><i class="fa fa-check"></i> <?= htmlspecialchars($vg) ?></span>
                        <?php endforeach; ?>
                        <?php foreach ($child['vaccines_missing'] as $vm): ?>
                            <span class="vacc-missing"><i class="fa fa-times"></i> <?= htmlspecialchars($vm) ?></span>
                        <?php endforeach; ?>
                    </td>
                    <td class="text-center">
                        <a href="<?= base_url('forms/view_child_health/' . $child['master_id']) ?>"
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
    <div class="card-body mv-all-given">
        <i class="fa fa-check-circle text-success"></i>
        <h5 class="text-success">All eligible children have received due vaccines!</h5>
        <p class="text-muted" style="font-size:13px;">No missing vaccines found for the selected filters.</p>
    </div>
</div>
<?php endif; ?>

<?php else: ?>
<div class="card">
    <div class="card-body mv-empty">
        <i class="fa fa-filter text-muted"></i>
        <h5 class="text-muted">Select UC and Age Group above to generate report</h5>
    </div>
</div>
<?php endif; ?>

</div>