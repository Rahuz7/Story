-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Nov 14, 2021 at 11:25 PM
-- Server version: 10.4.21-MariaDB
-- PHP Version: 8.0.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `story`
--

-- --------------------------------------------------------

--
-- Table structure for table `Posts`
--

CREATE TABLE `Posts` (
  `Post_id` int(11) NOT NULL,
  `User_id` int(11) NOT NULL,
  `Setup` varchar(600) NOT NULL,
  `Punchline` varchar(600) NOT NULL,
  `Type` varchar(50) NOT NULL,
  `Creation_Date` datetime NOT NULL,
  `Modification_Date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- RELATIONSHIPS FOR TABLE `Posts`:
--

--
-- Dumping data for table `Posts`
--

INSERT INTO `Posts` (`Post_id`, `User_id`, `Setup`, `Punchline`, `Type`, `Creation_Date`, `Modification_Date`) VALUES
(1, 1, 'All my life, my parents have told me not to open the basement door, but I got curious and disobeyed them.', 'What is that glowing ball in the sky and why does it hurt my eyes?', 'Short horror story', '2021-11-12 13:49:00', '2021-11-12 13:49:30'),
(2, 1, 'When the kidnapper made me guess where he kept my daughter, I went for the basement and he said \"Correct!\" allowing me to see her.', 'But when I found her severed head in there, I learned that every other choice would have been correct as well.', 'Short horror story', '2021-11-12 13:50:00', '2021-11-12 13:50:30'),
(3, 1, '...she said last time, we\'re stuck in a time loop', 'Which really pisses me off because that\'s what...', 'Short horror story', '2021-11-12 13:51:00', '2021-11-12 13:51:30'),
(4, 2, '\"Now be careful, that line of rock salt is the only thing keeping them out,\" the man said, welcoming my group into his refuge.', '\"Sea salt,\" I clarified, \"sea salt keeps us out.\"', 'Short horror story', '2021-11-12 13:50:00', '2021-11-12 13:50:30'),
(5, 2, 'I framed the first letter I got as a police officer, from a woman thanking me after I\'d supported her through her daughter\'s suicide.', 'I passed it in my hallway every day for nearly eight years before realising the handwriting was the same as on the girl\'s suicide note.', 'Short horror story', '2021-11-12 13:52:00', '2021-11-12 13:52:30'),
(6, 2, 'Please, take me instead! I scream, grabbing at the two men who took my child\" allowing me to see her.', '“Sorry ma’am, children only” they said, as they continue loading up the last lifeboat on the ship.', 'Short horror story', '2021-11-12 13:53:00', '2021-11-12 13:53:30'),
(7, 1, 'I always clean off my plate no matter how full I am.', 'I have no problem with letting food go to waist.', 'Joke', '2021-11-12 14:01:00', '2021-11-12 14:01:30'),
(8, 2, 'I\'m being arrested for curing pedophilia!?', 'Apparently killing every child in the world is \"a horrible crime\".', 'Joke', '2021-11-12 14:02:00', '2021-11-12 14:02:30'),
(9, 2, 'Someone just made me aware that I’m a Narcissist.', 'Fuck, I thought I was better than that.', 'Joke', '2021-11-12 14:03:00', '2021-11-12 14:03:30'),
(10, 2, '\"I beg your pardon\" said the hearing impaired criminal after the king sentenced him to life in prison.', 'The king responded - \"No damn way.\"', 'Joke', '2021-11-12 14:04:00', '2021-11-12 14:04:30');

-- --------------------------------------------------------

--
-- Table structure for table `Users`
--

CREATE TABLE `Users` (
  `User_id` int(11) NOT NULL,
  `Username` varchar(30) NOT NULL,
  `Password` varchar(60) NOT NULL,
  `Creation_Date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- RELATIONSHIPS FOR TABLE `Users`:
--

--
-- Dumping data for table `Users`
--

INSERT INTO `Users` (`User_id`, `Username`, `Password`, `Creation_Date`) VALUES
(1, 'vanier', '$2y$10$p2/ffLrJegZ35ylWzg49aeE/sLejfiype8w4lNN7XlYm/n/IWrEBq', '2021-11-12 13:49:00'),
(2, 'lecarpentier', '$2y$10$p2/ffLrJegZ35ylWzg49aeE/sLejfiype8w4lNN7XlYm/n/IWrEBq', '2021-11-12 13:49:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Posts`
--
ALTER TABLE `Posts`
  ADD PRIMARY KEY (`Post_id`);

--
-- Indexes for table `Users`
--
ALTER TABLE `Users`
  ADD PRIMARY KEY (`User_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Posts`
--
ALTER TABLE `Posts`
  MODIFY `Post_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `Users`
--
ALTER TABLE `Users`
  MODIFY `User_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
