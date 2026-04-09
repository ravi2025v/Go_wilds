<?php
session_start();
require_once 'includes/db.php';
require_once 'includes/header.php';

// Handle Search
$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';
$where_clause = "";
if(!empty($search)) {
    // Search by City/Destination or Tour Name
    $where_clause = " WHERE t.title LIKE '%$search%' OR t.destination LIKE '%$search%' OR b.customer_name LIKE '%$search%'";
}

// Fetch tour bookings with tour details and customer details
$sql = "SELECT b.*, t.title as tour_name, t.destination, t.tour_type 
        FROM tour_bookings b 
        LEFT JOIN tours t ON b.tour_id = t.id 
        $where_clause
        ORDER BY b.id DESC";
$result = $conn->query($sql);
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0 fw-bold">Tour Bookings Manager</h4>
    <form method="GET" action="tour_bookings.php" class="d-flex gap-2">
        <input type="text" name="search" class="form-control" placeholder="Search by city or tour..." value="<?php echo htmlspecialchars($search); ?>">
        <button type="submit" class="btn btn-secondary"><i class="fas fa-search"></i></button>
        <?php if(!empty($search)): ?>
            <a href="tour_bookings.php" class="btn btn-outline-danger"><i class="fas fa-times"></i></a>
        <?php endif; ?>
    </form>
</div>

<div class="card border-0 shadow-sm p-4">
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light text-muted small text-uppercase">
                <tr>
                    <th>Booking ID</th>
                    <th>Customer</th>
                    <th>Tour & Detail</th>
                    <th>Passengers</th>
                    <th>Dates</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if($result && $result->num_rows > 0): ?>
                    <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><span class="fw-bold text-primary">#<?php echo $row['id']; ?></span></td>
                        <td>
                            <div class="fw-bold"><?php echo $row['customer_name'] ?: 'N/A'; ?></div>
                            <div class="small text-muted"><i class="far fa-envelope me-1"></i><?php echo $row['customer_email']; ?></div>
                            <div class="small text-muted"><i class="fas fa-phone-alt me-1"></i><?php echo $row['customer_phone']; ?></div>
                        </td>
                        <td>
                            <div class="fw-bold text-dark"><?php echo $row['tour_name'] ?: ($row['tour_title'] ?? 'N/A'); ?></div>
                            <div class="small text-muted"><i class="fas fa-map-marker-alt me-1"></i><?php echo $row['destination'] ?? ''; ?> | <?php echo $row['tour_type'] ?? 'Standard'; ?></div>
                            <div class="badge bg-light text-dark border mt-1">
                                <i class="fas fa-hotel me-1 text-primary"></i> <?php echo $row['hotel_type']; ?>
                            </div>
                            <div class="fw-bold text-success mt-1">₹<?php echo number_format($row['total_price']); ?></div>
                        </td>
                        <td>
                            <div class="small">Adults: <span class="fw-bold"><?php echo $row['adults']; ?></span></div>
                            <div class="small">Children: <span class="fw-bold"><?php echo $row['children']; ?></span></div>
                            <div class="small">Infants: <span class="fw-bold"><?php echo $row['infants']; ?></span></div>
                        </td>
                        <td>
                            <div class="small mb-1">
                                <span class="text-muted small">Travel Date:</span><br>
                                <span class="badge bg-info text-white"><?php echo $row['tour_date'] != '0000-00-00' ? date('M d, Y', strtotime($row['tour_date'])) : 'Not Set'; ?></span>
                            </div>
                            <div class="small">
                                <span class="text-muted small">Booked On:</span><br>
                                <span><?php echo date('M d, Y', strtotime($row['created_at'])); ?></span>
                            </div>
                        </td>
                        <td>
                            <span class="badge rounded-pill <?php echo $row['status'] == 'confirmed' ? 'bg-success' : 'bg-warning text-dark'; ?>">
                                <?php echo ucfirst($row['status']); ?>
                            </span>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="6" class="text-center py-5 text-muted">No tour bookings found<?php echo !empty($search) ? " matching '$search'" : ""; ?>.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
