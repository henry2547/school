<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Csrf;
use App\Core\Request;
use App\Core\View;
use App\Models\Attendance;
use App\Models\AuditLog;
use App\Models\Lookup;

final class AttendanceController
{
    public function index(): void
    {
        Auth::requirePermission('attendance.view');
        $date = trim((string)Request::input('date', date('Y-m-d')));
        $classId = trim((string)Request::input('class_id', ''));
        View::render('attendance/index', [
            'title' => 'Attendance',
            'date' => $date,
            'classId' => $classId,
            'classes' => Lookup::classes(),
            'records' => Attendance::sheet($date, $classId),
        ]);
    }

    public function store(): void
    {
        Auth::requirePermission('attendance.manage');
        Csrf::verify();
        $date = trim((string)Request::input('date', date('Y-m-d')));
        Attendance::save($date, $_POST['records'] ?? [], Auth::id());
        AuditLog::record(Auth::id(), 'mark', 'attendance', null, ['date' => $date]);
        $_SESSION['flash_success'] = 'Attendance saved successfully.';
        redirect('/attendance?date=' . urlencode($date) . '&class_id=' . urlencode((string)Request::input('class_id', '')));
    }
}
