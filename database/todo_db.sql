-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 06, 2026 at 11:43 AM
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
-- Database: `todo_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `email_queue`
--

CREATE TABLE `email_queue` (
  `id` int(11) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `subject` text DEFAULT NULL,
  `body` text DEFAULT NULL,
  `status` enum('pending','sent') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `email_queue`
--

INSERT INTO `email_queue` (`id`, `email`, `name`, `subject`, `body`, `status`, `created_at`) VALUES
(1, 'ishusitikhu6@gmail.com', 'Ishu', 'New Task Assigned - TODO Project', '\r\n        <div style=\'font-family: Arial; padding:15px\'>\r\n            <h2>New Task Assigned</h2>\r\n\r\n            <p>Hello <b>Ishu</b>,</p>\r\n\r\n            <p>You have been assigned a new task.</p>\r\n\r\n            <hr>\r\n\r\n            <p><b>Project:</b> TODO Project</p>\r\n            <p><b>Task:</b> manage user</p>\r\n            <p><b>Assigned By:</b> Admin</p>\r\n            <p><b>Status:</b> Pending</p>\r\n\r\n            <hr>\r\n\r\n            <p style=\'color:gray\'>Please login to update task status.</p>\r\n        </div>\r\n    ', 'sent', '2026-04-28 10:57:14'),
(2, 'ishusitikhu6@gmail.com', 'Ishu', 'New Task Assigned - TODO Project', '\r\n        <div style=\'font-family: Arial; padding:15px\'>\r\n            <h2>New Task Assigned</h2>\r\n\r\n            <p>Hello <b>Ishu</b>,</p>\r\n\r\n            <p>You have been assigned a new task.</p>\r\n\r\n            <hr>\r\n\r\n            <p><b>Project:</b> TODO Project</p>\r\n            <p><b>Task:</b> MAnage Backend</p>\r\n            <p><b>Assigned By:</b> Admin</p>\r\n            <p><b>Status:</b> Pending</p>\r\n\r\n            <hr>\r\n\r\n            <p style=\'color:gray\'>Please login to update task status.</p>\r\n        </div>\r\n    ', 'sent', '2026-04-28 11:05:55'),
(3, 'ishusitikhu6@gmail.com', 'Ishu', 'New Task Assigned - TODO Project', '\r\n        <div style=\'font-family: Arial; padding:15px\'>\r\n            <h2>New Task Assigned</h2>\r\n\r\n            <p>Hello <b>Ishu</b>,</p>\r\n\r\n            <p>You have been assigned a new task.</p>\r\n\r\n            <hr>\r\n\r\n            <p><b>Project:</b> TODO Project</p>\r\n            <p><b>Task:</b> PHP Laravel</p>\r\n            <p><b>Assigned By:</b> Admin</p>\r\n            <p><b>Status:</b> Pending</p>\r\n\r\n            <hr>\r\n\r\n            <p style=\'color:gray\'>Please login to update task status.</p>\r\n        </div>\r\n    ', 'pending', '2026-04-28 11:12:47');

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `start` date DEFAULT NULL,
  `end` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `title`, `start`, `end`, `created_at`) VALUES
(42, 'GANESHA SCHOOL PROGRAM', '2026-05-17', '2026-05-23', '2026-05-06 08:53:09'),
(43, 'KRISHNA JANMASTAMI', '2026-05-07', '2026-05-07', '2026-05-06 09:22:25');

-- --------------------------------------------------------

--
-- Table structure for table `event_users`
--

CREATE TABLE `event_users` (
  `id` int(11) NOT NULL,
  `event_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

CREATE TABLE `projects` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `projects`
--

INSERT INTO `projects` (`id`, `title`, `description`, `created_at`) VALUES
(38, 'TODO Projecttt', 'helloo', '2026-04-28 07:22:19'),
(41, 'TIL', 'asjhkl', '2026-04-29 05:19:32'),
(42, 'ABC COMPANY', 'HII', '2026-05-04 06:39:37');

-- --------------------------------------------------------

--
-- Table structure for table `project_tasks`
--

