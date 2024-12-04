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
    <title>CONTACT | Sir Chief's Motorshop</title>
    <link rel="icon" href="SIR CHIEF’S (4).png" type="image/png">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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


        footer {
            text-align: center;
            padding: 10px;
            background-color: #014235;
            color: white;
            font-family: Times, serif;
            font-size: 120%;
        }

        #about {
            background-color: #f4f4f9;
            padding-left: 200px;
            padding-right: 200px;
            padding-bottom: 100px;
            color: #333;
        }

        .container3 {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 30px;
            max-width: 1200px;
            margin-top: 40px;
            margin-left: 70px;

            flex-wrap: wrap; /* For responsive design */
        }

        .container3 .text-content {
            flex: 1; /* Larger space for text */
        }

        .container3 .text-content p {
            font-size: 18px;
            text-align: justify;
            margin: 0;
        }

        .container3 .image-container {
            flex: 1; /* Smaller space for images */
            display: flex;
            flex-direction: column;
            gap: 40px; /* Spacing between images */
        }

        .container3 .image-container img {
            max-width: 70%;
            height: auto;
            border-radius: 4px;
        }

        .buttons {
            display: flex;
            justify-content: center; 
            align-items: center;      
            gap: 20px;               
            margin-top: 20px;
            }

        .buttons a {
            justify-content: center;
            align-items: center;
            display: inline-block;
            padding: 10px 20px;
            background-color: #004d26;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            margin-right: 10px;
        }

        .buttons a:hover {
            background-color: #006b35;
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



        @media (max-width: 768px) {
            .container3 {
                flex-direction: column;
                text-align: center;
                margin-left: 0;
                margin-right: 0;
            }

            .container3 .image-container {
                flex-direction: column;
                justify-content: center;
            }

            .container3 img {
                max-width: 80%;
                margin-bottom: 20px;
            }

            .buttons {
                flex-direction: column;
                align-items: center;
                gap: 10px;
            }

            .buttons a {
                width: 80%;
                text-align: center;
            }
        }

        @media (max-width: 480px) {
            nav a {
                font-size: 16px;
            }

            h1 {
                font-size: 20px;
            }

            footer {
                font-size: 12px;
            }

            .container3 img {
                max-width: 100%;
                margin-bottom: 20px;
            }

            .buttons a {
                width: 100%;
                text-align: center;
            }
        }
        #bodyofcontactus {
            margin-top: 30px;    /* Space above the section */
            margin-bottom: 80px; /* Space below the section */
            margin-left: 550px;   /* Space on the left */
            margin-right: 550px;  /* Space on the right */
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
            background-color: #014235;
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


<section style="background-color: #014235; padding: 5px;">
    </section>
    <center>
        <h2 style="font-family: times; font-size: 170%; letter-spacing: 1; margin-top: 80px; align-items: center;">GET IN TOUCH WITH US!</h2>
            </center>

    <section style="align-content: center; margin-left: auto; justify-content: center;">
        <div id="bodyofcontactus">
            <p> &emsp;&emsp;&emsp; Find us at 140-D Ninoy Aquino Ave., Pildera II, Brgy. 194, Pasay City, NCR, <br><img src="https://cdn-icons-png.flaticon.com/512/535/535239.png" width="25px" height="25px">&emsp;&emsp;Philippines, 1300.</p>

            <p> &emsp;&emsp;&emsp; Telephone
            <br><img src="https://icons-for-free.com/iff/png/512/contact+phone+telephone+icon-1320166083436482761.png" width="25px" height="25px">&emsp;&emsp;Tel: 123-456-7890</p>
            
            <p> &emsp;&emsp;&emsp; Email
            <br><img src="https://cdn4.iconfinder.com/data/icons/ionicons/512/icon-ios7-email-outline-512.png"width="30px" height="30px">&emsp;&ensp;info@sirchiefmotorshop.com</p>
            
            <p> &emsp;&emsp;&emsp; Opening Hours
            <br><img src="https://img.icons8.com/ios7/600/info.png" width="25px" height="25px">&emsp;&emsp;Mon - Fri: 8am - 7pm<br>&emsp;&emsp;&emsp;&ensp;Saturday: 9am - 5pm</p>
        </div>
    </section>
    <section style="background-color: #BDBDBD; padding: 10px; align-content: center;">
        <center>
            <h2 style="font-family: times; font-size: 170%; letter-spacing: 1; margin-top: 80px; align-items: center;">VISIT OUR LOCATION</h2>
            <p> 140-D Ninoy Aquino Ave., Pildera II, Brgy. 194, Pasay City, NCR, Philippines, 1300 </p>
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3862.55299301803!2d121.0023298516082!3d14.51033169876244!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3397cf32f0b39873%3A0xc6c36e4451e73e95!2sSir%20Chief&#39;s%20Motorcycle%20Parts%20and%20Accessories!5e0!3m2!1sen!2sph!4v1732772058244!5m2!1sen!2sph" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            <!-- ito code if bet niyo kita mismo store nila pat --> <!-- <iframe src="https://www.google.com/maps/embed?pb=!4v1732770472532!6m8!1m7!1szzFzQNS1xrrmNDemUFvdXw!2m2!1d14.51024374210832!2d121.0023026356316!3f276.2567705016944!4f-0.7948373886948161!5f2.5805494592371625" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe> -->
            <br><br><br><br>
        </center>
    </section>

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

    <footer>
        <p>&copy; 2024 Sir Chief's</p>
    </footer>
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
