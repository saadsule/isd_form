<?php 
// Default start and end dates
$default_start = isset($filters['start']) ? $filters['start'] : '2025-12-01';
$default_end   = isset($filters['end']) ? $filters['end'] : date('Y-m-d');

?>
<div class="page-container">
    <div class="main-content">

        <div class="page-header">
            <h2 class="header-title">OPD/MNCH Health Dashboard</h2>
        </div>

        <!-- FILTERS -->
        <div class="card mb-3">
            <div class="card-body">
                <form id="filterForm" class="row g-3">

                    <!-- Date Range -->
                    <div class="col-md-4">
                        <label>Select Date Range *</label>
                        <div class="d-flex align-items-center m-b-15">
                            <input type="text" class="form-control datepicker-input" name="start" placeholder="From"
                                   autocomplete="off" value="<?= $default_start ?>" required>
                            <span class="p-h-10">to</span>
                            <input type="text" class="form-control datepicker-input" name="end" placeholder="To"
                                   autocomplete="off" value="<?= $default_end ?>" required>
                        </div>
                    </div>

                    <!-- UC Multiple Select -->
                    <div class="col-md-4">
                        <label>Select UC(s) *</label>
                        <select class="select2" name="uc[]" multiple="multiple" style="width:100%">
                            <?php
                            // Determine selected UCs: use $filters['uc'] if set, otherwise select all by default
                            $selected_ucs = isset($filters['uc']) 
                                ? (is_array($filters['uc']) ? $filters['uc'] : [$filters['uc']])
                                : array_map(function($u){ return $u->pk_id; }, $ucs); // select all by default

                            foreach($ucs as $u): ?>
                                <option value="<?= $u->pk_id ?>" <?= in_array($u->pk_id, $selected_ucs) ? 'selected' : '' ?>>
                                    <?= $u->uc_name ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <small class="text-danger error-message" data-for="uc[]"></small>
                    </div>

                    <!-- Visit Type -->
                    <div class="col-md-4">
                        <label>Visit Type *</label>
                        <?php
                        $visit_types = ['OPD', 'MNCH'];
                        $selected_visit_types = isset($filters['visit_type']) 
                            ? (is_array($filters['visit_type']) ? $filters['visit_type'] : [$filters['visit_type']])
                            : $visit_types; // select all by default
                        ?>
                        <select class="select2" name="visit_type[]" multiple="multiple" style="width:100%">
                            <?php foreach($visit_types as $vt): ?>
                                <option value="<?= $vt ?>" <?= in_array($vt, $selected_visit_types) ? 'selected' : '' ?>>
                                    <?= $vt ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <small class="text-danger error-message" data-for="visit_type[]"></small>
                    </div>

                    <!-- Buttons -->
                    <div class="col-md-12 mt-2 m-b-15 d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary me-2">Apply Filters</button>
                        <a href="<?= base_url('dashboard/opd_mnch_health') ?>" class="btn btn-secondary ml-2">Clear Filters</a>
                    </div>

                </form>
            </div>
        </div>

        <!-- Default Message -->
        <div class="alert alert-info text-center" id="defaultMessage">
            Please select filter(s) to view data.
        </div>

        <!-- SUMMARY CARDS -->
        <!-- SUMMARY CARDS -->
        <div class="row mb-3" id="summaryCards" style="display:none;">
            <div class="col-md-6 col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <div class="media align-items-center">
                            <div class="avatar avatar-icon avatar-lg avatar-blue">
                                <i class="anticon anticon-team"></i>
                            </div>
                            <div class="m-l-15">
                                <h2 class="m-b-0" id="catchmentPopulation">0</h2>
                                <p class="m-b-0 text-muted">Catchment Population</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <div class="media align-items-center">
                            <div class="avatar avatar-icon avatar-lg avatar-cyan">
                                <i class="anticon anticon-hdd"></i>
                            </div>
                            <div class="m-l-15">
                                <h2 class="m-b-0" id="totalUCs">0</h2>
                                <p class="m-b-0 text-muted"># of UCs</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <div class="media align-items-center">
                            <div class="avatar avatar-icon avatar-lg avatar-gold">
                                <i class="anticon anticon-calendar"></i>
                            </div>
                            <div class="m-l-15">
                                <h6 class="m-b-0" id="dateRange">-</h6>
                                <p class="m-b-0 text-muted">Date Range</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- CHARTS -->
        <div class="row" id="chartsSection" style="display:none;">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <div id="visitTypeChart" style="height:400px;"></div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <div id="clientTypeChart" style="height:400px;"></div>
                    </div>
                </div>
            </div>
            
            <!-- Disability -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <div id="disabilityChart" style="height:400px;"></div>
                    </div>
                </div>
            </div>

            <!-- Marital Status -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <div id="maritalChart" style="height:400px;"></div>
                    </div>
                </div>
            </div>

            <!-- Pregnancy Status -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <div id="pregnancyChart" style="height:400px;"></div>
                    </div>
                </div>
            </div>
             
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <div id="q181DoughnutChart" style="height:400px;"></div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div id="q182DoughnutChart" style="height:400px;"></div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div id="q184ColumnChart" style="height:400px;"></div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <!-- Sunburst Chart -->
                        <div id="sunburstChart" style="height: 350px;"></div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <!-- Sunburst Chart -->
                        <div id="sunburstChart19_1" style="height: 350px;"></div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div id="heatmapChart" style="height:400px;"></div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div id="heatmapTrimesterChart" style="height:400px;"></div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div id="genderAgeChart" style="height:400px;"></div>
                    </div>
                </div>
            </div>
            
        </div>

    </div>

