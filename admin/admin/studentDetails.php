<?php
$studentId = $_GET['studentId'];

require_once('dbconnect.php');
include('header.php');
?>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-9">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <a href="viewStudent.php" class="btn btn-success">
                    <i class="bi bi-arrow-left"></i> Return Home
                </a>
                <button onclick="window_print()" class="btn btn-info">
                    <i class="bi bi-printer"></i> Print
                </button>
            </div>
            <div class="card shadow" id="outprint">
                <div class="card-header bg-success text-white">
                    <h4 class="mb-0"><i class="bi bi-person-lines-fill"></i> Student Details</h4>
                </div>
                <div class="card-body">
                    <?php
                    $query = mysqli_query($dbcon, "SELECT student.*, courses.* 
                        FROM student
                        JOIN courses ON student.courseId = courses.courseId
                        WHERE student.studentStatus = 'approved'
                        AND student.studentId = '$studentId'");
                    if ($row = mysqli_fetch_array($query)) {
                    ?>
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th width="200">Reg No</th>
                                    <td><?php echo $row['reg_no'] ?></td>
                                </tr>
                                <tr>
                                    <th>Firstname</th>
                                    <td><?php echo $row['fname'] ?></td>
                                </tr>
                                <tr>
                                    <th>Secondname</th>
                                    <td><?php echo $row['sname'] ?></td>
                                </tr>
                                <tr>
                                    <th>Email address</th>
                                    <td><?php echo $row['email'] ?></td>
                                </tr>
                                <tr>
                                    <th>Phone number</th>
                                    <td><?php echo $row['phone'] ?></td>
                                </tr>
                                <tr>
                                    <th>Registration Date</th>
                                    <td><?php echo $row['regDate'] ?></td>
                                </tr>
                                <tr>
                                    <th>County</th>
                                    <td><?php echo $row['county'] ?></td>
                                </tr>
                                <tr>
                                    <th>Subcounty</th>
                                    <td><?php echo $row['subcounty'] ?></td>
                                </tr>
                                <tr>
                                    <th>Taking Course</th>
                                    <td><strong><?php echo $row['course_name'] ?></strong></td>
                                </tr>
                            </tbody>
                        </table>
                    <?php
                    } else {
                        echo '<div class="alert alert-warning">Student not found or not approved.</div>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('scripts.php') ?>

<script type="text/javascript">
function window_print() {
    var _head = $('head').clone();
    var _p = $('#outprint').clone();
    var _html = $('<div>');
    _html.append("<head>" + _head.html() + "</head>");
    _html.append("<h3 class='text-center'>SCHOOL MANAGEMENT SYSTEM</h3>");
    _html.append(_p);
    var nw = window.open("", "_blank", "width=900,height=800");
    nw.document.write(_html.html());
    nw.document.close();
    nw.print();
    setTimeout(() => {
        nw.close();
    }, 500);
}
</script>
</body>
</html>