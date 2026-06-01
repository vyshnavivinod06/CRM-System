-- Optimized table definition for the provided leads query.
-- Key optimization: the query filters by account_id and deleted_at, orders by id DESC,
-- and returns the first 100 records. This composite index lets MySQL seek directly to
-- the matching account/non-deleted rows in id order instead of scanning/sorting millions
-- of records.

CREATE TABLE `leads` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `account_id` smallint unsigned NOT NULL,
  `organisation_id` int unsigned DEFAULT NULL,
  `team_id` int unsigned DEFAULT NULL,
  `activity` varchar(70) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `status` varchar(60) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `gender` enum('male','female') COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `firstname` varchar(120) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `lastname` varchar(120) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `postcode` varchar(6) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `housenumber` varchar(5) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `suffix` varchar(6) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `streetname` varchar(80) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `city` varchar(80) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `country` varchar(2) COLLATE utf8mb3_unicode_ci DEFAULT 'NL',
  `birthdate` date DEFAULT NULL,
  `email` varchar(120) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `phone` varchar(15) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `business` enum('0','1') COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT '0',
  `company_name` varchar(120) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `contact_person` varchar(120) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `data` text COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `attachments` text COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `history` text COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `created_by` int unsigned DEFAULT NULL,
  `created_ip` varchar(45) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `planned_user_id` int unsigned DEFAULT NULL,
  `planned_date` date DEFAULT NULL,
  `planned_by` int unsigned DEFAULT NULL,
  `planned_at` timestamp NULL DEFAULT NULL,
  `planned_from` timestamp NULL DEFAULT NULL,
  `planned_to` timestamp NULL DEFAULT NULL,
  `planned_duration` smallint(6) DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `completed_team_id` int unsigned DEFAULT NULL,
  `completed_by` int unsigned DEFAULT NULL,
  `started_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `leads_account_deleted_id_desc_idx` (`account_id`, `deleted_at`, `id` DESC),
  KEY `leads_account_planned_user_idx` (`account_id`, `planned_user_id`),
  KEY `leads_account_team_idx` (`account_id`, `team_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- Equivalent ALTER for an existing production table:
ALTER TABLE `leads`
  ADD INDEX `leads_account_deleted_id_desc_idx` (`account_id`, `deleted_at`, `id` DESC),
  ADD INDEX `leads_account_planned_user_idx` (`account_id`, `planned_user_id`),
  ADD INDEX `leads_account_team_idx` (`account_id`, `team_id`);

-- Final optimized query. The selected data and order match the original query.
SELECT
  `leads`.`id`,
  `leads`.`firstname`,
  `leads`.`lastname`,
  `leads`.`gender`,
  `leads`.`company_name`,
  `leads`.`business`,
  `leads`.`streetname`,
  `leads`.`housenumber`,
  `leads`.`suffix`,
  `leads`.`postcode`,
  `leads`.`city`,
  `leads`.`status`,
  `leads`.`organisation_id`,
  `leads`.`team_id`,
  `leads`.`planned_user_id`,
  `leads`.`created_by`,
  DATE_FORMAT(`leads`.`created_at`, "%d-%c-%Y %H:%i") AS `created_datetime`,
  DATE_FORMAT(`leads`.`updated_at`, "%d-%c-%Y %H:%i") AS `updated_datetime`,
  DATE_FORMAT(`leads`.`planned_date`, "%d-%c-%Y") AS `planned_date_formatted`,
  DATE_FORMAT(`leads`.`planned_from`, "%H:%i") AS `planned_from_time`,
  DATE_FORMAT(`leads`.`planned_to`, "%H:%i") AS `planned_to_time`
FROM `leads`
WHERE
  `leads`.`account_id` = 1
  AND `leads`.`deleted_at` IS NULL
ORDER BY
  `leads`.`id` DESC
LIMIT 100 OFFSET 0;
