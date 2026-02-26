<div class="page-container">
    <div class="main-content">

        <div class="page-header">
            <h2 class="header-title">Add User</h2>
        </div>
        
        <?php if($this->session->flashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= $this->session->flashdata('success'); ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php endif; ?>

        <?php if($this->session->flashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= $this->session->flashdata('error'); ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php endif; ?>
        
        <div class="row mb-3">
            <div class="col-12">
                <form method="post" class="row g-2">

                    <div class="col-md-6">
                        <label>Full Name *</label>
                        <input type="text" name="full_name" class="form-control" required>
                    </div>

                    <div class="col-md-6">
                        <label>Username *</label>
                        <input type="text" name="username" class="form-control" required>
                    </div>

                    <div class="col-md-6">
                        <label>Password *</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    
                    <div class="col-md-6">
                        <label>Status *</label>
                        <select name="status" class="form-control" required>
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                    
                    <div class="col-md-6">
                        <label>Role *</label>
                        <select name="role" class="form-control" required>
                            <?php foreach($roles as $key => $value): ?>
                                <option value="<?= $key ?>"><?= $value ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-12 mt-2 d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">Add User</button>
                        <a href="<?= base_url('users') ?>" class="btn btn-secondary ml-2">Cancel</a>
                    </div>

                </form>
            </div>
        </div>

    </div>