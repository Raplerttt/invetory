<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('title') ?>Profile<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Profile</h1>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Informasi Profile</h6>
            </div>
            <div class="card-body">
                <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?= session()->getFlashdata('success') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if (isset($validation)): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?= $validation->listErrors() ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <form action="<?= base_url('/dashboard/update-profile') ?>" method="post">
                    <?= csrf_field() ?>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Username</label>
                            <input type="text" class="form-control" name="username" 
                                   value="<?= old('username', $user['username'] ?? '') ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Role</label>
                            <input type="text" class="form-control" value="<?= $user['role'] ?>" readonly>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" class="form-control" name="name" 
                               value="<?= old('name', $user['name'] ?? '') ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" 
                               value="<?= old('email', $user['email'] ?? '') ?>">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Password Baru (kosongkan jika tidak ingin mengubah)</label>
                        <input type="password" class="form-control" name="password">
                    </div>

                    <button type="submit" class="btn btn-primary">Update Profile</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Informasi Akun</h6>
            </div>
            <div class="card-body text-center">
                <div class="mb-4">
                    <i class="fas fa-user-circle fa-5x text-primary"></i>
                </div>
                <h5 class="font-weight-bold"><?= $user['name'] ?></h5>
                <p class="text-muted">@<?= $user['username'] ?></p>
                <div class="badge bg-primary"><?= $user['role'] ?></div>
                
                <hr>
                
                <div class="text-start">
                    <p><strong>Member sejak:</strong><br>
                    <?= date('d F Y', strtotime($user['created_at'])) ?></p>
                    
                    <p><strong>Terakhir update:</strong><br>
                    <?= date('d F Y H:i', strtotime($user['updated_at'])) ?></p>
                    
                    <p><strong>Status:</strong><br>
                    <span class="badge bg-success">Aktif</span></p>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>