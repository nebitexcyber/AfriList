<?php
// Include the database configuration
include 'includes/config.php';

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // DO NOT use this approach in a real application - it's vulnerable to XSS!
    $name = $_POST['name'];
    $description = $_POST['description'];
    $logo_url = $_POST['logo_url'];

    // Insert into the database (vulnerable to XSS)
    $sql = "INSERT INTO businesses (name, description, logo_url) VALUES ('$name', '$description', '$logo_url')";
    if ($conn->query($sql) === TRUE) {
        echo "Business added successfully!";
    } else {
        echo "Error adding business: " . $conn->error;
    }
}

include 'templates/header.php';
?>

<div class="container mt-4">
    <h1 class="text-center">Add a Business Listing</h1>

    <form method="post" action="">
        <div class="form-group">
            <label for="name">Business Name:</label>
            <input type="text" class="form-control" name="name" required>
        </div>
        <div class="form-group">
            <label for="description">Business Description:</label>
            <textarea class="form-control" name="description" required></textarea>
        </div>
        <div class="form-group">
            <label for="logo_url">Logo URL:</label>
            <input type="text" class="form-control" name="logo_url" required>
        </div>
        <button type="submit" class="btn btn-primary">Add Business</button>
    </form>
</div>

<?php include 'templates/footer.php'; ?>
