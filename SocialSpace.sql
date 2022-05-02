CREATE SCHEMA IF NOT EXISTS `SocialSpace` DEFAULT CHARACTER SET = utf8mb4 DEFAULT COLLATE = utf8mb4_unicode_ci;
USE `SocialSpace` ;


-- -----------------------------------------------------
-- Table `SocialSpace`.`account`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `SocialSpace`.`account` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `email` VARCHAR(128) NOT NULL,
  `password` VARCHAR(128) NOT NULL,
  PRIMARY KEY (`id`));


-- -----------------------------------------------------
-- Table `SocialSpace`.`person`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `SocialSpace`.`person` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `firstname` VARCHAR(64) NOT NULL,
  `surname` VARCHAR(64) NOT NULL,
  `gender` VARCHAR(8) NULL,
  `profile_pic` VARCHAR(64) NULL,
  `dob` DATE NULL,
  `bio` VARCHAR(512) NULL,
  `account_id` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_person_account1`
    FOREIGN KEY (`account_id`)
    REFERENCES `SocialSpace`.`account` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE);


-- -----------------------------------------------------
-- Table `SocialSpace`.`post`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `SocialSpace`.`post` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `caption` VARCHAR(2048) NULL,
  `date` VARCHAR(64) NOT NULL,
  `person_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_post_person1`
    FOREIGN KEY (`person_id`)
    REFERENCES `SocialSpace`.`person` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE);


-- -----------------------------------------------------
-- Table `SocialSpace`.`likes`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `SocialSpace`.`likes` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `person_id` INT NOT NULL,
  `post_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_likes_post1`
    FOREIGN KEY (`post_id`)
    REFERENCES `SocialSpace`.`post` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE);


-- -----------------------------------------------------
-- Table `SocialSpace`.`comment`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `SocialSpace`.`comment` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `comment_message` VARCHAR(1024) NOT NULL,
  `person_id` INT NOT NULL,
  `post_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_comment_post1`
    FOREIGN KEY (`post_id`)
    REFERENCES `SocialSpace`.`post` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE);


-- -----------------------------------------------------
-- Table `SocialSpace`.`images`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `SocialSpace`.`images` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `image` VARCHAR(64) NOT NULL,
  `post_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_images_post1`
    FOREIGN KEY (`post_id`)
    REFERENCES `SocialSpace`.`post` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE);
