<?php
include('conexion.php');
session_start();
include("header.php");

// Recuperar y escapar las variables
$nombre_mascota = $conn->real_escape_string($_POST['nombre_mascota']);
$descripcion = $conn->real_escape_string($_POST['descripcion']);
$edad = $conn->real_escape_string($_POST['edad']);
$contacto_whatsapp = $conn->real_escape_string($_POST['contacto_whatsapp']);

// Manejo de la imagen
$foto = null;
if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
    $fileInfo = getimagesize($_FILES['foto']['tmp_name']);
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];

    if (in_array($fileInfo['mime'], $allowedTypes)) {
        $foto = base64_encode(file_get_contents($_FILES['foto']['tmp_name']));
    } else {
        die("Error: Solo se permiten imágenes en formato JPEG, PNG o GIF.");
    }
}

// Construir la consulta SQL sin usuario_id
$sql = "INSERT INTO adopciones (nombre_mascota, descripcion, edad, foto, contacto_whatsapp) 
        VALUES ('$nombre_mascota', '$descripcion', '$edad', '$foto', '$contacto_whatsapp')";

if ($conn->query($sql) === TRUE) {
    // Redirigir a 'inicio.php' después de crear el registro exitosamente
    echo "Nuevo registro de adopción creado con éxito";
    header("Location: inicio.php");
    exit; // Es importante usar exit después de header para evitar que el script continúe ejecutándose
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
