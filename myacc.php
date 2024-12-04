<?php
session_start();

// Database connection
$host = 'localhost'; // Your database host
$db = 'shop'; // Your database name
$user = 'root'; // Your database username
$pass = ''; // Your database password

$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$email = ""; // Default value for email

// Check if the user is logged in
if (isset($_SESSION['username'])) {
    // Retrieve user details from the database
    $username = $_SESSION['username'];
    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $email = $user['email'];
        $first = $user['first_name'];
        $last = $user['last_name'];
        $contact = $user['contact_number'];
        $address = $user['address'];
    } else {
        $errorMessage = "No user found.";
    }
} else {
    $errorMessage = "Please log in to view your account.";
}

$book = "SELECT b.date, b.make_model, b.status, s.SNAME AS service_name
        FROM bookings b
        JOIN service s ON b.S_ID = s.S_ID
        ORDER BY b.created_at DESC";

$result = $conn->query($book);

if ($result->num_rows > 0) {
    // Fetch all bookings
    $bookings = [];
    while ($row = $result->fetch_assoc()) {
        $bookings[] = $row;
    }
} else {
    $bookings = [];
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Sir Chief's Motorshop">
    <title>My Account</title>
    <link rel="icon" href="assets/SIR CHIEF’S (4).png" type="image/png">
    <!-- Link to the external CSS file -->
    <link rel="stylesheet" href="css/myacc.css">
</head>
<body>
    <header>
        <nav class="header-nav">
            <ul>
                <ul class="main-nav">
                    <li><a href="mainpage.php"><img src="assets/SIR CHIEF’S (5).png" class="logo" alt="Logo"></a></li>
                    <li><a href="aboutsir.php">ABOUT US</a></li>
                    <li><a href="services.php">SERVICES</a></li>
                    <li><a href="contact.php">CONTACT</a></li>
                    <li><a href="incredbs.php">SHOP</a></li>
                </ul>
                <ul class="login-nav">
                    <?php if (isset($_SESSION['username'])): ?>
                        <li><a href="myacc.php"> HI, <?php echo strtoupper(htmlspecialchars($_SESSION['username'])); ?></a></li>
                        <li style="text-decoration: underline;"><a href="php/logout_admin.php">LOG OUT</a></li>
                    <?php else: ?>
                        <li><a href="#" id="openModalBtn"><img src="assets/loginicon.png" class="login-icon" alt="Login Icon" style="justify-content: center;">LOG IN</a></li>
                    <?php endif; ?>
                </ul>
            </ul>
        </nav>
    </header>

    <!-- Username outside the container -->
    <div class="username" style="padding-top: 90px; display: flex; align-items: center; justify-content: flex-start;">
    <a href="#my-account"><img src="assets/usericon.png" class="user-icon" alt="User Icon" style="margin-right: 10px; width: 60px; height: 60px; vertical-align: middle;"> </a>
    <?php echo strtoupper(htmlspecialchars($first)) . ' ' . strtoupper(htmlspecialchars($last)); ?>
</div>

    <section id="container">
        <nav class="section-nav">
            <ul>
                <li><a href="#my-account" class="nav-link">My Account</a></li>
                <li><a href="#orders" class="nav-link">Orders</a></li>
                <li><a href="#history" class="nav-link">History</a></li>
            </ul>
        </nav>

        <div class="slider">
            <div class="slide" id="my-account">
                <div class="container" style="font-size: 16px; margin-bottom: 20px; text-align: left; padding-left: 0px;">
                    <div class="section-title">Account Info</div>
                    <p><strong>Username:</strong> <?php echo htmlspecialchars($username); ?></p>
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($email); ?></p>
                    <p><strong>Contact Number:</strong> <?php echo htmlspecialchars($contact); ?></p>
                    <p><strong>Address:</strong> <?php echo htmlspecialchars($address); ?></p>
                </div>
            </div>

            <div class="slide" id="orders">
                <div class="container" style="font-size: 16px; margin-bottom: 20px; text-align: left; padding-left: 0px;">
                    <p>View your order history below:</p>

                    <?php
                    $user_id = $_SESSION['user_id'];

                    $sql = "SELECT * FROM orders WHERE user_id = ? ORDER BY order_date DESC";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("i", $user_id);
                    $stmt->execute();
                    $order_result = $stmt->get_result();

                    if ($order_result->num_rows > 0) {
                        // Start order table
                        echo "<table class='order-table' cellpadding='0' cellspacing='0' border='0'>";
                        echo "<thead><tr><th>Order Date</th><th>Contact Number</th><th>Address</th><th>Total Amount</th><th>Status</th><th>View Items</th></tr></thead>";
                        echo "<tbody>";

                        while ($order = $order_result->fetch_assoc()) {
                            $order_id = $order['order_id'];
                            $contact_number = $order['contact_number'];
                            $address = $order['address'];
                            $total_amount = $order['total_amount'];
                            $order_date = $order['order_date'];
                            $status = $order['status'];

                            echo "<tr class='order-row'>";
                            echo "<td>" . date('F j, Y, g:i a', strtotime($order_date)) . "</td>";
                            echo "<td>" . htmlspecialchars($contact_number) . "</td>";
                            echo "<td>" . htmlspecialchars($address) . "</td>";
                            echo "<td>₱" . number_format($total_amount, 2) . "</td>";
                            echo "<td>" . htmlspecialchars($status) . "</td>";
                            echo "<td><button class='view-items-btn' data-order-id='$order_id'>View Items</button></td>";
                            echo "</tr>";

                            // Hidden row for order items
                            echo "<tr class='order-items-row' id='order-items-$order_id'>";
                            echo "<td colspan='6' class='order-item-list'>";
                            
                            // Start order items table
                            echo "<table class='order-items-table'>";
                            echo "<thead><tr><th>Product Name</th><th>Size</th><th>Quantity</th><th>Price</th><th>Total</th></tr></thead>";
                            echo "<tbody>";

                            $sql_items = "SELECT oi.*, p.name, s.size 
                                          FROM order_items oi
                                          JOIN products p ON oi.product_id = p.pid
                                          JOIN product_sizes s ON oi.size_id = s.id
                                          WHERE oi.order_id = ?";
                            $stmt_items = $conn->prepare($sql_items);
                            $stmt_items->bind_param("i", $order_id);
                            $stmt_items->execute();
                            $item_result = $stmt_items->get_result();

                            if ($item_result->num_rows > 0) {
                                while ($item = $item_result->fetch_assoc()) {
                                    $product_name = $item['name'];
                                    $size_name = $item['size'];
                                    $quantity = $item['quantity'];
                                    $price = $item['price'];
                                    $total = $item['total'];

                                    echo "<tr>";
                                    echo "<td>" . htmlspecialchars($product_name) . "</td>";
                                    echo "<td>" . htmlspecialchars($size_name) . "</td>";
                                    echo "<td>" . htmlspecialchars($quantity) . "</td>";
                                    echo "<td>₱" . number_format($price, 2) . "</td>";
                                    echo "<td>₱" . number_format($total, 2) . "</td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='5'>No items found for this order.</td></tr>";
                            }

                            echo "</tbody></table>"; // End order items table

                            echo "</td></tr>";
                        }

                        echo "</tbody></table>";
                    } else {
                        echo "<p>No orders found.</p>";
                    }
                    ?>
                </div>
            </div>

            <div class="slide" id="history">
                <h2>Booking History</h2>
                <table cellpadding="0" cellspacing="0" border="0">
                    <thead>
                        <tr>
                            <th><strong>Service</strong></th>
                            <th><strong>Model</strong></th>
                            <th><strong>Date</strong></th>
                            <th><strong>Status</strong></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($bookings): ?>
                            <?php foreach ($bookings as $booking): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($booking['service_name']); ?></td>
                                    <td><?php echo htmlspecialchars($booking['make_model']); ?></td>
                                    <td><?php echo htmlspecialchars($booking['date']); ?></td>
                                    <td><?php echo htmlspecialchars($booking['status']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4">No booking history available.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>


        </div>
    </section>

    <script>
        // Select all navigation links
        const navLinks = document.querySelectorAll('.nav-link');

        // Add click event listener to each link
        navLinks.forEach(link => {
            link.addEventListener('click', function() {
                // Remove 'active' class from all links
                navLinks.forEach(link => link.classList.remove('active'));
                
                // Add 'active' class to the clicked link
                this.classList.add('active');
            });
        });

        document.addEventListener('DOMContentLoaded', function () {
            const sections = document.querySelectorAll('.slide');
            const navLinks = document.querySelectorAll('.nav-link');

            // Initially hide all slides
            sections.forEach(section => section.style.display = 'none');

            // Show the first section by default
            if (sections.length > 0) {
                sections[0].style.display = 'block';
            }

            navLinks.forEach((link, index) => {
                link.addEventListener('click', function (e) {
                    e.preventDefault();

                    // Hide all sections
                    sections.forEach(section => section.style.display = 'none');

                    // Show the clicked section
                    sections[index].style.display = 'block';
                });
            });
        });

        const viewItemsButtons = document.querySelectorAll('.view-items-btn');
        viewItemsButtons.forEach(button => {
            button.addEventListener('click', function() {
                const orderId = this.getAttribute('data-order-id');
                const itemsRow = document.getElementById('order-items-' + orderId);

                // Toggle visibility of the order items row
                if (itemsRow.style.display === 'none') {
                    itemsRow.style.display = 'table-row';
                } else {
                    itemsRow.style.display = 'none';
                }
            });
        });

        $(window).on("load resize", function() {
            // Calculate the difference between the width of the tbl-content container and the width of the table
            var scrollWidth = $('.tbl-content').width() - $('.tbl-content table').width();
          
            // Adjust the padding-right of the tbl-header to account for the scrollbar width
            $('.tbl-header').css({'padding-right': scrollWidth});
        }).resize();  // Trigger the resize function on load to apply the styles

    </script>
</body>
<footer>
    <p>&copy; 2024 Sir Chief's</p>
</footer>
</html>
