-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Хост: mariadb
-- Время создания: Дек 16 2024 г., 15:23
-- Версия сервера: 10.4.34-MariaDB-1:10.4.34+maria~ubu2004
-- Версия PHP: 8.2.23

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `dbphp`
--

-- --------------------------------------------------------

--
-- Структура таблицы `goods`
--

CREATE TABLE `goods` (
  `id` int(11) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `price` int(11) DEFAULT NULL,
  `type` varchar(30) DEFAULT NULL,
  `img` varchar(255) DEFAULT NULL,
  `info` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Дамп данных таблицы `goods`
--

INSERT INTO `goods` (`id`, `name`, `price`, `type`, `img`, `info`) VALUES
(1, 'i5 ', 450, 'CPU', '/imgs/img1', 'Описание товара'),
(2, 'Монитор', 650, 'Монитор', '/imgs/img2', 'Описание товара'),
(3, 'ryzen 5', 375, 'CPU', '/imgs/img3', 'Описание товара'),
(4, 'Mouse m86', 150, 'mouse', '/imgs/img4', 'Описание товара'),
(5, 'product31', 356, 'CPU', '/imgs/img5', 'Описание товара');

-- --------------------------------------------------------

--
-- Структура таблицы `orders`
--

CREATE TABLE `orders` (
  `id` int(16) NOT NULL,
  `user_id` int(16) NOT NULL,
  `date` datetime NOT NULL DEFAULT current_timestamp(),
  `address` varchar(16) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `status` varchar(16) NOT NULL DEFAULT 'create'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Дамп данных таблицы `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `date`, `address`, `status`) VALUES
(47, 2, '2024-12-12 16:15:48', '', 'in delivery'),
(48, 1, '2024-12-16 13:50:46', '', 'create');

-- --------------------------------------------------------

--
-- Структура таблицы `order_list`
--

CREATE TABLE `order_list` (
  `order_id` int(16) NOT NULL,
  `good_id` int(16) NOT NULL,
  `count` int(16) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Дамп данных таблицы `order_list`
--

INSERT INTO `order_list` (`order_id`, `good_id`, `count`) VALUES
(47, 3, 3),
(48, 2, 2);

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int(16) NOT NULL,
  `name` varchar(32) NOT NULL DEFAULT 'none',
  `login` varchar(16) NOT NULL,
  `password` varchar(64) NOT NULL,
  `role` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `name`, `login`, `password`, `role`) VALUES
(1, 'admin', 'admin', '$2y$10$Gk97NKS08RRTF1IPDwnfJ.SPguLqc395GVtBvCV71r4gpyHC3z5qW', 1),
(2, 'John', 'name09', '$2y$10$iopUmjicsmjZWEw5rDfDMuuDb3MRh02qdLfbBsFakgKnNi69AirDG', NULL),
(3, 'Karl', 'Login01', '$2y$10$iopUmjicsmjZWEw5rDfDMuuDb3MRh02qdLfbBsFakgKnNi69AirDG', NULL),
(4, 'Tuik', 'login091', '$2y$10$iopUmjicsmjZWEw5rDfDMuuDb3MRh02qdLfbBsFakgKnNi69AirDG', NULL),
(5, 'Daloi', 'login1023', '$2y$10$dyqFNzNFFqL5RDe9.lrZBOnMjOuPuQC9Hx6RFSZQ.x4EVFNznbT/e', NULL),
(10, 'none', 'Test', '$2y$10$.FCUks9bMpTqBmIdMfJX6.e9eORxow170n5y2Z04TL5bNpvU7bf9e', 0),
(11, 'none', 'test', '$2y$10$kp9aoRXd1aU.L4hEmD37u.LxNhi52r/guzeMI46U83oBtwEQVZV1u', 0),
(12, 'none', 'test', '$2y$10$Bw4Ul.lwCyhV9LYfHWKUP.muAXT8qlqISUZ7AX.STl7BumaOCsYJu', 0),
(13, 'none', 'login1', '$2y$10$1HySHEZO1eygQcXNqOmkPeT0icZizjjXyoF2YfRmVwyN4CPPQ.yUG', 0),
(14, 'none', 'Abc', '$2y$10$YPIOsv/VUXB1IukRfRgfUewxhLC/dtWoSbyOewKmfu50YojAqeKDO', 0);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `goods`
--
ALTER TABLE `goods`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Индексы таблицы `order_list`
--
ALTER TABLE `order_list`
  ADD KEY `order_list_ibfk_1` (`order_id`),
  ADD KEY `order_list_ibfk_2` (`good_id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `goods`
--
ALTER TABLE `goods`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT для таблицы `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(16) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int(16) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Ограничения внешнего ключа таблицы `order_list`
--
ALTER TABLE `order_list`
  ADD CONSTRAINT `order_list_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_list_ibfk_2` FOREIGN KEY (`good_id`) REFERENCES `goods` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
