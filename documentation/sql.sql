-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jan 28, 2016 at 10:03 PM
-- Server version: 5.6.17
-- PHP Version: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `argos`
--

-- --------------------------------------------------------

--
-- Table structure for table `aboutus`
--

CREATE TABLE IF NOT EXISTS `aboutus` (
  `aboutus` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `aboutus`
--

INSERT INTO `aboutus` (`aboutus`) VALUES
('<strong>Lorem Ipsum</strong><span style="color:rgb(0, 0, 0); font-family:arial,helvetica,sans; \r\nfont-size:11px">\r\n&nbsp;е елементарен примерен текст, \r\nизползван в печатарската и типографската индустрия. Lorem Ipsum е индустриален стандарт от около \r\n1500 година, когато неизвестен печатар взема няколко печатарски букви и ги разбърква, за да \r\nнапечата с тях книга с примерни шрифтове. Този начин не само е оцелял повече от 5 века, \r\nно е навлязъл и в публикуването на електронни издания като е запазен почти без промяна. \r\nПопуляризиран е през 60те години на 20ти век със издаването на Letraset листи, \r\nсъдържащи Lorem Ipsum пасажи, популярен е и в наши дни във софтуер за печатни \r\nиздания като Aldus PageMaker, който включва различни версии на Lorem Ipsum.</span>');

-- --------------------------------------------------------

--
-- Table structure for table `advertise`
--

CREATE TABLE IF NOT EXISTS `advertise` (
  `id` int(12) NOT NULL AUTO_INCREMENT,
  `type` text COLLATE utf8_unicode_ci,
  `site_link` text COLLATE utf8_unicode_ci,
  `dobaven_na` text COLLATE utf8_unicode_ci,
  `banner_img` text COLLATE utf8_unicode_ci,
  `expire` text COLLATE utf8_unicode_ci,
  `link_title` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `banners`
--

CREATE TABLE IF NOT EXISTS `banners` (
  `id` int(12) NOT NULL AUTO_INCREMENT,
  `type` text COLLATE utf8_unicode_ci,
  `site_link` text COLLATE utf8_unicode_ci,
  `banner_img` text COLLATE utf8_unicode_ci,
  `link_title` text COLLATE utf8_unicode_ci,
  `avtor` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `chat`
--

CREATE TABLE IF NOT EXISTS `chat` (
  `id` int(12) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `text` text NOT NULL,
  `date` varchar(255) NOT NULL,
  `avatar` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=31 ;

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE IF NOT EXISTS `comments` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `author` text NOT NULL,
  `text` text NOT NULL,
  `date` text NOT NULL,
  `avatar` text NOT NULL,
  `nick_colour` text NOT NULL,
  `user_id` text NOT NULL,
  `newsid` text NOT NULL,
  `vote` varchar(255) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Table structure for table `config`
--

CREATE TABLE IF NOT EXISTS `config` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `site_name` text NOT NULL,
  `logo_text_small` text NOT NULL,
  `logo_text_big` text NOT NULL,
  `favicon` text NOT NULL,
  `admin_email` text NOT NULL,
  `chat_enable` text NOT NULL,
  `gallery_enable` text NOT NULL,
  `img_upload_enable` text NOT NULL,
  `file_upload_enable` text NOT NULL,
  `poll_enable` text NOT NULL,
  `footer_stats_enable` text NOT NULL,
  `socials_enable` text NOT NULL,
  `fb_link` text NOT NULL,
  `tw_link` text NOT NULL,
  `goo_link` text NOT NULL,
  `servers_enable` text NOT NULL,
  `video_enable` text NOT NULL,
  `cookie_policy` text NOT NULL,
  `default_language` text NOT NULL,
  `head_box_text` text NOT NULL,
  `last_news_link` text NOT NULL,
  `last_news_name` text NOT NULL,
  `google_analytics` text,
  `google_site_verify` text,
  `rating_enable` varchar(255) DEFAULT '0',
  `recaptcha_secret` text,
  `recaptcha_sitekey` text,
  `style` varchar(255) NOT NULL DEFAULT 'default',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;


INSERT INTO `config` (`id`, `site_name`, `logo_text_small`, `logo_text_big`, `favicon`, `admin_email`, `chat_enable`, `gallery_enable`, `img_upload_enable`, `file_upload_enable`, `poll_enable`, `footer_stats_enable`, `socials_enable`, `fb_link`, `tw_link`, `goo_link`, `servers_enable`, `video_enable`, `cookie_policy`, `default_language`, `head_box_text`, `last_news_link`, `last_news_name`, `google_analytics`, `google_site_verify`, `rating_enable`, `recaptcha_secret`, `recaptcha_sitekey`, `style`) VALUES
(1, 'Argos', 'Your Gaming Community', 'ARGOS', 'assets/img/favicon.ico', 'dedihost@data.bg', '1', '1', '1', '1', '1', '1', '1', 'http://facebook.com', 'http://twitter.com', 'http://google.bg', '1', '1', '1', 'bg', 'Welcome to Argos!<br/>Най-добрата multi-gaming система в България!', 'http://abv.bg', 'Света се пречупи.', '', '', '1', NULL, NULL,'default');

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

CREATE TABLE IF NOT EXISTS `contacts` (
  `id` int(12) NOT NULL AUTO_INCREMENT,
  `date` text COLLATE utf8_unicode_ci,
  `ip` text COLLATE utf8_unicode_ci,
  `username` text COLLATE utf8_unicode_ci,
  `text` text COLLATE utf8_unicode_ci,
  `question` text COLLATE utf8_unicode_ci,
  `email` text COLLATE utf8_unicode_ci,
  `respond` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `dpolls`
--

CREATE TABLE IF NOT EXISTS `dpolls` (
  `id` int(12) NOT NULL AUTO_INCREMENT,
  `poll_question` text NOT NULL,
  `poll_answer` text NOT NULL,
  `poll_votes` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Table structure for table `dpolls_votes`
--

CREATE TABLE IF NOT EXISTS `dpolls_votes` (
  `id` int(12) NOT NULL AUTO_INCREMENT,
  `poll_id` text NOT NULL,
  `ip` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Table structure for table `files`
--

CREATE TABLE IF NOT EXISTS `files` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `picture` text NOT NULL,
  `author` text NOT NULL,
  `down_counts` text NOT NULL,
  `date` text NOT NULL,
  `size` text NOT NULL,
  `type` text NOT NULL,
  `game` text NOT NULL,
  `type_not_real` text NOT NULL,
  `game_not_real` text NOT NULL,
  `category` text NOT NULL,
  `opisanie` text NOT NULL,
  `link` text NOT NULL,
  `name` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Table structure for table `gallery`
--

CREATE TABLE IF NOT EXISTS `gallery` (
  `id` int(12) NOT NULL AUTO_INCREMENT,
  `date` text COLLATE utf8_unicode_ci,
  `uploader` text COLLATE utf8_unicode_ci,
  `pic_link` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `greyfish_servers`
--

CREATE TABLE IF NOT EXISTS `greyfish_servers` (
  `id` int(12) NOT NULL AUTO_INCREMENT,
  `ip` text NOT NULL,
  `port` text NOT NULL,
  `players` text NOT NULL,
  `maxplayers` text NOT NULL,
  `version` text NOT NULL,
  `type` text NOT NULL,
  `map` text NOT NULL,
  `hostname` text NOT NULL,
  `vote` text NOT NULL,
  `status` text NOT NULL,
  `last_update` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Table structure for table `jquery_js`
--

CREATE TABLE IF NOT EXISTS `jquery_js` (
  `jquery_js` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `jquery_js`
--

INSERT INTO `jquery_js` (`jquery_js`) VALUES
('');

-- --------------------------------------------------------

--
-- Table structure for table `menus`
--

CREATE TABLE IF NOT EXISTS `menus` (
  `id` int(12) NOT NULL AUTO_INCREMENT,
  `title` text NOT NULL,
  `the_content` text NOT NULL,
  `position` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12 ;

--
-- Dumping data for table `menus`
--

INSERT INTO `menus` (`id`, `title`, `the_content`, `position`) VALUES
(7, 'Времето', 'Времето в София:<br />\r\n<br />\r\n<iframe height="120" src="http://sinoptik.bg/widget/100727011/4/150/120/6?url=http://sinoptik.bg/widgets" style="border:none;background:none" width="150"></iframe>', 'right'),
(9, 'BGtop', 'Гласувай за нас всеки ден!&lt;br/&gt;<br />\r\n<a href="#"><img alt="" src="http://i.imgur.com/OMKNJim.gif" /></a>', 'right');

-- --------------------------------------------------------

--
-- Table structure for table `news`
--

CREATE TABLE IF NOT EXISTS `news` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `author` text NOT NULL,
  `title` text NOT NULL,
  `seourl` text NOT NULL,
  `text` text NOT NULL,
  `date` varchar(128) DEFAULT NULL,
  `comments` int(3) DEFAULT NULL,
  `comments_enabled` varchar(128) DEFAULT NULL,
  `img` text NOT NULL,
  `vote` varchar(255) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=28 ;

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

CREATE TABLE IF NOT EXISTS `pages` (
  `id` int(12) NOT NULL AUTO_INCREMENT,
  `page_name` text NOT NULL,
  `page_title` text NOT NULL,
  `menu_type` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Table structure for table `sliders`
--

CREATE TABLE IF NOT EXISTS `sliders` (
  `id` int(12) NOT NULL AUTO_INCREMENT,
  `slider_img` text COLLATE utf8_unicode_ci,
  `is_link` text COLLATE utf8_unicode_ci,
  `slider_link` text COLLATE utf8_unicode_ci,
  `text` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `stats`
--

CREATE TABLE IF NOT EXISTS `stats` (
  `id` int(12) NOT NULL AUTO_INCREMENT,
  `date` date DEFAULT NULL,
  `ip` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Table structure for table `uploadvideos`
--

CREATE TABLE IF NOT EXISTS `uploadvideos` (
  `id` int(12) NOT NULL AUTO_INCREMENT,
  `uploader` text NOT NULL,
  `videolink` text NOT NULL,
  `date` text NOT NULL,
  `cat` text NOT NULL,
  `site` text NOT NULL,
  `approved` text NOT NULL,
  `original_title` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Table structure for table `videocat`
--

CREATE TABLE IF NOT EXISTS `videocat` (
  `id` int(12) NOT NULL AUTO_INCREMENT,
  `category` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Table structure for table `voting_ip`
--

CREATE TABLE IF NOT EXISTS `voting_ip` (
  `ip_id` int(11) NOT NULL AUTO_INCREMENT,
  `mes_id_fk` int(11) DEFAULT NULL,
  `ip_add` varchar(40) DEFAULT NULL,
  PRIMARY KEY (`ip_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `voting_ip_comments`
--

CREATE TABLE IF NOT EXISTS `voting_ip_comments` (
  `ip_id` int(11) NOT NULL AUTO_INCREMENT,
  `mes_id_fk` int(11) DEFAULT NULL,
  `ip_add` varchar(40) DEFAULT NULL,
  PRIMARY KEY (`ip_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `voting_ip_news`
--

CREATE TABLE IF NOT EXISTS `voting_ip_news` (
  `ip_id` int(11) NOT NULL AUTO_INCREMENT,
  `mes_id_fk` int(11) DEFAULT NULL,
  `ip_add` varchar(40) DEFAULT NULL,
  PRIMARY KEY (`ip_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
