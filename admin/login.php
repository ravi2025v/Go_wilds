<?php
session_start();
require_once 'includes/db.php';

// Redirect if already logged in
if (isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit();
}

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $conn->real_escape_string($_POST['email']);
    $password = $_POST['password'];

    $result = $conn->query("SELECT id, name, password, role FROM users WHERE email = '$email'");

    if ($result && $result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        // Ensure user is an admin and verify password
        if ($user['role'] == 'admin' && password_verify($password, $user['password'])) {
            $_SESSION['admin_id'] = $user['id'];
            $_SESSION['admin_name'] = $user['name'];
            
            header("Location: index.php");
            exit();
        } else {
            $error = "Invalid credentials or unauthorized access.";
        }
    } else {
        $error = "Invalid credentials or unauthorized access.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Gowilds</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8f9fa;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
        }
        .login-card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            overflow: hidden;
            width: 100%;
            max-width: 450px;
        }
        .login-header {
            background-color: #0d6efd;
            color: white;
            padding: 30px;
            text-align: center;
        }
        .login-body {
            padding: 40px 30px;
            background: white;
        }
        .brand-icon {
            font-size: 3rem;
            margin-bottom: 10px;
        }
        .form-control {
            padding: 12px;
            border-radius: 8px;
        }
        .btn-login {
            padding: 12px;
            border-radius: 8px;
            font-weight: 600;
        }
    </style>
</head>
<body>

<div class="login-card">
    <div class="login-header">
        <i class="fas fa-route brand-icon"></i>
        <h4 class="mb-0">Gowilds Admin</h4>
        <p class="text-white-50 mb-0">Sign in to manage your website</p>
    </div>
    <div class="login-body">
        <?php if(!empty($error)): ?>
            <div class="alert alert-danger fw-bold text-center">
                <i class="fas fa-exclamation-circle me-1"></i> <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="login.php">
            <div class="mb-4">
                <label class="form-label text-muted fw-bold">Email Address</label>
                <div class="input-group">
                    <span class="input-group-text bg-light"><i class="fas fa-envelope text-muted"></i></span>
                    <input type="email" name="email" class="form-control" placeholder="admin@gowilds.com" required value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                </div>
            </div>
            <div class="mb-4">
                <label class="form-label text-muted fw-bold">Password</label>
                <div class="input-group">
                    <span class="input-group-text bg-light"><i class="fas fa-lock text-muted"></i></span>
                    <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                </div>
            </div>
            
            <button type="submit" class="btn btn-primary w-100 btn-login mt-2">
                Sign In <i class="fas fa-sign-in-alt ms-1"></i>
            </button>
        </form>

        <div class="text-center mt-4">
            <a href="../index.php" class="text-decoration-none text-muted">
                <i class="fas fa-arrow-left me-1"></i> Back to Website
            </a>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
