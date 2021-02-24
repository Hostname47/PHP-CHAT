-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 24, 2021 at 01:25 PM
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

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_get_discussions` (IN `user_id` INT)  BEGIN
	SELECT MAX(M.id) AS mid, M.message_creator as message_creator, MR.receiver_id as message_receiver, M.create_date as message_date FROM message AS M
	INNER JOIN message_recipient AS MR
	ON M.id = MR.message_id
    WHERE M.message_creator = user_id OR MR.receiver_id = user_id
	GROUP BY M.message_creator, MR.receiver_id
	ORDER BY mid DESC;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `channel`
--

CREATE TABLE `channel` (
  `id` int(11) NOT NULL,
  `sender` int(11) DEFAULT NULL,
  `receiver` int(11) DEFAULT NULL,
  `group_recipient_id` int(11) DEFAULT NULL,
  `message_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `channel`
--

INSERT INTO `channel` (`id`, `sender`, `receiver`, `group_recipient_id`, `message_id`) VALUES
(1138, 35, 5, NULL, 131),
(1139, 35, 5, NULL, 132);

-- --------------------------------------------------------

--
-- Table structure for table `comment`
--

CREATE TABLE `comment` (
  `id` int(11) NOT NULL,
  `comment_owner` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `comment_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `comment_edit_date` timestamp NULL DEFAULT NULL,
  `comment_text` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `comment`
--

INSERT INTO `comment` (`id`, `comment_owner`, `post_id`, `comment_date`, `comment_edit_date`, `comment_text`) VALUES
(1, 35, 171, '2021-02-23 20:25:07', NULL, 'add  a comment');

-- --------------------------------------------------------

--
-- Table structure for table `like`
--

CREATE TABLE `like` (
  `id` int(11) NOT NULL,
  `post_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `like_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `like`
--

INSERT INTO `like` (`id`, `post_id`, `user_id`, `like_date`) VALUES
(138, 154, 5, '2021-02-22 23:01:49'),
(139, 161, 35, '2021-02-22 23:33:35'),
(140, 154, 35, '2021-02-22 23:35:19'),
(141, 171, 35, '2021-02-23 20:24:45');

-- --------------------------------------------------------

--
-- Table structure for table `message`
--

CREATE TABLE `message` (
  `id` int(11) NOT NULL,
  `message_creator` int(11) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `create_date` datetime NOT NULL DEFAULT current_timestamp(),
  `is_reply` int(11) DEFAULT NULL,
  `reply_to` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `message`
--

INSERT INTO `message` (`id`, `message_creator`, `message`, `create_date`, `is_reply`, `reply_to`) VALUES
(132, 35, 'message to grotto !', '2021-02-23 00:34:54', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `message_recipient`
--

CREATE TABLE `message_recipient` (
  `id` int(11) NOT NULL,
  `receiver_id` int(11) DEFAULT NULL,
  `message_id` int(11) DEFAULT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `message_recipient`
--

INSERT INTO `message_recipient` (`id`, `receiver_id`, `message_id`, `is_read`) VALUES
(132, 5, 132, 0);

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
  `post_place` int(11) DEFAULT 1,
  `is_shared` int(11) DEFAULT 0,
  `post_shared_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `post`
--

INSERT INTO `post` (`id`, `post_owner`, `post_visibility`, `post_date`, `post_edit_date`, `text_content`, `picture_media`, `video_media`, `post_place`, `is_shared`, `post_shared_id`) VALUES
(154, 5, 1, '2021-02-22 23:01:39', NULL, 'text post', 'data/users/grotto/posts/603437d38b9a00.04031233/media/pictures/', 'data/users/grotto/posts/603437d38b9a00.04031233/media/videos/', 1, 0, NULL),
(155, 35, 1, '2021-02-22 23:03:00', NULL, 'text post here !', 'data/users/nassri/posts/60343824868ae2.54726719/media/pictures/', 'data/users/nassri/posts/60343824868ae2.54726719/media/videos/', 1, 0, NULL),
(156, 35, 1, '2021-02-22 23:03:07', NULL, 'another one', 'data/users/nassri/posts/6034382b7574c5.07531067/media/pictures/', 'data/users/nassri/posts/6034382b7574c5.07531067/media/videos/', 1, 0, NULL),
(157, 35, 1, '2021-02-22 23:08:09', NULL, '', 'data/users/nassri/posts/603439595eab37.27153302/media/pictures/', 'data/users/nassri/posts/603439595eab37.27153302/media/videos/', 1, 0, NULL),
(158, 35, 1, '2021-02-22 23:19:16', NULL, 'image post', 'data/users/nassri/posts/60343bf4ddee63.46742849/media/pictures/', 'data/users/nassri/posts/60343bf4ddee63.46742849/media/videos/', 1, 0, NULL),
(159, 35, 1, '2021-02-22 23:19:40', NULL, '', 'data/users/nassri/posts/60343c0cc2c409.67782411/media/pictures/', 'data/users/nassri/posts/60343c0cc2c409.67782411/media/videos/', 1, 0, NULL),
(160, 35, 1, '2021-02-22 23:20:01', NULL, '', 'data/users/nassri/posts/60343c21751853.52687691/media/pictures/', 'data/users/nassri/posts/60343c21751853.52687691/media/videos/', 1, 0, NULL),
(161, 35, 1, '2021-02-22 23:21:07', NULL, '', 'data/users/nassri/posts/60343c636d30c2.87761569/media/pictures/', 'data/users/nassri/posts/60343c636d30c2.87761569/media/videos/', 1, 0, NULL),
(162, 35, 1, '2021-02-22 23:22:46', NULL, '4 again !', 'data/users/nassri/posts/60343cc6f0f767.60003370/media/pictures/', 'data/users/nassri/posts/60343cc6f0f767.60003370/media/videos/', 1, 0, NULL),
(163, 35, 1, '2021-02-22 23:27:57', NULL, '4 works', 'data/users/nassri/posts/60343dfd9eb255.36472558/media/pictures/', 'data/users/nassri/posts/60343dfd9eb255.36472558/media/videos/', 1, 0, NULL),
(164, 35, 1, '2021-02-22 23:31:35', NULL, '4 image should work now !', 'data/users/nassri/posts/60343ed73c0db4.49688226/media/pictures/', 'data/users/nassri/posts/60343ed73c0db4.49688226/media/videos/', 1, 0, NULL),
(165, 35, 1, '2021-02-22 23:34:08', NULL, '3 ?', 'data/users/nassri/posts/60343f70530e64.26862173/media/pictures/', 'data/users/nassri/posts/60343f70530e64.26862173/media/videos/', 1, 0, NULL),
(166, 35, 1, '2021-02-23 15:02:15', NULL, 'hell !', 'data/users/nassri/posts/603518f74b7006.85779351/media/pictures/', 'data/users/nassri/posts/603518f74b7006.85779351/media/videos/', 1, 0, NULL),
(167, 35, 1, '2021-02-23 15:02:22', NULL, 'hell 2', 'data/users/nassri/posts/603518fee71a85.14436653/media/pictures/', 'data/users/nassri/posts/603518fee71a85.14436653/media/videos/', 1, 0, NULL),
(168, 35, 1, '2021-02-23 15:11:35', NULL, 'now ?', 'data/users/nassri/posts/60351b27023c92.98258473/media/pictures/', 'data/users/nassri/posts/60351b27023c92.98258473/media/videos/', 1, 0, NULL),
(169, 35, 1, '2021-02-23 19:49:33', NULL, '', 'data/users/nassri/posts/60355c4de04d30.53005944/media/pictures/', 'data/users/nassri/posts/60355c4de04d30.53005944/media/videos/', 1, 0, NULL),
(170, 35, 1, '2021-02-23 20:19:00', NULL, '', 'data/users/nassri/posts/60356334ef72c6.37687776/media/pictures/', 'data/users/nassri/posts/60356334ef72c6.37687776/media/videos/', 1, 0, NULL),
(171, 35, 1, '2021-02-23 20:24:19', NULL, '', 'data/users/nassri/posts/603564737dffc9.88204236/media/pictures/', 'data/users/nassri/posts/603564737dffc9.88204236/media/videos/', 1, 0, NULL),
(172, 35, 1, '2021-02-23 20:24:48', NULL, '', NULL, NULL, 1, 1, 171),
(173, 35, 1, '2021-02-23 20:25:37', NULL, '', 'data/users/nassri/posts/603564c167d8d9.22599073/media/pictures/', 'data/users/nassri/posts/603564c167d8d9.22599073/media/videos/', 1, 0, NULL);

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

INSERT INTO `post_place` (`id`, `post_place`) VALUES
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

INSERT INTO `post_visibility` (`id`, `visibility`) VALUES
(1, 'public'),
(2, 'friends'),
(3, 'only me');

-- --------------------------------------------------------

--
-- Table structure for table `shared_post`
--

CREATE TABLE `shared_post` (
  `id` int(11) NOT NULL,
  `poster_id` int(11) DEFAULT NULL,
  `post_id` int(11) DEFAULT NULL,
  `shared_post_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `shared_post_edit_date` timestamp NULL DEFAULT NULL,
  `post_place` int(11) DEFAULT NULL,
  `post_visibility` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `shared_post`
--

INSERT INTO `shared_post` (`id`, `poster_id`, `post_id`, `shared_post_date`, `shared_post_edit_date`, `post_place`, `post_visibility`) VALUES
(2, 5, 33, '2021-02-12 21:06:15', NULL, 1, 1),
(3, 5, 35, '2021-02-12 21:07:28', NULL, 1, 1),
(4, 5, 73, '2021-02-12 21:08:08', NULL, 1, 1),
(5, 5, 37, '2021-02-12 21:17:56', NULL, 1, 1),
(6, 5, 35, '2021-02-12 21:18:05', NULL, 1, 1),
(7, 5, 34, '2021-02-12 21:18:55', NULL, 1, 1),
(8, 5, 34, '2021-02-12 21:47:52', NULL, 1, 1),
(9, 5, 33, '2021-02-12 21:48:30', NULL, 1, 1),
(10, 5, 73, '2021-02-12 21:51:41', NULL, 1, 1),
(11, 5, 34, '2021-02-12 22:01:02', NULL, 1, 1),
(12, 5, 34, '2021-02-12 22:18:45', NULL, 1, 1);

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

INSERT INTO `users_session` (`id`, `user_id`, `hash`) VALUES
(39, 18, '0fd7a7a8037be24bf3b44b63056ccdf6d950309af4526a7368b305fffd038a67'),
(116, 33, 'b7752f5cfad94d8c90958183ae43cf34c6f08d0e8097256e04266a0dd020db61'),
(120, 17, 'efd5d8aa28bfc6b6c82f21decd49d79261cdf3e1a41149e9f9673682bb644d39'),
(125, 35, '2aaec9d63fd1f439657eb6737f4f905a3760483229cb12ab918c8194fd1f1d3c');

-- --------------------------------------------------------

--
-- Table structure for table `user_follow`
--

CREATE TABLE `user_follow` (
  `id` int(11) NOT NULL,
  `follower_id` int(11) NOT NULL,
  `followed_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user_follow`
--

INSERT INTO `user_follow` (`id`, `follower_id`, `followed_id`) VALUES
(90, 5, 1),
(20, 5, 2),
(21, 5, 3),
(3, 5, 7),
(18, 5, 8),
(91, 5, 10),
(14, 5, 13),
(99, 5, 17),
(24, 5, 20),
(104, 5, 35),
(92, 17, 5),
(96, 17, 10),
(94, 22, 5),
(95, 23, 5),
(105, 35, 5),
(103, 35, 10);

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
  `private` int(11) NOT NULL DEFAULT -1,
  `last_active_update` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user_info`
--

INSERT INTO `user_info` (`id`, `username`, `password`, `salt`, `firstname`, `lastname`, `joined`, `user_type`, `email`, `bio`, `picture`, `cover`, `private`, `last_active_update`) VALUES
(1, 'Hostname47', 'password', 'salt', 'mouad', 'nassri', '2020-12-23 19:27:07', 1, 'mouad@gmail.com', '', NULL, NULL, 0, '2021-01-28 12:14:47'),
(2, 'Paulse', 'password', NULL, 'paulse', 'christian', '2020-12-23 21:01:17', 2, NULL, '', NULL, NULL, 0, '2021-01-28 12:14:47'),
(3, 'thomas56', '8042cea7a55eec8a6d0fe69772efe8c72eaecbfb54730dbd941dff618aaaa7bf', '694fb540b7752f849983104c0810f2d5', 'Thomas', 'Acquinas', '2020-12-25 08:15:32', 1, NULL, '', NULL, NULL, 0, '2021-01-28 12:14:47'),
(4, 'smith89', '805c51f9a51780975082954aa9bbb9fb514140942e18867e5a27ab8ba3ef8168', 'bd5f2b4be55b0eb5ef1cf53f21b5935a', 'John', 'smith', '2020-12-25 08:25:44', 1, NULL, '', NULL, NULL, 0, '2021-01-28 12:14:47'),
(5, 'grotto', 'c10866cafb83ebb8a93ce2c22dc4e2baae833dc2f7ea019a6bc3d85a4a1b9e19', '39ada050f036615c836648c0e2c7a75f', 'Vampire', 'Grotto', '2020-12-26 09:46:29', 1, 'mouadstev1@gmail.com', 'Hey, I\'m grotto and I like blood a lot <3', 'data/users/grotto/media/pictures/4e674701a324ce385ce0d6368ee3dcfd8e8f3b6ffe99a13710b8dd1cada8f95e.jpg', 'data/users/grotto/media/covers/b6be65dc07a120c00c58d910eb1c9a4e865c22e9de746c5e47b0d299535702cd.jpg', -1, '2021-02-23 11:02:40'),
(7, 'loupgarou', '6e480d1f73289708cdacaf326e3c5a52c2cdf6c76f1a7f2db65e7e670852b4e7', 'e574a504f0101da11a57f4e499a95ad5', 'loup', 'garou', '2020-12-28 08:26:34', 1, 'loup@vampire.com', '', NULL, NULL, 0, '2021-01-28 12:14:47'),
(8, 'killer47', '465fd128af13af86272b1837501850baa2f960e1a88f6a2012e04b627740853f', '6044e7dafe8ed48f17689d7bd96d935b', 'Krabston', 'killer', '2020-12-28 09:04:38', 1, 'killer47@gmail.com', '', NULL, NULL, 0, '2021-01-28 12:14:47'),
(9, 'nilson47', '86980860195f037703172e1c491ff8ad487deb15951860cb4758d807950320fe', 'b7132d6cedaefc6c4a11d771e6f4fad9', 'dennis', 'nilson', '2020-12-29 08:34:52', 1, 'nilson@gmail.com', '', NULL, NULL, 0, '2021-01-28 12:14:47'),
(10, 'shreda', '297e95eadbf62cbf0a50e42800c4fdf7839d165bb77e2f2488cbca47cf6b86a0', 'a3872acc63ade8376d26034f9bf4eee4', 'Master', 'Shreda', '2021-01-04 02:50:17', 1, 'shreda@gmail.com', 'I like calisthenics and street workout', NULL, NULL, -1, '2021-02-21 08:16:21'),
(11, 'master-shreda', '934f5ca49572685b8fda222b0f5a56959d17942144b13edc53e5043ed3892912', 'c7a0c9ff57f4a17d4e1dd211b75642de', 'Shreda1', 'master 1', '2021-01-04 08:06:21', 1, 'sh@gmail.com', NULL, NULL, NULL, -1, '2021-01-28 12:14:47'),
(12, 'test123', 'c1d57d10b931ee72b885f4b7d8c5d0ede619abe0b630a30e914e3b353376bb3f', '5da513d8896a6018f24530d3342fe92a', 'Test', 'Test1', '2021-01-04 08:07:36', 1, 'test@gmail.com', NULL, NULL, NULL, -1, '2021-01-28 12:14:47'),
(13, 'sparta247', '9cb9c3dd28d563a3e4f790ebbfd611d7899a5250da4fcbc1866f39fa98763fce', '22bac4043aeec3e78b289489629f7f90', 'gladiator', 'sparta', '2021-01-04 08:08:31', 1, 'sparta@gmail.com', NULL, NULL, NULL, -1, '2021-01-28 12:14:47'),
(16, 'jpergjpop', '4924547e5a85c078e6118ec51f14a349e1d0c07fa4842ac818d126fcd9852848', '4cb03c3b13a4a430691d3e283195f057', 'ergerg', 'ejirgjpejopr', '2021-01-05 08:31:59', 1, 'rthj@egeh.edg', NULL, NULL, NULL, -1, '2021-01-28 12:14:47'),
(17, 'hitman', '8f388657fb4b1710dcf4ba477beb30387db14831a433762ac7846b3764a7da59', '4000d206bda10718c58b031a38dd5c98', 'Hitman', 'Codename47', '2021-01-05 09:54:08', 1, 'hitman@gmail.com', 'This is CODENAME47 Agent 87', 'data/users/hitman/media/pictures/e49f8680df7bceb893681fd5603618a86137b937f378e9a191c97b4a01fe5cbf.jpg', 'data/users/hitman/media/covers/807178d5d52bd24e782d29b702c699a8e4d0d35d38863058eba46fee8c6442b9.jpg', -1, '2021-02-21 09:01:19'),
(19, 'samir1234', '756405d432bb9022b2f45d4078ef8d2e1bf4d7d40141b4233c88573783e2fc4e', '619ed102572a932799cd1f635bb51f15', 'Samir', 'Nasri', '2021-01-08 08:50:09', 1, 'samir@gmail.com', NULL, NULL, NULL, -1, '2021-01-28 12:14:47'),
(20, 'kratos47', '494cc0d6cd899a543904e1915dd3a5c30f1f7272f63102be4cdb7be445efabb3', 'e39accd01775a37f58e3df9593c6ad28', 'grottos', 'krattos', '2021-01-08 11:23:39', 1, 'kratos@gmail.com', NULL, NULL, NULL, -1, '2021-01-28 12:14:47'),
(21, 'corleone', '33e8900c56f4e8a12a4c5416f19f667d4d29673b31fc1e9530ac14954ee97e82', '57c1b1701527c2d7aeba50473c7b92d5', 'godfather', 'corleone', '2021-01-08 01:56:58', 1, 'cor@gmail.com', NULL, NULL, NULL, -1, '2021-01-28 12:14:47'),
(22, 'charonIV', 'ddade0ac2c1ba5009476032353b68a4a4f00431592fafed89a388706e406f745', '9c05ccc6c9af4ddaf977d4d339c368ea', 'charon', 'balisty', '2021-01-30 10:45:00', 1, 'charon@gmail.com', NULL, NULL, NULL, -1, '2021-01-31 11:21:20'),
(35, 'nassri', '2fb8550d6cc6055bc05ff79a9d637bbd879e813b3fe61b79f04a53a97f1363c3', '190e31eb7d7cb32bdb8d09861973a0fa', 'firstname', 'lastname', '2021-02-21 07:47:02', 1, 'mouad@gmail.com', 'this is my profile !!!', 'data/users/nassri/media/pictures/3ea69762c970fd4af8b0a1e8d980fa44f3ec8a00d0ade154f422c1ee3e249233.png', 'data/users/nassri/media/covers/7a7d9f584b60b235e0c76121a5d29d5000ea9a26a35f7e67c4e6e3c0bbdcda6b.jpg', -1, '2021-02-23 08:30:56');

-- --------------------------------------------------------

--
-- Table structure for table `user_metadata`
--

CREATE TABLE `user_metadata` (
  `id` int(11) NOT NULL,
  `label` varchar(200) DEFAULT NULL,
  `content` varchar(200) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user_metadata`
--

INSERT INTO `user_metadata` (`id`, `label`, `content`, `user_id`) VALUES
(1, 'speciality', 'Web developement', 5),
(2, 'hobby', 'calisthenics', 5),
(3, 'hobby', 'calisthenics', 17),
(5, 'Specialty', 'serial killer', 17),
(6, 'Job', 'Secret Agent N:47', 17),
(7, 'Education', 'PhD', 17),
(9, 'Career', 'Hidden', 17),
(10, 'hobby', 'football', 35);

-- --------------------------------------------------------

--
-- Table structure for table `user_relation`
--

CREATE TABLE `user_relation` (
  `from` int(11) NOT NULL,
  `to` int(11) NOT NULL,
  `status` varchar(1) DEFAULT NULL,
  `since` timestamp NOT NULL DEFAULT current_timestamp(),
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user_relation`
--

INSERT INTO `user_relation` (`from`, `to`, `status`, `since`, `id`) VALUES
(5, 8, 'F', '2021-01-15 11:44:51', 1),
(8, 5, 'F', '2021-01-15 11:44:51', 3),
(5, 20, 'P', '2021-01-15 07:27:03', 36),
(5, 1, 'F', '2021-01-18 14:29:30', 67),
(1, 5, 'F', '2021-01-18 14:29:40', 68),
(22, 5, 'P', '2021-01-30 10:53:46', 73),
(23, 5, 'F', '2021-02-16 14:30:06', 74),
(17, 10, 'P', '2021-02-15 08:14:08', 76),
(17, 5, 'F', '2021-02-16 14:29:29', 77),
(5, 17, 'F', '2021-02-16 02:29:29', 78),
(5, 23, 'F', '2021-02-16 02:30:06', 79),
(5, 12, 'P', '2021-02-17 10:56:38', 80),
(10, 5, 'F', '2021-02-17 22:57:13', 81),
(5, 10, 'F', '2021-02-17 10:57:13', 82),
(35, 5, 'F', '2021-02-21 20:18:53', 84),
(5, 35, 'F', '2021-02-21 08:18:53', 85);

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

INSERT INTO `user_type` (`id`, `type_name`, `permission`) VALUES
(1, 'Normal user', NULL),
(2, 'Admin', '{\'Admin\':1}');

-- --------------------------------------------------------

--
-- Table structure for table `writing_message_notifier`
--

CREATE TABLE `writing_message_notifier` (
  `message_writer` int(11) DEFAULT NULL,
  `message_waiter` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `channel`
--
ALTER TABLE `channel`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `comment`
--
ALTER TABLE `comment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `like`
--
ALTER TABLE `like`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_index` (`post_id`,`user_id`);

--
-- Indexes for table `message`
--
ALTER TABLE `message`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `message_recipient`
--
ALTER TABLE `message_recipient`
  ADD PRIMARY KEY (`id`),
  ADD KEY `message_id` (`message_id`);

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
-- Indexes for table `shared_post`
--
ALTER TABLE `shared_post`
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
-- Indexes for table `user_metadata`
--
ALTER TABLE `user_metadata`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_label_UK` (`label`,`user_id`);

--
-- Indexes for table `user_relation`
--
ALTER TABLE `user_relation`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQUE_RELATION` (`from`,`to`,`status`);

--
-- Indexes for table `user_type`
--
ALTER TABLE `user_type`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `channel`
--
ALTER TABLE `channel`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1140;

--
-- AUTO_INCREMENT for table `comment`
--
ALTER TABLE `comment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `like`
--
ALTER TABLE `like`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=142;

--
-- AUTO_INCREMENT for table `message`
--
ALTER TABLE `message`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=133;

--
-- AUTO_INCREMENT for table `message_recipient`
--
ALTER TABLE `message_recipient`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=133;

--
-- AUTO_INCREMENT for table `post`
--
ALTER TABLE `post`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=174;

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
-- AUTO_INCREMENT for table `shared_post`
--
ALTER TABLE `shared_post`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `users_session`
--
ALTER TABLE `users_session`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=126;

--
-- AUTO_INCREMENT for table `user_follow`
--
ALTER TABLE `user_follow`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=106;

--
-- AUTO_INCREMENT for table `user_info`
--
ALTER TABLE `user_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `user_metadata`
--
ALTER TABLE `user_metadata`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `user_relation`
--
ALTER TABLE `user_relation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=86;

--
-- AUTO_INCREMENT for table `user_type`
--
ALTER TABLE `user_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `message_recipient`
--
ALTER TABLE `message_recipient`
  ADD CONSTRAINT `message_recipient_ibfk_1` FOREIGN KEY (`message_id`) REFERENCES `message` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `post`
--
ALTER TABLE `post`
  ADD CONSTRAINT `fk_post_place` FOREIGN KEY (`post_place`) REFERENCES `post_place` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
