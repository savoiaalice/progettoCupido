-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Creato il: Mag 21, 2026 alle 17:48
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
('adri33', 1, 0, 0, 1, 0, 1, 1, 1, 0, 1, 1, 1, 0, 1, 0, 0),
('aleburo', 0, 0, 1, 0, 1, 1, 0, 1, 1, 0, 1, 1, 0, 1, 1, 0),
('angelina', 1, 0, 1, 1, 1, 1, 0, 1, 1, 0, 1, 0, 1, 0, 0, 1),
('brandoo', 1, 0, 0, 1, 1, 0, 1, 0, 1, 0, 1, 0, 0, 1, 0, 1),
('cate001', 1, 0, 1, 1, 1, 0, 0, 0, 1, 0, 1, 0, 1, 1, 0, 0),
('giadapiery', 1, 0, 0, 0, 1, 0, 0, 0, 1, 0, 0, 1, 0, 1, 0, 0),
('giorgiaaa', 0, 0, 1, 0, 0, 1, 0, 1, 1, 0, 0, 1, 1, 0, 0, 0),
('giovanny', 1, 0, 0, 0, 0, 1, 0, 0, 1, 0, 0, 0, 1, 1, 0, 0),
('giovi', 0, 0, 0, 1, 0, 0, 0, 0, 1, 0, 0, 1, 1, 0, 0, 0),
('giulyy', 1, 0, 1, 1, 1, 1, 0, 1, 0, 0, 0, 0, 1, 0, 0, 0),
('jenny29', 1, 0, 0, 1, 0, 0, 1, 1, 1, 0, 0, 0, 1, 0, 0, 0),
('leoleo', 0, 0, 0, 0, 0, 1, 0, 0, 1, 1, 0, 0, 1, 1, 0, 0),
('marcobelli', 0, 0, 0, 1, 1, 0, 0, 1, 0, 0, 0, 0, 1, 0, 0, 0),
('mattiabianchi', 1, 0, 0, 1, 0, 1, 0, 0, 1, 1, 1, 0, 1, 0, 0, 1),
('paolino', 1, 0, 1, 0, 0, 0, 0, 0, 1, 0, 1, 1, 1, 0, 0, 0),
('simo04', 1, 0, 1, 1, 1, 0, 0, 0, 1, 1, 1, 1, 1, 1, 0, 0),
('sofy06', 1, 0, 0, 1, 0, 1, 0, 0, 1, 0, 0, 1, 1, 1, 0, 0),
('tina', 1, 0, 0, 1, 1, 0, 0, 0, 1, 0, 0, 0, 1, 0, 0, 0),
('tommy98', 1, 0, 0, 1, 1, 1, 0, 0, 0, 0, 1, 0, 1, 0, 0, 1),
('tommy99', 1, 0, 1, 0, 1, 1, 0, 0, 0, 0, 1, 0, 1, 1, 0, 1);

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
  `sesso` varchar(10) DEFAULT NULL,
  `eta` int(2) NOT NULL,
  `citta` varchar(30) NOT NULL,
  `maxEta` int(2) DEFAULT NULL,
  `distanza` tinyint(1) DEFAULT NULL,
  `sessoP` varchar(30) DEFAULT NULL,
  `relazione` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `datiregistrazione`
--

