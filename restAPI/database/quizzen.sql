-- phpMyAdmin SQL Dump
-- version 4.8.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 21, 2018 at 02:26 PM
-- Server version: 10.1.32-MariaDB
-- PHP Version: 7.2.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `quizzen`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `admin_id` int(11) NOT NULL,
  `fname` varchar(100) NOT NULL,
  `mname` varchar(100) DEFAULT NULL,
  `lname` varchar(100) NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`admin_id`, `fname`, `mname`, `lname`, `username`, `password`) VALUES
(21, 'Marianne', 'Abanico', 'de Asis', 'marianne', 'marianne'),
(22, 'Russel', 'Abanico', 'de Asis', 'russel', 'russel'),
(23, 'Chris', '', 'Manuel', 'chrisjoshua1888', 'chrismanuel'),
(24, 'Chris', '', 'Manuel', 'chrisjoshua1889', 'chrismanuel'),
(25, 'marianne', 'abanico', 'de asis', 'marianneisneckdeep', 'mariannemarianne'),
(26, 'russel', 'de asis', 'catajan', 'russel0401', 'russelowen'),
(27, 'russel', 'de asis', 'catajan', 'russel0401', 'asaasaasaasa'),
(28, 'asd', 'asd', 'asd', 'asdsssssss', 'ssssssssss'),
(29, 'aaaaaaasd', 'asdasdasdasd', 'asdasdasdasd', 'asdasdasdasdasd', 'qweqweqweqwe'),
(30, 'sss', 'sss', 'sss', 'ssssssssss', 'ssssssssss');

-- --------------------------------------------------------

--
-- Table structure for table `answer_choices`
--

CREATE TABLE `answer_choices` (
  `choice_id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  `quiz_id` int(11) NOT NULL,
  `value` varchar(200) NOT NULL,
  `post` char(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `answer_choices`
--

INSERT INTO `answer_choices` (`choice_id`, `question_id`, `quiz_id`, `value`, `post`) VALUES
(91, 30, 25, 'ng-pristine', 'a'),
(92, 30, 25, 'ng-dirty', 'b'),
(93, 30, 25, 'ng-ntouched', 'c'),
(94, 30, 25, 'ng-clean', 'd'),
(95, 32, 25, 'ng-touched', 'a'),
(96, 32, 25, 'ng-dirty', 'b'),
(97, 32, 25, 'ng-untouched', 'c'),
(98, 32, 25, 'ng-content', 'd'),
(99, 34, 25, 'that the lord on holier', 'a'),
(100, 34, 25, 'asdasd', 'b'),
(101, 34, 25, 'ng-asdas', 'c'),
(102, 34, 25, 'ng-conastent', 'd'),
(103, 35, 25, 'that the lord on holier', 'a'),
(104, 35, 25, 'asdasd', 'b'),
(105, 35, 25, 'ng-asdas', 'c'),
(106, 35, 25, 'ng-conastent', 'd'),
(107, 36, 25, 'that the lord on holier', 'a'),
(108, 36, 25, 'asdasd', 'b'),
(109, 36, 25, 'ng-asdas', 'c'),
(110, 36, 25, 'ng-conastent', 'd');

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `course_id` int(11) NOT NULL,
  `course` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`course_id`, `course`) VALUES
(1, 'Bachelor of Science in Information and Technology'),
(2, 'Associate in Information Technology'),
(3, 'Bachelor of Library in Information Science'),
(4, 'Bachelor of Science in Computer Technology');

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

CREATE TABLE `questions` (
  `question_id` int(11) NOT NULL,
  `quiz_id` int(11) NOT NULL,
  `part_id` int(11) NOT NULL,
  `question` varchar(1000) NOT NULL,
  `answer` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `questions`
--

INSERT INTO `questions` (`question_id`, `quiz_id`, `part_id`, `question`, `answer`) VALUES
(30, 25, 55, 'What is the class of a form when it doesn\'t have any contents?', '91'),
(31, 25, 56, 'Directives has something to do with AngularJS', 'true'),
(32, 25, 55, 'What is the class of a form when it does have some contents?', '96'),
(33, 25, 55, 'Who is the founder of Apple Inc', 'Steve Jobs'),
(35, 25, 55, 'who am i?', '103'),
(36, 25, 55, 'who am i?', '107');

-- --------------------------------------------------------

--
-- Table structure for table `question_types`
--

CREATE TABLE `question_types` (
  `type_id` int(11) NOT NULL,
  `type` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `question_types`
--

INSERT INTO `question_types` (`type_id`, `type`) VALUES
(1, 'Multiple Choice'),
(2, 'True or False'),
(3, 'Arrange The Sequence'),
(4, 'Guess the Word');

-- --------------------------------------------------------

--
-- Table structure for table `quizzes`
--

CREATE TABLE `quizzes` (
  `quiz_id` int(11) NOT NULL,
  `quiz_title` varchar(250) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `description` varchar(300) DEFAULT NULL,
  `date_created` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `quizzes`
--

INSERT INTO `quizzes` (`quiz_id`, `quiz_title`, `admin_id`, `description`, `date_created`) VALUES
(25, 'Angular Quiz', 21, 'BSIT 3C-G1\'s Angular Quiz', '2018-08-04 09:40:16');

-- --------------------------------------------------------

--
-- Table structure for table `quiz_parts`
--

CREATE TABLE `quiz_parts` (
  `part_id` int(11) NOT NULL,
  `type_id` int(11) NOT NULL,
  `quiz_id` int(11) NOT NULL,
  `part_title` varchar(200) NOT NULL,
  `duration` int(11) NOT NULL,
  `position` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `quiz_parts`
--

INSERT INTO `quiz_parts` (`part_id`, `type_id`, `quiz_id`, `part_title`, `duration`, `position`) VALUES
(55, 1, 25, 'Form Classes', 10, 1),
(56, 2, 25, 'Directives', 10, 2);

-- --------------------------------------------------------

--
-- Table structure for table `sections`
--

CREATE TABLE `sections` (
  `section_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `section` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sections`
--

INSERT INTO `sections` (`section_id`, `course_id`, `section`) VALUES
(1, 1, 'BSIT 3C-G1'),
(2, 1, 'BSIT 4E-G1'),
(3, 2, 'ACT 2S-G2'),
(4, 1, 'BSIT 3A-G1'),
(5, 1, 'BSIT 3I-G2'),
(6, 1, 'BSIT 3C-G2'),
(7, 1, 'BSIT 3K-G1'),
(8, 1, 'BSIT 4G-G1'),
(9, 2, 'sss');

-- --------------------------------------------------------

--
-- Table structure for table `sections_handled`
--

CREATE TABLE `sections_handled` (
  `handling_id` int(11) NOT NULL,
  `host_id` int(11) NOT NULL,
  `section_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sections_handled`
--

INSERT INTO `sections_handled` (`handling_id`, `host_id`, `section_id`) VALUES
(1, 1, 1),
(2, 1, 4),
(3, 3, 2),
(4, 3, 3);

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `student_id` varchar(10) NOT NULL,
  `section_id` int(11) NOT NULL,
  `fname` varchar(100) NOT NULL,
  `mname` varchar(100) DEFAULT NULL,
  `lname` varchar(100) NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'INACTIVE',
  `course_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`student_id`, `section_id`, `fname`, `mname`, `lname`, `status`, `course_id`) VALUES
