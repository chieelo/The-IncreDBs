-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 04, 2024 at 11:55 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `shop`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `S_ID` int(2) NOT NULL,
  `date` date NOT NULL,
  `make_model` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(20) NOT NULL DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `user_id`, `S_ID`, `date`, `make_model`, `created_at`, `status`) VALUES
(10, 17, 1, '2024-12-08', 'Honda ADV 160', '2024-12-04 02:50:12', 'Pending');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `cid` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `size_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`) VALUES
(1, 'Tires'),
(2, 'Engine Oil'),
(3, 'Battery'),
(4, 'Gear Oil'),
(5, 'Brakes'),
(6, 'Cables'),
(7, 'Filters'),
(8, 'Spark Plug'),
(9, 'Electronics'),
(10, 'Accessories'),
(11, 'Helmets');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `contact_number` varchar(15) NOT NULL,
  `address` text NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `order_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(50) DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `user_id`, `contact_number`, `address`, `total_amount`, `order_date`, `status`) VALUES
(7, 17, '(02) 8777 7338', 'Far  Eastern University - Manila', 2275.00, '2024-12-04 01:27:36', 'Complete'),
(8, 17, '(02) 8777 7338', 'Far  Eastern University - Manila', 4849.00, '2024-12-04 03:13:55', 'Pending');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `order_item_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `size_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `total` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`order_item_id`, `order_id`, `product_id`, `size_id`, `quantity`, `price`, `total`) VALUES
(14, 7, 5, 13, 1, 25.00, 25.00),
(15, 7, 1, 4, 2, 75.00, 150.00),
(16, 7, 13, 27, 2, 1050.00, 2100.00),
(17, 8, 7, 16, 1, 4000.00, 4000.00),
(18, 8, 1, 3, 4, 70.00, 280.00),
(19, 8, 10, 21, 1, 500.00, 500.00),
(20, 8, 3, 9, 1, 69.00, 69.00);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `PID` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) NOT NULL,
  `category_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`PID`, `name`, `description`, `image`, `category_id`) VALUES
