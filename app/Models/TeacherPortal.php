<?php

namespace App\Models;

use App\Core\Database;

final class TeacherPortal
{
    public static function byEmail(string $email): ?array
    {
        $pdo = Database::connection();
        $stmt = $pdo->prepare('SELECT teachers.*, departments.name AS department_name FROM teachers LEFT JOIN departments ON departments.id = teachers.department_id WHERE teachers.email = :email LIMIT 1');
        $stmt->execute(['email' => $email]);
        $teacher = $stmt->fetch();
        if (!$teacher) {
            return null;
        }
        $id = (int)$teacher['id'];
        return [
            'profile' => $teacher,
            'assignments' => self::all('SELECT subjects.name AS subject_name, subjects.code, class_streams.name AS class_name, class_streams.stream FROM teacher_subjects JOIN subjects ON subjects.id = teacher_subjects.subject_id JOIN class_streams ON class_streams.id = teacher_subjects.class_stream_id WHERE teacher_subjects.teacher_id = :teacher_id ORDER BY class_streams.name, subjects.name', ['teacher_id' => $id]),
            'classrooms' => self::all('SELECT class_streams.id, class_streams.name, class_streams.stream, COUNT(students.id) AS student_count FROM class_streams LEFT JOIN students ON students.class_stream_id = class_streams.id AND students.status = "active" WHERE class_streams.class_teacher_id = :teacher_id GROUP BY class_streams.id ORDER BY class_streams.name', ['teacher_id' => $id]),
            'students' => self::all('SELECT DISTINCT students.admission_no, students.first_name, students.last_name, CONCAT(class_streams.name, " ", class_streams.stream) AS class_name FROM students JOIN class_streams ON class_streams.id = students.class_stream_id LEFT JOIN teacher_subjects ON teacher_subjects.class_stream_id = class_streams.id WHERE (class_streams.class_teacher_id = :class_teacher_id OR teacher_subjects.teacher_id = :subject_teacher_id) AND students.status = "active" ORDER BY students.first_name, students.last_name', ['class_teacher_id' => $id, 'subject_teacher_id' => $id]),
            'attendance' => self::all(
                'SELECT attendance_records.attendance_date, attendance_records.status, COUNT(*) AS count
                 FROM attendance_records
                 JOIN students ON students.id = attendance_records.student_id
                 WHERE attendance_records.created_by = (
                     SELECT users.id FROM users WHERE users.email = :attendance_email LIMIT 1
                 )
                 AND EXISTS (
                     SELECT 1
                     FROM class_streams
                     WHERE class_streams.id = students.class_stream_id
                       AND (
                           class_streams.class_teacher_id = :attendance_class_teacher_id
                           OR EXISTS (
                               SELECT 1
                               FROM teacher_subjects
                               WHERE teacher_subjects.class_stream_id = class_streams.id
                                 AND teacher_subjects.teacher_id = :attendance_subject_teacher_id
                           )
                       )
                 )
                 GROUP BY attendance_records.attendance_date, attendance_records.status
                 ORDER BY attendance_records.attendance_date DESC
                 LIMIT 20',
                [
                    'attendance_email' => $email,
                    'attendance_class_teacher_id' => $id,
                    'attendance_subject_teacher_id' => $id,
                ]
            ),
            'results' => self::all(
                'SELECT exams.name AS exam_name, subjects.name AS subject_name,
                        CONCAT(students.first_name, " ", students.last_name) AS student_name,
                        exam_results.marks, exam_results.grade
                 FROM exam_results
                 JOIN exams ON exams.id = exam_results.exam_id
                 JOIN subjects ON subjects.id = exam_results.subject_id
                 JOIN students ON students.id = exam_results.student_id
                 JOIN class_streams ON class_streams.id = students.class_stream_id
                 LEFT JOIN teacher_subjects
                     ON teacher_subjects.class_stream_id = students.class_stream_id
                    AND teacher_subjects.subject_id = exam_results.subject_id
                    AND teacher_subjects.teacher_id = :results_subject_teacher_id
                 WHERE exam_results.entered_by = (
                     SELECT users.id FROM users WHERE users.email = :results_email LIMIT 1
                 )
                   AND (
                       class_streams.class_teacher_id = :results_class_teacher_id
                       OR teacher_subjects.teacher_id IS NOT NULL
                   )
                 ORDER BY exams.starts_on DESC
                 LIMIT 25',
                [
                    'results_subject_teacher_id' => $id,
                    'results_email' => $email,
                    'results_class_teacher_id' => $id,
                ]
            ),
            'announcements' => $pdo->query('SELECT title, body, published_at FROM announcements WHERE published_at IS NOT NULL AND audience IN ("all", "staff") ORDER BY published_at DESC LIMIT 8')->fetchAll(),
            'events' => $pdo->query('SELECT title, event_type, starts_at, location FROM school_events WHERE starts_at >= CURDATE() ORDER BY starts_at LIMIT 8')->fetchAll(),
        ];
    }

    private static function all(string $sql, array $params): array
    {
        $stmt = Database::connection()->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
}
