<?php
include 'includes/header.php';
require_once 'admin/includes/db.php';

// Fetch Featured Tours
$featured_tours_query = "SELECT * FROM tours WHERE status = 'active' LIMIT 8";
$featured_tours_result = $conn->query($featured_tours_query);

// Fetch Destinations with tour count
$dest_query = "SELECT destination, COUNT(*) as tour_count FROM tours WHERE status = 'active' GROUP BY destination LIMIT 6";
$dest_result = $conn->query($dest_query);

// Fetch Testimonials (Latest approved reviews)
$testimonial_query = "SELECT r.*, t.title as tour_name FROM reviews r JOIN tours t ON r.tour_id = t.id WHERE r.status = 'approved' ORDER BY r.created_at DESC LIMIT 5";
$testimonial_result = $conn->query($testimonial_query);
?>

    <!--====== Start Hero Section ======-->
    <section class="hero-section">
        <div class="hero-wrapper-three">
            <div class="hero-arrows"></div>
            <div class="hero-slider-three">
                <!--=== Single Slider ===-->
                <div class="single-slider">
                    <div class="image-layer bg_cover" style="background-image: url(assets/images/slider-1.jpg);"></div>
                    <div class="container">
                        <div class="row align-items-center">
                            <div class="col-xl-7">
                                <!--=== Hero Content ===-->
                                <div class="hero-content text-white">
                                    <span class="sub-title">Welcome to GoWilds</span>
                                    <h1 data-animation="fadeInDown" data-delay=".4s">Tour Travel
                                        & Adventure</h1>
                                    <div class="hero-button" data-animation="fadeInRight" data-delay=".6s">
                                        <a href="tour.php" class="main-btn primary-btn">Explore More<i
                                                class="fas fa-paper-plane"></i></a>
                                        <a href="about.php" class="main-btn secondary-btn">Learn More<i
                                                class="fas fa-paper-plane"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--=== Single Slider ===-->
                <div class="single-slider">
                    <div class="image-layer bg_cover" style="background-image: url(assets/images/slider-2.jpg);"></div>
                    <div class="container">
                        <div class="row align-items-center">
                            <div class="col-xl-7">
                                <!--=== Hero Content ===-->
                                <div class="hero-content text-white">
                                    <span class="sub-title">Welcome to GoWilds</span>
                                    <h1 data-animation="fadeInDown" data-delay=".4s">Tour Travel
                                        & Camping</h1>
                                    <div class="hero-button" data-animation="fadeInRight" data-delay=".6s">
                                        <a href="tour.php" class="main-btn primary-btn">Explore More<i
                                                class="fas fa-paper-plane"></i></a>
                                        <a href="about.php" class="main-btn secondary-btn">Learn More<i
                                                class="fas fa-paper-plane"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--=== Single Slider ===-->
                <div class="single-slider">
                    <div class="image-layer bg_cover" style="background-image: url(assets/images/slider-3.jpg);"></div>
                    <div class="container">
                        <div class="row align-items-center">
                            <div class="col-xl-7">
                                <!--=== Hero Content ===-->
                                <div class="hero-content text-white">
                                    <span class="sub-title">Welcome to GoWilds</span>
                                    <h1 data-animation="fadeInDown" data-delay=".4s">Tour Travel
                                        & Adventure</h1>
                                    <div class="hero-button" data-animation="fadeInRight" data-delay=".6s">
                                        <a href="tour.php" class="main-btn primary-btn">Explore More<i
                                                class="fas fa-paper-plane"></i></a>
                                        <a href="about.php" class="main-btn secondary-btn">Learn More<i
                                                class="fas fa-paper-plane"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--====== End Hero Section ======-->

    <section class="services-seciton pt-100" style="background-image: url(assets/images/bg-shape-01.png);">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-8 col-lg-10">
                    <!--=== Section Title ===-->
                    <div class="section-title text-center mb-60 wow fadeInDown">
                        <span class="sub-title">Popular Services</span>
                        <h2>Amazing Adventure Camping
                            Services for Enjoyed</h2>
                    </div>
                </div>
            </div>
            <div class="slider-active-4-item wow fadeInUp">
                <!--=== Single Service Item ===-->
                <div class="single-features-item mb-40">
                    <div class="img-holder">
                        <img src="assets/images/image-01.jpg" alt="Features Image">
                        <div class="content">
                            <div class="text">
                                <h4 class="title">Tent Camping
                                    Services</h4>
                                <a href="tour.php?category=Wild%20Safari" class="icon-btn"><i class="far fa-arrow-right"></i></a>
                            </div>
                            <p>Adventure awaits in our luxury tents across wild terrains.</p>
                        </div>
                    </div>
                </div>
                <!--=== Single Service Item ===-->
                <div class="single-features-item mb-40">
                    <div class="img-holder">
                        <img src="assets/images/image-02.jpg" alt="Features Image">
                        <div class="content">
                            <div class="text">
                                <h4 class="title">Trailers and RV Spots</h4>
                                <a href="tour.php" class="icon-btn"><i class="far fa-arrow-right"></i></a>
                            </div>
                            <p>Explore the freedom of the road with curated RV destinations.</p>
                        </div>
                    </div>
                </div>
                <!--=== Single Service Item ===-->
                <div class="single-features-item mb-40">
                    <div class="img-holder">
                        <img src="assets/images/image-03.jpg" alt="Features Image">
                        <div class="content">
                            <div class="text">
                                <h4 class="title">Adventure and Climbing</h4>
                                <a href="tour.php?category=Trek" class="icon-btn"><i class="far fa-arrow-right"></i></a>
                            </div>
                            <p>Scale new heights with our expert-led trekking expeditions.</p>
                        </div>
                    </div>
                </div>
                <!--=== Single Service Item ===-->
                <div class="single-features-item mb-40">
                    <div class="img-holder">
                        <img src="assets/images/image-04.jpg" alt="Features Image">
                        <div class="content">
                            <div class="text">
                                <h4 class="title">Couple Camping
                                    or Cabin</h4>
                                <a href="tour.php" class="icon-btn"><i class="far fa-arrow-right"></i></a>
                            </div>
                            <p>Romantic getaways in the heart of nature.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--Endd of Service Section -->

    <!--====== Start About Section ======-->
    <section class="we-section pt-100 pb-50" style="background-image: url(assets/images/bg-shape-02.png">
        <div class="container">
            <div class="row align-items-xl-center">
                <div class="col-xl-6 order-2 order-xl-1">
                    <!--=== We Image Box ===-->
                    <div class="we-two_image-box mb-20">
                        <div class="we-image mb-30">
                            <img src="assets/images/abt.png" alt="we Image">
                        </div>
                    </div>
                </div>
                <div class="col-xl-6 order-1 order-xl-2">
                    <!--=== We Content Box ===-->
                    <div class="we-content-box pl-lg-50 mb-10">
                        <!--=== Section Title ===-->
                        <div class="section-title mb-30">
                            <span class="sub-title">Who We Are</span>
                            <h2>Great Opportunity For
                                Adventure & Travels</h2>
                        </div>
                        <!--=== Features List ===-->
                        <div class="features-list_one">
                            <!--=== Single Features List ===-->
                            <div class="single-features-list mb-40">
                                <div class="icon-inner d-flex align-items-center">
                                    <div class="icon-check">
                                        <i class="fas fa-badge-check"></i>
                                    </div>
                                    <div class="icon">
                                        <i class="flaticon-helmet"></i>
                                    </div>
                                </div>
                                <div class="content">
                                    <h4>Safety First Always</h4>
                                    <p>Your safety is our top priority. We use certified equipment and experienced guides for all adventures.</p>
                                </div>
                            </div>
                            <!--=== Single Features List ===-->
                            <div class="single-features-list mb-40">
                                <div class="icon-inner d-flex align-items-center">
                                    <div class="icon-check">
                                        <i class="fas fa-badge-check"></i>
                                    </div>
                                    <div class="icon">
                                        <i class="flaticon-best-price"></i>
                                    </div>
                                </div>
                                <div class="content">
                                    <h4>Low Price & Friendly</h4>
                                    <p>Competitive pricing without compromising on the quality of your experience.</p>
                                </div>
                            </div>
                            <!--=== Single Features List ===-->
                            <div class="single-features-list mb-40">
                                <div class="icon-inner d-flex align-items-center">
                                    <div class="icon-check">
                                        <i class="fas fa-badge-check"></i>
                                    </div>
                                    <div class="icon">
                                        <i class="flaticon-travel"></i>
                                    </div>
                                </div>
                                <div class="content">
                                    <h4>Trusted Travel Guide</h4>
                                    <p>Over 10 years of experience in curating the best adventures around the world.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!--====== Start Populat Activities ======-->
    <section class="about-section bg_cover pt-165 pb-210" style="background-image: url(assets/images/about-bg-1.jpg);">
        <div class="container">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-xl-7">
                        <div class="section-title text-center mb-50 wow fadeInDown">
                            <span class="sub-title">Popular Activity</span>
                            <h2 class="text-white">Feel Real Adventure and Very
                                Close to Nature</h2>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-4">
                        <!--=== Activity Nav Tab ===-->
                        <div class="activity-nav-tab mb-50 wow fadeInLeft">
                            <ul class="nav nav-tabs">
                                <li>
                                    <a href="#tab1" class="nav-link active" data-bs-toggle="tab"
                                        data-bs-target="#tab1">Tent Camping</a>
                                </li>
                                <li>
                                    <a href="#tab2" class="nav-link" data-bs-toggle="tab"
                                        data-bs-target="#tab2">Mountain Biking</a>
                                </li>
                                <li>
                                    <a href="#tab3" class="nav-link" data-bs-toggle="tab"
                                        data-bs-target="#tab3">Birdwatching</a>
                                </li>
                                <li>
                                    <a href="#tab4" class="nav-link" data-bs-toggle="tab"
                                        data-bs-target="#tab4">Fishing</a>
                                </li>
                                <li>
                                    <a href="#tab5" class="nav-link" data-bs-toggle="tab"
                                        data-bs-target="#tab5">Wild Safari</a>
                                </li>
                                <li>
                                    <a href="#tab6" class="nav-link" data-bs-toggle="tab"
                                        data-bs-target="#tab6">Mountain Hiking</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-8">
                        <!--=== Tab Content ===-->
                        <div class="tab-content mb-50 wow fadeInRight">
                            <!--=== Tab Pane ===-->
                            <div class="tab-pane fade show active" id="tab1">
                                <div class="row align-items-center">
                                    <div class="col-md-6">
                                        <!--=== Activity Content Box ===-->
                                        <div class="activity-content-box pl-lg-40">
                                            <div class="icon">
                                                <i class="flaticon-camp"></i>
                                            </div>
                                            <h3 class="title text-white">Real Adventure & Enjoy
                                                Your Dream Tours</h3>
                                            <p class="text-white">Experience the night under millions of stars with our professional camping setups.</p>
                                            <ul class="check-list check2">
                                                <li class="text-white"><i class="fas fa-badge-check"></i>Family Camping
                                                </li>
                                                <li class="text-white"><i class="fas fa-badge-check"></i>Couple Camping
                                                </li>
                                                <li class="text-white"><i class="fas fa-badge-check"></i>Wild Camping
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <!--=== Activity Image Box ===-->
                                        <div class="activity-image-box">
                                            <img src="assets/images/image-06.jpg" class="radius-12" alt="Image">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Repeat for other tabs with relevant static/dynamic content -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!--====== Start Fun Section ======-->
    <section class="fun-section">
        <div class="container">
            <!--=== Fun Wrapper ===-->
            <div class="fun-wrapper pt-30 pb-20 bg_cover" style="background-image: url(assets/images/bg-shape-04.png);">
                <div class="row">
                    <div class="col-lg-9 col-sm-6">
                        <!--=== Counter Item ===-->
                        <div class="single-counter-item-two">
                            <div class="inner-counter d-flex ">
                                <div class="icon">
                                    <i class="flaticon-journey"></i>
                                </div>
                                <div class="content pt-10">
                                    <p class="text-white">Happy Traveler</p>
                                    <h3 class="text-white">Ready to adventure and enjoy natural</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6">
                        <div class="btn">
                            <a href="contact.php" class="main-btn bg-white">Learn More<i class="far fa-paper-plane"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--====== End Fun Section ======-->

    <section class="about-section pt-100 pb-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-6 col-lg-9">
                    <!--=== About Content Box ===-->
                    <div class="about-content-box text-center mb-55 wow fadeInDown">
                        <div class="section-title mb-30">
                            <span class="sub-title">About Company</span>
                            <h2>Amazing tour places around the world</h2>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <?php if ($featured_tours_result && $featured_tours_result->num_rows > 0): ?>
                        <?php while ($tour = $featured_tours_result->fetch_assoc()): ?>
                        <div class="col-lg-3 col-md-6 col-sm-12 mb-30">
                            <div class="card tour-card shadow-lg h-100">
                                <span class="offer-badge"><?php echo $tour['tour_type']; ?></span>
                                <img src="<?php echo htmlspecialchars($tour['image']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($tour['title']); ?>" onerror="this.onerror=null; this.src='assets/images/tour.jpg'">
                                <div class="tour-info">
                                    <div class="d-flex align-items-center mb-2">
                                        <span class="text-warning me-2">★★★★★</span>
                                        <span>(5)</span>
                                    </div>
                                    <h5 class="fw-bold mb-1"><a href="tour-details.php?id=<?php echo $tour['id']; ?>"><?php echo htmlspecialchars($tour['title']); ?></a></h5>
                                    <p class="mb-1"><i class="bi bi-geo-alt-fill"></i> <?php echo htmlspecialchars($tour['destination']); ?></p>
                                    <p class="mb-0">From <span class="text-success fw-bold">₹<?php echo number_format($tour['price'], 2); ?></span></p>
                                </div>
                            </div>
                        </div>
                        <?php endwhile; ?>
                    <?php endif; ?>
                </div>

                <div class="row pt-5">
                    <div class="col-lg-4">
                        <div class="tour-info2">
                            <div class="icon pe-4">
                                <i class="flaticon-wifi-router"></i>
                            </div>
                            <div class="text">
                                <h4>Best Security</h4>
                                <p class="pt-2">We prioritize your safety with world-class security protocols and expert guides.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="tour-info2">
                            <div class="icon pe-4">
                                <i class="flaticon-wifi-router"></i>
                            </div>
                            <div class="text">
                                <h4>Free Internet</h4>
                                <p class="pt-2">Stay connected even in remote locations with our satellite internet support.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="tour-info2">
                            <div class="icon pe-4">
                                <i class="flaticon-wifi-router"></i>
                            </div>
                            <div class="text">
                                <h4>24/7 Support</h4>
                                <p class="pt-2">Our team is always available to assist you throughout your journey.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!--====== Start of Feature ======-->

    <!--End Of Feature Tour-->

    <section class="cta-bg overlay bg_cover pt-150 pb-150" style="background-image: url(assets/images/bg-01.jpg);">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-xl-7 col-lg-8">
                    <div class="play-box ">
                        <a href="https://www.youtube.com/watch?v=ibuUmMhD2Pg" class="video-popup"><i
                                class="fas fa-play"></i></a>
                    </div>
                    <div class="section-title pt-3">
                        <span class="sub-title2">About Company</span>
                    </div>
                    <div class="cta-content-box text-white pt-3">
                        <h2 class="mb-35 text-white">Ready to Travel With real adventure and enjoy natural</h2>
                        <a href="tour.php" class="btn btn-green">Check Availability</a>
                    </div>
                </div>
                <div class="col-xl-5 col-lg-4">
                    <!--=== Play Box ===-->
                    <div class="row mt-4 mt-lg-0">
                        <div class="col-lg-6 col-md-6 pb-3">
                            <div class="tour-info3 text-center">
                                <div class="icon">
                                    <i class="flaticon-camp"></i>
                                </div>
                                <div class="text  pt-3">
                                    <h4>Wildlife <br>Tours</h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 pb-3">
                            <div class="tour-info3 text-center">
                                <div class="icon">
                                    <i class="flaticon-mountain"></i>
                                </div>
                                <div class="text pt-3">
                                    <h4>Mountain <br>Adventure</h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 pb-3">
                            <div class="tour-info3 text-center">
                                <div class="icon">
                                    <i class="flaticon-beach"></i>
                                </div>
                                <div class="text pt-3">
                                    <h4>Beach <br>Holidays</h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 pb-3">
                            <div class="tour-info3 text-center">
                                <div class="icon">
                                    <i class="flaticon-camp-1"></i>
                                </div>
                                <div class="text pt-3">
                                    <h4>Camping <br>Nights</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <section class=" pt-100">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-6 col-lg-9">
                    <!--=== About Content Box ===-->
                    <div class="about-content-box text-center mb-55 wow fadeInDown">
                        <div class="section-title mb-30">
                            <span class="sub-title">Destination lists</span>
                            <h2>Go Exotic Places</h2>
                        </div>
                    </div>
                </div>
                <div class="row g-4">
                    <?php if ($dest_result && $dest_result->num_rows > 0): ?>
                        <?php while ($dest = $dest_result->fetch_assoc()): ?>
                        <div class="col-lg-4 col-md-6">
                            <div class="destination-card shadow">
                                <img src="assets/images/image-08.jpg" alt="<?php echo htmlspecialchars($dest['destination']); ?>" onerror="this.onerror=null; this.src='assets/images/tour.jpg'">
                                <span class="tour-badge"><?php echo $dest['tour_count']; ?> TOURS</span>
                                <div class="destination-overlay">
                                    <p class="mb-0">Travel to</p>
                                    <h5 class="fw-bold"><a href="tour.php?search=<?php echo urlencode($dest['destination']); ?>" class="text-white"><?php echo htmlspecialchars($dest['destination']); ?></a></h5>
                                </div>
                            </div>
                        </div>
                        <?php endwhile; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <!--====== Start Fact Section ======-->
    <section class="fact-section pt-100">
        <div class="container">
            <div class="row">
                <div class="col-xl-12 col-lg-12">
                    <!--=== About Content Box ===-->
                    <div class="about-content-box text-center mb-55 wow fadeInDown">
                        <div class="section-title mb-30">
                            <h2>ACHIEVEMENTS</h2>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <!--=== Counter Item ===-->
                    <div class="single-counter-item text-center mb-40 wow fadeInUp">
                        <div class="icon">
                            <i class="flaticon-journey"></i>
                        </div>
                        <h2 class="number"><span class="count">3568</span>+</h2>
                        <p>Happy Traveler</p>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <!--=== Counter Item ===-->
                    <div class="single-counter-item text-center mb-40 wow fadeInDown">
                        <div class="icon">
                            <i class="flaticon-tent-1"></i>
                        </div>
                        <h2 class="number"><span class="count">8453</span>+</h2>
                        <p>Tent Sites</p>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <!--=== Counter Item ===-->
                    <div class="single-counter-item text-center mb-40 wow fadeInUp">
                        <div class="icon">
                            <i class="flaticon-reviews"></i>
                        </div>
                        <h2 class="number"><span class="count">99.3</span>%</h2>
                        <p>Positive Reviews</p>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <!--=== Counter Item ===-->
                    <div class="single-counter-item text-center mb-40 wow fadeInDown">
                        <div class="icon">
                            <i class="flaticon-award"></i>
                        </div>
                        <h2 class="number"><span class="count">63</span>K</h2>
                        <p>Awards Winning</p>
                    </div>
                </div>
            </div>
        </div>
    </section><!--====== End Fact Section ======-->


    <section class="cta-bg overlay2 bg_cover pt-150 pb-100"
        style="background-image: url(assets/images/bg-shape-10.png);">
        <div class="container">
            <div class="row align-items-xl-center">
                <div class="col-xl-6 col-lg-12 order-2 order-xl-1">
                    <!--=== Testimonial Image ===-->
                    <div class="section-title mb-50 wow fadeInDown">
                        <span class="sub-title">Testimonials</span>
                        <h2>What they’re talking about our policy</h2>
                        <p>Real stories from real travelers who experienced the magic of GoWilds.</p>
                        <div class="cta-content-box pt-3">
                            <a href="about.php" class="btn btn-green">All Testimonials</a>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6 col-lg-12 pt-5 order-1 order-xl-2">
                    <!--=== Testimonial Slider ===-->
                    <div id="testimonialCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="4000"
                        data-bs-pause="false">

                        <!-- indicators -->
                        <div class="carousel-indicators mb-4">
                            <?php for($i=0; $i<$testimonial_result->num_rows; $i++): ?>
                            <button type="button" data-bs-target="#testimonialCarousel" data-bs-slide-to="<?php echo $i; ?>"
                                class="<?php echo $i == 0 ? 'active' : ''; ?>" aria-current="<?php echo $i == 0 ? 'true' : 'false'; ?>" aria-label="Slide <?php echo $i+1; ?>"></button>
                            <?php endfor; ?>
                        </div>

                        <div class="carousel-inner">
                            <?php if ($testimonial_result && $testimonial_result->num_rows > 0): ?>
                                <?php $count = 0; while ($review = $testimonial_result->fetch_assoc()): ?>
                                <!-- item -->
                                <div class="carousel-item <?php echo $count == 0 ? 'active' : ''; ?>">
                                    <div class="testimonial-card">
                                        <div class="testimonial-stars">
                                            <?php for($s=1; $s<=5; $s++) echo ($s <= $review['rating'] ? '★' : '☆'); ?>
                                        </div>
                                        <p class="mb-0 fs-6">
                                            "<?php echo htmlspecialchars($review['comment']); ?>"
                                        </p>
                                        <div class="testimonial-one__arrow">
                                            <span class="first"></span>
                                            <span class="second"></span>
                                        </div>
                                    </div>
                                    <div class="testimonial-author">
                                        <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($review['user_name']); ?>" alt="<?php echo htmlspecialchars($review['user_name']); ?>">
                                        <div>
                                            <h6><?php echo htmlspecialchars($review['user_name']); ?></h6>
                                            <small><?php echo htmlspecialchars($review['tour_name']); ?></small>
                                        </div>
                                    </div>
                                </div>
                                <?php $count++; endwhile; ?>
                            <?php endif; ?>
                        </div>

                        <!-- controls -->
                        <button class="carousel-control-prev" type="button" data-bs-target="#testimonialCarousel"
                            data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#testimonialCarousel"
                            data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!--====== Start Gallery Section ======-->
    <section class="gallery-section-two pb-100">
        <div class="container">
            <div class="row align-items-xl-center">
                <div class="col-xl-9 col-lg-12">
                    <div class="section-title d-flex mb-50 wow fadeInDown">
                        <div class="Head w-75">
                            <span class="sub-title">Recent news feed</span>
                            <h2>Amazing News & Blog For Every Single Update</h2>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-12 text-lg-end">
                    <div class="cta-content-box pt-3">
                        <a href="blog.php" class="btn btn-green">View All Blogs</a>
                    </div>
                </div>
                <div class="col-xl-12 col-lg-12 pt-5">

                    <div class="slider-active-3-item-dot">
                        <!--=== Gallery Item ===-->
                        <div class="single-gallery-item-two">
                            <div class="img-holder">
                                <img src="assets/images/blog-01-500x360.jpg" alt="Gallery Image">
                                <span class="date">19 Dec</span>
                                <span class="category">Adventure</span>
                            </div>
                            <div class="content">
                                <h3 class="title">Top 10 destinations & adventure travel trips</h3>
                                <p>There are many variations of but the majority have simply free text.</p>
                                <a href="blog-details.php">Read More </a>
                            </div>
                        </div>
                        <!--=== Gallery Item ===-->
                        <div class="single-gallery-item-two">
                            <div class="img-holder">
                                <img src="assets/images/blog-02-500x360.jpg" alt="Gallery Image">
                                <span class="date">22 Dec</span>
                                <span class="category">Camping</span>
                            </div>
                            <div class="content">
                                <h3 class="title">How to prepare for your first wild camping</h3>
                                <p>Essential tips and tricks for a safe and memorable night in the wild.</p>
                                <a href="blog-details.php">Read More </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--====== End Gallery Section ======-->


<?php include 'includes/footer.php'; ?>