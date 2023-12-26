<?php

include __DIR__ . '/../config/config.php';
require __DIR__ . '/../libs/dbhelper.php';


global $DBCONFIG_WEB_ALUMNOS;

function getActualTime(){
    $date = new DateTime("now", new DateTimeZone('America/Argentina/Buenos_Aires') );
    return $date->format('Y-m-d H:i:s');
}

function quitar_tildes($cadena) {
    $no_permitidas= array ("á","é","í","ó","ú","Á","É","Í","Ó","Ú","ñ","À","Ã","Ì","Ò","Ù","Ã™","Ã ","Ã¨","Ã¬","Ã²","Ã¹","ç","Ç","Ã¢","ê","Ã®","Ã´","Ã»","Ã‚","ÃŠ","ÃŽ","Ã”","Ã›","ü","Ã¶","Ã–","Ã¯","Ã¤","«","Ò","Ã","Ã„","Ã‹");
    $permitidas= array ("a","e","i","o","u","A","E","I","O","U","n","N","A","E","I","O","U","a","e","i","o","u","c","C","a","e","i","o","u","A","E","I","O","U","u","o","O","i","a","e","U","I","A","E");
    $texto = str_replace($no_permitidas, $permitidas ,$cadena);
    return utf8_decode($texto);
}

// $this->db->connect('pdo', 'mysql', $DBCONFIG['HOST'], $DBCONFIG['USERNAME'], $DBCONFIG['PASSWORD'],$DBCONFIG['DATABASE'],$DBCONFIG['PORT']);

$db_host= $DBCONFIG_WEB_ALUMNOS['HOST'];
$db_user="root";
$db_password = $DBCONFIG_WEB_ALUMNOS['PASSWORD'];
$db_name = $DBCONFIG_WEB_ALUMNOS['DATABASE'];
$db_table_name="students";

$db_connection = mysqli_connect($db_host, $db_user, $db_password);
mysqli_select_db($db_connection,$db_name );


if (!$db_connection) {
    die('No se ha podido conectar a la base de datos');
}


$subs_name = quitar_tildes($_POST['nombre']);

$subs_last = quitar_tildes($_POST['apellido']);
//$subs_nacimiento = utf8_decode($_POST['fecha_nacimiento']);
$subs_dia = utf8_decode($_POST['dia']);
$subs_mes = utf8_decode($_POST['mes']);
$subs_anio = utf8_decode($_POST['anio']);
$subs_nacimiento = $subs_anio."-".$subs_mes."-".$subs_dia;
$subs_edad = intval(utf8_decode($_POST['edad']),10);
$subs_dni = utf8_decode($_POST['dni']);

$subs_direccion = "";
$subs_localidad = "";
$subs_nombre_mama = quitar_tildes($_POST['nombre_mama']);

$subs_tel_mama = utf8_decode($_POST['tel_mama']);
$subs_email_mama = "";
$subs_instagram_mama = quitar_tildes($_POST['instagram_mama']);

$subs_nombre_papa = quitar_tildes($_POST['nombre_papa']);
$subs_tel_papa = quitar_tildes($_POST['tel_papa']);
$subs_email_papa = "";
$subs_instagram_papa = quitar_tildes($_POST['instagram_papa']);

$subs_tel_adulto = utf8_decode($_POST['tel_adulto']);
$subs_email_adulto = "";
$subs_instagram_adulto = quitar_tildes($_POST['instagram_adulto']);
$subs_facebook_adulto = "";


$resultado = mysqli_query($db_connection,"SELECT * FROM ".$db_table_name." WHERE dni = '".$subs_dni."'" );

//var_dump($resultado);

global  $form;

if (mysqli_num_rows($resultado)>0)
{

   /* $q = "UPDATE ".$db_table_name." SET edad = '".$subs_edad."'
     , fecha_nacimiento = '".$subs_nacimiento."'
     , nombre_mama = '".$subs_nombre_mama."'
     , tel_mama = '".$subs_tel_mama."'
     , email_mama = '".$subs_email_mama."'
     , instagram_mama = '".$subs_instagram_mama."'
     , nombre_papa = '".$subs_nombre_papa."'
     , tel_papa = '".$subs_tel_papa."'
     , email_papa = '".$subs_email_papa."'
     , instagram_papa = '".$subs_instagram_papa."'
     , tel_adulto = '".$subs_tel_adulto."'
     , instagram_adulto = '".$subs_instagram_adulto."'
     , updated_date = '".getActualTime()."'
     WHERE dni = '".$subs_dni."'";
    $retry_value = mysqli_query( $db_connection,$q);

    if (!$retry_value) {
        die('Error: ' . mysqli_error($db_connection));
    }*/

    $form = array('name' => $subs_name, 'apellido' => $subs_last, 'dni' => $subs_dni , 'info' => "actualizada");

    include "success.php";

   // header('Location: Fail.html');

} else {

    $insert_value = 'INSERT INTO `' . $db_name . '`.`'.$db_table_name.'` (`nombre` , `apellido` ,`dni` , `edad` ,`fecha_nacimiento`, `direccion`,`localidad`,
     `nombre_mama`, `tel_mama`,`email_mama`,`instagram_mama`, `nombre_papa`, `tel_papa`,`email_papa`,`instagram_papa`,`tel_adulto` , `email_adulto` , `instagram_adulto` , `facebook_adulto`)
      VALUES ("' . $subs_name . '", "' . $subs_last  . '",
     "' . $subs_dni . '", "' . $subs_edad . '",  "' . $subs_nacimiento . '","' . $subs_direccion . '","' . $subs_localidad . '","' . $subs_nombre_mama . '","' . $subs_tel_mama . '","' . $subs_email_mama . '","' . $subs_instagram_mama . '",
     "' . $subs_nombre_papa . '","' . $subs_tel_papa . '","' . $subs_email_papa . '","' . $subs_instagram_papa . '","' .$subs_tel_adulto.'","'.$subs_email_adulto.'","'.$subs_instagram_adulto.'","'.$subs_facebook_adulto.'")';
    // "' . $subs_nombre_papa . '","' . $subs_tel_papa . '","' . $subs_email_papa . '","' . $subs_instagram_papa . '")';

    $retry_value = mysqli_query( $db_connection,$insert_value);

    if (!$retry_value) {
        die('Error: ' . mysqli_error($db_connection));
    }

    $form = array('name' => $subs_name, 'apellido' => $subs_last, 'dni' => $subs_dni ,'info' => "creada", 'created' => $retry_value['created']);

    include "success.php";

    //generatePdf(render($subs_name),"leila.pdf");

}


mysqli_close($db_connection);

