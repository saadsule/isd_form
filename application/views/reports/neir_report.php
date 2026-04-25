<?php
$start_date = isset($start_date) ? $start_date : '';
$end_date   = isset($end_date)   ? $end_date   : '';
$options    = isset($options)    ? $options    : array();
$records    = isset($records)    ? $records    : array();
?>
<style>
#neir_table th, #neir_table td { padding: 4px 6px !important; font-size: 11px; white-space: nowrap; vertical-align: middle !important; }
#neir_table thead tr th { background-color: #1a3c5e !important; color: #fff !important; text-align: center; position: sticky; top: 0; z-index: 10; }
#neir_table tfoot tr td { background-color: #1a3c5e !important; color: #fff !important; font-weight: 700; text-align: center; }
.neir-table-wrap { overflow-x: auto; max-height: 75vh; overflow-y: auto; }
</style>

<div class="page-container">
<div class="main-content">

    <div class="page-header">
        <h2 class="header-title">NEIR Report &mdash; North Waziristan</h2>
    </div>

    <!-- Filter -->
    <div class="card">
        <div class="card-body">
            <form method="POST" action="<?= site_url('reports/neir_report') ?>">
                <div class="row align-items-end">
                    <div class="col-md-3">
                        <div class="form-group m-b-0">
                            <label>Start Date</label>
                            <input type="date" name="start_date" class="form-control" value="<?= htmlspecialchars($start_date) ?>">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group m-b-0">
                            <label>End Date</label>
                            <input type="date" name="end_date" class="form-control" value="<?= htmlspecialchars($end_date) ?>">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group m-b-0">
                            <label>&nbsp;</label><br>
                            <button type="submit" class="btn btn-primary m-r-5"><i class="fa fa-search"></i> Search</button>
                            <a href="<?= site_url('reports/neir_report') ?>" class="btn btn-default"><i class="fa fa-refresh"></i> Reset</a>
                        </div>
                    </div>
                    <div class="col-md-3 text-right">
                        <div class="form-group m-b-0">
                            <label>&nbsp;</label><br>
                            <button type="button" class="btn btn-success" id="exportNeirBtn"><i class="fa fa-file-excel-o"></i> Export Excel</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Table -->
    <div class="card">
        <div class="card-body p-0">
        <?php if (empty($records)): ?>
            <div class="p-4 text-center text-muted">
                <i class="fa fa-info-circle" style="font-size:24px;color:#17a2b8;"></i><br>
                No records found for the selected date range.
            </div>
        <?php else:
            $tot_enrolled = 0; $tot_vaccinated = 0; $tot_grand = 0;
            $tot_options = array();
            foreach (array_keys($options) as $col) { $tot_options[$col] = 0; }
            foreach ($records as $row) {
                $tot_enrolled   += (int) $row['children_enrolled'];
                $tot_vaccinated += (int) $row['children_vaccinated'];
                $tot_grand      += (int) $row['grand_total'];
                foreach (array_keys($options) as $col) {
                    $tot_options[$col] += (int) (isset($row[$col]) ? $row[$col] : 0);
                }
            }
        ?>
        <div class="neir-table-wrap">
            <table class="table table-bordered table-hover m-b-0" id="neir_table">
                <thead>
                    <tr>
                        <th style="width:35px;">#</th>
                        <th style="width:120px;">UC</th>
                        <th style="width:120px;">Facility</th>
                        <th style="width:120px;">Vaccinator Name</th>
                        <th style="width:80px;">Age Group</th>
                        <th style="width:90px;">Children Enrolled</th>
                        <th style="width:90px;">Children Vaccinated</th>
                        <?php foreach ($options as $col => $info): ?>
                            <th style="width:55px;"><?= htmlspecialchars($info['label']) ?></th>
                        <?php endforeach; ?>
                        <th style="width:70px;">Grand Total</th>
                    </tr>
                </thead>
                <tbody>
                <?php $serial = 0; foreach ($records as $row): $serial++; ?>
                <tr>
                    <td class="text-center">
                        <span style="display:inline-flex;align-items:center;justify-content:center;width:20px;height:20px;background:#1a3c5e;color:#fff;border-radius:4px;font-size:10px;font-weight:700;"><?= $serial ?></span>
                    </td>
                    <td><?= htmlspecialchars(isset($row['uc'])         ? $row['uc']         : '') ?></td>
                    <td><?= htmlspecialchars(isset($row['facility_name']) ? $row['facility_name'] : '') ?></td>
                    <td><?= htmlspecialchars(isset($row['vaccinator_name']) ? $row['vaccinator_name'] : '') ?></td>
                    <td class="text-center"><?= htmlspecialchars(isset($row['age_group']) ? $row['age_group'] : '') ?></td>
                    <td class="text-center"><?= (int) $row['children_enrolled'] ?></td>
                    <td class="text-center"><?= (int) $row['children_vaccinated'] ?></td>
                    <?php foreach (array_keys($options) as $col): ?>
                        <td class="text-center"><?= isset($row[$col]) ? (int) $row[$col] : 0 ?></td>
                    <?php endforeach; ?>
                    <td class="text-center" style="font-weight:700;"><?= (int) $row['grand_total'] ?></td>
                </tr>
                <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="5">TOTAL</td>
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

</div>
</div>

<script>
document.getElementById('exportNeirBtn') && document.getElementById('exportNeirBtn').addEventListener('click', function(){
    var table = document.getElementById('neir_table').cloneNode(true);
    table.querySelectorAll('th, td').forEach(function(c){
        c.style.border = '1px solid #7FA6C7'; c.style.padding = '6px 8px';
        c.style.fontFamily = 'Calibri, Arial, sans-serif'; c.style.fontSize = '12px';
    });
    table.querySelectorAll('thead tr th, tfoot tr td').forEach(function(c){
        c.style.backgroundColor = '#1a3c5e'; c.style.color = '#ffffff'; c.style.fontWeight = 'bold';
    });
    table.querySelectorAll('tbody tr').forEach(function(r, i){ if(i % 2 === 1){ r.style.backgroundColor = '#F0F4FA'; } });
    var html = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel">'
             + '<head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet>'
             + '<x:Name>NEIR Report</x:Name><x:WorksheetOptions><x:FreezePanes/><x:FrozenNoSplit/>'
             + '<x:SplitHorizontal>1</x:SplitHorizontal><x:TopRowBottomPane>1</x:TopRowBottomPane>'
             + '</x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--></head>'
             + '<body>' + table.outerHTML + '</body></html>';
    var blob = new Blob(['\ufeff', html], {type: 'application/vnd.ms-excel;charset=utf-8;'});
    var a = document.createElement('a'); a.href = URL.createObjectURL(blob);
    a.download = 'NEIR_Report_<?= date('Y-m-d') ?>.xls'; a.click();
});
</script>