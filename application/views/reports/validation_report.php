<div class="page-container">
<div class="main-content">

<div class="page-header">
<h2 class="header-title">Record Validation Report</h2>
</div>

<?php
$records  = isset($report['records']) ? $report['records'] : [];
$verified = isset($report['verified_total']) ? $report['verified_total'] : 0;
$reported = isset($report['reported_total']) ? $report['reported_total'] : 0;
?>

<!-- Slim Summary Card -->
<div class="row mb-3 justify-content-center">
<div class="col-md-4">
<div class="card shadow-sm" style="border-radius:8px;">
<div class="card-body py-2 px-3">
<div class="row text-center">
<div class="col-6 border-end">
<div style="font-size:12px; color:#6c757d;">Verified</div>
<div style="font-size:20px; font-weight:600; color:#28a745;">
<?= $verified ?>
</div>
</div>
<div class="col-6">
<div style="font-size:12px; color:#6c757d;">Reported</div>
<div style="font-size:20px; font-weight:600; color:#dc3545;">
<?= $reported ?>
</div>
</div>
</div>
</div>
</div>
</div>
</div>

<!-- Validation Table -->
<div class="table-responsive">
<table class="table table-bordered table-hover">
<thead class="thead-light">
<tr>
<th>#</th>
<th>Module Name</th>
<th>Master ID</th>
<th>Status</th>
<th>Remarks</th>
<th>Verified By</th>
<th>Form Created By</th>
<th>Varification Time</th>
<th>Action</th>
</tr>
</thead>
<tbody>
<?php if(!empty($records)): ?>
<?php $i = 1; foreach($records as $row): ?>
<tr>
<td><?= $i++ ?></td>
<td><?= htmlspecialchars($row['module_name']) ?></td>
<td><?= $row['master_id'] ?></td>
<td>
<?php if(strtolower($row['validation_status']) == 'verified'): ?>
<span class="badge bg-success text-white">Verified</span>
<?php else: ?>
<span class="badge bg-danger text-white">Reported</span>
<?php endif; ?>
</td>
<td><?= htmlspecialchars($row['remarks']) ?></td>
<td><?= htmlspecialchars($row['full_name']) ?></td>
<td>
<?php
// Show creator name if available
$creator_id = $row['form_created_by'];
if($creator_id){
    $this->db->select('full_name');
    $creator = $this->db->get_where('users', ['user_id'=>$creator_id])->row();
    echo $creator ? htmlspecialchars($creator->full_name) : '-';
} else {
    echo '-';
}
?>
</td>
<td><?= date('d-M-Y H:i', strtotime($row['created_at'])) ?></td>
<td>
<?php if($row['module_name'] == 'child_health'): ?>
<a href="<?= base_url('forms/view_child_health/'.$row['master_id']) ?>" class="btn btn-tone btn-success btn-sm" title="View">
<i class="fa fa-eye"></i>
</a>
<?php elseif($row['module_name'] == 'opd_mnch'): ?>
<a href="<?= base_url('forms/view_opd_mnch/'.$row['master_id']) ?>" class="btn btn-tone btn-success btn-sm" title="View">
<i class="fa fa-eye"></i>
</a>
<?php endif; ?>
</td>
</tr>
<?php endforeach; ?>
<?php else: ?>
<tr>
<td colspan="9" class="text-center">No records found</td>
</tr>
<?php endif; ?>
</tbody>
</table>
</div>
</div>