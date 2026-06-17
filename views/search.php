<?php
$lang = $_SESSION['lang'] ?? 'ar';
$dir = ($lang === 'en') ? 'ltr' : 'rtl';
?>

<div class="container py-4">
    <!-- Breadcrumbs -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo SITE_URL; ?>"><i class="bi bi-house-door"></i> <?php echo __('dashboard_home'); ?></a></li>
            <li class="breadcrumb-item active" aria-current="page"><?php echo __('search_compare'); ?></li>
        </ol>
    </nav>

    <div class="row g-4">
        <!-- Sidebar Filters Column (Col 3) -->
        <aside class="col-lg-3">
            <form action="<?php echo SITE_URL; ?>/search" method="GET" id="search-filter-form">
                <!-- Keep search query -->
                <input type="hidden" name="q" value="<?php echo htmlspecialchars($filters['q']); ?>">
                <input type="hidden" name="sort" value="<?php echo htmlspecialchars($sort); ?>">

                <div class="card border rounded-lg shadow-sm">
                    <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold m-0"><i class="bi bi-funnel text-gold"></i> <?php echo __('filter_results'); ?></h5>
                        <a href="<?php echo SITE_URL; ?>/search" class="text-danger small text-decoration-none"><?php echo __('reset'); ?></a>
                    </div>
                    
                    <div class="card-body p-3">
                        <!-- Category Filter -->
                        <div class="mb-4">
                            <h6 class="fw-bold text-dark mb-2"><?php echo __('categories_filter'); ?></h6>
                            <select name="category" class="form-select form-select-sm rounded-pill" onchange="this.form.submit()">
                                <option value=""><?php echo __('all_categories'); ?></option>
                                <?php foreach ($categories as $cat): ?>
                                    <?php 
                                    $catName = getLocalized($cat, 'name'); 
                                    ?>
                                    <option value="<?php echo $cat->id; ?>" <?php echo ($filters['category'] == $cat->id) ? 'selected' : ''; ?>>
                                        <?php echo $catName; ?>
                                    </option>
                                    <?php if (!empty($cat->subcategories)): ?>
                                        <?php foreach ($cat->subcategories as $sub): ?>
                                            <?php 
                                            $subName = getLocalized($sub, 'name'); 
                                            ?>
                                            <option value="<?php echo $sub->id; ?>" <?php echo ($filters['category'] == $sub->id) ? 'selected' : ''; ?>>
                                                &nbsp;&nbsp;— <?php echo $subName; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <hr class="my-3 border-light">

                        <!-- Governorate Filter -->
                        <div class="mb-4">
                            <h6 class="fw-bold text-dark mb-2"><?php echo __('governorate'); ?></h6>
                            <div class="overflow-y-auto pe-1" style="max-height: 200px;">
                                <?php foreach (GOVERNORATES[$lang] as $key => $govName): ?>
                                    <div class="form-check mb-1">
                                        <input class="form-check-input" type="checkbox" name="gov[]" value="<?php echo $key; ?>" 
                                               id="gov-<?php echo $key; ?>" 
                                               <?php echo in_array($key, $filters['governorates']) ? 'checked' : ''; ?>
                                               onchange="this.form.submit()">
                                        <label class="form-check-label small" for="gov-<?php echo $key; ?>">
                                            <?php echo $govName; ?>
                                        </label>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <hr class="my-3 border-light">

                        <!-- Condition Filter -->
                        <div class="mb-4">
                            <h6 class="fw-bold text-dark mb-2"><?php echo __('product_condition'); ?></h6>
                            <div class="form-check mb-1">
                                <input class="form-check-input" type="radio" name="condition" value="" id="cond-all" 
                                       <?php echo empty($filters['condition']) ? 'checked' : ''; ?> onchange="this.form.submit()">
                                <label class="form-check-label small" for="cond-all"><?php echo __('all'); ?></label>
                            </div>
                            <div class="form-check mb-1">
                                <input class="form-check-input" type="radio" name="condition" value="new" id="cond-new" 
                                       <?php echo ($filters['condition'] === 'new') ? 'checked' : ''; ?> onchange="this.form.submit()">
                                <label class="form-check-label small" for="cond-new"><?php echo __('cond_new'); ?></label>
                            </div>
                            <div class="form-check mb-1">
                                <input class="form-check-input" type="radio" name="condition" value="used" id="cond-used" 
                                       <?php echo ($filters['condition'] === 'used') ? 'checked' : ''; ?> onchange="this.form.submit()">
                                <label class="form-check-label small" for="cond-used"><?php echo __('cond_used'); ?></label>
                            </div>
                        </div>

                        <hr class="my-3 border-light">

                        <!-- Price Range Filter -->
                        <div class="mb-4">
                            <h6 class="fw-bold text-dark mb-2"><?php echo __('price_range'); ?></h6>
                            <div class="row g-2">
                                <div class="col-6">
                                    <input type="number" name="min_price" class="form-control form-control-sm rounded-md" placeholder="<?php echo __('min_price'); ?>" value="<?php echo $filters['min_price']; ?>">
                                </div>
                                <div class="col-6">
                                    <input type="number" name="max_price" class="form-control form-control-sm rounded-md" placeholder="<?php echo __('max_price'); ?>" value="<?php echo $filters['max_price']; ?>">
                                </div>
                            </div>
                            <button type="submit" class="btn btn-souk-teal btn-sm w-100 rounded-pill mt-2"><?php echo __('apply_price'); ?></button>
                        </div>

                        <hr class="my-3 border-light">

                        <!-- Verified Toggle -->
                        <div>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="verified" value="1" id="verified-only" 
                                       <?php echo $filters['verified_only'] ? 'checked' : ''; ?> onchange="this.form.submit()">
                                <label class="form-check-label small fw-bold" for="verified-only"><?php echo __('verified_stores_only'); ?></label>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </aside>

        <!-- Products List Column (Col 9) -->
        <main class="col-lg-9">
            <!-- Header Result Bar -->
            <div class="card border rounded-lg p-3 mb-4 bg-white shadow-sm d-flex flex-md-row justify-content-between align-items-center gap-3">
                <div>
                    <h5 class="fw-bold m-0 text-dark">
                        <?php if (!empty($filters['q'])): ?>
                            <?php echo __('search_results_for', ['query' => htmlspecialchars($filters['q'])]); ?>
                        <?php else: ?>
                            <?php echo __('all_listings'); ?>
                        <?php endif; ?>
                    </h5>
                    <small class="text-muted"><?php echo __('found_products_count', ['count' => $totalProducts]); ?></small>
                </div>

                <!-- Sorter Dropdown -->
                <div class="d-flex align-items-center gap-2">
                    <label class="text-nowrap small text-muted mb-0"><?php echo __('sort_by'); ?></label>
                    <select class="form-select form-select-sm rounded-pill" style="width: 170px;" 
                            onchange="location = '<?php echo SITE_URL; ?>/search?' + new URLSearchParams(window.location.search).toString().replace(/&?sort=[^&]*/, '') + '&sort=' + this.value;">
                        <option value="relevance" <?php echo ($sort === 'relevance') ? 'selected' : ''; ?>><?php echo __('sort_featured'); ?></option>
                        <option value="newest" <?php echo ($sort === 'newest') ? 'selected' : ''; ?>><?php echo __('recently_added'); ?></option>
                        <option value="price_low" <?php echo ($sort === 'price_low') ? 'selected' : ''; ?>><?php echo __('price_low_high'); ?></option>
                        <option value="price_high" <?php echo ($sort === 'price_high') ? 'selected' : ''; ?>><?php echo __('price_high_low'); ?></option>
                        <option value="views" <?php echo ($sort === 'views') ? 'selected' : ''; ?>><?php echo __('most_viewed'); ?></option>
                    </select>
                </div>
            </div>

            <!-- Product Grid -->
            <?php if (empty($products)): ?>
                <div class="card border rounded-lg p-5 text-center bg-white shadow-sm">
                    <i class="bi bi-search text-muted display-1 d-block mb-3"></i>
                    <h4 class="fw-bold"><?php echo __('no_search_results'); ?></h4>
                    <p class="text-muted mb-4"><?php echo __('no_search_results_desc'); ?></p>
                    <a href="<?php echo SITE_URL; ?>/search" class="btn btn-souk-primary rounded-pill px-4"><?php echo __('show_all_products'); ?></a>
                </div>
            <?php else: ?>
                <div class="row g-4">
                    <?php foreach ($products as $prod): ?>
                        <?php 
                        $prodName = getLocalized($prod, 'name'); 
                        $storeName = getLocalized($prod, 'store_name');
                        ?>
                        <div class="col-lg-4 col-md-6">
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
                                        <div class="mb-1"><small class="text-muted"><i class="bi bi-geo-alt-fill text-gold"></i> <?php echo GOVERNORATES[$lang][$prod->store_governorate] ?? $prod->store_governorate; ?></small></div>
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

                <!-- Pagination -->
                <?php if ($totalPages > 1): ?>
                    <nav class="mt-5" aria-label="Search results pages">
                        <ul class="pagination justify-content-center">
                            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                <?php 
                                $queryParams = $_GET;
                                $queryParams['page'] = $i;
                                $url = SITE_URL . '/search?' . http_build_query($queryParams);
                                ?>
                                <li class="page-item <?php echo ($currentPage == $i) ? 'active' : ''; ?>">
                                    <a class="page-link" href="<?php echo $url; ?>" style="<?php echo ($currentPage == $i) ? 'background-color: var(--color-primary); border-color: var(--color-primary); color: #FFF;' : 'color: var(--color-text-secondary);'; ?>">
                                        <?php echo $i; ?>
                                    </a>
                                </li>
                            <?php endfor; ?>
                        </ul>
                    </nav>
                <?php endif; ?>
            <?php endif; ?>
        </main>
    </div>
</div>
