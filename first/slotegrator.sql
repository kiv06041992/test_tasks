/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


-- Дамп структуры базы данных slotegrator
CREATE DATABASE IF NOT EXISTS `slotegrator` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `slotegrator`;

-- Дамп структуры для таблица slotegrator.gifts
CREATE TABLE IF NOT EXISTS `gifts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` char(255) NOT NULL,
  `type` int(11) NOT NULL,
  `quantity` int(11) NOT NULL COMMENT '-1 неограниченное количество\r\n0 не участвует в розыгрыше',
  `min` int(11) NOT NULL DEFAULT 0,
  `max` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `FK_gifts_gift_types` (`type`),
  CONSTRAINT `FK_gifts_gift_types` FOREIGN KEY (`type`) REFERENCES `gift_types` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='Список возможных подарков';

-- Дамп данных таблицы slotegrator.gifts: ~3 rows (приблизительно)
/*!40000 ALTER TABLE `gifts` DISABLE KEYS */;
INSERT INTO `gifts` (`id`, `name`, `type`, `quantity`, `min`, `max`) VALUES
	(1, 'rub', 1, 100, 10, 50),
	(2, 'бонусные баллы', 2, -1, 100, 500),
	(3, 'велосипед', 3, 10, 1, 1);
/*!40000 ALTER TABLE `gifts` ENABLE KEYS */;

-- Дамп структуры для таблица slotegrator.gifts_conversion_ratio
CREATE TABLE IF NOT EXISTS `gifts_conversion_ratio` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `gift_id_1` int(11) NOT NULL,
  `gift_id_2` int(11) NOT NULL,
  `ratio` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_gifts_conversion_ratio_gifts` (`gift_id_1`),
  KEY `FK_gifts_conversion_ratio_gifts_2` (`gift_id_2`),
  CONSTRAINT `FK_gifts_conversion_ratio_gifts` FOREIGN KEY (`gift_id_1`) REFERENCES `gifts` (`id`),
  CONSTRAINT `FK_gifts_conversion_ratio_gifts_2` FOREIGN KEY (`gift_id_2`) REFERENCES `gifts` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='gift_id_1 меняем на gift_id_2 по курсу ratio';

-- Дамп данных таблицы slotegrator.gifts_conversion_ratio: ~0 rows (приблизительно)
/*!40000 ALTER TABLE `gifts_conversion_ratio` DISABLE KEYS */;
INSERT INTO `gifts_conversion_ratio` (`id`, `gift_id_1`, `gift_id_2`, `ratio`) VALUES
	(1, 1, 2, 10);
/*!40000 ALTER TABLE `gifts_conversion_ratio` ENABLE KEYS */;

-- Дамп структуры для таблица slotegrator.gift_status
CREATE TABLE IF NOT EXISTS `gift_status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` char(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- Дамп данных таблицы slotegrator.gift_status: ~4 rows (приблизительно)
/*!40000 ALTER TABLE `gift_status` DISABLE KEYS */;
INSERT INTO `gift_status` (`id`, `name`) VALUES
	(1, 'ожидает принятия'),
	(2, 'принят'),
	(3, 'отправлен'),
	(4, 'отказ');
/*!40000 ALTER TABLE `gift_status` ENABLE KEYS */;

-- Дамп структуры для таблица slotegrator.gift_types
CREATE TABLE IF NOT EXISTS `gift_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='Типы подарков';

-- Дамп данных таблицы slotegrator.gift_types: ~2 rows (приблизительно)
/*!40000 ALTER TABLE `gift_types` DISABLE KEYS */;
INSERT INTO `gift_types` (`id`, `name`) VALUES
	(1, 'деньги'),
	(2, 'баллы'),
	(3, 'предмет');
/*!40000 ALTER TABLE `gift_types` ENABLE KEYS */;

-- Дамп структуры для таблица slotegrator.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` char(255) NOT NULL,
  `password` char(32) NOT NULL COMMENT 'md5',
  PRIMARY KEY (`id`),
  KEY `email_password` (`email`,`password`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- Дамп данных таблицы slotegrator.users: ~0 rows (приблизительно)
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` (`id`, `email`, `password`) VALUES
	(1, 'test@test.test', 'q');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;

-- Дамп структуры для таблица slotegrator.user_gifts
CREATE TABLE IF NOT EXISTS `user_gifts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `gift_id` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `date_create` datetime NOT NULL,
  `date_send` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_user_gifts_users` (`user_id`),
  KEY `FK_user_gifts_gifts` (`gift_id`),
  KEY `FK_user_gifts_gift_status` (`status`),
  CONSTRAINT `FK_user_gifts_gift_status` FOREIGN KEY (`status`) REFERENCES `gift_status` (`id`),
  CONSTRAINT `FK_user_gifts_gifts` FOREIGN KEY (`gift_id`) REFERENCES `gifts` (`id`),
  CONSTRAINT `FK_user_gifts_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Подарки пользователя и их статус';

-- Дамп данных таблицы slotegrator.user_gifts: ~0 rows (приблизительно)
/*!40000 ALTER TABLE `user_gifts` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_gifts` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
