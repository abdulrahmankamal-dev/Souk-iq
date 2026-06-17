<h3 class="fw-bold mb-4"><i class="bi bi-star text-warning"></i> <?php echo __('reviews_count_label'); ?></h3>

<?php if (empty($reviews)): ?>
    <div class="card border rounded-lg p-5 text-center bg-white shadow-sm">
        <i class="bi bi-chat-square-quote text-muted display-1 d-block mb-3"></i>
        <h5 class="fw-bold"><?php echo __('no_reviews_yet'); ?></h5>
        <p class="text-muted mb-4"><?php echo __('reviews_subtitle', [], 'آراؤك تساعد الآخرين في اتخاذ قرارات تسوق أفضل في العراق.'); ?></p>
    </div>
<?php else: ?>
    <div class="d-flex flex-column gap-3">
        <?php foreach ($reviews as $rev): ?>
            <div class="card border rounded-lg p-4 bg-white shadow-sm">
                <div class="d-flex justify-content-between align-items-start mb-2 border-bottom pb-2">
                    <div>
                        <h6 class="fw-bold m-0">
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
                        </h6>
                        <small class="text-muted"><?php echo date('Y/m/d H:i', strtotime($rev->created_at)); ?></small>
                    </div>

                    <!-- Delete button -->
                    <form action="<?php echo SITE_URL; ?>/dashboard/reviews/delete" method="POST" onsubmit="return confirm('<?php echo __('confirm_delete_review', [], 'هل أنت متأكد من حذف هذا التقييم؟'); ?>');">
                        <?php echo \Core\CSRF::field(); ?>
                        <input type="hidden" name="id" value="<?php echo $rev->id; ?>">
                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill">
                            <i class="bi bi-trash"></i> <?php echo __('delete_review', [], 'حذف التقييم'); ?>
                        </button>
                    </form>
                </div>

                <div class="d-flex align-items-center gap-2 mb-2">
                    <div class="text-warning small">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <i class="bi <?php echo ($i <= $rev->rating) ? 'bi-star-fill' : 'bi-star'; ?>"></i>
                        <?php endfor; ?>
                    </div>
                    <span class="badge rounded-pill <?php echo ($rev->status === 'approved') ? 'bg-success-subtle text-success' : (($rev->status === 'pending') ? 'bg-warning-subtle text-warning' : 'bg-danger-subtle text-danger'); ?>">
                        <?php echo ($rev->status === 'approved') ? __('status_approved', [], 'مقبول ونشط') : (($rev->status === 'pending') ? __('status_pending', [], 'قيد المراجعة') : __('status_rejected', [], 'مرفوض')); ?>
                    </span>
                </div>

                <?php if ($rev->title): ?>
                    <h6 class="fw-bold mb-1 text-dark"><?php echo $rev->title; ?></h6>
                <?php endif; ?>
                <p class="text-secondary small mb-0"><?php echo nl2br(htmlspecialchars($rev->body)); ?></p>

                <?php if ($rev->store_reply): ?>
                    <div class="bg-light p-3 rounded border-start border-4 border-gold mt-3 small">
                        <strong class="text-dark"><?php echo __('seller_reply', [], 'رد البائع:'); ?></strong>
                        <p class="text-secondary mb-0 mt-1"><?php echo $rev->store_reply; ?></p>
                    </div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
