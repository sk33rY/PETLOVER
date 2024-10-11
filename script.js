document.addEventListener("DOMContentLoaded", function() {
    // Inicializa el mapa cuando la página esté lista
    var map = new google.maps.Map(document.getElementById('mapa'), {
        center: { lat: 4.7352573, lng: -74.0182495}, // Coordenadas de inicio del mapa
        zoom: 8
    });

    // Cargar los marcadores de las mascotas desde la base de datos
    fetch('cargar_mascota.php')
        .then(response => response.json()) // Convertir la respuesta en formato JSON
        .then(data => {
            // Iterar sobre los datos de las mascotas y crear los marcadores
            data.forEach(mascota => {
                // Crear un marcador en el mapa para cada mascota
                var marker = new google.maps.Marker({
                    position: { lat: parseFloat(mascota.lat), lng: parseFloat(mascota.lng) }, // Coordenadas de la mascota
                    map: map, // Asignar el mapa
                    title: mascota.nombre, // Título del marcador
                    icon: "https://developers.google.com/maps/documentation/javascript/examples/full/images/beachflag.png" // Ícono del marcador
                });

                // Contenido del InfoWindow para mostrar detalles de la mascota
                var infowindowContent = `
                    <h3>${mascota.nombre}</h3>
                    <p>${mascota.descripcion}</p>
                    <p>Estado: ${mascota.tipo}</p>
                `;

                // Si la mascota tiene una imagen, la agregamos al InfoWindow
                if (mascota.imagen) {
                    infowindowContent += `<img src="data:image/jpeg;base64,${mascota.imagen}" alt="Imagen de la mascota" style="width:100px;height:auto;">`;
                }

                // Crear un InfoWindow con la información de la mascota
                var infowindow = new google.maps.InfoWindow({
                    content: infowindowContent
                });

                // Abrir el InfoWindow al hacer clic en el marcador
                marker.addListener('click', function() {
                    infowindow.open(map, marker);
                });
            });
        })
        .catch(error => console.error('Error al cargar los marcadores:', error)); // Manejo de errores en caso de que falle la carga
});