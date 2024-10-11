<?php
include("header_login.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PETLOVER</title>
    <link rel="stylesheet" href="estilos/mapa_marcadores_login.css">
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA5p7pnKq5ZgMhtuARruzRY0vGWdoMhK4M"></script>
    <style>
        /* Mapa ocupa el 100% del ancho y 80% del alto de la pantalla */
        #mapa {
            height: 80vh; /* Altura ajustada al 80% de la altura de la ventana */
            width: 100%; /* Ancho completo del contenedor */
            border-radius: 0.375rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
        }

        /* Otros estilos */
        body, html {
            height: 100%;
            margin: 0;
            padding: 0;
        }

        /* Sección principal */
        main {
            margin-top: 80px;
            padding: 0 20px;
        }

        /* Ajuste del contenedor del mapa */
        .mapa-container {
            width: 100%;
            max-width: 1280px;
            margin: 0 auto;
            padding: 20px;
        }

        /* Menú lateral */
        .menu-lateral {
            position: fixed;
            left: 0;
            top: 0;
            width: 250px;
            height: 100%;
            background-color: #333;
            color: #fff;
            transform: translateX(-100%);
            transition: transform 0.3s ease;
            z-index: 1000;
            padding-top: 60px;
        }

        .menu-lateral ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }

        .menu-lateral ul li {
            padding: 15px;
            border-bottom: 1px solid #444;
        }

        .menu-lateral ul li a {
            color: #fff;
            text-decoration: none;
        }

        /* Mostrar menú cuando está activo */
        .menu-lateral.active {
            transform: translateX(0);
        }

        /* Menú desplegable */
        .dropdown-content {
            display: none;
            position: absolute;
            top: 100%;
            right: 0;
            background-color: var(--background-color);
            box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
            z-index: 1;
            border-radius: 5px;
            overflow: hidden;
        }

        .dropdown-content a {
            color: var(--color-texto);
            padding: 12px 16px;
            text-decoration: none;
            display: block;
            text-align: left;
        }
        /* Estilo del nombre dentro del menú desplegable */
        .dropdown-username {
            display: block;
            padding: 12px 16px;
            font-weight: bold;
            color: var(--color-texto);
            border-bottom: 1px solid var(--color-principal); /* Línea separadora */
            background-color: var(--background-color);
            text-align: left;
        }


        .dropdown-content a:hover {
            background-color: var(--color-principal);
            color: var(--color-texto);
        }

        .show {
            display: block;
        }

        .logo {
            display: flex;
            align-items: center;
        }

        #btn-logout {
            margin-left: 10px;
        }

        @media (max-width: 768px) {
            #btn-menu {
                display: block;
            }
            .menu-opciones {
                display: none;
            }
        }
    </style>
</head>
<body>
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