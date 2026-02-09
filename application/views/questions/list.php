<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Questions</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .table th, .table td {
            vertical-align: middle;
        }
        .option-list span {
            display: inline-block;
            background: #e9f5ee;
            color: #2f855a;
            padding: 2px 8px;
            border-radius: 12px;
            margin: 2px 2px 2px 0;
            font-size: 0.85rem;
        }
    </style>
</head>
<body>
<div class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Manage Questions</h2>
        <a href="<?= base_url('questions/add'); ?>" class="btn btn-success">Add New Question</a>
    </div>

    <?php if($this->session->flashdata('success')): ?>
        <div class="alert alert-success"><?= $this->session->flashdata('success'); ?></div>
    <?php endif; ?>

    <div class="table-responsive">
    <table class="table table-bordered table-hover align-middle">
        <thead class="table-light">
            <tr>
                <th>#</th>
                <th>Form Type</th>
                <th>Section</th>
                <th>Question</th>
                <th>Type</th>
                <th>Order</th>
                <th>Options</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $i = 1;
            foreach($questions as $q):
                $question_details = $this->Questions_model->get_question($q->question_id);
                $opts = isset($question_details->options) ? $question_details->options : [];
            ?>
            <tr>
                <td><?= $i++ ?></td>
                <td><?= htmlspecialchars($q->form_type) ?></td>
                <td><?= htmlspecialchars($q->q_section) ?></td>
                <td><?= htmlspecialchars($q->q_text) ?></td>
                <td><?= htmlspecialchars($q->q_type) ?></td>
                <td><?= htmlspecialchars($q->q_order) ?></td>
                <td class="option-list">
                    <?php foreach($opts as $o) echo '<span>'.htmlspecialchars($o->option_text).'</span>'; ?>
                </td>
                <td>
                    <a href="<?= base_url('questions/edit/'.$q->question_id) ?>" class="btn btn-primary btn-sm">Edit</a>
                    <a href="<?= base_url('questions/delete/'.$q->question_id) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this question?')">Delete</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    </div>
</div>
</body>
</html>
