<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold m-0"><i class="bi bi-box-seam text-gold"></i> إدارة منتجات المتجر</h3>
    <a href="<?php echo SITE_URL; ?>/store-owner/products/create" class="btn btn-souk-primary rounded-pill btn-sm px-4">
        <i class="bi bi-plus-circle-fill me-1"></i> إضافة منتج جديد
    </a>
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

<div class="card border rounded-lg p-4 bg-white shadow-sm">
    <?php if (empty($products)): ?>
        <p class="text-muted text-center py-5">لا توجد منتجات مضافة لهذا المتجر بعد. انقر على الزر أعلاه لإضافة منتجك الأول!</p>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-striped table-bordered align-middle small">
                <thead>
                    <tr class="bg-light text-center">
                        <th style="width: 80px;">الصورة</th>
                        <th>اسم المنتج</th>
                        <th>السعر</th>
                        <th>التصنيف</th>
                        <th>الحالة</th>
                        <th>المخزون</th>
                        <th style="width: 180px;">العمليات</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $prod): ?>
                        <tr>
                            <td class="text-center">
                                <img src="<?php echo $prod->thumbnail ? SITE_URL . '/uploads/products/' . $prod->thumbnail : 'https://placehold.co/50'; ?>" 
                                     alt="thumb" class="rounded object-cover" width="50" height="50">
                            </td>
                            <td>
                                <strong class="text-dark d-block"><?php echo $prod->name_ar; ?></strong>
                                <?php if ($prod->brand): ?>
                                    <small class="text-muted">الماركة: <?php echo $prod->brand; ?></small>
                                <?php endif; ?>
                            </td>
                            <td class="fw-bold text-center text-nowrap">
                                <?php if ($prod->discount_price): ?>
                                    <span class="text-danger"><?php echo number_format($prod->discount_price); ?> د.ع</span>
                                    <small class="text-muted text-decoration-line-through d-block"><?php echo number_format($prod->price); ?></small>
                                <?php else: ?>
                                    <span><?php echo number_format($prod->price); ?> د.ع</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo $prod->cat_name; ?></td>
                            <td class="text-center">
                                <span class="badge rounded-pill <?php echo ($prod->condition_type === 'new') ? 'bg-success-subtle text-success' : 'bg-warning-subtle text-warning'; ?>">
                                    <?php echo __($prod->condition_type === 'new' ? 'cond_new' : ($prod->condition_type === 'used' ? 'cond_used' : 'cond_refurbished')); ?>
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="badge rounded-pill <?php echo ($prod->stock_status === 'in_stock') ? 'bg-success' : (($prod->stock_status === 'limited') ? 'bg-warning' : 'bg-danger'); ?>">
                                    <?php echo __($prod->stock_status === 'in_stock' ? 'stock_in' : ($prod->stock_status === 'limited' ? 'stock_limited' : 'stock_out')); ?>
                                </span>
                            </td>
                            <td class="text-center">
                                <div class="d-flex gap-2 justify-content-center">
                                    <a href="<?php echo SITE_URL; ?>/store-owner/products/edit/<?php echo $prod->id; ?>" class="btn btn-sm btn-outline-warning">
                                        <i class="bi bi-pencil"></i> تعديل
                                    </a>
                                    
                                    <form action="<?php echo SITE_URL; ?>/store-owner/products/delete" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذا المنتج؟');">
                                        <?php echo \Core\CSRF::field(); ?>
                                        <input type="hidden" name="id" value="<?php echo $prod->id; ?>">
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-trash"></i> حذف
                                        </button>
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
