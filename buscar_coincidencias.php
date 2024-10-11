<?php
include('conexion.php');
include_once('python_integration.php'); // Asegúrate de incluir el archivo para manejar la integración con Python

// Obtener el ID del reporte desde el formulario
$id = $_POST['id_mascota'];

// Obtener los detalles del reporte actual usando el ID
$sql_reporte = "SELECT * FROM mascotas WHERE id_mascota = ?";
$stmt_reporte = $conn->prepare($sql_reporte);
$stmt_reporte->bind_param("i", $id);
$stmt_reporte->execute();
$result_reporte = $stmt_reporte->get_result();

if ($result_reporte->num_rows > 0) {
    $reporte = $result_reporte->fetch_assoc();

    // Definir las variables necesarias a partir del reporte actual
    $lat = $reporte['lat'];
    $lng = $reporte['lng'];
    $tamano = $reporte['tamano'];
    $color = $reporte['color'];
    $tipo = $reporte['tipo'];
    $nombre = $reporte['nombre'];
    $descripcion = $reporte['descripcion'];

    // Determinar el tipo opuesto
    $tipoOpuesto = ($tipo == 'perdido') ? 'encontrado' : 'perdido';

    // Consulta para obtener todos los reportes del tipo opuesto
    $sql = "SELECT * FROM mascotas WHERE id_mascota != ? AND tipo = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $id, $tipoOpuesto);
    $stmt->execute();
    $result = $stmt->get_result();

    $reportes_encontrados = [];

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $reportes_encontrados[] = $row;
        }

        // Llamar a la función de Python para calcular las coincidencias
        $resultados = calcular_similitudes_python($reporte, $reportes_encontrados);

        if (is_array($resultados)) {
            usort($resultados, function ($a, $b) {
                return $b['similaridad'] <=> $a['similaridad'];
            });

            // Mostrar resultados ordenados
            ?>
            <!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buscar Coincidencias</title>
    <link rel="stylesheet" href="estilos/catalogo2.css">
    <style>
        /* Adaptaciones específicas para Buscar Coincidencias */
        .catalogo {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
            margin-top: 10px;
        }
        .mascota {
            border: 1px solid #ccc;
            padding: 20px;
            width: 250px;
            text-align: center;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            background-color: #fff;
            position: relative;
        }
        .mascota h3 {
            font-size: 1.5em;
            margin: 0;
            padding: 10px 0;
            color: rgb(218, 127, 61);
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
        }
        .mascota img {
            width: 100%;
            height: 150px;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 10px;
        }
        .mascota p {
            margin: 10px 0;
            color: #333;
        }
        .coincidencias {
            padding: 15px;
            background-color: #f1f1f1;
            border-top: 1px solid #ddd;
            border-bottom-left-radius: 8px;
            border-bottom-right-radius: 8px;
            margin-top: 10px;
        }
        .coincidencias p {
            margin: 5px 0;
            font-size: 1em;
            color: #555;
        }
        .coincidencias p span {
            font-weight: bold;
        }
        .similaridad-total {
            font-size: 1.2em;
            margin: 15px 0;
            text-align: center;
            color: #007bff;
            font-weight: bold;
        }
        .coincidencias h4 {
            font-size: 1.05em;
            text-align: center;
            color: #555;
            font-weight: bold;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            font-size: 1em;
            font-weight: bold;
            text-align: center;
            text-decoration: none;
            color: #fff;
            background-color: #007bff;
            border-radius: 5px;
            transition: background-color 0.3s ease;
            margin-top: 10px;
        }
        .btn:hover {
            background-color: #0056b3;
        }
        .titulo-principal {
            text-align: center;
            margin-top: 20px;
            font-size: 1.5em;
            color: #333;
        }
    </style>
</head>
<body>
    <main>
        <section class="seccion-1">
            <div class="texto">
                <h1 class="titulo-principal">Reporte de coincidencias</h1>
                <div class="catalogo">
            <!-- Aquí va el código PHP para mostrar los resultados de coincidencias -->
            <?php
            // Este es un ejemplo de cómo mostrar cada resultado
            foreach ($resultados as $resultado) {
                $row = $resultado['data'];
                echo '<div class="mascota">';
                echo '<h3>' . htmlspecialchars($row["nombre"]) . '</h3>';
                if ($row["imagen"]) {
                    echo '<img src="data:image/jpeg;base64,' . htmlspecialchars($row["imagen"]) . '" alt="Imagen de ' . htmlspecialchars($row["nombre"]) . '">';
                } else {
                    echo '<img src="https://via.placeholder.com/300x200" alt="Sin imagen disponible">';
                }
                echo '<p><strong>Raza:</strong> ' . htmlspecialchars($row["raza"]) . '</p>';
                echo '<p><strong>Tamaño:</strong> ' . htmlspecialchars($row["tamano"]) . '</p>';
                echo '<p><strong>Color:</strong> ' . htmlspecialchars($row["color"]) . '</p>';
                echo '<p><strong>Descripción:</strong> ' . htmlspecialchars($row["descripcion"]) . '</p>';
                echo '<p><strong>Estado:</strong> ' . htmlspecialchars($row["tipo"]) . '</p>';

                echo '<div class="coincidencias">';
                echo '<h4> Resultados </h4>';
                foreach ($resultado['coincidencias'] as $coincidencia) {
                    echo '<p>' . htmlspecialchars($coincidencia) . '</p>';
                }
                echo '</div>';
                
                echo '<p class="similaridad-total">Similaridad Total: ' . round($resultado['similaridad'], 2) . '%</p>';
    
                echo '</div>';
            
            }
            ?>

</div>
            </div>
        </section>
    </main>
     
</body>
</html>

            <?php
        } else {
            echo '<p>No se encontraron coincidencias o hubo un error en el procesamiento.</p>';
        }
    } else {
        echo '<p>No se encontraron coincidencias.</p>';
    }

    $stmt->close();
} else {
    echo '<p>Reporte no encontrado.</p>';
}

$stmt_reporte->close();
$conn->close();
?>