-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Creato il: Apr 07, 2024 alle 21:05
-- Versione del server: 10.4.32-MariaDB
-- Versione PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tecnoteca`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `centri`
--

CREATE TABLE `centri` (
  `id_centro` int(11) NOT NULL,
  `nome` varchar(128) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `centri`
--

INSERT INTO `centri` (`id_centro`, `nome`) VALUES
(1, 'centro1'),
(2, 'centro2'),
(3, 'centro3'),
(4, 'centroA'),
(5, 'centroB'),
(6, 'centroC');

-- --------------------------------------------------------

--
-- Struttura della tabella `oggetti`
--

CREATE TABLE `oggetti` (
  `id_oggetto` int(11) NOT NULL,
  `fk_id_centro` int(11) NOT NULL,
  `fk_id_utente` int(11) DEFAULT NULL,
  `categoria` set('Computer','Tablet','eBook','Viodeogioco','Software') NOT NULL,
  `prenotabile` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `oggetti`
--

INSERT INTO `oggetti` (`id_oggetto`, `fk_id_centro`, `fk_id_utente`, `categoria`, `prenotabile`) VALUES
(10, 1, NULL, 'Computer', 0),
(14, 1, NULL, 'Computer', 0),
(24, 5, 1, 'Viodeogioco', 0),
(26, 4, 2, 'Software', 0),
(27, 5, NULL, 'Tablet', 1),
(29, 3, NULL, 'eBook', 1),
(30, 4, 1, 'eBook', 0),
(31, 5, 2, 'Viodeogioco', 0),
(32, 3, 1, 'eBook', 0),
(33, 2, NULL, 'Software', 1);

-- --------------------------------------------------------

--
-- Struttura della tabella `utenti`
--

CREATE TABLE `utenti` (
  `id_utente` int(11) NOT NULL,
  `username` varchar(64) NOT NULL,
  `password` varchar(32) NOT NULL,
  `admin` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `utenti`
--

INSERT INTO `utenti` (`id_utente`, `username`, `password`, `admin`) VALUES
(1, 'Kevin', '2e05f63b3f51219d901e9e27da0ddce9', 1),
(2, 'Luca', '19ebcb138359290cd5224ea15f9acb56', 0);

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `centri`
--
ALTER TABLE `centri`
  ADD PRIMARY KEY (`id_centro`);

--
-- Indici per le tabelle `oggetti`
--
ALTER TABLE `oggetti`
  ADD PRIMARY KEY (`id_oggetto`);

--
-- Indici per le tabelle `utenti`
--
ALTER TABLE `utenti`
  ADD PRIMARY KEY (`id_utente`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `centri`
--
ALTER TABLE `centri`
  MODIFY `id_centro` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT per la tabella `oggetti`
--
ALTER TABLE `oggetti`
  MODIFY `id_oggetto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT per la tabella `utenti`
--
ALTER TABLE `utenti`
  MODIFY `id_utente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
