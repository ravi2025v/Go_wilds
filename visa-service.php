<?php
$title = 'Visa Service - MyEasyTrip';
include 'includes/header.php';
include 'admin/includes/db.php';

$countries_res = $conn->query("SELECT * FROM visa_countries WHERE status='active' ORDER BY country_name ASC");
?>

<!--====== Custom Professional Visa CSS ======-->
<link rel="stylesheet" href="assets/css/visa-modern.css">

<div class="visa-modern-page">
    
    <!--====== Start Breadcrumb Section ======-->
    <section class="page-banner overlay pt-170 pb-170 bg_cover" style="background-image: url(assets/images/abt-bg.jpg);">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="page-banner-content text-center text-white">
                        <h1 class="page-title text-white">Visa service</h1>
                        <ul class="breadcrumb-link text-white">
                            <li><a href="index.php">Home</a></li>
                            <li class="active">Visa service</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--====== End Breadcrumb Section ======-->

    <section class="visa-modern-container">
        <div class="container">
            
            <!-- Professional Stepper -->
            <div class="visa-stepper-wrapper">
                <div class="visa-step-item active" id="step-btn-1" onclick="displayStep(1)">
                    <div class="step-number">1</div>
                    <div class="step-label">Travel Info</div>
                </div>
                <div class="visa-step-item" id="step-btn-2" onclick="displayStep(2)">
                    <div class="step-number">2</div>
                    <div class="step-label">Profile</div>
                </div>
                <div class="visa-step-item" id="step-btn-3" onclick="displayStep(3)">
                    <div class="step-number">3</div>
                    <div class="step-label">Passport</div>
                </div>
            </div>

            <!-- Form Card -->
            <div class="visa-form-card">
                <form id="multi-step-form">
                    
                    <!-- STEP 1: Travel Details -->
                    <div class="step step-1">
                        <h2 class="step-title">Travel Details</h2>
                        <div class="row pt-2">
                            <div class="col-md-6 mb-4">
                                <label class="modern-label">Nationality</label>
                                <div class="bk-item">
                                    <select class="wide" name="nationality" id="nationality-select">                                 
                                        <option value="">Select Country</option>
                                        <option value="India">India</option>
                                        <option value="USA">USA</option>
                                        <option value="UK">UK</option>
                                        <option value="Australia">Australia</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 mb-4">
                                <label class="modern-label">Travelling To</label>
                                <div class="bk-item">
                                    <select class="wide" name="travelling_to_id" id="travelling-to-select" required onchange="fetchVisaTypes(this.value)">
                                        <option value="">Select Destination</option>
                                        <?php while($c = $countries_res->fetch_assoc()): ?>
                                            <option value="<?php echo $c['id']; ?>"><?php echo $c['country_name']; ?></option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 mb-4" id="visa-type-container" style="display:none;">
                                <label class="modern-label">Visa Type</label>
                                <div class="bk-item">
                                    <select class="wide" name="visa_service_id" id="visa-service-select" required>
                                        <option value="">Select Visa Type</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 mb-4">
                                <label class="modern-label">Trip Start Date</label>
                                <input type="date" class="modern-input-field" name="start_date" id="start-date" required>
                            </div>
                            <div class="col-md-6 mb-4">
                                <label class="modern-label">Departure From</label>
                                <div class="bk-item">
                                    <select class="wide" name="departure_from" id="departure-select" required>
                                        <option value="">Select Departure Country</option>
                                        <option value="India">India</option>
                                        <option value="USA">USA</option>
                                        <option value="Singapore">Singapore</option>
                                        <option value="UAE">UAE</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="visa-btn-group">
                            <button type="button" class="visa-primary-btn next-step">
                                Continue to Profile <i class="fas fa-arrow-right"></i>
                            </button>
                        </div>
                    </div>

                    <!-- STEP 2: Profile Details -->
                    <div class="step step-2" style="display:none;">
                        <h2 class="step-title">Profile Details</h2>
                        <div class="row align-items-center">
                            <div class="col-lg-5 mb-4 mb-lg-0">
                                <div class="modern-upload-zone" id="profile-upload-zone">
                                    <i class="fas fa-user-circle"></i>
                                    <h6>Profile Image</h6>
                                    <p class="text-muted small">Click to upload or capture</p>
                                    <button type="button" class="btn select-image open-modal mt-3" style="background: #63ab45; color: #fff; border-radius: 10px; padding: 10px 20px; font-weight: 600;">Select Image</button>
                                </div>
                            </div>
                            <div class="col-lg-7 ps-lg-5">
                                <div class="summary-box p-4 rounded-4" style="background: #f8fafc; border: 1px solid #eef2f6;">
                                    <h5 class="mb-4" style="font-weight: 700; color: #1e293b;">Trip Summary</h5>
                                    <div class="summary-item">
                                        <span class="summary-label">Nationality:</span>
                                        <span class="summary-value" id="summary-nat">N/A</span>
                                    </div>
                                    <div class="summary-item">
                                        <span class="summary-label">Travelling To:</span>
                                        <span class="summary-value" id="summary-to">N/A</span>
                                    </div>
                                    <div class="summary-item">
                                        <span class="summary-label">Visa Type:</span>
                                        <span class="summary-value text-primary fw-bold" id="summary-visa">N/A</span>
                                    </div>
                                    <div class="summary-item">
                                        <span class="summary-label">Start Date:</span>
                                        <span class="summary-value" id="summary-date">N/A</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="visa-btn-group">
                            <button type="button" class="visa-secondary-btn prev-step">Back</button>
                            <button type="button" class="visa-primary-btn next-step">
                                Document Details <i class="fas fa-arrow-right"></i>
                            </button>
                        </div>
                    </div>

                    <!-- STEP 3: Passport Details -->
                    <div class="step step-3" style="display:none;">
                        <h2 class="step-title">Passport Details</h2>
                        <div class="row">
                            <div class="col-12 mb-4">
                                <div class="modern-upload-zone" style="padding: 30px;">
                                    <i class="fas fa-id-card"></i>
                                    <h6>Upload Passport Photo</h6>
                                    <p class="text-muted small">Ensure all details are clearly visible</p>
                                    <button type="button" class="btn select-image direct-upload mt-2" style="background: #63ab45; color: #fff; border-radius: 10px; padding: 10px 20px;">Select Passport File</button>
                                </div>
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="modern-form-group">
                                    <label class="modern-label">First Name</label>
                                    <input type="text" class="modern-input-field" name="first_name" placeholder="As per passport" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="modern-form-group">
                                    <label class="modern-label">Last Name</label>
                                    <input type="text" class="modern-input-field" name="last_name" placeholder="Surname" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="modern-label">Gender</label>
                                <div class="bk-item">
                                    <select class="wide" name="gender" required>
                                        <option value="">Select Gender</option>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="modern-form-group">
                                    <label class="modern-label">Passport Number</label>
                                    <input type="text" class="modern-input-field" name="passport_no" placeholder="Ex: A1234567" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="modern-form-group">
                                    <label class="modern-label">Date of Birth</label>
                                    <input type="date" class="modern-input-field" name="dob" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="modern-form-group">
                                    <label class="modern-label">Place of Issue</label>
                                    <input type="text" class="modern-input-field" name="place_issue" placeholder="City" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="modern-form-group">
                                    <label class="modern-label">Passport Issue Date</label>
                                    <input type="date" class="modern-input-field" name="issue_date" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="modern-form-group">
                                    <label class="modern-label">Passport Expiry Date</label>
                                    <input type="date" class="modern-input-field" name="expiry_date" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="modern-form-group">
                                    <label class="modern-label">Email Address</label>
                                    <input type="email" class="modern-input-field" name="email" placeholder="For notifications" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="modern-form-group">
                                    <label class="modern-label">Phone Number</label>
                                    <input type="text" class="modern-input-field" name="phone" placeholder="Mobile Number" required>
                                </div>
                            </div>
                        </div>

                        <div class="visa-btn-group">
                            <button type="button" class="visa-secondary-btn prev-step">Back</button>
                            <button type="submit" class="visa-primary-btn">
                                Submit Application <i class="fas fa-check-circle"></i>
                            </button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </section>

    <!-- Hidden File Input -->
    <input type="file" id="fileInput" accept="image/*" hidden>

    <!-- Upload Modal -->
    <div class="modal fade" id="uploadModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 24px; border: none; box-shadow: 0 25px 50px rgba(0,0,0,0.1);">
                <div class="modal-header border-0 pt-4 px-4">
                    <h5 class="modal-title fw-bold">Upload Options</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4 text-center" style="max-height: 80vh; overflow-y: auto;">
                    <div class="d-grid gap-3" id="initialOptions">
                        <button id="btnFile" class="btn p-3" style="border: 2px solid #eef2f6; border-radius: 16px; font-weight: 700; color: #1e293b;">
                            <i class="fa fa-upload me-2 text-primary"></i> Upload from Gallery
                        </button>
                        <button id="btnCamera" class="btn p-3" style="border: 2px solid #eef2f6; border-radius: 16px; font-weight: 700; color: #1e293b;">
                            <i class="fa fa-camera me-2 text-success"></i> Capture with Camera
                        </button>
                    </div>

                    <div id="cameraContainer" class="mt-2" style="display:none;">
                        <div style="border-radius: 16px; overflow: hidden; position: relative; background: #000; line-height: 0;">
                            <video id="video" style="width: 100%; max-height: 300px; object-fit: cover;" autoplay></video>
                        </div>
                        <div class="d-flex gap-2 mt-3">
                            <button id="capture" class="visa-primary-btn flex-grow-1">
                                <i class="fas fa-camera me-2"></i> Capture Photo
                            </button>
                            <button type="button" class="btn btn-secondary rounded-pill px-4" onclick="stopCamera()">Cancel</button>
                        </div>
                        <canvas id="canvas" width="640" height="480" style="display:none;"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    let currentStep = 1;

    function displayStep(step) {
        if (step < 1 || step > 3) return;
        
        // Hide all steps
        document.querySelectorAll('.step').forEach(s => s.style.display = 'none');
        // Show target step
        document.querySelector('.step-' + step).style.display = 'block';
        
        // Update Stepper UI
        document.querySelectorAll('.visa-step-item').forEach((item, idx) => {
            item.classList.remove('active', 'completed');
            if (idx + 1 < step) item.classList.add('completed');
            if (idx + 1 === step) item.classList.add('active');
        });
        
        currentStep = step;
        window.scrollTo({ top: document.querySelector('.visa-modern-container').offsetTop - 100, behavior: 'smooth' });
    }

    function fetchVisaTypes(countryId) {
        if (!countryId) {
            $('#visa-type-container').fadeOut();
            return;
        }

        $.ajax({
            url: 'get_visa_types.php',
            type: 'GET',
            data: { country_id: countryId },
            success: function(data) {
                let options = '<option value="">Select Visa Type</option>';
                data.forEach(service => {
                    options += `<option value="${service.id}" data-name="${service.visa_name}" data-price="${service.price}">${service.visa_name} - ₹${service.price}</option>`;
                });
                $('#visa-service-select').html(options);
                $('#visa-type-container').fadeIn();
                
                // Re-initialize NiceSelect if used (standard in many themes)
                if ($.fn.niceSelect) {
                    $('#visa-service-select').niceSelect('update');
                }
            }
        });
    }

    $(document).ready(function() {
        // Next Step Logic
        $('.next-step').on('click', function() {
            let stepDiv = $('.step-' + currentStep);
            let inputs = stepDiv.find('input[required], select[required]');
            let valid = true;
            
            inputs.each(function() {
                if (!$(this).val() || $(this).val() === "") {
                    $(this).closest('.bk-item').addClass('is-invalid');
                    $(this).addClass('is-invalid');
                    valid = false;
                } else {
                    $(this).closest('.bk-item').removeClass('is-invalid');
                    $(this).removeClass('is-invalid');
                }
            });

            if (valid) {
                if (currentStep === 1) {
                    $('#summary-nat').text($('#nationality-select').val() || 'N/A');
                    $('#summary-to').text($('#travelling-to-select option:selected').text() || 'N/A');
                    $('#summary-visa').text($('#visa-service-select option:selected').text() || 'N/A');
                    $('#summary-date').text($('#start-date').val() || 'N/A');
                }
                displayStep(currentStep + 1);
            }
        });

        // Form Submission
        $('#multi-step-form').on('submit', function(e) {
            e.preventDefault();
            let formData = $(this).serialize();
            
            $.ajax({
                url: 'submit_visa_application.php',
                type: 'POST',
                data: formData,
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        alert(response.message);
                        window.location.href = 'my-bookings.php';
                    } else {
                        alert('Error: ' + response.message);
                    }
                }
            });
        });

        $('.prev-step').on('click', function() {
            displayStep(currentStep - 1);
        });

        // Modal Logic
        $('.open-modal').on('click', function() {
            $('#uploadModal').modal('show');
        });

        $('#btnFile').on('click', function() {
            $('#fileInput').click();
            $('#uploadModal').modal('hide');
        });

        // Camera Logic
        const video = document.getElementById('video');
        const canvas = document.getElementById('canvas');
        const captureButton = document.getElementById('capture');
        const cameraContainer = document.getElementById('cameraContainer');

        $('#btnCamera').on('click', async function() {
            $('#initialOptions').hide();
            $('#cameraContainer').show();
            try {
                const stream = await navigator.mediaDevices.getUserMedia({ video: true });
                video.srcObject = stream;
            } catch (err) {
                alert("Camera access denied or not available.");
                stopCamera();
            }
        });

        window.stopCamera = function() {
            if (video.srcObject) {
                let stream = video.srcObject;
                let tracks = stream.getTracks();
                tracks.forEach(track => track.stop());
                video.srcObject = null;
            }
            $('#cameraContainer').hide();
            $('#initialOptions').show();
        };

        captureButton.addEventListener('click', () => {
            const context = canvas.getContext('2d');
            context.drawImage(video, 0, 0, canvas.width, canvas.height);
            const imageData = canvas.toDataURL('image/png');
            alert("Photo captured successfully!");
            $('#uploadModal').modal('hide');
            stopCamera();
        });

        // Direct upload button (passport)
        $('.direct-upload').on('click', function() {
            $('#fileInput').click();
        });
    });
</script>

<?php include 'includes/footer.php'; ?>
