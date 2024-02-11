<?php
// Include your master.php file for database connection and functions
include 'master.php';

// Assuming you have functions to get subjects, delete subject, and update subject
// Replace these with your actual functions
function getSubjects($conn) {
    $query = "SELECT * FROM subjects";
    $result = $conn->query($query);

    // Fetch subjects into an array
    $subjects = [];
    while ($row = $result->fetch_assoc()) {
        $subjects[] = $row;
    }

    return $subjects;
}

function deleteSubject($conn, $subjectId) {
    $subjectId = $conn->real_escape_string($subjectId); // Sanitize input
    $conn->query("DELETE FROM subjects WHERE id = $subjectId");
}

function updateSubject($conn, $subjectId, $newSubjectData) {
    $subjectId = $conn->real_escape_string($subjectId); // Sanitize input
    $subjectName = $conn->real_escape_string($newSubjectData['subject_name']);
    $fees = $conn->real_escape_string($newSubjectData['fees']);
    $tax = $conn->real_escape_string($newSubjectData['tax']);

    // Update the subject with the given ID
    $conn->query("UPDATE subjects SET subject_name = '$subjectName', fees = $fees, tax = $tax WHERE id = $subjectId");
}

// Fetch subjects
$subjects = getSubjects($conn);

// Handle delete request
if (isset($_GET['delete']) && isset($_GET['subject_id'])) {
    $subjectIdToDelete = $_GET['subject_id'];
    deleteSubject($conn, $subjectIdToDelete);
    header("Location: view_subject.php"); // Redirect after deleting to refresh the page
    exit();
}

// Handle edit request
if (isset($_GET['edit']) && isset($_GET['subject_id'])) {
    $subjectIdToEdit = $_GET['subject_id'];
    // Implement the logic to load subject data for editing
    // Example: $subjectToEdit = getSubjectById($conn, $subjectIdToEdit);
    // Assume $subjectToEdit is an array containing subject data
    // You can then populate a form with this data for editing
}

// Handle update request
if (isset($_POST['update_subject'])) {
    $subjectIdToUpdate = $_POST['subject_id'];
    $newSubjectData = [
        'subject_name' => $_POST['updated_subject_name'],
        'fees' => $_POST['updated_fees'],
        'tax' => $_POST['updated_tax']
    ];
    updateSubject($conn, $subjectIdToUpdate, $newSubjectData);
    header("Location: view_subject.php"); // Redirect after updating to refresh the page
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Subjects</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h1 class="mt-4">View Subjects</h1>

        <table class="table mt-4">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Subject Name</th>
                    <th>Fees</th>
                    <th>Tax</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($subjects as $subject) : ?>
                    <tr>
                        <td><?php echo $subject['id']; ?></td>
                        <td><?php echo $subject['subject_name']; ?></td>
                        <td><?php echo $subject['fees']; ?></td>
                        <td><?php echo $subject['tax']; ?></td>
                        <td>
    <button onclick="toggleEditFormVisibility(<?php echo $subject['id']; ?>, '<?php echo $subject['subject_name']; ?>', <?php echo $subject['fees']; ?>, <?php echo $subject['tax']; ?>)" class="btn btn-warning btn-sm">Edit</button>
    <a href="view_subject.php?delete=true&subject_id=<?php echo $subject['id']; ?>" class="btn btn-danger btn-sm">Delete</a>
</td>

                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Add any additional content or features as needed -->

        <!-- Optional: Add a button to go back to the Master Dashboard -->
        <a href="master_dashboard.php" class="btn btn-primary mt-4">Back to Master Dashboard</a>

        <!-- Edit Subject Modal -->
       <!-- Edit Subject Modal -->
<!-- Edit Subject Modal -->
<div id="editSubjectModal" class="modal">
    <div class="modal-content edit-modal">
        <span class="close" onclick="toggleEditFormVisibility()">&times;</span>
        <form action="view_subject.php" method="post">
            <div class="form-group">
                <label for="updated_subject_name">Subject Name:</label>
                <input type="text" class="form-control" name="updated_subject_name" required>
            </div>
            <div class="form-group">
                <label for="updated_fees">Fees:</label>
                <input type="number" class="form-control" name="updated_fees" required>
            </div>
            <div class="form-group">
                <label for="updated_tax">Tax:</label>
                <input type="number" class="form-control" name="updated_tax" required>
            </div>

            <input type="hidden" name="subject_id" value="">
            <button type="submit" class="btn btn-primary" name="update_subject">Update Subject</button>
        </form>
    </div>
</div>

<script>
    function toggleEditFormVisibility(subjectId, subjectName, fees, tax) {
        var modal = document.getElementById("editSubjectModal");

        if (modal.style.display === "none" || modal.style.display === "") {
            // Show the modal
            modal.style.display = "block";

            // Center the modal on the screen
            var modalContent = modal.querySelector(".modal-content");
            modalContent.style.marginTop = (window.innerHeight - modalContent.clientHeight) / 2 + "px";
            modalContent.style.marginLeft = (window.innerWidth - modalContent.clientWidth) / 2 + "px";

            // Populate the form with subject data for editing
            document.getElementsByName("subject_id")[0].value = subjectId;
            document.getElementsByName("updated_subject_name")[0].value = subjectName;
            document.getElementsByName("updated_fees")[0].value = fees;
            document.getElementsByName("updated_tax")[0].value = tax;
        } else {
            // Hide the modal
            modal.style.display = "none";
        }
    }
</script>

