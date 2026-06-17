<div class="mb-4">
    <h3 class="fw-bold m-0 text-danger"><i class="bi bi-person-badge"></i> إدارة وتعيين المشرفين</h3>
    <small class="text-muted">دعوة وتعيين مشرفين جدد للمنصة وإدارة صلاحياتهم</small>
</div>

<!-- Alert messages -->
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
    <!-- Admins List (Col 7) -->
    <div class="col-lg-7">
        <div class="card border rounded-lg p-4 bg-white shadow-sm h-100">
            <h5 class="fw-bold text-dark mb-4 border-bottom pb-2">المشرفين العامين الحاليين</h5>
            
            <div class="table-responsive">
                <table class="table table-striped table-bordered align-middle small">
                    <thead>
                        <tr class="bg-light">
                            <th>الاسم الكامل</th>
                            <th>اسم المستخدم</th>
                            <th>البريد الإلكتروني</th>
                            <th>الدور</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($admins as $ad): ?>
                            <tr>
                                <td><strong class="text-dark"><?php echo $ad->full_name; ?></strong></td>
                                <td>@<?php echo $ad->username; ?></td>
                                <td><?php echo $ad->email; ?></td>
                                <td class="text-center">
                                    <span class="badge rounded-pill <?php echo ($ad->role === 'super_admin') ? 'bg-danger text-white' : 'bg-warning text-dark'; ?>">
                                        <?php echo ($ad->role === 'super_admin') ? 'مدير عام' : 'مشرف مساعد'; ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Create Admin Form (Col 5) -->
    <div class="col-lg-5">
        <div class="card border rounded-lg p-4 bg-white shadow-sm h-100">
            <h5 class="fw-bold text-dark mb-4 border-bottom pb-2">تعيين مشرف مساعد جديد</h5>
            
            <form action="<?php echo SITE_URL; ?>/super-admin/admins/create" method="POST">
                <?php echo \Core\CSRF::field(); ?>

                <div class="mb-3">
                    <label for="full_name" class="form-label small fw-bold text-dark">الاسم الكامل *</label>
                    <input type="text" name="full_name" id="full_name" class="form-control form-control-sm" required>
                </div>

                <div class="mb-3">
                    <label for="username" class="form-label small fw-bold text-dark">اسم المستخدم *</label>
                    <input type="text" name="username" id="username" class="form-control form-control-sm" placeholder="admin_user" required>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label small fw-bold text-dark">البريد الإلكتروني *</label>
                    <input type="email" name="email" id="email" class="form-control form-control-sm" required>
                </div>

                <div class="mb-4">
                    <label for="password" class="form-label small fw-bold text-dark">كلمة المرور *</label>
                    <input type="password" name="password" id="password" class="form-control form-control-sm" placeholder="••••••••" required>
                </div>

                <button type="submit" class="btn btn-danger w-100 rounded-pill py-2.5">إنشاء حساب المشرف</button>
            </form>
        </div>
    </div>
</div>
