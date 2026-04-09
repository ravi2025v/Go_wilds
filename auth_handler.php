<?php
// auth_handler.php
session_start();
require_once 'admin/includes/db.php';

header('Content-Type: application/json');

$action = $_GET['action'] ?? '';
$response = ['status' => 'error', 'message' => 'Invalid action'];

if ($action === 'login') {
    $email = $conn->real_escape_string($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $response['message'] = 'Please fill all details';
    } else {
        $user_res = $conn->query("SELECT * FROM users WHERE email = '$email'");
        if ($user_res && $user_res->num_rows > 0) {
            $user = $user_res->fetch_assoc();
            
            // Allow both hashed and plain password for compatibility
            if (password_verify($password, $user['password']) || $password === $user['password']) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_phone'] = $user['phone'];
                $_SESSION['phone_verified'] = 1; // Force verified
                $response = ['status' => 'success', 'message' => 'Logged in!'];
            } else {
                $response['message'] = 'Invalid password';
            }
        } else {
            $response['message'] = 'User not found';
        }
    }
}

if ($action === 'register') {
    $name = $conn->real_escape_string($_POST['name'] ?? '');
    $email = $conn->real_escape_string($_POST['email'] || '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if (empty($name) || empty($email) || empty($password)) {
        $response['message'] = 'Please fill all details';
    } elseif ($password !== $confirm_password) {
        $response['message'] = 'Passwords do not match';
    } elseif (strlen($password) < 6) {
        $response['message'] = 'Password should be at least 6 characters';
    } else {
        $check_email = $conn->query("SELECT id FROM users WHERE email = '$email'");
        if ($check_email->num_rows > 0) {
            $response['message'] = 'Email already registered';
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $reg_query = "INSERT INTO users (name, email, password, role, created_at) VALUES ('$name', '$email', '$hashed_password', 'user', NOW())";
            
            if ($conn->query($reg_query)) {
                $user_id = $conn->insert_id;
                $_SESSION['user_id'] = $user_id;
                $_SESSION['user_name'] = $name;
                $_SESSION['user_email'] = $email;
                $_SESSION['user_phone'] = ''; // New registration might not have phone yet in this legacy flow
                $_SESSION['phone_verified'] = 1; // Skip verification
                $response = ['status' => 'success', 'message' => 'Account created!'];
            } else {
                $response['message'] = 'Error creating account: ' . $conn->error;
            }
        }
    }
}

if ($action === 'social_login') {
    $data = json_decode(file_get_contents('php://input'), true);
    $token = $data['token'] ?? '';

    if (!empty($token)) {
        // Decode JWT payload (middle part)
        $parts = explode('.', $token);
        if (count($parts) === 3) {
            $payload = json_decode(base64_decode(str_replace(['-', '_'], ['+', '/'], $parts[1])), true);
            
            if ($payload && isset($payload['email'])) {
                $email = $conn->real_escape_string($payload['email']);
                $name = $conn->real_escape_string($payload['name'] ?? 'Social User');
                $google_id = $conn->real_escape_string($payload['sub']);

                // Check if user exists
                $user_res = $conn->query("SELECT * FROM users WHERE email = '$email'");
                if ($user_res->num_rows > 0) {
                    $user = $user_res->fetch_assoc();
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_name'] = $user['name'];
                    $_SESSION['user_email'] = $user['email'];
                    
                    if (empty($user['phone'])) {
                        $_SESSION['phone_verified'] = 0;
                        $response = ['status' => 'success', 'message' => 'Please verify mobile', 'needsPhone' => true];
                    } else {
                        $_SESSION['user_phone'] = $user['phone'];
                        $_SESSION['phone_verified'] = 1;
                        $response = ['status' => 'success', 'message' => 'Logged in!'];
                    }
                } else {
                    // Create new user for social login
                    $reg_query = "INSERT INTO users (name, email, password, created_at, phone_verified) VALUES ('$name', '$email', 'SOCIAL_AUTH_".uniqid()."', NOW(), 0)";
                    if ($conn->query($reg_query)) {
                        $_SESSION['user_id'] = $conn->insert_id;
                        $_SESSION['user_name'] = $name;
                        $_SESSION['user_email'] = $email;
                        $_SESSION['user_phone'] = '';
                        $_SESSION['phone_verified'] = 0;
                        $response = ['status' => 'success', 'message' => 'Social login successful!', 'needsPhone' => true];
                    }
                }
            }
        }
    }
}


if ($action === 'send_otp') {
    $phone = $conn->real_escape_string($_POST['phone'] ?? '');

    if (empty($phone)) {
        $response['message'] = 'Please provide a mobile number.';
    } else {
        // Generate a 4-digit OTP
        $otp = rand(1000, 9999);
        $expiry = date('Y-m-d H:i:s', strtotime('+5 minutes'));

        $name = $conn->real_escape_string($_POST['name'] ?? '');
        $email = $conn->real_escape_string($_POST['email'] ?? '');

        // Store OTP, phone, name and email in session temporarily
        $_SESSION['temp_phone'] = $phone;
        $_SESSION['temp_name'] = $name;
        $_SESSION['temp_email'] = $email;
        $_SESSION['temp_otp'] = $otp;
        $_SESSION['temp_otp_expiry'] = $expiry;

        // In a real application, you would call an SMS API here
        // For development/demo, we'll return it in the response for testing
        $response = [
            'status' => 'success', 
            'message' => 'OTP sent successfully!',
            'debug_otp' => $otp // REMOVE THIS IN PRODUCTION
        ];
    }
}

