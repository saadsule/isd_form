<script src="https://code.highcharts.com/highcharts.js"></script>
<div class="page-container">
    <div class="main-content">
        <div class="page-header">
            <h2 class="header-title">ISD Dashboard</h2>
        </div>

        <!-- Top Cards -->
        <div class="row g-4 mb-4">
            <div class="col-md-3">
                <div class="card shadow-sm border-start border-4 border-primary p-3">
                    <h6>Total Child Health Forms</h6>
                    <h2><?= $child_total ?></h2>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm border-start border-4 border-success p-3">
                    <h6>Total OPD / MNCH Forms</h6>
                    <h2><?= $opd_total ?></h2>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm border-start border-4 border-warning p-3">
                    <h6>Total Patients</h6>
                    <h2><?= $patients ?></h2>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm border-start border-4 border-info p-3">
                    <h6>Gender Distribution</h6>
                    <h2>Male: <?= $gender['male'] ?> | Female: <?= $gender['female'] ?></h2>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="row mb-4">
            <div class="col-md-3">
                <label>UC Filter:</label>
                <select id="uc_filter" class="form-select" multiple>
                    <?php foreach($district as $d): ?>
                        <option value="<?= $d['uc_id'] ?>"><?= $d['uc_name'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3">
                <label>Date Range:</label>
                <input type="date" id="start_date" class="form-control" value="<?= date('Y-m-01') ?>">
                <input type="date" id="end_date" class="form-control mt-1" value="<?= date('Y-m-d') ?>">
            </div>
            <div class="col-md-3 d-flex align-items-end">
                <button class="btn btn-primary" id="filter_dashboard">Apply Filter</button>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="row">
            <div class="col-md-6 mb-4">
                <div id="chart_monthly" style="height:400px;"></div>
            </div>
            <div class="col-md-6 mb-4">
                <div id="chart_outreach_fixed" style="height:400px;"></div>
            </div>

            <div class="col-md-6 mb-4">
                <div id="chart_client_type" style="height:400px;"></div>
            </div>
            <div class="col-md-6 mb-4">
                <div id="chart_district" style="height:400px;"></div>
            </div>

            <!-- Additional charts can go here -->
        </div>

    </div>

<!-- Highcharts -->
<script src="https://code.highcharts.com/highcharts.js"></script>
<script>
    // Monthly comparison
    Highcharts.chart('chart_monthly', {
        chart: { type: 'column' },
        title: { text: 'Monthly Forms Comparison' },
        xAxis: { categories: <?= json_encode(array_column($monthly, 'month')) ?> },
        yAxis: { title: { text: 'Total Forms' } },
        series: [{
            name: 'Child Health',
            data: <?= json_encode(array_column($monthly, 'child_health')) ?>
        },{
            name: 'OPD / MNCH',
            data: <?= json_encode(array_column($monthly, 'opd_mnch')) ?>
        }]
    });

    // Outreach vs Fixed
    Highcharts.chart('chart_outreach_fixed', {
        chart: { type: 'column' },
        title: { text: 'Outreach vs Fixed Forms' },
        xAxis: { categories: <?= json_encode(array_column($district, 'uc_name')) ?> },
        yAxis: { title: { text: 'Total Forms' } },
        series: [{
            name: 'Fixed Site',
            data: <?= json_encode(array_column($district, 'fixed')) ?>
        },{
            name: 'Outreach',
            data: <?= json_encode(array_column($district, 'outreach')) ?>
        }]
    });

    // Client type distribution
    Highcharts.chart('chart_client_type', {
        chart: { type: 'pie' },
        title: { text: 'Client Type Distribution' },
        series: [{
            name: 'Forms',
            colorByPoint: true,
            data: <?= json_encode([
                ['name'=>'New', 'y'=>$client['new']],
                ['name'=>'Followup', 'y'=>$client['followup']]
            ]) ?>
        }]
    });

    // District-wise forms
    Highcharts.chart('chart_district', {
        chart: { type: 'bar' },
        title: { text: 'Forms by District / UC' },
        xAxis: { categories: <?= json_encode(array_column($district, 'uc_name')) ?> },
        yAxis: { title: { text: 'Total Forms' } },
        series: [{
            name: 'Child Health',
            data: <?= json_encode(array_column($district, 'child_health')) ?>
        },{
            name: 'OPD / MNCH',
            data: <?= json_encode(array_column($district, 'opd_mnch')) ?>
        }]
    });
</script>
