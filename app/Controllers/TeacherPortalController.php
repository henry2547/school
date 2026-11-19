<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Csrf;
use App\Core\Request;
use App\Core\View;
use App\Models\TeacherPortal;

final class TeacherPortalController
{
    public function index(): void
    {
        Auth::requirePermission('teacher.portal');
        $portal = TeacherPortal::byEmail((string)Auth::user()['email']);
        if (!$portal) {
            http_response_code(404);
            View::render('errors/404', ['title' => 'Teacher profile not linked']);
            return;
        }
        View::render('teacher-portal/index', ['title' => 'My Teaching Dashboard', 'portal' => $portal]);
    }

    public function announce(): void
    {
        Auth::requirePermission('teacher.portal');
        Csrf::verify();
        $data = Request::only(['class_stream_id', 'title', 'body']);
        $classStreamId = filter_var($data['class_stream_id'], FILTER_VALIDATE_INT);
        $title = trim($data['title']);
        $body = trim($data['body']);

        if (!$classStreamId || $classStreamId < 1 || $title === '' || strlen($title) > 190 || $body === '') {
            $_SESSION['flash_error'] = 'Choose an assigned class and provide a title and message (up to 190 characters for the title).';
            redirect('/teacher-portal#school');
        }

        $published = TeacherPortal::publishAnnouncement(
            (string)Auth::user()['email'],
            (int)Auth::id(),
            $classStreamId,
            $title,
            $body
        );
        if (!$published) {
            $_SESSION['flash_error'] = 'You can only send notices to classes assigned to you.';
            redirect('/teacher-portal#school');
        }

        $_SESSION['flash_success'] = 'Notice sent to students in the selected class and their linked parents.';
        redirect('/teacher-portal#school');
    }

    public function createExam(): void
    {
        Auth::requirePermission('teacher.portal');
        Csrf::verify();
        $data = Request::only(['teaching_assignment', 'name', 'exam_type', 'starts_on', 'ends_on']);
        $assignment = self::assignmentPair($data['teaching_assignment']);
        $start = self::date($data['starts_on'], '!Y-m-d');
        $end = self::date($data['ends_on'], '!Y-m-d');
        $examTypes = ['cat', 'midterm', 'endterm', 'mock', 'national'];

        if (
            $assignment === null || trim($data['name']) === '' || strlen($data['name']) > 120
            || !in_array($data['exam_type'], $examTypes, true)
            || $start === null || $end === null || $end < $start
        ) {
            $_SESSION['flash_error'] = 'Enter a name, assigned class and subject, valid assessment type, and valid date range.';
            redirect('/teacher-portal#assessments');
        }

        if (!TeacherPortal::createExam(
            (string)Auth::user()['email'],
            (int)Auth::id(),
            $assignment['class_id'],
            $assignment['subject_id'],
            trim($data['name']),
            $data['exam_type'],
            $start->format('Y-m-d'),
            $end->format('Y-m-d')
        )) {
            $_SESSION['flash_error'] = 'You can only schedule assessments for subjects and classes assigned to you.';
            redirect('/teacher-portal#assessments');
        }

        $_SESSION['flash_success'] = 'Assessment published to the selected class schedule.';
        redirect('/teacher-portal#assessments');
    }

    public function createAssignment(): void
    {
        Auth::requirePermission('teacher.portal');
        Csrf::verify();
        $data = Request::only(['teaching_assignment', 'title', 'description', 'due_at']);
        $assignment = self::assignmentPair($data['teaching_assignment']);
        $dueAt = self::date($data['due_at'], '!Y-m-d\TH:i');
        $title = trim($data['title']);
        $description = trim($data['description']);

        if (
            $assignment === null || $title === '' || strlen($title) > 190
            || $description === '' || strlen($description) > 10000 || $dueAt === null
        ) {
            $_SESSION['flash_error'] = 'Enter an assigned class and subject, a title, instructions, and a valid due date.';
            redirect('/teacher-portal#assignments');
        }

        if (!TeacherPortal::createAssignment(
            (string)Auth::user()['email'],
            $assignment['class_id'],
            $assignment['subject_id'],
            $title,
            $description,
            $dueAt->format('Y-m-d H:i:s')
        )) {
            $_SESSION['flash_error'] = 'You can only assign work for subjects and classes assigned to you.';
            redirect('/teacher-portal#assignments');
        }

        $_SESSION['flash_success'] = 'Assignment published to the selected class.';
        redirect('/teacher-portal#assignments');
    }

    public function saveMark(): void
    {
        Auth::requirePermission('teacher.portal');
        Csrf::verify();
        $data = Request::only(['exam_id', 'student_id', 'marks', 'remarks']);
        $examId = filter_var($data['exam_id'], FILTER_VALIDATE_INT);
        $studentId = filter_var($data['student_id'], FILTER_VALIDATE_INT);
        $marks = filter_var($data['marks'], FILTER_VALIDATE_FLOAT);
        $remarks = trim($data['remarks']);

        if (
            !$examId || !$studentId || $marks === false || $marks < 0 || $marks > 100
            || strlen($remarks) > 160
        ) {
            $_SESSION['flash_error'] = 'Choose an exam and learner, enter a mark from 0 to 100, and keep feedback to 160 characters.';
            redirect('/teacher-portal#mark-entry');
        }

        if (!TeacherPortal::saveMark((int)Auth::id(), $examId, $studentId, (float)$marks, $remarks)) {
            $_SESSION['flash_error'] = 'Marks can only be entered for an active learner in one of your assigned exams.';
            redirect('/teacher-portal#mark-entry');
        }

        $_SESSION['flash_success'] = 'Mark saved and made available in the learner and parent portals.';
        redirect('/teacher-portal#mark-entry');
    }

    private static function date(string $value, string $format): ?\DateTimeImmutable
    {
        $date = \DateTimeImmutable::createFromFormat($format, $value);
        $errors = \DateTimeImmutable::getLastErrors();
        if (
            !$date || ($errors !== false && ($errors['warning_count'] > 0 || $errors['error_count'] > 0))
            || $date->format(substr($format, 1)) !== $value
        ) {
            return null;
        }
        return $date;
    }

    private static function assignmentPair(string $value): ?array
    {
        if (!preg_match('/^([1-9][0-9]*):([1-9][0-9]*)$/D', $value, $matches)) {
            return null;
        }
        return ['class_id' => (int)$matches[1], 'subject_id' => (int)$matches[2]];
    }
}
