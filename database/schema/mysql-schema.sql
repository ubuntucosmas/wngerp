/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
DROP TABLE IF EXISTS `archival_reports`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `archival_reports` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `project_id` bigint(20) unsigned NOT NULL,
  `title` varchar(191) NOT NULL,
  `description` text DEFAULT NULL,
  `google_drive_link` varchar(191) NOT NULL,
  `uploaded_by` bigint(20) unsigned NOT NULL,
  `report_type` varchar(191) NOT NULL,
  `report_date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `archival_reports_project_id_foreign` (`project_id`),
  KEY `archival_reports_uploaded_by_foreign` (`uploaded_by`),
  CONSTRAINT `archival_reports_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE,
  CONSTRAINT `archival_reports_uploaded_by_foreign` FOREIGN KEY (`uploaded_by`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `attachments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `attachments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `task_id` bigint(20) unsigned NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `file_type` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `attachments_task_id_foreign` (`task_id`),
  CONSTRAINT `attachments_task_id_foreign` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `booking_order_teams`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `booking_order_teams` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `booking_order_id` bigint(20) unsigned NOT NULL,
  `team_type` enum('set_down','pasting','technical') NOT NULL,
  `member_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `booking_order_teams_booking_order_id_foreign` (`booking_order_id`),
  CONSTRAINT `booking_order_teams_booking_order_id_foreign` FOREIGN KEY (`booking_order_id`) REFERENCES `booking_orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `booking_orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `booking_orders` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `project_id` bigint(20) unsigned NOT NULL,
  `project_name` varchar(255) NOT NULL,
  `contact_person` varchar(255) NOT NULL,
  `project_manager` varchar(255) NOT NULL,
  `project_captain` varchar(255) NOT NULL,
  `project_assistant_captain` varchar(255) NOT NULL,
  `phone_number` varchar(255) NOT NULL,
  `set_down_date` date NOT NULL,
  `set_down_time` varchar(255) NOT NULL,
  `event_venue` varchar(255) NOT NULL,
  `set_up_time` varchar(255) NOT NULL,
  `estimated_set_up_period` varchar(255) NOT NULL,
  `set_down_team` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`set_down_team`)),
  `pasting_team` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`pasting_team`)),
  `technical_team` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`technical_team`)),
  `logistics_designated_truck` varchar(255) NOT NULL,
  `driver` varchar(255) NOT NULL,
  `loading_team_confirmed` tinyint(1) NOT NULL DEFAULT 0,
  `printed_collateral_shared` tinyint(1) NOT NULL DEFAULT 0,
  `approved_mock_up_shared` tinyint(1) NOT NULL DEFAULT 0,
  `fabrication_preparation` text NOT NULL,
  `time_of_loading_departure` varchar(255) NOT NULL,
  `safety_gear_checker` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `booking_orders_project_id_foreign` (`project_id`),
  CONSTRAINT `booking_orders_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `budget_edit_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `budget_edit_logs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `project_budget_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `changes` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`changes`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `budget_edit_logs_project_budget_id_foreign` (`project_budget_id`),
  KEY `budget_edit_logs_user_id_foreign` (`user_id`),
  CONSTRAINT `budget_edit_logs_project_budget_id_foreign` FOREIGN KEY (`project_budget_id`) REFERENCES `project_budgets` (`id`) ON DELETE CASCADE,
  CONSTRAINT `budget_edit_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `budget_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `budget_items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `project_budget_id` bigint(20) unsigned NOT NULL,
  `category` varchar(255) NOT NULL,
  `item_name` varchar(191) DEFAULT NULL,
  `template_id` bigint(20) unsigned DEFAULT NULL,
  `particular` varchar(255) NOT NULL,
  `unit` varchar(255) DEFAULT NULL,
  `quantity` decimal(10,2) DEFAULT NULL,
  `unit_price` decimal(12,2) DEFAULT NULL,
  `budgeted_cost` decimal(12,2) DEFAULT NULL,
  `comment` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `budget_items_project_budget_id_foreign` (`project_budget_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `categories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `category_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `checkouts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `checkouts` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `inventory_id` bigint(20) unsigned NOT NULL,
  `check_out_id` char(36) NOT NULL,
  `checked_out_by` varchar(255) NOT NULL,
  `received_by` varchar(255) NOT NULL,
  `destination` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `checkouts_inventory_id_foreign` (`inventory_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `clients`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `clients` (
  `ClientID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `FullName` varchar(255) NOT NULL,
  `ContactPerson` varchar(255) DEFAULT NULL,
  `Email` varchar(255) NOT NULL,
  `Phone` varchar(255) NOT NULL,
  `AltContact` varchar(255) DEFAULT NULL,
  `Address` text NOT NULL,
  `City` varchar(255) NOT NULL,
  `County` varchar(255) NOT NULL,
  `PostalAddress` varchar(255) DEFAULT NULL,
  `CustomerType` enum('Individual','Business','Organization') NOT NULL,
  `LeadSource` varchar(255) NOT NULL,
  `PreferredContact` enum('Email','Phone','WhatsApp') NOT NULL,
  `Industry` varchar(255) DEFAULT NULL,
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `CreatedBy` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`ClientID`),
  UNIQUE KEY `clients_email_unique` (`Email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `comments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `comments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `task_id` bigint(20) unsigned NOT NULL,
  `comment` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `comments_user_id_foreign` (`user_id`),
  KEY `comments_task_id_foreign` (`task_id`),
  CONSTRAINT `comments_task_id_foreign` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`id`) ON DELETE CASCADE,
  CONSTRAINT `comments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `defective_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `defective_items` (
  `id` bigint(20) unsigned NOT NULL,
  `sku` varchar(255) NOT NULL,
  `item_name` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL,
  `defect_type` varchar(255) NOT NULL,
  `reported_by` varchar(255) NOT NULL,
  `date_reported` date NOT NULL,
  `remarks` text DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'Pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `deliverables`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `deliverables` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `item` varchar(255) DEFAULT NULL,
  `done` tinyint(1) NOT NULL DEFAULT 0,
  `task_id` bigint(20) unsigned NOT NULL,
  `description` text DEFAULT NULL,
  `completed` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `deliverables_task_id_index` (`task_id`),
  CONSTRAINT `deliverables_task_id_foreign` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `design_assets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `design_assets` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `project_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `file_url` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `design_assets_project_id_foreign` (`project_id`),
  KEY `design_assets_user_id_foreign` (`user_id`),
  CONSTRAINT `design_assets_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE,
  CONSTRAINT `design_assets_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `enquiries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `enquiries` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `converted_to_project_id` bigint(20) unsigned DEFAULT NULL,
  `project_name` varchar(255) DEFAULT NULL,
  `date_received` datetime NOT NULL,
  `expected_delivery_date` date DEFAULT NULL,
  `client_name` varchar(255) NOT NULL,
  `project_deliverables` text DEFAULT NULL,
  `contact_person` varchar(255) DEFAULT NULL,
  `venue` varchar(191) DEFAULT NULL,
  `status` enum('Open','Quoted','Approved','Declined') NOT NULL DEFAULT 'Open',
  `assigned_po` varchar(255) DEFAULT NULL,
  `follow_up_notes` text DEFAULT NULL,
  `project_id` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `enquiries_converted_to_project_id_foreign` (`converted_to_project_id`),
  CONSTRAINT `enquiries_converted_to_project_id_foreign` FOREIGN KEY (`converted_to_project_id`) REFERENCES `projects` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `enquiry_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `enquiry_logs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `project_id` bigint(20) unsigned DEFAULT NULL,
  `project_name` varchar(255) NOT NULL,
  `venue` varchar(255) NOT NULL,
  `date_received` date NOT NULL,
  `client_name` varchar(255) NOT NULL,
  `project_scope_summary` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`project_scope_summary`)),
  `contact_person` varchar(255) DEFAULT NULL,
  `status` enum('Open','Quoted','Approved','Declined') NOT NULL DEFAULT 'Open',
  `assigned_to` varchar(255) DEFAULT NULL,
  `follow_up_notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `enquiry_logs_project_id_foreign` (`project_id`),
  CONSTRAINT `enquiry_logs_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `forhires`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `forhires` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `sku` varchar(255) NOT NULL,
  `client` varchar(255) NOT NULL,
  `contacts` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `hire_fee` decimal(10,2) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'Pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `handover_reports`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `handover_reports` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `project_id` bigint(20) unsigned NOT NULL,
  `title` varchar(191) NOT NULL,
  `description` text DEFAULT NULL,
  `google_drive_link` varchar(191) NOT NULL,
  `uploaded_by` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `handover_reports_project_id_foreign` (`project_id`),
  KEY `handover_reports_uploaded_by_foreign` (`uploaded_by`),
  CONSTRAINT `handover_reports_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE,
  CONSTRAINT `handover_reports_uploaded_by_foreign` FOREIGN KEY (`uploaded_by`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `inventory`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `inventory` (
  `id` bigint(20) unsigned NOT NULL,
  `sku` varchar(255) NOT NULL,
  `item_name` varchar(255) NOT NULL,
  `category_id` bigint(20) unsigned DEFAULT NULL,
  `unit_of_measure` varchar(255) NOT NULL,
  `stock_on_hand` int(11) NOT NULL DEFAULT 0,
  `quantity_checked_in` int(11) NOT NULL DEFAULT 0,
  `quantity_checked_out` int(11) NOT NULL DEFAULT 0,
  `returns` int(11) NOT NULL DEFAULT 0,
  `supplier` varchar(255) NOT NULL,
  `unit_price` decimal(8,2) NOT NULL,
  `total_value` decimal(10,2) NOT NULL DEFAULT 0.00,
  `order_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `item_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `item_categories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) NOT NULL,
  `description` text DEFAULT NULL,
  `created_by` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `item_categories_name_unique` (`name`),
  KEY `item_categories_created_by_foreign` (`created_by`),
  CONSTRAINT `item_categories_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `item_template_particulars`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `item_template_particulars` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `item_template_id` bigint(20) unsigned NOT NULL,
  `particular` varchar(191) NOT NULL,
  `unit` varchar(191) DEFAULT NULL,
  `default_quantity` decimal(10,2) NOT NULL DEFAULT 1.00,
  `comment` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `item_template_particulars_item_template_id_foreign` (`item_template_id`),
  CONSTRAINT `item_template_particulars_item_template_id_foreign` FOREIGN KEY (`item_template_id`) REFERENCES `item_templates` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `item_templates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `item_templates` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `category_id` bigint(20) unsigned NOT NULL,
  `name` varchar(191) NOT NULL,
  `description` text DEFAULT NULL,
  `estimated_cost` decimal(10,2) DEFAULT NULL,
  `created_by` bigint(20) unsigned NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `item_templates_category_id_name_unique` (`category_id`,`name`),
  KEY `item_templates_created_by_foreign` (`created_by`),
  CONSTRAINT `item_templates_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `item_categories` (`id`) ON DELETE CASCADE,
  CONSTRAINT `item_templates_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `labour_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `labour_items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `material_list_id` bigint(20) unsigned NOT NULL,
  `category` varchar(255) NOT NULL,
  `item_name` varchar(255) DEFAULT NULL,
  `particular` varchar(255) DEFAULT NULL,
  `unit` varchar(255) DEFAULT NULL,
  `quantity` decimal(10,2) NOT NULL DEFAULT 0.00,
  `comment` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `labour_items_material_list_id_foreign` (`material_list_id`),
  CONSTRAINT `labour_items_material_list_id_foreign` FOREIGN KEY (`material_list_id`) REFERENCES `material_lists` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `loading_sheets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `loading_sheets` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `project_id` bigint(20) unsigned NOT NULL,
  `vehicle_number` varchar(191) DEFAULT NULL,
  `driver_name` varchar(191) DEFAULT NULL,
  `driver_phone` varchar(191) DEFAULT NULL,
  `loading_point` varchar(191) DEFAULT NULL,
  `unloading_point` varchar(191) DEFAULT NULL,
  `loading_date` date DEFAULT NULL,
  `unloading_date` date DEFAULT NULL,
  `special_instructions` text DEFAULT NULL,
  `items` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`items`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `loading_sheets_project_id_foreign` (`project_id`),
  CONSTRAINT `loading_sheets_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `logistics`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `logistics` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `project_id` bigint(20) unsigned NOT NULL,
  `vehicle_number` varchar(191) NOT NULL,
  `driver_name` varchar(191) NOT NULL,
  `contact` varchar(191) NOT NULL,
  `departure_time` datetime NOT NULL,
  `expected_arrival` datetime NOT NULL,
  `special_instructions` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `logistics_project_id_foreign` (`project_id`),
  CONSTRAINT `logistics_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `logistics_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `logistics_items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `logistics_id` bigint(20) unsigned NOT NULL,
  `description` varchar(191) NOT NULL,
  `quantity` int(11) NOT NULL,
  `unit` varchar(191) NOT NULL,
  `notes` varchar(191) DEFAULT NULL,
  `loaded` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `logistics_items_logistics_id_foreign` (`logistics_id`),
  CONSTRAINT `logistics_items_logistics_id_foreign` FOREIGN KEY (`logistics_id`) REFERENCES `logistics` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `logs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `action` varchar(255) NOT NULL,
  `performed_by` varchar(255) NOT NULL,
  `details` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `material_hires`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `material_hires` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `material_list_id` bigint(20) unsigned NOT NULL,
  `particular` varchar(255) NOT NULL,
  `unit` varchar(255) DEFAULT NULL,
  `quantity` decimal(10,2) NOT NULL DEFAULT 0.00,
  `comment` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `material_hires_material_list_id_foreign` (`material_list_id`),
  CONSTRAINT `material_hires_material_list_id_foreign` FOREIGN KEY (`material_list_id`) REFERENCES `material_lists` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `material_list_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `material_list_items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `material_list_id` bigint(20) unsigned NOT NULL,
  `category` varchar(255) DEFAULT NULL,
  `item_name` varchar(255) DEFAULT NULL,
  `particular` varchar(255) NOT NULL,
  `unit` varchar(255) DEFAULT NULL,
  `quantity` decimal(8,2) DEFAULT NULL,
  `comment` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `material_list_items_material_list_id_foreign` (`material_list_id`),
  CONSTRAINT `material_list_items_material_list_id_foreign` FOREIGN KEY (`material_list_id`) REFERENCES `material_lists` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `material_lists`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `material_lists` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `project_id` bigint(20) unsigned NOT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `approved_by` varchar(255) DEFAULT NULL,
  `approved_departments` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `material_lists_project_id_foreign` (`project_id`),
  CONSTRAINT `material_lists_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `model_has_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `model_has_permissions` (
  `permission_id` bigint(20) unsigned NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `model_has_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `model_has_roles` (
  `role_id` bigint(20) unsigned NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `newstock`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `newstock` (
  `id` bigint(20) unsigned NOT NULL,
  `sku` varchar(255) NOT NULL,
  `item_name` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL,
  `supplier` varchar(255) NOT NULL,
  `added_on` timestamp NOT NULL DEFAULT '2025-04-12 08:30:55',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `permissions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `phase_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `phase_logs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `phase_id` bigint(20) unsigned NOT NULL,
  `action` varchar(255) NOT NULL,
  `details` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `task_name` varchar(255) DEFAULT NULL,
  `deliverables` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`deliverables`)),
  `task_status` enum('Pending','In Progress','Completed') NOT NULL DEFAULT 'Pending',
  PRIMARY KEY (`id`),
  KEY `phase_logs_phase_id_foreign` (`phase_id`),
  CONSTRAINT `phase_logs_phase_id_foreign` FOREIGN KEY (`phase_id`) REFERENCES `phases` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `phases`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `phases` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `project_id` bigint(20) unsigned NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'Not Started',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `phases_project_id_foreign` (`project_id`),
  CONSTRAINT `phases_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `production_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `production_items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `material_list_id` bigint(20) unsigned NOT NULL,
  `item_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `template_id` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `production_items_material_list_id_foreign` (`material_list_id`),
  KEY `production_items_template_id_foreign` (`template_id`),
  CONSTRAINT `production_items_material_list_id_foreign` FOREIGN KEY (`material_list_id`) REFERENCES `material_lists` (`id`) ON DELETE CASCADE,
  CONSTRAINT `production_items_template_id_foreign` FOREIGN KEY (`template_id`) REFERENCES `item_templates` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `production_particulars`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `production_particulars` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `production_item_id` bigint(20) unsigned NOT NULL,
  `particular` varchar(255) NOT NULL,
  `unit` varchar(255) DEFAULT NULL,
  `quantity` decimal(10,2) NOT NULL DEFAULT 0.00,
  `comment` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `production_particulars_production_item_id_foreign` (`production_item_id`),
  CONSTRAINT `production_particulars_production_item_id_foreign` FOREIGN KEY (`production_item_id`) REFERENCES `production_items` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `production_tasks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `production_tasks` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `production_id` bigint(20) unsigned NOT NULL,
  `title` varchar(191) NOT NULL,
  `description` text DEFAULT NULL,
  `status` enum('pending','in_progress','completed') NOT NULL DEFAULT 'pending',
  `due_date` date DEFAULT NULL,
  `assigned_to` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `production_tasks_production_id_foreign` (`production_id`),
  CONSTRAINT `production_tasks_production_id_foreign` FOREIGN KEY (`production_id`) REFERENCES `productions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `productions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `productions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `project_id` bigint(20) unsigned NOT NULL,
  `job_number` varchar(191) NOT NULL,
  `project_title` varchar(191) NOT NULL,
  `client_name` varchar(191) NOT NULL,
  `briefing_date` date NOT NULL,
  `briefed_by` varchar(191) NOT NULL,
  `delivery_date` date NOT NULL,
  `production_team` text NOT NULL,
  `materials_required` text DEFAULT NULL,
  `key_instructions` text DEFAULT NULL,
  `special_considerations` text DEFAULT NULL,
  `files_received` tinyint(1) NOT NULL,
  `additional_notes` text DEFAULT NULL,
  `status` enum('pending','approved','rejected','completed') NOT NULL DEFAULT 'pending',
  `status_notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `productions_project_id_foreign` (`project_id`),
  CONSTRAINT `productions_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `project_budgets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `project_budgets` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `project_id` bigint(20) unsigned NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'draft',
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `budget_total` decimal(12,2) DEFAULT NULL,
  `invoice` varchar(255) DEFAULT NULL,
  `profit` decimal(12,2) DEFAULT NULL,
  `approved_by` varchar(255) DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `approved_departments` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `project_budgets_project_id_foreign` (`project_id`),
  CONSTRAINT `project_budgets_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `project_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `project_logs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `project_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `action` varchar(255) NOT NULL,
  `details` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `project_logs_project_id_foreign` (`project_id`),
  KEY `project_logs_user_id_foreign` (`user_id`),
  CONSTRAINT `project_logs_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE,
  CONSTRAINT `project_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `project_phases`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `project_phases` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `project_id` bigint(20) unsigned NOT NULL,
  `name` varchar(191) NOT NULL,
  `description` text DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `status` varchar(191) NOT NULL DEFAULT 'Not Started',
  `icon` varchar(191) DEFAULT NULL,
  `summary` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `project_phases_project_id_foreign` (`project_id`),
  CONSTRAINT `project_phases_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `projects`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `projects` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `project_id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `client_name` varchar(255) NOT NULL,
  `client_id` bigint(20) unsigned NOT NULL,
  `venue` varchar(255) NOT NULL,
  `deliverables` text DEFAULT NULL,
  `follow_up_notes` text DEFAULT NULL,
  `contact_person` varchar(255) DEFAULT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `project_manager_id` bigint(20) unsigned NOT NULL,
  `project_officer_id` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `quotation_path` varchar(255) DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'active',
  PRIMARY KEY (`id`),
  UNIQUE KEY `projects_project_id_unique` (`project_id`),
  KEY `projects_project_manager_id_foreign` (`project_manager_id`),
  KEY `projects_project_officer_id_foreign` (`project_officer_id`),
  KEY `projects_client_id_foreign` (`client_id`),
  CONSTRAINT `projects_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`ClientID`) ON DELETE CASCADE,
  CONSTRAINT `projects_project_manager_id_foreign` FOREIGN KEY (`project_manager_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `projects_project_officer_id_foreign` FOREIGN KEY (`project_officer_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `quote_line_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `quote_line_items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `quote_id` bigint(20) unsigned NOT NULL,
  `description` text NOT NULL,
  `days` int(11) NOT NULL DEFAULT 1,
  `quantity` decimal(10,2) NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `profit_margin` decimal(5,2) NOT NULL DEFAULT 0.00,
  `quote_price` decimal(12,2) NOT NULL DEFAULT 0.00,
  `total_cost` decimal(12,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `quote_line_items_quote_id_foreign` (`quote_id`),
  CONSTRAINT `quote_line_items_quote_id_foreign` FOREIGN KEY (`quote_id`) REFERENCES `quotes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `quotes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `quotes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `project_id` bigint(20) unsigned NOT NULL,
  `customer_name` varchar(255) NOT NULL,
  `customer_location` varchar(255) DEFAULT NULL,
  `attention` varchar(255) DEFAULT NULL,
  `quote_date` date NOT NULL,
  `project_start_date` date DEFAULT NULL,
  `reference` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `quotes_project_id_foreign` (`project_id`),
  CONSTRAINT `quotes_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `return_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `return_items` (
  `id` bigint(20) unsigned NOT NULL,
  `sku` varchar(255) NOT NULL,
  `item_name` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL,
  `reason` text DEFAULT NULL,
  `return_date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `returns`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `returns` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `sku` varchar(255) NOT NULL,
  `item_name` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL,
  `reason` text DEFAULT NULL,
  `return_date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `role_has_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `role_has_permissions` (
  `permission_id` bigint(20) unsigned NOT NULL,
  `role_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `role_has_permissions_role_id_foreign` (`role_id`),
  CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `roles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `set_down_returns`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `set_down_returns` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `project_id` bigint(20) unsigned NOT NULL,
  `title` varchar(191) NOT NULL,
  `description` text DEFAULT NULL,
  `google_drive_link` varchar(191) NOT NULL,
  `uploaded_by` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `set_down_returns_project_id_foreign` (`project_id`),
  KEY `set_down_returns_uploaded_by_foreign` (`uploaded_by`),
  CONSTRAINT `set_down_returns_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE,
  CONSTRAINT `set_down_returns_uploaded_by_foreign` FOREIGN KEY (`uploaded_by`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `setup_reports`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `setup_reports` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `project_id` bigint(20) unsigned NOT NULL,
  `title` varchar(191) NOT NULL,
  `description` text DEFAULT NULL,
  `google_drive_link` text NOT NULL,
  `uploaded_by` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `setup_reports_project_id_foreign` (`project_id`),
  KEY `setup_reports_uploaded_by_foreign` (`uploaded_by`),
  CONSTRAINT `setup_reports_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE,
  CONSTRAINT `setup_reports_uploaded_by_foreign` FOREIGN KEY (`uploaded_by`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `site_surveys`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `site_surveys` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `project_id` bigint(20) unsigned NOT NULL,
  `site_visit_date` date NOT NULL,
  `project_manager` varchar(255) NOT NULL,
  `client_name` varchar(255) NOT NULL,
  `location` varchar(255) NOT NULL,
  `attendees` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`attendees`)),
  `client_contact_person` varchar(255) NOT NULL,
  `client_phone` varchar(255) NOT NULL,
  `client_email` varchar(255) DEFAULT NULL,
  `project_description` text DEFAULT NULL,
  `objectives` text DEFAULT NULL,
  `current_condition` text DEFAULT NULL,
  `existing_branding` text DEFAULT NULL,
  `access_logistics` text DEFAULT NULL,
  `parking_availability` varchar(255) DEFAULT NULL,
  `size_accessibility` text DEFAULT NULL,
  `lifts` text DEFAULT NULL,
  `door_sizes` text DEFAULT NULL,
  `loading_areas` text DEFAULT NULL,
  `site_measurements` text DEFAULT NULL,
  `room_size` text DEFAULT NULL,
  `constraints` text DEFAULT NULL,
  `electrical_outlets` text DEFAULT NULL,
  `food_refreshment` text DEFAULT NULL,
  `branding_preferences` text DEFAULT NULL,
  `material_preferences` text DEFAULT NULL,
  `color_scheme` text DEFAULT NULL,
  `brand_guidelines` text DEFAULT NULL,
  `special_instructions` text DEFAULT NULL,
  `project_start_date` datetime DEFAULT NULL,
  `project_deadline` datetime DEFAULT NULL,
  `milestones` text DEFAULT NULL,
  `safety_conditions` text DEFAULT NULL,
  `potential_hazards` text DEFAULT NULL,
  `safety_requirements` text DEFAULT NULL,
  `additional_notes` text DEFAULT NULL,
  `special_requests` text DEFAULT NULL,
  `action_items` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`action_items`)),
  `prepared_by` varchar(255) DEFAULT NULL,
  `prepared_signature` varchar(255) DEFAULT NULL,
  `prepared_date` date DEFAULT NULL,
  `client_approval` varchar(255) DEFAULT NULL,
  `client_signature` varchar(255) DEFAULT NULL,
  `client_approval_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `site_surveys_project_id_foreign` (`project_id`),
  CONSTRAINT `site_surveys_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `tasks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tasks` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `phase_id` bigint(20) unsigned DEFAULT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `assigned_to` varchar(255) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'Pending',
  `description` text DEFAULT NULL,
  `comment` text DEFAULT NULL,
  `file` varchar(255) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tasks_phase_id_foreign` (`phase_id`),
  KEY `tasks_user_id_foreign` (`user_id`),
  CONSTRAINT `tasks_phase_id_foreign` FOREIGN KEY (`phase_id`) REFERENCES `phases` (`id`) ON DELETE SET NULL,
  CONSTRAINT `tasks_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `telescope_entries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `telescope_entries` (
  `sequence` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(36) NOT NULL,
  `batch_id` char(36) NOT NULL,
  `family_hash` varchar(191) DEFAULT NULL,
  `should_display_on_index` tinyint(1) NOT NULL DEFAULT 1,
  `type` varchar(20) NOT NULL,
  `content` longtext NOT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`sequence`),
  UNIQUE KEY `telescope_entries_uuid_unique` (`uuid`),
  KEY `telescope_entries_batch_id_index` (`batch_id`),
  KEY `telescope_entries_family_hash_index` (`family_hash`),
  KEY `telescope_entries_created_at_index` (`created_at`),
  KEY `telescope_entries_type_should_display_on_index_index` (`type`,`should_display_on_index`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `telescope_entries_tags`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `telescope_entries_tags` (
  `entry_uuid` char(36) NOT NULL,
  `tag` varchar(191) NOT NULL,
  PRIMARY KEY (`entry_uuid`,`tag`),
  KEY `telescope_entries_tags_tag_index` (`tag`),
  CONSTRAINT `telescope_entries_tags_entry_uuid_foreign` FOREIGN KEY (`entry_uuid`) REFERENCES `telescope_entries` (`uuid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `telescope_monitoring`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `telescope_monitoring` (
  `tag` varchar(191) NOT NULL,
  PRIMARY KEY (`tag`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `role` enum('super-admin','admin','pm','po','store','logistics','procurement','User') DEFAULT 'User',
  `department` varchar(255) DEFAULT NULL,
  `level` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (1,'0001_01_01_000000_create_users_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (2,'0001_01_01_000001_create_cache_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (3,'0001_01_01_000002_create_jobs_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (4,'2025_04_09_133727_add_role_and_department_to_users_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (5,'2025_04_12_105338_create_inventory_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (6,'2025_04_12_112822_create__new_stock_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (7,'2025_04_12_230915_create_checkouts_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (8,'2025_04_13_093409_create_categories_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (9,'2025_04_13_093551_add_category_id_to_inventory_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (10,'2025_04_13_100436_remove_category_name_from_inventory_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (11,'2025_04_13_101713_drop_category_foreign_key_from_inventory_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (12,'2025_04_13_104945_add_category_id_to_inventory_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (13,'2025_04_13_134201_create_returns_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (14,'2025_04_13_140029_create_return_items_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (15,'2025_04_13_155352_create_defective_items_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (16,'2025_04_16_134449_create_forhires_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (17,'2025_04_17_090633_add_quantity_to_forhires_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (18,'2025_04_18_121657_create_logs_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (19,'2025_04_19_045233_create_projects_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (20,'2025_04_19_091205_create_phases_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (21,'2025_04_22_000000_create_phase_logs_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (22,'2025_04_23_065021_update_phase_logs_table_for_tasks',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (23,'2025_04_23_123136_update_deliverables_to_array',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (24,'2025_04_29_184449_create_clients_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (25,'2025_04_29_194309_add_project_id_and_client_id_to_projects_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (26,'2025_04_30_115442_create_tasks_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (27,'2025_04_30_121814_create_deliverables_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (28,'2025_05_01_120500_migrate_json_deliverables_to_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (29,'2025_05_01_202715_alter_clients_table_change_leadsource_type',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (30,'2025_05_07_141302_create_permission_tables',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (31,'2025_05_07_234933_add_level_to_users_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (32,'2025_05_08_011029_update_user_role_enum',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (33,'2025_05_09_add_user_id_to_tasks_table',2);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (34,'2025_05_09_add_file_to_tasks_table',3);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (35,'2025_05_09_add_item_to_deliverables_table',4);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (36,'2025_05_09_add_done_to_deliverables_table',5);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (37,'2025_05_09_add_default_to_description_in_deliverables_table',6);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (38,'2025_05_11_084755_create_booking_orders_table',7);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (39,'2025_05_11_233611_create_booking_orders_table',8);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (40,'2025_05_12_012724_create_booking_order_teams_table',9);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (41,'2025_05_15_134728_create_comments_table',10);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (42,'2025_05_15_134811_create_attachments_table',10);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (43,'2025_05_16_073953_create_tasks_table',11);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (44,'2025_05_16_074757_create_comments_table',12);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (45,'2025_05_16_074825_create_attachments_table',12);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (46,'2025_05_16_075047_create_comments_table',13);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (47,'2025_05_16_075053_create_attachments_table',13);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (48,'2025_05_16_080913_add_task_id_to_attachments_table',14);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (49,'2025_05_16_081131_drop_comment_id_from_attachments_table',15);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (50,'2025_05_16_091531_create_tasks_table',16);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (51,'2025_05_16_093337_create_tasks_table',17);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (52,'2025_05_24_153234_add_deleted_at_to_inventory_table',18);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (53,'2025_05_27_112600_create_inquiry_logs_table',19);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (54,'2025_05_28_062237_create_enquiry_logs_table',20);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (55,'2025_05_28_183843_add_project_id_to_enquiry_logs_table',21);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (56,'2025_06_04_070610_add_quotation_path_to_projects_table',22);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (57,'2025_06_08_090000_create_quotes_table',23);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (58,'2025_06_08_093836_add_project_id_to_quotes_table',24);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (59,'2025_06_10_135928_create_design_assets_table',25);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (60,'2025_06_12_124606_create_site_surveys_table',26);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (61,'2025_06_15_053215_create_close_out_reports_table',27);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (62,'2025_06_15_053556_create_close_out_report_attachments_table',27);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (63,'2025_06_15_140703_add_status_to_projects_table',28);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (64,'2025_06_10_100842_create_enquiries_table',29);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (65,'2025_06_10_150935_add_project_name_to_enquiries_table',29);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (66,'2025_06_18_052003_create_enquiries_table',30);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (67,'2025_06_18_052702_add_project_name_and_enquiry_number_to_enquiries_table',31);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (68,'2025_06_19_221634_drop_file_type_and_file_size_from_design_assets_table',32);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (69,'2025_06_20_092919_create_project_budgets_table',33);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (70,'2025_06_20_093024_create_budget_items_table',33);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (71,'2025_06_21_172146_add_status_to_project_budgets_table',34);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (72,'2025_06_24_081415_create_materials_table',35);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (73,'2025_06_24_212050_add_deliverables_and_follow_up_notes_and_status_and_contact_person_to_projects',36);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (74,'2025_06_24_220859_add_converted_to_project_id_to_enquiries_table',37);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (75,'2025_06_26_113407_create_material_lists_table',38);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (76,'2025_06_26_113407_create_production_items_table',38);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (77,'2025_06_26_113408_create_production_particulars_table',38);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (78,'2025_06_26_113409_create_material_hires_table',38);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (79,'2025_06_26_113410_create_labour_items_table',38);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (80,'2025_06_26_113749_create_material_lists_table',39);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (81,'2025_06_26_114057_create_production_items_table',40);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (82,'2025_06_26_114212_create_production_particulars_table',41);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (83,'2025_06_26_114350_create_material_hires_table',42);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (84,'2025_06_26_114445_create_labour_items_table',43);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (85,'2025_06_26_124520_create_material_list_items_table',44);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (86,'2025_06_26_125746_create_material_list_items_table',45);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (87,'2025_06_26_142856_make_particular_nullable_in_labour_items_table',46);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (88,'2025_06_26_181500_add_item_name_to_labour_items_table',47);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (89,'2025_06_28_083235_create_telescope_entries_table',48);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (90,'2025_06_30_115525_create_project_phases_table',49);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (91,'2025_06_30_120310_add_progress_to_projects_table',49);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (92,'2025_06_30_120502_add_progress_to_projects_table',49);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (93,'2025_06_24_000000_create_productions_table',50);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (94,'2025_06_27_071521_create_logistics_table',50);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (95,'2025_06_27_071625_create_logistics_items_table',50);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (96,'2025_06_28_083132_create_setup_reports_table',50);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (97,'2025_06_28_101211_create_handover_reports_table',50);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (98,'2025_06_28_101826_create_set_down_returns_table',50);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (99,'2025_06_28_102619_create_archival_reports_table',50);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (100,'2025_07_01_072644_alter_date_received_column_on_enquiries_table',51);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (101,'2025_07_02_062129_create_project_logs_table',52);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (102,'2025_06_21_180000_create_budget_edit_logs_table',53);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (103,'2025_07_02_150135_add_approved_at_to_project_budgets_table',54);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (104,'2025_07_02_160000_create_project_phases_table',55);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (105,'2025_07_02_213427_add_name_icon_summary_to_project_phases_table',56);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (106,'2025_07_02_213909_rename_title_to_name_in_project_phases_table',56);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (107,'2025_07_02_213933_rename_title_to_name_in_project_phases_table',56);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (108,'2025_07_02_214420_rename_title_to_name_in_project_phases_table',57);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (109,'2025_07_02_223840_create_project_phases_table',58);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (110,'2025_07_02_223840_add_venue_to_enquiries_table',59);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (111,'2025_07_04_113631_add_venue_to_enquiries_table',59);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (112,'2025_07_05_023025_add_profit_margin_and_quote_price_to_quote_line_items_table',60);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (113,'2025_07_06_031331_remove_design_reference_columns',61);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (114,'2025_07_06_032336_create_item_categories_table',62);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (115,'2025_07_06_032345_create_item_templates_table',62);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (116,'2025_07_06_032401_create_item_template_particulars_table',62);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (117,'2025_01_27_000000_add_template_id_to_production_items_table',63);
