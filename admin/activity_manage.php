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

// Handle Delete (Archive)
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM activity_cards WHERE id = $id");
    header("Location: activity_manage.php?deleted=1");
    exit;
}

// Handle CSV Bulk Import (Robust Version)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['bulk_import'])) {
    if (isset($_FILES['csv_file']) && $_FILES['csv_file']['error'] === 0) {
        $file = $_FILES['csv_file']['tmp_name'];
        $handle = fopen($file, "r");
        $count = 0;
        
        $def_country = $conn->real_escape_string($_POST['def_country']);
        $def_city = $conn->real_escape_string($_POST['def_city']);
        $def_category = $conn->real_escape_string($_POST['def_category']);
        
        // Skip header row
        fgetcsv($handle);
        
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            // Check if title is not empty to avoid ghost rows
            if (!empty($data[1])) {
                $title = $conn->real_escape_string($data[1]);
                $city_code = $conn->real_escape_string($data[2]);
                $card_code = $conn->real_escape_string($data[3]);
                $unique_code = $conn->real_escape_string($data[4]);
                $price = floatval(str_replace(',', '', $data[5]));
                
                $conn->query("INSERT INTO activity_cards (title, country_name, city_name, city_code, card_code, unique_code, price, category, status) 
                             VALUES ('$title', '$def_country', '$def_city', '$city_code', '$card_code', '$unique_code', $price, '$def_category', 'active')");
                $count++;
            }
        }
        fclose($handle);
        header("Location: activity_manage.php?f_country=" . urlencode($def_country) . "&success=bulk&count=$count");
        exit;
    }
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
    
    // Image Handling
    $image = $conn->real_escape_string($_POST['existing_image']); 
    if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] === 0) {
        $target_dir = "../uploads/activities/";
        if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);
        
        $file_ext = pathinfo($_FILES['image_file']['name'], PATHINFO_EXTENSION);
        $new_filename = $unique_code . "_" . time() . "." . $file_ext;
        $target_file = $target_dir . $new_filename;
        
        if (move_uploaded_file($_FILES['image_file']['tmp_name'], $target_file)) {
            $image = "uploads/activities/" . $new_filename;
        }
    }

    if ($id > 0) {
        $sql = "UPDATE activity_cards SET title='$title', country_name='$country', city_name='$city', unique_code='$unique_code', card_code='$card_code', city_code='$city_code', price=$price, category='$category', image='$image' WHERE id=$id";
    } else {
        $sql = "INSERT INTO activity_cards (title, country_name, city_name, unique_code, card_code, city_code, price, category, image, status) VALUES ('$title', '$country', '$city', '$unique_code', '$card_code', '$city_code', $price, '$category', '$image', 'active')";
    }
    
    if ($conn->query($sql)) {
        header("Location: activity_manage.php?f_country=" . urlencode($country) . "&success=1");
    } else {
        $error = $conn->error;
    }
}

// Filters for multi-country management
$f_country = isset($_GET['f_country']) ? $conn->real_escape_string($_GET['f_country']) : '';

// Fetch activities
$query = "SELECT * FROM activity_cards WHERE 1=1";
if(!empty($f_country)) $query .= " AND country_name = '$f_country'";
$query .= " ORDER BY id DESC";
$result = $conn->query($query);

// Fetch lists for datalists
$countries_list = $conn->query("SELECT DISTINCT country_name FROM activity_cards ORDER BY country_name");
$cities_list = $conn->query("SELECT DISTINCT city_name FROM activity_cards ORDER BY city_name");

include 'includes/header.php';
?>

<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">

