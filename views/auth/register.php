<div class="card border-0 shadow-lg rounded-lg bg-surface" style="border-top: 5px solid var(--color-primary) !important;">
    <div class="card-body p-4 p-md-5">
        <h3 class="fw-bold mb-1 text-center text-dark"><?php echo __('register_title'); ?></h3>
        <p class="text-muted text-center small mb-4"><?php echo __('register_subtitle'); ?></p>

        <!-- Toast / Alert notification -->
        <?php if ($flashError = \Core\Session::getFlash('error')): ?>
            <div class="alert alert-danger border d-flex align-items-center gap-2 small py-2 mb-3">
                <i class="bi bi-exclamation-triangle-fill"></i>
                <span><?php echo $flashError; ?></span>
            </div>
        <?php endif; ?>

        <form action="<?php echo SITE_URL; ?>/register" method="POST" class="needs-validation" novalidate>
            <?php echo \Core\CSRF::field(); ?>

            <div class="row g-3">
                <!-- Full Name -->
                <div class="col-md-6">
                    <label for="full_name" class="form-label small fw-bold text-dark"><?php echo __('full_name'); ?> *</label>
                    <input type="text" name="full_name" id="full_name" class="form-control rounded-md" placeholder="<?php echo $lang === 'ar' ? 'علي أحمد' : ($lang === 'ku' ? 'عەلی ئەحمەد' : 'Ali Ahmed'); ?>" required>
                </div>

                <!-- Username -->
                <div class="col-md-6">
                    <label for="username" class="form-label small fw-bold text-dark"><?php echo __('username'); ?> *</label>
                    <input type="text" name="username" id="username" class="form-control rounded-md" placeholder="ali_ahmed" required>
                </div>

                <!-- Email -->
                <div class="col-md-6">
                    <label for="email" class="form-label small fw-bold text-dark"><?php echo __('email'); ?> *</label>
                    <input type="email" name="email" id="email" class="form-control rounded-md" placeholder="name@domain.com" required>
                </div>

                <!-- Phone -->
                <div class="col-md-6">
                    <label for="phone" class="form-label small fw-bold text-dark"><?php echo __('phone'); ?></label>
                    <input type="text" name="phone" id="phone" class="form-control rounded-md" placeholder="07700000000">
                </div>

                <!-- Password -->
                <div class="col-md-6">
                    <label for="password" class="form-label small fw-bold text-dark"><?php echo __('password'); ?> *</label>
                    <input type="password" name="password" id="password" class="form-control rounded-md" placeholder="••••••••" required>
                </div>

                <!-- Confirm Password -->
                <div class="col-md-6">
                    <label for="confirm_password" class="form-label small fw-bold text-dark"><?php echo __('confirm_password'); ?> *</label>
                    <input type="password" name="confirm_password" id="confirm_password" class="form-control rounded-md" placeholder="••••••••" required>
                </div>

                <!-- Governorate -->
                <div class="col-md-6">
                    <label for="governorate" class="form-label small fw-bold text-dark"><?php echo __('governorate'); ?> *</label>
                    <select name="governorate" id="governorate" class="form-select rounded-md" required>
                        <option value=""><?php echo __('select_governorate_option', [], 'اختر المحافظة...'); ?></option>
                        <?php foreach (GOVERNORATES[$lang] as $key => $govName): ?>
                            <option value="<?php echo $key; ?>"><?php echo $govName; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- City -->
                <div class="col-md-6">
                    <label for="city" class="form-label small fw-bold text-dark"><?php echo __('city'); ?></label>
                    <input type="text" name="city" id="city" class="form-control rounded-md" placeholder="<?php echo __('district_city', [], 'المنطقة / القضاء'); ?>">
                </div>

                <!-- DOB -->
                <div class="col-md-6">
                    <label for="dob" class="form-label small fw-bold text-dark"><?php echo __('dob'); ?></label>
                    <input type="date" name="dob" id="dob" class="form-control rounded-md">
                </div>

                <!-- Gender -->
                <div class="col-md-6">
                    <label for="gender" class="form-label small fw-bold text-dark"><?php echo __('gender'); ?></label>
                    <select name="gender" id="gender" class="form-select rounded-md">
                        <option value="prefer_not"><?php echo __('gender_prefer_not'); ?></option>
                        <option value="male"><?php echo __('gender_male'); ?></option>
                        <option value="female"><?php echo __('gender_female'); ?></option>
                    </select>
                </div>

                <!-- Account Role -->
                <div class="col-12">
                    <label class="form-label small fw-bold text-dark"><?php echo __('account_type', [], 'نوع الحساب'); ?> *</label>
                    <div class="d-flex gap-4">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="role" id="role-customer" value="customer" checked>
                            <label class="form-check-label text-dark" for="role-customer">
                                <?php echo __('role_customer_desc', [], 'زبون / متسوق (أبحث عن أسعار ومحلات)'); ?>
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="role" id="role-owner" value="store_owner" 
                                   <?php echo (isset($_GET['role']) && $_GET['role'] === 'store_owner') ? 'checked' : ''; ?>>
                            <label class="form-check-label text-dark" for="role-owner">
                                <?php echo __('role_owner_desc', [], 'صاحب متجر / بائع (أريد عرض خدماتي ومنتجاتي)'); ?>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Checkbox terms -->
                <div class="col-12 mt-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="terms_agree" id="terms_agree" value="1" required>
                        <label class="form-check-label small text-muted" for="terms_agree">
                            <?php echo __('terms_agree'); ?>
                        </label>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-souk-primary w-100 py-2.5 fs-5 rounded-pill mt-4 mb-3">
                <i class="bi bi-person-plus-fill me-1"></i> <?php echo __('sign_up'); ?>
            </button>

            <div class="text-center">
                <span class="small text-muted"><?php echo __('has_account'); ?></span>
                <a href="<?php echo SITE_URL; ?>/login" class="small text-gold fw-bold text-decoration-none ms-1"><?php echo __('login'); ?></a>
            </div>
        </form>
    </div>
</div>
