<div class="container py-5 my-5">
    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8">
            <div class="card border shadow-lg rounded-lg overflow-hidden">
                <div class="card-header bg-dark text-white p-4 text-center">
                    <h3 class="fw-bold m-0"><i class="bi bi-gear-wide-connected text-gold me-2"></i> تثبيت قاعدة بيانات سوق.IQ</h3>
                </div>
                <div class="card-body p-4 text-center">
                    <p class="text-muted mb-4">
                        يرجى النقر على الزر أدناه لإنشاء الجداول اللازمة واستيراد سجلات التهيئة والبيانات الافتراضية (Seeding) لبدء تشغيل منصة سوق.IQ بنجاح.
                    </p>
                    
                    <div class="alert alert-info border d-flex align-items-center gap-3 text-start small mb-4">
                        <i class="bi bi-info-circle-fill text-info fs-3"></i>
                        <div>
                            سيقوم هذا الإجراء بتشغيل ملفات الإعداد <code class="text-danger">schema.sql</code> و <code class="text-danger">seeds.sql</code>.
                            سيكون الحساب الافتراضي للمشرف العام: <strong>superadmin</strong> مع كلمة المرور: <strong>Password123</strong>
                        </div>
                    </div>

                    <form action="<?php echo SITE_URL; ?>/install" method="POST">
                        <button type="submit" class="btn btn-souk-primary px-5 py-3 rounded-pill fs-5 w-100">
                            <i class="bi bi-play-circle-fill me-1"></i> البدء في تشغيل التهيئة والتثبيت
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
