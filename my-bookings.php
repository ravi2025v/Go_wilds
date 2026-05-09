<?php
if (session_status() === PHP_SESSION_NONE) {
    session_name("GoWilds_Session");
    session_start();
}

// Redirect if not logged in - MUST be before header.php or any output
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$title = "My Bookings - Gowilds Travel";
include 'includes/header.php';
require_once 'admin/includes/db.php';
?>

$user_id = $_SESSION['user_id'];
$msg = '';
$msg_type = '';

// Handle Booking Update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_booking'])) {
    $booking_id = intval($_POST['booking_id']);
    $new_date = $conn->real_escape_string($_POST['booking_date']);
    $adults = intval($_POST['adults']);
    $children = intval($_POST['children']);
    $infants = intval($_POST['infants']);

    $hotel_info = $_POST['hotel_info']; // Format: "Type|Price"
    $parts = explode('|', $hotel_info);
    $hotel_type = $conn->real_escape_string($parts[0]);
    $hotel_price = floatval($parts[1]);

    // Security check: ensure this booking belongs to the user and is still pending
    $check = $conn->query("SELECT status FROM tour_bookings WHERE id = $booking_id AND user_id = $user_id");
    if ($check && $check->num_rows > 0) {
        $booking_data = $check->fetch_assoc();
        if ($booking_data['status'] === 'pending') {
            // Recalculate price: $hotel_price already includes base + upgrade
            $totalPeople = $adults + $children;

            // Re-calculate activities total from existing booking to preserve it
            $get_act = $conn->query("SELECT total_price, hotel_price, adults, children, infants FROM tour_bookings WHERE id = $booking_id");
            $old_data = $get_act->fetch_assoc();
            $old_total = $old_data['total_price'];
            $old_base = ($old_data['hotel_price'] * ($old_data['adults'] + $old_data['children'])) + 50 + (20 * ($old_data['adults'] + $old_data['children'] + $old_data['infants']));
            $activity_fee = max(0, $old_total - $old_base);

            // Include standard fees: ₹50 base + ₹20 per person (including infants)
            $new_total_price = ($hotel_price * $totalPeople) + 50 + (20 * ($totalPeople + $infants)) + $activity_fee;

            $update_sql = "UPDATE tour_bookings SET 
                           tour_date = '$new_date', 
                           adults = $adults, 
                           children = $children, 
                           infants = $infants, 
                           hotel_type = '$hotel_type',
                           hotel_price = $hotel_price,
                           total_price = $new_total_price 
                           WHERE id = $booking_id";

            if ($conn->query($update_sql)) {
                $msg = "Booking updated successfully!";
                $msg_type = "success";
            } else {
                $msg = "Error updating booking: " . $conn->error;
                $msg_type = "danger";
            }
        } else {
            $msg = "Only pending bookings can be edited.";
            $msg_type = "warning";
        }
    }
}

// Fetch user's bookings with tour hotel prices
$query = "SELECT tb.*, t.title as tour_title, t.image as tour_image, 
          t.price as p_budget, t.price_3star as p_3star, t.price_4star as p_4star, 
          t.price_5star as p_5star, t.price_camps as p_camps, t.price_homestay as p_homestay
          FROM tour_bookings tb 
          JOIN tours t ON tb.tour_id = t.id 
          WHERE tb.user_id = $user_id 
          ORDER BY tb.created_at DESC";
$bookings_res = $conn->query($query);
?>

<section class="page-title-area text-white bg_cover"
    style="background-image: url(assets/images/bg/page-bg-1.jpg); padding: 20px 0;">
    <div class="container text-center">
        <h2 class="mb-0" style="font-weight: 800; font-size: 42px;">My Bookings</h2>
        <ul class="breadcrumb-link text-white mt-2">
            <li><a href="index.php">Home</a></li>
            <li class="active">My Bookings</li>
        </ul>
    </div>
</section>

