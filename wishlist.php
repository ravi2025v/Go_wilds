<?php
ob_start(); // Prevent "headers already sent" warning
// wishlist.php
$title = "My Wishlist - MyEasyTrip";
include 'includes/header.php';

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php?msg=Please login to view your wishlist");
    exit;
}

$user_id = $_SESSION['user_id'];

// Remove item from wishlist if requested
if (isset($_GET['remove'])) {
    $wishlist_id = intval($_GET['remove']);
    $conn->query("DELETE FROM wishlist WHERE id = $wishlist_id AND user_id = $user_id");
    header("Location: wishlist.php?msg=Removed from wishlist");
    exit;
}

// Fetch user's wishlist items
$query = "SELECT w.id as wishlist_id, t.* 
          FROM wishlist w 
          JOIN tours t ON w.tour_id = t.id 
          WHERE w.user_id = $user_id
          ORDER BY w.id DESC";
$result = $conn->query($query);
?>

<style>
    /* Final bypass for any preloader issues */
    .preloader { display: none !important; opacity: 0 !important; visibility: hidden !important; }
</style>

<section class="page-title-area text-white bg_cover" style="background-image: url(assets/images/bg/page-bg-1.jpg); padding: 50px 0;">
    <div class="container text-center">
        <h2 class="mb-0" style="font-weight: 800; font-size: 42px;">My Wishlist</h2>
        <ul class="breadcrumb-link text-white mt-2">
            <li><a href="index.php">Home</a></li>
            <li class="active">Wishlist</li>
        </ul>
    </div>
</section>

<section class="wishlist-section py-5 bg-white">
    <div class="container">
        <?php if (isset($_GET['msg'])): ?>
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                <?php echo htmlspecialchars($_GET['msg']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="row g-4">
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while ($tour = $result->fetch_assoc()): ?>
                    <div class="col-lg-4 col-md-6">
                        <div class="sidebar-widget sidebar-banner-widget shadow-sm border-0 h-100" style="border-radius: 20px; overflow: hidden; position: relative; transition: 0.3s; background: #fff;">
                            <div class="banner-widget-content">
                                <div class="banner-img" style="height: 250px; overflow: hidden;">
                                    <img src="<?php echo htmlspecialchars($tour['image']); ?>" alt="<?php echo htmlspecialchars($tour['title']); ?>" style="width: 100%; height: 100%; object-fit: cover;">
                                    <div class="hover-overlay">
                                        <div class="hover-content">
                                            <a href="wishlist.php?remove=<?php echo $tour['wishlist_id']; ?>" class="btn btn-sm btn-danger rounded-pill px-3" onclick="return confirm('Remove this tour?')">
                                                <i class="fas fa-trash-alt me-1"></i> Remove
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="p-4">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h5 class="fw-bold text-dark mb-0"><?php echo htmlspecialchars($tour['title']); ?></h5>
                                    <span class="badge bg-light text-primary border border-primary-subtle px-2 py-1 small"><?php echo $tour['tour_type']; ?></span>
                                </div>
                                <p class="text-muted small mb-3"><i class="fas fa-map-marker-alt me-1 text-primary"></i> <?php echo htmlspecialchars($tour['destination']); ?></p>
                                
                                <div class="row align-items-center mb-4">
                                    <div class="col">
                                        <small class="text-muted d-block small">Starts from</small>
                                        <h4 class="text-primary fw-bold mb-0">₹<?php echo number_format($tour['price']); ?></h4>
                                    </div>
                                    <div class="col text-end">
                                        <span class="text-muted small"><i class="far fa-clock me-1"></i> <?php echo $tour['duration']; ?> Days</span>
                                    </div>
                                </div>

                                <div class="d-grid gap-2">
                                    <a href="tour-details.php?id=<?php echo $tour['id']; ?>" class="main-btn primary-btn btn-sm py-2">Book Now <i class="fas fa-paper-plane ms-1"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="col-12 text-center py-5">
                    <div class="mb-4">
                        <i class="far fa-heart fa-5x text-muted" style="opacity: 0.3;"></i>
                    </div>
                    <h3>Your Wishlist is Empty</h3>
                    <p class="text-muted">Explore our amazing tours and add your favorites here!</p>
                    <a href="tour.php" class="main-btn primary-btn mt-3">Browse Tours</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<style>
    .primary-btn {
        background: #F7921E;
        color: #fff;
        border: none;
        border-radius: 50px;
        transition: 0.3s;
    }
    .primary-btn:hover {
        background: #e08316;
        color: #fff;
        transform: translateY(-3px);
    }
    .text-primary {
        color: #F7921E !important;
    }
    .bg-primary {
        background: #F7921E !important;
    }
    .border-primary-subtle {
        border-color: #fdd4a5 !important;
    }
</style>

<?php include 'includes/footer.php'; ?>
<?php ob_end_flush(); ?>
