<!DOCTYPE html>
<html lang="id" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        
        .login-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        
        .login-header {
            background: linear-gradient(135deg, #007bff, #0056b3);
            color: white;
            padding: 2rem;
            text-align: center;
        }
        
        .login-body {
            padding: 2rem;
        }
        
        .form-control:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }
        
        .btn-login {
            background: linear-gradient(135deg, #007bff, #0056b3);
            border: none;
            padding: 0.75rem 2rem;
            font-weight: 600;
        }
        
        .btn-login:hover {
            background: linear-gradient(135deg, #0056b3, #004085);
            transform: translateY(-1px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }
        
        .login-logo {
            font-size: 3rem;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="login-card">
                    <!-- Header -->
                    <div class="login-header">
                        <div class="login-logo">
                            <i class="fas fa-boxes"></i>
                        </div>
                        <h2 class="mb-1">Inventory System</h2>
                        <p class="mb-0 opacity-75">Silakan login untuk melanjutkan</p>
                    </div>
                    
                    <!-- Body -->
                    <div class="login-body">
                        <!-- Alert Messages -->
                        <?php if (session()->getFlashdata('success')): ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="fas fa-check-circle me-2"></i>
                                <?= session()->getFlashdata('success') ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (session()->getFlashdata('error')): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-circle me-2"></i>
                                <?= session()->getFlashdata('error') ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (session()->getFlashdata('errors')): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-circle me-2"></i>
                                <ul class="mb-0">
                                    <?php foreach (session()->getFlashdata('errors') as $error): ?>
                                        <li><?= $error ?></li>
                                    <?php endforeach; ?>
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <!-- Login Form -->
                        <form action="<?= base_url('login') ?>" method="post">
                            <?= csrf_field() ?>
                            
                            <!-- Username/Email -->
                            <div class="mb-3">
                                <label for="username" class="form-label">
                                    <i class="fas fa-user me-2"></i>Username atau Email
                                </label>
                                <input type="text" 
                                       class="form-control form-control-lg <?= session()->getFlashdata('errors') && array_key_exists('username', session()->getFlashdata('errors')) ? 'is-invalid' : '' ?>" 
                                       id="username" 
                                       name="username" 
                                       value="<?= old('username') ?>" 
                                       placeholder="Masukkan username atau email"
                                       required>
                                <?php if (session()->getFlashdata('errors') && array_key_exists('username', session()->getFlashdata('errors'))): ?>
                                    <div class="invalid-feedback">
                                        <?= session()->getFlashdata('errors')['username'] ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <!-- Password -->
                            <div class="mb-3">
                                <label for="password" class="form-label">
                                    <i class="fas fa-lock me-2"></i>Password
                                </label>
                                <div class="input-group">
                                    <input type="password" 
                                           class="form-control form-control-lg <?= session()->getFlashdata('errors') && array_key_exists('password', session()->getFlashdata('errors')) ? 'is-invalid' : '' ?>" 
                                           id="password" 
                                           name="password" 
                                           placeholder="Masukkan password"
                                           required>
                                    <button type="button" class="btn btn-outline-secondary" onclick="togglePassword()">
                                        <i class="fas fa-eye" id="password-icon"></i>
                                    </button>
                                    <?php if (session()->getFlashdata('errors') && array_key_exists('password', session()->getFlashdata('errors'))): ?>
                                        <div class="invalid-feedback">
                                            <?= session()->getFlashdata('errors')['password'] ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Remember Me & Forgot Password -->
                            <div class="mb-3 d-flex justify-content-between align-items-center">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="remember" name="remember" value="1">
                                    <label class="form-check-label" for="remember">
                                        Ingat saya
                                    </label>
                                </div>
                                <a href="<?= base_url('forgot-password') ?>" class="text-decoration-none">
                                    Lupa password?
                                </a>
                            </div>

                            <!-- Submit Button -->
                            <div class="d-grid mb-3">
                                <button type="submit" class="btn btn-primary btn-lg btn-login">
                                    <i class="fas fa-sign-in-alt me-2"></i>Login
                                </button>
                            </div>

                            <!-- Demo Accounts Info -->
                            <div class="card border-info">
                                <div class="card-body py-2">
                                    <small class="text-muted">
                                        <strong>Demo Accounts:</strong><br>
                                        Admin: admin / password123<br>
                                        Staff: staff / password123
                                    </small>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                
                <!-- Footer -->
                <div class="text-center mt-4">
                    <small class="text-white">
                        &copy; <?= date('Y') ?> Inventory System. All rights reserved.
                    </small>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JS -->
    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const passwordIcon = document.getElementById('password-icon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                passwordIcon.className = 'fas fa-eye-slash';
            } else {
                passwordInput.type = 'password';
                passwordIcon.className = 'fas fa-eye';
            }
        }

        // Auto focus on username field
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('username').focus();
        });

        // Remove alerts after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
    </script>
</body>
</html>