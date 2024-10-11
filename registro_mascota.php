
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Mascota</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="estilos/registromascota.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Registrar Mascota</h1>

        <!-- Formulario Multi-step -->
        <form id="multiStepForm" enctype="multipart/form-data" method="POST" action="guardar_mascota.php">
            <!-- Paso 1: Tipo -->
            <div class="step active">
                <h3>Paso 1: ¿Perdiste o encontraste una mascota?</h3>
                <div class="option-buttons">
                    <div class="icon-option" data-target="tipo">
                        <img src="Imagenes/perdido2.png" alt="Perdido">
                        <p>Perdí una mascota</p>
                        <input type="radio" name="tipo" value="perdido" class="d-none" required>
                    </div>
                    <div class="icon-option" data-target="tipo">
                        <img src="Imagenes/encontrado2.png" alt="Encontrado">
                        <p>Encontré una mascota</p>
                        <input type="radio" name="tipo" value="encontrado" class="d-none" required>
                    </div>
                </div>
                <div class="btn-nav">
                    <button type="button" class="btn btn-primary" id="nextBtn">Siguiente</button>
                </div>
            </div>

            <!-- Paso 2: Tipo de animal -->
            <div class="step">
                <h3>Paso 2: Tipo de Animal</h3>
                <div class="option-buttons">
                    <div class="icon-option" id="option-perro" data-target="tipo_animal">
                        <img src="Imagenes/perritoicono.png" alt="Perro">
                        <p>Perro</p>
                        <input type="radio" name="tipo_animal" value="perro" class="d-none" required>
                    </div>
                    <div class="icon-option" id="option-gato" data-target="tipo_animal">
                        <img src="Imagenes/gato.png" alt="Gato">
                        <p>Gato</p>
                        <input type="radio" name="tipo_animal" value="gato" class="d-none" required>
                    </div>
                    <div class="icon-option" id="option-otro" data-target="tipo_animal">
                        <img src="Imagenes/animales2.png" alt="Otro">
                        <p>Otro</p>
                        <input type="radio" name="tipo_animal" value="otro" class="d-none" required>
                    </div>
                </div>
                <div class="btn-nav">
                    <button type="button" class="btn btn-secondary" id="prevBtn">Anterior</button>
                    <button type="button" class="btn btn-primary" id="nextBtn">Siguiente</button>
                </div>
            </div>

            <!-- Paso 3: Raza -->
            <div class="step">
                <h3>Paso 3: Selección de Raza</h3>
                <div class="mb-3">
                    <select name="raza" id="raza" class="form-select" required>
                        <option value="" disabled selected>Seleccione una raza</option>
                    </select>
                </div>
                <div class="btn-nav">
                    <button type="button" class="btn btn-secondary" id="prevBtn">Anterior</button>
                    <button type="button" class="btn btn-primary" id="nextBtn">Siguiente</button>
                </div>
            </div>

            <!-- Paso 4: Color -->
            <div class="step">
                <h3>Paso 4: Selección de Color</h3>
                <div class="mb-3">
                    <select name="color" id="color" class="form-select" required>
                        <option value="" disabled selected>Seleccione un color</option>
                        <option value="negro">Negro</option>
                        <option value="blanco">Blanco</option>
                        <option value="marron">Marrón</option>
                        <option value="gris">Gris</option>
                        <option value="dorado">Dorado</option>
                        <option value="manchado">Manchado</option>
                    </select>
                </div>
                <div class="btn-nav">
                    <button type="button" class="btn btn-secondary" id="prevBtn">Anterior</button>
                    <button type="button" class="btn btn-primary" id="nextBtn">Siguiente</button>
                </div>
            </div>

            <!-- Resto de los pasos... -->
            <!-- Paso 5: Nombre -->
            <div class="step">
                <h3>Paso 5: Nombre</h3>
                <div class="mb-3">
                    <input type="text" class="form-control" name="nombre" placeholder="Nombre de la mascota" required>
                </div>
                <div class="btn-nav">
                    <button type="button" class="btn btn-secondary" id="prevBtn">Anterior</button>
                    <button type="button" class="btn btn-primary" id="nextBtn">Siguiente</button>
                </div>
            </div>

            <!-- Paso 6: Sexo -->
            <div class="step">
                <h3>Paso 6: Sexo</h3>
                <div class="option-buttons">
                    <div class="icon-option">
                        <img src="img/macho.png" alt="Macho">
                        <p>Macho</p>
                        <input type="radio" name="sexo" value="macho" class="d-none" required>
                    </div>
                    <div class="icon-option">
                        <img src="img/hembra.png" alt="Hembra">
                        <p>Hembra</p>
                        <input type="radio" name="sexo" value="hembra" class="d-none" required>
                    </div>
                </div>
                <div class="btn-nav">
                    <button type="button" class="btn btn-secondary" id="prevBtn">Anterior</button>
                    <button type="button" class="btn btn-primary" id="nextBtn">Siguiente</button>
                </div>
            </div>

            <!-- Paso 7: Tamaño -->
            <div class="step">
                <h3>Paso 7: Tamaño</h3>
                <div class="option-buttons">
                    <div class="icon-option">
                        <img src="img/pequeno.png" alt="Pequeño">
                        <p>Pequeño</p>
                        <input type="radio" name="tamano" value="pequeño" class="d-none" required>
                    </div>
                    <div class="icon-option">
                        <img src="img/mediano.png" alt="Mediano">
                        <p>Mediano</p>
                        <input type="radio" name="tamano" value="mediano" class="d-none" required>
                    </div>
                    <div class="icon-option">
                        <img src="img/grande.png" alt="Grande">
                        <p>Grande</p>
                        <input type="radio" name="tamano" value="grande" class="d-none" required>
                    </div>
                </div>
                <div class="btn-nav">
                    <button type="button" class="btn btn-secondary" id="prevBtn">Anterior</button>
                    <button type="button" class="btn btn-primary" id="nextBtn">Siguiente</button>
                </div>
            </div>

            <!-- Paso 8: Descripción -->
            <div class="step">
                <h3>Paso 8: Descripción</h3>
                <div class="mb-3">
                    <textarea class="form-control" name="descripcion" placeholder="Descripción de la mascota" required></textarea>
                </div>
                <div class="btn-nav">
                    <button type="button" class="btn btn-secondary" id="prevBtn">Anterior</button>
                    <button type="button" class="btn btn-primary" id="nextBtn">Siguiente</button>
                </div>
            </div>

            <!-- Paso 9: Dirección y Mapa -->
            <div class="step">
                <h3>Paso 9: Ingresar dirección</h3>
                <div class="input-group mb-3">
                    <input type="text" id="address" class="form-control" placeholder="Ingresa la dirección">
                    <button type="button" id="geocode-btn" class="btn btn-secondary">Buscar Dirección</button>
                    <button type="button" id="ubicacion-btn" class="btn btn-outline-secondary ms-2">Obtener Mi Ubicación</button>
                </div>
                <input type="hidden" name="lat" id="lat">
                <input type="hidden" name="lng" id="lng">
                <div id="mapa" style="height: 300px;"></div>
                <div class="btn-nav">
                    <button type="button" class="btn btn-secondary" id="prevBtn">Anterior</button>
                    <button type="button" class="btn btn-primary" id="nextBtn">Siguiente</button>
                </div>
            </div>

            <!-- Paso 10: Subir imagen -->
            <div class="step">
                <h3>Paso 10: Subir imagen</h3>
                <div class="mb-3">
                    <label for="imagen" class="form-label">Subir imagen de la mascota</label>
                    <input type="file" class="form-control" name="imagen" accept="image/*" required>
                </div>
                <div class="btn-nav">
                    <button type="button" class="btn btn-secondary" id="prevBtn">Anterior</button>
                    <button type="submit" class="btn btn-success">Registrar Mascota</button>
                </div>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let currentStep = 0;
            const steps = document.querySelectorAll('.step');
            const nextBtns = document.querySelectorAll('#nextBtn');
            const prevBtns = document.querySelectorAll('#prevBtn');
            const razaSelect = document.getElementById('raza');

            let mapInitialized = false;

            function showStep(n) {
                steps.forEach((step, index) => {
                    step.classList.remove('active');
                    if (index === n) {
                        step.classList.add('active');
                    }
                });

                if (n === 8 && !mapInitialized) {
                    initMap();
                    mapInitialized = true;
                }
            }

            nextBtns.forEach(btn => {
                btn.addEventListener('click', () => {
                    if (currentStep < steps.length - 1) {
                        currentStep++;
                        showStep(currentStep);
                    }
                });
            });

            prevBtns.forEach(btn => {
                btn.addEventListener('click', () => {
                    if (currentStep > 0) {
                        currentStep--;
                        showStep(currentStep);
                    }
                });
            });

            // Lógica de selección de opciones
            document.querySelectorAll('.icon-option').forEach(option => {
                option.addEventListener('click', function() {
                    const targetName = this.getAttribute('data-target');
                    const radioInput = this.querySelector('input[type="radio"]');

                    // Desmarcar todas las opciones en este grupo
                    document.querySelectorAll(`.icon-option[data-target="${targetName}"]`).forEach(opt => {
                        opt.classList.remove('selected');
                        opt.querySelector('input[type="radio"]').checked = false;
                    });

                    // Marcar la opción seleccionada
                    this.classList.add('selected');
                    radioInput.checked = true;

                    // Cargar razas si es el paso 3
                    if (targetName === "tipo_animal") {
                        cargarRazas(radioInput.value);
                    }
                });
            });

            function cargarRazas(tipoAnimal) {
                let apiURL;
                razaSelect.innerHTML = '<option value="" disabled selected>Seleccione una raza</option>';

                if (tipoAnimal === "perro") {
                    apiURL = "https://dog.ceo/api/breeds/list/all";
                    fetch(apiURL)
                        .then(response => response.json())
                        .then(data => {
                            Object.keys(data.message).forEach(raza => {
                                const option = document.createElement('option');
                                option.value = raza;
                                option.textContent = raza.charAt(0).toUpperCase() + raza.slice(1);
                                razaSelect.appendChild(option);
                            });
                        });
                } else if (tipoAnimal === "gato") {
                    apiURL = "https://api.thecatapi.com/v1/breeds";
                    fetch(apiURL)
                        .then(response => response.json())
                        .then(data => {
                            data.forEach(raza => {
                                const option = document.createElement('option');
                                option.value = raza.id;
                                option.textContent = raza.name;
                                razaSelect.appendChild(option);
                            });
                        });
                } else {
                    const razasComunes = ["Conejo", "Hámster", "Loro", "Pez dorado", "Tortuga"];
                    razasComunes.forEach(raza => {
                        const option = document.createElement('option');
                        option.value = raza.toLowerCase();
                        option.textContent = raza;
                        razaSelect.appendChild(option);
                    });
                }
            }

            function initMap() {
                console.log("Initializing map...");
                var map = new google.maps.Map(document.getElementById('mapa'), {
                    center: {lat: 4.7352573, lng: -74.0182495},
                    zoom: 13
                });

                var geocoder = new google.maps.Geocoder();
                var marker;

                fetch('cargar_mascota.php')
                    .then(response => response.json())
                    .then(data => {
                        data.forEach(mascota => {
                            var marker = new google.maps.Marker({
                                position: {lat: parseFloat(mascota.lat), lng: parseFloat(mascota.lng)},
                                map: map,
                                title: mascota.nombre,
                                icon: {
                                    url: "Imagenes/icono3.png",
                                    scaledSize: new google.maps.Size(32, 32),
                                    origin: new google.maps.Point(0, 0),
                                    anchor: new google.maps.Point(16, 32)
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

            showStep(currentStep);
        });
    </script>

    <!-- Cargar la API de Google Maps de forma asíncrona -->
    <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA5p7pnKq5ZgMhtuARruzRY0vGWdoMhK4M&callback=initMap"></script>
</body>
</html>
