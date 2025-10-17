<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Register<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-xl-5 col-lg-6 col-md-7">
        <div class="card login-card">
            <div class="card-body p-4">
                <!-- Logo/Header -->
                <div class="text-center mb-4">
                    <i class="fas fa-user-plus text-primary login-icon"></i>
                    <h2 class="fw-bold mt-3">Daftar Akun Baru</h2>
                    <p class="text-muted">Buat akun untuk mengakses sistem</p>
                </div>
                
                <form action="<?= base_url('/register') ?>" method="post">
                    <?= csrf_field() ?>
                    
                    <!-- Flash Messages -->
                    <?php if (session()->getFlashdata('success')): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <?= session()->getFlashdata('success') ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <?php if (session()->getFlashdata('error')): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?= session()->getFlashdata('error') ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <!-- Validation Errors -->
                    <?php if (isset($validation) && $validation->getErrors()): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?= $validation->listErrors() ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <div class="row">
                        <!-- Username -->
                        <div class="col-md-6 mb-3">
                            <label for="username" class="form-label">Username <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-user"></i>
                                </span>
                                <input type="text" 
                                       class="form-control <?= (isset($validation) && $validation->hasError('username')) ? 'is-invalid' : '' ?>" 
                                       id="username" 
                                       name="username" 
                                       value="<?= old('username') ?>" 
                                       placeholder="Username unik"
                                       required>
                            </div>
                            <?php if (isset($validation) && $validation->hasError('username')): ?>
                                <div class="invalid-feedback d-block">
                                    <?= $validation->getError('username') ?>
                                </div>
                            <?php endif; ?>
                            <small class="form-text text-muted">Minimal 3 karakter</small>
                        </div>

                        <!-- Full Name -->
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-id-card"></i>
                                </span>
                                <input type="text" 
                                       class="form-control <?= (isset($validation) && $validation->hasError('name')) ? 'is-invalid' : '' ?>" 
                                       id="name" 
                                       name="name" 
                                       value="<?= old('name') ?>" 
                                       placeholder="Nama lengkap"
                                       required>
                            </div>
                            <?php if (isset($validation) && $validation->hasError('name')): ?>
                                <div class="invalid-feedback d-block">
                                    <?= $validation->getError('name') ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Email (Optional) -->
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-envelope"></i>
                            </span>
                            <input type="email" 
                                   class="form-control <?= (isset($validation) && $validation->hasError('email')) ? 'is-invalid' : '' ?>" 
                                   id="email" 
                                   name="email" 
                                   value="<?= old('email') ?>" 
                                   placeholder="email@contoh.com (opsional)">
                        </div>
                        <?php if (isset($validation) && $validation->hasError('email')): ?>
                            <div class="invalid-feedback d-block">
                                <?= $validation->getError('email') ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="row">
                        <!-- Password -->
                        <div class="col-md-6 mb-3">
                            <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-lock"></i>
                                </span>
                                <input type="password" 
                                       class="form-control <?= (isset($validation) && $validation->hasError('password')) ? 'is-invalid' : '' ?>" 
                                       id="password" 
                                       name="password" 
                                       placeholder="Minimal 6 karakter"
                                       required>
                                <button type="button" class="input-group-text toggle-password">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            <?php if (isset($validation) && $validation->hasError('password')): ?>
                                <div class="invalid-feedback d-block">
                                    <?= $validation->getError('password') ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Confirm Password -->
                        <div class="col-md-6 mb-3">
                            <label for="pass_confirm" class="form-label">Konfirmasi Password <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-lock"></i>
                                </span>
                                <input type="password" 
                                       class="form-control <?= (isset($validation) && $validation->hasError('pass_confirm')) ? 'is-invalid' : '' ?>" 
                                       id="pass_confirm" 
                                       name="pass_confirm" 
                                       placeholder="Ulangi password"
                                       required>
                                <button type="button" class="input-group-text toggle-password" data-target="pass_confirm">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            <?php if (isset($validation) && $validation->hasError('pass_confirm')): ?>
                                <div class="invalid-feedback d-block">
                                    <?= $validation->getError('pass_confirm') ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Terms Agreement -->
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="agree_terms" name="agree_terms" value="1" required>
                        <label class="form-check-label" for="agree_terms">
                            Saya menyetujui <a href="#" class="text-decoration-none">syarat dan ketentuan</a>
                        </label>
                    </div>
                    
                    <!-- Submit Button -->
                    <div class="d-grid mb-3">
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="fas fa-user-plus me-2"></i>Daftar Sekarang
                        </button>
                    </div>
                    
                    <!-- Login Link -->
                    <div class="text-center">
                        <p class="mb-0">Sudah punya akun? 
                            <a href="<?= base_url('/login') ?>" class="text-decoration-none fw-bold">
                                Login di sini
                            </a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Toggle password visibility
        const toggleButtons = document.querySelectorAll('.toggle-password');
        
        toggleButtons.forEach(button => {
            button.addEventListener('click', function() {
                const targetId = this.getAttribute('data-target') || 'password';
                const passwordField = document.getElementById(targetId);
                const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
                
                passwordField.setAttribute('type', type);
                this.querySelector('i').classList.toggle('fa-eye');
                this.querySelector('i').classList.toggle('fa-eye-slash');
            });
        });

        // Real-time password confirmation check
        const password = document.getElementById('password');
        const passConfirm = document.getElementById('pass_confirm');

        function validatePassword() {
            if (password.value && passConfirm.value) {
                if (password.value !== passConfirm.value) {
                    passConfirm.classList.add('is-invalid');
                } else {
                    passConfirm.classList.remove('is-invalid');
                }
            }
        }

        password.addEventListener('input', validatePassword);
        passConfirm.addEventListener('input', validatePassword);

        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
    });
</script>
<?= $this->endSection() ?>