<style>
.hover-shadow {
    transition: all 0.3s ease;
}
.hover-shadow:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 18px rgba(0,0,0,0.15);
}
.icon-holder {
    flex-shrink: 0;
}
</style>
<div class="page-container">
    <div class="main-content">
        <div class="page-header mb-4">
            <h2 class="header-title">Welcome to ISD Dashboard</h2>
        </div>

        <div class="row g-4">

            <!-- Total Forms -->
            <div class="col-md-4">
                <div class="card shadow-sm border-start border-4 border-primary hover-shadow">
                    <div class="card-body d-flex align-items-center">
                        <div class="icon-holder bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width:60px; height:60px; font-size:26px;">
                            <i class="anticon anticon-file-text"></i>
                        </div>
                        <div class="flex-grow-1 ml-2">
                            <h6 class="text-uppercase fw-bold mb-2">Total Forms Digitized</h6>
                            <h2 class="fw-bold text-end mb-0"><?= $total_forms ?></h2>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Child Health Forms -->
            <div class="col-md-4">
                <div class="card shadow-sm border-start border-4 border-success hover-shadow">
                    <div class="card-body d-flex align-items-center">
                        <div class="icon-holder bg-success text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width:60px; height:60px; font-size:26px;">
                            <i class="anticon anticon-user"></i>
                        </div>
                        <div class="flex-grow-1 ml-2">
                            <h6 class="text-uppercase fw-bold mb-2">Child Health Forms</h6>
                            <h2 class="fw-bold text-end mb-0"><?= $child_health_total ?></h2>
                        </div>
                    </div>
                </div>
            </div>

            <!-- OPD / MNCH Forms -->
            <div class="col-md-4">
                <div class="card shadow-sm border-start border-4 border-warning hover-shadow">
                    <div class="card-body d-flex align-items-center">
                        <div class="icon-holder bg-warning text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width:60px; height:60px; font-size:26px;">
                            <i class="anticon anticon-team"></i>
                        </div>
                        <div class="flex-grow-1 ml-2">
                            <h6 class="text-uppercase fw-bold mb-2">OPD / MNCH Forms</h6>
                            <h2 class="fw-bold text-end mb-0"><?= $opd_total ?></h2>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Today's Progress -->
        <div class="row mt-2">
            <!-- Today's Progress -->
            <div class="col-md-6">
            <!-- Today's Progress -->
            <div class="card shadow-sm border-start border-4 border-info hover-shadow">
                <div class="card-body d-flex align-items-center justify-content-center gap-4">
                    <div class="icon-holder bg-info text-white rounded-circle d-flex align-items-center justify-content-center" style="width:70px; height:70px; font-size:30px;">
                        <i class="anticon anticon-calendar"></i>
                    </div>
                    <div class="text-center">
                        <h6 class="text-uppercase fw-bold mb-2">Today's Progress</h6>
                        <h2 class="fw-bold mb-1"><?= $today_total ?></h2>
                        <small class="text-muted">Forms entered today</small>
                    </div>
                </div>
            </div>

            <!-- Total Parcel Forms -->
            <div class="card shadow-sm border-start border-4 border-success hover-shadow mt-3">
                <div class="card-body d-flex align-items-center justify-content-center gap-4">
                    <div class="icon-holder bg-success text-white rounded-circle d-flex align-items-center justify-content-center" style="width:70px; height:70px; font-size:30px;">
                        <i class="anticon anticon-file-text"></i> <!-- Changed icon -->
                    </div>
                    <div class="text-center">
                        <h6 class="text-uppercase fw-bold mb-2">Total Hardcopies Received</h6>
                        <h2 class="fw-bold mb-1"><?= $total_parcel_forms ?></h2>
                    </div>
                </div>
            </div>
        </div>

        <!-- Submission Progress -->
        <div class="col-md-6">
            <?php 
                $progress_percent = $total_parcel_forms > 0 ? round(($total_forms / $total_parcel_forms) * 100) : 0; 
            ?>
            <div class="card shadow-sm border-start border-4 border-success hover-shadow">
                <div class="card-body d-flex flex-column align-items-center justify-content-center" style="min-height:230px;">
                    <!-- Heading Centered -->
                    <h5 class="text-center mb-3">Overall Progress</h5>

                    <!-- Chart and Number -->
                    <div class="position-relative" style="width:150px; height:150px;">
                        <!-- Canvas -->
                        <canvas class="chart m-h-auto" id="overall-progress-chart" width="150" height="150" style="display:block; width:150px; height:150px;"></canvas>

                        <!-- Center Number -->
                        <h2 class="text-center text-large m-0 text-success font-weight-normal" style="position:absolute; top:50%; left:50%; transform:translate(-50%, -50%);">
                            <?= $total_forms.'/'.$total_parcel_forms ?>
                        </h2>
                    </div>

                    <!-- Progress % -->
                    <div class="d-flex justify-content-center align-items-center mt-3">
                        <span class="badge badge-success badge-dot m-r-10"></span>
                        <span><span class="font-weight-semibold"><?= $progress_percent ?>%</span> of Your Goal</span>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>    
<script src="<?= base_url('assets/vendors/chartjs/Chart.min.js') ?>"></script>
<script src="<?= base_url('assets/js/pages/dashboard-crm.js') ?>"></script>
<script>
    // Dynamic progress for Overall Progress
    const ctx = document.getElementById('overall-progress-chart').getContext('2d');
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            datasets: [{
                data: [<?= $progress_percent ?>, <?= 100 - $progress_percent ?>],
                backgroundColor: ['#05C9A7', '#e9ecef'], // primary color + light gray
                hoverBackgroundColor: ['#05C9A7', '#e9ecef'],
                borderWidth: 0
            }]
        },
        options: {
                cutoutPercentage: 92,
                responsive: false,
                maintainAspectRatio: false,
                tooltips: { enabled: false },
                legend: { display: false },
                scales: {
                    xAxes: [{ display: false }],
                    yAxes: [{ display: false }]
                }
            }
    });
</script>
