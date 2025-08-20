/* ESQUEMAS*/
CREATE SCHEMA administracion AUTHORIZATION postgres;
CREATE SCHEMA configuraciones AUTHORIZATION postgres;
CREATE SCHEMA parametricas AUTHORIZATION postgres;
CREATE SCHEMA contabilidad AUTHORIZATION postgres;
CREATE SCHEMA geografia AUTHORIZATION postgres;
CREATE SCHEMA correlativos AUTHORIZATION postgres;
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
/*MESES*/
INSERT INTO administracion.dominios (concepto, descripcion, valor1, valor2, orden, estado) VALUES('MESES', 'DESCRIPCION MESES', '1', 'ENERO', 1, 'ACT');
INSERT INTO administracion.dominios (concepto, descripcion, valor1, valor2, orden, estado) VALUES('MESES', 'DESCRIPCION MESES', '2', 'FEBRERO', 2, 'ACT');
INSERT INTO administracion.dominios (concepto, descripcion, valor1, valor2, orden, estado) VALUES('MESES', 'DESCRIPCION MESES', '3', 'MARZO', 3, 'ACT');
INSERT INTO administracion.dominios (concepto, descripcion, valor1, valor2, orden, estado) VALUES('MESES', 'DESCRIPCION MESES', '4', 'ABRIL', 4, 'ACT');
INSERT INTO administracion.dominios (concepto, descripcion, valor1, valor2, orden, estado) VALUES('MESES', 'DESCRIPCION MESES', '5', 'MAYO', 5, 'ACT');
INSERT INTO administracion.dominios (concepto, descripcion, valor1, valor2, orden, estado) VALUES('MESES', 'DESCRIPCION MESES', '6', 'JUNIO', 6, 'ACT');
INSERT INTO administracion.dominios (concepto, descripcion, valor1, valor2, orden, estado) VALUES('MESES', 'DESCRIPCION MESES', '7', 'JULIO', 7, 'ACT');
INSERT INTO administracion.dominios (concepto, descripcion, valor1, valor2, orden, estado) VALUES('MESES', 'DESCRIPCION MESES', '8', 'AGOSTO', 8, 'ACT');
INSERT INTO administracion.dominios (concepto, descripcion, valor1, valor2, orden, estado) VALUES('MESES', 'DESCRIPCION MESES', '9', 'SEPTIEMBRE', 9, 'ACT');
INSERT INTO administracion.dominios (concepto, descripcion, valor1, valor2, orden, estado) VALUES('MESES', 'DESCRIPCION MESES', '10', 'OCTUBRE', 10, 'ACT');
INSERT INTO administracion.dominios (concepto, descripcion, valor1, valor2, orden, estado) VALUES('MESES', 'DESCRIPCION MESES', '11', 'NOVIEMBRE', 11, 'ACT');
INSERT INTO administracion.dominios (concepto, descripcion, valor1, valor2, orden, estado) VALUES('MESES', 'DESCRIPCION MESES', '12', 'DICIEMBRE', 12, 'ACT');

/*MONEDA*/
INSERT INTO administracion.dominios (concepto, descripcion, valor1, valor2, orden) VALUES('MONEDA', 'TIPO MONEDA DE CAMBIO', 'USD', 'DOLAR', 2);
INSERT INTO administracion.dominios (concepto, descripcion, valor1, valor2, orden) VALUES('MONEDA', 'TIPO MONEDA DE CAMBIO', 'BOB', 'BOLIVIANO', 1);

