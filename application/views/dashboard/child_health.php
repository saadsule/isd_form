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
                        <label>Select Date Range *</label>
                        <div class="d-flex align-items-center m-b-15">
                            <input type="text" class="form-control datepicker-input" name="start" placeholder="From"
                                   autocomplete="off"
                                   value="<?= isset($filters['start']) ? $filters['start'] : '' ?>" required="">
                            <span class="p-h-10">to</span>
                            <input type="text" class="form-control datepicker-input" name="end" placeholder="To"
                                   autocomplete="off"
                                   value="<?= isset($filters['end']) ? $filters['end'] : '' ?>" required="">
                        </div>
                    </div>

                    <!-- UC Multiple Select -->
                    <div class="col-md-4">
                        <label>Select UC(s) *</label>
                        <select class="select2" name="uc[]" multiple="multiple" style="width:100%">
                            <?php foreach($ucs as $u): ?>
                                <option value="<?= $u->pk_id ?>"
                                    <?= (isset($filters['uc']) && in_array($u->pk_id,$filters['uc'])) ? 'selected' : '' ?>>
                                    <?= $u->uc_name ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <small class="text-danger error-message" data-for="uc[]"></small>
                    </div>

                    <!-- Visit Type -->
                    <div class="col-md-4">
                        <label>Visit Type *</label>
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
                        <small class="text-danger error-message" data-for="visit_type[]"></small>
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
                        <div id="visitTypeChart" style="height:400px;"></div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div id="clientTypeChart" style="height:400px;"></div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div id="genderPieChart" style="height:400px;"></div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div id="ageGroupColumnChart" style="height:400px;"></div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div id="q171DoughnutChart" style="height:400px;"></div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div id="sunburstChart" style="height:400px;"></div>
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
                        <div id="q21Chart" style="height:400px;"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div id="q22Chart" style="height:400px;"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div id="q23Chart" style="height:400px;"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div id="q24Chart" style="height:400px;"></div>
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
            
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div id="q25Chart" style="height:400px;"></div>
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
<!--<script src="<?= base_url('assets/highcharts/themes/adaptive.js') ?>"></script>-->

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
                    title: { text: 'Visit Type Distribution' },
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
                    title: { text: 'Client Type Distribution' },
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
                    title: { text: 'Q 17.1 Previous Vaccination History' },
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

                Highcharts.chart('sunburstChart', {
                    chart: { },

                    title: { text: 'Child Vaccination Status (Q17.2 → Q17.4)' },

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
                            const total = this.series.data[0].value;
                            const percentage = total ? ((this.value / total) * 100).toFixed(1) : 0;
                            return `<b>${this.name}</b><br>Count: ${this.value}<br>Percentage: ${percentage}%`;
                        }
                    }
                });

                console.log(response.heatmap);

                Highcharts.chart('heatmapChart', {

                    chart: {
                        type: 'heatmap'
                    },

                    title: {
                        text: 'Antigen Administration by Age Group'
                    },

                    xAxis: {
                        categories: response.heatmap.categoriesX,
                        labels: {
                            rotation: -60,
                            style: { fontSize: '11px' }
                        },
                        title: {
                            text: 'Antigen Options'
                        }
                    },

                    yAxis: {
                        categories: response.heatmap.categoriesY,
                        title: {
                            text: 'Age Group (Question)'
                        }
                    },

                    colorAxis: {
                        min: 0,
                        stops: [
                            [0, '#E3F2FD'],
                            [0.5, '#42A5F5'],
                            [1, '#0D47A1']
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

                            const antigen = this.series.xAxis.categories[this.point.x];
                            const question = this.series.yAxis.categories[this.point.y];
                            const total = this.point.value;

                            return `
                                <b>${question}</b><br/>
                                ${antigen}<br/>
                                Total : <b>${total}</b>
                            `;
                        }
                    }

                });

                Highcharts.chart('q21Chart', {
                    chart: { type: 'pie' },

                    title: { text: 'Q 21' },

                    plotOptions: {
                        pie: {
                            innerSize: '60%',
                            dataLabels: {
                                enabled: true,
                                format: '{point.name}: {point.y}'
                            }
                        }
                    },

                    tooltip: {
                        pointFormat: '<b>{point.y}</b>'
                    },

                    series: [{
                        name: 'Responses',
                        data: response.q21
                    }]
                });

                // 🔟 Doughnut – Q22
                Highcharts.chart('q22Chart', {
                    chart: { type: 'pie' },

                    title: { text: 'Q 22' },

                    plotOptions: {
                        pie: {
                            innerSize: '60%',
                            dataLabels: {
                                enabled: true,
                                format: '{point.name}: {point.y}'
                            }
                        }
                    },

                    tooltip: {
                        pointFormat: '<b>{point.y}</b>'
                    },

                    series: [{
                        name: 'Responses',
                        data: response.q22
                    }]
                });

                // 1️⃣1️⃣ Doughnut – Q23
                Highcharts.chart('q23Chart', {
                    chart: { type: 'pie' },

                    title: { text: 'Q 23' },

                    plotOptions: {
                        pie: {
                            innerSize: '60%',
                            dataLabels: {
                                enabled: true,
                                format: '{point.name}: {point.y}'
                            }
                        }
                    },

                    tooltip: {
                        pointFormat: '<b>{point.y}</b>'
                    },

                    series: [{
                        name: 'Responses',
                        data: response.q23
                    }]
                });

                // 1️⃣2️⃣ Doughnut – Q24
                Highcharts.chart('q24Chart', {
                    chart: { type: 'pie' },

                    title: { text: 'Q 24' },

                    plotOptions: {
                        pie: {
                            innerSize: '60%',
                            dataLabels: {
                                enabled: true,
                                format: '{point.name}: {point.y}'
                            }
                        }
                    },

                    tooltip: {
                        pointFormat: '<b>{point.y}</b>'
                    },

                    series: [{
                        name: 'Responses',
                        data: response.q24
                    }]
                });
                
