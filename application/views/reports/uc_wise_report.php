<div class="page-container">
<div class="main-content">

<div class="page-header">
    <h2 class="header-title">No. of forms digitized - UC Wise</h2>
</div>

<div class="card">
<div class="card-body">

<div class="table-responsive">
<table id="data-table" class="table table-bordered table-hover">
<thead class="thead-light">
<tr>
    <th>#</th>
    <th>UC</th>
    <th>Child Health</th>
    <th>OPD / MNCH</th>
</tr>
</thead>
<tbody>
<?php $i=1; foreach($report as $r): ?>
<tr>
    <td><?= $i++ ?></td>

    <!-- UC Name -->
    <td><?= htmlspecialchars($r->uc_name) ?></td>

    <!-- Child Health Total -->
    <td>
        <span class="badge badgex-info"><?= $r->child_health_total ?></span>
    </td>

    <!-- OPD / MNCH Total -->
    <td>
        <span class="badge badgex-success"><?= $r->opd_total ?></span>
    </td>
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
