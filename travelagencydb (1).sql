-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 15, 2020 at 09:09 PM
-- Server version: 10.4.11-MariaDB
-- PHP Version: 7.4.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `travelagencydb`
--
CREATE DATABASE IF NOT EXISTS `travelagencydb` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `travelagencydb`;

-- --------------------------------------------------------

--
-- Table structure for table `tbljourneys`
--

DROP TABLE IF EXISTS `tbljourneys`;
CREATE TABLE `tbljourneys` (
  `journeyNum` int(11) NOT NULL COMMENT 'מספר טיול',
  `journeyName` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'שם טיול',
  `journeyDescription` varchar(1024) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'תיאור מסלול',
  `journeyStartDate` date NOT NULL COMMENT 'תאריך התחלה',
  `journeyDuration` int(11) NOT NULL COMMENT 'משך הטיול',
  `journeyPrice` decimal(9,2) NOT NULL COMMENT 'מחיר ליחיד',
  `journeyKosher` char(1) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'כשר',
  `journeyAudiancesCode` int(11) NOT NULL COMMENT 'קוד קהל יעד'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tbljourneys`
--

INSERT INTO `tbljourneys` (`journeyNum`, `journeyName`, `journeyDescription`, `journeyStartDate`, `journeyDuration`, `journeyPrice`, `journeyKosher`, `journeyAudiancesCode`) VALUES
(1, 'אלפים', 'טיול להרי האלפים המושלגים', '2020-07-15', 13, '360.00', 'כ', 3),
(2, 'חרמון', 'טיול לחרמון, יציאה מירושלים. עצירה בטבריה לשחייה בכנרת.', '2020-07-19', 1, '48.00', 'ל', 1),
(3, 'מוזיאון ישראל', 'טיול למוזיאון ישראל, ביקור באתרים היסטוריים בהמשך היום.', '2020-07-25', 1, '55.00', 'כ', 2),
(6, 'אלפים סבב 2', 'נסיעה לאלפים בעקבות הצלחה בטיול הראשון', '2020-07-29', 1, '48.00', 'ל', 5),
(9, 'פריז בחורף', 'טיול לפריז היפה', '2020-11-26', 15, '550.00', 'כ', 3),
(11, 'סין בעקבות הקורונה', 'הגעה לסין, לעיירה היוואן ממנה הגיחה מגפת הקורונה. הגעה עם מסכות.', '2020-08-04', 14, '6000.00', 'כ', 1),
(12, 'לונדון בתקופת הקיץ', 'נסיעה ללונדון בתקופת החמה של הקיץ', '2020-08-06', 7, '3000.00', 'כ', 4),
(13, 'טיול בעקבות לוחמים', 'הגעה לירושלים למוזיאון אסירי המחתרות. משם נסיעה קצרה לגבעת התחמושת להרצאה מפי הרמטכ&#34;ל. משם נסיעה להר הרצל ופיזור לבתים.', '2020-08-05', 5, '50.00', 'כ', 4),
(14, 'ים המלח', 'ים המלח בימי הקית החמים. הגעה לחמאם טורקי. טבילה בים המלח ומריחת בוץ.', '2020-08-17', 1, '55.00', 'כ', 2),
(17, 'טבריה', 'נסיעה לטבריה וטבילה בכינרת. נסיעה לנהר הירדן ושיט בקיאקים.', '2020-08-07', 2, '48.00', 'ל', 1),
(18, 'ניגריה בגשם', 'נסיעה לניגריה, ביקור בספארי ובאתרי טבע.', '2020-08-31', 14, '8000.00', 'ל', 4),
(19, 'נתניה בקיץ', 'נסיעה לנתניה, ביקור בקניון ובפלנתניה.', '2020-08-19', 5, '450.00', 'כ', 4),
(28, 'חוף הבונים', 'הגעה לחוף הבונים. לינה באוהלים.', '2020-08-23', 5, '480.00', 'כ', 2),
(30, 'חוף דור', 'הגעה לחוף דור. לינה באוהלים.', '2020-08-30', 5, '480.00', 'כ', 5),
(31, 'נחל הקיבוצים', 'הגעה לנחל, לינה באוהלים.', '2020-09-06', 6, '500.00', 'ל', 3);

-- --------------------------------------------------------

--
-- Table structure for table `tblorders`
--

DROP TABLE IF EXISTS `tblorders`;
CREATE TABLE `tblorders` (
  `orderNum` int(11) NOT NULL COMMENT 'מספר הזמנה',
  `orderUserNum` int(11) NOT NULL COMMENT 'מספר משתמש ',
  `orderJournyNum` int(11) NOT NULL COMMENT 'מספר טיול',
  `orderQuantity` int(11) NOT NULL COMMENT 'כמות מוזמנת',
  `orederDate` date NOT NULL COMMENT 'תאריך הזמנה'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tblorders`
--

INSERT INTO `tblorders` (`orderNum`, `orderUserNum`, `orderJournyNum`, `orderQuantity`, `orederDate`) VALUES
(21, 2, 19, 6, '2020-08-09'),
(22, 4, 19, 5, '2020-08-09'),
(23, 4, 14, 3, '2020-08-09'),
(24, 2, 9, 3, '2020-08-09'),
(26, 2, 18, 5, '2020-08-15');

-- --------------------------------------------------------

--
-- Table structure for table `tblusers`
--

DROP TABLE IF EXISTS `tblusers`;
CREATE TABLE `tblusers` (
  `userNum` int(11) NOT NULL COMMENT 'מספר סידורי',
  `userEmail` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'מייל',
  `userPassword` varchar(12) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'סיסמא',
  `userType` char(1) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'ל' COMMENT 'סוג משתמש',
  `userRealname` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'שם אמיתי'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tblusers`
--

INSERT INTO `tblusers` (`userNum`, `userEmail`, `userPassword`, `userType`, `userRealname`) VALUES
(1, 'shmulika6@gmail.com', '123123', 'מ', 'שמואל אברהם'),
(2, 'fgnbmcs@gmail.com', '123123', 'ל', 'עמיחי'),
(4, 'drorkatz@gmail.com', 'droritkatz', 'ל', 'דרור כץ'),
(5, 'matanosh@gmail.com', '123123123', 'ל', 'מתנוש'),
(6, 'mosh@dror.katz', '1234', 'ל', 'מוש כץ'),
(7, 'galita@gov.il', '123456', 'ל', 'גלית אברהם');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbljourneys`
--
ALTER TABLE `tbljourneys`
  ADD PRIMARY KEY (`journeyNum`);

--
-- Indexes for table `tblorders`
--
ALTER TABLE `tblorders`
  ADD PRIMARY KEY (`orderNum`),
  ADD KEY `orderUserNum` (`orderUserNum`),
  ADD KEY `orderJournyNum` (`orderJournyNum`);

--
-- Indexes for table `tblusers`
--
ALTER TABLE `tblusers`
  ADD PRIMARY KEY (`userNum`),
  ADD UNIQUE KEY `emlKy` (`userEmail`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbljourneys`
--
ALTER TABLE `tbljourneys`
  MODIFY `journeyNum` int(11) NOT NULL AUTO_INCREMENT COMMENT 'מספר טיול', AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `tblorders`
--
ALTER TABLE `tblorders`
  MODIFY `orderNum` int(11) NOT NULL AUTO_INCREMENT COMMENT 'מספר הזמנה', AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `tblusers`
--
ALTER TABLE `tblusers`
  MODIFY `userNum` int(11) NOT NULL AUTO_INCREMENT COMMENT 'מספר סידורי', AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tblorders`
--
ALTER TABLE `tblorders`
  ADD CONSTRAINT `tblorders_ibfk_1` FOREIGN KEY (`orderUserNum`) REFERENCES `tblusers` (`userNum`),
  ADD CONSTRAINT `tblorders_ibfk_2` FOREIGN KEY (`orderJournyNum`) REFERENCES `tbljourneys` (`journeyNum`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
