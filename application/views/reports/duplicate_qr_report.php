<div class="page-container">
    <div class="main-content">

        <div class="page-header">
            <h2 class="header-title">Duplicate QR Code Report</h2>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="thead-light">
                    <tr>
                        <th>#</th>
                        <th>QR Code</th>
                        <th>Module</th>
                        <th>Occurrences</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($duplicates)): ?>
                        <?php $i = 1; foreach($duplicates as $row): ?>
                            <tr>
                                <td><?= $i++ ?></td>
                                <td><?= htmlspecialchars($row['qr_code']) ?></td>
                                <td><?= ucfirst($row['module_name']) ?></td>
                                <td><?= $row['total'] ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="text-center">No duplicate QR codes found</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

    </div>
</div>