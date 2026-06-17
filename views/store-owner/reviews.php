<div class="mb-4">
    <h3 class="fw-bold m-0"><i class="bi bi-chat-left-quote text-gold"></i> تقييمات ومراجعات متجرك</h3>
    <small class="text-muted">شاهد آراء العملاء على متجرك وعلى منتجاتك وقم بالرد عليها لزيادة موثوقيتك</small>
</div>

<!-- Alert notifications -->
<?php if ($flashSuccess = \Core\Session::getFlash('success')): ?>
    <div class="alert alert-success border small py-2 mb-3">
        <?php echo $flashSuccess; ?>
    </div>
<?php endif; ?>
<?php if ($flashError = \Core\Session::getFlash('error')): ?>
    <div class="alert alert-danger border small py-2 mb-3">
        <?php echo $flashError; ?>
    </div>
<?php endif; ?>

<div class="row g-4">
    <!-- Product Reviews Col -->
    <div class="col-lg-6">
        <div class="card border rounded-lg p-4 bg-white shadow-sm h-100">
            <h5 class="fw-bold text-dark mb-4 border-bottom pb-2"><i class="bi bi-box-seam text-gold"></i> تقييمات المنتجات</h5>
            
            <?php if (empty($productReviews)): ?>
                <p class="text-muted text-center py-5">لا توجد أي مراجعات على منتجاتك حالياً.</p>
            <?php else: ?>
                <div class="d-flex flex-column gap-3">
                    <?php foreach ($productReviews as $rev): ?>
                        <div class="border-bottom pb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <strong class="text-dark small"><?php echo $rev->user_name; ?> &mdash; <span class="text-gold"><?php echo $rev->prod_name; ?></span></strong>
                                <span class="text-warning small">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <i class="bi <?php echo ($i <= $rev->rating) ? 'bi-star-fill' : 'bi-star'; ?>"></i>
                                    <?php endfor; ?>
                                </span>
                            </div>
                            <p class="text-secondary small mb-2"><?php echo htmlspecialchars($rev->body); ?></p>
                            
                            <!-- Reply input -->
                            <?php if (empty($rev->store_reply)): ?>
                                <form action="<?php echo SITE_URL; ?>/store-owner/reviews/reply" method="POST" class="mt-2">
                                    <?php echo \Core\CSRF::field(); ?>
                                    <input type="hidden" name="review_id" value="<?php echo $rev->id; ?>">
                                    <div class="input-group input-group-sm">
                                        <input type="text" name="reply" class="form-control" placeholder="اكتب ردك على التقييم..." required>
                                        <button class="btn btn-souk-primary" type="submit">إرسال الرد</button>
                                    </div>
                                </form>
                            <?php else: ?>
                                <div class="bg-light p-2 rounded border-start border-4 border-gold small mt-2">
                                    <strong>ردك:</strong> <span><?php echo $rev->store_reply; ?></span>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Store Reviews Col -->
    <div class="col-lg-6">
        <div class="card border rounded-lg p-4 bg-white shadow-sm h-100">
            <h5 class="fw-bold text-dark mb-4 border-bottom pb-2"><i class="bi bi-shop text-gold"></i> تقييمات المحل المباشرة</h5>
            
            <?php if (empty($storeReviews)): ?>
                <p class="text-muted text-center py-5">لا توجد تقييمات مباشرة للمتجر حتى الآن.</p>
            <?php else: ?>
                <div class="d-flex flex-column gap-3">
                    <?php foreach ($storeReviews as $rev): ?>
                        <div class="border-bottom pb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <strong class="text-dark small"><?php echo $rev->user_name; ?></strong>
                                <span class="text-warning small">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <i class="bi <?php echo ($i <= $rev->rating) ? 'bi-star-fill' : 'bi-star'; ?>"></i>
                                    <?php endfor; ?>
                                </span>
                            </div>
                            <p class="text-secondary small mb-2"><?php echo htmlspecialchars($rev->body); ?></p>
                            
                            <!-- Reply input -->
                            <?php if (empty($rev->store_reply)): ?>
                                <form action="<?php echo SITE_URL; ?>/store-owner/reviews/reply" method="POST" class="mt-2">
                                    <?php echo \Core\CSRF::field(); ?>
                                    <input type="hidden" name="review_id" value="<?php echo $rev->id; ?>">
                                    <div class="input-group input-group-sm">
                                        <input type="text" name="reply" class="form-control" placeholder="اكتب ردك على التقييم..." required>
                                        <button class="btn btn-souk-primary" type="submit">إرسال الرد</button>
                                    </div>
                                </form>
                            <?php else: ?>
                                <div class="bg-light p-2 rounded border-start border-4 border-gold small mt-2">
                                    <strong>ردك:</strong> <span><?php echo $rev->store_reply; ?></span>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
