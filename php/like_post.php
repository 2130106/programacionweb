<?php
session_start();

if (!isset($_SESSION["id_usuario"])) {
    header("HTTP/1.0 403 Forbidden");
    exit("Acceso denegado. Debes iniciar sesión para dar like a una publicación.");
}

if ($_SERVER["REQUEST_METHOD"] !== "POST" || !isset($_POST["id_publicacion_like"])) {
    header("HTTP/1.0 400 Bad Request");
    exit("Solicitud inválida.");
}

$id_usuario = $_SESSION["id_usuario"];
$id_publicacion_like = $_POST["id_publicacion_like"];

// Conexión a la base de datos
$conexion = new mysqli("localhost", "root", "", "usuarios");

if ($conexion->connect_error) {
    header("HTTP/1.0 500 Internal Server Error");
    exit("Error de conexión a la base de datos: " . $conexion->connect_error);
}

// Verificar si el usuario ya ha dado like a esta publicación
$stmt_check = $conexion->prepare("SELECT id_like FROM likes WHERE id_publicacion = ? AND Usuario1 = ?");
$stmt_check->bind_param("ii", $id_publicacion_like, $id_usuario);
$stmt_check->execute();
$stmt_check->store_result();

if ($stmt_check->num_rows > 0) {
    // El usuario ya ha dado like a esta publicación, se elimina el like
    $stmt_delete = $conexion->prepare("DELETE FROM likes WHERE id_publicacion = ? AND Usuario1 = ?");
    $stmt_delete->bind_param("ii", $id_publicacion_like, $id_usuario);
    $stmt_delete->execute();
    echo "dislike";
} else {
    // El usuario no ha dado like a esta publicación, se agrega el like
    $stmt_insert = $conexion->prepare("INSERT INTO likes (Usuario1, Usuario2, id_publicacion, `Like`) VALUES (?, '', ?, 1)");
    $stmt_insert->bind_param("si", $id_usuario, $id_publicacion_like);
    $stmt_insert->execute();
    echo "like"; // Indicar que se agregó un nuevo like
}

$stmt_check->close();
$conexion->close();
?>
