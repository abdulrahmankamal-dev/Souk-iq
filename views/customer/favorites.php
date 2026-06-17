<h3 class="fw-bold mb-4"><i class="bi bi-heart text-danger"></i> <?php echo __('my_favorites'); ?></h3>

<?php if (empty($favorites)): ?>
    <div class="card border rounded-lg p-5 text-center bg-white shadow-sm">
        <i class="bi bi-heart-break text-muted display-1 d-block mb-3"></i>
        <h5 class="fw-bold"><?php echo __('no_favorites_yet'); ?></h5>
        <p class="text-muted mb-4"><?php echo __('no_favorites_desc', [], 'تصفح المنتجات في المنصة وقم بإضافتها هنا لمقارنة أسعارها في أي وقت.'); ?></p>
        <a href="<?php echo SITE_URL; ?>/search" class="btn btn-souk-primary rounded-pill px-4"><?php echo __('browse_products', [], 'تصفح المنتجات'); ?></a>
    </div>
<?php else: ?>
    <div class="row g-4">
        <?php foreach ($favorites as $prod): ?>
            <?php 
            $prodName = getLocalized($prod, 'name'); 
            $storeName = getLocalized($prod, 'store_name');
            ?>
            <div class="col-md-6 col-lg-4">
                <div class="souk-card">
                    <div class="souk-card__img-wrapper">
                        <img src="<?php echo $prod->thumbnail ? SITE_URL . '/uploads/products/' . $prod->thumbnail : 'https://placehold.co/300x225'; ?>" class="souk-card__img" alt="fav">
                    </div>
                    <div class="souk-card__body">
                        <div>
                            <small class="text-muted d-flex align-items-center gap-1 mb-1">
                                <i class="bi bi-shop text-gold"></i>
                                <span><?php echo $storeName; ?></span>
                            </small>
                            <h6 class="fw-bold text-dark text-truncate mb-2">
                                <a href="<?php echo SITE_URL; ?>/product/<?php echo $prod->store_slug; ?>/<?php echo $prod->slug; ?>" class="text-dark hover-gold">
                                    <?php echo $prodName; ?>
                                </a>
                            </h6>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <span class="text-dark fw-bold"><?php echo number_format($prod->price); ?> د.ع</span>
                            
                            <!-- Remove Favorite form -->
                            <form action="<?php echo SITE_URL; ?>/product/favorite" method="POST">
                                <?php echo \Core\CSRF::field(); ?>
                                <input type="hidden" name="product_id" value="<?php echo $prod->id; ?>">
                                <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-3">
                                    <?php echo __('remove', [], 'إزالة'); ?>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
