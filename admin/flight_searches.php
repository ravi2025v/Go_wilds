<?php
require_once 'includes/db.php';
require_once 'includes/header.php';

// Handle Delete Search Log
if(isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM flight_searches WHERE id = $id");
    echo "<script>window.location.href='flight_searches.php';</script>";
}
?>

<div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
    <h4 class="mb-0 fw-bold"><i class="fas fa-plane-departure text-primary me-2"></i>Flight Search Logs</h4>
    <p class="mb-0 text-muted">View live searches performed by users on the frontend.</p>
</div>

<div class="card p-4 border-0 shadow-sm rounded-4">
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>Trip Type</th>
                    <th>Route Map</th>
                    <th>Departure Date</th>
                    <th>Passengers / Class</th>
                    <th>Fare Type</th>
                    <th>Searched On</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $result = $conn->query("SELECT * FROM flight_searches ORDER BY id DESC");
                if($result && $result->num_rows > 0):
                    while($row = $result->fetch_assoc()):
                ?>
                <tr>
                    <td>
                        <span class="badge bg-secondary"><?php echo $row['trip_type']; ?></span>
                    </td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <strong><?php echo htmlspecialchars($row['origin']); ?></strong>
                            <i class="fas fa-arrow-right text-muted mx-1"></i>
                            <strong><?php echo htmlspecialchars($row['destination']); ?></strong>
                        </div>
                    </td>
                    <td>
                        <i class="fas fa-calendar-alt text-primary me-1"></i> <?php echo date('d M Y', strtotime($row['departure_date'])); ?>
                    </td>
                    <td>
                        <small class="d-block text-muted">
                            <i class="fas fa-user border rounded-circle p-1"></i> 
                            <?php echo $row['adults']; ?> Ad, <?php echo $row['children']; ?> Ch, <?php echo $row['infants']; ?> Inf
                        </small>
                        <span class="badge bg-info text-dark mt-1"><?php echo $row['travel_class']; ?></span>
                    </td>
                    <td>
                        <span class="badge" style="background-color: #f8f9fa; color: #0d6efd; border: 1px solid #0d6efd;">
                            <?php echo $row['special_fare']; ?>
                        </span>
                    </td>
                    <td>
                        <small class="text-muted"><i class="fas fa-clock me-1"></i><?php echo date('d M, Y - h:i A', strtotime($row['created_at'])); ?></small>
                    </td>
                    <td>
                        <a href="flight_searches.php?delete=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger rounded-circle px-2" onclick="return confirm('Delete this search log?')">
                            <i class="fas fa-trash"></i>
                        </a>
                    </td>
                </tr>
                <?php 
                    endwhile; 
                else: 
                ?>
                <tr>
                    <td colspan="7" class="text-center py-5">
                        <i class="fas fa-search display-4 text-muted mb-3 opacity-25"></i>
                        <h5 class="text-muted fw-bold">No recent searches found</h5>
                        <p class="text-muted small">When users search for flights on the homepage, the logs will appear here.</p>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
