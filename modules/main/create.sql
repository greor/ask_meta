CREATE TABLE `admins` (
	`id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
	`site_id` INT(10) UNSIGNED NOT NULL,
	`email` VARCHAR(254) NOT NULL DEFAULT '',
	`username` VARCHAR(32) NOT NULL DEFAULT '',
	`password` VARCHAR(64) NOT NULL DEFAULT '',
	`role` ENUM('base','full','main','super') NOT NULL,
	`token` VARCHAR(32) NOT NULL DEFAULT '',
	`logins` INT(10) UNSIGNED NOT NULL DEFAULT '0',
	`last_login` INT(10) UNSIGNED NULL DEFAULT NULL,
	`attempts` INT(10) UNSIGNED NOT NULL DEFAULT '0',
	`last_attempt` INT(10) UNSIGNED NULL DEFAULT NULL,
	`active` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
	`created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`creator_id` INT(10) NOT NULL DEFAULT '0',
	`updated` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
	`updater_id` INT(10) NOT NULL DEFAULT '0',
	`deleted` TINYINT(1) NOT NULL DEFAULT '0',
	`deleter_id` INT(10) NOT NULL DEFAULT '0',
	`delete_bit` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
	PRIMARY KEY (`id`)
)
COLLATE='utf8_general_ci'
ENGINE=MyISAM
AUTO_INCREMENT=2
;

INSERT INTO `admins` (`id`, `site_id`, `email`, `username`, `password`, `role`, `token`, `logins`, `last_login`, `attempts`, `last_attempt`, `active`, `created`, `creator_id`, `updated`, `updater_id`, `deleted`, `deleter_id`, `delete_bit`) VALUES (1, 1, 'g.gudin@kubikrubik.ru', 'superadmin', '$2a$12$Uc5UVUwpOMtMGUHOAOcRS.ut0bYqxVss.SmpUjrWj05iextmv5CQW', 'super', '$2a$12$U3zfQylz1ila8hMgYeGVK.EgD', 0, 0, 0, 0, 1, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 0, 0, 0, 0);



