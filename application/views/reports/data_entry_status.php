<div class="page-container">
<div class="main-content">

<div class="page-header">
    <h2 class="header-title">Data Entry Status</h2>
</div>

<div class="card">
<div class="card-body">

<div class="table-responsive">
<table id="data-table" class="table table-bordered table-hover">
<thead class="thead-light">
<tr>
    <th rowspan="2">#</th>
    <th rowspan="2">User</th>
    <th colspan="2" class="text-center">Child Health (Client Type)</th>
    <th colspan="2" class="text-center">Child Health (Visit Type)</th>
    <th colspan="2" class="text-center">OPD / MNCH (Client Type)</th>
    <th colspan="2" class="text-center">OPD / MNCH (Visit Type)</th>
</tr>
<tr>
    <th>New</th>
    <th>Followup</th>
    <th>Fixed</th>
    <th>Outreach</th>
    <th>New</th>
    <th>Followup</th>
    <th>OPD</th>
    <th>MNCH</th>
</tr>
</thead>
<tbody>
<?php $i=1; foreach($report as $r): ?>
<tr>
    <td><?= $i++ ?></td>
    <td><?= htmlspecialchars($r->user) ?></td>

    <!-- Child Health -->
    <td><span class="badge badge-info"><?= $r->ch_new ?></span></td>
    <td><span class="badge badge-primary"><?= $r->ch_follow ?></span></td>
    <td><span class="badge badge-warning"><?= $r->ch_fixed ?></span></td>
    <td><span class="badge badge-secondary"><?= $r->ch_outreach ?></span></td>

    <!-- OPD / MNCH -->
    <td><span class="badge badge-success"><?= $r->opd_new ?></span></td>
    <td><span class="badge badge-dark"><?= $r->opd_follow ?></span></td>
    <td><span class="badge badge-danger"><?= $r->opd ?></span></td>
    <td><span class="badge badge-info"><?= $r->mnch ?></span></td>
</tr>
<?php endforeach; ?>
</tbody>
</table>


</div>

</div>
</div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="<?php echo base_url('assets/vendors/datatables/jquery.dataTables.min.js') ?>"></script>
<script src="<?php echo base_url('assets/vendors/datatables/dataTables.bootstrap.min.js') ?>"></script>
<script>
    $('#data-table').DataTable();
</script>