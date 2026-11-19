<?php
$profile = $portal['profile'];
$outstanding = array_sum(array_map(
    static fn ($child) => array_sum(array_map(static fn ($invoice) => (float)$invoice['balance'], $child['invoices'])),
    $portal['children']
));
$attendanceCount = array_sum(array_map(static fn ($child) => count($child['attendance']), $portal['children']));
$resultsCount = array_sum(array_map(static fn ($child) => count($child['results']), $portal['children']));
?>
<div class="page-heading student-heading">
    <div>
        <p class="eyebrow">Parent Portal</p>
        <h1>Welcome, <?= e($profile['first_name']) ?></h1>
        <p class="text-muted mb-0"><?= e($profile['relationship']) ?> · <?= e($profile['phone']) ?></p>
    </div>
    <button class="btn btn-outline-secondary" type="button" onclick="window.print()"><i class="bi bi-printer me-2"></i>Print</button>
</div>

<nav class="portal-nav mb-4" aria-label="Parent portal sections">
    <a href="#profile">My Profile</a><a href="#children">My Children</a><a href="#school">School Updates</a>
</nav>

<section class="metric-grid student-metrics">
    <article class="metric-card"><span class="metric-icon text-bg-primary"><i class="bi bi-people"></i></span><p>Linked Children</p><strong><?= e(count($portal['children'])) ?></strong></article>
    <article class="metric-card"><span class="metric-icon text-bg-danger"><i class="bi bi-receipt"></i></span><p>Outstanding Fees</p><strong>KES <?= e(number_format($outstanding, 2)) ?></strong></article>
    <article class="metric-card"><span class="metric-icon text-bg-success"><i class="bi bi-calendar-check"></i></span><p>Attendance Records</p><strong><?= e($attendanceCount) ?></strong></article>
    <article class="metric-card"><span class="metric-icon text-bg-info"><i class="bi bi-clipboard-data"></i></span><p>Results Available</p><strong><?= e($resultsCount) ?></strong></article>
</section>

