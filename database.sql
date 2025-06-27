CREATE DATABASE nihao;

USE nihao;

CREATE TABLE `students` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `student_number` VARCHAR(50) NOT NULL,
  `name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `mobile_number` INT(11) NOT NULL,
  PRIMARY KEY (`id`)
);

ALTER TABLE students
  ADD COLUMN deleted_at DATETIME NULL DEFAULT NULL,
  ADD INDEX(deleted_at);