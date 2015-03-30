SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

CREATE SCHEMA IF NOT EXISTS `allergeeks` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;
USE `allergeeks` ;

-- -----------------------------------------------------
-- Table `allergeeks`.`user`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `allergeeks`.`user` (
  `ID` INT NOT NULL AUTO_INCREMENT,
  `Name` VARCHAR(100) NOT NULL,
  `Password` VARCHAR(200) NOT NULL,
  `eMail` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`ID`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `allergeeks`.`product`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `allergeeks`.`product` (
  `EAN` INT NOT NULL AUTO_INCREMENT,
  `Name` VARCHAR(200) NOT NULL,
  `Description` TEXT NULL,
  `ProductCategory_ID` INT NOT NULL,
  PRIMARY KEY (`EAN`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `allergeeks`.`ingredient`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `allergeeks`.`ingredient` (
  `ID` INT NOT NULL AUTO_INCREMENT,
  `Name` VARCHAR(200) NOT NULL,
  `Description` TEXT NULL,
  PRIMARY KEY (`ID`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `allergeeks`.`product_has_ingredient`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `allergeeks`.`product_has_ingredient` (
  `Product_EAN` INT NOT NULL,
  `Ingredient_ID` INT NOT NULL,
  `Amount` FLOAT NULL,
  `AmountUnit` VARCHAR(45) NULL,
  PRIMARY KEY (`Product_EAN`, `Ingredient_ID`),
  INDEX `fk_Product_has_Ingredient_Ingredient1_idx` (`Ingredient_ID` ASC),
  INDEX `fk_Product_has_Ingredient_Product_idx` (`Product_EAN` ASC),
  CONSTRAINT `fk_Product_has_Ingredient_Product`
    FOREIGN KEY (`Product_EAN`)
    REFERENCES `allergeeks`.`product` (`EAN`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Product_has_Ingredient_Ingredient1`
    FOREIGN KEY (`Ingredient_ID`)
    REFERENCES `allergeeks`.`ingredient` (`ID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `allergeeks`.`blacklist`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `allergeeks`.`blacklist` (
  `User_ID` INT NOT NULL,
  `Ingredient_ID` INT NOT NULL,
  PRIMARY KEY (`User_ID`, `Ingredient_ID`),
  INDEX `fk_User_has_Ingredient_Ingredient1_idx` (`Ingredient_ID` ASC),
  INDEX `fk_User_has_Ingredient_User1_idx` (`User_ID` ASC),
  CONSTRAINT `fk_User_has_Ingredient_User1`
    FOREIGN KEY (`User_ID`)
    REFERENCES `allergeeks`.`user` (`ID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_User_has_Ingredient_Ingredient1`
    FOREIGN KEY (`Ingredient_ID`)
    REFERENCES `allergeeks`.`ingredient` (`ID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `allergeeks`.`device`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `allergeeks`.`device` (
  `Token` INT NOT NULL,
  `Name` VARCHAR(45) NOT NULL,
  `valid` DATE NOT NULL,
  `admin_right` TINYINT(1) NOT NULL DEFAULT 0,
  `User_ID` INT NOT NULL,
  PRIMARY KEY (`Token`),
  INDEX `fk_Device_User1_idx` (`User_ID` ASC),
  CONSTRAINT `fk_Device_User1`
    FOREIGN KEY (`User_ID`)
    REFERENCES `allergeeks`.`user` (`ID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `allergeeks`.`category`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `allergeeks`.`category` (
  `ID` INT NOT NULL AUTO_INCREMENT,
  `Name` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE INDEX `Name_UNIQUE` (`Name` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `allergeeks`.`ingredient_has_categorie`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `allergeeks`.`ingredient_has_categorie` (
  `Ingredient_ID` INT NOT NULL,
  `Categorie_ID` INT NOT NULL,
  PRIMARY KEY (`Ingredient_ID`, `Categorie_ID`),
  INDEX `fk_Ingredient_has_Kategorie_Kategorie1_idx` (`Categorie_ID` ASC),
  INDEX `fk_Ingredient_has_Kategorie_Ingredient1_idx` (`Ingredient_ID` ASC),
  CONSTRAINT `fk_Ingredient_has_Kategorie_Ingredient1`
    FOREIGN KEY (`Ingredient_ID`)
    REFERENCES `allergeeks`.`ingredient` (`ID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Ingredient_has_Kategorie_Kategorie1`
    FOREIGN KEY (`Categorie_ID`)
    REFERENCES `allergeeks`.`category` (`ID`)
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
INSERT INTO `allergeeks`.`user` (`ID`, `Name`, `Password`, `eMail`) VALUES (0, 'Marco', '5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8', 'marco.heumann@web.de');

COMMIT;