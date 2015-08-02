-- phpMyAdmin SQL Dump
-- version 4.4.9
-- http://www.phpmyadmin.net
--
-- Host: localhost:8889
-- Generation Time: Aug 02, 2015 at 02:16 AM
-- Server version: 5.5.42
-- PHP Version: 5.6.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `tameras_posc`
--

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `perm_id` int(10) unsigned NOT NULL,
  `perm_desc` varchar(50) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=60 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`perm_id`, `perm_desc`) VALUES
(1, 'addAttr'),
(2, 'addColor'),
(3, 'addDept'),
(4, 'addDesc'),
(5, 'addIntlCode'),
(6, 'addItem'),
(7, 'addItemFull'),
(8, 'addItemLocation'),
(9, 'addItemLocationAdmin'),
(10, 'addSeason'),
(11, 'addSubDept'),
(12, 'addVend'),
(13, 'admin'),
(14, 'barcode'),
(15, 'bulkItemEdit'),
(16, 'changePassword'),
(17, 'costReport'),
(18, 'duplicatesReport'),
(19, 'editItem'),
(20, 'editItemLocation'),
(21, 'editItemLocationAdmin'),
(22, 'eodReport'),
(23, 'exchangeBox'),
(24, 'fullReport'),
(25, 'historyReport'),
(26, 'inventoryReport'),
(27, 'invoiceDetail'),
(28, 'invoicesReport'),
(29, 'itemList'),
(30, 'itemSizeStock'),
(31, 'itemsUpload'),
(32, 'manageLocations'),
(33, 'manageTarget'),
(34, 'manageVouchers'),
(35, 'merchandise'),
(36, 'receivingBox'),
(37, 'returnBox'),
(38, 'returnBox2'),
(39, 'returnBoxFull'),
(40, 'salesBox'),
(41, 'salesMDAM'),
(42, 'salesMDBR'),
(43, 'salesMDRM'),
(44, 'salesMDSD'),
(45, 'salesReport'),
(46, 'searchItemLocation'),
(47, 'selectBranch'),
(48, 'sizeSwap'),
(49, 'specialReturn'),
(50, 'specialSales'),
(51, 'specialTransfer'),
(52, 'stockAdmin'),
(53, 'stockReport'),
(54, 'storesSalesReport'),
(55, 'transReport'),
(56, 'transferBox'),
(57, 'transferLogReport'),
(58, 'userManager'),
(59, 'zone');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `role_id` int(10) unsigned NOT NULL,
  `role_name` varchar(50) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`role_id`, `role_name`) VALUES
(1, 'Sales'),
(2, 'Analyst'),
(3, 'Admin'),
(4, 'SuperAdmin'),
(5, 'Warehouse');

-- --------------------------------------------------------

--
-- Table structure for table `role_perm`
--

