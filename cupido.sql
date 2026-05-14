-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Creato il: Mag 14, 2026 alle 12:36
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
-- Struttura della tabella `datiregistrazione`
--

CREATE TABLE `datiregistrazione` (
  `id_utente` varchar(30) PRIMARY KEY NOT NULL,
  `email` varchar(30) UNIQUE NOT NULL,
  `password` varchar(30) NOT NULL,
  `nome` varchar(30) NOT NULL,
  `cognome` varchar(30) NOT NULL,
  `sesso` varchar(2) NOT NULL,
  `eta` int(2) NOT NULL,
  `citta` varchar(30) NOT NULL,
  `maxEta` int(2) NOT NULL,
  `distanza` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `interessi`
--

CREATE TABLE `interessi` (
  `id_utente` varchar(30) PRIMARY KEY,
  `sport` tinyint(1) NOT NULL,
  `cucinare` tinyint(1) NOT NULL,
  `viaggiare` tinyint(1) NOT NULL,
  `leggere` tinyint(1) NOT NULL,
  `film` tinyint(1) NOT NULL,
  `suonare` tinyint(1) NOT NULL,
  `camping` tinyint(1) NOT NULL,
  `casa` tinyint(1) NOT NULL,
  `meditazione` tinyint(1) NOT NULL,
  `cena` tinyint(1) NOT NULL,
  FOREIGN KEY (id_utente) REFERENCES datiregistrazione(id_utente)
  ON DELETE CASCADE
  ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


--
-- Struttura della tabella `aggettivi`
--

CREATE TABLE `aggettivi` (
  `id_utente` varchar(30) PRIMARY KEY NOT NULL,
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
  `avventuroso` tinyint(1) NOT NULL,
  FOREIGN KEY (id_utente) REFERENCES datiregistrazione(id_utente)
  ON DELETE CASCADE
  ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
--tabella per le foto da caricare--

CREATE TABLE foto_utenti (
    id_foto INT AUTO_INCREMENT PRIMARY KEY,
    id_utente VARCHAR(30) NOT NULL,
    percorso VARCHAR(255) NOT NULL,
    tipo ENUM('profilo', 'galleria') NOT NULL,
    caricata_il TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_utente) REFERENCES datiregistrazione(id_utente)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

-- Indici per le tabelle scaricate
--
-- Limiti per le tabelle scaricate
--

--
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
