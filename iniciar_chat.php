<?php
include('conexion.php');
session_start();

if (!isset($_SESSION['correo'])) {
    header("Location: IniciarSesion.php");
    exit;
}

$reporte_usuario_id = $_POST['reporte_usuario_id'];
$reporte_id = $_POST['reporte_id'];

// Obtener el id del usuario de la sesión actual
$user = $_SESSION['correo'];
$sqlUser = "SELECT id_usuario FROM usuario WHERE correo='$user'";
$resultUser = $conn->query($sqlUser);

if ($resultUser->num_rows > 0) {
    $rowUser = $resultUser->fetch_assoc();
    $usuario_id = $rowUser['id_usuario'];

    // Redirigir al chat con el reporte_usuario_id
    header("Location: chat.php?usuario_id=$usuario_id&reporte_usuario_id=$reporte_usuario_id&reporte_id=$reporte_id");
    exit;
} else {
    die("Error: Usuario no encontrado.");
}
?>
