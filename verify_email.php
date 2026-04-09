<?php
// verify_email.php
require_once 'admin/includes/db.php';
session_start();

$token = $_GET['token'] ?? '';

if (!empty($token)) {
    $token = $conn->real_escape_string($token);
    
    // Check if token exists
    $user_res = $conn->query("SELECT * FROM users WHERE verification_token = '$token'");
    
    if ($user_res && $user_res->num_rows > 0) {
        $user = $user_res->fetch_assoc();
        
        // Update user status
        $update = $conn->query("UPDATE users SET status = 'verified', verification_token = NULL WHERE id = {$user['id']}");
        
        if ($update) {
            $msg = "<h2 class='text-success'>Email verified successfully!</h2>
                    <p>You can now login to your account.</p>
                    <a href='index.php' class='main-btn'>Back to Home</a>";
        } else {
            $msg = "<h2 class='text-danger'>Error verifying email.</h2>";
        }
    } else {
        $msg = "<h2 class='text-danger'>Invalid or expired verification token.</h2>";
    }
} else {
    $msg = "<h2 class='text-warning'>Token is missing.</h2>";
}

$title = "Verify Email - GoWilds";
include 'includes/header.php';
?>

<section class="verify-section pt-150 pb-150 text-center">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="verify-content p-5 shadow-lg rounded-20 bg-white">
                    <?php echo $msg; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
