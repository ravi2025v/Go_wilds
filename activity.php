<?php
require_once 'admin/includes/db.php';
session_name("GoWilds_Session");
session_start();

// --- START: Database Sync Logic (MUST BE BEFORE ANY OUTPUT) ---
$editing_booking_id = isset($_GET['booking_id']) ? intval($_GET['booking_id']) : 0;
$return_tour_id = isset($_GET['return_tour_id']) ? intval($_GET['return_tour_id']) : ($_SESSION['active_booking_tour_id'] ?? 0);

if ($editing_booking_id > 0 && !isset($_GET['sync_done'])) {
    $_SESSION['activity_cart'] = [];
    $res = $conn->query("SELECT selected_activities FROM tour_bookings WHERE id = $editing_booking_id");
    if ($res && $res->num_rows > 0) {
        $row = $res->fetch_assoc();
        $titles = array_map('trim', explode(',', $row['selected_activities']));
        foreach ($titles as $t) {
            if (empty($t)) continue;
            $t_esc = $conn->real_escape_string($t);
            $act_res = $conn->query("SELECT id, title, price FROM activity_cards WHERE title = '$t_esc' LIMIT 1");
            if ($act_res && $act_res->num_rows > 0) {
                $a = $act_res->fetch_assoc();
                $_SESSION['activity_cart'][$a['id']] = ['title' => $a['title'], 'price' => floatval($a['price'])];
            }
        }
    }
    header("Location: activity.php?booking_id=$editing_booking_id&sync_done=1");
    exit;
}
// --- END: Database Sync Logic ---

$title = "Premium Activity Selection - GoWilds";
include 'includes/header.php';
?>

