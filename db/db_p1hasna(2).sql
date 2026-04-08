/*
SQLyog Professional v13.1.1 (64 bit)
MySQL - 8.0.30 : Database - db_p1hasna
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`db_p1hasna` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;

USE `db_p1hasna`;

/*Table structure for table `bundle_tools` */

DROP TABLE IF EXISTS `bundle_tools`;

CREATE TABLE `bundle_tools` (
  `id` int NOT NULL AUTO_INCREMENT,
  `bundle_id` int NOT NULL COMMENT 'FK ke tools dimana item_type = bundle',
  `tool_id` int NOT NULL COMMENT 'FK ke tools dimana item_type = bundle_tool',
  `qty` int NOT NULL COMMENT 'Jumlah sub-tool ini dalam satu bundle',
  PRIMARY KEY (`id`),
  KEY `bundle_tools_bundle_id_foreign` (`bundle_id`),
  KEY `bundle_tools_tool_id_foreign` (`tool_id`),
  CONSTRAINT `bundle_tools_bundle_id_foreign` FOREIGN KEY (`bundle_id`) REFERENCES `tools` (`id`),
  CONSTRAINT `bundle_tools_tool_id_foreign` FOREIGN KEY (`tool_id`) REFERENCES `tools` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Data for the table `bundle_tools` */

/*Table structure for table `categories` */

DROP TABLE IF EXISTS `categories`;

CREATE TABLE `categories` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL COMMENT 'Nama kategori alat',
  `description` text NOT NULL COMMENT 'Deskripsi kategori',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Data for the table `categories` */

/*Table structure for table `loans` */

DROP TABLE IF EXISTS `loans`;

CREATE TABLE `loans` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL COMMENT 'FK ke users  ,   peminjam yang mengajukan',
  `tool_id` int NOT NULL COMMENT 'FK ke tools  ,   template alat yang dipinjam',
  `unit_code` varchar(255) NOT NULL COMMENT 'FK ke tool_units  ,   unit fisik spesifik yang dipilih user   (  berlaku untuk single maupun bundle)',
  `employee_id` int DEFAULT NULL COMMENT 'FK ke users   (  Employee  )    ,   diisi saat approve atau reject',
  `status` enum('pending','active','rejected','closed') NOT NULL,
  `loan_date` date NOT NULL COMMENT 'Tanggal mulai peminjaman',
  `due_date` date NOT NULL COMMENT 'Tanggal wajib kembali',
  `purpose` text NOT NULL COMMENT 'Tujuan/keperluan peminjaman dari user',
  `notes` text COMMENT 'Catatan Employee saat approve atau reject',
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `loans_user_id_foreign` (`user_id`),
  KEY `loans_tool_id_foreign` (`tool_id`),
  KEY `loans_unit_code_foreign` (`unit_code`),
  KEY `loans_employee_id_foreign` (`employee_id`),
  CONSTRAINT `loans_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `users` (`id`),
  CONSTRAINT `loans_tool_id_foreign` FOREIGN KEY (`tool_id`) REFERENCES `tools` (`id`),
  CONSTRAINT `loans_unit_code_foreign` FOREIGN KEY (`unit_code`) REFERENCES `tool_units` (`code`),
  CONSTRAINT `loans_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Data for the table `loans` */

/*Table structure for table `migrations` */

DROP TABLE IF EXISTS `migrations`;

CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Data for the table `migrations` */

insert  into `migrations`(`id`,`migration`,`batch`) values 
(1,'2026_04_08_074537_create_categories_table',1),
(2,'2026_04_08_074538_create_users_table',1),
(3,'2026_04_08_074539_create_user_details_table',1),
(4,'2026_04_08_074540_create_tools_table',1),
(5,'2026_04_08_074541_create_tool_units_table',1),
(6,'2026_04_08_074542_create_unit_conditions_table',1),
(7,'2026_04_08_074543_create_bundle_tools_table',1),
(8,'2026_04_08_074544_create_loans_table',1),
(9,'2026_04_08_074545_create_returns_table',1);

/*Table structure for table `returns` */

DROP TABLE IF EXISTS `returns`;

CREATE TABLE `returns` (
  `id` int NOT NULL AUTO_INCREMENT,
  `loan_id` int NOT NULL COMMENT 'FK ke loans  ,   1 loan hanya bisa punya 1 return',
  `employee_id` int NOT NULL COMMENT 'FK ke users   (  Employee  )   yang mencatat pengembalian',
  `condition_id` varchar(255) NOT NULL COMMENT 'FK ke unit_conditions  ,   kondisi alat saat dikembalikan',
  `return_date` date NOT NULL COMMENT 'Tanggal aktual alat dikembalikan',
  `notes` text COMMENT 'Catatan pengembalian dari Employee',
  `created_at` timestamp NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `returns_loan_id_unique` (`loan_id`),
  KEY `returns_employee_id_foreign` (`employee_id`),
  KEY `returns_condition_id_foreign` (`condition_id`),
  CONSTRAINT `returns_condition_id_foreign` FOREIGN KEY (`condition_id`) REFERENCES `unit_conditions` (`id`),
  CONSTRAINT `returns_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `users` (`id`),
  CONSTRAINT `returns_loan_id_foreign` FOREIGN KEY (`loan_id`) REFERENCES `loans` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Data for the table `returns` */

/*Table structure for table `tool_units` */

DROP TABLE IF EXISTS `tool_units`;

CREATE TABLE `tool_units` (
  `code` varchar(255) NOT NULL COMMENT 'Kode unik unit fisik  ,   dibuat BE. Single: LPT-001 | Bundle: SET-PK-001',
  `tool_id` int NOT NULL COMMENT 'FK ke tools   (  template)',
  `status` enum('available','nonactive','lent') NOT NULL,
  `notes` text NOT NULL COMMENT 'Catatan tambahan unit',
  `created_at` timestamp NOT NULL,
  PRIMARY KEY (`code`),
  KEY `tool_units_tool_id_foreign` (`tool_id`),
  CONSTRAINT `tool_units_tool_id_foreign` FOREIGN KEY (`tool_id`) REFERENCES `tools` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Data for the table `tool_units` */

/*Table structure for table `tools` */

DROP TABLE IF EXISTS `tools`;

CREATE TABLE `tools` (
  `id` int NOT NULL AUTO_INCREMENT,
  `category_id` int NOT NULL COMMENT 'FK ke categories',
  `name` varchar(255) NOT NULL COMMENT 'Nama template/jenis alat',
  `item_type` enum('single','bundle','bundle_tool') NOT NULL,
  `status` enum('') NOT NULL,
  `description` text NOT NULL COMMENT 'Deskripsi umum alat atau bundle',
  `code_slug` bigint DEFAULT NULL,
  `photo_path` varchar(255) NOT NULL COMMENT 'Path foto representatif alat',
  `created_at` timestamp NOT NULL,
  PRIMARY KEY (`id`),
  KEY `tools_category_id_foreign` (`category_id`),
  CONSTRAINT `tools_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Data for the table `tools` */

/*Table structure for table `unit_conditions` */

DROP TABLE IF EXISTS `unit_conditions`;

CREATE TABLE `unit_conditions` (
  `id` varchar(255) NOT NULL COMMENT 'Kode unik riwayat kondisi  ,   dibuat BE',
  `unit_code` varchar(255) NOT NULL COMMENT 'FK ke tool_units',
  `return_id` int DEFAULT NULL COMMENT 'FK ke returns  ,   NULL jika dicatat di luar konteks pengembalian   (  entry awal  ,   maintenance  ,   inspeksi)',
  `conditions` enum('good','broken','maintenance') NOT NULL,
  `notes` text NOT NULL COMMENT 'Penjelasan kondisi saat dicatat',
  `recorded_at` timestamp NOT NULL COMMENT 'Waktu kondisi dicatat. Kondisi terkini = recorded_at paling baru',
  PRIMARY KEY (`id`),
  KEY `unit_conditions_unit_code_foreign` (`unit_code`),
  CONSTRAINT `unit_conditions_unit_code_foreign` FOREIGN KEY (`unit_code`) REFERENCES `tool_units` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Data for the table `unit_conditions` */

/*Table structure for table `user_details` */

DROP TABLE IF EXISTS `user_details`;

CREATE TABLE `user_details` (
  `nik` varchar(255) NOT NULL COMMENT 'Nomor Induk Kependudukan  ,   unik per orang',
  `user_id` int DEFAULT NULL COMMENT 'FK ke users',
  `name` varchar(255) DEFAULT NULL COMMENT 'Nama lengkap',
  `no_hp` varchar(255) DEFAULT NULL COMMENT 'Nomor handphone',
  `address` varchar(255) DEFAULT NULL COMMENT 'Alamat lengkap',
  `birth_date` date DEFAULT NULL COMMENT 'Tanggal lahir',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`nik`),
  KEY `user_details_user_id_foreign` (`user_id`),
  CONSTRAINT `user_details_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Data for the table `user_details` */

insert  into `user_details`(`nik`,`user_id`,`name`,`no_hp`,`address`,`birth_date`,`created_at`,`updated_at`) values 
('3174010101010001',1,'Admin Sistem','081111111111','Jakarta','1990-01-01','2026-04-08 07:54:29','2026-04-08 07:54:29'),
('3174010101010002',2,'Petugas Lapangan','082222222222','Bandung','1995-05-05','2026-04-08 07:54:29','2026-04-08 07:54:29'),
('3174010101010003',3,'User Peminjam','083333333333','Bogor','2000-10-10','2026-04-08 07:54:29','2026-04-08 07:54:29');

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL COMMENT 'Email untuk login  ,   harus unik',
  `password` varchar(255) NOT NULL COMMENT 'Password ter-hash   (  bcrypt)',
  `role` enum('Admin','Employee','User') NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Data for the table `users` */

insert  into `users`(`id`,`email`,`password`,`role`,`created_at`,`updated_at`) values 
(1,'admin@ukk2026.com','$2y$12$Ehw0JulqEcI2jjH.H3msHudGJRmMiq9tYROfua1h3p6KMahE4nwUi','Admin','2026-04-08 07:54:28','2026-04-08 07:54:28'),
(2,'petugas@ukk2026.com','$2y$12$/x1vGsug65CF1m/96gR6u.8wGQ94aDoYSR6KwtR6Gc8jlbLE12lBm','Employee','2026-04-08 07:54:28','2026-04-08 07:54:28'),
(3,'peminjam@ukk2026.com','$2y$12$.Xqo/F83OYAKL1DbCRr/nO.c5FSH73e46YiXwr8gwUQ75.mLCvTr.','User','2026-04-08 07:54:29','2026-04-08 07:54:29');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
