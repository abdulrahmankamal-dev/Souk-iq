<h3 class="fw-bold mb-4"><i class="bi bi-eye-slash text-gold"></i> <?php echo __('settings_privacy'); ?></h3>

<!-- Alert messages -->
<?php if ($flashSuccess = \Core\Session::getFlash('success')): ?>
    <div class="alert alert-success border small py-2 mb-3">
        <?php echo $flashSuccess; ?>
    </div>
<?php endif; ?>

<div class="card border rounded-lg p-4 bg-white shadow-sm">
    <form action="<?php echo SITE_URL; ?>/dashboard/settings/privacy" method="POST">
        <?php echo \Core\CSRF::field(); ?>

        <div class="mb-4">
            <label class="form-label small fw-bold text-dark d-block"><?php echo __('visibility_label'); ?></label>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="profile_visibility" id="vis-public" value="public" <?php echo ($settings->profile_visibility === 'public') ? 'checked' : ''; ?>>
                <label class="form-check-label text-dark" for="vis-public"><?php echo __('vis_public_desc'); ?></label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="profile_visibility" id="vis-private" value="private" <?php echo ($settings->profile_visibility === 'private') ? 'checked' : ''; ?>>
                <label class="form-check-label text-dark" for="vis-private"><?php echo __('vis_private_desc'); ?></label>
            </div>
        </div>

        <hr class="my-4 border-light">

        <h5 class="fw-bold text-dark mb-3"><?php echo __('contact_sharing_title'); ?></h5>
        <div class="d-flex flex-column gap-3 mb-4">
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" name="show_email" id="show_email" value="1" <?php echo $settings->show_email ? 'checked' : ''; ?>>
                <label class="form-check-label text-dark small" for="show_email"><?php echo __('show_email_desc'); ?></label>
            </div>
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" name="show_phone" id="show_phone" value="1" <?php echo $settings->show_phone ? 'checked' : ''; ?>>
                <label class="form-check-label text-dark small" for="show_phone"><?php echo __('show_phone_desc'); ?></label>
            </div>
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" name="show_location" id="show_location" value="1" <?php echo $settings->show_location ? 'checked' : ''; ?>>
                <label class="form-check-label text-dark small" for="show_location"><?php echo __('show_location_desc'); ?></label>
            </div>
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" name="allow_messages" id="allow_messages" value="1" <?php echo $settings->allow_messages ? 'checked' : ''; ?>>
                <label class="form-check-label text-dark small" for="allow_messages"><?php echo __('allow_messages_desc'); ?></label>
            </div>
        </div>

        <button type="submit" class="btn btn-souk-primary px-5 rounded-pill"><?php echo __('save_privacy'); ?></button>
    </form>
</div>
