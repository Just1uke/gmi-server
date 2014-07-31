-- Server version: 5.5.38
-- PHP Version: 5.3.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

CREATE TABLE IF NOT EXISTS `orders` (
  `id` int(11) NOT NULL,
  `type` varchar(255) NOT NULL,
  `price` int(11) NOT NULL,
  `ql` int(11) NOT NULL,
  `count` int(11) NOT NULL,
  `user` varchar(255) NOT NULL,
  `time_left` timestamp NULL DEFAULT NULL,
  `item` varchar(255) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `orders`
 ADD PRIMARY KEY (`id`);
