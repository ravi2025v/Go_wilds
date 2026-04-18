<?php
require_once 'includes/db.php';

// Handle AJAX Toggle Status
if (isset($_POST['toggle_status'])) {
    $id = intval($_POST['id']);
    $new_status = $_POST['new_status'] === 'active' ? 'active' : 'inactive';
    $conn->query("UPDATE activity_cards SET status = '$new_status' WHERE id = $id");
    echo "Success";
    exit;
}

// Handle Delete
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM activity_cards WHERE id = $id");
    header("Location: activity_manage.php?deleted=1");
    exit;
}

// Handle Add/Edit
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_activity'])) {
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $title = $conn->real_escape_string($_POST['title']);
    $country = $conn->real_escape_string($_POST['country_name']);
    $city = $conn->real_escape_string($_POST['city_name']);
    $unique_code = $conn->real_escape_string($_POST['unique_code']);
    $card_code = $conn->real_escape_string($_POST['card_code']);
    $city_code = $conn->real_escape_string($_POST['city_code']);
    $price = floatval($_POST['price']);
    $category = $conn->real_escape_string($_POST['category']);
    $image = $conn->real_escape_string($_POST['image']);

    if ($id > 0) {
        $sql = "UPDATE activity_cards SET title='$title', country_name='$country', city_name='$city', unique_code='$unique_code', card_code='$card_code', city_code='$city_code', price=$price, category='$category', image='$image' WHERE id=$id";
    } else {
        $sql = "INSERT INTO activity_cards (title, country_name, city_name, unique_code, card_code, city_code, price, category, image, status) VALUES ('$title', '$country', '$city', '$unique_code', '$card_code', '$city_code', $price, '$category', '$image', 'active')";
    }
    
    if ($conn->query($sql)) {
        header("Location: activity_manage.php?success=1");
    } else {
        $error = $conn->error;
    }
}

// Fetch all activities
$result = $conn->query("SELECT * FROM activity_cards ORDER BY id DESC");

include 'includes/header.php';
?>

