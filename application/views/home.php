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
                            <h6 class="text-uppercase fw-bold mb-2">Total Forms</h6>
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
        <div class="row mt-4">
            <div class="col-md-12">
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
            </div>
        </div>

    </div>

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
