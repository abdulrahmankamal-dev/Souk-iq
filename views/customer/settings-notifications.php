<h3 class="fw-bold mb-4"><i class="bi bi-bell-slash text-gold"></i> <?php echo __('settings_notifications'); ?></h3>

<!-- Alert messages -->
<?php if ($flashSuccess = \Core\Session::getFlash('success')): ?>
    <div class="alert alert-success border small py-2 mb-3">
        <?php echo $flashSuccess; ?>
    </div>
<?php endif; ?>

<div class="card border rounded-lg p-4 bg-white shadow-sm">
    <form action="<?php echo SITE_URL; ?>/dashboard/settings/notifications" method="POST">
        <?php echo \Core\CSRF::field(); ?>

        <h5 class="fw-bold text-dark mb-3"><?php echo __('email_alerts_title'); ?></h5>
        <div class="d-flex flex-column gap-3 mb-4 border-bottom pb-4">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="notify_email_reviews" id="email_rev" value="1" <?php echo $settings->notify_email_reviews ? 'checked' : ''; ?>>
                <label class="form-check-label text-dark small" for="email_rev"><?php echo __('email_rev_desc'); ?></label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="notify_email_follows" id="email_fol" value="1" <?php echo $settings->notify_email_follows ? 'checked' : ''; ?>>
                <label class="form-check-label text-dark small" for="email_fol"><?php echo __('email_fol_desc'); ?></label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="notify_email_marketing" id="email_mkt" value="1" <?php echo $settings->notify_email_marketing ? 'checked' : ''; ?>>
                <label class="form-check-label text-dark small" for="email_mkt"><?php echo __('email_mkt_desc'); ?></label>
            </div>
        </div>

        <h5 class="fw-bold text-dark mb-3"><?php echo __('push_alerts_title'); ?></h5>
        <div class="d-flex flex-column gap-3 mb-4">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="notify_push_reviews" id="push_rev" value="1" <?php echo $settings->notify_push_reviews ? 'checked' : ''; ?>>
                <label class="form-check-label text-dark small" for="push_rev"><?php echo __('push_rev_desc'); ?></label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="notify_push_follows" id="push_fol" value="1" <?php echo $settings->notify_push_follows ? 'checked' : ''; ?>>
                <label class="form-check-label text-dark small" for="push_fol"><?php echo __('push_fol_desc'); ?></label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="notify_push_pricedrops" id="push_drop" value="1" <?php echo $settings->notify_push_pricedrops ? 'checked' : ''; ?>>
                <label class="form-check-label text-dark small" for="push_drop"><?php echo __('push_drop_desc'); ?></label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="notify_push_newproducts" id="push_new" value="1" <?php echo $settings->notify_push_newproducts ? 'checked' : ''; ?>>
                <label class="form-check-label text-dark small" for="push_new"><?php echo __('push_new_desc'); ?></label>
            </div>
        </div>

        <button type="submit" class="btn btn-souk-primary px-5 rounded-pill"><?php echo __('save_notifications'); ?></button>
    </form>
</div>
