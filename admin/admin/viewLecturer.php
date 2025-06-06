<?php
include('dbconnect.php');
include('header.php');
?>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="bi bi-person-badge-fill"></i> Lecturer List</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="lecturerTable" class="table table-bordered table-hover align-middle">
                            <thead class="table-primary">
                                <tr>
                                    <th>S/N</th>
                                    <th><i class="bi bi-person"></i> Name</th>
                                    <th><i class="bi bi-envelope"></i> Email</th>
                                    <th><i class="bi bi-telephone"></i> Phone</th>
                                    <th><i class="bi bi-building"></i> Department</th>
                                    <th><i class="bi bi-gender-ambiguous"></i> Gender</th>
                                    <th><i class="bi bi-calendar"></i> Added</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $sn = 0;
                                $query = mysqli_query($dbcon, "SELECT lecturer.*, department.deptName 
                                FROM lecturer 
                                JOIN department ON lecturer.department = department.deptId
                                ORDER BY lecturer.created_at DESC");
                                while ($row = mysqli_fetch_array($query)) {
                                    $sn++;
                                ?>
                                <tr>
                                    <td><?php echo $sn; ?></td>
                                    <td><?php echo htmlspecialchars($row['fname'] . ' ' . $row['sname']); ?></td>
                                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                                    <td><?php echo htmlspecialchars($row['phone']); ?></td>
                                    <td><?php echo htmlspecialchars($row['deptName']); ?></td>
                                    <td>
                                        <?php if($row['gender'] == 'male'): ?>
                                            <span class="badge bg-info text-dark"><i class="bi bi-gender-male"></i> Male</span>
                                        <?php else: ?>
                                            <span class="badge bg-warning text-dark"><i class="bi bi-gender-female"></i> Female</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer text-center">
                    <a href="addLecturer.php" class="btn btn-success">
                        <i class="bi bi-person-plus"></i> Add Lecturer
                    </a>
                    <a href="index.php" class="btn btn-primary">
                        <i class="bi bi-house"></i> Home
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- DataTables JS (if not already included globally) -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script>
$(document).ready(function() {
    $('#lecturerTable').DataTable({
        "lengthMenu": [10, 25, 50, 100],
        "pageLength": 10,
        "language": {
            "search": "<i class='bi bi-search'></i> Search:"
        }
    });
});
</script>
</body>
</html>