<style>
    .admin-card { border: none; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.05); }
    .status-dot { height: 10px; width: 10px; border-radius: 50%; display: inline-block; margin-right: 5px; }
    .bg-active { background: #63AB45; }
    .bg-inactive { background: #dc3545; }
    .btn-toggle { cursor: pointer; transition: 0.3s; }
    .btn-toggle:hover { opacity: 0.8; }
</style>

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0">Global Activity Manager</h2>
            <p class="text-muted">Handle worldwide attractions, transfers, and stays.</p>
        </div>
        <button class="btn btn-primary rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#activityModal" onclick="clearForm()">
            <i class="fas fa-plus me-2"></i> Add New Activity
        </button>
    </div>

    <?php if(isset($_GET['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show rounded-pill px-4 mb-4" role="alert">
            Activity saved successfully!
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="card admin-card p-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>Activity</th>
                        <th>Country / City</th>
                        <th>Code</th>
                        <th>Price</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <?php 
                                    $admin_img = !empty($row['image']) ? $row['image'] : 'assets/images/activity-placeholder.png';
                                ?>
                                <img src="<?php echo $admin_img; ?>" class="rounded shadow-sm me-3" style="width: 50px; height: 50px; object-fit: cover;" onerror="this.onerror=null; this.src='assets/images/activity-placeholder.png'">
                                <div>
                                    <h6 class="mb-0 fw-bold"><?php echo $row['title']; ?></h6>
                                    <span class="badge bg-light text-dark small"><?php echo $row['category']; ?></span>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="fw-semibold"><?php echo $row['country_name']; ?></span><br>
                            <small class="text-muted"><?php echo $row['city_name']; ?> (<?php echo $row['city_code']; ?>)</small>
                        </td>
                        <td>
                            <div class="small mb-1">
                                <span class="text-muted">City:</span> <strong><?php echo $row['city_code']; ?></strong>
                            </div>
                            <div class="small mb-1">
                                <span class="text-muted">Card:</span> <strong><?php echo $row['card_code']; ?></strong>
                            </div>
                            <code class="text-primary fw-bold" style="font-size: 0.8rem;"><?php echo $row['unique_code']; ?></code>
                        </td>
                        <td class="fw-bold text-success">₹<?php echo number_format($row['price']); ?></td>
                        <td>
                            <span class="btn-toggle" onclick="toggleStatus(<?php echo $row['id']; ?>, '<?php echo $row['status']; ?>')">
                                <span class="status-dot <?php echo $row['status'] === 'active' ? 'bg-active' : 'bg-inactive'; ?>"></span>
                                <?php echo ucfirst($row['status']); ?>
                            </span>
                        </td>
                        <td>
                            <button class="btn btn-sm btn-outline-primary me-2" onclick="editActivity(<?php echo htmlspecialchars(json_encode($row)); ?>)">
                                <i class="fas fa-edit"></i>
                            </button>
                            <a href="?delete=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Permanently delete this activity?')">
                                <i class="fas fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add/Edit Modal -->
<div class="modal fade" id="activityModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 rounded-4 shadow">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold" id="modalTitle">Add New Activity</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="" method="POST">
                <div class="modal-body p-4">
                    <input type="hidden" name="id" id="activity_id">
                    <input type="hidden" name="save_activity" value="1">
                    
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Activity Title</label>
                            <input type="text" name="title" id="title" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Country Name</label>
                            <input type="text" name="country_name" id="country_name" class="form-control" placeholder="e.g. Singapore" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">City Name</label>
                            <input type="text" name="city_name" id="city_name" class="form-control" placeholder="e.g. Sentosa" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">City Code</label>
                            <input type="text" name="city_code" id="city_code" class="form-control" placeholder="SNC" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Card Code</label>
                            <input type="text" name="card_code" id="card_code" class="form-control" placeholder="MB" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Unique Code</label>
                            <input type="text" name="unique_code" id="unique_code" class="form-control" placeholder="SGSNCMB" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Price (₹)</label>
                            <input type="number" step="0.01" name="price" id="price" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Category</label>
                            <select name="category" id="category" class="form-select">
                                <option value="Attraction">Attraction</option>
                                <option value="Transport">Transport</option>
                                <option value="Stay">Stay Extension</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Image URL</label>
                            <input type="text" name="image" id="image" class="form-control" placeholder="assets/images/...">
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0 p-4">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">Save Activity</button>
                </div>
            </form>
        </div>
    </div>
</div>


<script>
function toggleStatus(id, current) {
    const next = current === 'active' ? 'inactive' : 'active';
    $.post('activity_manage.php', { toggle_status: 1, id: id, new_status: next }, function(res) {
        location.reload();
    });
}

function clearForm() {
    document.getElementById('activity_id').value = '';
    document.getElementById('title').value = '';
    document.getElementById('country_name').value = 'Singapore';
    document.getElementById('city_name').value = '';
    document.getElementById('city_code').value = '';
    document.getElementById('card_code').value = '';
    document.getElementById('unique_code').value = '';
    document.getElementById('price').value = '';
    document.getElementById('image').value = '';
    document.getElementById('modalTitle').innerText = 'Add New Activity';
}

function editActivity(data) {
    document.getElementById('activity_id').value = data.id;
    document.getElementById('title').value = data.title;
    document.getElementById('country_name').value = data.country_name;
    document.getElementById('city_name').value = data.city_name;
    document.getElementById('city_code').value = data.city_code;
    document.getElementById('card_code').value = data.card_code;
    document.getElementById('unique_code').value = data.unique_code;
    document.getElementById('price').value = data.price;
    document.getElementById('category').value = data.category;
    document.getElementById('image').value = data.image;
    document.getElementById('modalTitle').innerText = 'Edit Activity';
    new bootstrap.Modal(document.getElementById('activityModal')).show();
}
</script>
<?php include 'includes/footer.php'; ?>
