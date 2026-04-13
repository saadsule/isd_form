<?php
$records = isset($records) ? $records : array();
$summary = isset($summary) ? $summary : array();
$filter  = isset($filter)  ? $filter  : null;
$total   = isset($total)   ? $total   : 0;
$view_base = rtrim(base_url(), '/') . '/forms/view_child_health/';

$filter_cfg = array(
    'male' => array(
        'color'   => '#1a5276',
        'border'  => '#2980b9',
        'pill_bg' => '#d6eaf8',
        'icon'    => 'fa-mars',
        'label'   => 'Male + Pregnant',
        'desc'    => 'Gender marked Male but Pregnant',
        'key'     => 'male',
    ),
    'underage' => array(
        'color'   => '#c0392b',
        'border'  => '#e74c3c',
        'pill_bg' => '#fde8e8',
        'icon'    => 'fa-child',
        'label'   => 'Under 18 + Pregnant',
        'desc'    => 'Age below 18 but Pregnant',
        'key'     => 'underage',
    ),
    'Un-Married' => array(
        'color'   => '#b7770d',
        'border'  => '#f39c12',
        'pill_bg' => '#fef3cd',
        'icon'    => 'fa-times-circle',
        'label'   => 'Un-Married + Pregnant',
        'desc'    => 'Marital status Un-Married but Pregnant',
        'key'     => 'Un-Married',
    ),
);

function getIssueReasons($row) {
    $issues = array();
    if ($row['gender'] !== 'Female') {
        $issues[] = array('color' => '#1a5276', 'bg' => '#d6eaf8', 'icon' => 'fa-mars',       'text' => 'Male Marked');
    }
    if ((int)$row['age_year'] < 18) {
        $issues[] = array('color' => '#c0392b', 'bg' => '#fde8e8', 'icon' => 'fa-child',       'text' => 'Under 18');
    }
    if ($row['marital_status'] === 'Un-Married') {
        $issues[] = array('color' => '#b7770d', 'bg' => '#fef3cd', 'icon' => 'fa-times-circle','text' => 'Un-Married');
    }
    return $issues;
}

function ageDisplayPA($row) {
    $y = isset($row['age_year'])  ? (int)$row['age_year']  : 0;
    $m = isset($row['age_month']) ? (int)$row['age_month'] : 0;
    $parts = array();
    if ($y) { $parts[] = $y . 'y'; }
    if ($m) { $parts[] = $m . 'm'; }
    if (!empty($parts)) { return implode(' ', $parts); }
    return '—';
}
?>

