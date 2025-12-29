<?php
    //Aqui obligamos a contener el arhivo de conexion.php,si falla algo en la conexion,falla todo,por eso usamos require_once en lugar de include
    require_once __DIR__ . "/../conf/conexion.php";
    // Creamos la conexion con la variable conexion y llamamos la funcion que retorna la varible de conexion a la base de datos
    $conexion = conexion();
    //Aqui hacemos una condicion de que si el metodo es POST entoces ahi es cuando realizamos la operacion
    $errores = [];
    if($_SERVER["REQUEST_METHOD"] === "POST"){
        //recibimos el nombre 
        $nombre = trim($_POST["nombre"]);
        //Recibimos el correo
        $correo = trim($_POST["email"]);
        //Recibimos aqui la contraseña original creada y luego la convertimos a hash
        $contraseña = $_POST["contraseña"];

        // Validaciones
        if(strlen($nombre) < 5 || strlen($nombre) > 20){ //Aqui asignamos una longitud minima de 5 maxima de 20
            $errores['nombre'][] = "El nombre debe tener entre 5 y 20 caracteres";
        }

        if(strlen($correo) > 40){ //Aqui simplemente verficamos que el correo no pase un limite de 40 caracteres
            $errores['email'][] = "El correo es demasiado largo";
        }

        if(!filter_var($correo, FILTER_VALIDATE_EMAIL)){ //Condicion que verifica y valida el correo,usamos filter validate email
            $errores['email'][] = "El correo no tiene un formato valido";
        }

        if(strlen($contraseña) < 8 || strlen($contraseña) > 17){
            $errores['contraseña'][] = "La contraseña debe tener al menos 8 caracteres y como maximo 17";
        }
        //Condicion que verifica caracteres usados en la contraseña
        if(
            !preg_match("/[A-Z]/", $contraseña) || //Letras mayusculas
            !preg_match("/[a-z]/", $contraseña) || //Letras minusculas
            !preg_match("/[0-9]/", $contraseña) //Numeros entre el 0-9
        ){
            $errores['contraseña'][] = "La contraseña debe contener mayusculas, minusculas y numeros";
        }
        //Aqui lo que hacemos es que si el array esta vacio entonces no hay errores y insertamos y realizamos todo correctamente
        if(empty($errores)){
            //Como no hay errores en los pasos anteriores,convertimos la contraseña introducida a hash
            $hash = password_hash($contraseña, PASSWORD_DEFAULT);
            //Comenzamos a insertar los datos a la base de datos como nuevo usuario usando sentencia preparada para evitar SQL Injection
            $stmt = $conexion->prepare("INSERT INTO usuarios (nombre,email,password) VALUES (?, ?, ?);");
            //Antes hemos insertado sin los parametros para evitar injeccion SQL 
            $stmt->bind_param("sss", $nombre, $correo, $hash);
            //Si todo correcto la ejecucion,automaticamente el usuario se manda al archivo de inicio de sesion para que pueda iniciar correctamente
            if($stmt->execute()){
                header("Location: iniciar_sesion.php");
                exit;
            }
            //Cerramos conexion con la base de datos
            $stmt->close();
        }  
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrarse</title>
    <link rel="stylesheet" href="../estilos/registrar.css">
</head>
<body>
    <form action="?" method="POST">
        <h1>ZAPSTORE</h1>
        <input type="text" name="nombre" placeholder="NOMBRE" required>
        <?php if(isset($errores['nombre'])) foreach($errores['nombre'] as $error) echo "<p class='error'>$error</p>"; ?><!--Aqui aparece el mensaje de nombre debajo del input del nombre-->

        <input type="text" name="email" placeholder="EMAIL" required>
        <?php if(isset($errores['email'])) foreach($errores['email'] as $error) echo "<p class='error'>$error</p>"; ?><!--Aqui aparece el mensaje de correo debajo del input del correo-->

        <input type="password" name="contraseña" placeholder="CONTRASESEÑA" required>
        <?php if(isset($errores['contraseña'])) foreach($errores['contraseña'] as $error) echo "<p class='error'>$error</p>"; ?><!--Aqui aparece el mensaje de contraseña debajo del input de contraseña-->

        <button type="submit">CREAR CUENTA</button>
    </form>
</body>
</html>
