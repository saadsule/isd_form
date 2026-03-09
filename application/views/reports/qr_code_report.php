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
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($records as $r): ?>
                    <tr>
                        <td><?= $r['form_date'] ?></td>
                        <td><?= $r['patient_name'] ?></td>
                        <td><?= $r['guardian_name'] ?></td>
                        <td><?= $r['uc'] ?></td>
                        <td><?= $r['visit_type'] ?></td>
                        <td><?= isset($r['anc_card_no']) ? 'OPD/MNCH' : 'Child Health' ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>