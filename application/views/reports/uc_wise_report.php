<div class="page-container">
<div class="main-content">

<div class="page-header">
    <h2 class="header-title">No. of forms digitized - UC Wise</h2>
</div>

<div class="card">
<div class="card-body">

<div class="table-responsive">
<table class="table table-bordered table-hover">
    <thead class="thead-light">
        <tr>
            <th>#</th>
            <th>UC</th>
            <th>Child Health</th>
            <th>OPD / MNCH</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
        <?php 
        $i = 1; 
        $total_child = 0;
        $total_opd = 0;
        $grand_total = 0;
        foreach($report as $r): 
            $uc_total = $r->child_health_total + $r->opd_total;
            $total_child += $r->child_health_total;
            $total_opd += $r->opd_total;
            $grand_total += $uc_total;
        ?>
        <tr>
            <td><?= $i++ ?></td>
            <td><?= htmlspecialchars($r->uc_name) ?></td>
            <td><?= $r->child_health_total ?></td>
            <td><?= $r->opd_total ?></td>
            <td><strong><?= $uc_total ?></strong></td>
        </tr>
        <?php endforeach; ?>

        <!-- Total Row -->
        <tr class="table-secondary fw-bold">
            <td></td>
            <td><strong>Total</strong></td>
            <td><strong><?= $total_child ?></strong></td>
            <td><strong><?= $total_opd ?></strong></td>
            <td><strong><?= $grand_total ?></strong></td>
        </tr>
    </tbody>
</table>
</div>

</div>
</div>
</div>