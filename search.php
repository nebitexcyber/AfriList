<?php
// Include the database configuration
include 'includes/config.php';

// Ensure the search term is provided
if (!isset($_GET['search'])) {
    header("Location: index.php");
    exit();
}

$search_term = $_GET['search'];

// Perform the search
$sql = "SELECT * FROM businesses WHERE name LIKE ?";
$stmt = $conn->prepare($sql);
$search_param = "%{$search_term}%";
$stmt->bind_param('s', $search_param);

if ($stmt->execute()) {
    $result = $stmt->get_result();
    $businesses = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
} else {
    echo "Error performing search: " . $stmt->error;
    exit();
}

include 'templates/header.php';
?>

<div class="container mt-4">
    <h1 class="text-center">Search Results for "<?= $search_term ?>"</h1>

    <div class="row">
        <?php if (!empty($businesses)) : ?>
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
        <?php else : ?>
            <div class="col-12">
                <p>No businesses found for "<?= $search_term ?>".</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include 'templates/footer.php'; ?>
