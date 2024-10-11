<?php
include("header_login.php");


if (!isset($_SESSION['correo'])) {
    header("Location: iniciose.html");
    exit;
}

// Obtener el id del usuario de la sesión actual
$user = $_SESSION['correo'];
$sqlUser = "SELECT id_usuario FROM usuario WHERE correo = ?";
$stmtUser = $conn->prepare($sqlUser);
$stmtUser->bind_param("s", $user);
$stmtUser->execute();
$resultUser = $stmtUser->get_result();

if ($resultUser->num_rows > 0) {
    $rowUser = $resultUser->fetch_assoc();
    $current_user_id = $rowUser['id_usuario'];
} else {
    die("Error: Usuario no encontrado.");
}
$stmtUser->close();

// Consultar los usuarios con los que ha tenido conversaciones (emisor o receptor) y los IDs de los reportes
$sqlConversaciones = "
    SELECT DISTINCT 
        m.reporte_id,
        CASE
            WHEN m.emisor_id = ? THEN m.receptor_id
            WHEN m.receptor_id = ? THEN m.emisor_id
        END AS otro_usuario_id
    FROM mensajes m
    WHERE (m.emisor_id = ? OR m.receptor_id = ?)
";
$stmtConversaciones = $conn->prepare($sqlConversaciones);
$stmtConversaciones->bind_param("iiii", $current_user_id, $current_user_id, $current_user_id, $current_user_id);
$stmtConversaciones->execute();
$resultConversaciones = $stmtConversaciones->get_result();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

    <title>Bandeja de Mensajes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
       
      .container-fluid {
            background-color: #f0e4dc; /* Cambia este valor por el color de fondo que desees */
        }
       
        /* Contenedor principal que aloja la lista de conversaciones y el chat */
        .chat-container {
            display: flex;
            height: 100vh;
            background-color: #f5f5f5;
        }
        /* Estilo cuando se pasa el ratón sobre una conversación */
        .conversacion:hover {
            background-color: #f1f1f1; /* Color de fondo en hover */
        }

        /* Botón para mostrar/ocultar la lista de conversaciones */
        .toggle-conversations {
            border: none;
            position: absolute;
            top:10px; /* Ajusta este valor según sea necesario */
            left: 12px;
            z-index: 1000;
        }

        /* Lista de conversaciones */
        .conversations-list {
            width: 30%;
            max-width: 300px;
            padding: 10px;
            border-right: 1px solid #ddd;
            background-color: #eeeae9;
            overflow-y: auto;
            transition: transform 0.3s ease-in-out;
        }

        /* Ocultar lista de conversaciones en móviles por defecto */
        .conversations-list.hidden {
            transform: translateX(-100%);
            position: absolute;
            top: 0;
            bottom: 0;
            z-index: 1000;
        }

        /* Caja de chat */
        .chat-detalles {
            flex-grow: 1;
            background-color: #f5f5f5;
            display: flex;
            flex-direction: column;
            overflow-y: auto;
            padding: 0px;
        }
        .conversacion-info p {
            margin: 0;
            font-size: 15px;
            color: #666; /* Color gris para el texto */
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap; /* Evita el salto de línea */
        }
         /* Estilo para la foto de perfil de la mascota */
         .mascota-foto {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #ddd; /* Borde más suave */
        }

        .bandeja-header {
        background-color: #eee4db; /* Color de fondo similar al chat-header */
        color: black;
        padding: 10px 20px;
        display: flex;
        align-items: center;
        height: 80px;
        position: relative;
        font-size: 1.4rem;
        box-shadow: 0px 1px 2px rgba(0, 0, 0, 0.1); /* Sombra ligera para destacar */
    }

    .bandeja-header .toggle-conversations {
        background: none;
        border: none;
        cursor: pointer;
        position: absolute;
        left: 10px;
        top: 55%;
        transform: translateY(-50%); /* Centra verticalmente la imagen */
        padding: 0; /* Elimina el padding para que solo se vea la imagen */
    }

    .bandeja-header h2 {
        margin: 0 auto; /* Centra el texto horizontalmente */
        font-size: 1.6rem;
        font-weight: bold;
        text-align: center;
    }


        /* Responsiveness: en pantallas más pequeñas */
        @media (max-width: 768px) {
            .conversations-list {
                width: 100%;
                max-width: 100%;
                border-right: none;
                border-bottom: 1px solid #ddd;
                position: fixed;
                height: 100%;
                z-index: 1000;
                background-color: #eeeae9;
            }

            .chat-detalles {
                width: 100%;
                padding-top: 0px; /* Deja espacio para el botón */
            }
            .bandeja-header h2{
            background-color: #f0e4dc;
            color: black;
            align-items: center;
            display: flex;
            align-items: center;
            font-size: 1.4rem;
            }
        }
    </style>
