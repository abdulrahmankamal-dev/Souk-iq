<?php
$lang = $_SESSION['lang'] ?? 'ar';
$dir = ($lang === 'en') ? 'ltr' : 'rtl';
?>

<!-- Hero Section -->
<section class="py-5 text-light d-flex align-items-center position-relative" style="background: var(--gradient-hero); min-height: 80vh;">
    <div class="container text-center py-5">
        <div class="badge rounded-pill bg-gold-light text-gold border border-gold px-3 py-2 mb-4" data-aos="fade-down">
            <i class="bi bi-geo-alt-fill me-1"></i> <?php echo __('hero_eyebrow'); ?>
        </div>
        <h1 class="fw-bold text-white mb-3" data-aos="fade-up" style="max-width: 900px; margin: 0 auto; line-height: 1.3;">
            <?php echo __('hero_title'); ?>
        </h1>
        <p class="fs-5 text-muted mb-5" data-aos="fade-up" data-aos-delay="100" style="max-width: 700px; margin: 0 auto;">
            <?php echo __('hero_subtitle'); ?>
        </p>

        <!-- Centered Search -->
        <div class="mx-auto" style="max-width: 750px;" data-aos="zoom-in" data-aos-delay="200">
            <form action="<?php echo SITE_URL; ?>/search" method="GET" class="p-2 bg-white rounded-pill shadow-lg d-flex align-items-center">
                <div class="d-flex align-items-center flex-grow-1 px-3">
                    <i class="bi bi-search text-muted fs-4"></i>
                    <input type="text" name="q" class="form-control border-0 bg-transparent fs-5 ms-2" placeholder="<?php echo __('search_placeholder'); ?>" style="box-shadow: none;">
                </div>
                <button type="submit" class="btn btn-souk-primary px-5 py-3 rounded-pill fs-5">
                    <?php echo __('search_now'); ?>
                </button>
            </form>
        </div>

        <!-- Stats Counters -->
        <div class="row g-4 justify-content-center mt-5 text-white">
            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="300">
                <h2 class="fw-bold text-gold m-0"><?php echo __('products_count'); ?></h2>
                <small class="text-muted"><?php echo __('stats_products'); ?></small>
            </div>
            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="400">
                <h2 class="fw-bold text-gold m-0"><?php echo __('stores_count'); ?></h2>
                <small class="text-muted"><?php echo __('stats_stores'); ?></small>
            </div>
            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="500">
                <h2 class="fw-bold text-gold m-0"><?php echo __('cities_count'); ?></h2>
                <small class="text-muted"><?php echo __('stats_cities'); ?></small>
            </div>
        </div>
    </div>
</section>

<!-- Browse Categories Section -->
<section class="section-padding bg-white">
    <div class="container text-center">
        <h2 class="fw-bold mb-4" data-aos="fade-up"><?php echo __('browse_categories'); ?></h2>
        <div class="row g-4 mt-2 justify-content-center">
            <?php foreach ($categories as $cat): ?>
                <?php 
                $catName = getLocalized($cat, 'name'); 
                ?>
                <div class="col-lg-2 col-md-4 col-6" data-aos="fade-up">
                    <a href="<?php echo SITE_URL; ?>/search?category=<?php echo $cat->id; ?>" class="d-block text-decoration-none text-dark p-3 rounded-lg border hover-shadow transition-base">
                        <div class="bg-gold-light rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 70px; height: 70px;">
                            <i class="bi <?php echo $cat->icon; ?> text-gold fs-3"></i>
                        </div>
                        <h6 class="fw-bold mb-1"><?php echo $catName; ?></h6>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Featured Products Section -->
<section class="section-padding bg-light">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold m-0" data-aos="fade-right"><?php echo __('featured_products'); ?></h2>
            <a href="<?php echo SITE_URL; ?>/search" class="text-gold fw-bold text-decoration-none" data-aos="fade-left"><?php echo __('view_all'); ?></a>
        </div>

        <div class="row g-4 mt-2">
            <?php foreach ($featuredProducts as $prod): ?>
                <?php 
                $prodName = getLocalized($prod, 'name'); 
                $storeName = getLocalized($prod, 'store_name');
                ?>
                <div class="col-lg-3 col-md-6" data-aos="fade-up">
                    <div class="souk-card">
                        <div class="souk-card__img-wrapper">
                            <img src="<?php echo $prod->thumbnail ? SITE_URL . '/uploads/products/' . $prod->thumbnail : 'https://placehold.co/300x225'; ?>" 
                                 class="souk-card__img" alt="<?php echo $prodName; ?>">
                            <span class="position-absolute top-0 end-0 m-3 badge bg-dark text-white rounded-pill">
                                <?php echo __($prod->condition_type === 'new' ? 'cond_new' : ($prod->condition_type === 'used' ? 'cond_used' : 'cond_refurbished')); ?>
                            </span>
                        </div>
                        <div class="souk-card__body">
                            <div>
                                <small class="text-muted d-flex align-items-center gap-1 mb-1">
                                    <i class="bi bi-shop text-gold"></i>
                                    <span><?php echo $storeName; ?></span>
                                    <?php if ($prod->store_verified): ?>
                                        <i class="bi bi-check-circle-fill text-gold" title="<?php echo __('verified_store'); ?>"></i>
                                    <?php endif; ?>
                                </small>
                                <h5 class="fw-bold text-dark text-truncate mb-2">
                                    <a href="<?php echo SITE_URL; ?>/product/<?php echo $prod->store_slug; ?>/<?php echo $prod->slug; ?>" class="text-dark hover-gold">
                                        <?php echo $prodName; ?>
                                    </a>
                                </h5>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <div>
                                    <?php if ($prod->discount_price): ?>
                                        <span class="text-danger fw-bold fs-5"><?php echo number_format($prod->discount_price); ?> د.ع</span>
                                        <small class="text-muted text-decoration-line-through d-block" style="font-size: 0.8rem;"><?php echo number_format($prod->price); ?> د.ع</small>
                                    <?php else: ?>
                                        <span class="text-dark fw-bold fs-5"><?php echo number_format($prod->price); ?> د.ع</span>
                                    <?php endif; ?>
                                </div>
                                <a href="<?php echo SITE_URL; ?>/product/<?php echo $prod->store_slug; ?>/<?php echo $prod->slug; ?>" class="btn btn-sm btn-souk-secondary px-3 rounded-pill">
                                    <?php echo __('compare_prices'); ?>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Featured Stores Section -->
