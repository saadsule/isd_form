<div class="page-container">
    <div class="main-content">

        <div class="page-header">
            <h2 class="header-title">
                Vaccination Detail
                <small class="text-muted" style="font-size:14px;">
                    — <?= htmlspecialchars($uc_name) ?> &nbsp;|&nbsp;
                    <?= date('M Y', strtotime($month . '-01')) ?>
                </small>
            </h2>
            <a href="javascript:window.close();" class="btn btn-default btn-sm m-l-10">
                <i class="fa fa-times m-r-5"></i> Close
            </a>
        </div>

        <div class="card">
            <div class="card-header" style="background: linear-gradient(to right, #e8f4fd, #ffffff); border-left: 4px solid #007bff;">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <i class="fa fa-list text-primary m-r-10" style="font-size:20px;"></i>
                        <div>
                            <h5 class="m-b-0" style="color:#007bff; font-weight:700;">
                                <?= htmlspecialchars($uc_name) ?>
                            </h5>
                            <small class="text-muted">
                                <?php if ($is_retained): ?>
                                    <!-- Retained view label -->
                                    Retained children in
                                    <strong><?= date('M Y', strtotime($month . '-01')) ?></strong>
                                    who were also vaccinated in base month
                                    <strong><?= date('M Y', strtotime($base_month . '-01')) ?></strong>
                                <?php else: ?>
                                    <!-- Simple / base view label -->
                                    All vaccinated children in
                                    <strong><?= date('M Y', strtotime($month . '-01')) ?></strong>
                                <?php endif; ?>
                                &nbsp;—&nbsp;
                                <strong><?= count($records) ?></strong> records
                            </small>
                        </div>
                    </div>
                    <div>
                        <button onclick="window.print();" class="btn btn-default btn-sm">
                            <i class="fa fa-print m-r-5"></i> Print
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <?php if (!empty($records)): ?>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th>#</th>
                                <th>QR Code</th>
                                <th>Patient Name</th>
                                <th>Guardian Name</th>
                                <th>Age Group</th>
                                <th>Gender</th>
                                <th>Village</th>
                                <th>Vaccines Given</th>
                                <th>Form Date</th>
                            </tr>
                        </thead>
                        <?php
                        $serial     = 0;
                        $prev_qr    = NULL;
                        $color_index = 0;
                        $row_colors  = array('#ffffff', '#eef5ff');
                        ?>
                        <tbody>
                            <?php
                            $serial      = 0;
                            $prev_qr     = NULL;
                            $color_index = 0;
                            $row_colors  = array('#ffffff', '#f5f9ff');
                            ?>
                            <?php foreach ($records as $row): ?>
                                <?php
                                $is_new_qr = ($row['qr_code'] !== $prev_qr);
                                if ($is_new_qr) {
                                    if ($prev_qr !== NULL) { ?>
                                        <tr><td colspan="9" style="padding:0; height:4px; background:linear-gradient(to right, #007bff40, #e2eeff, transparent); border:none;"></td></tr>
                                    <?php }
                                    $serial++;
                                    $color_index = ($color_index == 0) ? 1 : 0;
                                    $prev_qr = $row['qr_code'];
                                }
                                $bg = $row_colors[$color_index];
                                ?>
                                <tr style="background-color:<?= $bg ?>; border-left: 3px solid <?= $is_new_qr ? '#007bff' : '#b3d1ff' ?>;">
                                    <td class="text-center align-middle">
                                        <?php if ($is_new_qr): ?>
                                            <span style="
                                                display:inline-flex; align-items:center; justify-content:center;
                                                width:26px; height:26px; background:#0f1c3f; color:#fff;
                                                border-radius:7px; font-size:12px; font-weight:700;">
                                                <?= $serial ?>
                                            </span>
                                        <?php else: ?>
                                            <span style="color:#b0c4de; font-size:15px; line-height:1;">↳</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="align-middle">
                                        <?php if ($is_new_qr): ?>
                                            <span style="
                                                font-family: monospace; font-size:12px; font-weight:700;
                                                color:#1a3a6e; background:#e8f0fe;
                                                border:1px solid #b3cdf9; border-radius:6px;
                                                padding:3px 8px; letter-spacing:0.5px; white-space:nowrap;">
                                                <?= $row['qr_code'] ? $row['qr_code'] : '—' ?>
                                            </span>
                                        <?php else: ?>
                                        <span style="
                                            display:inline-flex; align-items:center; gap:6px;
                                            background:#fff3cd; border:1px solid #ffc107;
                                            color:#856404; border-radius:20px;
                                            padding:4px 10px; font-size:11px; font-weight:600;
                                            white-space:nowrap;">
                                            <i class="fa fa-redo" style="font-size:10px;"></i>
                                            Visit Again
                                        </span>
                                    <?php endif; ?>
                                    </td>
                                    <td><?= $row['patient_name'] ? htmlspecialchars($row['patient_name']) : '—' ?></td>
                                    <td><?= $row['guardian_name'] ? htmlspecialchars($row['guardian_name']) : '—' ?></td>
                                    <td class="text-center">
                                        <span class="badge badge-info">
                                            <?= $row['age_group'] ? $row['age_group'] : '—' ?>
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge badge-<?= $row['gender'] == 'Male' ? 'primary' : 'danger' ?>">
                                            <?= $row['gender'] ? $row['gender'] : '—' ?>
                                        </span>
                                    </td>
                                    <td><?= $row['village'] ? htmlspecialchars($row['village']) : '—' ?></td>
                                    <td>
                                        <?php if (!empty($row['vaccines'])): ?>
                                            <?php foreach (explode(', ', $row['vaccines']) as $vaccine): ?>
                                                <span class="badge badge-success" style="font-size:11px; display:inline-block; margin:2px;">
                                                    <i class="fa fa-check" style="font-size:9px;"></i>
                                                    &nbsp;<?= htmlspecialchars(trim($vaccine)) ?>
                                                </span>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <span class="text-muted">—</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= $row['form_date'] ? date('d M Y', strtotime($row['form_date'])) : '—' ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php else: ?>
                    <div class="alert alert-warning">No records found.</div>
                <?php endif; ?>
            </div>
        </div>

    </div>