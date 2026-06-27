<?php

namespace App\Models;

use App\Core\Database;

final class Lookup
{
    public static function classes(): array
    {
        return Database::connection()->query(
            'SELECT class_streams.*, CONCAT(teachers.first_name, " ", teachers.last_name) AS teacher_name
             FROM class_streams
             LEFT JOIN teachers ON teachers.id = class_streams.class_teacher_id
             ORDER BY class_streams.name, class_streams.stream'
        )->fetchAll();
    }

    public static function teachers(): array
    {
        return Database::connection()->query(
            'SELECT id, staff_no, CONCAT(first_name, " ", last_name) AS name FROM teachers ORDER BY first_name, last_name'
        )->fetchAll();
    }

    public static function students(): array
    {
        return Database::connection()->query(
            'SELECT id, admission_no, CONCAT(first_name, " ", last_name) AS name FROM students ORDER BY admission_no'
        )->fetchAll();
    }

    public static function parents(): array
    {
        return Database::connection()->query(
            'SELECT id, CONCAT(first_name, " ", last_name) AS name, phone FROM parents ORDER BY first_name, last_name'
        )->fetchAll();
    }

    public static function departments(): array
    {
        return Database::connection()->query('SELECT id, name FROM departments ORDER BY name')->fetchAll();
    }

    public static function subjects(): array
    {
        return Database::connection()->query('SELECT id, name, code FROM subjects ORDER BY name')->fetchAll();
    }

    public static function terms(): array
    {
        return Database::connection()->query(
            'SELECT terms.id, CONCAT(academic_years.name, " - ", terms.name) AS name
             FROM terms
             JOIN academic_years ON academic_years.id = terms.academic_year_id
             ORDER BY academic_years.starts_on DESC, terms.starts_on'
        )->fetchAll();
    }

    public static function exams(): array
    {
        return Database::connection()->query('SELECT id, name FROM exams ORDER BY starts_on DESC')->fetchAll();
    }
}
