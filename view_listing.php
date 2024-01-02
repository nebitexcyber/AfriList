<?php
// Include the database configuration
include 'includes/config.php';

// Check if the business ID is provided in the URL
if (isset($_GET['id'])) {
    $business_id = $_GET['id'];

    // Retrieve business details
    $sql_business = "SELECT * FROM businesses WHERE id = ?";
    $stmt_business = $conn->prepare($sql_business);
    $stmt_business->bind_param('i', $business_id);

    if ($stmt_business->execute()) {
        $result_business = $stmt_business->get_result();
        $business = $result_business->fetch_assoc();
        $stmt_business->close();
    } else {
        echo "Error fetching business details: " . $stmt_business->error;
        exit();
    }

    // Retrieve user reviews for the business
    $sql_reviews = "SELECT * FROM reviews WHERE business_id = ?";
    $stmt_reviews = $conn->prepare($sql_reviews);
    $stmt_reviews->bind_param('i', $business_id);

    if ($stmt_reviews->execute()) {
        $result_reviews = $stmt_reviews->get_result();
        $reviews = $result_reviews->fetch_all(MYSQLI_ASSOC);
        $stmt_reviews->close();
    } else {
        echo "Error fetching reviews: " . $stmt_reviews->error;
        exit();
    }
} else {
    // Redirect if business ID is not provided
    header("Location: index.php");
    exit();
}

include 'templates/header.php';
?>

<div class="container mt-4">
    <h1><?= $business['name'] ?></h1>

    <div class="row">
        <div class="col-md-6">
            <img src="<?= $business['logo_url'] ?>" alt="<?= $business['name'] ?>" class="img-fluid">
        </div>
        <div class="col-md-6">
            <p><strong>Category:</strong> <?= $business['category'] ?></p>
            <p><strong>Location:</strong> <?= $business['location'] ?></p>
            <p><strong>Description:</strong> <?= $business['description'] ?></p>
            <p><strong>Contact Email:</strong> <?= $business['email'] ?></p>
            <p><strong>Contact Phone:</strong> <?= $business['phone'] ?></p>
        </div>
    </div>

    <h2>User Reviews</h2>
    <?php if (!empty($reviews)) : ?>
        <ul class="list-group">
            <?php foreach ($reviews as $review) : ?>
                <li class="list-group-item"><?= $review['review_text'] ?></li>
            <?php endforeach; ?>
        </ul>
    <?php else : ?>
        <p>No reviews yet.</p>
    <?php endif; ?>
</div>

<?php include 'templates/footer.php'; ?>
