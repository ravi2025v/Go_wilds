<?php
// login.php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once 'admin/includes/db.php';

// Redirect if already logged in
if (isset($_SESSION['user_id'])) {
    $redirect = isset($_GET['redirect']) ? $_GET['redirect'] : 'index.php';
    header("Location: $redirect");
    exit;
}

$title = "Login - Gowilds Travel";
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
            background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('https://images.unsplash.com/photo-1507525428034-b723cf961d3e?ixlib=rb-1.2.1&auto=format&fit=crop&w=1353&q=80') no-repeat center center fixed;
            background-size: cover;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            position: relative;
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
            min-height: 480px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .auth-logo {
            text-align: center;
            margin-bottom: 25px;
        }

        .auth-logo img {
            max-width: 150px;
        }

        .auth-title {
            font-weight: 700;
            color: #333;
            text-align: center;
            margin-bottom: 8px;
        }

        .auth-subtitle {
            text-align: center;
            color: #777;
            margin-bottom: 25px;
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
            margin-top: 10px;
        }

        .btn-primary:hover {
            background-color: var(--primary-hover);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(247, 146, 30, 0.4);
        }

        .btn-link {
            color: #777;
            text-decoration: none;
            font-size: 14px;
            display: block;
            text-align: center;
            margin-top: 15px;
        }

        .social-login {
            margin-top: 25px;
            text-align: center;
        }

        .social-login p {
            position: relative;
            margin-bottom: 20px;
            color: #999;
            font-size: 14px;
        }

        .social-login p::before,
        .social-login p::after {
            content: '';
            position: absolute;
            top: 50%;
            width: 30%;
            height: 1px;
            background: #ddd;
        }

        .social-login p::before { left: 0; }
        .social-login p::after { right: 0; }

        #g_id_signin {
            display: flex;
            justify-content: center;
        }

        .alert {
            border-radius: 10px;
            font-size: 14px;
            margin-bottom: 20px;
            display: none;
        }

        .step-container {
            display: none;
        }

        .step-container.active {
            display: block;
            animation: fadeIn 0.4s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .phone-input-group {
            display: flex;
            gap: 10px;
        }

        .country-code {
            width: 80px;
            text-align: center;
            padding-left: 15px !important;
        }

        .otp-inputs {
            display: flex;
            gap: 10px;
            justify-content: center;
            margin-bottom: 20px;
        }

        .otp-field {
            width: 50px;
            height: 60px;
            text-align: center;
            font-size: 24px;
            font-weight: 700;
            border: 2px solid #ddd;
            border-radius: 12px;
            transition: 0.3s;
        }

        .otp-field:focus {
            border-color: var(--primary-color);
            outline: none;
        }

        .resend-timer {
            text-align: center;
            font-size: 13px;
            color: #777;
            margin-top: 15px;
        }

        .resend-link {
            color: var(--primary-color);
            font-weight: 600;
            text-decoration: none;
            display: none;
        }
    </style>
</head>
<body>

<div class="auth-wrapper">
    <div class="auth-card">
        <div class="auth-logo">
            <a href="index.php"><img src="assets/images/logo-black.png" alt="Logo"></a>
        </div>

        <div id="auth-alert" class="alert alert-danger"></div>

        <!-- Step 1: Mobile Input -->
        <div id="step-mobile" class="step-container active">
            <h3 class="auth-title">Let's Get Started</h3>
            <p class="auth-subtitle">Enter your mobile number to continue</p>
            
            <form id="form-mobile">
                <div class="form-group">
                    <i class="far fa-user"></i>
                    <input type="text" name="name" id="name-input" class="form-control" placeholder="Full Name" required>
                </div>
                <div class="form-group">
                    <i class="far fa-envelope"></i>
                    <input type="email" name="email" id="email-input" class="form-control" placeholder="Email Address" required>
                </div>
                <div class="form-group phone-input-group">
                    <input type="text" class="form-control country-code" value="+91" readonly>
                    <div style="flex: 1; position: relative;">
                        <i class="fas fa-mobile-alt"></i>
                        <input type="tel" name="phone" id="phone-input" class="form-control" placeholder="Mobile Number" required maxlength="10" pattern="[0-9]{10}">
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">SEND OTP</button>
            </form>

            <div class="social-login">
                <p><span>Or continue with</span></p>
                <div id="g_id_signin"></div>
            </div>
        </div>

        <!-- Step 2: OTP Verification -->
        <div id="step-otp" class="step-container">
            <h3 class="auth-title">Verify OTP</h3>
            <p class="auth-subtitle" id="otp-subtitle">Sent to +91 0000000000</p>
            
            <form id="form-otp">
                <div class="otp-inputs">
                    <input type="text" class="otp-field" maxlength="1" pattern="\d*" inputmode="numeric">
                    <input type="text" class="otp-field" maxlength="1" pattern="\d*" inputmode="numeric">
                    <input type="text" class="otp-field" maxlength="1" pattern="\d*" inputmode="numeric">
                    <input type="text" class="otp-field" maxlength="1" pattern="\d*" inputmode="numeric">
                </div>
                <input type="hidden" name="otp" id="full-otp">
                <button type="submit" class="btn btn-primary">VERIFY & LOGIN</button>
            </form>

            <div class="resend-timer">
                Resend OTP in <span id="timer-sec">30</span>s
            </div>
            <a href="javascript:void(0)" class="resend-link" id="resend-btn">Resend OTP</a>
            
            <a href="javascript:void(0)" class="btn-link" onclick="goToStep('mobile')">Change Number</a>
        </div>

        <!-- Step 3: Profile Completion (For New Users) -->
        <div id="step-profile" class="step-container">
            <h3 class="auth-title">Almost There!</h3>
            <p class="auth-subtitle">Tell us a bit about yourself</p>
            
            <form id="form-profile">
                <div class="form-group">
                    <i class="far fa-user"></i>
                    <input type="text" name="name" class="form-control" placeholder="Full Name" required>
                </div>
                <div class="form-group">
                    <i class="far fa-envelope"></i>
                    <input type="email" name="email" class="form-control" placeholder="Email Address (Optional)">
                </div>
                <button type="submit" class="btn btn-primary">COMPLETE REGISTRATION</button>
            </form>
        </div>
    </div>
</div>

<!--====== Google Login Script ======-->
<script src="https://accounts.google.com/gsi/client" async defer></script>
<script>
    function handleCredentialResponse(response) {
        $.ajax({
            url: 'auth_handler.php?action=social_login',
            type: 'POST',
            data: JSON.stringify({ token: response.credential }),
            contentType: 'application/json',
            success: function(resp) {
                if (resp.status === 'success') {
                    if (resp.needsPhone) {
                        goToStep('mobile');
                        $('#auth-alert').hide();
                        showAlert('Social login successful! Please link your mobile number.', 'success');
                    } else {
                        window.location.href = "<?php echo $redirect_url; ?>";
                    }
                } else {
                    showAlert(resp.message);
                }
            }
        });
    }

    window.onload = function () {
        google.accounts.id.initialize({
            client_id: "784949162463-m58079m463qu2jnt390237m0i6hgehie.apps.googleusercontent.com", // Example ID, replace with real one if available
            callback: handleCredentialResponse
        });
        google.accounts.id.renderButton(
            document.getElementById("g_id_signin"),
            { theme: "outline", size: "large", width: 350, border_radius: 10 }
        );
    }
</script>

<!--====== Jquery js ======-->
<script src="assets/vendor/jquery-3.6.0.min.js"></script>
<!--====== Bootstrap js ======-->
<script src="assets/vendor/bootstrap/js/bootstrap.min.js"></script>

<script>
    let timer;
    let secondsLeft = 30;

    function showAlert(msg, color = 'danger') {
        const $alert = $('#auth-alert');
        $alert.removeClass('alert-danger alert-success').addClass('alert-' + color);
        $alert.text(msg).fadeIn();
        setTimeout(() => $alert.fadeOut(), 5000);
    }

    function goToStep(stepId) {
        $('.step-container').removeClass('active');
        $('#step-' + stepId).addClass('active');
        $('#auth-alert').hide();
    }

    function startResendTimer() {
        secondsLeft = 30;
        $('.resend-timer').show();
        $('#resend-btn').hide();
        clearInterval(timer);
        timer = setInterval(() => {
            secondsLeft--;
            $('#timer-sec').text(secondsLeft);
            if (secondsLeft <= 0) {
                clearInterval(timer);
                $('.resend-timer').hide();
                $('#resend-btn').show();
            }
        }, 1000);
    }

    $(document).ready(function() {
        // Phone Input Handler
        $('#form-mobile').on('submit', function(e) {
            e.preventDefault();
            const name = $('#name-input').val();
            const email = $('#email-input').val();
            const phone = $('#phone-input').val();
            const $btn = $(this).find('button');
            $btn.prop('disabled', true).text('SENDING...');

            $.ajax({
                url: 'auth_handler.php?action=send_otp',
                type: 'POST',
                data: { phone: phone, name: name, email: email },
                success: function(resp) {
                    if (resp.status === 'success') {
                        $('#otp-subtitle').text('Sent to +91 ' + phone);
                        goToStep('otp');
                        startResendTimer();
                        // For Demo Purpose
                        if(resp.debug_otp) {
                            showAlert('Testing OTP: ' + resp.debug_otp, 'success');
                        }
                    } else {
                        showAlert(resp.message);
                    }
                    $btn.prop('disabled', false).text('SEND OTP');
                }
            });
        });

        // OTP Input Focus Logic
        $('.otp-field').on('keyup', function(e) {
            const $this = $(this);
            if (e.key >= 0 && e.key <= 9) {
                $this.next('.otp-field').focus();
            } else if (e.key === 'Backspace') {
                $this.prev('.otp-field').focus();
            }
            
            let otp = '';
            $('.otp-field').each(function() {
                otp += $(this).val();
            });
            $('#full-otp').val(otp);
        });

        // OTP Form Handler
        $('#form-otp').on('submit', function(e) {
            e.preventDefault();
            const otp = $('#full-otp').val();
            if (otp.length < 4) {
                showAlert('Please enter complete OTP');
                return;
            }

            const $btn = $(this).find('button');
            $btn.prop('disabled', true).text('VERIFYING...');

            $.ajax({
                url: 'auth_handler.php?action=verify_otp',
                type: 'POST',
                data: { otp: otp },
                success: function(resp) {
                    if (resp.status === 'success') {
                        if (resp.newUser) {
                            goToStep('profile');
                        } else {
                            window.location.href = "<?php echo $redirect_url; ?>";
                        }
                    } else {
                        showAlert(resp.message);
                        $btn.prop('disabled', false).text('VERIFY & LOGIN');
                    }
                }
            });
        });

        // Profile Form Handler
        $('#form-profile').on('submit', function(e) {
            e.preventDefault();
            const $btn = $(this).find('button');
            $btn.prop('disabled', true).text('PROCESSING...');

            $.ajax({
                url: 'auth_handler.php?action=complete_signup',
                type: 'POST',
                data: $(this).serialize(),
                success: function(resp) {
                    if (resp.status === 'success') {
                        window.location.href = "<?php echo $redirect_url; ?>";
                    } else {
                        showAlert(resp.message);
                        $btn.prop('disabled', false).text('COMPLETE REGISTRATION');
                    }
                }
            });
        });

        // Resend Logic
        $('#resend-btn').on('click', function() {
            $('#form-mobile').submit();
        });
    });
</script>

</body>
</html>
