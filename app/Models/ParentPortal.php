<?php

namespace App\Models;

use App\Core\Database;

final class ParentPortal
{
    public static function byEmail(string $email): ?array
    {
        $pdo = Database::connection();
        $stmt = $pdo->prepare('SELECT * FROM parents WHERE email = :email LIMIT 1');
        $stmt->execute(['email' => $email]);
        $parent = $stmt->fetch();
        if (!$parent) {
            return null;
        }
        $students = $pdo->prepare('SELECT students.*, CONCAT(class_streams.name, " ", class_streams.stream) AS class_name FROM student_parent JOIN students ON students.id = student_parent.student_id LEFT JOIN class_streams ON class_streams.id = students.class_stream_id WHERE student_parent.parent_id = :parent_id ORDER BY students.first_name');
        $students->execute(['parent_id' => $parent['id']]);
        $children = $students->fetchAll();
        foreach ($children as &$child) {
            $child['invoices'] = self::all('SELECT invoice_no, description, amount, balance, due_date, status FROM fee_invoices WHERE student_id = :student_id ORDER BY due_date DESC', (int)$child['id']);
            $child['attendance'] = self::all('SELECT attendance_date, status FROM attendance_records WHERE student_id = :student_id ORDER BY attendance_date DESC LIMIT 10', (int)$child['id']);
            $child['results'] = self::all('SELECT exams.name AS exam_name, subjects.name AS subject_name, exam_results.marks, exam_results.grade FROM exam_results JOIN exams ON exams.id = exam_results.exam_id JOIN subjects ON subjects.id = exam_results.subject_id WHERE exam_results.student_id = :student_id ORDER BY exams.starts_on DESC', (int)$child['id']);
        }
        unset($child);
        return [
            'profile' => $parent,
            'children' => $children,
            'announcements' => $pdo->query('SELECT title, body, published_at FROM announcements WHERE published_at IS NOT NULL AND audience IN ("all", "parents") ORDER BY published_at DESC LIMIT 8')->fetchAll(),
            'events' => $pdo->query('SELECT title, event_type, starts_at, location FROM school_events WHERE starts_at >= CURDATE() ORDER BY starts_at LIMIT 8')->fetchAll(),
        ];
    }

    private static function all(string $sql, int $studentId): array
    {
        $stmt = Database::connection()->prepare($sql);
        $stmt->execute(['student_id' => $studentId]);
        return $stmt->fetchAll();
    }
}
