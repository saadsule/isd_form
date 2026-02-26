<div class="page-container">
    <div class="main-content">

        <div class="page-header">
            <h2 class="header-title">Edit User</h2>
        </div>

        <div class="row mb-3">
            <div class="col-12">
                <form method="post" class="row g-2">

                    <div class="col-md-6">
                        <label>Full Name *</label>
                        <input type="text" name="full_name" class="form-control" value="<?= $user->full_name ?>" required>
                    </div>

                    <div class="col-md-6">
                        <label>Username *</label>
                        <input type="text" name="username" class="form-control" value="<?= $user->username ?>" required>
                    </div>

                    <div class="col-md-6">
                        <label>Password <small>(Leave blank to keep current)</small></label>
                        <input type="password" name="password" class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label>Status *</label>
                        <select name="status" class="form-control" required>
                            <option value="1" <?= ($user->status == 1) ? 'selected' : '' ?>>Active</option>
                            <option value="0" <?= ($user->status == 0) ? 'selected' : '' ?>>Inactive</option>
                        </select>
                    </div>

                    <div class="col-md-12 mt-2 d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">Update User</button>
                        <a href="<?= base_url('users') ?>" class="btn btn-secondary ml-2">Cancel</a>
                    </div>

                </form>
            </div>
        </div>

    </div>
</div>