if ($action === 'verify_otp') {
    $otp_entered = $_POST['otp'] ?? '';
    $phone = $_SESSION['temp_phone'] ?? '';
    $stored_otp = $_SESSION['temp_otp'] ?? '';
    $expiry = $_SESSION['temp_otp_expiry'] ?? '';

    if (empty($otp_entered)) {
        $response['message'] = 'Please enter the OTP.';
    } elseif ($otp_entered != $stored_otp) {
        $response['message'] = 'Invalid OTP.';
    } elseif (date('Y-m-d H:i:s') > $expiry) {
        $response['message'] = 'OTP has expired.';
    } else {
        // OTP Verified!
        
        // If user is already logged in (e.g. via Social Login), link this phone
        if (isset($_SESSION['user_id'])) {
            $user_id = $_SESSION['user_id'];
            // Check if this phone is already taken by ANOTHER user
            $check_phone = $conn->query("SELECT id FROM users WHERE phone = '$phone' AND id != $user_id");
            if ($check_phone->num_rows > 0) {
                 // Phone is already registered to someone else. 
                 // In a real app, maybe merge or error. Here we'll just log into that account.
                 $user = $conn->query("SELECT * FROM users WHERE phone = '$phone'")->fetch_assoc();
                 $_SESSION['user_id'] = $user['id'];
                 $_SESSION['user_name'] = $user['name'];
                 $_SESSION['user_email'] = $user['email'];
                 $_SESSION['user_phone'] = $user['phone'];
                 $_SESSION['phone_verified'] = 1;
                 $response = ['status' => 'success', 'newUser' => false];
            } else {
                // Link phone to current session user
                $conn->query("UPDATE users SET phone = '$phone', phone_verified = 1 WHERE id = $user_id");
                $_SESSION['user_phone'] = $phone;
                $_SESSION['phone_verified'] = 1;
                $response = ['status' => 'success', 'newUser' => false];
            }
        } else {
            // Standard flow: Check if user exists by phone
            $user_res = $conn->query("SELECT * FROM users WHERE phone = '$phone'");
            if ($user_res && $user_res->num_rows > 0) {
                $user = $user_res->fetch_assoc();
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_phone'] = $user['phone'];
                $_SESSION['phone_verified'] = 1;
                $response = ['status' => 'success', 'newUser' => false];
            } else {
                // Auto-signup if we have name and email in session
                $temp_name = $_SESSION['temp_name'] ?? '';
                $temp_email = $_SESSION['temp_email'] ?? '';
                
                if (!empty($temp_name)) {
                    $password = password_hash(uniqid(), PASSWORD_DEFAULT);
                    $sql = "INSERT INTO users (name, email, phone, password, role, phone_verified, created_at) 
                            VALUES ('$temp_name', '$temp_email', '$phone', '$password', 'user', 1, NOW())";
                    
                    if ($conn->query($sql)) {
                        $_SESSION['user_id'] = $conn->insert_id;
                        $_SESSION['user_name'] = $temp_name;
                        $_SESSION['user_email'] = $temp_email;
                        $_SESSION['user_phone'] = $phone;
                        $_SESSION['phone_verified'] = 1;
                        $response = ['status' => 'success', 'newUser' => false];
                    } else {
                        $response['message'] = 'Error creating account: ' . $conn->error;
                    }
                } else {
                    $response = ['status' => 'success', 'newUser' => true];
                }
            }
        }
    }
}

if ($action === 'complete_signup') {
    $name = $conn->real_escape_string($_POST['name'] ?? '');
    $email = $conn->real_escape_string($_POST['email'] ?? '');
    $phone = $_SESSION['temp_phone'] ?? '';

    if (empty($name) || empty($phone)) {
        $response['message'] = 'Name and Phone are required.';
    } else {
        // Create new user
        $password = password_hash(uniqid(), PASSWORD_DEFAULT); // Default random password
        $sql = "INSERT INTO users (name, email, phone, password, role, phone_verified, created_at) 
                VALUES ('$name', '$email', '$phone', '$password', 'user', 1, NOW())";
        
        if ($conn->query($sql)) {
            $_SESSION['user_id'] = $conn->insert_id;
            $_SESSION['user_name'] = $name;
            $_SESSION['user_email'] = $email;
            $_SESSION['user_phone'] = $phone;
            $_SESSION['phone_verified'] = 1;
            $response = ['status' => 'success', 'message' => 'Account created!'];
        } else {
            $response['message'] = 'Error creating account: ' . $conn->error;
        }
    }
}

echo json_encode($response);
?>
