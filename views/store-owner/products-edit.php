<div class="mb-4">
    <h3 class="fw-bold m-0"><i class="bi bi-pencil-square text-gold"></i> تعديل المنتج: <?php echo $product->name_ar; ?></h3>
    <small class="text-muted">تحديث بيانات ومخزون وأسعار المنتج الحالي</small>
</div>

<!-- Alert messages -->
<?php if ($flashError = \Core\Session::getFlash('error')): ?>
    <div class="alert alert-danger border small py-2 mb-3">
        <?php echo $flashError; ?>
    </div>
<?php endif; ?>

<div class="card border rounded-lg p-4 bg-white shadow-sm">
    <form action="<?php echo SITE_URL; ?>/store-owner/products/edit/<?php echo $product->id; ?>" method="POST" enctype="multipart/form-data">
        <?php echo \Core\CSRF::field(); ?>

        <div class="row g-3 mb-4">
            <!-- Product Title AR -->
            <div class="col-md-6">
                <label for="name_ar" class="form-label small fw-bold text-dark">اسم المنتج (بالعربية) *</label>
                <input type="text" name="name_ar" id="name_ar" class="form-control" value="<?php echo htmlspecialchars($product->name_ar); ?>" required>
            </div>

            <!-- Product Title EN -->
            <div class="col-md-6">
                <label for="name_en" class="form-label small fw-bold text-dark">اسم المنتج (بالإنكليزية) *</label>
                <input type="text" name="name_en" id="name_en" class="form-control" value="<?php echo htmlspecialchars($product->name_en ?? ''); ?>" required>
            </div>

            <!-- Brand -->
            <div class="col-md-4">
                <label for="brand" class="form-label small fw-bold text-dark">الماركة / المصنع</label>
                <input type="text" name="brand" id="brand" class="form-control" value="<?php echo htmlspecialchars($product->brand ?? ''); ?>">
            </div>

            <!-- Price -->
            <div class="col-md-4">
                <label for="price" class="form-label small fw-bold text-dark">سعر المنتج الأصلي (د.ع) *</label>
                <input type="number" name="price" id="price" class="form-control" value="<?php echo intval($product->price); ?>" required>
            </div>

            <!-- Discount Price -->
            <div class="col-md-4">
                <label for="discount_price" class="form-label small fw-bold text-dark">سعر الخصم / العرض (اختياري)</label>
                <input type="number" name="discount_price" id="discount_price" class="form-control" value="<?php echo $product->discount_price ? intval($product->discount_price) : ''; ?>">
            </div>

            <!-- Category -->
            <div class="col-md-4">
                <label for="category_id" class="form-label small fw-bold text-dark">التصنيف *</label>
                <select name="category_id" id="category_id" class="form-select" required>
                    <option value="">اختر التصنيف...</option>
                    <?php foreach ($categories as $cat): ?>
                        <?php $catName = ($lang === 'en') ? $cat->name_en : (($lang === 'ku') ? $cat->name_ku : $cat->name_ar); ?>
                        <option value="<?php echo $cat->id; ?>" <?php echo ($product->category_id == $cat->id) ? 'selected' : ''; ?>><?php echo $catName; ?></option>
                        <?php if (!empty($cat->subcategories)): ?>
                            <?php foreach ($cat->subcategories as $sub): ?>
                                <?php $subName = ($lang === 'en') ? $sub->name_en : (($lang === 'ku') ? $sub->name_ku : $sub->name_ar); ?>
                                <option value="<?php echo $sub->id; ?>" <?php echo ($product->category_id == $sub->id) ? 'selected' : ''; ?>>&nbsp;&nbsp;— <?php echo $subName; ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Condition -->
            <div class="col-md-4">
                <label for="condition" class="form-label small fw-bold text-dark">الحالة</label>
                <select name="condition" id="condition" class="form-select">
                    <option value="new" <?php echo ($product->condition_type === 'new') ? 'selected' : ''; ?>>جديد (New)</option>
                    <option value="used" <?php echo ($product->condition_type === 'used') ? 'selected' : ''; ?>>مستعمل (Used)</option>
                    <option value="refurbished" <?php echo ($product->condition_type === 'refurbished') ? 'selected' : ''; ?>>مجدد (Refurbished)</option>
                </select>
            </div>

            <!-- Stock status -->
            <div class="col-md-4">
                <label for="stock" class="form-label small fw-bold text-dark">حالة المخزون</label>
                <select name="stock" id="stock" class="form-select">
                    <option value="in_stock" <?php echo ($product->stock_status === 'in_stock') ? 'selected' : ''; ?>>متوفر (In stock)</option>
                    <option value="limited" <?php echo ($product->stock_status === 'limited') ? 'selected' : ''; ?>>كمية محدودة (Limited)</option>
                    <option value="out_of_stock" <?php echo ($product->stock_status === 'out_of_stock') ? 'selected' : ''; ?>>غير متوفر (Out of stock)</option>
                </select>
            </div>

            <!-- Current image preview -->
            <div class="col-12 text-center pb-2 border-bottom">
                <label class="form-label small fw-bold d-block text-dark">صورة المنتج الحالية</label>
                <img src="<?php echo $product->thumbnail ? SITE_URL . '/uploads/products/' . $product->thumbnail : 'https://placehold.co/100'; ?>" 
                     alt="current" class="rounded object-cover border" width="100" height="100">
            </div>

            <!-- Image Upload -->
            <div class="col-12">
                <label for="image" class="form-label small fw-bold text-dark">تغيير صورة المنتج الأساسية</label>
                <input type="file" name="image" id="image" class="form-control">
            </div>

            <!-- Description -->
            <div class="col-12">
                <label for="description_ar" class="form-label small fw-bold text-dark">الوصف والتفاصيل</label>
                <textarea name="description_ar" id="description_ar" class="form-control" rows="4"><?php echo htmlspecialchars($product->description_ar ?? ''); ?></textarea>
            </div>
        </div>

        <div class="d-flex gap-3">
            <button type="submit" class="btn btn-souk-primary px-5 rounded-pill">تحديث المنتج</button>
            <a href="<?php echo SITE_URL; ?>/store-owner/products" class="btn btn-outline-secondary px-4 rounded-pill">إلغاء</a>
        </div>
    </form>
</div>
