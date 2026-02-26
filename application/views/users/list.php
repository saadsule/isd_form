<div class="page-container">
    <div class="main-content">
        <div class="page-header">
            <h2 class="header-title">Users List</h2>
            <?php if(in_array($this->session->userdata('role'), [3,4])): ?>
                <a href="<?= base_url('users/add') ?>" class="btn btn-primary float-right">Add User</a>
            <?php endif; ?>
        </div>

        <table class="table table-bordered table-hover">
                <thead class="thead-light">
                <tr>
                    <th>#</th>
                    <th>Full Name</th>
                    <th>Username</th>
                    <th>Status</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($users as $i => $u): ?>
                    <tr>
                        <td><?= $i+1 ?></td>
                        <td><?= $u->full_name ?></td>
                        <td><?= $u->username ?></td>
                        <td><?= $u->status ? 'Active' : 'Inactive' ?></td>
                        <td><?= $u->created_at ?></td>
                        <td>
                            <a href="<?= base_url('users/edit/'.$u->user_id) ?>" class="btn btn-sm btn-info">Edit</a>
                            <?php if(in_array($this->session->userdata('role'), [3,4])): ?>
                                <a href="<?= base_url('users/delete/'.$u->user_id) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

    </div>