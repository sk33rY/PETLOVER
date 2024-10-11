<?php
include("conexion.php");
session_start();

// Verificar si el usuario está logueado
if (!isset($_SESSION['id_usuario'])) {
    header('Location: login.php');
    exit;
}

include("header_login.php");

// Obtener el ID del usuario actual
$id_usuario = $_SESSION['id_usuario'];

// Consulta para obtener solo los reportes del usuario actual
$sql = "SELECT m.id_mascota, m.nombre, m.descripcion, m.raza, m.tamano, m.color, m.sexo, 
        m.imagen, m.lat, m.lng, m.tipo, m.is_active
        FROM mascotas m
        WHERE m.usuario_id = ? 
        ORDER BY m.fecha_registro DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$result = $stmt->get_result();
?>

<style>
/* General container */
.catalogo-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 20px;

}


/* Title styling */
.titulo-reportes {
    font-size: 1.5rem;
    text-align: center;
    margin-bottom: 20px;
}

/* Card container */
.catalogo {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    width: 100%;
    max-width: 1200px;
    justify-items: center;
}

/* Card styling */
.card {
    background-color: #fff;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    text-align: center;
    width: 100%;
    max-width: 300px;
}
.card-header {
    position: relative;
    width: 100%;
}


.card-header img {
    width: 100%;
    height: auto;
    border-top-left-radius: 10px;
    border-top-right-radius: 10px;
}

.icon-btn {
    position: absolute;
    top: 10px;
    left: 10px;
}

.icon-btn {
    position: absolute;
    top: 10px;
    left: 10px;
}

.card-body {
    padding: 15px;
}

.card-body h3 {
    font-size: 1.5rem;
    margin-bottom: 10px;
}

.card-body .age {
    color: #666;
    font-size: 1rem;
    margin-bottom: 15px;
}

