<?php
ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);
    //Hacemos sesion start
    session_start();
    //Llamamos a la base de datos
    require_once "../conf/conexion.php";
    //Creamos la variable de conexion
    $conexion = conexion();

    //Como paso importante y para evitar errores,verificaamos si realmente se recibio el id del producto
    if(!isset($_GET["id"])){
        //Como no se ha recibido entonces volvemos a la pagina iniciar
        header("Location: tienda.php");
        exit;
    }
    //Recibimos primero el id del producto y lo pasamos a numero correcto
    $id_del_prodcuto = (int) $_GET["id"];
    
    $usuario_id = $_SESSION["usuario_id"];//Necesitamos tambien el id del usuario con sesion
    
    //Si no hay usuario el proceso no puede continuar y redirigmios a la tienda otra vez
    if(!$usuario_id){
        header("Location: tienda.php");
        exit;
    }

    //Verficamos si el usuario tiene un pedido pendiente
    $sqlPedido = "SELECT id FROM pedidos WHERE usuario_id = ? AND estado = 'pendiente' LIMIT 1;";
    $stmtPedido = $conexion->prepare($sqlPedido); //Preparamos la consulta
    $stmtPedido->bind_param("i",$usuario_id); //Asignamos el parametro
    $stmtPedido->execute(); //Ejecutamos la consulta
    $resultado = $stmtPedido->get_result(); //Obtenemos el resultado
    $pedido = $resultado->fetch_assoc(); //Guardamos el pedido encontrado (si existe)

    //Aqui comprobamos si hay pedido sino lo hay creamos uno
    if(!$pedido){
        //Creamos un pedido insertando los valores necesarios
        $sql_insertar = "INSERT INTO pedidos (usuario_id,total) VALUES(?,0);";//Por defecto la columna total es valor 0,sino dara error
        $stmtInsertar = $conexion->prepare($sql_insertar); //Preparamos la consulta
        $stmtInsertar->bind_param("i",$usuario_id);//Insertamos como parametro el id del usuario para que pertenezca al pedido
        $stmtInsertar->execute(); //Ejecutamos la insercion

        //Obtenemos el id del pedido recien creado para poder agregar los productos despues
        $pedido_id = $stmtInsertar->insert_id;
    } else {
        //Si ya existia el pedido, usamos su id
        $pedido_id = $pedido['id'];
    }

    //Aqui hacemos la consulta para obtener la informacion del producto seleccionado
    $sqlProducto = "SELECT id, precio FROM zapatos WHERE id = ? AND activo = 1";
    $stmtProducto = $conexion->prepare($sqlProducto);
    $stmtProducto->bind_param("i", $id_del_prodcuto);
    $stmtProducto->execute();
    $resultadoProducto = $stmtProducto->get_result();
    $producto = $resultadoProducto->fetch_assoc();

    //Si el producto existe, procedemos a agregarlo al pedido_detalle
    if($producto){
        //Primero comprobamos si el producto ya esta en el pedido_detalle
        $sqlDetalle = "SELECT id, cantidad FROM pedido_detalle WHERE pedido_id = ? AND zapato_id = ?";
        $stmtDetalle = $conexion->prepare($sqlDetalle);
        $stmtDetalle->bind_param("ii", $pedido_id, $id_del_prodcuto);
        $stmtDetalle->execute();
        $resultadoDetalle = $stmtDetalle->get_result();
        $detalle = $resultadoDetalle->fetch_assoc();

        if($detalle){
            //Si ya existe, aumentamos la cantidad en 1
            $nuevaCantidad = $detalle['cantidad'] + 1;
            $sqlActualizar = "UPDATE pedido_detalle SET cantidad = ? WHERE id = ?";
            $stmtActualizar = $conexion->prepare($sqlActualizar);
            $stmtActualizar->bind_param("ii", $nuevaCantidad, $detalle['id']);
            $stmtActualizar->execute();
        } else {
            //Si no existe, lo insertamos por primera vez
            $sqlInsertDetalle = "INSERT INTO pedido_detalle (pedido_id, zapato_id, cantidad, precio_unitario) VALUES (?, ?, 1, ?)";
            $stmtInsertDetalle = $conexion->prepare($sqlInsertDetalle);
            $stmtInsertDetalle->bind_param("iid", $pedido_id, $id_del_prodcuto, $producto['precio']);
            $stmtInsertDetalle->execute();
        }
    }

    //Mandamos al usuario a ver todos los productos en un archivo ver_carrito donde estaran los productos ordenados y mostrados al usuario
    header("Location: ver_carrito.php");
    exit;
?>
