<h3 class="fw-bold mb-4"><i class="bi bi-shield-lock text-gold"></i> <?php echo __('settings_security'); ?></h3>

<!-- Alert messages -->
<?php if ($flashError = \Core\Session::getFlash('error')): ?>
    <div class="alert alert-danger border small py-2 mb-3">
        <?php echo $flashError; ?>
    </div>
<?php endif; ?>
<?php if ($flashSuccess = \Core\Session::getFlash('success')): ?>
    <div class="alert alert-success border small py-2 mb-3">
        <?php echo $flashSuccess; ?>
    </div>
<?php endif; ?>

<div class="card border rounded-lg p-4 bg-white shadow-sm">
    <form action="<?php echo SITE_URL; ?>/dashboard/settings/security" method="POST">
        <?php echo \Core\CSRF::field(); ?>

        <div class="row g-3 mb-4">
            <!-- Current Password -->
            <div class="col-12">
                <label for="current_password" class="form-label small fw-bold text-dark"><?php echo __('current_password', [], 'كلمة المرور الحالية'); ?> *</label>
                <input type="password" name="current_password" id="current_password" class="form-control" placeholder="••••••••" required>
            </div>

            <!-- New Password -->
            <div class="col-md-6">
                <label for="new_password" class="form-label small fw-bold text-dark"><?php echo __('new_password', [], 'كلمة المرور الجديدة'); ?> *</label>
                <input type="password" name="new_password" id="new_password" class="form-control" placeholder="••••••••" required>
            </div>

            <!-- Confirm New Password -->
            <div class="col-md-6">
                <label for="confirm_password" class="form-label small fw-bold text-dark"><?php echo __('confirm_new_password', [], 'تأكيد كلمة المرور الجديدة'); ?> *</label>
                <input type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder="••••••••" required>
            </div>
        </div>

        <button type="submit" class="btn btn-souk-danger px-5 rounded-pill"><?php echo __('update_password', [], 'تحديث كلمة المرور'); ?></button>
    </form>
</div>
