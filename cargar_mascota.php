<?php
include('conexion.php');

header('Content-Type: application/json');

$sql = "SELECT nombre, descripcion, raza, tamano, color, sexo, tipo_animal, imagen, lat, lng, tipo FROM mascotas";
$result = $conn->query($sql);

$mascotas = [];

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        // Decodificar la imagen en base64 si existe
        $row['imagen'] = !empty($row['imagen']) ? 'data:image/jpeg;base64,' . $row['imagen'] : null;
        $mascotas[] = $row;
    }
} else {
    echo json_encode(['error' => 'No se encontraron mascotas']);
    exit;
}

echo json_encode($mascotas);

$conn->close();
?>