CREATE TABLE `role_perm` (
  `role_id` int(10) unsigned NOT NULL,
  `perm_id` int(10) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `user_role`
--

CREATE TABLE `user_role` (
  `user_id` int(10) unsigned NOT NULL,
  `role_id` int(10) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`perm_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`role_id`);

--
-- Indexes for table `role_perm`
--
ALTER TABLE `role_perm`
  ADD KEY `role_id` (`role_id`),
  ADD KEY `perm_id` (`perm_id`);

--
-- Indexes for table `user_role`
--
ALTER TABLE `user_role`
  ADD KEY `user_id` (`user_id`),
  ADD KEY `role_id` (`role_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `perm_id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=60;
--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `role_id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `role_perm`
--
ALTER TABLE `role_perm`
  ADD CONSTRAINT `role_perm_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`role_id`),
  ADD CONSTRAINT `role_perm_ibfk_2` FOREIGN KEY (`perm_id`) REFERENCES `permissions` (`perm_id`);

--
-- Constraints for table `user_role`
--
ALTER TABLE `user_role`
  ADD CONSTRAINT `user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `role_id` FOREIGN KEY (`role_id`) REFERENCES `roles` (`role_id`);

DELIMITER $$
--
-- Events
--
CREATE DEFINER=`root`@`localhost` EVENT `Remove Stock` ON SCHEDULE EVERY 1 DAY STARTS '2015-06-30 05:00:00' ON COMPLETION NOT PRESERVE ENABLE DO DELETE FROM full_report WHERE `data` = 'Stock'$$

CREATE DEFINER=`root`@`localhost` EVENT `Add Stock` ON SCHEDULE EVERY 1 DAY STARTS '2015-06-30 05:30:00' ON COMPLETION NOT PRESERVE ENABLE DO INSERT INTO full_report (
`data`,`store`,`from`,`to`,`date`,`year`,`month`,`item`,
`size`,`dept`,`subdept`,`desc1`,`desc2`,`gend`,`attr`,`vend`,
`season`,`invo_no`,`trns_no`,`qty`,`msrp`,`rtp`,`cost`,`t.rtp`,
`t.cost`,`payment`,`status`
) SELECT 'Stock', locations.short_desc, '', '', '', '', '', warehouses.item_id,
items_size.desc, items_dept.desc, items_sub_dept.desc, items.item_name,
items_desc.desc, items_gender.desc, items_attr.desc, items_vendor.long_desc,
items_season.desc, '', '', SUM(warehouses.qty), items.msrp, items.rtp,
items.item_cost, SUM(warehouses.qty)*items.rtp, SUM(warehouses.qty)*items.item_cost,
'', ''
FROM warehouses
JOIN items ON warehouses.item_id = items.item_id
LEFT JOIN items_dept ON items.dept_id = items_dept.dept_id
LEFT JOIN items_desc ON items.desc_id = items_desc.desc_id
LEFT JOIN locations  ON warehouses.wrhs_id = locations.loc_id
LEFT JOIN items_size ON warehouses.size_id = items_size.size_id
LEFT JOIN items_gender ON items.gender_id = items_gender.gender_id
LEFT JOIN items_sub_dept ON items.sub_dept_id = items_sub_dept.sub_dept_id
LEFT JOIN items_attr ON items.attr_id = items_attr.attr_id
LEFT JOIN items_vendor ON items.vend_id = items_vendor.vend_id
LEFT JOIN items_season ON items.season_id = items_season.season_id
WHERE warehouses.qty > 0
GROUP BY warehouses.item_id, warehouses.size_id, warehouses.wrhs_id$$

CREATE DEFINER=`root`@`localhost` EVENT `Add Invoices` ON SCHEDULE EVERY 1 DAY STARTS '2015-06-30 06:00:00' ON COMPLETION NOT PRESERVE ENABLE DO INSERT INTO full_report (
`data`,`store`,`from`,`to`,`date`,`year`,`month`,`item`,
`size`,`dept`,`subdept`,`desc1`,`desc2`,`gend`,`attr`,`vend`,
`season`,`invo_no`,`trns_no`,`qty`,`msrp`,`rtp`,`cost`,`t.rtp`,
`t.cost`,`payment`,`status`
) SELECT trans_types.desc, locations.short_desc AS loc, '' as x, '' as y, invoice_header.date,
YEAR(invoice_header.date), MONTH(invoice_header.date), invoice_detail.item_id,
items_size.desc, items_dept.desc, items_sub_dept.desc, items.item_name,
items_desc.desc, items_gender.desc, items_attr.desc, items_vendor.long_desc,
items_season.desc, invoice_detail.invo_no, '' as z, invoice_detail.qty, items.msrp AS msrp,
invoice_detail.rtp, inventory_detail.cost, invoice_detail.rtp*invoice_detail.qty,
inventory_detail.cost*invoice_detail.qty, payment_types.desc, '' as m
FROM invoice_detail
JOIN invoice_header ON invoice_detail.invo_no = invoice_header.invo_no
AND invoice_detail.loc_id = invoice_header.loc_id
LEFT JOIN trans_types ON invoice_detail.type = trans_types.trans_type_id
LEFT JOIN payment_types ON invoice_header.payment_type_id = payment_types.payment_type_id
LEFT JOIN locations  ON invoice_detail.loc_id = locations.loc_id
LEFT JOIN items_size ON invoice_detail.size_id = items_size.size_id
LEFT JOIN  inventory_header ON inventory_header.invo_no  = invoice_detail.invo_no
AND   inventory_header.wrhs_id = invoice_detail.loc_id
JOIN items ON invoice_detail.item_id = items.item_id
LEFT JOIN  inventory_detail ON inventory_header.trans_no = inventory_detail.trans_no
AND   inventory_header.wrhs_id = inventory_detail.wrhs_id
AND   inventory_detail.item_id = invoice_detail.item_id
                AND   inventory_detail.serial  = invoice_detail.serial
LEFT JOIN items_dept ON items.dept_id = items_dept.dept_id
LEFT JOIN items_desc ON items.desc_id = items_desc.desc_id
LEFT JOIN items_gender ON items.gender_id = items_gender.gender_id
LEFT JOIN items_sub_dept ON items.sub_dept_id = items_sub_dept.sub_dept_id
LEFT JOIN items_attr ON items.attr_id = items_attr.attr_id
LEFT JOIN items_vendor ON items.vend_id = items_vendor.vend_id
LEFT JOIN items_season ON items.season_id = items_season.season_id
WHERE invoice_header.date = CAST(NOW( ) - INTERVAL 1 DAY AS DATE)
ORDER BY invoice_detail.invo_no ASC$$

CREATE DEFINER=`root`@`localhost` EVENT `Add Inventory` ON SCHEDULE EVERY 1 DAY STARTS '2015-06-30 06:30:00' ON COMPLETION NOT PRESERVE ENABLE DO INSERT INTO full_report (
`data`,`store`,`from`,`to`,`date`,`year`,`month`,`item`,
`size`,`dept`,`subdept`,`desc1`,`desc2`,`gend`,`attr`,`vend`,
`season`,`invo_no`,`trns_no`,`qty`,`msrp`,`rtp`,`cost`,`t.rtp`,
`t.cost`,`payment`,`status`
) SELECT trans_types.desc, loc_from.short_desc AS `loc_from`,
loc_from.short_desc AS `loc_from`, loc_to.short_desc AS `loc_to`,
inventory_header.date, YEAR(inventory_header.date), MONTH(inventory_header.date),
inventory_detail.item_id, items_size.desc, items_dept.desc, items_sub_dept.desc,
items.item_name, items_desc.desc, items_gender.desc, items_attr.desc,
items_vendor.long_desc, items_season.desc, '', inventory_detail.trans_no,
inventory_detail.qty, items.msrp, inventory_detail.rtp, inventory_detail.cost,
inventory_detail.rtp*inventory_detail.qty, inventory_detail.cost*inventory_detail.qty,
'', status.desc
FROM inventory_detail
JOIN inventory_header ON inventory_detail.trans_no = inventory_header.trans_no
AND inventory_detail.wrhs_id = inventory_header.wrhs_id
JOIN trans_types ON inventory_detail.type = trans_types.trans_type_id
JOIN locations AS loc_from ON inventory_header.from_wrhs_id = loc_from.loc_id
JOIN locations AS loc_to ON inventory_header.to_wrhs_id = loc_to.loc_id
LEFT JOIN items_size ON inventory_detail.size_id = items_size.size_id
JOIN items ON inventory_detail.item_id = items.item_id
LEFT JOIN items_dept ON items.dept_id = items_dept.dept_id
LEFT JOIN items_desc ON items.desc_id = items_desc.desc_id
LEFT JOIN items_gender ON items.gender_id = items_gender.gender_id
LEFT JOIN items_sub_dept ON items.sub_dept_id = items_sub_dept.sub_dept_id
LEFT JOIN items_attr ON items.attr_id = items_attr.attr_id
LEFT JOIN items_vendor ON items.vend_id = items_vendor.vend_id
LEFT JOIN items_season ON items.season_id = items_season.season_id
LEFT JOIN status ON inventory_header.status = status.status_id
WHERE inventory_header.date = CAST(NOW( ) - INTERVAL 1 DAY AS DATE)
ORDER BY inventory_detail.trans_no ASC$$

DELIMITER ;
