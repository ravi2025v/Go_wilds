<?php
/**
 * GoWilds - Professional Adventure & Travel Website
 * Specialized for Hero and CTA modern sections.
 */
include 'includes/header.php';
require_once 'admin/includes/db.php';

// Fetch Featured Tours
$featured_tours_query = "SELECT * FROM tours WHERE status = 'active' LIMIT 8";
$featured_tours_result = $conn->query($featured_tours_query);

// Fetch Destinations with tour count
$dest_query = "SELECT destination, COUNT(*) as tour_count FROM tours WHERE status = 'active' GROUP BY destination LIMIT 6";
$dest_result = $conn->query($dest_query);

// Fetch Testimonials (Latest approved reviews)
$testimonial_query = "SELECT r.*, t.title as tour_name, u.name as user_name FROM reviews r JOIN tours t ON r.tour_id = t.id JOIN users u ON r.user_id = u.id WHERE r.status = 'approved' ORDER BY r.created_at DESC LIMIT 5";
$testimonial_result = $conn->query($testimonial_query);
?>

<!--====== Custom Professional Hero CSS ======-->
<link rel="stylesheet" href="assets/css/hero-professional.css">
<link rel="stylesheet" href="assets/css/about-modern.css">
<link rel="stylesheet" href="assets/css/testimonial-v2.css">

<!--====== Start Hero Section ======-->
<section class="professional-hero">
    <div class="hero-slider-modern hero-slider-three">
        <!--=== Single Slider ===-->
        <div class="single-slider-modern bg_cover"
            style="background-image: url(https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?auto=format&fit=crop&w=1920&q=80);">
        </div>
        <!--=== Single Slider ===-->
        <div class="single-slider-modern bg_cover"
            style="background-image: url(https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=1920&q=80);">
        </div>
        <!--=== Single Slider ===-->
        <div class="single-slider-modern bg_cover"
            style="background-image: url(https://images.unsplash.com/photo-1473580044384-7ba9967e16a0?auto=format&fit=crop&w=1920&q=80);">
        </div>
    </div>

    <!-- Restoring Arrows for JS compatibility -->
    <div class="hero-arrows"></div>

    <div class="hero-content-overlay">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-10">
                    <div class="hero-text-box text-center">
                        <span class="modern-badge wow fadeInDown">Discover Your Next Adventure</span>
                        <h1 class="modern-title wow fadeInUp" data-wow-delay=".2s">Experience the World <br>Beyond <span
                                class="accent-text">Boundaries</span></h1>
                        <p class="modern-desc wow fadeInUp" data-wow-delay=".4s">Curated travel experiences for the
                            modern explorer. From wild safaris to serene beaches, find your perfect escape.</p>

                        <!-- Modern Search Bar -->
                        <div class="modern-search-wrapper wow fadeInUp" data-wow-delay=".6s">
                            <form action="search-results.php" method="GET" class="modern-search-form">
                                <div class="search-item">
                                    <label><i class="fas fa-map-marker-alt"></i> Destination</label>
                                    <input type="text" name="search" placeholder="Where are you going?"
                                        autocomplete="off">
                                </div>
                                <div class="search-item">
                                    <label><i class="fas fa-th-large"></i> Category</label>
                                    <select name="category">
                                        <option value="">All Types</option>
                                        <option value="Wild Safari">Wild Safari</option>
                                        <option value="Trek">Trekking</option>
                                        <option value="Domestic Destinations">Domestic</option>
                                        <option value="International Destinations">International</option>
                                    </select>
                                </div>
                                <div class="search-item-btn">
                                    <button type="submit" class="search-btn-modern">
                                        <i class="fas fa-search"></i> Search
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Stats/Features -->
                        <div class="hero-stats wow fadeInUp" data-wow-delay=".8s">
                            <div class="stat-item">
                                <span class="stat-num">150+</span>
                                <span class="stat-label">Destinations</span>
                            </div>
                            <div class="stat-item border-left-stat">
                                <span class="stat-num">24/7</span>
                                <span class="stat-label">Expert Support</span>
                            </div>
                            <div class="stat-item border-left-stat">
                                <span class="stat-num">100%</span>
                                <span class="stat-label">Secure Booking</span>
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
                            <a href="tour.php?category=Wild%20Safari" class="icon-btn"><i
                                    class="far fa-arrow-right"></i></a>
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

