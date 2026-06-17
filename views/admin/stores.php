<div class="mb-4">
    <h3 class="fw-bold m-0"><i class="bi bi-shop text-gold"></i> إدارة وتوثيق المتاجر</h3>
    <small class="text-muted">مراجعة طلبات الانضمام والموافقة على توثيق المتاجر</small>
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
                    <th>اسم المتجر</th>
                    <th>المالك</th>
                    <th>الموقع</th>
                    <th>الهاتف</th>
                    <th>حالة المتجر</th>
                    <th style="width: 280px;" class="text-center">العمليات الإدارية</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($stores as $store): ?>
                    <tr>
                        <td>
                            <strong class="text-dark d-block"><?php echo $store->name_ar; ?></strong>
                            <small class="text-muted">المشاهدات: <?php echo $store->views_count; ?></small>
                        </td>
                        <td><?php echo $store->owner_name; ?></td>
                        <td><?php echo $store->governorate . ', ' . $store->city; ?></td>
                        <td><?php echo $store->phone; ?></td>
                        <td class="text-center">
                            <span class="badge rounded-pill <?php echo ($store->status === 'active') ? 'bg-success' : (($store->status === 'pending') ? 'bg-warning' : 'bg-danger'); ?>">
                                <?php echo ($store->status === 'active') ? 'نشط' : (($store->status === 'pending') ? 'معلق' : 'موقوف / مرفوض'); ?>
                            </span>
                        </td>
                        <td class="text-center">
                            <div class="d-flex gap-2 justify-content-center">
                                <?php if ($store->status === 'pending'): ?>
                                    <form action="<?php echo SITE_URL; ?>/admin/stores/approve" method="POST">
                                        <?php echo \Core\CSRF::field(); ?>
                                        <input type="hidden" name="id" value="<?php echo $store->id; ?>">
                                        <button type="submit" class="btn btn-sm btn-success rounded-pill px-3">موافقة وتوثيق</button>
                                    </form>
                                    
                                    <button class="btn btn-sm btn-danger rounded-pill px-3" data-bs-toggle="collapse" data-bs-target="#reject-form-<?php echo $store->id; ?>">رفض</button>
                                <?php else: ?>
                                    <form action="<?php echo SITE_URL; ?>/admin/stores/suspend" method="POST">
                                        <?php echo \Core\CSRF::field(); ?>
                                        <input type="hidden" name="id" value="<?php echo $store->id; ?>">
                                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-3">
                                            <?php echo ($store->status === 'suspended') ? 'إلغاء الإيقاف' : 'إيقاف المتجر'; ?>
                                        </button>
                                    </form>
                                <?php endif; ?>
                            </div>

                            <!-- Collapse rejection reason -->
                            <div class="collapse mt-2" id="reject-form-<?php echo $store->id; ?>">
                                <form action="<?php echo SITE_URL; ?>/admin/stores/reject" method="POST" class="p-2 border rounded bg-light">
                                    <?php echo \Core\CSRF::field(); ?>
                                    <input type="hidden" name="id" value="<?php echo $store->id; ?>">
                                    <input type="text" name="rejection_reason" class="form-control form-control-sm mb-2" placeholder="أدخل سبب الرفض..." required>
                                    <button type="submit" class="btn btn-sm btn-danger w-100 rounded-pill">تأكيد الرفض</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