<style>
    :root { --brand-dark: #1C231F; --brand-secondary: #63AB45; --border-color: #ddd; }
    body { font-family: 'Outfit', sans-serif; background: #fff; color: #333; }
    .admin-container { max-width: 1400px; margin: 0 auto; padding: 40px 20px; }
    .page-header { border-bottom: 2px solid var(--brand-secondary); padding-bottom: 20px; margin-bottom: 30px; }
    .admin-card { border: 1px solid var(--border-color); border-radius: 4px; background: white; }
    .table thead th { background: var(--brand-dark); color: white; text-transform: uppercase; font-size: 0.7rem; letter-spacing: 1px; padding: 15px; border: none; }
    .table td { padding: 15px; border-bottom: 1px solid #eee; font-size: 0.9rem; }
    .btn-flat { border-radius: 2px; font-weight: 600; padding: 8px 18px; text-transform: uppercase; font-size: 0.75rem; transition: 0.2s; }
    .btn-brand { background: var(--brand-secondary); color: white; border: 1px solid var(--brand-secondary); }
    .status-box { font-size: 0.65rem; font-weight: 800; text-transform: uppercase; padding: 4px 8px; border: 1px solid var(--border-color); border-radius: 2px; cursor: pointer; }
    .status-active { border-color: #2e7d32; color: #2e7d32; }
    .status-inactive { border-color: #c62828; color: #c62828; }
    .filter-btn { border: 1px solid var(--border-color); padding: 5px 12px; font-size: 0.75rem; font-weight: 600; color: #666; text-decoration: none; margin-right: 5px; border-radius: 2px; }
    .filter-btn.active { background: var(--brand-dark); border-color: var(--brand-dark); color: white; }
</style>

<div class="admin-container">
    <div class="page-header d-flex justify-content-between align-items-end">
        <div>
            <h1 class="fw-bold mb-0">Global Activity Console</h1>
            <p class="text-muted mb-0">Manage worldwide attractions and logistics ledger.</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-flat border" data-bs-toggle="modal" data-bs-target="#bulkModal">
                <i class="fas fa-file-import me-2"></i> Import CSV
            </button>
            <button class="btn btn-flat btn-brand" data-bs-toggle="modal" data-bs-target="#activityModal" onclick="clearForm()">
                + ADD ACTIVITY
            </button>
        </div>
    </div>

    <!-- Multi-Country Filter -->
    <div class="mb-4 d-flex align-items-center flex-wrap">
        <span class="small fw-bold text-muted text-uppercase me-3">Filter Region:</span>
        <a href="activity_manage.php" class="filter-btn <?php echo empty($f_country) ? 'active' : ''; ?>">All Countries</a>
        <?php $countries_list->data_seek(0); while($c = $countries_list->fetch_assoc()): ?>
            <a href="?f_country=<?php echo urlencode($c['country_name']); ?>" class="filter-btn <?php echo $f_country === $c['country_name'] ? 'active' : ''; ?>">
                <?php echo $c['country_name']; ?>
            </a>
        <?php endwhile; ?>
    </div>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show rounded-0 px-4 mb-4 border-0 border-start border-4 border-success" role="alert">
            <?php 
                if($_GET['success'] == 'bulk') echo "Successfully imported " . intval($_GET['count']) . " activities!";
                else echo "Activity saved successfully!";
            ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="admin-card">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th width="30%">Activity Name</th>
                        <th>Location</th>
                        <th>Codes (City/Card/Unique)</th>
                        <th>Valuation</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if($result->num_rows > 0): ?>
                    <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <?php 
                                    $img = !empty($row['image']) ? $row['image'] : 'assets/images/activity-placeholder.png'; 
                                    if(strpos($img, 'uploads/') === 0) $img = '../' . $img;
                                ?>
                                <img src="<?php echo $img; ?>" class="me-3" style="width: 50px; height: 50px; object-fit: cover; border: 1px solid #eee;" onerror="this.onerror=null; this.src='../assets/images/activity-placeholder.png'">
                                <div>
                                    <div class="fw-bold"><?php echo $row['title']; ?></div>
                                    <div class="small text-muted text-uppercase" style="font-size: 0.6rem;"><?php echo $row['category']; ?></div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="fw-bold"><?php echo $row['country_name']; ?></div>
                            <div class="small text-muted"><?php echo $row['city_name']; ?> (<?php echo $row['city_code']; ?>)</div>
                        </td>
                        <td>
                            <div class="small mb-1"><span class="text-muted">C:</span> <?php echo $row['city_code']; ?> | <span class="text-muted">Cr:</span> <?php echo $row['card_code']; ?></div>
                            <code class="fw-bold text-primary small">UID: <?php echo $row['unique_code']; ?></code>
                        </td>
                        <td>
                            <div class="fw-bold fs-6">₹<?php echo number_format($row['price']); ?></div>
                        </td>
                        <td>
                            <span class="status-box <?php echo $row['status'] === 'active' ? 'status-active' : 'status-inactive'; ?>" 
                                  onclick="toggleStatus(<?php echo $row['id']; ?>, '<?php echo $row['status']; ?>')">
                                <?php echo $row['status']; ?>
                            </span>
                        </td>
                        <td class="text-end">
                            <button class="btn btn-sm btn-light border" onclick='editActivity(<?php echo json_encode($row, JSON_HEX_APOS); ?>)'>
                                <i class="fas fa-edit small"></i>
                            </button>
                            <a href="?delete=<?php echo $row['id']; ?>" class="btn btn-sm btn-light border ms-1" onclick="return confirm('Archiving will hide it from frontend. Continue?')">
                                <i class="fas fa-trash small text-danger"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                    <?php else: ?>
                    <tr><td colspan="6" class="text-center py-5">No activities found in <strong><?php echo empty($f_country) ? 'All Regions' : $f_country; ?></strong>.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add/Edit Modal -->
<div class="modal fade" id="activityModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content shadow-none rounded-1 border-0">
            <div class="modal-header bg-dark text-white rounded-0 border-0">
                <h5 class="modal-title fw-bold" id="modalTitle">ACTIVITY LOG SPECIFICATION</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="" method="POST" enctype="multipart/form-data">
                <div class="modal-body p-4 bg-white">
                    <input type="hidden" name="id" id="activity_id">
                    <input type="hidden" name="save_activity" value="1">
                    <input type="hidden" name="existing_image" id="existing_image">
                    
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-bold text-uppercase small">Full Title</label>
                            <input type="text" name="title" id="title" class="form-control rounded-0" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-uppercase small">Country</label>
                            <input type="text" name="country_name" id="country_name" class="form-control rounded-0" value="<?php echo $f_country; ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-uppercase small">City Name</label>
                            <input type="text" name="city_name" id="city_name" class="form-control rounded-0" value="<?php echo $f_country; ?>" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold text-uppercase small">City Code (e.g. SIN)</label>
                            <input type="text" name="city_code" id="city_code" class="form-control rounded-0" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold text-uppercase small">Card Code (e.g MB)</label>
                            <input type="text" name="card_code" id="card_code" class="form-control rounded-0" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold text-uppercase small">Unique Code</label>
                            <input type="text" name="unique_code" id="unique_code" class="form-control rounded-0" placeholder="e.g SGSINMB" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold text-uppercase small">Price (₹)</label>
                            <input type="number" step="0.01" name="price" id="price" class="form-control rounded-0" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold text-uppercase small">Category</label>
                            <select name="category" id="category" class="form-select rounded-0">
                                <option value="Attraction">Attraction</option>
                                <option value="Transport">Transport</option>
                                <option value="Stay">Stay Enhancement</option>
                                <option value="Meal">Meal/Dining</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold text-uppercase small">Upload Photo</label>
                            <input type="file" name="image_file" class="form-control rounded-0" accept="image/*">
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-white border-0 pt-0 p-4">
                    <button type="button" class="btn btn-light border rounded-0 py-2 px-4 fw-bold small" data-bs-dismiss="modal">DISCARD</button>
                    <button type="submit" class="btn btn-dark rounded-0 py-2 px-4 fw-bold small">SAVE ACTIVITY</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Bulk Import Modal (Optimized for your Excel Format) -->
<div class="modal fade" id="bulkModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-none border-0 overflow-hidden" style="border-radius: 8px;">
            <div class="modal-header bg-dark text-white border-0">
                <h5 class="modal-title fw-bold">IMPORT FROM EXCEL (CSV)</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="" method="POST" enctype="multipart/form-data">
                <div class="modal-body p-4 bg-white">
                    <div class="bg-primary bg-opacity-10 p-3 mb-4 rounded border border-primary border-opacity-25">
                        <p class="small mb-0 text-primary fw-bold"><i class="fas fa-globe me-2"></i> STEP 1: SELECT DESTINATION</p>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-uppercase small">Destination Country</label>
                            <input type="text" name="def_country" class="form-control border-primary" value="<?php echo !empty($f_country) ? $f_country : 'Singapore'; ?>" placeholder="e.g. Singapore" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-uppercase small">City / Region</label>
                            <input type="text" name="def_city" class="form-control border-primary" value="<?php echo !empty($f_country) ? $f_country : 'Singapore'; ?>" placeholder="e.g. Singapore City" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold text-uppercase small">Default Category</label>
                            <select name="def_category" class="form-select border-primary">
                                <option value="Attraction">Tourist Attractions</option>
                                <option value="Transport">Transfers & Transport</option>
                                <option value="Stay">Stay Enhancement</option>
                            </select>
                        </div>
                    </div>

                    <div class="bg-dark bg-opacity-10 p-3 mb-3 rounded">
                        <p class="small mb-0 fw-bold"><i class="fas fa-file-csv me-2"></i> STEP 2: UPLOAD CSV FILE</p>
                    </div>

                    <div class="mb-3">
                        <input type="file" name="csv_file" class="form-control rounded-0" accept=".csv" required>
                    </div>

                    <div class="bg-light p-3 small border border-dashed text-muted">
                        <strong>Expected Columns:</strong><br>
                        S.no | Title | CityCode | CardCode | UniqueCode | Price
                    </div>
                    
                    <input type="hidden" name="bulk_import" value="1">
                </div>
                <div class="modal-footer bg-light border-0 p-4">
                    <button type="button" class="btn btn-outline-secondary border-0 fw-bold small" data-bs-dismiss="modal">CANCEL</button>
                    <button type="submit" class="btn btn-primary px-5 fw-bold small">START IMPORT NOW</button>
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
    document.getElementById('country_name').value = '<?php echo $f_country; ?>';
    document.getElementById('city_name').value = '<?php echo $f_country; ?>';
    document.getElementById('city_code').value = '';
    document.getElementById('card_code').value = '';
    document.getElementById('unique_code').value = '';
    document.getElementById('price').value = '';
    document.getElementById('existing_image').value = '';
    document.getElementById('modalTitle').innerText = 'ADD NEW ACTIVITY LOG';
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
    document.getElementById('existing_image').value = data.image;
    document.getElementById('modalTitle').innerText = 'UPDATE LOG ENTRY';
    new bootstrap.Modal(document.getElementById('activityModal')).show();
}
</script>
<?php include 'includes/footer.php'; ?>