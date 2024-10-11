<?php
 include_once('conexion.php');
 $con=conectar();

 $id1 = isset($_GET['id']) ? $_GET['id'] : '';
if($id1 !=null){
$eliminar ="DELETE fROM usuario where id_usuario=$id1";
 $resultado = mysqli_query($con, $eliminar);

header("location:PanelAdmin.php");
 }
 mysqli_close($con);

 ?>