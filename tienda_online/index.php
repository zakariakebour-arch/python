<?php
    //Conectamos con el archivo que contiene la conexion a la base de datos
    require_once __DIR__ . "/conf/conexion.php";
    //Creamos la variable de conexion para usarla
    $conexion = conexion();
    //Hacemos la consulta para sacar ejemplo de fotos de zapatos
    $sql = "SELECT imagen,nombre FROM zapatos Limit 14;";//Como aqui no hay parametros de entrada en la consulta no hace falta hacer proteccion contra inyeccion SQL,hemos hecho limite 10 para que solo nos muestre 14 imagenes no queremos sacar todas las imagenes enteras
    $resultado = $conexion->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ZAPSTORE | España</title>
    <link rel="stylesheet" href="estilos/index.css">
</head>
<body>
    <!--Aqui colocamos la etiqueda del video dentro del header-->
    <header>
        <div class="contenedor-video">
        <video autoplay muted playsinline loop>
            <source src="videos/video.mp4" type="video/mp4">
        </video>
        <h1 class="titulo">ZAPSTORE</h1>
        <h2 class="frase">El paso perfecto empieza aquí</h2>
        <a href="inicio_sesion/iniciar_sesion.php" class="iniciar-sesion">Iniciar sesión</a>
        <a href="inicio_sesion/registrar.php" class="registrarse">Crear cuenta</a>
        </div>
    </header>
    <!--Colocamos el main con articulos-->
    <main>
        <article>
            <h1 class="zap">ZAP</h1>
            <h1 class="store">STORE</h1>
        </article>
        <!--El contenedor de los logos-->
        <div class="logos">
            <img src="logos/adidaslogo-removebg-preview.png" alt="adidas">
            <img src="logos/nikelogo-removebg-preview.png" alt="nike">
            <img src="logos/vanslogo-removebg-preview.png" alt="vans">
            <img src="logos/converse-removebg-preview.png" alt="converse">
        </div>
        <!--Hacemos una consulta a la base de datos y despues imprimimos imagenes de productos colocandolos en este contenedor div-->
        <div class="productos-ejemplo">
            <div class="cinta">
                <!---Sacamos las 10 fotos con el nombre tambien en alt-->
                <?php while($zapato = $resultado->fetch_assoc()):?>
                    <img src="<?= str_replace('../', '', $zapato['imagen']) ?>" alt="<?=$zapato['nombre']?>"> <!---Aqui he hecho un replace a la consulta para quitar los dos puntos y no modifcar las rutas en la base de datos entera-->
                <?php endwhile?>
            </div>
        </div>
    </main>
        <!--footer lo añadimos desde un archivo separado para reautilizar en varias partes sin repetir el codigo-->
        <?php include "conf/footer.php"?>
    <script src="script/index.js"></script>
</body>
</html>