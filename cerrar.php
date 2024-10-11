<?php
session_start();

// Destruir todas las sesiones
session_unset();  // Elimina todas las variables de sesión
session_destroy();  // Destruye la sesión

// Redirigir al index.html
header("Location: index.html");
exit();
?>
