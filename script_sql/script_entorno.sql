	
-- insert into aplicaciones.aplicaciones(id_entidad,nombre_aplicacion,abreviatura,descripcion_aplicacion,user_administrador)
-- values (1,'SISTEMA CONTABLE MERCURIO', 'MERCURIO','SISTEMA CONTABLE DEL SENAPE','SI');
alter table aplicaciones.aplicaciones rename column valor2 to fecha_baja ;
/*Aplicacion*/
INSERT INTO aplicaciones.aplicaciones (id, id_entidad, nombre_aplicacion, abreviatura, descripcion_aplicacion, user_administrador, fecha_alta, fecha_baja, valor3, valor4, valor5, valor6, estado) VALUES(11, 1, 'SISTEMA CONTABLE MERCURIO', 'MERCURIO', 'SISTEMA CONTABLE DEL SENAPE', 'SI', '2025-05-28 14:31:43.711', NULL, NULL, NULL, NULL, NULL, 'AC');
/*MODULOS*/
INSERT INTO aplicaciones.modulos (id, id_aplicacion, nombre_modulo, abreviatura_modulo, descripcion_modulo, valor1, valor2, valor3, valor4, valor5, valor6, estado) VALUES(130, 11, 'INICIO', 'INI', 'DESPLIEGA INFORMACION DE INICIO', NULL, NULL, NULL, NULL, NULL, NULL, 'AC');
INSERT INTO aplicaciones.modulos (id, id_aplicacion, nombre_modulo, abreviatura_modulo, descripcion_modulo, valor1, valor2, valor3, valor4, valor5, valor6, estado) VALUES(131, 11, 'PARÁMETROS', 'PAR', 'MÓDULOS PARA EL REGISTRO DE DATOS PARAMETRICOS', NULL, NULL, NULL, NULL, NULL, NULL, 'AC');
INSERT INTO aplicaciones.modulos (id, id_aplicacion, nombre_modulo, abreviatura_modulo, descripcion_modulo, valor1, valor2, valor3, valor4, valor5, valor6, estado) VALUES(132, 11, 'PLAN DE CUENTAS', 'PLAC', 'REGISTRO  DE PLANES DE CUENTAS PARA EL SISTEMA MERCURIO', NULL, NULL, NULL, NULL, NULL, NULL, 'AC');
INSERT INTO aplicaciones.modulos (id, id_aplicacion, nombre_modulo, abreviatura_modulo, descripcion_modulo, valor1, valor2, valor3, valor4, valor5, valor6, estado) VALUES(133, 11, 'ENTIDADES', 'ENT', 'REGISTRO DE ENTIDADES SISTEMA MERCURIO', NULL, NULL, NULL, NULL, NULL, NULL, 'AC');
INSERT INTO aplicaciones.modulos (id, id_aplicacion, nombre_modulo, abreviatura_modulo, descripcion_modulo, valor1, valor2, valor3, valor4, valor5, valor6, estado) VALUES(134, 11, 'COMPROBANTES DE REGISTROS CONTABLES', 'CRC', 'LOS COMPROBANTES DE REGISTRO CONTABLE COMPONEN LOS COMPROBANTES DEL DIARIO  Y COMPROBANTES DE TRASPASO ', NULL, NULL, NULL, NULL, NULL, NULL, 'AC');
INSERT INTO aplicaciones.modulos (id, id_aplicacion, nombre_modulo, abreviatura_modulo, descripcion_modulo, valor1, valor2, valor3, valor4, valor5, valor6, estado) VALUES(135, 11, 'LIBRO DIARIO', 'LD', 'REGISTRO DE MOVIMIENTOS DE CUENTAS CONTABLES*********', NULL, NULL, NULL, NULL, NULL, NULL, 'AC');
INSERT INTO aplicaciones.modulos (id, id_aplicacion, nombre_modulo, abreviatura_modulo, descripcion_modulo, valor1, valor2, valor3, valor4, valor5, valor6, estado) VALUES(136, 11, 'LIBRO MAYOR', 'LM', 'TRANSACCIONES QUE APARECEN EN EL LIBRO DIARIO,CON EL PROPÓSITO DE CONOCER SU MOVIMIENTO Y SALDO EN FORMA PARTICULAR; DONDE SE ORGANIZAN Y CLASIFICAN LAS DIFERENTES CUENTAS QUE USA LA UL.', NULL, NULL, NULL, NULL, NULL, NULL, 'AC');
INSERT INTO aplicaciones.modulos (id, id_aplicacion, nombre_modulo, abreviatura_modulo, descripcion_modulo, valor1, valor2, valor3, valor4, valor5, valor6, estado) VALUES(137, 11, 'SUMAS Y SALDOS', 'SS', '....', NULL, NULL, NULL, NULL, NULL, NULL, 'AC');
INSERT INTO aplicaciones.modulos (id, id_aplicacion, nombre_modulo, abreviatura_modulo, descripcion_modulo, valor1, valor2, valor3, valor4, valor5, valor6, estado) VALUES(138, 11, 'ESTADO DE RESULTADOS', 'ER', '....', NULL, NULL, NULL, NULL, NULL, NULL, 'AC');
INSERT INTO aplicaciones.modulos (id, id_aplicacion, nombre_modulo, abreviatura_modulo, descripcion_modulo, valor1, valor2, valor3, valor4, valor5, valor6, estado) VALUES(139, 11, 'BALANCE GENERAL', 'BG', '....', NULL, NULL, NULL, NULL, NULL, NULL, 'AC');
INSERT INTO aplicaciones.modulos (id, id_aplicacion, nombre_modulo, abreviatura_modulo, descripcion_modulo, valor1, valor2, valor3, valor4, valor5, valor6, estado) VALUES(141, 11, 'ESTADO DE CUENTA', 'EC', '...', NULL, NULL, NULL, NULL, NULL, NULL, 'AC');
INSERT INTO aplicaciones.modulos (id, id_aplicacion, nombre_modulo, abreviatura_modulo, descripcion_modulo, valor1, valor2, valor3, valor4, valor5, valor6, estado) VALUES(140, 11, 'ESTADO DE RESULTADOS', 'ER', '...', NULL, NULL, NULL, NULL, NULL, NULL, 'AN');
INSERT INTO aplicaciones.modulos (id, id_aplicacion, nombre_modulo, abreviatura_modulo, descripcion_modulo, valor1, valor2, valor3, valor4, valor5, valor6, estado) VALUES(142, 11, 'BALANCE GENERAL', 'BG', '...', NULL, NULL, NULL, NULL, NULL, NULL, 'AN');

