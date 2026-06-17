<?php
$user = \Core\Auth::user();
$lang = $_SESSION['lang'] ?? 'ar';
$dir = ($lang === 'en') ? 'ltr' : 'rtl';

// Fetch top categories for quick nav
require_once dirname(dirname(__DIR__)) . '/models/Category.php';
$catModel = new \Models\Category();
$topCategories = $catModel->getTopCategories(10);

// Unread notifications count
$unreadNotifications = 0;
if ($user) {
    require_once dirname(dirname(__DIR__)) . '/models/Notification.php';
    $notifModel = new \Models\Notification();
    $unreadNotifications = $notifModel->getUnreadCount($user->id);
}
?>
<nav class="navbar navbar-expand-lg sticky-top border-bottom bg-white shadow-sm py-2">
    <div class="container">
        <!-- Left: Logo and brand -->
        <a class="navbar-brand d-flex align-items-center gap-2 fw-bold text-dark" href="<?php echo SITE_URL; ?>" style="font-family: var(--font-display); font-size: 1.6rem;">
            <!-- Iraq Flag SVG -->
            <svg width="28" height="20" viewBox="0 0 3 2">
                <rect width="3" height="1" fill="#c1272d"/>
                <rect width="3" height="1" y="1" fill="#ffffff"/>
                <rect width="3" height="1" y="2" fill="#000000"/>
                <!-- Green Stars or Arabic Script representation in Iraq Flag -->
                <text x="1.5" y="1.6" font-size="0.4" fill="#007a3d" font-family="sans-serif" font-weight="bold" text-anchor="middle">الله اكبر</text>
            </svg>
            <span class="text-gold"><?php echo __('logo'); ?></span>
        </a>

        <!-- Mobile Toggler -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Navbar Content and Search -->
        <div class="collapse navbar-collapse" id="navbarContent">
            <!-- Center: Search Input Bar -->
            <div class="mx-auto my-2 my-lg-0 position-relative" style="width: 100%; max-width: 600px;">
                <form action="<?php echo SITE_URL; ?>/search" method="GET" class="d-flex align-items-center">
                    <div class="position-relative w-100">
                        <input type="text" name="q" class="form-control search-input-field rounded-pill pe-5 ps-4 py-2 border-2" 
                               placeholder="<?php echo __('search_placeholder'); ?>" 
                               value="<?php echo isset($_GET['q']) ? htmlspecialchars($_GET['q']) : ''; ?>" autocomplete="off" style="border-color: var(--color-border);">
                        
                        <button type="button" class="btn search-clear-btn position-absolute end-0 top-50 translate-middle-y me-3 border-0 bg-transparent text-muted" style="display: none;">
                            <i class="bi bi-x-lg"></i>
                        </button>
                    </div>
                    <button type="submit" class="btn btn-souk-primary ms-2 px-4 rounded-pill">
                        <i class="bi bi-search"></i>
                    </button>
                </form>

                <!-- Search Autocomplete Dropdown Panel -->
                <div class="search-autocomplete-dropdown dropdown-menu w-100 shadow-lg border rounded-lg mt-1" style="max-height: 400px; overflow-y: auto;"></div>
            </div>

            <!-- Right: User Account controls -->
            <div class="d-flex align-items-center gap-3 ms-lg-3">
                <!-- Language Switcher Pill Toggle -->
                <div class="btn-group rounded-pill border bg-light p-1">
                    <button type="button" class="btn btn-sm rounded-pill lang-toggle-btn px-2 py-1 <?php echo ($lang === 'ar') ? 'btn-souk-primary' : 'btn-light'; ?>" data-lang="ar">عربي</button>
                    <button type="button" class="btn btn-sm rounded-pill lang-toggle-btn px-2 py-1 <?php echo ($lang === 'ku') ? 'btn-souk-primary' : 'btn-light'; ?>" data-lang="ku">کوردی</button>
                    <button type="button" class="btn btn-sm rounded-pill lang-toggle-btn px-2 py-1 <?php echo ($lang === 'en') ? 'btn-souk-primary' : 'btn-light'; ?>" data-lang="en">EN</button>
                </div>

                <?php if ($user): ?>
                    <!-- Favorites Heart Link -->
                    <a href="<?php echo SITE_URL; ?>/dashboard/favorites" class="btn btn-light position-relative rounded-circle p-2" title="<?php echo __('favorites'); ?>">
                        <i class="bi bi-heart text-danger"></i>
                    </a>

                    <!-- Notifications Dropdown widget -->
                    <div class="dropdown">
                        <button class="btn btn-light position-relative rounded-circle p-2" type="button" id="notifDropdown" data-bs-toggle="dropdown" aria-expanded="false" title="<?php echo __('notifications'); ?>">
                            <i class="bi bi-bell text-dark"></i>
                            <?php if ($unreadNotifications > 0): ?>
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.65rem;">
                                    <?php echo $unreadNotifications; ?>
                                </span>
                            <?php endif; ?>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end shadow-lg border rounded-lg p-0" aria-labelledby="notifDropdown" style="width: 320px; max-height: 350px; overflow-y: auto;">
                            <div class="p-3 border-bottom d-flex justify-content-between align-items-center">
                                <span class="fw-bold"><?php echo __('notifications'); ?></span>
                                <?php if ($unreadNotifications > 0): ?>
                                    <button class="btn btn-link btn-sm text-gold p-0 text-decoration-none" onclick="markAllNotificationsRead()"><?php echo __('mark_all_read'); ?></button>
                                <?php endif; ?>
                            </div>
                            <div id="notifications-list-container">
                                <!-- Loaded dynamically via JS, fallback static items -->
                                <div class="p-3 text-muted text-center"><i class="bi bi-bell-slash"></i> <?php echo __('no_notifications_desc'); ?></div>
                            </div>
                        </div>
                    </div>

                    <!-- User Account dropdown menu -->
                    <div class="dropdown">
                        <a class="d-flex align-items-center gap-2 text-decoration-none dropdown-toggle text-dark" href="#" role="button" id="userMenu" data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="<?php echo $user->avatar ? SITE_URL . '/uploads/avatars/' . $user->avatar : 'https://placehold.co/40'; ?>" 
                                 alt="<?php echo $user->full_name; ?>" 
                                 class="rounded-circle object-cover border" 
                                 width="35" height="35">
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow border rounded-lg" aria-labelledby="userMenu">
                            <li><a class="dropdown-item fw-bold" href="<?php echo SITE_URL; ?>/dashboard"><i class="bi bi-speedometer2 me-2"></i> <?php echo __('dashboard'); ?></a></li>
                            <?php if ($user->role === 'store_owner'): ?>
                                <li><a class="dropdown-item" href="<?php echo SITE_URL; ?>/store-owner/dashboard"><i class="bi bi-shop me-2"></i> <?php echo __('store_dashboard'); ?></a></li>
                            <?php endif; ?>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="<?php echo SITE_URL; ?>/logout"><i class="bi bi-box-arrow-right me-2"></i> <?php echo __('logout'); ?></a></li>
                        </ul>
                    </div>

                <?php else: ?>
                    <!-- Visitor CTA controls -->
                    <div class="d-flex gap-2">
                        <a href="<?php echo SITE_URL; ?>/login" class="btn btn-souk-secondary rounded-pill px-4 btn-sm"><?php echo __('login'); ?></a>
                        <a href="<?php echo SITE_URL; ?>/register" class="btn btn-souk-primary rounded-pill px-4 btn-sm"><?php echo __('register'); ?></a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>

<!-- Category Quick-nav Bar below navbar (Horizontally scrollable) -->
<div class="bg-light border-bottom py-2 overflow-x-auto text-nowrap">
    <div class="container d-flex gap-3 align-items-center justify-content-start justify-content-lg-center">
        <?php foreach ($topCategories as $cat): ?>
            <?php $catName = ($lang === 'en') ? $cat->name_en : (($lang === 'ku') ? $cat->name_ku : $cat->name_ar); ?>
            <a href="<?php echo SITE_URL; ?>/search?category=<?php echo $cat->id; ?>" class="btn btn-sm btn-outline-secondary rounded-pill border bg-white text-dark text-decoration-none px-3 d-flex align-items-center gap-1">
                <i class="bi <?php echo $cat->icon; ?> text-gold"></i>
                <span><?php echo $catName; ?></span>
            </a>
        <?php endforeach; ?>
    </div>
</div>