<div class="row g-4 mt-1">
    <div class="col-12">
        <section id="profile" class="panel">
            <div class="panel-header"><div><h2>My Profile</h2><p>Your parent and contact details.</p></div></div>
            <dl class="detail-grid mb-0">
                <div><dt>Full Name</dt><dd><?= e($profile['first_name'] . ' ' . $profile['last_name']) ?></dd></div>
                <div><dt>Relationship</dt><dd><?= e($profile['relationship']) ?></dd></div>
                <div><dt>Email</dt><dd><?= e($profile['email'] ?: 'Not recorded') ?></dd></div>
                <div><dt>Phone</dt><dd><?= e($profile['phone']) ?></dd></div>
                <div><dt>Address</dt><dd><?= e($profile['address'] ?: 'Not recorded') ?></dd></div>
            </dl>
        </section>
    </div>

    <div class="col-12">
        <section id="children" class="panel">
            <div class="panel-header"><div><h2>My Children</h2><p>Only children linked to your parent account are shown.</p></div></div>
            <?php if ($portal['children'] === []): ?>
                <p class="text-muted mb-0">No student records are linked to your account yet. Please contact the school office.</p>
            <?php else: ?>
                <div class="row g-4">
                    <?php foreach ($portal['children'] as $child): ?>
                        <div class="col-12">
                            <article class="panel border">
                                <div class="panel-header">
                                    <div>
                                        <h2><?= e($child['first_name'] . ' ' . $child['last_name']) ?></h2>
                                        <p><?= e($child['admission_no']) ?> · <?= e($child['class_name'] ?? 'Class pending') ?> · <?= e(ucfirst($child['status'])) ?></p>
                                    </div>
                                </div>
                                <div class="row g-4">
                                    <div class="col-xl-6">
                                        <h3 class="h6">Subjects & Teachers</h3>
                                        <div class="table-responsive">
                                            <table class="table table-sm align-middle">
                                                <thead><tr><th>Subject</th><th>Code</th><th>Teacher</th></tr></thead>
                                                <tbody>
                                                <?php foreach ($child['subjects'] as $subject): ?>
                                                    <tr><td><?= e($subject['subject_name']) ?></td><td><?= e($subject['code']) ?></td><td><?= e($subject['teacher_name']) ?></td></tr>
                                                <?php endforeach; ?>
                                                <?php if ($child['subjects'] === []): ?><tr><td colspan="3" class="text-muted">No subjects are assigned yet.</td></tr><?php endif; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="col-xl-6">
                                        <h3 class="h6">Exam Schedule</h3>
                                        <div class="table-responsive">
                                            <table class="table table-sm align-middle">
                                                <thead><tr><th>Exam</th><th>Dates</th><th>Status</th></tr></thead>
                                                <tbody>
                                                <?php foreach ($child['exams'] as $exam): ?>
                                                    <tr><td><?= e($exam['name']) ?></td><td><?= e($exam['starts_on']) ?> – <?= e($exam['ends_on']) ?></td><td><span class="badge text-bg-<?= $exam['status'] === 'published' ? 'success' : 'primary' ?>"><?= e(ucfirst($exam['status'])) ?></span></td></tr>
                                                <?php endforeach; ?>
                                                <?php if ($child['exams'] === []): ?><tr><td colspan="3" class="text-muted">No exams have been announced.</td></tr><?php endif; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <h3 class="h6">Fees</h3>
                                        <div class="table-responsive"><table class="table table-sm align-middle">
                                            <thead><tr><th>Invoice</th><th>Due</th><th>Balance</th></tr></thead>
                                            <tbody>
                                            <?php foreach ($child['invoices'] as $invoice): ?>
                                                <tr><td><?= e($invoice['invoice_no']) ?></td><td><?= e($invoice['due_date']) ?></td><td>KES <?= e(number_format((float)$invoice['balance'], 2)) ?></td></tr>
                                            <?php endforeach; ?>
                                            <?php if ($child['invoices'] === []): ?><tr><td colspan="3" class="text-muted">No invoices recorded.</td></tr><?php endif; ?>
                                            </tbody>
                                        </table></div>
                                    </div>
                                    <div class="col-lg-4">
                                        <h3 class="h6">Recent Attendance</h3>
                                        <div class="table-responsive"><table class="table table-sm align-middle">
                                            <thead><tr><th>Date</th><th>Status</th></tr></thead>
                                            <tbody>
                                            <?php foreach ($child['attendance'] as $record): ?>
                                                <tr><td><?= e($record['attendance_date']) ?></td><td><?= e(ucfirst($record['status'])) ?></td></tr>
                                            <?php endforeach; ?>
                                            <?php if ($child['attendance'] === []): ?><tr><td colspan="2" class="text-muted">No attendance records.</td></tr><?php endif; ?>
                                            </tbody>
                                        </table></div>
                                    </div>
                                    <div class="col-lg-4">
                                        <h3 class="h6">Exam Results</h3>
                                        <div class="table-responsive"><table class="table table-sm align-middle">
                                            <thead><tr><th>Exam</th><th>Subject</th><th>Mark</th><th>Grade</th><th>Teacher Feedback</th></tr></thead>
                                            <tbody>
                                            <?php foreach ($child['results'] as $result): ?>
                                                <tr><td><?= e($result['exam_name']) ?></td><td><?= e($result['subject_name']) ?></td><td><?= e($result['marks']) ?></td><td><span class="badge text-bg-primary"><?= e($result['grade']) ?></span></td><td><?= e($result['remarks'] ?: '—') ?></td></tr>
                                            <?php endforeach; ?>
                                            <?php if ($child['results'] === []): ?><tr><td colspan="5" class="text-muted">No results have been recorded.</td></tr><?php endif; ?>
                                            </tbody>
                                        </table></div>
                                    </div>
                                </div>
                            </article>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>
    </div>

    <div class="col-lg-6">
        <section id="school" class="panel h-100">
            <div class="panel-header"><div><h2>School Notices</h2><p>Updates for parents and your children’s classes.</p></div></div>
            <div class="activity-list">
                <?php foreach ($portal['announcements'] as $announcement): ?>
                    <div class="activity-item"><span class="activity-dot"></span><div>
                        <strong><?= e($announcement['title']) ?></strong>
                        <p class="mb-1"><?= e($announcement['body']) ?></p>
                        <small class="text-muted"><?= e($announcement['sender_name']) ?><?= $announcement['class_name'] ? ' · ' . e($announcement['class_name'] . ' ' . $announcement['stream']) : '' ?> · <?= e(date('M j, Y H:i', strtotime($announcement['published_at']))) ?></small>
                    </div></div>
                <?php endforeach; ?>
                <?php if ($portal['announcements'] === []): ?><p class="text-muted mb-0">No notices right now.</p><?php endif; ?>
            </div>
        </section>
    </div>
    <div class="col-lg-6">
        <section class="panel h-100">
            <div class="panel-header"><div><h2>Upcoming Events</h2><p>School calendar highlights.</p></div></div>
            <div class="activity-list">
                <?php foreach ($portal['events'] as $event): ?>
                    <div class="activity-item"><span class="activity-dot"></span><div>
                        <strong><?= e($event['title']) ?></strong>
                        <p><?= e(date('M j, Y H:i', strtotime($event['starts_at']))) ?><?= $event['location'] ? ' · ' . e($event['location']) : '' ?></p>
                    </div></div>
                <?php endforeach; ?>
                <?php if ($portal['events'] === []): ?><p class="text-muted mb-0">No upcoming events.</p><?php endif; ?>
            </div>
        </section>
    </div>
</div>
