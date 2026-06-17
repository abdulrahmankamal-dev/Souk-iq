<?php
$lang = $_SESSION['lang'] ?? 'ar';
$dir = ($lang === 'en') ? 'ltr' : 'rtl';
$storeName = getLocalized($store, 'name');
$desc = getLocalized($store, 'description');
$catName = getLocalized($store, 'category_name');
?>

<!-- Store Header Banner -->
<section class="position-relative bg-dark overflow-hidden text-light" style="min-height: 250px;">
    <!-- Banner Photo -->
    <div class="position-absolute top-0 start-0 w-100 h-100" style="opacity: 0.4; background-image: url('<?php echo $store->banner ? SITE_URL . '/uploads/banners/' . $store->banner : 'https://placehold.co/1200x250'; ?>'); background-size: cover; background-position: center;"></div>
    
    <div class="container position-relative py-5 h-100 d-flex flex-column justify-content-end align-items-center align-items-md-start">
        <div class="d-flex flex-column flex-md-row align-items-center gap-4 mt-5">
            <!-- Store Logo -->
            <div class="position-relative bg-white p-1 rounded-circle border border-gold border-3 shadow" style="width: 120px; height: 120px;">
                <img src="<?php echo $store->logo ? SITE_URL . '/uploads/logos/' . $store->logo : 'https://placehold.co/120;'; ?>" 
                     class="rounded-circle object-cover w-100 h-100" alt="<?php echo $storeName; ?>">
            </div>

            <!-- Profile Meta details -->
            <div class="text-center text-md-start mt-3 mt-md-0">
                <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-start gap-2">
                    <h1 class="fw-bold text-white mb-0" style="font-family: var(--font-body); font-size: 2.2rem;"><?php echo $storeName; ?></h1>
                    <?php if ($store->is_verified): ?>
                        <span class="badge bg-gold-light border border-gold text-gold rounded-pill px-3 py-1"><?php echo __('verified_store'); ?></span>
                    <?php endif; ?>
                </div>
                <p class="text-muted mb-0 mt-1">
                    <i class="bi bi-tag text-gold"></i> <?php echo $catName; ?>
                    &nbsp;|&nbsp;
                    <i class="bi bi-geo-alt text-gold"></i> <?php echo GOVERNORATES[$lang][$store->governorate] ?? ($store->governorate . ', ' . $store->city); ?>
                </p>
                <div class="d-flex gap-3 justify-content-center justify-content-md-start mt-2 text-muted small">
                    <span><strong><?php echo $store->followers_count; ?></strong> <?php echo __('followers'); ?></span>
                    <span><strong><?php echo $store->views_count; ?></strong> <?php echo __('views'); ?></span>
                    <span><strong><?php echo $store->products_count; ?></strong> <?php echo __('products_listed'); ?></span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Main Store Profile Content -->
