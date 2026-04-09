<?php
include 'includes/header.php';
require_once 'admin/includes/db.php';

// Get tour ID from URL
$tour_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($tour_id <= 0) {
    echo "<script>window.location.href='tour.php';</script>";
    exit;
}

// Fetch tour details
$tour_query = "SELECT * FROM tours WHERE id = $tour_id AND status = 'active'";
$tour_result = $conn->query($tour_query);

if (!$tour_result || $tour_result->num_rows == 0) {
    echo "<script>window.location.href='tour.php';</script>";
    exit;
}

$tour = $tour_result->fetch_assoc();

// Fetch itinerary
$itinerary_query = "SELECT * FROM itineraries WHERE tour_id = $tour_id ORDER BY day_number ASC";
$itinerary_result = $conn->query($itinerary_query);
$itineraries = [];
if ($itinerary_result && $itinerary_result->num_rows > 0) {
    while ($row = $itinerary_result->fetch_assoc()) {
        $itineraries[] = $row;
    }
}

// Fetch reviews
$reviews_query = "SELECT * FROM reviews WHERE tour_id = $tour_id AND status = 'approved' ORDER BY created_at DESC";
$reviews_result = $conn->query($reviews_query);
$reviews = [];
$total_rating = 0;
if ($reviews_result && $reviews_result->num_rows > 0) {
    while ($row = $reviews_result->fetch_assoc()) {
        $reviews[] = $row;
        $total_rating += $row['rating'];
    }
}
$avg_rating = count($reviews) > 0 ? round($total_rating / count($reviews), 1) : 0;

