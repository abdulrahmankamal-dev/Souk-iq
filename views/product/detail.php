<?php
$lang = $_SESSION['lang'] ?? 'ar';
$dir = ($lang === 'en') ? 'ltr' : 'rtl';
$productName = getLocalized($product, 'name');
$storeName = getLocalized($product, 'store_name');
$categoryName = getLocalized($product, 'category_name');
?>

<div class="container py-4">
    <!-- Breadcrumbs -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo SITE_URL; ?>"><?php echo __('dashboard_home'); ?></a></li>
            <?php if ($product->category_id): ?>
                <li class="breadcrumb-item"><a href="<?php echo SITE_URL; ?>/search?category=<?php echo $product->category_id; ?>"><?php echo $categoryName; ?></a></li>
            <?php endif; ?>
            <li class="breadcrumb-item active" aria-current="page"><?php echo $productName; ?></li>
        </ol>
    </nav>

    <div class="row g-4">
        <!-- Left: Image Gallery (Col 5) -->
        <div class="col-lg-5">
            <div class="card border rounded-lg overflow-hidden bg-white shadow-sm p-3">
                <div class="position-relative text-center bg-light rounded mb-3" style="min-height: 350px; display: flex; align-items: center; justify-content: center;">
                    <img src="<?php echo $product->thumbnail ? SITE_URL . '/uploads/products/' . $product->thumbnail : 'https://placehold.co/400x350'; ?>" 
                         class="img-fluid rounded object-cover" id="main-product-image" style="max-height: 350px;" alt="<?php echo $productName; ?>">
                    <span class="position-absolute top-0 end-0 m-3 badge bg-dark text-white rounded-pill fs-6">
                        <?php echo __($product->condition_type === 'new' ? 'cond_new' : ($product->condition_type === 'used' ? 'cond_used' : 'cond_refurbished')); ?>
                    </span>
                </div>
                
                <!-- Thumbnails -->
                <?php if (count($imagesList) > 1): ?>
                    <div class="d-flex gap-2 justify-content-center overflow-x-auto py-1">
                        <?php foreach ($imagesList as $img): ?>
                            <img src="<?php echo SITE_URL . '/uploads/products/' . $img; ?>" 
                                 class="img-thumbnail cursor-pointer border-2" 
                                 style="width: 70px; height: 70px; object-fit: cover;" 
                                 onclick="document.getElementById('main-product-image').src = this.src" alt="thumb">
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Right: Specifications & CTAs (Col 7) -->
        <div class="col-lg-7">
            <div class="card border rounded-lg p-4 bg-white shadow-sm h-100 d-flex flex-column justify-content-between">
                <div>
                    <!-- Store Tag -->
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <small class="text-muted d-flex align-items-center gap-1">
                            <i class="bi bi-shop text-gold fs-5"></i>
                            <a href="<?php echo SITE_URL; ?>/store/<?php echo $product->store_slug; ?>" class="fw-bold text-dark hover-gold fs-6">
                                <?php echo $storeName; ?>
                            </a>
                            <?php if ($product->store_verified): ?>
                                <i class="bi bi-check-circle-fill text-gold" title="<?php echo __('verified_store'); ?>"></i>
                            <?php endif; ?>
                        </small>

                        <!-- Favorite button -->
                        <form action="<?php echo SITE_URL; ?>/product/favorite" method="POST" class="favorite-form">
                            <?php echo \Core\CSRF::field(); ?>
                            <input type="hidden" name="product_id" value="<?php echo $product->id; ?>">
                            <button type="submit" class="btn btn-sm rounded-circle <?php echo $isFavorite ? 'btn-danger' : 'btn-outline-danger'; ?> p-2">
                                <i class="bi <?php echo $isFavorite ? 'bi-heart-fill' : 'bi-heart'; ?>"></i>
                            </button>
                        </form>
                    </div>

                    <!-- Title -->
                    <h2 class="fw-bold mb-2"><?php echo $productName; ?></h2>
                    
                    <!-- Rating Summary -->
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <div class="text-warning">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <i class="bi <?php echo ($i <= round($product->avg_rating)) ? 'bi-star-fill' : 'bi-star'; ?>"></i>
                            <?php endfor; ?>
                        </div>
                        <span class="fw-bold small text-dark"><?php echo number_format($product->avg_rating, 1); ?></span>
                        <span class="text-muted small"><?php echo __('reviews_count', ['count' => $ratingSummary['total']]); ?></span>
                    </div>

                    <!-- Pricing Info -->
                    <div class="p-3 bg-light rounded-lg border mb-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <small class="text-muted d-block"><?php echo __('seller_price_label'); ?></small>
                                <?php if ($product->discount_price): ?>
                                    <h2 class="text-danger fw-bold m-0"><?php echo number_format($product->discount_price); ?> د.ع</h2>
                                    <span class="text-muted text-decoration-line-through small"><?php echo number_format($product->price); ?> د.ع</span>
                                <?php else: ?>
                                    <h2 class="text-dark fw-bold m-0"><?php echo number_format($product->price); ?> د.ع</h2>
                                <?php endif; ?>
                            </div>
                            
                            <div class="text-end">
                                <span class="badge px-3 py-2 rounded-pill <?php echo ($product->stock_status === 'in_stock') ? 'bg-success-subtle text-success border border-success' : (($product->stock_status === 'limited') ? 'bg-warning-subtle text-warning border border-warning' : 'bg-danger-subtle text-danger border border-danger'); ?>">
                                    <?php echo __($product->stock_status === 'in_stock' ? 'stock_in' : ($product->stock_status === 'limited' ? 'stock_limited' : 'stock_out')); ?>
                                </span>
                                <?php if ($product->price_negotiable): ?>
                                    <small class="text-success d-block mt-2"><i class="bi bi-chat-dots"></i> <?php echo __('price_negotiable'); ?></small>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="mb-4">
                        <h6 class="fw-bold mb-2"><?php echo __('about_product'); ?></h6>
                        <p class="text-secondary small mb-0">
                            <?php echo nl2br(htmlspecialchars(getLocalized($product, 'description'))); ?>
                        </p>
                    </div>

                    <!-- Warranty Info -->
                    <?php if ($product->warranty_info): ?>
                        <div class="mb-4 d-flex align-items-center gap-2 small text-dark">
                            <i class="bi bi-shield-check text-gold fs-5"></i>
                            <strong><?php echo __('warranty_label'); ?></strong> <span><?php echo $product->warranty_info; ?></span>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Seller CTA buttons -->
                <div class="d-flex flex-column flex-sm-row gap-3 pt-3 border-top mt-4">
                    <?php if ($product->store_phone): ?>
                        <a href="tel:<?php echo $product->store_phone; ?>" class="btn btn-souk-secondary py-3 px-4 rounded-pill flex-fill text-center">
                            <i class="bi bi-telephone-fill me-1"></i> <?php echo __('call_seller', ['phone' => $product->store_phone]); ?>
                        </a>
                    <?php endif; ?>
                    <?php if ($product->store_whatsapp): ?>
                        <?php 
                        $waText = rawurlencode("مرحباً " . $storeName . "، أنا مهتم بمنتجك (" . $productName . ") المعروض على منصة سوق.IQ.");
                        $waUrl = "https://wa.me/" . ltrim($product->store_whatsapp, '+') . "?text=" . $waText;
                        ?>
                        <a href="<?php echo $waUrl; ?>" target="_blank" class="btn btn-success py-3 px-4 rounded-pill flex-fill text-center" style="background-color: #25D366; border: none;">
                            <i class="bi bi-whatsapp me-1"></i> <?php echo __('contact_whatsapp'); ?>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Technical Specs & Location tabs -->
    <div class="row g-4 mt-4">
        <!-- Specs Column (Col 7) -->
        <div class="col-lg-7">
            <div class="card border rounded-lg p-4 bg-white shadow-sm h-100">
                <h4 class="fw-bold mb-4 border-bottom pb-2 text-dark"><i class="bi bi-cpu text-gold"></i> <?php echo __('specs'); ?></h4>
                
                <?php if (!empty($specificationsList)): ?>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered small">
                            <tbody>
                                <?php foreach ($specificationsList as $key => $val): ?>
                                    <tr>
                                        <th class="w-40 bg-light"><?php echo htmlspecialchars($key); ?></th>
                                        <td><?php echo htmlspecialchars($val); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p class="text-muted small"><?php echo __('no_specs'); ?></p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Location Column (Col 5) -->
        <div class="col-lg-5">
            <div class="card border rounded-lg p-4 bg-white shadow-sm h-100">
                <h4 class="fw-bold mb-3 border-bottom pb-2 text-dark"><i class="bi bi-geo-alt text-gold"></i> <?php echo __('store_location'); ?></h4>
                <p class="small text-muted mb-2">
                    <i class="bi bi-pin-map"></i> 
                    <?php 
                    $locationStr = '';
                    if (!empty($product->store_address_line)) {
                        $locationStr = $product->store_address_line;
                    }
                    if (!empty($product->store_governorate)) {
                        $govName = GOVERNORATES[$lang][$product->store_governorate] ?? $product->store_governorate;
                        $locationStr = $locationStr ? $locationStr . ', ' . $govName : $govName;
                    }
                    echo htmlspecialchars($locationStr);
                    ?>
                </p>
                
                <!-- Leaflet Map Container -->
                <div id="store-location-map" class="border rounded" style="height: 250px; z-index: 1;"></div>
            </div>
        </div>
    </div>

    <!-- Reviews Section -->
    <section class="mt-5">
        <div class="card border rounded-lg p-4 bg-white shadow-sm">
            <h4 class="fw-bold mb-4 border-bottom pb-2 text-dark"><i class="bi bi-chat-square-text text-gold"></i> <?php echo __('customer_reviews_title'); ?></h4>
            
            <div class="row g-4 mb-5">
                <!-- Rating Stats (Col 4) -->
                <div class="col-md-4 text-center border-end">
                    <h1 class="display-3 fw-bold text-dark m-0"><?php echo number_format($ratingSummary['average'], 1); ?></h1>
                    <div class="text-warning fs-4 my-2">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <i class="bi <?php echo ($i <= round($ratingSummary['average'])) ? 'bi-star-fill' : 'bi-star'; ?>"></i>
                        <?php endfor; ?>
                    </div>
                    <small class="text-muted d-block"><?php echo __('based_on_reviews', ['count' => $ratingSummary['total']]); ?></small>
                </div>

                <!-- Star breakdown (Col 8) -->
                <div class="col-md-8">
                    <?php for ($star = 5; $star >= 1; $star--): ?>
                        <div class="d-flex align-items-center gap-3 mb-2 small">
                            <span class="text-nowrap" style="width: 50px;"><?php echo __('stars_count', ['count' => $star]); ?></span>
                            <div class="progress flex-grow-1" style="height: 10px;">
                                <div class="progress-bar bg-warning" role="progressbar" style="width: <?php echo $ratingSummary['percentages'][$star]; ?>%;" aria-valuenow="<?php echo $ratingSummary['percentages'][$star]; ?>" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <span class="text-muted text-nowrap" style="width: 40px;"><?php echo $ratingSummary['percentages'][$star]; ?>%</span>
                        </div>
                    <?php endfor; ?>
                </div>
            </div>

            <?php if ($canReview): ?>
                <div class="bg-light p-4 rounded-lg border mb-5">
                    <h5 class="fw-bold mb-3"><?php echo __('add_product_review'); ?></h5>
                    <form action="<?php echo SITE_URL; ?>/product/review" method="POST">
                        <?php echo \Core\CSRF::field(); ?>
                        <input type="hidden" name="product_id" value="<?php echo $product->id; ?>">
 
                        <div class="row g-3">
                            <!-- Star Choice -->
                            <div class="col-md-4">
                                <label class="form-label small fw-bold"><?php echo __('star_rating_label'); ?></label>
                                <select name="rating" class="form-select" required>
                                    <option value="5"><?php echo __('rating_5'); ?></option>
                                    <option value="4"><?php echo __('rating_4'); ?></option>
                                    <option value="3"><?php echo __('rating_3'); ?></option>
                                    <option value="2"><?php echo __('rating_2'); ?></option>
                                    <option value="1"><?php echo __('rating_1'); ?></option>
                                </select>
                            </div>

                            <!-- Review title -->
                            <div class="col-md-8">
                                <label class="form-label small fw-bold"><?php echo __('review_title_label'); ?></label>
                                <input type="text" name="title" class="form-control" placeholder="<?php echo __('review_title_placeholder'); ?>">
                            </div>

                            <!-- Review text -->
                            <div class="col-12">
                                <label class="form-label small fw-bold"><?php echo __('review_body_label'); ?></label>
                                <textarea name="body" class="form-control" rows="3" placeholder="<?php echo __('review_body_placeholder'); ?>" required></textarea>
                            </div>

                            <!-- Pros -->
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-success"><?php echo __('pros_label'); ?></label>
                                <input type="text" name="pros" class="form-control" placeholder="<?php echo __('pros_placeholder'); ?>">
                            </div>

                            <!-- Cons -->
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-danger"><?php echo __('cons_label'); ?></label>
                                <input type="text" name="cons" class="form-control" placeholder="<?php echo __('cons_placeholder'); ?>">
                            </div>
                        </div>

                        <button type="submit" class="btn btn-souk-primary px-4 mt-3 rounded-pill"><?php echo __('submit_review_now'); ?></button>
                    </form>
                </div>
            <?php else: ?>
                <div class="alert alert-info border small py-3 mb-5 text-center">
                    <?php echo __('login_to_review', ['login_link' => '<a href="' . SITE_URL . '/login" class="fw-bold text-gold">' . __('login') . '</a>']); ?>
                </div>
            <?php endif; ?>

            <!-- List of Comments -->
            <div class="reviews-comments-list">
                <?php if (empty($reviews)): ?>
                    <p class="text-muted text-center py-4"><?php echo __('no_product_reviews_yet'); ?></p>
                <?php else: ?>
                    <?php foreach ($reviews as $rev): ?>
                        <div class="border-bottom py-4">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div class="d-flex align-items-center gap-2">
                                    <img src="<?php echo $rev->user_avatar ? SITE_URL . '/uploads/avatars/' . $rev->user_avatar : 'https://placehold.co/40'; ?>" 
                                         alt="user" class="rounded-circle object-cover" width="40" height="40">
                                    <div>
                                        <h6 class="fw-bold mb-0 text-dark"><?php echo $rev->user_name; ?></h6>
                                        <small class="text-muted" style="font-size: 0.75rem;"><?php echo date('Y/m/d', strtotime($rev->created_at)); ?></small>
                                    </div>
                                </div>
                                <div class="text-warning small">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <i class="bi <?php echo ($i <= $rev->rating) ? 'bi-star-fill' : 'bi-star'; ?>"></i>
                                    <?php endfor; ?>
                                </div>
                            </div>
                            
                            <?php if ($rev->title): ?>
                                <h6 class="fw-bold text-dark mt-2 mb-1"><?php echo $rev->title; ?></h6>
                            <?php endif; ?>
                            <p class="text-secondary small mb-3"><?php echo nl2br(htmlspecialchars($rev->body)); ?></p>

                            <!-- Pros & Cons badges -->
                            <?php if ($rev->pros || $rev->cons): ?>
                                <div class="row g-2 mb-3">
                                    <?php if ($rev->pros): ?>
                                        <div class="col-md-6 small"><span class="badge bg-success-subtle text-success border border-success me-1"><?php echo __('pros_badge'); ?></span> <span class="text-muted"><?php echo $rev->pros; ?></span></div>
                                    <?php endif; ?>
                                    <?php if ($rev->cons): ?>
                                        <div class="col-md-6 small"><span class="badge bg-danger-subtle text-danger border border-danger me-1"><?php echo __('cons_badge'); ?></span> <span class="text-muted"><?php echo $rev->cons; ?></span></div>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>

                            <!-- Owner reply -->
                            <?php if ($rev->store_reply): ?>
                                <div class="bg-light p-3 rounded-lg border-start border-4 border-gold mt-3 ms-4 small">
                                    <div class="d-flex align-items-center gap-2 mb-1">
                                        <i class="bi bi-chat-left-dots-fill text-gold"></i>
                                        <strong class="text-dark"><?php echo __('seller_reply_by', ['name' => $storeName]); ?></strong>
                                        <small class="text-muted ms-auto"><?php echo date('Y/m/d', strtotime($rev->replied_at)); ?></small>
                                    </div>
                                    <p class="text-secondary mb-0"><?php echo $rev->store_reply; ?></p>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Related Products -->
    <?php if (!empty($relatedProducts)): ?>
        <section class="mt-5">
            <h4 class="fw-bold mb-4 text-dark"><i class="bi bi-grid-3x3-gap text-gold"></i> <?php echo __('related_products'); ?></h4>
            <div class="row g-4">
                <?php foreach ($relatedProducts as $rp): ?>
                    <div class="col-lg-3 col-md-6">
                        <!-- Reusable souk-card structure -->
                        <div class="souk-card">
                            <div class="souk-card__img-wrapper">
                                <img src="<?php echo $rp->thumbnail ? SITE_URL . '/uploads/products/' . $rp->thumbnail : 'https://placehold.co/300x225'; ?>" class="souk-card__img" alt="related">
                            </div>
                            <div class="souk-card__body">
                                <h6 class="fw-bold text-truncate mb-2">
                                    <a href="<?php echo SITE_URL; ?>/product/<?php echo $rp->store_slug; ?>/<?php echo $rp->slug; ?>" class="text-dark hover-gold">
                                        <?php echo getLocalized($rp, 'name'); ?>
                                    </a>
                                </h6>
                                <div class="d-flex justify-content-between align-items-center mt-3">
                                    <span class="text-dark fw-bold"><?php echo number_format($rp->price); ?> د.ع</span>
                                    <a href="<?php echo SITE_URL; ?>/product/<?php echo $rp->store_slug; ?>/<?php echo $rp->slug; ?>" class="btn btn-sm btn-outline-warning rounded-pill px-3"><?php echo __('compare'); ?></a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
    <?php endif; ?>
</div>

<script>
    // Initialize interactive Leaflet map for store physical location
    document.addEventListener("DOMContentLoaded", function () {
        const lat = <?php echo $product->latitude ? floatval($product->latitude) : '33.3152'; ?>;
        const lng = <?php echo $product->longitude ? floatval($product->longitude) : '44.3661'; ?>;
        const storeName = "<?php echo addslashes($storeName); ?>";

        if (window.SoukMap) {
            SoukMap.initStoreMap('store-location-map', lat, lng, storeName);
        }
    });
</script>
