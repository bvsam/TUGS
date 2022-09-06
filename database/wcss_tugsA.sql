-- phpMyAdmin SQL Dump
-- version 4.9.5deb2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Sep 02, 2022 at 06:10 PM
-- Server version: 8.0.30-0ubuntu0.20.04.2
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
-- Database: `wcss_tugsA`
--

-- --------------------------------------------------------

--
-- Table structure for table `awards`
--

CREATE TABLE `awards` (
  `award_id` int NOT NULL,
  `award_name` varchar(128) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `awards`
--

INSERT INTO `awards` (`award_id`, `award_name`) VALUES
(1, 'Silver Medal'),
(2, 'WCSS Book Award'),
(3, 'Soccer Player Award'),
(4, 'Baketball Hoops Award'),
(5, 'Most Improved Award'),
(6, 'Leadership Award');

-- --------------------------------------------------------

--
-- Table structure for table `issues`
--

CREATE TABLE `issues` (
  `issues_id` int NOT NULL,
  `issues_email` varchar(128) NOT NULL,
  `issues_note` varchar(10240) NOT NULL,
  `issues_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `issues_stud_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `issues`
--

INSERT INTO `issues` (`issues_id`, `issues_email`, `issues_note`, `issues_timestamp`, `issues_stud_id`) VALUES
(237, 'hsath1@ocdsb.ca', 'BMW 440i - 400HP - 386 WHP - 0  to 60 in 4.1 Seconds.', '2022-06-13 16:09:28', 2),
(238, 'zyao1@ocdsb.ca', 'Hello Mr Dunn, \\n\\nRandom is creating a sick image of you. Cheers', '2022-06-20 17:42:40', 3),
(239, 'hoot@goe.com', 'WIJDWIOJDIOJW', '2022-08-04 19:35:12', NULL),
(240, 'dawdadawd@og.com', 'awdawdaw', '2022-08-04 19:37:54', NULL),
(241, 'tftyfytf@ok.com', 'Hello\\nthis\\nis\\nme\\n\\nBye', '2022-08-04 19:39:32', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `login`
--

CREATE TABLE `login` (
  `login_id` int NOT NULL,
  `login_email` varchar(128) NOT NULL,
  `login_pass` varchar(256) NOT NULL,
  `login_isAdmin` tinyint NOT NULL,
  `login_stud_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `login`
--

INSERT INTO `login` (`login_id`, `login_email`, `login_pass`, `login_isAdmin`, `login_stud_id`) VALUES
(1, 'test', '$2y$10$N3l1sCv7Q2WERbWXRxuVUuQ0v51QB8hmvsXldpnWiojlk3vE8mNpq', 1, NULL),
(2, 'hsath1@ocdsb.ca', '$2y$10$eeMFo7JLD1cB1x4o447tLO.gThMCH3IxH90WhO3eeJXMRNpMqT9QS', 0, 2),
(3, 'aalch4@ocdsb.ca', '$2y$10$ehHCSDaW3NSddIweRRJqY.ehj.eO8K/.KhlGVw.WDrn2Y4pMkmt.G', 0, 1),
(4, 'zyao1@ocdsb.ca', '$2y$10$HX31/gWzORE/17aZ9HnB1O7mmLIt0hCoV5CX59oLGut569cKzOdwq', 0, 3),
(6, 'ocobb1@ocdsb.ca', '$2y$10$Ezjgs9I8/2JAjzkCiqqJrejXRca74nz30hGMwQWxgdxDGCqg6ArdO', 0, 6),
(9, 'nbrad2@ocdsb.ca', '$2y$10$8b8B.ADZrwtX6F5OFdqa7uew4JTvOzObJiHUBWRUZCl7eWmkDsBqC', 0, 8),
(16, 'mzeng4@ocdsb.ca', '$2y$10$vIssPL2WgYPqnrxuVFPExeZ/WHMYUzo3Ze7/P326oAoqp0WotBGz2', 0, 27),
(93, 'hbar@ocdsb.ca', '$2y$10$rv0qvcF7SW2GEcoyamd35uiDnzDA3QE5epYgVhahM.KC5yQ4kKAmi', 0, 332890646),
(94, 'tbar@ocdsb.ca', '$2y$10$cePwahSCIq/GlOMZwOfNhe6dwaDbHtssJX0KCekREsEz/qlXM/AmO', 0, 332890647),
(95, 'hsmit@ocdsb.ca', '$2y$10$hQUQmm6CNZcCGNvalfjdSOAVu5J5W5YgqHlRnMj7Arf44lYTz8GCy', 0, 332890648),
(96, 'bsmit@ocdsb.ca', '$2y$10$l/0pGUWPD8G048X8KciEJuh41l//zAUUUvfQlRkQTyLWOQcRGVLUC', 0, 332890649),
(97, 'jdoe4@ocdsb.ca', '$2y$10$y6vN67URk170JEE8mslnbOI.0Qyx5TgpiNgevl9Aavvw2spN4q4BS', 0, 332890650);

-- --------------------------------------------------------

--
-- Table structure for table `logs`
--

CREATE TABLE `logs` (
  `log_id` int NOT NULL,
  `log_stud_id` int NOT NULL,
  `log_activityType` varchar(1024) NOT NULL,
  `log_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `logs`
--

INSERT INTO `logs` (`log_id`, `log_stud_id`, `log_activityType`, `log_timestamp`) VALUES
(1, 1, 'Edit ', '2022-04-28 18:10:09'),
(2, 2, 'Saved', '2022-04-28 18:10:17'),
(3, 3, 'Submitted', '2022-04-28 18:11:20'),
(4, 2, 'Login', '2022-09-02 17:21:03'),
(5, 5, 'Login', '2022-09-02 17:23:46'),
(6, 2, 'Logout', '2022-09-02 17:24:09'),
(7, 2, 'Login', '2022-09-02 17:24:29'),
(8, 2, 'Saved', '2022-09-02 17:24:46'),
(9, 2, 'Saved', '2022-09-02 17:25:00'),
(10, 2, 'Saved', '2022-09-02 17:25:18'),
(11, 2, 'Logout', '2022-09-02 17:25:29'),
(12, 1, 'Login', '2022-09-02 17:25:42'),
(13, 1, 'Logout', '2022-09-02 17:25:52'),
(14, 1, 'Login', '2022-09-02 17:25:59'),
(15, 1, 'Saved', '2022-09-02 17:26:16'),
(16, 1, 'Logout', '2022-09-02 17:26:22'),
(17, 332890650, 'Login', '2022-09-02 19:25:25'),
(18, 332890650, 'Logout', '2022-09-02 19:25:48'),
(19, 332890650, 'Login', '2022-09-02 21:03:53'),
(20, 332890650, 'Logout', '2022-09-02 21:04:27');

-- --------------------------------------------------------

--
-- Table structure for table `mailQueue`
--

CREATE TABLE `mailQueue` (
  `mail_id` int NOT NULL,
  `mail_toAddr` varchar(255) NOT NULL,
  `mail_toName` varchar(255) NOT NULL,
  `mail_fromName` varchar(255) NOT NULL,
  `mail_subj` varchar(1024) NOT NULL,
  `mail_body` varchar(10240) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pSlides`
--

CREATE TABLE `pSlides` (
  `slide_id` int NOT NULL,
  `slide_prepost` int NOT NULL,
  `slide_content` varchar(16000) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '',
  `slide_enabled` tinyint NOT NULL DEFAULT '1',
  `slide_name` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `pSlides`
--

INSERT INTO `pSlides` (`slide_id`, `slide_prepost`, `slide_content`, `slide_enabled`, `slide_name`) VALUES
(291, 0, '<h1 style=\"text-align: center;\">&nbsp;</h1>\n<h1 style=\"text-align: center;\">WCSS Graduation Ceremony 2022</h1>\n<p style=\"text-align: center;\"><strong><img src=\"https://internal.westcarletonss.ca/grad/assets/ShowManagementLogos/wcsslogo.png\"></strong></p>', 1, 'Intro Slide'),
(294, 0, '<h1 style=\"text-align: center;\">&nbsp;</h1>\n<h1 style=\"text-align: center;\">&nbsp;</h1>\n<h1 style=\"text-align: center;\">&nbsp;</h1>\n<h1 style=\"text-align: center;\">&nbsp;</h1>\n<h1 style=\"text-align: center;\">&nbsp;</h1>\n<h1 style=\"text-align: center;\">Principal\'s Address</h1>\n<p>&nbsp;</p>', 1, 'Principal Slide'),
(307, 0, '<h1 style=\"text-align: center;\">Valedictorian Slide</h1>\n<h3 style=\"text-align: center;\">This year\'s Valedictorian is Tom Hanks!</h3>\n<p><img style=\"display: block; margin-left: auto; margin-right: auto;\" src=\"https://m.media-amazon.com/images/M/MV5BMTQ2MjMwNDA3Nl5BMl5BanBnXkFtZTcwMTA2NDY3NQ@@._V1_.jpg\" alt=\"Tom Hanks\" width=\"292\" height=\"422\"></p>\n<h4 style=\"text-align: center;\">Well Done, Tom!</h4>', 1, 'Valedictorian'),
(313, 0, '', 0, 'Intro Speech'),
(314, 1, '', 0, 'Land Acknowledgement'),
(315, 1, '', 0, 'O Canada Slide'),
(316, 1, '', 1, 'THIS NEW ONE'),
(324, 1, '<h1 style=\"text-align: center;\">&nbsp;</h1>\n<h1 style=\"text-align: center;\">&nbsp;</h1>\n<h1 style=\"text-align: center;\">&nbsp;</h1>\n<h1 style=\"text-align: center;\">&nbsp;</h1>\n<h1 style=\"text-align: center;\"><span style=\"text-decoration: underline;\"><strong>Thank You!</strong></span></h1>', 1, 'Thank You Slide');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `setting_id` int NOT NULL,
  `setting_name` varchar(128) NOT NULL,
  `setting_value` varchar(10240) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`setting_id`, `setting_name`, `setting_value`) VALUES
(4, 'pictureFadeTime', '1000'),
(5, 'studentDeadline', '2023-09-30'),
(7, 'currentYear', '2022'),
(8, 'preOrder', '[\"291\",\"294\",\"307\"]'),
(12, 'postOrder', '[\"324\"]'),
(13, 'disabledOrder', '[\"313\",\"314\",\"315\"]'),
(14, 'announcementName', 'To All Students!'),
(15, 'announcementValue', 'The deadline for editing your graduation slide is currently 2022-09-30. Make sure to finish yours before the deadline!'),
(16, 'slideBackgroundColour', '#000000'),
(17, 'slideTextColour', '#ffffff'),
(18, 'fullPath', '/var/www/html/tugsA/studentPics/');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `stud_id` int NOT NULL,
  `stud_num` varchar(16) NOT NULL,
  `stud_lname` varchar(64) NOT NULL,
  `stud_fname` varchar(64) NOT NULL,
  `stud_enabled` tinyint NOT NULL DEFAULT '1',
  `stud_awards` varchar(1024) NOT NULL DEFAULT '',
  `stud_plans` varchar(1024) NOT NULL DEFAULT '',
  `stud_scholarships` varchar(1024) NOT NULL DEFAULT '',
  `stud_memMoments` varchar(10240) NOT NULL DEFAULT '',
  `stud_year` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`stud_id`, `stud_num`, `stud_lname`, `stud_fname`, `stud_enabled`, `stud_awards`, `stud_plans`, `stud_scholarships`, `stud_memMoments`, `stud_year`) VALUES
(1, '332890144', 'Al Choughri', 'Abdurrahman', 1, '[\"Ontario Secondary School Diploma\",\"Silver Medal\"]', '[\"University\",\"University of Toronto\",\"Mechanical Engineering\"]', '[\"Entrance Scholarship\"]', '<p style=\"text-align: left;\"><strong>-Helped plant trees infront of WCSS</strong></p>\n<p style=\"text-align: left;\"><strong>-Piling sand in front of the school</strong></p>', 2022),
(2, '332890145', 'Sathiyalingam', 'Hariram', 1, '[\"Silver Medal\",\"WCSS Books Award\",\"Soccer Player Award\"]', '[\"University\",\"Carleton University\",\"Business and Administration\"]', '[\"Entrance\"]', '<p><strong>My final project in English class<br></strong></p>', 2022),
(3, '332890146', 'Yao', 'Ryan', 1, '[\"Silver Medal\",\"Leadership Award\"]', '[\"Work\",\"Ryan\'s Workshop\"]', '[]', '<p>-Playing basketball in the winter</p>\n<p>-Losing intramurals volleyball 5 times in a row</p>', 2022),
(6, '332890149', 'Cobblepot', 'Oswald', 1, '[\"Ontario Secondary School Diploma\",\"Honour Roll\",\"Leadership Award\"]', '[\"University\",\"Gotham University\",\"Marine Biology\"]', '[\"Entrance Scholarship\",\"Ontario Trillium Scholarship\"]', '<p><strong>-Tournament of Champions 2022</strong></p>\n<p>-My 18th birthday</p>', 2022),
(8, '332890139', 'Bradley', 'Nicolaj', 0, '[\"Silver Medal\"]', '[\"College\",\"Algonquin\",\"Economics\"]', '[]', '', 2022),
(27, '10000000', 'Zeng', 'Mathew', 1, '[\"WCSS Book Award\",\"Soccer Player Award\",\"Leadership Award\"]', '[\"College\",\"Reed College\",\"Nursing\"]', '[\"Entrance Scholarship\",\"Outstanding Student Scholarship\"]', '<p><strong>-Reading my speech in front of the class</strong></p>\n<p><strong>-Cultural Day</strong></p>', 2022),
(332890646, '95726898', 'Harry', 'Bot', 0, '[]', '[]', '[]', '', 2022),
(332890647, '92483795', 'Trisha', 'Bot', 0, '[]', '[]', '[]', '', 2022),
(332890648, '985279048', 'Smith', 'Hol', 1, '[\"Honour Roll\",\"Ontario Secondary School Diploma\",\"Ontario Scholar\",\"Silver Medal\"]', '[\"University\",\"University of Toronto\",\"CS\"]', '[\"ALSO BEST STUDENT\",\"TRack scholarship\"]', '<p>YOOOO finally done here ;)</p>', 2026),
(332890649, '04838261939', 'Smith', 'Ball', 1, '[\"Honour Roll\",\"Ontario Scholar\"]', '[\"College\",\"Toronto College\",\"Nursing\"]', '[\"WOWOW\",\"BEst sutdent\"]', '<p>I had a great ime =)))))</p>\n<p>Shout out to...</p>', 2026),
(332890650, '937459212', 'Doe', 'John', 1, '[\"Silver Medal\",\"Baketball Hoops Award\",\"Most Improved Award\",\"Leadership Award\"]', '[\"University\",\"City University\",\"Computer Engineering\"]', '[\"Entrance Scholarship\",\"Principal\'s Scholarship\"]', '<p>-Tournament of Champions</p>\n<p>-Gym class</p>\n<p>-Chem. class</p>', 2022);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `awards`
--
ALTER TABLE `awards`
  ADD PRIMARY KEY (`award_id`);

--
-- Indexes for table `issues`
--
ALTER TABLE `issues`
  ADD PRIMARY KEY (`issues_id`),
  ADD KEY `issues_stud_id` (`issues_stud_id`);

--
-- Indexes for table `login`
--
ALTER TABLE `login`
  ADD PRIMARY KEY (`login_id`),
  ADD KEY `login_stud_id` (`login_stud_id`);

--
-- Indexes for table `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `log_stud_id` (`log_stud_id`);

--
-- Indexes for table `mailQueue`
--
ALTER TABLE `mailQueue`
  ADD PRIMARY KEY (`mail_id`);

--
-- Indexes for table `pSlides`
--
ALTER TABLE `pSlides`
  ADD PRIMARY KEY (`slide_id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`setting_id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`stud_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `awards`
--
ALTER TABLE `awards`
  MODIFY `award_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `issues`
--
ALTER TABLE `issues`
  MODIFY `issues_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=258;

--
-- AUTO_INCREMENT for table `login`
--
ALTER TABLE `login`
  MODIFY `login_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=98;

--
-- AUTO_INCREMENT for table `logs`
--
ALTER TABLE `logs`
  MODIFY `log_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `mailQueue`
--
ALTER TABLE `mailQueue`
  MODIFY `mail_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `pSlides`
--
ALTER TABLE `pSlides`
  MODIFY `slide_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=337;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `setting_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `stud_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=332890651;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
