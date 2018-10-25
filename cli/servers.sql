CREATE TABLE `servers` (
	`id` varchar(32) CHARACTER SET ascii NOT NULL,
	`ip` varchar(64) CHARACTER SET ascii NOT NULL,
	`port` smallint(5) UNSIGNED NOT NULL DEFAULT '27960',
	`game` tinytext COLLATE utf8mb4_bin NOT NULL,
	`version` tinytext COLLATE utf8mb4_bin NOT NULL,
	`data` text COLLATE utf8mb4_bin NOT NULL,
	`response` tinyint(1) NOT NULL DEFAULT '-1',
	`seen` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;
