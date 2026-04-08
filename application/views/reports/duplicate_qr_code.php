<?php
// views/reports/duplicate_qr_report.php

$records    = isset($records) ? $records : [];
$base       = rtrim(base_url(), '/');
$edit_base  = $base . '/forms/child_health/';   // ← edit URL prefix
$view_base  = $base . '/forms/view_child_health/';   // ← view URL prefix
$total      = count($records);
?>

<div class="page-container">
<div class="main-content">

    <!-- Header -->
    <div class="page-header">
        <h2 class="header-title">Duplicate QR Code Report</h2>
    </div>

    <!-- Summary Strip -->
    <div class="row m-b-20 justify-content-center">
        <div class="col-md-4">
            <div class="card shadow-sm" style="border-radius:10px;">
                <div class="card-body py-3 px-4 text-center">
                    <div style="font-size:11px;color:#6c757d;text-transform:uppercase;letter-spacing:.5px;">
                        Duplicate QR Codes Found
                    </div>
                    <div style="font-size:32px;font-weight:700;color:#dc3545;"><?= $total ?></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-hover m-b-0">
                    <thead class="thead-dark">
                        <tr>
                            <th style="width:40px;">#</th>
                            <th style="width:160px;">QR Code</th>
                            <th>Patient Names</th>
                            <th style="width:100px;">Date of Birth</th>
                            <th>Guardian</th>
                            <th style="width:90px;">Count</th>
                            <th>Entered By</th>
                            <th style="width:110px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if (!empty($records)):
                        $serial = 0;
                        foreach ($records as $row):
                            $serial++;
                            // Split comma-separated values into arrays (PHP 5 compatible)
                            $names     = array_map('trim', explode(',', isset($row['names'])     ? $row['names']     : ''));
                            $dobs      = array_map('trim', explode(',', isset($row['dobs'])      ? $row['dobs']      : ''));
                            $guardians = array_map('trim', explode(',', isset($row['guardians']) ? $row['guardians'] : ''));
                            $ids       = array_map('trim', explode(',', isset($row['master_ids'])? $row['master_ids']: ''));
                            $count     = (int) (isset($row['name_count']) ? $row['name_count'] : 0);
                            $first_id  = !empty($ids) ? $ids[0] : null;
                            $view_url  = $view_base . $first_id;
                            $edit_url  = $edit_base . $first_id;
                    ?>
                    <tr style="border-left:4px solid #dc3545;">

                        <!-- Serial -->
                        <td class="text-center align-middle">
                            <span style="display:inline-flex;align-items:center;justify-content:center;
                                         width:28px;height:28px;background:#dc3545;color:#fff;
                                         border-radius:7px;font-size:12px;font-weight:700;">
                                <?= $serial ?>
                            </span>
                        </td>

                        <!-- QR Code -->
                        <td class="align-middle">
                            <span style="display:inline-block;font-size:12px;font-weight:700;
                                         color:#1a3a6e;background:#e8f0fe;border:1px solid #b3cdf9;
                                         border-radius:6px;padding:4px 8px;word-break:break-all;">
                                <?= htmlspecialchars($row['qr_code']) ?>
                            </span>
                        </td>

                        <!-- Patient Names (one per line) -->
                        <td class="align-middle" style="padding:8px 12px;">
                            <?php foreach ($names as $i => $name): ?>
                                <div style="display:flex;align-items:center;gap:6px;
                                            padding:3px 0;<?= $i < count($names)-1 ? 'border-bottom:1px dashed #dee2e6;' : '' ?>">
                                    <span style="display:inline-flex;align-items:center;justify-content:center;
                                                 min-width:20px;height:20px;background:#f0f4ff;border:1px solid #c5d5f7;
                                                 border-radius:4px;font-size:10px;font-weight:700;color:#1a3a6e;">
                                        <?= $i+1 ?>
                                    </span>
                                    <span style="font-size:13px;font-weight:600;color:#212529;">
                                        <?= htmlspecialchars($name) ?>
                                    </span>
                                </div>
                            <?php endforeach; ?>
                        </td>

                        <!-- DOB (one per line) -->
                        <td class="align-middle" style="padding:8px 12px;">
                            <?php foreach ($dobs as $i => $dob): ?>
                                <div style="padding:3px 0;font-size:12px;color:#495057;
                                            <?= $i < count($dobs)-1 ? 'border-bottom:1px dashed #dee2e6;' : '' ?>">
                                    <?= $dob ? date('d M Y', strtotime($dob)) : '<span class="text-muted">—</span>' ?>
                                </div>
                            <?php endforeach; ?>
                        </td>

                        <!-- Guardians (one per line) -->
                        <td class="align-middle" style="padding:8px 12px;">
                            <?php foreach ($guardians as $i => $g): ?>
                                <div style="padding:3px 0;font-size:12px;color:#495057;
                                            <?= $i < count($guardians)-1 ? 'border-bottom:1px dashed #dee2e6;' : '' ?>">
                                    <?= htmlspecialchars($g) ?: '<span class="text-muted">—</span>' ?>
                                </div>
                            <?php endforeach; ?>
                        </td>

                        <!-- Count badge -->
                        <td class="text-center align-middle">
                            <span style="display:inline-block;padding:4px 12px;border-radius:20px;
                                         font-size:13px;font-weight:700;
                                         background:<?= $count >= 3 ? '#fce8e8' : '#fff3cd' ?>;
                                         color:<?= $count >= 3 ? '#b02a37' : '#856404' ?>;
                                         border:1px solid <?= $count >= 3 ? '#f5c2c7' : '#ffd97d' ?>;">
                                <?= $count ?> names
                            </span>
                        </td>

                        <!-- Entered By -->
                        <td class="align-middle" style="font-size:12px;color:#495057;">
                            <?= htmlspecialchars(isset($row['reported_by']) ? $row['reported_by'] : '') ?>
                        </td>

                        <!-- Actions -->
                        <td class="text-center align-middle">
                            <div style="display:flex;gap:6px;justify-content:center;flex-wrap:wrap;">

                                <!-- View button -->
                                <a href="<?= $view_url ?>" target="_blank"
                                   class="btn btn-sm"
                                   style="background:#e8f0fe;color:#1a3a6e;border:1px solid #b3cdf9;
                                          padding:4px 9px;"
                                   title="View Form">
                                    <i class="fa fa-eye"></i>
                                </a>

                                <!-- Copy edit URL button -->
                                <button type="button"
                                        class="btn btn-sm btn-copy"
                                        data-url="<?= $edit_url ?>"
                                        style="background:#fff8e1;color:#856404;border:1px solid #ffd97d;
                                               padding:4px 9px;"
                                        title="Copy Edit URL">
                                    <i class="fa fa-copy"></i>
                                </button>

                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-center p-4 text-muted">
                                <i class="fa fa-check-circle" style="font-size:24px;color:#28a745;"></i><br>
                                No duplicate QR codes found
                            </td>
                        </tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div><!-- /.table-responsive -->
        </div>
    </div><!-- /.card -->

