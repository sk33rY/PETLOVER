<?php
include_once('conexion.php');

$pamd = 1; // Perfil Administrador
$pcli = 2; // Perfil Cliente

if (isset($_POST['login'])) {
    // Recoger datos del formulario
    $correo = $_POST['correo'];
    $contrasenia = md5($_POST['password']);

    // Consulta para validar la existencia del usuario y obtener su ID
    $consulta = "SELECT id_usuario, correo, contrasenia, rol FROM usuario WHERE correo = '$correo' AND contrasenia = '$contrasenia'";
    $resultado = mysqli_query($conn, $consulta) or die(mysqli_error($conn));

    // Inicializar variables globales
    global $usu, $clav, $rol, $id_usuario;
    $usu = "";
    $clav = "";
    $rol = "";
    $id_usuario = "";

    // Recorrer el resultado
    while ($fila = mysqli_fetch_row($resultado)) {
        $id_usuario = $fila[0];  // Obtener el ID del usuario
        $usu = $fila[1];
        $clav = $fila[2];
        $rol = $fila[3];
    }

    // Verificar si el usuario y contraseña coinciden
    if (($usu != $correo) || ($clav != $contrasenia)) {
        echo "<script>alert('Usuario o clave incorrecta');</script>";
        require('iniciose.html');
        exit();
    }

    // Iniciar sesión y almacenar el correo y el ID de usuario en la sesión
    session_start();
    $_SESSION['correo'] = $correo;
    $_SESSION['id_usuario'] = $id_usuario;

    // Redireccionar dependiendo del rol del usuario
    if ($rol == $pamd) {
        echo "<script>alert('Hola Administrador');</script>";
        header("Location: PanelAdmin.php");
    } else {
        echo "<script>alert('Hola $usu');</script>";
        header("Location: inicio.php");
    }

    exit();
} else {
    mysqli_close($conn);
}
?>
