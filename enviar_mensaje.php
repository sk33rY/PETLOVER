<?php
include('conexion.php');
include('config_cifrado.php');
session_start();

if (!isset($_SESSION['correo'])) {
    header("Location: iniciose.php");
    exit;
}

$mensaje_original = $_POST['mensaje'];
$receptor_id = $_POST['receptor_id'];
$reporte_id = $_POST['reporte_id']; // Añadido para registrar el ID del reporte

// Obtener el id del usuario de la sesión actual
$user = $_SESSION['correo'];
$sqlUser = "SELECT id_usuario FROM usuario WHERE correo='$user'";
$resultUser = $conn->query($sqlUser);

if ($resultUser->num_rows > 0) {
    $rowUser = $resultUser->fetch_assoc();
    $emisor_id = $rowUser['id_usuario'];

    // Cifrar el mensaje
    $clave = CLAVE_CIFRADO;
    $iv = random_bytes(openssl_cipher_iv_length('AES-256-CBC'));
    $mensaje_cifrado = openssl_encrypt($mensaje_original, 'AES-256-CBC', $clave, 0, $iv);
    $iv_hex = bin2hex($iv);  // Convertimos el IV a hexadecimal para almacenarlo

    // Insertar el mensaje cifrado en la base de datos con el ID del reporte
    $sqlInsert = "INSERT INTO mensajes (emisor_id, receptor_id, mensaje, iv, fecha, reporte_id) VALUES (?, ?, ?, ?, NOW(), ?)";
    $stmtInsert = $conn->prepare($sqlInsert);
    $stmtInsert->bind_param("iissi", $emisor_id, $receptor_id, $mensaje_cifrado, $iv_hex, $reporte_id);
    
    if ($stmtInsert->execute()) {
        header("Location: chat.php?usuario_id=$emisor_id&reporte_usuario_id=$receptor_id&reporte_id=$reporte_id");
        exit;
    } else {
        die("Error al enviar el mensaje: " . $conn->error);
    }
} else {
    die("Error: Usuario no encontrado.");
}
?>
