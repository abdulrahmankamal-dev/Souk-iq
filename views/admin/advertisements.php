<div class="mb-4">
    <h3 class="fw-bold m-0"><i class="bi bi-badge-ad text-gold"></i> بنرات الحملات الإعلانية</h3>
    <small class="text-muted">جدولة وحجز البنرات الإعلانية في الصفحة الرئيسية وصفحات البحث</small>
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
    <!-- List of ads (Col 8) -->
    <div class="col-lg-8">
        <div class="card border rounded-lg p-4 bg-white shadow-sm h-100">
            <h5 class="fw-bold text-dark mb-4 border-bottom pb-2">الحملات النشطة والمعلقة</h5>
            
            <div class="table-responsive">
                <table class="table table-striped table-bordered align-middle small">
                    <thead>
                        <tr class="bg-light">
                            <th>الإعلان</th>
                            <th>الموضع</th>
                            <th>المتجر</th>
                            <th>الحالة</th>
                            <th>تغيير الحالة</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($ads as $ad): ?>
                            <tr>
                                <td>
                                    <strong class="text-dark d-block"><?php echo $ad->title; ?></strong>
                                    <small class="text-muted">الرابط: <?php echo $ad->link; ?></small>
                                </td>
                                <td><code><?php echo $ad->type; ?></code></td>
                                <td><?php echo $ad->store_name; ?></td>
                                <td class="text-center">
                                    <span class="badge rounded-pill <?php echo ($ad->status === 'active') ? 'bg-success' : 'bg-warning'; ?>">
                                        <?php echo ($ad->status === 'active') ? 'نشط' : 'موقوف / معلق'; ?>
                                    </span>
                                </td>
                                <td>
                                    <form action="<?php echo SITE_URL; ?>/admin/advertisements/status" method="POST">
                                        <?php echo \Core\CSRF::field(); ?>
                                        <input type="hidden" name="id" value="<?php echo $ad->id; ?>">
                                        <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                                            <option value="active" <?php echo ($ad->status === 'active') ? 'selected' : ''; ?>>تفعيل</option>
                                            <option value="paused" <?php echo ($ad->status === 'paused') ? 'selected' : ''; ?>>إيقاف مؤقت</option>
                                            <option value="expired" <?php echo ($ad->status === 'expired') ? 'selected' : ''; ?>>إنهاء الحملة</option>
                                        </select>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Create Ad form (Col 4) -->
    <div class="col-lg-4">
        <div class="card border rounded-lg p-4 bg-white shadow-sm h-100">
            <h5 class="fw-bold text-dark mb-4 border-bottom pb-2">جدولة إعلان جديد</h5>
            
            <form action="<?php echo SITE_URL; ?>/admin/advertisements/create" method="POST" enctype="multipart/form-data">
                <?php echo \Core\CSRF::field(); ?>

                <div class="mb-3">
                    <label for="title" class="form-label small fw-bold text-dark">عنوان الإعلان *</label>
                    <input type="text" name="title" id="title" class="form-control form-control-sm" required>
                </div>

                <div class="mb-3">
                    <label for="type" class="form-label small fw-bold text-dark">نوع الموضع الإعلاني</label>
                    <select name="type" id="type" class="form-select form-select-sm">
                        <option value="banner_home">سلايدر الصفحة الرئيسية (Home Banner)</option>
                        <option value="featured_store">متجر مميز بالواجهة (Featured Store)</option>
                        <option value="banner_search">بنر جانبي بالبحث (Search Banner)</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="store_id" class="form-label small fw-bold text-dark">المتجر المستفيد</label>
                    <select name="store_id" id="store_id" class="form-select form-select-sm">
                        <option value="">لا يوجد (إعلان خارجي)</option>
                        <?php foreach ($stores as $st): ?>
                            <option value="<?php echo $st->id; ?>"><?php echo $st->name_ar; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="link" class="form-label small fw-bold text-dark">رابط التوجيه (URL)</label>
                    <input type="text" name="link" id="link" class="form-control form-control-sm" placeholder="/store/store-slug">
                </div>

                <div class="mb-3">
                    <label for="image" class="form-label small fw-bold text-dark">صورة البنر الإعلاني *</label>
                    <input type="file" name="image" id="image" class="form-control form-control-sm" required>
                </div>

                <div class="mb-4">
                    <label for="position" class="form-label small fw-bold text-dark">ترتيب العرض</label>
                    <input type="number" name="position" id="position" class="form-control form-control-sm" value="1">
                </div>

                <button type="submit" class="btn btn-souk-primary w-100 rounded-pill py-2.5">بدء وبث الحملة</button>
            </form>
        </div>
    </div>
</div>
