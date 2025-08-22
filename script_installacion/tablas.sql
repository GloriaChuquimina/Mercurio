CREATE EXTENSION tablefunc;
CREATE EXTENSION dblink;

CREATE SCHEMA administracion;
create schema configuraciones;
CREATE SCHEMA contabilidad ;
CREATE SCHEMA correlativos;  



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


CREATE TABLE contabilidad.cierres_contables (
	id serial4 NOT NULL,
	id_entidad int4 NOT NULL,
	gestion int4 NOT NULL,
	mes int4 NOT NULL,
	tipo_cierre varchar(3) NOT NULL,
	fecha_cierre date NOT NULL,
	id_comprobante int4 NULL,
	comprobante text NULL,
	descripcion text NULL,
	saldo_resultado numeric(14, 2) NULL,
	saldo_activo numeric(14, 2) NULL,
	saldo_pasivo numeric(14, 2) NULL,
	saldo_patrimonio numeric(14, 2) NULL,
	saldo_cuentas_deudor numeric(14, 2) NULL,
	saldo_cuentas_acreedor numeric(14, 2) NULL,
	saldo_resultado_usd numeric(14, 2) NULL,
	saldo_activo_usd numeric(14, 2) NULL,
	saldo_pasivo_usd numeric(14, 2) NULL,
	saldo_patrimonio_usd numeric(14, 2) NULL,
	saldo_cuentas_deudor_usd numeric(14, 2) NULL,
	saldo_cuentas_acreedor_usd numeric(14, 2) NULL,
	id_usuario_registro int4 NULL,
	fecha_registro timestamp DEFAULT now() NULL,
	fecha_modificacion timestamp NULL,
	estado varchar(3) DEFAULT 'ACT'::character varying NULL,
	sec_log numeric(10) NULL,
	CONSTRAINT cierres_contables_pkey PRIMARY KEY (id)
);


CREATE TABLE contabilidad.cierres_contables_detalle (
	id serial4 NOT NULL,
	id_entidad int4 NOT NULL,
	id_cierre int4 NOT NULL,
	id_cuenta int4 NOT NULL,
	codigo_cuenta varchar(50) NOT NULL,
	nombre_cuenta varchar(300) NOT NULL,
	saldo_local numeric(14, 2) NULL,
	saldo_deudor numeric(14, 2) NULL,
	saldo_acreedor numeric(14, 2) NULL,
	saldo_usd numeric(14, 2) NULL,
	saldo_deudor_usd numeric(14, 2) NULL,
	saldo_acreedor_usd numeric(14, 2) NULL,
	id_usuario_registro int4 NULL,
	fecha_registro timestamp DEFAULT now() NULL,
	fecha_modificacion timestamp NULL,
	estado varchar(3) DEFAULT 'ACT'::character varying NULL,
	sec_log numeric(10) NULL,
	CONSTRAINT cierres_contables_detalle_pkey PRIMARY KEY (id)
);


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


CREATE TABLE contabilidad.plancuenta_dependencia (
	id serial4 NOT NULL,
	id_plancuenta int4 NULL,
	id_dependencia int4 NULL,
	fecha_registro timestamp DEFAULT now() NULL,
	id_usuario_registro int4 NULL,
	fecha_modificacion timestamp NULL,
	estado varchar(3) DEFAULT 'ACT'::character varying NULL,
	CONSTRAINT entidad_dependencia_pkey PRIMARY KEY (id)
);


CREATE TABLE contabilidad.plancuentas (
	id serial4 NOT NULL,
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
	codigo_cuenta varchar(10) NULL,
	CONSTRAINT plancuentas_pkey PRIMARY KEY (id)
);


CREATE TABLE contabilidad.plancuentas_auxiliares (
	id serial4 NOT NULL,
	id_plancuenta int4 NULL,
	codigo varchar(50) NULL,
	descripcion varchar(300) NULL,
	fecha_registro timestamp DEFAULT now() NULL,
	id_funcionario_registro int4 NULL,
	fecha_modificacion timestamp NULL,
	id_funcionario_update int4 NULL,
	estado varchar(3) DEFAULT 'ACT'::character varying NULL,
	CONSTRAINT plancuentas_auxiliares_pkey PRIMARY KEY (id)
);

CREATE TABLE contabilidad.tipo_cambio (
	id serial4 NOT NULL,
	fecha date NULL,
	valor numeric NULL,
	id_usuario_registro int4 NULL,
	fecha_registro timestamp DEFAULT now() NULL,
	fecha_modificacion timestamp NULL,
	estado varchar(3) DEFAULT 'ACT'::character varying NULL,
	sec_log numeric(10) NULL,
	CONSTRAINT tipo_cambio_pkey PRIMARY KEY (id)
);


CREATE TABLE correlativos.correlativos (
	id serial4 NOT NULL,
	nombre_documento varchar(50) NULL,
	abreviatura varchar(4) NULL,
	descripcion varchar(255) NULL,
	estado varchar(3) DEFAULT 'ACT'::character varying NULL,
	CONSTRAINT correlativos_pkey PRIMARY KEY (id)
);

CREATE TABLE correlativos.correlativos_entidad_gestion (
	id serial4 NOT NULL,
	id_correlativo serial4 NOT NULL,
	id_entidad serial4 NOT NULL,
	id_dependencia serial4 NOT NULL,
	gestion int4 NULL,
	correlativo int4 DEFAULT 0 NULL,
	id_usuario_registro int4 NULL,
	fecha_registro timestamp DEFAULT now() NULL,
	fecha_modificacion timestamp NULL,
	estado varchar(3) DEFAULT 'ACT'::character varying NULL,
	CONSTRAINT correlativos_gestion_pkey PRIMARY KEY (id)
);






























