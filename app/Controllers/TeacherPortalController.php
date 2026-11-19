<?php

namespace App\Controllers;

use App\Core\Auth;
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
}
