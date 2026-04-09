<?php
// otp_handler.php
session_start();
require_once 'admin/includes/db.php';

header('Content-Type: application/json');

$action = $_GET['action'] ?? '';
$response = ['status' => 'error', 'message' => 'Invalid action'];

// Check if user is logged in or if we have a temporary phone in session
$user_id = $_SESSION['user_id'] ?? null;
$temp_phone = $_SESSION['temp_phone'] ?? null;

if ($action === 'send_otp') {
    $phone = $conn->real_escape_string($_POST['phone'] ?? $temp_phone);

    if (empty($phone)) {
        $response['message'] = 'Please provide a mobile number.';
    } else {
        // Generate a 4-digit OTP
        $otp = rand(1000, 9999);
        $expiry = date('Y-m-d H:i:s', strtotime('+5 minutes'));

        // Store in session for all flows
        $_SESSION['temp_phone'] = $phone;
        $_SESSION['temp_otp'] = $otp;
        $_SESSION['temp_otp_expiry'] = $expiry;

        // If user is logged in, also update their record
        if ($user_id) {
            $sql = "UPDATE users SET phone = '$phone', otp_code = '$otp', otp_expiry = '$expiry' WHERE id = $user_id";
            $conn->query($sql);
        }
        
        // SIMULATE SMS SENDING
        $_SESSION['debug_last_otp'] = $otp; 
        
        $response = [
            'status' => 'success', 
            'message' => 'OTP sent successfully!',
            'debug_otp' => $otp // For testing
        ];
    }
}

if ($action === 'verify_otp') {
    $otp_entered = $conn->real_escape_string($_POST['otp'] ?? '');
    $phone = $_SESSION['temp_phone'] ?? '';
    $stored_otp = $_SESSION['temp_otp'] ?? '';
    $expiry = $_SESSION['temp_otp_expiry'] ?? '';

    if (empty($otp_entered)) {
        $response['message'] = 'Please enter the verification code.';
    } elseif ($otp_entered != $stored_otp) {
        $response['message'] = 'Invalid OTP.';
    } elseif (date('Y-m-d H:i:s') > $expiry) {
        $response['message'] = 'OTP has expired.';
    } else {
        // OTP Verified!
        if ($user_id) {
            $conn->query("UPDATE users SET phone_verified = 1, otp_code = NULL, otp_expiry = NULL WHERE id = $user_id");
            $_SESSION['phone_verified'] = 1;
        }
        $response = ['status' => 'success', 'message' => 'Mobile number verified successfully!'];
    }
}

echo json_encode($response);
?>
