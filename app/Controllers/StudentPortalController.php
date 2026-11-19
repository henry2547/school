<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\View;
use App\Models\StudentPortal;

final class StudentPortalController
{
    public function index(): void
    {
        Auth::requirePermission('student.portal');
        $portal = StudentPortal::byEmail((string)Auth::user()['email']);
        if (!$portal) {
            http_response_code(404);
            View::render('errors/404', ['title' => 'Student profile not linked']);
            return;
        }
        View::render('student-portal/index', ['title' => 'My School', 'portal' => $portal]);
    }
}
