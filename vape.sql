-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1
-- Время создания: Ноя 29 2018 г., 19:14
-- Версия сервера: 5.7.14
-- Версия PHP: 7.0.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `vape`
--

DELIMITER $$
--
-- Функции
--
CREATE DEFINER=`root`@`localhost` FUNCTION `calc_cost` (`COST` DECIMAL(7,2), `MIN_COST` DECIMAL(7,2), `GUEST_DISCOUNT` TINYINT, `ORDER_DISCOUNT` TINYINT) RETURNS DECIMAL(7,2) BEGIN
	RETURN COALESCE(get_min(MIN_COST,COST * (1 - 0.01 * GUEST_DISCOUNT) * (1 - 0.01 * ORDER_DISCOUNT)),0);
END$$

CREATE DEFINER=`root`@`localhost` FUNCTION `calc_cost_by_id` (`ID` INT, `GUEST_DISCOUNT` TINYINT, `ORDER_DISCOUNT` TINYINT) RETURNS DECIMAL(7,2) BEGIN
	RETURN (SELECT calc_cost(d_cost,d_min_cost,GUEST_DISCOUNT,ORDER_DISCOUNT) FROM dishes WHERE d_id = ID);
END$$

CREATE DEFINER=`root`@`localhost` FUNCTION `get_min` (`MINIMAL` DECIMAL(7,2), `CURRENT` DECIMAL(7,2)) RETURNS DECIMAL(7,2) BEGIN
	RETURN IF(MINIMAL >= CURRENT,MINIMAL,CURRENT);
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Структура таблицы `discounts`
--