<section class="booking-list-section py-4 bg-light">
    <div class="container">
        <?php if ($msg): ?>
                <div class="alert alert-<?php echo $msg_type; ?> alert-dismissible fade show" role="alert">
                    <?php echo $msg; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
        <?php endif; ?>

        <div class="row">
            <?php if ($bookings_res && $bookings_res->num_rows > 0): ?>
                    <?php while ($booking = $bookings_res->fetch_assoc()): ?>
                            <div class="col-lg-12 mb-4">
                                <div class="card border-0 shadow-sm overflow-hidden" style="border-radius: 15px;">
                                    <div class="row g-0">
                                        <div class="col-md-3">
                                            <img src="<?php echo htmlspecialchars($booking['tour_image']); ?>" class="img-fluid h-100"
                                                style="object-fit: cover;" alt="Tour">
                                        </div>
                                        <div class="col-md-9">
                                            <div class="card-body p-4">
                                                <div class="d-flex justify-content-between align-items-start mb-3">
                                                    <div>
                                                        <h4 class="card-title text-primary mb-1">
                                                            <?php echo htmlspecialchars($booking['tour_title']); ?>
                                                        </h4>
                                                        <p class="text-muted small mb-0"><i
                                                                class="fas fa-calendar-check me-1 text-success"></i> <strong>Travel
                                                                Date:
                                                                <?php echo date('d M Y', strtotime($booking['tour_date'])); ?></strong>
                                                        </p>
                                                        <p class="text-muted small mb-0"><i class="fas fa-history me-1"></i> Booked On:
                                                            <?php echo date('d M Y', strtotime($booking['created_at'])); ?>
                                                        </p>
                                                    </div>
                                                    <div class="text-end">
                                                        <span class="badge <?php
                                                        if ($booking['status'] == 'confirmed')
                                                            echo 'bg-success';
                                                        elseif ($booking['status'] == 'pending')
                                                            echo 'bg-warning text-dark';
                                                        else
                                                            echo 'bg-danger';
                                                        ?> px-3 py-2" style="border-radius: 20px;">
                                                            <?php echo strtoupper($booking['status']); ?>
                                                        </span>
                                                        <h4 class="mt-2 text-dark">
                                                            ₹<?php echo number_format($booking['total_price'], 2); ?></h4>
                                                    </div>
                                                </div>

                                                <div class="row mb-3 bg-light p-3 rounded">
                                                    <div class="col-md-4">
                                                        <small class="text-muted d-block">Travellers</small>
                                                        <span><?php echo ($booking['adults'] + $booking['children'] + $booking['infants']); ?>
                                                            Persons (<?php echo $booking['adults']; ?>A,
                                                            <?php echo $booking['children']; ?>C,
                                                            <?php echo $booking['infants']; ?>I)</span>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <small class="text-muted d-block">Hotel Package</small>
                                                        <span
                                                            class="badge bg-info-subtle text-info border border-info-subtle"><?php echo htmlspecialchars($booking['hotel_type'] ?: 'Budget Package'); ?></span>
                                                        <span
                                                            class="d-block small mt-1">₹<?php
                                                            $display_price = $booking['hotel_price'];
                                                            // Fallback for older budget bookings where hotel_price was stored as 0
                                                            if ($display_price <= 0 || ($booking['hotel_type'] == 'Budget' && $display_price < $booking['p_budget'])) {
                                                                $display_price = $booking['p_budget'];
                                                            }
                                                            echo number_format($display_price, 2);
                                                            ?> per pax</span>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <small class="text-muted d-block">Booking Reference</small>
                                                        <span>#TB-<?php echo str_pad($booking['id'], 5, '0', STR_PAD_LEFT); ?></span>
                                                    </div>
                                                </div>

                                                <?php if (!empty($booking['selected_activities'])): ?>
                                                    <div class="mb-3 border-top pt-2">
                                                        <small class="text-muted d-block mb-1"><i class="fas fa-stars me-1 text-warning"></i> Additional Activities Added:</small>
                                                        <div class="d-flex flex-wrap gap-1">
                                                            <?php
                                                            $acts = explode(', ', $booking['selected_activities']);
                                                            foreach ($acts as $a):
                                                                ?>
                                                                    <span class="badge bg-success-subtle text-success border border-success-subtle" style="font-size: 0.75rem;"><?php echo htmlspecialchars($a); ?></span>
                                                            <?php endforeach; ?>
                                                        </div>
                                                    </div>
                                                <?php endif; ?>

                                                <div class="d-flex gap-2">
                                                    <?php if ($booking['status'] !== 'cancelled'): ?>
                                                            <button class="btn btn-primary btn-sm px-4 rounded-pill"
                                                                onclick='openEditModal(<?php echo htmlspecialchars(json_encode($booking), ENT_QUOTES, "UTF-8"); ?>)'>
                                                                <i class="fas fa-edit me-1"></i>Modify Booking
                                                            </button>
                                                    <?php endif; ?>
                                                    <a href="tour-details.php?id=<?php echo $booking['tour_id']; ?>"
                                                        class="btn btn-outline-secondary btn-sm px-4 rounded-pill">
                                                        View Details
                                                    </a>
                                                    <?php if ($booking['status'] == 'pending'): ?>
                                                            <a href="activity.php?booking_id=<?php echo $booking['id']; ?>"
                                                                class="btn btn-outline-info btn-sm px-4 rounded-pill border-info text-info">
                                                                <i class="fas fa-plus-circle me-1"></i>Add/Edit Activities
                                                            </a>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    <?php endwhile; ?>
            <?php else: ?>
                    <div class="col-12 text-center py-5">
                        <div class="mb-4">
                            <i class="fas fa-calendar-times fa-4x text-muted"></i>
                        </div>
                        <h3>No Bookings Found</h3>
                        <p class="text-muted">You haven't booked any tours yet. Start exploring our amazing destinations!</p>
                        <a href="tour.php" class="btn btn-primary mt-3">Browse Tours</a>
                    </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Edit Booking Modal -->
