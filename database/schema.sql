CREATE DATABASE IF NOT EXISTS schoolhub CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE schoolhub;

SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS audit_logs;
DROP TABLE IF EXISTS exam_results;
DROP TABLE IF EXISTS exams;
DROP TABLE IF EXISTS fee_payments;
DROP TABLE IF EXISTS fee_invoices;
DROP TABLE IF EXISTS attendance_records;
DROP TABLE IF EXISTS teacher_subjects;
DROP TABLE IF EXISTS student_parent;
DROP TABLE IF EXISTS students;
DROP TABLE IF EXISTS teachers;
DROP TABLE IF EXISTS parents;
DROP TABLE IF EXISTS subjects;
DROP TABLE IF EXISTS class_streams;
DROP TABLE IF EXISTS departments;
DROP TABLE IF EXISTS terms;
DROP TABLE IF EXISTS academic_years;
DROP TABLE IF EXISTS library_books;
DROP TABLE IF EXISTS role_permissions;
DROP TABLE IF EXISTS permissions;
DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS roles;
DROP TABLE IF EXISTS school_settings;

SET FOREIGN_KEY_CHECKS = 1;

CREATE TABLE roles (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(120) NOT NULL UNIQUE,
    description VARCHAR(255) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE permissions (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(120) NOT NULL,
    slug VARCHAR(150) NOT NULL UNIQUE,
    module VARCHAR(80) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE role_permissions (
    role_id BIGINT UNSIGNED NOT NULL,
    permission_id BIGINT UNSIGNED NOT NULL,
    PRIMARY KEY (role_id, permission_id),
    CONSTRAINT fk_role_permissions_role FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE CASCADE,
    CONSTRAINT fk_role_permissions_permission FOREIGN KEY (permission_id) REFERENCES permissions(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    role_id BIGINT UNSIGNED NOT NULL,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    email VARCHAR(190) NOT NULL UNIQUE,
    phone VARCHAR(40) NULL,
    password_hash VARCHAR(255) NOT NULL,
    status ENUM('active', 'inactive', 'suspended') NOT NULL DEFAULT 'active',
    two_factor_secret VARCHAR(255) NULL,
    last_login_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_users_role_status (role_id, status),
    CONSTRAINT fk_users_role FOREIGN KEY (role_id) REFERENCES roles(id)
) ENGINE=InnoDB;

CREATE TABLE academic_years (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(40) NOT NULL UNIQUE,
    starts_on DATE NOT NULL,
    ends_on DATE NOT NULL,
    is_current TINYINT(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB;

CREATE TABLE terms (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    academic_year_id BIGINT UNSIGNED NOT NULL,
    name VARCHAR(40) NOT NULL,
    starts_on DATE NOT NULL,
    ends_on DATE NOT NULL,
    is_current TINYINT(1) NOT NULL DEFAULT 0,
    UNIQUE KEY uq_terms_year_name (academic_year_id, name),
    CONSTRAINT fk_terms_year FOREIGN KEY (academic_year_id) REFERENCES academic_years(id)
) ENGINE=InnoDB;

CREATE TABLE departments (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(120) NOT NULL UNIQUE,
    code VARCHAR(30) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE class_streams (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(80) NOT NULL,
    stream VARCHAR(40) NOT NULL,
    class_teacher_id BIGINT UNSIGNED NULL,
    capacity INT UNSIGNED NOT NULL DEFAULT 45,
    status ENUM('active', 'inactive') NOT NULL DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uq_class_stream (name, stream)
) ENGINE=InnoDB;

CREATE TABLE subjects (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    department_id BIGINT UNSIGNED NULL,
    name VARCHAR(120) NOT NULL,
    code VARCHAR(40) NOT NULL UNIQUE,
    status ENUM('active', 'inactive') NOT NULL DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_subjects_department FOREIGN KEY (department_id) REFERENCES departments(id) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE teachers (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    department_id BIGINT UNSIGNED NULL,
    staff_no VARCHAR(60) NOT NULL UNIQUE,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    email VARCHAR(190) NOT NULL UNIQUE,
    phone VARCHAR(40) NULL,
    gender ENUM('male', 'female', 'other') NOT NULL,
    qualification VARCHAR(190) NULL,
    hire_date DATE NULL,
    status ENUM('active', 'leave', 'inactive') NOT NULL DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_teachers_department FOREIGN KEY (department_id) REFERENCES departments(id) ON DELETE SET NULL
) ENGINE=InnoDB;

ALTER TABLE class_streams
    ADD CONSTRAINT fk_class_streams_teacher FOREIGN KEY (class_teacher_id) REFERENCES teachers(id) ON DELETE SET NULL;

CREATE TABLE parents (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    relationship VARCHAR(60) NOT NULL DEFAULT 'Guardian',
    email VARCHAR(190) NULL,
    phone VARCHAR(40) NOT NULL,
    address VARCHAR(255) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_parents_phone (phone)
) ENGINE=InnoDB;

CREATE TABLE students (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    class_stream_id BIGINT UNSIGNED NULL,
    admission_no VARCHAR(60) NOT NULL UNIQUE,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    email VARCHAR(190) NULL,
    phone VARCHAR(40) NULL,
    date_of_birth DATE NULL,
    gender ENUM('male', 'female', 'other') NOT NULL,
    nationality VARCHAR(80) NULL,
    religion VARCHAR(80) NULL,
    medical_notes TEXT NULL,
    emergency_contact VARCHAR(120) NULL,
    previous_school VARCHAR(160) NULL,
    status ENUM('active', 'transferred', 'graduated', 'suspended', 'inactive') NOT NULL DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_students_class_status (class_stream_id, status),
    CONSTRAINT fk_students_class FOREIGN KEY (class_stream_id) REFERENCES class_streams(id) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE student_parent (
    student_id BIGINT UNSIGNED NOT NULL,
    parent_id BIGINT UNSIGNED NOT NULL,
    is_primary TINYINT(1) NOT NULL DEFAULT 0,
    PRIMARY KEY (student_id, parent_id),
    CONSTRAINT fk_student_parent_student FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
    CONSTRAINT fk_student_parent_parent FOREIGN KEY (parent_id) REFERENCES parents(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE teacher_subjects (
    teacher_id BIGINT UNSIGNED NOT NULL,
    subject_id BIGINT UNSIGNED NOT NULL,
    class_stream_id BIGINT UNSIGNED NULL,
    PRIMARY KEY (teacher_id, subject_id, class_stream_id),
    CONSTRAINT fk_teacher_subjects_teacher FOREIGN KEY (teacher_id) REFERENCES teachers(id) ON DELETE CASCADE,
    CONSTRAINT fk_teacher_subjects_subject FOREIGN KEY (subject_id) REFERENCES subjects(id) ON DELETE CASCADE,
    CONSTRAINT fk_teacher_subjects_class FOREIGN KEY (class_stream_id) REFERENCES class_streams(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE attendance_records (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    student_id BIGINT UNSIGNED NOT NULL,
    class_stream_id BIGINT UNSIGNED NULL,
    attendance_date DATE NOT NULL,
    status ENUM('present', 'absent', 'late', 'excused') NOT NULL,
    remarks VARCHAR(255) NULL,
    created_by BIGINT UNSIGNED NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uq_attendance_student_date (student_id, attendance_date),
    INDEX idx_attendance_date_class (attendance_date, class_stream_id),
    CONSTRAINT fk_attendance_student FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
    CONSTRAINT fk_attendance_class FOREIGN KEY (class_stream_id) REFERENCES class_streams(id) ON DELETE SET NULL,
    CONSTRAINT fk_attendance_user FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE fee_invoices (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    student_id BIGINT UNSIGNED NOT NULL,
    term_id BIGINT UNSIGNED NULL,
    invoice_no VARCHAR(80) NOT NULL UNIQUE,
    description VARCHAR(190) NOT NULL,
    amount DECIMAL(12,2) NOT NULL,
    balance DECIMAL(12,2) NOT NULL,
    due_date DATE NOT NULL,
    status ENUM('draft', 'issued', 'part_paid', 'paid', 'overdue', 'void') NOT NULL DEFAULT 'issued',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_fee_invoices_student_status (student_id, status),
    CONSTRAINT fk_fee_invoices_student FOREIGN KEY (student_id) REFERENCES students(id),
    CONSTRAINT fk_fee_invoices_term FOREIGN KEY (term_id) REFERENCES terms(id) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE fee_payments (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    invoice_id BIGINT UNSIGNED NOT NULL,
    receipt_no VARCHAR(80) NOT NULL UNIQUE,
    amount_paid DECIMAL(12,2) NOT NULL,
    payment_method ENUM('cash', 'bank', 'mpesa', 'paypal', 'stripe') NOT NULL,
    payment_reference VARCHAR(120) NULL,
    received_by BIGINT UNSIGNED NULL,
    paid_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_fee_payments_invoice (invoice_id),
    CONSTRAINT fk_fee_payments_invoice FOREIGN KEY (invoice_id) REFERENCES fee_invoices(id),
    CONSTRAINT fk_fee_payments_user FOREIGN KEY (received_by) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE exams (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    term_id BIGINT UNSIGNED NULL,
    name VARCHAR(120) NOT NULL,
    exam_type ENUM('cat', 'midterm', 'endterm', 'mock', 'national') NOT NULL,
    starts_on DATE NOT NULL,
    ends_on DATE NOT NULL,
    status ENUM('draft', 'open', 'closed', 'published') NOT NULL DEFAULT 'draft',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_exams_term FOREIGN KEY (term_id) REFERENCES terms(id) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE exam_results (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    exam_id BIGINT UNSIGNED NOT NULL,
    student_id BIGINT UNSIGNED NOT NULL,
    subject_id BIGINT UNSIGNED NOT NULL,
    marks DECIMAL(5,2) NOT NULL,
    grade VARCHAR(4) NOT NULL,
    remarks VARCHAR(160) NULL,
    entered_by BIGINT UNSIGNED NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uq_exam_result (exam_id, student_id, subject_id),
    INDEX idx_exam_results_student (student_id),
    CONSTRAINT fk_exam_results_exam FOREIGN KEY (exam_id) REFERENCES exams(id) ON DELETE CASCADE,
    CONSTRAINT fk_exam_results_student FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
    CONSTRAINT fk_exam_results_subject FOREIGN KEY (subject_id) REFERENCES subjects(id),
    CONSTRAINT fk_exam_results_user FOREIGN KEY (entered_by) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE library_books (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    isbn VARCHAR(40) NULL,
    title VARCHAR(190) NOT NULL,
    author VARCHAR(190) NOT NULL,
    copies_total INT UNSIGNED NOT NULL DEFAULT 1,
    copies_available INT UNSIGNED NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE audit_logs (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NULL,
    action VARCHAR(80) NOT NULL,
    entity VARCHAR(80) NOT NULL,
    entity_id BIGINT UNSIGNED NULL,
    ip_address VARCHAR(45) NULL,
    user_agent VARCHAR(255) NULL,
    metadata JSON NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_audit_logs_user_created (user_id, created_at),
    INDEX idx_audit_logs_entity (entity, entity_id),
    CONSTRAINT fk_audit_logs_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE school_settings (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(120) NOT NULL UNIQUE,
    setting_value TEXT NULL,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

INSERT INTO roles (name, slug, description) VALUES
('Super Admin', 'super-admin', 'Full platform owner'),
('School Administrator', 'school-administrator', 'Daily school administration'),
('Principal', 'principal', 'Academic and institutional leadership'),
('Deputy Principal', 'deputy-principal', 'Deputy leadership'),
('Bursar', 'bursar', 'Fee and finance operations'),
('Accountant', 'accountant', 'Accounting and reporting'),
('Teacher', 'teacher', 'Teacher portal user'),
('Class Teacher', 'class-teacher', 'Class-level management'),
('Student', 'student', 'Student portal user'),
('Parent', 'parent', 'Parent portal user'),
('Librarian', 'librarian', 'Library operations'),
('Nurse', 'nurse', 'Clinic and medical records'),
('Transport Manager', 'transport-manager', 'Transport operations'),
('Hostel Manager', 'hostel-manager', 'Boarding and hostel operations'),
('Security Officer', 'security-officer', 'Gate and security records'),
('Store Keeper', 'store-keeper', 'Inventory store operations'),
('Admissions Officer', 'admissions-officer', 'Admissions workflow');

INSERT INTO permissions (name, slug, module) VALUES
('View Dashboard', 'dashboard.view', 'dashboard'),
('View Users', 'users.view', 'users'),
('Manage Users', 'users.manage', 'users'),
('View Students', 'students.view', 'students'),
('Manage Students', 'students.manage', 'students'),
('View Teachers', 'teachers.view', 'teachers'),
('Manage Teachers', 'teachers.manage', 'teachers'),
('View Classes', 'classes.view', 'classes'),
('Manage Classes', 'classes.manage', 'classes'),
('View Attendance', 'attendance.view', 'attendance'),
('Manage Attendance', 'attendance.manage', 'attendance'),
('View Finance', 'finance.view', 'finance'),
('Manage Finance', 'finance.manage', 'finance'),
('View Exams', 'exams.view', 'exams'),
('Manage Exams', 'exams.manage', 'exams'),
('View Reports', 'reports.view', 'reports'),
('Manage Settings', 'settings.manage', 'settings');

INSERT INTO role_permissions (role_id, permission_id)
SELECT roles.id, permissions.id
FROM roles
CROSS JOIN permissions
WHERE roles.slug = 'super-admin';

INSERT INTO role_permissions (role_id, permission_id)
SELECT roles.id, permissions.id
FROM roles
JOIN permissions ON permissions.slug IN (
    'dashboard.view', 'users.view', 'students.view', 'students.manage',
    'teachers.view', 'teachers.manage', 'classes.view', 'classes.manage',
    'attendance.view', 'attendance.manage', 'finance.view', 'exams.view', 'exams.manage', 'reports.view'
)
WHERE roles.slug IN ('school-administrator', 'principal', 'deputy-principal', 'admissions-officer');

INSERT INTO role_permissions (role_id, permission_id)
SELECT roles.id, permissions.id
FROM roles
JOIN permissions ON permissions.slug IN ('dashboard.view', 'finance.view', 'finance.manage', 'reports.view')
WHERE roles.slug IN ('bursar', 'accountant');

INSERT INTO role_permissions (role_id, permission_id)
SELECT roles.id, permissions.id
FROM roles
JOIN permissions ON permissions.slug IN ('dashboard.view', 'students.view', 'teachers.view', 'classes.view', 'attendance.view', 'attendance.manage', 'exams.view', 'exams.manage')
WHERE roles.slug IN ('teacher', 'class-teacher');

INSERT INTO role_permissions (role_id, permission_id)
SELECT roles.id, permissions.id
FROM roles
JOIN permissions ON permissions.slug IN ('dashboard.view', 'students.view')
WHERE roles.slug IN ('student', 'parent', 'librarian', 'nurse', 'transport-manager', 'hostel-manager', 'security-officer', 'store-keeper');

INSERT INTO users (role_id, first_name, last_name, email, phone, password_hash, status)
SELECT id, 'System', 'Admin', 'admin@school.test', '+254700000000',
       '$2y$10$FBVJjt7cPSNMKhfVT3HIkOuD3BhUkQP.S5o.3UXai/6Tn6DUotD2S', 'active'
FROM roles WHERE slug = 'super-admin';

INSERT INTO academic_years (name, starts_on, ends_on, is_current) VALUES
('2026', '2026-01-05', '2026-11-20', 1);

INSERT INTO terms (academic_year_id, name, starts_on, ends_on, is_current) VALUES
(1, 'Term 1', '2026-01-05', '2026-04-03', 0),
(1, 'Term 2', '2026-05-04', '2026-08-07', 1),
(1, 'Term 3', '2026-09-01', '2026-11-20', 0);

INSERT INTO departments (name, code) VALUES
('Languages', 'LANG'),
('Mathematics', 'MATH'),
('Sciences', 'SCI'),
('Humanities', 'HUM');

INSERT INTO teachers (department_id, staff_no, first_name, last_name, email, phone, gender, qualification, hire_date, status) VALUES
(1, 'TCH-0001', 'Grace', 'Njeri', 'grace.njeri@school.test', '+254733100001', 'female', 'B.Ed English', '2021-01-12', 'active'),
(2, 'TCH-0002', 'Peter', 'Mwangi', 'peter.mwangi@school.test', '+254733100002', 'male', 'B.Sc Mathematics', '2020-05-18', 'active'),
(3, 'TCH-0003', 'Lilian', 'Achieng', 'lilian.achieng@school.test', '+254733100003', 'female', 'B.Ed Biology', '2022-02-01', 'active');

INSERT INTO class_streams (name, stream, class_teacher_id, capacity, status) VALUES
('Grade 7', 'North', 1, 45, 'active'),
('Grade 7', 'South', 2, 45, 'active'),
('Grade 8', 'East', 3, 45, 'active');

INSERT INTO subjects (department_id, name, code, status) VALUES
(1, 'English', 'ENG', 'active'),
(2, 'Mathematics', 'MATH', 'active'),
(3, 'Integrated Science', 'SCI', 'active'),
(4, 'Social Studies', 'SST', 'active');

INSERT INTO teacher_subjects (teacher_id, subject_id, class_stream_id) VALUES
(1, 1, 1),
(2, 2, 1),
(3, 3, 1);

INSERT INTO parents (first_name, last_name, relationship, email, phone, address) VALUES
('Mary', 'Otieno', 'Mother', 'mary.otieno@example.test', '+254711111111', 'Nairobi'),
('James', 'Kamau', 'Father', 'james.kamau@example.test', '+254722222222', 'Thika'),
('Sarah', 'Wanjiru', 'Guardian', 'sarah.wanjiru@example.test', '+254733333333', 'Kiambu');

INSERT INTO students (class_stream_id, admission_no, first_name, last_name, email, phone, date_of_birth, gender, nationality, religion, medical_notes, emergency_contact, previous_school, status) VALUES
(1, 'ADM-2026-0001', 'Amina', 'Otieno', 'amina.otieno@school.test', NULL, '2013-02-14', 'female', 'Kenyan', 'Christian', 'Mild asthma', '+254711111111', 'Green Valley Primary', 'active'),
(1, 'ADM-2026-0002', 'Brian', 'Kamau', 'brian.kamau@school.test', NULL, '2012-09-03', 'male', 'Kenyan', 'Christian', NULL, '+254722222222', 'Hilltop Academy', 'active'),
(2, 'ADM-2026-0003', 'Leah', 'Wanjiru', 'leah.wanjiru@school.test', NULL, '2013-06-22', 'female', 'Kenyan', 'Muslim', 'Peanut allergy', '+254733333333', 'Bright Future School', 'active');

INSERT INTO student_parent (student_id, parent_id, is_primary) VALUES
(1, 1, 1),
(2, 2, 1),
(3, 3, 1);

INSERT INTO attendance_records (student_id, class_stream_id, attendance_date, status, created_by) VALUES
(1, 1, CURRENT_DATE, 'present', 1),
(2, 1, CURRENT_DATE, 'late', 1),
(3, 2, CURRENT_DATE, 'present', 1);

INSERT INTO fee_invoices (student_id, term_id, invoice_no, description, amount, balance, due_date, status) VALUES
(1, 2, 'INV-2026-0001', 'Term 2 Tuition and Activities', 45000, 15000, '2026-07-15', 'part_paid'),
(2, 2, 'INV-2026-0002', 'Term 2 Tuition and Activities', 45000, 45000, '2026-07-15', 'issued'),
(3, 2, 'INV-2026-0003', 'Term 2 Tuition and Activities', 45000, 0, '2026-07-15', 'paid');

INSERT INTO fee_payments (invoice_id, receipt_no, amount_paid, payment_method, payment_reference, received_by) VALUES
(1, 'RCT-2026-0001', 30000, 'mpesa', 'QAB12CD345', 1),
(3, 'RCT-2026-0002', 45000, 'bank', 'BANK-2026-001', 1);

INSERT INTO exams (term_id, name, exam_type, starts_on, ends_on, status) VALUES
(2, 'Term 2 CAT 1', 'cat', '2026-06-08', '2026-06-12', 'open'),
(2, 'Term 2 Midterm', 'midterm', '2026-07-06', '2026-07-10', 'draft');

INSERT INTO exam_results (exam_id, student_id, subject_id, marks, grade, remarks, entered_by) VALUES
(1, 1, 1, 82, 'A-', 'Strong comprehension', 1),
(1, 1, 2, 76, 'B+', 'Good working', 1),
(1, 2, 1, 68, 'B', 'Improving', 1),
(1, 3, 3, 91, 'A', 'Excellent', 1);

INSERT INTO library_books (isbn, title, author, copies_total, copies_available) VALUES
('9789966001010', 'Integrated Science Form 1', 'Kenya Literature Bureau', 12, 9),
('9789966001027', 'Mathematics Learner Book', 'Longhorn Publishers', 20, 17),
('9789966001034', 'English Grammar Companion', 'Moran Publishers', 10, 10);

INSERT INTO school_settings (setting_key, setting_value) VALUES
('school_name', 'Demo International School'),
('academic_year', '2026'),
('current_term', 'Term 2');