<style>
    :root { --primary-color: #F7921E; --secondary-color: #63AB45; --dark-bg: #1C231F; }
    body { background-color: #f8f9fa; }
    .premium-header { background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('assets/images/tour-details-bg.jpg'); background-size: cover; padding: 80px 0 40px; color: white; text-align: center; }
    .country-tab { padding: 10px 25px; border-radius: 30px; text-decoration: none; color: var(--dark-bg); font-weight: 600; border: 1px solid #ddd; margin: 5px; display: inline-block; transition: 0.3s; }
    .country-tab.active { background: var(--primary-color); color: white; border-color: var(--primary-color); }
    .act-card { background: white; border-radius: 15px; overflow: hidden; border: 1px solid #eee; transition: 0.3s; height: 100%; display: flex; flex-direction: column; position: relative; }
    .act-card:hover { transform: translateY(-5px); border-color: var(--primary-color); box-shadow: 0 10px 20px rgba(0,0,0,0.05); }
    .act-img-wrapper { position: relative; height: 180px; width: 100%; overflow: hidden; }
    .act-img { height: 100%; width: 100%; object-fit: cover; }
    .act-code-badge { position: absolute; top: 10px; left: 10px; background: rgba(0,0,0,0.6); color: white; padding: 4px 10px; border-radius: 5px; font-size: 11px; font-weight: 600; backdrop-filter: blur(5px); z-index: 5; border: 1px solid rgba(255,255,255,0.2); }
    .act-body { padding: 20px; flex-grow: 1; display: flex; flex-direction: column; }
    .act-title { font-size: 1.1rem; font-weight: 700; margin-bottom: 10px; color: var(--dark-bg); }
    .select-btn { background: var(--dark-bg); color: white; border-radius: 50px; padding: 8px 20px; font-weight: 600; transition: 0.3s; border: none; width: 100%; margin-top: 10px; }
    .select-btn.selected { background: var(--secondary-color); }
    #floating-selection-bar { position: fixed; bottom: 20px; left: 50%; transform: translateX(-50%); background: #1c231f; color: white; padding: 15px 30px; border-radius: 100px; display: flex; align-items: center; gap: 25px; box-shadow: 0 10px 30px rgba(0,0,0,0.4); z-index: 2000; border: 2px solid rgba(255,255,255,0.1); }
</style>

<?php
// Fetch Filters
$country_res = $conn->query("SELECT DISTINCT country_name FROM activity_cards WHERE status='active' ORDER BY country_name ASC");
$countries = []; while ($c = $country_res->fetch_assoc()) $countries[] = $c['country_name'];

$active_country = isset($_GET['country']) ? $_GET['country'] : (count($countries) > 0 ? $countries[0] : 'Singapore');
$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';

$query = "SELECT * FROM activity_cards WHERE status='active' AND country_name = '$active_country'";
if (!empty($search)) { $query .= " AND (title LIKE '%$search%' OR unique_code LIKE '%$search%')"; }
$query .= " ORDER BY title ASC";
$result = $conn->query($query);

$stored_cart = $_SESSION['activity_cart'] ?? [];
?>

<div class="premium-header">
    <div class="container">
        <h2 class="fw-bold"><?php echo $active_country; ?> Activities</h2>
        <p class="opacity-75">Select experiences to sync them with your booking.</p>
    </div>
</div>

<div class="container mt-4 mb-5 pb-5">
    <div class="text-center mb-4">
        <?php foreach ($countries as $c): ?>
            <a href="?country=<?php echo urlencode($c); ?>&booking_id=<?php echo $editing_booking_id; ?>&return_tour_id=<?php echo $return_tour_id; ?>&sync_done=1" 
               class="country-tab <?php echo $active_country === $c ? 'active' : ''; ?>">
                <?php echo $c; ?>
            </a>
        <?php endforeach; ?>
    </div>

    <div class="row g-4">
        <?php if($result && $result->num_rows > 0): while($row = $result->fetch_assoc()): ?>
            <div class="col-xl-3 col-lg-4 col-md-6">
                <div class="act-card shadow-sm">
                    <div class="act-img-wrapper">
                        <span class="act-code-badge"><?php echo $row['unique_code']; ?></span>
                        <img src="<?php echo !empty($row['image']) ? $row['image'] : 'assets/images/activity-placeholder.png'; ?>" class="act-img" onerror="this.onerror=null; this.src='assets/images/activity-placeholder.png'">
                    </div>
                    <div class="act-body text-center">
                        <h6 class="act-title"><?php echo $row['title']; ?></h6>
                        <div class="mt-auto">
                            <span class="text-success fw-bold d-block mb-1">₹<?php echo number_format($row['price']); ?></span>
                            <button class="select-btn <?php echo isset($stored_cart[$row['id']]) ? 'selected' : ''; ?>" 
                                    onclick="toggleActivity(this, <?php echo $row['id']; ?>, '<?php echo addslashes($row['title']); ?>', <?php echo $row['price']; ?>)">
                                <?php echo isset($stored_cart[$row['id']]) ? 'SELECTED' : 'SELECT'; ?>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        <?php endwhile; endif; ?>
    </div>
</div>

<div id="floating-selection-bar" class="<?php echo empty($stored_cart) ? 'd-none' : ''; ?>">
    <div class="d-flex align-items-center gap-3">
        <div class="text-start">
            <div class="fw-bold"><span id="cart-count"><?php echo count($stored_cart); ?></span> Selected</div>
            <div class="small opacity-75">Total: ₹<span id="cart-total"><?php echo number_format(array_sum(array_column($stored_cart, 'price'))); ?></span></div>
        </div>
        <div class="vr bg-white opacity-25"></div>
        <?php if($editing_booking_id > 0): ?>
            <button onclick="updateBooking()" class="btn btn-success rounded-pill px-4 fw-bold shadow-lg border-2 border-white">
                <i class="fas fa-save me-2"></i> Update Booking #<?php echo $editing_booking_id; ?>
            </button>
        <?php elseif($return_tour_id > 0): ?>
            <a href="tour-details.php?id=<?php echo $return_tour_id; ?>" class="btn btn-primary rounded-pill px-4 fw-bold shadow-lg border-2 border-white">
                <i class="fas fa-arrow-left me-2"></i> Save & Return to Tour
            </a>
        <?php else: ?>
            <a href="javascript:history.back()" class="btn btn-primary rounded-pill px-4 fw-bold shadow-lg border-2 border-white">
                <i class="fas fa-arrow-left me-2"></i> Confirm & Return to Booking
            </a>
        <?php endif; ?>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
function toggleActivity(btn, id, title, price) {
    const action = btn.classList.contains('selected') ? 'remove' : 'add';
    $.post('ajax_update_activity_cart.php', { action, id, title, price }, function(resp) {
        if(resp.success) {
            btn.classList.toggle('selected');
            btn.innerText = btn.classList.contains('selected') ? 'SELECTED' : 'SELECT';
            $('#cart-count').text(resp.cart_count);
            $('#cart-total').text(resp.cart_total.toLocaleString('en-IN'));
            if(resp.cart_count > 0) $('#floating-selection-bar').removeClass('d-none');
            else if(<?php echo $editing_booking_id; ?> == 0) $('#floating-selection-bar').addClass('d-none');
        }
    });
}
function updateBooking() {
    const btn = event.currentTarget;
    const bId = <?php echo $editing_booking_id; ?>;
    $(btn).prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i> UPDATING...');
    $.post('update_booking_activities.php', { booking_id: bId }, function(resp) {
        if(resp.success) window.location.href = 'my-bookings.php?msg=Booking updated successfully!';
        else {
            alert('Error updating booking: ' + resp.message);
            $(btn).prop('disabled', false).html('<i class="fas fa-save me-2"></i> Update Booking');
        }
    });
}
</script>
<?php include 'includes/footer.php'; ?>