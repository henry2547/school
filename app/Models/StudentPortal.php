<?php

namespace App\Models;

use App\Core\Database;

final class StudentPortal
{
    public static function byEmail(string $email): ?array
    {
        $pdo = Database::connection();
        $student = $pdo->prepare(
            'SELECT students.*, CONCAT(class_streams.name, " ", class_streams.stream) AS class_name,
                    CONCAT(teachers.first_name, " ", teachers.last_name) AS class_teacher
             FROM students
             LEFT JOIN class_streams ON class_streams.id = students.class_stream_id
             LEFT JOIN teachers ON teachers.id = class_streams.class_teacher_id
             WHERE students.email = :email LIMIT 1'
        );
        $student->execute(['email' => $email]);
        $profile = $student->fetch();
        if (!$profile) {
            return null;
        }

        $id = (int)$profile['id'];
        $classId = (int)($profile['class_stream_id'] ?? 0);
        return [
            'profile' => $profile,
            'subjects' => $classId > 0 ? self::fetchByClass(
                'SELECT subjects.name AS subject_name, subjects.code,
                        CONCAT(teachers.first_name, " ", teachers.last_name) AS teacher_name
                 FROM teacher_subjects
                 JOIN subjects ON subjects.id = teacher_subjects.subject_id
                 JOIN teachers ON teachers.id = teacher_subjects.teacher_id
                 WHERE teacher_subjects.class_stream_id = :class_stream_id
                 ORDER BY subjects.name',
                $classId
            ) : [],
            'parents' => self::fetchAll('SELECT parents.*, student_parent.is_primary FROM student_parent JOIN parents ON parents.id = student_parent.parent_id WHERE student_parent.student_id = :student_id ORDER BY student_parent.is_primary DESC', $id),
            'invoices' => self::fetchAll('SELECT * FROM fee_invoices WHERE student_id = :student_id ORDER BY due_date DESC', $id),
            'payments' => self::fetchAll('SELECT fee_payments.*, fee_invoices.invoice_no FROM fee_payments JOIN fee_invoices ON fee_invoices.id = fee_payments.invoice_id WHERE fee_invoices.student_id = :student_id ORDER BY fee_payments.paid_at DESC', $id),
            'attendance' => self::fetchAll('SELECT attendance_date, status, remarks FROM attendance_records WHERE student_id = :student_id ORDER BY attendance_date DESC LIMIT 30', $id),
            'results' => self::fetchAll('SELECT exams.name AS exam_name, subjects.name AS subject_name, exam_results.marks, exam_results.grade, exam_results.remarks FROM exam_results JOIN exams ON exams.id = exam_results.exam_id JOIN subjects ON subjects.id = exam_results.subject_id WHERE exam_results.student_id = :student_id ORDER BY exams.starts_on DESC, subjects.name', $id),
            'exams' => $pdo->query('SELECT name, exam_type, starts_on, ends_on, status FROM exams WHERE status IN ("open", "closed", "published") ORDER BY starts_on DESC, name')->fetchAll(),
            'announcements' => self::fetchByClass(
                'SELECT announcements.title, announcements.body, announcements.audience, announcements.published_at,
                        COALESCE(CONCAT(users.first_name, " ", users.last_name), "School") AS sender_name
                 FROM announcements
                 LEFT JOIN users ON users.id = announcements.created_by
                 WHERE announcements.published_at IS NOT NULL
                   AND announcements.audience IN ("all", "students")
                   AND (
                       announcements.class_stream_id IS NULL
                       OR announcements.class_stream_id = :class_stream_id
                   )
                 ORDER BY announcements.published_at DESC
                 LIMIT 20',
                $classId
            ),
            'events' => $pdo->query('SELECT title, event_type, starts_at, ends_at, location FROM school_events WHERE starts_at >= CURDATE() ORDER BY starts_at LIMIT 10')->fetchAll(),
            'loans' => self::fetchAll('SELECT library_books.title, book_loans.issued_on, book_loans.due_on, book_loans.returned_on, book_loans.fine_amount FROM book_loans JOIN library_books ON library_books.id = book_loans.book_id WHERE book_loans.student_id = :student_id ORDER BY book_loans.returned_on IS NULL DESC, book_loans.due_on', $id),
            'transport' => self::fetchOne('SELECT transport_routes.name, transport_routes.pickup_points, transport_routes.monthly_fee, vehicles.registration_no FROM transport_assignments JOIN transport_routes ON transport_routes.id = transport_assignments.route_id LEFT JOIN vehicles ON vehicles.id = transport_routes.vehicle_id WHERE transport_assignments.student_id = :student_id LIMIT 1', $id),
            'hostel' => self::fetchOne('SELECT hostels.name AS hostel_name, hostel_rooms.room_no, hostel_allocations.checked_in_on FROM hostel_allocations JOIN hostel_rooms ON hostel_rooms.id = hostel_allocations.room_id JOIN hostels ON hostels.id = hostel_rooms.hostel_id WHERE hostel_allocations.student_id = :student_id AND hostel_allocations.checked_out_on IS NULL LIMIT 1', $id),
        ];
    }

    private static function fetchAll(string $sql, int $studentId): array
    {
        $stmt = Database::connection()->prepare($sql);
        $stmt->execute(['student_id' => $studentId]);
        return $stmt->fetchAll();
    }

    private static function fetchOne(string $sql, int $studentId): ?array
    {
        $stmt = Database::connection()->prepare($sql);
        $stmt->execute(['student_id' => $studentId]);
        return $stmt->fetch() ?: null;
    }

    private static function fetchByClass(string $sql, int $classStreamId): array
    {
        $stmt = Database::connection()->prepare($sql);
        $stmt->execute(['class_stream_id' => $classStreamId]);
        return $stmt->fetchAll();
    }
}
