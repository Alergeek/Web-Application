SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

CREATE SCHEMA IF NOT EXISTS `allergeeks` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;
USE `allergeeks` ;

-- -----------------------------------------------------
-- Table `allergeeks`.`user`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `allergeeks`.`user` ;

CREATE TABLE IF NOT EXISTS `allergeeks`.`user` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `email` VARCHAR(45) NOT NULL,
  `password` VARCHAR(200) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `email_UNIQUE` (`email` ASC))
ENGINE = InnoDB
AUTO_INCREMENT = 1;


-- -----------------------------------------------------
-- Table `allergeeks`.`product`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `allergeeks`.`product` ;

CREATE TABLE IF NOT EXISTS `allergeeks`.`product` (
  `ean` BIGINT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(200) NOT NULL,
  `description` TEXT NULL,
  PRIMARY KEY (`ean`))
ENGINE = InnoDB
AUTO_INCREMENT = 1;


-- -----------------------------------------------------
-- Table `allergeeks`.`ingredient`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `allergeeks`.`ingredient` ;

CREATE TABLE IF NOT EXISTS `allergeeks`.`ingredient` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(200) NOT NULL,
  `description` TEXT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
AUTO_INCREMENT = 1;


-- -----------------------------------------------------
-- Table `allergeeks`.`product_has_ingredient`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `allergeeks`.`product_has_ingredient` ;

