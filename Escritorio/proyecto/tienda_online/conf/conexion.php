<?php
function conexion(){
    $host = "localhost";
    $user = "administrador";
    $pass = "1234";
    $db   = "tienda_online";

    $conexion = new mysqli($host, $user, $pass, $db);

    if ($conexion->connect_error) {
        echo "Error al conectarse a la base de datos: " . $conexion->connect_error;
    } else {
        return $conexion;
    }
}
?>
