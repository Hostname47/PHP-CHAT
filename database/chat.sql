-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 08, 2021 at 09:52 PM
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
(1, 5, 'Hi', '2021-02-02 13:39:11', 0, 0),
(2, 5, 'test', '2021-02-02 13:39:49', 0, 0),
(3, 17, 'ok', '2021-02-02 13:39:53', 0, 0),
(4, 5, 'now ?', '2021-02-02 13:39:57', 0, 0),
(5, 17, 'works', '2021-02-02 13:40:17', 0, 0),
(6, 5, 'gg', '2021-02-02 13:40:44', 0, 0),
(7, 17, 'works', '2021-02-02 13:40:48', 0, 0),
(8, 17, 'SPECIAL CASE', '2021-02-02 13:40:52', 0, 0),
(9, 17, 'ererh', '2021-02-02 13:40:55', 0, 0),
(10, 5, 'erth', '2021-02-02 13:41:00', 0, 0),
(11, 5, 'rth', '2021-02-02 13:41:01', 0, 0),
(12, 17, 'rth', '2021-02-02 13:41:02', 0, 0),
(13, 17, 'rth', '2021-02-02 13:41:04', 0, 0),
(14, 17, 'er', '2021-02-02 13:41:11', 0, 0),
(15, 17, 'r', '2021-02-02 13:41:13', 0, 0),
(16, 17, 'e', '2021-02-02 13:41:22', 0, 0),
(17, 17, 'r', '2021-02-02 13:42:05', 0, 0),
(18, 5, 'ee', '2021-02-02 13:42:39', 1, 14),
(19, 17, 'hi', '2021-02-02 13:43:08', NULL, NULL),
(20, 5, 'hello', '2021-02-02 13:43:11', NULL, NULL),
(21, 17, 'reply', '2021-02-02 13:43:15', 1, 20),
(22, 5, 'ee', '2021-02-02 13:43:52', 1, 19),
(23, 5, 'rr', '2021-02-02 13:45:19', 1, 19),
(24, 5, 'ee', '2021-02-02 13:45:39', 1, 19),
(25, 17, 'erg', '2021-02-02 13:45:42', 1, 20),
(26, 17, 'erg', '2021-02-02 13:45:44', 1, 20),
(27, 17, 'ee', '2021-02-02 13:45:48', 1, 20),
(28, 17, 'ert', '2021-02-02 13:45:52', 1, 20),
(29, 17, 'rt', '2021-02-02 13:45:58', 1, 20),
(30, 17, 'erre', '2021-02-02 13:46:38', 1, 20),
(31, 5, 'hi', '2021-02-02 13:47:47', NULL, NULL),
(32, 17, 'hello', '2021-02-02 13:47:50', NULL, NULL),
(34, 17, 'heey', '2021-02-02 13:48:16', NULL, NULL),
(35, 5, 'I think reply works now right ?', '2021-02-02 13:48:26', NULL, NULL),
(36, 17, 'let\'s see !', '2021-02-02 13:48:31', NULL, NULL),
(38, 17, 'yeah', '2021-02-02 13:48:47', NULL, NULL),
(39, 5, 'OK', '2021-02-02 13:48:51', NULL, NULL),
(40, 17, 'I think it works as expected', '2021-02-02 13:48:59', NULL, NULL),
(41, 5, 'hh', '2021-02-02 13:55:24', NULL, NULL),
(42, 5, 'IDK', '2021-02-02 13:55:33', NULL, NULL),
(45, 17, 'IDK', '2021-02-02 13:55:49', NULL, NULL),
(47, 5, 'ER', '2021-02-02 13:55:58', NULL, NULL),
(48, 17, 'ERG', '2021-02-02 13:56:01', NULL, NULL),
(51, 17, 'HI', '2021-02-02 13:58:45', NULL, NULL),
(53, 17, 'HOW ARE YOU ?', '2021-02-02 13:58:55', NULL, NULL),
(55, 17, 'THIS IS A REPLY', '2021-02-02 13:59:06', 1, 54),
(57, 17, 'ER', '2021-02-02 14:01:20', NULL, NULL),
(58, 17, 'ERG', '2021-02-02 14:01:24', NULL, NULL),
(62, 17, 'H', '2021-02-02 14:02:12', NULL, NULL),
(64, 5, 'hi', '2021-02-02 14:50:26', NULL, NULL),
(65, 17, 'hello', '2021-02-02 14:50:30', NULL, NULL),
(67, 17, 'yeah', '2021-02-02 14:50:40', NULL, NULL),
(72, 5, 'hello', '2021-02-02 15:02:14', NULL, NULL),
(74, 5, 'ih', '2021-02-02 15:32:14', NULL, NULL),
(75, 17, 'ok', '2021-02-02 15:32:18', NULL, NULL),
(76, 5, 'rep', '2021-02-02 15:32:30', 1, 75),
(77, 17, 'reply on ih', '2021-02-02 15:32:42', 1, 74),
(78, 17, 'h', '2021-02-02 15:32:52', NULL, NULL),
(79, 5, 'gggggggggggggg', '2021-02-02 15:32:56', NULL, NULL),
(80, 5, 'what happens !', '2021-02-02 15:33:10', NULL, NULL),
(81, 17, 'IDK', '2021-02-02 15:33:16', NULL, NULL),
(82, 5, 'HELLO', '2021-02-02 15:33:54', NULL, NULL),
(83, 17, 'HI', '2021-02-02 15:33:58', NULL, NULL),
(84, 5, 'REPLY', '2021-02-02 15:34:03', 1, 83),
(85, 17, 'THANKS', '2021-02-02 15:34:08', NULL, NULL),
(86, 17, 'ALSO REPLY', '2021-02-02 15:34:19', 1, 82),
(87, 17, 'H', '2021-02-02 15:35:14', NULL, NULL),
(88, 5, 'HH', '2021-02-02 15:35:17', NULL, NULL),
(89, 5, 'hh', '2021-02-02 15:46:14', 1, 83),
(90, 5, 'normal reply', '2021-02-02 21:14:03', 1, 87),
(91, 5, 'reply to reply', '2021-02-02 21:14:11', 1, 77),
(92, 5, 'normal', '2021-02-02 21:14:23', NULL, NULL),
(93, 17, 'hi', '2021-02-02 21:18:09', NULL, NULL),
(94, 5, 'hello', '2021-02-02 21:18:14', NULL, NULL),
(95, 5, 'normal reply right !', '2021-02-02 21:18:23', 1, 93),
(96, 5, 'look at this reply of reply', '2021-02-02 21:18:35', 1, 77),
(97, 5, 'hi', '2021-02-02 21:20:43', 1, 93),
(98, 5, 'Hey this is test', '2021-02-02 21:44:52', 1, 8),
(99, 5, 'LET\'s try a very long message just to see if the half width matches here or not !', '2021-02-02 22:32:26', NULL, NULL);

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
(3, 17, 2, 0),
(4, 5, 3, 0),
(5, 17, 4, 0),
(6, 5, 5, 0),
(7, 17, 6, 0),
(8, 5, 7, 0),
(9, 5, 8, 0),
(10, 5, 9, 0),
(11, 17, 10, 0),
(12, 17, 11, 0),
(13, 5, 12, 0),
(14, 5, 13, 0),
(15, 5, 14, 0),
(16, 5, 15, 0),
(17, 5, 16, 0),
(18, 17, 18, 0),
(19, 5, 19, 0),
(20, 17, 20, 0),
(21, 5, 21, 0),
(22, 17, 22, 0),
(23, 17, 23, 0),
(24, 17, 24, 0),
(25, 5, 25, 0),
(26, 5, 26, 0),
(27, 5, 27, 0),
(28, 5, 28, 0),
(29, 5, 29, 0),
(30, 5, 30, 0),
(31, 17, 31, 0),
(32, 5, 32, 0),
(34, 5, 34, 0),
(35, 17, 35, 0),
(36, 5, 36, 0),
(38, 5, 38, 0),
(39, 17, 39, 0),
(40, 5, 40, 0),
(41, 17, 41, 0),
(42, 17, 42, 0),
(47, 17, 47, 0),
(74, 17, 74, 0),
(75, 5, 75, 0),
(76, 17, 76, 0),
(77, 5, 77, 0),
(78, 5, 78, 0),
(79, 17, 79, 0),
(80, 17, 80, 0),
(81, 5, 81, 0),
(82, 17, 82, 0),
(83, 5, 83, 0),
(84, 17, 84, 0),
(87, 5, 87, 0),
(88, 17, 88, 0),
(89, 17, 89, 0),
(90, 17, 90, 0),
(91, 17, 91, 0),
(92, 17, 92, 0),
(93, 5, 93, 0),
(94, 17, 94, 0),
(95, 17, 95, 0),
(96, 17, 96, 0),
(97, 17, 97, 0),
(98, 17, 98, 0),
(99, 17, 99, 0);

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

