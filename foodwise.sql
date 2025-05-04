-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Creato il: Mag 04, 2025 alle 21:01
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
  `categoria` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `ingrediente`
--

INSERT INTO `ingrediente` (`id`, `nome`, `immagine`, `categoria`) VALUES
(1001, 'butter', 'https://spoonacular.com/cdn/ingredients_100x100/butter-sliced.jpg', ''),
(1057, 'eggnog', 'https://spoonacular.com/cdn/ingredients_100x100/eggnog.png', ''),
(1123, 'egg', 'https://spoonacular.com/cdn/ingredients_100x100/egg.png', ''),
(1124, 'egg whites', 'https://spoonacular.com/cdn/ingredients_100x100/egg-white.jpg', ''),
(1125, 'egg yolk', 'https://spoonacular.com/cdn/ingredients_100x100/egg-yolk.jpg', ''),
(1230, 'buttermilk', 'https://spoonacular.com/cdn/ingredients_100x100/buttermilk.jpg', ''),
(11011, 'asparagus', 'https://spoonacular.com/cdn/ingredients_100x100/asparagus.png', ''),
(11209, 'eggplant', 'https://spoonacular.com/cdn/ingredients_100x100/eggplant.png', ''),
(11282, 'onion', 'https://spoonacular.com/cdn/ingredients_100x100/brown-onion.png', ''),
(15008, 'carp', 'https://spoonacular.com/cdn/ingredients_100x100/fish-fillet.jpg', ''),
(19074, 'caramel', 'https://spoonacular.com/cdn/ingredients_100x100/soft-caramels.jpg', ''),
(23572, 'beef', 'https://spoonacular.com/cdn/ingredients_100x100/beef-cubes-raw.png', ''),
(99097, 'bael fruit', 'https://spoonacular.com/cdn/ingredients_100x100/bael-fruit.jpg', ''),
(1001033, 'asiago cheese', 'https://spoonacular.com/cdn/ingredients_100x100/parmesan.jpg', ''),
(1022068, 'red wine vinegar', 'https://spoonacular.com/cdn/ingredients_100x100/red-wine-vinegar.jpg', ''),
(1029159, 'lime wedge', 'https://spoonacular.com/cdn/ingredients_100x100/lime-wedge.jpg', ''),
(1102047, 'salt and pepper', 'https://spoonacular.com/cdn/ingredients_100x100/salt-and-pepper.jpg', '');

-- --------------------------------------------------------

--
-- Struttura della tabella `ingredienteinlista`
--

