<?php
$records     = isset($records) ? $records : array();
$summary     = isset($summary) ? $summary : array();
$filter_type = isset($filter_type) ? $filter_type : null;
$view_base   = rtrim(base_url(), '/') . '/forms/view_child_health/';
$total       = count($records);

$type_cfg = array(
    'Type 1' => array(
        'color'     => '#c0392b',
        'border'    => '#e74c3c',
        'pill_bg'   => '#fde8e8',
        'icon'      => 'fa-exclamation-circle',
        'q_label'   => 'Q18',
        'age_desc'  => 'Less than 1 year',
        'sev_class' => 'danger',
        'count_key' => 'type_1_count',
        'url_seg'   => '1',
    ),
    'Type 2' => array(
        'color'     => '#b7770d',
        'border'    => '#f39c12',
        'pill_bg'   => '#fef3cd',
        'icon'      => 'fa-exclamation-triangle',
        'q_label'   => 'Q19',
        'age_desc'  => '1 - 2 years',
        'sev_class' => 'warning',
        'count_key' => 'type_2_count',
        'url_seg'   => '2',
    ),
    'Type 3' => array(
        'color'     => '#1a5276',
        'border'    => '#2980b9',
        'pill_bg'   => '#d6eaf8',
        'icon'      => 'fa-info-circle',
        'q_label'   => 'Q20',
        'age_desc'  => '2 - 5 years',
        'sev_class' => 'info',
        'count_key' => 'type_3_count',
        'url_seg'   => '3',
    ),
);

function getAgeDisplay($row) {
    $y = isset($row['age_year'])  ? (int)$row['age_year']  : 0;
    $m = isset($row['age_month']) ? (int)$row['age_month'] : 0;
    $parts = array();
    if ($y) { $parts[] = $y . 'y'; }
    if ($m) { $parts[] = $m . 'm'; }
    if (!empty($parts)) { return implode(' ', $parts); }
    return !empty($row['dob']) ? date('d M Y', strtotime($row['dob'])) : '—';
}
?>

