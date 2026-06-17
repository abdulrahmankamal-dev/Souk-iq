<h3 class="fw-bold mb-4"><i class="bi bi-palette text-gold"></i> <?php echo __('settings_appearance'); ?></h3>

<!-- Alert messages -->
<?php if ($flashSuccess = \Core\Session::getFlash('success')): ?>
    <div class="alert alert-success border small py-2 mb-3">
        <?php echo $flashSuccess; ?>
    </div>
<?php endif; ?>

<div class="card border rounded-lg p-4 bg-white shadow-sm">
    <form action="<?php echo SITE_URL; ?>/dashboard/settings/appearance" method="POST">
        <?php echo \Core\CSRF::field(); ?>

        <div class="mb-4">
            <label class="form-label small fw-bold text-dark d-block"><?php echo __('theme_visibility_title'); ?></label>
            <div class="row g-3">
                <div class="col-md-4">
                    <div class="form-check p-3 border rounded text-center">
                        <input class="form-check-input float-none mx-auto mb-2 d-block" type="radio" name="theme_pref" id="theme-light" value="light" <?php echo ($user->theme_pref === 'light') ? 'checked' : ''; ?>>
                        <label class="form-check-label text-dark small fw-bold" for="theme-light">
                            <i class="bi bi-sun fs-4 d-block mb-1 text-warning"></i>
                            <?php echo __('theme_light'); ?>
                        </label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-check p-3 border rounded text-center">
                        <input class="form-check-input float-none mx-auto mb-2 d-block" type="radio" name="theme_pref" id="theme-dark" value="dark" <?php echo ($user->theme_pref === 'dark') ? 'checked' : ''; ?>>
                        <label class="form-check-label text-dark small fw-bold" for="theme-dark">
                            <i class="bi bi-moon-stars fs-4 d-block mb-1 text-info"></i>
                            <?php echo __('theme_dark'); ?>
                        </label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-check p-3 border rounded text-center">
                        <input class="form-check-input float-none mx-auto mb-2 d-block" type="radio" name="theme_pref" id="theme-system" value="system" <?php echo ($user->theme_pref === 'system') ? 'checked' : ''; ?>>
                        <label class="form-check-label text-dark small fw-bold" for="theme-system">
                            <i class="bi bi-laptop fs-4 d-block mb-1 text-muted"></i>
                            <?php echo __('theme_system'); ?>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <hr class="my-4 border-light">

        <!-- Language selection preference -->
        <div class="mb-4">
            <label for="lang_pref" class="form-label small fw-bold text-dark"><?php echo __('preferred_lang_title'); ?></label>
            <select name="lang_pref" id="lang_pref" class="form-select">
                <option value="ar" <?php echo ($user->lang_pref === 'ar') ? 'selected' : ''; ?>><?php echo __('lang_ar'); ?></option>
                <option value="ku" <?php echo ($user->lang_pref === 'ku') ? 'selected' : ''; ?>><?php echo __('lang_ku'); ?></option>
                <option value="en" <?php echo ($user->lang_pref === 'en') ? 'selected' : ''; ?>><?php echo __('lang_en'); ?></option>
            </select>
        </div>

        <button type="submit" class="btn btn-souk-primary px-5 rounded-pill"><?php echo __('save_changes'); ?></button>
    </form>
</div>
