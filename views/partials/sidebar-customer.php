<?php
$currentUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$scriptName = dirname($_SERVER['SCRIPT_NAME']);
$scriptName = str_replace('\\', '/', $scriptName);
if ($scriptName !== '/') {
    $currentUri = substr($currentUri, strlen($scriptName));
}
$currentUri = '/' . trim($currentUri, '/');

// Active helper
function isActiveCustRoute($route, $currentUri) {
    return (strpos($currentUri, $route) === 0) ? 'background-color: var(--color-primary); color: #FFF !important; font-weight: 600;' : 'color: var(--color-text-secondary);';
}
?>
<div class="nav flex-column nav-pills gap-1">
    <a href="<?php echo SITE_URL; ?>/dashboard" class="nav-link rounded-pill py-2 px-3 d-flex align-items-center gap-2" 
       style="<?php echo ($currentUri === '/dashboard') ? 'background-color: var(--color-primary); color: #FFF !important; font-weight: 600;' : 'color: var(--color-text-secondary);'; ?>">
        <i class="bi bi-speedometer2"></i>
        <span><?php echo __('dashboard_home'); ?></span>
    </a>
    <a href="<?php echo SITE_URL; ?>/dashboard/favorites" class="nav-link rounded-pill py-2 px-3 d-flex align-items-center gap-2" 
       style="<?php echo isActiveCustRoute('/dashboard/favorites', $currentUri); ?>">
        <i class="bi bi-heart"></i>
        <span><?php echo __('my_favorites'); ?></span>
    </a>
    <a href="<?php echo SITE_URL; ?>/dashboard/reviews" class="nav-link rounded-pill py-2 px-3 d-flex align-items-center gap-2" 
       style="<?php echo isActiveCustRoute('/dashboard/reviews', $currentUri); ?>">
        <i class="bi bi-star"></i>
        <span><?php echo __('my_reviews'); ?></span>
    </a>
    <a href="<?php echo SITE_URL; ?>/dashboard/notifications" class="nav-link rounded-pill py-2 px-3 d-flex align-items-center gap-2" 
       style="<?php echo isActiveCustRoute('/dashboard/notifications', $currentUri); ?>">
        <i class="bi bi-bell"></i>
        <span><?php echo __('notifications'); ?></span>
    </a>
    
    <div class="mt-4 mb-2 px-3 text-muted text-uppercase fw-bold" style="font-size: 0.75rem; letter-spacing: 0.5px;">
        <?php echo __('settings'); ?>
    </div>
    
    <a href="<?php echo SITE_URL; ?>/dashboard/settings/profile" class="nav-link rounded-pill py-2 px-3 d-flex align-items-center gap-2" 
       style="<?php echo isActiveCustRoute('/dashboard/settings/profile', $currentUri); ?>">
        <i class="bi bi-person"></i>
        <span><?php echo __('settings_profile'); ?></span>
    </a>
    <a href="<?php echo SITE_URL; ?>/dashboard/settings/security" class="nav-link rounded-pill py-2 px-3 d-flex align-items-center gap-2" 
       style="<?php echo isActiveCustRoute('/dashboard/settings/security', $currentUri); ?>">
        <i class="bi bi-shield-lock"></i>
        <span><?php echo __('settings_security'); ?></span>
    </a>
    <a href="<?php echo SITE_URL; ?>/dashboard/settings/privacy" class="nav-link rounded-pill py-2 px-3 d-flex align-items-center gap-2" 
       style="<?php echo isActiveCustRoute('/dashboard/settings/privacy', $currentUri); ?>">
        <i class="bi bi-eye-slash"></i>
        <span><?php echo __('settings_privacy'); ?></span>
    </a>
    <a href="<?php echo SITE_URL; ?>/dashboard/settings/notifications" class="nav-link rounded-pill py-2 px-3 d-flex align-items-center gap-2" 
       style="<?php echo isActiveCustRoute('/dashboard/settings/notifications', $currentUri); ?>">
        <i class="bi bi-bell-slash"></i>
        <span><?php echo __('settings_notifications'); ?></span>
    </a>
    <a href="<?php echo SITE_URL; ?>/dashboard/settings/appearance" class="nav-link rounded-pill py-2 px-3 d-flex align-items-center gap-2" 
       style="<?php echo isActiveCustRoute('/dashboard/settings/appearance', $currentUri); ?>">
        <i class="bi bi-palette"></i>
        <span><?php echo __('settings_appearance'); ?></span>
    </a>
</div>