INSERT INTO `datiregistrazione` (`id_utente`, `email`, `password`, `nome`, `cognome`, `sesso`, `eta`, `citta`, `maxEta`, `distanza`, `sessoP`, `relazione`) VALUES
('adri33', 'adriana@gmail.com', 'fhjkoiuygjkijuh', 'Adriana', 'Lima', 'donna', 18, 'Bari', 10, 0, 'uomo', 'seria'),
('aleburo', 'alessandra@gmail.com', 'dfghjklkjhghbnj', 'Alessandra', 'Bulocchi', 'donna', 24, 'Parma', 10, 0, 'uomo', 'seria'),
('angelina', 'angela@gmail.com', 'ewrtyuikjhg', 'Angela', 'Giuliani', 'donna', 19, 'Torino', 10, 0, 'uomo', 'seria'),
('brandoo', 'brando@gmail.com', 'rdftgyhjokpl', 'Brando', 'Pitti', 'uomo', 20, 'Roma', 7, 0, NULL, 'seria'),
('cate001', 'caterina@gmail.com', 'cvbjklokiuhgvhbj', 'Caterina', 'Coni', 'donna', 23, 'Pisa', 7, 0, 'uomo', 'seria'),
('giadapiery', 'giada@gmail.com', 'cvbjkl', 'Giada', 'Pierini', 'donna', 31, 'Firenze', 10, 0, 'uomo', 'aperta'),
('giorgiaaa', 'giorgia@gmail.com', 'vbnjklkjhnm', 'Giorgia', 'Fantasia', 'donna', 39, 'Ravenna', 18, NULL, 'uomo', 'seria'),
('giovanny', 'giovannigentile@gmail.com', 'dtfyuijokpl', 'Giovanni', 'Gentile', 'uomo', 22, 'Milano', 6, 0, 'donna', 'seria'),
('giovi', 'giovanni@gmail.com', 'xcvbnm,', 'Giovanni', 'Zecca', 'uomo', 53, 'Roma', 20, 0, 'donna', 'seria'),
('giulyy', 'giulia@gmail.com', 'errdftgkjhgfds', 'Giulia', 'Rossi', 'donna', 23, 'Latina', 7, 0, 'uomo', 'seria'),
('jenny29', 'jenny@gmail.com', 'fghjklò', 'Jennifer', 'Ariosto', 'donna', 22, 'Milano', 5, 0, 'uomo', 'seria'),
('leoleo', 'leonardo@gmail.com', 'rtfgioplè', 'Leonardo', 'Di Caprio', 'uomo', 19, 'Verona', 4, 0, 'donna', 'seria'),
('marcobelli', 'marco@gmail.com', 'asdfghjkl', 'Marco', 'Belli', 'uomo', 29, 'Roma', 10, 0, 'donna', 'seria'),
('mattiabianchi', 'mattia@gmail.com', 'cfvgbnjkoiuyg', 'Mattia', 'Bianchi', 'uomo', 23, 'Ancona', 7, 0, 'donna', 'seria'),
('paolino', 'paolo@gmail.com', 'cvbnm,.', 'Paolo', 'Bonolis', 'uomo', 57, 'Roma', 19, 0, 'donna', 'seria'),
('simo04', 'simone@gmail.com', 'dxfghjnklpoi', 'Simone', 'Iannone', 'uomo', 22, 'Roma', 4, 0, 'donna', 'seria'),
('sofy06', 'sofia@gmail.com', 'dftghjklò', 'Sofia', 'Vergara', 'donna', 18, 'Napoli', 6, 0, 'uomo', 'seria'),
('tina', 'tina@gmail.com', 'guioijmk', 'Tina', 'Cipollari', 'donna', 39, 'Viterbo', 10, NULL, 'uomo', 'aperta'),
('tommy98', 'tommasocrociera@gmail.com', 'dfghjiuytgbnk', 'Tommaso', 'Crociera', 'uomo', 25, 'Padova', 9, 0, 'donna', 'seria'),
('tommy99', 'tommaso@gmail.com', 'cghjkl', 'Tommaso', 'Rotella', 'uomo', 25, 'Venezia', 4, 0, 'donna', 'seria');

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

--
-- Dump dei dati per la tabella `foto_utenti`
--

