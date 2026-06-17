<h3 class="fw-bold mb-4"><i class="bi bi-speedometer2 text-gold"></i> لوحة التحكم الإدارية</h3>

<!-- Stats Row -->
<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card border rounded-lg p-3 bg-white shadow-sm d-flex flex-row align-items-center gap-3">
            <div class="bg-primary-subtle text-primary rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 55px; height: 55px;">
                <i class="bi bi-people fs-3"></i>
            </div>
            <div>
                <h4 class="fw-bold text-dark m-0"><?php echo $usersCount; ?></h4>
                <small class="text-muted">المستخدمين</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border rounded-lg p-3 bg-white shadow-sm d-flex flex-row align-items-center gap-3">
            <div class="bg-success-subtle text-success rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 55px; height: 55px;">
                <i class="bi bi-shop fs-3"></i>
            </div>
            <div>
                <h4 class="fw-bold text-dark m-0"><?php echo $storesCount; ?></h4>
                <small class="text-muted">المتاجر المسجلة</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border rounded-lg p-3 bg-white shadow-sm d-flex flex-row align-items-center gap-3">
            <div class="bg-info-subtle text-info rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 55px; height: 55px;">
                <i class="bi bi-box-seam fs-3"></i>
            </div>
            <div>
                <h4 class="fw-bold text-dark m-0"><?php echo $productsCount; ?></h4>
                <small class="text-muted">المنتجات النشطة</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border rounded-lg p-3 bg-white shadow-sm d-flex flex-row align-items-center gap-3">
            <div class="bg-danger-subtle text-danger rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 55px; height: 55px;">
                <i class="bi bi-flag fs-3"></i>
            </div>
            <div>
                <h4 class="fw-bold text-dark m-0"><?php echo $pendingReports; ?></h4>
                <small class="text-muted">البلاغات المعلقة</small>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Pending Stores Approvals (Col 8) -->
    <div class="col-lg-8">
        <div class="card border rounded-lg p-4 bg-white shadow-sm h-100">
            <h5 class="fw-bold text-dark mb-4 border-bottom pb-2">طلبات توثيق المتاجر الجديدة</h5>
            
            <?php if (empty($pendingStores)): ?>
                <p class="text-muted text-center py-5">لا توجد طلبات توثيق معلقة حالياً.</p>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered align-middle small">
                        <thead>
                            <tr class="bg-light">
                                <th>اسم المتجر</th>
                                <th>المالك</th>
                                <th>الموقع</th>
                                <th style="width: 170px;">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($pendingStores as $store): ?>
                                <tr>
                                    <td class="fw-bold"><?php echo $store->name_ar; ?></td>
                                    <td><?php echo $store->owner_name; ?></td>
                                    <td><?php echo $store->governorate . ', ' . $store->city; ?></td>
                                    <td class="text-center">
                                        <div class="d-flex gap-2 justify-content-center">
                                            <form action="<?php echo SITE_URL; ?>/admin/stores/approve" method="POST">
                                                <?php echo \Core\CSRF::field(); ?>
                                                <input type="hidden" name="id" value="<?php echo $store->id; ?>">
                                                <button type="submit" class="btn btn-sm btn-success rounded-pill px-3">موافقة</button>
                                            </form>
                                            
                                            <!-- Simple Reject form -->
                                            <form action="<?php echo SITE_URL; ?>/admin/stores/reject" method="POST">
                                                <?php echo \Core\CSRF::field(); ?>
                                                <input type="hidden" name="id" value="<?php echo $store->id; ?>">
                                                <input type="hidden" name="rejection_reason" value="مستندات غير كافية">
                                                <button type="submit" class="btn btn-sm btn-danger rounded-pill px-3">رفض</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Latest Users list (Col 4) -->
    <div class="col-lg-4">
        <div class="card border rounded-lg p-4 bg-white shadow-sm h-100">
            <h5 class="fw-bold text-dark mb-4 border-bottom pb-2">المستخدمين المسجلين حديثاً</h5>
            <div class="d-flex flex-column gap-3">
                <?php foreach ($latestUsers as $u): ?>
                    <div class="d-flex align-items-center gap-2 small">
                        <img src="<?php echo $u->avatar ? SITE_URL . '/uploads/avatars/' . $u->avatar : 'https://placehold.co/40'; ?>" class="rounded-circle" width="40" height="40" alt="avatar">
                        <div>
                            <strong class="text-dark d-block"><?php echo $u->full_name; ?></strong>
                            <small class="text-muted">@<?php echo $u->username; ?> &mdash; <?php echo $u->role; ?></small>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
