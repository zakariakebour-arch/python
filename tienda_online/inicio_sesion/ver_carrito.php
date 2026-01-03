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
    //Gurdamos la cantidad del carrito con sesion de tienda en una variable para usar
    $cantidad = $_SESSION["cantidad"];
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
        <!--Antes comprobamos que si no hay productos,sale mensaje de que no hay productos en el carrito,de lo contrario recorremos el bucle while e imprimimos los productos-->
        <?php if ($resultado->num_rows === 0): ?>
            <div class="cantidad">No hay productos en el carrito</div>
        <?php else: ?>
            <!--Aqui recorremos uno por uno creando cards cada producto--->
            <?php while($producto = $resultado->fetch_assoc()): ?>
                    <!--Aqui mientras guardamos cantidad del producto seleccionado en el carrito ppara poder borrar--->
                    <?php $_SESSION["cantidad_producto"] = $producto["cantidad"];?>
                    <article>
                        <a href="mas_informacion.php?zapato=<?=$producto["zapato_id"]?>">        <!--Nos llevamos el id del zapato para usar en el archivo de mas informacion sobre el producto en el que mustra solo un producto el seleccionado--->
                            <img src="<?=$producto["imagen"]?>" alt="<?=$producto["nombre"]?>">
                        </a>
                        <!--Aqui he creado un contenedor para separar de manera ordenada entre la imagen y la informacion del producto--->
                        <div class="descripcion">
                            <p>Nombre del producto: <strong><?=$producto["nombre"]?></strong></p>
                            <p><strong><?=$producto["descripcion"]?></strong></p>
                            <p>Precio: <strong><?=$producto["precio_unitario"]?>€</strong></p>
                            <p>Cantidad Seleccionada del producto: <strong><?=$producto["cantidad"]?></strong></p>
                            <p>Precio total con cantidad seleccionada: <strong><?=$producto["total_cantidad"]?></strong>€</p>
                            <!--Aqui voy a colocar un emoji so simbolo de papelera con un enlace que nos muestre el id del pedido detalle para borrarlo del carrito--->
                            <a href="eliminar_producto.php?zapato_id=<?=$producto["zapato_id"]?>" class="papelera" title="Desea borrar el producto?">
                                <img src="../logos/papelera.png" alt="papelera">
                            </a>
                        </div>
                    </article>
            <?php endwhile; ?>
        <?php endif?>
    </main>
    <?php include "../conf/footer.php"?>
    <script src="../script/tienda.js"></script>
</body>
</html>
