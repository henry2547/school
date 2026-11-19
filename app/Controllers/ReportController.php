<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\View;
use App\Models\Report;

final class ReportController
{
    public function index(): void
    {
        Auth::requirePermission('reports.view');
        View::render('reports/index', ['title' => 'Reports', 'summary' => Report::summary()]);
    }
}
