-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Creato il: Mag 14, 2026 alle 17:26
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
-- Database: `cupido`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `aggettivi`
--

CREATE TABLE `aggettivi` (
  `id_utente` varchar(30) NOT NULL,
  `solare` tinyint(1) NOT NULL,
  `intraprendente` tinyint(1) NOT NULL,
  `riflessivo` tinyint(1) NOT NULL,
  `spontaneo` tinyint(1) NOT NULL,
  `determinato` tinyint(1) NOT NULL,
  `curioso` tinyint(1) NOT NULL,
  `sognatore` tinyint(1) NOT NULL,
  `empatico` tinyint(1) NOT NULL,
  `ironico` tinyint(1) NOT NULL,
  `colto` tinyint(1) NOT NULL,
  `leale` tinyint(1) NOT NULL,
  `tranquillo` tinyint(1) NOT NULL,
  `socievole` tinyint(1) NOT NULL,
  `premuroso` tinyint(1) NOT NULL,
  `timido` tinyint(1) NOT NULL,
  `avventuroso` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `aggettivi`
--

INSERT INTO `aggettivi` (`id_utente`, `solare`, `intraprendente`, `riflessivo`, `spontaneo`, `determinato`, `curioso`, `sognatore`, `empatico`, `ironico`, `colto`, `leale`, `tranquillo`, `socievole`, `premuroso`, `timido`, `avventuroso`) VALUES
('alisav', 0, 1, 1, 0, 0, 0, 1, 0, 0, 0, 0, 0, 1, 0, 0, 1);

-- --------------------------------------------------------

--
-- Struttura della tabella `datiregistrazione`
--

CREATE TABLE `datiregistrazione` (
  `id_utente` varchar(30) NOT NULL,
  `email` varchar(30) NOT NULL,
  `password` varchar(30) NOT NULL,
  `nome` varchar(30) NOT NULL,
  `cognome` varchar(30) NOT NULL,
  `sesso` varchar(2) NOT NULL,
  `eta` int(2) NOT NULL,
  `citta` varchar(30) NOT NULL,
  `maxEta` int(2) NOT NULL,
  `distanza` tinyint(1) NOT NULL,
  `sessoP` varchar(30) NOT NULL,
  `relazione` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `datiregistrazione`
--

INSERT INTO `datiregistrazione` (`id_utente`, `email`, `password`, `nome`, `cognome`, `sesso`, `eta`, `citta`, `maxEta`, `distanza`, `sessoP`, `relazione`) VALUES
('alisav', 'alicesavoia2@gmail.com', '12345678', 'Alice', 'Savoia', 'Do', 22, 'Viterbo', 5, 0, '', ''),
('geogeo', 'georgiana@gmail.com', '12345678', 'Geo', 'Rotaru', '', 22, 'Roma', 0, 0, '', '');

-- --------------------------------------------------------

--
-- Struttura della tabella `foto_utenti`
--

CREATE TABLE `foto_utenti` (
  `id_foto` int(11) NOT NULL,
  `id_utente` varchar(30) NOT NULL,
  `percorso` varchar(255) NOT NULL,
  `tipo` enum('profilo','galleria') NOT NULL,
  `caricata_il` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `interessi`
--

CREATE TABLE `interessi` (
  `id_utente` varchar(30) NOT NULL,
  `sport` tinyint(1) NOT NULL,
  `cucinare` tinyint(1) NOT NULL,
  `viaggiare` tinyint(1) NOT NULL,
  `leggere` tinyint(1) NOT NULL,
  `film` tinyint(1) NOT NULL,
  `suonare` tinyint(1) NOT NULL,
  `camping` tinyint(1) NOT NULL,
  `casa` tinyint(1) NOT NULL,
  `meditazione` tinyint(1) NOT NULL,
  `cena` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `interessi`
--

INSERT INTO `interessi` (`id_utente`, `sport`, `cucinare`, `viaggiare`, `leggere`, `film`, `suonare`, `camping`, `casa`, `meditazione`, `cena`) VALUES
('alisav', 0, 0, 1, 1, 1, 0, 1, 1, 0, 1);

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `aggettivi`
--
ALTER TABLE `aggettivi`
  ADD PRIMARY KEY (`id_utente`);

--
-- Indici per le tabelle `datiregistrazione`
--
ALTER TABLE `datiregistrazione`
  ADD PRIMARY KEY (`id_utente`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indici per le tabelle `foto_utenti`
--
ALTER TABLE `foto_utenti`
  ADD PRIMARY KEY (`id_foto`),
  ADD KEY `id_utente` (`id_utente`);

--
-- Indici per le tabelle `interessi`
--
ALTER TABLE `interessi`
  ADD PRIMARY KEY (`id_utente`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `foto_utenti`
--
ALTER TABLE `foto_utenti`
  MODIFY `id_foto` int(11) NOT NULL AUTO_INCREMENT;

--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `aggettivi`
--
ALTER TABLE `aggettivi`
  ADD CONSTRAINT `aggettivi_ibfk_1` FOREIGN KEY (`id_utente`) REFERENCES `datiregistrazione` (`id_utente`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `foto_utenti`
--
ALTER TABLE `foto_utenti`
  ADD CONSTRAINT `foto_utenti_ibfk_1` FOREIGN KEY (`id_utente`) REFERENCES `datiregistrazione` (`id_utente`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `interessi`
--
ALTER TABLE `interessi`
  ADD CONSTRAINT `interessi_ibfk_1` FOREIGN KEY (`id_utente`) REFERENCES `datiregistrazione` (`id_utente`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
