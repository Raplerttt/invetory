<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Login<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-xl-4 col-lg-5 col-md-6">
        <div class="card login-card">
            <div class="card-body p-4">
                <!-- Logo/Header -->
                <div class="text-center mb-4">
                    <i class="fas fa-warehouse text-primary login-icon"></i>
                    <h2 class="fw-bold mt-3">Inventory System</h2>
                    <p class="text-muted">Masuk ke akun Anda</p>
                </div>
                
                <form action="<?= base_url('/login') ?>" method="post">
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
                    
                    <!-- Username Input -->
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-user"></i>
                            </span>
                            <input type="text" 
                                   class="form-control <?= (isset($validation) && $validation->hasError('username')) ? 'is-invalid' : '' ?>" 
                                   id="username" 
                                   name="username" 
                                   value="<?= old('username') ?>" 
                                   placeholder="Masukkan username"
                                   required
                                   autofocus>
                        </div>
                        <?php if (isset($validation) && $validation->hasError('username')): ?>
                            <div class="invalid-feedback d-block">
                                <?= $validation->getError('username') ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Password Input -->
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-lock"></i>
                            </span>
                            <input type="password" 
                                   class="form-control <?= (isset($validation) && $validation->hasError('password')) ? 'is-invalid' : '' ?>" 
                                   id="password" 
                                   name="password" 
                                   placeholder="Masukkan password"
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
                    
                    <!-- Remember Me -->
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="remember" name="remember" value="1">
                        <label class="form-check-label" for="remember">
                            Ingat Saya
                        </label>
                    </div>
                    
                    <!-- Submit Button -->
                    <div class="d-grid mb-3">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-sign-in-alt me-2"></i>Login
                        </button>
                    </div>
                    
                    <!-- Register Link -->
                    <div class="text-center">
                        <p class="mb-0">Belum punya akun? 
                            <a href="<?= base_url('/register') ?>" class="text-decoration-none fw-bold">
                                Daftar di sini
                            </a>
                        </p>
                    </div>
                </form>
            </div>
        </div>

        <!-- Demo Accounts Info -->
        <div class="text-center mt-4">
            <div class="card bg-light">
                <div class="card-body py-3">
                    <h6 class="card-title mb-2">Akun Demo:</h6>
                    <div class="row">
                        <div class="col-6">
                            <small class="text-muted d-block">Admin</small>
                            <small class="fw-bold">admin / password</small>
                        </div>
                        <div class="col-6">
                            <small class="text-muted d-block">Staff</small>
                            <small class="fw-bold">staff / password</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Toggle password visibility
        const togglePassword = document.querySelector('.toggle-password');
        const password = document.querySelector('#password');
        
        if (togglePassword) {
            togglePassword.addEventListener('click', function() {
                const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                password.setAttribute('type', type);
                this.querySelector('i').classList.toggle('fa-eye');
                this.querySelector('i').classList.toggle('fa-eye-slash');
            });
        }
        
        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);

        // Auto focus username field
        const usernameField = document.getElementById('username');
        if (usernameField && !usernameField.value) {
            usernameField.focus();
        }
    });
</script>
<?= $this->endSection() ?>