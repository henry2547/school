<?php

namespace App\Models;

use App\Core\Database;

final class Exam
{
    public static function exams(): array
    {
        return Database::connection()->query(
            'SELECT exams.*, terms.name AS term_name
             FROM exams
             LEFT JOIN terms ON terms.id = exams.term_id
             ORDER BY exams.starts_on DESC'
        )->fetchAll();
    }

    public static function results(string $examId = ''): array
    {
        $sql = 'SELECT exam_results.*, exams.name AS exam_name, subjects.name AS subject_name,
                       students.admission_no, CONCAT(students.first_name, " ", students.last_name) AS student_name
                FROM exam_results
                JOIN exams ON exams.id = exam_results.exam_id
                JOIN subjects ON subjects.id = exam_results.subject_id
                JOIN students ON students.id = exam_results.student_id';
        $params = [];
        if ($examId !== '') {
            $sql .= ' WHERE exam_results.exam_id = :exam_id';
            $params['exam_id'] = $examId;
        }
        $sql .= ' ORDER BY exams.starts_on DESC, students.first_name, subjects.name';
        $stmt = Database::connection()->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public static function createExam(array $data): int
    {
        Database::connection()->prepare(
            'INSERT INTO exams (term_id, name, exam_type, starts_on, ends_on, status)
             VALUES (:term_id, :name, :exam_type, :starts_on, :ends_on, :status)'
        )->execute([
            'term_id' => $data['term_id'] ?: null,
            'name' => $data['name'],
            'exam_type' => $data['exam_type'],
            'starts_on' => $data['starts_on'],
            'ends_on' => $data['ends_on'],
            'status' => $data['status'],
        ]);
        return (int)Database::connection()->lastInsertId();
    }

    public static function saveResult(array $data, ?int $userId): int
    {
        $grade = self::grade((float)$data['marks']);
        Database::connection()->prepare(
            'INSERT INTO exam_results (exam_id, student_id, subject_id, marks, grade, remarks, entered_by)
             VALUES (:exam_id, :student_id, :subject_id, :marks, :grade, :remarks, :entered_by)
             ON DUPLICATE KEY UPDATE marks = VALUES(marks), grade = VALUES(grade), remarks = VALUES(remarks), entered_by = VALUES(entered_by)'
        )->execute([
            'exam_id' => $data['exam_id'],
            'student_id' => $data['student_id'],
            'subject_id' => $data['subject_id'],
            'marks' => $data['marks'],
            'grade' => $grade,
            'remarks' => $data['remarks'] ?: null,
            'entered_by' => $userId,
        ]);
        return (int)Database::connection()->lastInsertId();
    }

    public static function grade(float $marks): string
    {
        return match (true) {
            $marks >= 80 => 'A',
            $marks >= 75 => 'A-',
            $marks >= 70 => 'B+',
            $marks >= 65 => 'B',
            $marks >= 60 => 'B-',
            $marks >= 55 => 'C+',
            $marks >= 50 => 'C',
            $marks >= 45 => 'C-',
            $marks >= 40 => 'D',
            default => 'E',
        };
    }
}
