<?php

include __DIR__ . '/../config/config.php';
require __DIR__ . '/../libs/dbhelper.php';

global $DBCONFIG_WEB_ALUMNOS;
global $DBCONFIG;

function getActualTime()
{
    $date = new DateTime("now", new DateTimeZone('America/Argentina/Buenos_Aires'));
    return $date->format('Y-m-d H:i:s');
}

function limpiar_cadena($cadena, $conexion)
{
    $cadena = quitar_tildes($cadena);
    $cadena = str_replace(["'", '"'], '', $cadena);
    return mysqli_real_escape_string($conexion, $cadena);
}

function quitar_tildes($cadena)
{
    $no_permitidas = array("á", "é", "í", "ó", "ú", "Á", "É", "Í", "Ó", "Ú", "ñ", "À", "Ã", "Ì", "Ò", "Ù", "Ã™", "Ã ", "Ã¨", "Ã¬", "Ã²", "Ã¹", "ç", "Ç", "Ã¢", "ê", "Ã®", "Ã´", "Ã»", "Ã‚", "ÃŠ", "ÃŽ", "Ã”", "Ã›", "ü", "Ã¶", "Ã–", "Ã¯", "Ã¤", "«", "Ò", "Ã", "Ã„", "Ã‹");
    $permitidas = array("a", "e", "i", "o", "u", "A", "E", "I", "O", "U", "n", "N", "A", "E", "I", "O", "U", "a", "e", "i", "o", "u", "c", "C", "a", "e", "i", "o", "u", "A", "E", "I", "O", "U", "u", "o", "O", "i", "a", "e", "U", "I", "A", "E");
    $texto = str_replace($no_permitidas, $permitidas, $cadena);
    return utf8_decode($texto);
}

// $this->db->connect('pdo', 'mysql', $DBCONFIG['HOST'], $DBCONFIG['USERNAME'], $DBCONFIG['PASSWORD'],$DBCONFIG['DATABASE'],$DBCONFIG['PORT']);

$db_host = $DBCONFIG_WEB_ALUMNOS['HOST'];
$db_user = "root";
$db_password = $DBCONFIG_WEB_ALUMNOS['PASSWORD'];
$db_name = $DBCONFIG_WEB_ALUMNOS['DATABASE'];
$db_table_name = "students";

$db_connection = mysqli_connect($db_host, $db_user, $db_password);
mysqli_select_db($db_connection, $db_name);


if (!$db_connection) {
    die('No se ha podido conectar a la base de datos');
}

//tabla 2
$db_host2 = $DBCONFIG['HOST'];
$db_user2 = "root";
$db_password2 = $DBCONFIG['PASSWORD'];
$db_name2 = $DBCONFIG['DATABASE'];
$db_table_name2 = "students";

$db_table_name_complementary = "complementary_information";

$db_connection2 = mysqli_connect($db_host2, $db_user2, $db_password2);
mysqli_select_db($db_connection2, $db_name2);


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
$subs_nacimiento = $subs_anio . "-" . $subs_mes . "-" . $subs_dia;
$subs_edad = intval(utf8_decode($_POST['edad']), 10);
$subs_dni = utf8_decode($_POST['dni']);

$subs_direccion = "";
$subs_localidad = "";
$subs_nombre_mama = limpiar_cadena($_POST['nombre_mama'], $db_connection);
$observation = "";

$subs_tel_mama = utf8_decode($_POST['tel_mama']);
$subs_email_mama = "";
$subs_instagram_mama = limpiar_cadena($_POST['instagram_mama'], $db_connection);

$subs_nombre_papa = limpiar_cadena($_POST['nombre_papa'], $db_connection);
$subs_tel_papa = limpiar_cadena($_POST['tel_papa'], $db_connection);
$subs_email_papa = "";
$subs_instagram_papa = limpiar_cadena($_POST['instagram_papa'], $db_connection);

$subs_tel_adulto = "";
$subs_email_adulto = "";
$subs_instagram_adulto = "";
$subs_facebook_adulto = "";

// Autorizados
$subs_autorizado1_nombre = limpiar_cadena($_POST['autorizado1_nombre'], $db_connection);
$subs_autorizado1_dni = limpiar_cadena($_POST['autorizado1_dni'], $db_connection);
$subs_autorizado1_parentesco = limpiar_cadena($_POST['autorizado1_parentesco'], $db_connection);

