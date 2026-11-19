ALTER TABLE exams
    ADD COLUMN class_stream_id BIGINT UNSIGNED NULL AFTER term_id,
    ADD COLUMN subject_id BIGINT UNSIGNED NULL AFTER class_stream_id,
    ADD COLUMN created_by BIGINT UNSIGNED NULL AFTER subject_id,
    ADD CONSTRAINT fk_exams_class FOREIGN KEY (class_stream_id) REFERENCES class_streams(id) ON DELETE SET NULL,
    ADD CONSTRAINT fk_exams_subject FOREIGN KEY (subject_id) REFERENCES subjects(id) ON DELETE SET NULL,
    ADD CONSTRAINT fk_exams_user FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL;

CREATE TABLE assignments (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    teacher_id BIGINT UNSIGNED NOT NULL,
    class_stream_id BIGINT UNSIGNED NOT NULL,
    subject_id BIGINT UNSIGNED NOT NULL,
    title VARCHAR(190) NOT NULL,
    description TEXT NOT NULL,
    due_at DATETIME NOT NULL,
    status ENUM('published', 'closed') NOT NULL DEFAULT 'published',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_assignments_class_due (class_stream_id, due_at),
    CONSTRAINT fk_assignments_teacher FOREIGN KEY (teacher_id) REFERENCES teachers(id) ON DELETE CASCADE,
    CONSTRAINT fk_assignments_class FOREIGN KEY (class_stream_id) REFERENCES class_streams(id) ON DELETE CASCADE,
    CONSTRAINT fk_assignments_subject FOREIGN KEY (subject_id) REFERENCES subjects(id) ON DELETE CASCADE
) ENGINE=InnoDB;
