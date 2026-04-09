<?php
require_once 'includes/db.php';
require_once 'includes/header.php';

// Handle Delete
if(isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM itineraries WHERE id = $id");
    echo "<script>window.location.href='itineraries.php';</script>";
}

// Handle Add/Edit
if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tour_id = intval($_POST['tour_id']);
    $day_number = intval($_POST['day_number']);
    $title = $conn->real_escape_string($_POST['title']);
    $description = $conn->real_escape_string($_POST['description']);
    
    $itinerary_id = 0;
    if(isset($_POST['id']) && !empty($_POST['id'])) {
        $id = intval($_POST['id']);
        $conn->query("UPDATE itineraries SET tour_id=$tour_id, day_number=$day_number, title='$title', description='$description' WHERE id=$id");
        $itinerary_id = $id;
    } else {
        $conn->query("INSERT INTO itineraries (tour_id, day_number, title, description) VALUES ($tour_id, $day_number, '$title', '$description')");
        $itinerary_id = $conn->insert_id;
    }
    
    // Handle Multiple Image Uploads
    if(isset($_FILES['images']['name'][0]) && !empty($_FILES['images']['name'][0])) {
        $target_dir = "../uploads/itineraries/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        
        $count = count($_FILES['images']['name']);
        for($i=0; $i<$count; $i++) {
            $filename = time() . '_' . rand(1000, 9999) . '_' . basename($_FILES['images']['name'][$i]);
            $target_file = $target_dir . $filename;
            
            if(move_uploaded_file($_FILES['images']['tmp_name'][$i], $target_file)) {
                $db_path = "uploads/itineraries/" . $filename;
                $conn->query("INSERT INTO itinerary_images (itinerary_id, image) VALUES ($itinerary_id, '$db_path')");
            }
        }
    }
    
    echo "<script>window.location.href='itineraries.php';</script>";
}

// Fetch all tours for dropdown
$toursResult = $conn->query("SELECT id, title FROM tours ORDER BY id DESC");
$tours = [];
while($t = $toursResult->fetch_assoc()){
    $tours[] = $t;
}

// Fetch itineraries with tour details
$query = "SELECT i.*, t.title as tour_title FROM itineraries i JOIN tours t ON i.tour_id = t.id ORDER BY i.tour_id DESC, i.day_number ASC";
$itineraries = $conn->query($query);
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Manage Itineraries</h4>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#itineraryModal" onclick="resetForm()">
        <i class="fas fa-plus me-1"></i> Add Itinerary Day
    </button>
</div>

<div class="card p-4">
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>Tour</th>
                    <th>Day</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $itineraries->fetch_assoc()): ?>
                <tr>
                    <td><strong><?php echo htmlspecialchars($row['tour_title']); ?></strong></td>
                    <td><span class="badge bg-info">Day <?php echo $row['day_number']; ?></span></td>
                    <td><?php echo htmlspecialchars($row['title']); ?></td>
                    <td><?php echo mb_strimwidth(htmlspecialchars($row['description']), 0, 50, "..."); ?></td>
                    <td class="text-end">
                        <button class="btn btn-sm btn-light me-2" onclick='editItinerary(<?php echo json_encode($row); ?>)'>
                            <i class="fas fa-edit text-primary"></i>
                        </button>
                        <a href="?delete=<?php echo $row['id']; ?>" class="btn btn-sm btn-light" onclick="return confirm('Delete this itinerary day?')">
                            <i class="fas fa-trash text-danger"></i>
                        </a>
                    </td>
                </tr>
                <?php endwhile; ?>
                <?php if($itineraries->num_rows == 0): ?>
                <tr><td colspan="5" class="text-center py-4">No itineraries found</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Add/Edit Modal -->
<div class="modal fade" id="itineraryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="" enctype="multipart/form-data">
                <input type="hidden" name="id" id="itemId">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Add Itinerary Day</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Select Tour</label>
                        <select name="tour_id" id="tourId" class="form-select" required>
                            <option value="">-- Choose Tour --</option>
                            <?php foreach($tours as $t): ?>
                            <option value="<?php echo $t['id']; ?>"><?php echo htmlspecialchars($t['title']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Day Number</label>
                        <input type="number" name="day_number" id="dayNumber" class="form-control" required placeholder="e.g. 1">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Title</label>
                        <input type="text" name="title" id="dayTitle" class="form-control" required placeholder="e.g. Arrival at Bali">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" id="dayDesc" class="form-control" rows="4" required placeholder="What will happen on this day?"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Add Images (Hold Ctrl/Cmd to select multiple)</label>
                        <input type="file" name="images[]" class="form-control" multiple accept="image/*">
                        <small class="text-muted">Images will be appended to this day.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Itinerary</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function resetForm() {
    document.getElementById('itemId').value = '';
    document.getElementById('tourId').value = '';
    document.getElementById('dayNumber').value = '';
    document.getElementById('dayTitle').value = '';
    document.getElementById('dayDesc').value = '';
    document.getElementById('modalTitle').innerText = 'Add Itinerary Day';
}

function editItinerary(data) {
    document.getElementById('itemId').value = data.id;
    document.getElementById('tourId').value = data.tour_id;
    document.getElementById('dayNumber').value = data.day_number;
    document.getElementById('dayTitle').value = data.title;
    document.getElementById('dayDesc').value = data.description;
    document.getElementById('modalTitle').innerText = 'Edit Itinerary Day';
    var modal = new bootstrap.Modal(document.getElementById('itineraryModal'));
    modal.show();
}
</script>

<?php require_once 'includes/footer.php'; ?>
