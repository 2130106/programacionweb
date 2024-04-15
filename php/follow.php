<?php
session_start();

if (!isset($_SESSION["id_usuario"])) {
    header("Location: ../index.html");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_usuario = $_POST["id_usuario"];
    $id_sesion = $_POST["id_sesion"];

    // Conectar a la base de datos
    $conexion = new mysqli("localhost", "root", "", "usuarios");
    if ($conexion->connect_error) {
        die("Error al conectar a la base de datos: " . $conexion->connect_error);
    }

    // Verificar si ya existe un seguimiento entre los usuarios
    $query = "SELECT follow FROM follow WHERE Usuario1 = $id_sesion AND Usuario2 = $id_usuario";
    $resultado = $conexion->query($query);
    if ($resultado->num_rows > 0) {
        $fila = $resultado->fetch_assoc();
        $follow = $fila["follow"];
        if ($follow == 1) {
            // Si ya sigue al usuario, eliminar el seguimiento (unfollow)
            $query = "DELETE FROM follow WHERE Usuario1 = $id_sesion AND Usuario2 = $id_usuario";
            if ($conexion->query($query) === TRUE) {
                echo "Unfollow exitoso";
            } else {
                echo "Error al realizar unfollow: " . $conexion->error;
            }
        } else {
            // Si no sigue al usuario, actualizar el seguimiento (follow)
            $query = "UPDATE follow SET follow = 1 WHERE Usuario1 = $id_sesion AND Usuario2 = $id_usuario";
            if ($conexion->query($query) === TRUE) {
                echo "Follow exitoso";
            } else {
                echo "Error al realizar follow: " . $conexion->error;
            }
        }
    } else {
        // Si no existe un seguimiento, crear uno (follow)
        $query = "INSERT INTO follow (Usuario1, Usuario2, follow) VALUES ($id_sesion, $id_usuario, 1)";
        if ($conexion->query($query) === TRUE) {
            echo "Follow exitoso";
        } else {
            echo "Error al realizar follow: " . $conexion->error;
        }
    }

    $conexion->close();
} else {
    echo "Acceso denegado";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Follow/Unfollow</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body>

<?php
if (isset($_GET["id"]) && !empty($_GET["id"])) {
    $id_usuario = $_GET["id"];
    $id_sesion = $_GET["id_sesion"];
    // Conectar a la base de datos y obtener información del usuario
    $conexion = new mysqli("localhost", "root", "", "usuarios");

    if ($conexion->connect_error) {
        die("Error al conectar a la base de datos: " . $conexion->connect_error);
    }

    $query = "SELECT nombre, apellidos FROM usuarios WHERE id = $id_usuario";
    $resultado = $conexion->query($query);

    if ($resultado->num_rows > 0) {
        $fila = $resultado->fetch_assoc();
        $nombre_usuario = $fila["nombre"] . " " . $fila["apellidos"];
    } else {
        die("Usuario no encontrado.");
    }

    // Verificar si el usuario ya sigue a este usuario
    $query = "SELECT follow FROM follow WHERE Usuario1 = $id_sesion AND Usuario2 = $id_usuario";
    $resultado = $conexion->query($query);
    if ($resultado->num_rows > 0) {
        $fila = $resultado->fetch_assoc();
        $follow = $fila["follow"];
        if ($follow == 1) {
            $boton_texto = "Unfollow";
        } else {
            $boton_texto = "Follow";
        }
    } else {
        $boton_texto = "Follow";
    }

    $conexion->close();
} else {
    die("ID de usuario no proporcionado.");
}
?>

<form id="follow_form_<?php echo $id_usuario; ?>">
    <input type="hidden" id="id_usuario_<?php echo $id_usuario; ?>" name="id_usuario" value="<?php echo $id_usuario; ?>">
    <input type="hidden" id="id_sesion_<?php echo $id_usuario; ?>" name="id_sesion" value="<?php echo $id_sesion; ?>">
    <button type="button" onclick="toggleFollow(<?php echo $id_usuario; ?>)">
        <?php echo $boton_texto; ?>
    </button>
</form>

<script>
    function toggleFollow(id_usuario) {
        var id_sesion = document.getElementById("id_sesion_" + id_usuario).value;

        $.ajax({
            type: "POST",
            url: "follow.php",
            data: { id_usuario: id_usuario, id_sesion: id_sesion },
            success: function(response) {
                // Actualizar el texto del botón después de seguir o dejar de seguir
                var followButton = document.getElementById("follow_form_" + id_usuario).getElementsByTagName("button")[0];
                followButton.innerHTML = response.includes("Follow") ? "Follow" : "Unfollow";
            },
            error: function(xhr, status, error) {
                alert("Error al procesar el seguimiento: " + error);
            }
        });
    }
</script>

</body>
</html>
