<?php
include('dbconnect.php');
include('header.php');
?>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow">
                <div class="card-header bg-success text-white">
                    <h4 class="mb-0"><i class="bi bi-cash-stack"></i> Student Finance List</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="myTable-trans" class="table table-bordered table-hover align-middle">
                            <thead class="table-success">
                                <tr>
                                    <th>S/N</th>
                                    <th><i class="bi bi-person-badge"></i> Regno</th>
                                    <th><i class="bi bi-person"></i> Fullname</th>
                                    <th><i class="bi bi-book"></i> Course</th>
                                    <th><i class="bi bi-currency-dollar"></i> Fee</th>
                                    <th><i class="bi bi-gear"></i> Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $sn = 0;
                                $query = mysqli_query($dbcon, "SELECT student.*, courses.* 
                                    FROM student
                                    JOIN courses ON student.courseId = courses.courseId
                                    WHERE student.studentStatus = 'approved'");
                                while ($row = mysqli_fetch_array($query)) {
                                    $regNo = $row['reg_no'];
                                    $sn++;
                                ?>
                                    <tr>
                                        <td><?php echo $sn; ?></td>
                                        <td><?php echo $row['reg_no']; ?></td>
                                        <td><?php echo $row['fname']; ?> <?php echo $row['sname']; ?></td>
                                        <td><?php echo $row['course_name']; ?></td>
                                        <td>Kshs <?php echo number_format($row['fee'], 2); ?></td>
                                        <td>
                                            <a href="paymentDetails.php?reg_no=<?php echo $regNo; ?>" class="btn btn-success btn-sm" title="View Details">
                                                <i class="bi bi-eye"></i> Details
                                            </a>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer text-center">
                    <a href="index.php" class="btn btn-primary">
                        <i class="bi bi-house"></i> Home
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('scripts.php'); ?>

<!-- DataTables JS (if not already included globally) -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script>
    $(document).ready(function() {
        $('#myTable-trans').DataTable({
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