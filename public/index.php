<?php

declare(strict_types=1);

spl_autoload_register(function (string $class): void {
    $prefix = 'App\\';
    if (!str_starts_with($class, $prefix)) {
        return;
    }

    $relative = str_replace('\\', '/', substr($class, strlen($prefix)));
    $file = dirname(__DIR__) . '/app/' . $relative . '.php';
    if (is_file($file)) {
        require $file;
    }
});

require dirname(__DIR__) . '/app/Core/helpers.php';

$config = require dirname(__DIR__) . '/config/app.php';
date_default_timezone_set($config['timezone']);
session_name($config['session_name']);
session_start();

use App\Controllers\AuthController;
use App\Controllers\AttendanceController;
use App\Controllers\ClassController;
use App\Controllers\DashboardController;
use App\Controllers\ExamController;
use App\Controllers\FeeController;
use App\Controllers\ParentController;
use App\Controllers\ParentPortalController;
use App\Controllers\OperationsController;
use App\Controllers\ReportController;
use App\Controllers\StudentController;
use App\Controllers\StudentPortalController;
use App\Controllers\TeacherController;
use App\Controllers\TeacherPortalController;
use App\Controllers\UserController;
use App\Core\Router;

$router = new Router();
$router->get('/', [DashboardController::class, 'index']);
$router->get('/login', [AuthController::class, 'showLogin']);
$router->post('/login', [AuthController::class, 'login']);
$router->post('/logout', [AuthController::class, 'logout']);
$router->get('/dashboard', [DashboardController::class, 'index']);
$router->get('/users', [UserController::class, 'index']);
$router->get('/users/create', [UserController::class, 'create']);
$router->post('/users', [UserController::class, 'store']);
$router->get('/users/edit', [UserController::class, 'edit']);
$router->post('/users/update', [UserController::class, 'update']);
$router->post('/users/delete', [UserController::class, 'destroy']);
$router->get('/students', [StudentController::class, 'index']);
$router->get('/students/create', [StudentController::class, 'create']);
$router->post('/students', [StudentController::class, 'store']);
$router->get('/students/edit', [StudentController::class, 'edit']);
$router->post('/students/update', [StudentController::class, 'update']);
$router->post('/students/delete', [StudentController::class, 'destroy']);
$router->get('/student-portal', [StudentPortalController::class, 'index']);
$router->get('/teacher-portal', [TeacherPortalController::class, 'index']);
$router->post('/teacher-portal/announcement', [TeacherPortalController::class, 'announce']);
$router->get('/parent-portal', [ParentPortalController::class, 'index']);
$router->get('/teachers', [TeacherController::class, 'index']);
$router->get('/teachers/create', [TeacherController::class, 'create']);
$router->post('/teachers', [TeacherController::class, 'store']);
$router->get('/teachers/edit', [TeacherController::class, 'edit']);
$router->post('/teachers/update', [TeacherController::class, 'update']);
$router->post('/teachers/delete', [TeacherController::class, 'destroy']);
$router->get('/parents', [ParentController::class, 'index']);
$router->get('/parents/create', [ParentController::class, 'create']);
$router->post('/parents', [ParentController::class, 'store']);
$router->get('/parents/edit', [ParentController::class, 'edit']);
$router->post('/parents/update', [ParentController::class, 'update']);
$router->post('/parents/delete', [ParentController::class, 'destroy']);
$router->get('/classes', [ClassController::class, 'index']);
$router->get('/classes/create', [ClassController::class, 'create']);
$router->post('/classes', [ClassController::class, 'store']);
$router->get('/classes/edit', [ClassController::class, 'edit']);
$router->post('/classes/update', [ClassController::class, 'update']);
$router->post('/classes/delete', [ClassController::class, 'destroy']);
$router->get('/attendance', [AttendanceController::class, 'index']);
$router->post('/attendance', [AttendanceController::class, 'store']);
$router->get('/fees', [FeeController::class, 'index']);
$router->post('/fees/invoice', [FeeController::class, 'invoice']);
$router->post('/fees/payment', [FeeController::class, 'payment']);
$router->get('/exams', [ExamController::class, 'index']);
$router->post('/exams/exam', [ExamController::class, 'exam']);
$router->post('/exams/result', [ExamController::class, 'result']);
$router->get('/operations', [OperationsController::class, 'index']);
$router->get('/operations/form', [OperationsController::class, 'form']);
$router->post('/operations/save', [OperationsController::class, 'save']);
$router->post('/operations/delete', [OperationsController::class, 'destroy']);
$router->get('/reports', [ReportController::class, 'index']);
$router->dispatch();
