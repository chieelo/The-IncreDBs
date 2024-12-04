<?php
session_start();

$servername = "localhost";
$username = "root"; 
$password = ""; 
$dbname = "shop"; 

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the request is a POST request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the user is logged in
    if (!isset($_SESSION['user_id'])) {
        $_SESSION['error_message'] = 'You need to be logged in to book a service.';
        echo "<script>
                alert('" . addslashes($_SESSION['error_message']) . "');
                window.location.href = 'mainpage.php';
              </script>";
        exit; // Stop further execution if the user is not logged in
    }

        // Get the data from the POST request
        $service = $_POST['S_ID'] ?? '';
        $date = $_POST['date'] ?? '';
        $makeModel = $_POST['makemodel'] ?? '';
        $userId = $_SESSION['user_id']; // Get the user ID from session
        $status = 'Pending';

        // Validate inputs
        if (!empty($service) && !empty($date) && !empty($makeModel)) {
        // Prepare and bind
        $stmt = $conn->prepare("INSERT INTO bookings (user_id, S_ID, date, make_model) VALUES (?, ?, ?, ?)");
        
        // Assuming $userId, $service, $date, and $makeModel are defined
        $stmt->bind_param("iiss", $userId, $service, $date, $makeModel); // 'i' for int, 's' for string

        // Execute the query
        if ($stmt->execute()) {
            echo "<script>alert('Booking added successfully.');</script>";
        } else {
            echo "<script>alert('Error: " . addslashes($stmt->error) . "');</script>";
        }


        $stmt->close();
    }
else {
        echo "<script>alert('Please fill in all fields.');</script>";
    }
}

// Close the connection
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Sir Chief's Motorshop Services">
    <title>Services - Sir Chief's Motorshop</title>
    <link rel="icon" href="assets/SIR CHIEF’S (4).png" type="image/png">
    <link rel="stylesheet" type="text/css" href="css/genstyle.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

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

<section>
    <h2 style="font-family: times;">Our Services</h2>
    <?php if (isset($_SESSION['error_message'])): ?>
        <div class="error-message" style="color: red;">
            <?php
            echo htmlspecialchars($_SESSION['error_message']);
            unset($_SESSION['error_message']); // Clear the message after displaying
            ?>
        </div>
    <?php endif; ?>
    <div class="service-container">
        <div class="service-item" onclick="toggleDescription(this)">
	<img src= "assets/service/servicepreventivepic.png">
            <h3>Preventive Maintenance Service</h3>
            <p class="description">This service includes a comprehensive check of your vehicle's systems to ensure everything is functioning properly.</p>
        </div>
        <div class="service-item" onclick="toggleDescription(this)">
	<img src= "assets/service/servicecheckuppic.png">
            <h3>General Checkup</h3>
            <p class="description">This service includes a thorough inspection of your vehicle to identify any potential issues.</p>
        </div>
        <div class="service-item" onclick="toggleDescription(this)">
	<img src= "assets/service/serviceenginepic.png">
            <h3>Engine Overhaul</h3>
            <p class="description">This service involves a complete disassembly and inspection of the engine components.</p>
        </div>
        <div class="service-item" onclick="toggleDescription(this)">
	<img src= "assets/service/servicetirepic.png">
            <h3>Wheel Build/Tire Replacement</h3>
            <p class="description">This service includes replacing old tires and ensuring proper wheel alignment.</p>
        </div>
    </div>

    <a class="button" id="bookNowBtn">Book Now!</a>
</section>

<div id="bookingModal" class="modal">
    <div class="modal-content">
        <span class="close" id="closeBookingModal">&times;</span>
        <h2>Book Your Service</h2>
        <form id="bookingForm" method="POST" action="services.php">
            <label for="serviceSelect">Select Service:</label>
            <select id="serviceSelect" name="S_ID">
                <option value="1">Preventive Maintenance Service</option>
                <option value="2">General Checkup</option>
                <option value="3">Engine Overhaul</option>
                <option value="4">Wheel Build/Tire Replacement</option>
		<option value="5">Others</option>
            </select>
            <br><br>
            <label for="dateInput">Select Date:</label>
            <input type="date" id="dateInput" name="date" required>
            <br><br>
            <input type="text" id="MakeModelInput" name="makemodel" placeholder="Make and Model info here..." required> 
            <br><br>
            <button type="submit">Submit Booking</button>
        </form>
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
    
    <script>
