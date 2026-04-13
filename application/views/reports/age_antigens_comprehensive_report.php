<?php
$records     = isset($records)     ? $records     : array();
$summary     = isset($summary)     ? $summary     : array();
$filter_type = isset($filter_type) ? $filter_type : null;
$view_base   = rtrim(base_url(), '/') . '/forms/view_child_health/';
$total       = count($records);

$type_cfg = array(
    'Type 1' => array(
        'color'     => '#c0392b',
        'border'    => '#e74c3c',
        'pill_bg'   => '#fde8e8',
        'icon'      => 'fa-exclamation-circle',
        'q_label'   => 'Q18',
        'age_desc'  => 'Less than 1 year',
        'sev_class' => 'danger',
        'count_key' => 'type_1_count',
        'url_seg'   => '1',
    ),
    'Type 2' => array(
        'color'     => '#b7770d',
        'border'    => '#f39c12',
        'pill_bg'   => '#fef3cd',
        'icon'      => 'fa-exclamation-triangle',
        'q_label'   => 'Q19',
        'age_desc'  => '1 - 2 years',
        'sev_class' => 'warning',
        'count_key' => 'type_2_count',
        'url_seg'   => '2',
    ),
    'Type 3' => array(
        'color'     => '#1a5276',
        'border'    => '#2980b9',
        'pill_bg'   => '#d6eaf8',
        'icon'      => 'fa-info-circle',
        'q_label'   => 'Q20',
        'age_desc'  => '2 - 5 years',
        'sev_class' => 'info',
        'count_key' => 'type_3_count',
        'url_seg'   => '3',
    ),
);

function getAgeDisplay($row) {
    $y = isset($row['age_year'])  ? (int)$row['age_year']  : 0;
    $m = isset($row['age_month']) ? (int)$row['age_month'] : 0;
    $parts = array();
    if ($y) { $parts[] = $y . 'y'; }
    if ($m) { $parts[] = $m . 'm'; }
    if (!empty($parts)) { return implode(' ', $parts); }
    return !empty($row['dob']) ? date('d M Y', strtotime($row['dob'])) : '—';
}
?>