</div>

<!-- Copy-to-clipboard script -->
<script>
document.querySelectorAll('.btn-copy').forEach(function(btn) {
    btn.addEventListener('click', function() {
        var url = this.getAttribute('data-url');
        if (navigator.clipboard && window.isSecureContext) {
            navigator.clipboard.writeText(url).then(function() {
                showCopyToast(url);
            });
        } else {
            // Fallback for non-HTTPS
            var ta = document.createElement('textarea');
            ta.value = url;
            ta.style.position = 'fixed';
            ta.style.opacity  = '0';
            document.body.appendChild(ta);
            ta.focus(); ta.select();
            document.execCommand('copy');
            document.body.removeChild(ta);
            showCopyToast(url);
        }
    });
});

function showCopyToast(url) {
    // Simple toast notification
    var toast = document.createElement('div');
    toast.innerHTML = '<i class="fa fa-clipboard-check"></i> Edit URL copied!';
    toast.style.cssText =
        'position:fixed;bottom:30px;right:30px;z-index:9999;' +
        'background:#1a3a6e;color:#fff;padding:10px 18px;' +
        'border-radius:8px;font-size:13px;box-shadow:0 4px 12px rgba(0,0,0,.2);' +
        'opacity:0;transition:opacity .3s;';
    document.body.appendChild(toast);
    setTimeout(function(){ toast.style.opacity = '1'; }, 10);
    setTimeout(function(){
        toast.style.opacity = '0';
        setTimeout(function(){ document.body.removeChild(toast); }, 400);
    }, 2500);
}
</script>