//                Gender Age Group Data
                const ageGroups = response.gender_age.age_groups; // ['0-18','19-30','31-45','46-60']
                const maleData = response.gender_age.series.Male; // [10,20,15,5]
                const femaleData = response.gender_age.series.Female; // [12,18,20,10]

                // Colors for each age group: Male, Female
                const colors = ['#B0FDFE', '#E3FED4', '#F9F492', '#FAF269', '#FAE146', '#FDA003'];

                // Combine Male/Female as separate series for stacking
                let series = [];
                ageGroups.forEach((age, i) => {
                    series.push({
                        name: `Male ${age}`,
                        data: [maleData[i]],
                        color: colors[i*2]
                    });
                    series.push({
                        name: `Female ${age}`,
                        data: [femaleData[i]],
                        color: colors[i*2+1]
                    });
                });

                Highcharts.chart('genderAgeChart', {
                    chart: {
                        type: 'pictorial',
                        marginRight: 200
                    },
                    title: {
                        text: 'Gender + Age Group Distribution',
                        align: 'left'
                    },
                    legend: {
                        align: 'right',
                        floating: true,
                        itemMarginTop: 5,
                        itemMarginBottom: 5,
                        layout: 'vertical',
                        margin: 0,
                        padding: 0,
                        verticalAlign: 'middle'
                    },
                    xAxis: {
                        visible: false,
                        min: 0
                    },
                    yAxis: {
                        visible: false
                    },
                    tooltip: {
                        shared: false,
                        formatter: function() {
                            return `<b>${this.series.name}</b><br>Value: ${this.y}`;
                        }
                    },
                    plotOptions: {
                        series: {
                            stacking: 'percent',
                            dataLabels: {
                                enabled: true,
                                formatter: function() {
                                    return this.y;
                                }
                            },
                            pointPadding: 0,
                            groupPadding: 0,
                            borderWidth: 0,
                            // Single bulb path
                            paths: [{
                                definition: 'M480.15 0.510986V0.531986C316.002 0.531986 197.223 56.655 119.105 139.78C40.987 222.905 3.50699 332.801 0.884992 440.062C-1.74001 547.459 36.194 644.769 79.287 725.354C122.38 805.938 170.742 870.203 188.861 909.922C205.994 947.479 203.626 990.232 206.788 1033.17C209.95 1076.11 219.126 1119.48 260.261 1156.26C260.888 1156.83 261.679 1157.18 262.52 1157.27C262.639 1157.28 262.75 1157.28 262.87 1157.29L262.747 1173.69L274.021 1200.24C275.812 1214.45 275.053 1222.2 273.364 1229.45C261.44 1238.59 250.866 1253.57 283.323 1261.97V1283.88C249.425 1299.28 261.103 1315.14 283.323 1327.03L281.331 1342.96C249.673 1354.72 261.6 1377.5 282.645 1388.76V1403.36C256.094 1414.86 256.771 1436.12 283.323 1451.16V1473.73L349.035 1535.46L396.163 1582.58L397.498 1600.51H565.433V1585.91L619.193 1535.46C631.786 1531.75 660.881 1505.66 698.191 1468.41L702.729 1451.49L686.753 1440.38L687.226 1426.38C714.969 1420.61 718.256 1388.06 687.226 1382.78V1366.87C725.039 1359.03 715.965 1331.13 690.532 1325.04V1311.77C735.92 1292.94 715.774 1272.19 695.193 1267.29V1245.38C721.584 1240.94 721.209 1210.5 702.688 1201.19L711.107 1183.45L711.682 1162.54C713.198 1162.5 714.725 1162.46 716.241 1162.38C717.056 1162.36 717.845 1162.09 718.5 1161.6C754.295 1134.83 762.81 1094.37 765.299 1051.47C767.789 1008.58 764.577 962.629 775.69 923.173C788.878 876.344 833.216 822.264 875.654 750.885C918.093 679.505 958.46 590.459 963.133 472.719C967.812 354.836 929.374 236.776 848.507 148.143C767.638 59.511 644.344 0.516987 480.15 0.516987V0.510986Z'
                            }],
                        }
                    },
                    series: series
                });

                // Q25 Drilldown Stacked Chart
                (function () {

                    const raw = response.flame_q25;

                    const questions = [];
                    const optionMap = {};

                    // Collect questions & options
                    raw.forEach(item => {

                        if (!questions.includes(item.question)) {
                            questions.push(item.question);
                        }

                        if (!optionMap[item.name]) {
                            optionMap[item.name] = {
                                name: item.name,
                                data: [],
                                color: item.color
                            };
                        }
                    });

                    // Initialize with zeros
                    Object.keys(optionMap).forEach(opt => {
                        optionMap[opt].data = new Array(questions.length).fill(0);
                    });

                    // Fill real values
                    raw.forEach(item => {
                        const qIndex = questions.indexOf(item.question);
                        optionMap[item.name].data[qIndex] = item.value;
                    });

                    const series = Object.values(optionMap);

                    Highcharts.chart('q25Chart', {

                        chart: {
                            type: 'bar',
                        },

                        title: {
                            text: 'TT Vaccination Summary (Q25)'
                        },

                        xAxis: {
                            categories: questions
                        },

                        yAxis: {
                            min: 0,
                            title: { text: 'Total Responses' },
                            stackLabels: {
                                enabled: true,
                                style: {
                                    fontWeight: 'bold',
                                    color: '#000'
                                }
                            }
                        },

                        plotOptions: {
                            series: {
                                stacking: 'normal',
                                borderWidth: 0,
                                dataLabels: {
                                    enabled: true,
                                    formatter: function () {
                                        if (this.y > 0) return this.y;
                                    }
                                }
                            }
                        },

                        tooltip: {
                            shared: true,
                            formatter: function () {

                                const category = this.x;
                                let total = 0;

                                this.points.forEach(point => {
                                    total += point.y;
                                });

                                let s = `<b>${category}</b><br/>Total: <b>${total}</b><br/><br/>`;

                                this.points.forEach(point => {
                                    if (point.y > 0) {
                                        const percentage = ((point.y / total) * 100).toFixed(1);
                                        s += `
                                            <span style="color:${point.color}">\u25CF</span> 
                                            ${point.series.name}: 
                                            <b>${point.y}</b> 
                                            (${percentage}%)
                                            <br/>
                                        `;
                                    }
                                });

                                return s;
                            }
                        },

                        legend: {
                            reversed: false
                        },

                        series: series
                    });

                })();

            }
        });
    }

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