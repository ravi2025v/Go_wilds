<?php
require_once 'includes/db.php';

// Check if description column exists
$check_desc = $conn->query("SHOW COLUMNS FROM tours LIKE 'description'");
if ($check_desc->num_rows == 0) {
    $conn->query("ALTER TABLE tours ADD COLUMN description TEXT AFTER duration");
}

// Check if more_info column exists
$check_info = $conn->query("SHOW COLUMNS FROM tours LIKE 'more_info'");
if ($check_info->num_rows == 0) {
    $conn->query("ALTER TABLE tours ADD COLUMN more_info TEXT AFTER description");
}

// Check for hotel price columns
$hotel_cols = ['price_3star', 'price_4star', 'price_5star', 'price_camps', 'price_homestay'];
foreach($hotel_cols as $col) {
    if ($conn->query("SHOW COLUMNS FROM tours LIKE '$col'")->num_rows == 0) {
        $conn->query("ALTER TABLE tours ADD COLUMN $col DECIMAL(10,2) DEFAULT 0.00");
    }
}

// Check for missing info columns
$info_cols = ['min_people', 'max_people', 'min_age', 'language', 'tour_type'];
foreach($info_cols as $col) {
    if ($conn->query("SHOW COLUMNS FROM tours LIKE '$col'")->num_rows == 0) {
        $conn->query("ALTER TABLE tours ADD COLUMN $col VARCHAR(255) DEFAULT ''");
    }
}

