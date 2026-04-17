<?php
// tour.php
$title = "Our Tours - MyEasyTrip";
include 'includes/header.php';
require_once 'admin/includes/db.php';

$category = isset($_GET['category']) ? $conn->real_escape_string($_GET['category']) : '';
$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';

$query = "SELECT t.* FROM tours t WHERE t.status = 'active'";
if ($category) {
    $query .= " AND t.category = '$category'";
}
if ($search) {
    $query .= " AND (t.title LIKE '%$search%' OR t.destination LIKE '%$search%')";
}
$query .= " ORDER BY t.id DESC";

$res = $conn->query($query);
$tours_list = [];
if ($res && $res->num_rows > 0) {
    while ($row = $res->fetch_assoc()) {
        // Check if in wishlist for current user
        $row['is_wishlisted'] = false;
        if (isset($_SESSION['user_id'])) {
            $uid = $_SESSION['user_id'];
            $tid = $row['id'];
            $cw = $conn->query("SELECT id FROM wishlist WHERE user_id = $uid AND tour_id = $tid");
            if ($cw && $cw->num_rows > 0) $row['is_wishlisted'] = true;
        }
        $tours_list[] = $row;
    }
}
?>

<!--====== Start Page Title Area ======-->
<section class="page-title-area text-white bg_cover" style="background-image: url(assets/images/bg/page-bg-1.jpg); padding: 30px 0;">
    <div class="container">
        <div class="page-title-inner text-center">
            <h1 class="page-title"><?php echo $category ? htmlspecialchars($category) : 'Our Tours'; ?></h1>
            <ul class="breadcrumb-link text-white">
                <li><a href="index.php">Home</a></li>
                <li class="active"><?php echo $category ? htmlspecialchars($category) : 'Tours'; ?></li>
            </ul>
        </div>
    </div>
</section><!--====== End Page Title Area ======-->

<!--====== Start Places Section ======-->
<section class="places-section py-5 bg-light">
    <div class="container">
        <div class="row">
            <?php if (!empty($tours_list)): ?>
                <?php foreach ($tours_list as $tour): ?>
                    <div class="col-xl-4 col-md-6 col-sm-12 mb-4">
                        <div class="single-place-item wow fadeInUp shadow-sm h-100 bg-white" style="border-radius: 15px; overflow: hidden; position: relative;">
                            <div class="place-img" style="height: 250px; position: relative;">
                                <img src="<?php echo htmlspecialchars($tour['image']); ?>" alt="<?php echo htmlspecialchars($tour['title']); ?>" style="width: 100%; height: 100%; object-fit: cover;" onerror="this.onerror=null; this.src='assets/images/tour.jpg'">
                                
                                <!-- Dynamic Wishlist Button -->
                                <button class="wishlist-btn-listing <?php echo $tour['is_wishlisted'] ? 'active' : ''; ?>" 
                                        data-id="<?php echo $tour['id']; ?>"
                                        style="position: absolute; top: 15px; right: 15px; width: 40px; height: 40px; border-radius: 50%; background: rgba(255,255,255,0.9); border: none; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: 0.3s; z-index: 5; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
                                    <i class="fa<?php echo $tour['is_wishlisted'] ? 's' : 'r'; ?> fa-heart" style="color: <?php echo $tour['is_wishlisted'] ? '#F7921E' : '#888'; ?>; font-size: 18px;"></i>
                                </button>
                            </div>
                            <div class="place-content p-4">
                                <div class="info">
                                    <h4 class="title mb-2"><a href="tour-details.php?id=<?php echo $tour['id']; ?>" class="text-dark text-decoration-none" style="font-weight: 700;"><?php echo htmlspecialchars($tour['title']); ?></a></h4>
                                    <p class="location mb-3 text-muted" style="font-size: 14px;"><i class="fas fa-map-marker-alt me-1 text-primary"></i><?php echo htmlspecialchars($tour['destination']); ?></p>
                                    
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <div class="price">
                                            <small class="text-muted d-block small">Per Pax</small>
                                            <span class="fw-bold text-primary h5 mb-0">₹<?php echo number_format($tour['price']); ?></span>
                                        </div>
                                        <div class="meta text-muted small">
                                            <span><i class="far fa-clock me-1"></i><?php echo htmlspecialchars($tour['duration']); ?> Days</span>
                                        </div>
                                    </div>
                                    
                                    <div class="d-grid">
                                        <a href="tour-details.php?id=<?php echo $tour['id']; ?>" class="btn btn-outline-primary rounded-pill btn-sm py-2">View Details</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12 text-center py-5">
                    <img src="assets/images/no-results.png" alt="No products" style="width: 150px; opacity: 0.3;" onerror="this.style.display='none'">
                    <h4 class="mt-4">No tours found</h4>
                    <p class="text-muted">Try searching for something else or browse categories.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<style>
    .text-primary { color: #F7921E !important; }
    .btn-outline-primary { color: #F7921E; border-color: #F7921E; }
    .btn-outline-primary:hover { background-color: #F7921E; border-color: #F7921E; color: #fff; }
    
    .wishlist-btn-listing:hover { transform: scale(1.1); background: #fff !important; }
    .wishlist-btn-listing.active i { color: #F7921E !important; }
    
    /* Notification Toast */
    .wishlist-toast {
        position: fixed; top: 100px; right: 20px; 
        background: #F7921E; color: white; 
        padding: 12px 25px; border-radius: 50px; 
        box-shadow: 0 5px 15px rgba(0,0,0,0.2); z-index: 10000;
        display: flex; align-items: center; gap: 10px;
        animation: slideInRight 0.4s ease-out; font-weight: 500;
    }
    @keyframes slideInRight { from { transform: translateX(100%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }
    @keyframes slideOutRight { from { transform: translateX(0); opacity: 1; } to { transform: translateX(100%); opacity: 0; } }
</style>

<script src="assets/vendor/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    function showToast(message) {
        const toastId = 'toast-' + Date.now();
        const toastHTML = `<div id="${toastId}" class="wishlist-toast"><i class="fas fa-check-circle"></i> ${message}</div>`;
        $('body').append(toastHTML);
        setTimeout(() => {
            $(`#${toastId}`).css('animation', 'slideOutRight 0.4s ease-in forwards');
            setTimeout(() => $(`#${toastId}`).remove(), 400);
        }, 3000);
    }

    $('.wishlist-btn-listing').on('click', function(e) {
        e.preventDefault();
        const btn = $(this);
        const tourId = btn.data('id');
        const icon = btn.find('i');

        $.ajax({
            url: 'wishlist_action.php?id=' + tourId,
            type: 'GET',
            success: function(resp) {
                if (resp.status === 'added') {
                    btn.addClass('active');
                    icon.removeClass('far').addClass('fas').css('color', '#F7921E');
                    showToast('Added to wishlist!');
                    
                    // Update Header Count
                    const badge = $('#header-wishlist-count');
                    let count = parseInt(badge.text()) || 0;
                    badge.text(count + 1).show();
                } else if (resp.status === 'removed') {
                    btn.removeClass('active');
                    icon.removeClass('fas').addClass('far').css('color', '#888');
                    showToast('Removed from wishlist!');
                    
                    // Update Header Count
                    const badge = $('#header-wishlist-count');
                    let count = parseInt(badge.text()) || 0;
                    if (count > 1) badge.text(count - 1);
                    else badge.hide().text(0);
                } else if (resp.status === 'error') {
                    window.location.href = 'login.php?msg=' + encodeURIComponent(resp.message);
                }
            }
        });
    });
});
</script>

<?php include 'includes/footer.php'; ?>