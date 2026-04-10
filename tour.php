<?php
include 'includes/header.php';

// Handle category filtering
$category = isset($_GET['category']) ? $conn->real_escape_string($_GET['category']) : '';
$where_clause = "WHERE status='active'";
if (!empty($category)) {
    $where_clause .= " AND category='$category'";
}

$query = "SELECT * FROM tours $where_clause ORDER BY created_at DESC";
$result = $conn->query($query);

$tours_list = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $tours_list[] = $row;
    }
}

$display_category = !empty($category) ? htmlspecialchars($category) : 'All Destinations';
?>

<!--====== Start Breadcrumb Section ======-->
<section class="page-banner overlay pt-170 pb-170 bg_cover" style="background-image: url(assets/images/abt-bg.jpg);">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="page-banner-content text-center text-white">
                    <h1 class="page-title text-white"><?php echo $display_category; ?></h1>
                    <ul class="breadcrumb-link text-white">
                        <li><a href="index.php">Home</a></li>
                        <li class="active"><?php echo $display_category; ?></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section><!--====== End Breadcrumb Section ======-->

<!--====== Start Booking Section ======-->
<section class="booking-form-section pb-100">
    <div class="container-fluid ">
        <div class="booking-form-wrapper p-r z-2">
            <form action="tour.php" class="booking-form-two justify-content-center">
                <div class="form_group">
                    <span>Tour Start Date</span>
                    <label><i class="far fa-calendar-alt"></i></label>
                    <input type="text" class="form_control datepicker" placeholder="Check In">
                </div>
                <div class="form_group">
                    <span>Accommodations</span>
                    <select class="wide">
                        <option data-display="Accommodations">Accommodations</option>
                        <option value="01">Classic Tent</option>
                        <option value="02">Forest Camping</option>
                        <option value="03">Small Trailer</option>
                        <option value="04">Tree House Tent</option>
                        <option value="05">Tent Camping</option>
                        <option value="06">Couple Tent</option>
                    </select>
                </div>
                <div class="form_group">
                    <button class="booking-btn">Check Availability <i class="far fa-angle-double-right"></i></button>
                </div>
            </form>
        </div>
    </div>
</section><!--====== End Booking Section ======-->

<!--====== Start Places Section ======-->
<section class="places-section pb-100">
    <div class="container">
        <div class="row justify-content-center">
            <?php if (!empty($tours_list)): ?>
                <?php foreach ($tours_list as $tour): ?>
                    <div class="col-xl-4 col-md-6 col-sm-12 places-column">
                        <!--=== Single Place Item ===-->
                        <div class="single-place-item mb-60 wow fadeInUp">
                            <div class="place-img">
                                <img src="<?php echo htmlspecialchars($tour['image']); ?>" alt="Place Image" onerror="this.onerror=null; this.src='assets/images/tour.jpg'">
                            </div>
                            <div class="place-content">
                                <div class="info">
                                    <ul class="ratings">
                                        <li><i class="fas fa-star"></i></li>
                                        <li><i class="fas fa-star"></i></li>
                                        <li><i class="fas fa-star"></i></li>
                                        <li><i class="fas fa-star"></i></li>
                                        <li><i class="fas fa-star"></i></li>
                                        <li><a href="#">(4.9)</a></li>
                                    </ul>
                                    <h4 class="title"><a href="tour-details.php?id=<?php echo $tour['id']; ?>"><?php echo htmlspecialchars($tour['title']); ?></a></h4>
                                    <p class="location"><i class="far fa-map-marker-alt"></i><?php echo htmlspecialchars($tour['destination'] ?? $tour['location'] ?? 'Various'); ?></p>
                                    <p class="price"><i class="fas fa-usd-circle"></i>Price <span class="currency">$</span><?php echo htmlspecialchars($tour['price']); ?> USD Per Pax</p>
                                    <div class="meta">
                                        <span><i class="far fa-clock"></i><?php echo htmlspecialchars($tour['duration']); ?></span>
                                        <span><i class="far fa-user"></i><?php echo htmlspecialchars($tour['max_people'] ?? '25'); ?></span>
                                        <span><a href="tour-details.php?id=<?php echo $tour['id']; ?>">Details<i class="far fa-long-arrow-right"></i></a></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12 text-center">
                    <p>No tours found in this category.</p>
                </div>
            <?php endif; ?>
        </div>
        
        <?php if (!empty($tours_list)): ?>
        <div class="row">
            <div class="col-lg-12">
                <!--=== Gowilds Pagination ===-->
                <ul class="gowilds-pagination wow fadeInUp text-center">
                    <li><a href="#"><i class="far fa-arrow-left"></i></a></li>
                    <li><a href="#" class="active">01</a></li>
                    <li><a href="#">02</a></li>
                    <li><a href="#">03</a></li>
                    <li><a href="#">04</a></li>
                    <li><a href="#"><i class="far fa-arrow-right"></i></a></li>
                </ul>
            </div>
        </div>
        <?php endif; ?>
    </div>
</section><!--====== End Places Section ======-->



<?php include 'includes/footer.php'; ?>