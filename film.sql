-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Creato il: Mag 15, 2020 alle 19:16
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
-- Database: `film`
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struttura della tabella `comparizioni`
--

CREATE TABLE `comparizioni` (
  `idPersonaggio` int(11) NOT NULL,
  `idVideo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
(8, 7);

-- --------------------------------------------------------

--
-- Struttura della tabella `curiositaserie`
--

CREATE TABLE `curiositaserie` (
  `id` int(11) NOT NULL,
  `idSerie` int(11) NOT NULL,
  `idUtente` int(11) NOT NULL,
  `idAdmin` int(11) NOT NULL,
  `testo` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struttura della tabella `curiositavideo`
--

CREATE TABLE `curiositavideo` (
  `id` int(11) NOT NULL,
  `idVideo` int(11) NOT NULL,
  `idUtente` int(11) NOT NULL,
  `idAdmin` int(11) NOT NULL,
  `testo` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struttura della tabella `interpretazioni`
--

CREATE TABLE `interpretazioni` (
  `idAttore` int(11) NOT NULL,
  `idPersonaggio` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `interpretazioni`
--

INSERT INTO `interpretazioni` (`idAttore`, `idPersonaggio`) VALUES
(1, 1),
(2, 2),
(3, 3),
(4, 4),
(5, 5),
(6, 6),
(7, 7),
(8, 9),
(10, 8),
(11, 3),
(12, 10);

-- --------------------------------------------------------

--
-- Struttura della tabella `partecipazioni`
--

CREATE TABLE `partecipazioni` (
  `idPersona` int(11) NOT NULL,
  `idVideo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `partecipazioni`
--

INSERT INTO `partecipazioni` (`idPersona`, `idVideo`) VALUES
(1, 1),
(1, 3),
(1, 6),
(1, 7),
(2, 5),
(2, 6),
(3, 6),
(4, 4),
(4, 6),
(4, 8),
(4, 9),
(5, 3),
(5, 6),
(5, 9),
(6, 4),
(6, 6),
(7, 10),
(10, 1),
(10, 3),
(10, 7),
(11, 2);

-- --------------------------------------------------------

--
-- Struttura della tabella `personaggi`
--

CREATE TABLE `personaggi` (
  `id` int(11) NOT NULL,
  `nome` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `personaggi`
--

INSERT INTO `personaggi` (`id`, `nome`) VALUES
(1, 'Iron Man'),
(2, 'Captain America'),
(3, 'Hulk'),
(4, 'Thor'),
(5, 'Vedova Nera'),
(6, 'Occhio di falco'),
(7, 'Star-Lord'),
(8, 'Happy Hogan'),
(9, 'Doctor Strange'),
(10, 'Ant-Man');

-- --------------------------------------------------------

--
-- Struttura della tabella `persone`
--

CREATE TABLE `persone` (
  `id` int(11) NOT NULL,
  `nome` varchar(50) NOT NULL,
  `cognome` varchar(50) NOT NULL,
  `regista` tinyint(1) NOT NULL,
  `attore` tinyint(1) NOT NULL,
  `produttore` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `persone`
--

INSERT INTO `persone` (`id`, `nome`, `cognome`, `regista`, `attore`, `produttore`) VALUES
(1, 'Robert', 'Downey Jr.', 0, 1, 0),
(2, 'Chris', 'Evans', 0, 1, 0),
(3, 'Mark', 'Ruffalo', 0, 1, 0),
(4, 'Chris', 'Hemsworth', 0, 1, 0),
(5, 'Scarlett', 'Johansson', 0, 1, 1),
(6, 'Jeremy', 'Renner', 0, 1, 0),
(7, 'Chris', 'Pratt', 0, 1, 0),
(8, 'Benedict', 'Cumberbatch', 0, 1, 0),
(9, 'Tom', 'Holland', 0, 1, 0),
(10, 'Jon', 'Favreau', 1, 1, 1),
(11, 'Edward', 'Norton', 0, 1, 0),
(12, 'Paul', 'Rudd', 0, 1, 0);

-- --------------------------------------------------------

--
-- Struttura della tabella `recensioneserie`
--

CREATE TABLE `recensioneserie` (
  `idSerie` int(11) NOT NULL,
  `idUtente` int(11) NOT NULL,
  `idAdmin` int(11) NOT NULL,
  `testo` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struttura della tabella `recensionevideo`
--

CREATE TABLE `recensionevideo` (
  `idVideo` int(11) NOT NULL,
  `idUtente` int(11) NOT NULL,
  `idAdmin` int(11) NOT NULL,
  `testo` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struttura della tabella `saghe`
--

CREATE TABLE `saghe` (
  `id` int(11) NOT NULL,
  `nome` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `saghe`
--

INSERT INTO `saghe` (`id`, `nome`) VALUES
(1, 'Marvel Cinematic Universe'),
(2, 'The wizarding world');

-- --------------------------------------------------------

--
-- Struttura della tabella `serie`
--

CREATE TABLE `serie` (
  `id` int(11) NOT NULL,
  `nome` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struttura della tabella `utenti`
--

CREATE TABLE `utenti` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(32) NOT NULL,
  `admin` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `utenti`
--

INSERT INTO `utenti` (`id`, `username`, `email`, `password`, `admin`) VALUES
(1, 'username', 'email@email.com', '5f4dcc3b5aa765d61d8327deb882cf99', 0);

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
  `selettore` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `video`
--

INSERT INTO `video` (`id`, `nome`, `durata`, `idSaga`, `idSerie`, `numero`, `stagione`, `selettore`) VALUES
(1, 'iron Man', 126, 1, NULL, 1, NULL, 1),
(2, 'L\'incredibile Hulk', 135, 1, NULL, 2, NULL, 1),
(3, 'Iron Man 2', 124, 1, NULL, 3, NULL, 1),
(4, 'Thor', 115, 1, NULL, 4, NULL, 1),
(5, 'Captain America - Il primo Vendicatore', 124, 1, NULL, 5, NULL, 1),
(6, 'The Avengers', 143, 1, NULL, 6, NULL, 1),
(7, 'Iron Man 3', 131, 1, NULL, 7, NULL, 1),
(8, 'Thor: The Dark World', 112, 1, NULL, 8, NULL, 1),
(9, 'Captain America: The Winter Soldier', 136, 1, NULL, 9, NULL, 1),
(10, 'Guardiani della Galassia', 125, 1, NULL, 10, NULL, 1);

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
-- Indici per le tabelle `interpretazioni`
--
ALTER TABLE `interpretazioni`
  ADD PRIMARY KEY (`idAttore`,`idPersonaggio`),
  ADD KEY `idPersonaggio` (`idPersonaggio`);

--
-- Indici per le tabelle `partecipazioni`
--
ALTER TABLE `partecipazioni`
  ADD PRIMARY KEY (`idPersona`,`idVideo`),
  ADD KEY `idVideo` (`idVideo`);

--
-- Indici per le tabelle `personaggi`
--
ALTER TABLE `personaggi`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `persone`
--
ALTER TABLE `persone`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `recensioneserie`
--
ALTER TABLE `recensioneserie`
  ADD PRIMARY KEY (`idSerie`,`idUtente`),
  ADD KEY `idUtente` (`idUtente`),
  ADD KEY `idAdmin` (`idAdmin`);

--
-- Indici per le tabelle `recensionevideo`
--
ALTER TABLE `recensionevideo`
  ADD PRIMARY KEY (`idVideo`,`idUtente`),
  ADD KEY `idUtente` (`idUtente`),
  ADD KEY `idAdmin` (`idAdmin`);

--
-- Indici per le tabelle `saghe`
--
ALTER TABLE `saghe`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `serie`
--
ALTER TABLE `serie`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `utenti`
--
ALTER TABLE `utenti`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `video`
--
ALTER TABLE `video`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idSaga` (`idSaga`),
  ADD KEY `idSerie` (`idSerie`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `accessi`
--
ALTER TABLE `accessi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `curiositaserie`
--
ALTER TABLE `curiositaserie`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `curiositavideo`
--
ALTER TABLE `curiositavideo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `personaggi`
--
ALTER TABLE `personaggi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT per la tabella `persone`
--
ALTER TABLE `persone`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT per la tabella `saghe`
--
ALTER TABLE `saghe`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT per la tabella `serie`
--
ALTER TABLE `serie`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `utenti`
--
ALTER TABLE `utenti`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT per la tabella `video`
--
ALTER TABLE `video`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `accessi`
--
ALTER TABLE `accessi`
  ADD CONSTRAINT `accessi_ibfk_1` FOREIGN KEY (`idUtente`) REFERENCES `utenti` (`id`);

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
-- Limiti per la tabella `interpretazioni`
--
ALTER TABLE `interpretazioni`
  ADD CONSTRAINT `interpretazioni_ibfk_1` FOREIGN KEY (`idAttore`) REFERENCES `persone` (`id`),
  ADD CONSTRAINT `interpretazioni_ibfk_2` FOREIGN KEY (`idPersonaggio`) REFERENCES `personaggi` (`id`);

--
-- Limiti per la tabella `partecipazioni`
--
ALTER TABLE `partecipazioni`
  ADD CONSTRAINT `partecipazioni_ibfk_1` FOREIGN KEY (`idPersona`) REFERENCES `persone` (`id`),
  ADD CONSTRAINT `partecipazioni_ibfk_2` FOREIGN KEY (`idVideo`) REFERENCES `video` (`id`);

--
-- Limiti per la tabella `recensioneserie`
--
ALTER TABLE `recensioneserie`
  ADD CONSTRAINT `recensioneserie_ibfk_1` FOREIGN KEY (`idSerie`) REFERENCES `serie` (`id`),
  ADD CONSTRAINT `recensioneserie_ibfk_2` FOREIGN KEY (`idUtente`) REFERENCES `utenti` (`id`),
  ADD CONSTRAINT `recensioneserie_ibfk_3` FOREIGN KEY (`idAdmin`) REFERENCES `utenti` (`id`);

--
-- Limiti per la tabella `recensionevideo`
--
ALTER TABLE `recensionevideo`
  ADD CONSTRAINT `recensionevideo_ibfk_1` FOREIGN KEY (`idVideo`) REFERENCES `video` (`id`),
  ADD CONSTRAINT `recensionevideo_ibfk_2` FOREIGN KEY (`idUtente`) REFERENCES `utenti` (`id`),
  ADD CONSTRAINT `recensionevideo_ibfk_3` FOREIGN KEY (`idAdmin`) REFERENCES `utenti` (`id`);

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