<div class="container py-5">
    <div class="row g-4">
        <!-- Sidebar Contact Card (Col 4) -->
             <div class="card border rounded-lg p-4 bg-white shadow-sm mb-4">
                <h5 class="fw-bold text-dark mb-4 border-bottom pb-2"><?php echo __('contact_follow'); ?></h5>
                
                <div class="d-grid gap-3 mb-4">
                    <!-- Follow Button -->
                    <?php if (\Core\Auth::check()): ?>
                        <button type="button" class="btn <?php echo $isFollowing ? 'btn-souk-secondary' : 'btn-souk-primary'; ?> rounded-pill py-2.5" id="store-follow-btn" data-store-id="<?php echo $store->id; ?>">
                            <i class="bi <?php echo $isFollowing ? 'bi-person-check-fill' : 'bi-person-plus-fill'; ?> me-1"></i>
                            <span id="store-follow-text"><?php echo $isFollowing ? __('unfollow') : __('follow_store'); ?></span>
                        </button>
                    <?php else: ?>
                        <a href="<?php echo SITE_URL; ?>/login" class="btn btn-souk-primary rounded-pill py-2.5 text-center">
                            <i class="bi bi-person-plus-fill me-1"></i> <?php echo __('follow_store'); ?>
                        </a>
                    <?php endif; ?>
 
                    <!-- WhatsApp Contact -->
                    <?php if ($store->whatsapp): ?>
                        <a href="https://wa.me/<?php echo ltrim($store->whatsapp, '+'); ?>" target="_blank" class="btn btn-success py-2.5 rounded-pill text-center" style="background-color: #25D366; border: none;">
                            <i class="bi bi-whatsapp me-1"></i> <?php echo __('contact_whatsapp'); ?>
                        </a>
                    <?php endif; ?>
                </div>
 
                <!-- Contact items -->
                <ul class="list-unstyled d-flex flex-column gap-3 small text-dark">
                    <?php if ($store->phone): ?>
                        <li class="d-flex align-items-center gap-2">
                            <i class="bi bi-telephone text-gold fs-5"></i>
                            <strong><?php echo __('phone_label'); ?></strong> <a href="tel:<?php echo $store->phone; ?>"><?php echo $store->phone; ?></a>
                        </li>
                    <?php endif; ?>
                    <?php if ($store->email): ?>
                        <li class="d-flex align-items-center gap-2">
                            <i class="bi bi-envelope text-gold fs-5"></i>
                            <strong><?php echo __('email_label'); ?></strong> <a href="mailto:<?php echo $store->email; ?>"><?php echo $store->email; ?></a>
                        </li>
                    <?php endif; ?>
                    <?php if ($store->website): ?>
                        <li class="d-flex align-items-center gap-2">
                            <i class="bi bi-globe2 text-gold fs-5"></i>
                            <strong><?php echo __('website_label'); ?></strong> <a href="<?php echo $store->website; ?>" target="_blank"><?php echo $store->website; ?></a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>

            <!-- Working Hours Widget -->
            <?php if (!empty($workingHours)): ?>
                <div class="card border rounded-lg p-4 bg-white shadow-sm">
                    <h5 class="fw-bold text-dark mb-3 border-bottom pb-2"><?php echo __('working_hours'); ?></h5>
                    <table class="table table-borderless table-sm small text-dark m-0">
                        <tbody>
                            <?php foreach ($workingHours as $day => $hours): ?>
                                <tr>
                                    <th class="text-muted font-weight-normal text-capitalize"><?php echo __($day, [], $day); ?></th>
                                    <td class="text-end fw-bold"><?php echo $hours; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </aside>

        <!-- Main Tab Pane (Col 8) -->
        <main class="col-lg-8">
            <div class="card border rounded-lg bg-white shadow-sm overflow-hidden">
                <!-- Navigation Tabs Header -->
                <div class="card-header bg-light p-0 border-bottom">
                    <ul class="nav nav-tabs border-0" id="storeProfileTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active py-3 px-4 fw-bold text-dark border-0 rounded-0" id="products-tab" data-bs-toggle="tab" data-bs-target="#products-pane" type="button" role="tab" aria-controls="products-pane" aria-selected="true">
                                <i class="bi bi-box-seam me-1"></i> <?php echo __('tab_products_count', ['count' => count($products)]); ?>
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link py-3 px-4 fw-bold text-dark border-0 rounded-0" id="about-tab" data-bs-toggle="tab" data-bs-target="#about-pane" type="button" role="tab" aria-controls="about-pane" aria-selected="false">
                                <i class="bi bi-info-circle me-1"></i> <?php echo __('about_store_title'); ?>
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link py-3 px-4 fw-bold text-dark border-0 rounded-0" id="reviews-tab" data-bs-toggle="tab" data-bs-target="#reviews-pane" type="button" role="tab" aria-controls="reviews-pane" aria-selected="false">
                                <i class="bi bi-star me-1"></i> <?php echo __('tab_reviews_count', ['count' => $store->reviews_count]); ?>
                            </button>
                        </li>
                    </ul>
                </div>

                <div class="card-body p-4 tab-content">
                    <!-- Tab 1: Products -->
                    <div class="tab-pane fade show active" id="products-pane" role="tabpanel" aria-labelledby="products-tab">
                        <h4 class="fw-bold text-dark mb-4"><?php echo __('store_products_title'); ?></h4>
                        
                        <?php if (empty($products)): ?>
                            <p class="text-muted text-center py-5"><?php echo __('no_store_products'); ?></p>
                        <?php else: ?>
                            <div class="row g-3">
                                <?php foreach ($products as $prod): ?>
                                    <?php 
                                    $prodName = getLocalized($prod, 'name'); 
                                    ?>
                                    <div class="col-md-6">
                                        <div class="souk-card">
                                            <div class="souk-card__img-wrapper">
                                                <img src="<?php echo $prod->thumbnail ? SITE_URL . '/uploads/products/' . $prod->thumbnail : 'https://placehold.co/300x225'; ?>" class="souk-card__img" alt="product">
                                            </div>
                                            <div class="souk-card__body">
                                                <h6 class="fw-bold text-truncate mb-2">
                                                    <a href="<?php echo SITE_URL; ?>/product/<?php echo $store->slug; ?>/<?php echo $prod->slug; ?>" class="text-dark hover-gold">
                                                        <?php echo $prodName; ?>
                                                    </a>
                                                </h6>
                                                <div class="d-flex justify-content-between align-items-center mt-3">
                                                    <span class="text-dark fw-bold"><?php echo number_format($prod->price); ?> د.ع</span>
                                                    <a href="<?php echo SITE_URL; ?>/product/<?php echo $store->slug; ?>/<?php echo $prod->slug; ?>" class="btn btn-sm btn-outline-warning rounded-pill px-3"><?php echo __('details'); ?></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Tab 2: About / Map -->
                    <div class="tab-pane fade" id="about-pane" role="tabpanel" aria-labelledby="about-tab">
                        <h4 class="fw-bold text-dark mb-3"><?php echo __('store_details_title'); ?></h4>
                        <p class="text-secondary small mb-4">
                            <?php echo empty($desc) ? __('no_store_desc') : nl2br(htmlspecialchars($desc)); ?>
                        </p>
                        
                        <h5 class="fw-bold text-dark mb-3"><i class="bi bi-geo-alt text-gold"></i> <?php echo __('store_location_map'); ?></h5>
                        <div id="store-profile-map" class="border rounded mb-3" style="height: 300px; z-index: 1;"></div>
                    </div>

                    <!-- Tab 3: Reviews -->
                    <div class="tab-pane fade" id="reviews-pane" role="tabpanel" aria-labelledby="reviews-tab">
                        <h4 class="fw-bold text-dark mb-4"><?php echo __('store_reviews_title'); ?></h4>

                        <!-- Add Review direct -->
                        <?php if (\Core\Auth::check()): ?>
                            <div class="bg-light p-3 rounded-lg border mb-4">
                                <h6 class="fw-bold mb-2"><?php echo __('add_your_review'); ?></h6>
                                <form action="<?php echo SITE_URL; ?>/store/review" method="POST">
                                    <?php echo \Core\CSRF::field(); ?>
                                    <input type="hidden" name="store_id" value="<?php echo $store->id; ?>">
                                    <div class="row g-2">
                                        <div class="col-md-3">
                                            <select name="rating" class="form-select form-select-sm" required>
                                                <option value="5"><?php echo __('rating_5'); ?></option>
                                                <option value="4"><?php echo __('rating_4'); ?></option>
                                                <option value="3"><?php echo __('rating_3'); ?></option>
                                                <option value="2"><?php echo __('rating_2'); ?></option>
                                                <option value="1"><?php echo __('rating_1'); ?></option>
                                            </select>
                                        </div>
                                        <div class="col-md-9">
                                            <input type="text" name="body" class="form-control form-control-sm" placeholder="<?php echo __('write_review_placeholder'); ?>" required>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-souk-primary btn-sm px-4 mt-2 rounded-pill"><?php echo __('submit_review'); ?></button>
                                </form>
                            </div>
                        <?php endif; ?>

                        <!-- Reviews list -->
                        <div class="reviews-list">
                            <?php if (empty($reviews)): ?>
                                <p class="text-muted text-center py-4"><?php echo __('no_store_reviews_yet'); ?></p>
                            <?php else: ?>
                                <?php foreach ($reviews as $rev): ?>
                                    <div class="border-bottom py-3">
                                        <div class="d-flex justify-content-between mb-1">
                                            <strong class="text-dark"><?php echo $rev->user_name; ?></strong>
                                            <span class="text-warning small">
                                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                                    <i class="bi <?php echo ($i <= $rev->rating) ? 'bi-star-fill' : 'bi-star'; ?>"></i>
                                                <?php endfor; ?>
                                            </span>
                                        </div>
                                        <p class="text-secondary small mb-0"><?php echo nl2br(htmlspecialchars($rev->body)); ?></p>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const lat = <?php echo $store->latitude ? floatval($store->latitude) : '33.3152'; ?>;
        const lng = <?php echo $store->longitude ? floatval($store->longitude) : '44.3661'; ?>;
        const storeName = "<?php echo addslashes($storeName); ?>";

        // Initialize Leaflet map
        if (window.SoukMap) {
            SoukMap.initStoreMap('store-profile-map', lat, lng, storeName);
        }

        // Handle followers click dynamically (AJAX toggler)
        const followBtn = document.getElementById('store-follow-btn');
        if (followBtn) {
            followBtn.addEventListener('click', function () {
                const storeId = this.dataset.storeId;
                const token = document.querySelector('input[name="csrf_token"]').value;

                const formData = new FormData();
                formData.append('store_id', storeId);
                formData.append('csrf_token', token);

                fetch(SITE_URL + '/store/follow', {
                    method: 'POST',
                    headers: { 'X-Requested-With': 'XMLHttpRequest' },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        if (data.following) {
                            followBtn.classList.remove('btn-souk-primary');
                            followBtn.classList.add('btn-souk-secondary');
                            document.getElementById('store-follow-text').innerText = 'إلغاء المتابعة';
                        } else {
                            followBtn.classList.remove('btn-souk-secondary');
                            followBtn.classList.add('btn-souk-primary');
                            document.getElementById('store-follow-text').innerText = 'متابعة المتجر';
                        }
                        location.reload(); // Refresh count stats
                    }
                })
                .catch(error => console.error('Error:', error));
            });
        }
    });
</script>