INSERT INTO `foto_utenti` (`id_foto`, `id_utente`, `percorso`, `tipo`, `caricata_il`) VALUES
(6, 'marcobelli', 'foto/1779130845_marco.png', 'profilo', '2026-05-18 19:00:45'),
(7, 'marcobelli', 'foto/1779130906_th (1).webp', 'galleria', '2026-05-18 19:01:46'),
(8, 'marcobelli', 'foto/1779130921_th.webp', 'galleria', '2026-05-18 19:02:01'),
(9, 'marcobelli', 'foto/1779130949_pngtree-a-man-in-walk-cartoon-art-png-image_12479235.png', 'galleria', '2026-05-18 19:02:29'),
(10, 'marcobelli', 'foto/1779130977_pngtree-a-man-in-walk-cartoon-art-png-image_12479235.png', 'galleria', '2026-05-18 19:02:57'),
(11, 'giadapiery', 'foto/1779131334_ff263fa12f30f79627a29063aa81c383.jpg', 'profilo', '2026-05-18 19:08:54'),
(12, 'giadapiery', 'foto/1779131342_56411352828a19ae576a9e5e6aaf08f0.jpg', 'galleria', '2026-05-18 19:09:02'),
(13, 'giorgiaaa', 'foto/1779131435_90b272fcee2f00e99f7cae932937f4a9.jpg', 'profilo', '2026-05-18 19:10:35'),
(14, 'giorgiaaa', 'foto/1779131442_f14d167d7b0febd8948948ab1a760fda.jpg', 'galleria', '2026-05-18 19:10:42'),
(15, 'giovi', 'foto/1779131564_866c80366f2b9fa8fae2fb5fba8732a8.jpg', 'profilo', '2026-05-18 19:12:44'),
(16, 'paolino', 'foto/1779131709_91a8719a6b2b738eec2ebbd19dc22687.jpg', 'profilo', '2026-05-18 19:15:09'),
(17, 'paolino', 'foto/1779131736_df7a13d09055bfb3543adcfd1209eabd.jpg', 'galleria', '2026-05-18 19:15:36'),
(18, 'paolino', 'foto/1779131743_3c3391e59f181b1a9b41e5a12e029fce.jpg', 'galleria', '2026-05-18 19:15:43'),
(19, 'tommy99', 'foto/1779131900_306b0b1ec96937008e129798174d581f.jpg', 'profilo', '2026-05-18 19:18:20'),
(20, 'tommy99', 'foto/1779131907_584cfa99748be9bdd5c5f7ed786bcdf3.jpg', 'galleria', '2026-05-18 19:18:27'),
(21, 'tommy99', 'foto/1779131914_d27c400ea87aeeb0845208e98548bdec.jpg', 'galleria', '2026-05-18 19:18:34'),
(22, 'tina', 'foto/1779132065_6a25ec1d39b0582866f60a984c9f4d80.jpg', 'profilo', '2026-05-18 19:21:05'),
(23, 'jenny29', 'foto/1779132213_a25b54eaa7f85f0ced11d5f0aae45163.jpg', 'profilo', '2026-05-18 19:23:33'),
(24, 'jenny29', 'foto/1779132220_a25b54eaa7f85f0ced11d5f0aae45163.jpg', 'galleria', '2026-05-18 19:23:40'),
(25, 'jenny29', 'foto/1779132230_download.png', 'galleria', '2026-05-18 19:23:50'),
(26, 'jenny29', 'foto/1779132255_c4cabed8f1d3955f56721b0b97bcfcc8.jpg', 'galleria', '2026-05-18 19:24:15'),
(27, 'sofy06', 'foto/1779132401_feb7ba1ad7ab6904e67fd1250bf5fa8c.jpg', 'profilo', '2026-05-18 19:26:41'),
(28, 'sofy06', 'foto/1779132410_ac0220a0ef2c8d979010c61314e6f113.jpg', 'galleria', '2026-05-18 19:26:50'),
(29, 'sofy06', 'foto/1779132420_bf1231698663349de6004a981a8b2c90.jpg', 'galleria', '2026-05-18 19:27:00'),
(30, 'sofy06', 'foto/1779132427_8a3eac6d145b51c78434e67691ebce06.jpg', 'galleria', '2026-05-18 19:27:07'),
(31, 'sofy06', 'foto/1779132435_9df41bd97453ec8845caac1a1e480120.jpg', 'galleria', '2026-05-18 19:27:15'),
(32, 'leoleo', 'foto/1779132592_309a49f2e8e2f02a53dba1e174f90a9d.jpg', 'profilo', '2026-05-18 19:29:52'),
(33, 'leoleo', 'foto/1779132604_download (1).png', 'galleria', '2026-05-18 19:30:04'),
(34, 'leoleo', 'foto/1779132611_6a8fd64e46ef290f188f999f7fd74073.jpg', 'galleria', '2026-05-18 19:30:11'),
(35, 'leoleo', 'foto/1779132617_c64fe429d499d164bd6d4cc058b6ff63.jpg', 'galleria', '2026-05-18 19:30:17'),
(36, 'giovanny', 'foto/1779132747_bbad701adf75aec83d8240ad7d37206b.jpg', 'profilo', '2026-05-18 19:32:27'),
(37, 'giovanny', 'foto/1779132754_db4fdc497ca9caef2644a64fe7199183.jpg', 'galleria', '2026-05-18 19:32:34'),
(38, 'giovanny', 'foto/1779132763_c5abc06909f9e4bf435ecb4db7ef2fc0.jpg', 'galleria', '2026-05-18 19:32:43'),
(39, 'giovanny', 'foto/1779132771_d54d85e0f266eace5ee2d123768c78f2.jpg', 'galleria', '2026-05-18 19:32:51'),
(40, 'brandoo', 'foto/1779132896_2f4bdc8e8ad0cd689cd15e594c9dbf52.jpg', 'profilo', '2026-05-18 19:34:56'),
(41, 'brandoo', 'foto/1779132904_3c92aa1ca8c705044e948ddd2536dccc.jpg', 'galleria', '2026-05-18 19:35:04'),
(42, 'brandoo', 'foto/1779132911_126402ad67a3f0225f4afe98a67f1d61.jpg', 'galleria', '2026-05-18 19:35:11'),
(43, 'brandoo', 'foto/1779132918_5d88da1b29d13d06812d78f75e17706f.jpg', 'galleria', '2026-05-18 19:35:18'),
(44, 'simo04', 'foto/1779227388_7c6d6ff6c07a33c0cfb24ea2d92c3a6c.jpg', 'profilo', '2026-05-19 21:49:48'),
(45, 'simo04', 'foto/1779227392_', 'galleria', '2026-05-19 21:49:52'),
(46, 'simo04', 'foto/1779227401_b48972639ca8f486de86153c4f300cdc.jpg', 'galleria', '2026-05-19 21:50:01'),
(47, 'simo04', 'foto/1779227409_5627b26f5b71d102f094ca0591b6f893.jpg', 'galleria', '2026-05-19 21:50:09'),
(48, 'simo04', 'foto/1779227425_5627b26f5b71d102f094ca0591b6f893.jpg', 'galleria', '2026-05-19 21:50:25'),
(49, 'simo04', 'foto/1779227433_1d46bcac53c20e4defcebe53770ab386.jpg', 'galleria', '2026-05-19 21:50:33'),
(50, 'simo04', 'foto/1779227441_c4d6d3d2838a1f73366f93ff43f00993.jpg', 'galleria', '2026-05-19 21:50:41'),
(51, 'giulyy', 'foto/1779227603_e3d1501913b1cd32ff952f958e55360a.jpg', 'profilo', '2026-05-19 21:53:23'),
(52, 'giulyy', 'foto/1779227613_4233480898781b9f5a6e77378b317cf3.jpg', 'galleria', '2026-05-19 21:53:33'),
(53, 'giulyy', 'foto/1779227621_1f5dae7b3be668ec8b833c690385fad6.jpg', 'galleria', '2026-05-19 21:53:41'),
(54, 'angelina', 'foto/1779227752_99fa929f666e16ee29cdf7e3473d9a70.jpg', 'profilo', '2026-05-19 21:55:52'),
(55, 'angelina', 'foto/1779227760_8aaff90fdd957338fa9d51b79f337346.jpg', 'galleria', '2026-05-19 21:56:00'),
(56, 'angelina', 'foto/1779227767_9a09a3773533da6aab6d23c7f6369fa8.jpg', 'galleria', '2026-05-19 21:56:07'),
(57, 'angelina', 'foto/1779227775_e6b307b02a4283364257eed9cf3617d7.jpg', 'galleria', '2026-05-19 21:56:15'),
(58, 'adri33', 'foto/1779227907_148ba00c6c6f7f8110098288df5b3e20.jpg', 'profilo', '2026-05-19 21:58:27'),
(59, 'adri33', 'foto/1779227916_2a5ae04ae3bf846e743d80e7ee94c179.jpg', 'galleria', '2026-05-19 21:58:36'),
(60, 'adri33', 'foto/1779227922_c0167f9d3d922a86a309338d1a22953b.jpg', 'galleria', '2026-05-19 21:58:43'),
(61, 'adri33', 'foto/1779227932_bbb20eaa9d1941dc3e380d14a54aa1be.jpg', 'galleria', '2026-05-19 21:58:52'),
(62, 'tommy98', 'foto/1779228156_02eca7bab31a209de9c4ce2e36db3a6e.jpg', 'profilo', '2026-05-19 22:02:36'),
(63, 'tommy98', 'foto/1779228163_d05a4ef11e67f4556f74ff94d39388f9.jpg', 'galleria', '2026-05-19 22:02:43'),
(64, 'tommy98', 'foto/1779228170_914905b37e89eed026613b43902097a8.jpg', 'galleria', '2026-05-19 22:02:50'),
(65, 'tommy98', 'foto/1779228177_408aca06a93b844d069af2137cb66630.jpg', 'galleria', '2026-05-19 22:02:57'),
(66, 'mattiabianchi', 'foto/1779228312_51dc0e7d7418a3d81f234e0f5a679afe.jpg', 'profilo', '2026-05-19 22:05:12'),
(67, 'mattiabianchi', 'foto/1779228320_32cb600629bfdad9cbe5f138a67dc7d3.jpg', 'galleria', '2026-05-19 22:05:20'),
(68, 'mattiabianchi', 'foto/1779228328_c7836d7eceb8af10de76f72c5236edce.jpg', 'galleria', '2026-05-19 22:05:28'),
(69, 'mattiabianchi', 'foto/1779228336_e01a79862eb71a9a8d6e5099a64d18c4.jpg', 'galleria', '2026-05-19 22:05:36'),
(70, 'cate001', 'foto/1779228476_5dd0696629dfde819ac7aabb2161c992.jpg', 'profilo', '2026-05-19 22:07:56'),
(71, 'cate001', 'foto/1779228483_53bcd55e9d07668d1d59033147a8fde5.jpg', 'galleria', '2026-05-19 22:08:03'),
(72, 'cate001', 'foto/1779228490_1c3effc3510bc175ac34ddc790b37ce8.jpg', 'galleria', '2026-05-19 22:08:10'),
(73, 'cate001', 'foto/1779228498_212685caf8e2cff51cc5be959fc0cbfd.jpg', 'galleria', '2026-05-19 22:08:18'),
(74, 'aleburo', 'foto/1779228665_3f67be922de9abef9d08a76b677aa198.jpg', 'profilo', '2026-05-19 22:11:05'),
(75, 'aleburo', 'foto/1779228673_54e6354380ecb572b5cd2760723de036.jpg', 'galleria', '2026-05-19 22:11:13'),
(76, 'aleburo', 'foto/1779228680_ec477c959fe7ac113a0cdd0d1ed001d0.jpg', 'galleria', '2026-05-19 22:11:20'),
(77, 'aleburo', 'foto/1779228687_0d7369fc44662c3ead37218c1165b86f.jpg', 'galleria', '2026-05-19 22:11:27');

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
('adri33', 1, 0, 1, 0, 0, 1, 0, 1, 1, 0),
('aleburo', 1, 1, 1, 0, 1, 0, 0, 0, 0, 1),
('angelina', 1, 1, 1, 0, 1, 0, 0, 0, 0, 1),
('brandoo', 1, 0, 1, 0, 1, 1, 0, 1, 0, 1),
('cate001', 0, 1, 1, 1, 1, 0, 0, 1, 1, 0),
('giadapiery', 0, 1, 0, 0, 1, 0, 0, 1, 0, 1),
('giorgiaaa', 1, 1, 0, 0, 0, 1, 0, 1, 0, 1),
('giovanny', 1, 0, 0, 0, 1, 0, 1, 1, 0, 0),
('giovi', 1, 0, 1, 0, 1, 0, 1, 0, 0, 1),
('giulyy', 0, 1, 1, 1, 1, 0, 0, 1, 1, 0),
('jenny29', 1, 0, 1, 1, 1, 0, 0, 1, 0, 1),
('leoleo', 1, 1, 0, 0, 1, 0, 0, 1, 0, 0),
('marcobelli', 0, 1, 0, 0, 1, 1, 0, 1, 0, 0),
('mattiabianchi', 1, 0, 1, 0, 0, 1, 1, 0, 0, 1),
('paolino', 0, 1, 0, 0, 1, 0, 0, 1, 0, 1),
('simo04', 1, 1, 1, 0, 1, 1, 0, 1, 0, 1),
('sofy06', 1, 1, 0, 0, 0, 1, 0, 1, 0, 1),
('tina', 0, 1, 0, 0, 1, 0, 0, 1, 1, 0),
('tommy98', 1, 0, 1, 0, 1, 1, 0, 0, 0, 1),
('tommy99', 1, 0, 0, 0, 0, 1, 0, 1, 0, 1);

