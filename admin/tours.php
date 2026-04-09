<?php
session_start();
require_once 'includes/db.php';

// Handle Delete
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM tours WHERE id = $id");
    header("Location: tours.php?deleted=1");
    exit;
}

// Handle Add/Edit
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_tour'])) {
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $title = $conn->real_escape_string($_POST['title']);
    $category = $conn->real_escape_string($_POST['category']);
    $destination = $conn->real_escape_string($_POST['destination']);
    $price = floatval($_POST['price']);
    $duration = $conn->real_escape_string($_POST['duration']);
    $status = $conn->real_escape_string($_POST['status']);
    $image = $conn->real_escape_string($_POST['image']);

    if ($id > 0) {
        $sql = "UPDATE tours SET title='$title', category='$category', destination='$destination', price=$price, duration='$duration', status='$status', image='$image' WHERE id=$id";
    } else {
        $sql = "INSERT INTO tours (title, category, destination, price, duration, status, image) VALUES ('$title', '$category', '$destination', $price, '$duration', '$status', '$image')";
    }
    
    if ($conn->query($sql)) {
        header("Location: tours.php?success=1");
    } else {
        $error = $conn->error;
    }
}

// Fetch all tours
$result = $conn->query("SELECT * FROM tours ORDER BY id DESC");

include 'includes/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Manage Tours</h4>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tourModal" onclick="clearTourForm()">
        <i class="fas fa-plus me-1"></i> Add New Tour
    </button>
</div>

<div class="card p-4">
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Title</th>
                    <th>Destination</th>
                    <th>Price</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><img src="../<?php echo !empty($row['image']) ? $row['image'] : 'assets/images/tour.jpg'; ?>" width="50" class="rounded"></td>
                    <td><strong><?php echo $row['title']; ?></strong><br><small class="text-muted"><?php echo $row['category']; ?></small></td>
                    <td><?php echo $row['destination']; ?></td>
                    <td>₹<?php echo number_format($row['price']); ?></td>
                    <td><span class="badge <?php echo $row['status'] == 'active' ? 'bg-success' : 'bg-danger'; ?>"><?php echo ucfirst($row['status']); ?></span></td>
                    <td>
                        <button class="btn btn-sm btn-info text-white" onclick='editTour(<?php echo json_encode($row); ?>)'>
                            <i class="fas fa-edit"></i>
                        </button>
                        <a href="?delete=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this tour?')">
                            <i class="fas fa-trash"></i>
                        </a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Tour Modal Logic -->
<div class="modal fade" id="tourModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="POST">
                <div class="modal-header">
                    <h5 class="modal-title" id="tourModalTitle">Add Tour</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="tourId">
                    <input type="hidden" name="save_tour" value="1">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label">Title</label>
                            <input type="text" name="title" id="tourTitle" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Category</label>
                            <input type="text" name="category" id="tourCategory" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Destination</label>
                            <input type="text" name="destination" id="tourDestination" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Price (₹)</label>
                            <input type="number" step="0.01" name="price" id="tourPrice" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Duration</label>
                            <input type="text" name="duration" id="tourDuration" class="form-control" placeholder="e.g. 3 Days">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Status</label>
                            <select name="status" id="tourStatus" class="form-select">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Image Path</label>
                            <input type="text" name="image" id="tourImage" class="form-control" placeholder="assets/images/...">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Save Tour</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function clearTourForm() {
    document.getElementById('tourId').value = '';
    document.getElementById('tourTitle').value = '';
    document.getElementById('tourCategory').value = '';
    document.getElementById('tourDestination').value = '';
    document.getElementById('tourPrice').value = '';
    document.getElementById('tourDuration').value = '';
    document.getElementById('tourImage').value = '';
    document.getElementById('tourModalTitle').innerText = 'Add Tour';
}
function editTour(data) {
    document.getElementById('tourId').value = data.id;
    document.getElementById('tourTitle').value = data.title;
    document.getElementById('tourCategory').value = data.category;
    document.getElementById('tourDestination').value = data.destination;
    document.getElementById('tourPrice').value = data.price;
    document.getElementById('tourDuration').value = data.duration;
    document.getElementById('tourImage').value = data.image;
    document.getElementById('tourStatus').value = data.status;
    document.getElementById('tourModalTitle').innerText = 'Edit Tour';
    new bootstrap.Modal(document.getElementById('tourModal')).show();
}
</script>

<?php include 'includes/footer.php'; ?>
