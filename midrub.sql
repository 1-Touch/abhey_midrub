-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 21, 2020 at 09:30 AM
-- Server version: 10.4.11-MariaDB
-- PHP Version: 7.2.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `midrub`
--

-- --------------------------------------------------------

--
-- Table structure for table `activities`
--

CREATE TABLE `activities` (
  `activity_id` bigint(20) NOT NULL,
  `app` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `template` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `member_id` bigint(20) NOT NULL,
  `created` varchar(30) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `activities`
--

INSERT INTO `activities` (`activity_id`, `app`, `template`, `id`, `user_id`, `member_id`, `created`) VALUES
(1, 'posts', 'posts', 1, 120, 0, '1585047522'),
(2, 'posts', 'posts', 2, 120, 0, '1585047537'),
(5, 'posts', 'posts', 5, 124, 0, '1585110442'),
(8, 'posts', 'posts', 8, 124, 0, '1585216201'),
(9, 'posts', 'posts', 9, 124, 0, '1585562504'),
(10, 'posts', 'posts', 10, 124, 0, '1585562646'),
(11, 'posts', 'posts', 11, 124, 0, '1585562715'),
(12, 'posts', 'posts', 12, 129, 0, '1585563415'),
(13, 'posts', 'posts', 13, 124, 0, '1585644513'),
(14, 'posts', 'posts', 14, 124, 0, '1585645042');

-- --------------------------------------------------------

--
-- Table structure for table `activity`
--

CREATE TABLE `activity` (
  `activity_id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `net_id` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `body` text COLLATE utf8_unicode_ci NOT NULL,
  `network_name` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `network_id` bigint(20) NOT NULL,
  `created` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `followed` tinyint(1) NOT NULL,
  `view` tinyint(1) NOT NULL,
  `dlt` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `autocomment` text COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `activity`
--

INSERT INTO `activity` (`activity_id`, `user_id`, `net_id`, `body`, `network_name`, `network_id`, `created`, `followed`, `view`, `dlt`, `autocomment`) VALUES
(1, 127, '589212885285087_589250001948042', '7', 'facebook_groups', 192, '1585214841', 0, 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `activity_meta`
--

CREATE TABLE `activity_meta` (
  `meta_id` bigint(20) NOT NULL,
  `activity_id` bigint(20) NOT NULL,
  `type` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `net_id` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `author_id` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `author_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `body` text COLLATE utf8_unicode_ci NOT NULL,
  `parent` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `network_id` bigint(20) NOT NULL,
  `created` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `view` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `add_facebook_users`
--

CREATE TABLE `add_facebook_users` (
  `id` int(11) UNSIGNED NOT NULL,
  `username` varchar(64) NOT NULL,
  `name` varchar(128) NOT NULL,
  `profile_picture_url` varchar(512) NOT NULL,
  `likes` int(11) NOT NULL,
  `followers` int(11) NOT NULL,
  `details` longtext NOT NULL,
  `is_verified` int(11) NOT NULL,
  `added_date` datetime NOT NULL,
  `last_check_date` datetime NOT NULL,
  `last_successful_check_date` datetime NOT NULL,
  `is_demo` int(11) NOT NULL,
  `is_private` int(11) NOT NULL,
  `is_featured` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `add_facebook_users`
--

INSERT INTO `add_facebook_users` (`id`, `username`, `name`, `profile_picture_url`, `likes`, `followers`, `details`, `is_verified`, `added_date`, `last_check_date`, `last_successful_check_date`, `is_demo`, `is_private`, `is_featured`) VALUES
(1, 'Facebook', 'Facebook', 'https://scontent.fluh3-1.fna.fbcdn.net/v/t1.0-1/p200x200/87284588_124830725745195_9124219877853233152_n.png?_nc_cat=1&_nc_sid=dbb9e7&_nc_ohc=468h7-0Gr94AX8ytEln&_nc_ht=scontent.fluh3-1.fna&oh=4fc30bdb00de8086458e57ba7a23b927&oe=5EBAA8CF', 2723073, 3058486, '{\"type\":false}', 1, '2020-04-02 11:07:40', '2020-04-13 06:04:31', '2020-04-13 06:04:31', 0, 0, 0),
(2, 'abheyfirstpage', 'My First Page', 'https://scontent.fluh3-1.fna.fbcdn.net/v/t1.0-1/p200x200/89550186_105800777713326_8902460077726760960_o.png?_nc_cat=102&_nc_sid=dbb9e7&_nc_ohc=vJ3mP3pNJBcAX9oBEVB&_nc_ht=scontent.fluh3-1.fna&oh=897db3dbbcae002c633f71678d7afbc7&oe=5EB2DE48', 1, 1, '{\"type\":false}', 0, '2020-04-02 11:24:15', '2020-04-09 05:05:54', '2020-04-09 05:05:54', 1, 0, 1),
(3, 'LoveIsTheEndOfLife', 'Love is the end of life', 'https://scontent.fluh3-1.fna.fbcdn.net/v/t1.0-1/c31.31.388.388a/s200x200/430995_276015082508009_1561824971_n.jpg?_nc_cat=111&_nc_sid=dbb9e7&_nc_ohc=gQT86WdghKMAX9WH5X1&_nc_ht=scontent.fluh3-1.fna&oh=5eccc9d3fe26350104cf463c51123566&oe=5EB929DB', 179, 181, '{\"type\":false}', 0, '2020-04-09 10:26:12', '2020-04-13 04:00:55', '2020-04-13 04:00:55', 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `administrator_dashboard_widgets`
--

CREATE TABLE `administrator_dashboard_widgets` (
  `widget_id` bigint(20) NOT NULL,
  `user_id` int(11) NOT NULL,
  `widget_slug` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `enabled` tinyint(1) NOT NULL,
  `order` smallint(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ads_account`
--

CREATE TABLE `ads_account` (
  `ads_id` bigint(20) NOT NULL,
  `user_id` int(11) NOT NULL,
  `network_id` bigint(20) NOT NULL,
  `network` varchar(250) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ads_boosts`
--

CREATE TABLE `ads_boosts` (
  `boost_id` bigint(20) NOT NULL,
  `boost_name` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `user_id` int(11) NOT NULL,
  `time` int(1) NOT NULL,
  `created` varchar(30) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ads_boosts_meta`
--

CREATE TABLE `ads_boosts_meta` (
  `meta_id` bigint(20) NOT NULL,
  `boost_id` bigint(20) NOT NULL,
  `meta_name` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `meta_value` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ads_boosts_stats`
--

CREATE TABLE `ads_boosts_stats` (
  `stat_id` bigint(20) NOT NULL,
  `boost_id` bigint(20) NOT NULL,
  `post_id` bigint(20) NOT NULL,
  `publisher_platforms` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `status` int(1) NOT NULL,
  `platform_status` text COLLATE utf8_unicode_ci NOT NULL,
  `ad_name` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `ad_id` text COLLATE utf8_unicode_ci NOT NULL,
  `created` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `end_time` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `end_status` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ads_labels`
--

CREATE TABLE `ads_labels` (
  `label_id` bigint(20) NOT NULL,
  `label_name` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `user_id` int(11) NOT NULL,
  `time` int(1) NOT NULL,
  `created` varchar(30) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ads_labels_meta`
--

CREATE TABLE `ads_labels_meta` (
  `meta_id` bigint(20) NOT NULL,
  `label_id` bigint(20) NOT NULL,
  `meta_name` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `meta_value` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ads_labels_stats`
--

CREATE TABLE `ads_labels_stats` (
  `stat_id` bigint(20) NOT NULL,
  `label_id` bigint(20) NOT NULL,
  `publisher_platforms` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `status` int(1) NOT NULL,
  `platform_status` text COLLATE utf8_unicode_ci NOT NULL,
  `ad_name` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `ad_id` text COLLATE utf8_unicode_ci NOT NULL,
  `created` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `end_time` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `end_status` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ads_networks`
--

CREATE TABLE `ads_networks` (
  `network_id` bigint(20) NOT NULL,
  `network_name` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `net_id` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `type` int(4) NOT NULL,
  `user_id` int(11) NOT NULL,
  `user_name` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `date` datetime NOT NULL,
  `expires` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `token` text COLLATE utf8_unicode_ci NOT NULL,
  `secret` text COLLATE utf8_unicode_ci NOT NULL,
  `extra` varchar(250) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bots`
--

CREATE TABLE `bots` (
  `bot_id` bigint(20) NOT NULL,
  `user_id` int(11) NOT NULL,
  `type` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `rule1` text COLLATE utf8_unicode_ci NOT NULL,
  `rule2` text COLLATE utf8_unicode_ci NOT NULL,
  `rule3` text COLLATE utf8_unicode_ci NOT NULL,
  `rule4` text COLLATE utf8_unicode_ci NOT NULL,
  `rule5` text COLLATE utf8_unicode_ci NOT NULL,
  `rule6` text COLLATE utf8_unicode_ci NOT NULL,
  `rule7` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `campaigns`
--

CREATE TABLE `campaigns` (
  `campaign_id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `type` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `created` varchar(30) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `campaigns_meta`
--

CREATE TABLE `campaigns_meta` (
  `meta_id` bigint(20) NOT NULL,
  `campaign_id` bigint(20) NOT NULL,
  `meta_name` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `meta_val1` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `meta_val2` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `meta_val3` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `meta_val4` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `meta_val5` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `meta_val6` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `meta_val7` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `meta_val8` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `meta_val9` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `meta_val10` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `meta_val11` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ci_sessions`
--

CREATE TABLE `ci_sessions` (
  `id` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `ip_address` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  `timestamp` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `data` blob NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `classifications`
--

CREATE TABLE `classifications` (
  `classification_id` bigint(20) NOT NULL,
  `slug` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `parent` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `classifications`
--

INSERT INTO `classifications` (`classification_id`, `slug`, `type`, `parent`) VALUES
(1, 'main_menu', 'menu', 0),
(66, 'user_left_menu', 'menu', 0),
(67, 'user_left_menu', 'menu', 0),
(68, 'user_left_menu', 'menu', 0),
(69, 'user_left_menu', 'menu', 0),
(70, 'user_left_menu', 'menu', 0),
(71, 'user_left_menu', 'menu', 0),
(72, 'user_left_menu', 'menu', 0);

-- --------------------------------------------------------

--
-- Table structure for table `classifications_meta`
--

CREATE TABLE `classifications_meta` (
  `meta_id` bigint(20) NOT NULL,
  `classification_id` bigint(20) NOT NULL,
  `meta_slug` text COLLATE utf8_unicode_ci NOT NULL,
  `meta_name` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `meta_value` text COLLATE utf8_unicode_ci NOT NULL,
  `meta_extra` text COLLATE utf8_unicode_ci NOT NULL,
  `language` varchar(30) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `classifications_meta`
--

INSERT INTO `classifications_meta` (`meta_id`, `classification_id`, `meta_slug`, `meta_name`, `meta_value`, `meta_extra`, `language`) VALUES
(1, 1, 'name', 'name', 'Home', '', 'english'),
(3, 1, 'permalink', 'permalink', '', '', 'english'),
(4, 1, 'description', 'description', '', '', 'english'),
(5, 1, 'class', 'class', '', '', 'english'),
(326, 66, 'name', 'name', 'Dashboard', '', 'english'),
(327, 66, 'selected_component', 'selected_component', 'dashboard', 'app', 'english'),
(328, 66, 'permalink', 'permalink', '', '', 'english'),
(329, 66, 'description', 'description', '', '', 'english'),
(330, 66, 'class', 'class', 'icon-speedometer', '', 'english'),
(331, 67, 'name', 'name', 'Posts', '', 'english'),
(332, 67, 'selected_component', 'selected_component', 'posts', 'app', 'english'),
(333, 67, 'permalink', 'permalink', '', '', 'english'),
(334, 67, 'description', 'description', '', '', 'english'),
(335, 67, 'class', 'class', 'icon-layers', '', 'english'),
(336, 68, 'name', 'name', 'Storage', '', 'english'),
(337, 68, 'selected_component', 'selected_component', 'storage', 'app', 'english'),
(338, 68, 'permalink', 'permalink', '', '', 'english'),
(339, 68, 'description', 'description', '', '', 'english'),
(340, 68, 'class', 'class', 'icon-drawer', '', 'english'),
(341, 69, 'name', 'name', 'Stream', '', 'english'),
(342, 69, 'selected_component', 'selected_component', 'stream', 'app', 'english'),
(343, 69, 'permalink', 'permalink', '', '', 'english'),
(344, 69, 'description', 'description', '', '', 'english'),
(345, 69, 'class', 'class', 'icon-grid', '', 'english'),
(346, 70, 'name', 'name', 'Settings', '', 'english'),
(347, 70, 'selected_component', 'selected_component', 'settings', 'component', 'english'),
(348, 70, 'permalink', 'permalink', '', '', 'english'),
(349, 70, 'description', 'description', '', '', 'english'),
(350, 70, 'class', 'class', 'icon-settings', '', 'english'),
(351, 71, 'name', 'name', 'Achieve', '', 'english'),
(352, 71, 'selected_component', 'selected_component', 'achieve', 'app', 'english'),
(353, 71, 'permalink', 'permalink', '', '', 'english'),
(354, 71, 'description', 'description', '', '', 'english'),
(355, 71, 'class', 'class', 'fab fa-apple', '', 'english'),
(356, 72, 'name', 'name', 'PHP Analyzer', '', 'english'),
(357, 72, 'selected_component', 'selected_component', 'phpanalyzer', 'app', 'english'),
(358, 72, 'permalink', 'permalink', '', '', 'english'),
(359, 72, 'description', 'description', '', '', 'english'),
(360, 72, 'class', 'class', 'fab fa-apple', '', 'english');

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `comment_id` bigint(20) NOT NULL,
  `comment` varchar(300) COLLATE utf8_unicode_ci NOT NULL,
  `created` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contents`
--

CREATE TABLE `contents` (
  `content_id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `contents_category` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `contents_component` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `contents_theme` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `contents_template` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `contents_slug` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL,
  `created` varchar(30) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `contents`
--

INSERT INTO `contents` (`content_id`, `user_id`, `contents_category`, `contents_component`, `contents_theme`, `contents_template`, `contents_slug`, `status`, `created`) VALUES
(2, 104, 'auth', 'signin', '', '', 'auth/2', 1, '1584960863'),
(3, 104, 'auth', 'signup', '', '', 'auth/3', 1, '1584960877'),
(5, 104, 'auth', 'page', '', '', 'auth/5', 1, '1584963865'),
(6, 104, 'auth', 'page', '', '', 'auth/6', 1, '1584963884');

-- --------------------------------------------------------

--
-- Table structure for table `contents_classifications`
--

CREATE TABLE `contents_classifications` (
  `classification_id` bigint(20) NOT NULL,
  `content_id` bigint(20) NOT NULL,
  `classification_slug` text COLLATE utf8_unicode_ci NOT NULL,
  `classification_value` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contents_meta`
--

CREATE TABLE `contents_meta` (
  `meta_id` bigint(20) NOT NULL,
  `content_id` bigint(20) NOT NULL,
  `meta_slug` text COLLATE utf8_unicode_ci NOT NULL,
  `meta_name` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `meta_value` text COLLATE utf8_unicode_ci NOT NULL,
  `meta_extra` text COLLATE utf8_unicode_ci NOT NULL,
  `language` varchar(30) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `contents_meta`
--

INSERT INTO `contents_meta` (`meta_id`, `content_id`, `meta_slug`, `meta_name`, `meta_value`, `meta_extra`, `language`) VALUES
(14, 2, '', 'content_title', 'Sign In', '', 'english'),
(15, 2, '', 'content_body', '<p><br></p>', '', 'english'),
(16, 2, 'auth_signin_details', 'auth_signin_details_title', 'Sign In', '', 'english'),
(17, 2, 'auth_signin_details', 'auth_signin_details_under_title', 'Forgot password?', '', 'english'),
(18, 3, '', 'content_title', 'Sign Up', '', 'english'),
(19, 3, '', 'content_body', '<p><br></p>', '', 'english'),
(20, 3, 'auth_signup_details', 'auth_signup_details_title', 'Start your completely free 7 day trial', '', 'english'),
(21, 3, 'auth_signup_details', 'auth_signup_details_under_title', 'Already have an account?', '', 'english'),
(22, 3, 'auth_signup_details', 'auth_signup_details_accept_terms', 'By pressing \'Get Started\', I agree with Midrub\'s', '', 'english'),
(31, 2, '', 'selected_page_role', 'settings_auth_sign_in_page', '', ''),
(32, 3, '', 'selected_page_role', 'settings_auth_sign_up_page', '', ''),
(34, 5, '', 'content_title', 'Privacy Policy', '', 'english'),
(35, 5, '', 'content_body', '<p><br></p>', '', 'english'),
(36, 6, '', 'content_title', 'Terms and Conditions', '', 'english'),
(37, 6, '', 'content_body', '<p><br></p>', '', 'english');

-- --------------------------------------------------------

--
-- Table structure for table `coupons`
--

CREATE TABLE `coupons` (
  `coupon_id` bigint(20) NOT NULL,
  `code` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `value` varchar(3) COLLATE utf8_unicode_ci NOT NULL,
  `type` tinyint(1) NOT NULL,
  `count` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dictionary`
--

CREATE TABLE `dictionary` (
  `dict_id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `name` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `body` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `employee_id` int(11) NOT NULL,
  `first_name` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `last_name` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phone_number` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`employee_id`, `first_name`, `last_name`, `email`, `phone_number`) VALUES
(1, 'Abhey', 'Kumar', 'abhey@1touch.market', '9915267456');

-- --------------------------------------------------------

--
-- Table structure for table `facebook_logs`
--

CREATE TABLE `facebook_logs` (
  `id` int(11) NOT NULL,
  `facebook_user_id` int(11) DEFAULT NULL,
  `username` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `likes` int(11) DEFAULT NULL,
  `followers` int(11) DEFAULT NULL,
  `date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `facebook_logs`
--

INSERT INTO `facebook_logs` (`id`, `facebook_user_id`, `username`, `likes`, `followers`, `date`) VALUES
(1, 1, 'Facebook', 2681641, 2934965, '2020-04-02 11:07:40'),
(2, 2, 'MyFirstPage', 1, 1, '2020-04-02 11:20:41'),
(3, 2, 'abheyfirstpage', 1, 1, '2020-04-02 11:24:15'),
(4, 2, 'abheyfirstpage', 1, 1, '2020-04-06 04:06:39'),
(5, 3, 'LoveIsTheEndOfLife', 177, 179, '2020-04-06 05:06:34'),
(6, 3, 'LoveIsTheEndOfLife', 177, 179, '2020-04-07 05:43:13'),
(7, 2, 'abheyfirstpage', 1, 1, '2020-04-07 05:51:11'),
(8, 3, 'LoveIsTheEndOfLife', 179, 181, '2020-04-08 11:13:24'),
(9, 2, 'abheyfirstpage', 1, 1, '2020-04-09 05:05:54'),
(10, 3, 'LoveIsTheEndOfLife', 179, 181, '2020-04-09 10:26:12'),
(11, 3, 'LoveIsTheEndOfLife', 179, 181, '2020-04-13 04:00:55'),
(12, 1, 'Facebook', 2723073, 3058486, '2020-04-13 06:04:31'),
(13, 1, 'Facebook', 2727469, 3064625, '2020-04-14 11:35:56'),
(14, 1, 'Facebook', 2730773, 3077185, '2020-04-15 11:58:28');

-- --------------------------------------------------------

--
-- Table structure for table `facebook_users`
--

CREATE TABLE `facebook_users` (
  `id` int(11) NOT NULL,
  `username` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(128) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `profile_picture_url` varchar(512) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `likes` int(11) DEFAULT NULL,
  `followers` int(11) DEFAULT NULL,
  `details` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_verified` int(11) DEFAULT 0,
  `added_date` datetime DEFAULT NULL,
  `last_check_date` datetime DEFAULT NULL,
  `last_successful_check_date` datetime DEFAULT NULL,
  `is_demo` int(11) DEFAULT 0,
  `is_private` int(11) DEFAULT 0,
  `is_featured` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `facebook_users`
--

INSERT INTO `facebook_users` (`id`, `username`, `name`, `profile_picture_url`, `likes`, `followers`, `details`, `is_verified`, `added_date`, `last_check_date`, `last_successful_check_date`, `is_demo`, `is_private`, `is_featured`) VALUES
(1, 'Facebook', 'Facebook', 'https://scontent.fluh3-1.fna.fbcdn.net/v/t1.0-1/p200x200/87284588_124830725745195_9124219877853233152_n.png?_nc_cat=1&_nc_sid=dbb9e7&_nc_ohc=468h7-0Gr94AX8ytEln&_nc_ht=scontent.fluh3-1.fna&oh=4fc30bdb00de8086458e57ba7a23b927&oe=5EBAA8CF', 2730773, 3077185, '{\"type\":false}', 1, '2020-04-02 11:07:40', '2020-04-13 06:04:31', '2020-04-13 06:04:31', 0, 0, 0),
(2, 'abheyfirstpage', 'My First Page', 'https://scontent.fluh3-1.fna.fbcdn.net/v/t1.0-1/p200x200/89550186_105800777713326_8902460077726760960_o.png?_nc_cat=102&_nc_sid=dbb9e7&_nc_ohc=vJ3mP3pNJBcAX9oBEVB&_nc_ht=scontent.fluh3-1.fna&oh=897db3dbbcae002c633f71678d7afbc7&oe=5EB2DE48', 1, 1, '{\"type\":false}', 0, '2020-04-02 11:24:15', '2020-04-09 05:05:54', '2020-04-09 05:05:54', 1, 0, 1),
(3, 'LoveIsTheEndOfLife', 'Love is the end of life', 'https://scontent.fluh3-1.fna.fbcdn.net/v/t1.0-1/c31.31.388.388a/s200x200/430995_276015082508009_1561824971_n.jpg?_nc_cat=111&_nc_sid=dbb9e7&_nc_ohc=gQT86WdghKMAX9WH5X1&_nc_ht=scontent.fluh3-1.fna&oh=5eccc9d3fe26350104cf463c51123566&oe=5EB929DB', 179, 181, '{\"type\":false}', 0, '2020-04-09 10:26:12', '2020-04-13 04:00:55', '2020-04-13 04:00:55', 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `faq_articles`
--

CREATE TABLE `faq_articles` (
  `article_id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `created` varchar(30) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `faq_articles_categories`
--

CREATE TABLE `faq_articles_categories` (
  `meta_id` bigint(20) NOT NULL,
  `article_id` bigint(20) NOT NULL,
  `category_id` int(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `faq_articles_meta`
--

CREATE TABLE `faq_articles_meta` (
  `meta_id` bigint(20) NOT NULL,
  `article_id` bigint(20) NOT NULL,
  `title` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `body` text COLLATE utf8_unicode_ci NOT NULL,
  `language` varchar(30) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `faq_categories`
--

CREATE TABLE `faq_categories` (
  `category_id` int(6) NOT NULL,
  `parent` int(6) NOT NULL,
  `created` varchar(30) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `faq_categories_meta`
--

CREATE TABLE `faq_categories_meta` (
  `meta_id` bigint(20) NOT NULL,
  `category_id` int(6) NOT NULL,
  `name` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `language` varchar(30) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `favorites`
--

CREATE TABLE `favorites` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `source_user_id` int(11) DEFAULT NULL,
  `source` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT 'INSTAGRAM',
  `date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `favorites`
--

INSERT INTO `favorites` (`id`, `user_id`, `source_user_id`, `source`, `date`) VALUES
(1, 104, 3, 'facebook', '2020-04-03 05:34:22');

-- --------------------------------------------------------

--
-- Table structure for table `guides`
--

CREATE TABLE `guides` (
  `guide_id` bigint(20) NOT NULL,
  `title` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `short` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `body` text COLLATE utf8_unicode_ci NOT NULL,
  `cover` text COLLATE utf8_unicode_ci NOT NULL,
  `created` varchar(30) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `invoices`
--

CREATE TABLE `invoices` (
  `invoice_id` bigint(20) NOT NULL,
  `transaction_id` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `plan_id` int(6) NOT NULL,
  `invoice_date` datetime NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `invoice_title` text COLLATE utf8_unicode_ci NOT NULL,
  `invoice_text` text COLLATE utf8_unicode_ci NOT NULL,
  `amount` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `currency` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `taxes` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `total` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `from_period` datetime NOT NULL,
  `to_period` datetime NOT NULL,
  `gateway` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `invoices_options`
--

CREATE TABLE `invoices_options` (
  `option_id` bigint(20) NOT NULL,
  `option_name` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `option_value` text COLLATE utf8_unicode_ci NOT NULL,
  `template_slug` varchar(250) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `invoices_templates`
--

CREATE TABLE `invoices_templates` (
  `template_id` bigint(20) NOT NULL,
  `template_title` text COLLATE utf8_unicode_ci NOT NULL,
  `template_body` text COLLATE utf8_unicode_ci NOT NULL,
  `template_slug` varchar(250) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lists`
--

CREATE TABLE `lists` (
  `list_id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `type` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `created` varchar(30) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lists_meta`
--

CREATE TABLE `lists_meta` (
  `meta_id` bigint(20) NOT NULL,
  `list_id` bigint(20) NOT NULL,
  `user_id` int(11) NOT NULL,
  `body` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `ip` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `country` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `city` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `active` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `media`
--

CREATE TABLE `media` (
  `media_id` bigint(20) NOT NULL,
  `user_id` int(11) NOT NULL,
  `body` text COLLATE utf8_unicode_ci NOT NULL,
  `cover` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `size` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `type` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `created` varchar(30) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `version` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`version`) VALUES
(1);

-- --------------------------------------------------------

--
-- Table structure for table `networks`
--

CREATE TABLE `networks` (
  `network_id` int(3) NOT NULL,
  `network_name` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `net_id` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `user_id` int(11) NOT NULL,
  `user_name` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `user_avatar` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `date` datetime NOT NULL,
  `expires` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `token` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `secret` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `completed` tinyint(1) NOT NULL,
  `api_key` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `api_secret` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `networks`
--

INSERT INTO `networks` (`network_id`, `network_name`, `net_id`, `user_id`, `user_name`, `user_avatar`, `date`, `expires`, `token`, `secret`, `completed`, `api_key`, `api_secret`) VALUES
(7, 'facebook_groups', '332474417265345', 120, 'HTML, CSS, PHP, JAVASCRIPT, LA', '', '2020-03-24 11:57:26', '', 'EAAEZA1NYmO70BAHSkohaeVHozmcnqZCfumjLTN12qSjP0wBU6Ox5geyN7TZCLA1q1uqTKZAlDJuzx3E85eWB7s72HVz4KRZBghkRpCDmIZBfIylxVqPNgpI6dVY9QUgWafoJtCsKL4poQz8pLTgKP5jDZCV0RM3LMGZB6UoWuLN8RAZDZD', ' ', 0, NULL, NULL),
(11, 'facebook_groups', '467041483336853', 120, 'Learn PHP & MYSQL (For BEGINNE', '', '2020-03-24 11:57:26', '', 'EAAEZA1NYmO70BAHSkohaeVHozmcnqZCfumjLTN12qSjP0wBU6Ox5geyN7TZCLA1q1uqTKZAlDJuzx3E85eWB7s72HVz4KRZBghkRpCDmIZBfIylxVqPNgpI6dVY9QUgWafoJtCsKL4poQz8pLTgKP5jDZCV0RM3LMGZB6UoWuLN8RAZDZD', ' ', 0, NULL, NULL),
(15, 'facebook_groups', '207355536376037', 120, 'Urvashi rautela', '', '2020-03-24 11:57:26', '', 'EAAEZA1NYmO70BAHSkohaeVHozmcnqZCfumjLTN12qSjP0wBU6Ox5geyN7TZCLA1q1uqTKZAlDJuzx3E85eWB7s72HVz4KRZBghkRpCDmIZBfIylxVqPNgpI6dVY9QUgWafoJtCsKL4poQz8pLTgKP5jDZCV0RM3LMGZB6UoWuLN8RAZDZD', ' ', 0, NULL, NULL),
(16, 'facebook_groups', '679562415878795', 120, 'Iron Man Fans', '', '2020-03-24 11:57:26', '', 'EAAEZA1NYmO70BAHSkohaeVHozmcnqZCfumjLTN12qSjP0wBU6Ox5geyN7TZCLA1q1uqTKZAlDJuzx3E85eWB7s72HVz4KRZBghkRpCDmIZBfIylxVqPNgpI6dVY9QUgWafoJtCsKL4poQz8pLTgKP5jDZCV0RM3LMGZB6UoWuLN8RAZDZD', ' ', 0, NULL, NULL),
(17, 'facebook_groups', '233613107217218', 120, 'One Stop Market (Ludhiana)', '', '2020-03-24 11:57:26', '', 'EAAEZA1NYmO70BAHSkohaeVHozmcnqZCfumjLTN12qSjP0wBU6Ox5geyN7TZCLA1q1uqTKZAlDJuzx3E85eWB7s72HVz4KRZBghkRpCDmIZBfIylxVqPNgpI6dVY9QUgWafoJtCsKL4poQz8pLTgKP5jDZCV0RM3LMGZB6UoWuLN8RAZDZD', ' ', 0, NULL, NULL),
(18, 'facebook_groups', '100325223387308', 120, 'PCTE', '', '2020-03-24 11:57:26', '', 'EAAEZA1NYmO70BAHSkohaeVHozmcnqZCfumjLTN12qSjP0wBU6Ox5geyN7TZCLA1q1uqTKZAlDJuzx3E85eWB7s72HVz4KRZBghkRpCDmIZBfIylxVqPNgpI6dVY9QUgWafoJtCsKL4poQz8pLTgKP5jDZCV0RM3LMGZB6UoWuLN8RAZDZD', ' ', 0, NULL, NULL),
(19, 'facebook_groups', '454927174655670', 120, 'HTML5 & CSS3', '', '2020-03-24 11:57:26', '', 'EAAEZA1NYmO70BAHSkohaeVHozmcnqZCfumjLTN12qSjP0wBU6Ox5geyN7TZCLA1q1uqTKZAlDJuzx3E85eWB7s72HVz4KRZBghkRpCDmIZBfIylxVqPNgpI6dVY9QUgWafoJtCsKL4poQz8pLTgKP5jDZCV0RM3LMGZB6UoWuLN8RAZDZD', ' ', 0, NULL, NULL),
(20, 'facebook_groups', '199294193565601', 120, 'PCTE Training and Placement Ce', '', '2020-03-24 11:57:26', '', 'EAAEZA1NYmO70BAHSkohaeVHozmcnqZCfumjLTN12qSjP0wBU6Ox5geyN7TZCLA1q1uqTKZAlDJuzx3E85eWB7s72HVz4KRZBghkRpCDmIZBfIylxVqPNgpI6dVY9QUgWafoJtCsKL4poQz8pLTgKP5jDZCV0RM3LMGZB6UoWuLN8RAZDZD', ' ', 0, NULL, NULL),
(21, 'facebook_groups', '356660771193123', 120, 'Web development | Programming ', '', '2020-03-24 11:57:26', '', 'EAAEZA1NYmO70BAHSkohaeVHozmcnqZCfumjLTN12qSjP0wBU6Ox5geyN7TZCLA1q1uqTKZAlDJuzx3E85eWB7s72HVz4KRZBghkRpCDmIZBfIylxVqPNgpI6dVY9QUgWafoJtCsKL4poQz8pLTgKP5jDZCV0RM3LMGZB6UoWuLN8RAZDZD', ' ', 0, NULL, NULL),
(22, 'facebook_groups', '124562427609035', 120, 'CFC DE JWAAK', '', '2020-03-24 11:57:26', '', 'EAAEZA1NYmO70BAHSkohaeVHozmcnqZCfumjLTN12qSjP0wBU6Ox5geyN7TZCLA1q1uqTKZAlDJuzx3E85eWB7s72HVz4KRZBghkRpCDmIZBfIylxVqPNgpI6dVY9QUgWafoJtCsKL4poQz8pLTgKP5jDZCV0RM3LMGZB6UoWuLN8RAZDZD', ' ', 0, NULL, NULL),
(23, 'facebook_groups', '850872408303727', 120, 'WordPress Development & Learni', '', '2020-03-24 11:57:26', '', 'EAAEZA1NYmO70BAHSkohaeVHozmcnqZCfumjLTN12qSjP0wBU6Ox5geyN7TZCLA1q1uqTKZAlDJuzx3E85eWB7s72HVz4KRZBghkRpCDmIZBfIylxVqPNgpI6dVY9QUgWafoJtCsKL4poQz8pLTgKP5jDZCV0RM3LMGZB6UoWuLN8RAZDZD', ' ', 0, NULL, NULL),
(24, 'facebook_groups', '1490228714536927', 120, 'PHP Mysql/Mysqli Apache/nginx ', '', '2020-03-24 11:57:26', '', 'EAAEZA1NYmO70BAHSkohaeVHozmcnqZCfumjLTN12qSjP0wBU6Ox5geyN7TZCLA1q1uqTKZAlDJuzx3E85eWB7s72HVz4KRZBghkRpCDmIZBfIylxVqPNgpI6dVY9QUgWafoJtCsKL4poQz8pLTgKP5jDZCV0RM3LMGZB6UoWuLN8RAZDZD', ' ', 0, NULL, NULL),
(25, 'facebook_groups', '446803512018181', 120, 'PHP Wordpress Magento Joomla D', '', '2020-03-24 11:57:26', '', 'EAAEZA1NYmO70BAHSkohaeVHozmcnqZCfumjLTN12qSjP0wBU6Ox5geyN7TZCLA1q1uqTKZAlDJuzx3E85eWB7s72HVz4KRZBghkRpCDmIZBfIylxVqPNgpI6dVY9QUgWafoJtCsKL4poQz8pLTgKP5jDZCV0RM3LMGZB6UoWuLN8RAZDZD', ' ', 0, NULL, NULL),
(26, 'facebook_groups', '443339319174300', 120, 'PCTE-MCA-PASSOUT-2012-2015', '', '2020-03-24 11:57:26', '', 'EAAEZA1NYmO70BAHSkohaeVHozmcnqZCfumjLTN12qSjP0wBU6Ox5geyN7TZCLA1q1uqTKZAlDJuzx3E85eWB7s72HVz4KRZBghkRpCDmIZBfIylxVqPNgpI6dVY9QUgWafoJtCsKL4poQz8pLTgKP5jDZCV0RM3LMGZB6UoWuLN8RAZDZD', ' ', 0, NULL, NULL),
(27, 'facebook_groups', '5267995887', 120, 'PHP', '', '2020-03-24 11:57:26', '', 'EAAEZA1NYmO70BAHSkohaeVHozmcnqZCfumjLTN12qSjP0wBU6Ox5geyN7TZCLA1q1uqTKZAlDJuzx3E85eWB7s72HVz4KRZBghkRpCDmIZBfIylxVqPNgpI6dVY9QUgWafoJtCsKL4poQz8pLTgKP5jDZCV0RM3LMGZB6UoWuLN8RAZDZD', ' ', 0, NULL, NULL),
(28, 'facebook_groups', '150044255065979', 120, 'C.F.C Public School', '', '2020-03-24 11:57:26', '', 'EAAEZA1NYmO70BAHSkohaeVHozmcnqZCfumjLTN12qSjP0wBU6Ox5geyN7TZCLA1q1uqTKZAlDJuzx3E85eWB7s72HVz4KRZBghkRpCDmIZBfIylxVqPNgpI6dVY9QUgWafoJtCsKL4poQz8pLTgKP5jDZCV0RM3LMGZB6UoWuLN8RAZDZD', ' ', 0, NULL, NULL),
(29, 'facebook_groups', '216872148482512', 120, 'MCA STUDENT ONLY(LDH)', '', '2020-03-24 11:57:26', '', 'EAAEZA1NYmO70BAHSkohaeVHozmcnqZCfumjLTN12qSjP0wBU6Ox5geyN7TZCLA1q1uqTKZAlDJuzx3E85eWB7s72HVz4KRZBghkRpCDmIZBfIylxVqPNgpI6dVY9QUgWafoJtCsKL4poQz8pLTgKP5jDZCV0RM3LMGZB6UoWuLN8RAZDZD', ' ', 0, NULL, NULL),
(30, 'facebook_groups', '164730110348195', 120, 'MCA 2012-2015 Batch', '', '2020-03-24 11:57:26', '', 'EAAEZA1NYmO70BAHSkohaeVHozmcnqZCfumjLTN12qSjP0wBU6Ox5geyN7TZCLA1q1uqTKZAlDJuzx3E85eWB7s72HVz4KRZBghkRpCDmIZBfIylxVqPNgpI6dVY9QUgWafoJtCsKL4poQz8pLTgKP5jDZCV0RM3LMGZB6UoWuLN8RAZDZD', ' ', 0, NULL, NULL),
(31, 'facebook_groups', '507870305907623', 120, 'Flying Feet', '', '2020-03-24 11:57:26', '', 'EAAEZA1NYmO70BAHSkohaeVHozmcnqZCfumjLTN12qSjP0wBU6Ox5geyN7TZCLA1q1uqTKZAlDJuzx3E85eWB7s72HVz4KRZBghkRpCDmIZBfIylxVqPNgpI6dVY9QUgWafoJtCsKL4poQz8pLTgKP5jDZCV0RM3LMGZB6UoWuLN8RAZDZD', ' ', 0, NULL, NULL),
(32, 'facebook_groups', '458565284163838', 120, 'MCA2012B', '', '2020-03-24 11:57:26', '', 'EAAEZA1NYmO70BAHSkohaeVHozmcnqZCfumjLTN12qSjP0wBU6Ox5geyN7TZCLA1q1uqTKZAlDJuzx3E85eWB7s72HVz4KRZBghkRpCDmIZBfIylxVqPNgpI6dVY9QUgWafoJtCsKL4poQz8pLTgKP5jDZCV0RM3LMGZB6UoWuLN8RAZDZD', ' ', 0, NULL, NULL),
(33, 'facebook_groups', '353196011391358', 120, 'BCA 2011-2012', '', '2020-03-24 11:57:26', '', 'EAAEZA1NYmO70BAHSkohaeVHozmcnqZCfumjLTN12qSjP0wBU6Ox5geyN7TZCLA1q1uqTKZAlDJuzx3E85eWB7s72HVz4KRZBghkRpCDmIZBfIylxVqPNgpI6dVY9QUgWafoJtCsKL4poQz8pLTgKP5jDZCV0RM3LMGZB6UoWuLN8RAZDZD', ' ', 0, NULL, NULL),
(34, 'facebook_groups', '219555754756744', 120, 'BACHELORS.......', '', '2020-03-24 11:57:26', '', 'EAAEZA1NYmO70BAHSkohaeVHozmcnqZCfumjLTN12qSjP0wBU6Ox5geyN7TZCLA1q1uqTKZAlDJuzx3E85eWB7s72HVz4KRZBghkRpCDmIZBfIylxVqPNgpI6dVY9QUgWafoJtCsKL4poQz8pLTgKP5jDZCV0RM3LMGZB6UoWuLN8RAZDZD', ' ', 0, NULL, NULL),
(35, 'facebook_groups', '134402543294037', 120, 'Arya college di  mandeerrrr', '', '2020-03-24 11:57:26', '', 'EAAEZA1NYmO70BAHSkohaeVHozmcnqZCfumjLTN12qSjP0wBU6Ox5geyN7TZCLA1q1uqTKZAlDJuzx3E85eWB7s72HVz4KRZBghkRpCDmIZBfIylxVqPNgpI6dVY9QUgWafoJtCsKL4poQz8pLTgKP5jDZCV0RM3LMGZB6UoWuLN8RAZDZD', ' ', 0, NULL, NULL),
(36, 'facebook_pages', '105800647713339', 120, 'My First Page', '', '2020-03-24 11:58:00', '', 'EAAEZA1NYmO70BAIU7XNZC6LfHIUFWZCMpAm9RNPk4SdBkbRkP6aJ8Di1ZB9dnlaMU0yyfEHI7CsPYC7M2NwrGy2xy3ZAlYZBR4thq2z14pkLS4NbYrg64Vc9PNSrivhWZBARVI7uqPzdCcCH3D1syOQ8zwtthBozNj8QeIfGCuK1wZDZD', 'EAAEZA1NYmO70BAFMOSe7nvJBoSWnnsHr0KwPi5bCrfOy63tnxYhg3THQqUsZBpUGWbGHXVvMTBvoQzGSUndPOZB2VPnGpB3qXnAGR59IY3cfsFYcfhdQMSkdGGHJyu3uHK4knOYg16leyKPbPLzi9ugsyLZBOKxvO4RJLod7VZCa00EZAYCnsm', 0, NULL, NULL),
(37, 'facebook_live', '105800647713339', 120, 'My First Page', '', '2020-03-24 12:42:45', '', 'EAAEZA1NYmO70BACNdhWtahkevVOIggh2iYw542HvYLJmHHlkgTkcHZBggrF7FteoZBGxdcE3VQYuU7fVq9SsdaZB7MsIWPwKp7p5V3Lc4JSCTRZBCojVEDfUJy2ePlsOlPrGcz7aEhmDDmZAFmfSa2a65Svk5L01pkZCqlR8VOvbQZDZD', 'EAAEZA1NYmO70BAFnRYPzxx6kMgsldvpv9gZAGEe5DXZCndXYoq8Ghgrcf9cS5Atqq61ZALq8Ea1b2wGbSur8YkCEPSUHFZC3kcoWUZBGDvlSOYiKA2p7KvLC3H5hmiPZC9RAbkjXk3z0nIPgrDTSIOuDUCEzx2i7t4tVvzvRwrx6CNxB2iwgxgO', 0, NULL, NULL),
(103, 'facebook_pages', '105800647713339', 124, 'My First Page', '', '2020-03-25 05:26:31', '', 'EAAEZA1NYmO70BAIyDSIvtuqoZCZAy6tlFfk4QI7ZBmxO1OQSw1Ph8PVdkjdE44l67472l37pblfvCFkSsPTzFj1CLDMItdEmz8ZBzQBkhNeXJsaIryciNjXZBSx5uQmDMfbnuIkT6CSEmpNJOkaLTiWwkhgTOmEO5ZBLNbZCIsOqGAZDZD', 'EAAEZA1NYmO70BAG2xxYPJv3AZCe9ZCuUuZAdVcDGpAmyRM0dFZCfVrXcXPCsjUTEt2Usj4JBeLnR9VSgXzGeEk0YMCa1sO4fZB5DcfIzmLW1KyOwCe1y0TgjlbLcgUZA0nqgULf8NVhuC77MkLhKowtgXC0cNYLozxtBYYYX42sUFh2SDWB3lOq', 0, NULL, NULL),
(282, 'facebook_pages', '105800647713339', 129, 'My First Page', '', '2020-03-30 12:16:38', '', 'EAAEZA1NYmO70BAK9gD0wfGa6lpZATGQWCknZBPF9usZAkg9z0HSB2d8dohbO77zpZBeAj3L4Qvbjmt0sYBmZBFrLZC3PMRiZBgbLF51t4GZCOEYmkMwtuYf7kgDYPQJbzwzucWLjLVdFf5RMxaRoy1uKdcKxXNdnzQzoABQBnz4WEOQZDZD', 'EAAEZA1NYmO70BAB1t1VtANvJ3J4ZA7NtD8u2vf0u2y0A8N6ZAKr5NgAN5hPpRXgat06eFjUZCzSRTUCuZABzdRTLLTD0ag37ZC1RBibNc9pcAGsLdrJOdsy5h6ybc4eZBuYRn2yM7pKSgkCh5Ax4MjqMNW7XCj5FISZCGO192gqbgVo8Xjmax4lb', 0, NULL, NULL),
(284, 'facebook_groups', '147733319916755', 124, 'ABP Group', '', '2020-03-31 01:25:26', '', 'EAAEZA1NYmO70BAPpz7rcYZBntFVdmI0xBuL5iD4kbU2bdLcn7KQZBcnhai9WoD3ERy595aJ5V2Q7kZCqvFCZBuOZBFMLq5XeZBzHzdEQaKhCZCoFSDcoyKmSZA1UV2pgmSqJ50jCUN9AUnE9yZB9B3PHcg5pJK6Ct33aiYk3YdY0lzLAZDZD', ' ', 0, NULL, NULL),
(285, 'facebook_groups', '246123986571794', 124, 'Neeraj Special Menu', '', '2020-03-31 01:25:26', '', 'EAAEZA1NYmO70BAPpz7rcYZBntFVdmI0xBuL5iD4kbU2bdLcn7KQZBcnhai9WoD3ERy595aJ5V2Q7kZCqvFCZBuOZBFMLq5XeZBzHzdEQaKhCZCoFSDcoyKmSZA1UV2pgmSqJ50jCUN9AUnE9yZB9B3PHcg5pJK6Ct33aiYk3YdY0lzLAZDZD', ' ', 0, NULL, NULL),
(287, 'facebook_groups', '589212885285087', 124, 'My First Groups', '', '2020-03-31 01:25:26', '', 'EAAEZA1NYmO70BAPpz7rcYZBntFVdmI0xBuL5iD4kbU2bdLcn7KQZBcnhai9WoD3ERy595aJ5V2Q7kZCqvFCZBuOZBFMLq5XeZBzHzdEQaKhCZCoFSDcoyKmSZA1UV2pgmSqJ50jCUN9AUnE9yZB9B3PHcg5pJK6Ct33aiYk3YdY0lzLAZDZD', ' ', 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `notification_id` bigint(20) NOT NULL,
  `notification_title` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `notification_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `notification_body` text COLLATE utf8_unicode_ci NOT NULL,
  `sent_time` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `template` tinyint(1) NOT NULL,
  `template_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`notification_id`, `notification_title`, `notification_name`, `notification_body`, `sent_time`, `template`, `template_name`) VALUES
(1, 'Welcome to [site_name]', 'Welcome message(without confirmation)', '<p>You can login here: [login_address]</p><p>Using this username and password:</p><p>Username: [username]</p><p>Password: *** Password you set during signup ***</p><p>Cheers!</p><p>The [site_name] Team</p><p><br></p>', '', 1, 'welcome-message-no-confirmation'),
(2, 'Welcome to [site_name]', 'Welcome message(with confirmation)', '<p>To activate your account and verify your email address, </p><p>please click the following link: [confirmation_link]</p><p><br></p>', '', 1, 'welcome-message-with-confirmation'),
(3, 'Your account has been activated', 'Success confirmation message', '<p>Congratulations, your account has been activated!</p><p>You can login here: [login_address]</p><p>Using this username and password:</p><p>Username: [username]</p><p>Password: *** Password you set during signup ***</p><p>Cheers!</p><p>The [site_name] Team</p>', '', 1, 'success-confirmation-message'),
(4, 'Password Reset', 'Reset password message', '<p>Dear [username]</p><p>To reset the password to your [site_name]\'s account, click the link below: </p><p>[reset_link]<br></p>', '', 1, 'password-reset'),
(5, 'Your password has been reset successfully', 'Success password changed message', '<p>Congratulations, your account has been activated!</p><p>You can login here: [login_address]<br></p><p><br></p>', '', 1, 'success-password-changed'),
(6, 'Your message wasn\'t published successfully', 'Publishing message error', '<p>You messagge wasn\'t published successfully on a social network.</p><p>You can login here: [login_address]<br></p>', '', 1, 'error-sent-notification'),
(7, 'Resend Confirmation Email', 'Resend confirmation email', '<p>To activate your account and verify your email address,</p><p>please click the following link: [confirmation_link]</p>', '', 1, 'resend-confirmation-email'),
(8, 'Your new account was created successfully', 'Send password to new users', '<p>A new account has been created for you on [site_name].</p><p>You can login here <span xss=\"removed\">[login_address]</span></p><p><span xss=\"removed\">Username: [username]</span></p><p><span xss=\"removed\">Password: [password]</span></p>', '', 1, 'send-password-new-users'),
(9, 'Scheduled Notification', 'Scheduled notification', '<p>An user has scheduled a new message.</p><p>Please Sign In: <span xss=\"removed\">[login_address]</span></p><p><br></p>', '', 1, 'scheduled-notification'),
(12, 'New user registration', 'New user registration', 'A new user has registered at <span xss=\"removed\">[site_name]</span>', '', 1, 'new-user-notification'),
(1000, 'The Planned Post was completed', 'Post Completation Notification', '<p>Dear [username]</p><p>Your planned post, [post] was published the planned number of times and will not be more published.</p>', '', 1, 'planned-completed-confirmation'),
(1100, 'New Ticket Reply', 'New Ticket Reply', '<p>Dear [username]</p><p>You have a new reply for your opened ticket.</p>', '', 1, 'ticket-notification-reply'),
(2000, 'The Planned Email Template was completed', 'Email Template Completation Notification', '<p>Dear [username]</p><p>Your planned email template, [template] was sent the planned number of times and will not be more sent.</p>', '', 1, 'planned-email-completed-confirmation');

-- --------------------------------------------------------

--
-- Table structure for table `notifications_stats`
--

CREATE TABLE `notifications_stats` (
  `stat_id` bigint(20) NOT NULL,
  `notification_id` bigint(20) NOT NULL,
  `user_id` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_applications`
--

CREATE TABLE `oauth_applications` (
  `application_id` bigint(20) NOT NULL,
  `user_id` int(11) NOT NULL,
  `application_name` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `redirect_url` text COLLATE utf8_unicode_ci NOT NULL,
  `cancel_url` text COLLATE utf8_unicode_ci NOT NULL,
  `type` tinyint(1) NOT NULL,
  `created` varchar(30) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_applications_permissions`
--

CREATE TABLE `oauth_applications_permissions` (
  `permission_id` bigint(20) NOT NULL,
  `application_id` bigint(20) NOT NULL,
  `permission_slug` varchar(250) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_authorization_codes`
--

CREATE TABLE `oauth_authorization_codes` (
  `code_id` bigint(20) NOT NULL,
  `user_id` int(11) NOT NULL,
  `application_id` bigint(20) NOT NULL,
  `code` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `created` varchar(30) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_authorization_codes_permissions`
--

CREATE TABLE `oauth_authorization_codes_permissions` (
  `permission_id` bigint(20) NOT NULL,
  `code_id` bigint(20) NOT NULL,
  `permission_slug` varchar(250) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_permissions`
--

CREATE TABLE `oauth_permissions` (
  `permission_id` bigint(20) NOT NULL,
  `permission_slug` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_tokens`
--

CREATE TABLE `oauth_tokens` (
  `token_id` bigint(20) NOT NULL,
  `application_id` bigint(20) NOT NULL,
  `user_id` int(11) NOT NULL,
  `token` text COLLATE utf8_unicode_ci NOT NULL,
  `created` varchar(30) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_tokens_permissions`
--

CREATE TABLE `oauth_tokens_permissions` (
  `permission_id` bigint(20) NOT NULL,
  `token_id` bigint(20) NOT NULL,
  `permission_slug` varchar(250) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `options`
--

CREATE TABLE `options` (
  `option_id` bigint(20) NOT NULL,
  `option_key` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `option_value` mediumtext COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `options`
--

INSERT INTO `options` (`option_id`, `option_key`, `option_value`) VALUES
(2, 'app_dashboard_enable', '1'),
(3, 'app_dashboard_enable_default_widgets', '1'),
(4, 'app_dashboard_left_side_position', '1'),
(5, 'app_posts_enable', '1'),
(6, 'app_posts_enable_composer', '1'),
(7, 'app_posts_enable_scheduled', '1'),
(8, 'app_posts_enable_insights', '1'),
(9, 'app_posts_enable_history', '1'),
(10, 'app_posts_rss_feeds', '1'),
(11, 'app_posts_enable_faq', '1'),
(12, 'app_posts_enable_url_download', '1'),
(13, 'app_storage_enable', '1'),
(14, 'app_storage_enable_url_download', '1'),
(15, 'themes_activated_user_theme', 'blue'),
(16, 'facebook_ad_labels', '1'),
(17, 'component_faq_enable', '1'),
(18, 'component_notifications_enable', '1'),
(19, 'component_plans_enable', '1'),
(20, 'component_settings_enable', '1'),
(21, 'component_team_enable', '1'),
(22, 'app_facebook_ads_enable', '1'),
(23, 'app_facebook_ads_enable_posts_boosting', '1'),
(24, 'themes_activated_theme', 'midrub'),
(25, 'settings_home_page', '1'),
(27, 'component_activities_enable', '1'),
(28, 'app_stream_enable', '1'),
(34, 'facebook_pages_app_id', '309876893301693'),
(35, 'facebook_pages_app_secret', '4f72ab39d063fa78f84846c5f632f5f9'),
(36, 'facebook_pages', '1'),
(37, 'facebook_live_app_id', '309876893301693'),
(38, 'facebook_live_app_secret', '4f72ab39d063fa78f84846c5f632f5f9'),
(39, 'facebook_live', '1'),
(82, 'facebook_groups_app_id', '309876893301693'),
(83, 'facebook_groups_app_secret', '4f72ab39d063fa78f84846c5f632f5f9'),
(84, 'facebook_groups', '1'),
(88, 'app_facebook_enable', '1'),
(89, 'app_phpanalyzer_enable', '1');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` bigint(20) NOT NULL,
  `user_id` int(11) NOT NULL,
  `txn_id` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `payment_amount` decimal(7,2) NOT NULL,
  `payment_status` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `plan_id` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `created` datetime NOT NULL,
  `source` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `recurring` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `plans`
--

CREATE TABLE `plans` (
  `plan_id` int(6) NOT NULL,
  `plan_name` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `plan_price` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `currency_sign` char(3) COLLATE utf8_unicode_ci NOT NULL,
  `currency_code` char(3) COLLATE utf8_unicode_ci NOT NULL,
  `network_accounts` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `sent_emails` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `storage` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `features` text COLLATE utf8_unicode_ci NOT NULL,
  `teams` tinyint(1) DEFAULT NULL,
  `header` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `period` bigint(10) NOT NULL,
  `visible` tinyint(1) DEFAULT NULL,
  `popular` tinyint(1) DEFAULT NULL,
  `featured` tinyint(1) DEFAULT NULL,
  `trial` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `plans`
--

INSERT INTO `plans` (`plan_id`, `plan_name`, `plan_price`, `currency_sign`, `currency_code`, `network_accounts`, `sent_emails`, `storage`, `features`, `teams`, `header`, `period`, `visible`, `popular`, `featured`, `trial`) VALUES
(1, 'Free Plan', '4.00', '$', 'USD', '1', '10', '60000000', '1 Social Profiles\n1 Feed Rss\nReal-time Analytics\nMessage Scheduling\n', 5, 'for personal use', 30, 0, 0, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `plans_meta`
--

CREATE TABLE `plans_meta` (
  `meta_id` int(6) NOT NULL,
  `plan_id` int(6) NOT NULL,
  `meta_name` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `meta_value` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `plans_meta`
--

INSERT INTO `plans_meta` (`meta_id`, `plan_id`, `meta_name`, `meta_value`) VALUES
(1, 1, 'publish_posts', '20'),
(2, 1, 'rss_feeds', '20'),
(3, 1, 'facebook_ad_labels', '1'),
(4, 1, 'app_dashboard', '1'),
(5, 1, 'app_posts', '1'),
(6, 1, 'app_storage', '1'),
(7, 1, 'app_facebook_ads', '1'),
(8, 1, 'stream_tabs_limit', '10'),
(9, 1, 'app_stream', '1'),
(10, 1, 'facebook_groups', '1'),
(11, 1, 'facebook_pages', '1'),
(12, 1, 'facebook_live', '1'),
(13, 1, 'linkedin', '0'),
(14, 1, 'app_achieve', '1'),
(15, 1, 'app_facebook', '1'),
(16, 1, 'app_phpanalyzer', '1');

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `post_id` bigint(20) NOT NULL,
  `user_id` int(11) NOT NULL,
  `body` varbinary(4000) DEFAULT NULL,
  `title` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `url` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `img` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `video` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `category` text COLLATE utf8_unicode_ci NOT NULL,
  `sent_time` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `resend` bigint(20) DEFAULT NULL,
  `ip_address` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL,
  `view` tinyint(1) NOT NULL,
  `fb_boost_id` bigint(20) DEFAULT NULL,
  `parent` bigint(20) DEFAULT NULL,
  `dlt` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`post_id`, `user_id`, `body`, `title`, `url`, `img`, `video`, `category`, `sent_time`, `resend`, `ip_address`, `status`, `view`, `fb_boost_id`, `parent`, `dlt`) VALUES
(1, 120, 0x48656c6c6f2050435445, '', '', 'a:0:{}', 'a:0:{}', 'null', '1585047522', NULL, '::1', 1, 1, NULL, NULL, NULL),
(5, 124, 0x48656c6c6f2045766572796f6e65, '', '', 'a:0:{}', 'a:0:{}', 'null', '1585110442', NULL, '::1', 1, 1, NULL, NULL, NULL),
(8, 124, 0x57656c636f6d6520546f204f75722047726f757073, '', '', 'a:0:{}', 'a:0:{}', 'null', '1585216201', NULL, '::1', 1, 1, NULL, NULL, NULL),
(12, 129, 0x48656c6c6f2045766572796f6e65, '', '', 'a:0:{}', 'a:0:{}', 'null', '1585563415', NULL, '::1', 1, 1, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `posts_meta`
--

CREATE TABLE `posts_meta` (
  `meta_id` bigint(20) NOT NULL,
  `post_id` bigint(20) NOT NULL,
  `network_id` bigint(20) NOT NULL,
  `network_name` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `sent_time` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL,
  `network_status` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `published_id` text COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `posts_meta`
--

INSERT INTO `posts_meta` (`meta_id`, `post_id`, `network_id`, `network_name`, `sent_time`, `status`, `network_status`, `published_id`) VALUES
(1, 5, 103, 'facebook_pages', '1585110443', 1, NULL, '105800647713339_117701209856616'),
(4, 12, 282, 'facebook_pages', '1585563416', 1, NULL, '105800647713339_120566762903394');

-- --------------------------------------------------------

--
-- Table structure for table `referrals`
--

CREATE TABLE `referrals` (
  `referrer_id` bigint(20) NOT NULL,
  `referrer` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `plan_id` bigint(20) NOT NULL,
  `earned` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `paid` tinyint(1) NOT NULL,
  `created` varchar(30) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `resend`
--

CREATE TABLE `resend` (
  `resend_id` bigint(20) NOT NULL,
  `user_id` int(11) NOT NULL,
  `time` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `created` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `updated` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `active` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `resend_meta`
--

CREATE TABLE `resend_meta` (
  `meta_id` bigint(20) NOT NULL,
  `resend_id` bigint(20) NOT NULL,
  `rule1` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `rule2` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `rule3` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `rule4` varchar(30) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `resend_rules`
--

CREATE TABLE `resend_rules` (
  `rule_id` bigint(20) NOT NULL,
  `resend_id` bigint(20) NOT NULL,
  `meta_id` bigint(20) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `totime` varchar(30) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rss`
--

CREATE TABLE `rss` (
  `rss_id` bigint(20) NOT NULL,
  `user_id` int(11) NOT NULL,
  `rss_name` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `rss_description` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `rss_url` text COLLATE utf8_unicode_ci NOT NULL,
  `publish_description` tinyint(1) NOT NULL,
  `publish_url` tinyint(1) NOT NULL,
  `remove_url` tinyint(1) DEFAULT NULL,
  `keep_html` tinyint(1) DEFAULT NULL,
  `group_id` bigint(20) NOT NULL,
  `include` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `exclude` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `enabled` tinyint(1) NOT NULL,
  `completed` tinyint(1) NOT NULL,
  `added` datetime NOT NULL,
  `pub` tinyint(1) NOT NULL,
  `refferal` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `period` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `updated` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `type` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rss_accounts`
--

CREATE TABLE `rss_accounts` (
  `account_id` bigint(20) NOT NULL,
  `network_id` bigint(20) NOT NULL,
  `rss_id` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rss_posts`
--

CREATE TABLE `rss_posts` (
  `post_id` bigint(20) NOT NULL,
  `rss_id` bigint(20) NOT NULL,
  `user_id` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `title` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `content` text COLLATE utf8_unicode_ci NOT NULL,
  `url` text COLLATE utf8_unicode_ci NOT NULL,
  `img` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `published` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `scheduled` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `network_status` text COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rss_posts_meta`
--

CREATE TABLE `rss_posts_meta` (
  `meta_id` bigint(20) NOT NULL,
  `post_id` bigint(20) NOT NULL,
  `network_id` bigint(20) NOT NULL,
  `network_name` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `sent_time` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL,
  `network_status` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `published_id` text COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `scheduled`
--

CREATE TABLE `scheduled` (
  `scheduled_id` bigint(20) NOT NULL,
  `user_id` int(11) NOT NULL,
  `type` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `campaign_id` bigint(20) NOT NULL,
  `list_id` bigint(20) NOT NULL,
  `template_id` bigint(20) NOT NULL,
  `con` tinyint(1) NOT NULL,
  `template` bigint(20) NOT NULL,
  `send_at` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `resend` bigint(20) DEFAULT NULL,
  `a` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `scheduled_stats`
--

CREATE TABLE `scheduled_stats` (
  `stat_id` bigint(20) NOT NULL,
  `sched_id` bigint(20) NOT NULL,
  `campaign_id` bigint(20) NOT NULL,
  `list_id` bigint(20) NOT NULL,
  `template_id` bigint(20) NOT NULL,
  `body` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `unsubscribed` tinyint(1) NOT NULL,
  `readed` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stream_cronology`
--

CREATE TABLE `stream_cronology` (
  `cronology_id` bigint(20) NOT NULL,
  `stream_id` bigint(20) NOT NULL,
  `value` text COLLATE utf8_unicode_ci NOT NULL,
  `up` tinyint(1) NOT NULL,
  `created` varchar(30) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `stream_cronology`
--

INSERT INTO `stream_cronology` (`cronology_id`, `stream_id`, `value`, `up`, `created`) VALUES
(1, 4, 'a:4:{i:0;s:31:\"117710829855654_117711066522297\";i:1;s:31:\"105800647713339_117710829855654\";i:2;s:31:\"117701209856616_117704463189624\";i:3;s:31:\"105800647713339_117701209856616\";}', 1, '1585112013'),
(2, 5, 'a:4:{i:0;s:31:\"117710829855654_117711066522297\";i:1;s:31:\"105800647713339_117710829855654\";i:2;s:31:\"117701209856616_117704463189624\";i:3;s:31:\"105800647713339_117701209856616\";}', 0, '1585112009'),
(3, 6, 'a:4:{i:0;s:31:\"117710829855654_117711066522297\";i:1;s:31:\"105800647713339_117710829855654\";i:2;s:31:\"117701209856616_117704463189624\";i:3;s:31:\"105800647713339_117701209856616\";}', 0, '1585112066'),
(4, 7, 'a:4:{i:0;s:31:\"117710829855654_117711066522297\";i:1;s:31:\"105800647713339_117710829855654\";i:2;s:31:\"117701209856616_117704463189624\";i:3;s:31:\"105800647713339_117701209856616\";}', 0, '1585129094'),
(5, 8, 'a:12:{i:0;s:31:\"105800647713339_129988378627899\";i:1;s:31:\"122027716090632_128900658736671\";i:2;s:31:\"105800647713339_122027716090632\";i:3;s:31:\"120566762903394_129979551962115\";i:4;s:31:\"120566762903394_128900715403332\";i:5;s:31:\"105800647713339_120566762903394\";i:6;s:31:\"117710829855654_117711066522297\";i:7;s:31:\"117710829855654_127563645537039\";i:8;s:31:\"117710829855654_127563445537059\";i:9;s:31:\"105800647713339_117710829855654\";i:10;s:31:\"117701209856616_117704463189624\";i:11;s:31:\"105800647713339_117701209856616\";}', 1, '1587360391'),
(6, 9, 'a:0:{}', 0, '1585138343'),
(9, 13, 'a:7:{i:0;s:31:\"589212885285087_591893461683696\";i:1;s:31:\"589212885285087_591854805020895\";i:2;s:31:\"589212885285087_589343868605322\";i:3;s:31:\"589212885285087_589262645280111\";i:4;s:31:\"589212885285087_589253321947710\";i:5;s:31:\"589212885285087_589250001948042\";i:6;s:31:\"589212885285087_589212891951753\";}', 1, '1585632127'),
(11, 15, 'a:5:{i:0;s:31:\"105800647713339_120566762903394\";i:1;s:31:\"117710829855654_117711066522297\";i:2;s:31:\"105800647713339_117710829855654\";i:3;s:31:\"117701209856616_117704463189624\";i:4;s:31:\"105800647713339_117701209856616\";}', 0, '1585563806'),
(12, 16, 'a:12:{i:0;s:31:\"105800647713339_129988378627899\";i:1;s:31:\"122027716090632_128900658736671\";i:2;s:31:\"105800647713339_122027716090632\";i:3;s:31:\"120566762903394_129979551962115\";i:4;s:31:\"120566762903394_128900715403332\";i:5;s:31:\"105800647713339_120566762903394\";i:6;s:31:\"117710829855654_117711066522297\";i:7;s:31:\"117710829855654_127563645537039\";i:8;s:31:\"117710829855654_127563445537059\";i:9;s:31:\"105800647713339_117710829855654\";i:10;s:31:\"117701209856616_117704463189624\";i:11;s:31:\"105800647713339_117701209856616\";}', 0, '1587360942'),
(13, 17, 'a:10:{i:0;s:31:\"589212885285087_601639024042473\";i:1;s:31:\"589212885285087_601638160709226\";i:2;s:31:\"589212885285087_601635500709492\";i:3;s:31:\"589212885285087_591893461683696\";i:4;s:31:\"589212885285087_591854805020895\";i:5;s:31:\"589212885285087_589343868605322\";i:6;s:31:\"589212885285087_589262645280111\";i:7;s:31:\"589212885285087_589253321947710\";i:8;s:31:\"589212885285087_589250001948042\";i:9;s:31:\"589212885285087_589212891951753\";}', 0, '1587360993'),
(14, 18, 'a:10:{i:0;s:31:\"589212885285087_601639024042473\";i:1;s:31:\"589212885285087_601638160709226\";i:2;s:31:\"589212885285087_601635500709492\";i:3;s:31:\"589212885285087_591893461683696\";i:4;s:31:\"589212885285087_591854805020895\";i:5;s:31:\"589212885285087_589343868605322\";i:6;s:31:\"589212885285087_589262645280111\";i:7;s:31:\"589212885285087_589253321947710\";i:8;s:31:\"589212885285087_589250001948042\";i:9;s:31:\"589212885285087_589212891951753\";}', 0, '1587376893'),
(15, 19, 'a:13:{i:0;s:31:\"129988378627899_130046171955453\";i:1;s:31:\"105800647713339_129988378627899\";i:2;s:31:\"122027716090632_128900658736671\";i:3;s:31:\"105800647713339_122027716090632\";i:4;s:31:\"120566762903394_129979551962115\";i:5;s:31:\"120566762903394_128900715403332\";i:6;s:31:\"105800647713339_120566762903394\";i:7;s:31:\"117710829855654_117711066522297\";i:8;s:31:\"117710829855654_127563645537039\";i:9;s:31:\"117710829855654_127563445537059\";i:10;s:31:\"105800647713339_117710829855654\";i:11;s:31:\"117701209856616_117704463189624\";i:12;s:31:\"105800647713339_117701209856616\";}', 1, '1587377019');

-- --------------------------------------------------------

--
-- Table structure for table `stream_history`
--

CREATE TABLE `stream_history` (
  `history_id` bigint(20) NOT NULL,
  `stream_id` bigint(20) NOT NULL,
  `id` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `type` tinyint(1) NOT NULL,
  `value` text COLLATE utf8_unicode_ci NOT NULL,
  `created` varchar(30) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stream_tabs`
--

CREATE TABLE `stream_tabs` (
  `tab_id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `tab_name` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `tab_icon` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `refresh` smallint(2) NOT NULL,
  `created` varchar(30) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `stream_tabs`
--

INSERT INTO `stream_tabs` (`tab_id`, `user_id`, `tab_name`, `tab_icon`, `refresh`, `created`) VALUES
(1, 120, 'Home', 'icon-home', 0, '1585044412'),
(5, 124, 'Home', 'icon-home', 0, '1585110753'),
(8, 124, 'FB Group Stream', 'icon-flag', 0, '1585196787'),
(13, 129, 'Home', 'icon-home', 0, '1585563439'),
(15, 124, 'Posts Tab', 'icon-flag', 0, '1587376838');

-- --------------------------------------------------------

--
-- Table structure for table `stream_tabs_streams`
--

CREATE TABLE `stream_tabs_streams` (
  `stream_id` bigint(20) NOT NULL,
  `tab_id` bigint(20) NOT NULL,
  `template` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `network` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `network_id` bigint(20) NOT NULL,
  `alert_sound` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `header_text_color` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `item_text_color` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `links_color` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `icons_color` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `background_color` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `border_color` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `stream_order` tinyint(1) NOT NULL,
  `new_event` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `stream_tabs_streams`
--

INSERT INTO `stream_tabs_streams` (`stream_id`, `tab_id`, `template`, `network`, `network_id`, `alert_sound`, `header_text_color`, `item_text_color`, `links_color`, `icons_color`, `background_color`, `border_color`, `stream_order`, `new_event`) VALUES
(1, 1, 'group_posts', 'facebook_groups', 26, '', '', '', '', '', '', '', 0, 0),
(8, 5, 'page_posts', 'facebook_pages', 103, '', '', '', '', '', '', '', 0, 1),
(15, 13, 'page_posts', 'facebook_pages', 282, '', '', '', '', '', '', '', 0, 0),
(18, 15, 'group_posts', 'facebook_groups', 287, '', '', '', '', '', '', '', 0, 0),
(19, 15, 'page_posts', 'facebook_pages', 103, '', '', '', '', '', '', '', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `stream_tabs_streams_setup`
--

CREATE TABLE `stream_tabs_streams_setup` (
  `setup_id` bigint(20) NOT NULL,
  `stream_id` bigint(20) NOT NULL,
  `template` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `setup_option` text COLLATE utf8_unicode_ci NOT NULL,
  `setup_extra` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subscriptions`
--

CREATE TABLE `subscriptions` (
  `subscription_id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `net_id` text COLLATE utf8_unicode_ci NOT NULL,
  `amount` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `currency` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `period` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `gateway` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL,
  `last_update` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `created` varchar(30) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `teams`
--

CREATE TABLE `teams` (
  `member_id` bigint(20) NOT NULL,
  `user_id` int(11) NOT NULL,
  `member_username` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `member_password` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `member_email` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `role_id` bigint(20) DEFAULT NULL,
  `status` tinyint(1) DEFAULT NULL,
  `about_member` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `date_joined` datetime NOT NULL,
  `last_access` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `teams_roles`
--

CREATE TABLE `teams_roles` (
  `role_id` bigint(20) NOT NULL,
  `user_id` int(11) NOT NULL,
  `role` varchar(250) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `teams_roles_permission`
--

CREATE TABLE `teams_roles_permission` (
  `permission_id` bigint(20) NOT NULL,
  `role_id` int(20) NOT NULL,
  `permission` varchar(250) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `templates`
--

CREATE TABLE `templates` (
  `template_id` bigint(20) NOT NULL,
  `user_id` int(11) NOT NULL,
  `campaign_id` bigint(20) NOT NULL,
  `type` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `body` text COLLATE utf8_unicode_ci NOT NULL,
  `created` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `resend` bigint(20) DEFAULT NULL,
  `list_id` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tickets`
--

CREATE TABLE `tickets` (
  `ticket_id` bigint(20) NOT NULL,
  `user_id` int(11) NOT NULL,
  `subject` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `body` text COLLATE utf8_unicode_ci NOT NULL,
  `attachment` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL,
  `important` tinyint(1) DEFAULT NULL,
  `created` varchar(30) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tickets_meta`
--

CREATE TABLE `tickets_meta` (
  `meta_id` bigint(20) NOT NULL,
  `ticket_id` bigint(20) NOT NULL,
  `user_id` int(11) NOT NULL,
  `body` text COLLATE utf8_unicode_ci NOT NULL,
  `attachment` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `created` varchar(30) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `transaction_id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `net_id` text COLLATE utf8_unicode_ci NOT NULL,
  `amount` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `currency` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `gateway` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL,
  `created` varchar(30) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transactions_fields`
--

CREATE TABLE `transactions_fields` (
  `field_id` bigint(20) NOT NULL,
  `transaction_id` bigint(20) NOT NULL,
  `field_name` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `field_value` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transactions_options`
--

CREATE TABLE `transactions_options` (
  `option_id` bigint(20) NOT NULL,
  `transaction_id` bigint(20) NOT NULL,
  `option_name` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `option_value` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `urls`
--

CREATE TABLE `urls` (
  `url_id` bigint(20) NOT NULL,
  `original_url` text CHARACTER SET utf8 NOT NULL,
  `created` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `urls_stats`
--

CREATE TABLE `urls_stats` (
  `stats_id` bigint(20) NOT NULL,
  `url_id` bigint(20) NOT NULL,
  `network_name` varchar(30) CHARACTER SET utf8 NOT NULL,
  `color` varchar(30) CHARACTER SET utf8 NOT NULL,
  `ip_address` varchar(30) CHARACTER SET utf8 NOT NULL,
  `created` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(254) COLLATE utf8_unicode_ci NOT NULL,
  `last_name` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `first_name` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password` varchar(254) COLLATE utf8_unicode_ci NOT NULL,
  `role` tinyint(1) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `date_joined` datetime NOT NULL,
  `last_access` datetime DEFAULT NULL,
  `ip_address` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `reset_code` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `activate` varchar(100) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `email`, `last_name`, `first_name`, `password`, `role`, `status`, `date_joined`, `last_access`, `ip_address`, `reset_code`, `activate`) VALUES
(104, 'administrator', 'admin@example.com', 'Tester', 'Admin', '$2a$08$RoKr.Dv1Z2egdrpKx8isCuJi/4.73Il2cy82VptMPImccyCMHqSgq', 1, 1, '2016-08-11 10:37:16', '2020-04-21 05:35:03', '', ' ', ''),
(124, 'usertest', 'usertest@yopmail.com', 'Sharma', 'Abhey', '$2a$08$ekpmYQmj2G9sMTT8.CN4B.3/G.Prfytg.v3QhSnKz8WdiB5W6DrVK', 0, 1, '2020-03-25 05:23:22', '2020-04-21 09:11:57', '::1', ' ', ''),
(129, 'test123', 'test@yopmail.com', 'Test', 'Test', '$2a$08$NMFChF4iRstF4aFefpYyf.ZXsup8U3bsXXZwBEpwhAhDeTI7nyxXK', 0, 1, '2020-03-30 12:15:56', '2020-03-30 12:16:14', '::1', ' ', '');

-- --------------------------------------------------------

--
-- Table structure for table `users_meta`
--

CREATE TABLE `users_meta` (
  `meta_id` bigint(20) NOT NULL,
  `user_id` int(11) NOT NULL,
  `meta_name` text COLLATE utf8_unicode_ci NOT NULL,
  `meta_value` text COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `users_meta`
--

INSERT INTO `users_meta` (`meta_id`, `user_id`, `meta_name`, `meta_value`) VALUES
(1, 119, 'plan', '1'),
(2, 119, 'plan_end', '2020-04-18 12:38:05'),
(3, 118, 'plan', '1'),
(4, 118, 'plan_end', '2020-04-22 10:01:34'),
(5, 120, 'plan', '1'),
(6, 120, 'plan_end', '2020-04-22 13:51:04'),
(7, 120, 'username', 'abhey5891'),
(8, 120, 'country', 'India'),
(9, 120, 'city', 'Panchkula'),
(10, 120, 'address', '#484, Sector-12 A'),
(25, 124, 'plan', '1'),
(26, 124, 'plan_end', '2020-04-24 05:23:37'),
(27, 124, 'published_posts', 'a:2:{s:4:\"date\";s:7:\"2020-03\";s:5:\"posts\";i:2;}'),
(43, 129, 'plan', '1'),
(44, 129, 'plan_end', '2020-04-29 12:16:14'),
(45, 129, 'published_posts', 'a:2:{s:4:\"date\";s:7:\"2020-03\";s:5:\"posts\";i:1;}');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activities`
--
ALTER TABLE `activities`
  ADD PRIMARY KEY (`activity_id`);

--
-- Indexes for table `activity`
--
ALTER TABLE `activity`
  ADD PRIMARY KEY (`activity_id`);

--
-- Indexes for table `activity_meta`
--
ALTER TABLE `activity_meta`
  ADD PRIMARY KEY (`meta_id`);

--
-- Indexes for table `add_facebook_users`
--
ALTER TABLE `add_facebook_users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `administrator_dashboard_widgets`
--
ALTER TABLE `administrator_dashboard_widgets`
  ADD PRIMARY KEY (`widget_id`);

--
-- Indexes for table `ads_account`
--
ALTER TABLE `ads_account`
  ADD PRIMARY KEY (`ads_id`);

--
-- Indexes for table `ads_boosts`
--
ALTER TABLE `ads_boosts`
  ADD PRIMARY KEY (`boost_id`);

--
-- Indexes for table `ads_boosts_meta`
--
ALTER TABLE `ads_boosts_meta`
  ADD PRIMARY KEY (`meta_id`);

--
-- Indexes for table `ads_boosts_stats`
--
ALTER TABLE `ads_boosts_stats`
  ADD PRIMARY KEY (`stat_id`);

--
-- Indexes for table `ads_labels`
--
ALTER TABLE `ads_labels`
  ADD PRIMARY KEY (`label_id`);

--
-- Indexes for table `ads_labels_meta`
--
ALTER TABLE `ads_labels_meta`
  ADD PRIMARY KEY (`meta_id`);

--
-- Indexes for table `ads_labels_stats`
--
ALTER TABLE `ads_labels_stats`
  ADD PRIMARY KEY (`stat_id`);

--
-- Indexes for table `ads_networks`
--
ALTER TABLE `ads_networks`
  ADD PRIMARY KEY (`network_id`);

--
-- Indexes for table `bots`
--
ALTER TABLE `bots`
  ADD PRIMARY KEY (`bot_id`);

--
-- Indexes for table `campaigns`
--
ALTER TABLE `campaigns`
  ADD PRIMARY KEY (`campaign_id`);

--
-- Indexes for table `campaigns_meta`
--
ALTER TABLE `campaigns_meta`
  ADD PRIMARY KEY (`meta_id`);

--
-- Indexes for table `classifications`
--
ALTER TABLE `classifications`
  ADD PRIMARY KEY (`classification_id`);

--
-- Indexes for table `classifications_meta`
--
ALTER TABLE `classifications_meta`
  ADD PRIMARY KEY (`meta_id`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`comment_id`);

--
-- Indexes for table `contents`
--
ALTER TABLE `contents`
  ADD PRIMARY KEY (`content_id`);

--
-- Indexes for table `contents_classifications`
--
ALTER TABLE `contents_classifications`
  ADD PRIMARY KEY (`classification_id`);

--
-- Indexes for table `contents_meta`
--
ALTER TABLE `contents_meta`
  ADD PRIMARY KEY (`meta_id`);

--
-- Indexes for table `coupons`
--
ALTER TABLE `coupons`
  ADD PRIMARY KEY (`coupon_id`);

--
-- Indexes for table `dictionary`
--
ALTER TABLE `dictionary`
  ADD PRIMARY KEY (`dict_id`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`employee_id`);

--
-- Indexes for table `facebook_logs`
--
ALTER TABLE `facebook_logs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `facebook_logs_id_uindex` (`id`),
  ADD KEY `facebook_user_id` (`facebook_user_id`),
  ADD KEY `username` (`username`);

--
-- Indexes for table `facebook_users`
--
ALTER TABLE `facebook_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `facebook_users_id_uindex` (`id`),
  ADD UNIQUE KEY `facebook_users_pk` (`username`),
  ADD KEY `username` (`username`);

--
-- Indexes for table `faq_articles`
--
ALTER TABLE `faq_articles`
  ADD PRIMARY KEY (`article_id`);

--
-- Indexes for table `faq_articles_categories`
--
ALTER TABLE `faq_articles_categories`
  ADD PRIMARY KEY (`meta_id`);

--
-- Indexes for table `faq_articles_meta`
--
ALTER TABLE `faq_articles_meta`
  ADD PRIMARY KEY (`meta_id`);

--
-- Indexes for table `faq_categories`
--
ALTER TABLE `faq_categories`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `faq_categories_meta`
--
ALTER TABLE `faq_categories_meta`
  ADD PRIMARY KEY (`meta_id`);

--
-- Indexes for table `favorites`
--
ALTER TABLE `favorites`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `favorites_id_uindex` (`id`);

--
-- Indexes for table `guides`
--
ALTER TABLE `guides`
  ADD PRIMARY KEY (`guide_id`);

--
-- Indexes for table `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`invoice_id`);

--
-- Indexes for table `invoices_options`
--
ALTER TABLE `invoices_options`
  ADD PRIMARY KEY (`option_id`);

--
-- Indexes for table `invoices_templates`
--
ALTER TABLE `invoices_templates`
  ADD PRIMARY KEY (`template_id`);

--
-- Indexes for table `lists`
--
ALTER TABLE `lists`
  ADD PRIMARY KEY (`list_id`);

--
-- Indexes for table `lists_meta`
--
ALTER TABLE `lists_meta`
  ADD PRIMARY KEY (`meta_id`);

--
-- Indexes for table `media`
--
ALTER TABLE `media`
  ADD PRIMARY KEY (`media_id`);

--
-- Indexes for table `networks`
--
ALTER TABLE `networks`
  ADD PRIMARY KEY (`network_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`notification_id`);

--
-- Indexes for table `notifications_stats`
--
ALTER TABLE `notifications_stats`
  ADD PRIMARY KEY (`stat_id`);

--
-- Indexes for table `oauth_applications`
--
ALTER TABLE `oauth_applications`
  ADD PRIMARY KEY (`application_id`);

--
-- Indexes for table `oauth_applications_permissions`
--
ALTER TABLE `oauth_applications_permissions`
  ADD PRIMARY KEY (`permission_id`);

--
-- Indexes for table `oauth_authorization_codes`
--
ALTER TABLE `oauth_authorization_codes`
  ADD PRIMARY KEY (`code_id`);

--
-- Indexes for table `oauth_authorization_codes_permissions`
--
ALTER TABLE `oauth_authorization_codes_permissions`
  ADD PRIMARY KEY (`permission_id`);

--
-- Indexes for table `oauth_permissions`
--
ALTER TABLE `oauth_permissions`
  ADD PRIMARY KEY (`permission_id`);

--
-- Indexes for table `oauth_tokens`
--
ALTER TABLE `oauth_tokens`
  ADD PRIMARY KEY (`token_id`);

--
-- Indexes for table `oauth_tokens_permissions`
--
ALTER TABLE `oauth_tokens_permissions`
  ADD PRIMARY KEY (`permission_id`);

--
-- Indexes for table `options`
--
ALTER TABLE `options`
  ADD PRIMARY KEY (`option_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `plans`
--
ALTER TABLE `plans`
  ADD PRIMARY KEY (`plan_id`);

--
-- Indexes for table `plans_meta`
--
ALTER TABLE `plans_meta`
  ADD PRIMARY KEY (`meta_id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`post_id`);

--
-- Indexes for table `posts_meta`
--
ALTER TABLE `posts_meta`
  ADD PRIMARY KEY (`meta_id`);

--
-- Indexes for table `referrals`
--
ALTER TABLE `referrals`
  ADD PRIMARY KEY (`referrer_id`);

--
-- Indexes for table `resend`
--
ALTER TABLE `resend`
  ADD PRIMARY KEY (`resend_id`);

--
-- Indexes for table `resend_meta`
--
ALTER TABLE `resend_meta`
  ADD PRIMARY KEY (`meta_id`);

--
-- Indexes for table `resend_rules`
--
ALTER TABLE `resend_rules`
  ADD PRIMARY KEY (`rule_id`);

--
-- Indexes for table `rss`
--
ALTER TABLE `rss`
  ADD PRIMARY KEY (`rss_id`);

--
-- Indexes for table `rss_accounts`
--
ALTER TABLE `rss_accounts`
  ADD PRIMARY KEY (`account_id`);

--
-- Indexes for table `rss_posts`
--
ALTER TABLE `rss_posts`
  ADD PRIMARY KEY (`post_id`);

--
-- Indexes for table `rss_posts_meta`
--
ALTER TABLE `rss_posts_meta`
  ADD PRIMARY KEY (`meta_id`);

--
-- Indexes for table `scheduled`
--
ALTER TABLE `scheduled`
  ADD PRIMARY KEY (`scheduled_id`);

--
-- Indexes for table `scheduled_stats`
--
ALTER TABLE `scheduled_stats`
  ADD PRIMARY KEY (`stat_id`);

--
-- Indexes for table `stream_cronology`
--
ALTER TABLE `stream_cronology`
  ADD PRIMARY KEY (`cronology_id`);

--
-- Indexes for table `stream_history`
--
ALTER TABLE `stream_history`
  ADD PRIMARY KEY (`history_id`);

--
-- Indexes for table `stream_tabs`
--
ALTER TABLE `stream_tabs`
  ADD PRIMARY KEY (`tab_id`);

--
-- Indexes for table `stream_tabs_streams`
--
ALTER TABLE `stream_tabs_streams`
  ADD PRIMARY KEY (`stream_id`);

--
-- Indexes for table `stream_tabs_streams_setup`
--
ALTER TABLE `stream_tabs_streams_setup`
  ADD PRIMARY KEY (`setup_id`);

--
-- Indexes for table `subscriptions`
--
ALTER TABLE `subscriptions`
  ADD PRIMARY KEY (`subscription_id`);

--
-- Indexes for table `teams`
--
ALTER TABLE `teams`
  ADD PRIMARY KEY (`member_id`);

--
-- Indexes for table `teams_roles`
--
ALTER TABLE `teams_roles`
  ADD PRIMARY KEY (`role_id`);

--
-- Indexes for table `teams_roles_permission`
--
ALTER TABLE `teams_roles_permission`
  ADD PRIMARY KEY (`permission_id`);

--
-- Indexes for table `templates`
--
ALTER TABLE `templates`
  ADD PRIMARY KEY (`template_id`);

--
-- Indexes for table `tickets`
--
ALTER TABLE `tickets`
  ADD PRIMARY KEY (`ticket_id`);

--
-- Indexes for table `tickets_meta`
--
ALTER TABLE `tickets_meta`
  ADD PRIMARY KEY (`meta_id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`transaction_id`);

--
-- Indexes for table `transactions_fields`
--
ALTER TABLE `transactions_fields`
  ADD PRIMARY KEY (`field_id`);

--
-- Indexes for table `transactions_options`
--
ALTER TABLE `transactions_options`
  ADD PRIMARY KEY (`option_id`);

--
-- Indexes for table `urls`
--
ALTER TABLE `urls`
  ADD PRIMARY KEY (`url_id`);

--
-- Indexes for table `urls_stats`
--
ALTER TABLE `urls_stats`
  ADD PRIMARY KEY (`stats_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `users_meta`
--
ALTER TABLE `users_meta`
  ADD PRIMARY KEY (`meta_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activities`
--
ALTER TABLE `activities`
  MODIFY `activity_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `activity`
--
ALTER TABLE `activity`
  MODIFY `activity_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `activity_meta`
--
ALTER TABLE `activity_meta`
  MODIFY `meta_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `add_facebook_users`
--
ALTER TABLE `add_facebook_users`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `administrator_dashboard_widgets`
--
ALTER TABLE `administrator_dashboard_widgets`
  MODIFY `widget_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ads_account`
--
ALTER TABLE `ads_account`
  MODIFY `ads_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ads_boosts`
--
ALTER TABLE `ads_boosts`
  MODIFY `boost_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ads_boosts_meta`
--
ALTER TABLE `ads_boosts_meta`
  MODIFY `meta_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ads_boosts_stats`
--
ALTER TABLE `ads_boosts_stats`
  MODIFY `stat_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ads_labels`
--
ALTER TABLE `ads_labels`
  MODIFY `label_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ads_labels_meta`
--
ALTER TABLE `ads_labels_meta`
  MODIFY `meta_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ads_labels_stats`
--
ALTER TABLE `ads_labels_stats`
  MODIFY `stat_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ads_networks`
--
ALTER TABLE `ads_networks`
  MODIFY `network_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bots`
--
ALTER TABLE `bots`
  MODIFY `bot_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `campaigns`
--
ALTER TABLE `campaigns`
  MODIFY `campaign_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `campaigns_meta`
--
ALTER TABLE `campaigns_meta`
  MODIFY `meta_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `classifications`
--
ALTER TABLE `classifications`
  MODIFY `classification_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=73;

--
-- AUTO_INCREMENT for table `classifications_meta`
--
ALTER TABLE `classifications_meta`
  MODIFY `meta_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=361;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `comment_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `contents`
--
ALTER TABLE `contents`
  MODIFY `content_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `contents_classifications`
--
ALTER TABLE `contents_classifications`
  MODIFY `classification_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `contents_meta`
--
ALTER TABLE `contents_meta`
  MODIFY `meta_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `coupons`
--
ALTER TABLE `coupons`
  MODIFY `coupon_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dictionary`
--
ALTER TABLE `dictionary`
  MODIFY `dict_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `employee_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `facebook_logs`
--
ALTER TABLE `facebook_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `facebook_users`
--
ALTER TABLE `facebook_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `faq_articles`
--
ALTER TABLE `faq_articles`
  MODIFY `article_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `faq_articles_categories`
--
ALTER TABLE `faq_articles_categories`
  MODIFY `meta_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `faq_articles_meta`
--
ALTER TABLE `faq_articles_meta`
  MODIFY `meta_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `faq_categories`
--
ALTER TABLE `faq_categories`
  MODIFY `category_id` int(6) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `faq_categories_meta`
--
ALTER TABLE `faq_categories_meta`
  MODIFY `meta_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `favorites`
--
ALTER TABLE `favorites`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `guides`
--
ALTER TABLE `guides`
  MODIFY `guide_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `invoice_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `invoices_options`
--
ALTER TABLE `invoices_options`
  MODIFY `option_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `invoices_templates`
--
ALTER TABLE `invoices_templates`
  MODIFY `template_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lists`
--
ALTER TABLE `lists`
  MODIFY `list_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `lists_meta`
--
ALTER TABLE `lists_meta`
  MODIFY `meta_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `media`
--
ALTER TABLE `media`
  MODIFY `media_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `networks`
--
ALTER TABLE `networks`
  MODIFY `network_id` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=317;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `notification_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2014;

--
-- AUTO_INCREMENT for table `notifications_stats`
--
ALTER TABLE `notifications_stats`
  MODIFY `stat_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `oauth_applications`
--
ALTER TABLE `oauth_applications`
  MODIFY `application_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `oauth_applications_permissions`
--
ALTER TABLE `oauth_applications_permissions`
  MODIFY `permission_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `oauth_authorization_codes`
--
ALTER TABLE `oauth_authorization_codes`
  MODIFY `code_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `oauth_authorization_codes_permissions`
--
ALTER TABLE `oauth_authorization_codes_permissions`
  MODIFY `permission_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `oauth_permissions`
--
ALTER TABLE `oauth_permissions`
  MODIFY `permission_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `oauth_tokens`
--
ALTER TABLE `oauth_tokens`
  MODIFY `token_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `oauth_tokens_permissions`
--
ALTER TABLE `oauth_tokens_permissions`
  MODIFY `permission_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `options`
--
ALTER TABLE `options`
  MODIFY `option_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=90;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `plans`
--
ALTER TABLE `plans`
  MODIFY `plan_id` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `plans_meta`
--
ALTER TABLE `plans_meta`
  MODIFY `meta_id` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `post_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `posts_meta`
--
ALTER TABLE `posts_meta`
  MODIFY `meta_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `referrals`
--
ALTER TABLE `referrals`
  MODIFY `referrer_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `resend`
--
ALTER TABLE `resend`
  MODIFY `resend_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `resend_meta`
--
ALTER TABLE `resend_meta`
  MODIFY `meta_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `resend_rules`
--
ALTER TABLE `resend_rules`
  MODIFY `rule_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rss`
--
ALTER TABLE `rss`
  MODIFY `rss_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rss_accounts`
--
ALTER TABLE `rss_accounts`
  MODIFY `account_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rss_posts`
--
ALTER TABLE `rss_posts`
  MODIFY `post_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rss_posts_meta`
--
ALTER TABLE `rss_posts_meta`
  MODIFY `meta_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `scheduled`
--
ALTER TABLE `scheduled`
  MODIFY `scheduled_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `scheduled_stats`
--
ALTER TABLE `scheduled_stats`
  MODIFY `stat_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stream_cronology`
--
ALTER TABLE `stream_cronology`
  MODIFY `cronology_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `stream_history`
--
ALTER TABLE `stream_history`
  MODIFY `history_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stream_tabs`
--
ALTER TABLE `stream_tabs`
  MODIFY `tab_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `stream_tabs_streams`
--
ALTER TABLE `stream_tabs_streams`
  MODIFY `stream_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `stream_tabs_streams_setup`
--
ALTER TABLE `stream_tabs_streams_setup`
  MODIFY `setup_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subscriptions`
--
ALTER TABLE `subscriptions`
  MODIFY `subscription_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `teams`
--
ALTER TABLE `teams`
  MODIFY `member_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `teams_roles`
--
ALTER TABLE `teams_roles`
  MODIFY `role_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `teams_roles_permission`
--
ALTER TABLE `teams_roles_permission`
  MODIFY `permission_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `templates`
--
ALTER TABLE `templates`
  MODIFY `template_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tickets`
--
ALTER TABLE `tickets`
  MODIFY `ticket_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tickets_meta`
--
ALTER TABLE `tickets_meta`
  MODIFY `meta_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `transaction_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transactions_fields`
--
ALTER TABLE `transactions_fields`
  MODIFY `field_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transactions_options`
--
ALTER TABLE `transactions_options`
  MODIFY `option_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `urls`
--
ALTER TABLE `urls`
  MODIFY `url_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `urls_stats`
--
ALTER TABLE `urls_stats`
  MODIFY `stats_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=131;

--
-- AUTO_INCREMENT for table `users_meta`
--
ALTER TABLE `users_meta`
  MODIFY `meta_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
