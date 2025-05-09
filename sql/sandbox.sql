-- phpMyAdmin SQL Dump
-- version 5.1.1deb5ubuntu1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Creato il: Mag 09, 2025 alle 13:11
-- Versione del server: 8.0.42-0ubuntu0.22.04.1
-- Versione PHP: 8.1.32

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sandbox`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `music_albums`
--

CREATE TABLE `music_albums` (
  `id` int NOT NULL,
  `title` varchar(150) NOT NULL,
  `artist` varchar(100) NOT NULL,
  `genre` varchar(50) DEFAULT NULL,
  `release_year` year DEFAULT NULL,
  `track_count` int DEFAULT NULL,
  `total_duration_minutes` int DEFAULT NULL,
  `label` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dump dei dati per la tabella `music_albums`
--

INSERT INTO `music_albums` (`id`, `title`, `artist`, `genre`, `release_year`, `track_count`, `total_duration_minutes`, `label`) VALUES
(1, 'What\'s Going On', 'Marvin Gaye', 'Soul', 1971, NULL, NULL, NULL),
(2, 'Pet Sounds', 'The Beach Boys', 'Pop', 1966, NULL, NULL, NULL),
(3, 'Blue', 'Joni Mitchell', 'Folk', 1971, NULL, NULL, NULL),
(4, 'Songs in the Key of Life', 'Stevie Wonder', 'Soul', 1976, NULL, NULL, NULL),
(5, 'Abbey Road', 'The Beatles', 'Rock', 1969, NULL, NULL, NULL),
(6, 'Nevermind', 'Nirvana', 'Grunge', 1991, NULL, NULL, NULL),
(7, 'Rumours', 'Fleetwood Mac', 'Rock', 1977, NULL, NULL, NULL),
(8, 'Purple Rain', 'Prince and The Revolution', 'Pop', 1984, NULL, NULL, NULL),
(9, 'Blood on the Tracks', 'Bob Dylan', 'Folk Rock', 1975, NULL, NULL, NULL),
(10, 'The Miseducation of Lauryn Hill', 'Lauryn Hill', 'R&B', 1998, NULL, NULL, NULL),
(11, 'Revolver', 'The Beatles', 'Rock', 1966, NULL, NULL, NULL),
(12, 'Thriller', 'Michael Jackson', 'Pop', 1982, NULL, NULL, NULL),
(13, 'I Never Loved a Man the Way I Love You', 'Aretha Franklin', 'Soul', 1967, NULL, NULL, NULL),
(14, 'Exile on Main St.', 'The Rolling Stones', 'Rock', 1972, NULL, NULL, NULL),
(15, 'It Takes a Nation of Millions to Hold Us Back', 'Public Enemy', 'Hip Hop', 1988, NULL, NULL, NULL),
(16, 'London Calling', 'The Clash', 'Punk Rock', 1979, NULL, NULL, NULL),
(17, 'My Beautiful Dark Twisted Fantasy', 'Kanye West', 'Hip Hop', 2010, NULL, NULL, NULL),
(18, 'Highway 61 Revisited', 'Bob Dylan', 'Folk Rock', 1965, NULL, NULL, NULL),
(19, 'To Pimp a Butterfly', 'Kendrick Lamar', 'Hip Hop', 2015, NULL, NULL, NULL),
(20, 'Kid A', 'Radiohead', 'Alternative Rock', 2000, NULL, NULL, NULL),
(21, 'Born to Run', 'Bruce Springsteen', 'Rock', 1975, NULL, NULL, NULL),
(22, 'Ready to Die', 'The Notorious B.I.G.', 'Hip Hop', 1994, NULL, NULL, NULL),
(23, 'The Velvet Underground & Nico', 'The Velvet Underground & Nico', 'Art Rock', 1967, NULL, NULL, NULL),
(24, 'Sgt. Pepper\'s Lonely Hearts Club Band', 'The Beatles', 'Rock', 1967, NULL, NULL, NULL),
(25, 'Tapestry', 'Carole King', 'Pop', 1971, NULL, NULL, NULL),
(26, 'Horses', 'Patti Smith', 'Punk Rock', 1975, NULL, NULL, NULL),
(27, 'Enter the Wu-Tang (36 Chambers)', 'Wu-Tang Clan', 'Hip Hop', 1993, NULL, NULL, NULL),
(28, 'Voodoo', 'D\'Angelo', 'R&B', 2000, NULL, NULL, NULL),
(29, 'The Beatles (The White Album)', 'The Beatles', 'Rock', 1968, NULL, NULL, NULL),
(30, 'Are You Experienced', 'The Jimi Hendrix Experience', 'Psychedelic Rock', 1967, NULL, NULL, NULL),
(31, 'Kind of Blue', 'Miles Davis', 'Jazz', 1959, NULL, NULL, NULL),
(32, 'Lemonade', 'Beyoncé', 'R&B', 2016, NULL, NULL, NULL),
(33, 'Back to Black', 'Amy Winehouse', 'Soul', 2006, NULL, NULL, NULL),
(34, 'Innervisions', 'Stevie Wonder', 'Soul', 1973, NULL, NULL, NULL),
(35, 'Rubber Soul', 'The Beatles', 'Rock', 1965, NULL, NULL, NULL),
(36, 'Off the Wall', 'Michael Jackson', 'Pop', 1979, NULL, NULL, NULL),
(37, 'The Chronic', 'Dr. Dre', 'Hip Hop', 1992, NULL, NULL, NULL),
(38, 'Blonde on Blonde', 'Bob Dylan', 'Folk Rock', 1966, NULL, NULL, NULL),
(39, 'Remain in Light', 'Talking Heads', 'New Wave', 1980, NULL, NULL, NULL),
(40, 'The Rise and Fall of Ziggy Stardust and the Spiders from Mars', 'David Bowie', 'Glam Rock', 1972, NULL, NULL, NULL),
(41, 'Let It Bleed', 'The Rolling Stones', 'Rock', 1969, NULL, NULL, NULL),
(42, 'OK Computer', 'Radiohead', 'Alternative Rock', 1997, NULL, NULL, NULL),
(43, 'The Low End Theory', 'A Tribe Called Quest', 'Hip Hop', 1991, NULL, NULL, NULL),
(44, 'Illmatic', 'Nas', 'Hip Hop', 1994, NULL, NULL, NULL),
(45, 'Sign o\' the Times', 'Prince', 'Funk', 1987, NULL, NULL, NULL),
(46, 'Graceland', 'Paul Simon', 'Worldbeat', 1986, NULL, NULL, NULL),
(47, 'Ramones', 'Ramones', 'Punk Rock', 1976, NULL, NULL, NULL),
(48, 'Exodus', 'Bob Marley & the Wailers', 'Reggae', 1977, NULL, NULL, NULL),
(49, 'Aquemini', 'OutKast', 'Hip Hop', 1998, NULL, NULL, NULL),
(50, 'The Blueprint', 'Jay-Z', 'Hip Hop', 2001, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Struttura della tabella `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `name` varchar(100) DEFAULT NULL,
  `surname` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dump dei dati per la tabella `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `created_at`, `name`, `surname`) VALUES
(7, 'admin', 'admin@gmail.com', '$2y$10$wwjvJoEnsj7FabgvUusg/emKoif.q77k6QMfHbvrtGOFXWpjm9w8y', '2025-05-09 09:29:57', NULL, NULL);

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `music_albums`
--
ALTER TABLE `music_albums`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `music_albums`
--
ALTER TABLE `music_albums`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT per la tabella `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
