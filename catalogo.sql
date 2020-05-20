-- phpMyAdmin SQL Dump
-- version 4.8.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Creato il: Mag 20, 2020 alle 20:12
-- Versione del server: 10.1.31-MariaDB
-- Versione PHP: 7.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
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
  `dataOra` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
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
(14, 18);

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
(12, 10),
(22, 11),
(23, 12),
(26, 14),
(27, 13);

-- --------------------------------------------------------

--
-- Struttura della tabella `partecipazioni`
--

CREATE TABLE `partecipazioni` (
  `idPersona` int(11) NOT NULL,
  `idVideo` int(11) NOT NULL,
  `selettore` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `partecipazioni`
--

INSERT INTO `partecipazioni` (`idPersona`, `idVideo`, `selettore`) VALUES
(1, 1, 2),
(1, 3, 2),
(1, 6, 2),
(1, 7, 2),
(2, 5, 2),
(2, 6, 2),
(2, 9, 2),
(3, 6, 2),
(4, 4, 2),
(4, 6, 2),
(4, 8, 2),
(5, 3, 2),
(5, 6, 2),
(5, 9, 2),
(6, 4, 2),
(6, 6, 2),
(7, 10, 2),
(10, 1, 1),
(10, 1, 2),
(10, 1, 3),
(10, 3, 1),
(10, 3, 2),
(10, 3, 3),
(10, 6, 3),
(10, 7, 2),
(10, 7, 3),
(11, 2, 2),
(13, 2, 1),
(14, 4, 1),
(15, 5, 1),
(16, 6, 1),
(16, 6, 3),
(17, 7, 1),
(18, 8, 1),
(19, 9, 1),
(20, 9, 1),
(21, 10, 1),
(21, 10, 3),
(22, 11, 2),
(22, 12, 2),
(22, 13, 2),
(22, 14, 2),
(23, 11, 2),
(23, 12, 2),
(23, 13, 2),
(23, 14, 2),
(24, 11, 1),
(24, 12, 1),
(24, 13, 1),
(24, 14, 1),
(25, 11, 1),
(25, 12, 1),
(25, 13, 1),
(25, 14, 1),
(26, 15, 2),
(26, 16, 2),
(26, 17, 2),
(26, 18, 2),
(27, 15, 2),
(27, 16, 2),
(27, 17, 2),
(27, 18, 2),
(28, 15, 1),
(28, 16, 1),
(28, 17, 1),
(28, 18, 1),
(29, 15, 1),
(29, 16, 1),
(29, 17, 1),
(29, 18, 1);

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
(10, 'Ant-Man'),
(11, 'Il professore'),
(12, 'Tokyo'),
(13, 'John Reese'),
(14, 'Harold Finch');

-- --------------------------------------------------------

--
-- Struttura della tabella `persone`
--

CREATE TABLE `persone` (
  `id` int(11) NOT NULL,
  `nome` varchar(50) NOT NULL,
  `cognome` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
(29, 'Greg', 'Plageman');

-- --------------------------------------------------------

--
-- Struttura della tabella `recensioneserie`
--

CREATE TABLE `recensioneserie` (
  `idSerie` int(11) NOT NULL,
  `idUtente` int(11) NOT NULL,
  `voto` int(11) NOT NULL,
  `testo` varchar(255) DEFAULT NULL,
  `idAdmin` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struttura della tabella `recensionevideo`
--

CREATE TABLE `recensionevideo` (
  `idVideo` int(11) NOT NULL,
  `idUtente` int(11) NOT NULL,
  `voto` int(11) NOT NULL,
  `testo` varchar(255) DEFAULT NULL,
  `idAdmin` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `recensionevideo`
--

INSERT INTO `recensionevideo` (`idVideo`, `idUtente`, `voto`, `testo`, `idAdmin`) VALUES
(1, 1, 10, 'IRON MAN SEI IL MIO EROE! ', 5),
(1, 3, 10, 'Davvero un gran bel film! Scaricatelo dal corsaronero: https://ilcorsaronero.xyz/tor/95175/Iron_Man_2008_iTA_ENG_AC3_Bluray_1080p_Subs_x264_DSS', 3),
(1, 5, 9, 'Mi è piaciuto molto, ottimo da vedere mentre si mangia un bel piatto di polenta', 3),
(1, 6, 6, 'Non ho capito perchè non c&#39;era spider-man', 5),
(1, 7, 10, 'Tony ❤ Pepper', 5),
(2, 3, 10, 'Bello', 3),
(2, 4, 8, 'Non mi sembrava bello ma l&#39;ho rivalutato.', NULL),
(6, 1, 8, 'Gran bel film', NULL);

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
  `nome` varchar(50) NOT NULL,
  `sinossi` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `utenti`
--

INSERT INTO `utenti` (`id`, `username`, `email`, `password`, `admin`) VALUES
(1, 'username', 'email@email.com', '5f4dcc3b5aa765d61d8327deb882cf99', 0),
(3, 'grande capo', 'admin@admin.it', '21232f297a57a5a743894a0e4a801fc3', 1),
(4, 'utente banana', 'user@user.it', 'ee11cbb19052e40b07aac0ca060c23ee', 0),
(5, 'barcigabri', 'barcigabri@gmail.com', '20e8fe46be8f49c48ed4eb7e4f8ecdc7', 1),
(6, 'pippobordo99', 'bordognapippo99@gmail.com', 'c1d48f0d3617b304beafee8490591d6b', 1),
(7, 'paperino', 'donald@duck.com', 'bac2b77e0926723c6ddbcb81d7d5ff8d', 0);

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
  `sinossi` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `video`
--

INSERT INTO `video` (`id`, `nome`, `durata`, `idSaga`, `idSerie`, `numero`, `stagione`, `selettore`, `sinossi`) VALUES
(1, 'Iron Man', 126, 1, NULL, 1, NULL, 1, 'Dopo essere sopravvissuto ad un attacco inaspettato in territorio nemico, l\'industriale Tony Stark costruisce un\'armatura ad alta tecnologia e giura di proteggere il mondo nei panni di Iron Man.'),
(2, 'L\'incredibile Hulk', 135, 1, NULL, 2, NULL, 1, 'Bruce Banner era uno scienziato, ma un\'esposizione accidentale ai raggi gamma ha provocato una mutazione genetica e sconvolto la sua esistenza. Ogni qualvolta le emozioni lo assalgono, Bruce si trasforma in Hulk, mostro verde dalla forza smisurata.'),
(3, 'Iron Man 2', 124, 1, NULL, 3, NULL, 1, 'Ora che tutto il mondo conosce la vera identità di Iron Man, l\'inventore milionario Tony Stark deve affrontare le pressioni alla collaborazione da parte dei militari e stringere nuove alleanze per affrontare un potente nemico.'),
(4, 'Thor', 115, 1, NULL, 4, NULL, 1, 'Dopo che le sue azioni sconsiderate hanno riacceso un\'antica guerra, il dio nordico Thor viene spogliato dei propri poteri, scacciato dal regno di Asgard, e costretto a vivere tra gli umani.'),
(5, 'Captain America - Il primo Vendicatore', 124, 1, NULL, 5, NULL, 1, 'Steve è un giovanotto smilzo che vuole ad ogni costo arruolarsi nell\'esercito per combattere i nazisti. Putroppo viene regolarmente scartato, ma un giorno gli si presenta la possibilità di fare da cavia: gli iniettano così il siero del supersoldato'),
(6, 'The Avengers', 143, 1, NULL, 6, NULL, 1, 'I leggendari supereroi Iron Man, Hulk, Thor, Capitan America, Occhio di Falco e la Vedova Nera vengono reclutati da un\'agenzia governativa segreta per combattere un nemico inatteso che minaccia la sicurezza della Terra.'),
(7, 'Iron Man 3', 131, 1, NULL, 7, NULL, 1, 'Dopo aver salvato New York dalla distruzione ed essere rimasto da allora insonne e preoccupato, Tony Stark rimasto senza armatura deve lottare contro le sue paure interiori per sconfiggere il suo nuovo nemico, il Mandarino.'),
(8, 'Thor: The Dark World', 112, 1, NULL, 8, NULL, 1, 'Dal personaggio della Marvel, Thor si allea con il perfido Loki per salvare la Terra dei Nove Regni da un antico nemico nato prima ancora dell\'universo.'),
(9, 'Captain America: The Winter Soldier', 136, 1, NULL, 9, NULL, 1, 'Capitan America, Vedova Nera e un nuovo alleato, Falcon, affrontano un nemico inaspettato mentre lottano per far emergere alla luce del sole un complotto che mette a rischio il mondo intero.'),
(10, 'Guardiani della Galassia', 125, 1, NULL, 10, NULL, 1, 'Un avventuriero spaziale, Brash Peter Quill, diventa preda di alcuni cacciatori di taglie dopo aver rubato una sfera ambita dal potente Ronan. Per sfuggire alla morte, l\'uomo si allea con quattro improbabili compagni di avventura.'),
(11, ' Eseguire ciò che è stato concordato', 47, NULL, 1, 1, 1, 2, 'A ciascun componente della banda viene dato il nome di una città: Tokyo, narratrice della storia, il cui compagno è appena morto dopo uno scontro a fuoco per un furto; Mosca e Denver, padre e figlio; Berlino, eletto dal Professore a capo delle operazioni; Nairobi, l\'altra donna del gruppo; Río, giovane hacker; Helsinki e Oslo, cugini serbi'),
(12, 'Incoscenza leale', 41, NULL, 1, 2, 1, 2, 'Río è vivo, è solo ferito alla testa. Berlino, al telefono con il Professore, gli rivela che Tokyo e Río hanno una relazione, ma lei smentisce tutto. Río, invece, quando sono soli, conferma a Berlino che ama Tokyo.'),
(13, 'Le maschere sono finite', 43, NULL, 1, 1, 2, 2, 'La polizia e la Scientifica stanno passando al setaccio il casale di Toledo in cerca di tracce; il Professore, che ha accompagnato Raquel, è in auto, estremamente nervoso, ma trova un quaderno di Paula, la figlia di Raquel, in cui la maestra scrive alla madre delle sue preoccupazioni per un invito da parte di Alberto, il padre. Dentro all\'edificio vi sono moltissime prove, tattiche, impronte, DNA.'),
(14, 'Il capo del piano', 44, NULL, 1, 2, 2, 2, 'Berlino è in ostaggio di Tokyo con l\'aiuto di Río e Denver. Tokyo vuole sapere i dettagli del \"piano Chernobyl\", il piano da attuare solo in caso di emergenza, e per farlo lo tortura rompendo alcune boccette del suo farmaco e giocando alla roulette russa con lui. '),
(15, 'La macchina della conoscenza', 44, NULL, 2, 1, 1, 2, 'La serie inizia con un flashback in cui si vede in un letto John Reese insieme a una donna. Siamo nel 2011, Reese è un barbone di New York.'),
(16, 'Una voce dal passato', 44, NULL, 2, 2, 1, 2, 'Dopo aver salvato un uomo di nome Bill da alcuni sicari assoldati da sua moglie, Reese pedina Finch per scoprire più informazioni sul suo conto, quando quest\'ultimo chiama l\'ex agente per assegnarli a un\'altra missione.'),
(17, 'Il piano di emergenza', 42, NULL, 2, 1, 2, 2, 'Reese riceve una serie di parole apparentemente casuali dalla Macchina, che l\'ha contattato tramite un telefono pubblico. Dopo aver assegnato alla Carter il compito di indagare più approfonditamente sulla morte di Alicia Corwin, uccisa da Root durante il rapimento di Finch, egli capisce che le parole identificano tre differenti libri nella biblioteca di Finch.'),
(18, 'Cattivi geni', 42, NULL, 2, 2, 2, 2, 'Reese e Carter partono per il Texas sulle tracce di Hanna Frey, una ragazzina scomparsa molti anni prima. Nel frattempo, Root tiene prigionieri Finch e Weeks e tortura ed interroga quest’ultimo per sapere la posizione della Macchina, accusandolo di essere un \"codice malevolo\".');

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
  ADD PRIMARY KEY (`idPersona`,`idVideo`,`selettore`),
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
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT per la tabella `persone`
--
ALTER TABLE `persone`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT per la tabella `saghe`
--
ALTER TABLE `saghe`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT per la tabella `serie`
--
ALTER TABLE `serie`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT per la tabella `utenti`
--
ALTER TABLE `utenti`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT per la tabella `video`
--
ALTER TABLE `video`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

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
