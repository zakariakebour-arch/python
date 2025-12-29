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
        <div class="productos-ejemplo">
            <div>
                <img src="" alt="">
            </div>
        </div>
    </main>
        <!--footer lo añadimos desde un archivo separado para reautilizar en varias partes sin repetir el codigo-->
        <?php include "conf/footer.php"?>
    <script src="script/index.js"></script>
</body>
</html>