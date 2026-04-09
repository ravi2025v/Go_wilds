<!-- auth_modal.php -->
<!-- Google Sign-In SDK (At the top of modal for global access) -->
<script src="https://accounts.google.com/gsi/client" async defer></script>

<div class="modal fade auth-modal" id="loginModal" tabindex="-1" aria-hidden="true" style="z-index: 9999;">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 24px; overflow: hidden; background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px);">
            <div class="modal-body p-0">
                <div class="row g-0">
                    <!-- Left Side: Branding & Info -->
                    <div class="col-md-5 d-none d-md-flex flex-column justify-content-center p-5 text-white bg-primary" style="background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);">
                        <h2 class="fw-bold mb-4 text-white">Start Your Journey!</h2>
                        <p class="mb-5 opacity-75">Connect with us to plan your dream vacation and get exclusive member rates.</p>
                        <div class="mb-4 d-flex align-items-center">
                            <i class="fas fa-umbrella-beach fa-2x me-3"></i>
                            <div><h6 class="mb-0 fw-bold">Exclusive Deals</h6><small class="opacity-75">Save up to 30%</small></div>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="fas fa-heart fa-2x me-3"></i>
                            <div><h6 class="mb-0 fw-bold">Trip Wishlist</h6><small class="opacity-75">Save spots</small></div>
                        </div>
                    </div>
                    <!-- Right Side: Forms & Social -->
                    <div class="col-md-7 p-4 p-lg-5 bg-white">
                        <div class="text-end"><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button></div>
                        <div class="mb-4">
                            <ul class="nav nav-pills justify-content-center bg-light p-1 rounded-pill" id="authTabs" role="tablist">
                                <li class="nav-item flex-fill text-center" role="presentation"><button class="nav-link active rounded-pill w-100" id="login-tab" data-bs-toggle="pill" data-bs-target="#login" type="button" role="tab">Sign In</button></li>
                                <li class="nav-item flex-fill text-center" role="presentation"><button class="nav-link rounded-pill w-100" id="register-tab" data-bs-toggle="pill" data-bs-target="#register" type="button" role="tab">Join Us</button></li>
                            </ul>
                        </div>
                        <div class="tab-content" id="authTabsContent">
                            <div class="tab-pane fade show active" id="login" role="tabpanel">
                                <form id="loginForm">
                                    <div id="loginMsg" class="mb-3"></div>
                                    <div class="form-floating mb-3"><input type="email" name="email" class="form-control" id="loginEmail" placeholder="name" required><label for="loginEmail">Email Address</label></div>
                                    <div class="form-floating mb-4"><input type="password" name="password" class="form-control" id="loginPass" placeholder="Password" required><label for="loginPass">Password</label></div>
                                    <button type="submit" class="btn btn-primary btn-lg w-100 mb-4 shadow-sm py-3 rounded-3">Sign In</button>
                                </form>
                                <div class="text-center position-relative mb-4"><hr class="opacity-25"><span class="position-absolute top-50 start-50 translate-middle bg-white px-3 text-secondary small">OR CONTINUE WITH</span></div>
                                <div class="row g-3">
                                    <div class="col-6"><button onclick="handleGoogleLogin()" class="btn btn-outline-secondary w-100 py-2"><img src="https://upload.wikimedia.org/wikipedia/commons/5/53/Google_%22G%22_Logo.svg" width="18" class="me-2">Google</button></div>
                                    <div class="col-6"><button class="btn btn-outline-secondary w-100 py-2"><i class="fab fa-facebook text-primary me-2"></i>Facebook</button></div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="register" role="tabpanel">
                                <form id="registerForm">
                                    <div id="registerMsg" class="mb-3"></div>
                                    <div class="form-floating mb-3"><input type="text" name="name" class="form-control" id="regName" placeholder="Full Name" required><label for="regName">Name</label></div>
                                    <div class="form-floating mb-3"><input type="email" name="email" class="form-control" id="regEmail" placeholder="name" required><label for="regEmail">Email</label></div>
                                    <div class="form-floating mb-4"><input type="password" name="password" class="form-control" id="regPass" placeholder="Password" required><label for="regPass">Password</label></div>
                                    <button type="submit" class="btn btn-primary btn-lg w-100 py-3 rounded-3 shadow-sm">Join Now</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>.auth-modal .nav-pills .nav-link{color:#6c757d}.auth-modal .nav-pills .nav-link.active{background-color:#007bff;color:white}.auth-modal .form-control{background-color:#f8f9fa}</style>

<script>
$(document).ready(function() {
    $('#loginForm, #registerForm').on('submit', function(e) {
        e.preventDefault();
        var isReg = $(this).attr('id') === 'registerForm';
        var $msg = isReg ? $('#registerMsg') : $('#loginMsg');
        var $btn = $(this).find('button');
        var action = isReg ? 'register' : 'login';

        $msg.html('<div class="alert alert-info py-2 small mb-0"><i class="fas fa-spinner fa-spin me-2"></i> Plase wait...</div>');
        $btn.prop('disabled', true);

        $.ajax({
            url: 'auth_handler.php?action=' + action,
            type: 'POST',
            data: $(this).serialize(),
            success: function(data) {
                if (data.status === 'success') {
                    $msg.html('<div class="alert alert-success py-2 small mb-0">Success! Reloading...</div>');
                    setTimeout(function() { location.reload(); }, 1000);
                } else {
                    $msg.html('<div class="alert alert-danger py-2 small mb-0">' + data.message + '</div>');
                    $btn.prop('disabled', false);
                }
            },
            error: function() {
                $msg.html('<div class="alert alert-danger py-2 small mb-0">Connection error</div>');
                $btn.prop('disabled', false);
            }
        });
    });
});

// Google Login Dynamic Integration
function handleGoogleLogin() {
    google.accounts.id.initialize({
        client_id: "your-client-id-here.apps.googleusercontent.com",
        callback: function(response) {
            $.post('auth_handler.php?action=social_login', { credential: response.credential }, function(data) {
                if (data.status === 'success') {
                    location.reload();
                } else {
                    alert(data.message);
                }
            });
        }
    });
    google.accounts.id.prompt(); 
}

// Global hook for the button - This is the primary trigger
window.checkAuthBeforeBooking = function(e) {
    if (window.is_logged_in === true) {
        // Safe Submit
        var form = document.querySelector('.sidebar-booking-form');
        if(form) form.submit();
        return true;
    } else {
        if (e) { e.preventDefault(); e.stopPropagation(); }
        try {
            // Priority: Try to show Modal instantly
            var myModal = new bootstrap.Modal(document.getElementById('loginModal'));
            myModal.show();
        } catch(err) {
            // Fallback for jQuery
            $('#loginModal').modal('show');
        }
        return false;
    }
}
</script>
