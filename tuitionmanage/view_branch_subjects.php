<?php
include 'master.php';

if (isset($_GET['branch_id'])) {
    $branchId = $_GET['branch_id'];
    $branchSubjects = getBranchSubjects($conn, $branchId);
} else {
    // Redirect to the master dashboard if branch ID is not provided
    header("Location: master_dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Branch Subjects</title>
  
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="./style/viw_branch_student.css">
</head>
<body>
    <div class="container">

        <h1 class="mt-4">View Subjects for Branch <?php echo $branchId; ?></h1>

        <div class="row cd">
    <?php foreach ($branchSubjects as $subject) : ?>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <h5 class="card-title"><?php echo $subject['subject_name']; ?></h5>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>


        <!-- Optional: Add a button to go back to the Master Dashboard -->
        <a href="master_dashboard.php" class="btn btn-primary mt-4">Back to Master Dashboard</a>
    </div>
</body>
</html>