CREATE TABLE `ingredienteinlista` (
  `idLista` int(11) NOT NULL,
  `idIngrediente` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `listaspesa`
--

CREATE TABLE `listaspesa` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `idUtente` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `pianocalorico`
--

CREATE TABLE `pianocalorico` (
  `id` int(11) NOT NULL,
  `data` date NOT NULL,
  `calorie` decimal(10,2) NOT NULL,
  `proteine` decimal(10,2) NOT NULL,
  `carboidrati` decimal(10,2) NOT NULL,
  `grassi` decimal(10,2) NOT NULL,
  `zuccheri` decimal(10,2) NOT NULL,
  `sodio` decimal(10,2) NOT NULL,
  `idUtente` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `pianocalorico`
--

INSERT INTO `pianocalorico` (`id`, `data`, `calorie`, `proteine`, `carboidrati`, `grassi`, `zuccheri`, `sodio`, `idUtente`) VALUES
(3, '2025-05-04', 496.67, 5.94, 59.00, 13.29, 39.33, 0.71, 6),
(4, '2025-05-04', 453.00, 35.96, 22.46, 24.05, 5.22, 1.34, 7);

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
  `calorie` decimal(10,2) NOT NULL,
  `proteine` decimal(10,2) NOT NULL,
  `carboidrati` decimal(10,2) NOT NULL,
  `grassi` decimal(10,2) NOT NULL,
  `zuccheri` decimal(10,2) NOT NULL,
  `sodio` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `ricetta`
--

INSERT INTO `ricetta` (`id`, `nome`, `immagine`, `porzioni`, `tempoPreparazione`, `calorie`, `proteine`, `carboidrati`, `grassi`, `zuccheri`, `sodio`) VALUES
(1, 'Plum Cake', 'https://img.spoonacular.com/recipes/656444-556x370.jpg', 6, 45, 462.00, 9.00, 50.00, 26.00, 19.00, 156.00),
(2, 'Plantain Pizza', 'https://img.spoonacular.com/recipes/716300-556x370.jpg', 3, 45, 642.00, 25.00, 84.00, 22.00, 14.00, 839.00),
(3, 'Amazing Chicken Burgers', 'https://img.spoonacular.com/recipes/632300-556x370.jpg', 3, 45, 416.00, 36.00, 17.00, 22.00, 1.00, 1288.00),
(4, 'Amazing Chicken Pot Pie', 'https://img.spoonacular.com/recipes/715571-556x370.jpg', 6, 50, 533.00, 42.00, 37.00, 24.00, 11.00, 575.00),
(5, 'Simply Amazing Cinnamon Swirl Wheat Bread', 'https://img.spoonacular.com/recipes/660157-556x370.jpg', 8, 45, 329.00, 6.00, 49.00, 13.00, 17.00, 179.00),
(6, 'Kung Po Chicken', 'https://img.spoonacular.com/recipes/649131-556x370.jpg', 4, 45, 478.00, 32.00, 12.00, 34.00, 5.00, 940.00),
(7, 'Kolaches', 'https://img.spoonacular.com/recipes/649012-556x370.jpg', 48, 45, 112.00, 1.00, 12.00, 6.00, 6.00, 131.00);

-- --------------------------------------------------------

--
-- Struttura della tabella `ricettepreferite`
--

CREATE TABLE `ricettepreferite` (
  `idUtente` int(11) NOT NULL,
  `idRicetta` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `ricettepreferite`
--

INSERT INTO `ricettepreferite` (`idUtente`, `idRicetta`) VALUES
(6, 1),
(6, 2),
(7, 3),
(7, 4),
(7, 5),
(7, 6),
(7, 7);

-- --------------------------------------------------------

--
-- Struttura della tabella `utente`
--

CREATE TABLE `utente` (
  `id` int(10) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(255) NOT NULL,
  `passmd5` char(32) NOT NULL,
  `dataNascita` date NOT NULL,
  `peso` decimal(5,2) NOT NULL,
  `altezza` int(11) NOT NULL,
  `sesso` enum('M','F') NOT NULL,
  `pesoGoal` decimal(5,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `utente`
--

INSERT INTO `utente` (`id`, `username`, `email`, `passmd5`, `dataNascita`, `peso`, `altezza`, `sesso`, `pesoGoal`) VALUES
(1, 'aaa', 'aaa@mail.com', '0788e3742a0343da66d1a33dc01a7532', '2000-10-10', 80.00, 190, 'M', 80.00),
(2, 'annalisa', 'pippo@gmail.com', 'f98ed07a4d5f50f7de1410d905f1477f', '2000-10-15', 20.00, 120, 'F', 32.00),
(3, 'paolo', 'paolo@mail.com', '6e6bc4e49dd477ebc98ef4046c067b5f', '2000-10-10', 80.00, 180, 'M', 72.50),
(4, 'umn', 'caiao@gmail.com', '6e6bc4e49dd477ebc98ef4046c067b5f', '0002-10-10', 80.00, 180, 'M', 72.50),
(6, 'lisa', 'lisa@mail.com', 'f98ed07a4d5f50f7de1410d905f1477f', '2000-02-10', 30.00, 100, 'F', 20.00),
(7, 'mamma', 'pizza@gcom.it', 'ed14f4a4d7ecddb6dae8e54900300b1e', '2010-02-10', 10.00, 100, 'F', 20.00),
(8, 'WolverineGod69', 'wolverine@mail.com', '81dc9bdb52d04dc20036dbd8313ed055', '2000-10-10', 100.00, 100, 'M', 12.50),
(10, 'we', 'ciao@mail.com', '202cb962ac59075b964b07152d234b70', '1960-10-10', 80.00, 80, 'F', 8.00);

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
  ADD PRIMARY KEY (`idLista`,`idIngrediente`),
  ADD KEY `id_ingrediente` (`idIngrediente`);

--
-- Indici per le tabelle `listaspesa`
--
ALTER TABLE `listaspesa`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_utente` (`idUtente`);

--
-- Indici per le tabelle `pianocalorico`
--
ALTER TABLE `pianocalorico`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idUtente` (`idUtente`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1102048;

--
-- AUTO_INCREMENT per la tabella `listaspesa`
--
ALTER TABLE `listaspesa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT per la tabella `pianocalorico`
--
ALTER TABLE `pianocalorico`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT per la tabella `ricetta`
--
ALTER TABLE `ricetta`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT per la tabella `utente`
--
ALTER TABLE `utente`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `ingredienteinlista`
--
ALTER TABLE `ingredienteinlista`
  ADD CONSTRAINT `ingredienteinlista_ibfk_1` FOREIGN KEY (`idIngrediente`) REFERENCES `ingrediente` (`id`),
  ADD CONSTRAINT `ingredienteinlista_ibfk_2` FOREIGN KEY (`idLista`) REFERENCES `listaspesa` (`id`);

--
-- Limiti per la tabella `listaspesa`
--
ALTER TABLE `listaspesa`
  ADD CONSTRAINT `listaspesa_ibfk_1` FOREIGN KEY (`idUtente`) REFERENCES `utente` (`id`);

--
-- Limiti per la tabella `pianocalorico`
--
ALTER TABLE `pianocalorico`
  ADD CONSTRAINT `pianocalorico_ibfk_1` FOREIGN KEY (`idUtente`) REFERENCES `utente` (`id`);

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
