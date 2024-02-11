<?php
include 'branch_admin_dashboard.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Fetch student details and subject details based on the provided invoice number
    $invoiceNumber = $_POST['invoice_Number']; // Updated to match the form field name

    $response = [];

    // Query to fetch student details
    $studentQuery = "SELECT student_name, contact_number, address FROM invoices WHERE invoice_number = ?";
    $studentStmt = $conn->prepare($studentQuery);
    $studentStmt->bind_param("s", $invoiceNumber);
    $studentStmt->execute();
    $studentResult = $studentStmt->get_result();

    // If student details are found
    if ($studentResult->num_rows > 0) {
        $studentRow = $studentResult->fetch_assoc();

        // Add student details to the response
        $response['studentDetails'] = [
            'studentName' => $studentRow['student_name'],
            'address' => $studentRow['address'],
            'contact' => $studentRow['contact_number'],
        ];
    }

    // Query to fetch subject details from invoices table
    $subjectQuery = "SELECT subject_name, grand_total, paid_amount, due_amount FROM invoices WHERE invoice_number = ?";
    $subjectStmt = $conn->prepare($subjectQuery);
    $subjectStmt->bind_param("s", $invoiceNumber);
    $subjectStmt->execute();
    $subjectResult = $subjectStmt->get_result();

    // If subject details are found
    if ($subjectResult->num_rows > 0) {
        $subjectDetails = [];

        // Iterate through each subject
        while ($subjectRow = $subjectResult->fetch_assoc()) {
            $subjectDetails[] = [
                'subjectName' => $subjectRow['subject_name'],
                'grandTotal' => $subjectRow['grand_total'],
                'paidAmount' => $subjectRow['paid_amount'],
                'dueAmount' => $subjectRow['due_amount'],
            ];
        }

        // Add subject details to the response
        $response['subjectDetails'] = $subjectDetails;
    }

    // Set the content type header to JSON
    header('Content-Type: application/json');

    // Return the response as JSON
    echo json_encode($response);

    $studentStmt->close();
    $subjectStmt->close();
    $conn->close();
} else {
    // Set the content type header to JSON for error responses
    header('Content-Type: application/json');

    echo json_encode(['error' => 'Invalid request']);
}
?>
