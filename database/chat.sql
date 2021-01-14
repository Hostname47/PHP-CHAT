-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 13, 2021 at 11:51 PM
-- Server version: 10.4.17-MariaDB
-- PHP Version: 7.4.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `chat`
--
CREATE DATABASE IF NOT EXISTS `chat` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `chat`;

-- --------------------------------------------------------

--
-- Table structure for table `post`
--

CREATE TABLE `post` (
  `id` int(11) NOT NULL,
  `post_owner` int(11) NOT NULL,
  `post_visibility` int(11) NOT NULL DEFAULT 0,
  `post_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `post_edit_date` timestamp NULL DEFAULT NULL,
  `text_content` text DEFAULT NULL,
  `picture_media` text DEFAULT NULL,
  `video_media` text DEFAULT NULL,
  `post_place` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `post`
--

INSERT INTO `post` VALUES
(16, 5, 0, '2021-01-12 09:26:32', NULL, 'This is only text', 'data/users/grotto/posts/5ffd6b48be7ec3.36680630/pictures/', 'data/users/grotto/posts/5ffd6b48be7ec3.36680630/videos/', 1),
(18, 5, 0, '2021-01-12 09:31:00', NULL, 'This is a land of a giant', 'data/users/grotto/posts/5ffd6c54244547.24431090/pictures/', 'data/users/grotto/posts/5ffd6c54244547.24431090/videos/', 1),
(19, 5, 0, '2021-01-12 09:32:00', NULL, 'This is another text only post !', 'data/users/grotto/posts/5ffd6c906b0616.85633437/pictures/', 'data/users/grotto/posts/5ffd6c906b0616.85633437/videos/', 1),
(24, 5, 1, '2021-01-12 11:14:38', NULL, 'Hello without image', 'data/users/grotto/posts/5ffd849e024064.06043876/pictures/', 'data/users/grotto/posts/5ffd849e024064.06043876/videos/', 1),
(25, 5, 1, '2021-01-12 11:20:51', NULL, 'This is for test', 'data/users/grotto/posts/5ffd8613488de3.75449047/pictures/', 'data/users/grotto/posts/5ffd8613488de3.75449047/videos/', 1),
(27, 5, 1, '2021-01-12 11:35:44', NULL, 'Post created from profile page', 'data/users/grotto/posts/5ffd899036bb94.59593865/pictures/', 'data/users/grotto/posts/5ffd899036bb94.59593865/videos/', 1),
(30, 5, 1, '2021-01-13 11:38:03', NULL, 'This I think is the final attemp to store a post with full saved image path', 'http://127.0.0.1/CHAT/data/users/grotto/posts/5ffedb9b7fe0a2.76620749/pictures/860a3d7ad713252c14447ceca64ed6399ef29bcdc01473fa52028cf2ca3125e5.jpg', 'http://127.0.0.1/CHAT/data/users/grotto/posts/5ffedb9b7fe0a2.76620749/videos/', 1),
(31, 5, 1, '2021-01-13 11:48:36', NULL, 'Final of final This I think is the final attemp to store a post with full saved image path', 'http://127.0.0.1/CHAT/data/users/grotto/posts/5ffede14032c13.65074557/media/pictures/aa82d8e17f9d4c8e90639713a35d689a655f09bca4c5cbbb980d767c96a64bce.jpg', 'http://127.0.0.1/CHAT/data/users/grotto/posts/5ffede14032c13.65074557/media/videos/', 1);

-- --------------------------------------------------------

--
-- Table structure for table `post_place`
--

CREATE TABLE `post_place` (
  `id` int(11) NOT NULL,
  `post_place` varchar(40) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `post_place`
--

INSERT INTO `post_place` VALUES
(1, 'timeline'),
(2, 'group');

-- --------------------------------------------------------

--
-- Table structure for table `post_visibility`
--

CREATE TABLE `post_visibility` (
  `id` int(11) NOT NULL,
  `visibility` varchar(40) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `post_visibility`
--

INSERT INTO `post_visibility` VALUES
(1, 'public'),
(2, 'friends'),
(3, 'only me');

-- --------------------------------------------------------

--
-- Table structure for table `users_session`
--

CREATE TABLE `users_session` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `hash` varchar(64) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users_session`
--

INSERT INTO `users_session` VALUES
(39, 18, '0fd7a7a8037be24bf3b44b63056ccdf6d950309af4526a7368b305fffd038a67'),
(70, 5, 'd86007d6b8c42820017393f0f847e5ccf612e4d303fd52489146d0e6b40038a7');

-- --------------------------------------------------------

--
-- Table structure for table `user_follow`
--

CREATE TABLE `user_follow` (
  `id` int(11) NOT NULL,
  `follower_id` int(11) NOT NULL,
  `followed_id` int(11) NOT NULL,
  `subscribed` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `user_info`
--

CREATE TABLE `user_info` (
  `id` int(11) NOT NULL,
  `username` varchar(20) DEFAULT NULL,
  `password` varchar(64) DEFAULT NULL,
  `salt` varchar(32) DEFAULT NULL,
  `firstname` varchar(50) DEFAULT NULL,
  `lastname` varchar(50) DEFAULT NULL,
  `joined` timestamp NOT NULL DEFAULT current_timestamp(),
  `user_type` int(11) DEFAULT NULL,
  `email` varchar(255) DEFAULT '',
  `bio` varchar(800) DEFAULT NULL,
  `picture` text DEFAULT NULL,
  `cover` text DEFAULT NULL,
  `private` int(11) NOT NULL DEFAULT -1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user_info`
--

INSERT INTO `user_info` VALUES
(1, 'Hostname47', 'password', 'salt', 'mouad', 'nassri', '2020-12-23 19:27:07', 1, 'mouad@gmail.com', '', NULL, NULL, 0),
(2, 'Paulse', 'password', NULL, NULL, NULL, '2020-12-23 21:01:17', 2, NULL, '', NULL, NULL, 0),
(3, 'thomas56', '8042cea7a55eec8a6d0fe69772efe8c72eaecbfb54730dbd941dff618aaaa7bf', '694fb540b7752f849983104c0810f2d5', 'Thomas', 'Acquinas', '2020-12-25 08:15:32', 1, NULL, '', NULL, NULL, 0),
(4, 'smith89', '805c51f9a51780975082954aa9bbb9fb514140942e18867e5a27ab8ba3ef8168', 'bd5f2b4be55b0eb5ef1cf53f21b5935a', 'John', 'smith', '2020-12-25 08:25:44', 1, NULL, '', NULL, NULL, 0),
(5, 'grotto', 'c10866cafb83ebb8a93ce2c22dc4e2baae833dc2f7ea019a6bc3d85a4a1b9e19', '39ada050f036615c836648c0e2c7a75f', 'vampire', 'grotto', '2020-12-26 09:46:29', 1, 'mouadstev1@gmail.com', 'Hey, I\'m grotto and I like blood a lot <3', 'data/users/grotto/media/pictures/c0853471aecae5e122f2bb8960c1dcf6b5962a463782110347fe5bd358e252e4.jpg', 'data/users/grotto/media/covers/07017628e51f6ce16870da933a5597f8890f2fcfc1bbabfe8525f4da7e4d114d.png', -1),
(7, 'loupgarou', '6e480d1f73289708cdacaf326e3c5a52c2cdf6c76f1a7f2db65e7e670852b4e7', 'e574a504f0101da11a57f4e499a95ad5', 'loup', 'garou', '2020-12-28 08:26:34', 1, 'loup@vampire.com', '', NULL, NULL, 0),
(8, 'killer47', '465fd128af13af86272b1837501850baa2f960e1a88f6a2012e04b627740853f', '6044e7dafe8ed48f17689d7bd96d935b', 'Krabston', 'killer', '2020-12-28 09:04:38', 1, 'killer47@gmail.com', '', NULL, NULL, 0),
(9, 'nilson47', '86980860195f037703172e1c491ff8ad487deb15951860cb4758d807950320fe', 'b7132d6cedaefc6c4a11d771e6f4fad9', 'dennis', 'nilson', '2020-12-29 08:34:52', 1, 'nilson@gmail.com', '', NULL, NULL, 0),
(10, 'shreda', '297e95eadbf62cbf0a50e42800c4fdf7839d165bb77e2f2488cbca47cf6b86a0', 'a3872acc63ade8376d26034f9bf4eee4', 'Master', 'Shreda', '2021-01-04 02:50:17', 1, 'shreda@gmail.com', 'I like calisthenics and street workout', NULL, NULL, -1),
(11, 'master-shreda', '934f5ca49572685b8fda222b0f5a56959d17942144b13edc53e5043ed3892912', 'c7a0c9ff57f4a17d4e1dd211b75642de', 'Shreda1', 'master 1', '2021-01-04 08:06:21', 1, 'sh@gmail.com', NULL, NULL, NULL, -1),
(12, 'test123', 'c1d57d10b931ee72b885f4b7d8c5d0ede619abe0b630a30e914e3b353376bb3f', '5da513d8896a6018f24530d3342fe92a', 'Test', 'Test1', '2021-01-04 08:07:36', 1, 'test@gmail.com', NULL, NULL, NULL, -1),
(13, 'sparta247', '9cb9c3dd28d563a3e4f790ebbfd611d7899a5250da4fcbc1866f39fa98763fce', '22bac4043aeec3e78b289489629f7f90', 'gladiator', 'sparta', '2021-01-04 08:08:31', 1, 'sparta@gmail.com', NULL, NULL, NULL, -1),
(16, 'jpergjpop', '4924547e5a85c078e6118ec51f14a349e1d0c07fa4842ac818d126fcd9852848', '4cb03c3b13a4a430691d3e283195f057', 'ergerg', 'ejirgjpejopr', '2021-01-05 08:31:59', 1, 'rthj@egeh.edg', NULL, NULL, NULL, -1),
(17, 'hitman', '8f388657fb4b1710dcf4ba477beb30387db14831a433762ac7846b3764a7da59', '4000d206bda10718c58b031a38dd5c98', 'Abdessamad', 'Nassri', '2021-01-05 09:54:08', 1, 'hitman@gmail.com', '', 'data/users/hitman/media/pictures/bbff0e2123ba25874a3f3b2c256740cf317143023cd48bc1f8428d7c0fc576b6.jpg', 'data/users/hitman/media/covers/9f5f99544fd0be7970903654bf2bb2e861966715a23fbc7a0ba4266297540f52.jpg', -1),
(19, 'samir1234', '756405d432bb9022b2f45d4078ef8d2e1bf4d7d40141b4233c88573783e2fc4e', '619ed102572a932799cd1f635bb51f15', 'Samir', 'Nasri', '2021-01-08 08:50:09', 1, 'samir@gmail.com', NULL, NULL, NULL, -1),
(20, 'kratos47', '494cc0d6cd899a543904e1915dd3a5c30f1f7272f63102be4cdb7be445efabb3', 'e39accd01775a37f58e3df9593c6ad28', 'grottos', 'krattos', '2021-01-08 11:23:39', 1, 'kratos@gmail.com', NULL, NULL, NULL, -1),
(21, 'corleone', '33e8900c56f4e8a12a4c5416f19f667d4d29673b31fc1e9530ac14954ee97e82', '57c1b1701527c2d7aeba50473c7b92d5', 'godfather', 'corleone', '2021-01-08 01:56:58', 1, 'cor@gmail.com', NULL, NULL, NULL, -1);

-- --------------------------------------------------------

--
-- Table structure for table `user_type`
--

CREATE TABLE `user_type` (
  `id` int(11) NOT NULL,
  `type_name` varchar(30) DEFAULT NULL,
  `permission` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user_type`
--

INSERT INTO `user_type` VALUES
(1, 'Normal user', NULL),
(2, 'Admin', '{\'Admin\':1}');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `post`
--
ALTER TABLE `post`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_post_place` (`post_place`);

--
-- Indexes for table `post_place`
--
ALTER TABLE `post_place`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `post_visibility`
--
ALTER TABLE `post_visibility`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users_session`
--
ALTER TABLE `users_session`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_follow`
--
ALTER TABLE `user_follow`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `follow_unique` (`follower_id`,`followed_id`);

--
-- Indexes for table `user_info`
--
ALTER TABLE `user_info`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `user_type`
--
ALTER TABLE `user_type`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `post`
--
ALTER TABLE `post`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `post_place`
--
ALTER TABLE `post_place`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `post_visibility`
--
ALTER TABLE `post_visibility`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users_session`
--
ALTER TABLE `users_session`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;

--
-- AUTO_INCREMENT for table `user_follow`
--
ALTER TABLE `user_follow`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_info`
--
ALTER TABLE `user_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `user_type`
--
ALTER TABLE `user_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `post`
--
ALTER TABLE `post`
  ADD CONSTRAINT `fk_post_place` FOREIGN KEY (`post_place`) REFERENCES `post_place` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
