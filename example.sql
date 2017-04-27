-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Czas generowania: 27 Kwi 2017, 16:38
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
  (1, 'Harry Potter', 8),
  (2, 'Ogniem i mieczem', 9),
  (3, 'Spierdman', 8),
  (4, 'Batman', 8),
  (5, 'Kurs Symfony', 48),
  (7, 'Kurs PHP', 10),
  (8, 'Wydanie 5', 45),
  (9, 'Wydanie 4', 45),
  (10, 'Wydanie 6', 46),
  (11, 'Wydanie 7', 46),
  (12, 'Wydanie ogólne', 43),
  (13, 'Samsung', 17),
  (14, 'Toshiba', 17),
  (15, 'Philips', 18),
  (16, 'Samsung 4k promocja', 12),
  (17, 'Najnowsze frameworki', 5),
  (18, 'Kurs Excela', 49),
  (19, 'Kurs Worda', 49),
  (20, 'Programowanie w C++', 48);

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
  (1, 'Książki', 0, 1),
  (2, 'RTV/AGD', 0, 2),
  (3, 'Spożywka', 0, 3),
  (4, 'Ubrania', 0, 4),
  (5, 'Z roku 2017', 1, 1),
  (6, 'Z roku 2016', 1, 2),
  (7, 'Czasopisma', 6, 1),
  (8, 'Nowoczesne', 5, 1),
  (9, 'Historyczne', 5, 2),
  (10, 'Informatyczne', 5, 3),
  (11, 'Naukowe', 5, 4),
  (12, 'Telewizory', 2, 1),
  (13, 'Lodówki', 2, 2),
  (14, 'Pralki', 2, 3),
  (15, 'Komputery', 2, 4),
  (16, 'Telefony', 2, 5),
  (17, 'Duże', 12, 1),
  (18, 'Małe', 12, 2),
  (19, 'Standardowe', 13, 1),
  (20, 'Przemysłowe', 13, 2),
  (21, 'Stacjonarne', 15, 1),
  (22, 'Podzespoły', 21, 1),
  (23, 'Laptopy', 15, 2),
  (24, 'Monitory', 15, 3),
  (25, 'Napoje', 3, 1),
  (26, 'Ciastka', 3, 2),
  (27, 'Na wagę', 26, 1),
  (28, 'Na sztuki', 26, 2),
  (29, 'Wiśniowe', 27, 1),
  (30, 'Orzechowe', 27, 2),
  (31, 'Duże opakowania', 28, 1),
  (32, 'Małe opakowania', 28, 2),
  (33, 'Spodnie', 4, 1),
  (34, 'Bluzy', 4, 2),
  (35, 'Podkoszulki', 4, 3),
  (36, 'Bielizna', 4, 4),
  (37, 'Sportowe', 33, 1),
  (38, 'Jeans', 33, 2),
  (39, 'Levis', 38, 1),
  (40, 'Lee', 38, 2),
  (41, 'Nike', 37, 1),
  (42, 'Addidas', 37, 2),
  (43, 'Twój czas', 7, 1),
  (44, 'Mój czas', 7, 2),
  (45, 'Maj', 43, 1),
  (46, 'Czerwiec', 44, 1),
  (48, 'Dla zaawansowanych', 10, 1),
  (49, 'Podstawowe', 10, 2);

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
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;
--
-- AUTO_INCREMENT dla tabeli `tree`
--
ALTER TABLE `tree`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