CREATE TABLE `discounts` (
  `ds_id` int(11) NOT NULL,
  `ds_number` int(11) NOT NULL,
  `ds_fio` text NOT NULL,
  `ds_value` int(3) NOT NULL DEFAULT '0',
  `ds_state` int(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `discounts`
--

INSERT INTO `discounts` (`ds_id`, `ds_number`, `ds_fio`, `ds_value`, `ds_state`) VALUES
(1, 1, 'Пупкин Василий Иванович', 15, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `dishes`
--

CREATE TABLE `dishes` (
  `d_id` int(11) NOT NULL,
  `d_type` int(1) NOT NULL DEFAULT '1',
  `d_name` text NOT NULL,
  `d_min_cost` float(7,2) NOT NULL DEFAULT '0.00',
  `d_cost` float(7,2) NOT NULL DEFAULT '0.00',
  `d_state` int(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `dishes`
--

INSERT INTO `dishes` (`d_id`, `d_type`, `d_name`, `d_min_cost`, `d_cost`, `d_state`) VALUES
(1, 1, 'Цитрусовый с маракуей', 0.00, 200.00, 1),
(2, 1, 'Персиковый', 0.00, 200.00, 1),
(3, 1, 'Грушевый с жасмином', 0.00, 200.00, 1),
(4, 1, 'Яблочный сбитень ', 0.00, 200.00, 1),
(5, 1, 'Облепиховый с яблоком', 200.00, 200.00, 1),
(6, 1, 'Согревающий и острый', 200.00, 200.00, 1),
(7, 1, 'Зелёный цитрусовый', 200.00, 200.00, 1),
(8, 1, 'Цитрусовый с маракуей ', 200.00, 200.00, 1),
(9, 2, 'Ягодный черный смородиновый', 200.00, 200.00, 1),
(10, 2, 'Ромашковый', 200.00, 200.00, 1),
(11, 2, 'Огуречно-базиличный', 200.00, 200.00, 1),
(12, 2, 'Тархун', 20.00, 200.00, 1),
(13, 2, 'Кокосовый', 200.00, 200.00, 1),
(14, 2, 'Мандариновый', 200.00, 200.00, 1),
(15, 2, 'Киви', 200.00, 200.00, 1),
(16, 3, 'Шоколадный', 200.00, 200.00, 1),
(17, 3, 'Клубничный', 200.00, 200.00, 1),
(18, 3, 'Шоколадный брауни', 200.00, 200.00, 1),
(19, 3, 'Сникерс', 200.00, 200.00, 1),
(20, 4, 'Кремовый банан', 150.00, 150.00, 1),
(21, 4, 'Ягодный', 150.00, 150.00, 1),
(22, 4, 'Черная смородина', 150.00, 150.00, 1),
(23, 4, 'Пеликан', 150.00, 150.00, 1),
(24, 4, 'Огуречный', 150.00, 150.00, 1),
(25, 5, 'Лате/ капучино ', 100.00, 100.00, 1),
(26, 5, 'Двойной капучино/гранд лате  ', 120.00, 120.00, 1),
(27, 5, 'Эспрессо ', 60.00, 60.00, 1),
(28, 5, 'Американо  ', 80.00, 80.00, 1),
(29, 5, 'Пряный/банановый раф ', 120.00, 120.00, 1),
(30, 5, 'Бамбл би  ', 10.00, 120.00, 1),
(31, 5, 'Холодный кофе ', 100.00, 100.00, 1),
(32, 5, 'Фрапучино/миндальный/ореховый/вишневый ', 130.00, 130.00, 1),
(33, 5, 'Кофе гляссе ', 130.00, 130.00, 1),
(34, 6, 'Пиво хугарден ', 170.00, 170.00, 1),
(35, 6, 'Козел темное/светлое РФ ', 150.00, 150.00, 1),
(36, 6, 'Черновар ', 200.00, 200.00, 1),
(37, 6, 'Бадвайзер ', 250.00, 250.00, 1),
(38, 6, 'Паулайнер  ', 200.00, 200.00, 1),
(39, 6, 'Сидр ', 200.00, 200.00, 1),
(40, 7, 'Глинтвейн ', 200.00, 200.00, 1),
(41, 7, 'Безалкогольный глинтвейн ', 170.00, 170.00, 1),
(42, 7, 'Лыхны', 850.00, 850.00, 1),
(43, 7, 'Алазанская долина ', 850.00, 850.00, 1),
(44, 7, 'Кьянти ', 1000.00, 1000.00, 1),
(45, 7, 'Пино гриджио ', 1000.00, 1000.00, 1),
(46, 8, 'Пуэр', 200.00, 200.00, 1),
(47, 8, 'Какао', 120.00, 120.00, 1),
(48, 8, 'Чай в ассортименте', 150.00, 150.00, 1),
(49, 8, 'Банка 0.33 фанта/спрайт/кола', 100.00, 100.00, 1),
(50, 8, 'Липтон чай 0.33', 100.00, 100.00, 1),
(51, 9, 'Кальян', 650.00, 650.00, 1),
(52, 9, 'Кальян', 700.00, 700.00, 1),
(53, 9, 'Кальян', 550.00, 550.00, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `dish_types`
--

CREATE TABLE `dish_types` (
  `dt_id` int(11) NOT NULL,
  `dt_name` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `dish_types`
--

INSERT INTO `dish_types` (`dt_id`, `dt_name`) VALUES
(1, 'Чаи'),
(2, 'Лимонады'),
(3, 'Милк-шейки'),
(4, 'Коктейли'),
(5, 'Кофе'),
(6, 'Пиво'),
(7, 'Вина'),
(8, 'Напитки'),
(9, 'Кальяны');

-- --------------------------------------------------------

--
-- Структура таблицы `orders`
--

CREATE TABLE `orders` (
  `o_id` int(11) NOT NULL,
  `o_date` datetime NOT NULL DEFAULT '1000-01-01 00:00:00',
  `o_discount` float(6,2) NOT NULL DEFAULT '0.00',
  `o_user` int(11) NOT NULL DEFAULT '0',
  `o_cancelled` int(2) NOT NULL DEFAULT '0',
  `o_card` int(1) UNSIGNED NOT NULL DEFAULT '0',
  `o_table` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `orders`
--

INSERT INTO `orders` (`o_id`, `o_date`, `o_discount`, `o_user`, `o_cancelled`, `o_card`, `o_table`) VALUES
(1, '2018-11-17 13:31:18', 50.00, 1, 0, 1, 1),
(2, '2018-11-17 13:38:35', 0.00, 1, 0, 0, 2),
(3, '2018-11-17 13:46:10', 50.00, 1, 0, 1, 1),
(4, '2018-11-17 14:43:36', 20.00, 1, 0, 1, 10),
(5, '2018-11-17 19:13:25', 0.00, 1, 0, 1, 2),
(7, '2018-11-17 19:57:50', 0.00, 1, 0, 0, 1),
(8, '2018-11-17 22:15:31', 0.00, 1, 0, 0, 1),
(9, '2018-11-17 22:21:27', 0.00, 2, 0, 1, 1),
(10, '2018-11-17 22:25:19', 0.00, 1, 0, 1, 9),
(11, '2018-11-17 22:28:28', 0.00, 1, 0, 1, 1),
(12, '2018-11-17 22:37:55', 0.00, 1, 0, 0, 1),
(13, '2018-11-17 22:38:16', 0.00, 1, 0, 0, 1),
(14, '2018-11-17 22:40:16', 0.00, 1, 0, 1, 1),
(15, '2018-11-17 22:40:38', 0.00, 1, 0, 1, 1),
(16, '2018-11-19 22:32:42', 5.00, 1, 0, 1, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `order_guests`
--

CREATE TABLE `order_guests` (
  `og_id` int(11) NOT NULL,
  `og_o_id` int(11) NOT NULL DEFAULT '0',
  `og_g_id` int(1) NOT NULL DEFAULT '1',
  `og_g_discount` float(6,2) NOT NULL DEFAULT '0.00',
  `og_g_card` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `order_guests`
--

INSERT INTO `order_guests` (`og_id`, `og_o_id`, `og_g_id`, `og_g_discount`, `og_g_card`) VALUES
(1, 1, 0, 10.00, 0),
(2, 2, 0, 0.00, 0),
(3, 3, 0, 10.00, 0),
(4, 4, 0, 5.00, 0),
(5, 4, 1, 10.00, 0),
(6, 4, 2, 15.00, 0),
(7, 5, 0, 0.00, 0),
(9, 7, 0, 0.00, 0),
(10, 8, 0, 0.00, 0),
(11, 9, 0, 15.00, 0),
(12, 10, 0, 15.00, 0),
(13, 11, 0, 15.00, 1),
(14, 12, 0, 15.00, 1),
(15, 13, 0, 15.00, 1),
(16, 14, 0, 0.00, 0),
(17, 15, 0, 0.00, 0),
(18, 16, 0, 40.00, 0);

-- --------------------------------------------------------

--
-- Структура таблицы `order_guest_content`
--

CREATE TABLE `order_guest_content` (
  `ogc_id` int(11) NOT NULL,
  `ogc_g_id` int(11) NOT NULL DEFAULT '0',
  `ogc_d_id` int(11) NOT NULL DEFAULT '0',
  `ogc_count` int(11) NOT NULL DEFAULT '0',
  `ogc_summa` decimal(10,0) UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `order_guest_content`
--

INSERT INTO `order_guest_content` (`ogc_id`, `ogc_g_id`, `ogc_d_id`, `ogc_count`, `ogc_summa`) VALUES
(1, 1, 1, 3, '270'),
(2, 2, 52, 1, '700'),
(3, 3, 40, 1, '200'),
(4, 3, 1, 2, '180'),
(5, 3, 2, 1, '90'),
(6, 4, 40, 1, '200'),
(7, 5, 41, 2, '340'),
(8, 6, 1, 1, '136'),
(9, 6, 2, 1, '136'),
(10, 6, 3, 2, '272'),
(11, 7, 41, 1, '170'),
(12, 9, 25, 1, '100'),
(13, 9, 26, 1, '120'),
(14, 10, 25, 1, '100'),
(15, 11, 1, 1, '170'),
(16, 12, 33, 1, '130'),
(17, 13, 34, 1, '170'),
(18, 14, 20, 1, '150'),
(19, 15, 20, 1, '150'),
(20, 16, 20, 1, '150'),
(21, 17, 21, 1, '150'),
(22, 18, 9, 1, '200'),
(23, 18, 10, 1, '200');

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `u_id` int(11) NOT NULL,
  `u_name` text NOT NULL,
  `u_pass` text NOT NULL,
  `u_rights` int(11) NOT NULL DEFAULT '0',
  `u_state` int(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`u_id`, `u_name`, `u_pass`, `u_rights`, `u_state`) VALUES
(1, 'Администратор', '202cb962ac59075b964b07152d234b70', 2, 1),
(2, 'Кассир 1', '202cb962ac59075b964b07152d234b70', 1, 1),
(4, 'Кассир 2', '202cb962ac59075b964b07152d234b70', 1, 1),
(5, 'Кассир 3', '202cb962ac59075b964b07152d234b70', 1, 1);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `discounts`
--
ALTER TABLE `discounts`
  ADD PRIMARY KEY (`ds_id`),
  ADD UNIQUE KEY `ids_ds2` (`ds_number`),
  ADD KEY `ids_ds1` (`ds_id`);

--
-- Индексы таблицы `dishes`
--
ALTER TABLE `dishes`
  ADD PRIMARY KEY (`d_id`),
  ADD KEY `d_idx1` (`d_id`),
  ADD KEY `d_fk1` (`d_type`);

--
-- Индексы таблицы `dish_types`
--
ALTER TABLE `dish_types`
  ADD PRIMARY KEY (`dt_id`);

--
-- Индексы таблицы `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`o_id`),
  ADD KEY `o_idx1` (`o_id`),
  ADD KEY `o_fk1` (`o_user`);

--
-- Индексы таблицы `order_guests`
--
ALTER TABLE `order_guests`
  ADD PRIMARY KEY (`og_id`),
  ADD KEY `og_idx1` (`og_id`),
  ADD KEY `og_fk1` (`og_o_id`);

--
-- Индексы таблицы `order_guest_content`
--
ALTER TABLE `order_guest_content`
  ADD PRIMARY KEY (`ogc_id`),
  ADD KEY `ogc_idx1` (`ogc_id`),
  ADD KEY `ogc_fk1` (`ogc_g_id`),
  ADD KEY `ogc_fk2` (`ogc_d_id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`u_id`),
  ADD KEY `u_idx1` (`u_id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `discounts`
--
ALTER TABLE `discounts`
  MODIFY `ds_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT для таблицы `dishes`
--
ALTER TABLE `dishes`
  MODIFY `d_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;
--
-- AUTO_INCREMENT для таблицы `dish_types`
--
ALTER TABLE `dish_types`
  MODIFY `dt_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT для таблицы `orders`
--
ALTER TABLE `orders`
  MODIFY `o_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
--
-- AUTO_INCREMENT для таблицы `order_guests`
--
ALTER TABLE `order_guests`
  MODIFY `og_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
--
-- AUTO_INCREMENT для таблицы `order_guest_content`
--
ALTER TABLE `order_guest_content`
  MODIFY `ogc_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;
--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `u_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `dishes`
--
ALTER TABLE `dishes`
  ADD CONSTRAINT `d_fk1` FOREIGN KEY (`d_type`) REFERENCES `dish_types` (`dt_id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Ограничения внешнего ключа таблицы `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `o_fk1` FOREIGN KEY (`o_user`) REFERENCES `users` (`u_id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Ограничения внешнего ключа таблицы `order_guests`
--
ALTER TABLE `order_guests`
  ADD CONSTRAINT `og_fk1` FOREIGN KEY (`og_o_id`) REFERENCES `orders` (`o_id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Ограничения внешнего ключа таблицы `order_guest_content`
--
ALTER TABLE `order_guest_content`
  ADD CONSTRAINT `ogc_fk1` FOREIGN KEY (`ogc_g_id`) REFERENCES `order_guests` (`og_id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `ogc_fk2` FOREIGN KEY (`ogc_d_id`) REFERENCES `dishes` (`d_id`) ON DELETE CASCADE ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
