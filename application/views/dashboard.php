<!DOCTYPE html>
<html>
<head>
<title>Health Dashboard</title>

<link rel="stylesheet"
href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">

<script src="https://code.highcharts.com/highcharts.js"></script>

<style>

body{
    background:#eef1f5;
}

.kpi{
    background:#fff;
    border-radius:10px;
    padding:25px;
    text-align:center;
    box-shadow:0 4px 12px rgba(0,0,0,.06);
}

.kpi h2{
    font-weight:800;
    font-size:34px;
}

.chart-card{
    background:#fff;
    padding:20px;
    border-radius:10px;
    box-shadow:0 4px 12px rgba(0,0,0,.05);
    margin-bottom:25px;
}

</style>
</head>

<body>

<div class="container-fluid mt-4">

<!-- KPI -->
<div class="row mb-4">

<div class="col-md-3">
<div class="kpi" style="border-left:5px solid #007bff;">
<h6>Child Records</h6>
<h2><?= $child_total ?></h2>
</div>
</div>

<div class="col-md-3">
<div class="kpi" style="border-left:5px solid #007bff;">
<h6>OPD/MNCH Records</h6>
<h2><?= $opd_total ?></h2>
</div>
</div>

<div class="col-md-3">
<div class="kpi" style="border-left:5px solid #007bff;">
<h6>Total Patients</h6>
<h2><?= $patients ?></h2>
</div>
</div>

<!-- Dummy -->
<div class="col-md-3">
    <div class="kpi" style="border-left:5px solid #007bff;">
        <h6>Today's Visits</h6>
        <h2>15</h2>
    </div>
</div>

    
</div>


<!-- CHARTS -->

<div class="row">

<div class="col-md-6">
<div class="chart-card">
<div id="genderChart"></div>
</div>
</div>

<div class="col-md-6">
<div class="chart-card">
<div id="clientChart"></div>
</div>
</div>

</div>


<div class="row">

<div class="col-md-6">
<div class="chart-card">
<div id="monthlyChart"></div>
</div>
</div>

<div class="col-md-6">
<div class="chart-card">
<div id="districtChart"></div>
</div>
</div>

</div>

<div class="chart-card">
<div id="diagnosisChart"></div>
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

</body>
</html>
