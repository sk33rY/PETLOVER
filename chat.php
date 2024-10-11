<?php 
include('conexion.php');
include('config_cifrado.php');
session_start();

if (!isset($_SESSION['correo'])) {
    header("Location: iniciose.html");
    exit;
}

// Verificar la existencia de los parámetros en $_GET
$usuario_id = isset($_GET['usuario_id']) ? $_GET['usuario_id'] : null;
$reporte_usuario_id = isset($_GET['reporte_usuario_id']) ? $_GET['reporte_usuario_id'] : null;
$reporte_id = isset($_GET['reporte_id']) ? $_GET['reporte_id'] : null;

// Si alguno de los parámetros es null, manejar el error o redirigir
if (is_null($usuario_id) || is_null($reporte_usuario_id) || is_null($reporte_id)) {
    die("Faltan parámetros necesarios en la URL.");
}

// Obtener los nombres de los usuarios
$sqlNombresUsuarios = "SELECT id_usuario, nombre_completo FROM usuario WHERE id_usuario IN (?, ?)";
$stmtNombresUsuarios = $conn->prepare($sqlNombresUsuarios);
$stmtNombresUsuarios->bind_param("ii", $usuario_id, $reporte_usuario_id);
$stmtNombresUsuarios->execute();
$resultNombresUsuarios = $stmtNombresUsuarios->get_result();

$nombresUsuarios = [];
while ($row = $resultNombresUsuarios->fetch_assoc()) {
    $nombresUsuarios[$row['id_usuario']] = $row['nombre_completo'];
}
$stmtNombresUsuarios->close();

// Obtener los mensajes entre los dos usuarios
$sqlChat = "SELECT emisor_id, mensaje, iv, fecha FROM mensajes WHERE reporte_id = ? ORDER BY fecha";
$stmtChat = $conn->prepare($sqlChat);
$stmtChat->bind_param("i", $reporte_id);
$stmtChat->execute();
$resultChat = $stmtChat->get_result();
?>

<style>
    body, html {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        
    }

    .chat-header {
        background-color: #eee4db;
        color: black;
        padding: 10px 50px;
        display: flex;
        align-items: center;
        font-size: 1.4rem;
    }

    .chat-container {
        display: flex;
            height: 100vh;
            background-color: #f8f9fa;
    }

    .chat-box {
        flex-grow: 1; /* Ocupa el espacio restante */
            background-color: #f5f5f5;
            display: flex;
            flex-direction: column;
            overflow-y: auto;
            padding: 20px;
    }

    .chat-box::-webkit-scrollbar {
        width: 5px;
    }

    .chat-box::-webkit-scrollbar-thumb {
        background-color: rgba(0, 0, 0, 0.2);
        border-radius: 10px;
    }

    .mensaje {
        margin-bottom: 10px;
        padding: 10px 15px;
        border-radius: 20px;
        max-width: 75%;
        word-wrap: break-word;
        display: inline-block;
        font-size: 1rem;
        line-height: 1.4;
        position: relative;
        box-shadow: 0px 1px 2px rgba(0, 0, 0, 0.1);
        min-width: fit-content;
    }

    .mensaje-enviado {
        background-color: #d0c2bf;
        align-self: flex-end;
        text-align: right;
        border-bottom-right-radius: 0;
    }

    .mensaje-recibido {
        background-color: #eeeae9;
        align-self: flex-start;
        text-align: left;
        border-bottom-left-radius: 0;
    }

    .mensaje .time {
        font-size: 0.8rem;
        color: black;
        display: block;
        margin-top: 5px;
    }

    .input-container {
        display: flex;
        align-items: center;
        padding: 10px;
        background-color: #e5ddd5;
        border-top: 1px solid #ddd;
    }

    .input-container textarea {
        flex: 1;
        resize: none;
        border-radius: 20px;
        border: none;
        padding: 10px 15px;
        outline: none;
        font-size: 1rem;
        background-color: #eee4db;
        box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.1);
    }

    .input-container button {
        padding: 10px 20px;
        border-radius: 50%;
        color: black;
        border: none;
        cursor: pointer;
        font-size: 1rem;
        background-color: #d0c2bf;
        margin-left: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0px 1px 2px rgba(0, 0, 0, 0.2);
    }

    .input-container button:hover {
        background-color: #beafac;
    }

    .input-container button i {
        font-size: 1.2rem;
    }

    @media (max-width: 768px) {
        .chat-header {
        font-size: 1.6rem; /* Aumenta el tamaño de la fuente para mejor legibilidad */
        padding: 15px 20px; /* Aumenta el padding para mayor espaciado */
    }

    .chat-box {
        padding: 10px 15px; /* Aumenta el padding para más espacio en la caja del chat */
        font-size: 1.1rem; /* Ajusta el tamaño de la fuente para el texto del chat */
    }

    .mensaje {
        font-size: 1.1rem; /* Aumenta el tamaño de la fuente de los mensajes */
        padding: 12px 18px; /* Aumenta el padding de los mensajes */
    }

    .input-container textarea {
        font-size: 1.1rem; /* Aumenta el tamaño de la fuente para una mejor lectura */
        padding: 12px 15px; /* Aumenta el padding para más comodidad al escribir */
    }

    .input-container button {
        padding: 12px 20px; /* Ajusta el padding para que el botón sea más grande */
        font-size: 1.1rem; /* Aumenta el tamaño de la fuente del botón */
    }

    .input-container {
        padding: 15px; /* Aumenta el padding alrededor del input container */
    }
}


