<div class="page-container">
<div class="main-content">

<div class="page-header">
    <h2 class="header-title">Child Health Report</h2>
</div>

<div class="card">
<div class="card-body">

<div class="table-responsive">

<table id="data-table" class="table">

<thead class="thead-light">
<tr>
    <th>#</th>
    <th>Date</th>
    <th>Patient</th>
    <th>Guardian</th>
    <th>District</th>
    <th>UC</th>
    <th>Gender</th>
    <th>Visit</th>
    <th>Client</th>
    <th width="120">Action</th>
</tr>
</thead>

<tbody>

<?php if(!empty($records)): ?>

<?php $i=1; foreach($records as $r): ?>

<tr>

<td><?= $i++ ?></td>

<td><?= date('d-M-Y',strtotime($r->form_date)) ?></td>

<td><?= htmlspecialchars($r->patient_name) ?></td>

<td><?= htmlspecialchars($r->guardian_name) ?></td>

<td><?= htmlspecialchars($r->district) ?></td>

<td><?= htmlspecialchars($r->uc) ?></td>

<td><?= $r->gender ?></td>

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

<a href="<?= base_url('forms/view_child_health/'.$r->master_id) ?>"
   class="btn btn-sm btn-primary">

   View

</a>

</td>

</tr>

<?php endforeach; ?>

<?php else: ?>

<tr>
<td colspan="9" class="text-center">
No records found
</td>
</tr>

<?php endif; ?>

</tbody>
</table>

</div>

</div>
</div>

</div>
    
<script>
    $('#data-table').DataTable();
</script>
