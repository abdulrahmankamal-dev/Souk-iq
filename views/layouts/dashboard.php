<!DOCTYPE html>
<?php
$htmlAttrs = getHtmlAttributes();
$lang = $_SESSION['lang'] ?? 'ar';
$dir = ($lang === 'en') ? 'ltr' : 'rtl';
$user = \Core\Auth::user();
?>
<html <?php echo $htmlAttrs; ?>>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($title) ? $title : __('dashboard') . ' | ' . __('logo'); ?></title>
    
    <!-- CSS Vendor dependencies -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />

    <!-- Custom CSS Core -->
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/main.css">
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/components.css">

    <?php if ($dir === 'rtl'): ?>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css">
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/rtl.css">
    <?php endif; ?>

    <style>
        .dashboard-wrapper {
            display: flex;
            min-height: 100vh;
        }
        .dashboard-sidebar {
            width: 280px;
            background-color: var(--color-surface);
            border-left: 1px solid var(--color-border);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            transition: width var(--transition-base);
        }
        [dir="ltr"] .dashboard-sidebar {
            border-left: none;
            border-right: 1px solid var(--color-border);
        }
        .dashboard-content {
            flex-grow: 1;
            background-color: var(--color-bg);
            padding: 40px;
            overflow-y: auto;
        }
        @media (max-width: 992px) {
            .dashboard-wrapper {
                flex-direction: column;
            }
            .dashboard-sidebar {
                width: 100%;
                border-left: none;
                border-right: none;
                border-bottom: 1px solid var(--color-border);
            }
            .dashboard-content {
                padding: 20px;
            }
        }
    </style>
    <script>
        const SITE_URL = '<?php echo SITE_URL; ?>';
    </script>
</head>
<body>

    <!-- Header Navbar -->
    <?php $this->partial('navbar'); ?>

    <!-- Dashboard Main Layout Container -->
    <div class="dashboard-wrapper">
        <!-- Dashboard Sidebar Navigation -->
        <aside class="dashboard-sidebar p-3">
            <div>
                <!-- User Profile Summary widget in sidebar -->
                <div class="text-center pb-4 border-bottom mb-4">
                    <img src="<?php echo $user->avatar ? SITE_URL . '/uploads/avatars/' . $user->avatar : 'https://placehold.co/100'; ?>" 
                         alt="<?php echo $user->full_name; ?>" 
                         class="rounded-circle object-cover border mb-2" 
                         width="80" height="80">
                    <h5 class="fw-bold m-0"><?php echo $user->full_name; ?></h5>
                    <small class="text-muted">@<?php echo $user->username; ?></small>
                    <div class="mt-2">
                        <span class="badge bg-gold-light text-gold rounded-pill border"><?php echo __($user->role); ?></span>
                    </div>
                </div>

                <!-- Load role-specific sidebar navigation links -->
                <?php 
                if ($user->role === 'store_owner') {
                    $this->partial('sidebar-store'); 
                } elseif ($user->role === 'admin' || $user->role === 'super_admin') {
                    $this->partial('sidebar-admin');
                } else {
                    $this->partial('sidebar-customer'); 
                }
                ?>
            </div>
            
            <div class="pt-4 border-top mt-4">
                <a href="<?php echo SITE_URL; ?>/logout" class="btn btn-outline-danger w-100 rounded-pill">
                    <i class="bi bi-box-arrow-right"></i> <?php echo __('logout'); ?>
                </a>
            </div>
        </aside>

        <!-- Dashboard Content pane -->
        <main class="dashboard-content">
            <?php echo $content; ?>
        </main>
    </div>

    <!-- Footer -->
    <?php $this->partial('footer'); ?>

    <!-- JS Vendor dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    
    <!-- Custom JS core scripts -->
    <script src="<?php echo SITE_URL; ?>/assets/js/app.js"></script>
    <script src="<?php echo SITE_URL; ?>/assets/js/validation.js"></script>
    <script src="<?php echo SITE_URL; ?>/assets/js/map.js"></script>
</body>
</html>
