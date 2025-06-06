<?php
include('session.php');
include('dbconnect.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Courses</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3><i class="bi bi-book"></i> Courses</h3>
            <a href="addCourse.php" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Add Course
            </a>
        </div>
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-primary">
                            <tr>
                                <th>#</th>
                                <th>Course Name</th>
                                <th>Fee (Kshs)</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sn = 0;
                            $courses = mysqli_query($dbcon, "SELECT * FROM courses ORDER BY course_name ASC");
                            if ($courses && mysqli_num_rows($courses) > 0) {
                                while ($row = mysqli_fetch_assoc($courses)) {
                                    $sn++;
                                    echo "<tr>";
                                    echo "<td>{$sn}</td>";
                                    echo "<td>" . htmlspecialchars($row['course_name']) . "</td>";
                                    echo "<td>Kshs " . number_format($row['fee'], 2) . "</td>";
                                    echo "<td>
                                        <a href='editCourse.php?id=" . $row['courseId'] . "' class='btn btn-sm btn-warning'><i class='bi bi-pencil'></i> Edit</a>
                                        <a href='deleteCourse.php?id=" . $row['courseId'] . "' class='btn btn-sm btn-danger' onclick=\"return confirm('Are you sure you want to delete this course?');\"><i class='bi bi-trash'></i> Delete</a>
                                    </td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='5' class='text-center text-muted'>No courses found.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- home button -->
        <div class="d-flex justify-content-center mt-4">
            <a href="index.php" class="btn btn-secondary">
                <i class="bi bi-house-door"></i> Home
            </a>
        </div>
    </div>
</body>
</html>