<style>
    /* Theme Color Overrides for this page */
    .text-primary {
        color: #63AB45 !important;
    }

    .bg-primary {
        background: linear-gradient(135deg, #63AB45 0%, #4e8a36 100%) !important;
    }

    .btn-primary {
        background-color: #63AB45 !important;
        border-color: #63AB45 !important;
        box-shadow: 0 4px 15px rgba(99, 171, 69, 0.3);
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        background-color: #559639 !important;
        border-color: #559639 !important;
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(99, 171, 69, 0.4);
    }

    .btn-outline-primary {
        color: #63AB45 !important;
        border-color: #63AB45 !important;
    }

    .btn-outline-primary:hover {
        background-color: #63AB45 !important;
        color: #fff !important;
    }

    /* Prevent nice-select from breaking modal dropdowns */
    #editBookingModal .nice-select {
        display: none !important;
    }

    #editBookingModal select {
        display: block !important;
    }

    #editBookingModal .btn-light {
        background-color: #f8f9fa;
        border: 1px solid #e9ecef;
        color: #666;
    }

    #editBookingModal .btn-light:hover {
        background-color: #e9ecef;
    }

    #editBookingModal .price-preview-box {
        border-radius: 12px !important;
    }

    #editBookingModal .input-group-text {
        border-right: none;
        color: #63AB45;
    }

    #editBookingModal .form-control:focus,
    #editBookingModal .form-select:focus {
        border-color: #63AB45;
        box-shadow: 0 0 0 0.25rem rgba(99, 171, 69, 0.1);
    }
</style>

