/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Table structure for table `rsvp_test`
--

--
-- Table structure for table `rsvp_test`
--

CREATE TABLE IF NOT EXISTS `rsvp_test` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `DATE` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `email` varchar(256) COLLATE utf8_unicode_ci NOT NULL,
  `firstName` varchar(256) COLLATE utf8_unicode_ci NOT NULL,
  `lastName` varchar(256) COLLATE utf8_unicode_ci NOT NULL,
  `postal` varchar(7) COLLATE utf8_unicode_ci NOT NULL,
  `gender` varchar(7) COLLATE utf8_unicode_ci NOT NULL,
  `category` varchar(7) COLLATE utf8_unicode_ci NOT NULL,
  `company` varchar(256) COLLATE utf8_unicode_ci NOT NULL,
  `guestOf` varchar(256) COLLATE utf8_unicode_ci NOT NULL,
  `guestFirstName` varchar(256) COLLATE utf8_unicode_ci NOT NULL,
  `guestLastName` varchar(256) COLLATE utf8_unicode_ci NOT NULL,
  `guestEmail` varchar(256) COLLATE utf8_unicode_ci NOT NULL,
  UNIQUE KEY `ID` (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=143 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
