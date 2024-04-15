<?php
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION["id_usuario"])) {
    header("Location: ../index.html");
    exit();
}

// Obtener el ID del archivo a borrar
$id_archivo = $_POST["id"];

// Conectar a la base de datos
$conexion = new mysqli("localhost", "root", "", "usuarios");

// Verificar la conexión
if ($conexion->connect_error) {
    die("Error al conectar a la base de datos: " . $conexion->connect_error);
}

// Consulta SQL para obtener la ruta del archivo a borrar
$sql = "SELECT ruta_archivo FROM archivos WHERE id = $id_archivo";
$resultado = $conexion->query($sql);

if ($resultado->num_rows > 0) {
    $fila = $resultado->fetch_assoc();
    $ruta_archivo = $fila["ruta_archivo"];

    // Borrar el archivo del servidor
    if (unlink($ruta_archivo)) {
        // Borrar la entrada de la base de datos
        $sql = "DELETE FROM archivos WHERE id = $id_archivo";
        if ($conexion->query($sql) === TRUE) {
            echo "El archivo se ha borrado correctamente.";
        } else {
            echo "Error al borrar el archivo de la base de datos: " . $conexion->error;
        }
    } else {
        echo "Error al borrar el archivo del servidor.";
    }
} else {
    echo "Archivo no encontrado.";
}

// Cerrar la conexión a la base de datos
$conexion->close();
?>
