<?php
include("conexion.php");
session_start();

// Verificar si el usuario está logueado
if (!isset($_SESSION['id_usuario'])) {
    header('Location: login.php');
    exit;
}

if (isset($_POST['id_mascota']) && isset($_POST['motivo'])) {
    $id_mascota = $_POST['id_mascota'];
    $motivo = $_POST['motivo'];

    // Actualizar el estado del reporte a deshabilitado
    $sql = "UPDATE mascotas SET is_active = 0 WHERE id_mascota = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_mascota);
    $stmt->execute();

    // Guardar el motivo en la tabla de motivos o similar
    $sql_motivo = "INSERT INTO deshabilitaciones (id_mascota, motivo) VALUES (?, ?)";
    $stmt_motivo = $conn->prepare($sql_motivo);
    $stmt_motivo->bind_param("is", $id_mascota, $motivo);
    $stmt_motivo->execute();

    // Redirigir a "Mis reportes" o mostrar un mensaje de éxito
    header("Location: mis_reportes.php?deshabilitado=success");
    exit;
} else {
    echo "Error: Faltan datos.";
}
?>
