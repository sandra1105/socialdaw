-- MySQL Script generated by MySQL Workbench
-- Tue Jan 14 17:09:53 2020
-- Model: New Model    Version: 1.0
-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema socialdaw
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema socialdaw
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `socialdaw` DEFAULT CHARACTER SET utf8 ;
USE `socialdaw` ;

-- -----------------------------------------------------
-- Table `socialdaw`.`categoria_post`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `socialdaw`.`categoria_post` (
  `id` INT NOT NULL,
  `descripcion` VARCHAR(45) NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `socialdaw`.`rol`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `socialdaw`.`rol` (
  `id` INT NOT NULL,
  `descripcion` VARCHAR(45) NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `socialdaw`.`usuario`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `socialdaw`.`usuario` (
  `login` VARCHAR(50) NOT NULL,
  `password` VARCHAR(200) NULL,
  `rol_id` INT NOT NULL,
  `nombre` VARCHAR(45) NULL,
  `email` VARCHAR(50) NULL,
  PRIMARY KEY (`login`),
  INDEX `fk_usuario_rol_idx` (`rol_id` ASC),
  CONSTRAINT `fk_usuario_rol`
    FOREIGN KEY (`rol_id`)
    REFERENCES `socialdaw`.`rol` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `socialdaw`.`post`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `socialdaw`.`post` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `fecha` DATETIME NULL,
  `resumen` VARCHAR(500) NULL,
  `texto` MEDIUMTEXT NULL,
  `foto` VARCHAR(100) NULL,
  `categoria_post_id` INT NOT NULL,
  `usuario_login` VARCHAR(50) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_post_categoria_post1_idx` (`categoria_post_id` ASC),
  INDEX `fk_post_usuario1_idx` (`usuario_login` ASC),
  CONSTRAINT `fk_post_categoria_post1`
    FOREIGN KEY (`categoria_post_id`)
    REFERENCES `socialdaw`.`categoria_post` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_post_usuario1`
    FOREIGN KEY (`usuario_login`)
    REFERENCES `socialdaw`.`usuario` (`login`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `socialdaw`.`like`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `socialdaw`.`like` (
  `post_id` INT NOT NULL,
  `usuario_login` VARCHAR(50) NOT NULL,
  PRIMARY KEY (`post_id`, `usuario_login`),
  INDEX `fk_post_has_usuario_usuario1_idx` (`usuario_login` ASC),
  INDEX `fk_post_has_usuario_post1_idx` (`post_id` ASC),
  CONSTRAINT `fk_post_has_usuario_post1`
    FOREIGN KEY (`post_id`)
    REFERENCES `socialdaw`.`post` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_post_has_usuario_usuario1`
    FOREIGN KEY (`usuario_login`)
    REFERENCES `socialdaw`.`usuario` (`login`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `socialdaw`.`comenta`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `socialdaw`.`comenta` (
  `post_id` INT NOT NULL,
  `usuario_login` VARCHAR(50) NOT NULL,
  `fecha` DATETIME NOT NULL,
  `texto` MEDIUMTEXT NULL,
  PRIMARY KEY (`post_id`, `usuario_login`, `fecha`),
  INDEX `fk_post_has_usuario_usuario2_idx` (`usuario_login` ASC),
  INDEX `fk_post_has_usuario_post2_idx` (`post_id` ASC),
  CONSTRAINT `fk_post_has_usuario_post2`
    FOREIGN KEY (`post_id`)
    REFERENCES `socialdaw`.`post` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_post_has_usuario_usuario2`
    FOREIGN KEY (`usuario_login`)
    REFERENCES `socialdaw`.`usuario` (`login`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
