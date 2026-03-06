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