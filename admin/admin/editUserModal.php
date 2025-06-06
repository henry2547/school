<?php

include('session.php');
include('dbconnect.php');

if (!isset($_GET['staffid'])) {
    echo '<div class="modal-body text-danger">Invalid request.</div>';
    exit;
}

$staffid = mysqli_real_escape_string($dbcon, $_GET['staffid']);
$q = mysqli_query($dbcon, "SELECT * FROM userlogin WHERE staffid = '$staffid' LIMIT 1");
if (!$q || mysqli_num_rows($q) == 0) {
    echo '<div class="modal-body text-danger">User not found.</div>';
    exit;
}
$row = mysqli_fetch_assoc($q);
if (!$row) {
    echo '<div class="modal-body text-danger">Error fetching user data.</div>';
    exit;
}
if ($row['staffid'] != $session_id && $row['role'] != 'Admin') {
    echo '<div class="modal-body text-danger">You do not have permission to edit this user.</div>';
    exit;
}
if ($row['role'] == 'Admin' && $session_id != 'Admin') {
    echo '<div class="modal-body text-danger">You cannot edit an admin user.</div>';
    exit;
}
?>
<form method="post" action="updateUser.php">
    <div class="modal-header">
        <h5 class="modal-title">Edit User</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
        <input type="hidden" name="staffid" value="<?php echo htmlspecialchars($row['staffid']); ?>">
        <div class="mb-3">
            <label class="form-label">Staff ID</label>
            <input type="text" class="form-control" value="<?php echo htmlspecialchars($row['staffid']); ?>" disabled>
        </div>
        <div class="mb-3">
            <label for="surname" class="form-label">Surname</label>
            <input type="text" class="form-control" id="surname" name="surname" value="<?php echo htmlspecialchars($row['surname']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="othernames" class="form-label">Other Names</label>
            <input type="text" class="form-control" id="othernames" name="othernames" value="<?php echo htmlspecialchars($row['othernames']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($row['username']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="role" class="form-label">Role</label>
            <select class="form-select" id="role" name="role" required>
                <option value="Admin" <?php if($row['role']=='Admin') echo 'selected'; ?>>Admin</option>
                <option value="Finance" <?php if($row['role']=='Finance') echo 'selected'; ?>>Finance</option>
                <option value="Lecturer" <?php if($row['role']=='Lecturer') echo 'selected'; ?>>Lecturer</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="userStatus" class="form-label">Status</label>
            <select class="form-select" id="userStatus" name="userStatus" required>
                <option value="active" <?php if($row['userStatus']=='active') echo 'selected'; ?>>Active</option>
                <option value="inactive" <?php if($row['userStatus']=='inactive') echo 'selected'; ?>>Inactive</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">New Password (leave blank to keep current)</label>
            <input type="password" class="form-control" id="password" name="password">
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