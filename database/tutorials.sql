-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 22, 2023 at 03:57 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tutorials`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`) VALUES
(1, 'Web Development'),
(2, 'Data Analysis'),
(3, 'Mobile App Development'),
(4, 'Machine Learning'),
(5, 'Database Design'),
(6, 'Game Development'),
(7, 'Cybersecurity'),
(8, 'React.js'),
(9, 'Data Science'),
(10, 'Cloud Computing');

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

CREATE TABLE `projects` (
  `id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL DEFAULT '',
  `creator` varchar(200) NOT NULL,
  `thumbnailUrl` varchar(300) NOT NULL DEFAULT '',
  `category_id` int(11) NOT NULL,
  `description` text NOT NULL DEFAULT '',
  `uploadDate` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `projects`
--

INSERT INTO `projects` (`id`, `title`, `creator`, `thumbnailUrl`, `category_id`, `description`, `uploadDate`) VALUES
(1, 'Introduction to Web Development', 'temp', 'uploads/1699998455716.jpeg', 1, 'Learn the basics of web development and HTML/CSS.', '2023-11-15 14:23:46'),
(2, 'Python Data Analysis with Pandas', 'karan', 'uploads/1699998457429.png', 2, 'Explore data analysis using Python with the Pandas library.', '2023-11-14 21:51:27'),
(3, 'Mobile App Development with React Native', 'anmol', 'uploads/1699998462683.png', 3, 'Build cross-platform mobile apps using React Native.', '2023-11-14 21:51:35'),
(4, 'Machine Learning Fundamentals', 'karan', 'uploads/1699998464124.jpeg', 4, 'An introduction to the basics of machine learning.', '2023-11-14 21:51:49'),
(5, 'Database Design and SQL Mastery', 'temp', '', 5, 'Learn how to design databases and write efficient SQL queries.', '2023-11-15 14:28:32'),
(6, 'Game Development with Unity', 'karan', 'uploads/1699998467432.png', 6, 'Create your own games using the Unity game development engine.', '2023-11-14 21:52:35'),
(7, 'Cybersecurity Essentials', 'anmol', 'uploads/1699998468907.jpeg', 7, 'Understand the essentials of cybersecurity and online safety.', '2023-11-14 21:52:43'),
(8, 'React.js Crash Course', 'karan', 'uploads/1699998470443.png', 8, 'A quick and comprehensive guide to React.js for web development.', '2023-11-14 21:53:13'),
(9, 'Data Science with TensorFlow', 'anmol', 'uploads/1699998471866.jpeg', 9, 'Explore data science applications using the TensorFlow library.', '2023-11-14 21:53:22'),
(10, 'Cloud Computing Basics with AWS', 'karan', 'uploads/1699998473084.png', 10, 'An overview of cloud computing principles using Amazon Web Services.', '2023-11-14 21:53:37'),
(11, 'How to train you Dragon?', 'temp', 'uploads/1700004323AGC_20230406_103320479.RESTORED.jpg', 0, 'Not the movie', '2023-11-15 00:42:54'),
(19, 'TEst 2', 'temp', '', 1, 'This should have a category', '2023-11-15 00:52:14'),
(21, 'Moderator Test', 'bob', 'uploads/1700058056IMG_20230728_112842.jpg', 1, 'sdsvdsvdsvdsvds', '2023-11-22 00:43:49'),
(22, 'New Tutorial', 'temp', '', 1, 'sdfsdfds', '2023-11-15 14:29:21');

-- --------------------------------------------------------

--
-- Table structure for table `slides`
--

CREATE TABLE `slides` (
  `id` int(11) NOT NULL,
  `num` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `content` text NOT NULL,
  `project_Id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `slides`
--

INSERT INTO `slides` (`id`, `num`, `title`, `content`, `project_Id`) VALUES
(1, 1, 'Getting Started with HTML', '<p>Welcome to the world of web development! Let\'s start with the basics of HTML:</p><ul><li>Create a simple HTML page</li><li>Add headings and paragraphs</li><li>Insert images</li></ul>', 1),
(2, 1, 'Exploring Pandas DataFrames', '<p>Explore the power of Pandas DataFrames:</p><ul><li>Load data into a DataFrame</li><li>Filter and manipulate data</li><li>Perform basic statistical analysis</li></ul>', 2),
(3, 1, 'Setting Up React Native', '<p>Let\'s kickstart your React Native journey:</p><ul><li>Install React Native</li><li>Create a new project</li><li>Understand the project structure</li></ul>', 3),
(4, 1, 'Introduction to Machine Learning', '<p>Dive into the world of machine learning:</p><ul><li>Understand supervised learning</li><li>Explore unsupervised learning</li><li>Overview of common algorithms</li></ul>', 4),
(5, 1, 'Designing Efficient Databases', '<p>Master the art of database design:</p><ul><li>Entity-Relationship Diagrams (ERD)</li><li>Normalization techniques</li><li>Optimizing SQL queries</li></ul>', 5),
(6, 1, 'Creating Your First Game in Unity', '<p>Let\'s build a simple game in Unity:</p><ul><li>Setting up the Unity environment</li><li>Adding game objects and components</li><li>Basic scripting with C#</li></ul>', 6),
(7, 1, 'Protecting Your Online Presence', '<p>Essential tips for online security:</p><ul><li>Strong password practices</li><li>Two-factor authentication (2FA)</li><li>Recognizing phishing attempts</li></ul>', 7),
(8, 1, 'Understanding React Components', '<p>Get started with React.js:</p><ul><li>Introduction to React components</li><li>Creating functional and class components</li><li>Passing props and state management</li></ul>', 8),
(9, 1, 'TensorFlow Basics for Data Science', '<p>Explore TensorFlow for data science:</p><ul><li>TensorFlow basics and installation</li><li>Building and training a simple neural network</li><li>Visualizing model performance</li></ul>', 9),
(10, 1, 'Introduction to AWS Cloud Services', '<p>Discover the fundamentals of cloud computing with AWS:</p><ul><li>Creating an AWS account</li><li>Launching a virtual server (EC2 instance)</li><li>Overview of AWS services</li></ul>', 10),
(218, 1, 'Basics', '<p>Get a dragon from pet Store</p><p>Train it</p><p><br></p>', 18),
(219, 1, 'Test1', '<p>This is an image</p><p>dsvdsvdsv sfb dsvvdsvc<br></p>', 19),
(220, 2, 'TEst2', '<p>param<br></p><p>param<br></p><p>param<br></p><p>param<br></p><p>param<br></p><p>param<br></p><p>param<br></p><p>param<br></p><p>param<br></p><p>param<br></p><p>param<br></p><p>param<br></p>', 19),
(221, 3, '', '<p>paramparamparamparamparamparamparamparamparamparamparamparamparamparamparamparamparamparamparamparamparamparamparamparamparamparamparamparamparamparamparamparamparamparamparamparamparamparamparamparamparamparamparamparamparamparamparamparamparamparamparamparamparamparamparamparamparamparamparamparamparamparamparamparamparamparamparamparamparamparamparamparamparam</p><p></p>', 19),
(222, 1, 'Slide 1', '<p>ascasc<br></p>', 24);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `username` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `hashedPassword` varchar(200) NOT NULL,
  `isModerator` tinyint(1) NOT NULL DEFAULT 0,
  `name` varchar(200) NOT NULL,
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`username`, `email`, `hashedPassword`, `isModerator`, `name`, `id`) VALUES
('karanveer', 'k@t.net', 'aaaaaaaaaaaaaaaaaaaaaaaaa', 0, 'karan', 1),
('Anmol', 'anmol@rrc.ca', '$2y$10$Ys1SaPPhM09j7dEhKvRK.uMx7zQroUJkcO5V60GpYni/.B6bz5QC6', 0, 'Anmol', 3),
('anmolsharma', 'a@n.mols', '$2y$10$VjQdk.OJxmNXFSpVTaDsmOPGNSriYFwWXOoxtN2rFgg3psyCLlaY6', 0, 'Anmol', 4),
('temp', 'temp@temp.com', '$2y$10$8TJDiGsGJZKFTgD0dHzV3OSWbFAsnDnZlu/811KfIDahbDriy44dK', 1, 'Temp', 5),
('bob', 'bob@mail.com', '$2y$10$WCAz.7ldlzBKq0ohZBJkKey7a8Z4IpmlPXacDG9x27x7VBEQY/e8G', 0, 'bob bobberton', 6);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `slides`
--
ALTER TABLE `slides`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `projects`
--
ALTER TABLE `projects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `slides`
--
ALTER TABLE `slides`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=223;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
