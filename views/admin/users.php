<div class="mb-4">
    <h3 class="fw-bold m-0"><i class="bi bi-people text-gold"></i> إدارة المستخدمين</h3>
    <small class="text-muted">مراقبة الحسابات وتغيير صلاحياتها أو حظرها من المنصة</small>
</div>

<!-- Alert messages -->
<?php if ($flashSuccess = \Core\Session::getFlash('success')): ?>
    <div class="alert alert-success border small py-2 mb-3">
        <?php echo $flashSuccess; ?>
    </div>
<?php endif; ?>

<div class="card border rounded-lg p-4 bg-white shadow-sm">
    <div class="table-responsive">
        <table class="table table-striped table-bordered align-middle small">
            <thead>
                <tr class="bg-light">
                    <th>المستخدم</th>
                    <th>اسم المستخدم</th>
                    <th>البريد الإلكتروني</th>
                    <th>الدور</th>
                    <th>الحالة</th>
                    <th style="width: 200px;">تعديل الحالة</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $u): ?>
                    <tr>
                        <td>
                            <strong class="text-dark d-block"><?php echo $u->full_name; ?></strong>
                            <small class="text-muted">انضم في: <?php echo date('Y/m/d', strtotime($u->created_at)); ?></small>
                        </td>
                        <td>@<?php echo $u->username; ?></td>
                        <td><?php echo $u->email; ?></td>
                        <td class="text-center"><span class="badge bg-gold-light text-gold border"><?php echo $u->role; ?></span></td>
                        <td class="text-center">
                            <span class="badge rounded-pill <?php echo ($u->status === 'active') ? 'bg-success' : 'bg-danger'; ?>">
                                <?php echo ($u->status === 'active') ? 'نشط' : 'موقوف'; ?>
                            </span>
                        </td>
                        <td>
                            <form action="<?php echo SITE_URL; ?>/admin/users/status" method="POST" class="d-flex gap-2">
                                <?php echo \Core\CSRF::field(); ?>
                                <input type="hidden" name="id" value="<?php echo $u->id; ?>">
                                <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                                    <option value="active" <?php echo ($u->status === 'active') ? 'selected' : ''; ?>>نشط</option>
                                    <option value="suspended" <?php echo ($u->status === 'suspended') ? 'selected' : ''; ?>>إيقاف مؤقت</option>
                                    <option value="banned" <?php echo ($u->status === 'banned') ? 'selected' : ''; ?>>حظر دائم</option>
                                </select>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
