<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once 'vendor/autoload.php';

function sendBookingEmail($bookingData, $tourTitle) {
    $mail = new PHPMailer(true);

    try {
        // --- SMTP CONFIGURATION (Aapki Details Yahan Ayengi) ---
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com'; // Gmail SMTP
        $mail->SMTPAuth   = true;
        $mail->Username   = 'gowilds@gmail.com'; // Go Wilds Email
        $mail->Password   = 'YOUR_APP_PASSWORD'; // App Password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Recipients
        $mail->setFrom('gowilds@gmail.com', 'Go Wilds - Bookings');
        $mail->addAddress($bookingData['email'], $bookingData['name']);
        $mail->addBCC('admin@gowilds.com');

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Booking Confirmation: ' . $tourTitle;
        
        // Email Template (Sundar Design)
        $mail->Body    = "
        <div style='font-family: Arial, sans-serif; max-width: 600px; margin: auto; border: 1px solid #eee; padding: 20px;'>
            <div style='text-align: center; border-bottom: 2px solid #F7921E; padding-bottom: 20px;'>
                <h2 style='color: #333;'>Booking Confirmed! ✅</h2>
            </div>
            <p>Hi <strong>{$bookingData['name']}</strong>,</p>
            <p>Thank you for choosing <strong>Go Wilds</strong>. We have received your booking request for the following tour:</p>
            
            <div style='background: #f9f9f9; padding: 15px; border-radius: 5px; margin: 20px 0;'>
                <p><strong>Tour:</strong> {$tourTitle}</p>
                <p><strong>Booking Date:</strong> {$bookingData['booking_date']}</p>
                <p><strong>Pax:</strong> {$bookingData['adults']} Adults, {$bookingData['children']} Children</p>
                <p><strong>Total Paid:</strong> $".number_format($bookingData['total_price'], 2)."</p>
            </div>
            
            <p>Our team will contact you shortly on <strong>{$bookingData['phone']}</strong> for further details.</p>
            
            <p style='color: #777; font-size: 12px; margin-top: 30px;'>
                Regards,<br>Team Go Wilds<br>
                <a href='https://www.gowilds.com'>www.gowilds.com</a>
            </p>
        </div>";

        $mail->send();
        return true;
    } catch (Exception $e) {
        // Email fail hone par server error na dikhe
        return "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
?>
