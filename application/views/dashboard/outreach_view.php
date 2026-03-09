<?php 
// Default start and end dates
$default_start = isset($filters['start']) ? $filters['start'] : '2025-12-01';
$default_end   = isset($filters['end']) ? $filters['end'] : date('Y-m-d');
?>
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
                                <?php
                                $selected_ucs = isset($filters['uc'])
                                    ? (is_array($filters['uc']) ? $filters['uc'] : [$filters['uc']])
                                    : array_map(function($u){ return $u->pk_id; }, $ucs);
                                foreach($ucs as $u): ?>
                                    <option value="<?= $u->pk_id ?>" <?= in_array($u->pk_id, $selected_ucs) ? 'selected' : '' ?>>
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
                                $selected_genders = isset($filters['gender'])
                                    ? (is_array($filters['gender']) ? $filters['gender'] : [$filters['gender']])
                                    : $genders;
                                foreach($genders as $g): ?>
                                    <option value="<?= $g ?>" <?= in_array($g, $selected_genders) ? 'selected' : '' ?>>
                                        <?= $g ?>
                                    </option>
                                <?php endforeach; ?>
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
                                $selected_age_groups = isset($filters['age_group'])
                                    ? (is_array($filters['age_group']) ? $filters['age_group'] : [$filters['age_group']])
                                    : $age_groups;
                                foreach($age_groups as $ag): ?>
                                    <option value="<?= $ag ?>" <?= in_array($ag, $selected_age_groups) ? 'selected' : '' ?>>
                                        <?= $ag ?>
                                    </option>
                                <?php endforeach; ?>
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
                                   value="<?= $default_start ?>" required="">
                            <span class="p-h-10">to</span>
                            <input type="text" class="form-control datepicker-input" name="end" placeholder="To" 
                                   autocomplete="off"
                                   value="<?= $default_end ?>" required="">
                        </div>
                    </div>

                    <div class="col-md-12">
                        <hr style="border: none; height: 3px; background: linear-gradient(to right, transparent, #6e8c75, transparent); opacity:1;">
                    </div>

                    <!-- Client Type -->
                    <div class="col-md-3 mt-2">
                        <label>Select Outpatient Variable (Plot 1)</label>
                        <div class="m-b-15">
                            <select class="select2" name="client_type[]" multiple="multiple" style="width:100%">
                                <?php
                                $client_types = ['New','Followup'];
                                $selected_client_types = isset($filters['client_type'])
                                    ? (is_array($filters['client_type']) ? $filters['client_type'] : [$filters['client_type']])
                                    : $client_types;
                                foreach($client_types as $vt): ?>
                                    <option value="<?= $vt ?>" <?= in_array($vt, $selected_client_types) ? 'selected' : '' ?>>
                                        <?= $vt ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <!-- Vaccination History -->
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
                                $selected_vaccination = isset($filters['vaccination_history'])
                                    ? (is_array($filters['vaccination_history']) ? $filters['vaccination_history'] : [$filters['vaccination_history']])
                                    : array_keys($vaccination_history_options);
                                foreach($vaccination_history_options as $id => $text): ?>
                                    <option value="<?= $id ?>" <?= in_array($id, $selected_vaccination) ? 'selected' : '' ?>>
                                        <?= $text ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <!-- Antigens < 1 Year -->
                    <div class="col-md-3 mt-2">
                        <label>Antigens Administered to Child &lt; 1 Year (Plot 3)</label>
                        <div class="m-b-15">
                            <select class="select2" name="antigens[]" multiple="multiple" style="width:100%">
                                <?php
                                $this->db->select('option_id, option_text');
                                $this->db->from('question_options');
                                $this->db->where('question_id', 5);
                                $this->db->where('status', 1);
                                $this->db->order_by('option_order', 'ASC');
                                $query = $this->db->get();
                                $options = $query->result();
                                $selected_antigens = isset($filters['antigens'])
                                    ? (is_array($filters['antigens']) ? $filters['antigens'] : [$filters['antigens']])
                                    : array_map(function($opt){ return $opt->option_id; }, $options);
                                foreach($options as $opt): ?>
                                    <option value="<?= $opt->option_id ?>" <?= in_array($opt->option_id, $selected_antigens) ? 'selected' : '' ?>>
                                        <?= $opt->option_text ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <!-- Antigens 1-2 Years -->
                    <div class="col-md-3 mt-2">
                        <label>Antigens Administered to Child 1–2 Years (Plot 4)</label>
                        <div class="m-b-15">
                            <select class="select2" name="antigens_1_2_years[]" multiple="multiple" style="width:100%">
                                <?php
                                $this->db->select('option_id, option_text');
                                $this->db->from('question_options');
                                $this->db->where('question_id', 6);
                                $this->db->where('status', 1);
                                $this->db->order_by('option_order', 'ASC');
                                $query = $this->db->get();
                                $options = $query->result();
                                $selected_antigens_1_2 = isset($filters['antigens_1_2_years'])
                                    ? (is_array($filters['antigens_1_2_years']) ? $filters['antigens_1_2_years'] : [$filters['antigens_1_2_years']])
                                    : array_map(function($opt){ return $opt->option_id; }, $options);
                                foreach($options as $opt): ?>
                                    <option value="<?= $opt->option_id ?>" <?= in_array($opt->option_id, $selected_antigens_1_2) ? 'selected' : '' ?>>
                                        <?= $opt->option_text ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <!-- Antigens 2-5 Years -->
                    <div class="col-md-3 mt-2">
                        <label>Antigens Administered to Child 2–5 Years (Plot 5)</label>
                        <div class="m-b-15">
                            <select class="select2" name="antigens_2_5_years[]" multiple="multiple" style="width:100%">
                                <?php
                                $this->db->select('option_id, option_text');
                                $this->db->from('question_options');
                                $this->db->where('question_id', 7);
                                $this->db->where('status', 1);
                                $this->db->order_by('option_order', 'ASC');
                                $query = $this->db->get();
                                $options = $query->result();
                                $selected_antigens_2_5 = isset($filters['antigens_2_5_years'])
                                    ? (is_array($filters['antigens_2_5_years']) ? $filters['antigens_2_5_years'] : [$filters['antigens_2_5_years']])
                                    : array_map(function($opt){ return $opt->option_id; }, $options);
                                foreach($options as $opt): ?>
                                    <option value="<?= $opt->option_id ?>" <?= in_array($opt->option_id, $selected_antigens_2_5) ? 'selected' : '' ?>>
                                        <?= $opt->option_text ?>
                                    </option>
                                <?php endforeach; ?>
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
            let select2Box = select.nextElementSibling;
            if (select2Box) {
                select2Box.style.borderRadius = "4px";
            }
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

    // Auto-submit on first load only (empty query string = no filters applied yet)
    if (window.location.search === '') {
        document.getElementById("filterForm").submit();
        return; // stop rest of DOMContentLoaded from running
    }

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
$seriesMap = [];
$temp = [];