<div class="modal fade" id="editBookingModal" tabindex="-1" aria-hidden="true" style="overflow-y: auto !important;">
    <div class="modal-dialog modal-lg mt-5 mb-5">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 15px;">
            <form method="POST">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold">Modify Your Booking</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <input type="hidden" name="booking_id" id="edit_id">
                    <input type="hidden" name="hotel_price" id="edit_hotel_price">
                    <input type="hidden" name="update_booking" value="1">

                    <div class="mb-3">
                        <label class="form-label fw-bold text-muted small text-uppercase">Departure Date</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0"><i
                                    class="far fa-calendar-alt text-primary"></i></span>
                            <input type="date" name="booking_date" id="edit_date"
                                class="form-control border-start-0 ps-0" required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold text-muted small text-uppercase">Hotel Package</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0"><i
                                    class="fas fa-hotel text-primary"></i></span>
                            <select name="hotel_info" id="edit_hotel_select" class="form-select border-start-0 ps-0"
                                required onchange="updateEstimatedPrice()" style="height: 45px;">
                                <!-- Populated via JS -->
                            </select>
                        </div>
                    </div>

                    <div class="travellers-selection bg-light p-2 px-3 rounded mb-3">
                        <label class="form-label fw-bold text-muted small text-uppercase mb-2 d-block">Number of
                            Travellers</label>

                        <div class="row align-items-center mb-2">
                            <div class="col-7">
                                <span class="fw-bold small"><i
                                        class="fas fa-user-friends me-2 text-primary"></i>Adults</span>
                            </div>
                            <div class="col-5">
                                <select name="adults" id="edit_adults" class="form-select form-select-sm"
                                    onchange="updateEstimatedPrice()">
                                    <?php for ($i = 1; $i <= 20; $i++)
                                        echo "<option value='$i'>$i</option>"; ?>
                                </select>
                            </div>
                        </div>

                        <div class="row align-items-center mb-2">
                            <div class="col-7">
                                <span class="fw-bold small"><i class="fas fa-child me-2 text-primary"></i>Child</span>
                            </div>
                            <div class="col-5">
                                <select name="children" id="edit_children" class="form-select form-select-sm"
                                    onchange="updateEstimatedPrice()">
                                    <?php for ($i = 0; $i <= 15; $i++)
                                        echo "<option value='$i'>$i</option>"; ?>
                                </select>
                            </div>
                        </div>

                        <div class="row align-items-center">
                            <div class="col-7">
                                <span class="fw-bold small"><i class="fas fa-baby me-2 text-primary"></i>Infant</span>
                            </div>
                            <div class="col-5">
                                <select name="infants" id="edit_infants" class="form-select form-select-sm"
                                    onchange="updateEstimatedPrice()">
                                    <?php for ($i = 0; $i <= 10; $i++)
                                        echo "<option value='$i'>$i</option>"; ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="price-preview-box bg-white border p-3 rounded shadow-sm mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-muted small">Hotels & Tour:</span>
                            <span class="text-dark small">₹<span id="base_est_total">0.00</span></span>
                        </div>
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-muted small">Booking Fee:</span>
                            <span class="text-dark small">₹50.00</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2 pb-2 border-bottom">
                            <span class="text-muted small">Service Fee (<span id="est_pax_label">1</span> pax):</span>
                            <span class="text-dark small">₹<span id="est_service_fee">20.00</span></span>
                        </div>
                    <div id="estimated_total_box">
                        <h4 class="text-center fw-bold text-success mb-2">Total: ₹<span id="estimated_total">0.00</span></h4>
                    </div>
 <div class="alert alert-warning py-2 mb-3 border-0 bg-warning bg-opacity-10 text-dark"
                        style="font-size: 0.75rem;">
                        <i class="fas fa-info-circle me-1"></i> Final price will be updated once you save.
                    </div>

                    <div class="row g-2 mt-2">
                        <div class="col-6">
                            <button type="button" class="btn btn-light w-100 rounded-pill fw-bold"
                                data-bs-dismiss="modal" style="padding: 10px;">Cancel</button>
                        </div>
                        <div class="col-6">
                            <button type="submit" class="btn btn-primary w-100 rounded-pill fw-bold"
                                style="padding: 10px;">Save Changes</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    let currentBooking = null;

    function openEditModal(booking) {
        currentBooking = booking;
        document.getElementById('edit_id').value = booking.id;
        document.getElementById('edit_date').value = booking.tour_date;
        document.getElementById('edit_adults').value = booking.adults;
        document.getElementById('edit_children').value = booking.children;
        document.getElementById('edit_infants').value = booking.infants;

        // Populate Hotel Select
        const hSelect = document.getElementById('edit_hotel_select');
        hSelect.innerHTML = '';

        const basePrice = parseFloat(booking.p_budget) || 0;
        const options = [
            { label: 'Budget hotels', price: basePrice },
            { label: '3 Star Hotel', price: basePrice + (parseFloat(booking.p_3star) || 0) },
            { label: '4 Star Hotel', price: basePrice + (parseFloat(booking.p_4star) || 0) },
            { label: '5 Star Hotel', price: basePrice + (parseFloat(booking.p_5star) || 0) },
            { label: 'Camps', price: basePrice + (parseFloat(booking.p_camps) || 0) },
            { label: 'Homestay', price: basePrice + (parseFloat(booking.p_homestay) || 0) }
        ];

        options.forEach(opt => {
            const isSelected = (opt.label === booking.hotel_type) ? 'selected' : '';
            hSelect.innerHTML += `<option value="${opt.label}|${opt.price}" ${isSelected}>${opt.label} (₹${parseFloat(opt.price).toLocaleString()})</option>`;
        });

        updateEstimatedPrice();

        var myModal = new bootstrap.Modal(document.getElementById('editBookingModal'));
        myModal.show();
    }

    function updateEstimatedPrice() {
        const adults = parseInt(document.getElementById('edit_adults').value) || 1;
        const children = parseInt(document.getElementById('edit_children').value) || 0;
        const infants = parseInt(document.getElementById('edit_infants').value) || 0;

        const hotelVal = document.getElementById('edit_hotel_select').value;
        const hotelPrice = parseFloat(hotelVal.split('|')[1]) || 0;
        const totalPeople = adults + children;
        const totalWithInfants = totalPeople + infants;
        
        const bookingFee = 50;
        const serviceFee = 20 * totalWithInfants;
        const baseTourTotal = hotelPrice * totalPeople;

        // Extract Activity Price from existing total for preview
        const currentTotal = parseFloat(currentBooking.total_price) || 0;
        const currentPeople = (parseInt(currentBooking.adults) || 0) + (parseInt(currentBooking.children) || 0);
        const currentWithInfants = currentPeople + (parseInt(currentBooking.infants) || 0);
        const currentHotelPrice = parseFloat(currentBooking.hotel_price) || 0;
        
        const currentBase = (currentHotelPrice * currentPeople) + 50 + (20 * currentWithInfants);
        const activityPrice = Math.max(0, currentTotal - currentBase);

        // Update UI
        document.getElementById('base_est_total').innerText = baseTourTotal.toLocaleString('en-IN', { minimumFractionDigits: 2 });
        document.getElementById('est_pax_label').innerText = totalWithInfants;
        document.getElementById('est_service_fee').innerText = (serviceFee + activityPrice).toLocaleString('en-IN', { minimumFractionDigits: 2 });

        const total = baseTourTotal + bookingFee + serviceFee + activityPrice;

        document.getElementById('estimated_total').innerText = total.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }
</script>

<?php include 'includes/footer.php'; ?>