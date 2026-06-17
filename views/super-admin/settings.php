<div class="mb-4">
    <h3 class="fw-bold m-0 text-danger"><i class="bi bi-sliders"></i> إعدادات النظام العامة</h3>
    <small class="text-muted">التحكم في المتغيرات الأساسية للمنصة ومعدلات الحماية ونظام الكاش</small>
</div>

<!-- Alert messages -->
<?php if ($flashSuccess = \Core\Session::getFlash('success')): ?>
    <div class="alert alert-success border small py-2 mb-3">
        <?php echo $flashSuccess; ?>
    </div>
<?php endif; ?>

<div class="card border rounded-lg p-4 bg-white shadow-sm">
    <form action="<?php echo SITE_URL; ?>/super-admin/settings/save" method="POST">
        <?php echo \Core\CSRF::field(); ?>

        <div class="row g-3 mb-4">
            <!-- Site Name -->
            <div class="col-md-6">
                <label for="site_name" class="form-label small fw-bold text-dark">اسم المنصة الرئيسي</label>
                <input type="text" id="site_name" class="form-control" value="سوق.IQ — دليلك للتسوق والمقارنة في العراق">
            </div>

            <!-- Maintenance mode -->
            <div class="col-md-6">
                <label for="maintenance" class="form-label small fw-bold text-dark">وضع الصيانة</label>
                <select id="maintenance" class="form-select">
                    <option value="0" selected>مغلق (المنصة نشطة للجميع)</option>
                    <option value="1">مفعل (إظهار صفحة الصيانة للزوار)</option>
                </select>
            </div>

            <!-- Cache status -->
            <div class="col-md-6">
                <label for="cache" class="form-label small fw-bold text-dark">نظام التخزين المؤقت (Cache)</label>
                <select id="cache" class="form-select">
                    <option value="1" selected>مفعل (سرعة تحميل فائقة)</option>
                    <option value="0">معطل (تحميل مباشر من قاعدة البيانات)</option>
                </select>
            </div>

            <!-- Rate limit threshold -->
            <div class="col-md-6">
                <label for="rate_limit" class="form-label small fw-bold text-dark">الحد الأقصى للطلبات بالدقيقة (Rate limit)</label>
                <input type="number" id="rate_limit" class="form-control" value="60">
            </div>
        </div>

        <button type="submit" class="btn btn-danger px-5 rounded-pill">حفظ الإعدادات العامة</button>
    </form>
</div>