('', 1, 'qweqwe', 'qeqweqwe', 'qwqweqwe', 'INACTIVE', 1),
('2011154565', 1, 'SAD', 'DASD', 'asd', 'INACTIVE', 0),
('2015100139', 1, 'Marianne', 'Abanico', 'De Asis', 'INACTIVE', 0),
('20151008', 1, 'Chris Joshua', 'Gonzales', 'Manuel', 'INACTIVE', 1),
('234234', 2, '234234', '234234', '234234', 'INACTIVE', 0),
('95959595', 2, 'ssssssssssss', 'ssssssssssssssss', 'ssssssssssssssss', 'INACTIVE', 2);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`admin_id`);

--
-- Indexes for table `answer_choices`
--
ALTER TABLE `answer_choices`
  ADD PRIMARY KEY (`choice_id`);

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`course_id`);

--
-- Indexes for table `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`question_id`);

--
-- Indexes for table `question_types`
--
ALTER TABLE `question_types`
  ADD PRIMARY KEY (`type_id`);

--
-- Indexes for table `quizzes`
--
ALTER TABLE `quizzes`
  ADD PRIMARY KEY (`quiz_id`);

--
-- Indexes for table `quiz_parts`
--
ALTER TABLE `quiz_parts`
  ADD PRIMARY KEY (`part_id`);

--
-- Indexes for table `sections`
--
ALTER TABLE `sections`
  ADD PRIMARY KEY (`section_id`);

--
-- Indexes for table `sections_handled`
--
ALTER TABLE `sections_handled`
  ADD PRIMARY KEY (`handling_id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`student_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `answer_choices`
--
ALTER TABLE `answer_choices`
  MODIFY `choice_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=111;

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `course_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `questions`
--
ALTER TABLE `questions`
  MODIFY `question_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `question_types`
--
ALTER TABLE `question_types`
  MODIFY `type_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `quizzes`
--
ALTER TABLE `quizzes`
  MODIFY `quiz_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `quiz_parts`
--
ALTER TABLE `quiz_parts`
  MODIFY `part_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT for table `sections`
--
ALTER TABLE `sections`
  MODIFY `section_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `sections_handled`
--
ALTER TABLE `sections_handled`
  MODIFY `handling_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
