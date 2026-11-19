<?php

namespace App\Core;

final class Auth
{
    public static function user(): ?array
    {
        return $_SESSION['user'] ?? null;
    }

    public static function check(): bool
    {
        return self::user() !== null;
    }

    public static function id(): ?int
    {
        return self::user()['id'] ?? null;
    }

    public static function requireLogin(): void
    {
        if (!self::check()) {
            redirect('/login');
        }
    }

    public static function can(string $permission): bool
    {
        $user = self::user();
        if (in_array($user['role_slug'] ?? '', ['teacher', 'class-teacher'], true)
            && !in_array($permission, ['dashboard.view', 'teacher.portal'], true)) {
            return false;
        }

        $permissions = $user['permissions'] ?? [];
        return in_array($permission, $permissions, true);
    }

    public static function requirePermission(string $permission): void
    {
        self::requireLogin();
        if (!self::can($permission)) {
            http_response_code(403);
            View::render('errors/403', ['title' => 'Forbidden']);
            exit;
        }
    }
}