<!-- Highcharts -->
<script src="<?= base_url('assets/highcharts/highcharts.js') ?>"></script>
<script src="<?= base_url('assets/highcharts/highcharts-more.js') ?>"></script>
<script src="<?= base_url('assets/highcharts/modules/exporting.js') ?>"></script>
<script src="<?= base_url('assets/highcharts/modules/heatmap.js') ?>"></script>
<script src="<?= base_url('assets/highcharts/modules/sunburst.js') ?>"></script>
<script src="<?= base_url('assets/highcharts/modules/exporting.js') ?>"></script>
<script src="<?= base_url('assets/highcharts/modules/pictorial.js') ?>"></script>
<script src="<?= base_url('assets/highcharts/modules/accessibility.js') ?>"></script>
<script src="<?= base_url('assets/highcharts/modules/drilldown.js') ?>"></script>
<script src="<?= base_url('assets/highcharts/modules/variable-pie.js') ?>"></script>
<!--<script src="<?= base_url('assets/highcharts/themes/adaptive.js') ?>"></script>-->

<script>
window.onload = function() {

    $('.select2').select2({width:'100%'});
    $('.datepicker-input').datepicker({format:'yyyy-mm-dd', autoclose:true});

    function loadDashboard(filters){

        $.ajax({
            url: "<?= base_url('dashboard/get_opd_mnch_health_ajax') ?>",
            type: "POST",
            data: filters,
            dataType: "json",
            success: function(response){

                $('#defaultMessage').hide();
                $('#summaryCards').show();
                $('#chartsSection').show();

                $('#catchmentPopulation').text(response.catchment_population);
                $('#totalUCs').text(response.total_ucs);
                $('#dateRange').text(response.date_range);

                Highcharts.chart('visitTypeChart', {
                    chart:{ type:'pie' },
                    title: { text: 'OPD / MNCH' },
                    series:[{
                        name:'Total',
                        colorByPoint:true,
                        data:[
                            { name:'OPD', y:parseInt(response.opd)||0 },
                            { name:'MNCH', y:parseInt(response.mnch)||0 }
                        ]
                    }]
                });

                Highcharts.chart('clientTypeChart', {
                    chart:{ type:'variablepie' },
                    title: { text: 'New Cases / Follow-up' },
                    
					 tooltip: {
						headerFormat: '',
						pointFormat: '<span style="color:{point.color}">\u25CF</span> <b> ' +
							'{point.name} : {point.y}</b><br/>' 
					},
					series:[{
						 minPointSize: 10,
						innerSize: '20%',
						zMin: 0,
						borderRadius: 5,
                        name:'Total',
                        colorByPoint:true,
                        data:[
                            { name:'New',z:80, y:parseInt(response.new_client)||0 },
                            { name:'Follow-up',z:120, y:parseInt(response.followup_client)||0 }
                        ],
						colors: [
							'#4caefe',
							'#23e274'
						]
                    }]
                });
                
                // Disability Chart
                Highcharts.chart('disabilityChart', {
                    chart: { type: 'variablepie' },
                    title: { text: 'Disability Status' },
                    tooltip: {
                        headerFormat: '',
                        pointFormat: '<span style="color:{point.color}">\u25CF</span> <b>{point.name}: {point.y}</b><br/>'
                    },
                    series: [{
                        minPointSize: 10,
                        innerSize: '20%',
                        zMin: 0,
                        borderRadius: 5,
                        name: 'Total',
                        colorByPoint: true,
                        data: [
                            { name: 'Yes', z: 80, y: parseInt(response.disability_yes) || 0 },
                            { name: 'No',  z: 120, y: parseInt(response.disability_no)  || 0 }
                        ],
                        colors: ['#f1c40f', '#95a5a6']
                    }]
                });

                // Marital Status Chart
                Highcharts.chart('maritalChart', {
                    chart: { type: 'variablepie' },
                    title: { text: 'Marital Status' },
                    tooltip: {
                        headerFormat: '',
                        pointFormat: '<span style="color:{point.color}">\u25CF</span> <b>{point.name}: {point.y}</b><br/>'
                    },
                    series: [{
                        minPointSize: 10,
                        innerSize: '20%',
                        zMin: 0,
                        borderRadius: 5,
                        name: 'Total',
                        colorByPoint: true,
                        data: [
                            { name: 'Married',   z: 80, y: parseInt(response.married)   || 0 },
                            { name: 'Unmarried', z: 120, y: parseInt(response.unmarried) || 0 }
                        ],
                        colors: ['#3498db', '#e67e22']
                    }]
                });

                // Pregnancy Status Chart
                Highcharts.chart('pregnancyChart', {
                    chart: { type: 'variablepie' },
                    title: { text: 'Pregnancy Status' },
                    tooltip: {
                        headerFormat: '',
                        pointFormat: '<span style="color:{point.color}">\u25CF</span> <b>{point.name}: {point.y}</b><br/>'
                    },
                    series: [{
                        minPointSize: 10,
                        innerSize: '20%',
                        zMin: 0,
                        borderRadius: 5,
                        name: 'Total',
                        colorByPoint: true,
                        data: [
                            { name: 'Pregnant',      z: 80, y: parseInt(response.pregnant)      || 0 },
                            { name: 'Non-Pregnant',  z: 120, y: parseInt(response.non_pregnant) || 0 }
                        ],
                        colors: ['#e74c3c', '#2ecc71']
                    }]
                });
                
                // Q18.1 Doughnut Chart
                var q18Data = response.q181 || { first_option:0, second_option:0, third_option:0 };

                Highcharts.chart('q181DoughnutChart', {
                    chart: { type: 'variablepie' },
                    title: { text: 'Antenatal Care Trimester' },
                    plotOptions: {
                        pie: { innerSize: '60%', dataLabels: { enabled: true } }
                    },
                    tooltip: {
                        headerFormat: '',
                        pointFormat: '<span style="color:{point.color}">\u25CF</span> <b>{point.name}</b> : <b>{point.y}</b><br/>'
                    },
                    series: [{
                        minPointSize: 10,
                        innerSize: '20%',
                        zMin: 0,
                        borderRadius: 5,
                        name: 'Responses',
                        colorByPoint: true,
                        data: [
                            { name: '1st', z: 100, y: Number(q18Data.first_option)  || 0, color:'#4caefe' },
                            { name: '2nd', z: 100, y: Number(q18Data.second_option) || 0, color:'#23e274' },
                            { name: '3rd', z: 100, y: Number(q18Data.third_option)  || 0, color:'#f1c40f' }
                        ]
                    }]
                });
                
                // Q18.2 Chart
                var q182Data = response.q182 || { first_option:0, second_option:0, third_option:0, fourth_option:0 };

                Highcharts.chart('q182DoughnutChart', {
                    chart: { type: 'variablepie' },
                    title: { text: 'Antenatal Care Visit' },
                    plotOptions: {
                        pie: { innerSize: '60%', dataLabels: { enabled: true } }
                    },
                    tooltip: {
                        headerFormat: '',
                        pointFormat: '<span style="color:{point.color}">\u25CF</span> <b>{point.name}</b> : <b>{point.y}</b><br/>'
                    },
                    series: [{
                        minPointSize: 10,
                        innerSize: '20%',
                        zMin: 0,
                        borderRadius: 5,
                        name: 'Responses',
                        colorByPoint: true,
                        data: [
                            { name: '1st', z: 100, y: Number(q182Data.first_option)  || 0, color:'#4caefe' },
                            { name: '2nd', z: 100, y: Number(q182Data.second_option) || 0, color:'#23e274' },
                            { name: '3rd', z: 100, y: Number(q182Data.third_option)  || 0, color:'#f1c40f' },
                            { name: '4th', z: 100, y: Number(q182Data.fourth_option) || 0, color:'#e74c3c' }
                        ]
                    }]
                });
                
                // Q18.4 Chart
                var q184Data = response.q184 || {}; // { 'Option A': 10, 'Option B': 5, ... }

                var categories = Object.keys(q184Data);         // Option labels
                var dataValues = Object.values(q184Data).map(Number); // Counts

                Highcharts.chart('q184ColumnChart', {
                    chart: { type: 'column' },
                    title: { text: 'What is complication' },
                    xAxis: {
                        categories: categories,
                        title: { text: '' },
                        labels: { rotation: -45, style: { fontSize: '12px' } } // rotate if long names
                    },
                    yAxis: {
                        min: 0,
                        title: { text: 'Number of Responses' },
                        allowDecimals: false
                    },
                    tooltip: {
                        headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                        pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                            '<td style="padding:0"><b>{point.y}</b></td></tr>',
                        footerFormat: '</table>',
                        shared: true,
                        useHTML: true
                    },
                    plotOptions: {
                        column: {
                            pointPadding: 0.2,
                            borderWidth: 0,
                            colorByPoint: true
                        }
                    },
                    series: [{
                        name: 'Responses',
                        data: dataValues,
                        colors: Highcharts.getOptions().colors // automatic colors
                    }]
                });
                
                // Set values in cards

                Highcharts.chart('sunburstChart', {
                    chart: { },
                    title: { text: 'Tetanus Vaccine Administered' }, // ← updated title
                    series: [{
                        type: 'sunburst',
                        data: response.sunburst,
                        allowDrillToNode: true,
                        cursor: 'pointer',
                        levels: [{
                            level: 1,
                            colorByPoint: true,
                            dataLabels: { rotationMode: 'parallel' }
                        }, {
                            level: 2,
                            colorVariation: { key: 'brightness', to: -0.2 }
                        }, {
                            level: 3,
                            colorVariation: { key: 'brightness', to: 0.2 }
                        }]
                    }],
                    tooltip: {
                        pointFormatter: function () {
                            // ← use yes_count + no_count as grand total
                            const total = response.yes_count + response.no_count;
                            const percentage = total ? ((this.value / total) * 100).toFixed(1) : 0;
                            return `<b>${this.name}</b><br>Count: ${this.value}<br>Percentage: ${percentage}%`;
                        }
                    }
                });
                
                Highcharts.chart('sunburstChart19_1', {
                    chart: { },
                    title: { text: 'TD Card / TT Schedule Overview' },
                    series: [{
                        type: 'sunburst',
                        data: response.sunburst19_1,
                        allowDrillToNode: true,
                        cursor: 'pointer',
                        levels: [{
                            level: 1,
                            colorByPoint: true,
                            dataLabels: { rotationMode: 'parallel' }
                        }, {
                            level: 2,
                            colorVariation: { key: 'brightness', to: -0.2 }
                        }, {
                            level: 3,
                            colorVariation: { key: 'brightness', to: 0.2 }
                        }]
                    }],
                    tooltip: {
                        pointFormatter: function () {
                            const total = response.total_records19_1;
                            const percentage = total ? ((this.value / total) * 100).toFixed(1) : 0;
                            return `<b>${this.name}</b><br>Count: ${this.value}<br>Percentage: ${percentage}%`;
                        }
                    }
                });
                
                Highcharts.chart('heatmapChart', {
                    chart: {
                        type: 'heatmap'
                    },
                    title: {
                        text: 'Diagnosis w.r.t. Age Group'
                    },
                    xAxis: {
                        categories: response.heatmap.categoriesX,
                        labels: {
                            rotation: -60,
                            style: { fontSize: '11px' }
                        },
                        title: {
                            text: 'Diagnosis'
                        }
                    },
                    yAxis: {
                        categories: response.heatmap.categoriesY,
                        title: {
                            text: 'Age Group'
                        }
                    },
                    colorAxis: {
                        min: 0,
                        stops: [
                            [0,   '#E3F2FD'],
                            [0.5, '#42A5F5'],
                            [1,   '#0D47A1']
                        ]
                    },
                    legend: {
                        align: 'right',
                        layout: 'vertical',
                        verticalAlign: 'top'
                    },
                    series: [{
                        borderWidth: 1,
                        data: response.heatmap.data,
                        dataLabels: {
                            enabled: true,
                            color: '#000'
                        }
                    }],
                    tooltip: {
                        formatter: function () {
                            const diagnosis = this.series.xAxis.categories[this.point.x];
                            const ageGroup  = this.series.yAxis.categories[this.point.y];
                            const total     = this.point.value;
                            return `<b>${ageGroup}</b><br/>${diagnosis}<br/>Total: <b>${total}</b>`;
                        }
                    }
                });
                
                Highcharts.chart('heatmapTrimesterChart', {
                    chart: {
                        type: 'heatmap'
                    },
                    title: {
                        text: 'Complications w.r.t. Trimester'
                    },
                    xAxis: {
                        categories: response.heatmap_trimester.categoriesX,
                        labels: {
                            rotation: -60,
                            style: { fontSize: '11px' }
                        },
                        title: {
                            text: 'Complication'
                        }
                    },
                    yAxis: {
                        categories: response.heatmap_trimester.categoriesY,
                        title: {
                            text: 'Trimester'
                        }
                    },
                    colorAxis: {
                        min: 0,
                        stops: [
                            [0,   '#E3F2FD'],
                            [0.5, '#42A5F5'],
                            [1,   '#0D47A1']
                        ]
                    },
                    legend: {
                        align: 'right',
                        layout: 'vertical',
                        verticalAlign: 'top'
                    },
                    series: [{
                        borderWidth: 1,
                        data: response.heatmap_trimester.data,
                        dataLabels: {
                            enabled: true,
                            color: '#000'
                        }
                    }],
                    tooltip: {
                        formatter: function () {
                            const complication = this.series.xAxis.categories[this.point.x];
                            const trimester    = this.series.yAxis.categories[this.point.y];
                            const total        = this.point.value;
                            return '<b>' + trimester + '</b><br/>' + complication + '<br/>Total: <b>' + total + '</b>';
                        }
                    }
                });
                
                var ageGroups = response.gender_age.age_groups;
var ageData   = response.gender_age.data;

var series = [];
ageData.forEach(function(val, i) {
    series.push({
        name: ageGroups[i],
        data: [val]
    });
});

Highcharts.chart('genderAgeChart', {
    chart: {
        type: 'pictorial',
        backgroundColor: 'transparent',
    },
    title: {
        text: 'Age Group Breakdown'
    },
    xAxis: {
        categories: ['Age Groups'],
        lineWidth: 0,
        opposite: true
    },
    yAxis: {
        visible: false
    },
    legend: {
        layout: 'vertical',
        verticalAlign: 'middle',
        align: 'right'
    },
    tooltip: {
        headerFormat: '',
        pointFormat: '<b>{series.name}</b><br/>Count: {point.y}'
    },
    plotOptions: {
        series: {
            stacking: 'percent',
            colorByPoint: false,
            borderWidth: 0,
            pointPadding: 0,
            groupPadding: 0,
            dataLabels: {
                enabled: true,
                format: '{series.name}: {point.percentage:.0f}%',
                style: {
                    textOutline: 'none',
                    fontWeight: 'bold'
                }
            },
            paths: [{
                definition: 'm 656.59433,740.097 c -0.634,-1.435 -13.566,' +
                    '-15.425 -33.487,-23.292 -4.568,-1.94 -4.545,2.705 ' +
                    '-16.944,-34.925 -26.957,-72.647 -5.661,-112.736 -51.135,' +
                    '-200.791 -6.888,-14.322 -9.901,-24.921 -16.16,-50.12 ' +
                    '-25.397,-104.478 -6.032,-90.98 -15.87,-135.251 -17.961,' +
                    '-63.049 -50.754,-59.498 -71.782,-59.155 -16.944,0.378 ' +
                    '-45.224,-11.699 -52.936,-19.746 -10.555,-11.486 -17.912,' +
                    '-20.548 -11.679,-58.855 0,0 7.037,-12.141 9.078,-34.125 ' +
                    '9.284,11.287 24.572,-33.84 16.065,-42.691 -1.745,-1.867 ' +
                    '-5.169,-1.236 -6.289,1.015 -1.292,1.484 -1.315,3.695 ' +
                    '-2.888,4.964 -2,-9.359 3.289,-28.498 -7.935,-56.968 ' +
                    '-5.541,-12.289 -11.235,-15.496 -21.547,-22.44 -8.401,' +
                    '-6.048 -28.842,-7.595 -29.842,-7.717 h -9.461 c -1,' +
                    '0.122 -21.441,1.669 -29.842,7.717 -10.312,6.944 -16.006,' +
                    '10.151 -21.547,22.44 -11.224,28.47 -5.935,47.609 -7.935,' +
                    '56.968 -1.573,-1.269 -1.596,-3.48 -2.888,-4.964 -1.12,' +
                    '-2.251 -4.544,-2.882 -6.289,-1.015 -8.507,8.851 6.781,' +
                    '53.978 16.065,42.691 2.041,21.984 9.078,34.125 9.078,' +
                    '34.125 6.233,38.307 -1.124,47.369 -11.679,58.855 -7.712,' +
                    '8.047 -35.992,20.124 -52.935,19.746 -21.029,-0.343 ' +
                    '-53.822,-3.894 -71.782,59.155 -9.838,44.271 9.527,' +
                    '30.773 -15.87,135.251 -6.259,25.199 -9.272,35.798 ' +
                    '-16.16,50.12 -45.474004,88.055 -24.178004,128.144 ' +
                    '-51.135004,200.791 -12.399,37.63 -12.376,32.985 -16.944,' +
                    '34.925 -19.921,7.867 -32.853,21.857 -33.487,23.292 ' +
                    '-8.923,20.454 -23.3280004,27.412 -19.92100038,33.844 ' +
                    '0.89599998,1.702 3.31799998,2.588 4.94399998,1.381 ' +
                    '5.1890004,0.91 12.7380004,-4.808 16.1270004,-8.599 ' +
                    '4.102,-4.706 3.375,-7.457 11.332,-13.86 1.824,2.047 ' +
                    '-2.155,20.335 -3.12,23.398 -4.877,14.729 -26.5670004,' +
                    '49.619 -17.595,54.417 0.945,0.4 2.227,0.955 3.073,0.089 ' +
                    '1.553,-1.53 3.53,-2.604 4.841,-4.372 8.025,-10.218 ' +
                    '17.566,-34.36 24.059,-39.238 3.279,0.224 1.596,2.346 ' +
                    '-4.475,22.532 -3.673,13.084 -5.142,19.941 -5.142,19.941 ' +
                    '-10.126,30.466 6.229,25.716 11.501,6.808 0.448,-1.537 ' +
                    '9.722,-26.912 10.129,-28.16 1.241,-3.291 4.602,-17.806 ' +
                    '8.801,-14.872 0.646,2.469 -0.335,3.044 -3.536,31.521 ' +
                    '-2.6,21.813 -3.236,8.789 -2.713,26.425 0.079,2.164 ' +
                    '4.439,3.257 6.282,2.115 10.539,-9.723 12.692,-57.611 ' +
                    '18.074,-61.022 3.669,4.293 4.272,33.754 5.982,39.221 ' +
                    '2.652,9.705 7.446,4.802 7.981,3.239 3.825004,-9.324 ' +
                    '-0.19,-30.536 0.628,-45.388 0,0 4.369004,-14.53 ' +
                    '7.198004,-38.676 4.176,-45.514 -17.861004,13.267 48.59,' +
                    '-167.185 0,0 5.299,-10.218 13.794,-30.791 9.81,-21.31 ' +
                    '5.988,-35.652 19.766,-73.451 0.361,-1 16.239,-47.758 ' +
                    '24.363,-68.15 45.673,232.645 -9.743,77.068 -28.904,' +
                    '331.531 -5.708,105.042 1.862,76.707 18.19,223.544 ' +
                    '31.719,289.304 -15.087,130.161 35.652,384.312 10.99,' +
                    '51.495 9.837,44.86 11.854,56.284 2.28,21.363 -1.788,' +
                    '21.528 -1.679,31.313 -0.699,24.031 5.964,8.574 -1.712,' +
                    '52.53 -4.993,24.181 -4.913,9.214 -7.677,37.417 -3.463,' +
                    '13.977 -13.912,52.732 0.856,52.45 1.286,7.64 5.541,' +
                    '9.156 9.756,6.712 -0.684,2.455 1.381,4.293 2.766,6.011 ' +
                    '4.813,1.322 4.76,1.029 6.828,-0.555 1.495,5.791 5.173,' +
                    '5.742 6.748,6.16 4.768,1.476 5.904,-11.237 6.781,-16.16 ' +
                    '0.856,-0.046 1.705,-0.096 2.551,-0.129 -1.072,3.151 ' +
                    '-7.161,15.833 2.634,16.835 7.651,1.238 8.542,0.168 ' +
                    '12.727,-3.791 6.992,-7.01 5.41,-8.94 6.623,-20.685 ' +
                    '0.191,-2.384 5.685,-6.58 0.872,-37.642 -1.855,-15.952 ' +
                    '-0.832,2.69 0.304,-35.715 0.371,-16.594 5.685,-19.576 ' +
                    '6.408,-31.349 -6.493,-27.396 -1.465,-14.55 -4.045,' +
                    '-30.51 -6.145,-34.313 -7.105,-27.255 0.575,-107.316 ' +
                    '6.987,-65.839 14.147,-68.677 7.72,-136.864 -14.296,' +
                    '-110.15 -0.224,-68.945 1.451,-126.216 1.503,-67.36 ' +
                    '-4.198,-108.808 3.103,-168.203 4.314,-34.735 12.351,' +
                    '-68.835 12.215,-90.227 2.948,-3.639 4.984,-7.885 7.168,' +
                    '-11.993 3.172,-6.203 2.655,-0.513 2.627,-35.675 1.424,' +
                    '-0.218 2.885,-0.281 4.27,-0.677 0.162,-0.334 0.307,' +
                    '-0.661 0.436,-0.985 0.007,0.007 0.014,0.015 0.022,0.023 ' +
                    '0.008,-0.008 0.015,-0.016 0.022,-0.023 0.129,0.324 ' +
                    '0.274,0.651 0.436,0.985 1.385,0.396 2.846,0.459 4.27,' +
                    '0.677 -0.028,35.162 -0.545,29.472 2.627,35.675 2.184,' +
                    '4.108 4.22,8.354 7.168,11.993 -0.136,21.392 7.901,' +
                    '55.493 12.215,90.227 7.301,59.394 1.6,100.842 3.103,' +
                    '168.203 1.675,57.27 15.747,16.066 1.451,126.216 -6.427,' +
                    '68.186 0.733,71.025 7.72,136.864 7.68,80.061 6.72,' +
                    '73.003 0.575,107.316 -2.58,15.96 2.448,3.114 -4.045,' +
                    '30.51 0.723,11.773 6.037,14.755 6.408,31.349 1.136,' +
                    '38.405 2.159,19.763 0.304,35.715 -4.813,31.062 0.681,' +
                    '35.258 0.872,37.642 1.213,11.745 -0.369,13.675 6.623,' +
                    '20.685 4.185,3.959 5.076,5.029 12.727,3.791 9.795,' +
                    '-1.002 3.706,-13.684 2.634,-16.835 0.846,0.033 1.695,' +
                    '0.083 2.551,0.129 0.877,4.923 2.013,17.636 6.781,16.16 ' +
                    '1.575,-0.418 5.253,-0.369 6.748,-6.16 2.068,1.584 2.015,' +
                    '1.877 6.828,0.555 1.385,-1.718 3.45,-3.556 2.766,-6.011 ' +
                    '4.215,2.444 8.47,0.928 9.756,-6.712 14.768,0.282 4.319,' +
                    '-38.473 0.856,-52.45 -2.764,-28.203 -2.684,-13.236 ' +
                    '-7.677,-37.417 -7.676,-43.956 -1.013,-28.499 -1.712,' +
                    '-52.53 0.109,-9.785 -3.959,-9.95 -1.679,-31.313 2.017,' +
                    '-11.424 0.864,-4.789 11.854,-56.284 50.739,-254.151 ' +
                    '3.933,-95.007 35.652,-384.312 16.328,-146.837 23.898,' +
                    '-118.502 18.19,-223.544 -19.161,-254.463 -74.576,' +
                    '-98.886 -28.904,-331.531 8.124,20.392 24.002,67.15 ' +
                    '24.363,68.15 13.778,37.8 9.956,52.142 19.766,73.451 ' +
                    '8.495,20.573 13.794,30.791 13.794,30.791 66.451,180.451 ' +
                    '44.414,121.671 48.59,167.185 2.829,24.146 7.198,38.676 ' +
                    '7.198,38.676 0.818,14.852 -3.197,36.064 0.628,45.388 ' +
                    '0.535,1.563 5.329,6.466 7.981,-3.239 1.71,-5.467 2.313,' +
                    '-34.928 5.982,-39.221 5.382,3.411 7.535,51.3 18.074,' +
                    '61.022 1.843,1.142 6.203,0.049 6.282,-2.115 0.523,' +
                    '-17.636 -0.113,-4.612 -2.713,-26.425 -3.201,-28.477 ' +
                    '-4.182,-29.052 -3.536,-31.521 4.199,-2.934 7.56,11.581 ' +
                    '8.801,14.872 0.407,1.248 9.681,26.623 10.129,28.16 ' +
                    '5.272,18.908 21.627,23.658 11.501,-6.808 0,0 -1.469,' +
                    '-6.857 -5.142,-19.941 -6.071,-20.186 -7.754,-22.308 ' +
                    '-4.475,-22.532 6.493,4.878 16.034,29.02 24.059,39.238 ' +
                    '1.311,1.768 3.288,2.842 4.841,4.372 0.846,0.866 2.128,' +
                    '0.311 3.073,-0.089 8.972,-4.798 -12.718,-39.688 -17.595,' +
                    '-54.417 -0.965,-3.063 -4.944,-21.351 -3.12,-23.398 ' +
                    '7.957,6.403 7.23,9.154 11.332,13.86 3.389,3.791 10.938,' +
                    '9.509 16.127,8.599 1.626,1.207 4.048,0.321 4.944,-1.381 ' +
                    '3.403,-6.432 -11.002,-13.39 -19.925,-33.844 z'
            }]
        }
    },
    series: series
});
                
            }
        });
    }

    // Function to build filters from form
    function getFilters() {
        return {
            uc: $('[name="uc[]"]').val(),
            start: $('[name="start"]').val(),
            end: $('[name="end"]').val(),
            visit_type: $('[name="visit_type[]"]').val()
        };
    }

    // Load dashboard for first time automatically
    loadDashboard(getFilters());

    // APPLY FILTER BUTTON
    $('#filterForm').on('submit', function(e){
        e.preventDefault();

        let requiredFields = ["uc[]", "visit_type[]"];
        let valid = true;

        // Clear old errors and borders
        $(".error-message").html("");
        $(".select2-container").css("border", "");

        requiredFields.forEach(function(name){
            let select = $('[name="'+name+'"]')[0];
            let errorBox = $('[data-for="'+name+'"]');

            if (!select || select.selectedOptions.length === 0){
                valid = false;

                // Add red border for Select2
                let select2Box = $(select).next(".select2-container");
                if (select2Box.length) {
                    select2Box.css({
                        "border": "1px solid red",
                        "border-radius": "4px"
                    });
                }

                // Show error message
                if (errorBox.length) {
                    errorBox.html("This field is required.");
                }
            }
        });

        if (!valid) return false; // stop submission if invalid

        // Build filters object
        var filters = {
            uc: $('[name="uc[]"]').val(),
            start: $('[name="start"]').val(),
            end: $('[name="end"]').val(),
            visit_type: $('[name="visit_type[]"]').val()
        };

        // Optional: check if all filters empty
        if(!filters.uc && !filters.start && !filters.end && !filters.visit_type){
            alert("Please select at least one filter.");
            return;
        }

        // Load dashboard
        loadDashboard(filters);
    });

};
</script>