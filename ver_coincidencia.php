<?php
include("conexion.php");
session_start();

if (!isset($_SESSION['id_usuario'])) {
    header("location: inicio.php");
    exit();
}

$usuario_actual_id = $_SESSION['id_usuario'];

// Obtener los IDs de los reportes
$reporte_usuario_id = $_GET['reporte1'];  // El ID del reporte del usuario actual
$reporte_otro_id = $_GET['reporte2'];     // El ID del reporte con el que hay coincidencia

// Obtener la información del reporte del usuario actual
$sql_usuario = "SELECT m.id_mascota, m.nombre, m.descripcion, m.raza, m.tamano, m.color, m.sexo, 
                m.imagen, m.tipo, m.usuario_id, u.Nombre_completo AS nombre_usuario
                FROM mascotas m
                JOIN usuario u ON m.usuario_id = u.id_usuario
                WHERE m.id_mascota = ?";
$stmt_usuario = $conn->prepare($sql_usuario);
$stmt_usuario->bind_param("i", $reporte_usuario_id);
$stmt_usuario->execute();
$reporte_usuario = $stmt_usuario->get_result()->fetch_assoc();

// Obtener la información del reporte del otro usuario
$sql_otro = "SELECT m.id_mascota, m.nombre, m.descripcion, m.raza, m.tamano, m.color, m.sexo, 
             m.imagen, m.tipo, m.usuario_id, u.Nombre_completo AS nombre_usuario
             FROM mascotas m
             JOIN usuario u ON m.usuario_id = u.id_usuario
             WHERE m.id_mascota = ?";
$stmt_otro = $conn->prepare($sql_otro);
$stmt_otro->bind_param("i", $reporte_otro_id);
$stmt_otro->execute();
$reporte_otro = $stmt_otro->get_result()->fetch_assoc();

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coincidencia de Reportes</title>
    <link rel="stylesheet" href="estilos/catalogo2.css">
    <style>
        body, html {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
        }

        .coincidencia-container {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            margin: 20px;
            gap: 0px; /* Menos espacio entre las tarjetas */
        }

        .reporte-card {
            width: 100%;
            max-width: 400px;
            border: 1px solid #ddd;
            border-radius: 10px;
            overflow: hidden;
            background-color: #f9f9f9;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        }

        .reporte-header {
            position: relative;
        }

        .reporte-header img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .reporte-body {
            padding: 15px;
        }

        .reporte-body h3 {
            margin-top: 0;
            font-size: 1.4rem;
            color: #333;
        }

        .reporte-body p {
            margin: 5px 0;
            font-size: 1rem;
        }

        .reporte-body .badge {
            background-color: #ebc086;
            color: white;
            padding: 5px 5px;
            border-radius: 10px;
            font-size: 1.05rem;
        }

        .reporte-card.usuario-actual .reporte-body h3 {
            color: #f6d5c0; /* Color enfático para tu reporte */
        }

        .reporte-card.otro-usuario .reporte-body h3 {
            color: gray; /* Color enfático para el reporte del otro usuario */
        }

        .btn-chatear {
            background-color: #e5a50a;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 20px;
            cursor: pointer;
            margin-top: 30px;
            text-align: center;
            font-weight: bold; /* Negrilla para el texto */
            display: block;
        }

        .btn-chatear:hover {
            background-color: #eee4db;
            color: #333;
        }

        @media (max-width: 768px) {
            .coincidencia-container {
                flex-direction: column;
                align-items: center;
            }

            .reporte-card {
                width: 100%;
                max-width: 90%;
            }
        }
    </style>
</head>
<body>
<main>
    <section class="coincidencia-container">
        <!-- Reporte del Usuario Actual -->
        <div class="reporte-card usuario-actual">
        <div class="reporte-body">
        <p><span class="badge"><?php echo htmlspecialchars($reporte_usuario['tipo']); ?></span></p>
        </div>

            <div class="reporte-header">
                <img src="data:image/jpeg;base64,<?php echo htmlspecialchars($reporte_usuario['imagen']); ?>" alt="Imagen de <?php echo htmlspecialchars($reporte_usuario['nombre']); ?>">
            </div>
            <div class="reporte-body">
                <h3>Tu reporte: <?php echo htmlspecialchars($reporte_usuario['nombre']); ?></h3>
                <p><strong>Raza:</strong> <?php echo htmlspecialchars($reporte_usuario['raza']); ?></p>
                <p><strong>Tamaño:</strong> <?php echo htmlspecialchars($reporte_usuario['tamano']); ?></p>
                <p><strong>Sexo:</strong> <?php echo htmlspecialchars($reporte_usuario['sexo']); ?></p>
                <p><strong>Color:</strong> <?php echo htmlspecialchars($reporte_usuario['color']); ?></p>
                <p><strong>Descripción:</strong> <?php echo htmlspecialchars($reporte_usuario['descripcion']); ?></p>
            </div>
        </div>

        <!-- Reporte del Otro Usuario -->
        <div class="reporte-card otro-usuario">
        <div class="reporte-body">
        <p><span class="badge"><?php echo htmlspecialchars($reporte_otro['tipo']); ?></span></p>
        </div>
            <div class="reporte-header">
                <img src="data:image/jpeg;base64,<?php echo htmlspecialchars($reporte_otro['imagen']); ?>" alt="Imagen de <?php echo htmlspecialchars($reporte_otro['nombre']); ?>">
            </div>
            <div class="reporte-body">
                <h3>Reporte coincidente: <?php echo htmlspecialchars($reporte_otro['nombre']); ?></h3>
                <p><strong>Raza:</strong> <?php echo htmlspecialchars($reporte_otro['raza']); ?></p>
                <p><strong>Tamaño:</strong> <?php echo htmlspecialchars($reporte_otro['tamano']); ?></p>
                <p><strong>Sexo:</strong> <?php echo htmlspecialchars($reporte_usuario['sexo']); ?></p>
                <p><strong>Color:</strong> <?php echo htmlspecialchars($reporte_otro['color']); ?></p>
                <p><strong>Descripción:</strong> <?php echo htmlspecialchars($reporte_otro['descripcion']); ?></p>
                <p><strong>Reportado por:</strong><?php echo htmlspecialchars($reporte_otro['nombre_usuario']); ?></p>

                <!-- Botón para chatear -->
                <form action="iniciar_chat.php" method="post">
                    <input type="hidden" name="reporte_usuario_id" value="<?php echo htmlspecialchars($reporte_otro['usuario_id']); ?>">
                    <input type="hidden" name="reporte_id" value="<?php echo htmlspecialchars($reporte_otro['id_mascota']); ?>">
                    <button type="submit" class="btn-chatear">Chatear con el dueño</button>
                </form>
            </div>
        </div>
    </section>
</main>
</body>
</html>

<?php
$stmt_usuario->close();
$stmt_otro->close();
$conn->close();
?>