<!--====== Start Modern Who We Are Section ======-->
<section class="about-modern-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-xl-6 col-lg-12">
                <div class="about-content wow fadeInLeft">
                    <span class="sub-title-modern">Who We Are</span>
                    <h2 class="about-title">Experience the World With Real Adventure</h2>
                    <p class="about-desc">At MyEasyTrip, we believe that travel is more than just visiting places; it's
                        about the stories you tell and the memories you create. Since 2014, we've been crafting unique
                        journeys that blend authentic adventure with unmatched comfort.</p>

                    <div class="feature-list-modern">
                        <div class="feature-item-modern">
                            <div class="feature-icon-modern">
                                <i class="fas fa-shield-alt"></i>
                            </div>
                            <div class="feature-info-modern">
                                <h4>Safety First</h4>
                                <p>Certified equipment and expert guides.</p>
                            </div>
                        </div>
                        <div class="feature-item-modern">
                            <div class="feature-icon-modern">
                                <i class="fas fa-tags"></i>
                            </div>
                            <div class="feature-info-modern">
                                <h4>Best Prices</h4>
                                <p>Premium experience at competitive rates.</p>
                            </div>
                        </div>
                        <div class="feature-item-modern">
                            <div class="feature-icon-modern">
                                <i class="fas fa-globe-americas"></i>
                            </div>
                            <div class="feature-info-modern">
                                <h4>Diverse Tours</h4>
                                <p>From local treks to global expeditions.</p>
                            </div>
                        </div>
                        <div class="feature-item-modern">
                            <div class="feature-icon-modern">
                                <i class="fas fa-headset"></i>
                            </div>
                            <div class="feature-info-modern">
                                <h4>24/7 Support</h4>
                                <p>Always there when you need us.</p>
                            </div>
                        </div>
                    </div>

                    <div class="about-button">
                        <a href="about.php" class="main-btn primary-btn">Learn More About Us <i
                                class="far fa-arrow-right"></i></a>
                    </div>
                </div>
            </div>
            <div class="col-xl-6 col-lg-12">
                <div class="about-image-wrapper wow fadeInRight text-end">
                    <img src="https://images.unsplash.com/photo-1503220317375-aaad61436b1b?auto=format&fit=crop&w=1200&q=80"
                        alt="Exploring Nature" class="main-image">
                    <img src="https://images.unsplash.com/photo-1478131143081-80f7f84ca84d?auto=format&fit=crop&w=600&q=80"
                        alt="Luxury Camping" class="secondary-image">
                    <div class="experience-badge">
                        <span class="number">10+</span>
                        <span class="text">Years of<br>Excellence</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!--====== End Modern Who We Are Section ======-->


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
                                <a href="#tab1" class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab1">Tent
                                    Camping</a>
                            </li>
                            <li>
                                <a href="#tab2" class="nav-link" data-bs-toggle="tab" data-bs-target="#tab2">Mountain
                                    Biking</a>
                            </li>
                            <li>
                                <a href="#tab3" class="nav-link" data-bs-toggle="tab"
                                    data-bs-target="#tab3">Birdwatching</a>
                            </li>
                            <li>
                                <a href="#tab4" class="nav-link" data-bs-toggle="tab" data-bs-target="#tab4">Fishing</a>
                            </li>
                            <li>
                                <a href="#tab5" class="nav-link" data-bs-toggle="tab" data-bs-target="#tab5">Wild
                                    Safari</a>
                            </li>
                            <li>
                                <a href="#tab6" class="nav-link" data-bs-toggle="tab" data-bs-target="#tab6">Mountain
                                    Hiking</a>
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
                                        <p class="text-white">Experience the night under millions of stars with our
                                            professional camping setups.</p>
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
                                <img src="<?php echo htmlspecialchars($tour['image']); ?>" class="card-img-top"
                                    alt="<?php echo htmlspecialchars($tour['title']); ?>"
                                    onerror="this.onerror=null; this.src='assets/images/tour.jpg'">
                                <div class="tour-info">
                                    <div class="d-flex align-items-center mb-2">
                                        <span class="text-warning me-2">★★★★★</span>
                                        <span>(5)</span>
                                    </div>
                                    <h5 class="fw-bold mb-1"><a
                                            href="tour-details.php?id=<?php echo $tour['id']; ?>"><?php echo htmlspecialchars($tour['title']); ?></a>
                                    </h5>
                                    <p class="mb-1"><i class="bi bi-geo-alt-fill"></i>
                                        <?php echo htmlspecialchars($tour['destination']); ?></p>
                                    <p class="mb-0">From <span
                                            class="text-success fw-bold">₹<?php echo number_format($tour['price'], 2); ?></span>
                                    </p>
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
                            <p class="pt-2">We prioritize your safety with world-class security protocols and expert
                                guides.</p>
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
                            <p class="pt-2">Stay connected even in remote locations with our satellite internet support.
                            </p>
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

<!--====== Modern Sections CSS ======-->
<link rel="stylesheet" href="assets/css/modern-sections.css">

