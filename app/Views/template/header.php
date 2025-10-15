<!-- User Menu -->
<?php if (session('logged_in')): ?>
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" 
           id="userDropdown" role="button" data-bs-toggle="dropdown">
            <i class="fas fa-user-circle me-2"></i>
            <div class="d-none d-sm-block text-end">
                <div class="fw-semibold"><?= session('name') ?></div>
                <small class="text-light"><?= ucfirst(session('role')) ?></small>
            </div>
        </a>
        <ul class="dropdown-menu dropdown-menu-end">
            <li>
                <a class="dropdown-item" href="<?= base_url('profile') ?>">
                    <i class="fas fa-user me-2"></i>Profile
                </a>
            </li>
            <li>
                <a class="dropdown-item" href="<?= base_url('settings') ?>">
                    <i class="fas fa-cog me-2"></i>Settings
                </a>
            </li>
            <li><hr class="dropdown-divider"></li>
            <li>
                <a class="dropdown-item text-danger" href="<?= base_url('logout') ?>">
                    <i class="fas fa-sign-out-alt me-2"></i>Logout
                </a>
            </li>
        </ul>
    </li>
<?php else: ?>
    <!-- Login Link -->
    <li class="nav-item">
        <a class="nav-link" href="<?= base_url('login') ?>">
            <i class="fas fa-sign-in-alt me-1"></i>Login
        </a>
    </li>
<?php endif; ?>