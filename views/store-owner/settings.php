<div class="mb-4">
    <h3 class="fw-bold m-0"><i class="bi bi-gear text-gold"></i> إعدادات الملف التجاري للمتجر</h3>
    <small class="text-muted">تحديث معلومات المحل والتواصل والموقع الجغرافي على الخريطة</small>
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

<div class="card border rounded-lg p-4 bg-white shadow-sm">
    <form action="<?php echo SITE_URL; ?>/store-owner/settings" method="POST" enctype="multipart/form-data">
        <?php echo \Core\CSRF::field(); ?>

        <div class="row g-3 mb-4">
            <!-- Store logo / banner preview if exists -->
            <?php if ($store): ?>
                <div class="col-md-6 text-center border-end pb-3 mb-3">
                    <label class="form-label small fw-bold d-block text-dark">شعار المتجر الحالي</label>
                    <img src="<?php echo $store->logo ? SITE_URL . '/uploads/logos/' . $store->logo : 'https://placehold.co/100'; ?>" 
                         class="rounded border object-cover mb-2" style="width: 80px; height: 80px;" alt="logo">
                    <div>
                        <label for="logo" class="form-label small fw-bold btn btn-sm btn-outline-secondary rounded-pill px-3">تحميل شعار جديد</label>
                        <input type="file" name="logo" id="logo" class="form-control d-none">
                    </div>
                </div>
                <div class="col-md-6 text-center pb-3 mb-3">
                    <label class="form-label small fw-bold d-block text-dark">تحميل غلاف للمتجر</label>
                    <input type="file" name="banner" id="banner" class="form-control">
                </div>
            <?php else: ?>
                <div class="col-md-6">
                    <label for="logo" class="form-label small fw-bold text-dark">شعار المتجر (Logo) *</label>
                    <input type="file" name="logo" id="logo" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label for="banner" class="form-label small fw-bold text-dark">صورة الغلاف للمتجر (Banner)</label>
                    <input type="file" name="banner" id="banner" class="form-control">
                </div>
            <?php endif; ?>

            <!-- Store Name AR -->
            <div class="col-md-6">
                <label for="name_ar" class="form-label small fw-bold text-dark">اسم المتجر (بالعربية) *</label>
                <input type="text" name="name_ar" id="name_ar" class="form-control" value="<?php echo htmlspecialchars($store->name_ar ?? ''); ?>" required>
            </div>

            <!-- Store Name EN -->
            <div class="col-md-6">
                <label for="name_en" class="form-label small fw-bold text-dark">اسم المتجر (بالإنكليزية) *</label>
                <input type="text" name="name_en" id="name_en" class="form-control" value="<?php echo htmlspecialchars($store->name_en ?? ''); ?>" required>
            </div>

            <!-- Store Slug -->
            <div class="col-md-6">
                <label for="slug" class="form-label small fw-bold text-dark">رابط المتجر الفرعي (URL Slug) *</label>
                <input type="text" name="slug" id="slug" class="form-control" placeholder="my-store-name" value="<?php echo htmlspecialchars($store->slug ?? ''); ?>" required>
            </div>

            <!-- Store category -->
            <div class="col-md-6">
                <label for="category_id" class="form-label small fw-bold text-dark">النشاط الرئيسي للفئة *</label>
                <select name="category_id" id="category_id" class="form-select" required>
                    <option value="">اختر الفئة...</option>
                    <?php foreach ($categories as $cat): ?>
                        <?php $catName = ($lang === 'en') ? $cat->name_en : (($lang === 'ku') ? $cat->name_ku : $cat->name_ar); ?>
                        <option value="<?php echo $cat->id; ?>" <?php echo ($store && $store->category_id == $cat->id) ? 'selected' : ''; ?>><?php echo $catName; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Governorate -->
            <div class="col-md-6">
                <label for="governorate" class="form-label small fw-bold text-dark">المحافظة *</label>
                <select name="governorate" id="governorate" class="form-select" required>
                    <option value="">اختر المحافظة...</option>
                    <?php foreach (GOVERNORATES['ar'] as $key => $govName): ?>
                        <option value="<?php echo $key; ?>" <?php echo ($store && $store->governorate === $key) ? 'selected' : ''; ?>><?php echo $govName; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- City -->
            <div class="col-md-6">
                <label for="city" class="form-label small fw-bold text-dark">المنطقة / القضاء *</label>
                <input type="text" name="city" id="city" class="form-control" value="<?php echo htmlspecialchars($store->city ?? ''); ?>" required>
            </div>

            <!-- Address line -->
            <div class="col-12">
                <label for="address_line" class="form-label small fw-bold text-dark">العنوان التفصيلي (الشارع، نقطة دالة)</label>
                <input type="text" name="address_line" id="address_line" class="form-control" value="<?php echo htmlspecialchars($store->address_line ?? ''); ?>">
            </div>

            <!-- Coordinates inputs -->
            <div class="col-md-6">
                <label for="latitude" class="form-label small fw-bold text-dark">خط العرض (Latitude)</label>
                <input type="text" name="latitude" id="latitude" class="form-control" value="<?php echo htmlspecialchars($store->latitude ?? '33.3152'); ?>" readonly>
            </div>
            <div class="col-md-6">
                <label for="longitude" class="form-label small fw-bold text-dark">خط الطول (Longitude)</label>
                <input type="text" name="longitude" id="longitude" class="form-control" value="<?php echo htmlspecialchars($store->longitude ?? '44.3661'); ?>" readonly>
            </div>

            <!-- Leaflet Map Picker -->
            <div class="col-12">
                <label class="form-label small fw-bold text-dark">اختر موقع المحل على الخريطة (اسحب العلامة للمكان الدقيق)</label>
                <div id="settings-map-picker" class="border rounded" style="height: 300px; z-index: 1;"></div>
            </div>

            <!-- Phone -->
            <div class="col-md-4">
                <label for="phone" class="form-label small fw-bold text-dark">رقم هاتف المحل للتواصل *</label>
                <input type="text" name="phone" id="phone" class="form-control" value="<?php echo htmlspecialchars($store->phone ?? ''); ?>" required>
            </div>

            <!-- WhatsApp -->
            <div class="col-md-4">
                <label for="whatsapp" class="form-label small fw-bold text-dark">رقم واتساب المبيعات</label>
                <input type="text" name="whatsapp" id="whatsapp" class="form-control" value="<?php echo htmlspecialchars($store->whatsapp ?? ''); ?>">
            </div>

            <!-- Website -->
            <div class="col-md-4">
                <label for="website" class="form-label small fw-bold text-dark">موقع الويب للمحل (إن وجد)</label>
                <input type="url" name="website" id="website" class="form-control" value="<?php echo htmlspecialchars($store->website ?? ''); ?>">
            </div>

            <!-- Description -->
            <div class="col-12">
                <label for="description_ar" class="form-label small fw-bold text-dark">وصف ونبذة عن المتجر</label>
                <textarea name="description_ar" id="description_ar" class="form-control" rows="3"><?php echo htmlspecialchars($store->description_ar ?? ''); ?></textarea>
            </div>
        </div>

        <button type="submit" class="btn btn-souk-primary px-5 rounded-pill">حفظ ملف المتجر</button>
    </form>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const initLat = <?php echo ($store && $store->latitude) ? floatval($store->latitude) : '33.3152'; ?>;
        const initLng = <?php echo ($store && $store->longitude) ? floatval($store->longitude) : '44.3661'; ?>;
        
        if (window.SoukMap) {
            SoukMap.initMapPicker('settings-map-picker', '#latitude', '#longitude', initLat, initLng);
        }
    });
</script>
