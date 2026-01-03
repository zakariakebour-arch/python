<?php
    //Hacemos un sesion start para llevarnos los datos del usuario si inicia sesion correctamente a otra pagina
    session_start();

    //Aqui obligamos a contener el arhivo de conexion.php,si falla algo en la conexion,falla todo,por eso usamos require_once en lugar de include
    require_once __DIR__ . "/../conf/conexion.php";

    // Creamos la conexion con la variable conexion y llamamos la funcion que retorna la varible de conexion a la base de datos
    $conexion = conexion();

    //Primero recibimos el correo y la contraseña introducida
    if($_SERVER["REQUEST_METHOD"] === "POST"){

        //Recibimos correo
        $correo_ingresado = $_POST["correo"];

        //Recibimos contraseña
        $contraseña_ingresada = $_POST["contraseña"];

        //Verificamos si exsiste el correo,si exsiste comparamos contraseña ingresada con contraseña guardad en la base de datos
        $consulta = $conexion->prepare("SELECT id, password, nombre FROM usuarios WHERE email = ?");
        $consulta->bind_param("s", $correo_ingresado);
        $consulta->execute();
        $resultado = $consulta->get_result();

        //Comparamos el correo y la contraseña si sosn correctos
        if($fila = $resultado->fetch_assoc()){

            //Verificamos contraseña
            if(password_verify($contraseña_ingresada, $fila["password"])){

                //Guardamos nombre eb la sesion
                $_SESSION["nombre"] = $fila["nombre"];
                //Gurdamos el id del usuario 
                $_SESSION["usuario_id"] = $fila["id"];

                header("Location: tienda.php");
                exit;
            } else {
                $error = "Contraseña incorrecta";
            }

        } else {
            $error_correo = "El correo no existe";
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <link rel="stylesheet" href="../estilos/inicio_sesion.css">
</head>
<body>
    <form action="?" method="POST">
        <h1>ZAPSTORE</h1>
        <p>INICIAR SESIÓN</p>
        <input type="email" name="correo" placeholder="EMAIL" required>
        <?php echo "<p style='color:red;'>".$error_correo."</p>"?> 
        <input type="password" name="contraseña" placeholder="CONTRASEÑA" required>
        <?php echo "<p style='color:red;'>".$error."</p>"?> 
        <button type="submit">INICIAR SESIÓN</button>
        <a href="registrar.php">REGÍSTRARSE</a>
    </form>
    <script></script>
</body>
</html>

