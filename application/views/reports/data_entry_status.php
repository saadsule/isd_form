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
    <td><?= $r->ch_new ?></td>
    <td><?= $r->ch_follow ?></td>
    <td><?= $r->ch_fixed ?></td>
    <td><?= $r->ch_outreach ?></td>

    <!-- OPD / MNCH -->
    <td><?= $r->opd_new ?></td>
    <td><?= $r->opd_follow ?></td>
    <td><?= $r->opd ?></td>
    <td><?= $r->mnch ?></td>
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