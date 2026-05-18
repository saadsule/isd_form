<?php
// ══════════════════════════════════════════════════════════════════════════
//  views/reports/missed_vaccines.php  — FINAL VERSION
// ══════════════════════════════════════════════════════════════════════════
$children              = isset($report_data['children'])               ? $report_data['children']               : array();
$fully_vaccinated      = isset($report_data['fully_vaccinated'])       ? $report_data['fully_vaccinated']       : array();
$due_vaccines          = isset($report_data['due_vaccines'])           ? $report_data['due_vaccines']           : array();
$total_eligible        = isset($report_data['total_eligible'])         ? $report_data['total_eligible']         : 0;
$total_fully_vaccinated= isset($report_data['total_fully_vaccinated']) ? $report_data['total_fully_vaccinated'] : 0;
$total_children        = isset($report_data['total_children'])         ? $report_data['total_children']         : 0;
$children_partial      = isset($report_data['children_partial'])       ? $report_data['children_partial']       : 0;
$children_all_missing  = isset($report_data['children_all_missing'])   ? $report_data['children_all_missing']   : 0;
$total                 = count($children);

// Active card filter from URL
$card_filter = isset($_GET['card_filter']) ? $_GET['card_filter'] : 'all';

$display_children = array();

if ($card_filter === 'eligible') {
    $display_children = array_merge($fully_vaccinated, $children);
} elseif ($card_filter === 'fully') {
    $display_children = $fully_vaccinated;
} elseif ($card_filter === 'partial') {
    foreach ($children as $child) {
        if (!empty($child['vaccines_given'])) $display_children[] = $child;
    }
} elseif ($card_filter === 'all_missing') {
    foreach ($children as $child) {
        if (empty($child['vaccines_given'])) $display_children[] = $child;
    }
} else {
    $display_children = $children;
}
$display_total = count($display_children);

function age_mv($row) {
    $p = array();
    if (!empty($row['age_year'])  && $row['age_year']  > 0) $p[] = $row['age_year']  . 'y';
    if (!empty($row['age_month']) && $row['age_month'] > 0) $p[] = $row['age_month'] . 'm';
    if (!empty($row['age_day'])   && $row['age_day']   > 0) $p[] = $row['age_day']   . 'd';
    return $p ? implode(' ', $p) : '—';
}

$base_params = http_build_query(array(
    'uc_id'        => isset($_GET['uc_id'])        ? $_GET['uc_id']        : 0,
    'min_age_days' => isset($_GET['min_age_days']) ? $_GET['min_age_days'] : '',
));
$base_url = current_url() . '?' . $base_params;
?>

function age_mv($row) {
    $p = array();
    if (!empty($row['age_year'])  && $row['age_year']  > 0) $p[] = $row['age_year']  . 'y';
    if (!empty($row['age_month']) && $row['age_month'] > 0) $p[] = $row['age_month'] . 'm';
    if (!empty($row['age_day'])   && $row['age_day']   > 0) $p[] = $row['age_day']   . 'd';
    return $p ? implode(' ', $p) : '—';
}
?>

