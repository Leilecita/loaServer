<?php

include __DIR__ . '/../config/config.php';
require __DIR__ . '/../libs/dbhelper.php';

$db_host="localhost";
$db_user="root";
$db_password=null;
$db_name='loa';
$db_table_name="students";

$db_connection = mysqli_connect($db_host, $db_user, $db_password);
mysqli_select_db($db_connection,$db_name );


if (!$db_connection) {
    die('No se ha podido conectar a la base de datos');
}
$subs_name = utf8_decode($_POST['nombre']);
$subs_last = utf8_decode($_POST['apellido']);
//$subs_email = utf8_decode($_POST['email']);
$subs_nacimiento = utf8_decode($_POST['fecha_nacimiento']);
$subs_edad = utf8_decode($_POST['edad']);
$subs_dni = utf8_decode($_POST['dni']);


$resultado=mysqli_query($db_connection,"SELECT * FROM ".$db_table_name." WHERE dni = '".$subs_dni."'" );

var_dump($resultado);

if (mysqli_num_rows($resultado)>0)
{
    header('Location: Fail.html');

} else {

    $insert_value = 'INSERT INTO `' . $db_name . '`.`'.$db_table_name.'` (`nombre` , `apellido` ,`dni` , `edad` ,`fecha_nacimiento`) VALUES ("' . $subs_name . '", "' . $subs_last  . '",
     "' . $subs_dni . '", "' . $subs_edad . '",  "' . $subs_nacimiento . '")';

    $retry_value = mysqli_query( $db_connection,$insert_value);

    if (!$retry_value) {
        die('Error: ' . mysqli_error());
    }

    header('Location: Success.html');
}

mysqli_close($db_connection);