<style>
.stat-card-link {
    display: block;
    text-decoration: none;
    border-radius: 8px;
    transition: transform .2s, box-shadow .2s;
    color: inherit;
}
.stat-card-link:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 18px rgba(0,0,0,.12) !important;
    text-decoration: none;
    color: inherit;
}
.stat-card-link.is-active {
    box-shadow: 0 0 0 3px rgba(0,0,0,.18), 0 4px 14px rgba(0,0,0,.1) !important;
}
.issue-block { font-size: .78rem; line-height: 1.7; }
.issue-block .q-pill {
    display: inline-block;
    padding: 2px 9px;
    border-radius: 20px;
    font-weight: 700;
    font-size: .7rem;
    margin-bottom: 3px;
}
.issue-block .lbl  { color: #888; font-size: .68rem; text-transform: uppercase; letter-spacing: .4px; }
.issue-block .val-bad  { color: #c0392b; font-weight: 600; }
.issue-block .val-good { color: #1a7a4a; font-weight: 600; }

/* QR code - fixed width, no break */
.qr-pill {
    font-family: monospace;
    font-size: .72rem;
    background: #f1f3f5;
    border: 1px solid #dee2e6;
    padding: 3px 7px;
    border-radius: 5px;
    display: inline-block;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 110px;
}

.row-stripe { border-left: 4px solid; }

/* Table header */
.mismatch-table thead tr {
    background: linear-gradient(135deg, #2c3e50 0%, #3d5a80 100%);
}
.mismatch-table thead th {
    color: #ffffff !important;
    font-size: .72rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: .6px;
    padding: 12px 10px;
    border: none !important;
    white-space: nowrap;
}
.mismatch-table tbody td {
    font-size: .82rem;
    vertical-align: middle;
    padding: 10px 10px;
}
.mismatch-table tbody tr:hover {
    background: #f8fbff;
}
</style>

<div class="page-container">
<div class="main-content">

    <div class="page-header">
        <div class="d-flex align-items-center justify-content-between flex-wrap">
            <div>
                <h2>
                    <i class="fa fa-exclamation-triangle text-warning"></i>
                    Age &amp; Antigens Mismatch Report
                </h2>
                <p class="text-muted mb-0">
                    Records where antigen given does not match the child's recorded age group
                </p>
            </div>
            <?php if ($filter_type): ?>
                <a href="<?= base_url('reports/age_antigens_mismatch_comprehensive') ?>"
                   class="btn btn-default btn-sm">
                    <i class="fa fa-times"></i> Clear Filter
                </a>
            <?php endif; ?>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">

        <div class="col-md-3 col-sm-6 mb-3">
            <a href="<?= base_url('reports/age_antigens_mismatch_comprehensive') ?>"
               class="stat-card-link card <?= !$filter_type ? 'is-active' : '' ?>"
               style="border-left: 5px solid #1a73e8;">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="mr-3" style="width:44px;height:44px;border-radius:50%;background:#e8f0fe;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i class="fa fa-list" style="color:#1a73e8;font-size:1.2rem;"></i>
                        </div>
                        <div>
                            <div class="text-muted" style="font-size:.75rem;">All Mismatches</div>
                            <div style="font-size:1.7rem;font-weight:700;color:#1a73e8;line-height:1.1;">
                                <?= isset($summary['total_mismatches']) ? $summary['total_mismatches'] : 0 ?>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <?php foreach ($type_cfg as $type_key => $cfg):
            $is_active = ($filter_type === $type_key);
            $count_val = isset($summary[$cfg['count_key']]) ? $summary[$cfg['count_key']] : 0;
        ?>
        <div class="col-md-3 col-sm-6 mb-3">
            <a href="<?= base_url('reports/age_antigens_mismatch_comprehensive/' . $cfg['url_seg']) ?>"
               class="stat-card-link card <?= $is_active ? 'is-active' : '' ?>"
               style="border-left: 5px solid <?= $cfg['border'] ?>;">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="mr-3" style="width:44px;height:44px;border-radius:50%;background:<?= $cfg['pill_bg'] ?>;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i class="fa <?= $cfg['icon'] ?>" style="color:<?= $cfg['color'] ?>;font-size:1.2rem;"></i>
                        </div>
                        <div>
                            <div class="text-muted" style="font-size:.75rem;">
                                <?= $type_key ?> &mdash; <?= $cfg['q_label'] ?>
                            </div>
                            <div style="font-size:1.7rem;font-weight:700;color:<?= $cfg['color'] ?>;line-height:1.1;">
                                <?= $count_val ?>
                            </div>
                            <div style="font-size:.68rem;color:<?= $cfg['color'] ?>;">
                                <?= $cfg['age_desc'] ?>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <?php endforeach; ?>

    </div>

    <!-- Info Box -->
    <div class="alert alert-info mb-4">
        <div class="row">
            <div class="col-md-4">
                <strong><i class="fa fa-exclamation-circle text-danger"></i> Type 1 (Q18)</strong><br>
                <small>Q18 antigen marked but age group is <strong>not</strong> "Less than 1 year".</small>
            </div>
            <div class="col-md-4">
                <strong><i class="fa fa-exclamation-triangle text-warning"></i> Type 2 (Q19)</strong><br>
                <small>Q19 antigen marked but age group is <strong>not</strong> "1-2 years".</small>
            </div>
            <div class="col-md-4">
                <strong><i class="fa fa-info-circle text-info"></i> Type 3 (Q20)</strong><br>
                <small>Q20 antigen marked but age group is <strong>not</strong> "2-5 years".</small>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="card">
        <div class="card-header">
            <div class="d-flex align-items-center justify-content-between">
                <h5 class="card-title mb-0">
                    <?php if ($filter_type && isset($type_cfg[$filter_type])): 
                        $active_cfg = $type_cfg[$filter_type];
                    ?>
                        <span style="color:<?= $active_cfg['color'] ?>;">
                            <i class="fa <?= $active_cfg['icon'] ?>"></i>
                            <?= $filter_type ?> Mismatch Records
                        </span>
                    <?php else: ?>
                        <i class="fa fa-table text-muted"></i>
                        All Mismatch Records
                    <?php endif; ?>
                    <span class="badge badge-default ml-2"><?= $total ?></span>
                </h5>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 mismatch-table">
                    <thead>
                        <tr>
                            <th width="40" class="pl-3">#</th>
                            <th width="125">QR Code</th>
                            <th>Patient Name</th>
                            <th width="120">Guardian</th>
                            <th width="95">DOB / Age</th>
                            <th width="100">Age Group</th>
                            <th width="195">Mismatch Issue</th>
                            <th width="105">Entered By</th>
                            <th width="105">Vaccinator</th>
                            <th width="95">Village</th>
                            <th width="85">Status</th>
                            <th width="60" class="text-center">View</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if (!empty($records)):
                        $serial = 0;
                        foreach ($records as $row):
                            $serial++;
                            $mtype = isset($row['mismatch_type']) ? $row['mismatch_type'] : '';
                            if (isset($type_cfg[$mtype])) {
                                $cfg = $type_cfg[$mtype];
                            } else {
                                $cfg = array(
                                    'color'    => '#6c757d',
                                    'border'   => '#adb5bd',
                                    'pill_bg'  => '#e9ecef',
                                    'icon'     => 'fa-circle',
                                    'q_label'  => '—',
                                    'age_desc' => '—',
                                    'sev_class'=> 'default',
                                    'count_key'=> '',
                                    'url_seg'  => '',
                                );
                            }
                            $dob         = !empty($row['dob']) ? date('d M Y', strtotime($row['dob'])) : '—';
                            $age_display = getAgeDisplay($row);
                            $age_group   = isset($row['age_group'])           ? $row['age_group']           : '—';
                            $exp_group   = isset($row['expected_age_group'])  ? $row['expected_age_group']  : '—';
                            $q_answered  = isset($row['question_answered'])   ? $row['question_answered']   : $cfg['q_label'];
                            $vs          = isset($row['verification_status']) ? $row['verification_status'] : '';
                            $d_user      = isset($row['data_entry_user'])     ? $row['data_entry_user']     : '';
                            $vaccinator  = isset($row['vaccinator_name'])     ? $row['vaccinator_name']     : '';
                            $village     = isset($row['village'])             ? $row['village']             : '';
                            $guardian    = isset($row['guardian_name'])       ? $row['guardian_name']       : '';
                    ?>
                        <tr class="row-stripe" style="border-left-color:<?= $cfg['border'] ?>;">
                            <td class="pl-3 text-muted"><?= $serial ?></td>
                            <td>
                                <span class="qr-pill" title="<?= htmlspecialchars($row['qr_code']) ?>">
                                    <?= htmlspecialchars($row['qr_code']) ?>
                                </span>
                            </td>
                            <td>
                                <strong><?= htmlspecialchars($row['patient_name']) ?></strong>
                            </td>
                            <td><?= htmlspecialchars($guardian ? $guardian : '—') ?></td>
                            <td>
                                <div><?= $dob ?></div>
                                <small class="text-muted"><?= $age_display ?></small>
                            </td>
                            <td>
                                <span class="badge" style="background:<?= $cfg['pill_bg'] ?>;color:<?= $cfg['color'] ?>;padding:4px 8px;">
                                    <?= htmlspecialchars($age_group) ?>
                                </span>
                            </td>
                            <td>
                                <div class="issue-block">
                                    <div>
                                        <span class="q-pill" style="background:<?= $cfg['pill_bg'] ?>;color:<?= $cfg['color'] ?>;">
                                            <i class="fa <?= $cfg['icon'] ?>"></i>
                                            <?= $q_answered ?> Given
                                        </span>
                                    </div>
                                    <div>
                                        <span class="lbl">Recorded: </span>
                                        <span class="val-bad">
                                            <i class="fa fa-times-circle"></i>
                                            <?= htmlspecialchars($age_group) ?>
                                        </span>
                                    </div>
                                    <div>
                                        <span class="lbl">Required: </span>
                                        <span class="val-good">
                                            <i class="fa fa-check-circle"></i>
                                            <?= htmlspecialchars($exp_group) ?>
                                        </span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <?php if ($d_user): ?>
                                    <i class="fa fa-user-circle-o text-muted"></i>
                                    <?= htmlspecialchars($d_user) ?>
                                <?php else: ?>
                                    <span class="text-muted">—</span>
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars($vaccinator ? $vaccinator : '—') ?></td>
                            <td><?= htmlspecialchars($village ? $village : '—') ?></td>
                            <td>
                                <?php if ($vs === 'verified'): ?>
                                    <span class="badge badge-success">
                                        <i class="fa fa-check"></i> Verified
                                    </span>
                                <?php elseif ($vs === 'rejected'): ?>
                                    <span class="badge badge-danger">
                                        <i class="fa fa-times"></i> Rejected
                                    </span>
                                <?php else: ?>
                                    <span class="badge badge-warning">
                                        <i class="fa fa-clock-o"></i> Pending
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <a href="<?= $view_base . $row['master_id'] ?>" target="_blank"
                                   class="btn btn-sm btn-primary" title="View Record">
                                    <i class="fa fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach;
                    else: ?>
                        <tr>
                            <td colspan="12" class="text-center" style="padding:50px 0;">
                                <i class="fa fa-check-circle text-success" style="font-size:3rem;"></i>
                                <h4 class="text-success mt-3">No Mismatches Found</h4>
                                <p class="text-muted">All antigen records match the recorded age groups correctly.</p>
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
            <?php if ($filter_type): ?>
                &mdash; filtered by <?= htmlspecialchars($filter_type) ?>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </div>

</div>