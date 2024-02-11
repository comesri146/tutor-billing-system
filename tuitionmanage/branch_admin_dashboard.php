<?php
ob_start();
include 'admin.php';
include 'update_invoices.php';
include 'session_helper.php';
// include 'pay_due.php';

// // Fetch subject details for the particular student
// $studentID = getStudentIDByContact($conn, $_POST['contact']); // Assuming you have a function to get student ID by contact

// // Fetch all subject details for the given student
// $subjectDetails = fetchSubjectDetailsForStudent($conn, $studentID);


if (isset($_POST['logout'])) {
    // Perform any additional logout actions if needed
    // For example, destroying the session
    session_destroy();

    // Redirect to the login page after logging out
    header("Location: master_login.php");
    exit();
}


// $invoiceDate = date("Y-m-d");
// echo "Invoice Date: " . $invoiceDate;
if (isset($_SESSION['branch_id'])) {
    $branchID = $_SESSION['branch_id'];
    // Print the branch name for the given branch ID
$branchName = getBranchNameById($conn, $branchID);
// echo "<br>Branch Name: " . $branchName;
//     // Now you can use $branchID as needed in this file
//     echo "Branch ID from session: " . $branchID;
} else {
    // Handle the case where "branch_id" is not set
    echo "Error: Branch ID is not set in the session.";
    // Redirect or handle accordingly
    // header("Location: master_login.php");
    // exit();
}
// Function to get the branch name by ID
function getBranchNameById($conn, $branchID) {
    $query = "SELECT branch_name FROM branches WHERE branch_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $branchID);
    $stmt->execute();
    $stmt->bind_result($branchName);
    $stmt->fetch();
    $stmt->close();

    return $branchName;
}


// Function to get the total number of students for a particular branch
function getTotalStudentsCount($conn, $branchID) {
    $result = $conn->query("SELECT COUNT(*) FROM branch_students WHERE branch_id = '$branchID'");
    return $result->fetch_row()[0];
}



// Display the total number of students for the current branch
$totalStudentsCount = getTotalStudentsCount($conn, $branchID);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Branch Admin Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="adminstyles.css"> <!-- Add your custom styles if needed -->
    <style>    
    </style>
<!-- Include jQuery, Popper.js, and Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<style>
        /* Custom styles for bigger square buttons */
        .btn-square {
            width: 150px; /* Adjust the width as needed */
            height: 100px; /* Adjust the height as needed */
            font-size: 18px; /* Adjust the font size as needed */
        }
    </style>
</head>
<body style="height: 100%; overflow: hidden;">

<div class="container mt-6">

        <div class="header text-center">
            <h1>Welcome, <?php echo $branchName; ?> Branch Admin</h1>
        </div>

        <div class="float-right">
            <form action="" method="post">
                <button type="submit" class="btn btn-outline-danger btn-sm" name="logout">Logout</button>
            </form>
        </div>

        <div class="row mt-3">
    <div class="col-md-3">
        <button id="addStudentBtn" class="btn btn-primary btn-block btn-square mt-3" data-toggle="modal"
            data-target="#addStudentModal">Add Student</button>
    </div>

    <div class="col-md-3">
        <form id="generateInvoiceForm" action="invoice_operations.php" method="post">
            <!-- ... -->
            <button type="submit" name="generateInvoice"
                class="btn btn-success btn-block btn-square mt-3">Generate Invoice</button>
        </form>
    </div>

    <div class="col-md-3">
        <button id="payDueBtn" class="btn btn-warning btn-block btn-square mt-3" data-toggle="modal"
            data-target="#payDueModal">Pay Due</button>
    </div>

    <div class="col-md-3">
        <button id="reverseInvoiceBtn" class="btn btn-danger btn-block btn-square mt-3" style="margin-left: 80px;"
            data-toggle="modal" data-target="#reverseInvoiceModal">Reverse Invoice</button>
    </div>

    <div class="col-md-3">
        <a href="view_invoice_report.php?branch=<?php echo urlencode($branchName); ?>"
            class="btn btn-success btn-block btn-square mt-3 pt-4 text-center">View Invoices</a>
    </div>