<section class="section-padding bg-white">
    <div class="container">
        <h2 class="fw-bold text-center mb-5" data-aos="fade-up"><?php echo __('featured_stores'); ?></h2>
        
        <div class="row g-4 justify-content-center">
            <?php foreach ($featuredStores as $store): ?>
                <?php 
                $storeName = getLocalized($store, 'name'); 
                ?>
                <div class="col-lg-3 col-md-6" data-aos="fade-up">
                    <div class="card border rounded-lg p-3 text-center hover-shadow transition-base">
                        <div class="position-relative mb-3">
                            <img src="<?php echo $store->logo ? SITE_URL . '/uploads/logos/' . $store->logo : 'https://placehold.co/100'; ?>" 
                                 alt="<?php echo $storeName; ?>" class="rounded-circle object-cover border" style="width: 90px; height: 90px;">
                            <?php if ($store->is_verified): ?>
                                <span class="position-absolute bottom-0 start-50 translate-middle badge rounded-pill bg-gold-light border border-gold text-gold" style="font-size: 0.65rem;">
                                    <?php echo __('verified_store'); ?>
                                </span>
                            <?php endif; ?>
                        </div>
                        <h5 class="fw-bold mb-1">
                            <a href="<?php echo SITE_URL; ?>/store/<?php echo $store->slug; ?>" class="text-dark hover-gold">
                                <?php echo $storeName; ?>
                            </a>
                        </h5>
                        <small class="text-muted d-block mb-2"><i class="bi bi-geo-alt-fill text-gold"></i> <?php echo $store->governorate . ', ' . $store->city; ?></small>
                        <div class="d-flex justify-content-center align-items-center gap-1 text-warning mb-3">
                            <i class="bi bi-star-fill"></i>
                            <span class="text-dark fw-bold"><?php echo number_format($store->avg_rating, 1); ?></span>
                            <span class="text-muted small">/ 5</span>
                        </div>
                        <a href="<?php echo SITE_URL; ?>/store/<?php echo $store->slug; ?>" class="btn btn-outline-secondary w-100 btn-sm rounded-pill">
                            <?php echo __('browse_store'); ?>
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- How it works section -->
<section class="section-padding bg-light border-top">
    <div class="container">
        <h2 class="fw-bold text-center mb-5"><?php echo __('how_it_works'); ?></h2>
        <div class="row g-4 text-center">
            <div class="col-md-4">
                <div class="p-3 bg-white rounded-lg border hover-shadow h-100">
                    <i class="bi bi-search text-gold fs-1 d-block mb-3"></i>
                    <h5 class="fw-bold"><?php echo __('step_1_title'); ?></h5>
                    <p class="text-muted mb-0"><?php echo __('step_1_desc'); ?></p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-3 bg-white rounded-lg border hover-shadow h-100">
                    <i class="bi bi-arrow-left-right text-gold fs-1 d-block mb-3"></i>
                    <h5 class="fw-bold"><?php echo __('step_2_title'); ?></h5>
                    <p class="text-muted mb-0"><?php echo __('step_2_desc'); ?></p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-3 bg-white rounded-lg border hover-shadow h-100">
                    <i class="bi bi-chat-left-text text-gold fs-1 d-block mb-3"></i>
                    <h5 class="fw-bold"><?php echo __('step_3_title'); ?></h5>
                    <p class="text-muted mb-0"><?php echo __('step_3_desc'); ?></p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Store registration CTA section -->
<section class="section-padding text-white border-top border-gold" style="background: var(--gradient-hero);">
    <div class="container text-center py-4">
        <h2 class="fw-bold text-white mb-3"><?php echo __('store_cta_title'); ?></h2>
        <p class="fs-5 text-muted mb-4 mx-auto" style="max-width: 650px;">
            <?php echo __('store_cta_desc'); ?>
        </p>
        <a href="<?php echo SITE_URL; ?>/register?role=store_owner" class="btn btn-souk-primary px-5 py-3 rounded-pill fs-5">
            <?php echo __('register_store_now'); ?>
        </a>
    </div>
</section>
