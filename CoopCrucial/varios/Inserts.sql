#Pais
INSERT INTO `pais` VALUES ('COL', 'Colombia', 'Sur America');
#Ciudad
INSERT INTO `ciudad` VALUES (2257, 'BogotÃ¡',  'BogotÃ¡', 'COL');
#Categorias Tipo
INSERT INTO `categoria` (`idCategoria`, `nombre`, `oferta`, `tituloDescripcion`, `descripcion`) VALUES (1, 'Salud', 0, 'Categoria Salud', 'Esta es la descripcion de la categoria Salud.');
UPDATE `categoria` SET `imagen` = 'salud.jpg' WHERE `categoria`.`idCategoria` = 1;
INSERT INTO `categoria` (`idCategoria`, `nombre`, `oferta`, `tituloDescripcion`, `descripcion`) VALUES (2, 'Hogar', 0, 'Categoria Hogar', 'Esta es la descripcion de la categoria Hogar.');
UPDATE `categoria` SET `imagen` = 'hogar.jpg' WHERE `categoria`.`idCategoria` = 2;
#Categorias Uso
INSERT INTO `uso` (`idUso`, `nombre`, `oferta`, `tituloDescripcion`, `descripcion`) VALUES (1, 'Incendio', 0, 'Uso Incendio', 'Esta es la descripcion de la categoria Incendio.');
UPDATE `uso` SET `imagen` = 'incendio.jpg' WHERE `uso`.`idUso` = 1;
INSERT INTO `uso` (`idUso`, `nombre`, `oferta`, `tituloDescripcion`, `descripcion`) VALUES (2, 'Electrico', 0, 'Uso Electrico', 'Esta es la descripcion de la categoria Electrico.');
UPDATE `uso` SET `imagen` = 'electrico.jpg' WHERE `uso`.`idUso` = 2;
#Productos
INSERT INTO `producto` (`idProducto`, `nombre`, `descripcion`, `garantia`, `marca`, `precio`, `precioWeb`, `vecesComprado`, `tags`, `destacado`, `recomendado`, `numeroVotos`, `calificacion`, `idCategoria`) VALUES (1, 'Camilla', 'Esta es la descripcion de la camilla.', '12 Meses', 'Cruz Roja', '150000', '130000', '0', 'Salud, Primeros auxilios', '1', '1', 0, '1', 1);
INSERT INTO `uso_producto` (`idUsoProducto`, `idProducto`, `idUso`) VALUES (1, 1, 1);
INSERT INTO `especificacion` (`idEspecificacion`, `titulo`, `descripcion`, `idProducto`) VALUES (1, 'Especificacion 1', 'Descripcion especificacion 1', 1);
INSERT INTO `caracteristica` (`idCaracteristica`, `nombre`, `descripcion`, `idProducto`) VALUES (1, 'Caracteristica 1', 'Descripcion caracteristica 1', 1);
INSERT INTO `imagen_producto` (`idImagenProducto`, `nombre`, `idProducto`) VALUES (1, 'camilla.jpg', 1);
INSERT INTO `imagen_producto` (`idImagenProducto`, `nombre`, `idProducto`) VALUES (2, 'camilla2.jpg', 1);
INSERT INTO `producto` (`idProducto`, `nombre`, `descripcion`, `garantia`, `marca`, `precio`, `precioWeb`, `vecesComprado`, `tags`, `destacado`, `recomendado`, `numeroVotos`, `calificacion`, `idCategoria`) VALUES (2, 'Botiquin', 'Esta es la descripcion del botiquin.', '12 Meses', 'ABC', '45000', '40000', '0', 'Hogar, Salud', '1', '1', 0, '1', 2);
INSERT INTO `uso_producto` (`idUsoProducto`, `idProducto`, `idUso`) VALUES (2, 2, 2);
INSERT INTO `especificacion` (`idEspecificacion`, `titulo`, `descripcion`, `idProducto`) VALUES (2, 'Especificacion 1', 'Descripcion especificacion 1', 2);
INSERT INTO `caracteristica` (`idCaracteristica`, `nombre`, `descripcion`, `idProducto`) VALUES (2, 'Caracteristica 1', 'Descripcion caracteristica 1', 2);
INSERT INTO `imagen_producto` (`idImagenProducto`, `nombre`, `idProducto`) VALUES (3, 'botiquin.jpg', 2);
INSERT INTO `imagen_producto` (`idImagenProducto`, `nombre`, `idProducto`) VALUES (4, 'botiquin2.jpg', 2);
#Destacado Imagenes Home
INSERT INTO `imagen_home` (`idImagenHome`, `imagen`) VALUES (1, 'destacado.jpg');
INSERT INTO `imagen_home` (`idImagenHome`, `imagen`) VALUES (2, 'destacado1.jpg');
#Destacado Ofertas especiales
INSERT INTO `oferta_especial` (`idOfertaEspecial`, `imagen`) VALUES (1, 'oferta.jpg');
INSERT INTO `oferta_especial` (`idOfertaEspecial`, `imagen`) VALUES (2, 'oferta1.jpg');
#Usuarios
INSERT INTO `usuario` VALUES (1, 'Julian', 'NuÃ±ez', 'CC', '1018413448', '1987/09/28', 'julian.nunezm@gmail.com', '3163013298', 0, 0, 'COOP123', 'julian', '7c4a8d09ca3762af61e59520943dc26494f8941b');
#Direcciones
INSERT INTO `direccion` VALUES (1,'casa-Calle x con Carrera y','casa-4111293',1,1,2257);