</div>



        </div>
    <?php if (isset($_POST['generateInvoice'])) :?>
        <div class="alert alert-success mt-2" role="alert">
            Invoice generated successfully!
        </div>
    <?php endif; ?>

    <?php
    // Handle Add Student Form Submission
    if (isset($_POST['addStudent'])) {
        // Call the function to add a student
        addStudent($conn, $branchID, $_POST['studentName'], $_POST['contact'], $_POST['address'], $_POST['parentName']);

        // Refresh the page to update the student count
        header("Location: branch_admin_dashboard.php");
        exit();
    }
    ?>

    <?php if (isset($loginError)) : ?>
        <p class="mt-4 text-danger"><?php echo $loginError; ?></p>
    <?php endif; ?>
</div>

<!-- Add Student Modal -->
<div class="modal fade" id="addStudentModal" tabindex="-1" role="dialog" aria-labelledby="addStudentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addStudentModalLabel">Add Student</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Add Student Form -->
                <form action="branch_admin.php" method="post">
                    <input type="hidden" name="branch_id" value="<?php echo isset($_SESSION['branch_id']) ? $_SESSION['branch_id'] : ''; ?>">

                    <div class="form-group">
                        <label for="studentName">Student Name:</label>
                        <input type="text" name="studentName" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="contact">Contact:</label>
                        <input type="text" name="contact" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="address">Address:</label>
                        <input type="text" name="address" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="parentName">Parent Name:</label>
                        <input type="text" name="parentName" class="form-control" required>
                    </div>

                    <button type="submit" name="addStudent" class="btn btn-primary">Add Student</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Pay Due Modal -->
<div class="modal fade" id="payDueModal" tabindex="-1" role="dialog" aria-labelledby="payDueModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="payDueModalLabel">Pay Due</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Pay Due Form -->
                <form id="payDueForm" action="pay_due.php" method="post">
                    <div class="form-group">
                        <label for="invoiceNumber">Invoice Number:</label>
                        <input type="text" name="invoiceNumber" id="invoiceNumber" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="studentName">Student Name:</label>
                        <input type="text" name="studentName" id="studentName" class="form-control" readonly>
                    </div>

                    <div class="form-group">
                        <label for="dueAmount">Due Amount:</label>
                        <input type="text" name="dueAmount" id="dueAmount" class="form-control" readonly>
                    </div>

                    <div class="form-group">
                        <label for="actualAmount">Actual Amount:</label>
                        <input type="text" name="actualAmount" class="form-control" required>
                    </div>

                    <button type="submit" name="payDue" class="btn btn-success">Pay Due</button>
                </form>
            </div>
        </div>
    </div>
</div>




