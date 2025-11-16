-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Ноя 16 2025 г., 21:12
-- Версия сервера: 8.0.30
-- Версия PHP: 8.1.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `MealDB`
--

-- --------------------------------------------------------

--
-- Структура таблицы `Cart`
--

CREATE TABLE `Cart` (
  `id_cart` int NOT NULL,
  `id_product` int DEFAULT NULL,
  `id_product_category` int DEFAULT NULL,
  `id_user` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `Cart Product`
--

CREATE TABLE `Cart Product` (
  `id_cart_product` int NOT NULL,
  `id_product` int DEFAULT NULL,
  `id_cart` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `Category`
--

CREATE TABLE `Category` (
  `id_product_category` int NOT NULL,
  `name` varchar(45) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `Category`
--

INSERT INTO `Category` (`id_product_category`, `name`) VALUES
(1, 'Молочные изделия'),
(2, 'Хлебные изделия'),
(3, 'Сладости'),
(4, 'Фрукты'),
(5, 'Овощи'),
(6, 'Приправы'),
(7, 'Бакалея'),
(8, 'Полуфабрикаты'),
(9, 'Мясные изделия'),
(10, 'Напитки'),
(11, 'Консервы');

-- --------------------------------------------------------

--
-- Структура таблицы `Product`
--

CREATE TABLE `Product` (
  `id_product` int NOT NULL,
  `Name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `id_product_category` int DEFAULT NULL,
  `image` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `Product`
--

INSERT INTO `Product` (`id_product`, `Name`, `id_product_category`, `image`) VALUES
(2, 'Молоко \"Простоквашино\" 2,5', 1, '1.jpg'),
(3, 'Хлеб Бородинский', 2, 'product3.jpeg'),
(4, 'Активиа 260г', 1, 'product4.jpg'),
(5, 'Паста Nutella с добавлением какао ореховая 350г', 3, 'nutella.jpeg'),
(6, 'Драже M&M\'s c арахисом и молочным шоколадом 80г', 3, 'mms.jpeg');

-- --------------------------------------------------------

--
-- Структура таблицы `Product Store`
--

CREATE TABLE `Product Store` (
  `id_product_store` int NOT NULL,
  `price` decimal(10,0) NOT NULL,
  `id_product` int DEFAULT NULL,
  `id_store` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `Product Store`
--

INSERT INTO `Product Store` (`id_product_store`, `price`, `id_product`, `id_store`) VALUES
(1, '179', 2, 1),
(2, '159', 2, 2),
(3, '59', 3, 1),
(4, '59', 3, 2),
(5, '79', 3, 3),
(6, '159', 2, 3),
(7, '189', 2, 5),
(8, '179', 2, 6),
(9, '502', 5, 1),
(10, '489', 5, 3),
(11, '479', 5, 5),
(12, '129', 6, 6),
(13, '119', 6, 2),
(14, '129', 6, 6),
(15, '109', 6, 3);

-- --------------------------------------------------------

--
-- Структура таблицы `Store`
--

CREATE TABLE `Store` (
  `id_store` int NOT NULL,
  `store_name` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `Adress` varchar(45) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `Store`
--

INSERT INTO `Store` (`id_store`, `store_name`, `Adress`) VALUES
(1, 'Пятерочка', 'ул. Большая'),
(2, 'Магнит', 'ул. Маленькая'),
(3, 'Лента', 'ул. Сахарова 12'),
(4, 'Eurospar', 'ул. Петербургская 1'),
(5, 'Ашан', 'ул. Проспект Победы 91'),
(6, 'Перекрёсток', 'ул. Ибрагимова 56');

-- --------------------------------------------------------

--
-- Структура таблицы `Store Logos`
--

CREATE TABLE `Store Logos` (
  `id_image` int NOT NULL,
  `id_store` int DEFAULT NULL,
  `logo` varchar(50) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `Store Logos`
--

INSERT INTO `Store Logos` (`id_image`, `id_store`, `logo`) VALUES
(1, 1, '5_logo.jpg'),
(2, 2, 'm_logo.jpg'),
(3, 3, 'l_logo.jpg'),
(4, 4, 'e_logo.jpg'),
(5, 5, 'a_logo.jpg'),
(6, 6, 'pe_logo.jpg');

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id_user` int NOT NULL,
  `FirstName` varchar(45) COLLATE utf8mb4_general_ci NOT NULL,
  `SecondName` varchar(45) COLLATE utf8mb4_general_ci NOT NULL,
  `Email` varchar(45) COLLATE utf8mb4_general_ci NOT NULL,
  `Password` varchar(45) COLLATE utf8mb4_general_ci NOT NULL,
  `PhoneNumber` varchar(45) COLLATE utf8mb4_general_ci NOT NULL,
  `Gender` varchar(2) COLLATE utf8mb4_general_ci NOT NULL,
  `DateBirth` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id_user`, `FirstName`, `SecondName`, `Email`, `Password`, `PhoneNumber`, `Gender`, `DateBirth`) VALUES
(1, 'Иван', 'Иванов', 'ivanov@mail.ru', '123', '88005553536', 'М', '1995-11-01');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `Cart`
--
ALTER TABLE `Cart`
  ADD PRIMARY KEY (`id_cart`),
  ADD KEY `id_product` (`id_product`,`id_product_category`,`id_user`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_product_category` (`id_product_category`);

--
-- Индексы таблицы `Cart Product`
--
ALTER TABLE `Cart Product`
  ADD PRIMARY KEY (`id_cart_product`),
  ADD KEY `id_product` (`id_product`,`id_cart`),
  ADD KEY `id_cart` (`id_cart`);

--
-- Индексы таблицы `Category`
--
ALTER TABLE `Category`
  ADD PRIMARY KEY (`id_product_category`);

--
-- Индексы таблицы `Product`
--
ALTER TABLE `Product`
  ADD PRIMARY KEY (`id_product`),
  ADD KEY `id_product_category` (`id_product_category`);

--
-- Индексы таблицы `Product Store`
--
ALTER TABLE `Product Store`
  ADD PRIMARY KEY (`id_product_store`),
  ADD KEY `id_product` (`id_product`,`id_store`),
  ADD KEY `id_store` (`id_store`);

--
-- Индексы таблицы `Store`
--
ALTER TABLE `Store`
  ADD PRIMARY KEY (`id_store`);

--
-- Индексы таблицы `Store Logos`
--
ALTER TABLE `Store Logos`
  ADD PRIMARY KEY (`id_image`),
  ADD KEY `id_store` (`id_store`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `Cart`
--
ALTER TABLE `Cart`
  MODIFY `id_cart` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `Cart Product`
--
ALTER TABLE `Cart Product`
  MODIFY `id_cart_product` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `Category`
--
ALTER TABLE `Category`
  MODIFY `id_product_category` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT для таблицы `Product`
--
ALTER TABLE `Product`
  MODIFY `id_product` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT для таблицы `Product Store`
--
ALTER TABLE `Product Store`
  MODIFY `id_product_store` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT для таблицы `Store`
--
ALTER TABLE `Store`
  MODIFY `id_store` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT для таблицы `Store Logos`
--
ALTER TABLE `Store Logos`
  MODIFY `id_image` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `Cart`
--
ALTER TABLE `Cart`
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`),
  ADD CONSTRAINT `cart_ibfk_3` FOREIGN KEY (`id_product_category`) REFERENCES `Category` (`id_product_category`);

--
-- Ограничения внешнего ключа таблицы `Cart Product`
--
ALTER TABLE `Cart Product`
  ADD CONSTRAINT `cart product_ibfk_1` FOREIGN KEY (`id_product`) REFERENCES `Product` (`id_product`),
  ADD CONSTRAINT `cart product_ibfk_2` FOREIGN KEY (`id_cart`) REFERENCES `Cart` (`id_cart`);

--
-- Ограничения внешнего ключа таблицы `Product`
--
ALTER TABLE `Product`
  ADD CONSTRAINT `product_ibfk_1` FOREIGN KEY (`id_product_category`) REFERENCES `Category` (`id_product_category`);

--
-- Ограничения внешнего ключа таблицы `Product Store`
--
ALTER TABLE `Product Store`
  ADD CONSTRAINT `product store_ibfk_2` FOREIGN KEY (`id_product`) REFERENCES `Product` (`id_product`),
  ADD CONSTRAINT `product store_ibfk_3` FOREIGN KEY (`id_store`) REFERENCES `Store` (`id_store`);

--
-- Ограничения внешнего ключа таблицы `Store Logos`
--
ALTER TABLE `Store Logos`
  ADD CONSTRAINT `store logos_ibfk_1` FOREIGN KEY (`id_store`) REFERENCES `Store` (`id_store`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
