-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Creato il: Apr 24, 2025 alle 15:38
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
-- Database: `foodwise`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `ingrediente`
--

CREATE TABLE `ingrediente` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `immagine` varchar(512) NOT NULL,
  `calorie` float NOT NULL,
  `proteine` float NOT NULL,
  `carboidrati` float NOT NULL,
  `grassi` float NOT NULL,
  `zucchero` float NOT NULL,
  `sodio` float NOT NULL,
  `categoria` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `ingredienteinlista`
--

CREATE TABLE `ingredienteinlista` (
  `id_lista` int(11) NOT NULL,
  `id_ingrediente` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `listaspesa`
--

CREATE TABLE `listaspesa` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `numeroElementi` int(11) NOT NULL,
  `id_utente` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `ricetta`
--

CREATE TABLE `ricetta` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `immagine` varchar(512) DEFAULT NULL,
  `porzioni` int(11) DEFAULT NULL,
  `tempoPreparazione` int(11) DEFAULT NULL,
  `healthScore` float NOT NULL,
  `veryHealthy` tinyint(1) NOT NULL,
  `calorie` float NOT NULL,
  `proteine` float NOT NULL,
  `carboidrati` float NOT NULL,
  `grassi` float NOT NULL,
  `zuccheri` float NOT NULL,
  `sodio` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `ricettepreferite`
--

CREATE TABLE `ricettepreferite` (
  `idUtente` int(11) NOT NULL,
  `idRicetta` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `utente`
--

CREATE TABLE `utente` (
  `id` int(10) NOT NULL,
  `username` varchar(50) NOT NULL,
  `passmd5` char(32) NOT NULL,
  `email` int(11) NOT NULL,
  `data_nascita` date NOT NULL,
  `peso` decimal(5,2) NOT NULL,
  `altezza` int(11) NOT NULL,
  `peso_goal` decimal(5,2) NOT NULL,
  `sesso` enum('M','F') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `ingrediente`
--
ALTER TABLE `ingrediente`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `ingredienteinlista`
--
ALTER TABLE `ingredienteinlista`
  ADD PRIMARY KEY (`id_lista`,`id_ingrediente`),
  ADD KEY `id_ingrediente` (`id_ingrediente`);

--
-- Indici per le tabelle `listaspesa`
--
ALTER TABLE `listaspesa`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_utente` (`id_utente`);

--
-- Indici per le tabelle `ricetta`
--
ALTER TABLE `ricetta`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `ricettepreferite`
--
ALTER TABLE `ricettepreferite`
  ADD PRIMARY KEY (`idUtente`,`idRicetta`),
  ADD KEY `idRicetta` (`idRicetta`);

--
-- Indici per le tabelle `utente`
--
ALTER TABLE `utente`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `ingrediente`
--
ALTER TABLE `ingrediente`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `listaspesa`
--
ALTER TABLE `listaspesa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `ricetta`
--
ALTER TABLE `ricetta`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `utente`
--
ALTER TABLE `utente`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `ingredienteinlista`
--
ALTER TABLE `ingredienteinlista`
  ADD CONSTRAINT `ingredienteinlista_ibfk_1` FOREIGN KEY (`id_ingrediente`) REFERENCES `ingrediente` (`id`),
  ADD CONSTRAINT `ingredienteinlista_ibfk_2` FOREIGN KEY (`id_lista`) REFERENCES `listaspesa` (`id`);

--
-- Limiti per la tabella `listaspesa`
--
ALTER TABLE `listaspesa`
  ADD CONSTRAINT `listaspesa_ibfk_1` FOREIGN KEY (`id_utente`) REFERENCES `utente` (`id`);

--
-- Limiti per la tabella `ricettepreferite`
--
ALTER TABLE `ricettepreferite`
  ADD CONSTRAINT `ricettepreferite_ibfk_1` FOREIGN KEY (`idRicetta`) REFERENCES `ricetta` (`id`),
  ADD CONSTRAINT `ricettepreferite_ibfk_2` FOREIGN KEY (`idUtente`) REFERENCES `utente` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