/*TIPO DE CIERRE*/
INSERT INTO administracion.dominios (concepto, descripcion, valor1, valor2, orden) VALUES('TIPO CIERRE', 'TIPO DE CIERRE CONTABLE', 'CIR', 'CIERRE DE RESULTADOS', 1);
INSERT INTO administracion.dominios (concepto, descripcion, valor1, valor2, orden) VALUES('TIPO CIERRE', 'TIPO DE CIERRE CONTABLE', 'CIB', 'CIERRE DE BALANCE', 2);



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
	estado varchar(3) DEFAULT 'AC'::character varying NULL,
	CONSTRAINT entidad_pkey PRIMARY KEY (id)
);
CREATE TABLE administracion.entidad_dependencia (
	id serial4 NOT NULL,
	id_entidad int4 NULL,
	id_dependencia int4 NULL,
	fecha_registro timestamp DEFAULT now() NULL,
	id_usuario_registro int4 NULL,
	fecha_modificacion timestamp NULL,
	estado varchar(3) DEFAULT 'AC'::character varying NULL,
	CONSTRAINT entidad_dependencia_pkey PRIMARY KEY (id)
);


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
	id serial4 NOT NULL,
	codigo_cuenta varchar(10) NULL,
	codigo varchar(50) NULL,
	sigla varchar(5) NULL,
	descripcion varchar(300) NULL,
	nivel int4 NULL,
	orden int4 NULL,
	padre int4 NULL,
	ruta varchar(30) NULL,
	hijos varchar(30) NULL,
	fecha_registro timestamp DEFAULT now() NULL,
	id_funcionario_registro int4 NULL,
	fecha_modificacion timestamp NULL,
	id_funcionario_update int4 NULL,
	id_dependencia int4 NULL,
	estado varchar(3) DEFAULT 'ACT'::character varying NULL,
	CONSTRAINT plancuentas_pkey PRIMARY KEY (id)
);
create table contabilidad.plancuentas_auxiliares(
	id serial4 NOT null,
	id_plancuenta int4 NULL,
	codigo varchar(50),
	descripcion varchar(300),
	fecha_registro timestamp DEFAULT now() NULL,
	id_funcionario_registro int4 null,
	fecha_modificacion timestamp NULL,
	id_funcionario_update int4 null,
	estado varchar(3) DEFAULT 'ACT'::character varying NULL,
	CONSTRAINT plancuentas_auxiliares_pkey PRIMARY KEY (id)
);
CREATE TABLE contabilidad.plancuenta_dependencia (
	id serial4 NOT NULL,
	id_plancuenta int4 NULL,
	id_dependencia int4 NULL,
	fecha_registro timestamp DEFAULT now() NULL,
	id_usuario_registro int4 NULL,
	fecha_modificacion timestamp NULL,
	estado varchar(3) DEFAULT 'ACT'::character varying null,
	CONSTRAINT entidad_dependencia_pkey PRIMARY KEY (id)
);
/*REGISTRO COMPROBANTE */

