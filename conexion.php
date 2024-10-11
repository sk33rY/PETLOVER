<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mydb";

// Crear la conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Definir el charset para evitar problemas con acentos y caracteres especiales
$conn->set_charset("utf8");
?>