<?php
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION["id_usuario"])) {
    header("Location: ../login.html");
    exit();
}

// Carpeta donde se guardarán los archivos
$carpeta_destino = "../archivos/";

// Obtener el nombre y tipo del archivo subido
$nombre_archivo = $_FILES["archivo"]["name"];
$tipo_archivo = $_FILES["archivo"]["type"];
$tamano_archivo = $_FILES["archivo"]["size"];
$ruta_archivo = $carpeta_destino . $nombre_archivo;

// Obtener la fecha actual en formato MySQL
$fecha_upload = date("Y-m-d H:i:s");

// Subir el archivo
if (move_uploaded_file($_FILES["archivo"]["tmp_name"], $ruta_archivo)) {
    // Conectar a la base de datos
    $conexion = new mysqli("localhost", "root", "", "usuarios");

    // Verificar la conexión
    if ($conexion->connect_error) {
        die("Error al conectar a la base de datos: " . $conexion->connect_error);
    }

    // Obtener el ID del usuario
    $id_usuario = $_SESSION["id_usuario"];

    // Insertar el archivo en la base de datos con la fecha de subida
    $sql = "INSERT INTO archivos (id_usuario, nombre_archivo, ruta_archivo, fecha_upload) VALUES ($id_usuario, '$nombre_archivo', '$ruta_archivo', '$fecha_upload')";

    if ($conexion->query($sql) === TRUE) {
        echo "Archivo subido correctamente.";
    } else {
        echo "Error al subir el archivo a la base de datos: " . $conexion->error;
    }

    // Cerrar la conexión a la base de datos
    $conexion->close();
} else {
    echo "Error al subir el archivo.";
}

?>
