<?php

namespace App\Models;

use App\Core\Database;

final class Operations
{
    public static function modules(): array
    {
        return [
            'library' => ['title' => 'Library Books', 'table' => 'library_books', 'permission' => 'operations.view', 'fields' => ['isbn' => 'ISBN', 'title' => 'Title', 'author' => 'Author', 'copies_total' => 'Total Copies:number', 'copies_available' => 'Available Copies:number']],
            'inventory' => ['title' => 'Inventory', 'table' => 'inventory_items', 'permission' => 'operations.view', 'fields' => ['item_code' => 'Item Code', 'name' => 'Item Name', 'category' => 'Category', 'quantity_on_hand' => 'Quantity:number', 'reorder_level' => 'Reorder Level:number', 'unit_cost' => 'Unit Cost:number', 'status' => 'Status:select(active|damaged|retired)']],
            'vehicles' => ['title' => 'Transport Fleet', 'table' => 'vehicles', 'permission' => 'operations.view', 'fields' => ['registration_no' => 'Registration No', 'make_model' => 'Make / Model', 'capacity' => 'Capacity:number', 'driver_name' => 'Driver Name', 'driver_phone' => 'Driver Phone', 'status' => 'Status:select(active|maintenance|inactive)']],
            'routes' => ['title' => 'Transport Routes', 'table' => 'transport_routes', 'permission' => 'operations.view', 'fields' => ['name' => 'Route Name', 'pickup_points' => 'Pickup Points:textarea', 'monthly_fee' => 'Monthly Fee:number', 'status' => 'Status:select(active|inactive)']],
            'hostels' => ['title' => 'Hostels', 'table' => 'hostels', 'permission' => 'operations.view', 'fields' => ['name' => 'Hostel Name', 'gender' => 'Gender:select(male|female|mixed)', 'warden_name' => 'Warden Name', 'status' => 'Status:select(active|inactive)']],
            'employees' => ['title' => 'Employees', 'table' => 'employees', 'permission' => 'operations.view', 'fields' => ['employee_no' => 'Employee No', 'first_name' => 'First Name', 'last_name' => 'Last Name', 'department' => 'Department', 'job_title' => 'Job Title', 'phone' => 'Phone', 'salary' => 'Monthly Salary:number', 'status' => 'Status:select(active|inactive)']],
            'announcements' => ['title' => 'Announcements', 'table' => 'announcements', 'permission' => 'operations.view', 'fields' => ['title' => 'Title', 'body' => 'Message:textarea', 'audience' => 'Audience:select(all|staff|parents|students)', 'published_at' => 'Published At:datetime-local']],
            'events' => ['title' => 'School Events', 'table' => 'school_events', 'permission' => 'operations.view', 'fields' => ['title' => 'Title', 'event_type' => 'Event Type', 'starts_at' => 'Starts At:datetime-local', 'ends_at' => 'Ends At:datetime-local', 'location' => 'Location', 'description' => 'Description:textarea']],
            'clinic' => ['title' => 'Clinic Visits', 'table' => 'clinic_visits', 'permission' => 'operations.view', 'student' => true, 'fields' => ['visited_at' => 'Visited At:datetime-local', 'complaint' => 'Complaint', 'treatment' => 'Treatment:textarea', 'disposition' => 'Disposition:select(returned_to_class|sent_home|referred)']],
            'discipline' => ['title' => 'Discipline Cases', 'table' => 'discipline_cases', 'permission' => 'operations.view', 'student' => true, 'fields' => ['incident_date' => 'Incident Date:date', 'category' => 'Category', 'details' => 'Details:textarea', 'action_taken' => 'Action Taken', 'status' => 'Status:select(open|resolved|escalated)']],
            'certificates' => ['title' => 'Certificate Requests', 'table' => 'certificate_requests', 'permission' => 'operations.view', 'student' => true, 'fields' => ['certificate_type' => 'Certificate Type:select(admission|leaving|bonafide|character|fee_clearance|id_card)', 'status' => 'Status:select(requested|approved|issued|rejected)']],
            'settings' => ['title' => 'School Settings', 'table' => 'school_settings', 'primary_key' => 'setting_key', 'permission' => 'settings.manage', 'manage_permission' => 'settings.manage', 'fields' => ['setting_key' => 'Setting Key', 'setting_value' => 'Value:textarea']],
        ];
    }

    public static function module(string $key): ?array
    {
        return self::modules()[$key] ?? null;
    }

    public static function records(array $module): array
    {
        $table = $module['table'];
        $sql = "SELECT {$table}.*";
        if (!empty($module['student'])) {
            $sql .= ", CONCAT(students.first_name, ' ', students.last_name) AS student_name, students.admission_no";
        }
        $sql .= " FROM {$table}";
        if (!empty($module['student'])) {
            $sql .= " JOIN students ON students.id = {$table}.student_id";
        }
        $sql .= $table === 'school_settings' ? ' ORDER BY setting_key LIMIT 100' : ' ORDER BY id DESC LIMIT 100';
        return Database::connection()->query($sql)->fetchAll();
    }

    public static function find(array $module, string $id): ?array
    {
        $key = $module['primary_key'] ?? 'id';
        $stmt = Database::connection()->prepare("SELECT * FROM {$module['table']} WHERE {$key} = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch() ?: null;
    }

    public static function save(array $module, array $input, string|int|null $id, ?int $userId): int|string
    {
        $data = [];
        foreach ($module['fields'] as $field => $_label) {
            $data[$field] = self::value($input[$field] ?? '');
        }
        if (!empty($module['student'])) {
            $data['student_id'] = (int)($input['student_id'] ?? 0);
        }
        if ($module['table'] === 'announcements') {
            $data['created_by'] = $userId;
        }
        if ($module['table'] === 'clinic_visits') {
            $data['handled_by'] = $userId;
        }
        if ($module['table'] === 'discipline_cases') {
            $data['recorded_by'] = $userId;
        }
        if ($module['table'] === 'certificate_requests' && ($data['status'] ?? '') === 'issued') {
            $data['issued_by'] = $userId;
            $data['issued_at'] = date('Y-m-d H:i:s');
        }

        $pdo = Database::connection();
        if ($id !== null && $id !== '') {
            $key = $module['primary_key'] ?? 'id';
            if ($key !== 'id') {
                unset($data[$key]);
            }
            $assignments = implode(', ', array_map(static fn ($field) => "{$field} = :{$field}", array_keys($data)));
            $pdo->prepare("UPDATE {$module['table']} SET {$assignments} WHERE {$key} = :id")->execute(['id' => $id] + $data);
            return $id;
        }
        $columns = implode(', ', array_keys($data));
        $params = implode(', ', array_map(static fn ($field) => ":{$field}", array_keys($data)));
        $pdo->prepare("INSERT INTO {$module['table']} ({$columns}) VALUES ({$params})")->execute($data);
        return (int)$pdo->lastInsertId();
    }

    public static function delete(array $module, string $id): void
    {
        $key = $module['primary_key'] ?? 'id';
        Database::connection()->prepare("DELETE FROM {$module['table']} WHERE {$key} = :id")->execute(['id' => $id]);
    }

    private static function value(mixed $value): mixed
    {
        if ($value === '') {
            return null;
        }
        return is_string($value) ? trim($value) : $value;
    }
}
