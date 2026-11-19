<?php $profile = $portal['profile']; ?>
<div class="page-heading student-heading">
    <div>
        <p class="eyebrow">Teacher Portal</p>
        <h1><?= e($profile['first_name'] . ' ' . $profile['last_name']) ?></h1>
        <p class="text-muted mb-0"><?= e($profile['staff_no']) ?> · <?= e($profile['department_name'] ?? 'Department pending') ?></p>
    </div>
    <button class="btn btn-outline-secondary" type="button" onclick="window.print()"><i class="bi bi-printer me-2"></i>Print</button>
</div>
<nav class="portal-nav mb-4" aria-label="Teacher dashboard sections">
    <a href="#teaching">Teaching</a><a href="#learners">Learners</a><a href="#assessments">Exams & CATs</a><a href="#assignments">Assignments</a><a href="#mark-entry">Enter Marks</a><a href="#school">Notices</a>
</nav>
<section class="metric-grid student-metrics">
    <article class="metric-card"><span class="metric-icon text-bg-primary"><i class="bi bi-journal-bookmark"></i></span><p>Subject Assignments</p><strong><?= e(count($portal['assignments'])) ?></strong></article>
    <article class="metric-card"><span class="metric-icon text-bg-success"><i class="bi bi-diagram-3"></i></span><p>Class Teacher Rooms</p><strong><?= e(count($portal['classrooms'])) ?></strong></article>
    <article class="metric-card"><span class="metric-icon text-bg-info"><i class="bi bi-people"></i></span><p>My Learners</p><strong><?= e(count($portal['students'])) ?></strong></article>
    <article class="metric-card"><span class="metric-icon text-bg-warning"><i class="bi bi-clipboard-data"></i></span><p>Marks Entered</p><strong><?= e(count($portal['results'])) ?></strong></article>
</section>