(1, 'Zeno Brake Pads', 'Zeno Brake Pads are engineered for riders who demand precision, power, and peak performance. With advanced materials and superior heat resistance, these pads deliver aggressive stopping power and control, making them perfect for spirited street rides or track days. Take your braking to the next level with Zeno.', 'assets/shop/zenobrake.jpg', 5),
(2, 'Yamakoto Brake Pads', 'Yamakoto Motorcycle Brake Pads offer reliable stopping power and durability at a price that won’t break the bank. Designed for everyday riders, they deliver solid performance and safety without compromising your budget.', 'assets/shop/yamakotobrake.jpg', 5),
(3, 'SureBrake Brake Fluid', 'Sure Brake® DOT 3 Brake Fluid is a heavy duty brake fluid designed to give added safety specially when operating under severe driving conditions. It contains corrosion inhibitor additives to protect against corrosion on metal and other parts brought about by the absorption of moisture. It is excellent for use in Disc, Drum and ABS brake systems and complies with DOT 3 of PNS 239 / FMVSS 116.', 'assets/shop/surebrake.jpg', 5),
(4, 'Smok Mini Driving Lights V1', 'The SMOK MINI Driving Light V1 is a compact, powerful LED solution that enhances visibility and safety for motorcyclists. Its durable, weather-resistant, and waterproof design ensures reliable performance in all conditions, while versatile mounting options make installation easy. Bright, efficient, and built to last, it\'s the perfect companion for any ride.', 'assets/shop/smok.jpg', 9),
(5, 'Halogen Bulbs', 'T10 and T13 halogen lights are versatile and reliable solutions for motorcycle lighting. The T10 is ideal for position lights, offering bright and focused illumination to enhance visibility. The T13 is perfect for instrument panels and signal lights, delivering clear and consistent light output. Both options are easy to install, energy-efficient, and designed for long-lasting performance, making them excellent choices for your motorcycle\'s lighting needs.', 'assets/shop/bulb.jpg', 9),
(6, 'EVO Helmet Black', 'The EVO Helmet is a high-performance, ECE and ICC certified helmet designed for safety and comfort. Its durable ABS composite shell provides excellent protection, while the Dura-flex washable foam set ensures a clean and comfortable fit. With aerodynamic vents for optimal airflow and a smoke lens paired with a free clear lens, the EVO Helmet offers both functionality and style for every rider. Perfect for those who prioritize safety, comfort, and convenience on the road.', 'assets/shop/evoblack.jpg', 11),
(7, 'Spyder Helmet White', 'Competitively priced full-face helmet for riders that value quality and safety.  The Advanced Polycarbonate Composite Shell provides a lightweight helmet, with superior fit and comfort. The Optically-superior face shield provides 100% UV protection and distortion free vision.  Exceeds ECE 22.05 standards, where testing includes energy dispersion, penetration resistance, and chin strap integrity.', 'assets/shop/spyderwhite.jpg', 11),
(8, 'Power Go Battery', 'PowerGo Batteries offer exceptional durability and long-lasting performance at an affordable price. Designed to provide reliable power for your motorcycle, these budget-friendly batteries are built to withstand the rigors of everyday riding, ensuring dependable starts and consistent performance. With a focus on durability, PowerGo Batteries are an excellent choice for riders who want reliable power without breaking the bank.', 'assets/shop/pg12.jpg', 3),
(10, 'Kixx Ultra 4T 10W-40', 'Kixx Ultra 4T Scooter SN is a premium synthetic technology engine oil designed to meet the advanced API SN grade standards. Formulated for optimal scooter engine performance, it enhances fuel efficiency, reduces friction loss, and boosts engine durability. Perfect for riders looking to maintain their scooter\'s peak performance while ensuring long-lasting reliability.', 'assets/shop/kixx1l1040.jpg', 2),
(11, 'RS8 10W-40', 'RS8 10W-40 is a high-quality, budget-friendly engine oil designed to provide reliable performance for motorcycles and scooters. Formulated to reduce friction and enhance engine protection, it ensures smooth operation and longevity without compromising on value. Ideal for riders seeking an affordable oil option that delivers consistent performance and durability across a range of riding conditions.', 'assets/shop/rs810401l.jpg', 2),
(12, 'Quick Tires', 'Quick Tires are the ideal choice for highway driving and endurance rides. Engineered for long-lasting performance, these tires offer smooth handling and excellent grip on highways, ensuring a safe and comfortable ride during your journeys. Perfect for those who want reliable tires at an affordable price, Quick Tires provide great value in the mid-range price category. Whether you\'re hitting the road for a weekend adventure or a long-distance trip, these tires are built to last and perform, making them the perfect addition to your vehicle. Get ready for your next ride with Quick Tires – the perfect blend of quality and affordability!', 'assets/shop/quick.jpg', 1),
(13, 'Maxxis', 'Maxxis Tires offer premium performance for drivers who demand the best. Known for their exceptional quality and durability, Maxxis tires are designed to provide superior grip, stability, and comfort, whether you\'re cruising on highways or tackling long-distance rides. Engineered with advanced technology, they deliver excellent performance in all conditions, ensuring a smooth, safe, and reliable driving experience. Ideal for those who value high-quality tires, Maxxis offers premium features at a price that reflects their superior craftsmanship. Upgrade your ride with Maxxis Tires, and experience the difference of premium engineering on every journey.', 'assets/shop/maxxis.jpg', 1),
(14, 'Yamaha Air Filter', 'Ensure optimal performance for your Yamaha motorcycle with our Original Yamaha Air Filter. Crafted to meet Yamaha’s high-quality standards, this air filter provides a perfect fit, just like the one installed at the factory. It’s designed to deliver superior filtration, keeping your engine running smoothly and efficiently by preventing dirt and debris from entering the intake system. Trust the reliability and precision of Yamaha\'s original parts for a seamless riding experience and enhanced engine longevity. Ideal for maintaining your motorcycle\'s peak performance, this air filter is an essential replacement for Yamaha owners seeking quality and reliability.', 'assets/shop/yamahaair.jpg', 7);

-- --------------------------------------------------------

--
-- Table structure for table `product_sizes`
--

