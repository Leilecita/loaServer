<?php

include __DIR__ . '/../config/config.php';
require __DIR__ . '/../libs/dbhelper.php';

//recaptcha
if (!isset($_POST['g-recaptcha-response']) || empty($_POST['g-recaptcha-response'])) {
    die("Debes completar el captcha");
}
$secret = "6LfhNYYsAAAAAPsmxSdjvh-fAo4gg2pioVh-f_GS";
$recaptcha = $_POST['g-recaptcha-response'];

$response = file_get_contents(
    "https://www.google.com/recaptcha/api/siteverify?secret=".$secret."&response=".$recaptcha
);

$responseKeys = json_decode($response, true);

if(!$responseKeys["success"]) {
    die("Captcha inválido");
}


//

global $DBCONFIG_WEB_ALUMNOS;
global $DBCONFIG;

function getActualTime(){
    $date = new DateTime("now", new DateTimeZone('America/Argentina/Buenos_Aires') );
    return $date->format('Y-m-d H:i:s');
}

function limpiar_cadena($cadena, $conexion) {
    $cadena = quitar_tildes($cadena);
    $cadena = str_replace(["'", '"'], '', $cadena);
    return mysqli_real_escape_string($conexion, $cadena);
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


//tabla 2
$db_host2= $DBCONFIG['HOST'];
$db_user2="root";
$db_password2 = $DBCONFIG['PASSWORD'];
$db_name2 = $DBCONFIG['DATABASE'];
$db_table_name2="students";

$db_connection2 = mysqli_connect($db_host2, $db_user2, $db_password2);
mysqli_select_db($db_connection2,$db_name2 );


if (!$db_connection2) {
    die('No se ha podido conectar a la base de datos');
}

//

$subs_name = limpiar_cadena($_POST['nombre'], $db_connection);

$subs_last = limpiar_cadena($_POST['apellido'], $db_connection);
//$subs_nacimiento = utf8_decode($_POST['fecha_nacimiento']);
$subs_dia = utf8_decode($_POST['dia']);
$subs_mes = utf8_decode($_POST['mes']);
$subs_anio = utf8_decode($_POST['anio']);
$subs_nacimiento = $subs_anio."-".$subs_mes."-".$subs_dia;
$subs_edad = intval(utf8_decode($_POST['edad']),10);
$subs_dni = utf8_decode($_POST['dni']);

$subs_direccion = "";
$subs_localidad = "";
$subs_nombre_mama = limpiar_cadena($_POST['nombre_mama'], $db_connection);
$observation = limpiar_cadena($_POST['observation'], $db_connection);

$subs_tel_mama = utf8_decode($_POST['tel_mama']);
$subs_email_mama = "";
$subs_instagram_mama = limpiar_cadena($_POST['instagram_mama'], $db_connection);

$subs_nombre_papa = limpiar_cadena($_POST['nombre_papa'], $db_connection);
$subs_tel_papa = limpiar_cadena($_POST['tel_papa'], $db_connection);
$subs_email_papa = "";
$subs_instagram_papa = limpiar_cadena($_POST['instagram_papa'], $db_connection);

$subs_tel_adulto = utf8_decode($_POST['tel_adulto']);
$subs_email_adulto = "";
$subs_instagram_adulto = limpiar_cadena($_POST['instagram_adulto'], $db_connection);
$subs_facebook_adulto = "";

if (empty($subs_dni)) {
    $subs_dni = "TEMP" . time();
}

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

    /*if (empty($subs_dni)) {
        $subs_dni = "TEMP" . time(); // ej: TEMP1695470123
    }*/

    $insert_value = 'INSERT INTO `' . $db_name . '`.`'.$db_table_name.'` (`nombre` , `apellido` ,`dni` , `edad` ,`fecha_nacimiento`, `direccion`,`localidad`,
     `nombre_mama`, `tel_mama`,`email_mama`,`instagram_mama`, `nombre_papa`, `observation` ,`tel_papa`,`email_papa`,`instagram_papa`,`tel_adulto` , `email_adulto` , `instagram_adulto` , `facebook_adulto`)
      VALUES ("' . $subs_name . '", "' . $subs_last  . '",
     "' . $subs_dni . '", "' . $subs_edad . '",  "' . $subs_nacimiento . '","' . $subs_direccion . '","' . $subs_localidad . '","' . $subs_nombre_mama . '","' . $subs_tel_mama . '","' . $subs_email_mama . '","' . $subs_instagram_mama . '",
     "' . $subs_nombre_papa  . '","' . $observation . '","' . $subs_tel_papa . '","' . $subs_email_papa . '","' . $subs_instagram_papa . '","' .$subs_tel_adulto.'","'.$subs_email_adulto.'","'.$subs_instagram_adulto.'","'.$subs_facebook_adulto.'")';

    $retry_value = mysqli_query( $db_connection,$insert_value);

    if (!$retry_value) {
        die('Error: ' . mysqli_error($db_connection));
    }

    $form = array('name' => $subs_name, 'apellido' => $subs_last, 'dni' => $subs_dni ,'info' => "creada", 'created' => $retry_value['created']);


    $insert_value2 = 'INSERT INTO `' . $db_name2 . '`.`'.$db_table_name2.'` (`nombre` , `apellido` ,`dni` , `edad` ,`fecha_nacimiento`, `direccion`,`localidad`,
     `nombre_mama`, `tel_mama`,`email_mama`,`instagram_mama`, `nombre_papa`, `observation` ,`tel_papa`,`email_papa`,`instagram_papa`,`tel_adulto` , `email_adulto` , `instagram_adulto` , `facebook_adulto`)
      VALUES ("' . $subs_name . '", "' . $subs_last  . '",
     "' . $subs_dni . '", "' . $subs_edad . '",  "' . $subs_nacimiento . '","' . $subs_direccion . '","' . $subs_localidad . '","' . $subs_nombre_mama . '","' . $subs_tel_mama . '","' . $subs_email_mama . '","' . $subs_instagram_mama . '",
     "' . $subs_nombre_papa  . '","' . $observation . '","' . $subs_tel_papa . '","' . $subs_email_papa . '","' . $subs_instagram_papa . '","' .$subs_tel_adulto.'","'.$subs_email_adulto.'","'.$subs_instagram_adulto.'","'.$subs_facebook_adulto.'")';

    $retry_value2 = mysqli_query( $db_connection2,$insert_value2);

    include "success.php";

    //generatePdf(render($subs_name),"leila.pdf");

}


mysqli_close($db_connection);
mysqli_close($db_connection2);