INSERT INTO `post` (`id`, `post_owner`, `post_visibility`, `post_date`, `post_edit_date`, `text_content`, `picture_media`, `video_media`, `post_place`) VALUES
(16, 5, 0, '2021-01-12 09:26:32', NULL, 'This is only text', 'data/users/grotto/posts/5ffd6b48be7ec3.36680630/pictures/', 'data/users/grotto/posts/5ffd6b48be7ec3.36680630/videos/', 1),
(18, 5, 0, '2021-01-12 09:31:00', NULL, 'This is a land of a giant', 'data/users/grotto/posts/5ffd6c54244547.24431090/pictures/', 'data/users/grotto/posts/5ffd6c54244547.24431090/videos/', 1),
(19, 5, 0, '2021-01-12 09:32:00', NULL, 'This is another text only post !', 'data/users/grotto/posts/5ffd6c906b0616.85633437/pictures/', 'data/users/grotto/posts/5ffd6c906b0616.85633437/videos/', 1),
(24, 5, 1, '2021-01-12 11:14:38', NULL, 'Hello without image', 'data/users/grotto/posts/5ffd849e024064.06043876/pictures/', 'data/users/grotto/posts/5ffd849e024064.06043876/videos/', 1),
(25, 5, 1, '2021-01-12 11:20:51', NULL, 'This is for test', 'data/users/grotto/posts/5ffd8613488de3.75449047/pictures/', 'data/users/grotto/posts/5ffd8613488de3.75449047/videos/', 1),
(27, 5, 1, '2021-01-12 11:35:44', NULL, 'Post created from profile page', 'data/users/grotto/posts/5ffd899036bb94.59593865/pictures/', 'data/users/grotto/posts/5ffd899036bb94.59593865/videos/', 1),
(30, 5, 1, '2021-01-13 11:38:03', NULL, 'This I think is the final attemp to store a post with full saved image path', 'data/users/grotto/posts/5ffedb9b7fe0a2.76620749/pictures/', 'data/users/grotto/posts/5ffedb9b7fe0a2.76620749/videos/', 1),
(31, 5, 1, '2021-01-13 11:48:36', NULL, 'Final of final This I think is the final attemp to store a post with full saved image path', 'data/users/grotto/posts/5ffede14032c13.65074557/media/pictures/', 'data/users/grotto/posts/5ffede14032c13.65074557/media/videos/', 1),
(32, 10, 1, '2021-01-18 09:46:27', NULL, 'Hey, My name is shreda and I like everything related to calisthenics.', 'data/users/shreda/posts/600558f35881a7.16247526/media/pictures/', 'data/users/shreda/posts/600558f35881a7.16247526/media/videos/', 1),
(33, 10, 1, '2021-01-18 10:14:17', NULL, 'Awesome &lt;3', 'data/users/shreda/posts/60055f79c17961.12723115/media/pictures/', 'data/users/shreda/posts/60055f79c17961.12723115/media/videos/', 1),
(34, 10, 1, '2021-01-18 10:16:34', NULL, 'This is only text post', 'data/users/shreda/posts/6005600246ebd5.74811975/media/pictures/', 'data/users/shreda/posts/6005600246ebd5.74811975/media/videos/', 1),
(35, 10, 1, '2021-01-18 10:19:13', NULL, 'Please don\'t try ..', 'data/users/shreda/posts/600560a1870a67.05597547/media/pictures/', 'data/users/shreda/posts/600560a1870a67.05597547/media/videos/', 1),
(36, 17, 1, '2021-01-18 10:26:30', NULL, 'Hello guys, this is my new post in this plateform', 'data/users/hitman/posts/60056256aeac69.42429506/media/pictures/', 'data/users/hitman/posts/60056256aeac69.42429506/media/videos/', 1),
(37, 17, 1, '2021-01-18 10:27:02', NULL, 'x+=1; seen hhh', 'data/users/hitman/posts/60056276d507a0.19921861/media/pictures/', 'data/users/hitman/posts/60056276d507a0.19921861/media/videos/', 1),
(38, 5, 1, '2021-01-18 10:28:43', NULL, 'This is text with backspace\r\nNote:\r\nThis is work', 'data/users/grotto/posts/600562db7fe896.96312798/media/pictures/', 'data/users/grotto/posts/600562db7fe896.96312798/media/videos/', 1),
(39, 5, 1, '2021-01-18 10:33:52', NULL, 'This is text with backspace\r\nNote: \r\nThis is work', 'data/users/grotto/posts/60056410ec4609.23618289/media/pictures/', 'data/users/grotto/posts/60056410ec4609.23618289/media/videos/', 1),
(40, 5, 1, '2021-01-18 10:34:28', NULL, 'ze\r\nehe', 'data/users/grotto/posts/600564345c0a04.12233238/media/pictures/', 'data/users/grotto/posts/600564345c0a04.12233238/media/videos/', 1),
(41, 5, 1, '2021-01-18 10:38:24', NULL, 'ze\r\nze', 'data/users/grotto/posts/60056520a43f22.14426060/media/pictures/', 'data/users/grotto/posts/60056520a43f22.14426060/media/videos/', 1),
(42, 5, 1, '2021-01-18 10:40:01', NULL, 'ze\r\nze', 'data/users/grotto/posts/600565818eca74.76126958/media/pictures/', 'data/users/grotto/posts/600565818eca74.76126958/media/videos/', 1),
(43, 5, 1, '2021-01-18 10:42:30', NULL, 'This is first line&lt;br/&gt;line break&lt;br/&gt;third line', 'data/users/grotto/posts/60056615f36a71.55396915/media/pictures/', 'data/users/grotto/posts/60056615f36a71.55396915/media/videos/', 1),
(44, 5, 1, '2021-01-27 00:53:07', NULL, 'GROTTO IS HERE !', 'data/users/grotto/posts/601162339e2fb7.85710768/media/pictures/', 'data/users/grotto/posts/601162339e2fb7.85710768/media/videos/', 1),
(45, 5, 1, '2021-01-27 00:54:22', NULL, 'What&lt;br/&gt;IS &lt;br/&gt;THIS', 'data/users/grotto/posts/6011627ecbf111.84694300/media/pictures/', 'data/users/grotto/posts/6011627ecbf111.84694300/media/videos/', 1),
(46, 5, 1, '2021-01-27 00:55:01', NULL, 'Interstellar &lt;3', 'data/users/grotto/posts/601162a5ee3696.67012269/media/pictures/', 'data/users/grotto/posts/601162a5ee3696.67012269/media/videos/', 1),
(47, 22, 1, '2021-01-30 10:52:03', NULL, 'Hey, my name is charon and I\'m new to this application !', 'data/users/charonIV/posts/6015e313e062e9.94365561/media/pictures/', 'data/users/charonIV/posts/6015e313e062e9.94365561/media/videos/', 1),
(48, 5, 1, '2021-02-05 07:41:52', NULL, 'That\'s text again', 'data/users/grotto/posts/601d9f80bfa0e6.93829591/media/pictures/', 'data/users/grotto/posts/601d9f80bfa0e6.93829591/media/videos/', 1),
(49, 5, 1, '2021-02-08 10:34:40', NULL, 'Yeah bro I can do it !', 'data/users/grotto/posts/602113c07363a5.35553178/media/pictures/', 'data/users/grotto/posts/602113c07363a5.35553178/media/videos/', 1),
(50, 5, 1, '2021-02-08 10:42:16', NULL, 'Two images post !', 'data/users/grotto/posts/602115889a66e4.61960258/media/pictures/', 'data/users/grotto/posts/602115889a66e4.61960258/media/videos/', 1),
(51, 5, 1, '2021-02-08 00:16:18', NULL, 'This is text new message !', 'data/users/grotto/posts/60212b92797fc9.89804266/media/pictures/', 'data/users/grotto/posts/60212b92797fc9.89804266/media/videos/', 1),
(52, 5, 1, '2021-02-08 00:17:51', NULL, 'eere', 'data/users/grotto/posts/60212bef187057.28096283/media/pictures/', 'data/users/grotto/posts/60212bef187057.28096283/media/videos/', 1),
(53, 5, 1, '2021-02-08 00:18:37', NULL, 'eeee', 'data/users/grotto/posts/60212c1c4ff136.86513646/media/pictures/', 'data/users/grotto/posts/60212c1c4ff136.86513646/media/videos/', 1),
(54, 5, 1, '2021-02-08 12:24:15', NULL, 'eee', 'data/users/grotto/posts/60212d6f010ba2.10870503/media/pictures/', 'data/users/grotto/posts/60212d6f010ba2.10870503/media/videos/', 1),
(55, 5, 1, '2021-02-08 12:28:33', NULL, '3 images post', 'data/users/grotto/posts/60212e714cd152.56304259/media/pictures/', 'data/users/grotto/posts/60212e714cd152.56304259/media/videos/', 1),
(56, 5, 1, '2021-02-08 12:53:19', NULL, '4 images post', 'data/users/grotto/posts/6021343f08aaa2.36029155/media/pictures/', 'data/users/grotto/posts/6021343f08aaa2.36029155/media/videos/', 1),
(57, 5, 1, '2021-02-08 13:00:06', NULL, 'more than 4 images post', 'data/users/grotto/posts/602135d6f09228.42319994/media/pictures/', 'data/users/grotto/posts/602135d6f09228.42319994/media/videos/', 1),
(58, 5, 1, '2021-02-08 20:32:04', NULL, 'hhh', 'data/users/grotto/posts/60219fc42ec381.67069022/media/pictures/', 'data/users/grotto/posts/60219fc42ec381.67069022/media/videos/', 1),
(59, 5, 1, '2021-02-08 20:32:35', NULL, 'Jesus !', 'data/users/grotto/posts/60219fe3ea1b91.47489732/media/pictures/', 'data/users/grotto/posts/60219fe3ea1b91.47489732/media/videos/', 1),
(60, 5, 1, '2021-02-08 20:33:36', NULL, 'Test for text only !', 'data/users/grotto/posts/6021a020d8d4c7.80673410/media/pictures/', 'data/users/grotto/posts/6021a020d8d4c7.80673410/media/videos/', 1);

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
(93, 5, '1b5cf2ad1ba526db08a5ee2a38209a90f0ba15f83d9963d71fde165722b5c268');

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
(93, 5, 17),
(24, 5, 20),
(92, 17, 5),
(94, 22, 5);

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
(5, 'grotto', 'c10866cafb83ebb8a93ce2c22dc4e2baae833dc2f7ea019a6bc3d85a4a1b9e19', '39ada050f036615c836648c0e2c7a75f', 'Vampire', 'Grotto', '2020-12-26 09:46:29', 1, 'mouadstev1@gmail.com', 'Hey, I\'m grotto and I like blood a lot <3', 'data/users/grotto/media/pictures/4e674701a324ce385ce0d6368ee3dcfd8e8f3b6ffe99a13710b8dd1cada8f95e.jpg', 'data/users/grotto/media/covers/b6be65dc07a120c00c58d910eb1c9a4e865c22e9de746c5e47b0d299535702cd.jpg', -1, '2021-02-08 08:51:54'),
(7, 'loupgarou', '6e480d1f73289708cdacaf326e3c5a52c2cdf6c76f1a7f2db65e7e670852b4e7', 'e574a504f0101da11a57f4e499a95ad5', 'loup', 'garou', '2020-12-28 08:26:34', 1, 'loup@vampire.com', '', NULL, NULL, 0, '2021-01-28 12:14:47'),
(8, 'killer47', '465fd128af13af86272b1837501850baa2f960e1a88f6a2012e04b627740853f', '6044e7dafe8ed48f17689d7bd96d935b', 'Krabston', 'killer', '2020-12-28 09:04:38', 1, 'killer47@gmail.com', '', NULL, NULL, 0, '2021-01-28 12:14:47'),
(9, 'nilson47', '86980860195f037703172e1c491ff8ad487deb15951860cb4758d807950320fe', 'b7132d6cedaefc6c4a11d771e6f4fad9', 'dennis', 'nilson', '2020-12-29 08:34:52', 1, 'nilson@gmail.com', '', NULL, NULL, 0, '2021-01-28 12:14:47'),
(10, 'shreda', '297e95eadbf62cbf0a50e42800c4fdf7839d165bb77e2f2488cbca47cf6b86a0', 'a3872acc63ade8376d26034f9bf4eee4', 'Master', 'Shreda', '2021-01-04 02:50:17', 1, 'shreda@gmail.com', 'I like calisthenics and street workout', NULL, NULL, -1, '2021-01-28 12:14:47'),
(11, 'master-shreda', '934f5ca49572685b8fda222b0f5a56959d17942144b13edc53e5043ed3892912', 'c7a0c9ff57f4a17d4e1dd211b75642de', 'Shreda1', 'master 1', '2021-01-04 08:06:21', 1, 'sh@gmail.com', NULL, NULL, NULL, -1, '2021-01-28 12:14:47'),
(12, 'test123', 'c1d57d10b931ee72b885f4b7d8c5d0ede619abe0b630a30e914e3b353376bb3f', '5da513d8896a6018f24530d3342fe92a', 'Test', 'Test1', '2021-01-04 08:07:36', 1, 'test@gmail.com', NULL, NULL, NULL, -1, '2021-01-28 12:14:47'),
(13, 'sparta247', '9cb9c3dd28d563a3e4f790ebbfd611d7899a5250da4fcbc1866f39fa98763fce', '22bac4043aeec3e78b289489629f7f90', 'gladiator', 'sparta', '2021-01-04 08:08:31', 1, 'sparta@gmail.com', NULL, NULL, NULL, -1, '2021-01-28 12:14:47'),
(16, 'jpergjpop', '4924547e5a85c078e6118ec51f14a349e1d0c07fa4842ac818d126fcd9852848', '4cb03c3b13a4a430691d3e283195f057', 'ergerg', 'ejirgjpejopr', '2021-01-05 08:31:59', 1, 'rthj@egeh.edg', NULL, NULL, NULL, -1, '2021-01-28 12:14:47'),
(17, 'hitman', '8f388657fb4b1710dcf4ba477beb30387db14831a433762ac7846b3764a7da59', '4000d206bda10718c58b031a38dd5c98', 'Agent', '47', '2021-01-05 09:54:08', 1, 'hitman@gmail.com', 'This is CODENAME47 Agent', 'data/users/hitman/media/pictures/e49f8680df7bceb893681fd5603618a86137b937f378e9a191c97b4a01fe5cbf.jpg', 'data/users/hitman/media/covers/807178d5d52bd24e782d29b702c699a8e4d0d35d38863058eba46fee8c6442b9.jpg', 1, '2021-02-08 08:31:27'),
(19, 'samir1234', '756405d432bb9022b2f45d4078ef8d2e1bf4d7d40141b4233c88573783e2fc4e', '619ed102572a932799cd1f635bb51f15', 'Samir', 'Nasri', '2021-01-08 08:50:09', 1, 'samir@gmail.com', NULL, NULL, NULL, -1, '2021-01-28 12:14:47'),
(20, 'kratos47', '494cc0d6cd899a543904e1915dd3a5c30f1f7272f63102be4cdb7be445efabb3', 'e39accd01775a37f58e3df9593c6ad28', 'grottos', 'krattos', '2021-01-08 11:23:39', 1, 'kratos@gmail.com', NULL, NULL, NULL, -1, '2021-01-28 12:14:47'),
(21, 'corleone', '33e8900c56f4e8a12a4c5416f19f667d4d29673b31fc1e9530ac14954ee97e82', '57c1b1701527c2d7aeba50473c7b92d5', 'godfather', 'corleone', '2021-01-08 01:56:58', 1, 'cor@gmail.com', NULL, NULL, NULL, -1, '2021-01-28 12:14:47'),
(22, 'charonIV', 'ddade0ac2c1ba5009476032353b68a4a4f00431592fafed89a388706e406f745', '9c05ccc6c9af4ddaf977d4d339c368ea', 'charon', 'balisty', '2021-01-30 10:45:00', 1, 'charon@gmail.com', NULL, NULL, NULL, -1, '2021-01-31 11:21:20');

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
(9, 'Career', 'Hidden', 17);

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
(5, 10, 'F', '2021-01-21 13:08:37', 69),
(10, 5, 'F', '2021-01-21 13:08:53', 70),
(5, 17, 'F', '2021-01-21 13:11:50', 71),
(17, 5, 'F', '2021-01-21 13:11:50', 72),
(22, 5, 'P', '2021-01-30 10:53:46', 73);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1107;

--
-- AUTO_INCREMENT for table `message`
--
ALTER TABLE `message`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=100;

--
-- AUTO_INCREMENT for table `message_recipient`
--
ALTER TABLE `message_recipient`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=100;

--
-- AUTO_INCREMENT for table `post`
--
ALTER TABLE `post`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=94;

--
-- AUTO_INCREMENT for table `user_follow`
--
ALTER TABLE `user_follow`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=95;

--
-- AUTO_INCREMENT for table `user_info`
--
ALTER TABLE `user_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `user_metadata`
--
ALTER TABLE `user_metadata`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `user_relation`
--
ALTER TABLE `user_relation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;

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
