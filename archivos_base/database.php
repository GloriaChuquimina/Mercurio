<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$active_group = 'db_entorno';
$query_builder = TRUE;

$db['db_entorno'] = array(
	'dsn'	=> '',
	'hostname' => '192.168.15.90',
	'password' => 'BDpruebas17..',
	/*'hostname' => 'localhost',
	'password' => '1982',*/
	'username' => 'postgres',	
	'database' => 'db_entorno_desarrollo',
	'dbdriver' => 'postgre',
	'dbprefix' => '',
	'pconnect' => FALSE,
	'db_debug' => (ENVIRONMENT !== 'production'),
	'cache_on' => FALSE,
	'cachedir' => '',
	'char_set' => 'utf8',
	'dbcollat' => 'utf8_general_ci',
	'swap_pre' => '',
	'encrypt' => FALSE,
	'compress' => FALSE,
	'stricton' => FALSE,
	'failover' => array(), 
	'save_queries' => TRUE
);

//RECURSOS HUMANOS
$db['db_recursos_humanos'] = array(
	'dsn'	=> '',
	'hostname' => '192.168.15.90',
	'password' => 'BDpruebas17..',
	/*'hostname' => 'localhost',
	'password' => '1982',*/
	'username' => 'postgres',
	'database' => 'db_sigap_oficial',
	'dbdriver' => 'postgre',
	'dbprefix' => '',
	'pconnect' => FALSE,
	'db_debug' => (ENVIRONMENT !== 'production'),
	'cache_on' => FALSE,
	'cachedir' => '',
	'char_set' => 'utf8',
	'dbcollat' => 'utf8_general_ci',
	'swap_pre' => '',
	'encrypt' => FALSE,
	'compress' => FALSE,
	'stricton' => FALSE,
	'failover' => array(), 
	'save_queries' => TRUE
);


//MERCURIO
$db['db_mercurio'] = array(
	'dsn'	=> '',
	'hostname' => '192.168.15.90',
	'password' => 'BDpruebas17..',
	/*'hostname' => 'localhost',
	'password' => '1982',*/
	'username' => 'postgres',
	'database' => 'db_mercurio_desarrollo',
	'dbdriver' => 'postgre',
	'dbprefix' => '',
	'pconnect' => FALSE,
	'db_debug' => (ENVIRONMENT !== 'production'),
	'cache_on' => FALSE,
	'cachedir' => '',
	'char_set' => 'utf8',
	'dbcollat' => 'utf8_general_ci',
	'swap_pre' => '',
	'encrypt' => FALSE,
	'compress' => FALSE,
	'stricton' => FALSE,
	'failover' => array(), 
	'save_queries' => TRUE
);