/* Button styling */
button {
    display: inline-block;
    background-color: #eee4db;
    color: black;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    font-size: 1rem;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

button:hover {
    background-color: #da7f3d;
}
.confirm-container select {
    display: block;
    width: 100%;
    padding: 5px;
    margin: 10px 0;
    border-radius: 5px;
    border: 1px solid #ddd;
    background-color: #fff;
}

.confirm-container label {
    font-weight: bold;
    margin-top: 10px;
}
/* Confirm container hidden by default */
.confirm-container {
    display: none;
    background-color: #f8f9fa;
    padding: 10px;
    border-radius: 5px;
    margin-top: 10px;
}

/* Show the confirm container when toggled */
.confirm-container.show {
    display: block;
}

.confirm-container .btn-danger, 
.confirm-container .btn-secondary {
    font-size: 0.85rem; /* Disminuye el tamaño de la fuente */
    padding: 5px 10px;  /* Reduce el relleno (padding) */
    border-radius: 5px; /* Mantén el borde redondeado */
    margin: 5px 5px 0 5px; /* Ajusta el margen entre los botones */
    min-width: 100px; /* Ancho mínimo para que se mantenga legible */
}

.confirm-container .btn-danger {
    background-color: #e07c3c;
    color: white;
    border: none;
}

.confirm-container .btn-secondary {
    background-color: #6c757d;
    color: white;
    border: none;
}

.confirm-container .btn-danger:hover,
.confirm-container .btn-secondary:hover {
    opacity: 0.8; /* Añade un efecto de hover */
}

/* Media Queries for responsiveness */
@media (max-width: 768px) {
    .catalogo-container {
        padding: 10px;
    }
    
    .titulo-reportes {
        font-size: 1.75rem;
        margin-bottom: 15px;
    }

    .card {
        max-width: 100%;
    }

    button {
        font-size: 0.9rem;
        padding: 8px 15px;
    }
}

@media (max-width: 480px) {
    .titulo-reportes {
        font-size: 1.5rem;
        margin-bottom: 10px;
    }

    .card-body h3 {
        font-size: 1.25rem;
    }

    button {
        font-size: 0.8rem;
        padding: 7px 10px;
    }
}

</style>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Reportes - PETLOVER</title>
    <link rel="stylesheet" href="estilos/catalogo2.css">
</head>
<body>
    <main>
        <section class="catalogo-container">
            <h1 class="titulo-reportes">Estos son tus reportes</h1>

            <!-- Sección de tarjetas -->
            <div class="catalogo">
                <?php
                if ($result->num_rows > 0) {
                    while ($row_mascota = $result->fetch_assoc()) {
                        echo '<div class="card">';
                        echo '<div class="card-header">';
                        echo '<img src="data:image/jpeg;base64,' . htmlspecialchars($row_mascota["imagen"]) . '" alt="Imagen de ' . htmlspecialchars($row_mascota["nombre"]) . '">';
                        // Botón para deshabilitar reporte si está habilitado
                        if ($row_mascota["is_active"] == 1) {
                             // Botón de deshabilitar con evento onclick
        echo '<button class="icon-btn" onclick="toggleDeshabilitar(' . htmlspecialchars($row_mascota["id_mascota"]) . ')">Deshabilitar<ion-icon name="paw-outline"></ion-icon></button>';
        // Contenedor de confirmación con motivo
        echo '<div id="deshabilitar-confirm-' . htmlspecialchars($row_mascota["id_mascota"]) . '" class="confirm-container">';
        echo '<p>¿Estás seguro de que quieres deshabilitar este reporte?</p>';
        echo '<form action="deshabilitar_reporte.php" method="post">';
        echo '<input type="hidden" name="id_mascota" value="' . htmlspecialchars($row_mascota["id_mascota"]) . '">';
        
        // Opciones de motivo
        echo '<label for="motivo">Motivo para deshabilitar:</label>';
        echo '<select name="motivo" required>';
        echo '<option value="">Selecciona un motivo</option>';
        echo '<option value="app">Ya encontré a mi perro por la aplicación</option>';
        echo '<option value="otro_medio">Ya encontré a mi perro por otro medio</option>';
        echo '<option value="error">Error en el reporte</option>';
        echo '<option value="otro">Otro motivo</option>';
        echo '</select>';
        
        // Botones de Confirmar y Cancelar
        echo '<button type="submit" class="btn btn-danger">Confirmar</button>';
        echo '<button type="button" class="btn btn-secondary" onclick="toggleDeshabilitar(' . htmlspecialchars($row_mascota["id_mascota"]) . ')">Cancelar</button>';
        echo '</form>';
        echo '</div>';
    } else {
        // Botón de reactivar
        echo '<form action="reactivar_reporte.php" method="post" class="icon-btn">';
        echo '<input type="hidden" name="id_mascota" value="' . htmlspecialchars($row_mascota["id_mascota"]) . '">';
        echo '<button type="submit" title="Habilitar">Habilitar<ion-icon name="paw-outline"></ion-icon></button>';
        echo '</form>';
    }                    
                        echo '</div>';
                        echo '<div class="card-body">';
                        echo '<h3>' . htmlspecialchars($row_mascota["nombre"]) . '</h3>';
                        echo '<p class="age">' . htmlspecialchars($row_mascota["tipo"]) . ' &bull; ' . htmlspecialchars($row_mascota["raza"]) . '</p>';

                        // Botón de "Más información"
                        echo '<button class="info-btn" onclick="openModal(' . htmlspecialchars($row_mascota["id_mascota"]) . ')">Más Información</button>';

                        echo '</div>';
                        echo '</div>';

                        // Modal para mostrar más información (si es necesario)
                        echo '<div id="modal-' . htmlspecialchars($row_mascota["id_mascota"]) . '" class="modal">';
                        echo '<div class="modal-content">';
                        echo '<span class="close" onclick="closeModal(' . htmlspecialchars($row_mascota["id_mascota"]) . ')">&times;</span>';
                        echo '<div class="modal-header">';
                        echo '<h2>' . htmlspecialchars($row_mascota["nombre"]) . '</h2>';
                        echo '<span class="badge">' . htmlspecialchars($row_mascota["tipo"]) . '</span>';
                        echo '</div>';
                        echo '<div class="modal-body">';
                        echo '<img src="data:image/jpeg;base64,' . htmlspecialchars($row_mascota["imagen"]) . '" alt="Imagen de ' . htmlspecialchars($row_mascota["nombre"]) . '" class="modal-image">';
                        echo '<p><strong>Raza:</strong> ' . htmlspecialchars($row_mascota["raza"]) . '</p>';
                        echo '<p><strong>Tamaño:</strong> ' . htmlspecialchars($row_mascota["tamano"]) . '</p>';
                        echo '<p><strong>Color:</strong> ' . htmlspecialchars($row_mascota["color"]) . '</p>';
                        echo '<p><strong>Sexo:</strong> ' . htmlspecialchars($row_mascota["sexo"]) . '</p>';
                        echo '<p><strong>Descripción:</strong> ' . htmlspecialchars($row_mascota["descripcion"]) . '</p>';
                       // Botón de "Buscar coincidencias" si el reporte está activo
                       if ($row_mascota["is_active"] == 1) {
                        echo '<form action="buscar_coincidencias.php" method="post">';
                        echo '<input type="hidden" name="id_mascota" value="' . htmlspecialchars($row_mascota["id_mascota"]) . '">';
                        echo '<button type="submit" class="btn btn-primary mt-3">Buscar coincidencias</button>';
                        echo '</form>';
                    }
                        echo '</div>';
                        echo '</div>';
                        echo '</div>';
                    }
                } else {
                    echo "<p>No tienes reportes registrados.</p>";
                }
                ?>
            </div>
        </section>
    </main>

    <script>
        // Función para abrir el modal
        function openModal(id) {
            document.getElementById('modal-' + id).style.display = 'block';
        }

        // Función para cerrar el modal
        function closeModal(id) {
            document.getElementById('modal-' + id).style.display = 'none';
        }

    
function toggleDeshabilitar(id) {
    var confirmContainer = document.getElementById('deshabilitar-confirm-' + id);
    if (confirmContainer.style.display === 'none' || confirmContainer.style.display === '') {
        confirmContainer.style.display = 'block';
    } else {
        confirmContainer.style.display = 'none';
    }
}



        
    </script>
</body>
</html>

<?php
$conn->close();
?>