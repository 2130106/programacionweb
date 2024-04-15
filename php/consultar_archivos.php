<?php
session_start();

if (!isset($_SESSION["id_usuario"])) {
    header("Location: ../index.html");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los parámetros de año y mes enviados por AJAX
    $year = $_POST["year"];
    $month = $_POST["month"];

    // Conectar a la base de datos
    $conexion = new mysqli("localhost", "root", "", "usuarios");

    if ($conexion->connect_error) {
        die("Error al conectar a la base de datos: " . $conexion->connect_error);
    }

    // Obtener el ID del usuario
    $id_usuario = $_SESSION["id_usuario"];

    // Verificar si existen archivos para la fecha especificada
    $sql_check = "SELECT COUNT(*) as count_archivos FROM archivos WHERE id_usuario = $id_usuario AND YEAR(fecha_upload) = $year AND MONTH(fecha_upload) = $month";
    $resultado_check = $conexion->query($sql_check);
    $fila_check = $resultado_check->fetch_assoc();
    $count_archivos = $fila_check["count_archivos"];

    if ($count_archivos > 0) {
        // Consultar los archivos del usuario para el año y mes especificados
        $sql = "SELECT id, nombre_archivo, ruta_archivo, visibilidad FROM archivos WHERE id_usuario = $id_usuario AND YEAR(fecha_upload) = $year AND MONTH(fecha_upload) = $month";
        $resultado = $conexion->query($sql);

        // Construir el HTML de los archivos consultados
        $archivos_html = "<ul class='grid-container'>";
        while ($fila = $resultado->fetch_assoc()) {
            $id_archivo = $fila["id"];
            $nombre_archivo = $fila["nombre_archivo"];
            $ruta_archivo = $fila["ruta_archivo"];
            $visibilidad = $fila["visibilidad"];
            $archivos_html .= "<li class='grid-item'>";
            $archivos_html .= "<a href='$ruta_archivo'><img src='$ruta_archivo' alt='$nombre_archivo'></a>";
            $archivos_html .= "<span class='delete-icon' onclick='borrarArchivo($id_archivo)'>&#128465;</span>";
            $archivos_html .= "<select onchange='cambiarVisibilidad($id_archivo, this.value)'>";
            $archivos_html .= "<option value='privado' " . ($visibilidad == 'privado' ? 'selected' : '') . ">Privado</option>";
            $archivos_html .= "<option value='publico' " . ($visibilidad == 'publico' ? 'selected' : '') . ">Público</option>";
            $archivos_html .= "</select>";
            $archivos_html .= "</li>";
        }
        $archivos_html .= "</ul>";

        echo $archivos_html; // Devolver el HTML de los archivos consultados
    } else {
        echo "No se encontraron archivos para el año $year y mes $month.";
    }

    $conexion->close();
} else {
    echo "Error: método de solicitud incorrecto.";
}
?>
