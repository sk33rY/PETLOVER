<?php
include('conexion.php');
include('header_login.php'); // Incluir el header_login.php


// Verificar si el usuario está logueado
if (!isset($_SESSION['correo'])) {
    header("Location: inicio.php");
    exit();
}

// Obtener el ID del usuario
$user_id = $_SESSION['id_usuario'];

// Obtener las notificaciones del usuario
$sql_notificaciones = "SELECT * FROM notificaciones WHERE user_id = ? ORDER BY fecha DESC";
$stmt_notificaciones = $conn->prepare($sql_notificaciones);
$stmt_notificaciones->bind_param("i", $user_id);
$stmt_notificaciones->execute();
$result_notificaciones = $stmt_notificaciones->get_result();

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buzón de Notificaciones</title>
    <link rel="stylesheet" href="estilos/buzon_notificaciones.css">
    <style>
        body, html {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            height: 100vh;
            margin: 0;
        }

        header {
            position: sticky;
            top: 0;
            z-index: 1000;
            background-color: white;
            width: 100%;
        }

        .notificaciones-container {
            display: flex;
            flex-direction: row;
            width: 100%;
            height: 100%;
            padding-top: 80px; /* Ajustar esto según la altura del header */
        }

        /* Lista de notificaciones (lado izquierdo en pantallas grandes) */
        .lista-notificaciones {
            width: 30%;
            background-color: #f5f5f5;
            border-right: 1px solid #ccc;
            padding: 20px;
            overflow-y: auto;
        }

        .lista-notificaciones::-webkit-scrollbar {
            width: 5px;
        }

        .lista-notificaciones::-webkit-scrollbar-thumb {
            background-color: rgba(0, 0, 0, 0.2);
            border-radius: 10px;
        }

        .notificacion-item {
            padding: 10px;
            background-color: #eee4db;
            margin-bottom: 10px;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .notificacion-item:hover {
            background-color: #d0c2bf;
        }

        .notificacion-item h4, .notificacion-item p {
            margin: 0;
            padding: 5px 0;
        }

        /* Detalles de la notificación (lado derecho) */
        .detalles-notificacion {
            width: 70%;
            background-color: #f8f9fa;
            padding: 20px;
            display: flex;
            flex-direction: column;
            overflow-y: auto;
        }

        .detalles-notificacion h1 {
            font-size: 1.5rem;
            margin-bottom: 20px;
        }

        .detalles-notificacion p {
            font-size: 1rem;
            line-height: 1.6;
        }

        .btn-chatear {
            padding: 10px 20px;
            border-radius: 20px;
            background-color: #d0c2bf;
            color: black;
            border: none;
            cursor: pointer;
            margin-top: 20px;
        }

        .btn-chatear:hover {
            background-color: #beafac;
        }

        /* Media query para pantallas pequeñas como celulares */
        @media (max-width: 768px) {
            .notificaciones-container {
                flex-direction: column;
                height: auto;
                padding-top: 100px; /* Ajustar esto para pantallas móviles */
            }

            .lista-notificaciones {
                width: 100%;
                border-right: none;
                border-bottom: 1px solid #ccc;
                height: 50vh; /* Se ajusta para que ocupe la mitad de la pantalla */
            }

            .detalles-notificacion {
                width: 100%;
                height: 50vh; /* Se ajusta para que ocupe la otra mitad de la pantalla */
                display: none; /* Inicialmente oculto en móviles */
            }

            .detalles-notificacion.active {
                display: block; /* Se muestra cuando se selecciona una notificación */
            }
        }
    </style>
</head>
<body>
    <!-- Incluir el encabezado que se maneja en header_login.php -->
    <?php include('header_login.php'); ?>

    <div class="notificaciones-container">
        <!-- Lista de notificaciones -->
        <div class="lista-notificaciones" id="lista-notificaciones">
            <h2>Mis Notificaciones</h2>
            <?php
            if ($result_notificaciones->num_rows > 0) {
                while ($row = $result_notificaciones->fetch_assoc()) {
                    echo '<div class="notificacion-item" onclick="mostrarDetalles(' . $row['reporte_usuario_id'] . ', ' . $row['reporte_coincidencia_id'] . ')">';
                    echo '<h4>Notificación</h4>';
                    echo '<p>' . htmlspecialchars($row['mensaje']) . '</p>';
                    echo '<span>Recibido el: ' . date("d/m/Y H:i", strtotime($row['fecha'])) . '</span>';
                    echo '</div>';
                }
            } else {
                echo '<p>No tienes notificaciones.</p>';
            }
            ?>
        </div>

        <!-- Detalles de la notificación -->
        <div class="detalles-notificacion" id="detalles-notificacion">
            <h1>Detalles de la Notificación</h1>
            <p>Selecciona una notificación para ver los detalles aquí.</p>
        </div>
    </div>

    <script>
        function mostrarDetalles(reporte_usuario_id, reporte_coincidencia_id) {
            const detallesDiv = document.getElementById('detalles-notificacion');

            // En dispositivos móviles, mostramos los detalles ocultos
            detallesDiv.classList.add('active');

            // Simular una petición AJAX para obtener los detalles de la notificación
            const xhr = new XMLHttpRequest();
            xhr.open('GET', 'ver_coincidencia.php?reporte1=' + reporte_usuario_id + '&reporte2=' + reporte_coincidencia_id, true);
            xhr.onload = function () {
                if (this.status === 200) {
                    detallesDiv.innerHTML = this.responseText;
                }
            };
            xhr.send();
        }
    </script>
</body>
</html>
<?php
$stmt_notificaciones->close();
$conn->close();
?>
