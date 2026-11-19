<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\View;
use App\Models\ParentPortal;

final class ParentPortalController
{
    public function index(): void
    {
        Auth::requirePermission('parent.portal');
        $portal = ParentPortal::byEmail((string)Auth::user()['email']);
        if (!$portal) {
            http_response_code(404);
            View::render('errors/404', ['title' => 'Parent profile not linked']);
            return;
        }
        View::render('parent-portal/index', ['title' => 'My Children', 'portal' => $portal]);
    }
}
