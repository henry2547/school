ALTER TABLE announcements
    ADD COLUMN class_stream_id BIGINT UNSIGNED NULL AFTER audience,
    ADD CONSTRAINT fk_announcements_class
        FOREIGN KEY (class_stream_id) REFERENCES class_streams(id) ON DELETE SET NULL;

DELETE role_permissions
FROM role_permissions
JOIN roles ON roles.id = role_permissions.role_id
JOIN permissions ON permissions.id = role_permissions.permission_id
WHERE roles.slug IN ('teacher', 'class-teacher')
  AND permissions.slug IN (
      'students.view',
      'teachers.view',
      'classes.view',
      'attendance.view',
      'attendance.manage',
      'exams.view',
      'exams.manage'
  );

INSERT IGNORE INTO role_permissions (role_id, permission_id)
SELECT roles.id, permissions.id
FROM roles
JOIN permissions ON permissions.slug IN ('dashboard.view', 'teacher.portal')
WHERE roles.slug IN ('teacher', 'class-teacher');
