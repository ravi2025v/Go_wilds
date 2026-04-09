<?php
require_once 'includes/db.php';
require_once 'includes/header.php';

// Handle Delete
if(isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM users WHERE id = $id");
    echo "<script>window.location.href='users.php';</script>";
}

// Handle Search
$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';
$where_clause = "";
if(!empty($search)) {
    $where_clause = " WHERE name LIKE '%$search%' OR email LIKE '%$search%' OR phone LIKE '%$search%'";
}

// Handle Add/Edit
if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $role = $conn->real_escape_string($_POST['role']);
    
    if(isset($_POST['id']) && !empty($_POST['id'])) {
        $id = intval($_POST['id']);
        if(!empty($_POST['password'])) {
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $conn->query("UPDATE users SET name='$name', email='$email', phone='$phone', role='$role', password='$password' WHERE id=$id");
        } else {
            $conn->query("UPDATE users SET name='$name', email='$email', phone='$phone', role='$role' WHERE id=$id");
        }
    } else {
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $conn->query("INSERT INTO users (name, email, phone, password, role) VALUES ('$name', '$email', '$phone', '$password', '$role')");
    }
    echo "<script>window.location.href='users.php';</script>";
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Manage Users</h4>
    <div class="d-flex gap-2">
        <form method="GET" action="users.php" class="d-flex gap-2">
            <input type="text" name="search" class="form-control" placeholder="Search by name, email, phone..." value="<?php echo htmlspecialchars($search); ?>">
            <button type="submit" class="btn btn-secondary"><i class="fas fa-search"></i></button>
            <?php if(!empty($search)): ?>
                <a href="users.php" class="btn btn-outline-danger"><i class="fas fa-times"></i></a>
            <?php endif; ?>
        </form>
        <button class="btn btn-primary text-nowrap" data-bs-toggle="modal" data-bs-target="#userModal" onclick="resetUserForm()">
            <i class="fas fa-user-plus me-1"></i> Add New User
        </button>
    </div>
</div>

<div class="card p-4">
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Mobile</th>
                    <th>Role</th>
                    <th>Registered Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $result = $conn->query("SELECT id, name, email, phone, role, created_at FROM users $where_clause ORDER BY id DESC");
                if($result && $result->num_rows > 0):
                    while($row = $result->fetch_assoc()):
                        $badge = $row['role'] == 'admin' ? 'bg-danger' : 'bg-primary';
                ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><strong><?php echo $row['name']; ?></strong></td>
                    <td><?php echo $row['email']; ?></td>
                    <td><?php echo $row['phone']; ?></td>
                    <td><span class="badge <?php echo $badge; ?> px-3 py-2"><?php echo ucfirst($row['role']); ?></span></td>
                    <td><?php echo date('M d, Y h:i A', strtotime($row['created_at'])); ?></td>
                    <td>
                        <button class="btn btn-sm btn-info text-white" onclick='editUser(<?php echo json_encode($row); ?>)'>
                            <i class="fas fa-edit"></i>
                        </button>
                        <a href="users.php?delete=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this user?')">
                            <i class="fas fa-trash"></i>
                        </a>
                    </td>
                </tr>
                <?php 
                    endwhile; 
                else:
                ?>
                <tr><td colspan="7" class="text-center py-4 text-muted">No users found<?php echo !empty($search) ? " matching '$search'" : ""; ?>.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- User Modal -->
<div class="modal fade" id="userModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="users.php">
                <div class="modal-header">
                    <h5 class="modal-title" id="userModalTitle">Add User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="userId">
                    <div class="mb-3">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="name" id="userName" class="form-control" required placeholder="User Full Name">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email Address</label>
                        <input type="email" name="email" id="userEmail" class="form-control" required placeholder="user@example.com">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Mobile Number</label>
                        <input type="text" name="phone" id="userPhone" class="form-control" placeholder="Mobile Number">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password <small class="text-muted" id="passwordHint">(Required)</small></label>
                        <input type="password" name="password" id="userPassword" class="form-control" required placeholder="Enter password">
                        <div class="form-text text-muted d-none" id="editPasswordNote">Leave blank to keep current password.</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Role</label>
                        <select name="role" id="userRole" class="form-select">
                            <option value="user">User</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save User</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function resetUserForm() {
    document.getElementById('userId').value = '';
    document.getElementById('userName').value = '';
    document.getElementById('userEmail').value = '';
    document.getElementById('userPhone').value = '';
    document.getElementById('userPassword').value = '';
    document.getElementById('userPassword').required = true;
    document.getElementById('passwordHint').innerText = '(Required)';
    document.getElementById('editPasswordNote').classList.add('d-none');
    document.getElementById('userRole').value = 'user';
    document.getElementById('userModalTitle').innerText = 'Add User';
}

function editUser(data) {
    document.getElementById('userId').value = data.id;
    document.getElementById('userName').value = data.name;
    document.getElementById('userEmail').value = data.email;
    document.getElementById('userPhone').value = data.phone || '';
    document.getElementById('userPassword').value = '';
    document.getElementById('userPassword').required = false;
    document.getElementById('passwordHint').innerText = '(Optional)';
    document.getElementById('editPasswordNote').classList.remove('d-none');
    document.getElementById('userRole').value = data.role;
    document.getElementById('userModalTitle').innerText = 'Edit User';
    
    var userModal = new bootstrap.Modal(document.getElementById('userModal'));
    userModal.show();
}
</script>

<?php require_once 'includes/footer.php'; ?>
