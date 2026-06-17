<h3 class="fw-bold mb-4 text-danger"><i class="bi bi-shield-shaded"></i> الإدارة العليا (Super Admin)</h3>

<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card border rounded-lg p-3 bg-white shadow-sm d-flex flex-row align-items-center gap-3">
            <div class="bg-danger-subtle text-danger rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 55px; height: 55px;">
                <i class="bi bi-person-badge fs-3"></i>
            </div>
            <div>
                <h4 class="fw-bold text-dark m-0"><?php echo $adminsCount; ?></h4>
                <small class="text-muted">المشرفين العامين</small>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border rounded-lg p-3 bg-white shadow-sm d-flex flex-row align-items-center gap-3">
            <div class="bg-success-subtle text-success rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 55px; height: 55px;">
                <i class="bi bi-credit-card fs-3"></i>
            </div>
            <div>
                <h4 class="fw-bold text-dark m-0"><?php echo $plansCount; ?></h4>
                <small class="text-muted">باقات العضوية</small>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border rounded-lg p-3 bg-white shadow-sm d-flex flex-row align-items-center gap-3">
            <div class="bg-info-subtle text-info rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 55px; height: 55px;">
                <i class="bi bi-file-earmark-medical fs-3"></i>
            </div>
            <div>
                <h4 class="fw-bold text-dark m-0"><?php echo $auditLogsCount; ?></h4>
                <small class="text-muted">سجل العمليات المعالجة</small>
            </div>
        </div>
    </div>
</div>

<div class="card border rounded-lg p-4 bg-white shadow-sm">
    <h5 class="fw-bold text-dark mb-3">صلاحيات المدير العام الحصرية:</h5>
    <div class="row g-3">
        <div class="col-md-4">
            <a href="<?php echo SITE_URL; ?>/super-admin/admins" class="btn btn-outline-danger w-100 py-3 rounded-lg text-center d-block">
                <i class="bi bi-person-badge fs-3 d-block mb-1 text-danger"></i>
                إدارة وتعيين المشرفين
            </a>
        </div>
        <div class="col-md-4">
            <a href="<?php echo SITE_URL; ?>/super-admin/plans" class="btn btn-outline-danger w-100 py-3 rounded-lg text-center d-block">
                <i class="bi bi-credit-card fs-3 d-block mb-1 text-danger"></i>
                إدارة باقات اشتراك المتاجر
            </a>
        </div>
        <div class="col-md-4">
            <a href="<?php echo SITE_URL; ?>/super-admin/audit-logs" class="btn btn-outline-danger w-100 py-3 rounded-lg text-center d-block">
                <i class="bi bi-file-earmark-medical fs-3 d-block mb-1 text-danger"></i>
                سجل العمليات الأمنية (Audit)
            </a>
        </div>
    </div>
</div>
