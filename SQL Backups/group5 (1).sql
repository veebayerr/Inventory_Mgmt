-- phpMyAdmin SQL Dump
-- version 4.6.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Sep 29, 2016 at 11:09 PM
-- Server version: 5.6.31
-- PHP Version: 5.6.24

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `group5`
--

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `customer_no` int(11) NOT NULL,
  `customer_name` text NOT NULL,
  `customer_address` text NOT NULL,
  `customer_since` date NOT NULL,
  `customer_rewards_points` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `department`
--

CREATE TABLE `department` (
  `department_id` int(11) NOT NULL,
  `department_name` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `department`
--

INSERT INTO `department` (`department_id`, `department_name`) VALUES
(1, 'Administration');

-- --------------------------------------------------------

--
-- Table structure for table `employee`
--

CREATE TABLE `employee` (
  `emp_no` int(11) NOT NULL,
  `emp_name` text NOT NULL,
  `emp_ssn` text NOT NULL,
  `emp_hire_date` date NOT NULL,
  `emp_leave_date` date DEFAULT NULL,
  `emp_sal_hour` enum('Hourly','Salary') NOT NULL,
  `emp_pay` decimal(11,2) NOT NULL,
  `emp_address` text NOT NULL,
  `password` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `employee`
--

INSERT INTO `employee` (`emp_no`, `emp_name`, `emp_ssn`, `emp_hire_date`, `emp_leave_date`, `emp_sal_hour`, `emp_pay`, `emp_address`, `password`) VALUES
(1, 'Admin', '', '2016-09-16', NULL, '', '0.00', '', ''),
(2, 'Test Emp', '', '2016-09-16', NULL, '', '0.00', '', ''),
(3, 'Name', '000-000-0000', '0000-00-00', '0000-00-00', 'Hourly', '0.00', 'Address', '');

-- --------------------------------------------------------

--
-- Table structure for table `employee_clock_in`
--

CREATE TABLE `employee_clock_in` (
  `emp_no` int(11) NOT NULL,
  `time_in` timestamp NULL DEFAULT NULL,
  `time_out` timestamp NULL DEFAULT '0000-00-00 00:00:00',
  `department_id` int(11) NOT NULL,
  `store_no` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `item_no` int(11) NOT NULL,
  `barcode` int(11) NOT NULL,
  `item_name` text NOT NULL,
  `item_producer` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `item_inventory`
--

CREATE TABLE `item_inventory` (
  `no` int(11) NOT NULL,
  `item_no` int(11) NOT NULL,
  `store_no` int(11) NOT NULL,
  `experation_date` date NOT NULL,
  `taxable` enum('True','False') NOT NULL DEFAULT 'True',
  `item_cost` decimal(11,2) NOT NULL,
  `item_sale_price` decimal(11,2) NOT NULL,
  `item_quantity` int(11) NOT NULL,
  `item_discount_percent` decimal(11,5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `item_sold`
--

CREATE TABLE `item_sold` (
  `sale_no` int(11) NOT NULL,
  `item_no` int(11) NOT NULL,
  `transaction_no` int(11) NOT NULL,
  `sale_price` int(11) NOT NULL,
  `quantity_sold` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `payment_no` int(11) NOT NULL,
  `payment_type` enum('Cash','Credit','Check','Gift Card') NOT NULL,
  `payment_ammount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `transaction_no` int(11) NOT NULL,
  `credit_card_type` enum('Visa','Amex','Master Card') DEFAULT NULL,
  `credit_card_no` text,
  `credit_card_exp` text,
  `credit_card_security_no` text,
  `check_no` text,
  `cash_back` decimal(10,2) NOT NULL DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `store`
--

CREATE TABLE `store` (
  `store_no` int(11) NOT NULL,
  `store_name` text NOT NULL,
  `store_location` text NOT NULL,
  `store_manager` int(11) NOT NULL,
  `store_tax` decimal(10,10) NOT NULL DEFAULT '0.0600000000'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `store`
--

INSERT INTO `store` (`store_no`, `store_name`, `store_location`, `store_manager`, `store_tax`) VALUES
(1, 'HQ', '', 1, '0.0600000000');

-- --------------------------------------------------------

--
-- Table structure for table `transaction`
--

CREATE TABLE `transaction` (
  `transaction_no` int(11) NOT NULL,
  `transaction_date` date NOT NULL,
  `price_before_tax` decimal(11,2) NOT NULL,
  `customer_no` int(11) NOT NULL,
  `employee_no` int(11) NOT NULL,
  `store_no` int(11) NOT NULL,
  `price_after_tax` decimal(10,2) NOT NULL DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `trigger_point`
--

CREATE TABLE `trigger_point` (
  `no` int(11) NOT NULL,
  `item_no` int(11) NOT NULL,
  `store_no` int(11) NOT NULL,
  `trigger_point` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `works_in`
--

CREATE TABLE `works_in` (
  `department_id` int(11) NOT NULL,
  `employee_no` int(11) NOT NULL,
  `store_no` int(11) NOT NULL,
  `since` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `works_in`
--

INSERT INTO `works_in` (`department_id`, `employee_no`, `store_no`, `since`) VALUES
(1, 1, 1, '2016-09-16'),
(1, 3, 1, '0000-00-00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`customer_no`);

--
-- Indexes for table `department`
--
ALTER TABLE `department`
  ADD PRIMARY KEY (`department_id`);

--
-- Indexes for table `employee`
--
ALTER TABLE `employee`
  ADD PRIMARY KEY (`emp_no`);

--
-- Indexes for table `employee_clock_in`
--
ALTER TABLE `employee_clock_in`
  ADD KEY `emp_no` (`emp_no`),
  ADD KEY `department_id` (`department_id`),
  ADD KEY `store_no` (`store_no`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`item_no`);

--
-- Indexes for table `item_inventory`
--
ALTER TABLE `item_inventory`
  ADD PRIMARY KEY (`no`),
  ADD KEY `item_no` (`item_no`),
  ADD KEY `store_no` (`store_no`);

--
-- Indexes for table `item_sold`
--
ALTER TABLE `item_sold`
  ADD PRIMARY KEY (`sale_no`),
  ADD KEY `transaction_no` (`transaction_no`),
  ADD KEY `item_no` (`item_no`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`payment_no`),
  ADD KEY `transaction_no` (`transaction_no`);

--
-- Indexes for table `store`
--
ALTER TABLE `store`
  ADD PRIMARY KEY (`store_no`),
  ADD UNIQUE KEY `store_manager` (`store_manager`);

--
-- Indexes for table `transaction`
--
ALTER TABLE `transaction`
  ADD PRIMARY KEY (`transaction_no`),
  ADD KEY `store_no` (`store_no`),
  ADD KEY `employee_no` (`employee_no`),
  ADD KEY `customer_no` (`customer_no`);

--
-- Indexes for table `trigger_point`
--
ALTER TABLE `trigger_point`
  ADD PRIMARY KEY (`no`),
  ADD KEY `store_no` (`store_no`),
  ADD KEY `item_no` (`item_no`);

--
-- Indexes for table `works_in`
--
ALTER TABLE `works_in`
  ADD KEY `employee_no` (`employee_no`),
  ADD KEY `store_no` (`store_no`),
  ADD KEY `department_id` (`department_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `customer_no` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `department`
--
ALTER TABLE `department`
  MODIFY `department_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `employee`
--
ALTER TABLE `employee`
  MODIFY `emp_no` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `item_no` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `item_inventory`
--
ALTER TABLE `item_inventory`
  MODIFY `no` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `item_sold`
--
ALTER TABLE `item_sold`
  MODIFY `sale_no` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `payment`
  MODIFY `payment_no` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `store`
--
ALTER TABLE `store`
  MODIFY `store_no` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `transaction`
--
ALTER TABLE `transaction`
  MODIFY `transaction_no` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `trigger_point`
--
ALTER TABLE `trigger_point`
  MODIFY `no` int(11) NOT NULL AUTO_INCREMENT;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `employee_clock_in`
--
ALTER TABLE `employee_clock_in`
  ADD CONSTRAINT `employee_clock_in_ibfk_1` FOREIGN KEY (`emp_no`) REFERENCES `employee` (`emp_no`),
  ADD CONSTRAINT `employee_clock_in_ibfk_3` FOREIGN KEY (`store_no`) REFERENCES `store` (`store_no`),
  ADD CONSTRAINT `employee_clock_in_ibfk_4` FOREIGN KEY (`department_id`) REFERENCES `department` (`department_id`);

--
-- Constraints for table `item_inventory`
--
ALTER TABLE `item_inventory`
  ADD CONSTRAINT `item_inventory_ibfk_1` FOREIGN KEY (`item_no`) REFERENCES `items` (`item_no`),
  ADD CONSTRAINT `item_inventory_ibfk_2` FOREIGN KEY (`store_no`) REFERENCES `store` (`store_no`);

--
-- Constraints for table `item_sold`
--
ALTER TABLE `item_sold`
  ADD CONSTRAINT `item_sold_ibfk_2` FOREIGN KEY (`transaction_no`) REFERENCES `transaction` (`transaction_no`);

--
-- Constraints for table `payment`
--
ALTER TABLE `payment`
  ADD CONSTRAINT `payment_ibfk_1` FOREIGN KEY (`transaction_no`) REFERENCES `transaction` (`transaction_no`);

--
-- Constraints for table `store`
--
ALTER TABLE `store`
  ADD CONSTRAINT `store_ibfk_1` FOREIGN KEY (`store_manager`) REFERENCES `employee` (`emp_no`);

--
-- Constraints for table `transaction`
--
ALTER TABLE `transaction`
  ADD CONSTRAINT `transaction_ibfk_1` FOREIGN KEY (`store_no`) REFERENCES `store` (`store_no`),
  ADD CONSTRAINT `transaction_ibfk_2` FOREIGN KEY (`employee_no`) REFERENCES `employee` (`emp_no`),
  ADD CONSTRAINT `transaction_ibfk_3` FOREIGN KEY (`customer_no`) REFERENCES `customer` (`customer_no`);

--
-- Constraints for table `trigger_point`
--
ALTER TABLE `trigger_point`
  ADD CONSTRAINT `trigger_point_ibfk_1` FOREIGN KEY (`item_no`) REFERENCES `items` (`item_no`),
  ADD CONSTRAINT `trigger_point_ibfk_2` FOREIGN KEY (`store_no`) REFERENCES `store` (`store_no`);

--
-- Constraints for table `works_in`
--
ALTER TABLE `works_in`
  ADD CONSTRAINT `works_in_ibfk_1` FOREIGN KEY (`department_id`) REFERENCES `department` (`department_id`),
  ADD CONSTRAINT `works_in_ibfk_2` FOREIGN KEY (`employee_no`) REFERENCES `employee` (`emp_no`),
  ADD CONSTRAINT `works_in_ibfk_3` FOREIGN KEY (`store_no`) REFERENCES `store` (`store_no`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
