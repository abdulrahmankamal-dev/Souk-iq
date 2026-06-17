<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold m-0"><i class="bi bi-speedometer2 text-gold"></i> <?php echo __('dashboard_personal', [], 'لوحة التحكم الشخصية'); ?></h3>
    <span class="badge bg-gold-light text-gold rounded-pill px-3 py-2 border"><?php echo __('role_label', ['role' => __($user->role)]); ?></span>
</div>

<!-- Welcome Widget -->
<div class="card border rounded-lg p-4 bg-white shadow-sm mb-4">
    <h4 class="fw-bold text-dark"><?php echo __('welcome_back', ['name' => $user->full_name]); ?></h4>
    <p class="text-muted small mb-3"><?php echo __('dashboard_desc'); ?></p>
    
    <!-- Completion Bar -->
    <div class="row align-items-center g-3">
        <div class="col-md-3">
            <span class="small fw-bold text-dark"><?php echo __('profile_completion_label', ['percent' => $profilePercent]); ?></span>
        </div>
        <div class="col-md-9">
            <div class="progress" style="height: 12px;">
                <div class="progress-bar bg-warning" role="progressbar" style="width: <?php echo $profilePercent; ?>%;" aria-valuenow="<?php echo $profilePercent; ?>" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
        </div>
    </div>
</div>

<!-- Stats Indicators -->
<div class="row g-4 mb-4">
    <!-- Favorites -->
    <div class="col-md-4">
        <div class="card border rounded-lg p-3 bg-white shadow-sm d-flex flex-row align-items-center gap-3">
            <div class="bg-danger-subtle text-danger rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                <i class="bi bi-heart-fill fs-3"></i>
            </div>
            <div>
                <h3 class="fw-bold text-dark m-0"><?php echo $favoritesCount; ?></h3>
                <small class="text-muted"><?php echo __('my_favorites_count', [], 'المنتجات المفضلة'); ?></small>
            </div>
        </div>
    </div>

    <!-- Followed Stores -->
    <div class="col-md-4">
        <div class="card border rounded-lg p-3 bg-white shadow-sm d-flex flex-row align-items-center gap-3">
            <div class="bg-success-subtle text-success rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                <i class="bi bi-shop fs-3"></i>
            </div>
            <div>
                <h3 class="fw-bold text-dark m-0"><?php echo $followedCount; ?></h3>
                <small class="text-muted"><?php echo __('followed_stores_count', [], 'متاجر أتابعها'); ?></small>
            </div>
        </div>
    </div>

    <!-- Reviews written -->
    <div class="col-md-4">
        <div class="card border rounded-lg p-3 bg-white shadow-sm d-flex flex-row align-items-center gap-3">
            <div class="bg-warning-subtle text-warning rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                <i class="bi bi-star-fill fs-3"></i>
            </div>
            <div>
                <h3 class="fw-bold text-dark m-0"><?php echo $reviewsCount; ?></h3>
                <small class="text-muted"><?php echo __('reviews_count_label', [], 'تقييماتي المنشورة'); ?></small>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activities -->
<div class="card border rounded-lg p-4 bg-white shadow-sm">
    <h5 class="fw-bold text-dark mb-4 border-bottom pb-2"><?php echo __('recent_reviews', [], 'تقييماتي الأخيرة'); ?></h5>
    
    <?php if (empty($latestReviews)): ?>
        <p class="text-muted text-center py-4"><?php echo __('no_reviews'); ?></p>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-striped table-bordered small">
                <thead>
                    <tr class="bg-light">
                        <th><?php echo __('product_or_store', [], 'المنتج / المتجر'); ?></th>
                        <th><?php echo __('rating', [], 'التقييم'); ?></th>
                        <th><?php echo __('comment', [], 'التعليق'); ?></th>
                        <th><?php echo __('date', [], 'التاريخ'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($latestReviews as $rev): ?>
                        <tr>
                            <td class="fw-bold">
                                <?php if ($rev->target_type === 'product'): ?>
                                    <span class="badge bg-gold-light text-gold me-1"><?php echo __('badge_product', [], 'منتج'); ?></span> 
                                    <a href="<?php echo SITE_URL; ?>/product/<?php echo $rev->store_slug; ?>/<?php echo $rev->product_slug; ?>" class="text-dark hover-gold">
                                        <?php echo htmlspecialchars(getLocalized($rev, 'product_name')); ?>
                                    </a>
                                <?php else: ?>
                                    <span class="badge bg-info-subtle text-info me-1"><?php echo __('badge_store', [], 'متجر'); ?></span>
                                    <a href="<?php echo SITE_URL; ?>/store/<?php echo $rev->store_slug; ?>" class="text-dark hover-gold">
                                        <?php echo htmlspecialchars(getLocalized($rev, 'store_name')); ?>
                                    </a>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="text-warning">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <i class="bi <?php echo ($i <= $rev->rating) ? 'bi-star-fill' : 'bi-star'; ?>"></i>
                                    <?php endfor; ?>
                                </div>
                            </td>
                            <td class="text-truncate" style="max-width: 250px;"><?php echo htmlspecialchars($rev->body); ?></td>
                            <td><?php echo date('Y/m/d H:i', strtotime($rev->created_at)); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
