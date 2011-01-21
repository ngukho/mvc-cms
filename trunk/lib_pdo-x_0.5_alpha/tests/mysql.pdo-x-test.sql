CREATE DATABASE `pdo-x-test` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;

USE `pdo-x-test`;

 CREATE TABLE `pdo-x-test`.`test` (
`id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`name` VARCHAR( 255 ) NOT NULL ,
`date` DATE NULL ,
`number` INT NULL
) ENGINE = InnoDB; 

INSERT INTO test (name, date, number) VALUES ('John', now(), 12345);
INSERT INTO test (name, date, number) VALUES ('Jane', now(), 54321);