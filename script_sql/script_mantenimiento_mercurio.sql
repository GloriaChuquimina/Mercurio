--TABLA=>PLAN DE CUENTAS
alter table contabilidad.plancuentas add tipo_moneda_cuenta varchar(3) DEFAULT 'BOB'::character varying NULL;
alter table contabilidad.plancuentas add id_entidad int4 NULL;

--TABLAS => DETALLE COMPROBANTES Y COMPROBANTES
alter table contabilidad.detalle_comprobante add column estado_balance varchar(3) DEFAULT 'PEN'::character varying NULL,
alter table contabilidad.detalle_comprobante add column estado_resultado varchar(3);
alter table contabilidad.comprobante add column tipo_cierre varchar(3);

--TABLA=> GESTION

alter table configuraciones.gestion add id_entidad int4 not NULL;
alter table configuraciones.gestion add id_usuario_registro int4 NULL;
alter table configuraciones.gestion add id_dependencia int4 NULL;


