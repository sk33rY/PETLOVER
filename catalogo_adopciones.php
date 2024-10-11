<?php
include('conexion.php');
session_start();

// Verificar que la conexión a la base de datos esté establecida
if (!isset($conn)) {
    die("Error: No se pudo conectar a la base de datos.");
}

// Obtener todas las adopciones
$sql = "SELECT nombre_mascota, descripcion, edad, foto, contacto_whatsapp FROM adopciones";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catálogo de Adopciones</title>
    <link rel="stylesheet" href="estilos/catalogo_adopciones.css">
</head>

<body>
    <div class="catalogo-container">
        <h1>Adopta una Mascota</h1>
        <div class="adopciones-grid">
            <?php if ($result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                    <div class="adopcion-card">
                        <img src="data:image/jpeg;base64,<?php echo $row['foto']; ?>" alt="Foto de <?php echo htmlspecialchars($row['nombre_mascota']); ?>">
                        <h2><?php echo htmlspecialchars($row['nombre_mascota']); ?></h2>
                        <p><strong>Edad:</strong> <?php echo htmlspecialchars($row['edad']); ?></p>
                        <p><?php echo htmlspecialchars($row['descripcion']); ?></p>
                        <?php 
                            // Formatear número de WhatsApp
                            $whatsapp_number = preg_replace('/[^0-9]/', '', $row['contacto_whatsapp']);
                        ?>
                        <a href="https://wa.me/<?php echo htmlspecialchars($whatsapp_number); ?>" target="_blank" class="whatsapp-btn">Contactar por WhatsApp</a>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No hay mascotas disponibles para adopción en este momento.</p>
            <?php endif; ?>
        </div>
    </div>
</body>

</html>

<?php
$conn->close();
?>
