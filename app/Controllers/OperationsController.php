<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Csrf;
use App\Core\Request;
use App\Core\View;
use App\Models\AuditLog;
use App\Models\Lookup;
use App\Models\Operations;

final class OperationsController
{
    public function index(): void
    {
        $module = $this->resolve();
        Auth::requirePermission($module['permission']);
        View::render('operations/index', ['title' => $module['title'], 'module' => $module, 'moduleKey' => Request::input('module'), 'records' => Operations::records($module), 'students' => !empty($module['student']) ? Lookup::students() : []]);
    }

    public function form(): void
    {
        $module = $this->resolve();
        Auth::requirePermission($module['manage_permission'] ?? 'operations.manage');
        $id = (string)Request::input('id', '');
        View::render('operations/form', ['title' => $id ? 'Edit ' . $module['title'] : 'Add ' . $module['title'], 'module' => $module, 'moduleKey' => Request::input('module'), 'record' => $id ? Operations::find($module, $id) : [], 'students' => !empty($module['student']) ? Lookup::students() : []]);
    }

    public function save(): void
    {
        $module = $this->resolve();
        Auth::requirePermission($module['manage_permission'] ?? 'operations.manage');
        Csrf::verify();
        $id = (string)Request::input('id', '');
        $savedId = Operations::save($module, $_POST, $id !== '' ? $id : null, Auth::id());
        AuditLog::record(Auth::id(), $id ? 'update' : 'create', $module['table'], is_numeric($savedId) ? (int)$savedId : null);
        $_SESSION['flash_success'] = $module['title'] . ' saved successfully.';
        redirect('/operations?module=' . urlencode((string)Request::input('module')));
    }

    public function destroy(): void
    {
        $module = $this->resolve();
        Auth::requirePermission($module['manage_permission'] ?? 'operations.manage');
        Csrf::verify();
        $id = (string)Request::input('id', '');
        Operations::delete($module, $id);
        AuditLog::record(Auth::id(), 'delete', $module['table'], is_numeric($id) ? (int)$id : null);
        $_SESSION['flash_success'] = $module['title'] . ' record deleted.';
        redirect('/operations?module=' . urlencode((string)Request::input('module')));
    }

    private function resolve(): array
    {
        $module = Operations::module((string)Request::input('module', 'library'));
        if (!$module) {
            http_response_code(404);
            View::render('errors/404', ['title' => 'Module not found']);
            exit;
        }
        return $module;
    }
}
