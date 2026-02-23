<div class="page-container">
    <div class="main-content">

        <div class="page-header">
            <h2 class="header-title">Outreach Dashboard</h2>
        </div>

        <!-- FILTERS -->
        <div class="row mb-3">
            <div class="col-12">
                <form method="get" id="filterForm" class="row g-2 align-items-end">

                    <!-- UC Multiple Select -->
                    <div class="col-md-3">
                        <label>Select UC(s) *</label>
                        <div class="m-b-15">
                            <select class="select2" name="uc[]" multiple="multiple" style="width:100%">
                                <?php foreach($ucs as $u): ?>
                                    <option value="<?= $u->pk_id ?>" <?= (isset($filters['uc']) && in_array($u->pk_id,$filters['uc'])) ? 'selected' : '' ?>>
                                        <?= $u->uc_name ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <small class="text-danger error-message" data-for="uc[]"></small>
                        </div>
                    </div>

                    <!-- Gender -->
                    <div class="col-md-2">
                        <label>Select Gender *</label>
                        <div class="m-b-15">
                            <select class="select2" name="gender[]" multiple="multiple" style="width:100%">
                                <?php
                                $genders = ['Male','Female'];
                                foreach($genders as $g){
                                    echo "<option value='{$g}' ".(isset($filters['gender']) && in_array($g,$filters['gender']) ? 'selected' : '').">{$g}</option>";
                                }
                                ?>
                            </select>
                            <small class="text-danger error-message" data-for="gender[]"></small>
                        </div>
                    </div>

                    <!-- Age Group -->
                    <div class="col-md-3">
                        <label>Select Age Group *</label>
                        <div class="m-b-15">
                            <select class="select2" name="age_group[]" multiple="multiple" style="width:100%">
                                <?php
                                $age_groups = ['<1 Year','1-2 Year','2-5 Year','5-15 Year','15-49 Year'];
                                foreach($age_groups as $ag){
                                    echo "<option value='{$ag}' ".(isset($filters['age_group']) && in_array($ag,$filters['age_group']) ? 'selected' : '').">{$ag}</option>";
                                }
                                ?>
                            </select>
                            <small class="text-danger error-message" data-for="age_group[]"></small>
                        </div>
                    </div>

                    <!-- Date Range Picker -->
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
                    
                    <div class="col-md-3 mt-2">
                        <label>Vaccination History (For Under Five Years Children) (Plot 2)</label>
                        <div class="m-b-15">
                            <select class="select2" name="vaccination_history[]" multiple="multiple" style="width:100%">
                                <?php
                                $vaccination_history_options = [
                                    1  => "Ever received any vaccination earlier – Yes",
                                    2  => "Ever received any vaccination earlier – No",
                                    3  => "Child vaccinated during this session – Yes",
                                    4  => "Child vaccinated during this session – No",
                                    5  => "In case of 'No' to child vaccinated during this session – Fully immunized as per age",
                                    6  => "In case of 'No' to child vaccinated during this session – Vaccine not due",
                                    7  => "In case of 'No' to child vaccinated during this session – Child is unwell",
                                    8  => "In case of 'No' to child vaccinated during this session – Refusal",
                                    9  => "Refusal Type – Demand Refusal",
                                    10 => "Refusal Type – Misconception Refusal",
                                    11 => "Refusal Type – Religious Refusal"
                                ];

                                foreach($vaccination_history_options as $id => $text){
                                    $selected = (isset($filters['vaccination_history']) && in_array($id, $filters['vaccination_history'])) ? 'selected' : '';
                                    echo "<option value='{$id}' {$selected}>{$text}</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-md-3 mt-2">
                        <label>Antigens Administered to Child &lt; 1 Year (Plot 3)</label>
                        <div class="m-b-15">
                            <select class="select2" name="antigens[]" multiple="multiple" style="width:100%">
                                <?php
                                // Fetch options from database
                                $this->db->select('option_id, option_text');
                                $this->db->from('question_options');
                                $this->db->where('question_id', 5);
                                $this->db->where('status', 1); // Only active options
                                $this->db->order_by('option_order', 'ASC');
                                $query = $this->db->get();
                                $options = $query->result();

                                foreach($options as $opt){
                                    $selected = (isset($filters['antigens']) && in_array($opt->option_id, $filters['antigens'])) ? 'selected' : '';
                                    echo "<option value='{$opt->option_id}' {$selected}>{$opt->option_text}</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-md-3 mt-2">
                        <label>Antigens Administered to Child 1–2 Years (Plot 4)</label>
                        <div class="m-b-15">
                            <select class="select2" name="antigens_1_2_years[]" multiple="multiple" style="width:100%">
                                <?php
                                // Fetch options from database for question_id = 6
                                $this->db->select('option_id, option_text');
                                $this->db->from('question_options');
                                $this->db->where('question_id', 6);
                                $this->db->where('status', 1); // Only active options
                                $this->db->order_by('option_order', 'ASC');
                                $query = $this->db->get();
                                $options = $query->result();

                                foreach($options as $opt){
                                    $selected = (isset($filters['antigens_1_2_years']) && in_array($opt->option_id, $filters['antigens_1_2_years'])) ? 'selected' : '';
                                    echo "<option value='{$opt->option_id}' {$selected}>{$opt->option_text}</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-md-3 mt-2">
                        <label>Antigens Administered to Child 2–5 Years (Plot 5)</label>
                        <div class="m-b-15">
                            <select class="select2" name="antigens_2_5_years[]" multiple="multiple" style="width:100%">
                                <?php
                                // Fetch options from database for question_id = 7
                                $this->db->select('option_id, option_text');
                                $this->db->from('question_options');
                                $this->db->where('question_id', 7);
                                $this->db->where('status', 1); // Only active options
                                $this->db->order_by('option_order', 'ASC');
                                $query = $this->db->get();
                                $options = $query->result();

                                foreach($options as $opt){
                                    $selected = (isset($filters['antigens_2_5_years']) && in_array($opt->option_id, $filters['antigens_2_5_years'])) ? 'selected' : '';
                                    echo "<option value='{$opt->option_id}' {$selected}>{$opt->option_text}</option>";
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

                        <?php if(!$showPlot1): ?>
                            <div class="text-center p-5">
                                <h5 class="text-muted">Please select filter(s) to view data</h5>
                            </div>
                        <?php else: ?>
                            <div id="outreachChart" style="height: 450px;"></div>
                        <?php endif; ?>

                    </div>
                </div>
            </div>
        </div>
        
        <div class="row mt-3">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-body">

                        <?php if(!$showPlot2): ?>
                            <div class="text-center p-5">
                                <h5 class="text-muted">Please select filter(s) to view data</h5>
                            </div>
                        <?php else: ?>
                            <div id="vaccinationChart" style="height: 450px;"></div>
                        <?php endif; ?>

                    </div>
                </div>
            </div>
        </div>
        
        <div class="row mt-3">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-body">

                        <?php if(!$showPlot3): ?>
                            <div class="text-center p-5">
                                <h5 class="text-muted">Please select filter(s) to view data</h5>
                            </div>
                        <?php else: ?>
                            <div id="antigenUnder1Chart" style="height: 450px;"></div>
                        <?php endif; ?>

                    </div>
                </div>
            </div>
        </div>
        
        <div class="row mt-3">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-body">

                        <?php if(!$showPlot4): ?>
                            <div class="text-center p-5">
                                <h5 class="text-muted">Please select filter(s) to view data</h5>
                            </div>
                        <?php else: ?>
                            <div id="antigen1to2Chart" style="height: 450px;"></div>
                        <?php endif; ?>

                    </div>
                </div>
            </div>
        </div>
        
        <div class="row mt-3">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-body">

                        <?php if(!$showPlot5): ?>
                            <div class="text-center p-5">
                                <h5 class="text-muted">Please select filter(s) to view data</h5>
                            </div>
                        <?php else: ?>
                            <div id="antigen2to5Chart" style="height: 450px;"></div>
                        <?php endif; ?>

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
document.getElementById("filterForm").onsubmit = function(e) {

    let requiredFields = ["uc[]", "gender[]", "age_group[]"];
    let valid = true;

    // Clear old errors
    document.querySelectorAll(".error-message").forEach(el => el.innerHTML = "");
    document.querySelectorAll(".select2-container").forEach(el => el.style.border = "");

    requiredFields.forEach(function(name) {

        let select = document.getElementsByName(name)[0];
        let errorBox = document.querySelector('[data-for="'+name+'"]');

        if (!select || select.selectedOptions.length === 0) {

            valid = false;

            // Add red border
            let select2Box = select.nextElementSibling;
            if (select2Box) {
                select2Box.style.borderRadius = "4px";
            }

            // Show message
            if (errorBox) {
                errorBox.innerHTML = "This field is required.";
            }
        }

    });

    if (!valid) {
        e.preventDefault();
        return false;
    }

};
document.addEventListener("DOMContentLoaded", function(){

    // Initialize Select2
    $('.select2').select2({
        placeholder: "Select option(s)",
        allowClear: true,
        width:'100%'
    });

    // Initialize datepicker
    $('.datepicker-input').datepicker({
        format:'yyyy-mm-dd',
        autoclose:true
    });

    <?php if($isFilterApplied): ?>

        var graphData = <?= json_encode($graph_data); ?>;

        // If no data found
        if (!graphData || graphData.length === 0) {
            document.getElementById("outreachChart").innerHTML =
                "<div class='text-center p-5 text-muted'>No data found for selected filters</div>";
            return;
        }

        // Get unique dates
        var days = [];
        graphData.forEach(function(row){
            if (!days.includes(row.form_date)) {
                days.push(row.form_date);
            }
        });

        // Prepare series
        var seriesMap = {};
        graphData.forEach(function(row){
            var seriesName = row.gender + ' ' + row.age_group + ' (' + row.client_type + ')';

            if (!seriesMap[seriesName]) {
                seriesMap[seriesName] = Array(days.length).fill(0);
            }

            var dayIndex = days.indexOf(row.form_date);
            seriesMap[seriesName][dayIndex] = parseInt(row.total);
        });

        var seriesData = [];
        for (var key in seriesMap) {
            seriesData.push({
                name: key,
                data: seriesMap[key]
            });
        }

        // Render Highcharts
        Highcharts.chart('outreachChart', {
            chart: { type:'spline' },
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
            xAxis: { 
                categories: days,
                title:{ text:'Date' }
            },
            yAxis: { 
                title:{ text:'Number of Cases' },
                allowDecimals:false
            },
            tooltip:{ shared:true },
            series: seriesData,
            responsive:{
                rules:[{
                    condition:{ maxWidth:600 },
                    chartOptions:{
                        legend:{
                            layout:'horizontal',
                            align:'center',
                            verticalAlign:'bottom'
                        }
                    }
                }]
            }
        });

    <?php endif; ?>
});

<?php
$days = [];
$series = [];
$temp = [];

foreach($plot2_data as $row){
    $days[$row->form_date] = $row->form_date;
    $temp[$row->option_id][$row->form_date] = (int)$row->total;
}

$days = array_values($days);
sort($days);

// Option labels
$labels = [
    1  => "Vaccination Earlier – Yes",
    2  => "Vaccination Earlier – No",
    3  => "Vaccinated This Session – Yes",
    4  => "Vaccinated This Session – No",
    5  => "Fully Immunized",
    6  => "Vaccine Not Due",
    7  => "Child Unwell",
    8  => "Refusal",
    9  => "Demand Refusal",
    10 => "Misconception Refusal",
    11 => "Religious Refusal"
];

foreach($temp as $option_id => $dateData){
    $dataPoints = [];
    foreach($days as $d){
        $dataPoints[] = isset($dateData[$d]) ? $dateData[$d] : 0;
    }

    $series[] = [
        'name' => isset($labels[$option_id]) ? $labels[$option_id] : 'Option '.$option_id,
        'data' => $dataPoints
    ];
}
?>

var plot2Days = <?= json_encode($days) ?>;
var plot2Series = <?= json_encode($series) ?>;

Highcharts.chart('vaccinationChart', {
    chart: { type:'spline' },

    title: { text:'Daily Vaccination History Trend' },

    xAxis: {
        categories: plot2Days,
        title:{ text:'Date' }
    },

    yAxis:{
        title:{ text:'Number of Cases' },
        allowDecimals:false
    },

    tooltip:{ shared:true },

    series: plot2Series,

    responsive:{
        rules:[{
            condition:{ maxWidth:600 },
            chartOptions:{
                legend:{
                    layout:'horizontal',
                    align:'center',
                    verticalAlign:'bottom'
                }
            }
        }]
    }
});

<?php
$days3 = [];
$temp3 = [];
$series3 = [];

// Get antigen labels dynamically
$antigen_labels = [];
$this->db->select('option_id, option_text');
$this->db->from('question_options');
$this->db->where('question_id', 5);
$this->db->where('status', 1);
$q = $this->db->get()->result();
foreach($q as $row){
    $antigen_labels[$row->option_id] = $row->option_text;
}

foreach($plot3_data as $row){
    $days3[$row->form_date] = $row->form_date;
    $temp3[$row->option_id][$row->form_date] = (int)$row->total;
}

$days3 = array_values($days3);
sort($days3);

foreach($temp3 as $option_id => $dateData){
    $dataPoints = [];
    foreach($days3 as $d){
        $dataPoints[] = isset($dateData[$d]) ? $dateData[$d] : 0;
    }

    $series3[] = [
        'name' => isset($antigen_labels[$option_id]) ? $antigen_labels[$option_id] : 'Option '.$option_id,
        'data' => $dataPoints
    ];
}
?>

var plot3Days = <?= json_encode($days3) ?>;
var plot3Series = <?= json_encode($series3) ?>;

Highcharts.chart('antigenUnder1Chart', {
    chart: { type:'spline' },

    title: { text:'Daily Antigens Administered (< 1 Year)' },

    xAxis: {
        categories: plot3Days,
        title:{ text:'Date' }
    },

    yAxis:{
        title:{ text:'Number of Cases' },
        allowDecimals:false
    },

    tooltip:{ shared:true },

    series: plot3Series
});

<?php
$days4 = [];
$temp4 = [];
$series4 = [];

// Fetch labels dynamically
$labels4 = [];
$this->db->select('option_id, option_text');
$this->db->from('question_options');
$this->db->where('question_id', 6);
$this->db->where('status', 1);
$q4 = $this->db->get()->result();
foreach($q4 as $row){
    $labels4[$row->option_id] = $row->option_text;
}

foreach($plot4_data as $row){
    $days4[$row->form_date] = $row->form_date;
    $temp4[$row->option_id][$row->form_date] = (int)$row->total;
}

$days4 = array_values($days4);
sort($days4);

foreach($temp4 as $option_id => $dateData){
    $dataPoints = [];
    foreach($days4 as $d){
        $dataPoints[] = isset($dateData[$d]) ? $dateData[$d] : 0;
    }

    $series4[] = [
        'name' => isset($labels4[$option_id]) ? $labels4[$option_id] : 'Option '.$option_id,
        'data' => $dataPoints
    ];
}
?>

var plot4Days = <?= json_encode($days4) ?>;
var plot4Series = <?= json_encode($series4) ?>;

Highcharts.chart('antigen1to2Chart', {
    chart: { type:'spline' },

    title: { text:'Daily Antigens Administered (1–2 Years)' },

    xAxis: {
        categories: plot4Days,
        title:{ text:'Date' }
    },

    yAxis:{
        title:{ text:'Number of Cases' },
        allowDecimals:false
    },

    tooltip:{ shared:true },

    series: plot4Series
});

<?php
$days5 = [];
$temp5 = [];
$series5 = [];

// Fetch labels dynamically
$labels5 = [];
$this->db->select('option_id, option_text');
$this->db->from('question_options');
$this->db->where('question_id', 7);
$this->db->where('status', 1);
$q5 = $this->db->get()->result();
foreach($q5 as $row){
    $labels5[$row->option_id] = $row->option_text;
}

foreach($plot5_data as $row){
    $days5[$row->form_date] = $row->form_date;
    $temp5[$row->option_id][$row->form_date] = (int)$row->total;
}

$days5 = array_values($days5);
sort($days5);

foreach($temp5 as $option_id => $dateData){
    $dataPoints = [];
    foreach($days5 as $d){
        $dataPoints[] = isset($dateData[$d]) ? $dateData[$d] : 0;
    }

    $series5[] = [
        'name' => isset($labels5[$option_id]) ? $labels5[$option_id] : 'Option '.$option_id,
        'data' => $dataPoints
    ];
}
?>

var plot5Days = <?= json_encode($days5) ?>;
var plot5Series = <?= json_encode($series5) ?>;

Highcharts.chart('antigen2to5Chart', {
    chart: { type:'spline' },

    title: { text:'Daily Antigens Administered (2–5 Years)' },

    xAxis: {
        categories: plot5Days,
        title:{ text:'Date' }
    },

    yAxis:{
        title:{ text:'Number of Cases' },
        allowDecimals:false
    },

    tooltip:{ shared:true },

    series: plot5Series
});
</script>

