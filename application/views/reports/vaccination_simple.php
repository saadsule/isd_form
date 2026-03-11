<div class="page-container">
    <div class="main-content">
        <div class="page-header">
            <h2 class="header-title">Vaccination — Report</h2>
        </div>

        <div class="card">
            <div class="card-body">
                <form id="filterForm" method="GET">
                    <div class="row">
                        <div class="col-md-3">
                            <label>From Month *</label>
                            <div class="m-b-15">
                                <input type="text" class="form-control datepicker-month"
                                       name="from_month" placeholder="YYYY-MM" autocomplete="off"
                                       value="<?= isset($filters['from_month']) ? $filters['from_month'] : '' ?>" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label>To Month *</label>
                            <div class="m-b-15">
                                <input type="text" class="form-control datepicker-month"
                                       name="to_month" placeholder="YYYY-MM" autocomplete="off"
                                       value="<?= isset($filters['to_month']) ? $filters['to_month'] : '' ?>" required>
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

        <div class="card m-t-20">
            <div class="card-header" style="background: linear-gradient(to right, #e8f4fd, #ffffff); border-left: 4px solid #007bff;">
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
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $grand_totals = array_fill_keys($months, 0);
                            foreach ($simple_data as $uc_name => $monthTotals):
                            ?>
                            <tr>
                                <td class="font-weight-bold"><?= $uc_name ?></td>
                                <?php foreach ($months as $m):
                                    $val = isset($monthTotals[$m]) ? $monthTotals[$m] : 0;
                                    $grand_totals[$m] += $val;
                                ?>
                                    <td class="text-center">
                                        <?php if ($val > 0): ?>
                                            <a href="<?= base_url('reports/vaccination_detail?uc_name='.urlencode($uc_name).'&month='.$m) ?>"
                                                target="_blank"
                                                style="font-size:15px; font-weight:bold; color:#007bff; text-decoration:none; border-bottom: 2px dashed #007bff;">
                                                 <?= $val ?>
                                             </a>
                                        <?php else: ?>
                                            <span class="text-muted">—</span>
                                        <?php endif; ?>
                                    </td>
                                <?php endforeach; ?>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr style="background-color:#f0f4ff;">
                                <td class="font-weight-bold">Total</td>
                                <?php foreach ($months as $m): ?>
                                    <td class="text-center">
                                        <span style="font-size:15px; font-weight:bold; color:#343a40;"><?= $grand_totals[$m] ?></span>
                                    </td>
                                <?php endforeach; ?>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <?php elseif ($isFilterApplied): ?>
            <div class="alert alert-warning m-t-20">No vaccination data found for selected filters.</div>
        <?php endif; ?>

    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    $('.datepicker-month').datepicker({
        format: 'yyyy-mm',
        startView: 'months',
        minViewMode: 'months',
        autoclose: true
    });
    if (window.location.search === '') {
        document.getElementById("filterForm").submit();
    }
});
</script>