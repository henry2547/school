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
        $announcedExams = $pdo->query(
            'SELECT name, exam_type, starts_on, ends_on, status
             FROM exams
             WHERE status IN ("open", "closed", "published")
             ORDER BY starts_on DESC, name'
        )->fetchAll();
        foreach ($children as &$child) {
            $child['subjects'] = self::allForClass(
                'SELECT subjects.name AS subject_name, subjects.code,
                        CONCAT(teachers.first_name, " ", teachers.last_name) AS teacher_name
                 FROM teacher_subjects
                 JOIN subjects ON subjects.id = teacher_subjects.subject_id
                 JOIN teachers ON teachers.id = teacher_subjects.teacher_id
                 WHERE teacher_subjects.class_stream_id = :class_stream_id
                 ORDER BY subjects.name',
                (int)($child['class_stream_id'] ?? 0)
            );
            $child['invoices'] = self::all('SELECT invoice_no, description, amount, balance, due_date, status FROM fee_invoices WHERE student_id = :student_id ORDER BY due_date DESC', (int)$child['id']);
            $child['attendance'] = self::all('SELECT attendance_date, status FROM attendance_records WHERE student_id = :student_id ORDER BY attendance_date DESC LIMIT 10', (int)$child['id']);
            $child['results'] = self::all('SELECT exams.name AS exam_name, subjects.name AS subject_name, exam_results.marks, exam_results.grade, exam_results.remarks FROM exam_results JOIN exams ON exams.id = exam_results.exam_id JOIN subjects ON subjects.id = exam_results.subject_id WHERE exam_results.student_id = :student_id ORDER BY exams.starts_on DESC', (int)$child['id']);
            $child['exams'] = $announcedExams;
        }
        unset($child);
        return [
            'profile' => $parent,
            'children' => $children,
            'announcements' => self::allForParent(
                'SELECT announcements.title, announcements.body, announcements.published_at,
                        COALESCE(CONCAT(users.first_name, " ", users.last_name), "School") AS sender_name,
                        class_streams.name AS class_name, class_streams.stream
                 FROM announcements
                 LEFT JOIN users ON users.id = announcements.created_by
                 LEFT JOIN class_streams ON class_streams.id = announcements.class_stream_id
                 WHERE announcements.published_at IS NOT NULL
                   AND announcements.audience IN ("all", "parents", "students")
                   AND (
                       announcements.class_stream_id IS NULL
                       OR EXISTS (
                           SELECT 1
                           FROM student_parent
                           JOIN students ON students.id = student_parent.student_id
                           WHERE student_parent.parent_id = :parent_id
                             AND students.class_stream_id = announcements.class_stream_id
                       )
                   )
                 ORDER BY announcements.published_at DESC
                 LIMIT 20',
                (int)$parent['id']
            ),
            'events' => $pdo->query('SELECT title, event_type, starts_at, location FROM school_events WHERE starts_at >= CURDATE() ORDER BY starts_at LIMIT 8')->fetchAll(),
        ];
    }

    private static function all(string $sql, int $studentId): array
    {
        $stmt = Database::connection()->prepare($sql);
        $stmt->execute(['student_id' => $studentId]);
        return $stmt->fetchAll();
    }

    private static function allForClass(string $sql, int $classStreamId): array
    {
        if ($classStreamId === 0) {
            return [];
        }
        $stmt = Database::connection()->prepare($sql);
        $stmt->execute(['class_stream_id' => $classStreamId]);
        return $stmt->fetchAll();
    }

    private static function allForParent(string $sql, int $parentId): array
    {
        $stmt = Database::connection()->prepare($sql);
        $stmt->execute(['parent_id' => $parentId]);
        return $stmt->fetchAll();
    }
}