<!--====== Start Modern CTA Section ======-->
<section class="modern-cta-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-xl-5 col-lg-6">
                <div class="cta-video-box wow fadeInLeft">
                    <img src="https://images.unsplash.com/photo-1504280390367-361c6d9f38f4?auto=format&fit=crop&w=800&q=80"
                        alt="Video Background">
                    <a href="https://www.youtube.com/watch?v=ibuUmMhD2Pg" class="modern-play-btn video-popup">
                        <i class="fas fa-play"></i>
                    </a>
                </div>
            </div>
            <div class="col-xl-7 col-lg-6">
                <div class="cta-content-inner wow fadeInRight">
                    <h2 class="cta-title">Ready to Travel With real adventure and enjoy natural</h2>
                    <a href="tour.php" class="search-btn-modern d-inline-flex mb-40">Check Availability</a>

                    <!-- Specialized Modern Grid -->
                    <div class="modern-category-grid">
                        <!-- wildlife -->
                        <a href="tour.php?category=Wild%20Safari" class="modern-cat-card">
                            <img src="https://images.unsplash.com/photo-1549366021-9f761d450615?auto=format&fit=crop&w=400&q=80"
                                alt="Wildlife">
                            <div class="modern-cat-overlay">
                                <div class="modern-cat-icon"><i class="flaticon-camp"></i></div>
                                <h4 class="modern-cat-title">Wildlife Tours</h4>
                            </div>
                        </a>
                        <!-- mountain -->
                        <a href="tour.php?category=Trek" class="modern-cat-card">
                            <img src="https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?auto=format&fit=crop&w=400&q=80"
                                alt="Mountain">
                            <div class="modern-cat-overlay">
                                <div class="modern-cat-icon"><i class="flaticon-mountain"></i></div>
                                <h4 class="modern-cat-title">Mountain Adventure</h4>
                            </div>
                        </a>
                        <!-- beach -->
                        <a href="tour.php" class="modern-cat-card">
                            <img src="https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=400&q=80"
                                alt="Beach">
                            <div class="modern-cat-overlay">
                                <div class="modern-cat-icon"><i class="flaticon-beach"></i></div>
                                <h4 class="modern-cat-title">Beach Holidays</h4>
                            </div>
                        </a>
                        <!-- camping -->
                        <a href="tour.php" class="modern-cat-card">
                            <img src="https://images.unsplash.com/photo-1523987355523-c7b5b0dd90a7?auto=format&fit=crop&w=400&q=80"
                                alt="Camping">
                            <div class="modern-cat-overlay">
                                <div class="modern-cat-icon"><i class="flaticon-camp-1"></i></div>
                                <h4 class="modern-cat-title">Camping Nights</h4>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!--====== End Modern CTA Section ======-->


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
                                <img src="assets/images/image-08.jpg"
                                    alt="<?php echo htmlspecialchars($dest['destination']); ?>"
                                    onerror="this.onerror=null; this.src='assets/images/tour.jpg'">
                                <span class="tour-badge"><?php echo $dest['tour_count']; ?> TOURS</span>
                                <div class="destination-overlay">
                                    <p class="mb-0">Travel to</p>
                                    <h5 class="fw-bold"><a href="tour.php?search=<?php echo urlencode($dest['destination']); ?>"
                                            class="text-white"><?php echo htmlspecialchars($dest['destination']); ?></a></h5>
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


<!--====== Start Testimonial Section (V2 Design - 3 Items) ======-->
<section class="testimonial-v2-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-8">
                <!--=== Testimonial Header (Centered) ===-->
                <div class="testimonial-v2-header text-center wow fadeInDown">
                    <span class="badge-v2">Testimonials</span>
                    <h2>Inspiring Journeys Shared by Our Explorers</h2>
                    <p>Explore heartfelt stories and unforgettable memories from travelers who discovered the world with MyEasyTrip.</p>
                </div>
            </div>
        </div>

        <!--=== Testimonial Slider (3 Items At Once) ===-->
        <div class="slider-active-3-item wow fadeInUp">
            <?php if ($testimonial_result && $testimonial_result->num_rows > 0): ?>
                <?php while ($review = $testimonial_result->fetch_assoc()): ?>
                    <div class="px-3"> <!-- Added padding for spacing between cards -->
                        <div class="card-v2 h-100">
                            <div class="quote-mark">“</div>
                            <div class="rating-v2">
                                <?php for ($s = 1; $s <= 5; $s++)
                                    echo ($s <= $review['rating'] ? '★' : '☆'); ?>
                            </div>
                            <p class="text-v2">
                                "<?php echo htmlspecialchars($review['comment']); ?>"
                            </p>
                            <div class="author-v2">
                                <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($review['user_name'] ?? 'Guest'); ?>&background=63ab45&color=fff"
                                    alt="<?php echo htmlspecialchars($review['user_name'] ?? 'Guest'); ?>"
                                    onerror="this.onerror=null; this.src='assets/images/user.jpg'">
                                <div>
                                    <h6><?php echo htmlspecialchars($review['user_name'] ?? 'Guest'); ?></h6>
                                    <span><?php echo htmlspecialchars($review['tour_name']); ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php endif; ?>
        </div>

        <div class="text-center mt-60">
            <a href="about.php" class="main-btn primary-btn">View All Testimonials <i
                    class="far fa-arrow-right"></i></a>
        </div>
    </div>
</section>
<!--====== End Testimonial Section ======-->
<!--====== End Testimonial Section ======-->

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