<!-- Reverse Invoice Modal -->
<!-- Reverse Invoice Modal -->
<div class="modal fade" id="reverseInvoiceModal" tabindex="-1" role="dialog" aria-labelledby="reverseInvoiceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reverseInvoiceModalLabel">Reverse Invoice</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- General Information Page -->
                <div id="generalInfoPage">
                    <form id="generalInfoForm" action="reverse_invoice.php" method="post">
                        <!-- Add your fields for general information -->
                        <div class="form-group">
                            <label for="invoice_Number">Invoice Number:</label>
                            <input type="text" name="invoice_Number" id="invoice_Number" class="form-control" required >
                        </div>

                        <div class="form-group">
                            <label for="studentName">Student Name:</label>
                            <input type="text" name="studentName" id="studentName" class="form-control" readonly >
                        </div>
                        <div class="form-group">
                            <label for="address">Address:</label>
                            <input type="text" name="address" id="address" class="form-control" readonly >
                        </div>
                        <div class="form-group">
                            <label for="contact">Contact:</label>
                            <input type="text" name="contact" id="contact" class="form-control" readonly >
                        </div>

                        <!-- Add other fields as needed -->

                        <!-- Next button to go to the next page -->
                        <button type="button" id="nextToSubjectDetails" class="btn btn-primary">Next</button>
                    </form>
                </div>

                <!-- Subject Details Page -->
                <div id="subjectDetailsPage" style="display: none;">
                    <div class="container mt-5">
                        <h2>Subject Details</h2>

                        <form id="subjectDetailsForm" action="reverse_invoice.php" method="post">
                            <!-- Display subject details for the particular student -->
                            <div id="subjectDetailsContainer"></div>

                            <!-- Add New Subject Button -->
                            <button type="button" id="addSubjectRow" class="btn btn-secondary">Add Subject</button>

                            <!-- Back button to go back to the General Information page -->
                            <button type="button" id="backToGeneralInfo" class="btn btn-primary mt-2">Back to General Information</button>

                            <!-- Submit button for subject details page -->
                            <button type="submit" name="reverseInvoice" class="btn btn-danger mt-2">Reverse Invoice</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        // Handle switching between pages in the modal
        $("#nextToSubjectDetails").click(function () {
            $("#generalInfoPage").hide();
            $("#subjectDetailsPage").show();
        });

        $("#backToGeneralInfo").click(function () {
            $("#subjectDetailsPage").hide();
            $("#generalInfoPage").show();
        });

        // Handle fetching student details when invoice number is entered
        $("#invoice_Number").on("input", function () {
            const invoiceNumber = $(this).val().trim();

            // Check if the invoice number is provided
            if (invoiceNumber !== "") {
                // Fetch student details
                $.ajax({
                    type: "POST",
                    url: "reverse_invoice.php",
                    data: { invoice_Number: invoiceNumber }, // Updated to match the form field name
                    dataType: "json",
                    success: function (data) {
                        if (data.studentDetails) {
                            $("#studentName").val(data.studentDetails.student_name); // Updated key to match the response structure
                            $("#address").val(data.studentDetails.address);
                            $("#contact").val(data.studentDetails.contact_number);
                        }

                        // Clear previous content
                        const subjectDetailsContainer = $("#subjectDetailsContainer");
                        subjectDetailsContainer.html('');

                        if (data.subjectDetails) {
                            // Dynamically generate subject details form fields based on the response
                            data.subjectDetails.forEach(subjectDetail => {
                                // Create new input fields for subject name, price, grand total, due amount, etc.
                                const newSubjectRow = `
                                    <div class="form-group">
                                        <label for="subjectName">Subject Name:</label>
                                        <input type="text" class="form-control" name="subjectName[]" value="${subjectDetail.subject_name}" readonly>

                                        <label for="price">Subject Price:</label>
                                        <input type="text" class="form-control" name="price[]" value="${subjectDetail.price}" readonly>

                                        <label for="grandTotal">Grand Total:</label>
                                        <input type="text" class="form-control" name="grandTotal[]" value="${subjectDetail.grand_total}" readonly>

                                        <label for="paidAmount">Paid Amount:</label>
                                        <input type="text" class="form-control" name="paidAmount[]" value="${subjectDetail.paid_amount}" readonly>

                                        <label for="dueAmount">Due Amount:</label>
                                        <input type="text" class="form-control" name="dueAmount[]" value="${subjectDetail.due_amount}" readonly>

                                        <hr>
                                    </div>`;

                                // Append the new row to the container
                                subjectDetailsContainer.append(newSubjectRow);
                            });
                        }

                        // Switch to the subject details page
                        $("#generalInfoPage").hide();
                        $("#subjectDetailsPage").show();
                    },
                    error: function (error) {
                        console.error('Error fetching student details:', error);
                    }
                });
            } else {
                // Handle the case where the invoice number is not provided
                alert("Please enter the invoice number");
            }
        });

        // Handle adding a new subject row
        $("#addSubjectRow").click(function () {
            // Add a new row for entering subject details dynamically
            const subjectDetailsContainer = $("#subjectDetailsContainer");

            // Create new input fields for subject name, price, grand total, due amount, etc.
            const newSubjectRow = `
                <div class="form-group">
                    <label for="subjectName">Subject Name:</label>
                    <input type="text" class="form-control" name="subjectName[]" readonly>

                    <label for="price">Subject Price:</label>
                    <input type="text" class="form-control" name="price[]" readonly>

                    <label for="grandTotal">Grand Total:</label>
                    <input type="text" class="form-control" name="grandTotal[]" readonly>

                    <label for="paidAmount">Paid Amount:</label>
                    <input type="text" class="form-control" name="paidAmount[]" readonly>

                    <label for="dueAmount">Due Amount:</label>
                    <input type="text" class="form-control" name="dueAmount[]" readonly>

                    <hr>
                </div>`;

            // Append the new row to the container
            subjectDetailsContainer.append(newSubjectRow);
        });
    });







$(document).ready(function() {
    $("#invoiceNumber").on("input", function() {
        var invoiceNumber = $(this).val();
        $.ajax({
            url: "get_invoice_details.php", // Update with the correct path
            type: "POST",
            data: { invoiceNumber: invoiceNumber },
            success: function(response) {
                var data = JSON.parse(response);
                $("#studentName").val(data.studentName);
                $("#dueAmount").val(data.dueAmount);
            },
            error: function(xhr, status, error) {
                console.error("Error:", error);
            }
        });
    });
});
</script>
</body>
</html>
<?php ob_end_flush();?>
