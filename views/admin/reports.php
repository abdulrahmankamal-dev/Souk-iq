<div class="mb-4">
    <h3 class="fw-bold m-0"><i class="bi bi-flag text-gold"></i> الشكاوى وبلاغات الإساءة</h3>
    <small class="text-muted">التحقيق في شكاوى الزبائن وتصنيف البلاغات ضد المتاجر أو المعروضات المزيفة</small>
</div>

<!-- Alert messages -->
<?php if ($flashSuccess = \Core\Session::getFlash('success')): ?>
    <div class="alert alert-success border small py-2 mb-3">
        <?php echo $flashSuccess; ?>
    </div>
<?php endif; ?>

<div class="card border rounded-lg p-4 bg-white shadow-sm">
    <?php if (empty($reports)): ?>
        <p class="text-muted text-center py-5">لا توجد أي بلاغات مسجلة بالمنصة حالياً.</p>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-striped table-bordered align-middle small">
                <thead>
                    <tr class="bg-light">
                        <th>المُبْلِغ</th>
                        <th>نوع الهدف</th>
                        <th>رقم المعرف للهدف</th>
                        <th>السبب</th>
                        <th>التفاصيل</th>
                        <th>الحالة</th>
                        <th style="width: 280px;" class="text-center">إجراء المعالجة</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($reports as $rep): ?>
                        <tr>
                            <td><?php echo $rep->reporter_name; ?></td>
                            <td><span class="badge bg-secondary"><?php echo $rep->target_type; ?></span></td>
                            <td class="text-center">#<?php echo $rep->target_id; ?></td>
                            <td><strong class="text-danger"><?php echo $rep->reason; ?></strong></td>
                            <td><?php echo htmlspecialchars($rep->details ?? ''); ?></td>
                            <td class="text-center">
                                <span class="badge rounded-pill <?php echo ($rep->status === 'resolved') ? 'bg-success' : (($rep->status === 'pending') ? 'bg-warning' : 'bg-secondary'); ?>">
                                    <?php echo ($rep->status === 'resolved') ? 'تمت المعالجة' : (($rep->status === 'pending') ? 'معلق للتحقيق' : 'مرفوض'); ?>
                                </span>
                            </td>
                            <td>
                                <?php if ($rep->status === 'pending'): ?>
                                    <form action="<?php echo SITE_URL; ?>/admin/reports/resolve" method="POST" class="d-flex gap-2">
                                        <?php echo \Core\CSRF::field(); ?>
                                        <input type="hidden" name="id" value="<?php echo $rep->id; ?>">
                                        <input type="text" name="resolution_note" class="form-control form-control-sm" placeholder="ملاحظة القرار..." required>
                                        <select name="status" class="form-select form-select-sm" style="width: 110px;" onchange="this.form.submit()">
                                            <option value="">اختر...</option>
                                            <option value="resolved">حل ومعالجة</option>
                                            <option value="dismissed">رفض البلاغ</option>
                                        </select>
                                    </form>
                                <?php else: ?>
                                    <small class="text-muted d-block text-center"><?php echo $rep->resolution_note; ?></small>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
