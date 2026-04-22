<?php
$records   = isset($records) ? $records : array();
$total     = isset($total)   ? $total   : 0;
$view_base = rtrim(base_url(), '/') . '/forms/view_child_health/';

function ageDisplayDup($row) {
    $y = isset($row['age_year'])  ? (int)$row['age_year']  : 0;
    $m = isset($row['age_month']) ? (int)$row['age_month'] : 0;
    $d = isset($row['age_day'])   ? (int)$row['age_day']   : 0;
    $parts = array();
    if ($y) { $parts[] = $y . 'y'; }
    if ($m) { $parts[] = $m . 'm'; }
    if ($d) { $parts[] = $d . 'd'; }
    return !empty($parts) ? implode(' ', $parts) : '—';
}

/* Build groups again in view for visual grouping */
$groups = array();
foreach ($records as $row) {
    $key = strtolower(trim($row['qr_code']))
         . '||' . strtolower(trim($row['patient_name']))
         . '||' . $row['form_date'];
    $groups[$key][] = $row;
}
?>

<style>
.dup-table thead tr {
    background: linear-gradient(135deg, #1b2a4a 0%, #e67e22 100%);
}
.dup-table thead th {
    color: #fff !important;
    font-size: .72rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: .6px;
    padding: 12px 10px;
    border: none !important;
    white-space: nowrap;
}
.dup-table tbody td {
    font-size: .82rem;
    vertical-align: middle;
    padding: 9px 10px;
}
.dup-table tbody tr:hover { background: #fffaf5; }

/* Group separator row */
.dup-group-header td {
    background: linear-gradient(90deg, #fef3e2, #fff8f0);
    border-top: 2px solid #e67e22 !important;
    border-bottom: 1px dashed #f0a050 !important;
    font-size: .75rem;
    font-weight: 700;
    color: #7d4e0f;
    padding: 7px 10px !important;
}
.dup-row-odd  { border-left: 4px solid #e67e22; background: #fff; }
.dup-row-even { border-left: 4px solid #e67e22; background: #fffcf8; }

.qr-pill-dup {
    font-family: monospace;
    font-size: .72rem;
    background: #fef3e2;
    border: 1px solid #f0c080;
    padding: 3px 7px;
    border-radius: 5px;
    display: inline-block;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 115px;
}
.dup-count-badge {
    display: inline-block;
    background: #e67e22;
    color: #fff;
    border-radius: 12px;
    padding: 2px 10px;
    font-size: .7rem;
    font-weight: 700;
    margin-left: 6px;
}
</style>

<div class="page-container">
<div class="main-content">

    <div class="page-header">
        <div class="d-flex align-items-center justify-content-between flex-wrap">
            <div>
                <h2>
                    <i class="fa fa-copy text-warning"></i>
                    Possible Duplicate Data
                </h2>
                <p class="text-muted mb-0">
                    Records sharing the same QR Code, Patient Name, and Form Date
                </p>
            </div>
        </div>
    </div>

    <!-- Info -->
    <div class="alert alert-warning mb-4">
        <strong><i class="fa fa-info-circle"></i> About this report:</strong>
        These records have identical <strong>QR Code</strong> +
        <strong>Patient Name</strong> + <strong>Form Date</strong> combinations,
        which may indicate duplicate data entry.
    </div>

    <!-- Table -->
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">
                <i class="fa fa-table text-muted"></i> Duplicate Groups
                <span class="badge badge-warning ml-2"><?= $total ?> record(s)</span>
                <span class="badge badge-secondary ml-1"><?= count($groups) ?> group(s)</span>
            </h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 dup-table">
                    <thead>
                        <tr>
                            <th width="40" class="pl-3">#</th>
                            <th width="120">QR Code</th>
                            <th width="105">Form Date</th>
                            <th>Patient Name</th>
                            <th width="130">Guardian Name</th>
                            <th width="105">DOB</th>
                            <th width="75">Age</th>
                            <th width="75">Gender</th>
                            <th width="90">Age Group</th>
                            <th width="130">Vaccinator</th>
                            <th width="110">Village</th>
                            <th width="130">Entered By</th>
                            <th width="110">Entered At</th>
                            <th width="90" class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if (!empty($groups)):
                        $serial = 0;
                        $g_num  = 0;
                        foreach ($groups as $key => $rows):
                            $g_num++;
                            $parts     = explode('||', $key);
                            $g_qr      = $parts[0];
                            $g_name    = $parts[1];
                            $g_date    = $parts[2];
                            $row_count = count($rows);
                    ?>
                        <!-- Group header -->
                        <tr class="dup-group-header">
                            <td colspan="14">
                                <i class="fa fa-folder-open-o"></i>
                                Group <?= $g_num ?>:
                                <span style="color:#c0392b;">QR: <?= htmlspecialchars(strtoupper($g_qr)) ?></span>
                                &nbsp;|&nbsp;
                                <span style="color:#1a5276;">Patient: <?= htmlspecialchars(ucwords($g_name)) ?></span>
                                &nbsp;|&nbsp;
                                <span style="color:#1e8449;">Date: <?= htmlspecialchars($g_date) ?></span>
                                <span class="dup-count-badge"><?= $row_count ?> duplicates</span>
                            </td>
                        </tr>

                        <?php foreach ($rows as $i => $row):
                            $serial++;
                            $dob         = !empty($row['dob']) ? date('d M Y', strtotime($row['dob'])) : '—';
                            $age_display = ageDisplayDup($row);
                            $entered_at  = !empty($row['created_at']) ? date('d M Y H:i', strtotime($row['created_at'])) : '—';
                            $d_user      = isset($row['data_entry_user']) ? $row['data_entry_user'] : '';
                            $row_class   = ($i % 2 === 0) ? 'dup-row-odd' : 'dup-row-even';

                            $user_id   = $this->session->userdata('user_id');
                            $user_role = $this->session->userdata('role');
                            $can_edit  = ($user_id == $row['created_by']) ||
                                         ($user_role == 'admin') ||
                                         ($user_role == 5);
                        ?>
                        <tr class="<?= $row_class ?>">
                            <td class="pl-3 text-muted"><?= $serial ?></td>
                            <td>
                                <span class="qr-pill-dup" title="<?= htmlspecialchars($row['qr_code']) ?>">
                                    <?= htmlspecialchars($row['qr_code']) ?>
                                </span>
                            </td>
                            <td><?= htmlspecialchars($row['form_date']) ?></td>
                            <td><strong><?= htmlspecialchars($row['patient_name']) ?></strong></td>
                            <td><?= htmlspecialchars($row['guardian_name'] ? $row['guardian_name'] : '—') ?></td>
                            <td>
                                <div><?= $dob ?></div>
                            </td>
                            <td><small class="text-muted"><?= $age_display ?></small></td>
                            <td>
                                <?php if ($row['gender'] === 'Female'): ?>
                                    <span class="badge badge-default"><?= htmlspecialchars($row['gender']) ?></span>
                                <?php elseif ($row['gender'] === 'Male'): ?>
                                    <span class="badge badge-primary"><i class="fa fa-mars"></i> Male</span>
                                <?php else: ?>
                                    <span class="text-muted">—</span>
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars($row['age_group'] ? $row['age_group'] : '—') ?></td>
                            <td><?= htmlspecialchars($row['vaccinator_name'] ? $row['vaccinator_name'] : '—') ?></td>
                            <td><?= htmlspecialchars($row['village'] ? $row['village'] : '—') ?></td>
                            <td>
                                <?php if ($d_user): ?>
                                    <i class="fa fa-user-circle-o text-muted"></i>
                                    <?= htmlspecialchars($d_user) ?>
                                <?php else: ?>
                                    <span class="text-muted">—</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <small class="text-muted"><?= $entered_at ?></small>
                            </td>
                            <td class="text-center">
                                <!-- View: everyone -->
                                <a href="<?= $view_base . $row['master_id'] ?>" target="_blank"
                                   class="btn btn-sm btn-primary" title="View">
                                    <i class="fa fa-eye"></i>
                                </a>
                                <!-- Edit: only district-level / admin -->
                                <?php if ($can_edit): ?>
                                    <a href="<?= base_url('forms/child_health/' . $row['master_id']) ?>"
                                       class="btn btn-tone btn-sm btn-primary" title="Edit">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>

                    <?php endforeach;
                    else: ?>
                        <tr>
                            <td colspan="14" class="text-center" style="padding:50px 0;">
                                <i class="fa fa-check-circle text-success" style="font-size:3rem;"></i>
                                <h4 class="text-success mt-3">No Duplicates Found</h4>
                                <p class="text-muted">All records appear to be unique.</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <?php if ($total > 0): ?>
        <div class="card-footer text-muted text-right" style="font-size:.8rem;">
            Showing <?= $total ?> record(s) across <?= count($groups) ?> duplicate group(s)
        </div>
        <?php endif; ?>
    </div>

</div>