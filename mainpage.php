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

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Sir Chief's Motorshop">
    <title>Sir Chief's Motorshop</title>
    <link rel="icon" href="SIR CHIEF’S (4).png" type="image/png">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <style>
                      body {
            font-family: verdana, sans-serif;
            font-stretch: extra-expanded;
            line-height: 1.6;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
            color: #333;
        }

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
        }

        footer {
            background: #333;
            color: #fff;
            text-align: center;
            padding: 5px;
            width: 100%;
            max-width: 100vw;
        }

        /* Home section with background image */
        #home {
            background-image: url('assets/main/b502fffab7dace64d090089c4fbb402a.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            padding: 50px;
            color: white;
        }

        /* About section */
        #about {
            background-color: #014235;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            padding: 50px 20px;
            color: white;
        }

        #home h1 {
            font-family: times;
            font-size: 80px;
            font-stretch: condensed;
            letter-spacing: -2.5px;
            margin-bottom: 10px;
            margin-top: 220px;
            line-height: 1.2;
        }

        #about {
            padding: 40px 20px;
            text-align: center;
        }

        .about-container {
            display: flex;
            margin: 0;
            justify-content: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .about-item {
            flex: 1%;
            max-width: 250px;
            padding: 15px;
            border: 1px;
            margin: 0;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        /* Contact section */
        #contact {
            background-color: #fff;
            padding-top: 30px;
            padding-bottom: 90px;
            color: #333;
            text-align: center;
            gap: 1px;
            display: flex;
            flex-wrap: wrap;
            flex-direction: column;
            align-items: center; 
        }

        /* Containers in the contact section */
        .container2 {
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            align-items: center;
            text-align: center;
            flex-wrap: wrap;
            margin-bottom: 30px;
        }

        .buttons {
            display: flex;
            justify-content: space-evenly;
            margin-top: 20px;
            width: 220px;
        }
        .buttons a {
            display: inline-block;
            padding: 10px;
            background-color: #004d26;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
             font-family: verdana, sans-serif;
        }
        .buttons a:hover {
            background-color: #006b35;
        }

        /* Ensure the image fits well on different screen sizes */
        .container2 img {
            max-width: 70%; 
            height: auto;
            border-radius: 8px;
            margin-top: 10px;
        }

        /* Media Query for larger devices */
        @media (min-width: 768px) {
            #contact {
                flex-direction: row;
                justify-content: space-between; 
                text-align: left; 
            }

            .container2 {
                flex: 1;
                margin: 0 20px; 
            }

            .container2 img {
                width: 80%; 
            }

            .buttons {
                flex-direction: column;
                align-items: center;
            }
            .buttons a {
                margin-bottom: 10px;
                width: 80%;
                text-align: center;
            }
        }

        /* Media Query for very small screens */
        @media (max-width: 480px) {
            .container2 img {
                width: 90%;
            }

            .container2 h2 {
                font-size: 140%;
            }

            .container2 p {
                font-size: 100%;
            }

            #contact {
                padding-top: 20px;
                padding-bottom: 30px;
            }
        }

        @media (max-width: 768px) {
            nav ul {
                flex-direction: column;
            }

            nav li {
                margin: 10px 0;
            }

            nav img.logo {
                width: 180px;
            }

            #home h1 {
                font-size: 50px;
            }

            section {
                padding: 15px;
            }

            footer {
                font-size: 14px;
            }
        }

        @media (max-width: 480px) {
            nav a {
                font-size: 16px;
            }

            #home h1 {
                font-size: 40px;
            }

            footer {
                font-size: 12px;
            }
        }
        /* Modal Styles */
        .modal {
            display: none; /* Hidden by default */
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .modal-content {
            background-color: white;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 500px;
            box-sizing: border-box;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
        }

        .form-modal {
            padding: 20px;
            text-align: center;
        }

        .form-toggle button {
    background-color: #014235; /* Updated button background color */
    color: white;
    font-size: 16px;
    padding: 10px 20px;
    margin: 5px;
    cursor: pointer;
    border: none;
    border-radius: 5px;
}

.form-toggle button:hover {
    background-color: #016d3f; /* Slightly lighter shade for hover effect */
}

#signup-form {
    display: none;
}


        #login-form input,
        #signup-form input {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        #login-form button,
        #signup-form button {
            background-color: #014235
;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
        }

        #login-form button:hover,
        #signup-form button:hover {
            background-color: #016d3f;
        }

        #login-form p,
        #signup-form p {
            font-size: 14px;
        }

        #login-form a,
        #signup-form a {
            color: #014235;
;
            text-decoration: none;
        }

        #login-form a:hover,
        #signup-form a:hover {
            text-decoration: underline;
        }
    </style>
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

    <section id="home">
        <h1 style="margin-top: 120px;">WELCOME TO SIR CHIEF'S MOTORSHOP</h1>
        <div class="container">
            <p style="font-size: 120%;">Sir Chief's Motorshop is your premier destination for motorcycle repair and maintenance services. Our shop is a great place for you to find high-quality products and learn more about our commitment to excellent service.</p>
        </div>
    </section>

    <section id="about">
        <div class="about-container">
            <div class="about-item">
                <h3 style="font-family: times; font-size: 160%;">OUR EXPERTISE</h3> <br>
                <p>At Sir Chief's Motorshop, our team of technicians are experts in motorcycle repair and maintenance. We are dedicated to providing top-notch service to all our customers.</p>
            </div>
            <div class="about-item">
                <h3 style="font-family: times;font-size: 160%;">MOTORCYCLE ACCESSORIES</h3>
                <p>Explore a wide range of motorcycle accessories at Sir Chief's Motorshop. We offer high-quality accessories to enhance your riding experience.</p>
            </div>
            <div class="about-item">
                <h3 style="font-family: times;font-size: 160%;">EMERGENCY ASSISTANCE</h3>
                <p>At Sir Chief's Motorshop, we provide reliable breakdown services to ensure you get back on the road quickly and safely.</p>
            </div>
        </div>
    </section>

   <section id="contact">
    <div class="container2">
        <center>
        <h2 style="font-family: times; font-size: 170%;letter-spacing: 1; margin-bottom: 0px;">VISIT OUR STORE</h2>
        <p style="font-size: 120%; padding-bottom: 10px;">Come and visit Sir Chief's Motorshop to experience our exceptional services and explore our extensive range of motorcycle products and accessories. Book your visit today!</p>
        <div class="buttons">
            <a href="services.php">Book Your Visit</a>
        </div>
    </center>
    </div>
    
    <div class="container2">
        <br>
        <img src="assets/main/f4a201bb4eab82b51dca174e85149311.jpg" alt="Store Image" />
    </div>
</section>

    <footer>
        <p style="font-family: times;font-size: 120%;">&copy; 2024 Sir Chief's</p>
    </footer>
    
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
</body>
</html>