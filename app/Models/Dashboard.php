<?php

namespace App\Models;

use App\Core\Database;

final class Dashboard
{
    public static function metrics(): array
    {
        $pdo = Database::connection();

        return [
            'students' => (int)$pdo->query('SELECT COUNT(*) FROM students')->fetchColumn(),
            'teachers' => (int)$pdo->query('SELECT COUNT(*) FROM teachers')->fetchColumn(),
            'parents' => (int)$pdo->query('SELECT COUNT(*) FROM parents')->fetchColumn(),
            'users' => (int)$pdo->query('SELECT COUNT(*) FROM users')->fetchColumn(),
            'fees_collected' => (float)$pdo->query('SELECT COALESCE(SUM(amount_paid), 0) FROM fee_payments')->fetchColumn(),
            'outstanding_fees' => (float)$pdo->query('SELECT COALESCE(SUM(balance), 0) FROM fee_invoices')->fetchColumn(),
            'attendance_rate' => (float)$pdo->query('SELECT COALESCE(AVG(CASE WHEN status = "present" THEN 100 ELSE 0 END), 0) FROM attendance_records')->fetchColumn(),
            'books' => (int)$pdo->query('SELECT COUNT(*) FROM library_books')->fetchColumn(),
        ];
    }
}
