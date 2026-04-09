<?php
require_once 'includes/db.php';
require_once 'includes/header.php';

// Handle Delete
if(isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM bookings WHERE id = $id");
    echo "<script>window.location.href='bookings.php';</script>";
}

// Handle Add/Edit Form Submission
if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $customer_name = $conn->real_escape_string($_POST['customer_name']);
    $email = $conn->real_escape_string($_POST['email']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $tour_id = intval($_POST['tour_id']);
    $booking_date = $conn->real_escape_string($_POST['booking_date']);
    $status = $conn->real_escape_string($_POST['status']);
    
    if(isset($_POST['id']) && !empty($_POST['id'])) {
        $id = intval($_POST['id']);
        $conn->query("UPDATE bookings SET customer_name='$customer_name', email='$email', phone='$phone', tour_id=$tour_id, booking_date='$booking_date', status='$status' WHERE id=$id");
    } else {
        $conn->query("INSERT INTO bookings (customer_name, email, phone, tour_id, booking_date, status) VALUES ('$customer_name', '$email', '$phone', $tour_id, '$booking_date', '$status')");
    }
    echo "<script>window.location.href='bookings.php';</script>";
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Manage Bookings</h4>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#bookingModal" onclick="resetBookingForm()">
        <i class="fas fa-plus me-1"></i> Add New Booking
    </button>
</div>

<div class="card p-4">
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Customer Name</th>
                    <th>Email / Phone</th>
                    <th>Tour</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $result = $conn->query("SELECT b.*, t.title as tour_name FROM bookings b LEFT JOIN tours t ON b.tour_id = t.id ORDER BY b.id DESC");
                if($result && $result->num_rows > 0):
                    while($row = $result->fetch_assoc()):
                        $badgeClass = 'bg-secondary';
                        if($row['status'] == 'confirmed') $badgeClass = 'bg-success';
                        else if($row['status'] == 'pending') $badgeClass = 'bg-warning text-dark';
                        else if($row['status'] == 'cancelled') $badgeClass = 'bg-danger';
                ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><strong><?php echo $row['customer_name']; ?></strong></td>
                    <td><a href="mailto:<?php echo $row['email']; ?>"><?php echo $row['email']; ?></a><br><small class="text-muted"><i class="fas fa-phone-alt ms-1"></i> <?php echo $row['phone']; ?></small></td>
                    <td><?php echo $row['tour_name'] ?? '<em class="text-muted">Deleted Tour</em>'; ?></td>
                    <td><?php echo $row['booking_date']; ?></td>
                    <td><span class="badge <?php echo $badgeClass; ?>"><?php echo ucfirst($row['status']); ?></span></td>
                    <td>
                        <button class="btn btn-sm btn-info text-white" onclick='editBooking(<?php echo json_encode($row); ?>)'>
                            <i class="fas fa-edit"></i>
                        </button>
                        <a href="bookings.php?delete=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this booking?')">
                            <i class="fas fa-trash"></i>
                        </a>
                    </td>
                </tr>
                <?php 
                    endwhile; 
                else:
                ?>
                <tr><td colspan="7" class="text-center py-4 text-muted">No bookings found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Booking Modal -->
<div class="modal fade" id="bookingModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="bookings.php">
                <div class="modal-header">
                    <h5 class="modal-title" id="bookingModalTitle">Add Booking</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="bookId">
                    <div class="mb-3">
                        <label class="form-label">Customer Name</label>
                        <input type="text" name="customer_name" id="bookName" class="form-control" required placeholder="John Doe">
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" id="bookEmail" class="form-control" required placeholder="john@example.com">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Phone</label>
                            <input type="text" name="phone" id="bookPhone" class="form-control" placeholder="+1 234 567 8900">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tour Package</label>
                        <select name="tour_id" id="bookTour" class="form-select" required>
                            <option value="">Select Tour</option>
                            <?php
                            $tours = $conn->query("SELECT id, title FROM tours WHERE status='active'");
                            if($tours) {
                                while($tour = $tours->fetch_assoc()) {
                                    echo "<option value='{$tour['id']}'>{$tour['title']}</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Booking Date</label>
                            <input type="date" name="booking_date" id="bookDate" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Status</label>
                            <select name="status" id="bookStatus" class="form-select">
                                <option value="pending">Pending</option>
                                <option value="confirmed">Confirmed</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Booking</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function resetBookingForm() {
    document.getElementById('bookId').value = '';
    document.getElementById('bookName').value = '';
    document.getElementById('bookEmail').value = '';
    document.getElementById('bookPhone').value = '';
    document.getElementById('bookTour').value = '';
    document.getElementById('bookDate').value = '';
    document.getElementById('bookStatus').value = 'pending';
    document.getElementById('bookingModalTitle').innerText = 'Add Booking';
}

function editBooking(data) {
    document.getElementById('bookId').value = data.id;
    document.getElementById('bookName').value = data.customer_name;
    document.getElementById('bookEmail').value = data.email;
    document.getElementById('bookPhone').value = data.phone;
    document.getElementById('bookTour').value = data.tour_id;
    document.getElementById('bookDate').value = data.booking_date;
    document.getElementById('bookStatus').value = data.status;
    document.getElementById('bookingModalTitle').innerText = 'Edit Booking';
    
    var bookingModal = new bootstrap.Modal(document.getElementById('bookingModal'));
    bookingModal.show();
}
</script>

<?php require_once 'includes/footer.php'; ?>
