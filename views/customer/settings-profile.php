<h3 class="fw-bold mb-4"><i class="bi bi-person text-gold"></i> <?php echo __('settings_profile_title'); ?></h3>

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
    <form action="<?php echo SITE_URL; ?>/dashboard/settings/profile" method="POST" enctype="multipart/form-data">
        <?php echo \Core\CSRF::field(); ?>

        <div class="row g-3 mb-4">
            <!-- Current Avatar widget -->
            <div class="col-12 text-center pb-3 border-bottom mb-3">
                <img src="<?php echo $user->avatar ? SITE_URL . '/uploads/avatars/' . $user->avatar : 'https://placehold.co/100'; ?>" 
                     alt="avatar" class="rounded-circle object-cover border mb-2" width="100" height="100">
                <div>
                    <label for="avatar" class="form-label small fw-bold btn btn-sm btn-outline-secondary rounded-pill px-3"><?php echo __('change_avatar'); ?></label>
                    <input type="file" name="avatar" id="avatar" class="form-control d-none">
                </div>
            </div>

            <!-- Full Name -->
            <div class="col-md-6">
                <label for="full_name" class="form-label small fw-bold text-dark"><?php echo __('full_name'); ?> *</label>
                <input type="text" name="full_name" id="full_name" class="form-control" value="<?php echo htmlspecialchars($user->full_name); ?>" required>
            </div>

            <!-- Phone -->
            <div class="col-md-6">
                <label for="phone" class="form-label small fw-bold text-dark"><?php echo __('phone'); ?></label>
                <input type="text" name="phone" id="phone" class="form-control" value="<?php echo htmlspecialchars($user->phone ?? ''); ?>">
            </div>

            <!-- Governorate -->
            <div class="col-md-6">
                <label for="governorate" class="form-label small fw-bold text-dark"><?php echo __('governorate'); ?></label>
                <select name="governorate" id="governorate" class="form-select">
                    <option value=""><?php echo __('select_governorate_option', [], 'اختر المحافظة...'); ?></option>
                    <?php foreach (GOVERNORATES[$lang] as $key => $govName): ?>
                        <option value="<?php echo $key; ?>" <?php echo ($user->governorate === $key) ? 'selected' : ''; ?>><?php echo $govName; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- City -->
            <div class="col-md-6">
                <label for="city" class="form-label small fw-bold text-dark"><?php echo __('district_city', [], 'المنطقة / القضاء'); ?></label>
                <input type="text" name="city" id="city" class="form-control" value="<?php echo htmlspecialchars($user->city ?? ''); ?>">
            </div>

            <!-- DOB -->
            <div class="col-md-6">
                <label for="dob" class="form-label small fw-bold text-dark"><?php echo __('dob'); ?></label>
                <input type="date" name="dob" id="dob" class="form-control" value="<?php echo $user->birth_date; ?>">
            </div>

            <!-- Gender -->
            <div class="col-md-6">
                <label for="gender" class="form-label small fw-bold text-dark"><?php echo __('gender'); ?></label>
                <select name="gender" id="gender" class="form-select">
                    <option value="prefer_not" <?php echo ($user->gender === 'prefer_not') ? 'selected' : ''; ?>><?php echo __('gender_prefer_not'); ?></option>
                    <option value="male" <?php echo ($user->gender === 'male') ? 'selected' : ''; ?>><?php echo __('gender_male'); ?></option>
                    <option value="female" <?php echo ($user->gender === 'female') ? 'selected' : ''; ?>><?php echo __('gender_female'); ?></option>
                </select>
            </div>
        </div>

        <button type="submit" class="btn btn-souk-primary px-5 rounded-pill"><?php echo __('save_changes'); ?></button>
    </form>
</div>