// Fetch related tours
$related_query = "SELECT * FROM tours WHERE category = '" . $conn->real_escape_string($tour['category']) . "' AND id != $tour_id LIMIT 6";
$related_result = $conn->query($related_query);
?>

        <!--====== Start Place Details Section ======-->
        <section class="place-details-section">
            <!--=== Single Place Image (No Slider) ===-->
            <div class="place-img-area wow fadeInUp">
                <div class="place-img">
                    <img src="<?php echo htmlspecialchars($tour['image']); ?>" alt="Place Image" style="height: 550px; width: 100%; object-fit: cover;" onerror="this.onerror=null; this.src='assets/images/tour.jpg'">
                </div>
            </div>
            <div class="container">
                <!--=== Tour Details Wrapper ===-->
                <div class="tour-details-wrapper pt-80">
                    <!--=== Tour Title Wrapper ===-->
                    <div class="tour-title-wrapper pb-30 wow fadeInUp">
                        <div class="row">
                            <div class="col-xl-6">
                                <div class="tour-title mb-20">
                                    <h3 class="title"><?php echo htmlspecialchars($tour['title']); ?></h3>
                                    <p><i class="far fa-map-marker-alt"></i><?php echo htmlspecialchars($tour['destination']); ?></p>
                                </div>
                            </div>
                            <div class="col-xl-6">
                                <div class="tour-widget-info">
                                    <div class="info-box mb-20">
                                        <div class="icon">
                                            <i class="fal fa-box-usd"></i>
                                        </div>
                                        <div class="info">
                                            <h4><span>From</span>₹<?php echo number_format($tour['price'], 2); ?></h4>
                                        </div>
                                    </div>
                                    <div class="info-box mb-20">
                                        <div class="icon">
                                            <i class="fal fa-clock"></i>
                                        </div>
                                        <div class="info">
                                            <h4><span>Durations</span><?php echo htmlspecialchars($tour['duration']); ?></h4>
                                        </div>
                                    </div>
                                    <div class="info-box mb-20">
                                        <div class="icon">
                                            <i class="fal fa-planet-ringed"></i>
                                        </div>
                                        <div class="info">
                                            <h4><span>Tour Type</span><?php echo htmlspecialchars($tour['tour_type'] ?: 'City Tour'); ?></h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--=== Tour Area Nav ===-->
                    <div class="tour-area-nav pt-20 pb-20 wow fadeInUp">
                        <div class="row align-items-center">
                            <div class="col-md-4">
                                <div class="ratings-box">
                                    <ul class="ratings">
                                        <?php for($i=1; $i<=5; $i++): ?>
                                            <li><i class="fas fa-star<?php echo $i <= $avg_rating ? '' : '-half-alt'; ?>" style="<?php echo $i <= $avg_rating ? 'color: #f7921e;' : ''; ?>"></i></li>
                                        <?php endfor; ?>
                                        <li><a href="#reviews">(<?php echo count($reviews); ?> Reviews)</a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="share-nav">
                                    <a href="#">Share<i class="far fa-share"></i></a>
                                    <a href="#reviews">Reviews<i class="far fa-comment"></i></a>
                                    <a href="wishlist_action.php?id=<?php echo $tour_id; ?>">Wishlist<i class="far fa-heart"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row pb-5">
                        <div class="col-xl-8">
                            <!--=== Place Content Wrap ===-->
                            <div class="place-content-wrap pt-45 wow fadeInUp">
                                <h3 class="title">Glimpse of Tour</h3>
                                <p><?php echo nl2br(htmlspecialchars($tour['description'])); ?></p>
                                
                                <?php if (!empty($tour['more_info'])): ?>
                                <h4>Important Information</h4>
                                <div class="row align-items-lg-center py-4">
                                    <div class="col-lg-12">
                                        <div class="more-info-content">
                                            <?php echo nl2br($tour['more_info']); ?>
                                        </div>
                                    </div>
                                </div>
                                <?php endif; ?>
                                
                                <div class="plan-detail py-3">
									<div class="d-flex align-items-center justify-content-between">
										<h4 class="mb-4 flex-fill">Itinerary</h4>
										<!-- Toggle Switch -->
										<div class="form-check form-switch mb-4 ms-auto">
											<input class="form-check-input" type="checkbox" id="toggleAll">
											<label class="form-check-label" for="toggleAll">Toggle All</label>
										</div>	
									</div>
									<div class="timeline accordion ps-5" id="itineraryAccordion">
									  <?php if (!empty($itineraries)): ?>
                                          <?php foreach($itineraries as $index => $item): ?>
                                          <div class="timeline-item accordion-item">
                                             <h2 class="accordion-header" id="heading<?php echo $index; ?>">
                                                <button class="accordion-button <?php echo $index == 0 ? '' : 'collapsed'; ?>" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?php echo $index; ?>" aria-expanded="<?php echo $index == 0 ? 'true' : 'false'; ?>" aria-controls="collapse<?php echo $index; ?>">
                                                    Day <?php echo $item['day_number']; ?> : <?php echo htmlspecialchars($item['title']); ?>
                                                </button>
                                                </h2>
                                              <div id="collapse<?php echo $index; ?>" class="accordion-collapse collapse <?php echo $index == 0 ? 'show' : ''; ?>" aria-labelledby="heading<?php echo $index; ?>">
                                                    <div class="accordion-body">
                                                        <div class="row">
                                                            <div class="col-lg-12">
                                                                <p><?php echo nl2br(htmlspecialchars($item['description'])); ?></p>
                                                            </div>
                                                            <?php
                                                            // Fetch itinerary images if any
                                                            $img_query = "SELECT image FROM itinerary_images WHERE itinerary_id = " . $item['id'];
                                                            $img_res = $conn->query($img_query);
                                                            if ($img_res && $img_res->num_rows > 0):
                                                                while($img = $img_res->fetch_assoc()):
                                                            ?>
                                                            <div class="col-lg-3">
                                                                <img src="<?php echo htmlspecialchars($img['image']); ?>" class="img-fluid" alt="Itinerary Image" onerror="this.onerror=null; this.src='assets/images/tour.jpg'">
                                                            </div>
                                                            <?php endwhile; endif; ?>
                                                        </div>
                                                    </div>
                                                </div>
                                           </div>
                                          <?php endforeach; ?>
                                      <?php else: ?>
                                          <p>No itinerary available for this tour.</p>
                                      <?php endif; ?>
									</div>
								  </div>
                            </div>
                            
                            <!--=== Map Box ===-->
                            <div class="map-box mb-60 wow fadeInUp pt-5 ">
                                <iframe src="https://maps.google.com/maps?q=<?php echo urlencode($tour['destination']); ?>&t=&z=13&ie=UTF8&iwloc=&output=embed"></iframe>
                            </div>
                           
                            <!--=== Releted Tour Place ===-->
                            <div class="related-tour-place wow fadeInUp">
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="section-title mb-35">
                                            <h3>Related Tours</h3>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="place-arrows mb-35"></div>
                                    </div>
                                </div>
                                <div class="recent-place-slider">
                                    <?php if ($related_result && $related_result->num_rows > 0): ?>
                                        <?php while($related = $related_result->fetch_assoc()): ?>
                                        <!--=== Single Place Item ===-->
                                        <div class="single-place-item mb-60 wow fadeInUp">
                                            <div class="place-img">
                                                <img src="<?php echo htmlspecialchars($related['image']); ?>" alt="Place Image" onerror="this.onerror=null; this.src='assets/images/tour.jpg'">
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
                                                    <h4 class="title"><a href="tour-details.php?id=<?php echo $related['id']; ?>"><?php echo htmlspecialchars($related['title']); ?></a></h4>
                                                    <p class="location"><i class="far fa-map-marker-alt"></i><?php echo htmlspecialchars($related['destination']); ?></p>
                                                    <p class="price"><i class="fas fa-rupee-sign"></i>Price <span class="currency">₹</span><?php echo number_format($related['price'], 2); ?></p>
                                                    <div class="meta">
                                                        <span><i class="far fa-clock"></i><?php echo htmlspecialchars($related['duration']); ?></span>
                                                        <span><i class="far fa-user"></i><?php echo htmlspecialchars($related['max_people']); ?></span>
                                                        <span><a href="tour-details.php?id=<?php echo $related['id']; ?>">Details<i class="far fa-long-arrow-right"></i></a></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php endwhile; ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <!--=== Reviews Area ===-->
                            <div id="reviews" class="reviews-wrapper mb-60 wow fadeInUp">
                                <div class="reviews-inner-box">
                                    <div class="rating-value">
                                        <h4>Clients Reviews</h4>
                                        <div class="rate-score"><?php echo $avg_rating; ?></div>
                                        <ul class="ratings">
                                            <?php for($i=1; $i<=5; $i++): ?>
                                                <li><i class="fas fa-star<?php echo $i <= $avg_rating ? '' : ($i - $avg_rating < 1 ? '-half-alt' : ''); ?>"></i></li>
                                            <?php endfor; ?>
                                        </ul>
                                        <span class="reviews"><?php echo count($reviews); ?> Reviews</span>
                                    </div>
                                    <div class="reviews-progress">
                                        <!-- Example progress bars (can be randomized or averaged if needed) -->
                                        <div class="single-progress-bar">
                                            <div class="progress-title">
                                                <h6>Quality <span class="rate">4.8</span></h6>
                                            </div>
                                            <div class="progress">
                                                <div class="progress-bar wow slideInLeft" style="width: 85%"></div>
                                            </div>
                                        </div>
                                        <div class="single-progress-bar">
                                            <div class="progress-title">
                                                <h6>Team Member<span class="rate">4.6</span></h6>
                                            </div>
                                            <div class="progress">
                                                <div class="progress-bar wow slideInLeft" style="width: 75%"></div>
                                            </div>
                                        </div>
                                        <div class="single-progress-bar">
                                            <div class="progress-title">
                                                <h6>Locations<span class="rate">4.7</span></h6>
                                            </div>
                                            <div class="progress">
                                                <div class="progress-bar wow slideInLeft" style="width: 90%"></div>
                                            </div>
                                        </div>
                                        <div class="single-progress-bar">
                                            <div class="progress-title">
                                                <h6>Cost<span class="rate">4.9</span></h6>
                                            </div>
                                            <div class="progress">
                                                <div class="progress-bar wow slideInLeft" style="width: 95%"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="comments-area mt-40">
                                    <ul class="comment-list">
                                        <?php foreach($reviews as $review): ?>
                                        <li>
                                            <div class="comment">
                                                <div class="comment-wrap">
                                                    <div class="comment-author-content">
                                                        <span class="author-name"><?php echo htmlspecialchars($review['user_name']); ?><span class="time"><i class="far fa-clock"></i><?php echo date('d M, Y', strtotime($review['created_at'])); ?></span></span>
                                                        <ul class="ratings">
                                                            <?php for($i=1; $i<=5; $i++): ?>
                                                                <li><i class="fas fa-star<?php echo $i <= $review['rating'] ? '' : '-half-alt'; ?>" style="<?php echo $i <= $review['rating'] ? 'color: #f7921e;' : ''; ?>"></i></li>
                                                            <?php endfor; ?>
                                                        </ul>
                                                        <p><?php echo htmlspecialchars($review['comment']); ?></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            </div>
                            
                            <!--===  Comments Form  ===-->
                            <div class="comments-respond mb-30 wow fadeInUp">
                                <h3 class="comments-heading" style="margin-bottom: 15px;">Need additional Information?</h3>
                                <form action="process_inquiry.php" method="POST" class="comment-form">
                                    <input type="hidden" name="tour_id" value="<?php echo $tour_id; ?>">
                                    <div class="row">
									  <!-- Name -->
									  <div class="col-lg-12 mb-3">
										<div class="form-group">
										  <label for="name" class="form-label">Full Name</label>
										  <input type="text" class="form-control" id="name" name="name" placeholder="Enter your name" required>
										</div>
									  </div>

									  <!-- Email -->
									  <div class="col-lg-6 mb-3">
										<div class="form-group">
										  <label for="email" class="form-label">Email Address</label>
										  <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required>
										</div>
									  </div>

									  <!-- Phone -->
									  <div class="col-lg-6 mb-3">
										  <div class="form-group">
											<label for="phone" class="form-label">Enter Number</label>
											<input type="text" class="form-control" id="phone" name="phone" placeholder="Enter phone number" required>
										</div>
									  </div>
									  <!-- Message -->
									  <div class="col-lg-12 mb-3">
										<div class="form-group">
										  <label for="message" class="form-label">Comments</label>
										  <textarea name="message" id="message" class="form-control" rows="4" placeholder="Write your comments" required></textarea>
										</div>
									  </div>

									  <!-- Submit -->
									  <div class="col-lg-12">
										<div class="form-group">
										  <button type="submit" class="btn btn-primary">
											Send <i class="fas fa-angle-double-right"></i>
										  </button>
										</div>
									  </div>
									</div>
                                </form>
                            </div>
                        </div>
                        <div class="col-xl-4">
                            <!--=== Sidebar Widget Area ===-->
                            <div class="sidebar-widget-area pt-60 pl-lg-30">
                                <!--=== Booking Widget ===-->
                                <div class="sidebar-widget booking-form-widget wow fadeInUp mb-40">
                                    <h4 class="widget-title">Booking Tour</h4>
                                    <form class="sidebar-booking-form" action="tour_booking_process.php" method="POST">
                                        <input type="hidden" name="tour_id" value="<?php echo $tour_id; ?>">
                                        <div class="booking-date-time mb-20">
                                            <div class="booking-item">
                                                <label>Date</label>
                                                <div class="bk-item booking-time">
                                                    <i class="far fa-calendar-alt"></i>
                                                    <input type="text" name="tour_date" placeholder="Select Date" class="datepicker" required>
                                                </div>
                                            </div>
                                            <div class="booking-item">
                                                <label>Time</label>
                                                <div class="bk-item booking-date">
                                                    <i class="far fa-clock"></i>
                                                    <select class="wide" name="booking_time">
                                                        <option value="09:00 AM">09.00 Am</option>
                                                        <option value="10:00 AM">10.00 Am</option>
                                                        <option value="11:00 AM">11.00 Am</option>
                                                        <option value="12:00 PM">12.00 Pm</option>
                                                        <option value="01:00 PM">01.00 Pm</option>
                                                        <option value="02:00 PM">02.00 Pm</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="booking-guest-box mb-20">
                                            <h6 class="mb-10">Customer Details</h6>
                                            <div class="booking-item mb-2">
                                                <input type="text" name="customer_name" class="form-control" placeholder="Full Name" value="<?php echo $_SESSION['user_name'] ?? ''; ?>" required>
                                            </div>
                                            <div class="booking-item mb-2">
                                                <input type="email" name="customer_email" class="form-control" placeholder="Email Address" value="<?php echo $_SESSION['user_email'] ?? ''; ?>" required>
                                            </div>
                                            <div class="booking-item mb-2">
                                                <input type="text" name="customer_phone" class="form-control" placeholder="Mobile Number" value="<?php echo $_SESSION['user_phone'] ?? ''; ?>" required>
                                            </div>

                                            <h6 class="mb-20 mt-20">Travellers</h6>
                                            <div class="booking-item">
                                                <label>Adults </label>
                                                <div class="bk-item booking-user">
                                                    <i class="far fa-user"></i>
                                                    <select class="wide" name="adults" id="adults_count">
                                                        <?php for($i=1;$i<=10;$i++): ?>
                                                        <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                                        <?php endfor; ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="booking-item">
                                                <label>Child (upto 12 Years)</label>
                                                <div class="bk-item booking-user">
                                                    <i class="far fa-user"></i>
                                                    <select class="wide" name="children" id="child_count">
                                                        <?php for($i=0;$i<=10;$i++): ?>
                                                        <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                                        <?php endfor; ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="booking-item">
                                                <label>Infants (<2 years ) </label>
                                                <div class="bk-item booking-user">
                                                    <i class="far fa-user"></i>
                                                    <select class="wide" name="infants" id="infants_count">
                                                        <?php for($i=0;$i<=5;$i++): ?>
                                                        <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                                        <?php endfor; ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        
										<div class=" mb-20">
                                            <h6 class="mb-20">Hotels Type</h6>
                                            <div class="booking-item">
                                                <div class="bk-item booking-user">
                                                    <i class="fas fa-hotel"></i>
                                                    <select class="wide w-100 pe-5" name="hotel_type" id="hotel_type">
                                                        <option value="Budget" data-price="0">Budget hotels</option>
                                                        <option value="3*" data-price="<?php echo $tour['price_3star']; ?>">3* (+₹<?php echo $tour['price_3star']; ?>)</option>
                                                        <option value="4*" data-price="<?php echo $tour['price_4star']; ?>">4* (+₹<?php echo $tour['price_4star']; ?>)</option>
                                                        <option value="5*" data-price="<?php echo $tour['price_5star']; ?>">5* (+₹<?php echo $tour['price_5star']; ?>)</option>
                                                        <option value="Camps" data-price="<?php echo $tour['price_camps']; ?>">Camps (+₹<?php echo $tour['price_camps']; ?>)</option>
														<option value="Homestay" data-price="<?php echo $tour['price_homestay']; ?>">Homestay (+₹<?php echo $tour['price_homestay']; ?>)</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="booking-total mb-20">
                                            <div class="total">
                                                <label>Price per Adult</label>
                                                <span class="price">₹<span id="base_price"><?php echo number_format($tour['price'], 2); ?></span></span>
                                            </div>
                                        </div>
                                        <div class="submit-button">
                                            <?php if($is_logged_in): ?>
                                                <button type="submit" class="main-btn primary-btn">Book Now<i class="far fa-paper-plane"></i></button>
                                            <?php else: ?>
                                                <a href="login.php?return_url=<?php echo urlencode($_SERVER['REQUEST_URI']); ?>" class="main-btn primary-btn">Login to Book<i class="fas fa-lock"></i></a>
                                            <?php endif; ?>
                                        </div>
                                    </form>
                                </div>
                                <!--=== Booking Info Widget ===-->
                                <div class="sidebar-widget booking-info-widget wow fadeInUp mb-40">
                                    <h4 class="widget-title">Tour Information</h4>
                                    <ul class="info-list">
										<li><span><i class="far fa-user-circle"></i>Min Guests<span><?php echo $tour['min_people']; ?></span></span></li>
                                        <li><span><i class="far fa-user-circle"></i>Max Guests<span><?php echo $tour['max_people']; ?></span></span></li>
                                        <li><span><i class="far fa-calendar-alt"></i>Minimum Age<span><?php echo $tour['min_age']; ?></span></span></li>
                                        <li><span><i class="far fa-map-marker-alt"></i>Tour Location<span><?php echo htmlspecialchars($tour['destination']); ?></span></span></li>
                                        <li><span><i class="far fa-globe"></i>Language<span><?php echo htmlspecialchars($tour['language']); ?></span></span></li>
                                    </ul>
                                </div>
                                
                                <!--=== Banner Widget ===-->
                                <div class="sidebar-widget sidebar-banner-widget wow fadeInUp mb-40">
                                    <div class="banner-widget-content">
                                        <div class="banner-img">
                                            <img src="assets/images/banner-1.webp" alt="Post Banner">
                                            <div class="hover-overlay">
                                                <div class="hover-content ">
                                                    <h4 class="title text-white"><a href="#">Adventure Tours</a></h4>
                                                    <p class="text-white"><i class="fas fa-map-marker-alt text-white"></i>Explore the World with MyEasyTrip</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section><!--====== End Place Details Section ======-->
		

<?php include 'includes/footer.php'; ?>

<script>
    document.getElementById("toggleAll").addEventListener("change", function() {
        let allCollapses = document.querySelectorAll(".accordion-collapse");
        if (this.checked) {
            allCollapses.forEach(collapse => {
                let bsCollapse = new bootstrap.Collapse(collapse, { toggle: false });
                bsCollapse.show();
            });
        } else {
            allCollapses.forEach(collapse => {
                let bsCollapse = new bootstrap.Collapse(collapse, { toggle: false });
                bsCollapse.hide();
            });
        }
    });

    // Price calculation logic could go here if needed dynamically
</script>