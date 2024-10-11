<?php
include("conexion.php");

// Iniciar sesión si no está ya iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['id_usuario'])) {
    header("location: iniciose.html");
    exit();
}

// Obtener el correo del usuario desde la sesión
$user = $_SESSION['correo'];

// Consulta para obtener el nombre completo del usuario logueado
$sql = "SELECT Nombre_completo FROM usuario WHERE correo='$user'";
$resultado = mysqli_query($conn, $sql) or die(mysqli_error($conn));
$row = $resultado->fetch_assoc();

// Si no hay nombre en la base de datos, mostrar 'Usuario'
$nombre_completo = isset($row['Nombre_completo']) ? utf8_decode($row['Nombre_completo']) : 'Usuario';
?>
<link rel="stylesheet" href="estilos/header_login.css">

<header>
    <div class="contenedor">
        <div class="logo">
            <ion-icon name="ionicons ion-map"></ion-icon>
            <span>PETLOVER</span>
        </div>

        <!-- Menú de hamburguesa para móviles -->
        <ion-icon id="btn-menu" name="menu-outline"></ion-icon>

        <div class="menu-opciones">
            <ul>
                <li><a href="inicio.php">Home</a></li>
                <li><a href="mapa_marcadoreslogin.php">Mapa de búsqueda</a></li>
                <li><a href="catalogo.php">Busca a tu mascota</a></li>
                <li><a href="registro_mascota.php">Reporta tu mascota</a></li>
                <li><a href="catalogo_adopciones.php">Adopciones</a></li>
            </ul>
        </div>

        <div class="controles-usuario">
            <!-- Ícono de notificaciones -->
            <a href="buzon_notificaciones.php" class="icono">
            <img src="Imagenes/notificacion21.gif" alt="Notificaciones" width="30" height="30">
            </a>

            <!-- Ícono de mis chats -->
            <a href="bandeja_mensajes.php" class="icono">
            <img src="Imagenes/CHAT21.png" alt="Notificaciones" width="35" height="35">
            </a>

            <!-- Contenedor del usuario -->
            <div class="user-container">
                <img src="Imagenes/usuario.png" id="menu-btn" width="35" height="35">
                <div id="menu-dropdown" class="dropdown-content">
                    <a href="perfil.php">Perfil</a>
                    <a href="mis_reportes.php">Mis reportes</a>
                    <a href="cerrar.php">Cerrar sesión</a>
                </div>
            </div>
        </div>
    </div>
</header>

<!-- Menú lateral para móviles -->
<nav id="menu-lateral" class="menu-lateral">
    <ul>
        <li><a href="inicio.php">Home</a></li>
        <li><a href="mapa_marcadoreslogin.php">Mapa de búsqueda</a></li>
        <li><a href="catalogo.php">Busca a tu mascota</a></li>
        <li><a href="registro_mascota.php">Reporta tu mascota</a></li>
        <li><a href="catalogo_adopciones.php">Adopciones</a></li>
        <li><a href="bandeja_mensajes.php">Mis chats</a></li>

    </ul>
</nav>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const btnMenu = document.getElementById('btn-menu');
    const menuLateral = document.getElementById('menu-lateral');
    const menuBtn = document.getElementById('menu-btn');
    const dropdown = document.getElementById('menu-dropdown');
    const body = document.querySelector('body');

    // Manejar el menú de hamburguesa
    btnMenu.addEventListener('click', () => {
        menuLateral.classList.toggle('active');
        body.classList.toggle('menu-active');
    });

    // Cerrar el menú lateral si se hace clic fuera de él
    window.addEventListener('click', (event) => {
        if (!menuLateral.contains(event.target) && !btnMenu.contains(event.target)) {
            menuLateral.classList.remove('active');
            body.classList.remove('menu-active');
        }
    });

    // Manejar el menú desplegable del usuario
    menuBtn.addEventListener('click', (event) => {
        event.stopPropagation();
        dropdown.classList.toggle('show');
    });

    // Cerrar el menú desplegable si se hace clic fuera de él
    window.addEventListener('click', (event) => {
        if (!menuBtn.contains(event.target) && !dropdown.contains(event.target)) {
            dropdown.classList.remove('show');
        }
    });
});
</script>
