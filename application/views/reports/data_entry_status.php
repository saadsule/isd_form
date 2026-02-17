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
    <th rowspan="2">Total</th> <!-- Row total -->
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
<?php 
// Initialize column totals
$totals = [
    'ch_new'=>0, 'ch_follow'=>0, 'ch_fixed'=>0, 'ch_outreach'=>0,
    'opd_new'=>0, 'opd_follow'=>0, 'opd'=>0, 'mnch'=>0
];

$i=1; 
foreach($report as $r): 

    // Calculate row total
    $row_total = $r->ch_new + $r->ch_follow + $r->ch_fixed + $r->ch_outreach
                 + $r->opd_new + $r->opd_follow + $r->opd + $r->mnch;

    // Add to column totals
    $totals['ch_new'] += $r->ch_new;
    $totals['ch_follow'] += $r->ch_follow;
    $totals['ch_fixed'] += $r->ch_fixed;
    $totals['ch_outreach'] += $r->ch_outreach;
    $totals['opd_new'] += $r->opd_new;
    $totals['opd_follow'] += $r->opd_follow;
    $totals['opd'] += $r->opd;
    $totals['mnch'] += $r->mnch;
?>
<tr>
    <td><?= $i++ ?></td>
    <td><?= htmlspecialchars($r->full_name) ?></td>

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

    <td><strong><?= $row_total ?></strong></td> <!-- Row total -->
</tr>
<?php endforeach; ?>
</tbody>

<!-- Column totals -->
<tfoot>
<tr>
    <th colspan="2">Total</th>
    <th><?= $totals['ch_new'] ?></th>
    <th><?= $totals['ch_follow'] ?></th>
    <th><?= $totals['ch_fixed'] ?></th>
    <th><?= $totals['ch_outreach'] ?></th>
    <th><?= $totals['opd_new'] ?></th>
    <th><?= $totals['opd_follow'] ?></th>
    <th><?= $totals['opd'] ?></th>
    <th><?= $totals['mnch'] ?></th>
    <th>
        <strong>
        <?= array_sum($totals) // Total of all columns ?>
        </strong>
    </th>
</tr>
</tfoot>
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