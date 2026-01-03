<?php
    //Hacemos sesion start
    session_start();
    //Conectamos con la base de datos
    require_once "../conf/conexion.php";
    
    //Creamos la variable de conexion
    $conexion = conexion();

    //Comprobamos si nos ha llegado el id del zapato
    if(!isset($_GET["zapato"])){
        header("Location: ver_carrito.php");
        exit;
        //Si no hay id del producto vuelve a la pagina donde estaba el usuario
    }
    $id_zapato = (int) $_GET["zapato"];

    //Hacemos una seleccion segun el producto que ha tocado el usuario
    $sql = "SELECT * FROM zapatos WHERE id = ? Limit 1;"; //Le indicamos que el limite es 1
    $stmt= $conexion->prepare($sql);
    $stmt->bind_param("i",$id_zapato);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $producto = $resultado->fetch_assoc();//Asi nos devuelve una fila del producto 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?=$producto["nombre"]?></title>
    <link rel="stylesheet" href="../estilos/mas_info.css">
</head>
<body>
    <main>
        <article class="imagen">
            <img src="<?=$producto["imagen"]?>" alt="<?=$producto["nombre"]?>">
        </article>
        <article class="informacion">
            <h2><?=$producto["nombre"]?></h2>
            <h3><?=$producto["descripcion"]?></h3>
            <p>stock: <?=$producto["stock"]?></p>
            <p>Precio: <?=$producto["precio"]?></p>
            <p>Color: <?=$producto["color_principal"]?></p>
            <p>Peso: <?=$producto["peso"]?>g</p>
            <p>Talla hasta: <?=$talla = (int) $producto["talla_hasta"]?></p><!---Aqui he convertido el valor a int porque en la base de datos esta como decimal-->
        </article>
    </main>
</body>
</html>