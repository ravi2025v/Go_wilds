<?php
include 'includes/header.php';
require_once 'includes/db.php';

// Handle Actions
if (isset($_GET['action'])) {
    $review_id = intval($_GET['id']);
    if ($_GET['action'] == 'approve') {
        $conn->query("UPDATE reviews SET status = 'approved' WHERE id = $review_id");
    } elseif ($_GET['action'] == 'pending') {
        $conn->query("UPDATE reviews SET status = 'pending' WHERE id = $review_id");
    } elseif ($_GET['action'] == 'delete') {
        $conn->query("DELETE FROM reviews WHERE id = $review_id");
    }
    header("Location: reviews.php");
    exit();
}

// Fetch Reviews
$reviews = $conn->query("SELECT r.*, t.title as tour_title FROM reviews r JOIN tours t ON r.tour_id = t.id ORDER BY r.created_at DESC");
?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h3 mb-0 text-gray-800">Manage Tour Reviews</h2>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                    <thead class="table-light">
                        <tr>
                            <th>Date</th>
                            <th>Tour</th>
                            <th>User Name</th>
                            <th>Rating</th>
                            <th>Comment</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if($reviews && $reviews->num_rows > 0): ?>
                            <?php while($row = $reviews->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo date('d M, Y', strtotime($row['created_at'])); ?></td>
                                <td><strong><?php echo htmlspecialchars($row['tour_title']); ?></strong></td>
                                <td><?php echo htmlspecialchars($row['user_name']); ?></td>
                                <td>
                                    <span class="badge bg-warning text-dark">
                                        <?php echo $row['rating']; ?> <i class="fas fa-star"></i>
                                    </span>
                                </td>
                                <td><p class="small mb-0" style="max-width: 300px;"><?php echo htmlspecialchars($row['comment']); ?></p></td>
                                <td>
                                    <?php if($row['status'] == 'approved'): ?>
                                        <span class="badge bg-success">Approved</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Pending</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <?php if($row['status'] == 'pending'): ?>
                                            <a href="reviews.php?action=approve&id=<?php echo $row['id']; ?>" class="btn btn-success" title="Approve"><i class="fas fa-check"></i></a>
                                        <?php else: ?>
                                            <a href="reviews.php?action=pending&id=<?php echo $row['id']; ?>" class="btn btn-warning" title="Set to Pending"><i class="fas fa-clock"></i></a>
                                        <?php endif; ?>
                                        <a href="reviews.php?action=delete&id=<?php echo $row['id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this review?')" title="Delete"><i class="fas fa-trash"></i></a>
                                    </div>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center py-4">No reviews found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
