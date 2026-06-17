<h3 class="fw-bold mb-4"><i class="bi bi-bell text-gold"></i> <?php echo __('notifications'); ?></h3>

<?php if (empty($notifications)): ?>
    <div class="card border rounded-lg p-5 text-center bg-white shadow-sm">
        <i class="bi bi-bell-slash text-muted display-1 d-block mb-3"></i>
        <h5 class="fw-bold"><?php echo __('no_notifications', [], 'علبة الإشعارات فارغة'); ?></h5>
        <p class="text-muted mb-0"><?php echo __('no_notifications_desc', [], 'لا توجد أي إشعارات جديدة لديك حالياً.'); ?></p>
    </div>
<?php else: ?>
    <div class="d-flex flex-column gap-2">
        <?php foreach ($notifications as $notif): ?>
            <div class="card border rounded-lg p-3 bg-white shadow-sm d-flex flex-row align-items-center gap-3 <?php echo !$notif->is_read ? 'border-start border-4 border-gold bg-light-gold' : ''; ?>">
                <div class="rounded-circle p-2 bg-light text-dark d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                    <i class="bi <?php echo $notif->icon ? $notif->icon : 'bi-bell-fill'; ?> text-gold fs-4"></i>
                </div>
                <div class="flex-grow-1">
                    <h6 class="fw-bold mb-1 text-dark"><?php echo $notif->title; ?></h6>
                    <p class="text-secondary small mb-0"><?php echo $notif->body; ?></p>
                    <small class="text-muted d-block mt-1" style="font-size: 0.7rem;"><?php echo date('Y/m/d H:i', strtotime($notif->created_at)); ?></small>
                </div>
                <?php if ($notif->link): ?>
                    <a href="<?php echo SITE_URL . $notif->link; ?>" class="btn btn-sm btn-outline-warning rounded-pill px-3"><?php echo __('view_details', [], 'عرض التفاصيل'); ?></a>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
