<?php
// views/reports/qr_details.php

$qr_code = isset($qr_code) ? $qr_code : '';
$records = isset($records) ? $records : [];
$total_records = isset($total_records) ? $total_records : 0;
?>

<div class="page-container">
<div class="main-content">

    <!-- Header -->
    <div class="page-header">
        <h2 class="header-title">QR Code Details: <?= htmlspecialchars($qr_code) ?></h2>
        <p style="color:#6c757d;margin-top:8px;">Total Records: <strong><?= $total_records ?></strong></p>
    </div>

    <!-- Edit Section -->
    <div class="card m-b-20">
        <div class="card-body">
            <h5 class="card-title">Edit Common Fields</h5>
            <p style="color:#6c757d;font-size:12px;margin-bottom:12px;">
                Enter values below and check records to update only selected ones
            </p>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="patient_name">Patient Name</label>
                        <input type="text" class="form-control" id="patient_name" 
                               placeholder="Enter patient name">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="father_name">Father Name</label>
                        <input type="text" class="form-control" id="father_name" 
                               placeholder="Enter father name">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Records Table -->
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-hover m-b-0">
                    <thead class="thead-dark">
                        <tr>
                            <th style="width:40px;">
                                <input type="checkbox" id="select_all" title="Select All">
                            </th>
                            <th style="width:40px;">#</th>
                            <th>Patient Name</th>
                            <th>Father Name</th>
                            <th style="width:100px;">Date of Birth</th>
                            <th>Vaccinator</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if (!empty($records)):
                        $serial = 0;
                        foreach ($records as $record):
                            $serial++;
                            $master_id = $record['master_id'];
                    ?>
                        <tr>
                            <td class="text-center">
                                <input type="checkbox" class="record-checkbox" 
                                       value="<?= htmlspecialchars($master_id) ?>">
                            </td>
                            <td class="text-center align-middle">
                                <span style="display:inline-flex;align-items:center;justify-content:center;
                                             width:28px;height:28px;background:#007bff;color:#fff;
                                             border-radius:50%;font-size:12px;font-weight:700;">
                                    <?= $serial ?>
                                </span>
                            </td>
                            <td class="align-middle">
                                <?= htmlspecialchars($record['patient_name']) ?>
                            </td>
                            <td class="align-middle">
                                <?= htmlspecialchars($record['guardian_name']) ?: '<span class="text-muted">—</span>' ?>
                            </td>
                            <td class="align-middle">
                                <?= $record['dob'] ? date('d M Y', strtotime($record['dob'])) : '—' ?>
                            </td>
                            <td class="align-middle">
                                <?= htmlspecialchars($record['vaccinator_name']) ?: '<span class="text-muted">—</span>' ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center p-4 text-muted">
                                No records found
                            </td>
                        </tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Save Button -->
    <div style="margin-top:20px;text-align:right;">
        <button type="button" class="btn btn-success btn-lg" id="save_button" 
                style="padding:10px 30px;font-size:16px;">
            <i class="fa fa-save"></i> Save Changes
        </button>
    </div>

</div>

<script>
// Select All checkbox
document.getElementById('select_all').addEventListener('change', function() {
    var checkboxes = document.querySelectorAll('.record-checkbox');
    checkboxes.forEach(function(cb) {
        cb.checked = this.checked;
    }, this);
});

// Save button
document.getElementById('save_button').addEventListener('click', function() {
    var patient_name = document.getElementById('patient_name').value;
    var father_name = document.getElementById('father_name').value;
    var checkboxes = document.querySelectorAll('.record-checkbox:checked');
    
    if (checkboxes.length === 0) {
        alert('Please select at least one record');
        return;
    }
    
    var selected_ids = [];
    checkboxes.forEach(function(cb) {
        selected_ids.push(cb.value);
    });
    
    // AJAX call
    var xhr = new XMLHttpRequest();
    xhr.open('POST', '<?= base_url('reports/update_qr_records') ?>', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    
    var postData = 'qr_code=' + encodeURIComponent('<?= htmlspecialchars($qr_code) ?>') +
                   '&patient_name=' + encodeURIComponent(patient_name) +
                   '&father_name=' + encodeURIComponent(father_name) +
                   '&selected_ids=' + encodeURIComponent(JSON.stringify(selected_ids));
    
    xhr.onload = function() {
        if (xhr.status === 200) {
            try {
                var response = JSON.parse(xhr.responseText);
                if (response.success) {
                    showSuccessToast(response.message);
                    // Reload page after 2 seconds
                    setTimeout(function() {
                        location.reload();
                    }, 2000);
                } else {
                    alert('Error: ' + response.message);
                }
            } catch(e) {
                alert('Error: ' + e.message);
            }
        }
    };
    
    xhr.send(postData);
});

function showSuccessToast(message) {
    var toast = document.createElement('div');
    toast.innerHTML = '<i class="fa fa-check-circle"></i> ' + message;
    toast.style.cssText =
        'position:fixed;bottom:30px;right:30px;z-index:9999;' +
        'background:#28a745;color:#fff;padding:10px 18px;' +
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