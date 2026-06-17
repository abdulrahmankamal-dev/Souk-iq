<div class="mb-4">
    <h3 class="fw-bold m-0 text-danger"><i class="bi bi-file-earmark-medical"></i> سجلات العمليات والنظام (Audit Logs)</h3>
    <small class="text-muted">مراقبة وتسجيل كافة العمليات والأنشطة الأمنية الحساسة التي ينفذها المشرفون والأعضاء</small>
</div>

<div class="card border rounded-lg p-4 bg-white shadow-sm">
    <div class="table-responsive">
        <table class="table table-striped table-bordered align-middle small">
            <thead>
                <tr class="bg-light">
                    <th style="width: 80px;">رقم المعرف</th>
                    <th>المستخدم</th>
                    <th>العملية المنفذة</th>
                    <th>نوع الهدف</th>
                    <th>عنوان IP</th>
                    <th>التاريخ والتوقيت</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($logs)): ?>
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">سجل العمليات فارغ حالياً.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($logs as $log): ?>
                        <tr>
                            <td class="text-center">#<?php echo $log->id; ?></td>
                            <td><strong class="text-dark">@<?php echo $log->username ? $log->username : 'زائر / غير معروف'; ?></strong></td>
                            <td><code><?php echo $log->action; ?></code></td>
                            <td><?php echo $log->target_type; ?></td>
                            <td class="text-center"><?php echo $log->ip_address; ?></td>
                            <td class="text-center"><?php echo date('Y/m/d H:i:s', strtotime($log->created_at)); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
