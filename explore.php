<?php
include 'config.php';  // Ensure session and database connection
include 'header.php';   // Navigation menu with login/logout

// Get the 'id' from the URL
$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0; // Ensure it's an integer

// Fetch product details including 'image_folder'
$sql = "SELECT title, description, price, image_folder FROM products WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$stmt->bind_result($title, $description, $price, $image_folder);

// Fetch the result
if (!$stmt->fetch()) {
    echo "Product not found.";
    exit;
}

$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QuickMedi</title>
    <link rel="icon" href="images/location.png">
    <link rel="stylesheet" href="style.css">
    <script src="include.js" defer></script>
</head>

<body>

    <h1><?php echo htmlspecialchars($title); ?></h1>

    <div class="container4">
        <p><?php echo nl2br(htmlspecialchars($description)); ?></p>
    </div>

    <div class="container1">
        <?php
        $image_path = __DIR__ . "/" . $image_folder;
        $images = glob($image_path . "*.{jpg,jpeg,png,gif}", GLOB_BRACE);

        if (!empty($images)) {
            foreach ($images as $image) {
                $image_url = $image_folder . basename($image);
                echo '<div class="image">
                    <img src="' . htmlspecialchars($image_url) . '" alt="">
                  </div>';
            }
        } else {
            echo "<p>No images available.</p>";
        }
        ?>
    </div>

    <div class="container4">
        <a href="booking_page.php?id=<?php echo $product_id; ?>">Book Now</a>
    </div>

    <?php
    // Fetch all products
    $sql = "SELECT id, title, description, img FROM products";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo '<div class="container1">';
        while ($row = $result->fetch_assoc()) {
            echo '<div class="card">';
            echo '<img src="images/' . htmlspecialchars($row['img']) . '" alt="">';
            echo '<p>' . htmlspecialchars($row['title']) . '</p>';
            echo '<a href="explore.php?id=' . $row['id'] . '"><button>Explore</button></a>';
            echo '</div>';
        }
        echo '</div>';
    } else {
        echo "No products found.";
    }

    $conn->close();
    ?>

    <?php
    include 'footer.php';
    ?>

</body>

</html>