<?php
session_start();

if (!isset($_SESSION["id_usuario"])) {
    header("Location: login.php");
    exit();
}

// Conexión a la base de datos
$conexion = new mysqli("localhost", "root", "", "usuarios");

if ($conexion->connect_error) {
    die("Error de conexión a la base de datos: " . $conexion->connect_error);
}

// Obtener ID del usuario actual
$id_usuario = $_SESSION["id_usuario"];

// Procesar el formulario de comentarios
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["id_publicacion"]) && isset($_POST["comentario"])) {
    $id_publicacion = $_POST["id_publicacion"];
    $comentario = substr($_POST["comentario"], 0, 1024); // Limitar a 1024 caracteres
    $usuario1 = $_SESSION["id_usuario"];
    $usuario2 = ''; // Aquí puedes definir el usuario destinatario del comentario, si es necesario

    // Insertar comentario en la base de datos
    $stmt = $conexion->prepare("INSERT INTO comentarios (id_publicacion, Usuario1, Usuario2, comentario) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiss", $id_publicacion, $usuario1, $usuario2, $comentario);
    $stmt->execute();
    $stmt->close();
}

// Procesar el formulario de likes/dislikes
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

// Consultar publicaciones públicas de los usuarios seguidos por el usuario actual
$query = "SELECT DISTINCT archivos.id AS id_publicacion, 
                 usuarios.nombre AS nombre_usuario, 
                 archivos.ruta_archivo, 
                 archivos.fecha_upload, 
                 (SELECT COUNT(*) FROM likes WHERE likes.id_publicacion = archivos.id AND `Like` = 1) AS likes_count
          FROM archivos
          LEFT JOIN usuarios ON archivos.id_usuario = usuarios.id
          WHERE archivos.id_usuario IN (SELECT Usuario2 FROM follow WHERE Usuario1 = $id_usuario) 
          AND archivos.visibilidad = 'publico'
          ORDER BY archivos.fecha_upload DESC";

