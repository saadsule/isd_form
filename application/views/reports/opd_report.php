<div class="page-container">
<div class="main-content">

<div class="page-header">
    <h2 class="header-title">OPD MNCH Report</h2>
</div>

<div class="card">
<div class="card-body">

<div class="table-responsive">

<table class="table table-bordered table-hover">

<thead class="thead-light">
<tr>
    <th>#</th>
    <th>Date</th>
    <th>Patient</th>
    <th>Guardian</th>
    <th>District</th>
    <th>UC</th>
    <th>Visit</th>
    <th>Client</th>
    <th width="120">Action</th>
</tr>
</thead>

<tbody>

<?php $i=1; foreach($records as $r): ?>

<tr>
<td><?= $i++ ?></td>
<td><?= date('d-M-Y',strtotime($r->form_date)) ?></td>
<td><?= htmlspecialchars($r->patient_name) ?></td>
<td><?= htmlspecialchars($r->guardian_name) ?></td>
<td><?= htmlspecialchars($r->district) ?></td>
<td><?= htmlspecialchars($r->uc) ?></td>

<td>
<span class="badge badge-warning">
<?= $r->visit_type ?>
</span>
</td>

<td>
<span class="badge badge-info">
<?= $r->client_type ?>
</span>
</td>

<td>
<a href="<?= base_url('forms/view_opd_mnch/'.$r->id) ?>"
   class="btn btn-sm btn-primary">
   View
</a>
    
<?php if($this->session->userdata('user_id') == $r->created_by): ?>
<a href="<?= base_url('forms/opd_mnch/'.$r->id) ?>"
   class="btn btn-sm btn-success">
   Edit
</a>
<?php endif; ?>

</td>

</tr>

<?php endforeach; ?>

</tbody>
</table>

</div>
</div>
</div>

</div>
