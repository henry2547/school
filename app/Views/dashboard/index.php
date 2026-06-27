<div class="page-heading">
    <div>
        <p class="eyebrow">Overview</p>
        <h1>Dashboard</h1>
    </div>
    <div class="d-flex gap-2">
        <button class="btn btn-outline-secondary" type="button"><i class="bi bi-printer me-2"></i>Print</button>
        <button class="btn btn-primary" type="button"><i class="bi bi-download me-2"></i>Export</button>
    </div>
</div>

<section class="metric-grid">
    <?php
    $cards = [
        ['Students', $metrics['students'], 'bi-person-vcard', 'text-bg-primary'],
        ['Teachers', $metrics['teachers'], 'bi-person-workspace', 'text-bg-success'],
        ['Parents', $metrics['parents'], 'bi-people', 'text-bg-info'],
        ['Users', $metrics['users'], 'bi-shield-lock', 'text-bg-dark'],
        ['Fees Collected', 'KES ' . number_format($metrics['fees_collected']), 'bi-cash-stack', 'text-bg-warning'],
        ['Outstanding Fees', 'KES ' . number_format($metrics['outstanding_fees']), 'bi-receipt', 'text-bg-danger'],
        ['Attendance Rate', number_format($metrics['attendance_rate'], 1) . '%', 'bi-calendar-check', 'text-bg-success'],
        ['Library Books', $metrics['books'], 'bi-book', 'text-bg-secondary'],
    ];
    ?>
    <?php foreach ($cards as [$label, $value, $icon, $tone]): ?>
        <article class="metric-card">
            <span class="metric-icon <?= e($tone) ?>"><i class="bi <?= e($icon) ?>"></i></span>
            <p><?= e($label) ?></p>
            <strong><?= e($value) ?></strong>
        </article>
    <?php endforeach; ?>
</section>

<div class="row g-4 mt-1">
    <div class="col-xl-8">
        <section class="panel">
            <div class="panel-header">
                <div>
                    <h2>School Activity</h2>
                    <p>Fees, attendance, and population trend placeholders from seeded Phase 1 data.</p>
                </div>
            </div>
            <canvas id="dashboardChart" height="110"
                data-students="<?= e($metrics['students']) ?>"
                data-teachers="<?= e($metrics['teachers']) ?>"
                data-parents="<?= e($metrics['parents']) ?>"
                data-fees="<?= e((int)$metrics['fees_collected']) ?>"></canvas>
        </section>
    </div>
    <div class="col-xl-4">
        <section class="panel">
            <div class="panel-header">
                <div>
                    <h2>Recent Activity</h2>
                    <p>Security and admin events.</p>
                </div>
            </div>
            <div class="activity-list">
                <?php foreach ($activities as $activity): ?>
                    <div class="activity-item">
                        <span class="activity-dot"></span>
                        <div>
                            <strong><?= e(ucfirst($activity['action'])) ?> <?= e($activity['entity']) ?></strong>
                            <p><?= e($activity['user_name'] ?? 'System') ?> · <?= e(date('M j, H:i', strtotime($activity['created_at']))) ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
                <?php if ($activities === []): ?>
                    <p class="text-muted mb-0">No activity yet.</p>
                <?php endif; ?>
            </div>
        </section>
    </div>
</div>
