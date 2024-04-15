<?php
// Validar si el formulario fue enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Conectar a la base de datos
    $conexion = new mysqli("localhost", "root", "", "usuarios");

    // Verificar la conexión
    if ($conexion->connect_error) {
        die("Error al conectar a la base de datos: " . $conexion->connect_error);
    }

    // Obtener los datos del formulario
    $login = $_POST["username"];  // Este puede ser un correo electrónico o un nombre de usuario
    $password = $_POST["password"];

    // Obtener la contraseña cifrada de la base de datos
    $sql = "SELECT id, password, salt FROM usuarios WHERE username = ? OR correo = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("ss", $login, $login);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        $fila = $resultado->fetch_assoc();
        $password_cifrada = hash("sha512", $password . $fila["salt"]);

        // Verificar que la contraseña coincida con la almacenada en la base de datos
        if ($password_cifrada == $fila["password"]) {
            // Iniciar sesión (ejemplo básico)
            session_start();
            $_SESSION["id_usuario"] = $fila["id"];
            header("Location: home.php");
        } else {
            echo "Contraseña incorrecta.";
        }
    } else {
        echo "Usuario no encontrado.";
    }

    // Cerrar la conexión a la base de datos
    $stmt->close();
    $conexion->close();
}
?>
