<?php
$lang = $_SESSION['lang'] ?? 'ar';
$dir = ($lang === 'en') ? 'ltr' : 'rtl';
?>
<footer class="bg-dark text-light py-5 mt-5 border-top border-gold">
    <div class="container">
        <div class="row g-4">
            <!-- Col 1: Brand & Bio -->
            <div class="col-lg-4 col-md-6">
                <h5 class="text-gold fw-bold mb-3" style="font-family: var(--font-display); font-size: 1.5rem;">
                    <i class="bi bi-shop me-1"></i> <?php echo __('logo'); ?>
                </h5>
                <p class="text-muted small mb-4">
                    <?php echo __('hero_subtitle'); ?>
                </p>
                <div class="d-flex gap-3">
                    <a href="#" class="text-light hover-gold"><i class="bi bi-facebook fs-5"></i></a>
                    <a href="#" class="text-light hover-gold"><i class="bi bi-instagram fs-5"></i></a>
                    <a href="#" class="text-light hover-gold"><i class="bi bi-tiktok fs-5"></i></a>
                    <a href="#" class="text-light hover-gold"><i class="bi bi-whatsapp fs-5"></i></a>
                </div>
            </div>

            <!-- Col 2: Categories Links -->
            <div class="col-lg-2 col-md-6">
                <h6 class="text-gold fw-bold mb-3"><?php echo __('categories'); ?></h6>
                <ul class="list-unstyled small d-flex flex-column gap-2">
                    <li><a href="<?php echo SITE_URL; ?>/search?category=1" class="text-muted hover-light"><?php echo ($lang === 'en') ? 'Electronics' : (($lang === 'ku') ? 'ئەلەکترۆنیات' : 'الإلكترونيات'); ?></a></li>
                    <li><a href="<?php echo SITE_URL; ?>/search?category=2" class="text-muted hover-light"><?php echo ($lang === 'en') ? 'Fashion' : (($lang === 'ku') ? 'جلوبەرگ و مۆدە' : 'الأزياء والملابس'); ?></a></li>
                    <li><a href="<?php echo SITE_URL; ?>/search?category=3" class="text-muted hover-light"><?php echo ($lang === 'en') ? 'Supermarket' : (($lang === 'ku') ? 'سۆپەرمارکێت' : 'السوبرماركت'); ?></a></li>
                    <li><a href="<?php echo SITE_URL; ?>/search?category=4" class="text-muted hover-light"><?php echo ($lang === 'en') ? 'Vehicles' : (($lang === 'ku') ? 'ئۆتۆمبێل' : 'السيارات'); ?></a></li>
                </ul>
            </div>

            <!-- Col 3: Governorate Search Links -->
            <div class="col-lg-3 col-md-6">
                <h6 class="text-gold fw-bold mb-3"><?php echo __('browse_governorate'); ?></h6>
                <div class="row g-2 list-unstyled small">
                    <div class="col-6">
                        <ul class="list-unstyled d-flex flex-column gap-2">
                            <li><a href="<?php echo SITE_URL; ?>/search?gov=Baghdad" class="text-muted hover-light"><?php echo ($lang === 'en') ? 'Baghdad' : (($lang === 'ku') ? 'بەغدا' : 'بغداد'); ?></a></li>
                            <li><a href="<?php echo SITE_URL; ?>/search?gov=Basra" class="text-muted hover-light"><?php echo ($lang === 'en') ? 'Basra' : (($lang === 'ku') ? 'بەسرە' : 'البصرة'); ?></a></li>
                            <li><a href="<?php echo SITE_URL; ?>/search?gov=Erbil" class="text-muted hover-light"><?php echo ($lang === 'en') ? 'Erbil' : (($lang === 'ku') ? 'هەولێر' : 'أربيل'); ?></a></li>
                        </ul>
                    </div>
                    <div class="col-6">
                        <ul class="list-unstyled d-flex flex-column gap-2">
                            <li><a href="<?php echo SITE_URL; ?>/search?gov=Sulaymaniyah" class="text-muted hover-light"><?php echo ($lang === 'en') ? 'Sulaymaniyah' : (($lang === 'ku') ? 'سلێمانی' : 'السليمانية'); ?></a></li>
                            <li><a href="<?php echo SITE_URL; ?>/search?gov=Duhok" class="text-muted hover-light"><?php echo ($lang === 'en') ? 'Duhok' : (($lang === 'ku') ? 'دهۆک' : 'دهوك'); ?></a></li>
                            <li><a href="<?php echo SITE_URL; ?>/search?gov=Nineveh" class="text-muted hover-light"><?php echo ($lang === 'en') ? 'Nineveh' : (($lang === 'ku') ? 'نەینەوا' : 'نينوى'); ?></a></li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Col 4: Platform Information -->
            <div class="col-lg-3 col-md-6">
                <h6 class="text-gold fw-bold mb-3"><?php echo __('how_it_works'); ?></h6>
                <p class="text-muted small">
                    سوق.IQ هي منصة عراقية مجانية للبحث والمقارنة. نحن نربط بين البائع والمشتري بشكل مباشر في جميع أنحاء العراق.
                </p>
                <div class="mt-3">
                    <a href="<?php echo SITE_URL; ?>/install" class="btn btn-outline-warning btn-sm rounded-pill px-3">
                        <i class="bi bi-gear-fill me-1"></i> تشغيل المثبت (Install DB)
                    </a>
                </div>
            </div>
        </div>

        <hr class="border-secondary my-4">

        <div class="row align-items-center small text-muted">
            <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                &copy; <?php echo date('Y'); ?> سوق.IQ. جميع الحقوق محفوظة. صنع في العراق 🇮🇶
            </div>
            <div class="col-md-6 text-center text-md-end d-flex gap-3 justify-content-center justify-content-md-end">
                <a href="#" class="text-muted hover-light">شروط الخدمة</a>
                <a href="#" class="text-muted hover-light">سياسة الخصوصية</a>
                <a href="#" class="text-muted hover-light">الدعم الفني</a>
            </div>
        </div>
    </div>
</footer>
