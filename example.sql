-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Czas generowania: 26 Kwi 2017, 12:38
-- Wersja serwera: 10.1.13-MariaDB
-- Wersja PHP: 7.0.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Baza danych: `treenavigation`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `files`
--
DROP TABLE IF EXISTS `files`;
CREATE TABLE `files` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_polish_ci NOT NULL,
  `folder` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `files`
--

INSERT INTO `files` (`id`, `name`, `folder`) VALUES
  (1, 'Kartka', 11),
  (2, 'Królik', 11),
  (3, 'Telewizor', 5),
  (4, 'Toster', 6),
  (7, 'Testowa rzecz', 12),
  (8, 'Rzecz', 2),
  (9, 'duzo', 23),
  (10, 'roznych', 23),
  (12, 'roznychaass', 2),
  (13, 'f', 3),
  (14, 'fss', 3),
  (15, 'easa', 23),
  (16, 'test', 23),
  (17, 'x5zg', 23),
  (18, 'x5zg', 12),
  (19, 'x5zg', 12),
  (20, 'x5zg', 17),
  (21, 'x5zg', 17),
  (22, 'x5zg', 17),
  (23, 'x5zg', 17),
  (24, 'x5zg', 17),
  (25, 'x5zg', 17),
  (29, 'x5zg', 17);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `tree`
--
DROP TABLE IF EXISTS `tree`;
CREATE TABLE `tree` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_polish_ci NOT NULL,
  `parent` int(11) DEFAULT NULL,
  `display_order` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `tree`
--

INSERT INTO `tree` (`id`, `name`, `parent`, `display_order`) VALUES
  (1, 'a', 0, 1),
  (2, 'b', 0, 2),
  (3, 'c', 0, 3),
  (4, 'ba', 2, 1),
  (5, 'bb', 2, 2),
  (6, 'bc', 2, 3),
  (7, 'd', 0, 4),
  (8, 'e', 0, 5),
  (9, 'f', 0, 6),
  (10, 'bba', 5, 1),
  (11, 'bbb', 5, 2),
  (12, 'bbc', 11, 1),
  (13, 'bbd', 5, 3),
  (14, 'bbca', 12, 1),
  (15, 'bbcb', 12, 2),
  (16, 'bbcc', 12, 3),
  (17, 'bbcd (sort: display_order - test)', 12, 5),
  (18, 'bbce (sort: display_order - test)', 12, 4),
  (19, 'da', 7, 1),
  (20, 'db', 7, 2),
  (21, 'dc', 7, 3),
  (22, 'dba', 20, 1),
  (23, 'dbb', 20, 2),
  (24, 'dbc', 20, 3);

--
-- Indeksy dla zrzutów tabel
--

--
-- Indexes for table `files`
--
ALTER TABLE `files`
ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tree`
--
ALTER TABLE `tree`
ADD PRIMARY KEY (`id`),
ADD UNIQUE KEY `name` (`name`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT dla tabeli `files`
--
ALTER TABLE `files`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;
--
-- AUTO_INCREMENT dla tabeli `tree`
--
ALTER TABLE `tree`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
