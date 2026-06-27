<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Csrf;
use App\Core\Request;
use App\Core\View;
use App\Models\AuditLog;
use App\Models\Lookup;
use App\Models\Student;

final class StudentController
{
    public function index(): void
    {
        Auth::requirePermission('students.view');
        $search = trim((string)Request::input('search', ''));
        $classId = trim((string)Request::input('class_id', ''));
        View::render('students/index', [
            'title' => 'Students',
            'students' => Student::paginate($search, $classId, max(1, (int)Request::input('page', 1))),
            'classes' => Lookup::classes(),
            'search' => $search,
            'classId' => $classId,
        ]);
    }

    public function create(): void
    {
        Auth::requirePermission('students.manage');
        View::render('students/form', $this->formData(['admission_no' => Student::nextAdmissionNo()], 'Create Student'));
    }

    public function store(): void
    {
        Auth::requirePermission('students.manage');
        Csrf::verify();
        $data = $this->data();
        $errors = $this->validate($data);
        if ($errors) {
            View::render('students/form', $this->formData($data, 'Create Student', $errors));
            return;
        }
        $id = Student::create($data);
        AuditLog::record(Auth::id(), 'create', 'students', $id, ['admission_no' => $data['admission_no']]);
        $_SESSION['flash_success'] = 'Student saved successfully.';
        redirect('/students');
    }

    public function edit(): void
    {
        Auth::requirePermission('students.manage');
        $student = Student::find((int)Request::input('id'));
        if (!$student) {
            http_response_code(404);
            View::render('errors/404', ['title' => 'Student not found']);
            return;
        }
        View::render('students/form', $this->formData($student, 'Edit Student'));
    }

    public function update(): void
    {
        Auth::requirePermission('students.manage');
        Csrf::verify();
        $id = (int)Request::input('id');
        $data = $this->data();
        $errors = $this->validate($data);
        if ($errors) {
            $data['id'] = $id;
            View::render('students/form', $this->formData($data, 'Edit Student', $errors));
            return;
        }
        Student::update($id, $data);
        AuditLog::record(Auth::id(), 'update', 'students', $id, ['admission_no' => $data['admission_no']]);
        $_SESSION['flash_success'] = 'Student updated successfully.';
        redirect('/students');
    }

    public function destroy(): void
    {
        Auth::requirePermission('students.manage');
        Csrf::verify();
        $id = (int)Request::input('id');
        Student::delete($id);
        AuditLog::record(Auth::id(), 'delete', 'students', $id);
        $_SESSION['flash_success'] = 'Student deleted successfully.';
        redirect('/students');
    }

    private function data(): array
    {
        $data = Request::only(['class_stream_id', 'admission_no', 'first_name', 'last_name', 'email', 'phone', 'date_of_birth', 'gender', 'nationality', 'religion', 'medical_notes', 'emergency_contact', 'previous_school', 'status']);
        $data['status'] = in_array($data['status'], ['active', 'transferred', 'graduated', 'suspended', 'inactive'], true) ? $data['status'] : 'active';
        $data['gender'] = in_array($data['gender'], ['male', 'female', 'other'], true) ? $data['gender'] : 'other';
        $data['parent_ids'] = $_POST['parent_ids'] ?? [];
        return $data;
    }

    private function validate(array $data): array
    {
        $errors = [];
        foreach (['admission_no', 'first_name', 'last_name', 'gender'] as $field) {
            if (($data[$field] ?? '') === '') {
                $errors[$field] = 'Required.';
            }
        }
        if (($data['email'] ?? '') !== '' && !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Use a valid email.';
        }
        return $errors;
    }

    private function formData(array $student, string $title, array $errors = []): array
    {
        return ['title' => $title, 'student' => $student, 'classes' => Lookup::classes(), 'parents' => Lookup::parents(), 'errors' => $errors];
    }
}
