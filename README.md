# School Management System

A comprehensive web-based School Management System built with PHP and MySQL. This project provides a robust platform for managing students, staff, courses, finances, academic records, and more. It features dedicated portals for administrators and students, each with tailored functionality and a modern, responsive interface.

---

## Modern MVC Build

Phase 1 of the new production-oriented MVC application now lives in:

```text
app/          Controllers, models, views, and core framework helpers
config/       App and database configuration
database/     Normalized MySQL schema and seed data
public/       Front controller, assets, and web entry point
storage/      Runtime logs and generated files
```

Implemented features:

- Secure session login/logout with CSRF protection
- `password_hash()` / `password_verify()` authentication
- Role-based access control for all requested school roles
- Dashboard metrics, activity history, Chart.js visualization, dark/light mode
- User management CRUD with search, role filter, pagination, and audit logs
- Student management with parent links, class allocation, profile details, status, search, filters, and CRUD
- Teacher management with departments, qualifications, staff profile data, and CRUD
- Parent/guardian management and class/stream management
- Daily attendance sheet with class/date filters and bulk save
- Fees module with invoices, payments, balances, payment methods, and recent payment history
- Exams module with exam setup, marks entry, automatic grade calculation, and result filtering
- Normalized schema for Phase 1-3 modules plus seed data

### Run the New MVC App

1. Import the schema. This development schema recreates the `schoolhub` database tables, so export anything important first if you already imported an older copy:

    ```bash
    /opt/lampp/bin/mysql -u root < database/schema.sql
    ```

2. Check database settings in:

    ```text
    config/database.php
    ```

3. Visit:

    ```text
    http://localhost/school/public
    ```

4. Seed admin login:

    ```text
    Email: admin@school.test
    Password: Admin@12345
    ```

5. Seed role portal logins (the password for each is `Admin@12345`):

    ```text
    Student: amina.otieno@school.test
    Teacher: grace.njeri@school.test
    Parent:  mary.otieno@example.test
    ```

    Student accounts open **My School**, showing only their own profile and school records. Teacher accounts open **Teaching Dashboard**, scoped to their profile, assigned classes and subjects, learners, submitted attendance and marks, staff notices, and events. Parent accounts open **Parent Dashboard**, showing only linked children and their fee, attendance, and academic records.

Composer metadata is included for PHP 8.3 and PHPMailer. Run `composer install` when network access is available and email features are being implemented in a later phase.

### Current Development Phases

- Phase 1: Authentication, roles, dashboard, user management.
- Phase 2: Student, teacher, parent, and class management.
- Phase 3: Attendance, fees, and examinations.
- Phase 4: Library catalogue, inventory, transport fleet/routes, hostels, HR employee records, clinic visits, discipline, announcements, events, and certificate requests.
- Phase 5: Operational reports, persisted school settings, audit logging, role permissions, print-ready screens, and responsive/dark-mode UI.

The administration screens under **Operations** use a constrained CRUD registry: only explicitly declared tables and fields are available to each screen. This keeps the broad operational modules maintainable and prevents arbitrary table/column access.

### Verification

All PHP files in the MVC application have passed syntax checks. To perform the database-backed smoke test locally, start MySQL first (for example, from the LAMPP manager as an administrator), then import the schema and visit `http://localhost/school/public`.

---

## Features

### For Administrators
- **Dashboard**: Overview of students, lecturers, courses, and finances.
- **Student Management**: Add, view, edit, and delete student records.
- **Lecturer Management**: Manage lecturer profiles and assignments.
- **Course Management**: Add, edit, and view courses and their fees.
- **Finance Management**: Track payments, view statements, and export reports.
- **Reports & Analytics**: Visualize key metrics (students, lecturers, courses, fees) with charts.
- **User Management**: Manage admin, finance, and lecturer user accounts.
- **Attendance Tracking**: Monitor student attendance records.
- **Document Management**: Upload and manage important school documents.
- **System Settings**: Change password, view system info, and configure settings.

### For Students
- **Personal Dashboard**: Quick overview of fees, payments, and academic progress.
- **Profile Management**: View and update personal information.
- **Academic Records**: View grades, GPA, and enrolled courses.
- **Class Timetable**: Access weekly and semester schedules.
- **Finance**: View fee statements, payment history, and outstanding balances.
- **Assignments**: Track assignments, deadlines, and submission status.
- **Attendance**: View attendance records for each course/unit.
- **Announcements**: Stay updated with the latest school and course announcements.
- **Resources & Downloads**: Access course materials and downloadable resources.
- **Support**: Raise support tickets or queries to the administration.

---

## Technologies Used

- **Backend**: PHP (with MySQLi)
- **Database**: MySQL
- **Frontend**: HTML5, CSS3, Bootstrap 5, JavaScript (with jQuery)
- **Icons**: Bootstrap Icons

---

## Getting Started

These instructions apply to the legacy portal folders. For the new MVC build, use the "Modern MVC Build" section above.

1. **Clone the repository**
    ```bash
    git clone https://github.com/henry2547/school.git
    cd school
    ```

2. **Import the Database**
    - Import the provided SQL file into your MySQL server.

3. **Configure Database Connection**
    - Edit `dbconnect.php` with your database credentials.

4. **Run the Application**
    - Place the project in your web server's root directory (e.g., `/opt/lampp/htdocs/` for XAMPP/LAMPP).
    - Access the system via `http://localhost/school/admin/admin/` for admin or `http://localhost/school/student/portal/` for student or `http://localhost/school/finance/finance/` for finance

---
5. **Passwords**
    - For admin panel, the AdminID is admin100 and password is 1234.
    - For finance panel, the Finance id is finance101 and password is 1234.

## Customization

- You can add more modules (e.g., library, hostel, transport) as needed.
- The system is modular and can be extended for your institution's requirements.

---

## Security Notes

- Passwords are hashed using SHA1 for demonstration. For production, use stronger hashing (e.g., `password_hash()`).
- Always validate and sanitize user input.
- Restrict access to sensitive pages using session checks.

---

## License

This project is open-source.

---

## Credits

Developed by [Henry Muchiri]  
Inspired by real-world school management needs.

---

## Contact

For questions or support, open an issue or contact [henrynjue255@gmail.com](mailto:henrynjue255@gmail.com).
