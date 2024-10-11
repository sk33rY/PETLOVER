let currentStep = 0;
const steps = document.querySelectorAll('.step');
const nextBtns = document.querySelectorAll('#nextBtn');
const prevBtns = document.querySelectorAll('#prevBtn');

// Variable para controlar si el mapa ya ha sido inicializado
let mapInitialized = false;

// Mostrar el paso inicial
function showStep(n) {
    steps.forEach((step, index) => {
        step.classList.remove('active');
        if (index === n) {
            step.classList.add('active');
        }
    });

    // Inicializar el mapa si estamos en el paso correspondiente y no se ha inicializado antes
    if (n === 6 && !mapInitialized) {
        initMap();
        mapInitialized = true;
    }
}

// Avanzar al siguiente paso
nextBtns.forEach(btn => {
    btn.addEventListener('click', () => {
        if (currentStep < steps.length - 1) {
            currentStep++;
            showStep(currentStep);
        }
    });
});

// Retroceder al paso anterior
prevBtns.forEach(btn => {
    btn.addEventListener('click', () => {
        if (currentStep > 0) {
            currentStep--;
            showStep(currentStep);
        }
    });
});

// Funciones relacionadas con el mapa
function initMap() {
    var map = new google.maps.Map(document.getElementById('mapa'), {
        center: {lat: 4.7352573, lng: -74.0182495},
        zoom: 13
    });

    var geocoder = new google.maps.Geocoder();
    var marker;

    // Cargar los marcadores desde la base de datos
    fetch('cargar_mascota.php')
        .then(response => response.json())
        .then(data => {
            data.forEach(mascota => {
                var marker = new google.maps.Marker({
                    position: {lat: parseFloat(mascota.lat), lng: parseFloat(mascota.lng)},
                    map: map,
                    title: mascota.nombre,
                    icon: {
                        url: "Imagenes/icono3.png", // Ruta de la imagen en tu proyecto
                        scaledSize: new google.maps.Size(32, 32), // Ajusta el tamaño del icono
                        origin: new google.maps.Point(0, 0), // Origen de la imagen
                        anchor: new google.maps.Point(16, 32) // Punto de anclaje (mitad del ancho y toda la altura)
                    }
                });

                var infoWindow = new google.maps.InfoWindow({
                    content: `<h3>${mascota.nombre}</h3><p>${mascota.descripcion}</p><p>${mascota.tipo}</p>`
                });

                marker.addListener('click', function() {
                    infoWindow.open(map, marker);
                });
            });
        });

    document.getElementById('geocode-btn').addEventListener('click', function() {
        geocodeAddress(geocoder, map);
    });

    document.getElementById('ubicacion-btn').addEventListener('click', function() {
        obtenerUbicacion(map);
    });

    map.addListener('click', function(event) {
        var lat = event.latLng.lat();
        var lng = event.latLng.lng();

        document.getElementById('lat').value = lat;
        document.getElementById('lng').value = lng;

        if (marker) {
            marker.setPosition(event.latLng);
        } else {
            marker = new google.maps.Marker({
                position: event.latLng,
                map: map
            });
        }
    });
}

function geocodeAddress(geocoder, map) {
    var address = document.getElementById('address').value;
    geocoder.geocode({'address': address}, function(results, status) {
        if (status === 'OK') {
            map.setCenter(results[0].geometry.location);
            var marker = new google.maps.Marker({
                map: map,
                position: results[0].geometry.location
            });
            document.getElementById('lat').value = results[0].geometry.location.lat();
            document.getElementById('lng').value = results[0].geometry.location.lng();
        } else {
            alert('Geocode no tuvo éxito por la siguiente razón: ' + status);
        }
    });
}

function obtenerUbicacion(map) {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
            var lat = position.coords.latitude;
            var lng = position.coords.longitude;
            document.getElementById('lat').value = lat;
            document.getElementById('lng').value = lng;

            var marker = new google.maps.Marker({
                position: {lat: lat, lng: lng},
                map: map
            });

            map.setCenter({lat: lat, lng: lng});
            map.setZoom(15);

        }, function(error) {
            switch(error.code) {
                case error.PERMISSION_DENIED:
                    alert("Usuario negó el permiso para la geolocalización.");
                    break;
                case error.POSITION_UNAVAILABLE:
                    alert("La información de la ubicación no está disponible.");
                    break;
                case error.TIMEOUT:
                    alert("La solicitud para obtener la ubicación ha expirado.");
                    break;
                case error.UNKNOWN_ERROR:
                    alert("Se ha producido un error desconocido.");
                    break;
            }
        });
    } else {
        alert("La geolocalización no es compatible con este navegador.");
    }
}

// Mostrar el primer paso al cargar la página
showStep(currentStep);