<style>
.pa-table thead tr {
    background: linear-gradient(135deg, #1b2a4a 0%, #c0392b 100%);
}
.pa-table thead th {
    color: #fff !important;
    font-size: .72rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: .6px;
    padding: 12px 10px;
    border: none !important;
    white-space: nowrap;
}
.pa-table tbody td {
    font-size: .82rem;
    vertical-align: middle;
    padding: 10px;
}
.pa-table tbody tr:hover { background: #fff8f8; }
.pa-table .row-stripe { border-left: 4px solid #c0392b; }
.qr-pill-pa {
    font-family: monospace;
    font-size: .72rem;
    background: #f8f0f0;
    border: 1px solid #f0d0d0;
    padding: 3px 7px;
    border-radius: 5px;
    display: inline-block;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 115px;
}
.pa-stat-card {
    display: block;
    text-decoration: none;
    border-radius: 10px;
    transition: transform .2s, box-shadow .2s;
    color: inherit;
    border-left: 5px solid;
}
.pa-stat-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 18px rgba(0,0,0,.12) !important;
    text-decoration: none;
    color: inherit;
}
.pa-stat-card.is-active {
    box-shadow: 0 0 0 3px rgba(0,0,0,.2), 0 4px 14px rgba(0,0,0,.1) !important;
}
.issue-tags-cell { line-height: 2; }
.issue-tag-pa {
    display: inline-block;
    padding: 2px 9px;
    border-radius: 20px;
    font-size: .68rem;
    font-weight: 700;
    margin: 1px 2px;
}
</style>

<div class="page-container">
<div class="main-content">

    <div class="page-header">
        <div class="d-flex align-items-center justify-content-between flex-wrap">
            <div>
                <h2>
                    <i class="fa fa-exclamation-triangle text-danger"></i>
                    Pregnancy Anomaly Report
                </h2>
                <p class="text-muted mb-0">
                    Records marked as Pregnant but with conflicting demographic data
                </p>
            </div>
            <?php if ($filter): ?>
                <a href="<?= base_url('reports/pregnancy_anomaly') ?>"
                   class="btn btn-default btn-sm">
                    <i class="fa fa-times"></i> Clear Filter
                </a>
            <?php endif; ?>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">

        <!-- All -->
        <div class="col-md-3 col-sm-6 mb-3">
            <a href="<?= base_url('reports/pregnancy_anomaly') ?>"
               class="pa-stat-card card <?= !$filter ? 'is-active' : '' ?>"
               style="border-left-color:#c0392b;">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="mr-3" style="width:46px;height:46px;border-radius:50%;background:#fde8e8;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i class="fa fa-list" style="color:#c0392b;font-size:1.2rem;"></i>
                        </div>
                        <div>
                            <div class="text-muted" style="font-size:.74rem;">All Anomalies</div>
                            <div style="font-size:1.8rem;font-weight:800;color:#c0392b;line-height:1;">
                                <?= isset($summary['total']) ? $summary['total'] : 0 ?>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <?php foreach ($filter_cfg as $fkey => $fcfg):
            $fcount  = isset($summary[$fkey]) ? $summary[$fkey] : 0;
            $is_active = ($filter === $fkey);
        ?>
        <div class="col-md-3 col-sm-6 mb-3">
            <a href="<?= base_url('reports/pregnancy_anomaly/' . $fkey) ?>"
               class="pa-stat-card card <?= $is_active ? 'is-active' : '' ?>"
               style="border-left-color:<?= $fcfg['border'] ?>;">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="mr-3" style="width:46px;height:46px;border-radius:50%;background:<?= $fcfg['pill_bg'] ?>;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i class="fa <?= $fcfg['icon'] ?>" style="color:<?= $fcfg['color'] ?>;font-size:1.2rem;"></i>
                        </div>
                        <div>
                            <div class="text-muted" style="font-size:.74rem;"><?= $fcfg['label'] ?></div>
                            <div style="font-size:1.8rem;font-weight:800;color:<?= $fcfg['color'] ?>;line-height:1;">
                                <?= $fcount ?>
                            </div>
                            <div style="font-size:.67rem;color:<?= $fcfg['color'] ?>;"><?= $fcfg['desc'] ?></div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <?php endforeach; ?>

    </div>

    <!-- Info -->
    <div class="alert alert-warning mb-4">
        <strong><i class="fa fa-info-circle"></i> About this report:</strong>
        These records have <strong>Pregnancy Status = Pregnant</strong> but contain one or more of the following issues:
        <span style="color:#1a5276;font-weight:600;"> Male gender</span> |
        <span style="color:#c0392b;font-weight:600;"> Age under 18</span> |
        <span style="color:#b7770d;font-weight:600;"> Un-Married status</span>
    </div>

    <!-- Table -->
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">
                <?php if ($filter && isset($filter_cfg[$filter])): ?>
                    <span style="color:<?= $filter_cfg[$filter]['color'] ?>;">
                        <i class="fa <?= $filter_cfg[$filter]['icon'] ?>"></i>
                        <?= $filter_cfg[$filter]['label'] ?> Records
                    </span>
                <?php else: ?>
                    <i class="fa fa-table text-muted"></i> All Anomaly Records
                <?php endif; ?>
                <span class="badge badge-danger ml-2"><?= $total ?></span>
            </h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 pa-table">
                    <thead>
                        <tr>
                            <th width="40" class="pl-3">#</th>
                            <th width="125">QR Code</th>
                            <th>Patient Name</th>
                            <th width="120">Guardian</th>
                            <th width="90">DOB / Age</th>
                            <th width="90">Gender</th>
                            <th width="110">Marital Status</th>
                            <th width="110">Preg. Status</th>
                            <th width="160">Issues Found</th>
                            <th width="110">Entered By</th>
                            <th width="110">Vaccinator</th>
                            <th width="100">Village</th>
                            <th width="85">Status</th>
                            <th width="60" class="text-center">View</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if (!empty($records)):
                        $s = 0;
                        foreach ($records as $row):
                            $s++;
                            $dob         = !empty($row['dob']) ? date('d M Y', strtotime($row['dob'])) : '—';
                            $age_display = ageDisplayPA($row);
                            $vs          = isset($row['verification_status']) ? $row['verification_status'] : '';
                            $d_user      = isset($row['data_entry_user'])     ? $row['data_entry_user']     : '';
                            $issues      = getIssueReasons($row);
                    ?>
                        <tr class="row-stripe">
                            <td class="pl-3 text-muted"><?= $s ?></td>
                            <td>
                                <span class="qr-pill-pa" title="<?= htmlspecialchars($row['qr_code']) ?>">
                                    <?= htmlspecialchars($row['qr_code']) ?>
                                </span>
                            </td>
                            <td><strong><?= htmlspecialchars($row['patient_name']) ?></strong></td>
                            <td><?= htmlspecialchars($row['guardian_name'] ? $row['guardian_name'] : '—') ?></td>
                            <td>
                                <div><?= $dob ?></div>
                                <small class="text-muted"><?= $age_display ?></small>
                            </td>
                            <td>
                                <?php if ($row['gender'] !== 'Female'): ?>
                                    <span class="badge badge-primary">
                                        <i class="fa fa-mars"></i>
                                        <?= htmlspecialchars($row['gender'] ? $row['gender'] : '—') ?>
                                    </span>
                                <?php else: ?>
                                    <span class="badge badge-default">
                                        <?= htmlspecialchars($row['gender']) ?>
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($row['marital_status'] === 'Un-Married'): ?>
                                    <span class="badge badge-warning text-dark">
                                        <?= htmlspecialchars($row['marital_status']) ?>
                                    </span>
                                <?php else: ?>
                                    <?= htmlspecialchars($row['marital_status'] ? $row['marital_status'] : '—') ?>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="badge badge-danger">
                                    <i class="fa fa-female"></i>
                                    <?= htmlspecialchars($row['pregnancy_status']) ?>
                                </span>
                            </td>
                            <td class="issue-tags-cell">
                                <?php foreach ($issues as $issue): ?>
                                    <span class="issue-tag-pa"
                                          style="background:<?= $issue['bg'] ?>;color:<?= $issue['color'] ?>;border:1px solid <?= $issue['color'] ?>;">
                                        <i class="fa <?= $issue['icon'] ?>"></i>
                                        <?= $issue['text'] ?>
                                    </span>
                                <?php endforeach; ?>
                            </td>
                            <td>
                                <?php if ($d_user): ?>
                                    <i class="fa fa-user-circle-o text-muted"></i>
                                    <?= htmlspecialchars($d_user) ?>
                                <?php else: ?>
                                    <span class="text-muted">—</span>
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars($row['vaccinator_name'] ? $row['vaccinator_name'] : '—') ?></td>
                            <td><?= htmlspecialchars($row['village'] ? $row['village'] : '—') ?></td>
                            <td>
                                <?php if ($vs === 'verified'): ?>
                                    <span class="badge badge-success"><i class="fa fa-check"></i> Verified</span>
                                <?php elseif ($vs === 'rejected'): ?>
                                    <span class="badge badge-danger"><i class="fa fa-times"></i> Rejected</span>
                                <?php else: ?>
                                    <span class="badge badge-warning"><i class="fa fa-clock-o"></i> Pending</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <a href="<?= $view_base . $row['master_id'] ?>" target="_blank"
                                   class="btn btn-sm btn-primary" title="View">
                                    <i class="fa fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach;
                    else: ?>
                        <tr>
                            <td colspan="14" class="text-center" style="padding:50px 0;">
                                <i class="fa fa-check-circle text-success" style="font-size:3rem;"></i>
                                <h4 class="text-success mt-3">No Anomalies Found</h4>
                                <p class="text-muted">All pregnancy records appear consistent.</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php if ($total > 0): ?>
        <div class="card-footer text-muted text-right" style="font-size:.8rem;">
            Showing <?= $total ?> record<?= $total != 1 ? 's' : '' ?>
            <?php if ($filter && isset($filter_cfg[$filter])): ?>
                &mdash; filtered by: <?= $filter_cfg[$filter]['label'] ?>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </div>

</div>
</div>