<?php

namespace App\Controllers;

use App\Core\Csrf;
use App\Core\Request;
use App\Core\View;
use App\Models\AuditLog;
use App\Models\User;

final class AuthController
{
    public function showLogin(): void
    {
        if (\App\Core\Auth::check()) {
            redirect('/dashboard');
        }
        View::render('auth/login', ['title' => 'Sign in'], 'auth');
    }

    public function login(): void
    {
        Csrf::verify();
        $email = trim((string)Request::input('email'));
        $password = (string)Request::input('password');
        $user = User::findByEmail($email);

        if (!$user || $user['status'] !== 'active' || !password_verify($password, $user['password_hash'])) {
            $_SESSION['flash_error'] = 'Invalid credentials or inactive account.';
            redirect('/login');
        }

        session_regenerate_id(true);
        $_SESSION['user'] = [
            'id' => (int)$user['id'],
            'name' => trim($user['first_name'] . ' ' . $user['last_name']),
            'email' => $user['email'],
            'role' => $user['role_name'],
            'role_slug' => $user['role_slug'],
            'permissions' => User::permissions((int)$user['role_id']),
        ];

        User::touchLogin((int)$user['id']);
        AuditLog::record((int)$user['id'], 'login', 'users', (int)$user['id']);
        $portal = match ($user['role_slug']) {
            'student' => '/student-portal',
            'parent' => '/parent-portal',
            'teacher', 'class-teacher' => '/teacher-portal',
            default => '/dashboard',
        };
        redirect($portal);
    }

    public function logout(): void
    {
        if (\App\Core\Auth::check()) {
            AuditLog::record(\App\Core\Auth::id(), 'logout', 'users', \App\Core\Auth::id());
        }

        $_SESSION = [];
        session_destroy();
        redirect('/login');
    }
}
