<div class="card border-0 shadow-lg rounded-lg bg-surface shimmer-hover" style="border-top: 5px solid var(--color-primary) !important;">
    <div class="card-body p-4 p-md-5">
        <h3 class="fw-bold mb-1 text-center text-dark"><?php echo __('login_title'); ?></h3>
        <p class="text-muted text-center small mb-4"><?php echo __('login_subtitle'); ?></p>

        <!-- Toast / Alert notification -->
        <?php if ($flashError = \Core\Session::getFlash('error')): ?>
            <div class="alert alert-danger border d-flex align-items-center gap-2 small py-2 mb-3">
                <i class="bi bi-exclamation-triangle-fill"></i>
                <span><?php echo $flashError; ?></span>
            </div>
        <?php endif; ?>
        <?php if ($flashSuccess = \Core\Session::getFlash('success')): ?>
            <div class="alert alert-success border d-flex align-items-center gap-2 small py-2 mb-3">
                <i class="bi bi-check-circle-fill"></i>
                <span><?php echo $flashSuccess; ?></span>
            </div>
        <?php endif; ?>

        <form action="<?php echo SITE_URL; ?>/login" method="POST" class="needs-validation" novalidate>
            <?php echo \Core\CSRF::field(); ?>

            <!-- Username or Email -->
            <div class="souk-input-group mb-3">
                <label for="email_or_username" class="form-label small fw-bold text-dark"><?php echo __('email_or_username'); ?></label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-1"><i class="bi bi-person text-muted"></i></span>
                    <input type="text" name="email_or_username" id="email_or_username" class="form-control" placeholder="username or email" required>
                </div>
            </div>

            <!-- Password -->
            <div class="souk-input-group mb-3">
                <label for="password" class="form-label small fw-bold text-dark"><?php echo __('password'); ?></label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-1"><i class="bi bi-lock text-muted"></i></span>
                    <input type="password" name="password" id="password" class="form-control" placeholder="••••••••" required>
                </div>
            </div>

            <!-- Options -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remember_me" id="remember_me" value="1">
                    <label class="form-check-label small text-muted" for="remember_me">
                        <?php echo __('remember_me'); ?>
                    </label>
                </div>
                <a href="#" class="small text-gold text-decoration-none"><?php echo __('forgot_password'); ?></a>
            </div>

            <button type="submit" class="btn btn-souk-primary w-100 py-2.5 fs-5 rounded-pill mb-3">
                <i class="bi bi-box-arrow-in-left me-1"></i> <?php echo __('sign_in'); ?>
            </button>

            <div class="text-center">
                <span class="small text-muted"><?php echo __('no_account'); ?></span>
                <a href="<?php echo SITE_URL; ?>/register" class="small text-gold fw-bold text-decoration-none ms-1"><?php echo __('sign_up'); ?></a>
            </div>
        </form>
    </div>
</div>
