<?php
$months           = isset($months)           ? $months           : array();
$uc_list          = isset($uc_list)          ? $uc_list          : array();
$matrix           = isset($matrix)           ? $matrix           : array();
$total_visits     = isset($total_visits)     ? $total_visits     : 0;
$total_children   = isset($total_children)   ? $total_children   : 0;
$no_followup      = isset($no_followup)      ? $no_followup      : 0;
$children_with_fu = isset($children_with_fu) ? $children_with_fu : 0;
$fu_1             = isset($fu_1)             ? $fu_1             : 0;
$fu_2             = isset($fu_2)             ? $fu_2             : 0;
$fu_3plus         = isset($fu_3plus)         ? $fu_3plus         : 0;

function fmt_month_fu($ym) {
    return DateTime::createFromFormat('Y-m', $ym)->format('M Y');
}
?>

<style>
.fu-stat-card {
    border-radius: 10px; padding: 18px 22px; background: #fff;
    border: 1px solid #e3e8ef; box-shadow: 0 2px 8px rgba(0,0,0,.05);
    transition: transform .2s, box-shadow .2s; height: 100%;
}
.fu-stat-card:hover { transform: translateY(-3px); box-shadow: 0 6px 18px rgba(0,0,0,.10); }
.fu-stat-card .fu-icon {
    width: 44px; height: 44px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 18px; margin-bottom: 12px;
}
.fu-stat-card .fu-number { font-size: 32px; font-weight: 700; line-height: 1; margin-bottom: 4px; }
.fu-stat-card .fu-label  { font-size: 11px; text-transform: uppercase; letter-spacing: .5px; color: #8a9ab0; font-weight: 600; }
.fu-stat-card .fu-sub    { font-size: 11px; color: #8a9ab0; margin-top: 6px; }

.fu-table thead tr { background: linear-gradient(135deg, #2c3e50 0%, #3d5a80 100%); }
.fu-table thead th {
    color: #fff !important; font-size: .70rem; font-weight: 600;
    text-transform: uppercase; letter-spacing: .6px; padding: 11px 8px;
    border: none !important; white-space: nowrap; vertical-align: middle;
}
.fu-table thead th.th-month { text-align: center; min-width: 80px; }
.fu-table tbody td { font-size: .82rem; vertical-align: middle; padding: 8px; border-color: #edf0f4 !important; }
.fu-table tbody tr:hover { background: #f5f8fc; }
.fu-table tfoot td { font-weight: 700; font-size: .82rem; background: #f0f4f8; border-color: #dde3ea !important; }

.month-cell { text-align: center; line-height: 1.8; }

/* Clickable value links */
a.val-reg, a.val-fu {
    display: block; font-weight: 700; border-radius: 4px;
    padding: 1px 6px; transition: background .15s; text-decoration: none;
}
a.val-reg { font-size: .88rem; color: #2980b9; }
a.val-reg:hover { background: #d6eaf8; color: #1a5276; text-decoration: none; }
a.val-fu  { font-size: .78rem; color: #27ae60; }
a.val-fu:hover  { background: #d5f5e3; color: #1e8449; text-decoration: none; }

.empty-val { color: #ccc; font-size: .8rem; }
.col-total-reg { color: #2980b9; font-weight: 700; text-align: center; }
.col-total-fu  { color: #27ae60; font-weight: 700; text-align: center; }

.fu-legend {
    display: flex; gap: 20px; align-items: center;
    font-size: .78rem; color: #6c7a8d;
    padding: 8px 16px; background: #f7f9fb;
    border-top: 1px solid #edf0f4; border-radius: 0 0 8px 8px;
}
.fu-legend-dot { width: 10px; height: 10px; border-radius: 2px; display: inline-block; margin-right: 5px; }
</style>

<div class="page-container">
<div class="main-content">

<div class="page-header">
    <div class="d-flex align-items-center justify-content-between flex-wrap">
        <div>
            <h2><i class="fa fa-calendar-check-o text-primary"></i> Follow Up Status Report</h2>
            <p class="text-muted mb-0">
                UC-wise registration &amp; follow-up tracking — December 2025 to <?= fmt_month_fu(end($months)) ?>
            </p>
        </div>
    </div>
</div>

<!-- SUMMARY CARDS -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="fu-stat-card">
            <div class="fu-icon" style="background:#e8f4fd;"><i class="fa fa-list-alt" style="color:#2980b9;"></i></div>
            <div class="fu-number" style="color:#2980b9;"><?= number_format($total_visits) ?></div>
            <div class="fu-label">Total Visits Recorded</div>
            <div class="fu-sub">All child health form submissions</div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="fu-stat-card">
            <div class="fu-icon" style="background:#eaf4ee;"><i class="fa fa-child" style="color:#27ae60;"></i></div>
            <div class="fu-number" style="color:#27ae60;"><?= number_format($total_children) ?></div>
            <div class="fu-label">Total Children Registered</div>
            <div class="fu-sub">Unique QR codes in the system</div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="fu-stat-card">
            <div class="fu-icon" style="background:#fdecea;"><i class="fa fa-times-circle" style="color:#e74c3c;"></i></div>
            <div class="fu-number" style="color:#e74c3c;"><?= number_format($no_followup) ?></div>
            <div class="fu-label">No Follow Up Recorded</div>
            <div class="fu-sub">Children with only 1 visit</div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="fu-stat-card">
            <div class="fu-icon" style="background:#fde8c8;"><i class="fa fa-check-circle" style="color:#e67e22; font-size:22px;"></i></div>
            <div class="fu-number" style="color:#f39c12;"><?= number_format($children_with_fu) ?></div>
            <div class="fu-label">Children With Follow Ups</div>
            <div class="fu-sub">
                <span style="color:#2980b9;"><?= number_format($fu_1) ?> with 1 FU</span>
                &nbsp;•&nbsp;
                <span style="color:#27ae60;"><?= number_format($fu_2) ?> with 2 FUs</span>
                <?php if ($fu_3plus): ?>
                &nbsp;•&nbsp;
                <span style="color:#8e44ad;"><?= number_format($fu_3plus) ?> with 3+ FUs</span>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- MATRIX TABLE -->
<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between">
        <h5 class="card-title mb-0">
            <i class="fa fa-table text-muted"></i> UC-wise Monthly Breakdown
        </h5>
        <small class="text-muted"><i class="fa fa-mouse-pointer"></i> Click on any number to view child details</small>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-bordered mb-0 fu-table">
                <thead>
                    <tr>
                        <th width="35">#</th>
                        <th style="min-width:130px;">UC Name</th>
                        <?php foreach ($months as $m): ?>
                        <th class="th-month"><?= fmt_month_fu($m) ?></th>
                        <?php endforeach; ?>
                        <th class="th-month" style="background:rgba(0,0,0,.15);">Total Reg</th>
                        <th class="th-month" style="background:rgba(0,0,0,.15);">Total FU</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                $serial        = 0;
                $col_reg_tots  = array_fill_keys($months, 0);
                $col_fu_tots   = array_fill_keys($months, 0);
                $grand_reg     = 0;
                $grand_fu      = 0;
                $base          = base_url('reports/drill_down');

                foreach ($uc_list as $uc):
                    $serial++;
                    $uid     = $uc['pk_id'];
                    $uc_enc  = urlencode($uc['uc']);
                    $row_reg = isset($matrix[$uid]['total_reg']) ? $matrix[$uid]['total_reg'] : 0;
                    $row_fu  = isset($matrix[$uid]['total_fu'])  ? $matrix[$uid]['total_fu']  : 0;
                    $grand_reg += $row_reg;
                    $grand_fu  += $row_fu;

                    foreach ($months as $m) {
                        $col_reg_tots[$m] += isset($matrix[$uid]['months'][$m]) ? $matrix[$uid]['months'][$m]['reg'] : 0;
                        $col_fu_tots[$m]  += isset($matrix[$uid]['months'][$m]) ? $matrix[$uid]['months'][$m]['fu']  : 0;
                    }
                ?>
                <tr>
                    <td class="text-muted"><?= $serial ?></td>
                    <td><strong><?= htmlspecialchars($uc['uc']) ?></strong></td>
                    <?php foreach ($months as $m):
                        $reg = isset($matrix[$uid]['months'][$m]) ? $matrix[$uid]['months'][$m]['reg'] : 0;
                        $fu  = isset($matrix[$uid]['months'][$m]) ? $matrix[$uid]['months'][$m]['fu']  : 0;
                    ?>
                    <td class="month-cell">
                        <?php if ($reg || $fu): ?>
                            <?php if ($reg): ?>
                                <a class="val-reg"
                                   href="<?= $base ?>?type=reg&uc_id=<?= $uid ?>&month=<?= $m ?>&uc_name=<?= $uc_enc ?>"
                                   title="View <?= $reg ?> registrations">
                                    <?= $reg ?>
                                </a>
                            <?php endif; ?>
                            <?php if ($fu): ?>
                                <a class="val-fu"
                                   href="<?= $base ?>?type=fu&uc_id=<?= $uid ?>&month=<?= $m ?>&uc_name=<?= $uc_enc ?>"
                                   title="View <?= $fu ?> children with FU">
                                    +<?= $fu ?>
                                </a>
                            <?php endif; ?>
                        <?php else: ?>
                            <span class="empty-val">—</span>
                        <?php endif; ?>
                    </td>
                    <?php endforeach; ?>
                    <td class="col-total-reg"><?= $row_reg ?: '—' ?></td>
                    <td class="col-total-fu"><?= $row_fu ?: '—' ?></td>
                </tr>
                <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td></td>
                        <td><strong>Total</strong></td>
                        <?php foreach ($months as $m): ?>
                        <td class="month-cell">
                            <?php if ($col_reg_tots[$m] || $col_fu_tots[$m]): ?>
                                <?php if ($col_reg_tots[$m]): ?>
                                    <span class="val-reg" style="cursor:default;"><?= $col_reg_tots[$m] ?></span>
                                <?php endif; ?>
                                <?php if ($col_fu_tots[$m]): ?>
                                    <span class="val-fu" style="cursor:default; display:block;">+<?= $col_fu_tots[$m] ?></span>
                                <?php endif; ?>
                            <?php else: ?>
                                <span class="empty-val">—</span>
                            <?php endif; ?>
                        </td>
                        <?php endforeach; ?>
                        <td class="col-total-reg"><?= $grand_reg ?></td>
                        <td class="col-total-fu"><?= $grand_fu ?></td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <div class="fu-legend">
            <div><span class="fu-legend-dot" style="background:#2980b9;"></span>Blue = New Registrations (click to view list)</div>
            <div><span class="fu-legend-dot" style="background:#27ae60;"></span>Green (+) = Unique children with follow-up that month (click to view list)</div>
        </div>
    </div>
</div>

</div>