</style>

<!-- Cabecera del chat -->
<div class="chat-header" id="chat-header">
    <a href="bandeja_mensajes.php" style="position: absolute; left: 10px; top: 5px;">
        <img src="Imagenes/mensajes.png" alt="Volver" style="width: 40px; height: 40px;">
    </a>
    <h4>Chat con <?php echo htmlspecialchars($nombresUsuarios[$reporte_usuario_id]); ?></h4>
</div>


<!-- Contenedor del chat -->

    <div class="chat-box" id="chat-box">
        <?php
        if ($resultChat->num_rows > 0) {
            while ($rowChat = $resultChat->fetch_assoc()) {
                $emisor_id = $rowChat['emisor_id'];
                $emisor = $emisor_id == $usuario_id ? 'Tú' : (isset($nombresUsuarios[$emisor_id]) ? $nombresUsuarios[$emisor_id] : 'Usuario');

                // Descifrar el mensaje
                $clave = CLAVE_CIFRADO;
                $iv = hex2bin($rowChat['iv']);
                $mensaje_cifrado = $rowChat['mensaje'];
                $mensaje_descifrado = openssl_decrypt($mensaje_cifrado, 'AES-256-CBC', $clave, 0, $iv);

                // Alinear los mensajes
                $fecha = date("d/m/Y H:i", strtotime($rowChat['fecha']));
                $mensaje_clase = $emisor_id == $usuario_id ? 'mensaje-enviado' : 'mensaje-recibido';

                echo '<div class="mensaje ' . htmlspecialchars($mensaje_clase) . '">';
                echo '<p><strong>' . htmlspecialchars($emisor) . ':</strong> ' . htmlspecialchars($mensaje_descifrado) . '</p>';
                echo '<span class="time">' . $fecha . '</span>';
                echo '</div>';
            }
        } else {
            echo "<p>No hay mensajes.</p>";
        }
        ?>
    </div>
    <form id="chat-form" action="enviar_mensaje.php" method="post">
        <input type="hidden" name="receptor_id" value="<?php echo htmlspecialchars($reporte_usuario_id); ?>">
        <input type="hidden" name="reporte_id" value="<?php echo htmlspecialchars($reporte_id); ?>">
        <div class="input-container">
            <textarea class="form-control" name="mensaje" rows="2" placeholder="Escribe tu mensaje..." id="mensaje-textarea"></textarea>
            <button type="submit" class="btn btn-primary">Enviar</button>
        </div>
    </form>


<script>
// Auto-scroll al final del chat
const chatBox = document.getElementById('chat-box');
chatBox.scrollTop = chatBox.scrollHeight;

// Enviar el formulario al presionar Enter y mantener la posición del chat
const textarea = document.getElementById('mensaje-textarea');
const form = document.getElementById('chat-form');

textarea.addEventListener('keydown', function(event) {
    if (event.key === 'Enter' && !event.shiftKey) {
        event.preventDefault();
        form.submit();
    }
});

form.addEventListener('submit', function(event) {
    event.preventDefault();
    const formData = new FormData(form);
    const xhr = new XMLHttpRequest();
    xhr.open('POST', form.action, true);
    xhr.onload = function() {
        if (this.status === 200) {
            window.location.reload();
            chatBox.innerHTML += this.responseText;
            chatBox.scrollTop = chatBox.scrollHeight; // Mantener el scroll al final
            textarea.value = ''; // Limpiar el textarea
        }
    };
    xhr.send(formData);
});

</script>

<?php
$stmtChat->close();
$conn->close();
?>
