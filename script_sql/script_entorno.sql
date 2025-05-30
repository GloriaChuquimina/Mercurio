	
insert into aplicaciones.aplicaciones(id_entidad,nombre_aplicacion,abreviatura,descripcion_aplicacion,user_administrador)
values (1,'SISTEMA CONTABLE MERCURIO', 'MERCURIO','SISTEMA CONTABLE DEL SENAPE','SI');





insert into aplicaciones.modulos(id_aplicacion,nombre_modulo,abreviatura_modulo,descripcion_modulo)
values (11,'INICIO','INI','DESPLIEGA INFORMACION DE INICIO');

insert into aplicaciones.modulos(id_aplicacion,nombre_modulo,abreviatura_modulo,descripcion_modulo)
values (11,'PARÁMETROS','PAR','MÓDULOS PARA EL REGISTRO DE DATOS PARAMETRICOS');




insert into aplicaciones.opciones(id_modulo,codigo_opciones,opcion,link,icono,nivel,orden,id_aplicacion)values (130,365,'INICIO','','',0,1,11);
insert into aplicaciones.opciones(id_modulo,codigo_opciones,opcion,link,icono,nivel,orden,id_aplicacion)values (131,366,'PARÁMETROS','','',1,2,11);