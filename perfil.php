<?php
include("conexion.php");
session_start();

// Verificar si la sesión tiene un correo
if (!isset($_SESSION['correo'])) {
    header("location: iniciose.html");
    exit;
}

$user = $_SESSION['correo'];

// Preparar la consulta para obtener la información del usuario
$sql = "SELECT Nombre_completo, numero_telefono, correo, fecha_nacimiento, direccion_residencia FROM usuario WHERE correo=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $user);
$stmt->execute();
$resultado = $stmt->get_result();

// Verificar si se encontró el usuario
if ($resultado->num_rows > 0) {
    $row = $resultado->fetch_assoc();
} else {
    echo '<p>No se encontraron datos del usuario.</p>';
    exit;
}

$stmt->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil del Usuario</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <?php
    // Mostrar un alert si el update fue exitoso
    if (isset($_GET['update']) && $_GET['update'] == 'success') {
        echo "<script>alert('Los datos se han actualizado correctamente.');</script>";
    }
    ?>

    <!-- Contenido de la página -->
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-gray-800 text-gray-100 p-6">
            <div class="text-2xl font-bold text-center mb-10">
                PETLOVER
            </div>
            <nav>
                <a href="inicio.php" class="block py-2.5 px-4 rounded hover:bg-gray-700">Inicio</a>
                <a href="mapa_marcadoreslogin.php" class="block py-2.5 px-4 rounded hover:bg-gray-700">Mapa</a>
                <a href="mis_reportes.php" class="block py-2.5 px-4 rounded hover:bg-gray-700">Reportes</a>
                <a href="bandeja_mensajes.php" class="block py-2.5 px-4 rounded hover:bg-gray-700">Chats</a>
                <a href="iniciose.html" class="block py-2.5 px-4 rounded hover:bg-red-600">Cerrar Sesión</a>
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="flex-grow p-10 bg-gray-100">
            <div class="bg-white p-8 rounded-lg shadow-lg max-w-3xl mx-auto">
                <h2 class="text-2xl font-bold mb-6 text-gray-800">Modificar Datos del Usuario</h2>

                <form action="modificarUser.php" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nombre Completo -->
                    <div>
                        <label for="Nombres" class="block text-sm font-medium text-gray-700">Nombres y Apellidos completos</label>
                        <input type="text" name="Nombres" id="Nombres" class="mt-1 p-2 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" value="<?php echo htmlspecialchars($row['Nombre_completo']); ?>" required>
                    </div>

                    <!-- Correo -->
                    <div>
                        <label for="correo" class="block text-sm font-medium text-gray-700">Correo Electrónico</label>
                        <input type="email" name="correo" id="correo" class="mt-1 p-2 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" value="<?php echo htmlspecialchars($row['correo']); ?>" required>
                    </div>

                    <!-- Número de Teléfono -->
                    <div>
                        <label for="numero_telefono" class="block text-sm font-medium text-gray-700">Número de Celular</label>
                        <input type="tel" name="numero_telefono" id="numero_telefono" class="mt-1 p-2 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" value="<?php echo htmlspecialchars($row['numero_telefono']); ?>" pattern="^3[0-9]{9}$" required>
                    </div>

                    <!-- Fecha de Nacimiento -->
                    <div>
                        <label for="fecha_nacimiento" class="block text-sm font-medium text-gray-700">Fecha de Nacimiento</label>
                        <input type="date" name="fecha_nacimiento" id="fecha_nacimiento" class="mt-1 p-2 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" value="<?php echo htmlspecialchars($row['fecha_nacimiento']); ?>" required>
                    </div>

                    <!-- Dirección -->
                    <div class="md:col-span-2">
                        <label for="direccion_residencia" class="block text-sm font-medium text-gray-700">Dirección</label>
                        <input type="text" name="direccion_residencia" id="direccion_residencia" class="mt-1 p-2 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" value="<?php echo htmlspecialchars($row['direccion_residencia']); ?>" required>
                    </div>

                    <!-- Botón para modificar -->
                    <div class="md:col-span-2 text-right">
                        <input type="submit" name="submit" value="Modificar" class="py-2 px-4 bg-indigo-600 text-white font-semibold rounded-lg shadow-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</body>
</html>
