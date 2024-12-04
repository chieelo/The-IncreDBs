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

// Fetch categories that have products
$category_sql = "
    SELECT c.* 
    FROM categories c
    JOIN products p ON c.id = p.category_id
    GROUP BY c.id";
$categories = $conn->query($category_sql);

// Fetch products based on selected category
$selected_category_id = isset($_GET['category']) ? intval($_GET['category']) : 0;
$product_sql = "SELECT * FROM products" . ($selected_category_id ? " WHERE category_id = $selected_category_id" : "");
$products = $conn->query($product_sql);


if (isset($_SESSION['user_id'])) {
    // Fetch user's contact number and address
    $user_id = $_SESSION['user_id'];
    $user_sql = "SELECT contact_number, address FROM users WHERE uid = ?";
    $stmt = $conn->prepare($user_sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $user_result = $stmt->get_result();
    $user_data = $user_result->fetch_assoc();

    $contact_number = $user_data['contact_number'] ?? ''; // Default to an empty string if null
    $address = $user_data['address'] ?? ''; // Default to an empty string if null
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sir Chief's Shop - Categories</title>
    <link rel="icon" href="assets/SIR CHIEF’S (4).png" type="image/png">
    <link rel="stylesheet" href="css/incredbs.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
    <style>
       header {
            background: #FFFFFF;
            color: #333;
            text-align: center;
        }

        nav ul {
            display: flex;
            align-items: center;
            justify-content: center;
            list-style-type: none;
            margin: 0;
            padding: 0;
        }

        nav li {
            margin-left: 35px;
            margin-right: 35px;
            margin-bottom: 5px;
            margin-top: 5px;
            align-items: center;
        }

        nav img.logo {
            width: 220px;
            height: auto;
        }

        nav a {
            text-decoration: none;
            color: black;
            font-size: 20px;
        }

        nav a:hover {
            color: indianred;
        }

        ul.login-nav {
            display: flex;
            align-items: center; /* Align items vertically */
            gap: 5px;
            margin: 0; /* Remove any extra margins */
            padding: 0;
        }

        nav .login-icon {
            width: 30px;
            height: 30px; /* Keep it square for uniformity */
            vertical-align: middle;
        }

        .login-nav a {
            display: flex;
            align-items: center;
            gap: 8px; /* Space between icon and text */
            text-decoration: none;
            color: black;
            font-size: 18px;
        }

        .login-nav a:hover {
            color: indianred;
        }
        .login-nav li {
             gap: 0px; 
             margin: 7px;
        }


</style>
<body>
    <header>
   <nav>
    <ul>
        <ul class="main-nav">
            <li><a href="mainpage.php"><img src="assets/SIR CHIEF’S (5).png" class="logo" alt="Logo"></a></li>
            <li><a href="aboutsir.php">ABOUT US</a></li>
            <li><a href="services.php">SERVICES</a></li>
            <li><a href="contact.php">CONTACT</a></li>
            <li><a href="incredbs.php">SHOP</a></li>
        </ul>
        <!-- Check if the user is logged in -->
        <ul class="login-nav">
            <?php if (isset($_SESSION['username'])): ?>
                <li><a href="myacc.php"> HI, <?php echo strtoupper(htmlspecialchars($_SESSION['username'])) ; ?></a></li>
                <li><a href="php/logout.php"><u>LOG OUT</u></a></li>
            <?php else: ?>
                <li><a href="#" id="openLoginBtn"><img src="assets/loginicon.png" class="login-icon" alt="Login Icon" style="justify-content: center;">LOG IN</a></li>
            <?php endif; ?>
        </ul>
    </ul>
</nav>
</header>
<section style="background-color: #014235; padding: 5px;">
    </section>


    <div class = 'flex-container'>
        <div class="php-container">
            <h2>Categories</h2>
            <ul>
                <li class="php-link"><a href="incredbs.php" class="php-text">All</a></li>
                <?php 
                    if ($categories->num_rows > 0) { 
                        while($category = $categories->fetch_assoc()) { 
                            echo '<li class="php-link"><a href="incredbs.php?category=' . $category['id'] . '" class="php-text">' . htmlspecialchars($category['name']) . '</a></li>'; 
                        } 
                    } 
                    else { 
                        echo "<li>No categories available.</li>"; 
                    } 
                ?>
                <?php 
        if (isset($_SESSION['user_id'])) { // Check if user is logged in
            echo '<a id="cartBtn" class="php-link">View Cart</a>';
        }
    ?>
            </ul>
        </div>

        <div class="php-product-list" style="background-color: #fff;">
            <?php
                if ($products->num_rows > 0) {
                    while($row = $products->fetch_assoc()) {
                        echo '<div class="php-product">';
                        echo '<img src="' . htmlspecialchars($row["image"]) . '" alt="' . htmlspecialchars($row["name"]) . '">';
                        echo '<h2>' . htmlspecialchars($row["name"]) . '</h2>';
                        echo '<div class="php-product-footer">'; 
                        echo '<a href="product_details.php?product_id=' . $row["PID"] . '" class="php-add-to-cart-button">View Product</a>';
                        echo '</div>'; 
                        echo '</div>';
                    }
                } 
                else {
                    echo "<p>No products found in this category.</p>";
                }
            ?>
        </div>
    </div>

    <!-- Modal -->
    <div id="loginModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <div class="form-modal">
                <div class="form-toggle">
                    <button id="login-toggle" onclick="toggleLogin()">Log In</button>
                    <button id="signup-toggle" onclick="toggleSignup()">Sign Up</button>
                </div>

                <div id="login-form">
                    <!-- Login Form -->
                    <form action="php/login.php" method="POST">
                        <input type="text" name="username" placeholder="Enter email or username" required />
                        <input type="password" name="password" placeholder="Enter password" required />
                        <input type="hidden" name="redirect_to" value="<?php echo htmlspecialchars($_SERVER['REQUEST_URI']); ?>" />
                        <button type="submit">Login</button>
                    </form>
                </div>

                <div id="signup-form" style="display: none;">
                    <!-- Sign-Up Form -->
                    <form action="php/registration.php" method="POST">
                        <input type="text" name="first_name" placeholder="Enter your first name" required />
                        <input type="text" name="last_name" placeholder="Enter your last name" required />
                        <input type="email" name="email" placeholder="Enter your email" required />
                        <input type="text" name="username" placeholder="Choose a username" required />
                        <input type="password" name="password" placeholder="Create password" required />
                        <input type="text" name="address" placeholder="Enter your address (optional)" />
                        <input type="text" name="contact_number" placeholder="Enter your contact number (optional)" />
                        <button type="submit" class="btn signup">Create Account</button>
                        <p>Clicking <strong>create account</strong> means that you agree to our <a href="javascript:void(0)">terms of services</a>.</p>
                        <hr />
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Cart Modal -->
    <div id="cartModal" class="cart-modal">
        <div class="cart-modal-content">
            <span class="close" id="closeBtn">&times;</span>
            <h1>Your Shopping Cart</h1>
            <table>
                <tr>
                    <th>Product Name</th>
                    <th>Size</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Total</th>
                    <th>Action</th>
                </tr>
                <!-- Cart items dynamically loaded via PHP -->
                <?php
                // Fetch cart data
                $user_id = $_SESSION['user_id']; // Assuming user is logged in
                $sql = "SELECT c.cid, c.product_id, c.size_id, c.quantity, c.price, p.name AS product_name, s.size AS size_name 
                        FROM cart c 
                        JOIN products p ON c.product_id = p.PID 
                        JOIN product_sizes s ON c.size_id = s.id
                        WHERE c.user_id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $user_id);
                $stmt->execute();
                $cartResult = $stmt->get_result();

                $totalSum = 0;
                if ($cartResult->num_rows > 0) {
                    while ($item = $cartResult->fetch_assoc()) {
                        $total = $item['quantity'] * $item['price'];
                        $totalSum += $total;
                        echo "<tr>
                                <td>{$item['product_name']}</td>
                                <td>{$item['size_name']}</td>
                                <td>{$item['quantity']}</td>
                                <td>{$item['price']}</td>
                                <td>{$total}</td>
                                <td><button class='remove-item' data-cid='{$item['cid']}'>Remove</button></td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>Your cart is empty.</td></tr>";
                }
                ?>
                <tr>
                    <td colspan="4"><strong>Total Sum</strong></td>
                    <td><strong><?php echo number_format($totalSum, 2); ?></strong></td>
                </tr>
            </table>
            <div id="orderForm">
                <h3>Confirm Order</h3>
                <form id="orderFormDetails">
                    <label for="contact_number">Contact Number:</label>
                    <input 
                        type="text" 
                        id="contact_number" 
                        name="contact_number" 
                        value="<?php echo htmlspecialchars($contact_number); ?>" 
                        required
                    >
                    <label for="address">Address:</label>
                    <textarea 
                        id="address" 
                        name="address" 
                        required
                    ><?php echo htmlspecialchars($address); ?></textarea>
                    <input type="hidden" name="total_amount" value="<?php echo $totalSum; ?>">
                    <button type="submit" id="confirmOrderBtn">Confirm Order</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // Check if 'cartBtn' element exists before adding the click handler
            var cartBtn = document.getElementById('cartBtn');
            if (cartBtn) {
                cartBtn.onclick = function(event) {
                    event.preventDefault(); // Prevent the default behavior (navigation)
                    var modal = document.getElementById('cartModal');
                    if (modal) {
                        modal.style.display = 'block'; // Show the cart modal
                    }
                };
            }

            // Check if 'openLoginBtn' element exists before adding the click handler
            var openLoginBtn = document.getElementById('openLoginBtn');
            if (openLoginBtn) {
                openLoginBtn.onclick = function() {
                    var loginModal = document.getElementById('loginModal');
                    if (loginModal) {
                        loginModal.style.display = 'block'; // Show the login modal
                    }
                };
            }

            // Check if 'cartModal' and 'closeBtn' elements exist before setting click events
            var modal = document.getElementById('cartModal');
            var closeBtn = document.getElementById('closeBtn');
            if (modal && closeBtn) {
                closeBtn.onclick = function() {
                    modal.style.display = 'none'; // Close the modal
                };

                // Close modal when clicking outside of it
                window.onclick = function(event) {
                    if (event.target == modal) {
                        modal.style.display = 'none'; // Close the modal if clicked outside
                    }
                };
            }

            // Check if 'loginModal' and 'close' button for login modal exist before attaching events
            var loginModal = document.getElementById('loginModal');
            var closeLoginModalBtn = document.querySelector('.close');
            if (loginModal && closeLoginModalBtn) {
                closeLoginModalBtn.onclick = function() {
                    loginModal.style.display = 'none'; // Close the login modal
                };

                // Close login modal when clicking outside of it
                window.onclick = function(event) {
                    if (event.target == loginModal) {
                        loginModal.style.display = 'none'; // Close the login modal if clicked outside
                    }
                };
            }

            // Handle AJAX for remove item from cart
            $(document).on('click', '.remove-item', function() {
                var cid = $(this).data('cid');  // Get the cart item ID
                
                // Ask for confirmation before removing
                if (confirm('Are you sure you want to remove this item from your cart?')) {
                    // Send AJAX request to remove the item from the cart
                    $.ajax({
                        url: 'php/remove_from_cart.php',  // PHP script to handle removal
                        type: 'POST',
                        data: { cid: cid },
                        success: function(response) {
                            alert(response);  // Show success or error message
                            location.reload();  // Reload the page to update the cart
                        },
                        error: function(xhr, status, error) {
                            alert("An error occurred. Please try again.");
                        }
                    });
                }
            });

            // Handle order form submission via AJAX
            $('#orderFormDetails').submit(function(event) {
                event.preventDefault();  // Prevent form from submitting normally

                var formData = $(this).serialize();  // Get form data

                $.ajax({
                    url: 'php/confirm_order.php',  // PHP script to process the order
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        alert(response);  // Show success or error message
                        location.reload();  // Reload the page to reflect the new order
                    },
                    error: function(xhr, status, error) {
                        alert("An error occurred. Please try again.");
                    }   
                });
            });

            // Close the modal when clicking anywhere outside the modal (for both cart and login modals)
            window.onclick = function(event) {
                var cartModal = document.getElementById("cartModal");
                if (event.target == cartModal) {
                    cartModal.style.display = "none"; // Close the cart modal if clicked outside
                }

                var loginModal = document.getElementById("loginModal");
                if (event.target == loginModal) {
                    loginModal.style.display = "none"; // Close the login modal if clicked outside
                }
            };

            // Ensure login-modal behavior works
            var openLoginBtn = document.getElementById("openLoginBtn");
            if (openLoginBtn) {
                openLoginBtn.addEventListener("click", function() {
                    document.getElementById("loginModal").style.display = "block";  // Show the login modal
                });
            }

            // Function to close the modal
            function closeModal() {
                document.getElementById("loginModal").style.display = "none";  // Hide the login modal
            }

            // Display any session-based alerts
            <?php if (isset($_SESSION['error_message'])): ?>
                alert("<?php echo $_SESSION['error_message']; ?>");
                <?php unset($_SESSION['error_message']); // Clear the error message after displaying ?>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['registration_success'])): ?>
                alert("<?php echo $_SESSION['registration_success']; ?>");
                <?php unset($_SESSION['registration_success']); // Clear the success message after displaying ?>
            <?php endif; ?>
        });

        // Handling toggle between login and signup forms
            function toggleSignup() {
                document.getElementById("login-toggle").style.backgroundColor = "#fff";
                document.getElementById("login-toggle").style.color = "#222";
                document.getElementById("signup-toggle").style.backgroundColor = "#57b846";
                document.getElementById("signup-toggle").style.color = "#fff";
                document.getElementById("login-form").style.display = "none";
                document.getElementById("signup-form").style.display = "block";
            }

            function toggleLogin() {
                document.getElementById("login-toggle").style.backgroundColor = "#57B846";
                document.getElementById("login-toggle").style.color = "#fff";
                document.getElementById("signup-toggle").style.backgroundColor = "#fff";
                document.getElementById("signup-toggle").style.color = "#222";
                document.getElementById("signup-form").style.display = "none";
                document.getElementById("login-form").style.display = "block";
            }
    </script>
    <footer>
        <p>&copy; 2024 Sir Chief's Motorshop</p>
    </footer>
</body>
</html>

<?php
$conn->close();
?>