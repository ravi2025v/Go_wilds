<?php
// verify_mobile.php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once 'admin/includes/db.php';

// Redirect if not logged in at all
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Check if already verified
$user_id = $_SESSION['user_id'];
$user_res = $conn->query("SELECT phone, phone_verified FROM users WHERE id = $user_id");
$user = $user_res->fetch_assoc();

if ($user['phone_verified']) {
    $redirect = isset($_GET['redirect']) ? $_GET['redirect'] : 'index.php';
    header("Location: $redirect");
    exit;
}

$title = "Verify Mobile - Gowilds Travel";
$redirect_url = isset($_GET['redirect']) ? htmlspecialchars($_GET['redirect']) : 'index.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>
    <!--====== Bootstrap css ======-->
    <link rel="stylesheet" href="assets/vendor/bootstrap/css/bootstrap.min.css">
    <!--====== FontAwesome css ======-->
    <link rel="stylesheet" href="assets/fonts/fontawesome/css/all.min.css">
    <!--====== Google Fonts ======-->
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #F7921E;
            --primary-hover: #e08316;
            --bg-overlay: rgba(0, 0, 0, 0.4);
        }

        body, html {
            height: 100%;
            margin: 0;
            font-family: 'Prompt', sans-serif;
            overflow-x: hidden;
        }

        .auth-wrapper {
            background: url('assets/images/bg-01.jpg') no-repeat center center fixed;
            background-size: cover;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            position: relative;
        }

        .auth-wrapper::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: var(--bg-overlay);
            z-index: 1;
        }

        .auth-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.3);
            width: 100%;
            max-width: 450px;
            padding: 40px;
            z-index: 2;
            position: relative;
            animation: fadeInUp 0.6s ease-out;
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .auth-title {
            font-weight: 700;
            color: #333;
            text-align: center;
            margin-bottom: 10px;
        }

        .auth-subtitle {
            text-align: center;
            color: #777;
            margin-bottom: 30px;
            font-size: 14px;
        }

        .form-group {
            margin-bottom: 20px;
            position: relative;
        }

        .form-group i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #aaa;
        }

        .form-control {
            border-radius: 10px;
            padding: 12px 15px 12px 45px;
            border: 1px solid #ddd;
            transition: 0.3s;
            font-size: 15px;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(247, 146, 30, 0.25);
        }

        .btn-primary {
            background-color: var(--primary-color);
            border: none;
            padding: 12px;
            border-radius: 10px;
            font-weight: 600;
            width: 100%;
            transition: 0.3s;
        }

        .btn-primary:hover {
            background-color: var(--primary-hover);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(247, 146, 30, 0.4);
        }

        .alert {
            border-radius: 10px;
            font-size: 14px;
            margin-bottom: 20px;
            display: none;
        }

        .otp-inputs {
            display: flex;
            justify-content: space-between;
            gap: 10px;
            margin-bottom: 20px;
        }

        .otp-input {
            width: 50px;
            height: 50px;
            text-align: center;
            font-size: 24px;
            font-weight: 700;
            border: 1px solid #ddd;
            border-radius: 10px;
            background: #f9f9f9;
        }

        .otp-input:focus {
            border-color: var(--primary-color);
            outline: none;
            background: #fff;
        }

        .resend-link {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
        }

        .resend-link a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 600;
        }

        .resend-link a.disabled {
            color: #999;
            pointer-events: none;
        }
    </style>
</head>
<body>

<div class="auth-wrapper">
    <div class="auth-card">
        <div class="auth-logo text-center mb-4">
            <a href="index.php"><img src="assets/images/logo.png" alt="MyEasyTrip Logo" style="max-width: 150px;"></a>
        </div>

        <div id="phone-section" <?php echo ($user['phone'] ? 'style="display:none;"' : ''); ?>>
            <h3 class="auth-title">Mobile Verification</h3>
            <p class="auth-subtitle">Please enter your mobile number to continue.</p>
            
            <div id="phone-alert" class="alert alert-danger"></div>

            <form id="phone-form">
                <div class="form-group">
                    <i class="fas fa-mobile-alt"></i>
                    <input type="text" name="phone" class="form-control" placeholder="Mobile Number (e.g. +919876543210)" required>
                </div>
                <button type="submit" class="btn btn-primary">SEND OTP</button>
            </form>
        </div>

        <div id="otp-section" <?php echo ($user['phone'] ? '' : 'style="display:none;"'); ?>>
            <h3 class="auth-title">Enter OTP</h3>
            <p class="auth-subtitle">A verification code has been sent to your mobile <span id="display-phone"><?php echo $user['phone']; ?></span>.</p>
            
            <div id="otp-alert" class="alert alert-danger"></div>

            <form id="otp-form">
                <div class="otp-inputs">
                    <input type="text" class="otp-input" maxlength="1" pattern="\d*" inputmode="numeric" required>
                    <input type="text" class="otp-input" maxlength="1" pattern="\d*" inputmode="numeric" required>
                    <input type="text" class="otp-input" maxlength="1" pattern="\d*" inputmode="numeric" required>
                    <input type="text" class="otp-input" maxlength="1" pattern="\d*" inputmode="numeric" required>
                </div>
                <!-- Hidden input to hold the full OTP -->
                <input type="hidden" name="otp" id="hidden-otp">
                <button type="submit" class="btn btn-primary">VERIFY & PROCEED</button>
            </form>

            <div class="resend-link">
                Didn't receive code? <a href="javascript:void(0)" id="resend-btn">Resend OTP</a>
                <span id="timer" class="text-muted small ms-2"></span>
            </div>
            
            <div class="text-center mt-3">
                <a href="javascript:void(0)" onclick="changePhone()" class="text-muted small">Change Mobile Number</a>
            </div>
        </div>
    </div>
