<div class="page-container">
<div class="main-content">

<div class="page-header">
    <h2 class="header-title">Child Health Report</h2>
</div>

<div class="card">
<div class="card-body">

<form method="get" class="mb-4">
    <div class="row">

    <div class="col-md-3">
    <label>From Date</label>
    <input type="date" name="from_date"
           value="<?= isset($filters['from_date']) ? $filters['from_date'] : '' ?>"
           class="form-control">
    </div>

    <div class="col-md-3">
    <label>To Date</label>
    <input type="date" name="to_date"
           value="<?= isset($filters['to_date']) ? $filters['to_date'] : '' ?>"
           class="form-control">
    </div>

    <div class="col-md-3">
    <label>UC</label>
    <select name="uc_id" class="form-control" onchange="this.form.submit()">
    <option value="">All</option>
    <?php foreach($ucs as $u): ?>
    <option value="<?= $u->pk_id ?>"
    <?= (!empty($filters['uc_id']) && $filters['uc_id'] == $u->pk_id) ? 'selected' : '' ?>>
    <?= $u->uc ?>
    </option>
    <?php endforeach; ?>
    </select>
    </div>

    <div class="col-md-3">
    <label>Facility</label>
    <select name="facility_id" class="form-control">
    <option value="">All</option>
    <?php foreach($facilities as $f): ?>
    <option value="<?= $f->id ?>"
    <?= (!empty($filters['facility_id']) && $filters['facility_id'] == $f->id) ? 'selected' : '' ?>>
    <?= $f->facility_name ?>
    </option>
    <?php endforeach; ?>
    </select>
    </div>

    <div class="col-md-3">
    <label>Search</label>
    <input type="text"
           name="search"
           placeholder="Patient name / QR Code"
           value="<?= isset($filters['search']) ? $filters['search'] : '' ?>"
           class="form-control">
    </div>

    <div class="col-md-3 d-flex align-items-end">
        <button type="submit" class="btn btn-primary mr-2">Filter</button>
        <a href="<?= base_url('forms/child_health_report') ?>" class="btn btn-secondary">Clear</a>
    </div>

    </div>
</form>
    
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
    
<?php if($this->session->userdata('user_id') == $r->created_by): ?>
<a href="<?= base_url('forms/child_health/'.$r->master_id) ?>"
   class="btn btn-sm btn-success">
   Edit
</a>
<?php endif; ?>
    
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
    
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>    
        <!-- page js -->
<script src="<?php echo base_url('assets/vendors/datatables/jquery.dataTables.min.js') ?>"></script>
<script src="<?php echo base_url('assets/vendors/datatables/dataTables.bootstrap.min.js') ?>"></script>
<script>
    $('#data-table').DataTable();
</script>
