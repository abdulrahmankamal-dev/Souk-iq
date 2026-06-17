<div class="mb-4">
    <h3 class="fw-bold m-0"><i class="bi bi-people text-gold"></i> إدارة طاقم عمل المتجر</h3>
    <small class="text-muted">دعوة وإدارة صلاحيات المدراء والمحررين والمشرفين المساعدين لك في المتجر</small>
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
    <!-- List of staff (Col 8) -->
    <div class="col-lg-8">
        <div class="card border rounded-lg p-4 bg-white shadow-sm h-100">
            <h5 class="fw-bold text-dark mb-4 border-bottom pb-2">فريق العمل الحالي</h5>
            
            <?php if (empty($staff)): ?>
                <p class="text-muted text-center py-5">أنت فقط تدير هذا المتجر حالياً. يمكنك دعوة شركاء لمساعدتك.</p>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered align-middle small">
                        <thead>
                            <tr class="bg-light text-center">
                                <th>الاسم</th>
                                <th>البريد الإلكتروني</th>
                                <th>الدور الصلاحية</th>
                                <th>تاريخ الانضمام</th>
                                <th style="width: 100px;">إزالة</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($staff as $member): ?>
                                <tr>
                                    <td>
                                        <strong class="text-dark d-block"><?php echo $member->full_name; ?></strong>
                                    </td>
                                    <td><?php echo $member->email; ?></td>
                                    <td class="text-center">
                                        <span class="badge bg-gold-light text-gold border rounded-pill">
                                            <?php echo ($member->role === 'manager') ? 'مدير عام' : (($member->role === 'editor') ? 'محرر منتجات' : 'مشاهد فقط'); ?>
                                        </span>
                                    </td>
                                    <td class="text-center"><?php echo date('Y/m/d', strtotime($member->joined_at)); ?></td>
                                    <td class="text-center">
                                        <form action="<?php echo SITE_URL; ?>/store-owner/staff/delete" method="POST" onsubmit="return confirm('هل أنت متأكد من إلغاء تعيين وإزالة هذا الموظف؟');">
                                            <?php echo \Core\CSRF::field(); ?>
                                            <input type="hidden" name="id" value="<?php echo $member->id; ?>">
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="bi bi-person-x"></i> إزالة
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Invite Staff (Col 4) -->
    <div class="col-lg-4">
        <div class="card border rounded-lg p-4 bg-white shadow-sm h-100">
            <h5 class="fw-bold text-dark mb-4 border-bottom pb-2">دعوة موظف جديد</h5>
            
            <form action="<?php echo SITE_URL; ?>/store-owner/staff/invite" method="POST">
                <?php echo \Core\CSRF::field(); ?>

                <div class="mb-3">
                    <label for="email" class="form-label small fw-bold text-dark">البريد الإلكتروني للموظف *</label>
                    <input type="email" name="email" id="email" class="form-control" placeholder="user@domain.com" required>
                    <small class="text-muted d-block mt-1">ملاحظة: يجب أن يكون الموظف مسجلاً بالفعل بالمنصة.</small>
                </div>

                <div class="mb-4">
                    <label for="role" class="form-label small fw-bold text-dark">دور وصلاحية العمل</label>
                    <select name="role" id="role" class="form-select">
                        <option value="viewer">مشاهد فقط (لا يمكنه التعديل)</option>
                        <option value="editor">محرر منتجات (إضافة وتعديل المنتجات)</option>
                        <option value="manager">مدير مساعد (صلاحيات كاملة للمتجر)</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-souk-primary w-100 rounded-pill py-2.5">إرسال دعوة الانضمام</button>
            </form>
        </div>
    </div>
</div>