<div class="row g-4 mt-1">
    <div class="col-xl-7">
        <section id="teaching" class="panel h-100">
            <div class="panel-header"><div><h2>Teaching Assignments</h2><p>Only your assigned subjects and classes are available for assessment creation.</p></div></div>
            <div class="table-responsive"><table class="table align-middle">
                <thead><tr><th>Subject</th><th>Code</th><th>Class</th></tr></thead>
                <tbody>
                <?php foreach ($portal['assignments'] as $assignment): ?>
                    <tr><td><?= e($assignment['subject_name']) ?></td><td><?= e($assignment['code']) ?></td><td><?= e($assignment['class_name'] . ' ' . $assignment['stream']) ?></td></tr>
                <?php endforeach; ?>
                <?php if ($portal['assignments'] === []): ?><tr><td colspan="3" class="text-muted">No subject assignments found. Contact the school office to update your timetable.</td></tr><?php endif; ?>
                </tbody>
            </table></div>
            <h3 class="h6 mt-4">Class Teacher Rooms</h3>
            <div class="service-list">
                <?php foreach ($portal['classrooms'] as $classroom): ?><div><strong><?= e($classroom['name'] . ' ' . $classroom['stream']) ?></strong><span><?= e($classroom['student_count']) ?> active learners</span></div><?php endforeach; ?>
                <?php if ($portal['classrooms'] === []): ?><p class="text-muted mb-0">No class teacher assignment.</p><?php endif; ?>
            </div>
        </section>
    </div>
    <div class="col-xl-5">
        <section id="learners" class="panel h-100">
            <div class="panel-header"><div><h2>My Learners</h2><p>Students in your assigned subject classes.</p></div></div>
            <div class="table-responsive"><table class="table table-sm align-middle">
                <thead><tr><th>Admission No</th><th>Student</th><th>Class</th></tr></thead>
                <tbody>
                <?php foreach ($portal['students'] as $student): ?>
                    <tr><td><?= e($student['admission_no']) ?></td><td><?= e($student['first_name'] . ' ' . $student['last_name']) ?></td><td><?= e($student['class_name']) ?></td></tr>
                <?php endforeach; ?>
                <?php if ($portal['students'] === []): ?><tr><td colspan="3" class="text-muted">No active learners assigned.</td></tr><?php endif; ?>
                </tbody>
            </table></div>
        </section>
    </div>

    <div class="col-12">
        <section id="assessments" class="panel">
            <div class="panel-header"><div><h2>Exams & CATs</h2><p>Publish a class-specific assessment schedule. Learners and their linked parents can view it in their portals.</p></div></div>
            <?php if ($portal['assessment_options'] === []): ?>
                <p class="text-muted mb-0">You need an assigned subject and class before creating an assessment.</p>
            <?php else: ?>
                <form action="<?= e(url('/teacher-portal/exam')) ?>" method="post" class="row g-3 mb-4">
                    <?= csrf_field() ?>
                    <div class="col-md-4"><label class="form-label" for="exam-assignment">Class & subject</label><select class="form-select" id="exam-assignment" name="teaching_assignment" required><option value="">Choose your assignment</option>
                        <?php foreach ($portal['assessment_options'] as $option): ?><option value="<?= e($option['class_stream_id'] . ':' . $option['subject_id']) ?>"><?= e($option['class_name'] . ' ' . $option['stream'] . ' · ' . $option['subject_name']) ?></option><?php endforeach; ?>
                    </select></div>
                    <div class="col-md-3"><label class="form-label" for="exam-name">Assessment name</label><input class="form-control" id="exam-name" name="name" maxlength="120" placeholder="e.g. Term 2 CAT 1" required></div>
                    <div class="col-md-2"><label class="form-label" for="exam-type">Type</label><select class="form-select" id="exam-type" name="exam_type" required><?php foreach (['cat' => 'CAT', 'midterm' => 'Midterm', 'endterm' => 'End term', 'mock' => 'Mock', 'national' => 'National'] as $value => $label): ?><option value="<?= e($value) ?>"><?= e($label) ?></option><?php endforeach; ?></select></div>
                    <div class="col-md-1"><label class="form-label" for="exam-starts">Starts</label><input class="form-control" id="exam-starts" type="date" name="starts_on" required></div>
                    <div class="col-md-1"><label class="form-label" for="exam-ends">Ends</label><input class="form-control" id="exam-ends" type="date" name="ends_on" required></div>
                    <div class="col-md-1 d-flex align-items-end"><button class="btn btn-primary w-100" type="submit" <?= $portal['assessment_options'] === [] ? 'disabled' : '' ?>>Publish</button></div>
                </form>
            <?php endif; ?>
            <div class="table-responsive"><table class="table align-middle">
                <thead><tr><th>Assessment</th><th>Type</th><th>Class</th><th>Subject</th><th>Dates</th><th>Status</th></tr></thead>
                <tbody>
                <?php foreach ($portal['assessments'] as $exam): ?>
                    <tr><td><?= e($exam['name']) ?></td><td><?= e(strtoupper($exam['exam_type'])) ?></td><td><?= e($exam['class_name'] . ' ' . $exam['stream']) ?></td><td><?= e($exam['subject_name']) ?></td><td><?= e($exam['starts_on']) ?> – <?= e($exam['ends_on']) ?></td><td><span class="badge text-bg-primary"><?= e(ucfirst($exam['status'])) ?></span></td></tr>
                <?php endforeach; ?>
                <?php if ($portal['assessments'] === []): ?><tr><td colspan="6" class="text-muted">No assessments created yet.</td></tr><?php endif; ?>
                </tbody>
            </table></div>
        </section>
    </div>

    <div class="col-lg-6">
        <section id="assignments" class="panel h-100">
            <div class="panel-header"><div><h2>Assignments</h2><p>Publish homework or classwork with instructions and a due date.</p></div></div>
            <?php if ($portal['assessment_options'] === []): ?>
                <p class="text-muted mb-0">You need an assigned subject and class before publishing an assignment.</p>
            <?php else: ?>
                <form action="<?= e(url('/teacher-portal/assignment')) ?>" method="post" class="row g-3 mb-4">
                    <?= csrf_field() ?>
                    <div class="col-12"><label class="form-label" for="task-assignment">Class & subject</label><select class="form-select" id="task-assignment" name="teaching_assignment" required><option value="">Choose your assignment</option>
                        <?php foreach ($portal['assessment_options'] as $option): ?><option value="<?= e($option['class_stream_id'] . ':' . $option['subject_id']) ?>"><?= e($option['class_name'] . ' ' . $option['stream'] . ' · ' . $option['subject_name']) ?></option><?php endforeach; ?>
                    </select></div>
                    <div class="col-12"><label class="form-label" for="task-title">Title</label><input class="form-control" id="task-title" name="title" maxlength="190" required></div>
                    <div class="col-12"><label class="form-label" for="task-description">Instructions</label><textarea class="form-control" id="task-description" name="description" rows="3" maxlength="10000" required></textarea></div>
                    <div class="col-8"><label class="form-label" for="task-due">Due date and time</label><input class="form-control" id="task-due" type="datetime-local" name="due_at" required></div>
                    <div class="col-4 d-flex align-items-end"><button class="btn btn-primary w-100" type="submit">Publish assignment</button></div>
                </form>
            <?php endif; ?>
            <div class="activity-list">
                <?php foreach ($portal['class_tasks'] as $task): ?>
                    <div class="activity-item"><span class="activity-dot"></span><div><strong><?= e($task['title']) ?></strong><p class="mb-1"><?= e($task['class_name'] . ' ' . $task['stream'] . ' · ' . $task['subject_name']) ?> · Due <?= e(date('M j, Y H:i', strtotime($task['due_at']))) ?></p><p class="mb-0"><?= nl2br(e($task['description'])) ?></p></div></div>
                <?php endforeach; ?>
                <?php if ($portal['class_tasks'] === []): ?><p class="text-muted mb-0">No assignments published yet.</p><?php endif; ?>
            </div>
        </section>
    </div>

    <div class="col-lg-6">
        <section id="mark-entry" class="panel h-100">
            <div class="panel-header"><div><h2>Enter Marks</h2><p>Marks are saved to the learner’s record and become visible to the learner and linked parent.</p></div></div>
            <?php if ($portal['assessments'] === [] || $portal['students'] === []): ?>
                <p class="text-muted mb-0">Create an assessment for your assigned class before entering marks.</p>
            <?php else: ?>
                <form action="<?= e(url('/teacher-portal/mark')) ?>" method="post" class="row g-3">
                    <?= csrf_field() ?>
                    <div class="col-12"><label class="form-label" for="mark-exam">Assessment</label><select class="form-select" id="mark-exam" name="exam_id" required><option value="">Choose assessment</option>
                        <?php foreach ($portal['assessments'] as $exam): ?>
                            <?php if (in_array($exam['status'], ['open', 'closed'], true)): ?><option value="<?= e($exam['id']) ?>" data-class="<?= e($exam['class_stream_id']) ?>"><?= e($exam['name'] . ' · ' . $exam['class_name'] . ' ' . $exam['stream'] . ' · ' . $exam['subject_name']) ?></option><?php endif; ?>
                        <?php endforeach; ?>
                    </select></div>
                    <div class="col-12"><label class="form-label" for="mark-student">Learner</label><select class="form-select" id="mark-student" name="student_id" required><option value="">Choose learner</option>
                        <?php foreach ($portal['students'] as $student): ?><option value="<?= e($student['id']) ?>" data-class="<?= e($student['class_stream_id']) ?>"><?= e($student['first_name'] . ' ' . $student['last_name'] . ' · ' . $student['class_name']) ?></option><?php endforeach; ?>
                    </select></div>
                    <div class="col-md-4"><label class="form-label" for="mark-score">Mark (0–100)</label><input class="form-control" id="mark-score" name="marks" type="number" min="0" max="100" step="0.01" required></div>
                    <div class="col-md-8"><label class="form-label" for="mark-feedback">Feedback</label><input class="form-control" id="mark-feedback" name="remarks" maxlength="160"></div>
                    <div class="col-12"><button class="btn btn-primary" type="submit">Save mark</button></div>
                </form>
            <?php endif; ?>
            <h3 class="h6 mt-4">Recently entered marks</h3>
            <div class="table-responsive"><table class="table table-sm align-middle">
                <thead><tr><th>Assessment</th><th>Learner</th><th>Subject</th><th>Mark</th><th>Grade</th></tr></thead>
                <tbody>
                <?php foreach ($portal['results'] as $result): ?><tr><td><?= e($result['exam_name']) ?></td><td><?= e($result['student_name']) ?></td><td><?= e($result['subject_name']) ?></td><td><?= e($result['marks']) ?></td><td><span class="badge text-bg-primary"><?= e($result['grade']) ?></span></td></tr><?php endforeach; ?>
                <?php if ($portal['results'] === []): ?><tr><td colspan="5" class="text-muted">No marks entered yet.</td></tr><?php endif; ?>
                </tbody>
            </table></div>
        </section>
    </div>

    <div class="col-xl-6">
        <section id="attendance" class="panel h-100">
            <div class="panel-header"><div><h2>Attendance Submitted</h2><p>Your recent attendance records in assigned classes.</p></div></div>
            <div class="table-responsive"><table class="table table-sm align-middle">
                <thead><tr><th>Date</th><th>Status</th><th>Count</th></tr></thead>
                <tbody>
                <?php foreach ($portal['attendance'] as $record): ?><tr><td><?= e($record['attendance_date']) ?></td><td><?= e(ucfirst($record['status'])) ?></td><td><?= e($record['count']) ?></td></tr><?php endforeach; ?>
                <?php if ($portal['attendance'] === []): ?><tr><td colspan="3" class="text-muted">No attendance records submitted.</td></tr><?php endif; ?>
                </tbody>
            </table></div>
        </section>
    </div>
    <div class="col-xl-6">
        <section id="school" class="panel h-100">
            <div class="panel-header"><div><h2>School Updates</h2><p>Staff notices, your class notices, and upcoming events.</p></div></div>
            <div class="activity-list">
                <?php foreach ($portal['announcements'] as $announcement): ?>
                    <div class="activity-item"><span class="activity-dot"></span><div><strong><?= e($announcement['title']) ?></strong><p class="mb-1"><?= e($announcement['body']) ?></p><small class="text-muted"><?= e($announcement['sender_name']) ?><?= $announcement['class_name'] ? ' · ' . e($announcement['class_name'] . ' ' . $announcement['stream']) : '' ?> · <?= e(date('M j, Y H:i', strtotime($announcement['published_at']))) ?></small></div></div>
                <?php endforeach; ?>
                <?php foreach ($portal['events'] as $event): ?><div class="activity-item"><span class="activity-dot"></span><div><strong><?= e($event['title']) ?></strong><p><?= e(date('M j, Y H:i', strtotime($event['starts_at']))) ?><?= $event['location'] ? ' · ' . e($event['location']) : '' ?></p></div></div><?php endforeach; ?>
                <?php if ($portal['announcements'] === [] && $portal['events'] === []): ?><p class="text-muted mb-0">No school updates at this time.</p><?php endif; ?>
            </div>
            <hr>
            <h3 class="h6">Send a class notice</h3>
            <p class="small text-muted">Students in the selected class and their linked parents can read this notice.</p>
            <form action="<?= e(url('/teacher-portal/announcement')) ?>" method="post" class="row g-3">
                <?= csrf_field() ?>
                <div class="col-12"><label class="form-label" for="announcement-class">Class</label><select class="form-select" id="announcement-class" name="class_stream_id" required><option value="">Choose an assigned class</option><?php foreach ($portal['classes'] as $class): ?><option value="<?= e($class['id']) ?>"><?= e($class['name'] . ' ' . $class['stream']) ?></option><?php endforeach; ?></select></div>
                <div class="col-12"><label class="form-label" for="announcement-title">Title</label><input class="form-control" id="announcement-title" name="title" maxlength="190" required></div>
                <div class="col-12"><label class="form-label" for="announcement-body">Message</label><textarea class="form-control" id="announcement-body" name="body" rows="3" required></textarea></div>
                <div class="col-12"><button class="btn btn-primary" type="submit" <?= $portal['classes'] === [] ? 'disabled' : '' ?>>Publish notice</button></div>
            </form>
        </section>
    </div>
</div>
