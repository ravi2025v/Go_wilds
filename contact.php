<?php
$title = 'Contact Us - MyEasyTrip';
include 'includes/header.php';
?>


<!--====== Start Breadcrumb Section ======-->
<section class="page-banner overlay pt-170 pb-170 bg_cover" style="background-image: url(assets/images/abt-bg.jpg);">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="page-banner-content text-center text-white">
                    <h1 class="page-title text-white">Contact Us</h1>
                    <ul class="breadcrumb-link text-white">
                        <li><a href="index.php">Home</a></li>
                        <li class="active">Contact Us</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section><!--====== End Breadcrumb Section ======-->
<!--====== Start Info Section ======-->
<section class="contact-info-section pt-100 pb-60">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-8">
                <!--=== Section Title ===-->
                <div class="section-title text-center mb-45 wow fadeInDown">
                    <span class="sub-title">Contact Us</span>
                    <h2>Ready to Get Our Best Services!
                        Feel Free to Contact With Us</h2>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-4 col-md-6 col-sm-12">
                <!--=== Contact Info Item ===-->
                <div class="contact-info-item text-center mb-40 wow fadeInUp">
                    <div class="icon">
                        <img src="assets/images/icon-1.webp" alt="icon">
                    </div>
                    <div class="info">
                        <span class="title">Office Location</span>
                        <p>86-26-15/20, Tilak Road, Rajahmundry - 533103, Andhra Pradesh.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-12">
                <!--=== Contact Info Item ===-->
                <div class="contact-info-item text-center mb-40 wow fadeInDown">
                    <div class="icon">
                        <img src="assets/images/icon-2.png" alt="icon">
                    </div>
                    <div class="info">
                        <span class="title">Email Address</span>
                        <p><a href="mailto:support@myeasytrip.in">support@myeasytrip.in</a></p>

                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-12">
                <!--=== Contact Info Item ===-->
                <div class="contact-info-item text-center mb-40 wow fadeInUp">
                    <div class="icon">
                        <img src="assets/images/icon-3.webp" alt="icon">
                    </div>
                    <div class="info">
                        <span class="title">Hotline</span>
                        <p><a href="tel:+918233803333">+91-8233803333</a></p>

                    </div>
                </div>
            </div>
        </div>
    </div>
</section><!--====== End Info Section ======-->
<!--====== Start Contact Map Section ======-->
<section class="contact-page-map pb-100 wow fadeInUp">
    <!--=== Map Box ===-->
    <div class="map-box">
        <iframe
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3815.4257716602883!2d81.79353427577344!3d17.00277311376997!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3a37a39468ce2901%3A0x570c5015ea8c3c11!2s86%2C%20Tilak%20Rd%2C%20State%20Bank%20Colony%202%2C%20Gandhipuram%2C%20Rajamahendravaram%2C%20Andhra%20Pradesh%20533103%2C%20India!5e0!3m2!1sen!2sus!4v1759399752874!5m2!1sen!2sus"
            width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"
            referrerpolicy="no-referrer-when-downgrade"></iframe>
    </div>
</section><!--====== End Contact Map Section ======-->
<!--====== Start Contact Section ======-->
<section class="contact-section pb-100">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-6">
                <div class="section-title text-center mb-50 wow fadeInDown">
                    <span class="sub-title">Get In Touch</span>
                    <h2>Send Us Message</h2>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="contact-area wow fadeInUp">
                    <form class="contact-form">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form_group">
                                    <input type="text" placeholder="Name" class="form_control" name="name" required>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form_group">
                                    <input type="text" placeholder="Phone Number" class="form_control" name="number"
                                        required>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form_group">
                                    <input type="email" placeholder="Email Address" class="form_control" name="email"
                                        required>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form_group">
                                    <input type="url" placeholder="Website" class="form_control" name="website"
                                        required>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form_group">
                                    <textarea name="message" placeholder="Write Message" class="form_control"
                                        rows="6"></textarea>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form_group text-center">
                                    <button class="main-btn primary-btn">Send Us Message<i
                                            class="fas fa-paper-plane"></i></button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section><!--====== End Contact Section ======-->


<?php include 'includes/footer.php'; ?>