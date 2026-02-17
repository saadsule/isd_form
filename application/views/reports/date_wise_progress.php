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
                    <?php 
                    $grand_totals = array_fill(0, count($report['dates']), 0);
                    $overall_total = 0;

                    foreach($report['users'] as $user): 
                        $user_id = $user['user_id']; 
                        $total_user = 0;
                    ?>
                    <tr>
                        <td><?= htmlspecialchars($user['username']) ?></td>
                        <?php foreach($report['dates'] as $idx => $date): 
                            $count = isset($report['progress'][$user_id][$date]) ? $report['progress'][$user_id][$date] : 0;
                            $total_user += $count;
                            $grand_totals[$idx] += $count;
                        ?>
                        <td><?= $count ?></td>
                        <?php endforeach; ?>
                        <td><strong><?= $total_user ?></strong></td>
                    </tr>
                    <?php 
                        $overall_total += $total_user;
                    endforeach; 
                    ?>
                    <!-- Grand Total Row -->
                    <tr class="table-secondary fw-bold">
                        <td>Total</td>
                        <?php foreach($grand_totals as $gt): ?>
                        <td><?= $gt ?></td>
                        <?php endforeach; ?>
                        <td><?= $overall_total ?></td>
                    </tr>
                </tbody>
            </table>
        </div>

    </div>
