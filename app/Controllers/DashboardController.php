<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\View;
use App\Models\AuditLog;
use App\Models\Dashboard;

final class DashboardController
{
    public function index(): void
    {
        Auth::requireLogin();
        $portal = match (Auth::user()['role_slug'] ?? '') {
            'student' => '/student-portal',
            'parent' => '/parent-portal',
            'teacher', 'class-teacher' => '/teacher-portal',
            default => null,
        };
        if ($portal !== null) {
            redirect($portal);
        }
        View::render('dashboard/index', [
            'title' => 'Dashboard',
            'metrics' => Dashboard::metrics(),
            'activities' => AuditLog::recent(),
        ]);
    }
}
