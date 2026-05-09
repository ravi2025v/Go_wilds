<?php
include 'admin/includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nationality = $_POST['nationality'];
    $country_id = $_POST['travelling_to_id'];
    $service_id = $_POST['visa_service_id'];
    $start_date = $_POST['start_date'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $gender = $_POST['gender'];
    $passport_no = $_POST['passport_no'];
    $dob = $_POST['dob'];
    $place_issue = $_POST['place_issue'];
    $issue_date = $_POST['issue_date'];
    $expiry_date = $_POST['expiry_date'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    
    // In a real scenario, you'd handle file uploads here
    // For now, we'll just save the text data
    $documents = json_encode(['passport' => 'pending', 'profile' => 'pending']);

    $sql = "INSERT INTO visa_applications 
            (country_id, service_id, first_name, last_name, email, phone, passport_no, dob, gender, place_issue, issue_date, expiry_date, status, documents) 
            VALUES 
            ('$country_id', '$service_id', '$first_name', '$last_name', '$email', '$phone', '$passport_no', '$dob', '$gender', '$place_issue', '$issue_date', '$expiry_date', 'pending', '$documents')";

    if ($conn->query($sql)) {
        echo json_encode(['status' => 'success', 'message' => 'Application submitted successfully!']);
    } else {
        echo json_encode(['status' => 'error', 'message' => $conn->error]);
    }
}
?>
