<div class="page-container">
    <div class="main-content">
            <div class="page-header">
                <h2 class="header-title">QR Code: <?= $qr_code ?></h2>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                <thead class="thead-light">
                    <tr>
                        <th>Form Date</th>
                        <th>Patient Name</th>
                        <th>Guardian Name</th>
                        <th>UC</th>
                        <th>Visit Type</th>
                        <th>Module</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($records as $r): ?>
                    <?php $is_opd = ($r['form_type'] == 'opd'); ?>
                    <tr>
                        <td><?= $r['form_date'] ?></td>
                        <td><?= $r['patient_name'] ?></td>
                        <td><?= $r['guardian_name'] ?></td>
                        <td><?= $r['uc_name'] ?></td>
                        <td><?= $r['visit_type'] ?></td>
                        <td><?= $is_opd ? 'OPD/MNCH' : 'Child Health' ?></td>
                        <td>
                            <?php if($is_opd): ?>
                                <a href="<?= base_url('forms/view_opd_mnch/'.$r['id']) ?>" class="btn btn-tone btn-success btn-sm" title="View" target="_blank">
                                    <i class="fa fa-eye"></i>
                                </a>
                            <?php else: ?>
                                <a href="<?= base_url('forms/view_child_health/'.$r['master_id']) ?>" class="btn btn-tone btn-success btn-sm" title="View" target="_blank">
                                    <i class="fa fa-eye"></i>
                                </a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>