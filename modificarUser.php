<?php
include("conexion.php");
session_start();

// Verificar si la sesión tiene un correo
if (!isset($_SESSION['correo'])) {
    header("location: iniciose.html");
    exit;
}

$user = $_SESSION['correo'];

// Verificar si el formulario ha sido enviado
if (isset($_POST['submit'])) {
    // Obtener los nuevos datos desde el formulario
    $nombre_completo = $_POST['Nombres'];
    $correo = $_POST['correo'];
    $numero_telefono = $_POST['numero_telefono'];
    $fecha_nacimiento = $_POST['fecha_nacimiento'];
    $direccion_residencia = $_POST['direccion_residencia'];

    // Preparar la consulta de actualización
    $sql = "UPDATE usuario SET Nombre_completo=?, correo=?, numero_telefono=?, fecha_nacimiento=?, direccion_residencia=? WHERE correo=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssss", $nombre_completo, $correo, $numero_telefono, $fecha_nacimiento, $direccion_residencia, $user);

    // Ejecutar la consulta
    if ($stmt->execute()) {
        // Actualizar la sesión con el nuevo correo si fue cambiado
        $_SESSION['correo'] = $correo;
        
        // Redirigir directamente al perfil del usuario con un mensaje de éxito
        header("Location: perfil.php?update=success");
        exit;
    } else {
        // Si hay algún error, mostrar un mensaje de error
        echo "Error al actualizar los datos.";
    }

    $stmt->close();
    $conn->close();
}
?>
