<?php
include 'master.php';
include 'update_invoices.php';


if (isset($_POST['addAdmin'])) {
    $adminUsername = $_POST['adminUsername'];

    $adminPassword =$_POST['adminPassword'];
    $adminPassword =md5($adminPassword);
    $adminBranch = $_POST['adminBranch'];

    // Insert admin details into branch_admins table
    addAdmin($conn, $adminUsername, $adminPassword, $adminBranch);
}

// Function to check if a branch is already assigned to an admin
function isBranchAssignedToAdmin($conn, $branchId) {
    $query = "SELECT COUNT(*) FROM branch_admins WHERE branch_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $branchId);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    return $count > 0;
}

// Function to add an admin to the branch_admins table
function addAdmin($conn, $adminUsername, $adminPassword, $adminBranch) {
    $query = "INSERT INTO branch_admins (username, password, branch_id) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sss", $adminUsername, $adminPassword, $adminBranch);

    if ($stmt->execute()) {
        echo '<script>alert("Admin added successfully!");</script>';
    } else {
        echo '<script>alert("Error adding admin: ' . $stmt->error . '");</script>';
    }

    $stmt->close();
}



if (isset($_POST['addSubject'])) {
    $subjectName = $_POST['subjectName'];
    $fees = $_POST['fees'];
    $tax = $_POST['tax'];
    $selectedBranches = $_POST['branches'];

//     echo "selectedBranches: ";
// print_r($selectedBranches);

// echo "subject". $subjectName;
// echo "fees". $fees;
// echo "tax". $tax;

    // Check if at least one branch is selected
    if (empty($selectedBranches)) {
        // Handle the case where no branches are selected
        echo "Please select at least one branch for the subject.";
        exit();
    }
    
    addSubject($conn, $subjectName, $fees, $tax, $selectedBranches);

}



// Function to get the student count for a specific branch
function getBranchStudentCount($conn, $branchId) {
    // Use prepared statements to avoid SQL injection
    $query = "SELECT COUNT(*) FROM branch_students WHERE branch_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $branchId);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    return $count;
}

if (isset($_POST['logout'])) {
    // Perform any additional logout actions if needed
    // For example, destroying the session
    session_destroy();

    // Redirect to the login page after logging out
    header("Location: master_login.php");
    exit();
}

// Check if Master is logged in, otherwise redirect to login page
// You should implement a session-based login system for security

// Fetch branches for dropdown
$branches = getMasterBranches($conn);

// // Fetch Master dashboard data
// $dashboardData = getMasterDashboardData($conn);

// Get the count of total subjects
$totalSubjectsCount = $conn->query("SELECT COUNT(*) FROM subjects")->fetch_row()[0];

//Get the count of total subjects
$totalStudentCount = $conn->query("SELECT COUNT(*) FROM branch_students")->fetch_row()[0];
?>

<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Master Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="masterstyles.css"> <!-- Add your custom styles if needed -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>


    <style>
        .custom-btn {
            width:10%;
            margin-bottom: 10px;
        }

        .top-buttons {
            display: flex;
            justify-content: center;
            margin-bottom: 30px;
           
        }

        .top-buttons button {
            margin: 0 10px; /* Add space between buttons */
        }

        .top-buttons button {
        padding: -2 rem 0.3rem; /* Adjust these values to control height and width */
        height: 3rem;
    }

    /* .top-buttons select {
        height: 1.5rem; 
    } */

    /* .top-buttons form button {
        padding: 0.25rem 1rem; 
    } */
        </style>
</head>

<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-6 offset-md-6 text-right">
                <form action="" method="post">
                    <button type="submit" class="btn btn-outline-danger btn-sm" name="logout">Logout</button>
                </form>
            </div>
        </div>

        <h1 class="text-center mb-4">Welcome, Master!</h1>


<!-- Top Buttons Row -->
<!-- Top Buttons Row -->
<div class="top-buttons text-center">
    <button class="btn btn-info btn-sm my-1" onclick="viewBranchSubjects()">View Subjects</button>
    <button class="btn btn-primary custom-btn btn-sm my-1" onclick="toggleFormVisibility()">Add Subject</button>
    <button class="btn btn-success custom-btn btn-sm my-1" onclick="toggleBranchFormVisibility()">Add Branch</button>
    <!-- Add Admin Button -->
