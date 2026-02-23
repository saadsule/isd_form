<div class="page-container">
    <div class="main-content">

        <div class="page-header">
            <h2 class="header-title">Child Health Dashboard</h2>
        </div>

        <!-- FILTERS -->
        <div class="card mb-3">
            <div class="card-body">
                <form id="filterForm" class="row g-3">

                    <!-- Date Range -->
                    <div class="col-md-4">
                        <label>Select Date Range</label>
                        <div class="d-flex align-items-center m-b-15">
                            <input type="text" class="form-control datepicker-input" name="start" placeholder="From"
                                   autocomplete="off"
                                   value="<?= isset($filters['start']) ? $filters['start'] : '' ?>">
                            <span class="p-h-10">to</span>
                            <input type="text" class="form-control datepicker-input" name="end" placeholder="To"
                                   autocomplete="off"
                                   value="<?= isset($filters['end']) ? $filters['end'] : '' ?>">
                        </div>
                    </div>

                    <!-- UC Multiple Select -->
                    <div class="col-md-4">
                        <label>Select UC(s)</label>
                        <select class="select2" name="uc[]" multiple="multiple" style="width:100%">
                            <?php foreach($ucs as $u): ?>
                                <option value="<?= $u->pk_id ?>"
                                    <?= (isset($filters['uc']) && in_array($u->pk_id,$filters['uc'])) ? 'selected' : '' ?>>
                                    <?= $u->uc_name ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Visit Type -->
                    <div class="col-md-4">
                        <label>Visit Type</label>
                        <select class="select2" name="visit_type[]" multiple="multiple" style="width:100%">
                            <?php
                            $visit_type = ['Outreach','Fixed Site'];
                            foreach($visit_type as $vt){
                                echo "<option value='{$vt}' ".
                                (isset($filters['visit_type']) && in_array($vt,$filters['visit_type']) ? 'selected' : '').
                                ">{$vt}</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <!-- Buttons -->
                    <div class="col-md-12 mt-2 m-b-15 d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary me-2">Apply Filters</button>
                        <a href="<?= base_url('dashboard/child_health') ?>" class="btn btn-secondary ml-2">Clear Filters</a>
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
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5>Visit Type Distribution</h5>
                        <div id="visitTypeChart" style="height:400px;"></div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5>Client Type Distribution</h5>
                        <div id="clientTypeChart" style="height:400px;"></div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row">

            <div class="col-md-6"><div id="genderPieChart" style="height:400px;"></div></div>
            <div class="col-md-6"><div id="ageGroupColumnChart" style="height:400px;"></div></div>

            <div class="col-md-6"><div id="q171DoughnutChart" style="height:400px;"></div></div>
            <div class="col-md-6"><div id="sunburstChart" style="height:400px;"></div></div>

            <div class="col-md-12"><div id="heatmapChart" style="height:400px;"></div></div>

            <div class="col-md-6"><div id="q21Chart" style="height:400px;"></div></div>
            <div class="col-md-6"><div id="q22Chart" style="height:400px;"></div></div>

            <div class="col-md-6"><div id="q23Chart" style="height:400px;"></div></div>
            <div class="col-md-6"><div id="q24Chart" style="height:400px;"></div></div>

            <div class="col-md-6"><div id="pictorialChart" style="height:400px;"></div></div>
            <div class="col-md-6"><div id="flameChart" style="height:400px;"></div></div>

        </div>

    </div>

<!-- Highcharts -->
<script src="<?= base_url('assets/highcharts/highcharts.js') ?>"></script>
<script src="<?= base_url('assets/highcharts/modules/exporting.js') ?>"></script>
<script src="<?= base_url('assets/highcharts/modules/heatmap.js') ?>"></script>
<script src="<?= base_url('assets/highcharts/modules/sunburst.js') ?>"></script>
<script src="<?= base_url('assets/highcharts/modules/exporting.js') ?>"></script>

<script>
window.onload = function() {

    $('.select2').select2({width:'100%'});
    $('.datepicker-input').datepicker({format:'yyyy-mm-dd', autoclose:true});

    function loadDashboard(filters){

        $.ajax({
            url: "<?= base_url('dashboard/get_child_health_ajax') ?>",
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
                    title:{ text:null },
                    series:[{
                        name:'Total',
                        colorByPoint:true,
                        data:[
                            { name:'Outreach', y:parseInt(response.outreach)||0 },
                            { name:'Fixed Site', y:parseInt(response.fixed)||0 }
                        ]
                    }]
                });

                Highcharts.chart('clientTypeChart', {
                    chart:{ type:'pie' },
                    title:{ text:null },
                    series:[{
                        name:'Total',
                        colorByPoint:true,
                        data:[
                            { name:'New', y:parseInt(response.new_client)||0 },
                            { name:'Follow-up', y:parseInt(response.followup_client)||0 }
                        ]
                    }]
                });
                
                var genderData = response.gender || { Male:0, Female:0, Other:0 };
                Highcharts.chart('genderPieChart', {
                    chart: { type: 'pie' },
                    title: { text: 'Gender Distribution' },
                    series: [{
                        name: 'Children',
                        colorByPoint: true,
                        data: [
                            { name: 'Male', y: Number(genderData.Male) || 0 },
                            { name: 'Female', y: Number(genderData.Female) || 0 },
                            { name: 'Other', y: Number(genderData.Other) || 0 }
                        ]
                    }]
                });

                // Age Group Column Chart
                var ageGroupData = response.age_group || {
                    '<1 Year':0,
                    '1-2 Year':0,
                    '2-5 Year':0,
                    '5-15 Year':0,
                    '15-49 Year':0
                };

                Highcharts.chart('ageGroupColumnChart', {
                    chart: { type: 'column' },
                    title: { text: 'Age Group Distribution' },
                    xAxis: { 
                        categories: Object.keys(ageGroupData),
                        title: { text: 'Age Group' }
                    },
                    yAxis: { 
                        min: 0, 
                        title: { text: 'Total Children' } 
                    },
                    series: [{
                        name: 'Children',
                        data: Object.values(ageGroupData).map(Number)
                    }]
                });

                // Q17.1 Doughnut Chart
                var q171Data = response.q171 || { Yes:0, No:0, None:0 };

                Highcharts.chart('q171DoughnutChart', {
                    chart: { type: 'pie' },
                    title: { text: 'Q 17.1 Response' },
                    plotOptions: {
                        pie: { innerSize: '60%', dataLabels: { enabled: true } }
                    },
                    series: [{
                        name: 'Responses',
                        colorByPoint: true,
                        data: [
                            { name: 'Yes', y: Number(q171Data.Yes) || 0 },
                            { name: 'No',  y: Number(q171Data.No)  || 0 },
                            { name: 'None',y: Number(q171Data.None)|| 0 }
                        ]
                    }]
                });
                
                
                console.log(response.sunburst); // check data

                Highcharts.chart('sunburstChart', {
                    chart: { height: 400 },
                    title: { text: 'Q 17.2, 17.3 & 17.4' },
                    series: [{
                        type: 'sunburst',
                        data: response.sunburst,
                        allowDrillToNode: true,
                        cursor: 'pointer',
                        dataLabels: { 
                            format: '{point.name}' // show all labels
                        }
                    }],
                    tooltip: {
                        headerFormat: '',
                        pointFormat: '<b>{point.name}</b>: {point.value}'
                    }
                });

                // 8️⃣ Heatmap
                Highcharts.chart('heatmapChart', {
                    chart: { type: 'heatmap' },
                    title: { text: 'Q 18, 19 & 20 Combined' },
                    xAxis: { categories: ['Q18', 'Q19', 'Q20'] },
                    yAxis: { categories: ['UC1', 'UC2', 'UC3'], title: null },
                    colorAxis: { min: 0 },
                    series: [{
                        data: [
                            [0,0,10],[1,0,15],[2,0,20],
                            [0,1,5],[1,1,25],[2,1,30],
                            [0,2,12],[1,2,18],[2,2,22]
                        ]
                    }]
                });

                // 9️⃣ Doughnut – Q21
                Highcharts.chart('q21Chart', {
                    chart: { type: 'pie' },
                    title: { text: 'Q 21' },
                    plotOptions: { pie: { innerSize: '60%' } },
                    series: [{
                        data: [
                            ['Option 1', 45],
                            ['Option 2', 35],
                            ['None', 20]
                        ]
                    }]
                });

                // 🔟 Doughnut – Q22
                Highcharts.chart('q22Chart', {
                    chart: { type: 'pie' },
                    title: { text: 'Q 22' },
                    plotOptions: { pie: { innerSize: '60%' } },
                    series: [{
                        data: [
                            ['Option 1', 30],
                            ['Option 2', 50],
                            ['None', 20]
                        ]
                    }]
                });

                // 1️⃣1️⃣ Doughnut – Q23
                Highcharts.chart('q23Chart', {
                    chart: { type: 'pie' },
                    title: { text: 'Q 23' },
                    plotOptions: { pie: { innerSize: '60%' } },
                    series: [{
                        data: [
                            ['Option 1', 25],
                            ['Option 2', 55],
                            ['None', 20]
                        ]
                    }]
                });

                // 1️⃣2️⃣ Doughnut – Q24
                Highcharts.chart('q24Chart', {
                    chart: { type: 'pie' },
                    title: { text: 'Q 24' },
                    plotOptions: { pie: { innerSize: '60%' } },
                    series: [{
                        data: [
                            ['Option 1', 40],
                            ['Option 2', 40],
                            ['None', 20]
                        ]
                    }]
                });

                // 5A️⃣ Pictorial (Grouped Column)
                Highcharts.chart('pictorialChart', {
                    chart: { type: 'column' },
                    title: { text: 'Gender + Age Group' },
                    xAxis: { categories: ['0-2 Years', '3-5 Years'] },
                    series: [{
                        name: 'Male',
                        data: [30, 40]
                    },{
                        name: 'Female',
                        data: [25, 35]
                    }]
                });

                // 1️⃣3️⃣ Flame Chart (Bar)
                Highcharts.chart('flameChart', {
                    chart: { type: 'bar' },
                    title: { text: 'Q 25 (All Options)' },
                    xAxis: { categories: ['Option A','Option B','Option C','Option D'] },
                    yAxis: { min: 0, title: { text: 'Responses' } },
                    series: [{
                        name: 'Total',
                        data: [20, 35, 15, 30]
                    }]
                });
                

            }
        });
    }

    // APPLY FILTER BUTTON
    $('#filterForm').on('submit', function(e){
        e.preventDefault();

        var filters = {
            uc: $('[name="uc[]"]').val(),
            start: $('[name="start"]').val(),
            end: $('[name="end"]').val(),
            visit_type: $('[name="visit_type[]"]').val()
        };

        if(!filters.uc && !filters.start && !filters.end && !filters.visit_type){
            alert("Please select at least one filter.");
            return;
        }

        loadDashboard(filters);
    });

};
</script>