/*OPCIONES*/
INSERT INTO aplicaciones.opciones (id_modulo, codigo_opciones, opcion, link, icono, nivel, orden, estado, id_aplicacion) VALUES(135, 376, 'LIBRO DIARIO', '...', NULL, 1, 0, 'AC', 11);
INSERT INTO aplicaciones.opciones (id_modulo, codigo_opciones, opcion, link, icono, nivel, orden, estado, id_aplicacion) VALUES(132, 367, 'PLAN DE CUENTAS', '...', NULL, 1, 0, 'AC', 11);
INSERT INTO aplicaciones.opciones (id_modulo, codigo_opciones, opcion, link, icono, nivel, orden, estado, id_aplicacion) VALUES(133, 369, 'ENTIDADES', '...', NULL, 1, 0, 'AC', 11);
INSERT INTO aplicaciones.opciones (id_modulo, codigo_opciones, opcion, link, icono, nivel, orden, estado, id_aplicacion) VALUES(134, 373, 'COMPROBANTES', '...', NULL, 1, 0, 'AC', 11);
INSERT INTO aplicaciones.opciones (id_modulo, codigo_opciones, opcion, link, icono, nivel, orden, estado, id_aplicacion) VALUES(136, 378, 'LIBRO MAYOR', '...', NULL, 1, 0, 'AC', 11);
INSERT INTO aplicaciones.opciones (id_modulo, codigo_opciones, opcion, link, icono, nivel, orden, estado, id_aplicacion) VALUES(137, 380, 'SUMAS Y SALDOS', '...', NULL, 1, 0, 'AC', 11);
INSERT INTO aplicaciones.opciones (id_modulo, codigo_opciones, opcion, link, icono, nivel, orden, estado, id_aplicacion) VALUES(138, 382, 'ESTADO DE RESULTADOS', '...', NULL, 1, 0, 'AC', 11);
INSERT INTO aplicaciones.opciones (id_modulo, codigo_opciones, opcion, link, icono, nivel, ordupdate aplicaciones.opciones set icono = 'nav-icon fas fa-book' where id = 367 and id_aplicacion = 11;
update aplicaciones.opciones set icono = 'nav-icon fas fa-book' where id = 368 and id_aplicacion = 11;

update aplicaciones.opciones set icono = 'nav-icon fas fa-building' where id = 369 and id_aplicacion = 11;
update aplicaciones.opciones set icono = 'nav-icon fas fa-building' where id = 370 and id_aplicacion = 11;

update aplicaciones.opciones set icono = 'nav-icon fas fa-tags' where id = 373 and id_aplicacion = 11;
update aplicaciones.opciones set icono = 'nav-icon fas fa-tags' where id = 374 and id_aplicacion = 11;

update aplicaciones.opciones set icono = 'nav-icon fas fa-envelope-open-text' where id = 376 and id_aplicacion = 11;
update aplicaciones.opciones set icono = 'nav-icon fas fa-envelope-open-text' where id = 377 and id_aplicacion = 11;

update aplicaciones.opciones set icono = 'nav-icon fas fa-briefcase' where id = 378 and id_aplicacion = 11;
update aplicaciones.opciones set icono = 'nav-icon fas fa-briefcase' where id = 379 and id_aplicacion = 11;

update aplicaciones.opciones set icono = 'nav-icon fas fa-calculator' where id = 381 and id_aplicacion = 11;
update aplicaciones.opciones set icono = 'nav-icon fas fa-calculator' where id = 380 and id_aplicacion = 11;

update aplicaciones.opciones set icono = 'nav-icon fas fa-chart-line' where id = 382 and id_aplicacion = 11;
update aplicaciones.opciones set icono = 'nav-icon fas fa-chart-line' where id = 383 and id_aplicacion = 11;

update aplicaciones.opciones set icono = 'nav-icon fas fa-balance-scale' where id = 384 and id_aplicacion = 11;
update aplicaciones.opciones set icono = 'nav-icon fas fa-balance-scale' where id = 385 and id_aplicacion = 11;

update aplicaciones.opciones set icono = 'nav-icon fas fa-asterisk' where id = 386 and id_aplicacion = 11;
update aplicaciones.opciones set icono = 'nav-icon fas fa-asterisk' where id = 387 and id_aplicacion = 11;
en, estado, id_aplicacion) VALUES(133, 369, 'ENTIDADES', 'Entidades/Entidades', NULL, 2, 1, 'AC', 11);
INSERT INTO aplicaciones.opciones (id_modulo, codigo_opciones, opcion, link, icono, nivel, orden, estado, id_aplicacion) VALUES(135, 376, 'LIBRO DIARIO', 'Contabilidad/LibroDiario', NULL, 2, 1, 'AC', 11);
INSERT INTO aplicaciones.opciones (id_modulo, codigo_opciones, opcion, link, icono, nivel, orden, estado, id_aplicacion) VALUES(136, 378, 'LIBRO MAYOR', 'Contabilidad/LibroMayor', NULL, 2, 1, 'AC', 11);
INSERT INTO aplicaciones.opciones (id_modulo, codigo_opciones, opcion, link, icono, nivel, orden, estado, id_aplicacion) VALUES(138, 382, 'ESTADO DE RESULTADOS', 'Contabilidad/EstadoDeResultados', NULL, 2, 1, 'AC', 11);
INSERT INTO aplicaciones.opciones (id_modulo, codigo_opciones, opcion, link, icono, nivel, orden, estado, id_aplicacion) VALUES(139, 384, 'BALANCE GENERAL', 'Contabilidad/BalanceGeneral', NULL, 2, 1, 'AC', 11);
INSERT INTO aplicaciones.opciones (id_modulo, codigo_opciones, opcion, link, icono, nivel, orden, estado, id_aplicacion) VALUES(141, 386, 'ESTADO DE CUENTA', 'Contabilidad/EstadoDeCuenta', NULL, 2, 1, 'AC', 11);

