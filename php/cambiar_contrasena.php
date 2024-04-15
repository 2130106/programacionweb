<?php
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION["id_usuario"])) {
    die("Usuario no autenticado.");
}

// Conectar a la base de datos
$conexion = new mysqli("localhost", "root", "", "usuarios");

// Verificar la conexión
if ($conexion->connect_error) {
    die("Error al conectar a la base de datos: " . $conexion->connect_error);
}

// Obtener los datos del formulario
$id_usuario = $_SESSION["id_usuario"];
$password_actual = $_POST["password_actual"];
$nueva_password = $_POST["nueva_password"];
$confirmar_nueva_password = $_POST["confirmar_nueva_password"];

// Obtener la contraseña actual cifrada de la base de datos
$sql = "SELECT password, salt FROM usuarios WHERE id = $id_usuario";
$resultado = $conexion->query($sql);

if ($resultado->num_rows > 0) {
    $fila = $resultado->fetch_assoc();
    $password_cifrada_actual = hash("sha512", $password_actual . $fila["salt"]);

    // Verificar que la contraseña actual coincida con la almacenada en la base de datos
    if ($password_cifrada_actual == $fila["password"]) {
        // Validar que las contraseñas nuevas coincidan
        if ($nueva_password != $confirmar_nueva_password) {
            die("Las contraseñas nuevas no coinciden.");
        }

        // Generar el salt y cifrar la nueva contraseña
        $salt = bin2hex(random_bytes(32));
        $nueva_password_cifrada = hash("sha512", $nueva_password . $salt);

        // Actualizar la contraseña en la base de datos
        $sql = "UPDATE usuarios SET password = '$nueva_password_cifrada', salt = '$salt' WHERE id = $id_usuario";

        if ($conexion->query($sql) === TRUE) {
            echo "Contraseña cambiada correctamente.";
        } else {
            echo "Error al actualizar la contraseña: " . $conexion->error;
        }
    } else {
        echo "La contraseña actual es incorrecta.";
    }
} else {
    echo "Usuario no encontrado.";
}

// Cerrar la conexión a la base de datos
$conexion->close();
?>