-- --------------------------------------------------------

--
-- Struttura della tabella `likes`
--

CREATE TABLE `likes` (
  `id_like` int(30) NOT NULL,
  `id_mit` varchar(30) NOT NULL,
  `id_dest` varchar(30) NOT NULL,
  `stato` enum('like','match','skipped') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `likes`
--

INSERT INTO `likes` (`id_like`, `id_mit`, `id_dest`, `stato`) VALUES
(1, 'simo04', 'aleburo', 'like'),
(3, 'simo04', 'angelina', 'like'),
(4, 'simo04', 'cate001', 'like'),
(5, 'simo04', 'giulyy', 'like'),
(6, 'simo04', 'jenny29', 'like'),
(7, 'adri33', 'giovanny', 'like'),
(9, 'adri33', 'leoleo', 'like'),
(10, 'adri33', 'mattiabianchi', 'like'),
(11, 'adri33', 'tommy98', 'like');

-- --------------------------------------------------------

--
-- Struttura della tabella `messaggi`
--

CREATE TABLE `messaggi` (
  `id` int(10) NOT NULL,
  `id_mit` varchar(30) NOT NULL,
  `id_dest` varchar(30) NOT NULL,
  `testo` varchar(255) NOT NULL,
  `letto` tinyint(1) NOT NULL DEFAULT 0,
  `data` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `notifica`
--

CREATE TABLE `notifica` (
  `id` int(11) NOT NULL,
  `id_dest` varchar(30) NOT NULL,
  `tipo` enum('like','match','messaggio') NOT NULL,
  `id_mit` varchar(30) NOT NULL,
  `data` timestamp NOT NULL DEFAULT current_timestamp(),
  `letto` tinyint(1) DEFAULT 0,
  `testo` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
-- Indici per le tabelle `likes`
--
ALTER TABLE `likes`
  ADD PRIMARY KEY (`id_like`);

--
-- Indici per le tabelle `messaggi`
--
ALTER TABLE `messaggi`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `notifica`
--
ALTER TABLE `notifica`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `foto_utenti`
--
ALTER TABLE `foto_utenti`
  MODIFY `id_foto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=78;

--
-- AUTO_INCREMENT per la tabella `likes`
--
ALTER TABLE `likes`
  MODIFY `id_like` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT per la tabella `messaggi`
--
ALTER TABLE `messaggi`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `notifica`
--
ALTER TABLE `notifica`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

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

ALTER TABLE `datiregistrazione`
  ADD COLUMN `latitudine` DECIMAL(10, 8) NULL;
ALTER TABLE `datiregistrazione` 
  ADD COLUMN `longitudine` DECIMAL(11, 8) NULL;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;