</div>

<!--====== Jquery js ======-->
<script src="assets/vendor/jquery-3.6.0.min.js"></script>
<!--====== Bootstrap js ======-->
<script src="assets/vendor/bootstrap/js/bootstrap.min.js"></script>

<script>
    const redirectUrl = "<?php echo $redirect_url; ?>";
    let timerOn = false;

    function startTimer(duration) {
        let timer = duration, minutes, seconds;
        timerOn = true;
        $('#resend-btn').addClass('disabled');
        
        const interval = setInterval(function () {
            minutes = parseInt(timer / 60, 10);
            seconds = parseInt(timer % 60, 10);

            minutes = minutes < 10 ? "0" + minutes : minutes;
            seconds = seconds < 10 ? "0" + seconds : seconds;

            $('#timer').text(minutes + ":" + seconds);

            if (--timer < 0) {
                clearInterval(interval);
                $('#timer').text("");
                $('#resend-btn').removeClass('disabled');
                timerOn = false;
            }
        }, 1000);
    }

    function changePhone() {
        $('#otp-section').fadeOut(300, function() {
            $('#phone-section').fadeIn(300);
        });
    }

    $(document).ready(function() {
        // Handle OTP input auto-focus
        $('.otp-input').on('keyup', function(e) {
            if (this.value.length === 1) {
                $(this).next('.otp-input').focus();
            }
            if (e.key === 'Backspace' && this.value.length === 0) {
                $(this).prev('.otp-input').focus();
            }
            
            // Collect all inputs
            let otp = '';
            $('.otp-input').each(function() {
                otp += $(this).val();
            });
            $('#hidden-otp').val(otp);
        });

        // Phone Form Handler
        $('#phone-form').on('submit', function(e) {
            e.preventDefault();
            const $btn = $(this).find('button');
            const originalText = $btn.text();
            const phone = $(this).find('input[name="phone"]').val();
            
            $btn.prop('disabled', true).text('SENDING...');
            $('#phone-alert').hide();

            $.ajax({
                url: 'otp_handler.php?action=send_otp',
                type: 'POST',
                data: { phone: phone },
                success: function(resp) {
                    if (resp.status === 'success') {
                        $('#display-phone').text(phone);
                        $('#phone-section').fadeOut(300, function() {
                            $('#otp-section').fadeIn(300);
                            startTimer(60);
                        });
                    } else {
                        $('#phone-alert').text(resp.message).fadeIn();
                        $btn.prop('disabled', false).text(originalText);
                    }
                },
                error: function() {
                    $('#phone-alert').text('An error occurred. Please try again.').fadeIn();
                    $btn.prop('disabled', false).text(originalText);
                }
            });
        });

        // OTP Form Handler
        $('#otp-form').on('submit', function(e) {
            e.preventDefault();
            const otp = $('#hidden-otp').val();
            if (otp.length < 4) {
                $('#otp-alert').text('Please enter 4-digit OTP').fadeIn();
                return;
            }

            const $btn = $(this).find('button');
            const originalText = $btn.text();
            $btn.prop('disabled', true).text('VERIFYING...');
            $('#otp-alert').hide();

            $.ajax({
                url: 'otp_handler.php?action=verify_otp',
                type: 'POST',
                data: { otp: otp },
                success: function(resp) {
                    if (resp.status === 'success') {
                        window.location.href = redirectUrl;
                    } else {
                        $('#otp-alert').text(resp.message).fadeIn();
                        $btn.prop('disabled', false).text(originalText);
                    }
                },
                error: function() {
                    $('#otp-alert').text('An error occurred. Please try again.').fadeIn();
                    $btn.prop('disabled', false).text(originalText);
                }
            });
        });

        // Resend OTP
        $('#resend-btn').on('click', function() {
            if (timerOn) return;
            
            const phone = $('#display-phone').text();
            $.ajax({
                url: 'otp_handler.php?action=send_otp',
                type: 'POST',
                data: { phone: phone },
                success: function(resp) {
                    if (resp.status === 'success') {
                        $('#otp-alert').removeClass('alert-danger').addClass('alert-success').text('OTP resent successfully!').fadeIn();
                        setTimeout(() => $('#otp-alert').fadeOut(), 3000);
                        startTimer(60);
                    } else {
                        $('#otp-alert').text(resp.message).fadeIn();
                    }
                }
            });
        });
        
        // If phone already exists, maybe trigger timer or just wait for input
        <?php if ($user['phone']): ?>
        // startTimer(60); // Optionally start timer on load if phone exists
        <?php endif; ?>
    });
</script>

</body>
</html>
