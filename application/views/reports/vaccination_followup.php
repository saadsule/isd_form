<div class="page-container">
    <div class="main-content">
        <div class="page-header">
            <h2 class="header-title">Vaccination Follow-up Report</h2>
        </div>

        <!-- Filter -->
        <div class="card">
            <div class="card-body">
                <form id="filterForm" method="GET">
                    <div class="row">

                        <div class="col-md-3">
                            <label>From Month *</label>
                            <div class="m-b-15">
                                <input type="month" class="form-control" name="from_month"
                                       value="<?= isset($filters['from_month']) ? $filters['from_month'] : '' ?>"
                                       required>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <label>To Month *</label>
                            <div class="m-b-15">
                                <input type="month" class="form-control" name="to_month"
                                       value="<?= isset($filters['to_month']) ? $filters['to_month'] : '' ?>"
                                       required>
                            </div>
                        </div>

                        <div class="col-md-2 d-flex align-items-center">
                            <button type="submit" class="btn btn-primary">Apply Filter</button>
                        </div>

                    </div>
                </form>
            </div>
        </div>

        <?php if ($isFilterApplied && !empty($months)): ?>

        <!-- Simple Version -->
        <div class="card m-t-20">
            <div class="card-header mt-3" style="background: linear-gradient(to right, #e8f4fd, #ffffff); border-left: 4px solid #007bff;">
                <div class="d-flex align-items-center">
                    <i class="fa fa-syringe text-primary m-r-10" style="font-size:20px;"></i>
                    <div>
                        <h5 class="m-b-0" style="color:#007bff; font-weight:700;">
                            Total Vaccinated Children per UC per Month
                        </h5>
                        <small class="text-muted">Simple overview of vaccination counts by union council</small>
                    </div>
                </div>
            </div>
           <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th>UC</th>
                                <?php foreach ($months as $m): ?>
                                    <th class="text-center"><?= date('M Y', strtotime($m . '-01')) ?></th>
                                <?php endforeach; ?>
                                <th class="text-center">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $grand_totals  = array_fill_keys($months, 0);
                            $overall_total = 0;
                            foreach ($simple_data as $uc_name => $monthTotals):
                                $row_total = 0;
                                foreach ($months as $m) {
                                    $row_total += isset($monthTotals[$m]) ? $monthTotals[$m] : 0;
                                }
                                $overall_total += $row_total;
                            ?>
                            <tr>
                                <td class="font-weight-bold"><?= $uc_name ?></td>
                                <?php foreach ($months as $m):
                                    $val = isset($monthTotals[$m]) ? $monthTotals[$m] : 0;
                                    $grand_totals[$m] += $val;
                                ?>
                                    <td class="text-center">
                                        <?php if ($val > 0): ?>
                                            <span style="font-size:15px; font-weight:bold; color:#007bff;">
                                                <?= $val ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="text-muted">—</span>
                                        <?php endif; ?>
                                    </td>
                                <?php endforeach; ?>
                                <td class="text-center">
                                    <span style="font-size:15px; font-weight:bold; color:#28a745;">
                                        <?= $row_total ?>
                                    </span>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr style="background-color:#f0f4ff;">
                                <td class="font-weight-bold">Total</td>
                                <?php foreach ($months as $m): ?>
                                    <td class="text-center">
                                        <span style="font-size:15px; font-weight:bold; color:#343a40;">
                                            <?= $grand_totals[$m] ?>
                                        </span>
                                    </td>
                                <?php endforeach; ?>
                                <td class="text-center">
                                    <span style="font-size:15px; font-weight:bold; color:#dc3545;">
                                        <?= $overall_total ?>
                                    </span>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <!-- Comparison Version -->
        <div class="card m-t-20">
            <div class="card-header mt-3" style="background: linear-gradient(to right, #fff8e8, #ffffff); border-left: 4px solid #ffc107;">
                <div class="d-flex align-items-center">
                    <i class="fa fa-chart-line text-warning m-r-10" style="font-size:20px;"></i>
                    <div>
                        <h5 class="m-b-0" style="color:#e6a817; font-weight:700;">
                            Follow-up Retention
                        </h5>
                        <small class="text-muted">
                            Base: <strong style="color:#333;"><?= date('M Y', strtotime($months[0] . '-01')) ?></strong>
                            — subsequent months show how many base children returned for vaccination
                        </small>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th>UC</th>
                                <?php foreach ($months as $i => $m): ?>
                                    <th class="text-center">
                                        <?= date('M Y', strtotime($m . '-01')) ?>
                                        <?php if ($i == 0): ?>
                                            <br><small class="text-primary font-weight-bold">(Base)</small>
                                        <?php else: ?>
                                            <br><small class="text-muted">Retained / Rate</small>
                                        <?php endif; ?>
                                    </th>
                                <?php endforeach; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($comparison_data as $uc_name => $monthData): ?>
                            <tr>
                                <td class="font-weight-bold"><?= $uc_name ?></td>
                                <?php foreach ($months as $i => $m): ?>
                                    <?php $d = isset($monthData[$m])
                                        ? $monthData[$m]
                                        : array('retained'=>0,'percent'=>0,'total'=>0);
                                        $color = $d['percent'] >= 75 ? '#28a745' : ($d['percent'] >= 40 ? '#ffc107' : '#dc3545');
                                    ?>
                                    <td class="text-center">
                                        <?php if ($i == 0): ?>
                                            <span style="font-size:18px; font-weight:bold; color:#007bff;">
                                                <?= $d['total'] ?>
                                            </span>
                                            <br><small class="text-muted">children</small>
                                        <?php else: ?>
                                            <div style="display:flex; flex-direction:column; align-items:center; gap:4px;">
                                                <span style="font-size:16px; font-weight:bold; color:#333;">
                                                    <?= $d['retained'] ?>
                                                </span>
                                                <div style="
                                                    background-color: <?= $color ?>;
                                                    color: #fff;
                                                    border-radius: 20px;
                                                    padding: 2px 10px;
                                                    font-size: 12px;
                                                    font-weight: bold;
                                                    min-width: 52px;
                                                    text-align: center;
                                                ">
                                                    <?= $d['percent'] ?>%
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                <?php endforeach; ?>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <?php elseif ($isFilterApplied): ?>
            <div class="alert alert-warning m-t-20">No vaccination data found for selected filters.</div>
        <?php endif; ?>

    </div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    if (window.location.search === '') {
        document.getElementById("filterForm").submit();
    }
});
</script>