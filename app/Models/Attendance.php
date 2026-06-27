<?php

namespace App\Models;

use App\Core\Database;

final class Attendance
{
    public static function sheet(string $date, string $classId = ''): array
    {
        $params = ['date' => $date];
        $where = 'students.status = "active"';
        if ($classId !== '') {
            $where .= ' AND students.class_stream_id = :class_id';
            $params['class_id'] = $classId;
        }
        $stmt = Database::connection()->prepare(
            "SELECT students.id, students.admission_no, students.first_name, students.last_name,
                    CONCAT(class_streams.name, ' ', class_streams.stream) AS class_name,
                    attendance_records.status AS attendance_status, attendance_records.remarks
             FROM students
             LEFT JOIN class_streams ON class_streams.id = students.class_stream_id
             LEFT JOIN attendance_records ON attendance_records.student_id = students.id AND attendance_records.attendance_date = :date
             WHERE {$where}
             ORDER BY class_streams.name, class_streams.stream, students.first_name"
        );
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public static function save(string $date, array $records, ?int $userId): void
    {
        $stmt = Database::connection()->prepare(
            'INSERT INTO attendance_records (student_id, class_stream_id, attendance_date, status, remarks, created_by)
             SELECT id, class_stream_id, :attendance_date, :status, :remarks, :created_by FROM students WHERE id = :student_id
             ON DUPLICATE KEY UPDATE status = VALUES(status), remarks = VALUES(remarks), created_by = VALUES(created_by)'
        );
        foreach ($records as $studentId => $record) {
            $stmt->execute([
                'student_id' => $studentId,
                'attendance_date' => $date,
                'status' => $record['status'] ?? 'present',
                'remarks' => trim((string)($record['remarks'] ?? '')) ?: null,
                'created_by' => $userId,
            ]);
        }
    }
}
