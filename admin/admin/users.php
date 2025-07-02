<?php
include('session.php');
include('dbconnect.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>User Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3><i class="bi bi-person-gear"></i> User Management</h3>

            <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">
                <i class="bi bi-plus-circle"></i> Add User
            </a>


            <!-- Add User Modal -->
            <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <form class="modal-content" method="post" action="addUser.php">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addUserModalLabel">Add New User</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="staffid" class="form-label">Staff ID</label>
                                <input type="text" class="form-control" id="staffid" name="staffid" required>
                            </div>
                            <div class="mb-3">
                                <label for="surname" class="form-label">Surname</label>
                                <input type="text" class="form-control" id="surname" name="surname" required>
                            </div>
                            <div class="mb-3">
                                <label for="othernames" class="form-label">Other Names</label>
                                <input type="text" class="form-control" id="othernames" name="othernames" required>
                            </div>

                            <div class="mb-3">
                                <label for="role" class="form-label">Role</label>
                                <select class="form-select" id="role" name="role" required>
                                    <option value="" selected disabled>Select Role</option>
                                    <option value="Admin">Admin</option>
                                    <option value="Finance">Finance</option>
                                    <option value="Lecturer">Lecturer</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="courseId" class="form-label">Assign Course</label>
                                <select class="form-select" id="courseId" name="courseId" disabled>
                                    <option value="" selected disabled>Select Course</option>
                                    <?php
                                    $courses = mysqli_query($dbcon, "SELECT courseId, course_name FROM courses ORDER BY course_name ASC");
                                    while ($course = mysqli_fetch_assoc($courses)) {
                                        echo "<option value='{$course['courseId']}'>{$course['course_name']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select" id="status" name="status" required>
                                    <option value="active" selected>Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                <i class="bi bi-x-circle"></i> Cancel
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Add User
                            </button>
                        </div>
                    </form>
                </div>
            </div>


        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle table-striped">
                        <thead class="table-secondary">
                            <tr>
                                <th>#</th>
                                <th>Staff ID</th>
                                <th>Name</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sn = 0;
                            $users = mysqli_query($dbcon, "SELECT * FROM userlogin ORDER BY surname ASC");
                            if ($users && mysqli_num_rows($users) > 0) {
                                while ($row = mysqli_fetch_assoc($users)) {
                                    $sn++;
                                    echo "<tr>";
                                    echo "<td>{$sn}</td>";
                                    echo "<td>" . htmlspecialchars($row['staffid']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['surname'] . ' ' . $row['othernames']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['status']) . "</td>";
                                    echo "<td>";
                                    if ($row['userStatus'] == 'active') {
                                        echo "<span class='badge bg-success'>Active</span>";
                                    } else {
                                        echo "<span class='badge bg-danger'>Inactive</span>";
                                    }
                                    echo "</td>";

                                    echo "<td>
                                        <button type='button' class='btn btn-sm btn-warning editUserBtn' 
                                            data-staffid='" . htmlspecialchars($row['staffid']) . "' 
                                            data-bs-toggle='modal' data-bs-target='#editUserModal'>
                                            <i class='bi bi-pencil'></i> Edit
                                        </button>
                                        <button type='button' class='btn btn-sm btn-danger deleteUserBtn' 
                                            data-staffid='" . htmlspecialchars($row['staffid']) . "' 
                                            data-username='" . htmlspecialchars($row['surname'] . ' ' . $row['othernames']) . "'
                                            data-bs-toggle='modal' data-bs-target='#deleteUserModal'>
                                            <i class='bi bi-trash'></i> Delete
                                        </button>
                                    </td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='6' class='text-center text-muted'>No users found.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Edit User Modal -->
        <div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form method="post" id="editUserForm">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editUserModalLabel">Edit User</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="staffid" id="editStaffId">
                            <div class="mb-3">
                                <label class="form-label">Staff ID</label>
                                <input type="text" class="form-control" id="editStaffIdDisplay" value="<?php echo $row['staffid']; ?>" disabled>
                            </div>
                            <div class="mb-3">
                                <label for="editSurname" class="form-label">Surname</label>
                                <input type="text" class="form-control" id="editSurname" name="surname" value="<?php echo $row['surname']; ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="editOthernames" class="form-label">Other Names</label>
                                <input type="text" class="form-control" id="editOthernames" name="othernames" value="<?php echo $row['othernames']; ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="editRole" class="form-label">Role</label>
                                <select class="form-select" id="editRole" name="role" required>
                                    <option value="Admin" <?php if ($row['role'] == 'Admin') echo 'selected'; ?>>Admin</option>
                                    <option value="Finance" <?php if ($row['role'] == 'Finance') echo 'selected'; ?>>Finance</option>
                                    <option value="Lecturer" <?php if ($row['role'] == 'Lecturer') echo 'selected'; ?>>Lecturer</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="editStatus" class="form-label">Status</label>
                                <select class="form-select" id="editStatus" name="userStatus" required>
                                    <option value="active" <?php if ($row['userStatus'] == 'active') echo 'selected'; ?>>Active</option>
                                    <option value="inactive" <?php if ($row['userStatus'] == 'inactive') echo 'selected'; ?>>Inactive</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="editPassword" class="form-label">New Password (leave blank to keep current)</label>
                                <input type="password" class="form-control" id="editPassword" name="password">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                <i class="bi bi-x-circle"></i> Cancel
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Delete User Modal -->
        <div class="modal fade" id="deleteUserModal" tabindex="-1" aria-labelledby="deleteUserModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <form class="modal-content" method="post" action="deleteUser.php">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteUserModalLabel">Delete User</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="staffid" id="deleteStaffId">
                        <p>Are you sure you want to delete <span id="deleteUserName" class="fw-bold"></span>?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-x-circle"></i> Cancel
                        </button>
                        <button type="submit" class="btn btn-danger">
                            <i class="bi bi-trash"></i> Delete
                        </button>
                    </div>
                </form>
            </div>
        </div>



    </div>

    <div class="d-flex justify-content-center mt-4">
        <a href="index.php" class="btn btn-secondary">
            <i class="bi bi-house-door"></i> Home
        </a>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>


    <script>
        $(document).ready(function() {
            // When Edit button is clicked, fill modal fields with user data from table row
            $('.editUserBtn').on('click', function() {
                var row = $(this).closest('tr');
                var staffid = $(this).data('staffid');
                var surname = row.find('td').eq(2).text().split(' ')[0];
                var othernames = row.find('td').eq(2).text().replace(surname + ' ', '');
                var role = row.find('td').eq(3).text();
                var status = row.find('td').eq(4).find('span').hasClass('bg-success') ? 'active' : 'inactive';

                $('#editStaffId').val(staffid);
                $('#editStaffIdDisplay').val(staffid);
                $('#editSurname').val(surname);
                $('#editOthernames').val(othernames);
                $('#editRole').val(role);
                $('#editStatus').val(status);
                $('#editPassword').val('');
            });

            // Submit Edit User Form via AJAX
            $('#editUserForm').on('submit', function(e) {
                e.preventDefault();
                $.ajax({
                    url: 'updateUser.php',
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        location.reload();
                    },
                    error: function() {
                        alert('Failed to update user. Please try again.');
                    }
                });
            });

            // Delete User Modal setup
            $('.deleteUserBtn').on('click', function() {
                var staffid = $(this).data('staffid');
                var username = $(this).data('username');
                $('#deleteStaffId').val(staffid);
                $('#deleteUserName').text(username);
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            $('#role').on('change', function() {
                if ($(this).val() === 'Lecturer') {
                    $('#courseId').prop('disabled', false);
                } else {
                    $('#courseId').prop('disabled', true).val('');
                }
            });
        });
    </script>

</body>

</html>