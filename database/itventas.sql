CREATE DATABASE IF NOT EXISTS `sistema MVC` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `sistema MVC`;

CREATE TABLE IF NOT EXISTS categoria (
  idcategoria INT NOT NULL AUTO_INCREMENT,
  nombre VARCHAR(50) NOT NULL,
  descripcion VARCHAR(256) DEFAULT NULL,
  condicion TINYINT(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (idcategoria),
  UNIQUE KEY uq_categoria_nombre (nombre)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS articulo (
  idarticulo INT NOT NULL AUTO_INCREMENT,
  idcategoria INT NOT NULL,
  codigo VARCHAR(50) NOT NULL,
  nombre VARCHAR(100) NOT NULL,
  stock INT NOT NULL DEFAULT 0,
  descripcion VARCHAR(500) DEFAULT NULL,
  imagen VARCHAR(255) DEFAULT NULL,
  condicion TINYINT(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (idarticulo),
  UNIQUE KEY uq_articulo_codigo (codigo),
  KEY idx_articulo_categoria (idcategoria),
  CONSTRAINT fk_articulo_categoria FOREIGN KEY (idcategoria)
    REFERENCES categoria(idcategoria) ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


USE `sistema MVC`;

CREATE TABLE IF NOT EXISTS proveedor (
 idproveedor INT AUTO_INCREMENT PRIMARY KEY,
 tipo_documento VARCHAR(20) DEFAULT 'RUC',
 num_documento VARCHAR(20) NOT NULL,
 nombre VARCHAR(120) NOT NULL,
 telefono VARCHAR(30) DEFAULT NULL,
 email VARCHAR(120) DEFAULT NULL,
 direccion VARCHAR(180) DEFAULT NULL,
 condicion TINYINT(1) NOT NULL DEFAULT 1,
 UNIQUE KEY uq_proveedor_doc (num_documento)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS cliente (
 idcliente INT AUTO_INCREMENT PRIMARY KEY,
 tipo_documento VARCHAR(20) DEFAULT 'DNI',
 num_documento VARCHAR(20) NOT NULL,
 nombre VARCHAR(120) NOT NULL,
 telefono VARCHAR(30) DEFAULT NULL,
 email VARCHAR(120) DEFAULT NULL,
 direccion VARCHAR(180) DEFAULT NULL,
 condicion TINYINT(1) NOT NULL DEFAULT 1,
 UNIQUE KEY uq_cliente_doc (num_documento)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS usuario (
 idusuario INT AUTO_INCREMENT PRIMARY KEY,
 nombre VARCHAR(120) NOT NULL,
 login VARCHAR(60) NOT NULL UNIQUE,
 clave VARCHAR(255) NOT NULL,
 email VARCHAR(120) DEFAULT NULL,
 rol VARCHAR(30) NOT NULL DEFAULT 'Vendedor',
 condicion TINYINT(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS permiso (
 idpermiso INT AUTO_INCREMENT PRIMARY KEY,
 rol VARCHAR(30) NOT NULL UNIQUE,
 escritorio TINYINT(1) NOT NULL DEFAULT 1,
 almacen TINYINT(1) NOT NULL DEFAULT 1,
 compras TINYINT(1) NOT NULL DEFAULT 0,
 ventas TINYINT(1) NOT NULL DEFAULT 1,
 acceso TINYINT(1) NOT NULL DEFAULT 0,
 consultas TINYINT(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS ingreso (
 idingreso INT AUTO_INCREMENT PRIMARY KEY,
 idproveedor INT NOT NULL,
 fecha DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
 tipo_comprobante VARCHAR(30) DEFAULT 'Boleta',
 serie_comprobante VARCHAR(20) DEFAULT '',
 num_comprobante VARCHAR(30) DEFAULT '',
 total DECIMAL(12,2) NOT NULL DEFAULT 0,
 estado VARCHAR(20) NOT NULL DEFAULT 'Aceptado',
 CONSTRAINT fk_ingreso_proveedor FOREIGN KEY (idproveedor) REFERENCES proveedor(idproveedor)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS detalle_ingreso (
 iddetalle_ingreso INT AUTO_INCREMENT PRIMARY KEY,
 idingreso INT NOT NULL,
 idarticulo INT NOT NULL,
 cantidad INT NOT NULL,
 precio DECIMAL(12,2) NOT NULL,
 CONSTRAINT fk_det_ingreso FOREIGN KEY (idingreso) REFERENCES ingreso(idingreso) ON DELETE CASCADE,
 CONSTRAINT fk_det_ingreso_art FOREIGN KEY (idarticulo) REFERENCES articulo(idarticulo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS venta (
 idventa INT AUTO_INCREMENT PRIMARY KEY,
 idcliente INT NOT NULL,
 fecha DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
 tipo_comprobante VARCHAR(30) DEFAULT 'Boleta',
 serie_comprobante VARCHAR(20) DEFAULT '',
 num_comprobante VARCHAR(30) DEFAULT '',
 total DECIMAL(12,2) NOT NULL DEFAULT 0,
 estado VARCHAR(20) NOT NULL DEFAULT 'Aceptado',
 CONSTRAINT fk_venta_cliente FOREIGN KEY (idcliente) REFERENCES cliente(idcliente)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS detalle_venta (
 iddetalle_venta INT AUTO_INCREMENT PRIMARY KEY,
 idventa INT NOT NULL,
 idarticulo INT NOT NULL,
 cantidad INT NOT NULL,
 precio DECIMAL(12,2) NOT NULL,
 CONSTRAINT fk_det_venta FOREIGN KEY (idventa) REFERENCES venta(idventa) ON DELETE CASCADE,
 CONSTRAINT fk_det_venta_art FOREIGN KEY (idarticulo) REFERENCES articulo(idarticulo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT IGNORE INTO permiso (rol,escritorio,almacen,compras,ventas,acceso,consultas)
VALUES ('Administrador',1,1,1,1,1,1),('Vendedor',1,1,0,1,0,1),('Almacenero',1,1,1,0,0,1);
