<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Inicio</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #b8ffc7;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .dropdown {
            float: right;
            margin-right: 20px;
        }

        .dropdown button {
            background-color: #4CAF50; /* Verde */
            color: #fff;
            border: none;
            padding: 15px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
        }

        .dropdown button:hover {
            background-color: #45a049; /* Verde oscuro al pasar el mouse */
        }

        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #4CAF50; /* Verde */
            min-width: 160px;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            z-index: 1;
        }

        .dropdown:hover .dropdown-content {
            display: block;
        }

        .dropdown-content a {
            color: #fff;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
        }

        .dropdown-content a:hover {
            background-color: #45a049; /* Verde oscuro al pasar el mouse */
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
            background-color: #d4edda; /* Verde claro */
        }

        .grid-item img {
            width: 250px;
            height: 300px;
            object-fit: cover;
            border-radius: 0px;
            margin-bottom: 10px;
        }

        .delete-icon {
            position: absolute;
            top: 1px;
            right: 1px;
            cursor: pointer;
            font-size: 44px;
            color: #dc3545; /* Rojo */
            z-index: 1;
        }

        .delete-icon:hover {
            color: #c82333; /* Rojo oscuro al pasar el mouse */
        }

        ul {
            list-style-type: none;
            padding-left: 0;
        }

        li {
            margin-bottom: 10px;
        }

        input[type="file"],
        input[type="button"],
        input[type="submit"] {
            background-color: #4CAF50; /* Verde */
            color: #fff;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            margin-bottom: 10px;
            display: block;
        }

        input[type="file"]:focus,
        input[type="button"]:focus,
        input[type="submit"]:focus {
            outline: none;
        }

        input[type="button"]:hover,
        input[type="submit"]:hover {
            background-color: #45a049; /* Verde oscuro al pasar el mouse */
        }
    </style>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body>
    <?php
    session_start();

    // Verificar si el usuario está autenticado
    if (!isset($_SESSION["id_usuario"])) {
        header("Location: ../index.html");
        exit();
    }

    // Conectar a la base de datos
    $conexion = new mysqli("localhost", "root", "", "usuarios");

    // Verificar la conexión
    if ($conexion->connect_error) {
        die("Error al conectar a la base de datos: " . $conexion->connect_error);
    }

    // Obtener el ID del usuario actual
    $id_usuario = $_SESSION["id_usuario"];

    // Consultar el nombre del usuario actual
    $sql_nombre_usuario = "SELECT nombre FROM usuarios WHERE id = $id_usuario";
    $resultado_nombre_usuario = $conexion->query($sql_nombre_usuario);
    $fila_nombre_usuario = $resultado_nombre_usuario->fetch_assoc();
    $nombre_usuario = $fila_nombre_usuario["nombre"];

    // Consultar el número de seguidores
    $sql_seguidores = "SELECT COUNT(*) AS total_seguidores FROM follow WHERE Usuario2 = $id_usuario";
    $resultado_seguidores = $conexion->query($sql_seguidores);
    $fila_seguidores = $resultado_seguidores->fetch_assoc();
    $total_seguidores = $fila_seguidores["total_seguidores"];

    // Consultar el número de usuarios seguidos
    $sql_siguiendo = "SELECT COUNT(*) AS total_siguiendo FROM follow WHERE Usuario1 = $id_usuario";
    $resultado_siguiendo = $conexion->query($sql_siguiendo);
    $fila_siguiendo = $resultado_siguiendo->fetch_assoc();
    $total_siguiendo = $fila_siguiendo["total_siguiendo"];

    // Cerrar la conexión a la base de datos
    $conexion->close();
    ?>

    <div class="container">
        <div class="dropdown">
            <button class="dropbtn">Opciones</button>
            <div class="dropdown-content">
                <a href="homepage.php">Home</a>
                <a href="../cambiar_contraseña.html">Cambiar Contraseña</a>
                <a href="modificar_datos.php">Modificar Datos Personales</a>
                <a href="cerrar_sesion.php">Cerrar Sesión</a>
            </div>
        </div><br><br>

        <h2>Perfil de <?php echo $nombre_usuario; ?></h2>
        <h2>Estadísticas de Seguidores</h2>
        <p>Total de Seguidores: <?php echo $total_seguidores; ?></p>
        <h2>Estadísticas de Seguidos</h2>
        <p>Total de Seguidos: <?php echo $total_siguiendo; ?></p>

        <h2>Subir Archivos</h2>
        <form id="subir_archivo_form" enctype="multipart/form-data">
            <input type="file" id="archivo" name="archivo" required><br><br>
            <input type="button" value="Subir Archivo" onclick="subirArchivo()">
        </form>

        <h2>Archivos Subidos</h2>
        <ul class="grid-container">
            <?php
            // Obtener el ID del usuario actual
            $id_usuario = $_SESSION["id_usuario"];

            // Consulta SQL para obtener los archivos del usuario actual
            $conexion = new mysqli("localhost", "root", "", "usuarios");
            $sql = "SELECT id, nombre_archivo, ruta_archivo, visibilidad FROM archivos WHERE id_usuario = $id_usuario";
            $resultado = $conexion->query($sql);

            // Mostrar los archivos del usuario actual en la cuadrícula
            while ($fila = $resultado->fetch_assoc()) {
                $id_archivo = $fila["id"];
                $nombre_archivo = $fila["nombre_archivo"];
                $ruta_archivo = $fila["ruta_archivo"];
                $visibilidad = $fila["visibilidad"];
                echo "<li class='grid-item'>";
                echo "<a href='$ruta_archivo'><img src='$ruta_archivo' alt='$nombre_archivo'></a>";
                echo "<span class='delete-icon' onclick='borrarArchivo($id_archivo)'>&#128465;</span>"; // Icono de papelera Unicode
                echo "<select onchange='cambiarVisibilidad($id_archivo, this.value)'>";
                echo "<option value='privado' " . ($visibilidad == 'privado' ? 'selected' : '') . ">Privado</option>";
                echo "<option value='publico' " . ($visibilidad == 'publico' ? 'selected' : '') . ">Público</option>";
                echo "</select>";
                echo "</li>";
            }

            // Cerrar la conexión a la base de datos
            $conexion->close();
            ?>
        </ul>
        <h2>Consultar Archivos por Año y Mes</h2>
        <form id="consultar_archivos_form">
            <label for="year">Año:</label>
            <select id="year" name="year">
                <?php
                // Obtener el año actual
                $year = date("Y");
                // Generar opciones para años desde el año actual hasta 2023
                for ($i = $year; $i >= 2023; $i--) {
                    echo "<option value='$i'>$i</option>";
                }
                ?>
            </select>
            <label for="month">Mes:</label>
            <select id="month" name="month">
                <?php
                // Generar opciones para los meses del año
                for ($i = 1; $i <= 12; $i++) {
                    $monthName = date("F", mktime(0, 0, 0, $i, 1));
                    echo "<option value='$i'>$monthName</option>";
                }
                ?>
            </select>
            <input type="button" value="Consultar" onclick="consultarArchivos()">
        </form>
        <div id="archivos_container">
            <!-- Aquí se mostrarán los archivos consultados -->
        </div>

        <form id="buscar_usuario_form">
            <label for="parametro_busqueda">Buscar usuario por nombre o username:</label>
            <input type="text" id="parametro_busqueda" name="parametro_busqueda" required>
            <?php
            $id_usuario = $_SESSION["id_usuario"];
            // Agregar el botón de búsqueda con el ID del usuario como parámetro
            echo '<input type="button" value="Buscar" onclick="buscarUsuario(parametro_busqueda)">';
            ?>
        </form>
        <div id="usuarios_encontrados">

        </div>

    </div>

    <script>
        function buscarUsuario() {
            var parametro = document.getElementById("parametro_busqueda").value;

            $.ajax({
                url: "buscar_usuario.php",
                type: "POST",
                data: { parametro: parametro , id_usuario: <?php echo $id_usuario; ?>},
                success: function(response) {
                    $("#usuarios_encontrados").html(response); // Actualizar la lista de usuarios encontrados
                },
                error: function(xhr, status, error) {
                    alert("Error al buscar usuarios: " + error);
                }
            });
        }

        function consultarArchivos() {
            var year = document.getElementById("year").value;
            var month = document.getElementById("month").value;

            $.ajax({
                url: "consultar_archivos.php",
                type: "POST",
                data: { year: year, month: month },
                success: function(response) {
                    $("#archivos_container").html(response); // Actualiza el contenedor de archivos
                },
                error: function(xhr, status, error) {
                    alert("Error al consultar archivos: " + error);
                }
            });
        }

        function borrarArchivo(id) {
            if (confirm("¿Estás seguro de borrar este archivo?")) {
                // Realizar la petición AJAX para borrar el archivo
                $.ajax({
                    type: "POST",
                    url: "borrar_archivo.php",
                    data: { id: id },
                    success: function(response) {
                        alert(response);
                        location.reload(); // Recargar la página después de borrar el archivo
                    }
                });
            }
        }

        function subirArchivo() {
            var formData = new FormData($("#subir_archivo_form")[0]);

            $.ajax({
                url: "subir_archivo.php",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    alert(response); // Muestra la respuesta del servidor en una alerta
                    location.reload(); // Recargar la página después de subir el archivo
                },
                error: function(xhr, status, error) {
                    alert("Error al subir el archivo: " + error); // Muestra un mensaje de error en caso de fallo
                }
            });
        }

        function cambiarVisibilidad(id, visibilidad) {
            if (confirm("¿Estás seguro de cambiar la visibilidad de este archivo?")) {
                // Realizar la petición AJAX
                $.ajax({
                    type: "POST",
                    url: "cambiar_visibilidad.php",
                    data: { id: id, visibilidad: visibilidad },
                });
            }
        }
    </script>
</body>
</html>
