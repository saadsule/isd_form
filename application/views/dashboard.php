<script src="<?= base_url('assets/highcharts/highcharts.js') ?>"></script>
<div class="page-container">
    <div class="main-content">

        <div class="page-header">
            <h2 class="header-title">Health Dashboard</h2>
        </div>

        <!-- KPI -->
        <div class="row mb-4">

            <!-- Child Records -->
            <div class="col-md-6 col-lg-3 d-flex">
                <div class="card flex-fill">
                    <div class="card-body">
                        <div class="media align-items-center">
                            <div class="avatar avatar-icon avatar-lg avatar-blue">
                                <i class="anticon anticon-profile"></i>
                            </div>
                            <div class="m-l-15">
                                <h6 class="m-b-0 text-muted">Child Records</h6>
                                <h2 class="m-b-0"><?= $child_total ?></h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- OPD/MNCH Records -->
            <div class="col-md-6 col-lg-3 d-flex">
                <div class="card flex-fill">
                    <div class="card-body">
                        <div class="media align-items-center">
                            <div class="avatar avatar-icon avatar-lg avatar-cyan">
                                <i class="anticon anticon-file"></i>
                            </div>
                            <div class="m-l-15">
                                <h6 class="m-b-0 text-muted">OPD/MNCH Records</h6>
                                <h2 class="m-b-0"><?= $opd_total ?></h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Patients -->
            <div class="col-md-6 col-lg-3 d-flex">
                <div class="card flex-fill">
                    <div class="card-body">
                        <div class="media align-items-center">
                            <div class="avatar avatar-icon avatar-lg avatar-gold">
                                <i class="anticon anticon-user"></i>
                            </div>
                            <div class="m-l-15">
                                <h6 class="m-b-0 text-muted">Total Patients</h6>
                                <h2 class="m-b-0"><?= $patients ?></h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Today's Visits -->
            <div class="col-md-6 col-lg-3 d-flex">
                <div class="card flex-fill">
                    <div class="card-body">
                        <div class="media align-items-center">
                            <div class="avatar avatar-icon avatar-lg avatar-purple">
                                <i class="anticon anticon-calendar"></i>
                            </div>
                            <div class="m-l-15">
                                <h6 class="m-b-0 text-muted">Today's Visits</h6>
                                <h2 class="m-b-0">15</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>


        <!-- CHARTS -->
        <div class="row">

            <div class="col-md-6">
                <div class="card chart-card shadow-sm h-100">
                    <div class="card-body">
                        <div id="genderChart"></div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card chart-card shadow-sm h-100">
                    <div class="card-body">
                        <div id="clientChart"></div>
                    </div>
                </div>
            </div>

        </div>

        <div class="row">

            <div class="col-md-6">
                <div class="card chart-card shadow-sm h-100">
                    <div class="card-body">
                        <div id="monthlyChart"></div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card chart-card shadow-sm h-100">
                    <div class="card-body">
                        <div id="districtChart"></div>
                    </div>
                </div>
            </div>

        </div>

        <div class="card chart-card shadow-sm mt-4">
            <div class="card-body">
                <div id="diagnosisChart"></div>
            </div>
        </div>

    </div>

<script>
/* GENDER */
Highcharts.chart('genderChart',{
    chart:{type:'pie'},
    title:{text:'Gender Distribution'},
    series:[{
        data:[
        <?php foreach($gender as $g): ?>
            {name:'<?= $g->gender ?>',y:<?= $g->total ?>},
        <?php endforeach; ?>
        ]
    }]
});

/* CLIENT */
Highcharts.chart('clientChart',{
    chart:{type:'pie'},
    title:{text:'Client Type'},
    series:[{
        data:[
        <?php foreach($client as $c): ?>
            {name:'<?= $c->client_type ?>',y:<?= $c->total ?>},
        <?php endforeach; ?>
        ]
    }]
});

/* MONTHLY */
Highcharts.chart('monthlyChart',{
    chart:{type:'line'},
    title:{text:'Monthly Comparison'},
    xAxis:{
        categories:[
        <?php foreach($monthly as $m): ?>
            '<?= $m->month ?>',
        <?php endforeach; ?>
        ]
    },
    series:[{
        name:'Child',
        data:[
        <?php foreach($monthly as $m): ?>
            <?= $m->child ?>,
        <?php endforeach; ?>
        ]
    },{
        name:'OPD/MNCH',
        data:[
        <?php foreach($monthly as $m): ?>
            <?= $m->opd ?>,
        <?php endforeach; ?>
        ]
    }]
});

/* DISTRICT */
Highcharts.chart('districtChart',{
    chart:{type:'column'},
    title:{text:'Top Districts'},
    xAxis:{
        categories:[
        <?php foreach($district as $d): ?>
            '<?= $d->district ?>',
        <?php endforeach; ?>
        ]
    },
    series:[{
        name:'Child',
        data:[
        <?php foreach($district as $d): ?>
            <?= $d->child ?>,
        <?php endforeach; ?>
        ]
    },{
        name:'OPD',
        data:[
        <?php foreach($district as $d): ?>
            <?= $d->opd ?>,
        <?php endforeach; ?>
        ]
    }]
});

/* DIAGNOSIS */
Highcharts.chart('diagnosisChart',{
    chart:{type:'bar'},
    title:{text:'Top Diagnoses'},
    xAxis:{
        categories:[
        <?php foreach($diagnosis as $d): ?>
            '<?= $d->answer ?>',
        <?php endforeach; ?>
        ]
    },
    series:[{
        name:'Cases',
        data:[
        <?php foreach($diagnosis as $d): ?>
            <?= $d->total ?>,
        <?php endforeach; ?>
        ]
    }]
});
</script>
