<?php

session_start();

// Database connection
$host = 'localhost';
$db = 'shop';
$user = 'root';
$pass = '';

$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the product ID from the URL
$product_id = isset($_GET['product_id']) ? intval($_GET['product_id']) : 0;

if ($product_id > 0) {
    // Fetch product details using prepared statement
    $product_stmt = $conn->prepare("
        SELECT p.PID, p.Name, p.Description, p.image
        FROM products p
        WHERE p.PID = ?");
    $product_stmt->bind_param("i", $product_id);
    $product_stmt->execute();
    $product_result = $product_stmt->get_result();

    // Check if product exists
    if ($product_result->num_rows > 0) {
        $product = $product_result->fetch_assoc();

        // Fetch product sizes and prices from Product_Sizes
        $sizes_stmt = $conn->prepare("
            SELECT id, size, price, quantity
            FROM Product_Sizes
            WHERE product_id = ?");
        $sizes_stmt->bind_param("i", $product_id);
        $sizes_stmt->execute();
        $sizes_result = $sizes_stmt->get_result();
    } else {
        echo "Product not found.";
        exit;
    }
    $product_stmt->close();
} else {
    echo "Invalid product ID.";
    exit;
}

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
    <title>Product Details - <?php echo htmlspecialchars($product['Name']); ?></title>
    <link rel="icon" href="SIR CHIEF’S (4).png" type="image/png">
    <link rel="stylesheet" href="css/product_details.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
       body {
            font-family: verdana, sans-serif;
            font-stretch: extra-expanded;
            line-height: 1.6;
            margin: 0;
            padding: 0;
            color: #333;
        }

        header {
            font-family: verdana, sans-serif;
            background: #fff;
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


        section {
            padding: 20px;
            text-align: center;
        }

        .container {
            width: 80%;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            margin-bottom: 10px;
            background-color: #BDBDBD;
        }


        footer {
            text-align: center;
            background-color: #333;
            color: white;
            font-family: Times, serif;
            font-size: 120%;
            margin-top: auto; /* Push footer to the bottom */
        }
    </style>
</head>
<body>
    <header>
        <nav>
            <ul>
                <li><a href="mainpage.php"><img src="assets/SIR CHIEF’S (5).png" class="logo" alt="Logo"></a></li>
                <li><a href="aboutsir.php">ABOUT US</a></li>
                <li><a href="services.php">SERVICES</a></li>
                <li><a href="contact.php">CONTACT</a></li>
                <li><a href="incredbs.php" style="color: seagreen;">SHOP</a></li>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li><a id="cartBtn" class="php-link">View Cart</a></li>
                <?php endif; ?>
                <!-- Check if the user is logged in -->
                <ul class="login-nav">
                    <?php if (isset($_SESSION['username'])): ?>
                        <li><a href="myacc.php" style="text-transform: uppercase;"><?php echo "HI, " . htmlspecialchars($_SESSION['username']); ?></a></li>
                        <li><a href = "php/logout.php" style = "text-decoration: underline;">LOG OUT</a></li>
                    <?php else: ?>
                        <li><a href="#" id="openLoginBtn"><img src="assets/loginicon.png" class="login-icon" alt="Login Icon">&nbsp;LOG IN</a></li>
                    <?php endif; ?>
                </ul>
            </ul>
        </nav>
    </header>
    <section style="background-color: #014235; padding: 5px;">
    </section>

    <div class="product-flex-container">
        <div class="product-image">
            <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['Name']); ?>" onerror="this.onerror=null; this.src='path/to/default/image.jpg';">
        </div>
        <div class="product-info">
            <h1><?php echo htmlspecialchars($product['Name']); ?></h1>
            <p><?php echo nl2br(htmlspecialchars($product['Description'])); ?></p>

            <form id="addToCartForm" method="POST">
                <div class="product-sizes">
                    <h3>Available Sizes:</h3>
                    <div class="size-options">
                        <?php
                        if ($sizes_result && $sizes_result->num_rows > 0) {
                            while ($row = $sizes_result->fetch_assoc()) {
                                echo '<label class="size-option">';
                                echo '<input type="radio" name="size_id" value="' . htmlspecialchars($row["id"]) . '" data-quantity="' . $row["quantity"] . '" required>';
                                echo htmlspecialchars($row["size"]) . ' - ₱ ' . htmlspecialchars($row["price"]);
                                echo '</label>';
                            }
                        } 
                        else {
                            echo '<p>No sizes available.</p>';
                        }
                        ?>
                    </div>
                </div>
                <div class="quantity-display">
                    Please select a size to see the available quantity.
                </div>
                <div class="quantity-container">
                    <label for="quantity">Quantity:</label>
                    <input type="number" id="quantity" name="quantity" value="1" min="1" required>
                </div>

                <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($product['PID']); ?>">
                <button type="submit" class="add-to-cart" style="background: #014235; font-family: verdana, sans-serif;margin-top: 30px;">Add to Cart</button>
            </form>

            <!-- Alert Message -->
            <div id="cartAlert" style="display:none; background-color: #4CAF50; color: white; padding: 15px; margin-top: 20px; text-align: center;">
                Product added to cart!
            </div>

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

    <!-- Modal (will show cart contents) -->
    <div id="cartModal" class="cart-modal">
        <div class="cart-modal-content">
            <span class="cart-close-btn" id="closeBtn">&times;</span>
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
                                <td>₱{$item['price']}</td>
                                <td>₱{$total}</td>
                                <td><button class='remove-item' data-cid='{$item['cid']}'>Remove</button></td> <!-- Add remove button -->
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>Your cart is empty.</td></tr>";
                }
                ?>
                <tr>
                    <td colspan="4"><strong>Total Sum</strong></td>
                    <td><strong>₱<?php echo number_format($totalSum, 2); ?></strong></td>
                </tr>
            </table>
            
            <!-- Confirm Order Form -->
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
            // Handle the form submission for adding to cart
            $('#addToCartForm').submit(function(event) {
                event.preventDefault();  // Prevent the default form submission

                var formData = $(this).serialize();  // Get the form data

                // Send the form data via AJAX
                $.ajax({
                    url: 'php/add_to_cart.php',  // PHP script that processes the request
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        alert(response);
                        location.reload();  // Reload the page to update the cart
                    },
                    error: function(response) {
                        alert(response);
                    }
                });
            });

            // When a size is selected, update the quantity display
            $('input[name="size_id"]').change(function() {
                var quantity = $(this).data('quantity');  // Get the quantity from the selected size's data-quantity attribute

                if (quantity) {
                    // Update the quantity display to show the available quantity
                    $('.quantity-display').html(quantity + ' items left.');

                    // Optionally, set the quantity input's max value to the available quantity
                    $('#quantity').attr('max', quantity);
                } else {
                    // Handle the case where no size is selected (reset the quantity display)
                    $('.quantity-display').html('Please select a size to see the available quantity.');
                }
            });

            // Handle the cart modal open and close actions
            var modal = document.getElementById('cartModal');
            var btn = document.getElementById('cartBtn');
            var closeBtn = document.getElementById('closeBtn');

            // When the user clicks the view cart button, open the modal
            if (btn) {
                btn.onclick = function(event) {
                    event.preventDefault();  // Prevent the default anchor link behavior
                    modal.style.display = 'block';
                }
            }

            // When the user clicks on <span> (x), close the modal
            if (closeBtn) {
                closeBtn.onclick = function() {
                    modal.style.display = 'none';
                }
            }

            // When the user clicks anywhere outside of the modal, close it
            window.onclick = function(event) {
                if (event.target == modal) {
                    modal.style.display = 'none';
                }
            };

            // Handle removing an item from the cart
            $('.remove-item').click(function() {
                var cid = $(this).data('cid');  // Get the cart item ID

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

            // Handle order form submission
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

            // Display session-based messages (Error, Success)
            <?php if (isset($_SESSION['error_message'])): ?>
                alert("<?php echo $_SESSION['error_message']; ?>");
                <?php unset($_SESSION['error_message']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['registration_success'])): ?>
                alert("<?php echo $_SESSION['registration_success']; ?>");
                <?php unset($_SESSION['registration_success']); ?>
            <?php endif; ?>

            // Handle the "Log In" button click to open the login modal
            if (document.getElementById("openLoginBtn")) {
                document.getElementById("openLoginBtn").addEventListener("click", function() {
                    document.getElementById("loginModal").style.display = "block";  // Display modal
                });
            }


            // Close the login modal if clicked outside
            window.onclick = function(event) {
                var loginModal = document.getElementById("loginModal");
                if (event.target == loginModal) {
                    loginModal.style.display = "none";
                }

                var cartModal = document.getElementById("cartModal");
                if (event.target == cartModal) {
                    cartModal.style.display = "none";
                }
            };

        });
    
        // Function to close the login modal
            function closeModal() {
                document.getElementById("loginModal").style.display = "none";  // Hide modal
            }

        // Handle the form toggle between login and signup
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
    
</body>
 <section style="background-color: #014235; padding: 5px;">
    <footer>
    <p>&copy; 2024 Sir Chief's</p>
    </footer>
</section>

</html>

<?php
$conn->close();
?>