<style>
.stat-card-link {
    display: block;
    text-decoration: none;
    border-radius: 8px;
    transition: transform .2s, box-shadow .2s;
    color: inherit;
}
.stat-card-link:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 18px rgba(0,0,0,.12) !important;
    text-decoration: none;
    color: inherit;
}
.stat-card-link.is-active {
    box-shadow: 0 0 0 3px rgba(0,0,0,.18), 0 4px 14px rgba(0,0,0,.1) !important;
}
.issue-block { font-size: .78rem; line-height: 1.7; }
.issue-block .q-pill {
    display: inline-block;
    padding: 2px 9px;
    border-radius: 20px;
    font-weight: 700;
    font-size: .7rem;
    margin-bottom: 3px;
}
.issue-block .lbl  { color: #888; font-size: .68rem; text-transform: uppercase; letter-spacing: .4px; }
.issue-block .val-bad  { color: #c0392b; font-weight: 600; }
.issue-block .val-good { color: #1a7a4a; font-weight: 600; }

/* QR code */
.qr-pill {
    font-family: monospace;
    font-size: .72rem;
    background: #f1f3f5;
    border: 1px solid #dee2e6;
    padding: 3px 7px;
    border-radius: 5px;
    display: inline-block;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 110px;
}

/* Table header */
.mismatch-table thead tr {
    background: linear-gradient(135deg, #2c3e50 0%, #3d5a80 100%);
}
.mismatch-table thead th {
    color: #ffffff !important;
    font-size: .72rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: .6px;
    padding: 12px 10px;
    border: none !important;
    white-space: nowrap;
}
.mismatch-table tbody td {
    font-size: .82rem;
    vertical-align: middle;
    padding: 10px 10px;
}
.mismatch-table tbody tr:hover {
    background: #f8fbff;
}
</style>

<div class="page-container">
<div class="main-content">

<!-- HEADER -->
<div class="page-header">
    <div class="d-flex align-items-center justify-content-between flex-wrap">
        <div>
            <h2>
                <i class="fa fa-exclamation-triangle text-warning"></i>
                Age & Antigens Mismatch Report
            </h2>
            <p class="text-muted mb-0">
                Records where antigen given does not match the child's recorded age group
            </p>
        </div>
    </div>
</div>

<!-- TABLE -->
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">
            <i class="fa fa-table text-muted"></i>
            All Mismatch Records
            <span class="badge badge-default ml-2"><?= $total ?></span>
        </h5>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 mismatch-table">
                <thead>
                    <tr>
                        <th width="40" class="pl-3">#</th>
                        <th width="125">QR Code</th>
                        <th width="125">Form Date</th>
                        <th>Patient Name</th>
                        <th width="120">Guardian</th>
                        <th width="95">DOB / Age</th>
                        <th width="100">Age Group</th>
                        <th width="195">Mismatch Issue</th>
                        <th width="105">Entered By</th>
                        <th width="105">Vaccinator</th>
                        <th width="95">Village</th>
                        <th width="60" class="text-center">View</th>
                    </tr>
                </thead>

                <tbody>
                <?php if (!empty($records)):
                    $serial = 0;
                    foreach ($records as $row):
                        $serial++;

                        $mtype = $row['mismatch_type'];
                        $cfg = isset($type_cfg[$mtype]) ? $type_cfg[$mtype] : null;

                        $dob         = !empty($row['dob']) ? date('d M Y', strtotime($row['dob'])) : '—';
                        $age_display = getAgeDisplay($row);
                        $age_group   = $row['age_group'];
                        $exp_group   = $row['expected_age_group'];
                        $q_answered  = $row['question_answered'];
                ?>
                    <tr class="row-stripe" style="border-left:4px solid <?= $cfg['border'] ?>;">
                        <td class="pl-3 text-muted"><?= $serial ?></td>

                        <td>
                            <span class="qr-pill"><?= $row['qr_code'] ?></span>
                        </td>
                        
                        <td>
                            <span class="qr-pill"><?= $row['form_date'] ?></span>
                        </td>

                        <td><strong><?= $row['patient_name'] ?></strong></td>

                        <td><?= $row['guardian_name'] ?: '—' ?></td>

                        <td>
                            <div><?= $dob ?></div>
                            <small class="text-muted"><?= $age_display ?></small>
                        </td>

                        <td>
                            <span class="badge" style="background:<?= $cfg['pill_bg'] ?>;color:<?= $cfg['color'] ?>;">
                                <?= $age_group ?>
                            </span>
                        </td>

                        <td>
                            <div class="issue-block">
                                <div>
                                    <span class="q-pill" style="background:<?= $cfg['pill_bg'] ?>;color:<?= $cfg['color'] ?>;">
                                        <i class="fa <?= $cfg['icon'] ?>"></i>
                                        <?= $q_answered ?> Given
                                    </span>
                                </div>
                                <div>
                                    <span class="lbl">Recorded:</span>
                                    <span class="val-bad"><?= $age_group ?></span>
                                </div>
                                <div>
                                    <span class="lbl">Required:</span>
                                    <span class="val-good"><?= $exp_group ?></span>
                                </div>
                            </div>
                        </td>

                        <td><?= $row['data_entry_user'] ?: '—' ?></td>

                        <td><?= $row['vaccinator_name'] ?: '—' ?></td>

                        <td><?= $row['village'] ?: '—' ?></td>

                        <td class="text-center">
                            <a href="<?= $view_base . $row['master_id'] ?>" target="_blank"
                               class="btn btn-sm btn-primary">
                                <i class="fa fa-eye"></i>
                            </a>
                            
                            <?php 
                                $user_id = $this->session->userdata('user_id');
                                $user_role = $this->session->userdata('role');

                                $can_edit = ($user_id == $row['created_by']) || 
                                            ($user_role == 'admin') || 
                                            ($user_role == 5);

                                if($can_edit): 
                            ?>
                                <a href="<?= base_url('forms/child_health/'.$row['master_id']) ?>" class="btn btn-tone btn-sm btn-primary" title="Edit">
                                    <i class="fa fa-edit"></i>
                                </a>
                            <?php endif; ?>
                            
                        </td>
                    </tr>
                <?php endforeach; else: ?>
                    <tr>
                        <td colspan="12" class="text-center p-4">
                            No Records Found
                        </td>
                    </tr>
                <?php endif; ?>
                </tbody>

            </table>
        </div>
    </div>
</div>

</div>