SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

DROP SCHEMA IF EXISTS `coopcrucial` ;
CREATE SCHEMA IF NOT EXISTS `coopcrucial` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci ;
USE `coopcrucial` ;

-- -----------------------------------------------------
-- Table `coopcrucial`.`categoria`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `coopcrucial`.`categoria` ;

CREATE  TABLE IF NOT EXISTS `coopcrucial`.`categoria` (
  `idCategoria` INT NOT NULL AUTO_INCREMENT ,
  `nombre` VARCHAR(50) NOT NULL ,
  `imagen` VARCHAR(45) NULL ,
  `oferta` INT(2) NULL ,
  `tituloDescripcion` VARCHAR(45) NOT NULL ,
  `descripcion` TEXT NOT NULL ,
  PRIMARY KEY (`idCategoria`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `coopcrucial`.`producto`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `coopcrucial`.`producto` ;

CREATE  TABLE IF NOT EXISTS `coopcrucial`.`producto` (
  `idProducto` INT NOT NULL AUTO_INCREMENT ,
  `nombre` VARCHAR(50) NOT NULL ,
  `descripcion` TEXT NOT NULL ,
  `garantia` VARCHAR(45) NOT NULL ,
  `marca` VARCHAR(45) NOT NULL ,
  `precio` FLOAT NOT NULL ,
  `precioWeb` FLOAT NOT NULL ,
  `vecesComprado` INT NOT NULL DEFAULT 0 ,
  `tags` VARCHAR(50) NULL ,
  `destacado` TINYINT(1) NOT NULL DEFAULT false ,
  `recomendado` TINYINT(1) NOT NULL DEFAULT false ,
  `numeroVotos` INT NOT NULL ,
  `calificacion` INT(5) NOT NULL DEFAULT 1 ,
  `idCategoria` INT NOT NULL ,
  PRIMARY KEY (`idProducto`) ,
  INDEX `fk_producto_categoria` (`idCategoria` ASC) ,
  CONSTRAINT `fk_producto_categoria`
    FOREIGN KEY (`idCategoria` )
    REFERENCES `coopcrucial`.`categoria` (`idCategoria` )
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `coopcrucial`.`usuario`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `coopcrucial`.`usuario` ;

CREATE  TABLE IF NOT EXISTS `coopcrucial`.`usuario` (
  `idUsuario` INT NOT NULL AUTO_INCREMENT ,
  `nombre` VARCHAR(50) NOT NULL ,
  `apellidos` VARCHAR(50) NOT NULL ,
  `tipoDocumento` VARCHAR(3) NOT NULL ,
  `numeroDocumento` INT NOT NULL ,
  `fechaNacimiento` DATE NOT NULL ,
  `correoElectronico` VARCHAR(60) NOT NULL ,
  `celular` VARCHAR(30) NOT NULL ,
  `actualizacion` TINYINT(1) NOT NULL ,
  `promocion` TINYINT(1) NOT NULL ,
  `codigo` VARCHAR(8) NOT NULL ,
  `usuario` VARCHAR(15) NOT NULL ,
  `contrasenia` VARCHAR(40) NOT NULL ,
  PRIMARY KEY (`idUsuario`) ,
  UNIQUE INDEX `usuario_UNIQUE` (`usuario` ASC) ,
  UNIQUE INDEX `numeroDocumento_UNIQUE` (`numeroDocumento` ASC) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `coopcrucial`.`imagen_producto`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `coopcrucial`.`imagen_producto` ;

CREATE  TABLE IF NOT EXISTS `coopcrucial`.`imagen_producto` (
  `idImagenProducto` INT NOT NULL AUTO_INCREMENT ,
  `nombre` VARCHAR(50) NOT NULL ,
  `idProducto` INT NOT NULL ,
  PRIMARY KEY (`idImagenProducto`) ,
  INDEX `fk_imagenes_producto_producto1` (`idProducto` ASC) ,
  CONSTRAINT `fk_imagenes_producto_producto1`
    FOREIGN KEY (`idProducto` )
    REFERENCES `coopcrucial`.`producto` (`idProducto` )
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `coopcrucial`.`especificacion`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `coopcrucial`.`especificacion` ;

CREATE  TABLE IF NOT EXISTS `coopcrucial`.`especificacion` (
  `idEspecificacion` INT NOT NULL AUTO_INCREMENT ,
  `titulo` VARCHAR(50) NOT NULL ,
  `descripcion` TEXT NOT NULL ,
  `idProducto` INT NOT NULL ,
  PRIMARY KEY (`idEspecificacion`) ,
  INDEX `fk_especificacion_producto1` (`idProducto` ASC) ,
  CONSTRAINT `fk_especificacion_producto1`
    FOREIGN KEY (`idProducto` )
    REFERENCES `coopcrucial`.`producto` (`idProducto` )
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `coopcrucial`.`caracteristica`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `coopcrucial`.`caracteristica` ;

CREATE  TABLE IF NOT EXISTS `coopcrucial`.`caracteristica` (
  `idCaracteristica` INT NOT NULL AUTO_INCREMENT ,
  `nombre` VARCHAR(45) NOT NULL ,
  `descripcion` VARCHAR(150) NOT NULL ,
  `idProducto` INT NOT NULL ,
  PRIMARY KEY (`idCaracteristica`) ,
  INDEX `fk_caracteristica_producto1` (`idProducto` ASC) ,
  CONSTRAINT `fk_caracteristica_producto1`
    FOREIGN KEY (`idProducto` )
    REFERENCES `coopcrucial`.`producto` (`idProducto` )
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `coopcrucial`.`imagen_destacado`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `coopcrucial`.`imagen_destacado` ;

CREATE  TABLE IF NOT EXISTS `coopcrucial`.`imagen_destacado` (
  `idImagenDestacado` INT NOT NULL AUTO_INCREMENT ,
  `imagen` VARCHAR(45) NOT NULL ,
  `link` VARCHAR(50) NOT NULL ,
  `nombre` VARCHAR(50) NOT NULL ,
  PRIMARY KEY (`idImagenDestacado`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `coopcrucial`.`color`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `coopcrucial`.`color` ;

CREATE  TABLE IF NOT EXISTS `coopcrucial`.`color` (
  `idColor` INT NOT NULL AUTO_INCREMENT ,
  `nombre` VARCHAR(45) NOT NULL ,
  `codigo` VARCHAR(45) NOT NULL ,
  PRIMARY KEY (`idColor`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `coopcrucial`.`color_producto`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `coopcrucial`.`color_producto` ;

CREATE  TABLE IF NOT EXISTS `coopcrucial`.`color_producto` (
  `idColorProducto` INT NOT NULL AUTO_INCREMENT ,
  `idProducto` INT NOT NULL ,
  `idColor` INT NOT NULL ,
  PRIMARY KEY (`idColorProducto`) ,
  INDEX `fk_producto_has_color_color1` (`idColor` ASC) ,
  INDEX `fk_producto_has_color_producto1` (`idProducto` ASC) ,
  CONSTRAINT `fk_producto_has_color_producto1`
    FOREIGN KEY (`idProducto` )
    REFERENCES `coopcrucial`.`producto` (`idProducto` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_producto_has_color_color1`
    FOREIGN KEY (`idColor` )
    REFERENCES `coopcrucial`.`color` (`idColor` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `coopcrucial`.`imagen_home`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `coopcrucial`.`imagen_home` ;

CREATE  TABLE IF NOT EXISTS `coopcrucial`.`imagen_home` (
  `idImagenHome` INT NOT NULL AUTO_INCREMENT ,
  `imagen` VARCHAR(45) NOT NULL ,
  PRIMARY KEY (`idImagenHome`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `coopcrucial`.`pais`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `coopcrucial`.`pais` ;

CREATE  TABLE IF NOT EXISTS `coopcrucial`.`pais` (
  `idPais` VARCHAR(3) NOT NULL ,
  `nombre` VARCHAR(55) NOT NULL ,
  `continente` ENUM('Asia','Europa','Norte America','Africa','Oceania','Antarctica','Sur America') NOT NULL ,
  PRIMARY KEY (`idPais`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `coopcrucial`.`ciudad`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `coopcrucial`.`ciudad` ;

CREATE  TABLE IF NOT EXISTS `coopcrucial`.`ciudad` (
  `idCiudad` INT NOT NULL ,
  `nombre` VARCHAR(45) NOT NULL ,
  `distrito` VARCHAR(45) NOT NULL ,
  `idPais` VARCHAR(3) NOT NULL ,
  PRIMARY KEY (`idCiudad`) ,
  INDEX `fk_ciudad_pais1` (`idPais` ASC) ,
  CONSTRAINT `fk_ciudad_pais1`
    FOREIGN KEY (`idPais` )
    REFERENCES `coopcrucial`.`pais` (`idPais` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `coopcrucial`.`direccion`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `coopcrucial`.`direccion` ;

CREATE  TABLE IF NOT EXISTS `coopcrucial`.`direccion` (
  `idDireccion` INT NOT NULL AUTO_INCREMENT ,
  `direccion` VARCHAR(45) NOT NULL ,
  `telefono` VARCHAR(45) NOT NULL ,
  `predeterminada` TINYINT(1) NOT NULL ,
  `idUsuario` INT NOT NULL ,
  `idCiudad` INT NOT NULL ,
  PRIMARY KEY (`idDireccion`) ,
  INDEX `fk_direccion_usuario1` (`idUsuario` ASC) ,
  INDEX `fk_direccion_ciudad1` (`idCiudad` ASC) ,
  CONSTRAINT `fk_direccion_usuario1`
    FOREIGN KEY (`idUsuario` )
    REFERENCES `coopcrucial`.`usuario` (`idUsuario` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_direccion_ciudad1`
    FOREIGN KEY (`idCiudad` )
    REFERENCES `coopcrucial`.`ciudad` (`idCiudad` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `coopcrucial`.`venta`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `coopcrucial`.`venta` ;

CREATE  TABLE IF NOT EXISTS `coopcrucial`.`venta` (
  `idVenta` INT NOT NULL AUTO_INCREMENT ,
  `idProducto` INT NOT NULL ,
  `idUsuario` INT NOT NULL ,
  `cantidad` INT NOT NULL DEFAULT 1 ,
  `carrito` TINYINT(1) NOT NULL DEFAULT true ,
  `idDireccion` INT NULL ,
  `fecha` DATETIME NULL ,
  INDEX `fk_producto_has_usuario_usuario1` (`idUsuario` ASC) ,
  INDEX `fk_producto_has_usuario_producto1` (`idProducto` ASC) ,
  PRIMARY KEY (`idVenta`) ,
  INDEX `fk_venta_direccion1` (`idDireccion` ASC) ,
  CONSTRAINT `fk_producto_has_usuario_producto1`
    FOREIGN KEY (`idProducto` )
    REFERENCES `coopcrucial`.`producto` (`idProducto` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_producto_has_usuario_usuario1`
    FOREIGN KEY (`idUsuario` )
    REFERENCES `coopcrucial`.`usuario` (`idUsuario` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_venta_direccion1`
    FOREIGN KEY (`idDireccion` )
    REFERENCES `coopcrucial`.`direccion` (`idDireccion` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `coopcrucial`.`comentario`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `coopcrucial`.`comentario` ;

CREATE  TABLE IF NOT EXISTS `coopcrucial`.`comentario` (
  `idComentario` INT NOT NULL AUTO_INCREMENT ,
  `idUsuario` INT NOT NULL ,
  `idProducto` INT NOT NULL ,
  `comentario` TEXT NOT NULL ,
  `fecha` DATETIME NOT NULL ,
  INDEX `fk_usuario_has_producto_producto1` (`idProducto` ASC) ,
  INDEX `fk_usuario_has_producto_usuario1` (`idUsuario` ASC) ,
  PRIMARY KEY (`idComentario`) ,
  CONSTRAINT `fk_usuario_has_producto_usuario1`
    FOREIGN KEY (`idUsuario` )
    REFERENCES `coopcrucial`.`usuario` (`idUsuario` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_usuario_has_producto_producto1`
    FOREIGN KEY (`idProducto` )
    REFERENCES `coopcrucial`.`producto` (`idProducto` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `coopcrucial`.`registro`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `coopcrucial`.`registro` ;

CREATE  TABLE IF NOT EXISTS `coopcrucial`.`registro` (
  `idRegistro` INT NOT NULL AUTO_INCREMENT ,
  `nombre` VARCHAR(45) NOT NULL ,
  `valor` INT NOT NULL ,
  PRIMARY KEY (`idRegistro`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `coopcrucial`.`oferta_especial`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `coopcrucial`.`oferta_especial` ;

CREATE  TABLE IF NOT EXISTS `coopcrucial`.`oferta_especial` (
  `idOfertaEspecial` INT NOT NULL AUTO_INCREMENT ,
  `imagen` VARCHAR(45) NOT NULL ,
  PRIMARY KEY (`idOfertaEspecial`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `coopcrucial`.`uso`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `coopcrucial`.`uso` ;

CREATE  TABLE IF NOT EXISTS `coopcrucial`.`uso` (
  `idUso` INT NOT NULL AUTO_INCREMENT ,
  `nombre` VARCHAR(50) NOT NULL ,
  `imagen` VARCHAR(45) NULL ,
  `oferta` INT(2) NULL ,
  `tituloDescripcion` VARCHAR(45) NOT NULL ,
  `descripcion` TEXT NOT NULL ,
  PRIMARY KEY (`idUso`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `coopcrucial`.`uso_producto`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `coopcrucial`.`uso_producto` ;

CREATE  TABLE IF NOT EXISTS `coopcrucial`.`uso_producto` (
  `idUsoProducto` INT NOT NULL AUTO_INCREMENT ,
  `idProducto` INT NOT NULL ,
  `idUso` INT NOT NULL ,
  PRIMARY KEY (`idUsoProducto`) ,
  INDEX `fk_producto_has_uso_uso1` (`idUso` ASC) ,
  INDEX `fk_producto_has_uso_producto1` (`idProducto` ASC) ,
  CONSTRAINT `fk_producto_has_uso_producto1`
    FOREIGN KEY (`idProducto` )
    REFERENCES `coopcrucial`.`producto` (`idProducto` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_producto_has_uso_uso1`
    FOREIGN KEY (`idUso` )
    REFERENCES `coopcrucial`.`uso` (`idUso` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `coopcrucial`.`preferencia`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `coopcrucial`.`preferencia` ;

CREATE  TABLE IF NOT EXISTS `coopcrucial`.`preferencia` (
  `idPreferencia` INT NOT NULL AUTO_INCREMENT ,
  `nombre` VARCHAR(45) NOT NULL ,
  PRIMARY KEY (`idPreferencia`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `coopcrucial`.`preferencia_usuario`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `coopcrucial`.`preferencia_usuario` ;

CREATE  TABLE IF NOT EXISTS `coopcrucial`.`preferencia_usuario` (
  `idPreferenciaUsuario` INT NOT NULL AUTO_INCREMENT ,
  `idUsuario` INT NOT NULL ,
  `idPreferencia` INT NOT NULL ,
  INDEX `fk_usuario_has_preferencia_preferencia1` (`idPreferencia` ASC) ,
  INDEX `fk_usuario_has_preferencia_usuario1` (`idUsuario` ASC) ,
  PRIMARY KEY (`idPreferenciaUsuario`) ,
  CONSTRAINT `fk_usuario_has_preferencia_usuario1`
    FOREIGN KEY (`idUsuario` )
    REFERENCES `coopcrucial`.`usuario` (`idUsuario` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_usuario_has_preferencia_preferencia1`
    FOREIGN KEY (`idPreferencia` )
    REFERENCES `coopcrucial`.`preferencia` (`idPreferencia` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

-- -----------------------------------------------------
-- Data for table `coopcrucial`.`registro`
-- -----------------------------------------------------
START TRANSACTION;
USE `coopcrucial`;
INSERT INTO `coopcrucial`.`registro` (`idRegistro`, `nombre`, `valor`) VALUES (1, 'bono', 15000);

COMMIT;

-- -----------------------------------------------------
-- Data for table `coopcrucial`.`preferencia`
-- -----------------------------------------------------
START TRANSACTION;
USE `coopcrucial`;
INSERT INTO `coopcrucial`.`preferencia` (`idPreferencia`, `nombre`) VALUES (1, 'Salud');
INSERT INTO `coopcrucial`.`preferencia` (`idPreferencia`, `nombre`) VALUES (2, 'Hogar');
INSERT INTO `coopcrucial`.`preferencia` (`idPreferencia`, `nombre`) VALUES (3, 'Familia');
INSERT INTO `coopcrucial`.`preferencia` (`idPreferencia`, `nombre`) VALUES (4, 'Viajes');
INSERT INTO `coopcrucial`.`preferencia` (`idPreferencia`, `nombre`) VALUES (5, 'Deporte');
INSERT INTO `coopcrucial`.`preferencia` (`idPreferencia`, `nombre`) VALUES (6, 'Tecnologia');

COMMIT;
