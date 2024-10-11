//
<?php
include_once('conexion.php');
$con=conectar();
?>
<!DOCTYPE html>
<link rel="icon" href="imagenes/iconoApp.ico">
    <link rel="stylesheet" href="estilos/registro.css">
<html>
 <head>
 <title>Modificar Datos</title>
 </head>
<body>
 <?php
 $id1 = isset($_GET['id']) ? $_GET['id'] : '';
 $consulta="SELECT * FROM usuario WHERE id_usuario = '$id1'";
 $resultado = mysqli_query($con,$consulta) ;
 ?>
 <?php
 while($fila = mysqli_fetch_array($resultado)){
 ?>
<center>
            <fieldset>

                <section class="form-register">

 <form id="form1" name="form1" method="post" action="#">

 <tr>
 <tr>
 
 <tr>
 <td>ID.</td>
 <td><label for="id_usuario"></label>
 <input  class= "controls"  name="id_usuario" type="text" id="id_usuario" size="45" value="<?php echo $fila['id_usuario'];?>" disabled/></td>
 </tr>

 <tr>
 <td>Nombre completo</td>
 <td><label for="Nombre_completo"></label>
 <input  class= "controls"  name="Nombre_completo" type="text" id="Nombre_completo" size="45" value="<?php echo $fila['Nombre_completo'];?>" required /></td>
 </tr>

 <tr>
 <td>Celular</td>
 <td><label for="numero_telefono"></label>
 <input  class= "controls" name="numero_telefono" type="text" id="numero_telefono" size="45" value="<?php echo $fila['numero_telefono'];?>" required/></td>
 </tr>
 <tr>
 <td>Correo</td>
 <td><label for="correo"></label>
 <input  class= "controls" name="correo" type="text" id="correo" size="45" value="<?php echo $fila['correo'];?>" required/></td>
 </tr>
 <tr>
 <td>fecha_nacimiento</td>
 <td><label for="fecha_nacimiento"></label>
 <input  class= "controls" name="fecha_nacimiento" type="text" id="fecha_nacimiento" size="45" value="<?php echo $fila['fecha_nacimiento'];?>" required/></td>
 </tr>
 <tr>
 <td>direccion residencia</td>
 <td><label for="direccion_residencia"></label>
 <input  class= "controls" name="direccion_residencia" type="text" id="direccion_residencia" size="45" value="<?php echo $fila['direccion_residencia'];?>" required/></td>
 </tr>
 <tr>
 <td>Rol</td>
 <td><label for="rol"></label>
 <input class= "controls"  name="rol" type="text" id="rol" size="45" value="<?php echo $fila['rol'];?>" required/></td>
 </tr>
 <tr>
 <td><input class= "botons" type="submit" name="actualizar" id="button" value="Actualizar" /></td>

 </tr>
 <tr>
 <p> <a  href="PanelAdmin.php"> Regresar </a> </p> 

 
 </section>
</form>
 <?php


}
 ?>





</center>
<?php
$id1 = isset($_GET['id']) ? $_GET['id'] : '';
   $Nombre_completo = isset($_POST['Nombre_completo']) ? $_POST['Nombre_completo'] : '';
   $numero_telefono = isset($_POST['numero_telefono']) ? $_POST['numero_telefono'] : '';
   $correo = isset($_POST['correo']) ? $_POST['correo'] : '';
   $fecha_nacimiento = isset($_POST['fecha_nacimiento']) ? $_POST['fecha_nacimiento'] : '';
   $direccion_residencia = isset($_POST['direccion_residencia']) ? $_POST['direccion_residencia'] : '';
   $rol = isset($_POST['rol']) ? $_POST['rol'] : '';
  
  $modificar="UPDATE usuario SET Nombre_completo= '$Nombre_completo', numero_telefono= '$numero_telefono', correo= '$correo', fecha_nacimiento= '$fecha_nacimiento',
   direccion_residencia='$direccion_residencia', rol= '$rol' WHERE id_usuario= '$id1' ";


  $resultado= mysqli_query($con, $modificar);
  mysqli_close($con);
  
?> 

</body>
</html>

