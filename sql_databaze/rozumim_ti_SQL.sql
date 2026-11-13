-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Počítač: localhost:8889
-- Vytvořeno: Pát 13. lis 2026, 20:03
-- Verze serveru: 8.0.40
-- Verze PHP: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Databáze: `rozumim_ti_SQL`
--

-- --------------------------------------------------------

--
-- Struktura tabulky `cviceni`
--

CREATE TABLE `cviceni` (
  `id_excercise` int NOT NULL,
  `name_excercise` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name_model` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `descr` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `gestures` text COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Vypisuji data pro tabulku `cviceni`
--

INSERT INTO `cviceni` (`id_excercise`, `name_excercise`, `name_model`, `descr`, `gestures`) VALUES
(5, 'Abeceda', 'model_alphabet.keras', 'Celá obouruční abeceda českého znakového jazyka.', '[\"A\",\"B\",\"C\",\"D\",\"E\",\"F\",\"G\",\"H\",\"CH\",\"I\",\"J\",\"K\",\"L\",\"M\",\"N\",\"O\",\"P\",\"Q\",\"R\",\"S\",\"T\",\"U\",\"V\",\"W\",\"X\",\"Y\",\"Z\"]');

-- --------------------------------------------------------

--
-- Struktura tabulky `kontakt`
--

CREATE TABLE `kontakt` (
  `id_zprava` int NOT NULL,
  `jmeno` varchar(100) COLLATE utf8mb4_czech_ci NOT NULL,
  `prijmeni` varchar(100) COLLATE utf8mb4_czech_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_czech_ci NOT NULL,
  `typ_zpravy` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_czech_ci NOT NULL,
  `text_zpravy` text CHARACTER SET utf8mb4 COLLATE utf8mb4_czech_ci NOT NULL,
  `hotovo` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_czech_ci;

--
-- Vypisuji data pro tabulku `kontakt`
--

INSERT INTO `kontakt` (`id_zprava`, `jmeno`, `prijmeni`, `email`, `typ_zpravy`, `text_zpravy`, `hotovo`) VALUES
(1, 'name', 'name', 'mail@mail.mail', 'Zpráva/dotaz', 'text zpravy', 0);

--
-- Indexy pro exportované tabulky
--

--
-- Indexy pro tabulku `cviceni`
--
ALTER TABLE `cviceni`
  ADD PRIMARY KEY (`id_excercise`);

--
-- Indexy pro tabulku `kontakt`
--
ALTER TABLE `kontakt`
  ADD PRIMARY KEY (`id_zprava`);

--
-- AUTO_INCREMENT pro tabulky
--

--
-- AUTO_INCREMENT pro tabulku `cviceni`
--
ALTER TABLE `cviceni`
  MODIFY `id_excercise` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pro tabulku `kontakt`
--
ALTER TABLE `kontakt`
  MODIFY `id_zprava` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
