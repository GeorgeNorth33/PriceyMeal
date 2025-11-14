-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Ноя 14 2025 г., 16:36
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
  `Product_id` int NOT NULL,
  `Product_category_id` int NOT NULL,
  `id_user` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `Cart Product`
--

CREATE TABLE `Cart Product` (
  `id_cart_product` int NOT NULL,
  `id_product` int NOT NULL,
  `id_cart` int NOT NULL
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
(2, 'Сладости'),
(3, 'Крупы');

-- --------------------------------------------------------

--
-- Структура таблицы `Product`
--

CREATE TABLE `Product` (
  `id_product` int NOT NULL,
  `Name` varchar(45) COLLATE utf8mb4_general_ci NOT NULL,
  `id_product_category` int NOT NULL,
  `id_category` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `Product Store`
--

CREATE TABLE `Product Store` (
  `id_product_store` int NOT NULL,
  `price` decimal(10,0) NOT NULL,
  `id_product` int NOT NULL,
  `id_store` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `Store`
--

CREATE TABLE `Store` (
  `id_store` int NOT NULL,
  `Retailer` varchar(45) COLLATE utf8mb4_general_ci NOT NULL,
  `Adress` varchar(45) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `Store`
--

INSERT INTO `Store` (`id_store`, `Retailer`, `Adress`) VALUES
(1, 'Пятерочка', 'ул. Большая'),
(2, 'Магнит', 'ул. Маленькая');

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
(1, 'Иван', 'Иванов', 'ivanov@mail.ru', '123', '88005553535', 'М', '1995-11-01');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `Cart`
--
ALTER TABLE `Cart`
  ADD PRIMARY KEY (`id_cart`);

--
-- Индексы таблицы `Cart Product`
--
ALTER TABLE `Cart Product`
  ADD PRIMARY KEY (`id_cart_product`);

--
-- Индексы таблицы `Category`
--
ALTER TABLE `Category`
  ADD PRIMARY KEY (`id_product_category`);

--
-- Индексы таблицы `Product`
--
ALTER TABLE `Product`
  ADD PRIMARY KEY (`id_product`);

--
-- Индексы таблицы `Product Store`
--
ALTER TABLE `Product Store`
  ADD PRIMARY KEY (`id_product_store`);

--
-- Индексы таблицы `Store`
--
ALTER TABLE `Store`
  ADD PRIMARY KEY (`id_store`);

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
  MODIFY `id_product_category` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT для таблицы `Product`
--
ALTER TABLE `Product`
  MODIFY `id_product` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `Product Store`
--
ALTER TABLE `Product Store`
  MODIFY `id_product_store` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `Store`
--
ALTER TABLE `Store`
  MODIFY `id_store` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
