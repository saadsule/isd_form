<div class="page-container">
    <div class="main-content">
        <div class="page-header">
            <h2 class="header-title">Date-wise Progress Report</h2>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="thead-light">
                    <tr>
                        <th>User</th>
                        <?php foreach($report['dates'] as $date): ?>
                        <th><?= date('d-M', strtotime($date)) ?></th>
                        <?php endforeach; ?>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($report['users'] as $user): 
                        $user_id = $user['user_id']; 
                        $total_user = 0;
                    ?>
                    <tr>
                        <td><?= htmlspecialchars($user['username']) ?></td>
                        <?php foreach($report['dates'] as $date): 
                            $count = isset($report['progress'][$user_id][$date]) ? $report['progress'][$user_id][$date] : 0;
                            $total_user += $count;
                        ?>
                        <td><?= $count ?></td>
                        <?php endforeach; ?>
                        <td><strong><?= $total_user ?></strong></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

    </div>
</div>
