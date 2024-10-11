<?php
include('conexion.php');
session_start();

if (!isset($_SESSION['correo'])) {
    header("Location: inicio.php");
    exit;
}

// Verificar la conexión a la base de datos
if (!isset($conn)) {
    die("Error: No se pudo conectar a la base de datos.");
}

// Obtener el ID del usuario actual
$user = $_SESSION['correo'];
$sqlUser = "SELECT id_usuario FROM usuario WHERE correo='$user'";
$resultUser = $conn->query($sqlUser);
if ($resultUser->num_rows > 0) {
    $rowUser = $resultUser->fetch_assoc();
    $usuario_id = $rowUser['id_usuario'];
} else {
    die("Error: Usuario no encontrado.");
}

// Recuperar y escapar los datos del formulario
$nombre = $conn->real_escape_string($_POST['nombre']);
$descripcion = $conn->real_escape_string($_POST['descripcion']);
$raza = $conn->real_escape_string($_POST['raza']);
$tamano = $conn->real_escape_string($_POST['tamano']); 
$color = $conn->real_escape_string($_POST['color']);
$sexo = $conn->real_escape_string($_POST['sexo']);
$lat = isset($_POST['lat']) ? floatval($_POST['lat']) : 0.0;
$lng = isset($_POST['lng']) ? floatval($_POST['lng']) : 0.0;
$tipo = $conn->real_escape_string($_POST['tipo']);
$tipo_animal = $conn->real_escape_string($_POST['tipo_animal']);

// Manejo de la imagen
$imagen = null;
if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
    $fileInfo = getimagesize($_FILES['imagen']['tmp_name']);
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];

    if (in_array($fileInfo['mime'], $allowedTypes)) {
        $imagen = base64_encode(file_get_contents($_FILES['imagen']['tmp_name']));
    } else {
        die("Error: Solo se permiten imágenes en formato JPEG, PNG o GIF.");
    }
}

// Insertar el nuevo reporte en la base de datos
$sql = "INSERT INTO mascotas (nombre, descripcion, raza, tamano, color, sexo, tipo_animal, imagen, lat, lng, tipo, usuario_id) 
        VALUES ('$nombre', '$descripcion', '$raza', '$tamano', '$color', '$sexo', '$tipo_animal', '$imagen', $lat, $lng, '$tipo', $usuario_id)";
if ($conn->query($sql) === TRUE) {
    // Obtener el ID del reporte recién insertado
    $reporte_id = $conn->insert_id;

    // Buscar coincidencias con reportes existentes
    $sql_reportes_existentes = "SELECT * FROM mascotas WHERE id_mascota != ? AND usuario_id != ?";
    $stmt_existentes = $conn->prepare($sql_reportes_existentes);
    $stmt_existentes->bind_param("ii", $reporte_id, $usuario_id);
    $stmt_existentes->execute();
    $result_existentes = $stmt_existentes->get_result();

    $reportes_encontrados = [];
    while ($row = $result_existentes->fetch_assoc()) {
        $reportes_encontrados[] = $row;
    }

    // Preparar los datos para la ejecución del script de Python
    $reporte_data = array(
        'reporte' => array(
            'lat' => $lat,
            'lng' => $lng,
            'descripcion' => $descripcion,
            'raza' => $raza,
            'tamano' => $tamano,
            'color' => $color,
            'sexo' => $sexo
        ),
        'reportes_encontrados' => $reportes_encontrados
    );

    // Guardar los datos en un archivo temporal
    $filename = tempnam(sys_get_temp_dir(), 'data_') . '.json';
    file_put_contents($filename, json_encode($reporte_data));

    // Ejecutar el script de Python
    $command = escapeshellcmd("python3 calcular_similitudes.py $filename");
    $output = shell_exec($command);

    // Procesar el resultado de Python
    $resultados = json_decode($output, true);

    // Si hay coincidencias, mostrarlas en un pop-up y guardar notificaciones
    $coincidencias_encontradas = false;
    foreach ($resultados as $resultado) {
        // Verificar si la clave "distancia_km" está definida
        if (isset($resultado['distancia_km']) && $resultado['similaridad'] >= 70 && $resultado['distancia_km'] <= 10) {
            $coincidencias_encontradas = true;

            // Guardar notificación para ambos usuarios
            guardar_notificacion($usuario_id, $reporte_id, $resultado['data']['id_mascota']);
            guardar_notificacion($resultado['data']['usuario_id'], $resultado['data']['id_mascota'], $reporte_id);

            // Mostrar el pop-up con el mensaje personalizado
            echo "<script>
                alert('¡Se ha encontrado una coincidencia con tu reporte!! Para más detalle busca en la sección de notificaciones');
                setTimeout(function() {
                    window.location.href = 'buzon_notificaciones.php';
                }, 1000); // Redirige después de 3 segundos
            </script>";
        }
    }

    // Si no se encontraron coincidencias, redirigir de inmediato
    if (!$coincidencias_encontradas) {
        header("Location: catalogo.php");
        exit;
    }

} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();

// Función para guardar una notificación en la base de datos
function guardar_notificacion($user_id, $reporte_usuario_id, $reporte_coincidencia_id) {
    global $conn;
    $mensaje = "Se ha encontrado una coincidencia para tu reporte.";

    // Verificar si ya existe una notificación entre estos dos reportes
    $sql_check = "SELECT COUNT(*) as count FROM notificaciones 
                  WHERE user_id = ? AND reporte_usuario_id = ? AND reporte_coincidencia_id = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("iii", $user_id, $reporte_usuario_id, $reporte_coincidencia_id);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result()->fetch_assoc();

    // Si no existe una notificación previa, la creamos
    if ($result_check['count'] == 0) {
        $sql = "INSERT INTO notificaciones (user_id, reporte_usuario_id, reporte_coincidencia_id, mensaje, leido) 
                VALUES (?, ?, ?, ?, 0)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iiis", $user_id, $reporte_usuario_id, $reporte_coincidencia_id, $mensaje);
        $stmt->execute();
    }
}

?>