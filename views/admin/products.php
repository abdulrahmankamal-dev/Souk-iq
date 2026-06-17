<div class="mb-4">
    <h3 class="fw-bold m-0"><i class="bi bi-box-seam text-gold"></i> مراقبة المنتجات والمعروضات</h3>
    <small class="text-muted">مراقبة المنتجات المعروضة من كافة المحلات وحذف أو إيقاف المخالف منها</small>
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
                    <th>المنتج</th>
                    <th>المحل</th>
                    <th>السعر</th>
                    <th>الحالة</th>
                    <th style="width: 220px;" class="text-center">تعديل الحالة</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $prod): ?>
                    <tr>
                        <td>
                            <strong class="text-dark d-block"><?php echo $prod->name_ar; ?></strong>
                            <small class="text-muted">الرابط: <?php echo $prod->slug; ?></small>
                        </td>
                        <td><?php echo $prod->store_name; ?></td>
                        <td><?php echo number_format($prod->price); ?> د.ع</td>
                        <td class="text-center">
                            <span class="badge rounded-pill <?php echo ($prod->status === 'active') ? 'bg-success' : (($prod->status === 'pending') ? 'bg-warning' : 'bg-danger'); ?>">
                                <?php echo ($prod->status === 'active') ? 'نشط وموافق عليه' : (($prod->status === 'pending') ? 'قيد الانتظار' : 'مخفي / مرفوض'); ?>
                            </span>
                        </td>
                        <td>
                            <form action="<?php echo SITE_URL; ?>/admin/products/status" method="POST" class="d-flex gap-2">
                                <?php echo \Core\CSRF::field(); ?>
                                <input type="hidden" name="id" value="<?php echo $prod->id; ?>">
                                <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                                    <option value="active" <?php echo ($prod->status === 'active') ? 'selected' : ''; ?>>نشط ومقبول</option>
                                    <option value="pending" <?php echo ($prod->status === 'pending') ? 'selected' : ''; ?>>معلق للمراجعة</option>
                                    <option value="rejected" <?php echo ($prod->status === 'rejected') ? 'selected' : ''; ?>>مرفوض ومخفي</option>
                                    <option value="archived" <?php echo ($prod->status === 'archived') ? 'selected' : ''; ?>>مؤرشف</option>
                                </select>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
