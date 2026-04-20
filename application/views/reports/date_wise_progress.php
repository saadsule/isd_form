<style>
    .table th, .table td {
        padding: 3px 5px !important;
    }
</style>
<div class="page-container">
    <div class="main-content">
        <div class="page-header">
            <h2 class="header-title">Date-wise Progress Report</h2>
        </div>

        <!-- Date Range Filter Form -->
        <div class="card mb-3">
            <div class="card-body py-2">
                <form method="GET" action="" class="form-inline">
                    <div class="form-group mr-3">
                        <label class="mr-2 font-weight-bold">From:</label>
                        <input type="date" name="from_date" class="form-control form-control-sm"
                               value="<?= htmlspecialchars($report['from_date']) ?>">
                    </div>
                    <div class="form-group mr-3">
                        <label class="mr-2 font-weight-bold">To:</label>
                        <input type="date" name="to_date" class="form-control form-control-sm"
                               value="<?= htmlspecialchars($report['to_date']) ?>">
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm">Apply</button>
                    <a href="?" class="btn btn-secondary btn-sm ml-2">Reset</a>
                </form>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-hover table-sm" style="font-size: 10px;">
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
                        <td><?= htmlspecialchars($user['full_name']) ?></td>
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
                        <td><strong><?= $gt ?></strong></td>
                        <?php endforeach; ?>
                        <td><strong><?= $overall_total ?></strong></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>