CREATE TABLE `admin_fail_logins` (
	`login` VARCHAR(63) NOT NULL DEFAULT '',
	`password` VARCHAR(127) NOT NULL DEFAULT '',
	`ip` VARCHAR(63) NOT NULL DEFAULT '',
	`user_agent` VARCHAR(127) NOT NULL DEFAULT '',
	`time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	INDEX `login` (`login`(16)),
	INDEX `ip` (`ip`(16)),
	INDEX `time` (`time`)
)
COLLATE='utf8_general_ci'
ENGINE=MyISAM
;



CREATE TABLE `hided_list` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`object_name` VARCHAR(50) NULL DEFAULT NULL,
	`site_id` INT(10) NULL DEFAULT NULL,
	`element_id` INT(10) NULL DEFAULT NULL,
	PRIMARY KEY (`id`),
	INDEX `object_name_site_id` (`object_name`, `site_id`)
)
COLLATE='utf8_general_ci'
ENGINE=MyISAM
;

CREATE TABLE `properties` (
	`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`owner` VARCHAR(255) NOT NULL,
	`owner_id` INT(10) UNSIGNED NOT NULL DEFAULT '0',
	`name` VARCHAR(255) NOT NULL,
	`type` VARCHAR(255) NOT NULL DEFAULT '',
	`value` VARCHAR(255) NOT NULL DEFAULT '',
	`created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`creator_id` INT(10) NOT NULL DEFAULT '0',
	`updated` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
	`updater_id` INT(10) NOT NULL DEFAULT '0',
	`deleted` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
	`deleter_id` INT(10) NOT NULL DEFAULT '0',
	`delete_bit` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
	PRIMARY KEY (`id`)
)
COLLATE='utf8_general_ci'
ENGINE=MyISAM
;

CREATE TABLE `properties_enum` (
	`property_id` INT(10) UNSIGNED NOT NULL DEFAULT '0',
	`value_id` INT(11) NOT NULL,
	PRIMARY KEY (`property_id`, `value_id`)
)
COLLATE='utf8_general_ci'
ENGINE=MyISAM
;

CREATE TABLE `properties_text` (
	`property_id` INT(10) UNSIGNED NOT NULL DEFAULT '0',
	`value` TEXT NOT NULL,
	PRIMARY KEY (`property_id`)
)
COLLATE='utf8_general_ci'
ENGINE=MyISAM
;

CREATE TABLE `pages` (
	`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`site_id` INT(10) UNSIGNED NOT NULL DEFAULT '0',
	`name` VARCHAR(255) NOT NULL DEFAULT '',
	`status` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
	`for_all` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
	`can_hiding` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
	`parent_id` INT(10) UNSIGNED NOT NULL DEFAULT '0',
	`uri` VARCHAR(255) NOT NULL,
	`title` VARCHAR(255) NOT NULL DEFAULT '',
	`text` TEXT NOT NULL,
	`type` ENUM('static','module','page','url') NOT NULL DEFAULT 'static',
	`data` TEXT NOT NULL,
	`position` INT(10) UNSIGNED NOT NULL DEFAULT '0',
	`level` INT(10) UNSIGNED NOT NULL DEFAULT '0',
	`title_tag` VARCHAR(255) NOT NULL DEFAULT '',
	`keywords_tag` VARCHAR(255) NOT NULL DEFAULT '',
	`description_tag` VARCHAR(255) NOT NULL DEFAULT '',
	`sm_changefreq` ENUM('always','hourly','daily','weekly','monthly','yearly','never') NOT NULL DEFAULT 'daily',
	`sm_priority` VARCHAR(3) NOT NULL DEFAULT '0.5',
	`sm_separate_file` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
	`created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`creator_id` INT(10) NOT NULL DEFAULT '0',
	`updated` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
	`updater_id` INT(10) NOT NULL DEFAULT '0',
	`deleted` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
	`deleter_id` INT(10) NOT NULL DEFAULT '0',
	`delete_bit` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
	PRIMARY KEY (`id`),
	INDEX `site_id_parent_id_uri_delete` (`site_id`, `parent_id`, `uri`, `deleted`),
	INDEX `id_site_id_delete` (`id`, `site_id`, `deleted`, `name`)
)
COLLATE='utf8_general_ci'
ENGINE=MyISAM
;



CREATE TABLE `sites` (
	`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`code` VARCHAR(255) NOT NULL,
	`name` VARCHAR(255) NOT NULL,
	`logo` VARCHAR(255) NOT NULL,
	`image` VARCHAR(255) NOT NULL,
	`type` ENUM('master','full','base') NOT NULL,
	`mmt` VARCHAR(6) NOT NULL DEFAULT '+04:00',
	`active` TINYINT(1) UNSIGNED ZEROFILL NOT NULL DEFAULT '1',
	`sharing_image` VARCHAR(255) NOT NULL DEFAULT '',
	`title_tag` VARCHAR(255) NOT NULL DEFAULT '',
	`keywords_tag` VARCHAR(255) NOT NULL DEFAULT '',
	`description_tag` VARCHAR(255) NOT NULL DEFAULT '',
	`created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`creator_id` INT(10) NOT NULL DEFAULT '0',
	`updated` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
	`updater_id` INT(10) NOT NULL DEFAULT '0',
	`deleted` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
	`deleter_id` INT(10) NOT NULL DEFAULT '0',
	`delete_bit` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
	PRIMARY KEY (`id`)
)
COLLATE='utf8_general_ci'
ENGINE=MyISAM
;



CREATE TABLE `sites_properties` (
	`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`site_id` INT(10) UNSIGNED NOT NULL DEFAULT '0',
	`name` VARCHAR(255) NOT NULL,
	`type` VARCHAR(255) NOT NULL DEFAULT '',
	`value` VARCHAR(255) NOT NULL DEFAULT '',
	`created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`creator_id` INT(10) NOT NULL DEFAULT '0',
	`updated` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
	`updater_id` INT(10) NOT NULL DEFAULT '0',
	`deleted` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
	`deleter_id` INT(10) NOT NULL DEFAULT '0',
	`delete_bit` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
	PRIMARY KEY (`id`)
)
COLLATE='utf8_general_ci'
ENGINE=MyISAM
;

CREATE TABLE `likes` (
	`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`element_id` INT(10) UNSIGNED NOT NULL,
	`model` VARCHAR(50) NOT NULL,
	`count` INT(10) NOT NULL DEFAULT '0',
	PRIMARY KEY (`id`)
)
COLLATE='utf8_general_ci'
ENGINE=MyISAM
;

