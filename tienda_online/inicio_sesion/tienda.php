<?php

    //Hacemos sesion start para pasar los valores del usuario 
    session_start();

    //Conectamos con el archivo de conexion con la base de datos
    require_once __DIR__ . "/../conf/conexion.php";
    $conexion = conexion();

    //Hacemos una consulta a todos los productos zapatos
    $sql = "SELECT id, nombre, precio, imagen FROM zapatos WHERE activo = 1";

    //Ejecutamos la consulta
    $resultado = $conexion->query($sql);

    $usuario_id = $_SESSION["usuario_id"] ?? null;
    $cantidad_carrito = 0; //Creamos la varibale que tiene la cantidad del carrito

    //Comprobamos si realmente tenemos el id del usuario y de verdad estamos buscando un usuario
    if($usuario_id){
        //Entonces hacemos la consulta y seleccionamos el id del pedido en la tabla de pedidos
        $sqlpedido = "SELECT id FROM pedidos WHERE usuario_id = ? AND estado = 'pendiente' LIMIT 1";
        //Preparamos la consulta
        $stmtPedido = $conexion->prepare($sqlpedido);
        $stmtPedido->bind_param("i",$usuario_id); //Corregido: antes estaba mal escrito "beind_param"
        $stmtPedido->execute();
        $pedido = $stmtPedido->get_result()->fetch_assoc(); //En esta variable tenemos el resultado y todas las filas
        
        //Comprobamos si el usuario tiene pedido
        if($pedido){
            $sqlCantidad = "SELECT SUM(cantidad) AS total FROM pedido_detalle WHERE pedido_id = ?";
            $stmtCantidad = $conexion->prepare($sqlCantidad);
            $stmtCantidad->bind_param("i", $pedido['id']); //El id del pedido
            $stmtCantidad->execute();
            $resultadoCantidad = $stmtCantidad->get_result()->fetch_assoc();
            $cantidad_carrito = $resultadoCantidad['total'] ?? 0; //Asignamos el total al carrito
        }
    }
    //Nos llevamos la cantidad del carrito,para que en el archivo ver carrito si no hay elementos que salga un contenedor con mensaje de que no hay productos
    $_SESSION["cantidad"] = $cantidad_carrito;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ZAPSTORE | tienda</title>
    <link rel="stylesheet" href="../estilos/tienda.css">
</head>
<body>
<!--Aqui donde va el header --->
<header>
    <nav>
        <h1>ZAPSTORE</h1>

        <!--Aqui creo un contenedor de categorias--->
        <div class="categorias">
            <a href="#nike">Nike</a>
            <a href="#adidas">Adidas</a>
            <a href="#vans">Vans</a>
            <a href="#converse">Converse</a>
        </div>
        <!--Aqui creo un contenedor que contiene el carrito y el nombre del usuario-->
        <div class="usuario">
            <a href="ver_carrito.php" class="carrito">Carrito(<?= $cantidad_carrito?>)</a>
            <p>|</p>
            <p><?= $_SESSION["nombre"]?></p>
        </div>
    </nav>
</header>
<main>
    <!--Contenedor grande que contiene todos los productos de la tienda que los sacamos dinamicamente desde la base de datos-->
    <div class="productos">
        <?php while($zapato = $resultado->fetch_assoc()): ?>
            <!--Creamos la tarjeta que representa el zapato-->
            <div class="producto">
               <a href="mas_informacion.php?zapato=<?=$zapato["id"]?>" class="ver"><img src="<?= htmlspecialchars($zapato['imagen']) ?>" alt="<?= htmlspecialchars($zapato['nombre']) ?>"></a>
                <h3><?= htmlspecialchars($zapato['nombre']) ?></h3>
                <p class="precio"><?= number_format($zapato['precio'], 2) ?> €</p> <!--Le damos formato de numero para precio-->
                <a href="carrito.php?id=<?=$zapato["id"]?>" class="añadir">Añadir al carrito</a> <!--Este enlace nos manda al archivo carrito.php y mandamos por url el id(con el id ya podemos sacar informacion de solo ese producto de la base de datos)-->
            </div>
        <?php endwhile; ?>
    </div>
</main>
    <!--Colocamos el footer separado desde el archivo footer.php-->
    <?php include "../conf/footer.php"?>
    <script src="../script/tienda.js"></script>
</body>
</html>
