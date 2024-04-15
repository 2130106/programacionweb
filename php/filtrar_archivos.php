<?php
session_start();

if (!isset($_SESSION["id_usuario"])) {
    header("Location: ../index.html");
    exit();
}

if (isset($_GET["id"]) && !empty($_GET["id"]) && isset($_POST["year"]) && isset($_POST["month"])) {
    $id_usuario_buscado = $_GET["id"];
    $year = $_POST["year"];
    $month = $_POST["month"];

    // Conectar a la base de datos y obtener archivos del usuario filtrados
    $conexion = new mysqli("localhost", "usuario", "contraseña", "base_de_datos");
    if ($conexion->connect_error) {
        die("Error al conectar a la base de datos: " . $conexion->connect_error);
    }

    // Realizar la consulta de archivos del usuario filtrados por año y mes
    $query_archivos = "SELECT nombre_archivo, ruta_archivo FROM archivos WHERE id_usuario = $id_usuario_buscado AND YEAR(fecha_upload) = $year AND MONTH(fecha_upload) = $month";
    $resultado_archivos = $conexion->query($query_archivos);

    if ($resultado_archivos->num_rows > 0) {
        // Mostrar los archivos encontrados
        echo "<h1>Archivos del Usuario</h1>";
        while ($fila_archivo = $resultado_archivos->fetch_assoc()) {
            $nombre_archivo = $fila_archivo["nombre_archivo"];
            $ruta_archivo = $fila_archivo["ruta_archivo"];
            echo "<a href='$ruta_archivo'>$nombre_archivo</a><br>";
        }
    } else {
        echo "No se encontraron archivos para este usuario en el año $year y mes $month.";
    }

    $conexion->close();
} else {
    echo "Error: ID de usuario, año o mes no proporcionado.";
}
?>
