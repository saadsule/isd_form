<div class="page-container">
<div class="main-content">
<div class="page-header">
    <h2 class="header-title">Record Validation Report</h2>
</div>

<?php
$records      = isset($report['records'])        ? $report['records']        : array();
$verified     = isset($report['verified_total']) ? $report['verified_total'] : 0;
$reported     = isset($report['reported_total']) ? $report['reported_total'] : 0;
$unique_forms = isset($report['unique_forms'])   ? $report['unique_forms']   : 0;
?>

<!-- Summary Strip -->
<div class="row m-b-20 justify-content-center">
    <div class="col-md-6">
        <div class="card shadow-sm" style="border-radius:10px;">
            <div class="card-body py-3 px-4">
                <div class="row text-center">
                    <div class="col-4 border-right">
                        <div style="font-size:11px; color:#6c757d; text-transform:uppercase; letter-spacing:0.5px;">Total Reviews</div>
                        <div style="font-size:24px; font-weight:700; color:#0f1c3f;"><?= $unique_forms ?></div>
                    </div>
                    <div class="col-4 border-right">
                        <div style="font-size:11px; color:#6c757d; text-transform:uppercase; letter-spacing:0.5px;">Verified</div>
                        <div style="font-size:24px; font-weight:700; color:#28a745;"><?= $verified ?></div>
                    </div>
                    <div class="col-4">
                        <div style="font-size:11px; color:#6c757d; text-transform:uppercase; letter-spacing:0.5px;">Reported Data Issue</div>
                        <div style="font-size:24px; font-weight:700; color:#dc3545;"><?= $reported ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Table -->
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-bordered table-hover m-b-0">
                <thead class="thead-dark">
                    <tr>
                        <th>#</th>
                        <th>Module</th>
                        <th>Tracking ID</th>
                        <th>Data Entry By</th>
                        <th>Status</th>
                        <th>Remarks</th>
                        <th>Reviewed By</th>
                        <th>Time</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (!empty($records)):
                    $serial   = 0;
                    $prev_key = NULL;
                    $row_colors = array('#ffffff', '#f5f9ff');
                    $color_index = 0;
                    foreach ($records as $row):
                        $current_key = $row['module_name'] . '_' . $row['master_id'];
                        $is_new = ($current_key !== $prev_key);
                        if ($is_new) {
                            if ($prev_key !== NULL): ?>
                                <tr><td colspan="9" style="padding:0; height:4px; background:linear-gradient(to right, #007bff40, #e2eeff, transparent); border:none;"></td></tr>
                            <?php endif;
                            $serial++;
                            $color_index = ($color_index == 0) ? 1 : 0;
                            $prev_key = $current_key;
                        }
                        $bg = $row_colors[$color_index];
                        $status = strtolower($row['validation_status']);
                ?>
                <tr style="background-color:<?= $bg ?>; border-left: 3px solid <?= $is_new ? '#007bff' : '#b3d1ff' ?>;">
                    <td class="text-center align-middle">
                        <?php if ($is_new): ?>
                            <span style="display:inline-flex; align-items:center; justify-content:center; width:26px; height:26px; background:#0f1c3f; color:#fff; border-radius:7px; font-size:12px; font-weight:700;">
                                <?= $serial ?>
                            </span>
                        <?php else: ?>
                            <span style="color:#b0c4de; font-size:15px;">↳</span>
                        <?php endif; ?>
                    </td>
                    <td class="align-middle">
                        <?php if ($is_new): ?>
                            <span style="font-size:12px; font-weight:600; color:#1a3a6e; background:#e8f0fe; border:1px solid #b3cdf9; border-radius:6px; padding:3px 8px;">
                                <?= htmlspecialchars($row['module_name']) ?>
                            </span>
                        <?php else: ?>
                            <span style="font-size:11px; color:#90a4c0; font-style:italic; padding-left:10px; display:flex; align-items:center; gap:5px;">
                                <span style="display:inline-block; width:6px; height:6px; border-radius:50%; background:#b3d1ff;"></span>
                                same form
                            </span>
                        <?php endif; ?>
                    </td>
                    <td class="align-middle">
                        <?php if ($is_new): ?>
                            <strong><?= $row['master_id'] ?></strong>
                        <?php else: ?>
                            <span class="text-muted" style="font-size:12px;">—</span>
                        <?php endif; ?>
                    </td>
                    <td class="align-middle">
                        <?php if ($is_new): ?>
                            <?= htmlspecialchars($row['creator_name']) ?>
                        <?php else: ?>
                            <span class="text-muted" style="font-size:12px;">—</span>
                        <?php endif; ?>
                    </td>
                    <td class="align-middle">
                        <?php if ($status == 'verified'): ?>
                            <span class="badge badge-success" style="font-size:11px; padding:5px 10px;">
                                <i class="fa fa-check m-r-3"></i> Verified
                            </span>
                        <?php elseif ($status == 'reported'): ?>
                            <span class="badge badge-danger" style="font-size:11px; padding:5px 10px;">
                                <i class="fa fa-flag m-r-3"></i> Reported
                            </span>
                        <?php else: ?>
                            <span class="badge badge-warning" style="font-size:11px; padding:5px 10px;">
                                <?= htmlspecialchars($row['validation_status']) ?>
                            </span>
                        <?php endif; ?>
                    </td>
                    <td class="align-middle" style="font-size:13px;"><?= htmlspecialchars($row['remarks']) ?></td>
                    <td class="align-middle" style="font-size:13px;"><?= htmlspecialchars($row['full_name']) ?></td>
                    <td class="align-middle" style="font-size:12px; white-space:nowrap;">
                        <strong><?= date('d M Y', strtotime($row['created_at'])) ?></strong><br>
                        <span style="color:#6c757d;"><?= date('H:i', strtotime($row['created_at'])) ?></span>
                    </td>
                    <td class="align-middle">
                        <?php if ($is_new): ?>
                            <?php if ($row['module_name'] == 'child_health'): ?>
                                <a href="<?= base_url('forms/view_child_health/'.$row['master_id']) ?>" target="_blank" class="btn btn-sm" style="background:#e8f0fe; color:#1a3a6e; border:1px solid #b3cdf9;" title="View">
                                    <i class="fa fa-eye"></i>
                                </a>
                            <?php elseif ($row['module_name'] == 'opd_mnch'): ?>
                                <a href="<?= base_url('forms/view_opd_mnch/'.$row['master_id']) ?>" target="_blank" class="btn btn-sm" style="background:#e8f0fe; color:#1a3a6e; border:1px solid #b3cdf9;" title="View">
                                    <i class="fa fa-eye"></i>
                                </a>
                            <?php endif; ?>
                        <?php else: ?>
                            <span class="text-muted">—</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="9" class="text-center p-4 text-muted">No records found</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

</div>
</div>