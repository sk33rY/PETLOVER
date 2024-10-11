<?php

include('conexion.php');

// Obtener el nombre de la mascota desde el formulario
$nombreMascota = isset($_GET['nombre']) ? $conn->real_escape_string($_GET['nombre']) : '';

$sql = "
    SELECT m.id_mascota, m.nombre, m.descripcion, m.raza, m.tamano, m.color, m.sexo, 
    m.imagen, m.lat, m.lng, m.tipo, m.usuario_id, u.Nombre_completo AS nombre_usuario
    FROM mascotas m
    JOIN usuario u ON m.usuario_id = u.id_usuario
    WHERE m.nombre LIKE '%$nombreMascota%'
";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultados de búsqueda - <?php echo htmlspecialchars($nombreMascota); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .catalogo {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
        }
        .mascota {
            border: 1px solid #ccc;
            padding: 20px;
            width: 250px;
            text-align: center;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .mascota img {
            width: 100%;
            height: 150px;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body class="bg-light">
<div class="container mt-5">
    <h1 class="text-center mb-4">Resultados para: <?php echo htmlspecialchars($nombreMascota); ?></h1>
    <div class="catalogo">
        <?php
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo '<div class="mascota bg-white">';
                echo '<h3>' . htmlspecialchars($row["nombre"]) . '</h3>';
                echo '<p><strong>Raza:</strong> ' . htmlspecialchars($row["raza"]) . '</p>';
                echo '<p><strong>Tamaño:</strong> ' . htmlspecialchars($row["tamano"]) . '</p>';
                echo '<p><strong>Color:</strong> ' . htmlspecialchars($row["color"]) . '</p>';
                echo '<p><strong>Sexo:</strong> ' . htmlspecialchars($row["sexo"]) . '</p>';
                echo '<p><strong>Descripción:</strong> ' . htmlspecialchars($row["descripcion"]) . '</p>';
                echo '<p><strong>Estado:</strong> ' . htmlspecialchars($row["tipo"]) . '</p>';
                echo '<p><strong>Reportado por:</strong> ' . htmlspecialchars($row["nombre_usuario"]) . '</p>';
                if ($row["imagen"]) {
                    echo '<img src="data:image/jpeg;base64,' . htmlspecialchars($row["imagen"]) . '" alt="Imagen de ' . htmlspecialchars($row["nombre"]) . '">';
                } else {
                    echo '<p>Sin imagen disponible</p>';
                }

                // Botón de buscar coincidencias
                echo '<form action="buscar_coincidencias.php" method="post">';
                echo '<input type="hidden" name="id_mascota" value="' . htmlspecialchars($row["id_mascota"]) . '">';
                echo '<button type="submit" class="btn btn-primary mt-3">Buscar coincidencias</button>';
                echo '</form>';

                // Botón para iniciar el chat
                echo '<form action="chat.php" method="post">';
                echo '<input type="hidden" name="reporte_usuario_id" value="' . htmlspecialchars($row["usuario_id"]) . '">';
                echo '<input type="hidden" name="reporte_id" value="' . htmlspecialchars($row["id_mascota"]) . '">';
                echo '<button type="submit" class="btn btn-secondary mt-3">Chatear</button>';
                echo '</form>';

                echo '</div>';
            }
        } else {
            echo "<p>No se encontraron resultados para <strong>" . htmlspecialchars($nombreMascota) . "</strong>.</p>";
        }
        ?>
    </div>
</div>
</body>
</html>
<?php
$conn->close();
?>
