<?php
session_start();

// Database connection setup
$host = 'localhost';
$db = 'shop';
$user = 'root';
$pass = '';

$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get data from POST request
    $product_id = $_POST['product_id'];
    $size = $_POST['size'];
    $quantity = intval($_POST['quantity']);

    // Validate input
    if (!empty($product_id) && !empty($size) && $quantity > 0) {
        // Check available stock for the selected size
        $stock_stmt = $conn->prepare("
            SELECT ps.quantity 
            FROM product_sizes ps 
            WHERE ps.product_id = ? AND ps.size = ?");
        $stock_stmt->bind_param("is", $product_id, $size);
        $stock_stmt->execute();
        $stock_result = $stock_stmt->get_result();

        if ($stock_result->num_rows > 0) {
            $row = $stock_result->fetch_assoc();
            $available_stock = intval($row['quantity']);

            // Check if the requested quantity is available
            if ($quantity <= $available_stock) {
                $session_id = session_id();

                // Insert into cart
                $cart_stmt = $conn->prepare("
                    INSERT INTO cart (session_id, product_id, size_id, quantity, price) 
                    SELECT ?, ?, ps.id, ?, ps.price 
                    FROM product_sizes ps 
                    WHERE ps.product_id = ? AND ps.size = ?");
                $cart_stmt->bind_param("siiss", $session_id, $product_id, $quantity, $product_id, $size);

                if ($cart_stmt->execute()) {
                    echo "Product added to cart!";
                } else {
                    echo "Error adding product to cart: " . $cart_stmt->error;
                }

                $cart_stmt->close();
            } else {
                echo "The requested quantity exceeds the available stock. Only $available_stock left.";
            }
        } else {
            echo "The selected size is not available.";
        }

        $stock_stmt->close();
    } else {
        echo "Please complete the form correctly.";
    }
} else {
    echo "Invalid request method.";
}

$conn->close();
?>
