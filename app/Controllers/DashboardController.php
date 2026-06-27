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
        View::render('dashboard/index', [
            'title' => 'Dashboard',
            'metrics' => Dashboard::metrics(),
            'activities' => AuditLog::recent(),
        ]);
    }
}
