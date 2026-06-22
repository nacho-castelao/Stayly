-- Stayly seed data for lookup/reference tables (amenities, languages).
-- Required for the host wizard (amenities) and user language profiles to work.
-- Apply after loading database/schema.sql:
--   mysql -u root stayly < database/seed.sql

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

LOCK TABLES `amenities` WRITE;
/*!40000 ALTER TABLE `amenities` DISABLE KEYS */;
INSERT INTO `amenities` (`id`, `name`, `icon`) VALUES (1,'WiFi','wifi');
INSERT INTO `amenities` (`id`, `name`, `icon`) VALUES (2,'Air conditioning','snowflake');
INSERT INTO `amenities` (`id`, `name`, `icon`) VALUES (3,'Heating','thermometer');
INSERT INTO `amenities` (`id`, `name`, `icon`) VALUES (4,'Kitchen','utensils');
INSERT INTO `amenities` (`id`, `name`, `icon`) VALUES (5,'Washing machine','washing-machine');
INSERT INTO `amenities` (`id`, `name`, `icon`) VALUES (6,'Dryer','dryer');
INSERT INTO `amenities` (`id`, `name`, `icon`) VALUES (7,'TV','tv');
INSERT INTO `amenities` (`id`, `name`, `icon`) VALUES (8,'Free parking','car');
INSERT INTO `amenities` (`id`, `name`, `icon`) VALUES (14,'Workspace','desk');
/*!40000 ALTER TABLE `amenities` ENABLE KEYS */;
UNLOCK TABLES;

LOCK TABLES `languages` WRITE;
/*!40000 ALTER TABLE `languages` DISABLE KEYS */;
INSERT INTO `languages` (`id`, `name`, `code`, `created_at`) VALUES (1,'English','en','2026-02-23 10:27:44');
INSERT INTO `languages` (`id`, `name`, `code`, `created_at`) VALUES (2,'Spanish','es','2026-02-23 10:27:44');
INSERT INTO `languages` (`id`, `name`, `code`, `created_at`) VALUES (3,'French','fr','2026-02-23 10:27:44');
INSERT INTO `languages` (`id`, `name`, `code`, `created_at`) VALUES (4,'German','de','2026-02-23 10:27:44');
INSERT INTO `languages` (`id`, `name`, `code`, `created_at`) VALUES (5,'Italian','it','2026-02-23 10:27:44');
INSERT INTO `languages` (`id`, `name`, `code`, `created_at`) VALUES (6,'Portuguese','pt','2026-02-23 10:27:44');
/*!40000 ALTER TABLE `languages` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