$resultado = $conexion->query($query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Inicio</title>
    <style>
        /* Colores */
        :root {
            --color1: #6F5F90; /* Fondo oscuro */
            --color2: #597e7b; /* Fondo oscuro */
            --color3: #FF7889; /* Color claro */
            --color4: #002b56; /* Color claro */
            --color5: #A5CAD2; /* Color claro */
            --color6: #FFFFFFFF;
        }

        /* Degradado de colores */
        body {
            background: linear-gradient(to bottom right, var(--color1), var(--color2));
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        /* Contenedor principal */
        .container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            height: 100vh;
        }

        /* Columna izquierda (Logo) */
        .left-column {
            flex: 1;
            padding: 20px;
            display: flex;
            justify-content: flex-end;
            align-items: center;
        }

        .logo-container img {
            max-width: 800px;
        }

        /* Columna derecha (Registro) */
        .right-column {
            flex: 1;
            padding: 30px;
            color: var(--color6); /* Color claro */
            background: linear-gradient(to bottom right, var(--color3), var(--color4)); /* Degradado para el contenedor del registro */
            border-radius: 30px; /* Añade bordes redondeados en las esquinas superior izquierda y inferior izquierda */
            box-shadow: 0px 0px 20px rgb(183, 71, 59);
            margin-left: 140px;
            margin-right: 80px;
        }

        /* Contenedor de registro */
        .registro-container {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
        }

        .card {
            width: 400px;
        }

        /* Formulario */
        form {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        input[type="text"],
        input[type="password"],
        input[type="email"],
        input[type="date"] {
            padding: 10px;
            border-radius: 5px;
            border: none;
            outline: none;
            background-color: var(--color4); /* Color claro */
        }

        input[type="button"],
        input[type="submit"] {
            padding: 10px;
            border-radius: 5px;
            border: none;
            outline: none;
            cursor: pointer;
            background-color: var(--color3); /* Color claro */
            color: white;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }

        input[type="button"]:hover,
        input[type="submit"]:hover {
            background-color: var(--color3); /* Color claro */
        }

        .registro-container {
            display: flex;
            flex-direction: column;
            align-items: center; /* Centra horizontalmente */
            text-align: center; /* Centra verticalmente */
        }

        .registro-container h2 {
            font-size: 40px; /* Cambia el tamaño del texto para los títulos (h2) */
        }

    </style>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body>
    <div class="container">
        <h1>Inicio</h1>
        <div class="dropdown">
            <button class="dropbtn">Opciones</button>
            <div class="dropdown-content">
                <a href="home.php">Perfil</a>
                <a href="../cambiar_contrasena.html">Cambiar Contraseña</a>
                <a href="modificar_datos.php">Modificar Datos Personales</a>
                <a href="cerrar_sesion.php">Cerrar Sesión</a>
            </div>
        </div><br><br>
        <?php
        if ($resultado && $resultado->num_rows > 0) {
            while ($fila = $resultado->fetch_assoc()) {
                echo "<div class='publicacion'>";
                echo "<h3>{$fila['nombre_usuario']}</h3>";
                echo "<img src='{$fila['ruta_archivo']}' alt='{$fila['nombre_usuario']}'>";
                // Calcular el tiempo transcurrido desde la fecha de carga
                $fecha_carga = strtotime($fila['fecha_upload']);
                $tiempo_transcurrido = time() - $fecha_carga;
                echo "<p>Fecha de carga: " . date("Y-m-d H:i:s", $fecha_carga) . "</p>";
                echo "<p>Tiempo transcurrido: " . formatTiempo($tiempo_transcurrido) . "</p>";
                echo "<button class='like-button' onclick='toggleLike({$fila['id_publicacion']})' id='likeButton_{$fila['id_publicacion']}'>Like</button>";
                echo "<span id='likesCount_{$fila['id_publicacion']}'>{$fila['likes_count']} likes</span>";
                echo "<h4>Comentarios:</h4>";
                echo "<div class='comentarios'>";
                // Mostrar comentarios
                $id_publicacion_actual = $fila['id_publicacion'];
                $query_comentarios = "SELECT usuarios.nombre AS nombre_usuario_comentario, comentarios.comentario
                                      FROM comentarios
                                      LEFT JOIN usuarios ON comentarios.Usuario1 = usuarios.id
                                      WHERE comentarios.id_publicacion = $id_publicacion_actual";
                $resultado_comentarios = $conexion->query($query_comentarios);
                if ($resultado_comentarios && $resultado_comentarios->num_rows > 0) {
                    while ($comentario = $resultado_comentarios->fetch_assoc()) {
                        echo "<div class='comentario'><strong>{$comentario['nombre_usuario_comentario']}</strong>: {$comentario['comentario']}</div>";
                    }
                } else {
                    echo "<p>No hay comentarios</p>";
                }
                echo "</div>";
                echo "<div class='formulario-comentario'>";
                echo "<h2>Agregar Comentario</h2>";
                echo "<form action='' method='post'>";
                echo "<input type='hidden' name='id_publicacion' value='{$fila['id_publicacion']}'>";
                echo "<label for='comentario'>Comentario (máximo 1024 caracteres):</label><br>";
                echo "<textarea id='comentario' name='comentario' rows='4' cols='50' maxlength='1024'></textarea><br>";
                echo "<input type='submit' value='Enviar Comentario'>";
                echo "</form>";
                echo "</div>";
                echo "</div>";
            }
        } else {
            echo "<p>No hay publicaciones disponibles.</p>";
        }
        ?>
    </div>
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
</body>
</html>

<?php
function formatTiempo($segundos) {
    $tiempo_formato = '';

    $dias = floor($segundos / (60 * 60 * 24));
    if ($dias > 0) {
        $tiempo_formato .= $dias . " día" . ($dias == 1 ? '' : 's') . ", ";
    }

    $horas = floor($segundos / (60 * 60)) % 24;
    if ($horas > 0) {
        $tiempo_formato .= $horas . " hora" . ($horas == 1 ? '' : 's') . ", ";
    }

    $minutos = floor($segundos / 60) % 60;
    if ($minutos > 0) {
        $tiempo_formato .= $minutos . " minuto" . ($minutos == 1 ? '' : 's') . ", ";
    }

    $segundos = $segundos % 60;
    if ($segundos > 0) {
        $tiempo_formato .= $segundos . " segundo" . ($segundos == 1 ? '' : 's');
    }

    return rtrim($tiempo_formato, ", ");
}
?>
