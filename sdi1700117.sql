-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Φιλοξενητής: localhost
-- Χρόνος δημιουργίας: 10 Ιαν 2021 στις 02:29:04
-- Έκδοση διακομιστή: 10.4.16-MariaDB
-- Έκδοση PHP: 7.4.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Βάση δεδομένων: `sdi1700117`
--

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `children`
--

CREATE TABLE `children` (
  `parent_id` int(9) NOT NULL,
  `age` tinyint(3) UNSIGNED NOT NULL,
  `category` tinyint(3) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Άδειασμα δεδομένων του πίνακα `children`
--

INSERT INTO `children` (`parent_id`, `age`, `category`) VALUES
(123456789, 12, 4);

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `special_leave`
--

CREATE TABLE `special_leave` (
  `parent_id` int(9) NOT NULL,
  `from_date` date NOT NULL,
  `to_date` date NOT NULL,
  `special_notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Άδειασμα δεδομένων του πίνακα `special_leave`
--

INSERT INTO `special_leave` (`parent_id`, `from_date`, `to_date`, `special_notes`) VALUES
(123456789, '2021-01-12', '2021-01-14', 'lmao');

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `users`
--

CREATE TABLE `users` (
  `afm` int(9) NOT NULL,
  `amka` int(11) DEFAULT NULL,
  `name` varchar(64) NOT NULL,
  `surname` varchar(64) NOT NULL,
  `registered` tinyint(1) NOT NULL,
  `email` varchar(64) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `children` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Άδειασμα δεδομένων του πίνακα `users`
--

INSERT INTO `users` (`afm`, `amka`, `name`, `surname`, `registered`, `email`, `password`, `children`) VALUES
(123456789, NULL, 'bruh', 'brotherhood', 0, NULL, NULL, 1),
(240741129, NULL, 'Αθανάσιος', 'Σπηλιωτόπουλος', 1, 'spilios@gmail.com', '$2y$10$IDSGenNkkfLmugqgohhZ2ez0DBEjIbpVFv38S0lNrO3CKSa4J9GCe', 0),
(304696340, NULL, 'Haruhi', 'Suzumiya', 1, 'haruhisuzu@yahoo.com', '$2y$10$.RVuwu.qqyqb2B.tEtpUx.CRawLWj76PKlbOw6By3p94eM9/anR0S', 0);

--
-- Ευρετήρια για άχρηστους πίνακες
--

--
-- Ευρετήρια για πίνακα `children`
--
ALTER TABLE `children`
  ADD PRIMARY KEY (`parent_id`,`age`,`category`);

--
-- Ευρετήρια για πίνακα `special_leave`
--
ALTER TABLE `special_leave`
  ADD PRIMARY KEY (`parent_id`,`from_date`,`to_date`);

--
-- Ευρετήρια για πίνακα `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`afm`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Περιορισμοί για άχρηστους πίνακες
--

--
-- Περιορισμοί για πίνακα `children`
--
ALTER TABLE `children`
  ADD CONSTRAINT `children_ibfk_1` FOREIGN KEY (`parent_id`) REFERENCES `users` (`afm`);

--
-- Περιορισμοί για πίνακα `special_leave`
--
ALTER TABLE `special_leave`
  ADD CONSTRAINT `special_leave_ibfk_1` FOREIGN KEY (`parent_id`) REFERENCES `users` (`afm`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
