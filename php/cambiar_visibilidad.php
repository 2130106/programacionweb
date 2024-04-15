<?php
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION["id_usuario"])) {
    header("Location: ../index.html");
    exit();
}

// Obtener el ID del archivo y la nueva visibilidad
$id_archivo = $_POST["id"];
$visibilidad = $_POST["visibilidad"];

// Conectar a la base de datos
$conexion = new mysqli("localhost", "root", "", "usuarios");

// Verificar la conexión
if ($conexion->connect_error) {
    die("Error al conectar a la base de datos: " . $conexion->connect_error);
}

// Actualizar la visibilidad del archivo en la base de datos
$sql = "UPDATE archivos SET visibilidad = '$visibilidad' WHERE id = $id_archivo";
if ($conexion->query($sql) === TRUE) {
    // Devolver la nueva visibilidad
    echo "<select onchange='cambiarVisibilidad($id_archivo, this.value)'>";
    echo "<option value='privado' " . ($visibilidad == 'privado' ? 'selected' : '') . ">Privado</option>";
    echo "<option value='publico' " . ($visibilidad == 'publico' ? 'selected' : '') . ">Público</option>";
    echo "</select>";
} else {
    echo "Error al cambiar la visibilidad del archivo: " . $conexion->error;
}

// Cerrar la conexión a la base de datos
$conexion->close();
?>
