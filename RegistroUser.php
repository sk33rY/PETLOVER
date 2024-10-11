<?php
// Mostrar errores de PHP
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$nombres = $_POST['Nombres'] ?? '';
$correo = $_POST['correo'] ?? '';
$telefono = $_POST['telefono'] ?? '';
$fecha_nacimiento = $_POST['fecha_nacimiento'] ?? '';
$direccion_residencia = $_POST['direccion_residencia'] ?? '';
$password = $_POST['password'] ?? '';
$repassword = $_POST['rePassword'] ?? '';

if (empty($nombres) || empty($correo) || empty($telefono) || empty($fecha_nacimiento) || empty($direccion_residencia) || empty($password) || empty($repassword)) {
    echo json_encode(['status' => 'error', 'message' => 'Por favor, rellene todos los campos requeridos.']);
    exit;
}

if ($password !== $repassword) {
    echo json_encode(['status' => 'error', 'message' => 'Por favor, introduzca dos contraseñas idénticas.']);
    exit;
}

$mysql = new mysqli("localhost", "root", "", "mydb");

if ($mysql->connect_error) {
    echo json_encode(['status' => 'error', 'message' => 'Error de conexión a la base de datos.']);
    exit;
}

$password_hashed = md5($password);
$nombres = mysqli_real_escape_string($mysql, $nombres);
$telefono = mysqli_real_escape_string($mysql, $telefono);
$correo = mysqli_real_escape_string($mysql, $correo);
$fecha_nacimiento = mysqli_real_escape_string($mysql, $fecha_nacimiento);
$direccion_residencia = mysqli_real_escape_string($mysql, $direccion_residencia);
$password_hashed = mysqli_real_escape_string($mysql, $password_hashed);

$query = "INSERT INTO usuario (Nombre_completo, numero_telefono, correo, fecha_nacimiento, direccion_residencia, contrasenia) VALUES ('$nombres', '$telefono', '$correo', '$fecha_nacimiento', '$direccion_residencia', '$password_hashed')";

if ($mysql->query($query) === TRUE) {
    // Redirigir a la misma página y mostrar el formulario de inicio de sesión
    header('Location: iniciose.html?login=true');
    exit;
} else {
    echo json_encode(['status' => 'error', 'message' => 'Error creando usuario: ' . $mysql->error]);
}

$mysql->close();
?>
