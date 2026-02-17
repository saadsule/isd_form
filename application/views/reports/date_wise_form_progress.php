<div class="page-container">
    <div class="main-content">
        <div class="page-header">
            <h2 class="header-title">Date-wise Progress Report</h2>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="thead-light">
                    <tr>
                        <th>Form Type</th>
                        <?php foreach($report['dates'] as $date): ?>
                        <th><?= date('d-M', strtotime($date)) ?></th>
                        <?php endforeach; ?>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $form_types = ['Child Health', 'OPD / MNCH'];
                    $grand_totals = [];

                    foreach($form_types as $type):
                        $total_type = 0;
                    ?>
                    <tr>
                        <td><?= $type ?></td>
                        <?php foreach($report['dates'] as $date):
                            if($type == 'Child Health'){
                                $count = isset($report['progress']['child_health'][$date]) ? $report['progress']['child_health'][$date] : 0;
                            } else {
                                $count = isset($report['progress']['opd_mnch'][$date]) ? $report['progress']['opd_mnch'][$date] : 0;
                            }
                            $total_type += $count;

                            // Sum for grand total
                            if(!isset($grand_totals[$date])) $grand_totals[$date] = 0;
                            $grand_totals[$date] += $count;
                        ?>
                        <td><?= $count ?></td>
                        <?php endforeach; ?>
                        <td><strong><?= $total_type ?></strong></td>
                    </tr>
                    <?php endforeach; ?>

                    <!-- Grand Total Row -->
                    <tr class="table-secondary fw-bold">
                        <td>Total</td>
                        <?php 
                        $grand_total = 0;
                        foreach($report['dates'] as $date):
                            $grand_total += $grand_totals[$date];
                        ?>
                        <td><strong><?= $grand_totals[$date] ?></strong></td>
                        <?php endforeach; ?>
                        <td><strong><?= $grand_total ?></strong></td>
                    </tr>

                </tbody>
            </table>
        </div>
    </div>
