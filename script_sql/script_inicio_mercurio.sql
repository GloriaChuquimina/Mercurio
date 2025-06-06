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
/*DOMINIO TIPOS DE MOVIMIENTO*/
INSERT INTO administracion.dominios (concepto, descripcion, valor1, valor2, orden, estado) VALUES('TIPO MOVIMIENTO', 'TIPO DE MOVIMIENTO DE LOS REGISTROS CONTABLES', 'DB', 'DEBE', 1,  'ACT');
INSERT INTO administracion.dominios (concepto, descripcion, valor1, valor2, orden, estado) VALUES('TIPO MOVIMIENTO', 'TIPO DE MOVIMIENTO DE LOS REGISTROS CONTABLES', 'HB', 'HABER', 2,  'ACT');
/*DOMINIO TIPOS DE REGISTROS COMPROBANTES CONTABLES*/
INSERT INTO administracion.dominios (concepto, descripcion, valor1, valor2, orden, estado) VALUES('TIPO COMPROBANTES CONTABLE', 'TIPO DE COMPROBANTES DE REGISTRO CONTABLE', 'CD', 'DIARIO', 1,  'ACT');
INSERT INTO administracion.dominios (concepto, descripcion, valor1, valor2, orden, estado) VALUES('TIPO COMPROBANTES CONTABLE', 'TIPO DE COMPROBANTES DE REGISTRO CONTABLE', 'TR', 'TRASPASO', 2,  'ACT');
INSERT INTO administracion.dominios (concepto, descripcion, valor1, valor2, orden, estado) VALUES('TIPO COMPROBANTES CONTABLE', 'TIPO DE COMPROBANTES DE REGISTRO CONTABLE', 'GA', 'GASTO', 3,  'ACT');
INSERT INTO administracion.dominios (concepto, descripcion, valor1, valor2, orden, estado) VALUES('TIPO COMPROBANTES CONTABLE', 'TIPO DE COMPROBANTES DE REGISTRO CONTABLE', 'IN', 'INGRESO', 4,  'ACT');



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
	id_funcionario_registro int4 null,
	fecha_modificacion timestamp NULL,
	id_funcionario_update int4 null,
	estado varchar(2) DEFAULT 'AC'::character varying NULL,
	sigla varchar(5) NULL,	
	CONSTRAINT plancuentas_pkey PRIMARY KEY (id)
);
CREATE TABLE contabilidad.plancuenta_dependencia (
	id serial4 NOT NULL,
	id_plancuenta int4 NULL,
	id_dependecia int4 NULL,
	fecha_registro timestamp DEFAULT now() NULL,
	id_usuario_registro int4 NULL,
	fecha_modificacion timestamp NULL,
	estado varchar(3) DEFAULT 'AC'::character varying null,
	CONSTRAINT entidad_dependencia_pkey PRIMARY KEY (id)
);

/*ENTIDAD*/
CREATE TABLE administracion.entidad (
	id serial4 NOT NULL,
	nombre varchar(100) NULL,
	sigla varchar(100) NULL,
	fecha_registro timestamp DEFAULT now() NULL,
	fecha_modificacion timestamp NULL,
	activo bool NULL,
	id_usuario int4 NULL,
	id_dependencia int4 NULL,
	observaciones text NULL,
	/*documento_admin varchar(100) NULL,
	nro_admin varchar(15) NULL,
	fecha_admin date NULL,*/
	estado varchar(3) DEFAULT 'AC'::character varying NULL,
	CONSTRAINT entidad_pkey PRIMARY KEY (id)
);
CREATE TABLE administracion.entidad_dependencia (
	id serial4 NOT NULL,
	id_entidad int4 NULL,
	id_dependecia int4 NULL,
	fecha_registro timestamp DEFAULT now() NULL,
	id_usuario_registro int4 NULL,
	fecha_modificacion timestamp NULL,
	estado varchar(3) DEFAULT 'AC'::character varying null,
	CONSTRAINT entidad_dependencia_pkey PRIMARY KEY (id)
);


/*REGISTRO COMPROBANTE */

CREATE TABLE contabilidad.comprobante (
	id serial4 NOT NULL,
	id_entidad int4 not null,
	tipo_comprobante varchar(3) not null,
	correlativo int4 null,
	periodo varchar(25) not null,
	gestion int4 null,	
	glosa_comprobante text null,
	fecha_comprobante timestamp not NULL,
	tipo_cambio numeric null,
	fecha_registro timestamp DEFAULT now() NULL,
	id_usuario_registro int4 NULL,
	fecha_modificacion timestamp NULL,
	id_funcionario_update int4 null,
	estado varchar(3) DEFAULT 'AC'::character varying null,
	sec_log numeric(10,0),
	CONSTRAINT comprobante_pkey PRIMARY KEY (id)
);

COMMENT ON TABLE contabilidad.comprobante IS 'Tabla que registra los distintos tipos de comprobantes contables.';

