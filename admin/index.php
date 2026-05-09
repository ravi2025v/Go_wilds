<?php
require_once 'includes/db.php';
require_once 'includes/header.php';

// Fetch stats for dashboard
$tours_count = $conn->query("SELECT COUNT(*) FROM tours")->fetch_row()[0] ?? 0;
$bookings_count = $conn->query("SELECT COUNT(*) FROM tour_bookings")->fetch_row()[0] ?? 0;
$users_count = $conn->query("SELECT COUNT(*) FROM users")->fetch_row()[0] ?? 0;
$activities_count = $conn->query("SELECT COUNT(*) FROM activity_cards")->fetch_row()[0] ?? 0;
$visa_apps_count = $conn->query("SELECT COUNT(*) FROM visa_applications WHERE status = 'pending'")->fetch_row()[0] ?? 0;

// Fetch recent bookings with tour titles and customer details
$recent_bookings = $conn->query("SELECT b.*, u.name as user_name, u.phone as user_phone, t.title as tour_title 
                                FROM tour_bookings b 
                                LEFT JOIN users u ON b.user_id = u.id 
                                LEFT JOIN tours t ON b.tour_id = t.id
                                ORDER BY b.id DESC LIMIT 5");

// Fetch recent visa applications
$recent_visas = $conn->query("SELECT va.*, vc.country_name 
                             FROM visa_applications va 
                             JOIN visa_countries vc ON va.country_id = vc.id 
                             ORDER BY va.id DESC LIMIT 5");
?>

<div class="row g-4 mb-4">
    <div class="col-md">
        <div class="card p-3 border-0 shadow-sm bg-primary text-white h-100">
            <div class="d-flex justify-content-between">
                <div>
                    <h6 class="mb-0 opacity-75">Total Tours</h6>
                    <h3 class="mb-0 fw-bold"><?php echo $tours_count; ?></h3>
                </div>
                <div class="stat-icon"><i class="fas fa-map-marked-alt"></i></div>
            </div>
            <a href="tours.php" class="text-white text-decoration-none small mt-3">View All <i class="fas fa-arrow-right ms-1"></i></a>
        </div>
    </div>
    <div class="col-md">
        <div class="card p-3 border-0 shadow-sm bg-success text-white h-100">
            <div class="d-flex justify-content-between">
                <div>
                    <h6 class="mb-0 opacity-75">Tour Bookings</h6>
                    <h3 class="mb-0 fw-bold"><?php echo $bookings_count; ?></h3>
                </div>
                <div class="stat-icon"><i class="fas fa-shopping-cart"></i></div>
            </div>
            <a href="tour_bookings.php" class="text-white text-decoration-none small mt-3">View All <i class="fas fa-arrow-right ms-1"></i></a>
        </div>
    </div>
    <div class="col-md">
        <div class="card p-3 border-0 shadow-sm bg-info text-white h-100">
            <div class="d-flex justify-content-between">
                <div>
                    <h6 class="mb-0 opacity-75">Pending Visas</h6>
                    <h3 class="mb-0 fw-bold"><?php echo $visa_apps_count; ?></h3>
                </div>
                <div class="stat-icon"><i class="fas fa-passport"></i></div>
            </div>
            <a href="visa_applications.php" class="text-white text-decoration-none small mt-3">View All <i class="fas fa-arrow-right ms-1"></i></a>
        </div>
    </div>
    <div class="col-md">
        <div class="card p-3 border-0 shadow-sm bg-info text-white h-100" style="background-color: #0dcaf0 !important;">
            <div class="d-flex justify-content-between">
                <div>
                    <h6 class="mb-0 opacity-75">Attractions</h6>
                    <h3 class="mb-0 fw-bold"><?php echo $activities_count; ?></h3>
                </div>
                <div class="stat-icon"><i class="fas fa-map-pin"></i></div>
            </div>
            <a href="activity_manage.php" class="text-white text-decoration-none small mt-3">View All <i class="fas fa-arrow-right ms-1"></i></a>
        </div>
    </div>
    <div class="col-md">
        <div class="card p-3 border-0 shadow-sm bg-warning text-white h-100">
            <div class="d-flex justify-content-between">
                <div>
                    <h6 class="mb-0 opacity-75">Active Users</h6>
                    <h3 class="mb-0 fw-bold"><?php echo $users_count; ?></h3>
                </div>
                <div class="stat-icon"><i class="fas fa-users"></i></div>
            </div>
            <a href="users.php" class="text-white text-decoration-none small mt-3">View All <i class="fas fa-arrow-right ms-1"></i></a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm p-4 h-100">
            <h5 class="fw-bold mb-4">Recent Tour Bookings</h5>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Customer</th>
                            <th>Tour</th>
                            <th>Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if($recent_bookings && $recent_bookings->num_rows > 0): ?>
                            <?php while($row = $recent_bookings->fetch_assoc()): ?>
                            <tr>
                                <td>#<?php echo $row['id']; ?></td>
                                <td>
                                    <div class="fw-bold"><?php echo $row['customer_name'] ?: ($row['user_name'] ?? 'Guest'); ?></div>
                                    <div class="small text-muted"><i class="fas fa-phone-alt me-1" style="font-size: 10px;"></i><?php echo $row['customer_phone'] ?: ($row['user_phone'] ?? 'N/A'); ?></div>
                                </td>
                                <td><?php echo $row['tour_title'] ?: ($row['tour_name'] ?? 'N/A'); ?></td>
                                <td><?php echo date('M d, Y', strtotime($row['created_at'])); ?></td>
                                <td>
                                    <?php
                                    $status_class = 'bg-warning text-dark';
                                    if($row['status'] == 'confirmed') $status_class = 'bg-success text-white';
                                    if($row['status'] == 'cancelled') $status_class = 'bg-danger text-white';
                                    ?>
                                    <span class="badge <?php echo $status_class; ?>"><?php echo ucfirst($row['status'] ?: 'Pending'); ?></span>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="5" class="text-center py-4">No recent bookings found.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card border-0 shadow-sm p-4 mt-4">
            <h5 class="fw-bold mb-4">Recent Visa Applications</h5>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Applicant</th>
                            <th>Destination</th>
                            <th>Passport</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if($recent_visas && $recent_visas->num_rows > 0): ?>
                            <?php while($row = $recent_visas->fetch_assoc()): ?>
                            <tr>
                                <td>#VSA-<?php echo str_pad($row['id'], 5, '0', STR_PAD_LEFT); ?></td>
                                <td>
                                    <div class="fw-bold"><?php echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?></div>
                                    <div class="small text-muted"><?php echo $row['email']; ?></div>
                                </td>
                                <td><span class="badge bg-light text-dark border"><?php echo htmlspecialchars($row['country_name']); ?></span></td>
                                <td class="font-monospace small text-primary"><?php echo $row['passport_no']; ?></td>
                                <td>
                                    <?php
                                    $v_status_class = 'bg-warning text-dark';
                                    if($row['status'] == 'Approved') $v_status_class = 'bg-success text-white';
                                    if($row['status'] == 'Rejected') $v_status_class = 'bg-danger text-white';
                                    ?>
                                    <span class="badge <?php echo $v_status_class; ?>"><?php echo ucfirst($row['status']); ?></span>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="5" class="text-center py-4">No visa applications found.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm p-4 h-100">
            <h5 class="fw-bold mb-4">Quick Links</h5>
            <div class="d-grid gap-2">
                <a href="tours.php" class="btn btn-outline-primary text-start p-3"><i class="fas fa-plus me-2"></i> Add New Tour</a>
                <a href="activity_manage.php" class="btn btn-outline-success text-start p-3"><i class="fas fa-map-marked-alt me-2"></i> Manage Activities</a>
                <a href="visa_services.php" class="btn btn-outline-info text-start p-3"><i class="fas fa-passport me-2"></i> Manage Visa Types</a>
                <a href="visa_applications.php" class="btn btn-outline-primary text-start p-3" style="color: #63AB45; border-color: #63AB45;"><i class="fas fa-file-invoice me-2"></i> Visa Applications</a>
                <a href="users.php" class="btn btn-outline-secondary text-start p-3"><i class="fas fa-user-shield me-2"></i> Admin Settings</a>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
