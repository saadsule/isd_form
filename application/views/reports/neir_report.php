<?php
$start_date = isset($start_date) ? $start_date : '';
$end_date   = isset($end_date)   ? $end_date   : '';
$options    = isset($options)    ? $options    : array();
$records    = isset($records)    ? $records    : array();
?>
<div class="page-container">
<div class="main-content">

    <!-- Header -->
    <div class="page-header">
        <h2 class="header-title">NEIR Report &mdash; North Waziristan</h2>
    </div>

    <!-- Filter Card -->
    <div class="card">
        <div class="card-body">
            <form method="POST" action="<?= site_url('reports/neir_report') ?>">
                <div class="row align-items-end">

                    <div class="col-md-3">
                        <div class="form-group m-b-0">
                            <label>Start Date</label>
                            <input type="date" name="start_date" class="form-control"
                                   value="<?= htmlspecialchars($start_date) ?>">
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group m-b-0">
                            <label>End Date</label>
                            <input type="date" name="end_date" class="form-control"
                                   value="<?= htmlspecialchars($end_date) ?>">
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group m-b-0">
                            <label>&nbsp;</label><br>
                            <button type="submit" class="btn btn-primary m-r-5">
                                <i class="fa fa-search"></i> Search
                            </button>
                            <a href="<?= site_url('reports/neir_report') ?>" class="btn btn-default">
                                <i class="fa fa-refresh"></i> Reset
                            </a>
                        </div>
                    </div>

                    <div class="col-md-3 text-right">
                        <div class="form-group m-b-0">
                            <label>&nbsp;</label><br>
                            <button type="button" class="btn btn-success" id="exportNeirBtn">
                                <i class="fa fa-file-excel-o"></i> Export Excel
                            </button>
                        </div>
                    </div>

                </div>
            </form>
        </div>
    </div>

    <!-- Report Table -->
    <div class="card">
        <div class="card-body p-0">

        <?php if (empty($records)): ?>
            <div class="p-4 text-center text-muted">
                <i class="fa fa-info-circle" style="font-size:24px;color:#17a2b8;"></i><br>
                No records found for the selected date range.
            </div>
        <?php else:

            // ---- pre-compute totals ----
            $tot_enrolled   = 0;
            $tot_vaccinated = 0;
            $tot_options    = array();
            foreach (array_keys($options) as $col) { $tot_options[$col] = 0; }
            $tot_grand = 0;

            foreach ($records as $row) {
                $tot_enrolled   += (int) $row['children_enrolled'];
                $tot_vaccinated += (int) $row['children_vaccinated'];
                $tot_grand      += (int) $row['grand_total'];
                foreach (array_keys($options) as $col) {
                    $tot_options[$col] += (int) (isset($row[$col]) ? $row[$col] : 0);
                }
            }
        ?>

        <div class="table-responsive">
            <table class="table table-bordered table-hover m-b-0" id="neir_table"
                   style="font-size:11px; white-space:nowrap;">

                <thead>
                    <tr class="thead-dark" style="text-align:center; vertical-align:middle;">
                        <th>#</th>
                        <th>Province</th>
                        <th>District</th>
                        <th>UC</th>
                        <th>Health Facility</th>
                        <th>Age Group</th>
                        <th>Vaccinator Name</th>
                        <th>Organization</th>
                        <th>Strategy</th>
                        <th>Children Enrolled</th>
                        <th>Children Vaccinated</th>
                        <?php foreach ($options as $col => $info): ?>
                            <th><?= htmlspecialchars($info['label']) ?></th>
                        <?php endforeach; ?>
                        <th>Grand Total</th>
                    </tr>
                </thead>

                <tbody>
                <?php $serial = 0; foreach ($records as $row): $serial++; ?>
                <tr>
                    <td class="text-center align-middle">
                        <span style="display:inline-flex;align-items:center;justify-content:center;
                                     width:22px;height:22px;background:#1a3c5e;color:#fff;
                                     border-radius:4px;font-size:10px;font-weight:700;">
                            <?= $serial ?>
                        </span>
                    </td>
                    <td class="align-middle"><?= htmlspecialchars(isset($row['province'])        ? $row['province']        : '') ?></td>
                    <td class="align-middle"><?= htmlspecialchars(isset($row['district'])        ? $row['district']        : '') ?></td>
                    <td class="align-middle"><?= htmlspecialchars(isset($row['uc'])              ? $row['uc']              : '') ?></td>
                    <td class="align-middle"><?= htmlspecialchars(isset($row['facility_name'])   ? $row['facility_name']   : '') ?></td>
                    <td class="text-center align-middle"><?= htmlspecialchars(isset($row['age_group'])      ? $row['age_group']      : '') ?></td>
                    <td class="align-middle"><?= htmlspecialchars(isset($row['vaccinator_name']) ? $row['vaccinator_name'] : '') ?></td>
                    <td class="align-middle"><?= htmlspecialchars(isset($row['organization'])    ? $row['organization']    : '') ?></td>
                    <td class="align-middle"><?= htmlspecialchars(strtoupper(isset($row['strategy']) ? $row['strategy'] : '')) ?></td>
                    <td class="text-center align-middle"><?= (int) $row['children_enrolled'] ?></td>
                    <td class="text-center align-middle"><?= (int) $row['children_vaccinated'] ?></td>
                    <?php foreach (array_keys($options) as $col): ?>
                        <td class="text-center align-middle"><?= isset($row[$col]) ? (int) $row[$col] : 0 ?></td>
                    <?php endforeach; ?>
                    <td class="text-center align-middle" style="font-weight:700;"><?= (int) $row['grand_total'] ?></td>
                </tr>
                <?php endforeach; ?>
                </tbody>

                <tfoot>
                    <tr class="thead-dark" style="text-align:center; font-weight:700; vertical-align:middle;">
                        <td colspan="9">TOTAL</td>
                        <td><?= $tot_enrolled ?></td>
                        <td><?= $tot_vaccinated ?></td>
                        <?php foreach (array_keys($options) as $col): ?>
                            <td><?= $tot_options[$col] ?></td>
                        <?php endforeach; ?>
                        <td><?= $tot_grand ?></td>
                    </tr>
                </tfoot>

            </table>
        </div>

        <?php endif; ?>
        </div>
    </div>