COMMENT ON COLUMN contabilidad.comprobante.id IS 'Identificador único del comprobante.';
COMMENT ON COLUMN contabilidad.comprobante.id_entidad IS 'ID de la entidad a la que pertenece el comprobante.';
COMMENT ON COLUMN contabilidad.comprobante.tipo_comprobante IS 'Tipo de comprobante (CD, TR, GA, IN, etc.).';
COMMENT ON COLUMN contabilidad.comprobante.correlativo IS 'Número secuencial del comprobante para la gestión y entidad.';
COMMENT ON COLUMN contabilidad.comprobante.periodo IS 'Periodo contable en el que se emite el comprobante.';
COMMENT ON COLUMN contabilidad.comprobante.gestion IS 'Año o gestión contable.';
COMMENT ON COLUMN contabilidad.comprobante.glosa_comprobante IS 'Descripción o glosa general del comprobante.';
COMMENT ON COLUMN contabilidad.comprobante.fecha_comprobante IS 'Fecha en que se realiza el comprobante.';
COMMENT ON COLUMN contabilidad.comprobante.tipo_cambio IS 'Tipo de cambio aplicado.';
COMMENT ON COLUMN contabilidad.comprobante.fecha_registro IS 'Fecha de registro del comprobante en el sistema.';
COMMENT ON COLUMN contabilidad.comprobante.id_usuario_registro IS 'Usuario que registró el comprobante.';
COMMENT ON COLUMN contabilidad.comprobante.fecha_modificacion IS 'Fecha de la última modificación del comprobante.';
COMMENT ON COLUMN contabilidad.comprobante.id_funcionario_update IS 'Funcionario que realizó la última modificación.';
COMMENT ON COLUMN contabilidad.comprobante.estado IS 'Estado del comprobante (AC = Activo, AN = Anulado, etc.).';
COMMENT ON COLUMN contabilidad.comprobante.sec_log IS 'Campo para control de cambios o logging.';

CREATE TABLE contabilidad.detalle_comprobante (
	id serial4 NOT NULL,
	id_entidad int4 not null,
	id_cuenta int4 not null,
	tipo_movimiento varchar(3) not null,
	tipo_cambio numeric null,
	importe_moneda_nacional numeric DEFAULT 0 null,
	importe_moneda_extranjera numeric DEFAULT 0 null,
	glosa_cuenta text null,
	fecha_registro timestamp DEFAULT now() NULL,
	id_usuario_registro int4 NULL,
	fecha_modificacion timestamp NULL,
	id_funcionario_update int4 null,
	estado varchar(3) DEFAULT 'AC'::character varying NULL,
	sec_log numeric(10,0),
	CONSTRAINT detalle_comprobante_pkey PRIMARY KEY (id)
);


COMMENT ON TABLE contabilidad.detalle_comprobante IS 'Detalle de las cuentas asociadas a cada comprobante contable.';

COMMENT ON COLUMN contabilidad.detalle_comprobante.id IS 'Identificador único del detalle.';
COMMENT ON COLUMN contabilidad.detalle_comprobante.id_entidad IS 'Entidad a la que pertenece el detalle del comprobante.';
COMMENT ON COLUMN contabilidad.detalle_comprobante.id_cuenta IS 'ID de la cuenta contable utilizada en el asiento.';
COMMENT ON COLUMN contabilidad.detalle_comprobante.tipo_movimiento IS 'Tipo de movimiento contable (DBE = Debe, HAB = Haber).';
COMMENT ON COLUMN contabilidad.detalle_comprobante.tipo_cambio IS 'Tipo de cambio aplicado a la cuenta, si corresponde.';
COMMENT ON COLUMN contabilidad.detalle_comprobante.importe_moneda_nacional IS 'Importe del asiento en moneda nacional.';
COMMENT ON COLUMN contabilidad.detalle_comprobante.importe_moneda_extranjera IS 'Importe del asiento en moneda extranjera.';
COMMENT ON COLUMN contabilidad.detalle_comprobante.glosa_cuenta IS 'Glosa o descripción del asiento contable.';
COMMENT ON COLUMN contabilidad.detalle_comprobante.fecha_registro IS 'Fecha en que se registró el detalle.';
COMMENT ON COLUMN contabilidad.detalle_comprobante.id_usuario_registro IS 'ID del usuario que registró el detalle.';
COMMENT ON COLUMN contabilidad.detalle_comprobante.fecha_modificacion IS 'Fecha de la última modificación del registro.';
COMMENT ON COLUMN contabilidad.detalle_comprobante.id_funcionario_update IS 'Funcionario que realizó la última modificación.';
COMMENT ON COLUMN contabilidad.detalle_comprobante.estado IS 'Estado del detalle (AC = Activo, AN = Anulado, etc.).';
COMMENT ON COLUMN contabilidad.detalle_comprobante.sec_log IS 'Campo para control de cambios o logging.';

