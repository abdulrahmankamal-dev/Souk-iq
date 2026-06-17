<div class="mb-4">
    <h3 class="fw-bold m-0"><i class="bi bi-tags text-gold"></i> تصنيفات المنصة</h3>
    <small class="text-muted">إضافة وتعديل التصنيفات والفئات الرئيسية والفرعية للمنتجات</small>
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
    <!-- List of categories (Col 7) -->
    <div class="col-lg-7">
        <div class="card border rounded-lg p-4 bg-white shadow-sm h-100">
            <h5 class="fw-bold text-dark mb-4 border-bottom pb-2">التصنيفات الحالية</h5>
            
            <div class="table-responsive">
                <table class="table table-striped table-bordered align-middle small">
                    <thead>
                        <tr class="bg-light">
                            <th>الاسم (عربي)</th>
                            <th>الاسم (إنكليزي)</th>
                            <th>الـ Slug</th>
                            <th>الأيقونة</th>
                            <th>حذف</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($categories as $cat): ?>
                            <tr>
                                <td>
                                    <strong><?php echo $cat->name_ar; ?></strong>
                                    <?php if ($cat->parent_id): ?>
                                        <span class="badge bg-secondary-subtle text-secondary ms-1">فرعي</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo $cat->name_en; ?></td>
                                <td><code><?php echo $cat->slug; ?></code></td>
                                <td class="text-center"><i class="bi <?php echo $cat->icon; ?> text-gold fs-5"></i></td>
                                <td class="text-center">
                                    <form action="<?php echo SITE_URL; ?>/admin/categories/delete" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذا التصنيف؟');">
                                        <?php echo \Core\CSRF::field(); ?>
                                        <input type="hidden" name="id" value="<?php echo $cat->id; ?>">
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Create Category (Col 5) -->
    <div class="col-lg-5">
        <div class="card border rounded-lg p-4 bg-white shadow-sm h-100">
            <h5 class="fw-bold text-dark mb-4 border-bottom pb-2">إضافة تصنيف جديد</h5>
            
            <form action="<?php echo SITE_URL; ?>/admin/categories/create" method="POST">
                <?php echo \Core\CSRF::field(); ?>

                <div class="mb-3">
                    <label for="name_ar" class="form-label small fw-bold text-dark">الاسم بالعربية *</label>
                    <input type="text" name="name_ar" id="name_ar" class="form-control form-control-sm" required>
                </div>

                <div class="mb-3">
                    <label for="name_en" class="form-label small fw-bold text-dark">الاسم بالإنكليزية *</label>
                    <input type="text" name="name_en" id="name_en" class="form-control form-control-sm" required>
                </div>

                <div class="mb-3">
                    <label for="slug" class="form-label small fw-bold text-dark">الرابط الفرعي (Slug) *</label>
                    <input type="text" name="slug" id="slug" class="form-control form-control-sm" placeholder="electronics" required>
                </div>

                <div class="mb-3">
                    <label for="icon" class="form-label small fw-bold text-dark">أيقونة Bootstrap Icons</label>
                    <input type="text" name="icon" id="icon" class="form-control form-control-sm" placeholder="bi-laptop">
                </div>

                <div class="mb-4">
                    <label for="parent_id" class="form-label small fw-bold text-dark">التصنيف الأب (اختياري للفئات الفرعية)</label>
                    <select name="parent_id" id="parent_id" class="form-select form-select-sm">
                        <option value="">لا يوجد (فئة رئيسية)</option>
                        <?php foreach ($categories as $cat): ?>
                            <?php if (!$cat->parent_id): ?>
                                <option value="<?php echo $cat->id; ?>"><?php echo $cat->name_ar; ?></option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </select>
                </div>

                <button type="submit" class="btn btn-souk-primary w-100 rounded-pill py-2">إنشاء التصنيف</button>
            </form>
        </div>
    </div>
</div>
