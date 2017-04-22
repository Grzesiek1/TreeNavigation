-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Czas generowania: 22 Kwi 2017, 16:38
-- Wersja serwera: 10.1.13-MariaDB
-- Wersja PHP: 7.0.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Baza danych: `treesnavigation`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `trees`
--

CREATE TABLE `trees` (
  `id` int(11) NOT NULL,
  `name` text COLLATE utf8_polish_ci NOT NULL,
  `parent` int(11) DEFAULT NULL,
  `display_order` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `trees`
--

INSERT INTO `trees` (`id`, `name`, `parent`, `display_order`) VALUES
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
(12, 'bbc', 5, 3),
(13, 'bbd', 5, 4),
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
-- Indexes for table `trees`
--
ALTER TABLE `trees`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT dla tabeli `trees`
--
ALTER TABLE `trees`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
