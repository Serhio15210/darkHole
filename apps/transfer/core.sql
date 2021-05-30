-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Хост: mysql
-- Час створення: Квт 11 2021 р., 00:45
-- Версія сервера: 8.0.20
-- Версія PHP: 7.4.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База даних: `core`
--

-- --------------------------------------------------------

--
-- Структура таблиці `Messages`
--

CREATE TABLE `Messages` (
  `id` int UNSIGNED NOT NULL,
  `parent` int UNSIGNED DEFAULT NULL,
  `type` int UNSIGNED NOT NULL DEFAULT '0' COMMENT '0 - повідомлення, - 1 кнопка, 2 - форма, 3 - поле форми, 4 - сервіс',
  `guid` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `code` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `title` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `entrypoint` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `position` int UNSIGNED NOT NULL DEFAULT '0',
  `service` int UNSIGNED DEFAULT NULL,
  `reload` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп даних таблиці `Messages`
--

INSERT INTO `Messages` (`id`, `parent`, `type`, `guid`, `code`, `title`, `text`, `entrypoint`, `position`, `service`, `reload`) VALUES
(1, NULL, 0, '5fa24011-8a7b-11eb-8926-0242ac130003', 'welcome', '👩🏽‍🚀 Взлітаємо!', 'Ця система буде корисна Вам, якщо Ви є студентом Одеської політехніки.\r\nФункціонал для викладачів, адміністрації та абітурієнтів буде трохи згодом 😉\r\n\r\nПриклад запитів:\r\n\r\n❔ Коли карантин\r\n\r\n', '/start', 0, NULL, 0),
(2, NULL, 0, 'a1d06b79-8a82-11eb-8926-0242ac130003', 'unknown', '', '🤷 Мене ще такому не навчили', NULL, 0, NULL, 0),
(3, NULL, 0, 'a600b0d1-8a84-11eb-8926-0242ac130003', 'why', '', '🤔 Хмм. Складно відповісти', 'Чому', 0, NULL, 0),
(4, NULL, 0, '0aa0c4a1-8a85-11eb-8926-0242ac130003', 'quarantine', '', '🤒 Карантин то може й добре. А вчитися коли будемо?', 'Коли карантин', 0, NULL, 0),
(5, NULL, 0, 'f5db4827-8a85-11eb-8926-0242ac130003', 'unknown', '', '😩 От халепа. Але я навчусь!', NULL, 0, NULL, 0),
(6, NULL, 0, '2b432631-8a86-11eb-8926-0242ac130003', 'unknown', '', '⏳ Треба відпочити', NULL, 0, NULL, 0),
(7, NULL, 0, '629faeb7-8a86-11eb-8926-0242ac130003', 'unknown', '', '😜 Ухтишка. Давай ще!', NULL, 0, NULL, 0),
(8, NULL, 0, '8e14d213-8a8b-11eb-8926-0242ac130003', 'unknown', '', '🙈 Просто текст не по-нормальному написано', NULL, 0, NULL, 0),
(9, NULL, 0, 'e194fae2-8a8b-11eb-8926-0242ac130003', 'unknown', '', '😏 Все не так, давай з початку', NULL, 0, NULL, 0),
(10, NULL, 0, '3b46aa08-8a8c-11eb-8926-0242ac130003', 'unknown', '', '🏓 Батя, я стараюсь..', NULL, 0, NULL, 0),
(11, NULL, 0, 'eae498e4-8a8c-11eb-8926-0242ac130003', 'unknown', '', '🐧 Тримай пінгвіна', NULL, 0, NULL, 0),
(12, NULL, 0, 'eae4a5bb-8a8c-11eb-8926-0242ac130003', 'unknown', '', '🐸 Тримай жабу', NULL, 0, NULL, 0),
(13, NULL, 0, '114a8d04-8a8d-11eb-8926-0242ac130003', 'unknown', '', '☕ Пішли каву пити', NULL, 0, NULL, 0),
(14, 1, 1, '063924dc-8a8e-11eb-8926-0242ac130003', 'opportunities', '💎 Можливості', 'В університеті ти можеш не лише вчитися, а й бути соціально активним. Дізнайся, як саме розвинути свої softskills і не тільки', '/opportunities', 0, NULL, 1),
(15, 14, 1, '42b1a807-8a8f-11eb-8926-0242ac130003', 'development', '⚙️ Розробникам', 'Єдина інформаційна система(ЄІС) Одеської політехніки діє за принципом відкритості та децентралізації.\r\nБудь який ідентифікований стейкґолдер може здійснити підключення за допомогою SDK\r\n\r\nДля взаємодії із ЄІС необхідно притримуватись простих правил:\r\n\r\n🔑 Пройти ідентифікацію\r\n🔑 Не зберігати інформацію з інших реєстрів\r\n🔑 За вимогою оператора ЄІС видалити дані користувача\r\n🔑 Прозоро використовувати дані\r\n🔑 Використовувати українську мову інтерфейсу', '/fordev', 0, NULL, 1),
(16, 15, 2, '8311dbb3-8c1a-11eb-99df-0242ac130002', 'connection', 'Запит на підключення', '', NULL, 0, 1, 0),
(17, 16, 3, 'a433490f-8d0b-11eb-9ad4-0242ac130002', 's-title', 'Назва сервісу', 'Введіть назву сервісу, який будете писати. Обмеження 40 символів', NULL, 0, NULL, 0),
(18, 16, 3, 'da726ece-8e37-11eb-a399-0242ac130004', 's-description', 'Опис сервісу', 'Опишіть, який сервіс будете надавати. Мінімальна кількість - 3 речення', NULL, 1, NULL, 0),
(19, 16, 3, '30075115-8ea3-11eb-a399-0242ac130004', 's-webhook', 'Webhook', 'Використовуйте це поле для налаштування url адрес webhook.\r\nПри настанні подій, на які підписаний сервіс, на цю адресу надійде опис подій.\r\nВважається, що всі системи працюють на протоколі https, тому починати треба з домену. Обмеження 64 символи.\r\nНаприклад, domain.com/core/webhook', NULL, 2, NULL, 0),
(20, 1, 1, '6dc694f8-931a-11eb-8133-0242ac120007', 'study', '🧑‍🎓 Моє навчання', 'Не витрачайте час на прогулянки під деканатом, нехай дані бігають за Вас', NULL, 0, NULL, 1);

--
-- Тригери `Messages`
--
DELIMITER $$
CREATE TRIGGER `MessagesGUIDCreator` BEFORE INSERT ON `Messages` FOR EACH ROW SET NEW.guid = UUID()
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Структура таблиці `Services`
--

CREATE TABLE `Services` (
  `id` int UNSIGNED NOT NULL,
  `title` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `code` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `status` int NOT NULL DEFAULT '0',
  `webhook` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `updated` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `office` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `user` int UNSIGNED DEFAULT NULL,
  `token` char(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `signature` char(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп даних таблиці `Services`
--

INSERT INTO `Services` (`id`, `title`, `description`, `code`, `status`, `webhook`, `updated`, `office`, `user`, `token`, `signature`) VALUES
(1, 'Інтеграція з ЄІС', 'Керує доступом зовнішніх сервісів до єдиної інформаційної системи', NULL, 0, NULL, '2021-03-23 21:04:21', 'ПНІТ', NULL, NULL, NULL),
(2, 'Проксі', 'Точка входу до боту', 'proxy', 0, 'api.pnit.od.ua', '2021-03-27 03:44:06', NULL, 1, 'm5MrP2rTkLsmja1qutI3OImBlhRuj210', '5DWuFK9qxwmut0tOOIn4ZGhFMcvKDzre');

-- --------------------------------------------------------

--
-- Структура таблиці `Users`
--

CREATE TABLE `Users` (
  `id` int UNSIGNED NOT NULL,
  `chat` int NOT NULL,
  `person` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL COMMENT 'Тимчасовий стовпчик для розробки',
  `guid` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `service` int UNSIGNED DEFAULT NULL,
  `message` int UNSIGNED DEFAULT NULL,
  `input` json DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп даних таблиці `Users`
--

INSERT INTO `Users` (`id`, `chat`, `person`, `guid`, `service`, `message`, `input`) VALUES
(1, 911, 'Root user', NULL, 2, NULL, NULL);


--
-- Індекси збережених таблиць
--

--
-- Індекси таблиці `Messages`
--
ALTER TABLE `Messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `parent` (`parent`),
  ADD KEY `service` (`service`);

--
-- Індекси таблиці `Services`
--
ALTER TABLE `Services`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user` (`user`);

--
-- Індекси таблиці `Users`
--
ALTER TABLE `Users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `service` (`service`),
  ADD KEY `message` (`message`);

--
-- AUTO_INCREMENT для збережених таблиць
--

--
-- AUTO_INCREMENT для таблиці `Messages`
--
ALTER TABLE `Messages`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT для таблиці `Services`
--
ALTER TABLE `Services`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT для таблиці `Users`
--
ALTER TABLE `Users`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- Обмеження зовнішнього ключа збережених таблиць
--

--
-- Обмеження зовнішнього ключа таблиці `Messages`
--
ALTER TABLE `Messages`
  ADD CONSTRAINT `Messages_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `Messages` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `Messages_ibfk_2` FOREIGN KEY (`service`) REFERENCES `Services` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Обмеження зовнішнього ключа таблиці `Services`
--
ALTER TABLE `Services`
  ADD CONSTRAINT `Services_ibfk_1` FOREIGN KEY (`user`) REFERENCES `Users` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Обмеження зовнішнього ключа таблиці `Users`
--
ALTER TABLE `Users`
  ADD CONSTRAINT `Users_ibfk_1` FOREIGN KEY (`service`) REFERENCES `Services` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `Users_ibfk_2` FOREIGN KEY (`message`) REFERENCES `Messages` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;