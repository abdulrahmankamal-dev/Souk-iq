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
    <title><?php echo isset($title) ? $title : __('logo') . ' | ' . __('login'); ?></title>
    
    <!-- CSS Vendor dependencies -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css" rel="stylesheet">

    <!-- Custom CSS Core -->
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/main.css">
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/components.css">

    <?php if ($dir === 'rtl'): ?>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css">
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/rtl.css">
    <?php endif; ?>

    <style>
        .auth-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--gradient-hero);
            padding: 20px 0;
            position: relative;
            overflow: hidden;
        }
        .auth-card-wrapper {
            width: 100%;
            max-width: 520px;
            z-index: 10;
        }
        .auth-bg-pattern {
            position: absolute;
            top: 0;
            right: 0;
            width: 300px;
            height: 300px;
            opacity: 0.15;
            background-image: radial-gradient(circle, var(--color-primary) 10%, transparent 11%);
            background-size: 20px 20px;
        }
    </style>
    <script>
        const SITE_URL = '<?php echo SITE_URL; ?>';
    </script>
</head>
<body>

    <div class="auth-container">
        <div class="auth-bg-pattern"></div>
        <div class="auth-card-wrapper px-3">
            <div class="text-center mb-4">
                <a href="<?php echo SITE_URL; ?>" class="h1 text-white text-decoration-none fw-bold" style="font-family: var(--font-display);">
                    <i class="bi bi-shop text-gold"></i> <?php echo __('logo'); ?>
                </a>
            </div>
            
            <?php echo $content; ?>
        </div>
    </div>

    <!-- JS Vendor dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo SITE_URL; ?>/assets/js/app.js"></script>
    <script src="<?php echo SITE_URL; ?>/assets/js/validation.js"></script>
</body>
</html>
