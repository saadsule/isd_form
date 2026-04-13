<?php
$records = isset($records) ? $records : array();
$total   = isset($total)   ? $total   : 0;
$view_base = rtrim(base_url(), '/') . '/forms/view_child_health/';

function ageDisplayUM($row) {
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
.um-table thead tr {
    background: linear-gradient(135deg, #6a1b4d 0%, #a0306a 100%);
}
.um-table thead th {
    color: #fff !important;
    font-size: .72rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: .6px;
    padding: 12px 10px;
    border: none !important;
    white-space: nowrap;
}
.um-table tbody td {
    font-size: .82rem;
    vertical-align: middle;
    padding: 10px;
}
.um-table tbody tr:hover { background: #fdf4f9; }
.um-table .row-stripe { border-left: 4px solid #a0306a; }
.qr-pill-um {
    font-family: monospace;
    font-size: .72rem;
    background: #f8f0f5;
    border: 1px solid #e8d0e0;
    padding: 3px 7px;
    border-radius: 5px;
    display: inline-block;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 115px;
}
.stat-box-um {
    border-radius: 10px;
    background: linear-gradient(135deg, #6a1b4d, #a0306a);
    color: #fff;
    padding: 20px 24px;
    display: flex;
    align-items: center;
    gap: 16px;
    box-shadow: 0 4px 14px rgba(106,27,77,.25);
}
.stat-box-um .stat-icon {
    width: 52px; height: 52px;
    border-radius: 50%;
    background: rgba(255,255,255,.2);
    display: flex; align-items: center; justify-content: center;
    font-size: 1.4rem; flex-shrink: 0;
}
.stat-box-um .stat-num  { font-size: 2rem; font-weight: 800; line-height: 1; }
.stat-box-um .stat-lbl  { font-size: .78rem; opacity: .85; }
.issue-tag {
    display: inline-block;
    padding: 3px 10px;
    border-radius: 20px;
    font-size: .7rem;
    font-weight: 700;
    background: #fde8f3;
    color: #6a1b4d;
    border: 1px solid #e8b0d0;
}
</style>

<div class="page-container">
<div class="main-content">

    <div class="page-header">
        <h2>
            <i class="fa fa-warning text-danger"></i>
            Underage Married Records
        </h2>
        <p class="text-muted mb-0">
            Children under 18 years of age whose marital status is recorded as Married
        </p>
    </div>

    <!-- Stat Card -->
    <div class="row mb-4">
        <div class="col-md-4 col-sm-6 mb-3">
            <div class="stat-box-um">
                <div class="stat-icon">
                    <i class="fa fa-exclamation-triangle"></i>
                </div>
                <div>
                    <div class="stat-num"><?= $total ?></div>
                    <div class="stat-lbl">Underage Married Records Found</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Alert -->
    <div class="alert alert-danger mb-4">
        <strong><i class="fa fa-info-circle"></i> Issue:</strong>
        These records have age below 18 years but marital status is marked as <strong>Married</strong>.
        This may indicate a data entry error or an underage marriage case requiring attention.
    </div>

    <!-- Table -->
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">
                <i class="fa fa-table text-muted"></i>
                Records
                <span class="badge badge-danger ml-2"><?= $total ?></span>
            </h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 um-table">
                    <thead>
                        <tr>
                            <th width="40" class="pl-3">#</th>
                            <th width="125">QR Code</th>
                            <th>Patient Name</th>
                            <th width="120">Guardian</th>
                            <th width="90">DOB / Age</th>
                            <th width="100">Age Group</th>
                            <th width="90">Gender</th>
                            <th width="110">Marital Status</th>
                            <th width="110">Issue</th>
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
                            $age_display = ageDisplayUM($row);
                            $vs          = isset($row['verification_status']) ? $row['verification_status'] : '';
                            $d_user      = isset($row['data_entry_user'])     ? $row['data_entry_user']     : '';
                    ?>
                        <tr class="row-stripe">
                            <td class="pl-3 text-muted"><?= $s ?></td>
                            <td>
                                <span class="qr-pill-um" title="<?= htmlspecialchars($row['qr_code']) ?>">
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
                                <span class="badge badge-default">
                                    <?= htmlspecialchars($row['age_group'] ? $row['age_group'] : '—') ?>
                                </span>
                            </td>
                            <td><?= htmlspecialchars($row['gender'] ? $row['gender'] : '—') ?></td>
                            <td>
                                <span class="badge badge-danger">
                                    <?= htmlspecialchars($row['marital_status']) ?>
                                </span>
                            </td>
                            <td>
                                <span class="issue-tag">
                                    <i class="fa fa-exclamation-circle"></i>
                                    Under 18 + Married
                                </span>
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
                                <h4 class="text-success mt-3">No Records Found</h4>
                                <p class="text-muted">No underage married records detected.</p>
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
        </div>
        <?php endif; ?>
    </div>

</div>