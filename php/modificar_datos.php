<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Modificar Datos Personales</title>
    <link rel="stylesheet" href="../css/modificar_datos.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $("#modificar_datos_form").submit(function(event) {
                event.preventDefault();
                var formData = $(this).serialize();
                $.ajax({
                    type: "POST",
                    url: "guardar_datos_personales.php",
                    data: formData,
                    success: function(response) {
                        $("#mensaje").html(response);
                    }
                });
            });
        });
    </script>
</head>
<body>
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

    // Obtener los datos del usuario
    $id_usuario = $_SESSION["id_usuario"];
    $sql = "SELECT * FROM usuarios WHERE id = $id_usuario";
    $resultado = $conexion->query($sql);

    if ($resultado->num_rows > 0) {
        $fila = $resultado->fetch_assoc();
        $nombre = $fila["nombre"];
        $apellidos = $fila["apellidos"];
        $genero = $fila["genero"];
        $fecha_nacimiento = $fila["fecha_nacimiento"];
    }
    ?>
    
    <h1>Modificar Datos Personales</h1>
    <div id="mensaje"></div><br>
    <form id="modificar_datos_form">
        <label for="nombre">Nombre:</label><br>
        <input type="text" id="nombre" name="nombre" value="<?php echo $nombre; ?>" required><br><br>

        <label for="apellidos">Apellidos:</label><br>
        <input type="text" id="apellidos" name="apellidos" value="<?php echo $apellidos; ?>" required><br><br>

        <label for="genero">Género:</label><br>
        <select id="genero" name="genero" required>
            <option value="M" <?php if ($genero == "M") echo "selected"; ?>>Masculino</option>
            <option value="F" <?php if ($genero == "F") echo "selected"; ?>>Femenino</option>
            <option value="X" <?php if ($genero == "X") echo "selected"; ?>>Prefiero no especificar</option>
        </select><br><br>

        <label for="fecha_nacimiento">Fecha de Nacimiento:</label><br>
        <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" value="<?php echo $fecha_nacimiento; ?>" required><br><br>

        <input type="submit" value="Guardar Cambios"><br><br>
        <a href="home.php">Regresar</a>
    </form>
</body>
</html>

<?php
// Cerrar la conexión a la base de datos
$conexion->close();
?>
