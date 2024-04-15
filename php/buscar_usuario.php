<?php
session_start();

if (!isset($_SESSION["id_usuario"])) {
    header("Location: ../index.html");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $parametro = $_POST["parametro"];
    $id_usuario_sesionIniciada = $_POST["id_usuario"];

    // Conectar a la base de datos y realizar la consulta
    $conexion = new mysqli("localhost", "root", "", "usuarios");

    if ($conexion->connect_error) {
        die("Error al conectar a la base de datos: " . $conexion->connect_error);
    }

    $query = "SELECT id, nombre, apellidos, username FROM usuarios WHERE nombre LIKE '%$parametro%' OR username LIKE '%$parametro%'";

    $resultado = $conexion->query($query);

    if ($resultado->num_rows > 0) {
        while ($fila = $resultado->fetch_assoc()) {
            $id_usuario = $fila["id"];
            $nombre = $fila["nombre"];
            $apellidos = $fila["apellidos"];
            $username = $fila["username"];

            // Mostrar los resultados como enlaces
            echo "<a href='archivos_usuario.php?id=$id_usuario&id_sesion=$id_usuario_sesionIniciada'>$nombre $apellidos ($username)</a><br>";
        }
    } else {
        echo "No se encontraron usuarios con ese parámetro de búsqueda.";
    }

    $conexion->close();
} else {
    echo "Error: método de solicitud incorrecto.";
}
?>