</div><!-- /.main-content -->
</div><!-- /.page-container -->

<script>
document.getElementById('exportNeirBtn') && document.getElementById('exportNeirBtn').addEventListener('click', function(){
    var table = document.getElementById('neir_table').cloneNode(true);
    table.querySelectorAll('th, td').forEach(function(c){
        c.style.border     = '1px solid #7FA6C7';
        c.style.padding    = '6px 8px';
        c.style.fontFamily = 'Calibri, Arial, sans-serif';
        c.style.fontSize   = '12px';
    });
    table.querySelectorAll('thead tr th, tfoot tr td').forEach(function(c){
        c.style.backgroundColor = '#1a3c5e';
        c.style.color           = '#ffffff';
        c.style.fontWeight      = 'bold';
    });
    table.querySelectorAll('tbody tr').forEach(function(r, i){
        if(i % 2 === 1){ r.style.backgroundColor = '#F0F4FA'; }
    });
    var html = '<html xmlns:o="urn:schemas-microsoft-com:office:office"'
             + ' xmlns:x="urn:schemas-microsoft-com:office:excel">'
             + '<head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets>'
             + '<x:ExcelWorksheet><x:Name>NEIR Report</x:Name>'
             + '<x:WorksheetOptions><x:FreezePanes/><x:FrozenNoSplit/>'
             + '<x:SplitHorizontal>1</x:SplitHorizontal>'
             + '<x:TopRowBottomPane>1</x:TopRowBottomPane>'
             + '</x:WorksheetOptions></x:ExcelWorksheet>'
             + '</x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--></head>'
             + '<body>' + table.outerHTML + '</body></html>';
    var blob = new Blob(['\ufeff', html], {type: 'application/vnd.ms-excel;charset=utf-8;'});
    var a    = document.createElement('a');
    a.href   = URL.createObjectURL(blob);
    a.download = 'NEIR_Report_<?= date('Y-m-d') ?>.xls';
    a.click();
});
</script>