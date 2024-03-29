-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Φιλοξενητής: localhost
-- Χρόνος δημιουργίας: 20 Ιαν 2021 στις 21:48:57
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
  `parent_id` varchar(9) NOT NULL,
  `age` tinyint(3) UNSIGNED NOT NULL,
  `category` enum('daycare','kindergarten','primary','secondary','special_school','disabled') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Άδειασμα δεδομένων του πίνακα `children`
--

INSERT INTO `children` (`parent_id`, `age`, `category`) VALUES
('123456789', 12, 'secondary'),
('240741129', 12, 'secondary');

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `companies`
--

CREATE TABLE `companies` (
  `afm` varchar(9) NOT NULL,
  `name` varchar(255) NOT NULL,
  `address` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Άδειασμα δεδομένων του πίνακα `companies`
--

INSERT INTO `companies` (`afm`, `name`, `address`) VALUES
('048919395', 'SOS BRIGADE S.A.', 'Παπαπάνου 8, Ηλιούπολη, Αττική, 76531'),
('333444563', 'Cellular Reasoning S.A.', NULL);

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `contact`
--

CREATE TABLE `contact` (
  `id` varchar(9) NOT NULL,
  `time` datetime NOT NULL DEFAULT current_timestamp(),
  `message` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Άδειασμα δεδομένων του πίνακα `contact`
--

INSERT INTO `contact` (`id`, `time`, `message`) VALUES
('304696340', '2021-01-20 21:19:59', 'Καλημέρα, έχω πρόβλημα με τους υπαλλήλους μου. Δεν σταματούν να μιλούν. Pls help.');

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `employee_info`
--

CREATE TABLE `employee_info` (
  `id` varchar(9) NOT NULL,
  `type` enum('leave','suspension','remote-work') NOT NULL,
  `from_date` date NOT NULL,
  `to_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Άδειασμα δεδομένων του πίνακα `employee_info`
--

INSERT INTO `employee_info` (`id`, `type`, `from_date`, `to_date`) VALUES
('123456789', 'leave', '2020-12-21', '2021-01-09'),
('123456789', 'leave', '2021-01-28', '2021-01-30'),
('123456789', 'remote-work', '2021-02-01', '2021-02-28'),
('240741129', 'remote-work', '2021-01-01', '2021-01-31'),
('485882945', 'suspension', '2021-01-01', '2021-01-31'),
('485882945', 'remote-work', '2020-12-01', '2020-12-31');

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `e_rendezvous`
--

CREATE TABLE `e_rendezvous` (
  `user_id` varchar(9) NOT NULL,
  `time` datetime NOT NULL,
  `reason` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Άδειασμα δεδομένων του πίνακα `e_rendezvous`
--

INSERT INTO `e_rendezvous` (`user_id`, `time`, `reason`) VALUES
('240741129', '2021-01-14 13:09:00', 'Το καίγομαι σου λέω...!'),
('304696340', '2021-01-15 17:00:00', 'Just testing, honestly');

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `special_leave`
--

CREATE TABLE `special_leave` (
  `parent_id` varchar(9) NOT NULL,
  `from_date` date NOT NULL,
  `to_date` date NOT NULL,
  `special_notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Άδειασμα δεδομένων του πίνακα `special_leave`
--

INSERT INTO `special_leave` (`parent_id`, `from_date`, `to_date`, `special_notes`) VALUES
('123456789', '2021-01-12', '2021-01-14', 'My name is everywhere.'),
('240741129', '2021-01-14', '2021-01-20', 'Υπερωρίες...');

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `users`
--

CREATE TABLE `users` (
  `afm` varchar(9) NOT NULL,
  `amka` varchar(11) DEFAULT NULL,
  `name` varchar(64) NOT NULL,
  `surname` varchar(64) NOT NULL,
  `registered` tinyint(1) NOT NULL DEFAULT 0,
  `email` varchar(64) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `phone` varchar(10) DEFAULT NULL,
  `children` tinyint(3) UNSIGNED DEFAULT NULL,
  `category` enum('employer','employee','unemployed') DEFAULT NULL,
  `company_id` varchar(9) DEFAULT NULL,
  `contract` enum('full-time','part-time') DEFAULT NULL,
  `role` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Άδειασμα δεδομένων του πίνακα `users`
--

INSERT INTO `users` (`afm`, `amka`, `name`, `surname`, `registered`, `email`, `password`, `phone`, `children`, `category`, `company_id`, `contract`, `role`) VALUES
('123456789', NULL, 'John', 'Smith', 0, 'john@example.com', NULL, NULL, 1, 'employee', '048919395', 'full-time', 'Υπάλληλος Γραφείου'),
('240741129', '01106100081', 'Αθανάσιος', 'Σπηλιωτόπουλος', 1, 'spilios@gmail.com', '$2y$10$IDSGenNkkfLmugqgohhZ2ez0DBEjIbpVFv38S0lNrO3CKSa4J9GCe', '6262626262', 1, 'employee', '333444563', 'full-time', 'Senior Developer'),
('304696340', '88888888888', 'Haruhi', 'Suzumiya', 1, 'haruhisuzu@yahoo.com', '$2y$10$6GR9qf8P/vKvawMY/aYmIuFILwld/GAvBSqVDgaKHMViwXhY57hWO', '', NULL, 'employer', '048919395', 'full-time', 'Διευθύντρια'),
('349008654', NULL, 'Yuki', 'Nagato', 0, 'yuki@sosbrigade.jp', NULL, '', NULL, 'employee', '048919395', 'full-time', 'Γραμματέας'),
('485882945', NULL, 'Suzuha', 'Amane', 0, 'johntitor@resistance.com', NULL, NULL, NULL, 'employee', '048919395', 'part-time', 'Πρακτική Εργασία');

--
-- Ευρετήρια για άχρηστους πίνακες
--

--
-- Ευρετήρια για πίνακα `children`
--
ALTER TABLE `children`
  ADD PRIMARY KEY (`parent_id`,`age`,`category`);

--
-- Ευρετήρια για πίνακα `companies`
--
ALTER TABLE `companies`
  ADD PRIMARY KEY (`afm`);

--
-- Ευρετήρια για πίνακα `contact`
--
ALTER TABLE `contact`
  ADD PRIMARY KEY (`id`,`time`);

--
-- Ευρετήρια για πίνακα `employee_info`
--
ALTER TABLE `employee_info`
  ADD PRIMARY KEY (`id`,`type`,`from_date`,`to_date`);

--
-- Ευρετήρια για πίνακα `e_rendezvous`
--
ALTER TABLE `e_rendezvous`
  ADD PRIMARY KEY (`user_id`,`time`);

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
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `company_id` (`company_id`);

--
-- Περιορισμοί για άχρηστους πίνακες
--

--
-- Περιορισμοί για πίνακα `children`
--
ALTER TABLE `children`
  ADD CONSTRAINT `children_ibfk_1` FOREIGN KEY (`parent_id`) REFERENCES `users` (`afm`);

--
-- Περιορισμοί για πίνακα `contact`
--
ALTER TABLE `contact`
  ADD CONSTRAINT `contact_ibfk_1` FOREIGN KEY (`id`) REFERENCES `users` (`afm`);

--
-- Περιορισμοί για πίνακα `employee_info`
--
ALTER TABLE `employee_info`
  ADD CONSTRAINT `employee_info_ibfk_1` FOREIGN KEY (`id`) REFERENCES `users` (`afm`);

--
-- Περιορισμοί για πίνακα `e_rendezvous`
--
ALTER TABLE `e_rendezvous`
  ADD CONSTRAINT `e_rendezvous_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`afm`);

--
-- Περιορισμοί για πίνακα `special_leave`
--
ALTER TABLE `special_leave`
  ADD CONSTRAINT `special_leave_ibfk_1` FOREIGN KEY (`parent_id`) REFERENCES `users` (`afm`);

--
-- Περιορισμοί για πίνακα `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`company_id`) REFERENCES `companies` (`afm`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