$subs_autorizado2_nombre = limpiar_cadena($_POST['autorizado2_nombre'], $db_connection);
$subs_autorizado2_dni = limpiar_cadena($_POST['autorizado2_dni'], $db_connection);
$subs_autorizado2_parentesco = limpiar_cadena($_POST['autorizado2_parentesco'], $db_connection);

$subs_autorizado3_nombre = limpiar_cadena($_POST['autorizado3_nombre'], $db_connection);
$subs_autorizado3_dni = limpiar_cadena($_POST['autorizado3_dni'], $db_connection);
$subs_autorizado3_parentesco = limpiar_cadena($_POST['autorizado3_parentesco'], $db_connection);

// Salud
$subs_salud = limpiar_cadena($_POST['salud'], $db_connection);

// deportes
$subs_deportes = limpiar_cadena($_POST['deportes'], $db_connection);

$subs_sabe_nadar = limpiar_cadena($_POST['sabe_nadar'], $db_connection);

if (empty($subs_dni)) {
    $subs_dni = "TEMP" . time();
}

$resultado = mysqli_query($db_connection, "SELECT * FROM " . $db_table_name . " WHERE dni = '" . $subs_dni . "'");

//var_dump($resultado);

global $form;

if (mysqli_num_rows($resultado) > 0) {

    $row = mysqli_fetch_assoc($resultado);

    $q = "UPDATE ".$db_table_name." SET edad = '".$subs_edad."'
       , fecha_nacimiento = '".$subs_nacimiento."'
       , nombre_mama = '".$subs_nombre_mama."'
       , tel_mama = '".$subs_tel_mama."'
       , instagram_mama = '".$subs_instagram_mama."'
       , nombre_papa = '".$subs_nombre_papa."'
       , tel_papa = '".$subs_tel_papa."'
       , instagram_papa = '".$subs_instagram_papa."'
       , tel_adulto = '".$subs_tel_adulto."'
       , instagram_adulto = '".$subs_instagram_adulto."'
       , updated_date = '".getActualTime()."'
       WHERE dni = '".$subs_dni."'";
      $retry_value = mysqli_query( $db_connection,$q);

      if (!$retry_value) {
          die('Error: ' . mysqli_error($db_connection));
      }

    //Autorizados y salud
    $insert_value_info_complementaria = 'INSERT INTO `' . $db_name . '`.`' . $db_table_name_complementary . '` (
        `student_id`,
        `autorizado1_nombre`, `autorizado1_dni`, `autorizado1_parentesco`,
        `autorizado2_nombre`, `autorizado2_dni`, `autorizado2_parentesco`,
        `autorizado3_nombre`, `autorizado3_dni`, `autorizado3_parentesco`,
        `salud`, `sabe_nadar`, `deportes`
    ) VALUES (
        "' .  $row['id'] . '",
        "' . $subs_autorizado1_nombre . '", "' . $subs_autorizado1_dni . '", "' . $subs_autorizado1_parentesco . '",
        "' . $subs_autorizado2_nombre . '", "' . $subs_autorizado2_dni . '", "' . $subs_autorizado2_parentesco . '",
        "' . $subs_autorizado3_nombre . '", "' . $subs_autorizado3_dni . '", "' . $subs_autorizado3_parentesco . '",
        "' . $subs_salud . '", "' . $subs_sabe_nadar . '", "' . $subs_deportes . '")';


    $retry_value_colonia = mysqli_query($db_connection, $insert_value_info_complementaria);

    if (!$retry_value_colonia) {
        die('Error: ' . mysqli_error($db_connection));
    }

    $form = array('name' => $subs_name, 'apellido' => $subs_last, 'dni' => $subs_dni, 'info' => "actualizada");

    include "success_colonia.php";

    // header('Location: Fail.html');

} else {

    $insert_value = 'INSERT INTO `' . $db_name . '`.`' . $db_table_name . '` (`nombre` , `apellido` ,`dni` , `edad` ,`fecha_nacimiento`, `direccion`,`localidad`,
     `nombre_mama`, `tel_mama`,`email_mama`,`instagram_mama`, `nombre_papa`, `observation` ,`tel_papa`,`email_papa`,`instagram_papa`,`tel_adulto` , `email_adulto` , `instagram_adulto` , `facebook_adulto`)
      VALUES ("' . $subs_name . '", "' . $subs_last . '",
     "' . $subs_dni . '", "' . $subs_edad . '",  "' . $subs_nacimiento . '","' . $subs_direccion . '","' . $subs_localidad . '","' . $subs_nombre_mama . '","' . $subs_tel_mama . '","' . $subs_email_mama . '","' . $subs_instagram_mama . '",
     "' . $subs_nombre_papa . '","' . $observation . '","' . $subs_tel_papa . '","' . $subs_email_papa . '","' . $subs_instagram_papa . '","' . $subs_tel_adulto . '","' . $subs_email_adulto . '","' . $subs_instagram_adulto . '","' . $subs_facebook_adulto . '")';

    $retry_value = mysqli_query($db_connection, $insert_value);

    if ($retry_value) {
        $last_id = mysqli_insert_id($db_connection);
        $student_id = $last_id;
       // echo "El ID insertado es: " . $last_id;
    } else {
        $student_id = -1;
        $last_id = -1;
        echo "Error al insertar: " . mysqli_error($db_connection);
    }

    $student_id = $last_id;

    //Autorizados y salud
    $insert_value_info_complementaria = 'INSERT INTO `' . $db_name . '`.`' . $db_table_name_complementary . '` (
        `student_id`,
        `autorizado1_nombre`, `autorizado1_dni`, `autorizado1_parentesco`,
        `autorizado2_nombre`, `autorizado2_dni`, `autorizado2_parentesco`,
        `autorizado3_nombre`, `autorizado3_dni`, `autorizado3_parentesco`,
               `salud`, `sabe_nadar`, `deportes`
    ) VALUES (
        "' . $student_id . '",
        "' . $subs_autorizado1_nombre . '", "' . $subs_autorizado1_dni . '", "' . $subs_autorizado1_parentesco . '",
        "' . $subs_autorizado2_nombre . '", "' . $subs_autorizado2_dni . '", "' . $subs_autorizado2_parentesco . '",
        "' . $subs_autorizado3_nombre . '", "' . $subs_autorizado3_dni . '", "' . $subs_autorizado3_parentesco . '",
           "' . $subs_salud . '", "' . $subs_sabe_nadar . '", "' . $subs_deportes . '")';


    $retry_value_colonia = mysqli_query($db_connection, $insert_value_info_complementaria);

    if (!$retry_value_colonia) {
        die('Error: ' . mysqli_error($db_connection));
    }

    $created = date("Y-m-d H:i:s"); // fecha actual
    $form = array(
        'name' => $subs_name,
        'apellido' => $subs_last,
        'dni' => $subs_dni,
        'info' => "creada",
        'created' => $created
    );

    $insert_value2 = 'INSERT INTO `' . $db_name2 . '`.`' . $db_table_name2 . '` (`nombre` , `apellido` ,`dni` , `edad` ,`fecha_nacimiento`, `direccion`,`localidad`,
     `nombre_mama`, `tel_mama`,`email_mama`,`instagram_mama`, `nombre_papa`, `observation` ,`tel_papa`,`email_papa`,`instagram_papa`,`tel_adulto` , `email_adulto` , `instagram_adulto` , `facebook_adulto`)
      VALUES ("' . $subs_name . '", "' . $subs_last . '",
     "' . $subs_dni . '", "' . $subs_edad . '",  "' . $subs_nacimiento . '","' . $subs_direccion . '","' . $subs_localidad . '","' . $subs_nombre_mama . '","' . $subs_tel_mama . '","' . $subs_email_mama . '","' . $subs_instagram_mama . '",
     "' . $subs_nombre_papa . '","' . $observation . '","' . $subs_tel_papa . '","' . $subs_email_papa . '","' . $subs_instagram_papa . '","' . $subs_tel_adulto . '","' . $subs_email_adulto . '","' . $subs_instagram_adulto . '","' . $subs_facebook_adulto . '")';

    $retry_value2 = mysqli_query($db_connection2, $insert_value2);

    include "success_colonia.php";
}

mysqli_close($db_connection);
mysqli_close($db_connection2);


