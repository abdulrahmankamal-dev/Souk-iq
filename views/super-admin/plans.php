<div class="mb-4">
    <h3 class="fw-bold m-0 text-danger"><i class="bi bi-credit-card"></i> باقات اشتراك المتاجر</h3>
    <small class="text-muted">تخصيص أسعار الاشتراكات الشهرية وتحديد الحد الأقصى للمنتجات والخصائص لكل باقة</small>
</div>

<!-- Alert messages -->
<?php if ($flashSuccess = \Core\Session::getFlash('success')): ?>
    <div class="alert alert-success border small py-2 mb-3">
        <?php echo $flashSuccess; ?>
    </div>
<?php endif; ?>
<?php if ($flashError = \Core\Session::getFlash('error')): ?>
    <div class="alert alert-danger border small py-2 mb-3">
        <?php echo $flashError; ?>
    </div>
<?php endif; ?>

<div class="row g-4">
    <!-- List of plans (Col 7) -->
    <div class="col-lg-7">
        <div class="card border rounded-lg p-4 bg-white shadow-sm h-100">
            <h5 class="fw-bold text-dark mb-4 border-bottom pb-2">الباقات النشطة حالياً</h5>
            
            <div class="table-responsive">
                <table class="table table-striped table-bordered align-middle small">
                    <thead>
                        <tr class="bg-light">
                            <th>الباقة</th>
                            <th>الاسم بالإنكليزية</th>
                            <th>السعر الشهري</th>
                            <th>أقصى منتجات</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($plans as $plan): ?>
                            <tr>
                                <td><strong class="text-dark"><?php echo $plan->name_ar; ?></strong></td>
                                <td><?php echo $plan->name_en; ?></td>
                                <td class="text-center fw-bold"><?php echo number_format($plan->price_monthly); ?> د.ع</td>
                                <td class="text-center"><?php echo $plan->max_products; ?> منتج</td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Edit/Create Plan (Col 5) -->
    <div class="col-lg-5">
        <div class="card border rounded-lg p-4 bg-white shadow-sm h-100">
            <h5 class="fw-bold text-dark mb-4 border-bottom pb-2">إضافة / تعديل باقة</h5>
            
            <form action="<?php echo SITE_URL; ?>/super-admin/plans/save" method="POST">
                <?php echo \Core\CSRF::field(); ?>
                <input type="hidden" name="id" value="0">

                <div class="mb-3">
                    <label for="name_ar" class="form-label small fw-bold text-dark">اسم الباقة بالعربية *</label>
                    <input type="text" name="name_ar" id="name_ar" class="form-control form-control-sm" required>
                </div>

                <div class="mb-3">
                    <label for="name_en" class="form-label small fw-bold text-dark">اسم الباقة بالإنكليزية *</label>
                    <input type="text" name="name_en" id="name_en" class="form-control form-control-sm" required>
                </div>

                <div class="mb-3">
                    <label for="price_monthly" class="form-label small fw-bold text-dark">السعر الشهري (د.ع) *</label>
                    <input type="number" name="price_monthly" id="price_monthly" class="form-control form-control-sm" required>
                </div>

                <div class="mb-4">
                    <label for="max_products" class="form-label small fw-bold text-dark">الحد الأقصى للمنتجات *</label>
                    <input type="number" name="max_products" id="max_products" class="form-control form-control-sm" value="10" required>
                </div>

                <button type="submit" class="btn btn-danger w-100 rounded-pill py-2.5">حفظ الباقة</button>
            </form>
        </div>
    </div>
</div>
