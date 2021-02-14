-- phpMyAdmin SQL Dump
-- version 4.9.3
-- https://www.phpmyadmin.net/
--
-- Hostiteľ: localhost:8889
-- Čas generovania: Št 08.Okt 2020, 20:38
-- Verzia serveru: 5.7.26
-- Verzia PHP: 7.3.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Databáza: `frioblog`
--

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `name` varchar(250) NOT NULL,
  `email` varchar(250) NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` longtext NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Sťahujem dáta pre tabuľku `posts`
--

INSERT INTO `posts` (`id`, `title`, `content`, `created_at`, `user_id`) VALUES
(34, 'fr3f43r43', 'gfdsgfdsgfdsg', '2020-10-07 18:34:20', 1),
(35, '32432', '4324', '2020-10-07 18:38:38', 1),
(36, '213', 'fr', '2020-10-07 18:38:45', 1),
(37, 'hjdjfj', 'hhjfgfd\n', '2020-10-07 18:58:41', 1),
(38, 'fdsf', 'dsfds', '2020-10-07 18:59:20', 1),
(51, 'admin', 'admin\n', '2020-10-08 09:37:58', 1);

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `content` longtext NOT NULL,
  `price` float NOT NULL,
  `description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Sťahujem dáta pre tabuľku `products`
--

INSERT INTO `products` (`id`, `name`, `content`, `price`, `description`) VALUES
(1, 'Product1', 'Rjdsjlfkds', 22, 'Perex perex perex'),
(3, 'fgdnjkghfkjd', 'fdsfdsfa', 333.444, 'hfkjdhskjhf'),
(4, 'SuperDuper product', 'Popis a obsah v jednom', 23.45, 'superProduct'),
(5, 'Pridany', 'fdkjgkfjdsg', 222.4, 'mfkldjg'),
(6, 'Produkt2343', 'fdsfdsf', 123.54, 'rfdsfd sfjk djskaf jkdsa');

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `login` varchar(255) NOT NULL,
  `fullname` varchar(255) NOT NULL,
  `password` text NOT NULL,
  `role` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Sťahujem dáta pre tabuľku `users`
--

INSERT INTO `users` (`id`, `login`, `fullname`, `password`, `role`) VALUES
(1, 'admin', 'Administrator', '$2y$12$y2s0EIO9QQKzukKy4ZzBa.r1d5XJU2p79MsnN9vejSLt1.68UOGLq', 'admin'),
(2, 'marcel', 'Marcel Vrana', '$2y$12$Q7MMm628S4O1Xugx6xPkN.cZG2LBH8fNKA9OBz3AdiCn/H054ipgi', 'creator');

--
-- Kľúče pre exportované tabuľky
--

--
-- Indexy pre tabuľku `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `comments_ibfk_1` (`post_id`);

--
-- Indexy pre tabuľku `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `users` (`user_id`);

--
-- Indexy pre tabuľku `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexy pre tabuľku `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `login` (`login`);

--
-- AUTO_INCREMENT pre exportované tabuľky
--

--
-- AUTO_INCREMENT pre tabuľku `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pre tabuľku `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT pre tabuľku `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pre tabuľku `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Obmedzenie pre exportované tabuľky
--

--
-- Obmedzenie pre tabuľku `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE;

--
-- Obmedzenie pre tabuľku `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