/*SIGAP*/
INSERT INTO aplicaciones.modulos (id, id_aplicacion, nombre_modulo, abreviatura_modulo, descripcion_modulo, valor1, valor2, valor3, valor4, valor5, valor6, estado) VALUES(129, 1, 'POAI - EDD', 'POAIEDD', 'PERMITE CONTROL Y SEGUIMIENTO DEL LA EVALUACIÓN Y POAI POR GESTIONES', NULL, NULL, NULL, NULL, NULL, NULL, 'AC');



/*OPCIONES MERCURIO*/
/*ANTIGUO*/
INSERT INTO aplicaciones.opciones (id_modulo, codigo_opciones, opcion, link, icono, nivel, orden, estado, id_aplicacion) VALUES(135, 376, 'LIBRO DIARIO', '...', NULL, 1, 0, 'AC', 11);
INSERT INTO aplicaciones.opciones (id_modulo, codigo_opciones, opcion, link, icono, nivel, orden, estado, id_aplicacion) VALUES(134, 373, 'COMPROBANTES DE TRASPASO', '...', NULL, 2, 2, 'AN', 11);
INSERT INTO aplicaciones.opciones (id_modulo, codigo_opciones, opcion, link, icono, nivel, orden, estado, id_aplicacion) VALUES(132, 367, 'PLAN DE CUENTAS', '...', NULL, 1, 0, 'AC', 11);
INSERT INTO aplicaciones.opciones (id_modulo, codigo_opciones, opcion, link, icono, nivel, orden, estado, id_aplicacion) VALUES(133, 369, 'ENTIDADES', '...', NULL, 1, 0, 'AC', 11);
INSERT INTO aplicaciones.opciones (id_modulo, codigo_opciones, opcion, link, icono, nivel, orden, estado, id_aplicacion) VALUES(134, 373, 'COMPROBANTES', '...', NULL, 1, 0, 'AC', 11);
INSERT INTO aplicaciones.opciones (id_modulo, codigo_opciones, opcion, link, icono, nivel, orden, estado, id_aplicacion) VALUES(132, 367, 'PLAN DE CUENTAS', 'Contabilidad/PlanDeCuentas', NULL, 2, 1, 'AC', 11);
INSERT INTO aplicaciones.opciones (id_modulo, codigo_opciones, opcion, link, icono, nivel, orden, estado, id_aplicacion) VALUES(134, 373, 'COMPROBANTES', 'Contabilidad/Comprobante', NULL, 2, 1, 'AC', 11);
INSERT INTO aplicaciones.opciones (id_modulo, codigo_opciones, opcion, link, icono, nivel, orden, estado, id_aplicacion) VALUES(136, 378, 'LIBRO MAYOR', '...', NULL, 1, 0, 'AC', 11);
INSERT INTO aplicaciones.opciones (id_modulo, codigo_opciones, opcion, link, icono, nivel, orden, estado, id_aplicacion) VALUES(137, 380, 'SUMAS Y SALDOS', 'Contabilidad/SumasYSaldos', NULL, 2, 1, 'AC', 11);
INSERT INTO aplicaciones.opciones (id_modulo, codigo_opciones, opcion, link, icono, nivel, orden, estado, id_aplicacion) VALUES(137, 380, 'SUMAS Y SALDOS', '...', NULL, 1, 0, 'AC', 11);
INSERT INTO aplicaciones.opciones (id_modulo, codigo_opciones, opcion, link, icono, nivel, orden, estado, id_aplicacion) VALUES(142, 388, 'BALANCE GENERAL', '...', NULL, 2, 1, 'AN', 11);
INSERT INTO aplicaciones.opciones (id_modulo, codigo_opciones, opcion, link, icono, nivel, orden, estado, id_aplicacion) VALUES(138, 382, 'ESTADO DE RESULTADOS', '...', NULL, 1, 0, 'AC', 11);
INSERT INTO aplicaciones.opciones (id_modulo, codigo_opciones, opcion, link, icono, nivel, orden, estado, id_aplicacion) VALUES(133, 369, 'ENTIDADES', 'Entidades/Entidades', NULL, 2, 1, 'AC', 11);
INSERT INTO aplicaciones.opciones (id_modulo, codigo_opciones, opcion, link, icono, nivel, orden, estado, id_aplicacion) VALUES(142, 388, 'BALANCE GENERAL', '...', NULL, 1, 0, 'AN', 11);
INSERT INTO aplicaciones.opciones (id_modulo, codigo_opciones, opcion, link, icono, nivel, orden, estado, id_aplicacion) VALUES(139, 384, 'BALANCE GENERAL', '...', NULL, 1, 0, 'AC', 11);
INSERT INTO aplicaciones.opciones (id_modulo, codigo_opciones, opcion, link, icono, nivel, orden, estado, id_aplicacion) VALUES(135, 376, 'LIBRO DIARIO', 'Contabilidad/LibroDiario', NULL, 2, 1, 'AC', 11);
INSERT INTO aplicaciones.opciones (id_modulo, codigo_opciones, opcion, link, icono, nivel, orden, estado, id_aplicacion) VALUES(141, 386, 'ESTADO DE CUENTA', '...', NULL, 1, 0, 'AC', 11);
INSERT INTO aplicaciones.opciones (id_modulo, codigo_opciones, opcion, link, icono, nivel, orden, estado, id_aplicacion) VALUES(136, 378, 'LIBRO MAYOR', 'Contabilidad/LibroMayor', NULL, 2, 1, 'AC', 11);
INSERT INTO aplicaciones.opciones (id_modulo, codigo_opciones, opcion, link, icono, nivel, orden, estado, id_aplicacion) VALUES(138, 382, 'ESTADO DE RESULTADOS', 'Contabilidad/EstadoDeResultados', NULL, 2, 1, 'AC', 11);
INSERT INTO aplicaciones.opciones (id_modulo, codigo_opciones, opcion, link, icono, nivel, orden, estado, id_aplicacion) VALUES(139, 384, 'BALANCE GENERAL', 'Contabilidad/BalanceGeneral', NULL, 2, 1, 'AC', 11);
INSERT INTO aplicaciones.opciones (id_modulo, codigo_opciones, opcion, link, icono, nivel, orden, estado, id_aplicacion) VALUES(141, 386, 'ESTADO DE CUENTA', 'Contabilidad/EstadoDeCuenta', NULL, 2, 1, 'AC', 11);
/*NUEVO*/
INSERT INTO aplicaciones.opciones (id, id_modulo, codigo_opciones, opcion, link, icono, nivel, orden, estado, id_aplicacion) VALUES(357, 132, 357, 'PLAN DE CUENTAS', '...', NULL, 1, 0, 'AC', 11);
INSERT INTO aplicaciones.opciones (id, id_modulo, codigo_opciones, opcion, link, icono, nivel, orden, estado, id_aplicacion) VALUES(358, 132, 357, 'PLAN DE CUENTAS', 'Contabilidad/PlanDeCuentas', NULL, 2, 1, 'AC', 11);
INSERT INTO aplicaciones.opciones (id, id_modulo, codigo_opciones, opcion, link, icono, nivel, orden, estado, id_aplicacion) VALUES(359, 133, 359, 'ENTIDADES', '...', NULL, 1, 0, 'AC', 11);
INSERT INTO aplicaciones.opciones (id, id_modulo, codigo_opciones, opcion, link, icono, nivel, orden, estado, id_aplicacion) VALUES(360, 133, 359, 'ENTIDADES', 'Entidades/Entidades', NULL, 2, 1, 'AC', 11);
INSERT INTO aplicaciones.opciones (id, id_modulo, codigo_opciones, opcion, link, icono, nivel, orden, estado, id_aplicacion) VALUES(361, 134, 361, 'COMPROBANTES', '...', NULL, 1, 0, 'AC', 11);
INSERT INTO aplicaciones.opciones (id, id_modulo, codigo_opciones, opcion, link, icono, nivel, orden, estado, id_aplicacion) VALUES(362, 134, 361, 'COMPROBANTES', 'Contabilidad/Comprobante', NULL, 2, 1, 'AC', 11);
INSERT INTO aplicaciones.opciones (id, id_modulo, codigo_opciones, opcion, link, icono, nivel, orden, estado, id_aplicacion) VALUES(363, 134, 361, 'COMPROBANTES DE TRASPASO', '...', NULL, 2, 2, 'AN', 11);
INSERT INTO aplicaciones.opciones (id, id_modulo, codigo_opciones, opcion, link, icono, nivel, orden, estado, id_aplicacion) VALUES(364, 135, 364, 'LIBRO DIARIO', '...', NULL, 1, 0, 'AC', 11);
INSERT INTO aplicaciones.opciones (id, id_modulo, codigo_opciones, opcion, link, icono, nivel, orden, estado, id_aplicacion) VALUES(365, 135, 364, 'LIBRO DIARIO', 'Contabilidad/LibroDiario', NULL, 2, 1, 'AC', 11);
INSERT INTO aplicaciones.opciones (id, id_modulo, codigo_opciones, opcion, link, icono, nivel, orden, estado, id_aplicacion) VALUES(366, 136, 366, 'LIBRO MAYOR', '...', NULL, 1, 0, 'AC', 11);
INSERT INTO aplicaciones.opciones (id, id_modulo, codigo_opciones, opcion, link, icono, nivel, orden, estado, id_aplicacion) VALUES(367, 136, 366, 'LIBRO MAYOR', 'Contabilidad/LibroMayor', NULL, 2, 1, 'AC', 11);
INSERT INTO aplicaciones.opciones (id, id_modulo, codigo_opciones, opcion, link, icono, nivel, orden, estado, id_aplicacion) VALUES(368, 137, 368, 'SUMAS Y SALDOS', '...', NULL, 1, 0, 'AC', 11);
INSERT INTO aplicaciones.opciones (id, id_modulo, codigo_opciones, opcion, link, icono, nivel, orden, estado, id_aplicacion) VALUES(369, 137, 368, 'SUMAS Y SALDOS', 'Contabilidad/SumasYSaldos', NULL, 2, 1, 'AC', 11);
INSERT INTO aplicaciones.opciones (id, id_modulo, codigo_opciones, opcion, link, icono, nivel, orden, estado, id_aplicacion) VALUES(370, 138, 370, 'ESTADO DE RESULTADOS', '...', NULL, 1, 0, 'AC', 11);
INSERT INTO aplicaciones.opciones (id, id_modulo, codigo_opciones, opcion, link, icono, nivel, orden, estado, id_aplicacion) VALUES(371, 138, 370, 'ESTADO DE RESULTADOS', 'Contabilidad/EstadoDeResultados', NULL, 2, 1, 'AC', 11);
INSERT INTO aplicaciones.opciones (id, id_modulo, codigo_opciones, opcion, link, icono, nivel, orden, estado, id_aplicacion) VALUES(372, 139, 372, 'BALANCE GENERAL', '...', NULL, 1, 0, 'AC', 11);
INSERT INTO aplicaciones.opciones (id, id_modulo, codigo_opciones, opcion, link, icono, nivel, orden, estado, id_aplicacion) VALUES(373, 139, 372, 'BALANCE GENERAL', 'Contabilidad/BalanceGeneral', NULL, 2, 1, 'AC', 11);
INSERT INTO aplicaciones.opciones (id, id_modulo, codigo_opciones, opcion, link, icono, nivel, orden, estado, id_aplicacion) VALUES(374, 141, 374, 'ESTADO DE CUENTA', '...', NULL, 1, 0, 'AC', 11);
INSERT INTO aplicaciones.opciones (id, id_modulo, codigo_opciones, opcion, link, icono, nivel, orden, estado, id_aplicacion) VALUES(375, 141, 374, 'ESTADO DE CUENTA', 'Contabilidad/EstadoDeCuenta', NULL, 2, 1, 'AC', 11);
INSERT INTO aplicaciones.opciones (id, id_modulo, codigo_opciones, opcion, link, icono, nivel, orden, estado, id_aplicacion) VALUES(376, 142, 376, 'BALANCE GENERAL', '...', NULL, 1, 0, 'AN', 11);
INSERT INTO aplicaciones.opciones (id, id_modulo, codigo_opciones, opcion, link, icono, nivel, orden, estado, id_aplicacion) VALUES(377, 142, 376, 'BALANCE GENERAL', '...', NULL, 2, 1, 'AN', 11);



