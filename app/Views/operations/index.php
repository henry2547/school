<div class="page-heading">
    <div><p class="eyebrow">Operations</p><h1><?= e($module['title']) ?></h1></div>
    <?php if (\App\Core\Auth::can($module['manage_permission'] ?? 'operations.manage')): ?><a class="btn btn-primary" href="<?= e(url('/operations/form?module=' . urlencode($moduleKey))) ?>"><i class="bi bi-plus-lg me-2"></i>Add Record</a><?php endif; ?>
</div>
<section class="panel">
    <div class="table-responsive"><table class="table align-middle"><thead><tr>
        <?php if (!empty($module['student'])): ?><th>Student</th><?php endif; ?>
        <?php foreach ($module['fields'] as $label): ?><th><?= e(explode(':', $label)[0]) ?></th><?php endforeach; ?>
        <?php if (\App\Core\Auth::can($module['manage_permission'] ?? 'operations.manage')): ?><th class="text-end">Actions</th><?php endif; ?>
    </tr></thead><tbody>
    <?php foreach ($records as $record): ?><tr>
        <?php if (!empty($module['student'])): ?><td><div class="fw-semibold"><?= e($record['student_name']) ?></div><small class="text-muted"><?= e($record['admission_no']) ?></small></td><?php endif; ?>
        <?php foreach ($module['fields'] as $field => $label): $value = $record[$field] ?? ''; ?><td><?= e(is_string($value) && strlen($value) > 70 ? substr($value, 0, 70) . '...' : $value) ?></td><?php endforeach; ?>
        <?php $recordId = $record[$module['primary_key'] ?? 'id']; ?><?php if (\App\Core\Auth::can($module['manage_permission'] ?? 'operations.manage')): ?><td class="text-end"><a class="btn btn-sm btn-outline-secondary" href="<?= e(url('/operations/form?module=' . urlencode($moduleKey) . '&id=' . urlencode((string)$recordId))) ?>"><i class="bi bi-pencil"></i></a><form class="d-inline" action="<?= e(url('/operations/delete')) ?>" method="post" data-confirm="Delete this record?"><?= csrf_field() ?><input type="hidden" name="module" value="<?= e($moduleKey) ?>"><input type="hidden" name="id" value="<?= e($recordId) ?>"><button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button></form></td><?php endif; ?>
    </tr><?php endforeach; ?>
    <?php if ($records === []): ?><tr><td colspan="<?= count($module['fields']) + (!empty($module['student']) ? 2 : 1) ?>" class="text-center text-muted py-4">No records yet.</td></tr><?php endif; ?>
    </tbody></table></div>
</section>
