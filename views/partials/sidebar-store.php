<?php
$currentUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$scriptName = dirname($_SERVER['SCRIPT_NAME']);
$scriptName = str_replace('\\', '/', $scriptName);
if ($scriptName !== '/') {
    $currentUri = substr($currentUri, strlen($scriptName));
}
$currentUri = '/' . trim($currentUri, '/');

// Active helper
function isActiveStoreRoute($route, $currentUri) {
    return (strpos($currentUri, $route) === 0) ? 'background-color: var(--color-primary); color: #FFF !important; font-weight: 600;' : 'color: var(--color-text-secondary);';
}
?>
<div class="nav flex-column nav-pills gap-1">
    <a href="<?php echo SITE_URL; ?>/store-owner/dashboard" class="nav-link rounded-pill py-2 px-3 d-flex align-items-center gap-2" 
       style="<?php echo ($currentUri === '/store-owner/dashboard') ? 'background-color: var(--color-primary); color: #FFF !important; font-weight: 600;' : 'color: var(--color-text-secondary);'; ?>">
        <i class="bi bi-shop"></i>
        <span>لوحة التحكم الرئيسية</span>
    </a>
    <a href="<?php echo SITE_URL; ?>/store-owner/products" class="nav-link rounded-pill py-2 px-3 d-flex align-items-center gap-2" 
       style="<?php echo isActiveStoreRoute('/store-owner/products', $currentUri); ?>">
        <i class="bi bi-box-seam"></i>
        <span>إدارة المنتجات</span>
    </a>
    <a href="<?php echo SITE_URL; ?>/store-owner/products/create" class="nav-link rounded-pill py-2 px-3 d-flex align-items-center gap-2" 
       style="<?php echo isActiveStoreRoute('/store-owner/products/create', $currentUri); ?>">
        <i class="bi bi-plus-circle"></i>
        <span>إضافة منتج جديد</span>
    </a>
    <a href="<?php echo SITE_URL; ?>/store-owner/reviews" class="nav-link rounded-pill py-2 px-3 d-flex align-items-center gap-2" 
       style="<?php echo isActiveStoreRoute('/store-owner/reviews', $currentUri); ?>">
        <i class="bi bi-chat-left-quote"></i>
        <span>تقييمات الزبائن</span>
    </a>
    <a href="<?php echo SITE_URL; ?>/store-owner/staff" class="nav-link rounded-pill py-2 px-3 d-flex align-items-center gap-2" 
       style="<?php echo isActiveStoreRoute('/store-owner/staff', $currentUri); ?>">
        <i class="bi bi-people"></i>
        <span>الموظفين والشركاء</span>
    </a>
    <a href="<?php echo SITE_URL; ?>/store-owner/settings" class="nav-link rounded-pill py-2 px-3 d-flex align-items-center gap-2" 
       style="<?php echo isActiveStoreRoute('/store-owner/settings', $currentUri); ?>">
        <i class="bi bi-gear"></i>
        <span>إعدادات المتجر</span>
    </a>
    
    <div class="mt-4 mb-2 border-top pt-3">
        <a href="<?php echo SITE_URL; ?>/dashboard" class="btn btn-outline-secondary btn-sm w-100 rounded-pill">
            <i class="bi bi-arrow-right-short"></i> لوحة الزبون العامة
        </a>
    </div>
</div>
