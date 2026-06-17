<?php
$currentUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$scriptName = dirname($_SERVER['SCRIPT_NAME']);
$scriptName = str_replace('\\', '/', $scriptName);
if ($scriptName !== '/') {
    $currentUri = substr($currentUri, strlen($scriptName));
}
$currentUri = '/' . trim($currentUri, '/');
$user = \Core\Auth::user();

// Active helper
function isActiveAdminRoute($route, $currentUri) {
    return (strpos($currentUri, $route) === 0) ? 'background-color: var(--color-primary); color: #FFF !important; font-weight: 600;' : 'color: var(--color-text-secondary);';
}
?>
<div class="nav flex-column nav-pills gap-1">
    <!-- Admin Section -->
    <div class="px-3 mb-2 text-muted text-uppercase fw-bold" style="font-size: 0.75rem; letter-spacing: 0.5px;">
        لوحة الإشراف
    </div>
    
    <a href="<?php echo SITE_URL; ?>/admin" class="nav-link rounded-pill py-2 px-3 d-flex align-items-center gap-2" 
       style="<?php echo ($currentUri === '/admin') ? 'background-color: var(--color-primary); color: #FFF !important; font-weight: 600;' : 'color: var(--color-text-secondary);'; ?>">
        <i class="bi bi-speedometer2"></i>
        <span>إحصائيات المنصة</span>
    </a>
    <a href="<?php echo SITE_URL; ?>/admin/users" class="nav-link rounded-pill py-2 px-3 d-flex align-items-center gap-2" 
       style="<?php echo isActiveAdminRoute('/admin/users', $currentUri); ?>">
        <i class="bi bi-people"></i>
        <span>إدارة المستخدمين</span>
    </a>
    <a href="<?php echo SITE_URL; ?>/admin/stores" class="nav-link rounded-pill py-2 px-3 d-flex align-items-center gap-2" 
       style="<?php echo isActiveAdminRoute('/admin/stores', $currentUri); ?>">
        <i class="bi bi-shop"></i>
        <span>توثيق وقبول المتاجر</span>
    </a>
    <a href="<?php echo SITE_URL; ?>/admin/products" class="nav-link rounded-pill py-2 px-3 d-flex align-items-center gap-2" 
       style="<?php echo isActiveAdminRoute('/admin/products', $currentUri); ?>">
        <i class="bi bi-box-seam"></i>
        <span>مراقبة المنتجات</span>
    </a>
    <a href="<?php echo SITE_URL; ?>/admin/categories" class="nav-link rounded-pill py-2 px-3 d-flex align-items-center gap-2" 
       style="<?php echo isActiveAdminRoute('/admin/categories', $currentUri); ?>">
        <i class="bi bi-tags"></i>
        <span>تصنيفات المنصة</span>
    </a>
    <a href="<?php echo SITE_URL; ?>/admin/advertisements" class="nav-link rounded-pill py-2 px-3 d-flex align-items-center gap-2" 
       style="<?php echo isActiveAdminRoute('/admin/advertisements', $currentUri); ?>">
        <i class="bi bi-badge-ad"></i>
        <span>إعلانات السلايدر والبنرات</span>
    </a>
    <a href="<?php echo SITE_URL; ?>/admin/reports" class="nav-link rounded-pill py-2 px-3 d-flex align-items-center gap-2" 
       style="<?php echo isActiveAdminRoute('/admin/reports', $currentUri); ?>">
        <i class="bi bi-flag"></i>
        <span>الشكاوى والبلاغات</span>
    </a>

    <?php if ($user->role === 'super_admin'): ?>
    <!-- Super Admin Section -->
    <div class="mt-4 px-3 mb-2 text-danger text-uppercase fw-bold" style="font-size: 0.75rem; letter-spacing: 0.5px;">
        المدير العام (Super Admin)
    </div>
    
    <a href="<?php echo SITE_URL; ?>/super-admin" class="nav-link rounded-pill py-2 px-3 d-flex align-items-center gap-2" 
       style="<?php echo ($currentUri === '/super-admin') ? 'background-color: var(--color-error); color: #FFF !important; font-weight: 600;' : 'color: var(--color-text-secondary);'; ?>">
        <i class="bi bi-shield-shaded"></i>
        <span>لوحة التحكم العامة</span>
    </a>
    <a href="<?php echo SITE_URL; ?>/super-admin/admins" class="nav-link rounded-pill py-2 px-3 d-flex align-items-center gap-2" 
       style="<?php echo isActiveAdminRoute('/super-admin/admins', $currentUri); ?>">
        <i class="bi bi-person-badge"></i>
        <span>إدارة المشرفين</span>
    </a>
    <a href="<?php echo SITE_URL; ?>/super-admin/plans" class="nav-link rounded-pill py-2 px-3 d-flex align-items-center gap-2" 
       style="<?php echo isActiveAdminRoute('/super-admin/plans', $currentUri); ?>">
        <i class="bi bi-credit-card"></i>
        <span>باقات الاشتراكات</span>
    </a>
    <a href="<?php echo SITE_URL; ?>/super-admin/audit-logs" class="nav-link rounded-pill py-2 px-3 d-flex align-items-center gap-2" 
       style="<?php echo isActiveAdminRoute('/super-admin/audit-logs', $currentUri); ?>">
        <i class="bi bi-file-earmark-medical"></i>
        <span>سجل العمليات (Audit)</span>
    </a>
    <a href="<?php echo SITE_URL; ?>/super-admin/settings" class="nav-link rounded-pill py-2 px-3 d-flex align-items-center gap-2" 
       style="<?php echo isActiveAdminRoute('/super-admin/settings', $currentUri); ?>">
        <i class="bi bi-sliders"></i>
        <span>إعدادات النظام العامة</span>
    </a>
    <?php endif; ?>
    
    <div class="mt-4 mb-2 border-top pt-3">
        <a href="<?php echo SITE_URL; ?>/dashboard" class="btn btn-outline-secondary btn-sm w-100 rounded-pill">
            <i class="bi bi-arrow-right-short"></i> لوحة الزبون العامة
        </a>
    </div>
</div>
