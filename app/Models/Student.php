<?php

namespace App\Models;

use App\Core\Database;
use PDO;

final class Student
{
    public static function paginate(string $search = '', string $classId = '', int $page = 1, int $perPage = 12): array
    {
        $where = ['1 = 1'];
        $params = [];
        if ($search !== '') {
            $where[] = '(students.admission_no LIKE :search OR students.first_name LIKE :search OR students.last_name LIKE :search OR students.email LIKE :search)';
            $params['search'] = "%{$search}%";
        }
        if ($classId !== '') {
            $where[] = 'students.class_stream_id = :class_id';
            $params['class_id'] = $classId;
        }
        $whereSql = implode(' AND ', $where);
        $count = Database::connection()->prepare("SELECT COUNT(*) FROM students WHERE {$whereSql}");
        $count->execute($params);
        $total = (int)$count->fetchColumn();
        $offset = max(0, ($page - 1) * $perPage);
        $stmt = Database::connection()->prepare(
            "SELECT students.*, CONCAT(class_streams.name, ' ', class_streams.stream) AS class_name
             FROM students
             LEFT JOIN class_streams ON class_streams.id = students.class_stream_id
             WHERE {$whereSql}
             ORDER BY students.created_at DESC
             LIMIT :limit OFFSET :offset"
        );
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->bindValue('limit', $perPage, PDO::PARAM_INT);
        $stmt->bindValue('offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return ['items' => $stmt->fetchAll(), 'total' => $total, 'pages' => max(1, (int)ceil($total / $perPage)), 'page' => $page];
    }

    public static function find(int $id): ?array
    {
        $stmt = Database::connection()->prepare('SELECT * FROM students WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $student = $stmt->fetch();
        if (!$student) {
            return null;
        }
        $links = Database::connection()->prepare('SELECT parent_id FROM student_parent WHERE student_id = :id');
        $links->execute(['id' => $id]);
        $student['parent_ids'] = array_column($links->fetchAll(), 'parent_id');
        return $student;
    }

    public static function create(array $data): int
    {
        $stmt = Database::connection()->prepare(
            'INSERT INTO students (class_stream_id, admission_no, first_name, last_name, email, phone, date_of_birth, gender, nationality, religion, medical_notes, emergency_contact, previous_school, status)
             VALUES (:class_stream_id, :admission_no, :first_name, :last_name, :email, :phone, :date_of_birth, :gender, :nationality, :religion, :medical_notes, :emergency_contact, :previous_school, :status)'
        );
        $stmt->execute(self::params($data));
        $id = (int)Database::connection()->lastInsertId();
        self::syncParents($id, $data['parent_ids'] ?? []);
        return $id;
    }

    public static function update(int $id, array $data): void
    {
        Database::connection()->prepare(
            'UPDATE students SET class_stream_id = :class_stream_id, admission_no = :admission_no, first_name = :first_name,
             last_name = :last_name, email = :email, phone = :phone, date_of_birth = :date_of_birth, gender = :gender,
             nationality = :nationality, religion = :religion, medical_notes = :medical_notes, emergency_contact = :emergency_contact,
             previous_school = :previous_school, status = :status WHERE id = :id'
        )->execute(['id' => $id] + self::params($data));
        self::syncParents($id, $data['parent_ids'] ?? []);
    }

    public static function delete(int $id): void
    {
        Database::connection()->prepare('DELETE FROM students WHERE id = :id')->execute(['id' => $id]);
    }

    public static function nextAdmissionNo(): string
    {
        $year = date('Y');
        $count = (int)Database::connection()->query('SELECT COUNT(*) FROM students')->fetchColumn() + 1;
        return sprintf('ADM-%s-%04d', $year, $count);
    }

    private static function params(array $data): array
    {
        return [
            'class_stream_id' => $data['class_stream_id'] ?: null,
            'admission_no' => $data['admission_no'],
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'] ?: null,
            'phone' => $data['phone'] ?: null,
            'date_of_birth' => $data['date_of_birth'] ?: null,
            'gender' => $data['gender'],
            'nationality' => $data['nationality'] ?: null,
            'religion' => $data['religion'] ?: null,
            'medical_notes' => $data['medical_notes'] ?: null,
            'emergency_contact' => $data['emergency_contact'] ?: null,
            'previous_school' => $data['previous_school'] ?: null,
            'status' => $data['status'],
        ];
    }

    private static function syncParents(int $studentId, array $parentIds): void
    {
        $pdo = Database::connection();
        $pdo->prepare('DELETE FROM student_parent WHERE student_id = :student_id')->execute(['student_id' => $studentId]);
        $stmt = $pdo->prepare('INSERT INTO student_parent (student_id, parent_id, is_primary) VALUES (:student_id, :parent_id, :is_primary)');
        foreach (array_values(array_filter($parentIds)) as $index => $parentId) {
            $stmt->execute(['student_id' => $studentId, 'parent_id' => $parentId, 'is_primary' => $index === 0 ? 1 : 0]);
        }
    }
}
