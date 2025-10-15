<?php
use Config\Navbar;

$navbarConfig = new Navbar();
$currentUrl = current_url();
$userRole = session('role') ?? 'guest';
$menuItems = $navbarConfig->getMenu($currentUrl, $userRole);
?>

<!-- Sidebar - Mobile/Tablet -->
<div class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse" id="sidebarMenu">
    <div class="position-sticky pt-3">
        <ul class="nav flex-column">
            <?php foreach ($menuItems as $item): ?>
                <?php if (isset($item['children'])): ?>
                    <!-- Parent Menu with Children -->
                    <li class="nav-item">
                        <a class="nav-link <?= $item['active'] ? 'active' : '' ?>" 
                           data-bs-toggle="collapse" href="#submenu-<?= md5($item['text']) ?>" 
                           role="button">
                            <i class="<?= $item['icon'] ?> me-2"></i>
                            <?= $item['text'] ?>
                            <i class="fas fa-chevron-down float-end mt-1"></i>
                        </a>
                        <div class="collapse <?= $item['active'] ? 'show' : '' ?>" id="submenu-<?= md5($item['text']) ?>">
                            <ul class="nav flex-column ms-3">
                                <?php foreach ($item['children'] as $child): ?>
                                    <?php if (in_array($userRole, $child['roles'])): ?>
                                        <li class="nav-item">
                                            <a class="nav-link <?= $child['active'] ? 'active' : '' ?>" 
                                               href="<?= base_url($child['url']) ?>">
                                                <i class="<?= $child['icon'] ?> me-2"></i>
                                                <?= $child['text'] ?>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </li>
                <?php else: ?>
                    <!-- Single Menu Item -->
                    <li class="nav-item">
                        <a class="nav-link <?= $item['active'] ? 'active' : '' ?>" 
                           href="<?= base_url($item['url']) ?>">
                            <i class="<?= $item['icon'] ?> me-2"></i>
                            <?= $item['text'] ?>
                        </a>
                    </li>
                <?php endif; ?>
            <?php endforeach; ?>
        </ul>
        
        <!-- Quick Stats Sidebar -->
        <?php if (isset($quickStats) && $userRole !== 'guest'): ?>
        <div class="mt-4 p-3 bg-white rounded shadow-sm">
            <h6 class="border-bottom pb-2">Quick Stats</h6>
            <div class="row text-center">
                <div class="col-6 mb-2">
                    <small class="text-muted d-block">Low Stock</small>
                    <span class="fw-bold text-warning"><?= $quickStats['low_stock'] ?? 0 ?></span>
                </div>
                <div class="col-6 mb-2">
                    <small class="text-muted d-block">Pending PO</small>
                    <span class="fw-bold text-primary"><?= $quickStats['pending_po'] ?? 0 ?></span>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>