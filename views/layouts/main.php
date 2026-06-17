<!DOCTYPE html>
<?php
$htmlAttrs = getHtmlAttributes();
$lang = $_SESSION['lang'] ?? 'ar';
$dir = ($lang === 'en') ? 'ltr' : 'rtl';
?>
<html <?php echo $htmlAttrs; ?>>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($title) ? $title : __('logo') . ' | ' . __('hero_title'); ?></title>
    
    <!-- SEO Meta Tags -->
    <meta name="description" content="<?php echo isset($meta_description) ? $meta_description : __('hero_subtitle'); ?>">
    <meta property="og:title" content="<?php echo isset($title) ? $title : __('logo'); ?>">
    <meta property="og:description" content="<?php echo isset($meta_description) ? $meta_description : __('hero_subtitle'); ?>">
    <meta property="og:type" content="website">
    
    <!-- hreflang alternates -->
    <link rel="alternate" hreflang="ar" href="?lang=ar">
    <link rel="alternate" hreflang="ku" href="?lang=ku">
    <link rel="alternate" hreflang="en" href="?lang=en">

    <!-- CSS Vendor dependencies -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <!-- Custom CSS Core -->
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/main.css">
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/components.css">

    <?php if ($dir === 'rtl'): ?>
    <!-- Bootstrap RTL (only loaded for RTL languages) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css">
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/rtl.css">
    <?php endif; ?>

    <!-- Define global JS variables -->
    <script>
        const SITE_URL = '<?php echo SITE_URL; ?>';
    </script>
</head>
<body class="<?php echo ($lang === 'en') ? 'ltr-mode' : 'rtl-mode'; ?>">

    <!-- Toast Notification Container -->
    <div id="souk-toast-container">
        <?php if ($flashError = \Core\Session::getFlash('error')): ?>
            <div class="souk-toast souk-toast--error">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-exclamation-triangle-fill text-danger"></i>
                    <span><?php echo $flashError; ?></span>
                </div>
                <button type="button" class="btn-close ms-2" onclick="this.parentElement.remove()"></button>
            </div>
        <?php endif; ?>
        <?php if ($flashSuccess = \Core\Session::getFlash('success')): ?>
            <div class="souk-toast souk-toast--success">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-check-circle-fill text-success"></i>
                    <span><?php echo $flashSuccess; ?></span>
                </div>
                <button type="button" class="btn-close ms-2" onclick="this.parentElement.remove()"></button>
            </div>
        <?php endif; ?>
    </div>

    <!-- Header Navbar -->
    <?php $this->partial('navbar'); ?>

    <!-- Main View Content -->
    <main>
        <?php echo $content; ?>
    </main>

    <!-- Footer -->
    <?php $this->partial('footer'); ?>

    <!-- JS Vendor dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    
    <!-- Custom JS core scripts -->
    <script src="<?php echo SITE_URL; ?>/assets/js/app.js"></script>
    <script src="<?php echo SITE_URL; ?>/assets/js/search.js"></script>

    <script>
        // Init animations
        AOS.init({
            duration: 800,
            once: true
        });
    </script>
</body>
</html>
