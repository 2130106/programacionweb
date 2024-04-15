<?php
session_start();

if (!isset($_SESSION["id_usuario"])) {
    header("Location: ../index.html");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["id_publicacion_like"])) {
    $id_publicacion_like = $_POST["id_publicacion_like"];

    // Verificar si el usuario ya dio like a esta publicación
    $stmt_check = $conexion->prepare("SELECT id_like FROM likes WHERE id_publicacion = ? AND Usuario1 = ?");
    $stmt_check->bind_param("ii", $id_publicacion_like, $id_usuario);
    $stmt_check->execute();
    $stmt_check->store_result();
    if ($stmt_check->num_rows > 0) {
        // El usuario ya dio like a esta publicación, se elimina el like
        $stmt_delete = $conexion->prepare("DELETE FROM likes WHERE id_publicacion = ? AND Usuario1 = ?");
        $stmt_delete->bind_param("ii", $id_publicacion_like, $id_usuario);
        $stmt_delete->execute();
        echo "dislike";
    } else {
        // El usuario no ha dado like a esta publicación, se agrega el like
        $insert_stmt = $conexion->prepare("INSERT INTO likes (Usuario1, Usuario2, id_publicacion, `Like`) VALUES (?, ?, ?, 1)");
        $insert_stmt->bind_param("ssi", $id_usuario, $usuario2, $id_publicacion_like);
        $insert_stmt->execute();
        echo "like"; // Indicar que se agregó un nuevo like
    }
    $stmt_check->close();
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
                echo "Follow";
            } else {
                echo "Error al realizar unfollow: " . $conexion->error;
            }
        } else {
            // Si no sigue al usuario, actualizar el seguimiento (follow)
            $query = "UPDATE follow SET follow = 1 WHERE Usuario1 = $id_sesion AND Usuario2 = $id_usuario";
            if ($conexion->query($query) === TRUE) {
                echo "Unfollow";
            } else {
                echo "Error al realizar follow: " . $conexion->error;
            }
        }
    } else {
        // Si no existe un seguimiento, crear uno (follow)
        $query = "INSERT INTO follow (Usuario1, Usuario2, follow) VALUES ($id_sesion, $id_usuario, 1)";
        if ($conexion->query($query) === TRUE) {
            echo "Unfollow";
        } else {
            echo "Error al realizar follow: " . $conexion->error;
        }
    }

    $conexion->close();
} else {
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
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Archivos de <?php echo $nombre_usuario; ?></title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body>
    <form id="follow_form">
        <input type="hidden" id="id_usuario" name="id_usuario" value="<?php echo $id_usuario; ?>">
        <input type="hidden" id="id_sesion" name="id_sesion" value="<?php echo $id_sesion; ?>">
        <input type="submit" value="<?php echo $boton_texto; ?>">
    </form>

    <h1>Archivos de <?php echo $nombre_usuario; ?></h1>
    <div id="archivos_container">
        <!-- Aquí se mostrarán los archivos -->
    </div>

    <h2>Consultar Archivos por Año y Mes</h2>
    <form id="consultar_archivos_form">
        <label for="year">Año:</label>
        <select id="year" name="year">
            <?php
            $year = date("Y");
            for ($i = $year; $i >= 2023; $i--) {
                echo "<option value='$i'>$i</option>";
            }
            ?>
        </select>
        <label for="month">Mes:</label>
        <select id="month" name="month">
            <?php
            for ($i = 1; $i <= 12; $i++) {
                $monthName = date("F", mktime(0, 0, 0, $i, 1));
                echo "<option value='$i'>$monthName</option>";
            }
            ?>
        </select>
        <input type="button" value="Consultar" onclick="consultarArchivos()">
    </form>

    <script>
        function toggleLike(id_publicacion) {
    var likeButton = $("#likeButton_" + id_publicacion);
    var likesCount = $("#likesCount_" + id_publicacion);

    $.ajax({
        type: "POST",
        url: "like_post.php",
        data: { id_publicacion_like: id_publicacion },
        success: function(response) {
            if (response === "like") {
                likeButton.text("Dislike");
                likesCount.text(parseInt(likesCount.text()) + 1 + " likes");
            } else if (response === "dislike") {
                likeButton.text("Like");
                likesCount.text(parseInt(likesCount.text()) - 1 + " likes");
            } else if (response === "already_liked") {
                alert("Ya has dado like a esta publicación.");
            }
        }
    });
}
    </script>

    <div id="archivos_consultados">
        <!-- Aquí se mostrarán los archivos consultados -->
    </div>

    <script>
        $(document).ready(function() {
            $("#follow_form").submit(function(event) {
                event.preventDefault();
                var id_usuario = $("#id_usuario").val();
                var id_sesion = $("#id_sesion").val();
                $.ajax({
                    type: "POST",
                    url: "<?php echo $_SERVER['PHP_SELF']; ?>",
                    data: { id_usuario: id_usuario, id_sesion: id_sesion },
                    success: function(response) {
                        // Actualizar el texto del botón después de seguir o dejar de seguir
                        $("#follow_form input[type='submit']").val(response.includes("Follow") ? "Follow" : "Unfollow");
                    },
                    error: function(xhr, status, error) {
                        alert("Error al procesar el seguimiento: " + error);
                    }
                });
            });
        });

        function consultarArchivos() {
            var year = document.getElementById("year").value;
            var month = document.getElementById("month").value;
            $.ajax({
                url: "consultar_archivos_usuario.php",
                type: "POST",
                data: { id_usuario: <?php echo $id_usuario; ?>, year: year, month: month , id_sesion: <?php echo $id_sesion; ?>},
                success: function(response) {
                    $("#archivos_consultados").html(response);
                },
                error: function(xhr, status, error) {
                    alert("Error al consultar archivos: " + error);
                }
            });
        }
    </script>
</body>
</html>
<?php
}
?>
