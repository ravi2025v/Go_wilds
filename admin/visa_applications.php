<?php
include 'includes/db.php';
include 'includes/header.php';

// Handle Status Update
if (isset($_POST['update_status'])) {
    $id = $_POST['app_id'];
    $status = $_POST['status'];
    $conn->query("UPDATE visa_applications SET status='$status' WHERE id=$id");
    $msg = "Application status updated successfully!";
}

// Handle Delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM visa_applications WHERE id=$id");
    header("Location: visa_applications.php");
    exit();
}

$applications = $conn->query("SELECT va.*, vc.country_name, vs.visa_name 
                              FROM visa_applications va 
                              JOIN visa_countries vc ON va.country_id = vc.id 
                              JOIN visa_services vs ON va.service_id = vs.id 
                              ORDER BY va.created_at DESC");
?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3><i class="fas fa-file-invoice me-2"></i> Visa Applications</h3>
        <span class="badge bg-primary px-3 py-2"><?php echo $applications->num_rows; ?> Total Submissions</span>
    </div>

    <?php if (isset($msg)): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo $msg; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Ref ID</th>
                            <th>Applicant</th>
                            <th>Service</th>
                            <th>Passport No</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $applications->fetch_assoc()): ?>
                            <tr>
                                <td>#VSA-<?php echo str_pad($row['id'], 5, '0', STR_PAD_LEFT); ?></td>
                                <td>
                                    <div class="fw-bold"><?php echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?></div>
                                    <div class="small text-muted"><?php echo htmlspecialchars($row['email']); ?></div>
                                    <div class="small text-muted"><?php echo htmlspecialchars($row['phone']); ?></div>
                                </td>
                                <td>
                                    <div class="fw-bold text-info"><?php echo htmlspecialchars($row['country_name']); ?></div>
                                    <div class="small"><?php echo htmlspecialchars($row['visa_name']); ?></div>
                                </td>
                                <td><code><?php echo htmlspecialchars($row['passport_no']); ?></code></td>
                                <td>
                                    <?php
                                    $status_class = [
                                        'pending' => 'bg-warning text-dark',
                                        'review' => 'bg-info text-white',
                                        'approved' => 'bg-success text-white',
                                        'rejected' => 'bg-danger text-white'
                                    ];
                                    ?>
                                    <span class="badge <?php echo $status_class[$row['status']]; ?>">
                                        <?php echo ucfirst($row['status']); ?>
                                    </span>
                                </td>
                                <td><?php echo date('d M Y, h:i A', strtotime($row['created_at'])); ?></td>
                                <td class="text-end">
                                    <button class="btn btn-sm btn-outline-primary" onclick='viewApp(<?php echo json_encode($row); ?>)'>
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <a href="?delete=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-danger" 
                                       onclick="return confirm('Are you sure you want to delete this application?')">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                        <?php if($applications->num_rows == 0): ?>
                            <tr><td colspan="7" class="text-center py-5 text-muted">No applications received yet.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- View Modal -->
<div class="modal fade" id="appModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Application Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="appDetails">
                <!-- Details injected by JS -->
            </div>
            <div class="modal-footer">
                <form method="POST" class="d-flex align-items-center w-100">
                    <input type="hidden" name="app_id" id="app_id_update">
                    <div class="me-auto">
                        <label class="me-2">Update Status:</label>
                        <select name="status" id="status_update" class="form-select d-inline-block w-auto">
                            <option value="pending">Pending</option>
                            <option value="review">Review</option>
                            <option value="approved">Approved</option>
                            <option value="rejected">Rejected</option>
                        </select>
                        <button type="submit" name="update_status" class="btn btn-primary ms-2">Update</button>
                    </div>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function viewApp(data) {
    document.getElementById('app_id_update').value = data.id;
    document.getElementById('status_update').value = data.status;
    
    let details = `
        <div class="row g-3">
            <div class="col-md-6">
                <label class="text-muted small">Applicant Name</label>
                <p class="fw-bold">${data.first_name} ${data.last_name}</p>
            </div>
            <div class="col-md-6">
                <label class="text-muted small">Passport Number</label>
                <p class="fw-bold">${data.passport_no}</p>
            </div>
            <div class="col-md-6">
                <label class="text-muted small">Email Address</label>
                <p>${data.email}</p>
            </div>
            <div class="col-md-6">
                <label class="text-muted small">Phone Number</label>
                <p>${data.phone}</p>
            </div>
            <div class="col-md-4">
                <label class="text-muted small">Gender</label>
                <p>${data.gender}</p>
            </div>
            <div class="col-md-4">
                <label class="text-muted small">DOB</label>
                <p>${data.dob}</p>
            </div>
            <div class="col-md-4">
                <label class="text-muted small">Place of Issue</label>
                <p>${data.place_issue}</p>
            </div>
            <div class="col-md-12"><hr></div>
            <div class="col-md-6">
                <label class="text-muted small">Destination</label>
                <p class="text-info fw-bold">${data.country_name}</p>
            </div>
            <div class="col-md-6">
                <label class="text-muted small">Visa Type</label>
                <p class="fw-bold">${data.visa_name}</p>
            </div>
            <div class="col-md-12 mt-4">
                <h6 class="mb-3">Uploaded Documents</h6>
                <div class="d-flex gap-2">
                    <div class="p-3 border rounded text-center" style="width: 150px;">
                        <i class="fas fa-file-image fs-1 text-primary mb-2"></i><br>
                        <span class="small">Passport Copy</span><br>
                        <button class="btn btn-sm btn-link mt-1">View File</button>
                    </div>
                    <div class="p-3 border rounded text-center" style="width: 150px;">
                        <i class="fas fa-user fs-1 text-success mb-2"></i><br>
                        <span class="small">Profile Photo</span><br>
                        <button class="btn btn-sm btn-link mt-1">View File</button>
                    </div>
                </div>
            </div>
        </div>
    `;
    document.getElementById('appDetails').innerHTML = details;
    var myModal = new bootstrap.Modal(document.getElementById('appModal'));
    myModal.show();
}
</script>

<?php include 'includes/footer.php'; ?>
