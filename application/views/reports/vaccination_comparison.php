<div class="page-container">
    <div class="main-content">
        <div class="page-header">
            <h2 class="header-title">Vaccination — Follow-up Retention Report</h2>
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
            <div class="card-header" style="background: linear-gradient(to right, #fff8e8, #ffffff); border-left: 4px solid #ffc107;">
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
                                <?php foreach ($months as $i => $m):
                                    $d = isset($monthData[$m])
                                        ? $monthData[$m]
                                        : array('retained'=>0,'percent'=>0,'total'=>0);
                                    $color = $d['percent'] >= 75 ? '#28a745' : ($d['percent'] >= 40 ? '#ffc107' : '#dc3545');
                                ?>
                                    <td class="text-center">
                                        <?php if ($i == 0): ?>
                                            <!-- Base month — show all children of that month -->
                                            <a href="<?= base_url('reports/vaccination_detail?uc_name='.urlencode($uc_name).'&month='.$m) ?>"
                                               target="_blank"
                                               style="font-size:18px; font-weight:bold; color:#007bff; text-decoration:none; border-bottom: 2px dashed #007bff;">
                                                <?= $d['total'] ?>
                                            </a>
                                            <br><small class="text-muted">children</small>
                                        <?php else: ?>
                                            <!-- Retained month — pass base_month so detail page knows to filter by intersection -->
                                            <div style="display:flex; flex-direction:column; align-items:center; gap:4px;">
                                                <a href="<?= base_url('reports/vaccination_detail?uc_name='.urlencode($uc_name).'&month='.$m.'&base_month='.$months[0]) ?>"
                                                   target="_blank"
                                                   style="font-size:16px; font-weight:bold; color:#007bff; text-decoration:none; border-bottom: 2px dashed #007bff;">
                                                    <?= $d['retained'] ?>
                                                </a>
                                                <div style="background-color:<?= $color ?>; color:#fff; border-radius:20px; padding:2px 10px; font-size:12px; font-weight:bold; min-width:52px; text-align:center;">
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