</head>
<div class="bandeja-header" id="bandeja-header">
<link rel="stylesheet" href="estilos/catalogo2.css">
    <button class="toggle-conversations" onclick="toggleConversations()">
    <img src="Imagenes/mensajes.png" style="width: 40px; height: 40px; ">
    </button>
    <h2>Tus mensajes </h2>
</div>
<body class="bg-light">
<div class="container-fluid" id = "container-fluid">
    <div class="chat-container">
        <!-- Lista de conversaciones -->
        <div class="conversations-list" id="conversations-list">
            <?php
            if ($resultConversaciones->num_rows > 0) {
                while ($rowConversacion = $resultConversaciones->fetch_assoc()) {
                    $otro_usuario_id = $rowConversacion['otro_usuario_id'];
                    $reporte_id = $rowConversacion['reporte_id'];

                    // Obtener información del otro usuario
                    $sqlOtroUsuario = "SELECT nombre_completo FROM usuario WHERE id_usuario = ?";
                    $stmtOtroUsuario = $conn->prepare($sqlOtroUsuario);
                    $stmtOtroUsuario->bind_param("i", $otro_usuario_id);
                    $stmtOtroUsuario->execute();
                    $resultOtroUsuario = $stmtOtroUsuario->get_result();

                    // Obtener la imagen y el nombre de la mascota asociada al reporte
                    $sqlMascota = "SELECT imagen, Nombre FROM mascotas WHERE id_mascota = ?";
                    $stmtMascota = $conn->prepare($sqlMascota);
                    $stmtMascota->bind_param("i", $reporte_id);
                    $stmtMascota->execute();
                    $resultMascota = $stmtMascota->get_result();
                    $fotoMascota = '';
                    $nombreMascota = '';
                    if ($resultMascota->num_rows > 0) {
                        $rowMascota = $resultMascota->fetch_assoc();
                        $fotoMascota = $rowMascota['imagen'];
                        $nombreMascota = $rowMascota['Nombre'];
                    }

                    if ($resultOtroUsuario->num_rows > 0) {
                        $rowOtroUsuario = $resultOtroUsuario->fetch_assoc();
                        $nombre_otro_usuario = $rowOtroUsuario['nombre_completo'];
            ?>
                        <div class="conversacion" onclick="cargarChat(<?php echo $current_user_id; ?>, <?php echo $otro_usuario_id; ?>, <?php echo $reporte_id; ?>)">
                            <?php if (!empty($fotoMascota)) : ?>
                                <img src="data:image/jpeg;base64,<?php echo htmlspecialchars($fotoMascota); ?>" alt="Foto Mascota" class="mascota-foto">
                            <?php endif; ?>
                            <div class="conversacion-info">
                                <h5>Chat con <?php echo htmlspecialchars($nombre_otro_usuario); ?></h5>
                                <p>Reporte ID: <?php echo htmlspecialchars($reporte_id); ?></p>
                                <p>Nombre de la Mascota: <?php echo htmlspecialchars($nombreMascota); ?></p>
                            </div>
                        </div>
            <?php
                    }
                    $stmtOtroUsuario->close();
                    $stmtMascota->close();
                }
            } else {
                echo '<p>No tienes conversaciones recientes.</p>';
            }
            ?>
        </div>

        <!-- Caja de chat -->
        <div class="chat-detalles" id="chat-detalles">
            <p>Selecciona una conversación para ver los mensajes</p>
        </div>
    </div>
</div>

<script>
// Función que carga el chat usando AJAX
function cargarChat(usuarioId, reporteUsuarioId, reporteId) {
    const xhr = new XMLHttpRequest();
    xhr.open('GET', 'chat.php?usuario_id=' + usuarioId + '&reporte_usuario_id=' + reporteUsuarioId + '&reporte_id=' + reporteId, true);
    xhr.onload = function() {
        if (this.status === 200) {
            document.getElementById('chat-detalles').innerHTML = this.responseText;
            toggleConversations(); // Oculta la lista de conversaciones después de cargar el chat
        }
    };
    xhr.send();
}

// Función para mostrar/ocultar la lista de conversaciones
function toggleConversations() {
    const conversationsList = document.getElementById('conversations-list');
    const chatDetalles = document.getElementById('chat-detalles');
    conversationsList.classList.toggle('hidden');
    
    if (conversationsList.classList.contains('hidden')) {
        chatDetalles.style.width = '100%';
    } else {
        chatDetalles.style.width = '';
    }
}
</script>
</body>
</html>
