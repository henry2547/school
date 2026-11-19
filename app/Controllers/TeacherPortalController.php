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
}
