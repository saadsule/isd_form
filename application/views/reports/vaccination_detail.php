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
                        <tbody>
                            <?php foreach ($records as $i => $row): ?>
                            <tr>
                                <td><?= $i + 1 ?></td>
                                <td>
                                    <span class="badge badge-light" style="font-size:12px; color:#333;">
                                        <?= $row['qr_code'] ? $row['qr_code'] : '—' ?>
                                    </span>
                                </td>
                                <td><?= $row['patient_name'] ? htmlspecialchars($row['patient_name']) : '—' ?></td>
                                <td><?= $row['guardian_name'] ? htmlspecialchars($row['guardian_name']) : '—' ?></td>
                                <td>
                                    <span class="badge badge-info">
                                        <?= $row['age_group'] ? $row['age_group'] : '—' ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge badge-<?= $row['gender'] == 'Male' ? 'primary' : 'danger' ?>">
                                        <?= $row['gender'] ? $row['gender'] : '—' ?>
                                    </span>
                                </td>
                                <td><?= $row['village'] ? htmlspecialchars($row['village']) : '—' ?></td>
                                <td>
                                    <?php if (!empty($row['vaccines'])): ?>
                                        <?php foreach (explode(', ', $row['vaccines']) as $vaccine): ?>
                                            <span class="badge badge-success m-b-2" style="font-size:11px; display:inline-block; margin:2px 2px;">
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