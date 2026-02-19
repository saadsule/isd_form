<div class="page-container">
    <div class="main-content">

        <div class="page-header">
            <h2 class="header-title">Outreach Dashboard</h2>
        </div>

        <!-- FILTERS -->
        <div class="row mb-3">
            <div class="col-12">
                <form method="get" class="row g-2 align-items-end">

                    <!-- UC Multiple Select -->
                    <div class="col-md-3">
                        <label>Select UC(s)</label>
                        <div class="m-b-15">
                            <select class="select2" name="uc[]" multiple="multiple" style="width:100%">
                                <?php foreach($ucs as $u): ?>
                                    <option value="<?= $u->pk_id ?>" <?= (isset($filters['uc']) && in_array($u->pk_id,$filters['uc'])) ? 'selected' : '' ?>>
                                        <?= $u->uc_name ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <!-- Gender -->
                    <div class="col-md-2">
                        <label>Select Gender</label>
                        <div class="m-b-15">
                            <select class="select2" name="gender[]" multiple="multiple" style="width:100%">
                                <?php
                                $genders = ['Male','Female'];
                                foreach($genders as $g){
                                    echo "<option value='{$g}' ".(isset($filters['gender']) && in_array($g,$filters['gender']) ? 'selected' : '').">{$g}</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <!-- Age Group -->
                    <div class="col-md-3">
                        <label>Select Age Group</label>
                        <div class="m-b-15">
                            <select class="select2" name="age_group[]" multiple="multiple" style="width:100%">
                                <?php
                                $age_groups = ['<1 Year','1-2 Year','2-5 Year','5-15 Year','15-49 Year'];
                                foreach($age_groups as $ag){
                                    echo "<option value='{$ag}' ".(isset($filters['age_group']) && in_array($ag,$filters['age_group']) ? 'selected' : '').">{$ag}</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <!-- Date Range Picker -->
                    <div class="col-md-4">
                        <label>Range Datepicker</label>
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
                    
                    <div class="col-md-12">
                        <hr style="
                            border: none;
                            height: 3px;
                            background: linear-gradient(to right, transparent, #6e8c75, transparent);
                            opacity:1;
                        ">
                    </div>

                    <!-- Client Type -->
                    <div class="col-md-3 mt-2">
                        <label>Select Outpatient Variable (Plot 1)</label>
                        <div class="m-b-15">
                            <select class="select2" name="client_type[]" multiple="multiple" style="width:100%">
                                <?php
                                $client_types = ['New','Followup'];
                                foreach($client_types as $vt){
                                    echo "<option value='{$vt}' ".(isset($filters['client_type']) && in_array($vt,$filters['client_type']) ? 'selected' : '').">{$vt}</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-4 mt-2 m-b-15">
                        <button type="submit" class="btn btn-primary">Apply Filters</button>
                        <a href="<?= base_url('dashboard/outreach') ?>" class="btn btn-secondary">Clear Filters</a>
                    </div>

                </form>
            </div>
        </div>

        <!-- GRAPH -->
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div id="outreachChart" style="height: 450px;"></div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- BAR GRAPH -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div id="outreachBarChart" style="height: 450px;"></div>
                    </div>
                </div>
            </div>
        </div>

    </div>

<!-- Include Highcharts via live CDN BEFORE your script -->
<script src="<?= base_url('assets/highcharts/highcharts.js') ?>"></script>
<script src="<?= base_url('assets/highcharts/modules/exporting.js') ?>"></script>
<script src="<?= base_url('assets/highcharts/modules/export-data.js') ?>"></script>
<script src="<?= base_url('assets/highcharts/modules/accessibility.js') ?>"></script>

<script>
document.addEventListener("DOMContentLoaded", function(){

    // Initialize Select2
    $('.select2').select2({
        placeholder: "Select option(s)",
        allowClear: true,
        width:'100%'
    });

    // Initialize datepicker without autocomplete
    $('.datepicker-input').datepicker({
        format:'yyyy-mm-dd',
        autoclose:true
    });

    // Graph data from controller
    var graphData = <?= json_encode($graph_data); ?>;

    // Get unique days for x-axis (daily)
    var days = [];
    graphData.forEach(function(row){
        if(!days.includes(row.form_date)) days.push(row.form_date);
    });

    // Prepare series: combine gender + age group + visit type
    var seriesMap = {};
    graphData.forEach(function(row){
        var seriesName = row.gender + ' ' + row.age_group + ' (' + row.client_type + ')';
        if(!seriesMap[seriesName]) seriesMap[seriesName] = Array(days.length).fill(0);
        var dayIndex = days.indexOf(row.form_date);
        seriesMap[seriesName][dayIndex] = parseInt(row.total);
    });

    var seriesData = [];
    for(var key in seriesMap) seriesData.push({name:key, data:seriesMap[key]});

    // Render Highcharts line chart (daily)
    Highcharts.chart('outreachChart', {
        chart: { type:'line' },
        exporting: {
            enabled: true,
            buttons: {
                contextButton: {
                    menuItems: [
                        'viewFullscreen',
                        'printChart',
                        'separator',
                        'downloadPNG',
                        'downloadJPEG',
                        'downloadPDF',
                        'downloadSVG',
                        'separator',
                        'downloadCSV',
                        'downloadXLS',
                        'viewData'
                    ]
                }
            }
        },
        title: { text:'Daily Outreach Trend' },
        xAxis: { categories: days, title:{ text:'Date' } },
        yAxis: { title:{ text:'Number of Cases' }, allowDecimals:false },
        tooltip:{ shared:true },
        series: seriesData,
        responsive:{
            rules:[{
                condition:{ maxWidth:600 },
                chartOptions:{ legend:{ layout:'horizontal', align:'center', verticalAlign:'bottom' } }
            }]
        }
    });
    
    // Render Highcharts BAR chart (daily)
    Highcharts.chart('outreachBarChart', {

        chart: {
            type: 'column'   // 👈 same style as your example
        },

        title: {
            text: 'Daily Outreach Cases'
        },

        subtitle: {
            text: 'Gender + Age Group + Visit Type Distribution'
        },

        exporting: {
            enabled: true
        },

        xAxis: {
            categories: days,   // 👈 Dates on X-axis
            crosshair: true,
            title: {
                text: 'Date'
            }
        },

        yAxis: {
            min: 0,
            title: {
                text: 'Number of Cases'
            },
            allowDecimals: false
        },

        tooltip: {
            shared: true,
            useHTML: true
        },

        plotOptions: {
            column: {
                borderRadius: 5,
                dataLabels: {
                    enabled: true
                }
            }
        },

        series: seriesData,

        responsive: {
            rules: [{
                condition: {
                    maxWidth: 600
                },
                chartOptions: {
                    legend: {
                        layout: 'horizontal',
                        align: 'center',
                        verticalAlign: 'bottom'
                    }
                }
            }]
        }

    });

});
</script>

