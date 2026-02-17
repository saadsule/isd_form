<div class="page-container">
<div class="main-content">

<div class="page-header">
    <h2 class="header-title">View Entered Data - OPD/MNCH</h2>
</div>

<div class="card">
<div class="card-body">

<form method="get" class="mb-4">
    <div class="row">

    <div class="col-md-3">
    <label>From Date</label>
    <input type="date" name="from_date"
           value="<?= isset($filters['from_date']) ? $filters['from_date'] : '' ?>"
           class="form-control">
    </div>

    <div class="col-md-3">
    <label>To Date</label>
    <input type="date" name="to_date"
           value="<?= isset($filters['to_date']) ? $filters['to_date'] : '' ?>"
           class="form-control">
    </div>

    <div class="col-md-3">
    <label>UC</label>
    <select name="uc_id" class="form-control" onchange="this.form.submit()">
    <option value="">All</option>
    <?php foreach($ucs as $u): ?>
    <option value="<?= $u->pk_id ?>"
    <?= (!empty($filters['uc_id']) && $filters['uc_id'] == $u->pk_id) ? 'selected' : '' ?>>
    <?= $u->uc ?>
    </option>
    <?php endforeach; ?>
    </select>
    </div>

    <div class="col-md-3">
    <label>Facility</label>
    <select name="facility_id" class="form-control">
    <option value="">All</option>
    <?php foreach($facilities as $f): ?>
    <option value="<?= $f->id ?>"
    <?= (!empty($filters['facility_id']) && $filters['facility_id'] == $f->id) ? 'selected' : '' ?>>
    <?= $f->facility_name ?>
    </option>
    <?php endforeach; ?>
    </select>
    </div>

    <div class="col-md-6">
    <label>Search</label>
    <input type="text"
           name="search"
           placeholder="Patient name / QR Code / ANC Card# "
           value="<?= isset($filters['search']) ? $filters['search'] : '' ?>"
           class="form-control">
    </div>

    <div class="col-md-3 d-flex align-items-end">
        <button type="submit" class="btn btn-primary mr-2">Filter</button>
        <a href="<?= base_url('forms/opd_report') ?>" class="btn btn-secondary">Clear</a>
    </div>

    </div>
</form>
    
<div class="table-responsive">

<table id="data-table" class="table table-bordered table-hover">
<thead class="thead-light">
<tr>
    <th>#</th>
    <th>Date</th>
    <th>Visit / Client</th>
    <th>District / UC / Facility</th>
    <th>Patient / Guardian</th>
    <th>QR Code</th>
    <th>ANC</th>
    <th>Action</th>
</tr>
</thead>
<tbody>
<?php $i=1; foreach($records as $r): ?>
<tr>
    <td><?= $i++ ?></td>
    <td><?= date('d-M-Y', strtotime($r->form_date)) ?></td>

    <?php
        $visit_type = strtolower(trim($r->visit_type));

        $visit_classes = [
            'mnch' => ['class' => 'badge-info', 'icon' => 'fa-female'],
            'opd'  => ['class' => 'badge-primary', 'icon' => 'fa-stethoscope']
        ];

        if (isset($visit_classes[$visit_type])) {
            $visit_style = $visit_classes[$visit_type];
        } else {
            $visit_style = ['class' => 'badge-secondary', 'icon' => 'fa-hospital-o'];
        }

        // Client Type
        $client_type = strtolower(trim($r->client_type));

        $client_classes = [
            'new'       => ['class' => 'badge-success', 'icon' => 'fa-user-plus'],
            'followup'  => ['class' => 'badge-warning', 'icon' => 'fa-sync']
        ];

        if (isset($client_classes[$client_type])) {
            $client_style = $client_classes[$client_type];
        } else {
            $client_style = ['class' => 'badge-secondary', 'icon' => 'fa-user'];
        }
    ?>

    <!-- Visit Type / Client Type -->
    <td>
        <span class="badge <?= $visit_style['class']; ?>">
            <i class="fa <?= $visit_style['icon']; ?>" aria-hidden="true"></i>
            <?= htmlspecialchars($r->visit_type); ?>
        </span>

        <br>

        <span class="badge <?= $client_style['class']; ?>">
            <i class="fa <?= $client_style['icon']; ?>" aria-hidden="true"></i>
            <?= htmlspecialchars($r->client_type); ?>
        </span>
    </td>
    
    <!-- District / UC / Facility -->
    <td>
        <b>Dist:</b> <?= htmlspecialchars($r->district) ?><br>
        <b>UC:</b> <?= htmlspecialchars($r->uc) ?><br>
        <b>Fac:</b> <?= htmlspecialchars($r->facility) ?>
    </td>

    <!-- Patient / Guardian -->
    <td>
        <b>Patient:</b> <?= htmlspecialchars($r->patient_name) ?><br>
        <b>Father:</b> <?= htmlspecialchars($r->guardian_name) ?>
    </td>

    <!-- QR Code -->
    <td><?= htmlspecialchars(!empty($r->qr_code) ? $r->qr_code : '-') ?></td>
    
    <!-- ANC -->
    <td><?= htmlspecialchars($r->anc_card_no ?  $r->anc_card_no : '-') ?></td>

    <td>
        <a href="<?= base_url('forms/view_opd_mnch/'.$r->id) ?>" class="badge badge-primary" title="View">
            <i class="fa fa-eye"></i>
        </a>

        <?php if($this->session->userdata('user_id') == $r->created_by): ?>
        <a href="<?= base_url('forms/opd_mnch/'.$r->id) ?>" class="badge badge-success" title="Edit">
            <i class="fa fa-edit"></i>
        </a>
        <?php endif; ?>
        
        <!-- STATUS ICON -->
        <?php if($r->verification_status == 'Verified'): ?>
            <span class="badge badge-success" title="Verified">
                <i class="fa fa-check"></i>
            </span>

        <?php elseif($r->verification_status == 'Reported'): ?>
            <span class="badge badge-danger" title="Reported">
                <i class="fa fa-flag"></i>
            </span>
        <?php endif; ?>
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
        <!-- page js -->
<script src="<?php echo base_url('assets/vendors/datatables/jquery.dataTables.min.js') ?>"></script>
<script src="<?php echo base_url('assets/vendors/datatables/dataTables.bootstrap.min.js') ?>"></script>
<script>
    $('#data-table').DataTable();
</script>