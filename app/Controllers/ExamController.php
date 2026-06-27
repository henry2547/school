<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Csrf;
use App\Core\Request;
use App\Core\View;
use App\Models\AuditLog;
use App\Models\Exam;
use App\Models\Lookup;

final class ExamController
{
    public function index(): void
    {
        Auth::requirePermission('exams.view');
        $examId = trim((string)Request::input('exam_id', ''));
        View::render('exams/index', [
            'title' => 'Exams',
            'examId' => $examId,
            'exams' => Exam::exams(),
            'results' => Exam::results($examId),
            'terms' => Lookup::terms(),
            'students' => Lookup::students(),
            'subjects' => Lookup::subjects(),
        ]);
    }

    public function exam(): void
    {
        Auth::requirePermission('exams.manage');
        Csrf::verify();
        $data = Request::only(['term_id', 'name', 'exam_type', 'starts_on', 'ends_on', 'status']);
        $id = Exam::createExam($data);
        AuditLog::record(Auth::id(), 'create', 'exams', $id, ['name' => $data['name']]);
        $_SESSION['flash_success'] = 'Exam created successfully.';
        redirect('/exams');
    }

    public function result(): void
    {
        Auth::requirePermission('exams.manage');
        Csrf::verify();
        $data = Request::only(['exam_id', 'student_id', 'subject_id', 'marks', 'remarks']);
        $id = Exam::saveResult($data, Auth::id());
        AuditLog::record(Auth::id(), 'save', 'exam_results', $id, ['exam_id' => $data['exam_id']]);
        $_SESSION['flash_success'] = 'Result saved successfully.';
        redirect('/exams?exam_id=' . urlencode($data['exam_id']));
    }
}
