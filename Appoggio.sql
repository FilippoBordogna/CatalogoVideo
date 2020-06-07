-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Creato il: Giu 07, 2020 alle 12:08
-- Versione del server: 10.4.11-MariaDB
-- Versione PHP: 7.4.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `catalogo`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `accessi`
--

CREATE TABLE `accessi` (
  `id` int(11) NOT NULL,
  `indirizzoIP` varchar(18) NOT NULL,
  `dataOra` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `durata` int(11) DEFAULT NULL,
  `idUtente` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `accessi`
--

INSERT INTO `accessi` (`id`, `indirizzoIP`, `dataOra`, `durata`, `idUtente`) VALUES
(1, '::1', '2020-05-26 20:20:11', 83, 3),
(2, '::1', '2020-05-26 20:29:23', 906, 4),
(3, '::1', '2020-05-26 20:31:15', 107, 3),
(4, '::1', '2020-05-26 20:32:50', 24, 1),
(5, '::1', '2020-05-26 20:34:27', 14, 5),
(6, '::1', '2020-05-26 20:35:09', 63, 6),
(7, '::1', '2020-05-26 20:36:04', 82, 7),
(8, '::1', '2020-05-26 20:37:16', 99, 7),
(9, '::1', '2020-05-26 20:37:49', 28, 4),
(10, '::1', '2020-05-27 04:48:38', 1440, 3),
(11, '::1', '2020-05-26 20:41:50', 293, 1),
(12, '::1', '2020-05-26 20:41:51', 1440, 1),
(13, '::1', '2020-05-27 04:48:45', 1440, 3),
(14, '::1', '2020-05-27 05:07:57', 1440, 7),
(15, '::1', '2020-05-27 05:32:26', 1440, 3),
(16, '::1', '2020-06-02 13:26:57', 3, 8),
(19, '172.68.198.45', '2020-06-04 15:04:25', 1440, 4),
(20, '172.68.198.81', '2020-06-04 15:05:17', 65, 4),
(21, '172.68.198.45', '2020-06-04 15:06:48', 115, 1),
(22, '172.68.198.45', '2020-06-04 15:07:57', 104, 3),
(23, '188.114.103.242', '2020-06-04 15:08:18', 13, 1),
(24, '188.114.102.27', '2020-06-04 15:08:23', 1440, 3),
(25, '172.68.198.45', '2020-06-04 19:16:36', 1440, 1),
(26, '172.68.198.127', '2020-06-06 12:45:52', 1440, 3),
(27, '172.68.198.127', '2020-06-06 12:47:39', 1440, 3);

-- --------------------------------------------------------

--
-- Struttura della tabella `attorivideo`
--

CREATE TABLE `attorivideo` (
  `idVideo` int(11) NOT NULL,
  `idPersona` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `attorivideo`
--

INSERT INTO `attorivideo` (`idVideo`, `idPersona`) VALUES
(1, 1),
(1, 10),
(2, 11),
(3, 1),
(3, 5),
(3, 10),
(4, 4),
(4, 6),
(5, 2),
(6, 1),
(6, 2),
(6, 3),
(6, 4),
(6, 5),
(6, 6),
(7, 1),
(7, 10),
(8, 4),
(9, 2),
(9, 5),
(10, 7),
(11, 22),
(11, 23),
(12, 22),
(12, 23),
(13, 22),
(13, 23),
(14, 22),
(14, 23),
(15, 26),
(15, 27),
(16, 26),
(16, 27),
(17, 26),
(17, 27),
(18, 26),
(18, 27),
(19, 30),
(19, 31),
(20, 30),
(20, 31),
(21, 30),
(21, 31),
(22, 30),
(22, 31),
(24, 37),
(24, 38),
(24, 39),
(24, 40),
(25, 42),
(25, 43),
(27, 46),
(29, 51),
(29, 52),
(30, 54),
(30, 55),
(31, 56),
(31, 57),
(31, 58),
(32, 60),
(33, 5),
(34, 5),
(34, 62);

-- --------------------------------------------------------

--
-- Struttura della tabella `comparizioni`
--

CREATE TABLE `comparizioni` (
  `idPersonaggio` int(11) NOT NULL,
  `idVideo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `comparizioni`
--

INSERT INTO `comparizioni` (`idPersonaggio`, `idVideo`) VALUES
(1, 1),
(1, 3),
(1, 6),
(1, 7),
(2, 5),
(2, 6),
(2, 9),
(3, 2),
(3, 6),
(4, 4),
(4, 6),
(4, 8),
(5, 3),
(5, 6),
(5, 9),
(6, 4),
(6, 6),
(7, 10),
(8, 1),
(8, 3),
(8, 7),
(11, 11),
(11, 12),
(11, 13),
(11, 14),
(12, 11),
(12, 12),
(12, 13),
(12, 14),
(13, 15),
(13, 16),
(13, 17),
(13, 18),
(14, 15),
(14, 16),
(14, 17),
(14, 18),
(15, 19),
(15, 20),
(15, 21),
(15, 22),
(16, 19),
(16, 20),
(16, 21),
(16, 22),
(17, 31),
(18, 31),
(19, 33),
(20, 34),
(21, 34);

-- --------------------------------------------------------

--
-- Struttura della tabella `curiositaserie`
--

CREATE TABLE `curiositaserie` (
  `id` int(11) NOT NULL,
  `idSerie` int(11) NOT NULL,
  `idUtente` int(11) NOT NULL,
  `idAdmin` int(11) DEFAULT NULL,
  `testo` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struttura della tabella `curiositavideo`
--

CREATE TABLE `curiositavideo` (
  `id` int(11) NOT NULL,
  `idVideo` int(11) NOT NULL,
  `idUtente` int(11) NOT NULL,
  `idAdmin` int(11) DEFAULT NULL,
  `testo` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `curiositavideo`
--

INSERT INTO `curiositavideo` (`id`, `idVideo`, `idUtente`, `idAdmin`, `testo`) VALUES
(3, 1, 4, NULL, 'Prova'),
(6, 1, 3, NULL, 'prova prova'),
(7, 19, 7, 3, 'Rispetto al libro manca una scena in cui Katniss rischia di morire di sete'),
(8, 19, 7, 3, 'Jennifer Lawrence e Liam Hemsworth si sono dovuti tingere i capelli castani, Josh Hutcherson se li è dovuti tingere biondi'),
(9, 20, 7, 3, 'Gli attori hanno dovuto realmente mangiare del pesce crudo per registrare una scena, tutti l&#39;hanno trovato disgustoso e Jennifer Lawrence si è sentita male');

-- --------------------------------------------------------

--
-- Struttura della tabella `generi`
--

CREATE TABLE `generi` (
  `id` int(11) NOT NULL,
  `tipo` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `generi`
--

INSERT INTO `generi` (`id`, `tipo`) VALUES
(1, 'Animazone'),
(2, 'Avventura'),
(3, 'Azione'),
(4, 'Biografico'),
(5, 'Catastrofico'),
(6, 'Comico'),
(7, 'Commedia'),
(8, 'Documentario'),
(9, 'Drammatico'),
(10, 'Epico'),
(11, 'Fantascienza'),
(12, 'Fantasy'),
(13, 'Giallo'),
(14, 'Grottesco'),
(15, 'Guerra'),
(16, 'Horror'),
(17, 'Mitologico'),
(18, 'Musicale'),
(19, 'Noir'),
(20, 'Politico'),
(21, 'Poliziesco'),
(22, 'Religioso'),
(23, 'Sentimentale'),
(25, 'Spionaggio'),
(24, 'Sportivo'),
(26, 'Storico'),
(27, 'Thriller'),
(28, 'Western');

-- --------------------------------------------------------

--
-- Struttura della tabella `generivideo`
--

CREATE TABLE `generivideo` (
  `id` int(11) NOT NULL,
  `idVideo` int(11) NOT NULL,
  `idGenere` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `generivideo`
--

INSERT INTO `generivideo` (`id`, `idVideo`, `idGenere`) VALUES
(31, 1, 2),
(1, 1, 3),
(21, 1, 11),
(11, 1, 15),
(41, 1, 27),
(32, 2, 2),
(2, 2, 3),
(22, 2, 11),
(12, 2, 15),
(42, 2, 27),
(33, 3, 2),
(3, 3, 3),
(23, 3, 11),
(13, 3, 15),
(43, 3, 27),
(34, 4, 2),
(4, 4, 3),
(24, 4, 11),
(14, 4, 15),
(44, 4, 27),
(35, 5, 2),
(5, 5, 3),
(25, 5, 11),
(15, 5, 15),
(45, 5, 27),
(36, 6, 2),
(6, 6, 3),
(26, 6, 11),
(16, 6, 15),
(46, 6, 27),
(37, 7, 2),
(7, 7, 3),
(27, 7, 11),
(17, 7, 15),
(47, 7, 27),
(38, 8, 2),
(8, 8, 3),
(28, 8, 11),
(18, 8, 15),
(48, 8, 27),
(39, 9, 2),
(9, 9, 3),
(29, 9, 11),
(19, 9, 15),
(49, 9, 27),
(40, 10, 2),
(10, 10, 3),
(30, 10, 11),
(20, 10, 15),
(50, 10, 27),
(64, 11, 13),
(68, 11, 27),
(65, 12, 13),
(69, 12, 27),
(66, 13, 13),
(70, 13, 27),
(67, 14, 13),
(71, 14, 27),
(99, 15, 2),
(79, 15, 3),
(91, 15, 9),
(95, 15, 11),
(87, 15, 13),
(103, 15, 21),
(83, 15, 27),
(100, 16, 2),
(80, 16, 3),
(92, 16, 9),
(96, 16, 11),
(88, 16, 13),
(104, 16, 21),
(84, 16, 27),
(101, 17, 2),
(81, 17, 3),
(93, 17, 9),
(97, 17, 11),
(89, 17, 13),
(105, 17, 21),
(85, 17, 27),
(102, 18, 2),
(82, 18, 3),
(94, 18, 9),
(98, 18, 11),
(90, 18, 13),
(106, 18, 21),
(86, 18, 27),
(110, 19, 2),
(118, 19, 3),
(114, 19, 11),
(122, 19, 27),
(111, 20, 2),
(119, 20, 3),
(115, 20, 11),
(123, 20, 27),
(112, 21, 2),
(120, 21, 3),
(116, 21, 11),
(124, 21, 27),
(113, 22, 2),
(121, 22, 3),
(117, 22, 11),
(125, 22, 27),
(137, 23, 8),
(138, 24, 8),
(126, 24, 15),
(139, 25, 8),
(140, 26, 8),
(141, 27, 8),
(129, 27, 9),
(128, 27, 15),
(127, 27, 26),
(142, 28, 8),
(143, 29, 8),
(130, 29, 18),
(144, 30, 8),
(131, 30, 9),
(132, 30, 13),
(134, 31, 1),
(145, 31, 8),
(136, 31, 9),
(135, 31, 15),
(133, 31, 26),
(146, 32, 8);

-- --------------------------------------------------------

--
-- Struttura della tabella `interpretazioni`
--

CREATE TABLE `interpretazioni` (
  `idPersona` int(11) NOT NULL,
  `idPersonaggio` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `interpretazioni`
--

INSERT INTO `interpretazioni` (`idPersona`, `idPersonaggio`) VALUES
(1, 1),
(2, 2),
(3, 3),
(4, 4),
(5, 5),
(5, 19),
(5, 21),
(6, 6),
(7, 7),
(8, 9),
(10, 8),
(11, 3),
(12, 10),
(22, 11),
(23, 12),
(26, 14),
(27, 13),
(30, 15),
(31, 16),
(57, 17),
(58, 18),
(62, 20);

-- --------------------------------------------------------

--
-- Struttura della tabella `personaggi`
--

CREATE TABLE `personaggi` (
  `id` int(11) NOT NULL,
  `nome` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `personaggi`
--

INSERT INTO `personaggi` (`id`, `nome`) VALUES
(10, 'Ant-Man'),
(17, 'Boaz Rein-Buskila'),
(2, 'Captain America'),
(18, 'Carmi Cna\'an'),
(20, 'Charlie Barber'),
(9, 'Doctor Strange'),
(8, 'Happy Hogan'),
(14, 'Harold Finch'),
(3, 'Hulk'),
(11, 'Il professore'),
(1, 'Iron Man'),
(13, 'John Reese'),
(16, 'Katniss Everdeen'),
(19, 'Lucy Miller'),
(21, 'Nicole Barber'),
(6, 'Occhio di falco'),
(15, 'Peeta Mellark'),
(7, 'Star-Lord'),
(4, 'Thor'),
(12, 'Tokyo'),
(5, 'Vedova Nera');

-- --------------------------------------------------------

--
-- Struttura della tabella `persone`
--

CREATE TABLE `persone` (
  `id` int(11) NOT NULL,
  `nome` varchar(50) NOT NULL,
  `cognome` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `persone`
--

INSERT INTO `persone` (`id`, `nome`, `cognome`) VALUES
(1, 'Robert', 'Downey Jr.'),
(2, 'Chris', 'Evans'),
(3, 'Mark', 'Ruffalo'),
(4, 'Chris', 'Hemsworth'),
(5, 'Scarlett', 'Johansson'),
(6, 'Jeremy', 'Renner'),
(7, 'Chris', 'Pratt'),
(8, 'Benedict', 'Cumberbatch'),
(9, 'Tom', 'Holland'),
(10, 'Jon', 'Favreau'),
(11, 'Edward', 'Norton'),
(12, 'Paul', 'Rudd'),
(13, 'Louis', 'Leterrier'),
(14, 'Kenneth', 'Branagh'),
(15, 'Joe', 'Johnston'),
(16, 'Joss', 'Whedon'),
(17, 'Shane', 'Black'),
(18, 'Alan', 'Taylor'),
(19, 'Anthony', 'Russo'),
(20, 'Joe', 'Russo'),
(21, 'James', 'Gunn'),
(22, 'Alvaro', 'Morte'),
(23, 'Ursula', 'Corbero'),
(24, ' Alex ', 'Rodrigo'),
(25, 'Alejandro', 'Bazzano'),
(26, 'Michael', 'Emerson'),
(27, 'Jim', 'Caviezel'),
(28, 'Jonathan', 'Nolan'),
(29, 'Greg', 'Plageman'),
(30, 'Josh', 'Hutcherson'),
(31, 'Jennifer', 'Lawrence'),
(32, 'Gary', 'Ross'),
(33, 'Francis', 'Lawrence'),
(34, 'Nina', 'Jacobson'),
(35, 'Jon', 'Kilik'),
(36, 'Luc', 'Jacquet'),
(37, 'Michael', 'Moore'),
(38, 'Donald', 'Rumsfeld'),
(39, 'George W.', 'Bush'),
(40, 'Ben', 'Affleck'),
(41, 'Gianfranco', 'Rosi'),
(42, 'Pietro', 'Bartolo'),
(43, 'Samuel', 'Pucillo'),
(44, 'Robert', 'Kenner'),
(45, 'Claude', 'Lanzmann'),
(46, 'Szymon', 'Srebrnik'),
(47, '?Alain', ' Resnais'),
(48, 'Miyuki', 'Kuwano'),
(49, 'Kei', 'Sato'),
(50, 'D.A.', 'Pennebaker'),
(51, 'Bob', 'Dylan'),
(52, 'Joan', 'Baez'),
(53, 'Errol', 'Morris'),
(54, 'Randall Dale', 'Adams'),
(55, 'Gus', 'Rose'),
(56, 'Ari', 'Folman'),
(57, 'Mickey', 'Leon'),
(58, 'Yehezkel', 'Lazarov'),
(59, 'Dziga', 'Vertov'),
(60, 'Mikhail', 'Kaufman'),
(61, 'Alain', 'Resnais'),
(62, 'Adam', 'Driver'),
(63, 'Noah', 'Baumbach'),
(64, 'Luc', 'Besson');

-- --------------------------------------------------------

--
-- Struttura della tabella `produttorivideo`
--

CREATE TABLE `produttorivideo` (
  `idVideo` int(11) NOT NULL,
  `idPersona` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `produttorivideo`
--

INSERT INTO `produttorivideo` (`idVideo`, `idPersona`) VALUES
(1, 10),
(3, 10),
(6, 10),
(6, 16),
(7, 10),
(10, 21),
(15, 28),
(15, 29),
(16, 28),
(16, 29),
(17, 28),
(17, 29),
(18, 28),
(18, 29),
(19, 34),
(19, 35),
(20, 34),
(20, 35),
(21, 34),
(21, 35),
(22, 34),
(22, 35),
(33, 64),
(34, 63);

-- --------------------------------------------------------

--
-- Struttura della tabella `recensioniserie`
--

CREATE TABLE `recensioniserie` (
  `idSerie` int(11) NOT NULL,
  `idUtente` int(11) NOT NULL,
  `voto` int(11) NOT NULL,
  `testo` varchar(255) DEFAULT NULL,
  `idAdmin` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `recensioniserie`
--

INSERT INTO `recensioniserie` (`idSerie`, `idUtente`, `voto`, `testo`, `idAdmin`) VALUES
(1, 1, 5, 'Non un granchè', 6),
(1, 7, 8, 'Molto carina. Mi sono sfuggiti alcuni passaggi', 3),
(2, 1, 9, 'Molto carina', 5),
(2, 4, 4, 'Non un granchè', NULL),
(2, 7, 7, 'Bella ma meglio la casa di carta', 3);

-- --------------------------------------------------------

--
-- Struttura della tabella `recensionivideo`
--

CREATE TABLE `recensionivideo` (
  `idVideo` int(11) NOT NULL,
  `idUtente` int(11) NOT NULL,
  `voto` int(11) NOT NULL,
  `testo` varchar(255) DEFAULT NULL,
  `idAdmin` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `recensionivideo`
--

INSERT INTO `recensionivideo` (`idVideo`, `idUtente`, `voto`, `testo`, `idAdmin`) VALUES
(1, 1, 10, 'IRON MAN SEI IL MIO EROE! ', 5),
(1, 3, 10, 'Davvero un gran bel film! Scaricatelo dal corsaronero: https://ilcorsaronero.xyz/tor/95175/Iron_Man_2008_iTA_ENG_AC3_Bluray_1080p_Subs_x264_DSS', 3),
(1, 5, 9, 'Mi è piaciuto molto, ottimo da vedere mentre si mangia un bel piatto di polenta', 3),
(1, 6, 6, 'Non ho capito perchè non c&#39;era spider-man', 5),
(1, 7, 10, 'Tony ? Pepper', 5),
(2, 3, 7, 'Non eccezionale', 3),
(2, 4, 8, 'Non mi sembrava bello ma l&#39;ho rivalutato.', 3),
(6, 1, 8, 'Gran bel film', NULL),
(11, 7, 9, 'Ottimo inizio per questa serie', 5),
(12, 4, 5, 'Che brutta serie. Venite a vedere la mia su www.utente-banana.it', NULL),
(13, 1, 10, 'Troppo emozionante questo episodio. Molti colpi di scena', 6),
(14, 7, 3, 'Quanto odio Berlino', 3),
(15, 4, 9, 'Non vedo l\'ora di vedere altri episodi. Questa serie promette bene', 6),
(16, 1, 10, 'Proprio bella questa serie. Il professore e Tokyo sono fantastici', NULL),
(17, 4, 8, 'Quanto è affascinante il personaggio di Harold Finch: genio nascosto', 5),
(18, 7, 7, 'Molto difficile capire tutti gli stravolgimenti se non si è davvero attenti', 3),
(19, 7, 9, 'Mi è piaciuto molto e alcune scene sono mozzafiato, gran bella ambientazione', 3),
(20, 3, 9, 'Mi è piaciuto tanto, l&#39;ho riguardato tre volte, guardatelo tutti!', 3),
(20, 7, 10, 'Film fantastico, anche meglio del sequel', 3),
(23, 1, 8, 'Forse uno dei migliori documentari che abbia mai visto', 3),
(23, 4, 8, 'Carino e ben fatto\r\n', 3),
(23, 7, 7, 'Molto bello peccato per la voce di PIF: orribile. Rovina tutto', 5);

-- --------------------------------------------------------

--
-- Struttura della tabella `registivideo`
--

CREATE TABLE `registivideo` (
  `idVideo` int(11) NOT NULL,
  `idPersona` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `registivideo`
--

INSERT INTO `registivideo` (`idVideo`, `idPersona`) VALUES
(1, 10),
(2, 13),
(3, 10),
(4, 14),
(5, 15),
(6, 16),
(7, 17),
(8, 18),
(9, 19),
(9, 20),
(10, 21),
(11, 24),
(11, 25),
(12, 24),
(12, 25),
(13, 24),
(13, 25),
(14, 24),
(14, 25),
(15, 28),
(15, 29),
(16, 28),
(16, 29),
(17, 28),
(17, 29),
(18, 28),
(18, 29),
(19, 32),
(20, 33),
(21, 33),
(22, 33),
(23, 36),
(24, 37),
(25, 41),
(26, 44),
(27, 45),
(28, 61),
(29, 50),
(30, 53),
(31, 56),
(32, 59),
(33, 64),
(34, 63);

-- --------------------------------------------------------

--
-- Struttura della tabella `saghe`
--

CREATE TABLE `saghe` (
  `id` int(11) NOT NULL,
  `nome` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `saghe`
--

INSERT INTO `saghe` (`id`, `nome`) VALUES
(3, 'Hunger Games'),
(1, 'Marvel Cinematic Universe'),
(2, 'The Wizarding World');

-- --------------------------------------------------------

--
-- Struttura della tabella `serie`
--

CREATE TABLE `serie` (
  `id` int(11) NOT NULL,
  `nome` varchar(50) NOT NULL,
  `sinossi` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `serie`
--

INSERT INTO `serie` (`id`, `nome`, `sinossi`) VALUES
(1, 'La casa di Carta', 'La storia narra gli sviluppi di una rapina estremamente ambiziosa e originale: irrompere nella Fábrica Nacional de Moneda y Timbre, a Madrid, far stampare migliaia di milioni di banconote e scappare con il bottino.'),
(2, 'Person of Interest', 'In seguito agli attentati che colpirono gli Stati Uniti nel 2001, il misterioso genio dell\'informatica miliardario Harold Finch ha costruito La Macchina, un\'intelligenza artificiale (IA), per un progetto segreto antiterroristico dell\'amministrazione americana chiamato Northern Lights.');

-- --------------------------------------------------------

--
-- Struttura della tabella `utenti`
--

CREATE TABLE `utenti` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(90) NOT NULL,
  `password` varchar(32) NOT NULL,
  `admin` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `utenti`
--

INSERT INTO `utenti` (`id`, `username`, `email`, `password`, `admin`) VALUES
(1, 'username', 'email@email.com', '5f4dcc3b5aa765d61d8327deb882cf99', 0),
(3, 'grande capo', 'admin@admin.it', '21232f297a57a5a743894a0e4a801fc3', 1),
(4, 'utente banana', 'user@user.it', 'ee11cbb19052e40b07aac0ca060c23ee', 0),
(5, 'barcigabri', 'barcigabri@gmail.com', '0c8d209966c8154c43ef97bb1ba5e5e0', 1),
(6, 'pippobordo99', 'bordognapippo99@gmail.com', '0c88028bf3aa6a6a143ed846f2be1ea4', 1),
(7, 'paperino', 'donald@duck.com', 'b54b45b19ca1f1ddc424e6b878a53f2d', 0),
(8, 'AldoBaglio', 'aldo@baglio.com', 'b104ab9a0e58c861b9628208b3fecd58', 0);

-- --------------------------------------------------------

--
-- Struttura della tabella `video`
--

CREATE TABLE `video` (
  `id` int(11) NOT NULL,
  `nome` varchar(50) NOT NULL,
  `durata` int(11) NOT NULL,
  `idSaga` int(11) DEFAULT NULL,
  `idSerie` int(11) DEFAULT NULL,
  `numero` int(11) DEFAULT NULL,
  `stagione` int(11) DEFAULT NULL,
  `selettore` int(11) NOT NULL,
  `sinossi` varchar(500) DEFAULT NULL,
  `annoUscita` year(4) NOT NULL,
  `nazionalita` varchar(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `video`
--

INSERT INTO `video` (`id`, `nome`, `durata`, `idSaga`, `idSerie`, `numero`, `stagione`, `selettore`, `sinossi`, `annoUscita`, `nazionalita`) VALUES
(1, 'Iron Man', 126, 1, NULL, 1, NULL, 1, 'Dopo essere sopravvissuto ad un attacco inaspettato in territorio nemico, l\'industriale Tony Stark costruisce un\'armatura ad alta tecnologia e giura di proteggere il mondo nei panni di Iron Man.', 2008, 'USA'),
(2, 'L\'incredibile Hulk', 135, 1, NULL, 2, NULL, 1, 'Bruce Banner era uno scienziato, ma un\'esposizione accidentale ai raggi gamma ha provocato una mutazione genetica e sconvolto la sua esistenza. Ogni qualvolta le emozioni lo assalgono, Bruce si trasforma in Hulk, mostro verde dalla forza smisurata.', 2008, 'USA'),
(3, 'Iron Man 2', 124, 1, NULL, 3, NULL, 1, 'Ora che tutto il mondo conosce la vera identità di Iron Man, l\'inventore milionario Tony Stark deve affrontare le pressioni alla collaborazione da parte dei militari e stringere nuove alleanze per affrontare un potente nemico.', 2010, 'USA'),
(4, 'Thor', 115, 1, NULL, 4, NULL, 1, 'Dopo che le sue azioni sconsiderate hanno riacceso un\'antica guerra, il dio nordico Thor viene spogliato dei propri poteri, scacciato dal regno di Asgard, e costretto a vivere tra gli umani.', 2011, 'USA'),
(5, 'Captain America - Il primo Vendicatore', 124, 1, NULL, 5, NULL, 1, 'Steve è un giovanotto smilzo che vuole ad ogni costo arruolarsi nell\'esercito per combattere i nazisti. Putroppo viene regolarmente scartato, ma un giorno gli si presenta la possibilità di fare da cavia: gli iniettano così il siero del supersoldato', 2011, 'USA'),
(6, 'The Avengers', 143, 1, NULL, 6, NULL, 1, 'I leggendari supereroi Iron Man, Hulk, Thor, Capitan America, Occhio di Falco e la Vedova Nera vengono reclutati da un\'agenzia governativa segreta per combattere un nemico inatteso che minaccia la sicurezza della Terra.', 2012, 'USA'),
(7, 'Iron Man 3', 131, 1, NULL, 7, NULL, 1, 'Dopo aver salvato New York dalla distruzione ed essere rimasto da allora insonne e preoccupato, Tony Stark rimasto senza armatura deve lottare contro le sue paure interiori per sconfiggere il suo nuovo nemico, il Mandarino.', 2013, 'USA'),
(8, 'Thor: The Dark World', 112, 1, NULL, 8, NULL, 1, 'Dal personaggio della Marvel, Thor si allea con il perfido Loki per salvare la Terra dei Nove Regni da un antico nemico nato prima ancora dell\'universo.', 2013, 'USA'),
(9, 'Captain America: The Winter Soldier', 136, 1, NULL, 9, NULL, 1, 'Capitan America, Vedova Nera e un nuovo alleato, Falcon, affrontano un nemico inaspettato mentre lottano per far emergere alla luce del sole un complotto che mette a rischio il mondo intero.', 2014, 'USA'),
(10, 'Guardiani della Galassia', 125, 1, NULL, 10, NULL, 1, 'Un avventuriero spaziale, Brash Peter Quill, diventa preda di alcuni cacciatori di taglie dopo aver rubato una sfera ambita dal potente Ronan. Per sfuggire alla morte, l\'uomo si allea con quattro improbabili compagni di avventura.', 2014, 'USA'),
(11, ' Eseguire ciò che è stato concordato', 47, NULL, 1, 1, 1, 2, 'A ciascun componente della banda viene dato il nome di una città: Tokyo, narratrice della storia, il cui compagno è appena morto dopo uno scontro a fuoco per un furto; Mosca e Denver, padre e figlio; Berlino, eletto dal Professore a capo delle operazioni; Nairobi, l\'altra donna del gruppo; Río, giovane hacker; Helsinki e Oslo, cugini serbi', 2017, 'ESP'),
(12, 'Incoscenza leale', 41, NULL, 1, 2, 1, 2, 'Río è vivo, è solo ferito alla testa. Berlino, al telefono con il Professore, gli rivela che Tokyo e Río hanno una relazione, ma lei smentisce tutto. Río, invece, quando sono soli, conferma a Berlino che ama Tokyo.', 2017, 'ESP'),
(13, 'Le maschere sono finite', 43, NULL, 1, 1, 2, 2, 'La polizia e la Scientifica stanno passando al setaccio il casale di Toledo in cerca di tracce; il Professore, che ha accompagnato Raquel, è in auto, estremamente nervoso, ma trova un quaderno di Paula, la figlia di Raquel, in cui la maestra scrive alla madre delle sue preoccupazioni per un invito da parte di Alberto, il padre. Dentro all\'edificio vi sono moltissime prove, tattiche, impronte, DNA.', 2019, 'ESP'),
(14, 'Il capo del piano', 44, NULL, 1, 2, 2, 2, 'Berlino è in ostaggio di Tokyo con l\'aiuto di Río e Denver. Tokyo vuole sapere i dettagli del \"piano Chernobyl\", il piano da attuare solo in caso di emergenza, e per farlo lo tortura rompendo alcune boccette del suo farmaco e giocando alla roulette russa con lui. ', 2019, 'ESP'),
(15, 'La macchina della conoscenza', 44, NULL, 2, 1, 1, 2, 'La serie inizia con un flashback in cui si vede in un letto John Reese insieme a una donna. Siamo nel 2011, Reese è un barbone di New York.', 2011, 'USA'),
(16, 'Una voce dal passato', 44, NULL, 2, 2, 1, 2, 'Dopo aver salvato un uomo di nome Bill da alcuni sicari assoldati da sua moglie, Reese pedina Finch per scoprire più informazioni sul suo conto, quando quest\'ultimo chiama l\'ex agente per assegnarli a un\'altra missione.', 2011, 'USA'),
(17, 'Il piano di emergenza', 42, NULL, 2, 1, 2, 2, 'Reese riceve una serie di parole apparentemente casuali dalla Macchina, che l\'ha contattato tramite un telefono pubblico. Dopo aver assegnato alla Carter il compito di indagare più approfonditamente sulla morte di Alicia Corwin, uccisa da Root durante il rapimento di Finch, egli capisce che le parole identificano tre differenti libri nella biblioteca di Finch.', 2012, 'USA'),
(18, 'Cattivi geni', 42, NULL, 2, 2, 2, 2, 'Reese e Carter partono per il Texas sulle tracce di Hanna Frey, una ragazzina scomparsa molti anni prima. Nel frattempo, Root tiene prigionieri Finch e Weeks e tortura ed interroga quest’ultimo per sapere la posizione della Macchina, accusandolo di essere un \"codice malevolo\".', 2012, 'USA'),
(19, 'Hunger Games', 143, 3, NULL, 1, NULL, 1, 'Ogni anno, come punizione per aver scatenato la ribellione anni prima, in ogni distretto vengono scelti un ragazzo e una ragazza di età compresa tra i dodici e i diciotto anni per partecipare agli Hunger Games, un evento nel quale i partecipanti devono combattere in un luogo detto \"arena\", che viene controllata dagli Strateghi per mezzo di computer molto sofisticati, finché uno solo rimane vivo', 2012, 'USA'),
(20, 'Hunger Games: La ragazza di fuoco', 146, 3, NULL, 2, NULL, 1, 'Katniss Everdeen ritorna a casa in seguito alla vittoria ottenuta nell\'ultima edizione degli Hunger Games insieme al suo compagno Peeta Mellark. Ora i due alloggiano al Villaggio dei Vincitori, presente in ogni distretto, assieme al loro mentore Haymitch Abernathy.\r\n\r\nPer i due giovani è giunto il momento di partire per il Tour della Vittoria, un viaggio attraverso i distretti per ricordare coloro che sono morti nei giochi.', 2013, 'USA'),
(21, 'Hunger Games: Il canto della rivolta - Parte 1', 123, 3, NULL, 3, NULL, 1, 'Katniss Everdeen si trova nel Distretto 13 in stato confusionale, dopo aver distrutto l\'arena dei settantacinquesimi Hunger Games. Appena la ragazza inizia a riprendersi viene nominata da Plutarch Heavensbee e dalla presidentessa Alma Coin come immagine simbolo della ribellione, ma la ragazza, ormai nota a tutti come la \"ghiandaia imitatrice\", inizialmente rifiuta l\'offerta poiché ancora molto turbata.', 2014, 'USA'),
(22, 'Hunger Games: Il canto della rivolta parte 2', 137, 3, NULL, 4, NULL, 1, 'La nazione di Panem è in guerra. Tutti i distretti sono ormai uniti nella rivolta contro Capitol City, guidata da Katniss Everdeen, che continua a vestire i panni della ghiandaia imitatrice e quindi a impersonare il simbolo della rivoluzione stessa. Nel frattempo Peeta è ancora sotto shock a causa del depistaggio cerebrale causatogli dal Presidente Snow mentre era prigioniero a Capitol City e i suoi sentimenti di odio verso Katniss sono ancora presenti, benché lentamente comincino a svanire.', 2015, 'USA'),
(23, 'La marcia dei pinguini', 85, NULL, NULL, NULL, NULL, 3, 'Il documentario francese che ha commosso tutto il mondo parla della lotta per la sopravvivenza del pinguino imperatore.\r\nUn documentario toccante che racconta come l’amore per i propri piccoli non sia un affare solo umano: guardando questo film capirete quanto umano sia il sentimento che i pinguini nutrono per la loro prole.', 2005, 'FRA'),
(24, 'Fahrenheit 9/11', 122, NULL, NULL, NULL, NULL, 3, 'gioiellino che scardina le falsità e le bugie raccontate da George W. Bush e dalla sua Amministrazione.', 2004, 'USA'),
(25, 'Fuocoammare', 106, NULL, NULL, NULL, NULL, 3, 'Attraverso gli occhi di Samuele, un ragazzino che vive a Lampedusa, viene raccontato il dramma attualissimo dei migranti che tentano una seconda vita attraversando il mare ma che spesso non solo non arrivano a quella tanto anelata seconda vita: addirittura perdono la prima e unica che gli rimane.', 2016, 'ITA'),
(26, 'Food Inc.', 94, NULL, NULL, NULL, NULL, 3, NULL, 2008, 'USA'),
(27, 'Shoah', 613, NULL, NULL, NULL, NULL, 3, 'Documentario del tutto privo di immagini di repertorio. Si analizzano le tre tipologie di superstiti dei campi di concentramento: vittime, carnefici e testimoni. Molte le immagini degli ex nazisti, che avevano accettato d’essere soltanto intervistati in audio. Ciò che ne vien fuori è un ritratto terribile.', 1985, 'FRA'),
(28, 'Notte e nebbia', 32, NULL, NULL, NULL, NULL, 3, 'Suddiviso in quattro sezioni, propone una storia di seduzione dal finale tragico, la vita notturna di Parigi, a partire dalle prime ore della sera fino al mattino seguente, una satira su La Gioconda e un’analisi dei crimini nazisti nei campi di concentramento.', 1960, 'FRA'),
(29, 'Don’t Look Back', 96, NULL, NULL, NULL, NULL, 3, 'Documentario che segue le tappe della tournée inglese di un giovanissimo Bob Dylan. Il tutto prodotto in presa diretta con una cinepresa portatile. Un film documentario che immortala il clima on the road di quei tempi.', 1967, 'USA'),
(30, 'La sottile linea blu', 106, NULL, NULL, NULL, NULL, 3, 'Un attacco al sistema giudiziario americano, sfruttando la storia di Randall Adams e David Harris, coinvolti in una sparatoria con dei poliziotti. Uno di questi rimase ucciso e le prove offerte dagli altri agenti, che hanno portato Adams al braccio della morte, per molti sono risultate inconsistenti.', 1988, 'USA'),
(31, 'Valzer con Bashir', 90, NULL, NULL, NULL, NULL, 3, 'Ripercorre infatti i conflitti che coinvolsero il Libano nei primi anni ottanta, culminando con crudezza e assoluta drammaticità nella rappresentazione del massacro di Sabra e Shatila del 1982.', 2008, 'ISR'),
(32, 'L’uomo con la macchina da presa', 68, NULL, NULL, NULL, NULL, 3, 'Racconta la giornata di un cineoperatore, dall’alba al tramonto. Questi riprende soprattutto scene di vita quotidiana, girando per le strade di Mosca, mostrando una certa arditezza nella ricerca di inquadrature a sensazione.', 1929, 'URS'),
(33, 'Lucy', 90, NULL, NULL, NULL, NULL, 1, 'Lucy è una studentessa americana che vive a Taiwan. La sua vita scorre tranquilla, dividendosi fra studio e divertimento notturno. Fino al giorno in cui un ragazzo le chiede di consegnare una valigetta. Lei vorrebbe defilarsi ma viene incastrata in una situazione pericolosa.', 2014, 'FRA'),
(34, 'Storia di un matrimonio', 137, NULL, NULL, NULL, NULL, 1, 'Un regista teatrale e la moglie attrice, un tempo felicemente sposati, intraprendono un lungo ed estenuante divorzio, che li pone di fronte ai loro limiti e alle necessarie rinunce con cui dovranno fare i conti.', 2019, 'USA');

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `accessi`
--
ALTER TABLE `accessi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idUtente` (`idUtente`);

--
-- Indici per le tabelle `attorivideo`
--
ALTER TABLE `attorivideo`
  ADD PRIMARY KEY (`idVideo`,`idPersona`),
  ADD KEY `idPersona` (`idPersona`);

--
-- Indici per le tabelle `comparizioni`
--
ALTER TABLE `comparizioni`
  ADD PRIMARY KEY (`idPersonaggio`,`idVideo`),
  ADD KEY `idVideo` (`idVideo`);

--
-- Indici per le tabelle `curiositaserie`
--
ALTER TABLE `curiositaserie`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idSerie` (`idSerie`),
  ADD KEY `idUtente` (`idUtente`),
  ADD KEY `idAdmin` (`idAdmin`);

--
-- Indici per le tabelle `curiositavideo`
--
ALTER TABLE `curiositavideo`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idVideo` (`idVideo`),
  ADD KEY `idUtente` (`idUtente`),
  ADD KEY `idAdmin` (`idAdmin`);

--
-- Indici per le tabelle `generi`
--
ALTER TABLE `generi`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tipo` (`tipo`);

--
-- Indici per le tabelle `generivideo`
--
ALTER TABLE `generivideo`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `idVideo_2` (`idVideo`,`idGenere`),
  ADD KEY `idGenere` (`idGenere`);

--
-- Indici per le tabelle `interpretazioni`
--
ALTER TABLE `interpretazioni`
  ADD PRIMARY KEY (`idPersona`,`idPersonaggio`),
  ADD KEY `idPersonaggio` (`idPersonaggio`);

--
-- Indici per le tabelle `personaggi`
--
ALTER TABLE `personaggi`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nome` (`nome`);

--
-- Indici per le tabelle `persone`
--
ALTER TABLE `persone`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `produttorivideo`
--
ALTER TABLE `produttorivideo`
  ADD PRIMARY KEY (`idVideo`,`idPersona`),
  ADD KEY `idPersona` (`idPersona`);

--
-- Indici per le tabelle `recensioniserie`
--
ALTER TABLE `recensioniserie`
  ADD PRIMARY KEY (`idSerie`,`idUtente`),
  ADD KEY `idUtente` (`idUtente`),
  ADD KEY `idAdmin` (`idAdmin`);

--
-- Indici per le tabelle `recensionivideo`
--
ALTER TABLE `recensionivideo`
  ADD PRIMARY KEY (`idVideo`,`idUtente`),
  ADD KEY `idUtente` (`idUtente`),
  ADD KEY `idAdmin` (`idAdmin`);

--
-- Indici per le tabelle `registivideo`
--
ALTER TABLE `registivideo`
  ADD PRIMARY KEY (`idVideo`,`idPersona`),
  ADD KEY `idPersona` (`idPersona`);

--
-- Indici per le tabelle `saghe`
--
ALTER TABLE `saghe`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nome` (`nome`);

--
-- Indici per le tabelle `serie`
--
ALTER TABLE `serie`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `utenti`
--
ALTER TABLE `utenti`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indici per le tabelle `video`
--
ALTER TABLE `video`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `idSerie` (`idSerie`,`numero`,`stagione`),
  ADD UNIQUE KEY `idSaga` (`idSaga`,`numero`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `accessi`
--
ALTER TABLE `accessi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT per la tabella `curiositaserie`
--
ALTER TABLE `curiositaserie`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `curiositavideo`
--
ALTER TABLE `curiositavideo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT per la tabella `generi`
--
ALTER TABLE `generi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT per la tabella `generivideo`
--
ALTER TABLE `generivideo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=147;

--
-- AUTO_INCREMENT per la tabella `personaggi`
--
ALTER TABLE `personaggi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT per la tabella `persone`
--
ALTER TABLE `persone`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;

--
-- AUTO_INCREMENT per la tabella `saghe`
--
ALTER TABLE `saghe`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT per la tabella `serie`
--
ALTER TABLE `serie`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT per la tabella `utenti`
--
ALTER TABLE `utenti`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT per la tabella `video`
--
ALTER TABLE `video`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `accessi`
--
ALTER TABLE `accessi`
  ADD CONSTRAINT `accessi_ibfk_1` FOREIGN KEY (`idUtente`) REFERENCES `utenti` (`id`);

--
-- Limiti per la tabella `attorivideo`
--
ALTER TABLE `attorivideo`
  ADD CONSTRAINT `attorivideo_ibfk_1` FOREIGN KEY (`idPersona`) REFERENCES `persone` (`id`),
  ADD CONSTRAINT `attorivideo_ibfk_2` FOREIGN KEY (`idVideo`) REFERENCES `video` (`id`);

--
-- Limiti per la tabella `comparizioni`
--
ALTER TABLE `comparizioni`
  ADD CONSTRAINT `comparizioni_ibfk_1` FOREIGN KEY (`idPersonaggio`) REFERENCES `personaggi` (`id`),
  ADD CONSTRAINT `comparizioni_ibfk_2` FOREIGN KEY (`idVideo`) REFERENCES `video` (`id`);

--
-- Limiti per la tabella `curiositaserie`
--
ALTER TABLE `curiositaserie`
  ADD CONSTRAINT `curiositaserie_ibfk_1` FOREIGN KEY (`idSerie`) REFERENCES `serie` (`id`),
  ADD CONSTRAINT `curiositaserie_ibfk_2` FOREIGN KEY (`idUtente`) REFERENCES `utenti` (`id`),
  ADD CONSTRAINT `curiositaserie_ibfk_3` FOREIGN KEY (`idAdmin`) REFERENCES `utenti` (`id`);

--
-- Limiti per la tabella `curiositavideo`
--
ALTER TABLE `curiositavideo`
  ADD CONSTRAINT `curiositavideo_ibfk_1` FOREIGN KEY (`idVideo`) REFERENCES `video` (`id`),
  ADD CONSTRAINT `curiositavideo_ibfk_2` FOREIGN KEY (`idUtente`) REFERENCES `utenti` (`id`),
  ADD CONSTRAINT `curiositavideo_ibfk_3` FOREIGN KEY (`idAdmin`) REFERENCES `utenti` (`id`);

--
-- Limiti per la tabella `generivideo`
--
ALTER TABLE `generivideo`
  ADD CONSTRAINT `generivideo_ibfk_1` FOREIGN KEY (`idVideo`) REFERENCES `video` (`id`),
  ADD CONSTRAINT `generivideo_ibfk_2` FOREIGN KEY (`idGenere`) REFERENCES `generi` (`id`);

--
-- Limiti per la tabella `interpretazioni`
--
ALTER TABLE `interpretazioni`
  ADD CONSTRAINT `interpretazioni_ibfk_1` FOREIGN KEY (`idPersona`) REFERENCES `persone` (`id`),
  ADD CONSTRAINT `interpretazioni_ibfk_2` FOREIGN KEY (`idPersonaggio`) REFERENCES `personaggi` (`id`);

--
-- Limiti per la tabella `produttorivideo`
--
ALTER TABLE `produttorivideo`
  ADD CONSTRAINT `produttorivideo_ibfk_1` FOREIGN KEY (`idPersona`) REFERENCES `persone` (`id`),
  ADD CONSTRAINT `produttorivideo_ibfk_2` FOREIGN KEY (`idVideo`) REFERENCES `video` (`id`);

--
-- Limiti per la tabella `recensioniserie`
--
ALTER TABLE `recensioniserie`
  ADD CONSTRAINT `recensioniserie_ibfk_1` FOREIGN KEY (`idSerie`) REFERENCES `serie` (`id`),
  ADD CONSTRAINT `recensioniserie_ibfk_2` FOREIGN KEY (`idUtente`) REFERENCES `utenti` (`id`),
  ADD CONSTRAINT `recensioniserie_ibfk_3` FOREIGN KEY (`idAdmin`) REFERENCES `utenti` (`id`);

--
-- Limiti per la tabella `recensionivideo`
--
ALTER TABLE `recensionivideo`
  ADD CONSTRAINT `recensionivideo_ibfk_1` FOREIGN KEY (`idVideo`) REFERENCES `video` (`id`),
  ADD CONSTRAINT `recensionivideo_ibfk_2` FOREIGN KEY (`idUtente`) REFERENCES `utenti` (`id`),
  ADD CONSTRAINT `recensionivideo_ibfk_3` FOREIGN KEY (`idAdmin`) REFERENCES `utenti` (`id`);

--
-- Limiti per la tabella `registivideo`
--
ALTER TABLE `registivideo`
  ADD CONSTRAINT `registivideo_ibfk_1` FOREIGN KEY (`idPersona`) REFERENCES `persone` (`id`),
  ADD CONSTRAINT `registivideo_ibfk_2` FOREIGN KEY (`idVideo`) REFERENCES `video` (`id`);

--
-- Limiti per la tabella `video`
--
ALTER TABLE `video`
  ADD CONSTRAINT `video_ibfk_1` FOREIGN KEY (`idSaga`) REFERENCES `saghe` (`id`),
  ADD CONSTRAINT `video_ibfk_2` FOREIGN KEY (`idSerie`) REFERENCES `serie` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
