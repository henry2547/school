# School Management System

A comprehensive web-based School Management System built with PHP and MySQL. This project provides a robust platform for managing students, staff, courses, finances, academic records, and more. It features dedicated portals for administrators and students, each with tailored functionality and a modern, responsive interface.

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

1. **Clone the repository**
    ```bash
    git clone https://github.com/henry3547/school.git
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
    - For finance panel, the Finance id is finance100 and password is 1234.

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

This project is open-source and available under the [MIT License](LICENSE).

---

## Credits

Developed by [Henry Muchiri]  
Inspired by real-world school management needs.

---

## Contact

For questions or support, open an issue or contact [henrynjue255@gmail.com](mailto:your.email@example.com).