CREATE TABLE `product_sizes` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `size` varchar(50) NOT NULL,
  `quantity` int(11) DEFAULT 0,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_sizes`
--

INSERT INTO `product_sizes` (`id`, `product_id`, `size`, `quantity`, `price`) VALUES
(1, 1, 'Wave 125', 50, 75.00),
(2, 1, 'Sniper 155', 50, 75.00),
(3, 1, 'ADV Front/Rear', 96, 70.00),
(4, 1, 'R150 Rear', 98, 75.00),
(5, 2, 'Wave 125', 50, 30.00),
(6, 2, 'Sniper 155', 50, 30.00),
(7, 2, 'ADV Front/Rear', 100, 35.00),
(8, 2, 'R150 Rear', 100, 35.00),
(9, 3, '150 ml', 9, 69.00),
(10, 3, '900 ml', 10, 250.00),
(11, 4, 'Universal', 5, 899.00),
(12, 5, 'T10', 100, 19.00),
(13, 5, 'T13', 99, 25.00),
(14, 6, 'Medium', 5, 3500.00),
(15, 6, 'Large', 5, 3500.00),
(16, 7, 'Medium', 4, 4000.00),
(17, 7, 'Large', 5, 4000.00),
(20, 10, '800 ml', 5, 480.00),
(21, 10, '1 L', 4, 500.00),
(22, 11, '800 ml', 5, 180.00),
(23, 11, '1 L', 5, 190.00),
(24, 8, ' 12N5L - BS', 5, 650.00),
(25, 8, 'YTX4L - BS', 5, 350.00),
(26, 13, '60/80 - 17', 10, 900.00),
(27, 13, '70/80 - 17', 8, 1050.00),
(28, 13, '80/80 - 17', 10, 1180.00),
(29, 13, '90/80 - 17', 10, 1500.00),
(30, 12, '45/90 - 17', 10, 575.00),
(31, 12, '50/100 - 17', 10, 590.00),
(32, 12, '60/80 - 17', 10, 650.00),
(33, 12, '70/80 - 17', 10, 700.00),
(34, 12, '70/90 - 17', 10, 710.00),
(35, 12, '80/90 - 17', 10, 890.00),
(36, 14, 'Sniper 150', 10, 500.00),
(37, 14, 'Aerox', 10, 500.00),
(38, 14, 'Mio Soul i115', 10, 450.00),
(39, 14, 'Mio Sporty', 10, 350.00),
(40, 14, 'FZ', 10, 550.00),
(41, 14, 'Mio Soul i125', 10, 450.00);

-- --------------------------------------------------------

--
-- Table structure for table `service`
--

CREATE TABLE `service` (
  `S_ID` int(2) NOT NULL,
  `SNAME` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `service`
--

INSERT INTO `service` (`S_ID`, `SNAME`) VALUES
(1, 'Preventive Maintenance Service '),
(2, 'General Checkup'),
(3, 'Engine Overhaul'),
(4, 'Wheel Build/Tire Replacement'),
(5, 'Others');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `UID` int(5) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `address` text DEFAULT NULL,
  `contact_number` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`UID`, `username`, `email`, `password`, `first_name`, `last_name`, `address`, `contact_number`) VALUES
(1, 'admin', 'admin@admin.com', 'admin', 'admin', 'admin', NULL, NULL),
(16, 'patrick', 'patricksigue@patrick.com', 'patrick', 'Patrick', 'Sigue', '', ''),
(17, 'juan', 'juan@cruz.com', 'juan', 'Juan', 'Cruz', 'Far  Eastern University - Manila', '(02) 8777 7338');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `fk_S_ID` (`S_ID`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`cid`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `size_id` (`size_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`order_item_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `size_id` (`size_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`PID`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `product_sizes`
--
ALTER TABLE `product_sizes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `service`
--
ALTER TABLE `service`
  ADD PRIMARY KEY (`S_ID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`UID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `cid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `order_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `PID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `product_sizes`
--
ALTER TABLE `product_sizes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `UID` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`UID`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_S_ID` FOREIGN KEY (`S_ID`) REFERENCES `service` (`S_ID`);

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`UID`),
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`PID`),
  ADD CONSTRAINT `cart_ibfk_3` FOREIGN KEY (`size_id`) REFERENCES `product_sizes` (`id`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`UID`);

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`),
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`PID`),
  ADD CONSTRAINT `order_items_ibfk_3` FOREIGN KEY (`size_id`) REFERENCES `product_sizes` (`id`);

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);

--
-- Constraints for table `product_sizes`
--
ALTER TABLE `product_sizes`
  ADD CONSTRAINT `product_sizes_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`PID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
