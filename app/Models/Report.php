<?php

namespace App\Models;

use App\Core\Database;

final class Report
{
    public static function summary(): array
    {
        $pdo = Database::connection();
        return [
            'fees' => $pdo->query('SELECT status, COUNT(*) AS count, COALESCE(SUM(balance),0) AS balance FROM fee_invoices GROUP BY status')->fetchAll(),
            'attendance' => $pdo->query('SELECT status, COUNT(*) AS count FROM attendance_records GROUP BY status')->fetchAll(),
            'exams' => $pdo->query('SELECT exams.name, ROUND(AVG(exam_results.marks),1) AS mean_mark, COUNT(exam_results.id) AS entries FROM exams LEFT JOIN exam_results ON exam_results.exam_id = exams.id GROUP BY exams.id ORDER BY exams.starts_on DESC')->fetchAll(),
            'stock' => $pdo->query('SELECT item_code, name, quantity_on_hand, reorder_level FROM inventory_items WHERE quantity_on_hand <= reorder_level ORDER BY quantity_on_hand')->fetchAll(),
        ];
    }
}
