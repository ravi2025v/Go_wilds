<?php
require_once 'admin/includes/db.php';

// Fetch unique countries for global selection
$country_res = $conn->query("SELECT DISTINCT country_name FROM activity_cards WHERE status='active' ORDER BY country_name ASC");
$countries = [];
while($c = $country_res->fetch_assoc()) $countries[] = $c['country_name'];

// Selected Country (Default to first one or all)
$active_country = isset($_GET['country']) ? $_GET['country'] : (count($countries) > 0 ? $countries[0] : 'Singapore');

// Fetch categories for the selected country
$cat_res = $conn->query("SELECT DISTINCT category FROM activity_cards WHERE country_name = '$active_country' AND status='active'");
$categories = ['All'];
while($ct = $cat_res->fetch_assoc()) $categories[] = $ct['category'];

$active_cat = isset($_GET['category']) ? $_GET['category'] : 'All';

// Search term
$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';

// Build query
$query = "SELECT * FROM activity_cards WHERE status='active' AND country_name = '$active_country'";
if ($active_cat !== 'All') {
    $query .= " AND category = '$active_cat'";
}
if (!empty($search)) {
    $query .= " AND (title LIKE '%$search%' OR unique_code LIKE '%$search%' OR city_name LIKE '%$search%')";
}
$query .= " ORDER BY title ASC";

$result = $conn->query($query);
?>
<?php 
$title = "Premium Activity Selection - GoWilds";
include 'includes/header.php'; 
?>

