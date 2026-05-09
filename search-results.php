<?php
/**
 * search-results.php
 * Handles the display of tour search results with improved UI.
 */
require_once 'admin/includes/db.php';

$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';
$category = isset($_GET['category']) ? $conn->real_escape_string($_GET['category']) : '';

// SEO and Page Metadata
$title = "Search Results - " . ($search ? htmlspecialchars($search) : "Tours") . " | MyEasyTrip";
include 'includes/header.php';

// Construct Query
$query = "SELECT * FROM tours WHERE status = 'active'";
if (!empty($search)) {
    $query .= " AND (title LIKE '%$search%' OR destination LIKE '%$search%' OR description LIKE '%$search%')";
}
if (!empty($category)) {
    $query .= " AND category = '$category'";
}
$query .= " ORDER BY id DESC";

$result = $conn->query($query);
?>

<style>
    .search-results-header {
        position: relative;
        padding: 60px 0;
        background-size: cover;
        background-position: center;
        color: #fff !important;
    }

    .search-results-header h1 {
        color: #fff !important;
        /* text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3); */
    }

    .search-results-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.4);
        /* Slightly lighter overlay */
        z-index: 1;
    }

    .search-results-header .container {
        position: relative;
        z-index: 2;
    }

    .result-card {
        background: #fff;
        border-radius: 20px;
        overflow: hidden;
        transition: all 0.3s ease;
        border: 1px solid #eee;
    }

    .result-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
    }

    .result-img-wrapper {
        position: relative;
        height: 240px;
        overflow: hidden;
    }

    .result-img-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }

    /* .result-card:hover .result-img-wrapper img {
        transform: scale(1.1);
    } */

    .price-badge {
        position: absolute;
        top: 20px;
        right: 20px;
        background: #63ab45;
        color: #fff;
        padding: 8px 18px;
        border-radius: 50px;
        font-weight: 700;
        font-size: 16px;
        /* box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2); */
        z-index: 5;
    }

    .category-badge {
        position: absolute;
        bottom: 20px;
        left: 20px;
        background: rgba(255, 255, 255, 0.9);
        color: #333;
        padding: 4px 12px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
    }

    .result-content {
        padding: 25px;
    }

    .result-title {
        font-size: 20px;
        font-weight: 700;
        margin-bottom: 10px;
        color: #222;
        transition: color 0.3s;
    }

    .result-title:hover {
        color: #63ab45;
    }

    .result-meta {
        display: flex;
        align-items: center;
        gap: 15px;
        color: #777;
        font-size: 14px;
        margin-bottom: 20px;
    }

    .result-meta i {
        color: #63ab45;
    }

    .btn-explore {
        display: block;
        width: 100%;
        text-align: center;
        padding: 12px;
        background: #f8f9fa;
        color: #63ab45;
        border: 2px solid #63ab45;
        border-radius: 50px;
        font-weight: 700;
        transition: all 0.3s;
    }

    .btn-explore:hover {
        background: #63ab45;
        color: #fff;
    }
</style>

<!--====== Start Page Title Area ======-->
<section class="search-results-header text-white" style="background-image: url(assets/images/abt-bg.jpg);">
    <div class="container text-center">
        <span class="badge bg-success mb-3 px-3 py-2 rounded-pill">Exploration Mode</span>
        <h1 class="display-4 fw-bold mb-3">Search Results</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb justify-content-center bg-transparent">
                <li class="breadcrumb-item"><a href="index.php" class="text-white-50">Home</a></li>
                <li class="breadcrumb-item active text-white" aria-current="page">Search Results</li>
            </ol>
        </nav>
    </div>
</section>

<!--====== Start Search Results Section ======-->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row mb-5 justify-content-center">
            <div class="col-lg-8 text-center">
                <?php if (!empty($search)): ?>
                    <div class="d-inline-block px-4 py-2 bg-white rounded-pill shadow-sm mb-3">
                        <span class="text-muted">Showing results for: </span>
                        <span class="fw-bold text-primary">"<?php echo htmlspecialchars($search); ?>"</span>
                    </div>
                <?php endif; ?>
                <h2 class="fw-bold">
                    <?php echo ($result && $result->num_rows > 0) ? "We found these amazing tours for you" : "No tours match your search"; ?>
                </h2>
            </div>
        </div>

        <div class="row">
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while ($tour = $result->fetch_assoc()): ?>
                    <div class="col-xl-4 col-md-6 col-sm-12 mb-4">
                        <div class="result-card wow fadeInUp">
                            <div class="result-img-wrapper">
                                <img src="<?php echo htmlspecialchars($tour['image']); ?>"
                                    alt="<?php echo htmlspecialchars($tour['title']); ?>"
                                    onerror="this.src='assets/images/tour.jpg'">
                                <div class="price-badge">₹<?php echo number_format($tour['price']); ?></div>
                                <div class="category-badge"><?php echo htmlspecialchars($tour['category'] ?? 'Tour'); ?></div>
                            </div>
                            <div class="result-content">
                                <h4 class="result-title">
                                    <a href="tour-details.php?id=<?php echo $tour['id']; ?>" class="text-decoration-none">
                                        <?php echo htmlspecialchars($tour['title']); ?>
                                    </a>
                                </h4>
                                <div class="result-meta">
                                    <span><i class="fas fa-map-marker-alt"></i>
                                        <?php echo htmlspecialchars($tour['destination']); ?></span>
                                    <span><i class="far fa-clock"></i>
                                        <?php echo htmlspecialchars($tour['duration'] ?? 'N/A'); ?> Days</span>
                                </div>
                                <a href="tour-details.php?id=<?php echo $tour['id']; ?>" class="btn-explore">Explore More</a>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="col-12 text-center py-5">
                    <div class="mb-4">
                        <i class="fas fa-search-location fa-6x text-muted opacity-25"></i>
                    </div>
                    <h3 class="fw-bold">No Match Found</h3>
                    <p class="text-muted mb-4">Don't worry, we have plenty of other adventures waiting for you!</p>
                    <a href="tour.php" class="main-btn primary-btn rounded-pill">Browse All Tours</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>