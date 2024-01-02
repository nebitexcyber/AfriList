<?php
// Include the database configuration
include 'includes/config.php';

// Search functionality
$search_term = isset($_GET['search']) ? $_GET['search'] : '';

if (!empty($search_term)) {
    $sql = "SELECT * FROM businesses WHERE name LIKE ?";
    $stmt = $conn->prepare($sql);
    $search_param = "%{$search_term}%";
    $stmt->bind_param('s', $search_param);
} else {
    // Fetch all businesses
    $sql = "SELECT * FROM businesses";
    $stmt = $conn->prepare($sql);
}

if ($stmt->execute()) {
    $result = $stmt->get_result();
    $businesses = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
} else {
    echo "Error fetching businesses: " . $stmt->error;
    exit();
}

include 'templates/header.php';
?>

<div class="container mt-4">
    <h1 class="text-center">Local Business Directory - AfriList</h1>

    <form method="get" action="">
        <div class="input-group mb-3">
            <input type="text" class="form-control" placeholder="Search for a business..." name="search" value="<?= $search_term ?>">
            <div class="input-group-append">
                <button class="btn btn-primary" type="submit">Search</button>
            </div>
        </div>
    </form>

    <div class="row">
        <?php foreach ($businesses as $business) : ?>
            <div class="col-md-4 mb-4">
                <div class="card">
                    <img src="<?= $business['logo_url'] ?>" class="card-img-top" alt="<?= $business['name'] ?>" style="max-height: 200px; object-fit: cover;">
                    <div class="card-body">
                        <h5 class="card-title"><?= $business['name'] ?></h5>
                        <p class="card-text"><?= substr($business['description'], 0, 100) . '...' ?></p>
                        <a href="view_listing.php?id=<?= $business['id'] ?>" class="btn btn-primary">View Details</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php include 'templates/footer.php'; ?>
