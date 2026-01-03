<?php
    //Hacemos sesion start
    session_start();

    //Conectamos con la base de datos
    require_once __DIR__ . "/../conf/conexion.php";
    //Creamos la variable de conexion
    $conexion = conexion();

    //Comprobamos que tanto el id del zapato y de usuario los hemos recibido o sino volvemos a tienda.php
    if (!isset($_GET["zapato_id"]) && !isset($_GET["usuario_id"])) {        
        header("Location: tienda.php");
        exit;
    }
    //Creamos las variables que contienen el id del zapato y el id del usuario
    $zapato_id = (int) $_GET["zapato_id"];
    $usuario_id = (int) $_SESSION["usuario_id"];

    // Eliminar el producto del pedido pendiente del usuario
    $sql = "DELETE pedido_detalle FROM pedido_detalle
            INNER JOIN pedidos ON pedido_detalle.pedido_id = pedidos.id
            WHERE pedido_detalle.zapato_id = ? AND pedidos.usuario_id = ? AND pedidos.estado = 'pendiente'";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("ii", $zapato_id, $usuario_id);
    $stmt->execute();

    // Redirigir de nuevo al carrito,asi no hace falta recargar la pagina y el usuario ve el resultado de borrar
    header("Location: ver_carrito.php");
    exit();
?>