CREATE TABLE `project_tasks` (
  `id` int(11) NOT NULL,
  `project_id` int(11) DEFAULT NULL,
  `task` varchar(255) DEFAULT NULL,
  `status` varchar(20) DEFAULT 'pending',
  `assigned_user_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `assigned_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `project_tasks`
--

INSERT INTO `project_tasks` (`id`, `project_id`, `task`, `status`, `assigned_user_id`, `created_at`, `assigned_by`) VALUES
(11, 38, 'Database', 'completed', 8, '2026-04-28 07:22:48', NULL),
(15, 38, 'Ui', 'pending', 25, '2026-04-28 07:52:00', NULL),
(29, 38, 'MAnage Backend', 'pending', 8, '2026-04-28 11:05:55', 1),
(30, 38, 'PHP Laravel', 'pending', 8, '2026-04-28 11:12:47', 1),
(32, 38, 'manager', 'pending', 8, '2026-04-29 02:51:16', 1),
(33, 38, 'PHP Laravel', 'pending', 8, '2026-04-29 04:37:48', 1),
(46, 41, 'GAME', 'pending', 6, '2026-05-03 17:45:50', 1),
(47, 42, 'ABC', 'pending', 7, '2026-05-04 06:40:17', 1),
(48, 42, 'Delete', 'pending', 29, '2026-05-04 12:33:54', 1);

-- --------------------------------------------------------

--
-- Table structure for table `project_users`
--

CREATE TABLE `project_users` (
  `id` int(11) NOT NULL,
  `project_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `project_users`
--

INSERT INTO `project_users` (`id`, `project_id`, `user_id`) VALUES
(24, 38, 8),
(26, 42, 7),
(27, 42, 29);

-- --------------------------------------------------------

--
-- Table structure for table `task_users`
--

CREATE TABLE `task_users` (
  `id` int(11) NOT NULL,
  `task_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `todos`
--

CREATE TABLE `todos` (
  `id` int(11) NOT NULL,
  `task` varchar(255) DEFAULT NULL,
  `status` enum('pending','completed') DEFAULT 'pending',
  `user_id` int(11) DEFAULT NULL,
  `assigned_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `todos`
--

INSERT INTO `todos` (`id`, `task`, `status`, `user_id`, `assigned_by`) VALUES
(12, 'HOMEWORK', 'pending', NULL, NULL),
(15, 'HAndle Social Media', 'pending', 6, 1),
(17, 'abc', 'pending', 4, 1),
(20, 'php', 'pending', 8, 1),
(21, 'php laravel', 'pending', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` enum('admin','staff') DEFAULT 'staff'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`) VALUES
(1, 'Admin', 'admin@gmail.com', '$2y$10$K5QjSosxnaNlmz6GESnqP.KGVvN3OlM03H0R4fz0rvjuBRY1/vnfm', 'admin'),
(3, 'Test1', 'test1@gmail.com', '$2y$10$65/NixsKhKLtaWwiGZmggemOX6YBv/bQ.fdmctLSTRzSs6TMYCr7e', 'staff'),
(6, 'ram', 'ram@gmail.com', '$2y$10$0LlyaZjiieuxvck7kPoCzuqERD1XIIxbbF5f6.Zl/rEmWMqYRuCgC', 'staff'),
(7, 'abc', 'abc@gmail.com', '$2y$10$DlREsgLtCXzJDrbOyn2treeABMw4KKvOK9iTzqDg6vMrDdeGk1kSO', 'staff'),
(8, 'Ishu', 'ishusitikhu6@gmail.com', '$2y$10$xtq4HD5soGvjbVQETgm7Gu6S2XRa7qUZNlOeu5.sYqaBa1VdwYfx6', 'staff'),
(25, 'Krishna', 'Krishna@gmail.com', '$2y$10$1sLBqBR8PnCFnbUL2cv59O9a2S/sMon9LoDqHbRSsGHAHw575oPw2', 'staff'),
(29, 'janani', 'janani@gmail.com', '$2y$10$SDdFqd4YOfsCZ9rF0qNBw.CIqrjYR1zazM10YQYjZf1HVdH63iJe.', 'staff');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `email_queue`
--
ALTER TABLE `email_queue`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `event_users`
--
ALTER TABLE `event_users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `project_tasks`
--
ALTER TABLE `project_tasks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `project_users`
--
ALTER TABLE `project_users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `task_users`
--
ALTER TABLE `task_users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `task_id` (`task_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `todos`
--
ALTER TABLE `todos`
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
-- AUTO_INCREMENT for table `email_queue`
--
ALTER TABLE `email_queue`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `event_users`
--
ALTER TABLE `event_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `projects`
--
ALTER TABLE `projects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `project_tasks`
--
ALTER TABLE `project_tasks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `project_users`
--
ALTER TABLE `project_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `task_users`
--
ALTER TABLE `task_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `todos`
--
ALTER TABLE `todos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `task_users`
--
ALTER TABLE `task_users`
  ADD CONSTRAINT `task_users_ibfk_1` FOREIGN KEY (`task_id`) REFERENCES `project_tasks` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `task_users_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
