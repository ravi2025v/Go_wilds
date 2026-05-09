<?php
include 'includes/db.php';
include 'includes/header.php';

// Handle Add/Edit
if (isset($_POST['save_service'])) {
    $country_id = $_POST['country_id'];
    $visa_name = $conn->real_escape_string($_POST['visa_name']);
    $price = $_POST['price'];
    $processing_time = $conn->real_escape_string($_POST['processing_time']);
    $validity = $conn->real_escape_string($_POST['validity']);
    $requirements = $conn->real_escape_string($_POST['requirements']);
    $status = $_POST['status'];
    $id = isset($_POST['service_id']) ? $_POST['service_id'] : '';

    if ($id) {
        $sql = "UPDATE visa_services SET 
                country_id='$country_id', 
                visa_name='$visa_name', 
                price='$price', 
                processing_time='$processing_time', 
                validity='$validity', 
                requirements='$requirements', 
                status='$status' 
                WHERE id=$id";
        $conn->query($sql);
        $msg = "Visa service updated successfully!";
    } else {
        $sql = "INSERT INTO visa_services (country_id, visa_name, price, processing_time, validity, requirements, status) 
                VALUES ('$country_id', '$visa_name', '$price', '$processing_time', '$validity', '$requirements', '$status')";
        $conn->query($sql);
        $msg = "Visa service added successfully!";
    }
}

// Handle Delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM visa_services WHERE id=$id");
    header("Location: visa_services.php");
    exit();
}

$services = $conn->query("SELECT vs.*, vc.country_name 
                          FROM visa_services vs 
                          JOIN visa_countries vc ON vs.country_id = vc.id 
                          ORDER BY vc.country_name ASC, vs.price ASC");

$countries = $conn->query("SELECT * FROM visa_countries WHERE status='active' ORDER BY country_name ASC");
$countries_list = [];
while($c = $countries->fetch_assoc()) $countries_list[] = $c;
?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3><i class="fas fa-passport me-2"></i> Manage Visa Services</h3>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#serviceModal" onclick="resetForm()">
            <i class="fas fa-plus me-1"></i> Add New Visa Service
        </button>
    </div>

    <?php if (isset($msg)): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo $msg; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Country</th>
                            <th>Visa Name</th>
                            <th>Price</th>
                            <th>Processing</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $services->fetch_assoc()): ?>
                            <tr>
                                <td>#<?php echo $row['id']; ?></td>
                                <td><span class="badge bg-info text-dark"><?php echo htmlspecialchars($row['country_name']); ?></span></td>
                                <td class="fw-bold"><?php echo htmlspecialchars($row['visa_name']); ?></td>
                                <td class="text-primary fw-bold">₹<?php echo number_format($row['price'], 2); ?></td>
                                <td><?php echo htmlspecialchars($row['processing_time']); ?></td>
                                <td>
                                    <span class="badge <?php echo $row['status'] == 'active' ? 'bg-success' : 'bg-danger'; ?>">
                                        <?php echo ucfirst($row['status']); ?>
                                    </span>
                                </td>
                                <td class="text-end">
                                    <button class="btn btn-sm btn-outline-primary me-1" 
                                            onclick='editService(<?php echo json_encode($row); ?>)'>
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <a href="?delete=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-danger" 
                                       onclick="return confirm('Are you sure you want to delete this visa service?')">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                        <?php if($services->num_rows == 0): ?>
                            <tr><td colspan="7" class="text-center py-4 text-muted">No visa services found. Add one to get started!</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="serviceModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form method="POST" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Add New Visa Service</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="service_id" id="service_id">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Destination Country</label>
                        <select name="country_id" id="country_id" class="form-select" required>
                            <option value="">Select Country</option>
                            <?php foreach($countries_list as $c): ?>
                                <option value="<?php echo $c['id']; ?>"><?php echo $c['country_name']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Visa Name/Type</label>
                        <input type="text" name="visa_name" id="visa_name" class="form-control" required placeholder="e.g. 30 Days Single Entry Tourist">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Price (INR)</label>
                        <input type="number" step="0.01" name="price" id="price" class="form-control" required placeholder="0.00">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Processing Time</label>
                        <input type="text" name="processing_time" id="processing_time" class="form-control" placeholder="e.g. 3-5 Working Days">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Validity</label>
                        <input type="text" name="validity" id="validity" class="form-control" placeholder="e.g. 58 Days from issue">
                    </div>
                    <div class="col-12 mb-3">
                        <label class="form-label">Requirements Checklist</label>
                        <textarea name="requirements" id="requirements" class="form-control" rows="4" placeholder="Enter each requirement on a new line..."></textarea>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" id="status" class="form-select">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" name="save_service" class="btn btn-primary">Save Visa Service</button>
            </div>
        </form>
    </div>
</div>

<script>
function resetForm() {
    document.getElementById('modalTitle').innerText = 'Add New Visa Service';
    document.getElementById('service_id').value = '';
    document.getElementById('country_id').value = '';
    document.getElementById('visa_name').value = '';
    document.getElementById('price').value = '';
    document.getElementById('processing_time').value = '';
    document.getElementById('validity').value = '';
    document.getElementById('requirements').value = '';
    document.getElementById('status').value = 'active';
}

function editService(data) {
    document.getElementById('modalTitle').innerText = 'Edit Visa Service';
    document.getElementById('service_id').value = data.id;
    document.getElementById('country_id').value = data.country_id;
    document.getElementById('visa_name').value = data.visa_name;
    document.getElementById('price').value = data.price;
    document.getElementById('processing_time').value = data.processing_time;
    document.getElementById('validity').value = data.validity;
    document.getElementById('requirements').value = data.requirements;
    document.getElementById('status').value = data.status;
    var myModal = new bootstrap.Modal(document.getElementById('serviceModal'));
    myModal.show();
}
</script>

<?php include 'includes/footer.php'; ?>
