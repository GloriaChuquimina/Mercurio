/* ESQUEMAS*/
CREATE SCHEMA administracion AUTHORIZATION postgres;
CREATE SCHEMA configuraciones AUTHORIZATION postgres;
CREATE SCHEMA parametricas AUTHORIZATION postgres;
CREATE SCHEMA contabilidad AUTHORIZATION postgres;
CREATE SCHEMA geografia AUTHORIZATION postgres;
/*========================DATOS PARAMETRICOS=============================*/
/*CREAR TABLA DOMINIOS*/
CREATE TABLE administracion.dominios (
	id serial4 NOT NULL,
	concepto text NULL,
	descripcion text NULL,
	valor1 text NULL,
	valor2 text NULL,
	orden int4 NULL,
	fecha_registro timestamp DEFAULT now() NULL,
	fecha_modificacion timestamp NULL,
	estado varchar(3) DEFAULT 'ACT'::character varying NULL,
	CONSTRAINT dominios_pkey PRIMARY KEY (id)
);

/*REGISTRO DE ESTADOS DE RGISTRO DE DOMINIO*/
INSERT INTO administracion.dominios (concepto, descripcion, valor1, valor2, orden, estado) VALUES('ESTADO REGISTRO', 'TIPOS DE ESTADO DE LOS REGISTROS', 'ACT', 'ACTIVO', 1,  'ACT');
INSERT INTO administracion.dominios (concepto, descripcion, valor1, valor2, orden, estado) VALUES('ESTADO REGISTRO', 'TIPOS DE ESTADO DE LOS REGISTROS', 'HI', 'HISTORICO', 2,  'ACT');
INSERT INTO administracion.dominios (concepto, descripcion, valor1, valor2, orden, estado) VALUES('ESTADO REGISTRO', 'TIPOS DE ESTADO DE LOS REGISTROS', 'ANU', 'ANULADO', 3,  'ACT');
INSERT INTO administracion.dominios (concepto, descripcion, valor1, valor2, orden, estado) VALUES('ESTADO REGISTRO', 'TIPOS DE ESTADO DE LOS REGISTROS', 'BAJ', 'BAJA', 4,  'ACT');
INSERT INTO administracion.dominios (concepto, descripcion, valor1, valor2, orden, estado) VALUES('ESTADO REGISTRO', 'TIPOS DE ESTADO DE LOS REGISTROS', 'CON', 'CONFIRMADO', 5,  'ACT');
INSERT INTO administracion.dominios (concepto, descripcion, valor1, valor2, orden, estado) VALUES('ESTADO REGISTRO', 'TIPOS DE ESTADO DE LOS REGISTROS', 'PEN', 'PENDIENTE', 6,  'ACT');
INSERT INTO administracion.dominios (concepto, descripcion, valor1, valor2, orden, estado) VALUES('ESTADO REGISTRO', 'TIPOS DE ESTADO DE LOS REGISTROS', 'REV', 'REVISADO', 7,  'ACT');
INSERT INTO administracion.dominios (concepto, descripcion, valor1, valor2, orden, estado) VALUES('ESTADO REGISTRO', 'TIPOS DE ESTADO DE LOS REGISTROS', 'VER', 'VERIFICADO', 8,  'ACT');
INSERT INTO administracion.dominios (concepto, descripcion, valor1, valor2, orden, estado) VALUES('ESTADO REGISTRO', 'TIPOS DE ESTADO DE LOS REGISTROS', 'INI', 'INICIADO', 9,  'ACT');
INSERT INTO administracion.dominios (concepto, descripcion, valor1, valor2, orden, estado) VALUES('ESTADO REGISTRO', 'TIPOS DE ESTADO DE LOS REGISTROS', 'CNS', 'CONSOLIDADO', 10,  'ACT');
INSERT INTO administracion.dominios (concepto, descripcion, valor1, valor2, orden, estado) VALUES('ESTADO REGISTRO', 'TIPOS DE ESTADO DE LOS REGISTROS', 'REC', 'RECHAZADO', 11,  'ACT');
INSERT INTO administracion.dominios (concepto, descripcion, valor1, valor2, orden, estado) VALUES('ESTADO REGISTRO', 'TIPOS DE ESTADO DE LOS REGISTROS', 'ESP', 'EN ESPERA', 12,  'ACT');
INSERT INTO administracion.dominios (concepto, descripcion, valor1, valor2, orden, estado) VALUES('ESTADO REGISTRO', 'TIPOS DE ESTADO DE LOS REGISTROS', 'PRO', 'PROCESADO', 13,  'ACT');
INSERT INTO administracion.dominios (concepto, descripcion, valor1, valor2, orden, estado) VALUES('ESTADO REGISTRO', 'TIPOS DE ESTADO DE LOS REGISTROS', 'EX', 'EXPIRADO', 14,  'ACT');
INSERT INTO administracion.dominios (concepto, descripcion, valor1, valor2, orden, estado) VALUES('ESTADO REGISTRO', 'TIPOS DE ESTADO DE LOS REGISTROS', 'RES', 'RESETEADO', 15,  'ACT');
INSERT INTO administracion.dominios (concepto, descripcion, valor1, valor2, orden, estado) VALUES('ESTADO REGISTRO', 'TIPOS DE ESTADO DE LOS REGISTROS', 'RCD', 'RECEPCIONADO', 16,  'ACT');
INSERT INTO administracion.dominios (concepto, descripcion, valor1, valor2, orden, estado) VALUES('ESTADO REGISTRO', 'TIPOS DE ESTADO DE LOS REGISTROS', 'CER', 'CERTIFICADO', 17,  'ACT');
INSERT INTO administracion.dominios (concepto, descripcion, valor1, valor2, orden, estado) VALUES('ESTADO REGISTRO', 'TIPOS DE ESTADO DE LOS REGISTROS', 'ELI', 'ELIMINADO', 18,  'ACT');
INSERT INTO administracion.dominios (concepto, descripcion, valor1, valor2, orden, estado) VALUES('ESTADO REGISTRO', 'TIPOS DE ESTADO DE LOS REGISTROS', 'ARC', 'ARCHIVADO', 19,  'ACT');




/*========================CONFIGURACIONES=============================*/
CREATE TABLE configuraciones.gestion (
	id serial4 NOT NULL,
	gestion int4 NULL,
	fecha_inicio date NULL,
	fecha_fin date NULL,
	fecha_registro timestamp DEFAULT now() NULL,
	fecha_modificacion timestamp NULL,
	estado varchar(3) DEFAULT 'ACT'::character varying NULL,
	CONSTRAINT gestion_pkey PRIMARY KEY (id)
);
/*CONTABILIDAD*/
create table contabilidad.plancuentas(
	id serial4 NOT null,
	codigo varchar(10),
	descripcion varchar(300),
	nivel int4 NULL,
	orden int4 null,
	padre int4 NULL,
	ruta varchar(30) NULL,
	hijos varchar(30) NULL,
	fecha_registro timestamp DEFAULT now() NULL,
	fecha_modificacion timestamp NULL,
	estado varchar(2) DEFAULT 'AC'::character varying NULL,
	sigla varchar(5) NULL,
	CONSTRAINT plancuentas_pkey PRIMARY KEY (id)
);