<button class="btn btn-warning custom-btn btn-sm my-1" onclick="toggleAdminFormVisibility()">Add Admin</button>
    <div class="top-buttons text-center">
    <div class="row">
        <div class="col-md-8">
            <!-- Change the form action in master_dashboard.php -->
            <form action="view_invoice_report.php" method="get">
                <div class="form-group">
                    <label for="branch">Select Branch:</label>
                    <select class="form-control" name="branch" required style="width: 100%;">
                        <?php foreach ($branches as $branch) : ?>
                            <option value="<?php echo $branch['branch_name']; ?>"><?php echo $branch['branch_name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-info btn-block btn-sm my-1" style="width: 200%;">View Invoice Report</button>
            </div>
        </form>
    </div>
</div>




</div>


<div class="row mb-4">
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Total Students</h5>
                        <a href="view_student_details.php" class="btn btn-link stretched-link">
                            <?php echo $totalStudentCount; ?>
                        </a>
                        <?php foreach ($branches as $branch) : ?>
                        <div onclick="showBranchStudents('<?php echo $branch['branch_id']; ?>')">
                        <h6><?php echo $branch['branch_name'] . ' :' . getBranchStudentCount($conn, $branch['branch_id']); ?></h6>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            
        
        
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Total Subjects</h5>
                        <a href="view_subject.php" class="btn btn-link stretched-link">
                            <?php echo $totalSubjectsCount; ?>
                        </a>
                    </div>
                </div>
            </div> 
        </div>

  
        <!-- Inside the "Add Subject Form Modal" -->
        <div id="addSubjectModal" class="modal">
            <div class="modal-content">
                <span class="close" onclick="toggleFormVisibility()">&times;</span>
                <form action="master_dashboard.php" method="post">
                    <div class="form-group">
                        <label for="subjectName">Subject Name:</label>
                        <input type="text" class="form-control" name="subjectName" required>
                    </div>

                    <div class="form-group">
                        <label for="fees">Fees:</label>
                        <input type="number" class="form-control" name="fees" required>
                    </div>

                    <div class="form-group">
                        <label for="tax">Tax:</label>
                        <input type="number" class="form-control" name="tax" required>
                    </div>

                    <div class="form-group">
                        <label>Select Branch(es):</label><br>
                        <?php foreach ($branches as $branch) : ?>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" name="branches[]"
                                    value="<?php echo $branch['branch_id']; ?>"
                                    id="branch_<?php echo $branch['branch_id']; ?>">
                                <label class="form-check-label"
                                    for="branch_<?php echo $branch['branch_id']; ?>"><?php echo $branch['branch_name']; ?></label>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block" name="addSubject">Add Subject</button>
                </form>
            </div>
        </div>

        <div class="container mt-4">
            <div class="row">
                <!-- Create a card for each branch -->
                <?php foreach ($branches as $branch) : ?>
                    <div class="col-md-3 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">
                                    <?php echo $branch['branch_name']; ?>
                                    <span
                                        class="badge badge-secondary"><?php echo count(getBranchSubjects($conn, $branch['branch_id'])); ?></span>
                                </h5>
                                <!-- List subjects under the current branch -->
                                <?php $branchSubjects = getBranchSubjects($conn, $branch['branch_id']); ?>
                                <ul class="list-group">
                                    <?php foreach ($branchSubjects as $subject) : ?>
                                        <li class="list-group-item"><?php echo $subject['subject_name']; ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Add Branch Form Modal -->
        <div id="createBranchModal" class="modal">
            <div class="modal-content">
                <span class="close" onclick="toggleBranchFormVisibility()">&times;</span>
                <form action="master.php" method="post">
                    <div class="form-group">
                        <label for="branchName">Branch Name:</label>
                        <input type="text" class="form-control" name="branchName" required>
                    </div>
                    <button type="submit" class="btn btn-success btn-block" name="createBranch">Create Branch</button>
                </form>
            </div>
        </div>

       
 <!-- Inside the "Add Admin Form Modal" -->
 <div class="modal" id="addAdminModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Add Admin</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal Body -->
                <div class="modal-body">
                    <form action="master_dashboard.php" method="post">
                        <!-- Your form fields go here -->
                        <div class="form-group">
                            <label for="adminUsername">Username:</label>
                            <input type="text" class="form-control" name="adminUsername" required>
                        </div>
                        <div class="form-group">
                <label for="adminPassword">Password:</label>
                <input type="password" class="form-control" name="adminPassword" required>
            </div>

            <div class="form-group">
                <label for="adminBranch">Select Branch:</label>
                <select class="form-control" name="adminBranch" required>
                    <?php foreach ($branches as $branch) : ?>
                        <?php if (!isBranchAssignedToAdmin($conn, $branch['branch_id'])) : ?>
                            <option value="<?php echo $branch['branch_id']; ?>"><?php echo $branch['branch_name']; ?></option>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </select>
            </div>
                        <!-- ... (other form fields) -->
                        <button type="submit" class="btn btn-warning btn-block" name="addAdmin">Add Admin</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


        <?php if (isset($loginError)) : ?>
            <p class="mt-4 text-danger"><?php echo $loginError; ?></p>
        <?php endif; ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        function toggleFormVisibility() {
            var modal = document.getElementById("addSubjectModal");

            if (modal.style.visibility === "hidden" || modal.style.visibility === "") {
                // Show the modal
                modal.style.visibility = "visible";
                modal.style.opacity = 1;
            } else {
                // Hide the modal
                modal.style.visibility = "hidden";
                modal.style.opacity = 0;
            }
        }

        function toggleBranchFormVisibility() {
            var modal = document.getElementById("createBranchModal");

            if (modal.style.visibility === "hidden" || modal.style.visibility === "") {
                // Show the modal
                modal.style.visibility = "visible";
                modal.style.opacity = 1;
            } else {
                // Hide the modal
                modal.style.visibility = "hidden";
                modal.style.opacity = 0;
            }
        }

        function viewBranchSubjects() {
            var branchId = prompt("Enter Branch ID:");
            if (branchId) {
                window.location.href = "view_branch_subjects.php?branch_id=" + branchId;
            }
        }
        
        $(document).ready(function () {
            // Initialize Bootstrap's modal
            $('#addAdminModal').modal({
                show: false
            });
        });

        function toggleAdminFormVisibility() {
            // Show/hide the modal using Bootstrap's modal function
            $('#addAdminModal').modal('toggle');
        }
    </script>
</body>

</html>

