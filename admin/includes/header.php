<?php
if (session_status() === PHP_SESSION_NONE) {
    session_name("GoWilds_Session");
    session_start();
}
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - MyEasyTrip</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f8f9fa; }
        .sidebar { min-height: 100vh; background-color: #1a1d20; color: #fff; }
        .sidebar a { color: #adb5bd; text-decoration: none; padding: 12px 20px; display: block; border-radius: 5px; margin-bottom: 5px; transition: all 0.3s; }
        .sidebar a:hover, .sidebar a.active { background-color: #0d6efd; color: #fff; }
        .sidebar .brand { padding: 20px; font-size: 1.5rem; font-weight: bold; border-bottom: 1px solid #333; margin-bottom: 20px; }
        .topbar { background-color: #fff; padding: 15px 30px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
        .main-content { padding: 30px; }
        .card { border: none; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); transition: transform 0.2s; }
        .card:hover { transform: translateY(-5px); }
        .stat-icon { font-size: 2.5rem; opacity: 0.8; }
        .cursor-pointer { cursor: pointer; }
    </style>
</head>
<body>
<div class="d-flex">
    <!-- Sidebar -->
    <div class="sidebar col-md-2 d-none d-md-block">
        <div class="brand text-center">
            <a href="index.php">
                <img src="../assets/images/logo.png" alt="MyEasyTrip Logo" style="height: 50px; width: auto;">
            </a>
        </div>
        <div class="px-3">
            <a href="index.php" <?php if(basename($_SERVER['PHP_SELF']) == 'index.php') echo 'class="active"'; ?>><i class="fas fa-home me-2"></i> Dashboard</a>
            <a href="tours.php" <?php if(basename($_SERVER['PHP_SELF']) == 'tours.php') echo 'class="active"'; ?>><i class="fas fa-map-marked-alt me-2"></i> Manage Tours</a>
            <a href="activity_manage.php" <?php if(basename($_SERVER['PHP_SELF']) == 'activity_manage.php') echo 'class="active"'; ?>><i class="fas fa-puzzle-piece me-2"></i> Manage Activities</a>
            <a href="itineraries.php" <?php if(basename($_SERVER['PHP_SELF']) == 'itineraries.php') echo 'class="active"'; ?>><i class="fas fa-clipboard-list me-2"></i> Manage Itinerary</a>
            <a href="tour_bookings.php" <?php if(basename($_SERVER['PHP_SELF']) == 'tour_bookings.php') echo 'class="active"'; ?>><i class="fas fa-ticket-alt me-2"></i> Tour Bookings</a>
            <a href="bookings.php" <?php if(basename($_SERVER['PHP_SELF']) == 'bookings.php') echo 'class="active"'; ?>><i class="fas fa-calendar-check me-2"></i> Bookings</a>
            <a href="users.php" <?php if(basename($_SERVER['PHP_SELF']) == 'users.php') echo 'class="active"'; ?>><i class="fas fa-users me-2"></i> Users</a>
            <a href="reviews.php" <?php if(basename($_SERVER['PHP_SELF']) == 'reviews.php') echo 'class="active"'; ?>><i class="fas fa-star me-2"></i> Manage Reviews</a>
            <a href="flight_searches.php" <?php if(basename($_SERVER['PHP_SELF']) == 'flight_searches.php') echo 'class="active"'; ?>><i class="fas fa-plane-departure me-2"></i> User Searches</a>
            <a href="../index.php" target="_blank"><i class="fas fa-globe me-2"></i> View Website</a>
            <a href="logout.php"><i class="fas fa-sign-out-alt me-2"></i> Logout</a>
        </div>
    </div>
    <!-- Main Content Wrapper -->
    <div class="col-md-10 flex-grow-1">
        <!-- Topbar -->
        <div class="topbar d-flex justify-content-between align-items-center mb-4">
            <div class="d-flex align-items-center">
                <i class="fas fa-bars fs-4 me-3 d-md-none cursor-pointer"></i>
                <h5 class="mb-0 text-muted">Admin Panel</h5>
            </div>
            <div class="dropdown">
                <button class="btn btn-light dropdown-toggle border" type="button" data-bs-toggle="dropdown">
                    <i class="fas fa-user-circle me-1 text-primary"></i> <?php echo isset($_SESSION['admin_name']) ? htmlspecialchars($_SESSION['admin_name']) : 'Admin User'; ?>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="#">Profile</a></li>
                    <li><a class="dropdown-item" href="#">Settings</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
        <!-- Page Content -->
        <div class="main-content pt-0">
