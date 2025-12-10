-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3307
-- Время создания: Дек 10 2025 г., 16:54
-- Версия сервера: 5.7.39-log
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
  `id_cart` int(11) NOT NULL,
  `id_product` int(11) DEFAULT NULL,
  `id_product_category` int(11) DEFAULT NULL,
  `id_user` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Структура таблицы `Cart Product`
--

CREATE TABLE `Cart Product` (
  `id_cart_product` int(11) NOT NULL,
  `id_product` int(11) DEFAULT NULL,
  `id_cart` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Структура таблицы `Category`
--

CREATE TABLE `Category` (
  `id_product_category` int(11) NOT NULL,
  `name` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
(10, 'Вода и напитки'),
(11, 'Консервы'),
(12, 'Снеки и чипсы'),
(13, 'Рыба и морепродукты'),
(14, 'Замороженные продукты'),
(15, 'Готовая еда');

-- --------------------------------------------------------

--
-- Структура таблицы `Product`
--

CREATE TABLE `Product` (
  `id_product` int(11) NOT NULL,
  `Name` varchar(100) NOT NULL,
  `id_product_category` int(11) DEFAULT NULL,
  `image` varchar(50) NOT NULL,
  `calories` int(11) DEFAULT NULL,
  `proteins` decimal(5,2) DEFAULT NULL,
  `fats` decimal(5,2) DEFAULT NULL,
  `carbohydrates` decimal(5,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `Product`
--

INSERT INTO `Product` (`id_product`, `Name`, `id_product_category`, `image`, `calories`, `proteins`, `fats`, `carbohydrates`) VALUES
(2, 'Молоко \"Простоквашино\" 2,5', 1, '_milkcat.jpg', 60, '3.20', '3.60', '4.80'),
(3, 'Хлеб Бородинский', 2, 'product3.jpeg', 210, '6.50', '1.20', '42.00'),
(4, 'Активиа 260г', 1, 'product4.jpg', 71, '3.40', '3.20', '5.80'),
(5, 'Паста Nutella с добавлением какао ореховая 350г', 3, 'nutella.jpeg', 539, '6.00', '30.00', '57.00'),
(6, 'Драже M&M\'s c арахисом и молочным шоколадом 80г', 3, 'mms.jpeg', 492, '10.30', '23.90', '59.60'),
(7, 'Бананы Global Village', 4, 'banana.jpeg', 96, '1.10', '0.20', '21.80'),
(8, 'Завтрак Хрутка Duo шоколадный обогащенный кальцием', 7, 'khrutka.jpeg', 380, '7.50', '5.00', '75.00'),
(9, 'Макароны Barilla Капеллин п.1 450г', 7, 'barilla450.jpeg', 358, '12.50', '2.00', '70.00'),
(10, 'Драже Skittles 38г', 3, 'skittles.jpeg', 405, '0.50', '4.30', '90.50'),
(11, 'Стейк говяжий Русский мрамор Стриплойн Халяль охлажденный 250г', 9, 'product_steak_rus.jpeg', 137, '22.10', '5.50', '0.80'),
(12, 'Батончик Snickers Super 80г', 3, 'snickers.jpeg', 507, '9.50', '27.50', '56.50'),
(13, 'Сыр Село Зеленое Фермерский со сливками нарезка 50% БЗМЖ 130г', 1, 'cheese_greenfarm.jpeg', 360, '23.00', '29.00', '2.50'),
(14, 'Горошек Global Village зеленый из мозговых сортов 400г', 11, 'pea_globalvillage.jpeg', 73, '5.00', '0.20', '13.80'),
(15, 'Сухарики Хрусteam Багет томат-зелень 60г', 12, 'хрустим_багет.jpeg', 412, '9.20', '14.50', '64.80'),
(16, 'Шампиньоны Global Village королевские 300г', 5, 'champigons.jpeg', 27, '4.30', '1.00', '0.10'),
(17, 'Перец красный сладкий', 5, 'pepper.jpeg', 27, '1.30', '0.00', '5.30'),
(18, 'Чебупицца Курочка по-итальянски Горячая Штучка 250г', 8, 'chebupizza_it.jpeg', 245, '8.20', '11.50', '26.80'),
(19, 'Чебупицца Пепперони Горячая Штучка 250г', 8, 'chebupizza_pepper.jpeg', 255, '8.50', '12.00', '25.20'),
(20, 'Пельмени «Горячая штучка» Бульмени с говядиной и свининой, 700 г', 14, 'bulmens.jpeg', 235, '10.80', '11.20', '20.50'),
(21, 'Макароны Шебекинские рожок полубублик №202 450г', 7, 'sheben.jpeg', 348, '11.50', '1.30', '70.80'),
(22, 'Напиток Cool Cola газированный 330мл', 10, 'coolcola_can.jpeg', 42, '0.00', '0.00', '10.60'),
(23, 'Напиток Cool Cola газированный 1л', 10, 'coolcola_bottle.jpeg', 42, '0.00', '0.00', '10.60'),
(24, 'Напиток Coca-Cola Classic 330мл', 10, 'cocacola_can.jpeg', 42, '0.00', '0.00', '10.60'),
(25, 'Крабовые палочки Русское море охлажденные 200г', 13, 'crabsticks.jpeg', 88, '17.50', '1.00', '0.00'),
(26, 'Сок Добрый яблоко 1л', 10, 'Добрый_Яблоко.jpeg', 46, '0.40', '0.40', '10.00'),
(27, 'Вода Святой источник Спорт негазированная 750мл', 10, 'Источник_негазиров.jpeg', 0, '0.00', '0.00', '0.00'),
(28, 'Перец Kotanyi черный в мельнице 36г', 6, 'Kotanyi.jpeg', 255, '11.00', '9.00', '42.00'),
(29, 'Перец Kotanyi черный горошком 20г', 6, 'kotanyi_pack.jpeg', 255, '11.00', '9.00', '42.00'),
(30, 'Перец Русский аппетит черный молотый 50г', 6, 'rus_appetite.jpeg', 255, '11.00', '9.00', '42.00'),
(31, 'Чипсы картофельные Lay\'s Сметана и зелень 225г', 12, 'lays_sour cream.jpeg', 510, '6.00', '30.00', '57.00'),
(32, 'Попкорн Mixbar со вкусом сыра 85г', 12, 'popcorn_mixbar.jpeg', 512, '6.50', '31.00', '52.50'),
(33, 'Рыба масляная Fish House ломтики холодного копчения 100г', 13, 'fish_house.jpeg', 202, '17.50', '13.50', '1.00'),
(34, 'Кукуруза сахарная в початках вареная 1уп.', 5, 'corn.jpeg', 123, '4.20', '2.00', '22.80'),
(35, 'Морковь мытая упаковка 1кг', 5, 'corn.jpeg', 35, '1.30', '0.10', '7.20'),
(36, 'Апельсины фасованные', 4, 'orange.jpeg', 45, '0.90', '0.20', '10.30'),
(37, 'Яблоко Global Village красное', 4, 'apple_global_village_red.jpeg', 52, '0.30', '0.20', '13.80'),
(38, 'Хлеб Рижский Хлеб Бородинский 300г', 2, 'bread_rizhski.jpeg', 210, '6.50', '1.30', '41.00'),
(39, 'Хлеб Челны-Хлеб Цельнозерновой 300г', 2, 'bread_chelniy.jpeg', 242, '13.00', '3.60', '43.00'),
(40, 'Мука Makfa высшего сорта 2кг', 7, 'makfa_2kg.jpeg', 334, '10.30', '1.10', '70.90');

-- --------------------------------------------------------

--
-- Структура таблицы `Product Store`
--

CREATE TABLE `Product Store` (
  `id_product_store` int(11) NOT NULL,
  `price` decimal(10,0) NOT NULL,
  `id_product` int(11) DEFAULT NULL,
  `id_store` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `Product Store`
--

INSERT INTO `Product Store` (`id_product_store`, `price`, `id_product`, `id_store`) VALUES
(189, '179', 2, 1),
(190, '159', 2, 2),
(191, '169', 2, 3),
(192, '175', 2, 4),
(193, '189', 2, 5),
(194, '179', 2, 6),
(195, '59', 3, 1),
(196, '59', 3, 2),
(197, '65', 3, 3),
(198, '62', 3, 4),
(199, '67', 3, 5),
(200, '64', 3, 6),
(201, '89', 4, 1),
(202, '85', 4, 2),
(203, '92', 4, 3),
(204, '95', 4, 4),
(205, '87', 4, 5),
(206, '90', 4, 6),
(207, '502', 5, 1),
(208, '495', 5, 2),
(209, '489', 5, 3),
(210, '510', 5, 4),
(211, '479', 5, 5),
(212, '485', 5, 6),
(213, '125', 6, 1),
(214, '119', 6, 2),
(215, '115', 6, 3),
(216, '122', 6, 4),
(217, '118', 6, 5),
(218, '129', 6, 6),
(219, '148', 7, 1),
(220, '145', 7, 2),
(221, '139', 7, 3),
(222, '142', 7, 4),
(223, '179', 7, 5),
(224, '148', 7, 6),
(225, '45', 8, 1),
(226, '42', 8, 2),
(227, '48', 8, 3),
(228, '50', 8, 4),
(229, '43', 8, 5),
(230, '46', 8, 6),
(231, '95', 9, 1),
(232, '100', 9, 2),
(233, '110', 9, 3),
(234, '105', 9, 4),
(235, '90', 9, 5),
(236, '98', 9, 6),
(237, '65', 10, 1),
(238, '62', 10, 2),
(239, '68', 10, 3),
(240, '70', 10, 4),
(241, '63', 10, 5),
(242, '66', 10, 6),
(243, '450', 11, 1),
(244, '430', 11, 2),
(245, '470', 11, 3),
(246, '490', 11, 4),
(247, '440', 11, 5),
(248, '460', 11, 6),
(249, '55', 12, 1),
(250, '52', 12, 2),
(251, '58', 12, 3),
(252, '60', 12, 4),
(253, '53', 12, 5),
(254, '56', 12, 6),
(255, '189', 13, 1),
(256, '179', 13, 2),
(257, '199', 13, 3),
(258, '209', 13, 4),
(259, '185', 13, 5),
(260, '195', 13, 6),
(261, '85', 14, 1),
(262, '79', 14, 2),
(263, '89', 14, 3),
(264, '92', 14, 4),
(265, '82', 14, 5),
(266, '87', 14, 6),
(267, '45', 15, 1),
(268, '42', 15, 2),
(269, '48', 15, 3),
(270, '50', 15, 4),
(271, '43', 15, 5),
(272, '46', 15, 6),
(273, '120', 16, 1),
(274, '115', 16, 2),
(275, '125', 16, 3),
(276, '130', 16, 4),
(277, '118', 16, 5),
(278, '122', 16, 6),
(279, '85', 17, 1),
(280, '79', 17, 2),
(281, '89', 17, 3),
(282, '92', 17, 4),
(283, '82', 17, 5),
(284, '87', 17, 6),
(285, '207', 18, 1),
(286, '195', 18, 2),
(287, '242', 18, 3),
(288, '190', 18, 4),
(289, '179', 18, 5),
(290, '200', 18, 6),
(291, '199', 19, 1),
(292, '189', 19, 2),
(293, '199', 19, 3),
(294, '195', 19, 4),
(295, '205', 19, 5),
(296, '192', 19, 6),
(297, '339', 20, 1),
(298, '325', 20, 2),
(299, '334', 20, 3),
(300, '315', 20, 4),
(301, '263', 20, 5),
(302, '399', 20, 6),
(303, '65', 21, 1),
(304, '62', 21, 2),
(305, '68', 21, 3),
(306, '70', 21, 4),
(307, '63', 21, 5),
(308, '66', 21, 6),
(309, '45', 22, 1),
(310, '42', 22, 2),
(311, '48', 22, 3),
(312, '50', 22, 4),
(313, '43', 22, 5),
(314, '46', 22, 6),
(315, '65', 23, 1),
(316, '62', 23, 2),
(317, '68', 23, 3),
(318, '70', 23, 4),
(319, '63', 23, 5),
(320, '66', 23, 6),
(321, '95', 24, 1),
(322, '92', 24, 2),
(323, '98', 24, 3),
(324, '105', 24, 4),
(325, '93', 24, 5),
(326, '96', 24, 6),
(327, '120', 25, 1),
(328, '115', 25, 2),
(329, '125', 25, 3),
(330, '130', 25, 4),
(331, '118', 25, 5),
(332, '122', 25, 6),
(333, '95', 26, 1),
(334, '89', 26, 2),
(335, '99', 26, 3),
(336, '102', 26, 4),
(337, '92', 26, 5),
(338, '97', 26, 6),
(339, '45', 27, 1),
(340, '42', 27, 2),
(341, '48', 27, 3),
(342, '50', 27, 4),
(343, '43', 27, 5),
(344, '46', 27, 6),
(345, '249', 28, 1),
(346, '239', 28, 2),
(347, '259', 28, 3),
(348, '269', 28, 4),
(349, '235', 28, 5),
(350, '245', 28, 6),
(351, '129', 29, 1),
(352, '119', 29, 2),
(353, '139', 29, 3),
(354, '149', 29, 4),
(355, '115', 29, 5),
(356, '125', 29, 6),
(357, '89', 30, 1),
(358, '79', 30, 2),
(359, '99', 30, 3),
(360, '109', 30, 4),
(361, '75', 30, 5),
(362, '85', 30, 6),
(363, '199', 31, 1),
(364, '189', 31, 2),
(365, '209', 31, 3),
(366, '219', 31, 4),
(367, '185', 31, 5),
(368, '195', 31, 6),
(369, '129', 32, 1),
(370, '119', 32, 2),
(371, '139', 32, 3),
(372, '149', 32, 4),
(373, '115', 32, 5),
(374, '125', 32, 6),
(375, '299', 33, 1),
(376, '289', 33, 2),
(377, '309', 33, 3),
(378, '319', 33, 4),
(379, '285', 33, 5),
(380, '295', 33, 6),
(381, '149', 34, 1),
(382, '139', 34, 2),
(383, '159', 34, 3),
(384, '169', 34, 4),
(385, '135', 34, 5),
(386, '145', 34, 6),
(387, '99', 35, 1),
(388, '89', 35, 2),
(389, '109', 35, 3),
(390, '119', 35, 4),
(391, '85', 35, 5),
(392, '95', 35, 6),
(393, '189', 36, 1),
(394, '179', 36, 2),
(395, '199', 36, 3),
(396, '209', 36, 4),
(397, '175', 36, 5),
(398, '185', 36, 6),
(399, '149', 37, 1),
(400, '139', 37, 2),
(401, '159', 37, 3),
(402, '169', 37, 4),
(403, '135', 37, 5),
(404, '145', 37, 6),
(405, '89', 38, 1),
(406, '79', 38, 2),
(407, '99', 38, 3),
(408, '109', 38, 4),
(409, '75', 38, 5),
(410, '85', 38, 6),
(411, '99', 39, 1),
(412, '89', 39, 2),
(413, '109', 39, 3),
(414, '119', 39, 4),
(415, '85', 39, 5),
(416, '95', 39, 6),
(417, '199', 40, 1),
(418, '189', 40, 2),
(419, '209', 40, 3),
(420, '219', 40, 4),
(421, '185', 40, 5),
(422, '195', 40, 6);

-- --------------------------------------------------------

--
-- Структура таблицы `Store`
--

CREATE TABLE `Store` (
  `id_store` int(11) NOT NULL,
  `store_name` varchar(45) NOT NULL,
  `Adress` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
  `id_image` int(11) NOT NULL,
  `id_store` int(11) DEFAULT NULL,
  `logo` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
-- Структура таблицы `UserFavorites`
--

CREATE TABLE `UserFavorites` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `UserFavorites`
--

INSERT INTO `UserFavorites` (`id`, `user_id`, `product_id`, `created_at`) VALUES
(5, 2, 9, '2025-11-29 20:31:55'),
(6, 2, 7, '2025-11-29 20:32:28'),
(7, 2, 10, '2025-11-30 14:59:12'),
(13, 13, 19, '2025-11-30 19:59:39'),
(18, 13, 8, '2025-12-07 12:43:03'),
(21, 14, 7, '2025-12-07 15:59:53'),
(22, 13, 24, '2025-12-07 17:05:54'),
(23, 13, 20, '2025-12-07 17:06:20'),
(24, 13, 12, '2025-12-07 17:06:32');

-- --------------------------------------------------------

--
-- Структура таблицы `UserPreferences`
--

CREATE TABLE `UserPreferences` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `interests` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `UserRecommendations`
--

CREATE TABLE `UserRecommendations` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `reason` text COLLATE utf8mb4_unicode_ci,
  `ranking` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id_user` int(11) NOT NULL,
  `FirstName` varchar(45) NOT NULL,
  `SecondName` varchar(45) NOT NULL,
  `Email` varchar(45) NOT NULL,
  `Password` varchar(45) NOT NULL,
  `PhoneNumber` varchar(45) NOT NULL,
  `Gender` varchar(2) NOT NULL,
  `DateBirth` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id_user`, `FirstName`, `SecondName`, `Email`, `Password`, `PhoneNumber`, `Gender`, `DateBirth`) VALUES
(2, 'Ростислав', 'Салахов', 'salakhovrost@bk.ru', '404', 'Не указано', 'М', '2003-02-19'),
(4, 'Даниил', 'Никешин', 'superroblox@gmail.com', '404', '', 'М', '2003-04-23'),
(13, 'Иван', 'Иванов', 'ivanov@mail.ru', '123', '', 'М', '1990-01-01'),
(14, 'Даниил', 'Никешин', 'roblox2003@mail.ru', 'bruh', '', 'М', '2003-04-23');

-- --------------------------------------------------------

--
-- Структура таблицы `UserViewHistory`
--

CREATE TABLE `UserViewHistory` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `viewed_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
-- Индексы таблицы `UserFavorites`
--
ALTER TABLE `UserFavorites`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_user_product` (`user_id`,`product_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Индексы таблицы `UserPreferences`
--
ALTER TABLE `UserPreferences`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_user` (`user_id`);

--
-- Индексы таблицы `UserRecommendations`
--
ALTER TABLE `UserRecommendations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `idx_user_ranking` (`user_id`,`ranking`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`);

--
-- Индексы таблицы `UserViewHistory`
--
ALTER TABLE `UserViewHistory`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `idx_user_view` (`user_id`,`viewed_at`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `Cart`
--
ALTER TABLE `Cart`
  MODIFY `id_cart` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `Cart Product`
--
ALTER TABLE `Cart Product`
  MODIFY `id_cart_product` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `Category`
--
ALTER TABLE `Category`
  MODIFY `id_product_category` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT для таблицы `Product`
--
ALTER TABLE `Product`
  MODIFY `id_product` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT для таблицы `Product Store`
--
ALTER TABLE `Product Store`
  MODIFY `id_product_store` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=423;

--
-- AUTO_INCREMENT для таблицы `Store`
--
ALTER TABLE `Store`
  MODIFY `id_store` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT для таблицы `Store Logos`
--
ALTER TABLE `Store Logos`
  MODIFY `id_image` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT для таблицы `UserFavorites`
--
ALTER TABLE `UserFavorites`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT для таблицы `UserPreferences`
--
ALTER TABLE `UserPreferences`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `UserRecommendations`
--
ALTER TABLE `UserRecommendations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT для таблицы `UserViewHistory`
--
ALTER TABLE `UserViewHistory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

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

--
-- Ограничения внешнего ключа таблицы `UserFavorites`
--
ALTER TABLE `UserFavorites`
  ADD CONSTRAINT `userfavorites_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id_user`) ON DELETE CASCADE,
  ADD CONSTRAINT `userfavorites_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `Product` (`id_product`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `UserPreferences`
--
ALTER TABLE `UserPreferences`
  ADD CONSTRAINT `userpreferences_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id_user`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `UserRecommendations`
--
ALTER TABLE `UserRecommendations`
  ADD CONSTRAINT `userrecommendations_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id_user`) ON DELETE CASCADE,
  ADD CONSTRAINT `userrecommendations_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `Product` (`id_product`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `UserViewHistory`
--
ALTER TABLE `UserViewHistory`
  ADD CONSTRAINT `userviewhistory_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id_user`) ON DELETE CASCADE,
  ADD CONSTRAINT `userviewhistory_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `Product` (`id_product`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
