<?php
include("header.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PETLOVER</title>
    <link rel="stylesheet" href="estilos/mapa_marcadores.css">
    <!-- Incluir Google Maps API -->
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA5p7pnKq5ZgMhtuARruzRY0vGWdoMhK4M"></script>
    <style>
        #mapa {
            height: 500px;
            width: 100%;
            border-radius: 0.375rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <header>
        <div class="contenedor">
            <div class="logo">
                <ion-icon name="ionicons ion-map"></ion-icon>
                <span>PETLOVER</span>
            </div>
            <div class="menu-opciones">
                <ul>
                    <li><a href="index.html">Home</a></li>
                    <li><a href="mapa_marcadores.html">Mapa de busqueda</a></li>
                    <li><a href="catalogo.php">Busca a tu mascota</a></li>
                    <li><a href="registro_mascota.php">Registra tu mascota</a></li>
                </ul>
            </div>
            <div class="controles-usuario">
                <button id="btn-sign-up">Regístrate</button>
                <ion-icon id="btn-menu" name="menu"></ion-icon>
            </div>
        </div>
    </header>
    <main>
        <section class="seccion-1">
            <section class="mapa">
                <section class="text-center mb-4">
                    <h2>Mapa de Mascotas Registradas</h2>
                </section>
                <div id="mapa"></div>
            </section>
        </section>
    </main>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <script src="js/scriptindex.js"></script>
    <!-- Archivo JS para cargar los marcadores en el mapa -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Inicializar el mapa
            var map = new google.maps.Map(document.getElementById('mapa'), {
                center: { lat: 4.7352573, lng: -74.0182495}, // Coordenadas iniciales
                zoom: 13
            });

            // Cargar los marcadores desde la base de datos
            fetch('cargar_mascota.php')
            .then(response => {
                // Verificar si la respuesta es correcta
                if (!response.ok) {
                    throw new Error('Error en la respuesta del servidor');
                }
                return response.json(); // Convertir la respuesta en JSON
            })
            .then(data => {
                if (data.error) {
                    // Mostrar cualquier error del servidor
                    console.error('Error del servidor:', data.error);
                    return;
                }
                // Iterar sobre los datos de las mascotas y crear los marcadores
                data.forEach(mascota => {
                    var marker = new google.maps.Marker({
                        position: { lat: parseFloat(mascota.lat), lng: parseFloat(mascota.lng) },
                        map: map,
                        animation: google.maps.Animation.DROP,
                        title: mascota.nombre,
                        icon: {
                            url: "Imagenes/icono3.png", // Ruta de la imagen en tu proyecto
                            scaledSize: new google.maps.Size(32, 32), // Ajusta el tamaño del icono
                            origin: new google.maps.Point(0, 0), // Origen de la imagen
                            anchor: new google.maps.Point(16, 32) // Punto de anclaje (mitad del ancho y toda la altura)
                        }
                    });

                    // Crear el contenido del InfoWindow con la información de la mascota
                    var infowindowContent = `
                    <div style="font-family: 'Poppins', sans-serif; padding: 15px; max-width: 320px; background: linear-gradient(135deg, #f5f7fa, #c3cfe2); border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);">
                        <h3 style="margin-top: 0; color: #34495E; font-size: 1.4em; text-align: center; border-bottom: 2px solid #1ABC9C; padding-bottom: 10px;">${mascota.nombre}</h3>
                        <p style="margin: 8px 0; color: #2C3E50; font-weight: bold;"><i class="fas fa-info-circle" style="color: #1ABC9C;"></i> Descripción: <span style="font-weight: normal;">${mascota.descripcion}</span></p>
                        <p style="margin: 8px 0; color: #2C3E50; font-weight: bold;"><i class="fas fa-flag" style="color: #E74C3C;"></i> Estado: <span style="font-weight: normal;">${mascota.tipo}</span></p>
                        <p style="margin: 8px 0; color: #2C3E50; font-weight: bold;"><i class="fas fa-paw" style="color: #F39C12;"></i> Animal: <span style="font-weight: normal;">${mascota.tipo_animal}</span></p>
                        ${mascota.imagen ? '<div style="text-align: center; margin: 15px 0;"><img src="' + mascota.imagen + '" alt="Imagen de la mascota" style="width: 120px; height: 120px; object-fit: cover; border-radius: 50%; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);"></div>' : ''}
                        <button style="background-color: #1ABC9C; color: white; border: none; padding: 12px 18px; border-radius: 25px; cursor: pointer; font-size: 1em; font-weight: bold; width: 100%; text-align: center; transition: background-color 0.3s ease;" 
                            onmouseover="this.style.backgroundColor='#16A085';" 
                            onmouseout="this.style.backgroundColor='#1ABC9C';" 
                            onclick="window.location.href = 'iniciose.html';">
                            <i class="fas fa-comment-alt"></i> Chatear con el dueño
                        </button>
                    </div>
                `;
                
                    
                    function iniciarChat(receptorId) {
                        window.location.href = `chat.php?receptor_id=${receptorId}`;
                    }
    
                    var infowindow = new google.maps.InfoWindow({
                        content: infowindowContent
                    });
    
                    // Abrir el InfoWindow cuando se hace clic en el marcador
                    marker.addListener('click', function() {
                        infowindow.open(map, marker);
                    });
                });
            })
            .catch(error => {
                console.error('Error al cargar los marcadores:', error); // Mostrar errores de red o de JSON
            });
        });
    </script>
    </section>
</body>
</html>
