-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Хост: mariadb
-- Время создания: Апр 01 2025 г., 14:59
-- Версия сервера: 10.4.34-MariaDB-1:10.4.34+maria~ubu2004
-- Версия PHP: 8.2.28

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
-- Структура таблицы `codes`
--

CREATE TABLE `codes` (
  `id` int(11) NOT NULL,
  `code` varchar(16) NOT NULL,
  `sale` int(11) NOT NULL,
  `id_city` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Дамп данных таблицы `codes`
--

INSERT INTO `codes` (`id`, `code`, `sale`, `id_city`) VALUES
(1, 'aBcDe1', 10, 1),
(2, 'FgHiJ2', 5, 2),
(3, 'kLmNo3', 15, 3),
(4, 'PqRsT4', 23, 4),
(5, 'uVwXy5', 9, 5),
(6, 'ZaBcD6', 14, 6),
(7, 'EfGhI7', 34, 7),
(8, 'JkLmN8', 50, 8),
(9, 'OpQrS9', 10, 9),
(10, 'TuVwX0', 18, 10);

-- --------------------------------------------------------

--
-- Структура таблицы `colors`
--

CREATE TABLE `colors` (
  `id` int(11) NOT NULL,
  `name` varchar(16) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Дамп данных таблицы `colors`
--

INSERT INTO `colors` (`id`, `name`) VALUES
(1, 'default'),
(2, 'green'),
(3, 'pink'),
(4, 'red');

-- --------------------------------------------------------

--
-- Структура таблицы `countries`
--

CREATE TABLE `countries` (
  `id` int(11) NOT NULL,
  `country` varchar(16) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Дамп данных таблицы `countries`
--

INSERT INTO `countries` (`id`, `country`) VALUES
(1, 'Russia'),
(2, 'UK'),
(3, 'France'),
(4, 'Japan'),
(5, 'US'),
(6, 'Germany'),
(7, 'Italy'),
(8, 'China'),
(9, 'Australia'),
(10, 'Canada');

-- --------------------------------------------------------

--
-- Структура таблицы `goods`
--

CREATE TABLE `goods` (
  `id` int(11) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `price` int(11) DEFAULT NULL,
  `img` varchar(255) DEFAULT NULL,
  `designer_id` int(11) NOT NULL,
  `brand_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `gender` varchar(16) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Дамп данных таблицы `goods`
--

INSERT INTO `goods` (`id`, `name`, `price`, `img`, `designer_id`, `brand_id`, `category_id`, `gender`) VALUES
(121, 'Socks', 100, 'item-2.jpg', 1, 1, 1, 'unisex'),
(165, 'Prod7', 909, 'item-8.jpg', 2, 2, 2, 'man'),
(265, 'Dress', 700, 'item-3.jpg', 3, 3, 3, 'woman'),
(371, 'Prod5', 980, 'item-8.jpg', 4, 4, 4, 'man'),
(372, 'Prod8', 987, 'item-8.jpg', 5, 5, 5, 'man'),
(421, 'Mango People T-shirt', 52, 'item-1.jpg', 6, 6, 6, 'unisex'),
(509, 'Boots', 1200, 'item-4.jpg', 7, 7, 7, 'woman'),
(566, 'Prod6', 990, 'item-8.jpg', 8, 8, 8, 'man'),
(634, 'Prod1', 901, 'item-6.jpg', 9, 9, 9, 'man'),
(722, 'Prod2', 950, 'item-7.jpg', 10, 10, 10, 'man'),
(819, 'Prod3', 120, 'item-8.jpg', 1, 1, 1, 'man'),
(875, 'Skirt', 950, 'item-5.jpg', 2, 2, 2, 'woman'),
(956, 'Prod4', 970, 'item-8.jpg', 3, 3, 3, 'man');

-- --------------------------------------------------------

--
-- Структура таблицы `goods_brands`
--

CREATE TABLE `goods_brands` (
  `id` int(11) NOT NULL,
  `name` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Дамп данных таблицы `goods_brands`
--

INSERT INTO `goods_brands` (`id`, `name`) VALUES
(1, 'Nike'),
(2, 'Adidas'),
(3, 'Puma'),
(4, 'Reebok'),
(5, 'Under Armour'),
(6, 'New Balance'),
(7, 'Levi\'s'),
(8, 'H&M'),
(9, 'Zara'),
(10, 'Uniqlo');

-- --------------------------------------------------------

--
-- Структура таблицы `goods_categories`
--

CREATE TABLE `goods_categories` (
  `id` int(11) NOT NULL,
  `name` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Дамп данных таблицы `goods_categories`
--

INSERT INTO `goods_categories` (`id`, `name`) VALUES
(1, 'Accessories'),
(2, 'Bags'),
(3, 'Denim'),
(4, 'Hoodies'),
(5, 'Jackets'),
(6, 'Pants'),
(7, 'Polos'),
(8, 'Shirts'),
(9, 'Shoes'),
(10, 'Shorts'),
(11, 'Sweaters'),
(12, 'T-Shirts'),
(13, 'Tanks');

-- --------------------------------------------------------

--
-- Структура таблицы `goods_designers`
--

CREATE TABLE `goods_designers` (
  `id` int(11) NOT NULL,
  `name` varchar(32) NOT NULL,
  `touch_count` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Дамп данных таблицы `goods_designers`
--

INSERT INTO `goods_designers` (`id`, `name`, `touch_count`) VALUES
(1, 'Virgil Abloh', 5),
(2, 'Kim Jones', 3),
(3, 'Riccardo Tisci', 1),
(4, 'Hedi Slimane', 6),
(5, 'Demna Gvasalia', 2),
(6, 'Phoebe Philo', 0),
(7, 'Rei Kawakubo', 0),
(8, 'Nicolas Ghesquiere', 0),
(9, 'Olivier Rousteing', 0),
(10, 'Jonathan Anderson', 0);

-- --------------------------------------------------------

--
-- Структура таблицы `orders`
--

CREATE TABLE `orders` (
  `id` int(16) NOT NULL,
  `user_id` int(16) NOT NULL,
  `date` datetime NOT NULL DEFAULT current_timestamp(),
  `status` varchar(16) NOT NULL DEFAULT 'created'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Дамп данных таблицы `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `date`, `status`) VALUES
(108, 18, '2025-02-19 14:33:36', 'created');

-- --------------------------------------------------------

--
-- Структура таблицы `order_address`
--

CREATE TABLE `order_address` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `country_id` int(11) NOT NULL,
  `city` varchar(32) NOT NULL,
  `address` varchar(32) NOT NULL,
  `zip` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Дамп данных таблицы `order_address`
--

INSERT INTO `order_address` (`id`, `order_id`, `country_id`, `city`, `address`, `zip`) VALUES
(33, 108, 1, 'asd', 'asd', '12');

-- --------------------------------------------------------

--
-- Структура таблицы `order_billing`
--

CREATE TABLE `order_billing` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `first` varchar(11) NOT NULL,
  `second` varchar(11) NOT NULL,
  `sur` varchar(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Дамп данных таблицы `order_billing`
--

INSERT INTO `order_billing` (`id`, `order_id`, `first`, `second`, `sur`) VALUES
(51, 108, 'asd', 'asd', 'asd');

-- --------------------------------------------------------

--
-- Структура таблицы `order_info`
--

CREATE TABLE `order_info` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `address_id` int(11) NOT NULL,
  `billing_id` int(11) NOT NULL,
  `shipping` varchar(32) NOT NULL,
  `sale` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Дамп данных таблицы `order_info`
--

INSERT INTO `order_info` (`id`, `order_id`, `address_id`, `billing_id`, `shipping`, `sale`) VALUES
(13, 108, 33, 51, 'car', 0);

-- --------------------------------------------------------

--
-- Структура таблицы `order_list`
--

CREATE TABLE `order_list` (
  `id` int(11) NOT NULL,
  `order_id` int(16) NOT NULL,
  `good_id` int(16) NOT NULL,
  `color` varchar(16) DEFAULT 'Default',
  `size` varchar(16) NOT NULL,
  `count` int(16) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Дамп данных таблицы `order_list`
--

INSERT INTO `order_list` (`id`, `order_id`, `good_id`, `color`, `size`, `count`) VALUES
(22, 108, 265, 'default', 'XXL', 3),
(23, 108, 165, 'default', 'XS', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `sizes`
--

CREATE TABLE `sizes` (
  `id` int(11) NOT NULL,
  `name` varchar(16) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Дамп данных таблицы `sizes`
--

INSERT INTO `sizes` (`id`, `name`) VALUES
(1, 'XXL'),
(2, 'XL'),
(3, 'L'),
(4, 'M'),
(5, 'S'),
(6, 'XS'),
(7, 'XXS');

-- --------------------------------------------------------

--
-- Структура таблицы `storage`
--

CREATE TABLE `storage` (
  `id` int(11) NOT NULL,
  `good_id` int(16) NOT NULL,
  `size_id` int(16) NOT NULL,
  `color_id` int(16) NOT NULL,
  `count` int(16) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Дамп данных таблицы `storage`
--

INSERT INTO `storage` (`id`, `good_id`, `size_id`, `color_id`, `count`) VALUES
(1, 121, 3, 3, 12),
(2, 421, 3, 1, 101),
(3, 875, 1, 2, 10),
(4, 875, 6, 4, 2),
(5, 875, 4, 2, 3),
(6, 875, 4, 4, 5),
(7, 121, 1, 1, 45),
(8, 121, 2, 2, 67),
(9, 121, 4, 4, 23),
(10, 819, 2, 3, 89),
(11, 819, 5, 1, 11),
(12, 819, 7, 4, 56),
(13, 165, 1, 2, 34),
(14, 165, 3, 3, 78),
(15, 165, 6, 1, 9),
(16, 875, 2, 1, 15),
(17, 875, 3, 2, 32),
(18, 875, 5, 3, 47),
(19, 265, 4, 4, 63),
(20, 265, 1, 1, 28),
(21, 265, 7, 2, 91),
(22, 956, 2, 3, 14),
(23, 956, 5, 4, 6),
(24, 956, 6, 1, 77),
(25, 371, 3, 2, 50),
(26, 371, 4, 3, 22),
(27, 371, 7, 4, 88),
(28, 372, 1, 1, 39),
(29, 372, 2, 4, 41),
(30, 372, 6, 3, 10),
(31, 421, 2, 2, 55),
(32, 421, 5, 3, 18),
(33, 421, 7, 1, 73),
(34, 509, 3, 4, 29),
(35, 509, 4, 1, 64),
(36, 509, 6, 2, 37),
(37, 566, 1, 3, 82),
(38, 566, 5, 4, 13),
(39, 566, 7, 1, 95),
(40, 634, 2, 2, 5),
(41, 634, 3, 1, 60),
(42, 634, 4, 4, 44),
(43, 722, 1, 1, 70),
(44, 722, 5, 3, 26),
(45, 722, 6, 2, 53),
(46, 819, 3, 2, 80);

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int(16) NOT NULL,
  `name` varchar(32) NOT NULL DEFAULT 'none',
  `login` varchar(32) NOT NULL,
  `password` varchar(64) NOT NULL,
  `role` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `name`, `login`, `password`, `role`) VALUES
(1, 'admin', 'admin', '$2y$10$iopUmjicsmjZWEw5rDfDMuuDb3MRh02qdLfbBsFakgKnNi69AirDG', 1),
(2, 'John', 'name09', '$2y$10$iopUmjicsmjZWEw5rDfDMuuDb3MRh02qdLfbBsFakgKnNi69AirDG', NULL),
(3, 'Karl', 'Login01', '$2y$10$iopUmjicsmjZWEw5rDfDMuuDb3MRh02qdLfbBsFakgKnNi69AirDG', NULL),
(4, 'Tuik', 'login091', '$2y$10$iopUmjicsmjZWEw5rDfDMuuDb3MRh02qdLfbBsFakgKnNi69AirDG', NULL),
(5, 'Daloi', 'login1023', '$2y$10$dyqFNzNFFqL5RDe9.lrZBOnMjOuPuQC9Hx6RFSZQ.x4EVFNznbT/e', NULL),
(10, 'none', 'Test', '$2y$10$.FCUks9bMpTqBmIdMfJX6.e9eORxow170n5y2Z04TL5bNpvU7bf9e', 0),
(11, 'none', 'test', '$2y$10$kp9aoRXd1aU.L4hEmD37u.LxNhi52r/guzeMI46U83oBtwEQVZV1u', 0),
(12, 'none', 'test', '$2y$10$Bw4Ul.lwCyhV9LYfHWKUP.muAXT8qlqISUZ7AX.STl7BumaOCsYJu', 0),
(13, 'none', 'login1', '$2y$10$1HySHEZO1eygQcXNqOmkPeT0icZizjjXyoF2YfRmVwyN4CPPQ.yUG', 0),
(14, 'none', 'Abc', '$2y$10$YPIOsv/VUXB1IukRfRgfUewxhLC/dtWoSbyOewKmfu50YojAqeKDO', 0),
(17, 'none', 'testUser', '$2y$10$we/z01aXeadjQ3XgC3jQVOOPU19mAEVyT61OCdOyM0wcBZ6wiCWi2', 0),
(18, 'none', 'TesUser1', '$2y$10$vhoD86Xs5xbALb.eWsY4Ru73g5Y9xL4JhIov6Djn06dIwpcCRy6WS', 0),
(19, 'none', 'TestUser12', '$2y$10$PvEPRjyPJgFHh1/5kgidrOHRPn/5svFu24Q.FZmh2taklFwxkwlS.', 0),
(20, 'none', 'testUser123', '$2y$10$q3cXeHkSo3kn8VG/wbnpL.LFNOhxfLEbq/tZYFg2f7hcKZcCq03Ba', 0);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `codes`
--
ALTER TABLE `codes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `city_id` (`id_city`);

--
-- Индексы таблицы `colors`
--
ALTER TABLE `colors`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `countries`
--
ALTER TABLE `countries`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `goods`
--
ALTER TABLE `goods`
  ADD PRIMARY KEY (`id`),
  ADD KEY `brand_id` (`brand_id`),
  ADD KEY `designer_id` (`designer_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Индексы таблицы `goods_brands`
--
ALTER TABLE `goods_brands`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `goods_categories`
--
ALTER TABLE `goods_categories`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `goods_designers`
--
ALTER TABLE `goods_designers`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Индексы таблицы `order_address`
--
ALTER TABLE `order_address`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_address_ibfk_2` (`country_id`),
  ADD KEY `order_address_ibfk_3` (`order_id`);

--
-- Индексы таблицы `order_billing`
--
ALTER TABLE `order_billing`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_billing_ibfk_1` (`order_id`);

--
-- Индексы таблицы `order_info`
--
ALTER TABLE `order_info`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_info_ibfk_1` (`address_id`),
  ADD KEY `order_info_ibfk_2` (`order_id`),
  ADD KEY `order_info_ibfk_3` (`billing_id`);

--
-- Индексы таблицы `order_list`
--
ALTER TABLE `order_list`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_list_ibfk_2` (`good_id`),
  ADD KEY `order_list_ibfk_1` (`order_id`);

--
-- Индексы таблицы `sizes`
--
ALTER TABLE `sizes`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `storage`
--
ALTER TABLE `storage`
  ADD PRIMARY KEY (`id`),
  ADD KEY `color_id` (`color_id`),
  ADD KEY `good_id` (`good_id`),
  ADD KEY `size_id` (`size_id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `codes`
--
ALTER TABLE `codes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT для таблицы `colors`
--
ALTER TABLE `colors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT для таблицы `countries`
--
ALTER TABLE `countries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT для таблицы `goods`
--
ALTER TABLE `goods`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=957;

--
-- AUTO_INCREMENT для таблицы `goods_brands`
--
ALTER TABLE `goods_brands`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT для таблицы `goods_categories`
--
ALTER TABLE `goods_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT для таблицы `goods_designers`
--
ALTER TABLE `goods_designers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT для таблицы `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(16) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=114;

--
-- AUTO_INCREMENT для таблицы `order_address`
--
ALTER TABLE `order_address`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT для таблицы `order_billing`
--
ALTER TABLE `order_billing`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT для таблицы `order_info`
--
ALTER TABLE `order_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT для таблицы `order_list`
--
ALTER TABLE `order_list`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT для таблицы `sizes`
--
ALTER TABLE `sizes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT для таблицы `storage`
--
ALTER TABLE `storage`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int(16) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `codes`
--
ALTER TABLE `codes`
  ADD CONSTRAINT `codes_ibfk_1` FOREIGN KEY (`id_city`) REFERENCES `countries` (`id`);

--
-- Ограничения внешнего ключа таблицы `goods`
--
ALTER TABLE `goods`
  ADD CONSTRAINT `goods_ibfk_1` FOREIGN KEY (`brand_id`) REFERENCES `goods_brands` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `goods_ibfk_2` FOREIGN KEY (`designer_id`) REFERENCES `goods_designers` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `goods_ibfk_3` FOREIGN KEY (`category_id`) REFERENCES `goods_categories` (`id`) ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Ограничения внешнего ключа таблицы `order_address`
--
ALTER TABLE `order_address`
  ADD CONSTRAINT `order_address_ibfk_2` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `order_address_ibfk_3` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `order_billing`
--
ALTER TABLE `order_billing`
  ADD CONSTRAINT `order_billing_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `order_info`
--
ALTER TABLE `order_info`
  ADD CONSTRAINT `order_info_ibfk_2` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `order_list`
--
ALTER TABLE `order_list`
  ADD CONSTRAINT `order_list_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `order_list_ibfk_2` FOREIGN KEY (`good_id`) REFERENCES `goods` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `storage`
--
ALTER TABLE `storage`
  ADD CONSTRAINT `storage_ibfk_1` FOREIGN KEY (`color_id`) REFERENCES `colors` (`id`),
  ADD CONSTRAINT `storage_ibfk_2` FOREIGN KEY (`good_id`) REFERENCES `goods` (`id`),
  ADD CONSTRAINT `storage_ibfk_3` FOREIGN KEY (`size_id`) REFERENCES `sizes` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
