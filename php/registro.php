<?php
session_start();  // Iniciar sesión

// Validar si el formulario fue enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Conectar a la base de datos
    $conexion = new mysqli("localhost", "root", "", "usuarios");

    // Verificar la conexión
    if ($conexion->connect_error) {
        die("Error al conectar a la base de datos: " . $conexion->connect_error);
    }

    // Obtener los datos del formulario
    $nombre = trim($_POST["nombre"]);
    $apellidos = trim($_POST["apellidos"]);
    $username = trim($_POST["username"]);
    $correo = trim($_POST["correo"]);
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];
    $genero = $_POST["genero"];
    $fecha_nacimiento = $_POST["fecha_nacimiento"];

    // Validar que las contraseñas coincidan
    if ($password != $confirm_password) {
        $_SESSION["error"] = "Las contraseñas no coinciden.";
        header("Location: ../registro.html");
        exit();
    }

    // Validar que el correo electrónico sea válido
    if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $_SESSION["error"] = "El correo electrónico ingresado no es válido.";
        header("Location: ../registro.html");
        exit();
    }

    // Validar el username
    if (!preg_match('/^[a-z][a-z0-9_]{1,}$/i', $username)) {
        $_SESSION["error"] = "El username no es válido. Debe contener caracteres de letras (a - z), números (0 - 9) y solo el caracter especial '_'. 
        El username debe ser solo minúsculas y únicamente puede empezar con una letra (a - z). 
        La longitud mínima del username debe ser 2 caracteres.";
        header("Location: ../registro.html");
        exit();
    }

    // Convertir el username a minúsculas
    $username = strtolower($username);

    // Verificar que no exista otro correo y usuario con el mismo correo y usuario
    $sql = "SELECT * FROM usuarios WHERE correo = '$correo'";
    $sql1 = "SELECT * FROM usuarios WHERE username = '$username'";
    $resultado = $conexion->query($sql);
    $resultado1 = $conexion->query($sql1);

    if ($resultado->num_rows > 0 ) {
        $_SESSION["error"] = "Ya existe un correo registrado con ese correo electrónico.";
        header("Location: ../registro.html");
        exit();
    } else {
        if ($resultado1->num_rows > 0) {
            $_SESSION["error"] = "Ya existe un username con ese nombre registrado";
            header("Location: ../registro.html");
            exit();
        }
    }

    // Generar el salt y cifrar la contraseña
    $salt = bin2hex(random_bytes(32));
    $password_encrypted = hash("sha512", $password . $salt);

    // Insertar el nuevo usuario en la base de datos
    $sql = "INSERT INTO usuarios (nombre, apellidos, username, password, genero, fecha_nacimiento, salt, correo) 
    VALUES ('$nombre', '$apellidos', '$username','$password_encrypted', '$genero', '$fecha_nacimiento', '$salt',  '$correo')";

    if ($conexion->query($sql) === TRUE) {
        $_SESSION["success"] = "Usuario registrado exitosamente.";
        header("Location: ../index.html");
    } else {
        $_SESSION["error"] = "Error al registrar el usuario: " . $conexion->error;
        header("Location: ../registro.html");
    }

    // Cerrar la conexión a la base de datos
    $conexion->close();
}
?>
