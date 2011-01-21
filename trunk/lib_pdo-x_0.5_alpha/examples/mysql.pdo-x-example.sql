CREATE DATABASE `pdo-x-example` ;

USE `pdo-x-example`;

 CREATE TABLE `pdo-x-example`.`person` (
`id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`name` VARCHAR( 100 ) NOT NULL ,
`phone_number` VARCHAR( 100 ) NOT NULL ,
`email` VARCHAR( 100 ) NOT NULL ,
UNIQUE (
`name` ,
`email`
)
) ENGINE = InnoDB;


 CREATE TABLE `pdo-x-example`.`groups` (
`id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`name` VARCHAR( 100 ) NOT NULL ,
UNIQUE (
`name`
)
) ENGINE = InnoDB;

 CREATE TABLE `pdo-x-example`.`person_groups` (
`id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`person_id` INT UNSIGNED NOT NULL ,
`group_id` INT UNSIGNED NOT NULL
) ENGINE = InnoDB;

ALTER TABLE `person_groups` ADD INDEX ( `person_id` );

ALTER TABLE `person_groups` ADD INDEX ( `group_id` );

ALTER TABLE `person_groups` ADD FOREIGN KEY ( `person_id` ) REFERENCES `pdo-x-example`.`person` (
`id`
);

ALTER TABLE `person_groups` ADD FOREIGN KEY ( `group_id` ) REFERENCES `pdo-x-example`.`groups` (
`id`
);
