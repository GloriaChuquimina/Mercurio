<?php
/*include "../../logs/logger.php";
include "configuracion.php";
include "DB.php";
include "Response.php";
include "autentification.php";*/

function verificarConexionInternet() {
    $headers = @get_headers("http://www.google.com");
    if ($headers && strpos($headers[0], '200 OK') !== false) {
        return true; // La conexión se pudo establecer
    } else {
        return false; // No se pudo establecer la conexión
    }
}

function xmlEscape($string) {
    return str_replace(array('&', '<', '>', '\'', '"'), array('&amp;', '&lt;', '&gt;', '&apos;', '&quot;'), $string);
}

function siat_enlace_qr() 
{
    $enlace = "https://pilotosiat.impuestos.gob.bo/consulta/QR?nit=";
    return $enlace;
}