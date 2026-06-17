<div class="mb-4">
    <h3 class="fw-bold m-0"><i class="bi bi-plus-circle text-gold"></i> إضافة منتج جديد</h3>
    <small class="text-muted">قم بتعبئة بيانات المنتج لعرضه على المنصة ومقارنته بالأسواق</small>
</div>

<div class="card border rounded-lg p-4 bg-white shadow-sm">
    <form action="<?php echo SITE_URL; ?>/store-owner/products/create" method="POST" enctype="multipart/form-data">
        <?php echo \Core\CSRF::field(); ?>

        <div class="row g-3 mb-4">
            <!-- Product Title AR -->
            <div class="col-md-6">
                <label for="name_ar" class="form-label small fw-bold text-dark">اسم المنتج (بالعربية) *</label>
                <input type="text" name="name_ar" id="name_ar" class="form-control" placeholder="مثال: لابتوب ديل كور آي 7" required>
            </div>

            <!-- Product Title EN -->
            <div class="col-md-6">
                <label for="name_en" class="form-label small fw-bold text-dark">اسم المنتج (بالإنكليزية) *</label>
                <input type="text" name="name_en" id="name_en" class="form-control" placeholder="Dell Laptop Core i7" required>
            </div>

            <!-- Brand -->
            <div class="col-md-4">
                <label for="brand" class="form-label small fw-bold text-dark">الماركة / المصنع</label>
                <input type="text" name="brand" id="brand" class="form-control" placeholder="Dell, Apple, Samsung...">
            </div>

            <!-- Price -->
            <div class="col-md-4">
                <label for="price" class="form-label small fw-bold text-dark">سعر المنتج الأصلي (د.ع) *</label>
                <input type="number" name="price" id="price" class="form-control" placeholder="150000" required>
            </div>

            <!-- Discount Price -->
            <div class="col-md-4">
                <label for="discount_price" class="form-label small fw-bold text-dark">سعر الخصم / العرض (اختياري)</label>
                <input type="number" name="discount_price" id="discount_price" class="form-control" placeholder="140000">
            </div>

            <!-- Category -->
            <div class="col-md-4">
                <label for="category_id" class="form-label small fw-bold text-dark">التصنيف *</label>
                <select name="category_id" id="category_id" class="form-select" required>
                    <option value="">اختر التصنيف...</option>
                    <?php foreach ($categories as $cat): ?>
                        <?php $catName = ($lang === 'en') ? $cat->name_en : (($lang === 'ku') ? $cat->name_ku : $cat->name_ar); ?>
                        <option value="<?php echo $cat->id; ?>"><?php echo $catName; ?></option>
                        <?php if (!empty($cat->subcategories)): ?>
                            <?php foreach ($cat->subcategories as $sub): ?>
                                <?php $subName = ($lang === 'en') ? $sub->name_en : (($lang === 'ku') ? $sub->name_ku : $sub->name_ar); ?>
                                <option value="<?php echo $sub->id; ?>">&nbsp;&nbsp;— <?php echo $subName; ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Condition -->
            <div class="col-md-4">
                <label for="condition" class="form-label small fw-bold text-dark">الحالة</label>
                <select name="condition" id="condition" class="form-select">
                    <option value="new">جديد (New)</option>
                    <option value="used">مستعمل (Used)</option>
                    <option value="refurbished">مجدد (Refurbished)</option>
                </select>
            </div>

            <!-- Stock status -->
            <div class="col-md-4">
                <label for="stock" class="form-label small fw-bold text-dark">حالة المخزون</label>
                <select name="stock" id="stock" class="form-select">
                    <option value="in_stock">متوفر (In stock)</option>
                    <option value="limited">كمية محدودة (Limited)</option>
                    <option value="out_of_stock">غير متوفر (Out of stock)</option>
                </select>
            </div>

            <!-- Image Upload -->
            <div class="col-12">
                <label for="image" class="form-label small fw-bold text-dark">صورة المنتج الأساسية</label>
                <input type="file" name="image" id="image" class="form-control">
            </div>

            <!-- Description -->
            <div class="col-12">
                <label for="description_ar" class="form-label small fw-bold text-dark">الوصف والتفاصيل</label>
                <textarea name="description_ar" id="description_ar" class="form-control" rows="4" placeholder="اكتب تفاصيل وميزات ومواصفات المنتج هنا..."></textarea>
            </div>
        </div>

        <div class="d-flex gap-3">
            <button type="submit" class="btn btn-souk-primary px-5 rounded-pill">إضافة المنتج ونشره</button>
            <a href="<?php echo SITE_URL; ?>/store-owner/products" class="btn btn-outline-secondary px-4 rounded-pill">إلغاء</a>
        </div>
    </form>
</div>
