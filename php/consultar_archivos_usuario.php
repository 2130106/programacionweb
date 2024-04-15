<?php
session_start();

if (!isset($_SESSION["id_usuario"])) {
    header("Location: ../index.html");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["id_usuario"], $_POST["year"], $_POST["month"])) {
    $id_usuario = $_POST["id_usuario"];
    $year = $_POST["year"];
    $month = $_POST["month"];
    $id_sesion = $_POST["id_sesion"];

    // Conectar a la base de datos
    $conexion = new mysqli("localhost", "root", "", "usuarios");

    if ($conexion->connect_error) {
        die("Error al conectar a la base de datos: " . $conexion->connect_error);
    }

    // Verificar si el usuario es administrador
    $sql_admin = "SELECT es_admin FROM usuarios WHERE id = $id_sesion";
    $resultado_admin = $conexion->query($sql_admin);
    $es_administrador = $resultado_admin->fetch_assoc()["es_admin"];

    // Consultar archivos públicos del usuario por año-mes
    if ($es_administrador) {
        // Si es administrador, consultar todos los archivos
        $sql_archivos = "SELECT id, nombre_archivo, ruta_archivo FROM archivos WHERE  id_usuario = $id_usuario AND YEAR(fecha_upload) = $year AND MONTH(fecha_upload) = $month";
    } else {
        // Si no es administrador, consultar solo los archivos públicos
        $sql_archivos = "SELECT id, nombre_archivo, ruta_archivo FROM archivos WHERE id_usuario = $id_usuario AND visibilidad = 'publico' AND YEAR(fecha_upload) = $year AND MONTH(fecha_upload) = $month";
    }

    $resultado_archivos = $conexion->query($sql_archivos);

  // Construir el HTML de los archivos consultados
  $archivos_html = "<style>
  body {
      font-family: Arial, sans-serif;
  }

  .container {
      max-width: 1200px;
      margin: 0 auto;
      padding: 20px;
  }

  .grid-container {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
      grid-gap: 20px;
  }

  .grid-item {
      padding: 10px;
      text-align: center;
      position: relative;
      border-radius: 5px;
      background-color: #f1f1f1;
  }

  .grid-item img {
      width: 250px;
      height: 300px;
      object-fit: cover;
      border-radius: 0px;
      margin-bottom: 10px;
  }

  ul {
      list-style-type: none;
      padding-left: 0;
  }

  li {
      margin-bottom: 10px;
  }
</style>";

    $archivos_html .= "<div class='container'><ul class='grid-container'>";
    while ($fila = $resultado_archivos->fetch_assoc()) {
        $id_archivo = $fila["id"];
        $nombre_archivo = $fila["nombre_archivo"];
        $ruta_archivo = $fila["ruta_archivo"];
        $archivos_html .= "<li class='grid-item'>";
        $archivos_html .= "<a href='$ruta_archivo'><img src='$ruta_archivo' alt='$nombre_archivo'></a>";
        $archivos_html .= "</li>";
    }
    $archivos_html .= "</ul></div>";

    echo $archivos_html; // Devolver el HTML de los archivos consultados

    $conexion->close();
} else {
    echo "Error: Parámetros incorrectos.";
}
?>