<style>
/* ── Filter Card ── */
.mv-filter-card {
    background: #fff; border: 1px solid #e3e8ef; border-radius: 12px;
    padding: 22px 26px; margin-bottom: 24px;
    box-shadow: 0 2px 10px rgba(0,0,0,.05);
}
.mv-filter-card label {
    font-weight: 600; font-size: 13px; margin-bottom: 6px; display: block; color: #34495e;
}
.mv-filter-card select {
    height: 44px; border-radius: 8px; border: 1.5px solid #ced4da;
    font-size: 14px; padding: 0 12px; width: 100%; color: #2c3e50;
    transition: border-color .2s;
}
.mv-filter-card select:focus {
    outline: none; border-color: #2980b9;
    box-shadow: 0 0 0 3px rgba(41,128,185,.12);
}
.mv-btn-search {
    background: linear-gradient(135deg, #2c3e50 0%, #3d5a80 100%);
    color: #fff; border: none; border-radius: 8px;
    padding: 0 28px; font-size: 14px; font-weight: 600;
    height: 44px; letter-spacing: .3px; cursor: pointer;
    transition: opacity .15s; width: 100%;
}
.mv-btn-search:hover { opacity: .88; }

/* ── 3 Summary Cards ── */
.mv-stat-card {
    border-radius: 12px; padding: 20px 24px; background: #fff;
    border: 1px solid #e3e8ef; box-shadow: 0 2px 10px rgba(0,0,0,.05);
    transition: transform .2s, box-shadow .2s; height: 100%;
    cursor: pointer;
}
.mv-stat-card:hover { transform: translateY(-3px); box-shadow: 0 6px 20px rgba(0,0,0,.09); }
.card-active-blue   { border: 2px solid #2980b9 !important; background: #f0f8ff !important; }
.card-active-green  { border: 2px solid #27ae60 !important; background: #f0faf3 !important; }
.card-active-orange { border: 2px solid #e67e22 !important; background: #fff8f0 !important; }
.card-active-red    { border: 2px solid #e74c3c !important; background: #fff5f5 !important; }
.card-active-purple { border: 2px solid #8e44ad !important; background: #f9f0ff !important; }
.mv-stat-icon {
    width: 46px; height: 46px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 20px; margin-bottom: 14px;
}
.mv-stat-number { font-size: 34px; font-weight: 700; line-height: 1; margin-bottom: 4px; }
.mv-stat-label  { font-size: 11px; text-transform: uppercase; letter-spacing: .6px; color: #8a9ab0; font-weight: 600; }
.mv-stat-sub    { font-size: 12px; color: #aab; margin-top: 5px; }

/* ── Due Vaccines Bar ── */
.mv-due-bar {
    background: linear-gradient(135deg, #2c3e50 0%, #3d5a80 100%);
    border-radius: 10px; padding: 14px 22px; margin-bottom: 20px;
    color: #fff; display: flex; align-items: center; flex-wrap: wrap; gap: 10px;
}
.mv-due-bar .due-label { font-size: 12px; opacity: .8; font-weight: 600; margin-right: 4px; white-space: nowrap; }
.mv-due-badge {
    display: inline-flex; align-items: center; gap: 5px;
    background: rgba(255,255,255,.18); border-radius: 20px;
    padding: 4px 14px; font-size: 12px; font-weight: 600; letter-spacing: .2px;
}

/* ── Table ── */
.mv-table { table-layout: fixed; width: 100%; }
.mv-table thead tr { background: linear-gradient(135deg, #2c3e50 0%, #3d5a80 100%); }
.mv-table thead th {
    color: #fff !important; font-weight: 600;
    text-transform: uppercase; letter-spacing: .4px;
    border: none !important; white-space: nowrap; vertical-align: middle;
    font-size: .60rem; padding: 9px 6px;
}
.mv-table tbody td {
    vertical-align: middle; border-color: #edf0f4 !important;
    font-size: .72rem; padding: 7px 6px; word-break: break-word;
}
.mv-table tbody tr:hover { background: #f5f8fc; }

/* ── Vaccine Pills ── */
.vacc-wrap { display: flex; flex-wrap: wrap; gap: 3px; }
.vacc-given {
    display: inline-flex; align-items: center; gap: 3px;
    padding: 2px 7px; border-radius: 10px; font-size: .60rem; font-weight: 600;
    background: #eaf6f0; color: #1a7a4a; white-space: nowrap;
    border: 1px solid #c3e6cb;
}
.vacc-missing {
    display: inline-flex; align-items: center; gap: 3px;
    padding: 2px 7px; border-radius: 10px; font-size: .60rem; font-weight: 600;
    background: #fdecea; color: #c0392b; white-space: nowrap;
    border: 1px solid #f5c6cb;
}

/* ── View Button ── */
.btn-view {
    padding: 3px 8px; font-size: .65rem; border-radius: 6px;
}

/* ── Empty States ── */
.mv-empty, .mv-all-given {
    text-align: center; padding: 60px 20px;
}
.mv-empty i, .mv-all-given i { font-size: 48px; margin-bottom: 16px; display: block; }
</style>

<div class="page-container">
<div class="main-content">

<div class="page-header">
    <h2><i class="fa fa-exclamation-triangle text-warning"></i> Missed Vaccine Report</h2>
    <p class="text-muted mb-0">Children who are old enough but have not received due vaccines</p>
</div>

<!-- ── FILTER ── -->
<div class="mv-filter-card">
    <form method="GET" action="<?= current_url() ?>">
        <div class="row align-items-end">
            <div class="col-md-4 mb-2">
                <label><i class="fa fa-map-marker"></i> UC (Union Council)</label>
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
                <label><i class="fa fa-child"></i> Age Group / Visit</label>
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
                <label>&nbsp;</label>
                <button type="submit" class="mv-btn-search">
                    <i class="fa fa-search"></i> Search
                </button>
            </div>
        </div>
    </form>
</div>

<?php if ($searched): ?>

<?php if ($total > 0): ?>

<!-- ── 4 SUMMARY CARDS (All Clickable) ── -->
<div class="row mb-4">

    <!-- Card 1: Total Eligible — shows ALL children in table -->
    <div class="col-md-3 mb-3">
        <a href="<?= $base_url ?>&card_filter=eligible" style="text-decoration:none;">
        <div class="mv-stat-card <?= $card_filter === 'eligible' ? 'card-active-purple' : '' ?>">
            <div class="mv-stat-icon" style="background:#f0e6ff;">
                <i class="fa fa-users" style="color:#8e44ad;"></i>
            </div>
            <div class="mv-stat-number" style="color:#8e44ad;"><?= number_format($total_eligible) ?></div>
            <div class="mv-stat-label">Total Eligible Children</div>
            <div class="mv-stat-sub">Old enough for this visit's vaccines &nbsp;<i class="fa fa-arrow-right"></i></div>
        </div>
        </a>
    </div>

    <!-- Card 2: Fully Vaccinated -->
    <div class="col-md-3 mb-3">
        <a href="<?= $base_url ?>&card_filter=fully" style="text-decoration:none;">
        <div class="mv-stat-card <?= $card_filter === 'fully' ? 'card-active-green' : '' ?>">
            <div class="mv-stat-icon" style="background:#eaf6f0;">
                <i class="fa fa-check-circle" style="color:#27ae60;"></i>
            </div>
            <div class="mv-stat-number" style="color:#27ae60;"><?= number_format($total_fully_vaccinated) ?></div>
            <div class="mv-stat-label">Fully Vaccinated</div>
            <div class="mv-stat-sub">All due vaccines received ✓ &nbsp;<i class="fa fa-arrow-right"></i></div>
        </div>
        </a>
    </div>

    <!-- Card 3: Partially Vaccinated -->
    <div class="col-md-3 mb-3">
        <a href="<?= $base_url ?>&card_filter=partial" style="text-decoration:none;">
        <div class="mv-stat-card <?= $card_filter === 'partial' ? 'card-active-orange' : '' ?>">
            <div class="mv-stat-icon" style="background:#fff3e0;">
                <i class="fa fa-exclamation-circle" style="color:#e67e22;"></i>
            </div>
            <div class="mv-stat-number" style="color:#e67e22;"><?= number_format($children_partial) ?></div>
            <div class="mv-stat-label">Partially Vaccinated</div>
            <div class="mv-stat-sub">Some given, some missing &nbsp;<i class="fa fa-arrow-right"></i></div>
        </div>
        </a>
    </div>

    <!-- Card 4: Not Vaccinated at All -->
    <div class="col-md-3 mb-3">
        <a href="<?= $base_url ?>&card_filter=all_missing" style="text-decoration:none;">
        <div class="mv-stat-card <?= $card_filter === 'all_missing' ? 'card-active-red' : '' ?>">
            <div class="mv-stat-icon" style="background:#fdecea;">
                <i class="fa fa-times-circle" style="color:#e74c3c;"></i>
            </div>
            <div class="mv-stat-number" style="color:#e74c3c;"><?= number_format($children_all_missing) ?></div>
            <div class="mv-stat-label">Not Vaccinated at All</div>
            <div class="mv-stat-sub">Zero due vaccines received &nbsp;<i class="fa fa-arrow-right"></i></div>
        </div>
        </a>
    </div>

</div>

<!-- Show All Missing button when filtered -->
<?php if (!in_array($card_filter, array('all', 'eligible'))): ?>
<div class="mb-3">
    <a href="<?= $base_url ?>&card_filter=all" class="btn btn-sm btn-outline-secondary">
        <i class="fa fa-list"></i> Show All <?= number_format($total_children) ?> Children with Missing Vaccines
    </a>
</div>
<?php endif; ?>

<!-- ── DUE VACCINES BAR ── -->
<div class="mv-due-bar">
    <span class="due-label"><i class="fa fa-syringe"></i> Due vaccines for this age group:</span>
    <?php foreach ($due_vaccines as $dv): ?>
        <span class="mv-due-badge">
            <i class="fa fa-circle" style="font-size:6px;"></i>
            <?= htmlspecialchars($dv['vaccine_name']) ?>
        </span>
    <?php endforeach; ?>
</div>

<?php if ($display_total > 0): ?>
<!-- ── TABLE ── -->
<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between">
        <h5 class="card-title mb-0">
            <i class="fa fa-list text-muted"></i> Children List
            <span class="badge badge-default ml-2"><?= $display_total ?> records</span>
            <?php if ($card_filter === 'fully'): ?>
                <span class="badge ml-1" style="background:#eaf6f0;color:#27ae60;border:1px solid #c3e6cb;">Fully Vaccinated ✓</span>
            <?php elseif ($card_filter === 'partial'): ?>
                <span class="badge ml-1" style="background:#fff3e0;color:#e67e22;border:1px solid #ffd09e;">Partially Vaccinated</span>
            <?php elseif ($card_filter === 'all_missing'): ?>
                <span class="badge ml-1" style="background:#fdecea;color:#c0392b;border:1px solid #f5c6cb;">Not Vaccinated at All</span>
            <?php elseif ($card_filter === 'eligible'): ?>
                <span class="badge ml-1" style="background:#f0e6ff;color:#8e44ad;border:1px solid #d9b3ff;">All Eligible Children</span>
            <?php else: ?>
                <span class="badge ml-1" style="background:#e8f4fd;color:#2980b9;border:1px solid #b8d4f0;">All with Missing Vaccines</span>
            <?php endif; ?>
        </h5>
        <div>
            <span style="background:#eaf6f0; color:#1a7a4a; padding:3px 10px; border-radius:10px; font-size:.72rem; font-weight:600; border:1px solid #c3e6cb;">
                <i class="fa fa-check"></i> Given
            </span>
            &nbsp;
            <span style="background:#fdecea; color:#c0392b; padding:3px 10px; border-radius:10px; font-size:.72rem; font-weight:600; border:1px solid #f5c6cb;">
                <i class="fa fa-times"></i> Missing
            </span>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-bordered table-sm mb-0 mv-table">
                <thead>
                    <tr>
                        <th style="width:30px">#</th>
                        <th style="width:105px">QR Code</th>
                        <th style="width:100px">Child Name</th>
                        <th style="width:90px">Guardian</th>
                        <th style="width:65px">Age</th>
                        <th style="width:55px">Gender</th>
                        <th style="width:80px">Village</th>
                        <th style="width:80px">UC</th>
                        <th style="width:90px">Vaccinator</th>
                        <th style="width:80px">Reg Date</th>
                        <th>Vaccine Status</th>
                        <th style="width:45px" class="text-center">View</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($display_children as $i => $child): ?>
                <tr>
                    <td class="text-muted"><?= $i + 1 ?></td>
                    <td>
                        <a href="<?= base_url('reports/qr_history_search?qr_code=') . urlencode($child['qr_code']) ?>"
                           target="_blank" style="font-size:.68rem; font-weight:600; color:#2980b9;">
                            <?= htmlspecialchars($child['qr_code']) ?>
                        </a>
                    </td>
                    <td><strong><?= htmlspecialchars($child['patient_name'] ?: '—') ?></strong></td>
                    <td><?= htmlspecialchars($child['guardian_name'] ?: '—') ?></td>
                    <td><?= age_mv($child) ?></td>
                    <td><?= ucfirst($child['gender'] ?: '—') ?></td>
                    <td><?= htmlspecialchars($child['village'] ?: '—') ?></td>
                    <td><?= htmlspecialchars($child['uc_name'] ?: '—') ?></td>
                    <td><?= htmlspecialchars($child['vaccinator_name'] ?: '—') ?></td>
                    <td><strong><?= !empty($child['form_date']) ? date('d M Y', strtotime($child['form_date'])) : '—' ?></strong></td>
                    <td>
                        <div class="vacc-wrap">
                        <?php if (!empty($child['vaccines_given'])): ?>
                            <?php foreach ($child['vaccines_given'] as $vg): ?>
                                <span class="vacc-given"><i class="fa fa-check"></i> <?= htmlspecialchars($vg) ?></span>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        <?php if (!empty($child['vaccines_missing'])): ?>
                            <?php foreach ($child['vaccines_missing'] as $vm): ?>
                                <span class="vacc-missing"><i class="fa fa-times"></i> <?= htmlspecialchars($vm) ?></span>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        <?php if (empty($child['vaccines_given']) && empty($child['vaccines_missing'])): ?>
                            <span style="color:#27ae60; font-size:.70rem; font-weight:600;">
                                <i class="fa fa-check-circle"></i> All vaccines given
                            </span>
                        <?php endif; ?>
                        </div>
                    </td>
                    <td class="text-center">
                        <a href="<?= base_url('forms/view_child_health/' . $child['master_id']) ?>"
                           target="_blank" class="btn btn-primary btn-view" title="View full form">
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
<!-- No records for this filter -->
<div class="card">
    <div class="card-body mv-all-given">
        <i class="fa fa-check-circle text-success"></i>
        <h5 class="text-success">No records found for this filter!</h5>
        <p class="text-muted" style="font-size:13px;">Try selecting a different filter above.</p>
        <a href="<?= $base_url ?>&card_filter=all" class="btn btn-primary">
            <i class="fa fa-list"></i> Show All Missing
        </a>
    </div>
</div>
<?php endif; // end display_total / fully ?>

<?php endif; // end total > 0 ?>

<?php else: ?>
<!-- Not searched yet -->
<div class="card">
    <div class="card-body mv-empty">
        <i class="fa fa-filter text-muted"></i>
        <h5 class="text-muted">Select UC and Age Group above to generate report</h5>
    </div>
</div>
<?php endif; // end searched ?>

</div>