// Create gallery table if not exists
$conn->query("CREATE TABLE IF NOT EXISTS tour_gallery (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tour_id INT NOT NULL,
    image VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

// Handle Delete Image from Gallery (AJAX)
if (isset($_GET['delete_gallery_image'])) {
    $img_id = intval($_GET['delete_gallery_image']);
    $conn->query("DELETE FROM tour_gallery WHERE id = $img_id");
    echo json_encode(['status' => 'success']);
    exit;
}

// Handle Fetch Gallery for Edit (AJAX)
if (isset($_GET['fetch_gallery'])) {
    $tid = intval($_GET['fetch_gallery']);
    $res = $conn->query("SELECT * FROM tour_gallery WHERE tour_id = $tid");
    $imgs = [];
    while($r = $res->fetch_assoc()) $imgs[] = $r;
    echo json_encode($imgs);
    exit;
}

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
    $description = $conn->real_escape_string($_POST['description']);
    $more_info = $conn->real_escape_string($_POST['more_info']);
    
    // Hotel Prices
    $p3 = floatval($_POST['price_3star']);
    $p4 = floatval($_POST['price_4star']);
    $p5 = floatval($_POST['price_5star']);
    $pc = floatval($_POST['price_camps']);
    $ph = floatval($_POST['price_homestay']);
    
    // Info Widget Fields
    $min_p = $conn->real_escape_string($_POST['min_people']);
    $max_p = $conn->real_escape_string($_POST['max_people']);
    $min_a = $conn->real_escape_string($_POST['min_age']);
    $lang = $conn->real_escape_string($_POST['language']);
    $type = $conn->real_escape_string($_POST['tour_type']);
    
    // Handle Image Upload
    $image = $conn->real_escape_string($_POST['existing_image'] ?? 'assets/images/tour.jpg');
    if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] === 0) {
        $target_dir = "../assets/images/tours/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $file_ext = pathinfo($_FILES["image_file"]["name"], PATHINFO_EXTENSION);
        $file_name = time() . "_" . preg_replace("/[^a-zA-Z0-0]/", "_", $title) . "." . $file_ext;
        $target_file = $target_dir . $file_name;
        
        if (move_uploaded_file($_FILES["image_file"]["tmp_name"], $target_file)) {
            $image = "assets/images/tours/" . $file_name;
        }
    } elseif (!empty($_POST['image_path'])) {
        $image = $conn->real_escape_string($_POST['image_path']);
    }

    if ($id > 0) {
        $sql = "UPDATE tours SET title='$title', category='$category', destination='$destination', price=$price, duration='$duration', status='$status', image='$image', description='$description', more_info='$more_info', 
                price_3star=$p3, price_4star=$p4, price_5star=$p5, price_camps=$pc, price_homestay=$ph,
                min_people='$min_p', max_people='$max_p', min_age='$min_a', language='$lang', tour_type='$type' WHERE id=$id";
    } else {
        $sql = "INSERT INTO tours (title, category, destination, price, duration, status, image, description, more_info, price_3star, price_4star, price_5star, price_camps, price_homestay, min_people, max_people, min_age, language, tour_type) 
                VALUES ('$title', '$category', '$destination', $price, '$duration', '$status', '$image', '$description', '$more_info', $p3, $p4, $p5, $pc, $ph, '$min_p', '$max_p', '$min_a', '$lang', '$type')";
    }
    
    if ($conn->query($sql)) {
        $tour_id = ($id > 0) ? $id : $conn->insert_id;
        
        // Handle Gallery Uploads
        if (isset($_FILES['gallery_files'])) {
            $total_files = count($_FILES['gallery_files']['name']);
            for ($i = 0; $i < $total_files; $i++) {
                if ($_FILES['gallery_files']['error'][$i] === 0) {
                    $target_dir = "../assets/images/tours/gallery/";
                    if (!is_dir($target_dir)) {
                        mkdir($target_dir, 0777, true);
                    }
                    $file_ext = pathinfo($_FILES["gallery_files"]["name"][$i], PATHINFO_EXTENSION);
                    $file_name = time() . "_gal_" . $i . "." . $file_ext;
                    $target_file = $target_dir . $file_name;
                    
                    if (move_uploaded_file($_FILES["gallery_files"]["tmp_name"][$i], $target_file)) {
                        $gal_image = "assets/images/tours/gallery/" . $file_name;
                        $conn->query("INSERT INTO tour_gallery (tour_id, image) VALUES ($tour_id, '$gal_image')");
                    }
                }
            }
        }
        
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
                    <td><img src="../<?php echo !empty($row['image']) ? $row['image'] : 'assets/images/tour.jpg'; ?>" width="50" height="50" style="object-fit: cover;" class="rounded" onerror="this.onerror=null; this.src='../assets/images/tour.jpg'"></td>
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
            <form method="POST" enctype="multipart/form-data">
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
                            <label class="form-label text-primary fw-bold">Category</label>
                            <select name="category" id="tourCategory" class="form-select" required>
                                <option value="">Select Category</option>
                                <option value="Domestic Destinations">Domestic Destinations</option>
                                <option value="International Destinations">International Destinations</option>
                                <option value="Hot Destinations">Hot Destinations</option>
                                <option value="Niche Experience Tours">Niche Experience Tours</option>
                                <option value="Pilgrimage">Pilgrimage</option>
                                <option value="Wild Safari">Wild Safari</option>
                                <option value="Trek">Trek</option>
                                <option value="General">General</option>
                                <?php
                                $extra_cat_query = "SELECT DISTINCT category FROM tours WHERE category NOT IN ('Domestic Destinations', 'International Destinations', 'Hot Destinations', 'Niche Experience Tours', 'Pilgrimage', 'Wild Safari', 'Trek', 'General') ORDER BY category";
                                $extra_cat_res = $conn->query($extra_cat_query);
                                while($extra_cat = $extra_cat_res->fetch_assoc()) {
                                    echo "<option value='".htmlspecialchars($extra_cat['category'])."'>".htmlspecialchars($extra_cat['category'])."</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-primary fw-bold">Destination</label>
                            <input list="destList" name="destination" id="tourDestination" class="form-control" placeholder="Select or type destination" required>
                            <datalist id="destList">
                                <?php
                                $dest_query = "SELECT DISTINCT destination FROM tours ORDER BY destination";
                                $dest_res = $conn->query($dest_query);
                                while($dest_row = $dest_res->fetch_assoc()) {
                                    echo "<option value='".htmlspecialchars($dest_row['destination'])."'>";
                                }
                                ?>
                            </datalist>
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
                            <label class="form-label">Glimpse of Tour (Description)</label>
                            <textarea name="description" id="tourDescription" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Important Information (List items)</label>
                            <textarea name="more_info" id="tourImportantInfo" class="form-control" rows="3" placeholder="✓ Item 1&#10;✓ Item 2"></textarea>
                        </div>
                        <div class="col-md-12 py-2 border-bottom mb-2">
                            <h6 class="text-primary mb-0">Tour Information (Sidebar details)</h6>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-dark fw-bold">Min Guests</label>
                            <input type="text" name="min_people" id="tourMinPeople" class="form-control" placeholder="e.g. 1">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-dark fw-bold">Max Guests</label>
                            <input type="text" name="max_people" id="tourMaxPeople" class="form-control" placeholder="e.g. 25">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-dark fw-bold">Minimum Age</label>
                            <input type="text" name="min_age" id="tourMinAge" class="form-control" placeholder="e.g. 12+">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-dark fw-bold">Tour Language</label>
                            <input type="text" name="language" id="tourLanguage" class="form-control" placeholder="e.g. English">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-dark fw-bold">Tour Type</label>
                            <input type="text" name="tour_type" id="tourType" class="form-control" placeholder="e.g. City Tour">
                        </div>
                        <div class="col-md-12 py-2 border-bottom mb-2">
                            <h6 class="text-primary mb-0">Hotel Upgrade Prices (Extra per person)</h6>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-success">3* Hotel (+₹)</label>
                            <input type="number" step="0.01" name="price_3star" id="tourPrice3" class="form-control" value="0.00">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-success">4* Hotel (+₹)</label>
                            <input type="number" step="0.01" name="price_4star" id="tourPrice4" class="form-control" value="0.00">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-success">5* Hotel (+₹)</label>
                            <input type="number" step="0.01" name="price_5star" id="tourPrice5" class="form-control" value="0.00">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-success">Camps (+₹)</label>
                            <input type="number" step="0.01" name="price_camps" id="tourPriceCamps" class="form-control" value="0.00">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-success">Homestay (+₹)</label>
                            <input type="number" step="0.01" name="price_homestay" id="tourPriceHomestay" class="form-control" value="0.00">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Tour Main Image</label>
                            <div class="input-group">
                                <input type="file" name="image_file" id="tourImageFile" class="form-control" accept="image/*">
                            </div>
                            <div class="mt-2">
                                <small class="text-muted">Or enter URL/Path:</small>
                                <input type="text" name="image_path" id="tourImagePath" class="form-control form-control-sm" placeholder="assets/images/...">
                                <input type="hidden" name="existing_image" id="tourExistingImage">
                            </div>
                        </div>
                        <div class="col-md-12 border-top pt-3">
                            <label class="form-label fw-bold">Tour Gallery Images (Select Multiple)</label>
                            <input type="file" name="gallery_files[]" class="form-control" multiple accept="image/*">
                            <div id="galleryManagement" class="mt-3 d-flex flex-wrap gap-2">
                                <!-- Gallery images will be loaded here via JS -->
                            </div>
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
    document.getElementById('tourDescription').value = '';
    document.getElementById('tourImportantInfo').value = '';
    document.getElementById('tourMinPeople').value = '';
    document.getElementById('tourMaxPeople').value = '';
    document.getElementById('tourMinAge').value = '';
    document.getElementById('tourLanguage').value = '';
    document.getElementById('tourType').value = '';
    document.getElementById('tourPrice3').value = '0.00';
    document.getElementById('tourPrice4').value = '0.00';
    document.getElementById('tourPrice5').value = '0.00';
    document.getElementById('tourPriceCamps').value = '0.00';
    document.getElementById('tourPriceHomestay').value = '0.00';
    document.getElementById('tourImagePath').value = '';
    document.getElementById('tourExistingImage').value = '';
    document.getElementById('tourModalTitle').innerText = 'Add Tour';
}
function editTour(data) {
    document.getElementById('tourId').value = data.id;
    document.getElementById('tourTitle').value = data.title;
    document.getElementById('tourCategory').value = data.category;
    document.getElementById('tourDestination').value = data.destination;
    document.getElementById('tourPrice').value = data.price;
    document.getElementById('tourDuration').value = data.duration;
    document.getElementById('tourDescription').value = data.description || '';
    document.getElementById('tourImportantInfo').value = data.more_info || '';
    document.getElementById('tourMinPeople').value = data.min_people || '';
    document.getElementById('tourMaxPeople').value = data.max_people || '';
    document.getElementById('tourMinAge').value = data.min_age || '';
    document.getElementById('tourLanguage').value = data.language || '';
    document.getElementById('tourType').value = data.tour_type || '';
    document.getElementById('tourPrice3').value = data.price_3star || '0.00';
    document.getElementById('tourPrice4').value = data.price_4star || '0.00';
    document.getElementById('tourPrice5').value = data.price_5star || '0.00';
    document.getElementById('tourPriceCamps').value = data.price_camps || '0.00';
    document.getElementById('tourPriceHomestay').value = data.price_homestay || '0.00';
    document.getElementById('tourImagePath').value = data.image;
    document.getElementById('tourExistingImage').value = data.image;
    document.getElementById('tourStatus').value = data.status;
    document.getElementById('tourModalTitle').innerText = 'Edit Tour';
    
    // Load Gallery
    const galleryDiv = document.getElementById('galleryManagement');
    galleryDiv.innerHTML = '<small class="text-muted">Loading gallery...</small>';
    fetch('tours.php?fetch_gallery=' + data.id)
        .then(res => res.json())
        .then(imgs => {
            galleryDiv.innerHTML = '';
            imgs.forEach(img => {
                const div = document.createElement('div');
                div.className = 'position-relative';
                div.style.width = '80px';
                div.style.height = '80px';
                div.innerHTML = `
                    <img src="../${img.image}" class="rounded w-100 h-100" style="object-fit: cover;">
                    <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0 p-1" style="line-height:1; font-size:10px;" onclick="deleteGalleryImage(${img.id}, this.parentElement)">×</button>
                `;
                galleryDiv.appendChild(div);
            });
        });

    new bootstrap.Modal(document.getElementById('tourModal')).show();
}

function deleteGalleryImage(id, element) {
    if(confirm('Remove this image from gallery?')) {
        fetch('tours.php?delete_gallery_image=' + id)
            .then(res => res.json())
            .then(resp => {
                if(resp.status === 'success') {
                    element.remove();
                }
            });
    }
}
</script>

<?php include 'includes/footer.php'; ?>
