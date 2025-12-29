<?php
    //Hacemos Sesion start
    session_start();
    //Creamos contacto con el archivo de configuracion de la base de datos
    require_once __DIR__ . "/../conf/conexion.php";
    //Creamos la variable conexion
    $conexion = conexion();
    $usuario_id = (int) $_SESSION["usuario_id"];//Pasamos otra vez el id del usuario porque lo necesitamos para saber que productos son los suyos al realizar la consulta

    //Hacemos la consulta de los productos del usuario,un JOIN juntando 
    $sql = "SELECT pedidos.id,
    pedido_detalle.zapato_id,
    pedido_detalle.cantidad,
    pedido_detalle.precio_unitario,
    zapatos.nombre,
    zapatos.descripcion,
    zapatos.imagen,
    pedido_detalle.cantidad * CAST(pedido_detalle.precio_unitario AS DECIMAL(10,2)) AS total_cantidad
FROM pedidos
JOIN pedido_detalle ON pedidos.id = pedido_detalle.pedido_id
JOIN zapatos ON zapatos.id = pedido_detalle.zapato_id
WHERE pedidos.usuario_id = ?
AND pedidos.estado = 'pendiente';";

    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i",$usuario_id);
    $stmt->execute();
    $resultado = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito</title>
    <link rel="stylesheet" href="../estilos/ver_carrito.css">
</head>
<body>
    <!--Aqui voy a crear el contenedor con (main) que va a contener todos los productos del usuario--->
    <main>
        <!--Aqui recorremos uno por uno creando cards cada producto--->
        <?php while($producto = $resultado->fetch_assoc()): ?>
                <article>
                    <img src="<?=$producto["imagen"]?>" alt="<?=$producto["nombre"]?>">
                    <!--Aqui he creado un contenedor para separar de manera ordenada entre la imagen y la informacion del producto--->
                    <div class="descripcion">
                        <p>Nombre del producto: <strong><?=$producto["nombre"]?></strong></p>
                        <p><strong><?=$producto["descripcion"]?></strong></p>
                        <p>Precio: <strong><?=$producto["precio_unitario"]?>€</strong></p>
                        <p>Cantidad Seleccionada del producto: <strong><?=$producto["cantidad"]?></strong></p>
                        <p>Precio total con cantidad seleccionada: <strong><?=$producto["total_cantidad"]?></strong>€</p>
                    </div>
                </article>
        <?php endwhile; ?>
    </main>
    <?php include "../conf/footer.php"?>
    <script src="../script/tienda.js"></script>
</body>
</html>
