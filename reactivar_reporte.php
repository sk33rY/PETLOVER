<?php
include("conexion.php");
session_start();

// Verificar si el usuario está logueado
if (!isset($_SESSION['id_usuario'])) {
    header('Location: login.php');
    exit;
}

// Verificar si se envió el ID del reporte
if (isset($_POST['id_mascota'])) {
    $id_mascota = $_POST['id_mascota'];

    // Reactivar el reporte (poner is_active en 1)
    $sql_update = "UPDATE mascotas SET is_active = 1 WHERE id_mascota = ?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param("i", $id_mascota);
    $stmt_update->execute();

    // Redirigir a la página de "Mis reportes"
    header("Location: mis_reportes.php");
    exit;
} else {
    echo "Error: No se ha especificado un reporte.";
}
?>