<style>
    :root {
        --primary-color: #F7921E;
        --secondary-color: #63AB45;
        --dark-bg: #1C231F;
        --light-bg: #f8f9fa;
        --text-muted: #6c757d;
    }

    /* Override some theme styles for this specific page */
    body {
        background-color: var(--light-bg);
    }

    .premium-header {
        background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('assets/images/tour-details-bg.jpg');
        background-size: cover;
        background-position: center;
        padding: 100px 0 60px;
        color: white;
        text-align: center;
    }

    .country-selector {
        background: white;
        padding: 20px;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        margin-top: -40px;
        position: relative;
        z-index: 10;
    }

    .nav-pill-custom {
        padding: 10px 25px;
        border-radius: 30px;
        text-decoration: none;
        color: var(--dark-bg);
        font-weight: 600;
        transition: all 0.3s ease;
        border: 1px solid transparent;
        display: inline-block;
        margin: 5px;
    }

    .nav-pill-custom:hover {
        background: rgba(247, 146, 30, 0.1);
        color: var(--primary-color);
    }

    .nav-pill-custom.active {
        background: var(--primary-color);
        color: white;
    }

    .search-box {
        max-width: 600px;
        margin: 30px auto;
        position: relative;
    }

    .search-box input {
        border-radius: 50px;
        padding: 12px 60px 12px 25px;
        border: 1px solid #eee;
        box-shadow: 0 5px 20px rgba(0,0,0,0.05);
    }

    .search-box button {
        position: absolute;
        right: 8px;
        top: 50%;
        transform: translateY(-50%);
        background: var(--primary-color);
        border: none;
        color: white;
        width: 40px;
        height: 40px;
        border-radius: 50%;
    }

    .activity-card {
        background: white;
        border-radius: 20px;
        overflow: hidden;
        border: 1px solid #eee;
        transition: all 0.4s ease;
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .activity-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        border-color: var(--primary-color);
    }

    .activity-img-wrapper {
        position: relative;
        height: 180px;
        overflow: hidden;
    }

    .activity-img-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.6s ease;
    }

    .activity-card:hover .activity-img-wrapper img {
        transform: scale(1.1);
    }

    .category-badge {
        position: absolute;
        top: 15px;
        left: 15px;
        background: rgba(255,255,255,0.9);
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 700;
        color: var(--primary-color);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }

    .city-tag {
        position: absolute;
        bottom: 10px;
        right: 15px;
        background: var(--secondary-color);
        color: white;
        padding: 2px 10px;
        border-radius: 4px;
        font-size: 0.7rem;
        font-weight: 600;
    }

    .activity-body {
        padding: 20px;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
    }

    .activity-title {
        font-size: 1.15rem;
        font-weight: 700;
        margin-bottom: 8px;
        line-height: 1.3;
        color: var(--dark-bg);
    }

    .activity-code {
        font-size: 0.75rem;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 12px;
        display: block;
    }

    .price-tag {
        font-size: 1.3rem;
        font-weight: 700;
        color: var(--secondary-color);
    }

    .price-tag span {
        font-size: 0.8rem;
        color: var(--text-muted);
        font-weight: 400;
    }

    .select-btn {
        background: var(--dark-bg);
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 50px;
        font-weight: 600;
        transition: all 0.3s ease;
        font-size: 0.9rem;
    }

    .select-btn:hover {
        background: var(--primary-color);
    }

    .select-btn.selected {
        background: var(--secondary-color);
    }

    .card-footer-custom {
        margin-top: auto;
        padding-top: 15px;
        border-top: 1px solid #f8f8f8;
    }

    .empty-state { text-align: center; padding: 100px 0; color: #aaa; }
    .empty-state i { font-size: 4rem; margin-bottom: 20px; }
  </style>
</head>

<body>

<div class="premium-header">
    <div class="container">
        <h1 class="display-5 fw-bold"><?php echo $active_country; ?> Activities</h1>
        <p class="lead opacity-75">Curated experiences in <?php echo $active_country; ?> and beyond.</p>
    </div>
</div>

<div class="container">
    <!-- Country Selector -->
    <div class="country-selector text-center">
        <h6 class="mb-3 text-uppercase small fw-bold text-muted">Select Destination</h6>
        <div class="d-flex justify-content-center flex-wrap">
            <?php foreach($countries as $country): ?>
                <a href="?country=<?php echo $country; ?>" 
                   class="nav-pill-custom <?php echo $active_country === $country ? 'active' : ''; ?>">
                   <i class="fas fa-globe-asia me-2"></i><?php echo $country; ?>
                </a>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Category Tabs -->
    <div class="text-center mt-4 mb-2">
        <?php foreach($categories as $cat): ?>
            <a href="?country=<?php echo $active_country; ?>&category=<?php echo $cat; ?>&search=<?php echo $search; ?>" 
               class="nav-pill-custom border <?php echo $active_cat === $cat ? 'active' : ''; ?>">
                <?php echo $cat === 'All' ? 'All Types' : $cat; ?>
            </a>
        <?php endforeach; ?>
    </div>

    <!-- Search Box -->
    <div class="search-box">
        <form action="" method="GET">
            <input type="hidden" name="country" value="<?php echo $active_country; ?>">
            <input type="hidden" name="category" value="<?php echo $active_cat; ?>">
            <input type="text" name="search" class="form-control" placeholder="Search attractions in <?php echo $active_country; ?>..." value="<?php echo htmlspecialchars($search); ?>">
            <button type="submit"><i class="fas fa-search"></i></button>
        </form>
    </div>

    <!-- Activities Grid -->
    <div class="row g-4 mb-5">
        <?php if ($result && $result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
                    <div class="activity-card shadow-sm" data-id="<?php echo $row['id']; ?>">
                        <div class="activity-img-wrapper">
                            <span class="category-badge"><?php echo $row['category']; ?></span>
                            <span class="city-tag"><?php echo $row['city_name']; ?></span>
                            <?php 
                                $img_src = !empty($row['image']) ? $row['image'] : 'assets/images/activity-placeholder.png';
                            ?>
                            <img src="<?php echo $img_src; ?>" onerror="this.onerror=null; this.src='assets/images/activity-placeholder.png'" alt="<?php echo $row['title']; ?>">
                        </div>
                        <div class="activity-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <span class="activity-code" style="font-size: 0.65rem; color: #aaa;"><?php echo $row['city_code']; ?> | <?php echo $row['card_code']; ?></span>
                                <span class="activity-code fw-bold" style="color: var(--primary-color);"><?php echo $row['unique_code']; ?></span>
                            </div>
                            <h3 class="activity-title"><?php echo $row['title']; ?></h3>
                            
                            <div class="card-footer-custom d-flex justify-content-between align-items-center">
                                <div class="price-tag">
                                    <span>From</span> ₹<?php echo number_format($row['price']); ?>
                                </div>
                                <button class="btn select-btn" onclick="toggleSelect(this)">SELECT</button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="col-12 empty-state">
                <i class="fas fa-map-marked-alt"></i>
                <h3>No activities found in <?php echo $active_country; ?></h3>
                <p>Try searching for a different city or category.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
function toggleSelect(btn) {
    btn.classList.toggle('selected');
    if (btn.classList.contains('selected')) {
        btn.innerHTML = '<i class="fas fa-check me-2"></i> SELECTED';
        btn.style.background = 'var(--secondary-color)';
    } else {
        btn.innerHTML = 'SELECT';
        btn.style.background = 'var(--dark-bg)';
    }
}
</script>

<?php include 'includes/footer.php'; ?>
