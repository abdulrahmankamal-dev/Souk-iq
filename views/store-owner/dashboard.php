<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold m-0"><i class="bi bi-shop text-gold"></i> لوحة تحكم المتجر</h3>
    <a href="<?php echo SITE_URL; ?>/store/<?php echo $store->slug; ?>" target="_blank" class="btn btn-outline-warning rounded-pill btn-sm px-3">
        <i class="bi bi-box-arrow-up-right me-1"></i> عرض المتجر للعامة
    </a>
</div>

<!-- Alert notifications -->
<?php if ($flashInfo = \Core\Session::getFlash('info')): ?>
    <div class="alert alert-info border small py-2 mb-3">
        <?php echo $flashInfo; ?>
    </div>
<?php endif; ?>

<!-- Store Status Card -->
<div class="card border rounded-lg p-4 bg-white shadow-sm mb-4">
    <div class="d-flex align-items-center justify-content-between">
        <div>
            <h4 class="fw-bold text-dark mb-1"><?php echo $store->name_ar; ?></h4>
            <p class="text-muted small mb-0"><i class="bi bi-link-45deg"></i> Slug: <?php echo $store->slug; ?></p>
        </div>
        <div class="text-end">
            <span class="badge px-3 py-2 rounded-pill fs-6 <?php echo ($store->status === 'active') ? 'bg-success-subtle text-success border border-success' : (($store->status === 'pending') ? 'bg-warning-subtle text-warning border border-warning' : 'bg-danger-subtle text-danger border border-danger'); ?>">
                حالة المتجر: <?php echo ($store->status === 'active') ? 'نشط وموثق ✓' : (($store->status === 'pending') ? 'قيد المراجعة والقبول 🕒' : 'مرفوض ✗'); ?>
            </span>
            <?php if ($store->status === 'rejected'): ?>
                <small class="text-danger d-block mt-2">سبب الرفض: <?php echo $store->rejection_reason; ?></small>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Stats Indicators -->
<div class="row g-4 mb-4">
    <!-- Products count -->
    <div class="col-md-3">
        <div class="card border rounded-lg p-3 bg-white shadow-sm d-flex flex-row align-items-center gap-3">
            <div class="bg-primary-subtle text-primary rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 55px; height: 55px;">
                <i class="bi bi-box-seam fs-3"></i>
            </div>
            <div>
                <h3 class="fw-bold text-dark m-0"><?php echo $totalProducts; ?></h3>
                <small class="text-muted">المنتجات المنشورة</small>
            </div>
        </div>
    </div>

    <!-- Views count -->
    <div class="col-md-3">
        <div class="card border rounded-lg p-3 bg-white shadow-sm d-flex flex-row align-items-center gap-3">
            <div class="bg-info-subtle text-info rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 55px; height: 55px;">
                <i class="bi bi-eye fs-3"></i>
            </div>
            <div>
                <h3 class="fw-bold text-dark m-0"><?php echo $store->views_count; ?></h3>
                <small class="text-muted">مشاهدات المحل</small>
            </div>
        </div>
    </div>

    <!-- Followers count -->
    <div class="col-md-3">
        <div class="card border rounded-lg p-3 bg-white shadow-sm d-flex flex-row align-items-center gap-3">
            <div class="bg-success-subtle text-success rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 55px; height: 55px;">
                <i class="bi bi-heart fs-3"></i>
            </div>
            <div>
                <h3 class="fw-bold text-dark m-0"><?php echo $store->followers_count; ?></h3>
                <small class="text-muted">المتابعين</small>
            </div>
        </div>
    </div>

    <!-- Rating -->
    <div class="col-md-3">
        <div class="card border rounded-lg p-3 bg-white shadow-sm d-flex flex-row align-items-center gap-3">
            <div class="bg-warning-subtle text-warning rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 55px; height: 55px;">
                <i class="bi bi-star-fill fs-3"></i>
            </div>
            <div>
                <h3 class="fw-bold text-dark m-0"><?php echo number_format($store->avg_rating, 1); ?></h3>
                <small class="text-muted">تقييم المتجر</small>
            </div>
        </div>
    </div>
</div>

<div class="card border rounded-lg p-4 bg-white shadow-sm">
    <h5 class="fw-bold text-dark mb-3">ابدأ بإدارة متجرك اليوم:</h5>
    <div class="row g-3">
        <div class="col-md-4">
            <a href="<?php echo SITE_URL; ?>/store-owner/products" class="btn btn-outline-secondary w-100 py-3 rounded-lg text-center d-block">
                <i class="bi bi-box-seam fs-3 d-block mb-1 text-gold"></i>
                إدارة منتجات المحل
            </a>
        </div>
        <div class="col-md-4">
            <a href="<?php echo SITE_URL; ?>/store-owner/settings" class="btn btn-outline-secondary w-100 py-3 rounded-lg text-center d-block">
                <i class="bi bi-gear fs-3 d-block mb-1 text-gold"></i>
                تعديل إعدادات وبيانات المحل
            </a>
        </div>
        <div class="col-md-4">
            <a href="<?php echo SITE_URL; ?>/store-owner/reviews" class="btn btn-outline-secondary w-100 py-3 rounded-lg text-center d-block">
                <i class="bi bi-chat-left-quote fs-3 d-block mb-1 text-gold"></i>
                عرض تقييمات والرد على الزبائن
            </a>
        </div>
    </div>
</div>