CREATE TABLE contabilidad.comprobante (
	id serial4 NOT NULL,
	id_entidad int4 NOT NULL,
	tipo_comprobante varchar(3) NOT NULL,
	correlativo int4 NULL,
	periodo varchar(25) NOT NULL,
	gestion int4 NULL,
	referencia_comprobante text NULL,
	glosa_comprobante text NULL,
	fecha_comprobante timestamp NOT NULL,
	tipo_cambio numeric NULL,
	fecha_registro timestamp DEFAULT now() NULL,
	id_usuario_registro int4 NULL,
	fecha_modificacion timestamp NULL,
	id_funcionario_update int4 NULL,
	estado varchar(3) DEFAULT 'ACT'::character varying NULL,
	sec_log numeric(10) NULL,
	tipo_cierre varchar(3) NULL,
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
	id_entidad int4 NOT NULL,
	id_comprobante int4 NOT NULL,
	id_cuenta int4 NOT NULL,
	id_cuenta_auxiliar int4 NULL,
	tipo_movimiento varchar(3) NOT NULL,
	tipo_cambio numeric(10, 2) NULL,
	importe_moneda_nacional numeric(10, 2) DEFAULT 0 NULL,
	importe_moneda_extranjera numeric DEFAULT 0 NULL,
	glosa_cuenta text NULL,
	fecha_registro timestamp DEFAULT now() NULL,
	id_usuario_registro int4 NULL,
	fecha_modificacion timestamp NULL,
	id_funcionario_update int4 NULL,
	estado varchar(3) DEFAULT 'ACT'::character varying NULL,
	estado_balance varchar(3) DEFAULT 'PEN'::character varying NULL,
	estado_resultado varchar(3) NULL,
	sec_log numeric(10) NULL,
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


CREATE TABLE contabilidad.tipo_cambio (
	id serial4 NOT NULL,
	fecha date NULL,
	valor numeric null,
	id_usuario_registro int4 NULL,
	fecha_registro timestamp DEFAULT now() NULL,
	fecha_modificacion timestamp NULL,
	estado varchar(3) DEFAULT 'ACT'::character varying NULL,
	sec_log numeric(10,0),
	CONSTRAINT tipo_cambio_pkey PRIMARY KEY (id)
);


COMMENT ON TABLE  contabilidad.tipo_cambio  IS 'Define los valores de los tipo de cambio registrados por fecha.';

COMMENT ON COLUMN contabilidad.tipo_cambio.id IS 'Identificador único del tipo de cambio.';
COMMENT ON COLUMN contabilidad.tipo_cambio.fecha IS 'Fecha a la que corresponde el tipo de cambio registrado.';
COMMENT ON COLUMN contabilidad.tipo_cambio.valor IS 'Valor del tipo de cambio.';
COMMENT ON COLUMN contabilidad.tipo_cambio.id_usuario_registro IS 'ID del usuario que registró el valor. Se usa para trazabilidad.';
COMMENT ON COLUMN contabilidad.tipo_cambio.fecha_registro IS 'Fecha y hora en que se registró el tipo de cambio. Por defecto: now().';
COMMENT ON COLUMN contabilidad.tipo_cambio.fecha_modificacion IS 'Fecha de la última modificación (si la hubo). Puede ser NULL si no se modificó.';
COMMENT ON COLUMN contabilidad.tipo_cambio.estado IS 'Define el estado del registro(AC = Activo, AN = Anulado, etc.).';
COMMENT ON COLUMN contabilidad.tipo_cambio.sec_log IS 'Campo para fines de auditoría o bitácora';



-- alter table contabilidad.detalle_comprobante add column id_cuenta_auxiliar int4 null;
-- alter table contabilidad.detalle_comprobante add column saldo_moneda_nacional numeric(10, 2) null;
alter table contabilidad.detalle_comprobante add column estado_balance varchar(3) DEFAULT 'PEN'::character varying NULL,
alter table contabilidad.detalle_comprobante add column estado_resultado varchar(3);
alter table contabilidad.comprobante add column tipo_cierre varchar(3);


/*TABLAS DE CONTROL PARA LOS CORRELATIVOS*/
 CREATE TABLE correlativos.correlativos (
	id serial4 NOT NULL,
	nombre_documento varchar(50) NULL,
	abreviatura varchar(4) NULL,
	descripcion varchar(255) NULL,
	estado varchar(3) DEFAULT 'ACT'::character varying NULL,
	CONSTRAINT correlativos_pkey PRIMARY KEY (id)
);

COMMENT ON TABLE correlativos.correlativos IS 'Define los tipos de documentos que requieren un número correlativo dentro del sistema.';

COMMENT ON COLUMN correlativos.correlativos.id IS 'Identificador único para cada tipo de documento correlativo.';
COMMENT ON COLUMN correlativos.correlativos.nombre_documento IS 'Nombre completo del documento que utilizará el correlativo (ej. "Nota de Venta", "Factura").';
COMMENT ON COLUMN correlativos.correlativos.abreviatura IS 'Abreviatura corta que identifica el tipo de documento (ej. "NV", "FAC").';
COMMENT ON COLUMN correlativos.correlativos.descripcion IS 'Descripción detallada del propósito y uso del tipo de documento.';
COMMENT ON COLUMN correlativos.correlativos.estado IS 'Estado del detalle (AC = Activo, AN = Anulado, etc.).';



CREATE TABLE correlativos.correlativos_entidad_gestion (
	id serial4 NOT NULL,
	id_correlativo serial4 not null,
	id_entidad serial4 not null,
	id_dependencia serial4 not null,
	gestion int4 NULL,
	correlativo int4 DEFAULT 0 NULL,
	id_usuario_registro int4 NULL,
	fecha_registro timestamp DEFAULT now() NULL,
	fecha_modificacion timestamp NULL,
	estado varchar(3) DEFAULT 'ACT'::character varying NULL,
	CONSTRAINT correlativos_gestion_pkey PRIMARY KEY (id)
);


COMMENT ON TABLE correlativos.correlativos_entidad_gestion IS 'Gestiona los números correlativos asignados a documentos para una entidad y gestión específicas.';

COMMENT ON COLUMN correlativos.correlativos_entidad_gestion.id IS 'Identificador único para cada asignación de correlativo a una entidad y gestión.';
COMMENT ON COLUMN correlativos.correlativos_entidad_gestion.id_correlativo IS 'Referencia al ID del tipo de documento correlativo de la tabla "correlativos".';
COMMENT ON COLUMN correlativos.correlativos_entidad_gestion.id_entidad IS 'Identificador de la entidad a la que pertenece este correlativo.';
COMMENT ON COLUMN correlativos.correlativos_entidad_gestion.id_dependencia IS 'Identificador de la dependencia o unidad organizacional dentro de la entidad.';
COMMENT ON COLUMN correlativos.correlativos_entidad_gestion.gestion IS 'Año o período de gestión al que aplica el correlativo (ej. 2023, 2024).';
COMMENT ON COLUMN correlativos.correlativos_entidad_gestion.correlativo IS 'Numero correlativo utilizado para este tipo de documento, entidad y gestión.';
COMMENT ON COLUMN correlativos.correlativos_entidad_gestion.fecha_registro IS 'Fecha ede registró de cuando se creó este registro de asignación de correlativo.';
COMMENT ON COLUMN correlativos.correlativos_entidad_gestion.fecha_modificacion IS 'Fecha de la última modificación del registro.';
COMMENT ON COLUMN correlativos.correlativos_entidad_gestion.estado IS 'Estado del detalle (AC = Activo, AN = Anulado, etc.).';


/*INSERT CORRELATIVOS*/

INSERT INTO correlativos.correlativos (nombre_documento, abreviatura, descripcion, estado) VALUES('COMPROBANTE DE DIARIO', 'CD', 'REGISTRO DE TODOS LOS INGRESOS Y EGRESOS EFECTUADOS POR LA EMPRESA , EN EL ORDEN QUE SE VAYA REALIZANDO DURANTE EL PERÍODO (TRANSFERENCIAS,PAGOS,COBROS,GASTOS,OTROS)', 'ACT');
INSERT INTO correlativos.correlativos (nombre_documento, abreviatura, descripcion, estado) VALUES('COMPROGANTE DE TRASPASO', 'TR', 'NO REGISTRA INGRESOS NI EGRESOS DE FONDOS, SINO EL MOVIMIENTO DE LOS RECURSOS DE LA EMPRESA.', 'ACT');
INSERT INTO correlativos.correlativos (nombre_documento, abreviatura, descripcion, estado) VALUES('COMPROBANTE DE GASTO', 'GA', NULL, 'ACT');
INSERT INTO correlativos.correlativos (nombre_documento, abreviatura, descripcion, estado) VALUES('COMPROBANTE DE INGRESO', 'IN', NULL, 'ACT');



/*TABLAS CIERRES DE CUENTAS*/
CREATE TABLE contabilidad.cierres_contables (
    id serial4 NOT NULL, 
    id_entidad int4 not null,
    gestion int4 NOT NULL,          
    mes int4 NOT NULL,              
    tipo_cierre varchar(3) NOT null,
    fecha_cierre DATE NOT NULL,    
	id_comprobante int4 null,
	comprobante TEXT,
    descripcion TEXT,              

    -- Totales en moneda local
    saldo_resultado NUMERIC(14, 2) NULL,
    saldo_activo NUMERIC(14, 2) NULL,
    saldo_pasivo NUMERIC(14, 2) NULL,
    saldo_patrimonio NUMERIC(14, 2) NULL,
    saldo_cuentas_deudor NUMERIC(14, 2) NULL,
    saldo_cuentas_acreedor NUMERIC(14, 2) NULL,

    -- Totales en USD
    saldo_resultado_usd NUMERIC(14, 2) NULL,
    saldo_activo_usd NUMERIC(14, 2) NULL,
    saldo_pasivo_usd NUMERIC(14, 2) NULL,
    saldo_patrimonio_usd NUMERIC(14, 2) NULL,
    saldo_cuentas_deudor_usd NUMERIC(14, 2) NULL,
    saldo_cuentas_acreedor_usd NUMERIC(14, 2) NULL,

    id_usuario_registro int4  NULL,  
    fecha_registro TIMESTAMP DEFAULT NOW(),
	fecha_modificacion timestamp NULL,
	estado varchar(3) DEFAULT 'ACT'::character varying NULL,
	sec_log numeric(10,0),	
	CONSTRAINT cierres_contables_pkey PRIMARY KEY (id)
);

-- Comentarios para la tabla y columnas
COMMENT ON TABLE contabilidad.cierres_contables IS 'Tabla que almacena los registros de cierres contables (RESULTADOS o BALANCE) con sus totales en moneda local y USD.';

COMMENT ON COLUMN contabilidad.cierres_contables.id IS 'Identificador único del cierre contable.';
COMMENT ON COLUMN contabilidad.cierres_contables.id_entidad IS 'Identificador de la entidad al que pertenece el cierre contable';
COMMENT ON COLUMN contabilidad.cierres_contables.gestion IS 'Año de la gestión contable (ej. 2025).';
COMMENT ON COLUMN contabilidad.cierres_contables.mes IS 'Mes del cierre (1-12). Si es 0, indica cierre anual.';
COMMENT ON COLUMN contabilidad.cierres_contables.tipo_cierre IS 'Tipo de cierre: RESULTADOS o BALANCE.';
COMMENT ON COLUMN contabilidad.cierres_contables.fecha_cierre IS 'Fecha exacta en la que se ejecutó el cierre.';
COMMENT ON COLUMN contabilidad.cierres_contables.id_comprobante IS 'Identificador unico del comprobante de resultados del cierre';
COMMENT ON COLUMN contabilidad.cierres_contables.descripcion IS 'Notas u observaciones adicionales del cierre contable.';

-- Totales moneda local
COMMENT ON COLUMN contabilidad.cierres_contables.saldo_resultado IS 'Saldo total de la cuenta de resultados en moneda local.';
COMMENT ON COLUMN contabilidad.cierres_contables.saldo_activo IS 'Saldo total de las cuentas de activo en moneda local.';
COMMENT ON COLUMN contabilidad.cierres_contables.saldo_pasivo IS 'Saldo total de las cuentas de pasivo en moneda local.';
COMMENT ON COLUMN contabilidad.cierres_contables.saldo_patrimonio IS 'Saldo total de las cuentas de patrimonio en moneda local.';
COMMENT ON COLUMN contabilidad.cierres_contables.saldo_cuentas_deudor IS 'Saldo total de cuentas deudoras en moneda local.';
COMMENT ON COLUMN contabilidad.cierres_contables.saldo_cuentas_acreedor IS 'Saldo total de cuentas acreedoras en moneda local.';

-- Totales USD
COMMENT ON COLUMN contabilidad.cierres_contables.saldo_resultado_usd IS 'Saldo total de la cuenta de resultados en USD.';
COMMENT ON COLUMN contabilidad.cierres_contables.saldo_activo_usd IS 'Saldo total de las cuentas de activo en USD.';
COMMENT ON COLUMN contabilidad.cierres_contables.saldo_pasivo_usd IS 'Saldo total de las cuentas de pasivo en USD.';
COMMENT ON COLUMN contabilidad.cierres_contables.saldo_patrimonio_usd IS 'Saldo total de las cuentas de patrimonio en USD.';
COMMENT ON COLUMN contabilidad.cierres_contables.saldo_cuentas_deudor_usd IS 'Saldo total de cuentas deudoras en USD.';
COMMENT ON COLUMN contabilidad.cierres_contables.saldo_cuentas_acreedor_usd IS 'Saldo total de cuentas acreedoras en USD.';

COMMENT ON COLUMN contabilidad.cierres_contables.estado IS 'Estado del cierre: APROBADO, ANULADO, PENDIENTE, etc.';
COMMENT ON COLUMN contabilidad.cierres_contables.id_usuario_registro IS 'ID del usuario que realizó el cierre.';
COMMENT ON COLUMN contabilidad.cierres_contables.fecha_registro IS 'Fecha y hora de registro del cierre contable.';
COMMENT ON COLUMN contabilidad.cierres_contables.fecha_modificacion IS 'Fecha y hora de registro de modificacion del registro cierre contable.';
COMMENT ON COLUMN contabilidad.cierres_contables.sec_log IS 'Campo para fines de auditoría o bitácora';

-- ======================================
-- Tabla: contabilidad.cierres_contables_detalle
-- Detalle de saldos por cuenta en un cierre
-- ======================================
CREATE TABLE contabilidad.cierres_contables_detalle (
    id serial4 not NULL,
    id_entidad int4 not null,
    id_cierre int4 not null, -- Relación con la cabecera de cierre
    id_cuenta int4 not null,
    codigo_cuenta VARCHAR(50) NOT NULL, -- Código de la cuenta contable
    nombre_cuenta VARCHAR(300) NOT NULL, -- Nombre de la cuenta contable

    -- Saldos en moneda local
    saldo_local NUMERIC(14, 2) NULL, -- Saldo total en moneda local
    saldo_deudor NUMERIC(14, 2) NULL, -- Saldo deudor en moneda local
    saldo_acreedor NUMERIC(14, 2) NULL, -- Saldo acreedor en moneda local

    -- Saldos en USD
    saldo_usd NUMERIC(14, 2) NULL, -- Saldo total en USD
    saldo_deudor_usd NUMERIC(14, 2) NULL, -- Saldo deudor en USD
    saldo_acreedor_usd NUMERIC(14, 2) null, -- Saldo acreedor en USD
    
    id_usuario_registro int4  NULL,  
    fecha_registro TIMESTAMP DEFAULT NOW(),
	fecha_modificacion timestamp NULL,
	estado varchar(3) DEFAULT 'ACT'::character varying NULL,
	sec_log numeric(10,0),	
    CONSTRAINT cierres_contables_detalle_pkey PRIMARY KEY (id)
);

COMMENT ON TABLE contabilidad.cierres_contables_detalle IS 'Detalle de cuentas y saldos para un cierre contable, tanto en moneda local como en USD.';
COMMENT ON COLUMN contabilidad.cierres_contables_detalle.id IS 'Identificador único del detalle del cierre.';
COMMENT ON COLUMN contabilidad.cierres_contables_detalle.id_entidad IS 'ID del de la entidad al que pertenece este detalle.';
COMMENT ON COLUMN contabilidad.cierres_contables_detalle.id_cierre IS 'ID del cierre contable al que pertenece este detalle.';
COMMENT ON COLUMN contabilidad.cierres_contables_detalle.id_cuenta IS 'ID de la cuenta contable.';
COMMENT ON COLUMN contabilidad.cierres_contables_detalle.codigo_cuenta IS 'Código contable de la cuenta.';
COMMENT ON COLUMN contabilidad.cierres_contables_detalle.nombre_cuenta IS 'Nombre de la cuenta contable.';
COMMENT ON COLUMN contabilidad.cierres_contables_detalle.saldo_local IS 'Saldo total de la cuenta en moneda local.';
COMMENT ON COLUMN contabilidad.cierres_contables_detalle.saldo_deudor IS 'Saldo deudor en moneda local.';
COMMENT ON COLUMN contabilidad.cierres_contables_detalle.saldo_acreedor IS 'Saldo acreedor en moneda local.';
COMMENT ON COLUMN contabilidad.cierres_contables_detalle.saldo_usd IS 'Saldo total de la cuenta en USD.';
COMMENT ON COLUMN contabilidad.cierres_contables_detalle.saldo_deudor_usd IS 'Saldo deudor en USD.';
COMMENT ON COLUMN contabilidad.cierres_contables_detalle.saldo_acreedor_usd IS 'Saldo acreedor en USD.';
COMMENT ON COLUMN contabilidad.cierres_contables_detalle.id_usuario_registro IS 'ID del usuario que realizó el cierre.';
COMMENT ON COLUMN contabilidad.cierres_contables_detalle.fecha_registro IS 'Fecha y hora de registro del cierre contable.';
COMMENT ON COLUMN contabilidad.cierres_contables_detalle.fecha_modificacion IS 'Fecha y hora de registro de modificacion del registro cierre contable.';
COMMENT ON COLUMN contabilidad.cierres_contables_detalle.estado IS 'Estado del cierre: CONSOLIDADO, ANULADO, PENDIENTE, etc.';
COMMENT ON COLUMN contabilidad.cierres_contables_detalle.sec_log IS 'Campo para fines de auditoría o bitácora';