<?php

session_start();

$host = 'localhost'; // or your host
$db = 'shop'; // your database name
$user = 'root'; // your database username
$pass = ''; // your database password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

// Handle form submission for deleting a product size
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_size'])) {
    $sizeId = $_POST['size_id'];

    // Prepare and execute the delete statement
    $stmt = $pdo->prepare("DELETE FROM product_sizes WHERE id = :id");
    $stmt->execute(['id' => $sizeId]);
}

// Handle form submission for adding new product
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add'])) {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $image = $_FILES['image']; // Get the uploaded file
    $category_id = $_POST['category_id']; // Make this a drop down to select what type of product.

    // Check if the file was uploaded without errors
    if (isset($image) && $image['error'] == 0) {
        // Define the target directory for uploads
        $targetDir = "assets/shop/"; // Make sure this directory exists and is writable
        $targetFile = $targetDir . basename($image['name']);
        
        // Check file type and size (optional)
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
        
        if (in_array($imageFileType, $allowedTypes) && $image['size'] < 5000000) { // Limit to 5MB
            // Move the uploaded file to the target directory
            if (move_uploaded_file($image['tmp_name'], $targetFile)) {
                // Insert into the database
                $stmt = $pdo->prepare("INSERT INTO products (name, description, image, category_id) VALUES (:name, :description, :image, :category_id)");
                $stmt->execute(['name' => $name, 'description' => $description, 'image' => $targetFile, 'category_id' => $category_id]);
            } } 
    }
}

// Handle form submission for deleting a product
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete'])) {
    $PID = $_POST['PID'];

    $stmt = $pdo->prepare("DELETE FROM products WHERE PID = :PID");
    $stmt->execute(['PID' => $PID]);
}

// Handle form submission for viewing product sizes
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['view_sizes'])) {
    $PID = $_POST['PID'];
    
    // Make sure to include the WHERE clause correctly
    $stmt = $pdo->prepare("
        SELECT product_sizes.id, product_sizes.size, product_sizes.quantity, product_sizes.price 
        FROM product_sizes
        LEFT JOIN products ON products.PID = product_sizes.product_id
        WHERE products.PID = :PID;
    ");
    
    // Execute the statement with the bound parameter
    $stmt->execute(['PID' => $PID]); // Make sure 'PID' is being passed
    $product_sizes = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    $product_sizes = [];
}

// Handle form submission for adding a new product size
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_size'])) {
    $productId = $_POST['product_id'];
    $size = $_POST['size'];
    $quantity = $_POST['quantity'];
    $price = $_POST['price'];

    // Insert into the product_sizes table
    $stmt = $pdo->prepare("INSERT INTO product_sizes (product_id, size, quantity, price) VALUES (:product_id, :size, :quantity, :price)");
    $stmt->execute(['product_id' => $productId, 'size' => $size, 'quantity' => $quantity, 'price' => $price]);
}

// Updating Product Size
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_size'])) {

    if (isset($_POST['id'], $_POST['quantity'], $_POST['price'])) {
        $sizeId = trim($_POST['id']); // Trim to remove any extra spaces
        $newQuantity = trim($_POST['quantity']);
        $newPrice = trim($_POST['price']);

        // Check if the sizeId is not empty
        if (!empty($sizeId)) {
            // Update the product size in the database
            $stmt = $pdo->prepare("UPDATE product_sizes SET quantity = :quantity, price = :price WHERE id = :id");
            $stmt->execute(['quantity' => $newQuantity, 'price' => $newPrice, 'id' => $sizeId]);
} 
    }
}