document.addEventListener("DOMContentLoaded", function() {
    var bookingModal = document.getElementById("bookingModal");
    var bookNowBtn = document.getElementById("bookNowBtn");
    var closeBookingModalBtn = document.getElementById("closeBookingModal");

    // Show booking modal when clicking 'Book Now'
    if (bookNowBtn) {
        bookNowBtn.onclick = function() {
            bookingModal.style.display = "block";
        };
    }

    // Close booking modal when clicking 'X' (close button)
    if (closeBookingModalBtn) {
        closeBookingModalBtn.onclick = function() {
            bookingModal.style.display = "none";
        };
    }

    // Close booking modal when clicking outside of it
    window.addEventListener('click', function(event) {
        if (event.target == bookingModal) {
            bookingModal.style.display = "none";
        }
    });

    // Toggle description visibility
    function toggleDescription(element) {
        const description = element.querySelector('.description');
        const computedStyle = window.getComputedStyle(description);

        description.style.display = (computedStyle.display === "none") ? "block" : "none";
    }

    // Handle 'Cart' modal
    var cartBtn = document.getElementById('cartBtn');
    var cartModal = document.getElementById('cartModal');
    var closeCartBtn = document.getElementById('closeCartModal');

    if (cartBtn) {
        cartBtn.onclick = function(event) {
            event.preventDefault();
            if (cartModal) {
                cartModal.style.display = 'block';
            }
        };
    }

    if (closeCartBtn) {
        closeCartBtn.onclick = function() {
            cartModal.style.display = 'none';
        };
    }

    // Close cart modal when clicking outside of it
    window.addEventListener('click', function(event) {
        if (event.target == cartModal) {
            cartModal.style.display = 'none';
        }
    });

    // Handle AJAX for removing items from cart
    $(document).on('click', '.remove-item', function() {
        var cid = $(this).data('cid');
        if (confirm('Are you sure you want to remove this item from your cart?')) {
            $.ajax({
                url: 'php/remove_from_cart.php',
                type: 'POST',
                data: { cid: cid },
                success: function(response) {
                    alert(response);
                    location.reload();
                },
                error: function() {
                    alert("An error occurred. Please try again.");
                }
            });
        }
    });

    // Handle order form submission
    $('#orderFormDetails').submit(function(event) {
        event.preventDefault();
        var formData = $(this).serialize();
        $.ajax({
            url: 'php/confirm_order.php',
            type: 'POST',
            data: formData,
            success: function(response) {
                alert(response);
                location.reload();
            },
            error: function() {
                alert("An error occurred. Please try again.");
            }
        });
    });

    // Booking form validation
    $('#bookingForm').on('submit', function(e) {
        const date = $('#dateInput').val();
        if (!date || new Date(date) < new Date()) {
            e.preventDefault();
            alert("Please select a valid future date.");
        }
    });

    // Handle login modal
    var openLoginBtn = document.getElementById('openLoginBtn');
    var loginModal = document.getElementById('loginModal');
    var closeLoginModalBtn = document.getElementById('closeLoginModal');

    if (openLoginBtn) {
        openLoginBtn.onclick = function() {
            if (loginModal) {
                loginModal.style.display = 'block';
            }
        };
    }

    if (closeLoginModalBtn) {
        closeLoginModalBtn.onclick = function() {
            loginModal.style.display = 'none';
        };
    }

    // Close login modal when clicking outside
    window.addEventListener('click', function(event) {
        if (event.target == loginModal) {
            loginModal.style.display = 'none';
        }
    });

    // Handle signup modal toggling
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

    // Display session-based alerts
    <?php if (isset($_SESSION['error_message'])): ?>
        alert("<?php echo $_SESSION['error_message']; ?>");
        <?php unset($_SESSION['error_message']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['registration_success'])): ?>
        alert("<?php echo $_SESSION['registration_success']; ?>");
        <?php unset($_SESSION['registration_success']); ?>
    <?php endif; ?>
});
</script>

<br><br>
<footer>
    <p>&copy; 2024 Sir Chief's Motorshop</p>
</footer>
</body>
</html>