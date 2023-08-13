<?php
// Assuming you have established a database connection
include('config.php');

// Check if the user is logged in
if (!isset($_SESSION["id"]) || empty($_SESSION["id"])) {
    header("location: login.php"); // Redirect to login page if not logged in
    exit();
}

// User is logged in, retrieve the user ID
$user_id = $_SESSION["id"];

// Fetch the approved admission data for the user, including school fee
if (isset($_GET['print_form_id']) && is_numeric($_GET['print_form_id'])) {
    $requestedFormId = $_GET['print_form_id'];
    $query = "SELECT registration_form.*, schools.name AS school_name, schools.fee, cities.name AS city_name 
              FROM registration_form
              INNER JOIN schools ON registration_form.school_id = schools.school_id
              INNER JOIN cities ON registration_form.city_id = cities.id
              WHERE registration_form.user_id = '$user_id' AND registration_form.register_status = 'Approved' AND registration_form.form_id = '$requestedFormId'";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $admissionData = mysqli_fetch_assoc($result);
    }
}

// Include the FPDF library
require('FPDF-master/fpdf.php');

class PDF extends FPDF
{
    function Header()
    {
        global $admissionData;
        $this->SetFont('Arial', 'B', 14);
        $this->Cell(0, 10, 'Welcome to ' . $admissionData['school_name'] . ', ' . $admissionData['city_name'], 0, 1, 'C');
        $this->Ln(10);
    }

    function Footer()
    {
        global $admissionData;
        $this->SetY(-15);
        $this->SetFont('Arial', '', 10);
        $this->Cell(0, 10, 'Thanks for applying to ' . $admissionData['school_name'], 0, 0, 'C');
    }
}

// Create a new PDF instance
$pdf = new PDF();
$pdf->AddPage();

// Content
if ($admissionData) {
    $pdf->SetFont('Arial', '', 12);

    $pdf->Cell(0, 10, 'Fee Voucher', 0, 1, 'C');
    $pdf->Ln(5);

    // Add details to the PDF
    $pdf->Cell(40, 10, 'Form ID:', 0);
    $pdf->Cell(0, 10, $admissionData['form_id'], 0, 1);

    $pdf->Cell(40, 10, 'School Name:', 0);
    $pdf->Cell(0, 10, $admissionData['school_name'], 0, 1);

    $pdf->Cell(40, 10, 'City:', 0);
    $pdf->Cell(0, 10, $admissionData['city_name'], 0, 1);

    $pdf->Cell(40, 10, 'Student Name:', 0);
    $pdf->Cell(0, 10, $admissionData['student_name'], 0, 1);

    $pdf->Cell(40, 10, 'Student CNIC:', 0);
    $pdf->Cell(0, 10, $admissionData['student_cnic'], 0, 1);

    $pdf->Cell(40, 10, "Father's Name:", 0);
    $pdf->Cell(0, 10, $admissionData['student_father_name'], 0, 1);

    $pdf->Cell(40, 10, "Father's CNIC:", 0);
    $pdf->Cell(0, 10, $admissionData['student_father_cnic'], 0, 1);

    $pdf->Cell(40, 10, "Mother's Name:", 0);
    $pdf->Cell(0, 10, $admissionData['student_mother_name'], 0, 1);

    $pdf->Cell(40, 10, 'Address:', 0);
    $pdf->Cell(0, 10, $admissionData['student_address'], 0, 1);

    $pdf->Cell(40, 10, 'Contact Number:', 0);
    $pdf->Cell(0, 10, $admissionData['student_contact_number'], 0, 1);

    $pdf->Cell(40, 10, 'School Fee:', 0);
    $pdf->Cell(0, 10, $admissionData['fee'], 0, 1);

    // Output the PDF
    $pdf->Output('fee_voucher.pdf', 'I'); // I: Inline display, D: Download
} else {
    echo "No voucher data found.";
}
?>