CREATE TABLE `likes_expires` (
	`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`like_id` INT(10) UNSIGNED NOT NULL DEFAULT '1',
	`limit` INT(10) UNSIGNED NOT NULL DEFAULT '1',
	`ip` VARCHAR(255) NOT NULL DEFAULT '',
	`user_agent` VARCHAR(255) NOT NULL DEFAULT '',
	`expires` INT(10) UNSIGNED NOT NULL DEFAULT '0',
	PRIMARY KEY (`id`)
)
COLLATE='utf8_general_ci'
ENGINE=MyISAM
;

CREATE TABLE `forms` (
	`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`site_id` INT(10) UNSIGNED NOT NULL DEFAULT '0',
	`owner` VARCHAR(255) NOT NULL DEFAULT '',
	`title` VARCHAR(255) NOT NULL DEFAULT '',
	`email` VARCHAR(255) NOT NULL DEFAULT '',
	`text` TEXT NOT NULL,
	`text_show_top` TINYINT(1) NOT NULL DEFAULT '0',
	`captcha` TINYINT(1) NOT NULL DEFAULT '1',
	`active` TINYINT(1) NOT NULL DEFAULT '1',
	`created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`creator_id` INT(10) NOT NULL DEFAULT '0',
	`updated` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
	`updater_id` INT(10) NOT NULL DEFAULT '0',
	`deleted` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
	`deleter_id` INT(10) NOT NULL DEFAULT '0',
	`delete_bit` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
	`public_date` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
	`close_date` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
	PRIMARY KEY (`id`)
)
COLLATE='utf8_general_ci'
ENGINE=MyISAM
ROW_FORMAT=DEFAULT;

CREATE TABLE `forms_fields` (
	`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`form_id` INT(10) UNSIGNED NOT NULL DEFAULT '0',
	`type` VARCHAR(255) NOT NULL DEFAULT '',
	`title` VARCHAR(255) NOT NULL DEFAULT '',
	`default` VARCHAR(255) NOT NULL DEFAULT '',
	`required` TINYINT(1) NOT NULL DEFAULT '1',
	`additional` TEXT NOT NULL,
	`position` INT(10) UNSIGNED NOT NULL DEFAULT '0',
	`created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`creator_id` INT(10) NOT NULL DEFAULT '0',
	`updated` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
	`updater_id` INT(10) NOT NULL DEFAULT '0',
	`deleted` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
	`deleter_id` INT(10) NOT NULL DEFAULT '0',
	`delete_bit` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
	PRIMARY KEY (`id`)
)
COLLATE='utf8_general_ci'
ENGINE=MyISAM
ROW_FORMAT=DEFAULT;

CREATE TABLE `forms_responses` (
	`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`site_id` INT(10) UNSIGNED NOT NULL DEFAULT '0',
	`form_id` INT(10) UNSIGNED NOT NULL DEFAULT '0',
	`email` VARCHAR(255) NOT NULL DEFAULT '',
	`text` TEXT NOT NULL,
	`new` TINYINT(1) NOT NULL DEFAULT '1',
	`created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`creator_id` INT(10) NOT NULL DEFAULT '0',
	`updated` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
	`updater_id` INT(10) NOT NULL DEFAULT '0',
	`deleted` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
	`deleter_id` INT(10) NOT NULL DEFAULT '0',
	`delete_bit` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
	PRIMARY KEY (`id`)
)
COLLATE='utf8_general_ci'
ENGINE=MyISAM
ROW_FORMAT=DEFAULT;


INSERT INTO `sites` (`id`, `code`, `name`, `logo`, `image`, `type`, `mmt`, `active`, `sharing_image`, `title_tag`, `keywords_tag`, `description_tag`, `created`, `creator_id`, `updated`, `updater_id`, `deleted`, `deleter_id`, `delete_bit`) VALUES (1, '', 'Федеральный сайт', '', '', 'master', '00:00', 1, '', 'Федеральный сайт', '', '', '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 0, 0);