CREATE TABLE IF NOT EXISTS `allergeeks`.`product_has_ingredient` (
  `product_ean` BIGINT NOT NULL,
  `ingredient_id` INT NOT NULL,
  `amount` FLOAT NULL,
  `amount_unit` VARCHAR(45) NULL,
  PRIMARY KEY (`product_ean`, `ingredient_id`),
  INDEX `fk_Product_has_Ingredient_Ingredient1_idx` (`ingredient_id` ASC),
  INDEX `fk_Product_has_Ingredient_Product_idx` (`product_ean` ASC),
  CONSTRAINT `fk_Product_has_Ingredient_Product`
    FOREIGN KEY (`product_ean`)
    REFERENCES `allergeeks`.`product` (`ean`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Product_has_Ingredient_Ingredient1`
    FOREIGN KEY (`ingredient_id`)
    REFERENCES `allergeeks`.`ingredient` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `allergeeks`.`blacklist`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `allergeeks`.`blacklist` ;

CREATE TABLE IF NOT EXISTS `allergeeks`.`blacklist` (
  `user_id` INT NOT NULL,
  `ingredient_id` INT NOT NULL,
  PRIMARY KEY (`user_id`, `ingredient_id`),
  INDEX `fk_User_has_Ingredient_Ingredient1_idx` (`ingredient_id` ASC),
  INDEX `fk_User_has_Ingredient_User1_idx` (`user_id` ASC),
  CONSTRAINT `fk_User_has_Ingredient_User1`
    FOREIGN KEY (`user_id`)
    REFERENCES `allergeeks`.`user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_User_has_Ingredient_Ingredient1`
    FOREIGN KEY (`ingredient_id`)
    REFERENCES `allergeeks`.`ingredient` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `allergeeks`.`device`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `allergeeks`.`device` ;

CREATE TABLE IF NOT EXISTS `allergeeks`.`device` (
  `token` CHAR(20) NOT NULL,
  `name` VARCHAR(45) NOT NULL,
  `valid` DATETIME NOT NULL,
  `admin_right` TINYINT NOT NULL DEFAULT 0,
  `user_id` INT NOT NULL,
  PRIMARY KEY (`token`),
  INDEX `fk_Device_User1_idx` (`user_id` ASC),
  CONSTRAINT `fk_Device_User1`
    FOREIGN KEY (`user_id`)
    REFERENCES `allergeeks`.`user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `allergeeks`.`category`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `allergeeks`.`category` ;

CREATE TABLE IF NOT EXISTS `allergeeks`.`category` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `Name_UNIQUE` (`name` ASC))
ENGINE = InnoDB
AUTO_INCREMENT = 1;


-- -----------------------------------------------------
-- Table `allergeeks`.`ingredient_has_category`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `allergeeks`.`ingredient_has_category` ;

CREATE TABLE IF NOT EXISTS `allergeeks`.`ingredient_has_category` (
  `ingredient_id` INT NOT NULL,
  `category_id` INT NOT NULL,
  PRIMARY KEY (`ingredient_id`, `category_id`),
  INDEX `fk_Ingredient_has_Kategorie_Kategorie1_idx` (`category_id` ASC),
  INDEX `fk_Ingredient_has_Kategorie_Ingredient1_idx` (`ingredient_id` ASC),
  CONSTRAINT `fk_Ingredient_has_Kategorie_Ingredient1`
    FOREIGN KEY (`ingredient_id`)
    REFERENCES `allergeeks`.`ingredient` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Ingredient_has_Kategorie_Kategorie1`
    FOREIGN KEY (`category_id`)
    REFERENCES `allergeeks`.`category` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

-- -----------------------------------------------------
-- Data for table `allergeeks`.`user`
-- -----------------------------------------------------
START TRANSACTION;
USE `allergeeks`;
INSERT INTO `allergeeks`.`user` (`id`, `email`, `password`) VALUES (1, 'marco.heumann@web.de', '5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8');
INSERT INTO `allergeeks`.`user` (`id`, `email`, `password`) VALUES (2, 'test@example.com', 'b444ac06613fc8d63795be9ad0beaf55011936ac');

COMMIT;


-- -----------------------------------------------------
-- Data for table `allergeeks`.`product`
-- -----------------------------------------------------
START TRANSACTION;
USE `allergeeks`;
INSERT INTO `allergeeks`.`product` (`ean`, `name`, `description`) VALUES (1234567890123, 'Testproduct', 'This is a test product.');
INSERT INTO `allergeeks`.`product` (`ean`, `name`, `description`) VALUES (1234567890125, 'Test Toilet Paper', 'Does not contain Nueffe.');

COMMIT;


-- -----------------------------------------------------
-- Data for table `allergeeks`.`ingredient`
-- -----------------------------------------------------
START TRANSACTION;
USE `allergeeks`;
INSERT INTO `allergeeks`.`ingredient` (`id`, `name`, `description`) VALUES (1, 'Testingredient', NULL);
INSERT INTO `allergeeks`.`ingredient` (`id`, `name`, `description`) VALUES (2, 'Nueffe', 'May contain nuts.');

COMMIT;


-- -----------------------------------------------------
-- Data for table `allergeeks`.`product_has_ingredient`
-- -----------------------------------------------------
START TRANSACTION;
USE `allergeeks`;
INSERT INTO `allergeeks`.`product_has_ingredient` (`product_ean`, `ingredient_id`, `amount`, `amount_unit`) VALUES (1234567890123, 1, NULL, NULL);

COMMIT;


-- -----------------------------------------------------
-- Data for table `allergeeks`.`blacklist`
-- -----------------------------------------------------
START TRANSACTION;
USE `allergeeks`;
INSERT INTO `allergeeks`.`blacklist` (`user_id`, `ingredient_id`) VALUES (1, 1);
INSERT INTO `allergeeks`.`blacklist` (`user_id`, `ingredient_id`) VALUES (1, 2);

COMMIT;


-- -----------------------------------------------------
-- Data for table `allergeeks`.`device`
-- -----------------------------------------------------
START TRANSACTION;
USE `allergeeks`;
INSERT INTO `allergeeks`.`device` (`token`, `name`, `valid`, `admin_right`, `user_id`) VALUES ('aaaaaaaaaaaaaaaaaaaa', 'Browser', '2015-05-01 21:00:00', 1, 1);
INSERT INTO `allergeeks`.`device` (`token`, `name`, `valid`, `admin_right`, `user_id`) VALUES ('1f1f1f1f1f1f1f1f1f1f', 'Android 5.0', '2016-01-01 03:00:00', 1, 1);
INSERT INTO `allergeeks`.`device` (`token`, `name`, `valid`, `admin_right`, `user_id`) VALUES ('11111111111111111111', 'Vuzix M100', '2015-05-27 09:00:00', 0, 1);
INSERT INTO `allergeeks`.`device` (`token`, `name`, `valid`, `admin_right`, `user_id`) VALUES ('d2fd2fd2fd2fd2fd2fd', 'Browser', '2015-01-01 03:00:00', 1, 2);

COMMIT;


-- -----------------------------------------------------
-- Data for table `allergeeks`.`category`
-- -----------------------------------------------------
START TRANSACTION;
USE `allergeeks`;
INSERT INTO `allergeeks`.`category` (`id`, `name`) VALUES (1, 'Nüsse');

COMMIT;


-- -----------------------------------------------------
-- Data for table `allergeeks`.`ingredient_has_category`
-- -----------------------------------------------------
START TRANSACTION;
USE `allergeeks`;
INSERT INTO `allergeeks`.`ingredient_has_category` (`ingredient_id`, `category_id`) VALUES (1, 1);

COMMIT;