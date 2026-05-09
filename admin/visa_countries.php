<?php
include 'includes/db.php';
include 'includes/header.php';

// Handle Add/Edit
if (isset($_POST['save_country'])) {
    $name = $conn->real_escape_string($_POST['country_name']);
    $status = $_POST['status'];
    $id = isset($_POST['country_id']) ? $_POST['country_id'] : '';

    if ($id) {
        $conn->query("UPDATE visa_countries SET country_name='$name', status='$status' WHERE id=$id");
        $msg = "Country updated successfully!";
    } else {
        $conn->query("INSERT INTO visa_countries (country_name, status) VALUES ('$name', '$status')");
        $msg = "Country added successfully!";
    }
}

// Handle Delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM visa_countries WHERE id=$id");
    header("Location: visa_countries.php");
    exit();
}

$countries = $conn->query("SELECT * FROM visa_countries ORDER BY country_name ASC");
?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3><i class="fas fa-globe-asia me-2"></i> Manage Visa Countries</h3>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#countryModal" onclick="resetForm()">
            <i class="fas fa-plus me-1"></i> Add New Country
        </button>
    </div>

    <?php if (isset($msg)): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo $msg; ?>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Country Name</th>
                            <th>Status</th>
                            <th>Created At</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $countries->fetch_assoc()): ?>
                            <tr>
                                <td>#<?php echo $row['id']; ?></td>
                                <td class="fw-bold"><?php echo htmlspecialchars($row['country_name']); ?></td>
                                <td>
                                    <span class="badge <?php echo $row['status'] == 'active' ? 'bg-success' : 'bg-danger'; ?>">
                                        <?php echo ucfirst($row['status']); ?>
                                    </span>
                                </td>
                                <td><?php echo date('d M Y', strtotime($row['created_at'])); ?></td>
                                <td class="text-end">
                                    <button class="btn btn-sm btn-outline-primary me-1" 
                                            onclick='editCountry(<?php echo json_encode($row); ?>)'>
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <a href="?delete=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-danger" 
                                       onclick="return confirm('Are you sure you want to delete this country and all its associated services?')">
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
</div>

<!-- Modal -->
<div class="modal fade" id="countryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Add New Country</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="country_id" id="country_id">
                <div class="mb-3">
                    <label class="form-label">Country Name</label>
                    <input type="text" name="country_name" id="country_name" class="form-control" required placeholder="e.g. United Arab Emirates">
                </div>
                <div class="mb-3">
                    <label class="form-label">Status</label>
                    <select name="status" id="status" class="form-select">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" name="save_country" class="btn btn-primary">Save Country</button>
            </div>
        </form>
    </div>
</div>

<script>
function resetForm() {
    document.getElementById('modalTitle').innerText = 'Add New Country';
    document.getElementById('country_id').value = '';
    document.getElementById('country_name').value = '';
    document.getElementById('status').value = 'active';
}

function editCountry(data) {
    document.getElementById('modalTitle').innerText = 'Edit Country';
    document.getElementById('country_id').value = data.id;
    document.getElementById('country_name').value = data.country_name;
    document.getElementById('status').value = data.status;
    var myModal = new bootstrap.Modal(document.getElementById('countryModal'));
    myModal.show();
}
</script>

<?php include 'includes/footer.php'; ?>
