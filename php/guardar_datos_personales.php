<?php
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION["id_usuario"])) {
    header("Location: login.html");
    exit();
}

// Conectar a la base de datos
$conexion = new mysqli("localhost", "root", "", "usuarios");

// Verificar la conexión
if ($conexion->connect_error) {
    die("Error al conectar a la base de datos: " . $conexion->connect_error);
}

// Obtener los datos del formulario
$id_usuario = $_SESSION["id_usuario"];
$nombre = trim($_POST["nombre"]);
$apellidos = trim($_POST["apellidos"]);
$genero = $_POST["genero"];
$fecha_nacimiento = $_POST["fecha_nacimiento"];

// Validar que los campos no estén vacíos
if (empty($nombre) || empty($apellidos) || empty($genero) || empty($fecha_nacimiento)) {
    die("Todos los campos son obligatorios o no pueden ser espacios en blanco.");
}

// Validar que no se ingresen puros caracteres de espacios en blanco
if (ctype_space($nombre) || ctype_space($apellidos)) {
    die("Los campos de nombre y apellidos no pueden contener solo espacios en blanco.");
}

// Actualizar los datos en la base de datos
$sql = "UPDATE usuarios SET nombre = '$nombre', apellidos = '$apellidos', genero = '$genero', fecha_nacimiento = '$fecha_nacimiento' WHERE id = $id_usuario";

if ($conexion->query($sql) === TRUE) {
    echo "Se han guardado correctamente los datos";
} else {
    echo "Error al actualizar los datos: " . $conexion->error;
}

// Cerrar la conexión a la base de datos
$conexion->close();
?>