foreach($plot2_data as $row){
    $days[$row->form_date] = $row->form_date;
    
    // Create unique series key per option + gender + age group
    $key = $row->option_id . '|' . $row->gender . '|' . $row->age_group;
    $temp[$key][$row->form_date] = (int)$row->total;
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

// Prepare series
$series = [];
foreach($temp as $key => $dateData){
    list($option_id, $gender, $age_group) = explode('|', $key);

    $dataPoints = [];
    foreach($days as $d){
        $dataPoints[] = isset($dateData[$d]) ? $dateData[$d] : 0;
    }

    $series[] = [
        'name' => (isset($labels[$option_id]) ? $labels[$option_id] : 'Option '.$option_id)
                  . ' (' . $gender . ' / ' . $age_group . ')',
        'data' => $dataPoints
    ];
}
?>

var plot2Days = <?= json_encode($days) ?>;
var plot2Series = <?= json_encode($series) ?>;

if (plot2Series.length > 0) {
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
}
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

// Prepare temp array with unique key: option + gender + age group
foreach($plot3_data as $row){
    $days3[$row->form_date] = $row->form_date;

    // Unique series key per option + gender + age group
    $key = $row->option_id . '|' . $row->gender . '|' . $row->age_group;
    $temp3[$key][$row->form_date] = (int)$row->total;
}

$days3 = array_values($days3);
sort($days3);

// Prepare series array
foreach($temp3 as $key => $dateData){
    list($option_id, $gender, $age_group) = explode('|', $key);

    $dataPoints = [];
    foreach($days3 as $d){
        $dataPoints[] = isset($dateData[$d]) ? $dateData[$d] : 0;
    }

    $series3[] = [
        'name' => (isset($antigen_labels[$option_id]) ? $antigen_labels[$option_id] : 'Option '.$option_id)
                  . ' (' . $gender . ' / ' . $age_group . ')',
        'data' => $dataPoints
    ];
}
?>

var plot3Days = <?= json_encode($days3) ?>;
var plot3Series = <?= json_encode($series3) ?>;

if (plot3Series.length > 0) {
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
}
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

// Prepare temp array with unique key: option + gender + age group
foreach($plot4_data as $row){
    $days4[$row->form_date] = $row->form_date;

    // Unique series key per option + gender + age group
    $key = $row->option_id . '|' . $row->gender . '|' . $row->age_group;
    $temp4[$key][$row->form_date] = (int)$row->total;
}

$days4 = array_values($days4);
sort($days4);

// Prepare series array
foreach($temp4 as $key => $dateData){
    list($option_id, $gender, $age_group) = explode('|', $key);

    $dataPoints = [];
    foreach($days4 as $d){
        $dataPoints[] = isset($dateData[$d]) ? $dateData[$d] : 0;
    }

    $series4[] = [
        'name' => (isset($labels4[$option_id]) ? $labels4[$option_id] : 'Option '.$option_id)
                  . ' (' . $gender . ' / ' . $age_group . ')',
        'data' => $dataPoints
    ];
}
?>

var plot4Days = <?= json_encode($days4) ?>;
var plot4Series = <?= json_encode($series4) ?>;

if (plot4Series.length > 0) {
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
}
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

// Prepare temp array with unique key: option + gender + age group
foreach($plot5_data as $row){
    $days5[$row->form_date] = $row->form_date;

    // Unique series key per option + gender + age group
    $key = $row->option_id . '|' . $row->gender . '|' . $row->age_group;
    $temp5[$key][$row->form_date] = (int)$row->total;
}

$days5 = array_values($days5);
sort($days5);

// Prepare series array
foreach($temp5 as $key => $dateData){
    list($option_id, $gender, $age_group) = explode('|', $key);

    $dataPoints = [];
    foreach($days5 as $d){
        $dataPoints[] = isset($dateData[$d]) ? $dateData[$d] : 0;
    }

    $series5[] = [
        'name' => (isset($labels5[$option_id]) ? $labels5[$option_id] : 'Option '.$option_id)
                  . ' (' . $gender . ' / ' . $age_group . ')',
        'data' => $dataPoints
    ];
}
?>

var plot5Days = <?= json_encode($days5) ?>;
var plot5Series = <?= json_encode($series5) ?>;

if (plot5Series.length > 0) {
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
}
</script>

