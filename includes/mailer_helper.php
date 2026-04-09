<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../vendor/autoload.php';

function sendVerificationEmail($email, $name, $token) {
    $mail = new PHPMailer(true);

    try {
        // --- SMTP SETTINGS ---
        // $mail->SMTPDebug = 2; // Enable for debugging
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com'; // Replace with yours
        $mail->SMTPAuth   = true;
        $mail->Username   = 'your-email@gmail.com'; // Replace with yours
        $mail->Password   = 'your-app-password';     // Replace with yours
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // --- EMAIL CONTENT ---
        $mail->setFrom('no-reply@gowilds.com', 'GoWilds Tours');
        $mail->addAddress($email, $name);

        $mail->isHTML(true);
        $mail->Subject = 'Verify Your Account - GoWilds';
        
        $verify_link = "http://" . $_SERVER['HTTP_HOST'] . "/Go_wilds/verify_email.php?token=$token";

        $mail->Body    = "
            <div style='font-family: Arial, sans-serif; padding: 20px; border: 1px solid #eee; border-radius: 10px;'>
                <h2 style='color: #28a745;'>Welcome to GoWilds, $name!</h2>
                <p>Thank you for registering. Please click the button below to verify your email address and activate your account:</p>
                <a href='$verify_link' style='background: #28a745; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block; margin-top: 10px;'>Verify My Account</a>
                <p style='margin-top: 20px; font-size: 12px; color: #777;'>If you did not create an account, please ignore this email.</p>
            </div>
        ";

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Mailer Error: {$mail->ErrorInfo}");
        return false;
    }
}
?>