/*ICONOS DEL MENU*/
update aplicaciones.opciones set icono = 'nav-icon fas fa-book' where id = 367 and id_aplicacion = 11;
update aplicaciones.opciones set icono = 'nav-icon fas fa-book' where id = 368 and id_aplicacion = 11;
update aplicaciones.opciones set icono = 'nav-icon fas fa-building' where id = 369 and id_aplicacion = 11;
update aplicaciones.opciones set icono = 'nav-icon fas fa-building' where id = 370 and id_aplicacion = 11;
update aplicaciones.opciones set icono = 'nav-icon fas fa-tags' where id = 373 and id_aplicacion = 11;
update aplicaciones.opciones set icono = 'nav-icon fas fa-tags' where id = 374 and id_aplicacion = 11;
update aplicaciones.opciones set icono = 'nav-icon fas fa-envelope-open-text' where id = 376 and id_aplicacion = 11;
update aplicaciones.opciones set icono = 'nav-icon fas fa-envelope-open-text' where id = 377 and id_aplicacion = 11;
update aplicaciones.opciones set icono = 'nav-icon fas fa-briefcase' where id = 378 and id_aplicacion = 11;
update aplicaciones.opciones set icono = 'nav-icon fas fa-briefcase' where id = 379 and id_aplicacion = 11;
update aplicaciones.opciones set icono = 'nav-icon fas fa-calculator' where id = 381 and id_aplicacion = 11;
update aplicaciones.opciones set icono = 'nav-icon fas fa-calculator' where id = 380 and id_aplicacion = 11;
update aplicaciones.opciones set icono = 'nav-icon fas fa-chart-line' where id = 382 and id_aplicacion = 11;
update aplicaciones.opciones set icono = 'nav-icon fas fa-chart-line' where id = 383 and id_aplicacion = 11;
update aplicaciones.opciones set icono = 'nav-icon fas fa-balance-scale' where id = 384 and id_aplicacion = 11;
update aplicaciones.opciones set icono = 'nav-icon fas fa-balance-scale' where id = 385 and id_aplicacion = 11;
update aplicaciones.opciones set icono = 'nav-icon fas fa-asterisk' where id = 386 and id_aplicacion = 11;
update aplicaciones.opciones set icono = 'nav-icon fas fa-asterisk' where id = 387 and id_aplicacion = 11;