// Fetch products
$stmt = $pdo->query("SELECT * FROM products");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
// Fetch products with category names
$stmt = $pdo->query("
    SELECT products.*, categories.name AS category_name 
    FROM products 
    LEFT JOIN categories ON categories.id = products.category_id
");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch categories
$stmt = $pdo->query("SELECT id, name FROM categories");
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/test/css/styles2.css">
    <title>Sir Chief's Shop - Inventory</title>
</head>
<body>

    <div class="side-menu">
        <div class="brand-name">
            <h1 style="color: white;">Sir Chief's Shop</h1>
        </div>
    <ul>
        <li><span><a href="admin/dashboard_admin.php#"> Dashboard </a></span> </li>
        <li><span><a href="admin_inventory.php#">Inventory</a></span> </li>
        <li><span><a href="admin/admin_bookings.php#">Appointments</a></span> </li>
        <li><span><a href="admin/admin_orders.php#">Orders</a></span> </li> 
    </ul>
    </div>
    <div class="container">
        <div class="header">
            <div class="nav">
                <h1>Inventory</h1>
                <div class="user">
                    <?php if (isset($_SESSION['username'])): ?>
                    <li><h3>Hello, Admin!</h3></li>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <li><a href="/test/php/logout_admin.php" id="nav">LOG OUT</a></li>
            <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="content">
            <div class="cards">
                <h2>Add New Product</h2>
                &nbsp;
                <form method="POST" action="" enctype="multipart/form-data">
                    <label for="name">Product Name:</label>
                    <input type="text" name="name" required>

                    <label for="description">Description:</label>
                    <input type="text" name="description" required>

                    <label for="image">Image:</label>
                    <input type="file" name="image" accept="image/*" required>

                    <label for="category_id">Category:</label>
                        <select name="category_id" required>
                            <option value="" selected disabled>Select a category</option>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?php echo htmlspecialchars($category['id']); ?>">
                                    <?php echo htmlspecialchars($category['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>

                    <input type="submit" name="add" value="Add Product">
                </form>
            </div>

    <h2 align="center">Existing Products</h2>
    &nbsp;
    <table border="1" align="center">
        <tr>
            <th>PID</th>
            <th>Name</th>
            <th>Description</th>
            <th>Image</th>
            <th>Category</th>
            <th>Action</th>
        </tr>
        <?php foreach ($products as $product): ?>
        <tr>
            <td><?php echo $product['PID']; ?></td>
            <td><?php echo htmlspecialchars($product['name']); ?></td>
            <td><?php echo htmlspecialchars($product['description']); ?></td>
            <td><img src="<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" style="width: 50px; height: auto;"></td>
            <td><?php echo htmlspecialchars($product['category_name']); ?></td>
            <td>
                <form method="POST" action="" style="display:inline;">
                    <input type="hidden" name="PID" value="<?php echo $product['PID']; ?>">
                    <input type="submit" name="view_sizes" value="View Sizes">
                </form>
                <form method="POST" action="" style="display:inline;">
                    <input type="hidden" name="PID" value="<?php echo $product['PID']; ?>">
                    <input type="submit" name="delete" value="Delete" onclick="return confirm('Are you sure you want to delete this product?');">
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
    </table>

    <h2 align="center" style="margin-top: 10px;">Product Sizes</h2>
    <table border="1" align="center">
        <tr>
            <th>Size</th>
            <th>Quantity</th>
            <th>Price</th>
            <th>Set New Quantity and Price</th>
            <th>Action</th>
        </tr>

    <?php foreach ($product_sizes as $size): ?>

        <tr>
            <td><?php echo htmlspecialchars($size['size']); ?></td>
            
            <td><?php echo htmlspecialchars($size['quantity']); ?></td>
            
            <td>₱<?php echo htmlspecialchars($size['price']); ?></td>
    
            <td>
                <form method="POST" action="">
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($size['id']); ?>">
                    <input type="number" name="quantity" value="<?php echo htmlspecialchars($size['quantity']); ?>" required>
                    <input type="number" name="price" value="<?php echo  htmlspecialchars($size['price']); ?>" step="1" required>
                    <input type="submit" name="update_size" value="Update">
                </form> 
            </td>
            <td>
                <form method="POST" action="">
                    <input type="hidden" name="size_id" value="<?php echo htmlspecialchars($size['id']); ?>">
                    <input type="submit" name="delete_size" value="Delete" onclick="return confirm('Are you sure you want to delete this product size?');">
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
    </table>

    <div class="form-container">
        <form method="POST" action="">
            <label for="product_id">Select Product:</label>
            <select name="product_id" required>
                <option value="" selected disabled>Select a product</option>
                <?php foreach ($products as $product): ?>
                    <option value="<?php echo htmlspecialchars($product['PID']); ?>">
                        <?php echo htmlspecialchars($product['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        
            <label for="size">Add Size:</label>
            <input type="text" name="size" required>
        
            <label for="quantity">Quantity:</label>
            <input type="number" name="quantity" required>
        
            <label for="price">Price:</label>
            <input type="number" name="price" step="1" required>
        
            <input type="submit" name="add_size" value="Add Size">
        </form>
    </div>
    </div> 
    <footer>
        <p>&copy; 2024 Sir Chief's Motorshop</p>
    </footer>


</body>
</html>