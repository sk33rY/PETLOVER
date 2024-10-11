<?php
include("header_login.php");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PETLOVER</title>
    <link rel="stylesheet" href="estilos/principalsesion.css">
    <style>
        /* Estilos para el botón del menú de hamburguesa */
        #btn-menu {
            display: none;
            font-size: 24px;
            cursor: pointer;
            color: var(--color-principal);
            margin-right: 10px;
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
    <!-- Menú lateral -->
    <nav id="menu-lateral" class="menu-lateral">
        <ul>
            <li><a href="indexfinal.html">Inicio</a></li>
            <li><a href="mapa_marcadoreslogin.php">Mapa de búsqueda</a></li>
            <li><a href="catalogo.php">Busca a tu mascota</a></li>
            <li><a href="registro_mascota.php">Registra tu mascota</a></li>
            <li><a href="catalogo_adopciones.php">Adopciones</a></li>
            <li><a href="bandeja_mensajes.php">Mis chats</a></li>
        </ul>
    </nav>

    <!-- Sección principal -->
    <main>
        <section class="seccion-1">
            <div class="texto">
                <h1 class="titulo-principal">¿Cuál es el nombre de tu mascota?</h1>
                <form action="catalogo.php" method="GET">
                    <input type="text" name="nombre" placeholder="Ingresa el nombre de tu mascota" required>
                    <button type="submit" class="btn-1">Buscar mascota</button>
                </form>
            </div>
        </section>

        <section class="about">
            <div class="about-content">
                <div class="about-1">
                    <h3>¿Cómo encontramos tu mascota?</h3>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Expedita unde laborum placeat...</p>
                </div>
                <div class="about-2">
                    <h3>Nuestro equipo</h3>
                    <div class="about-img">
                        <img src="Imagenes/valogo.png" alt="Valeria Fuentes">
                        <div class="about-txt">
                            <h4>Valeria Fuentes Salcedo</h4>
                            <p>Desarrollador de software</p>
                            <p>Ingeniería de Sistemas</p>
                        </div>
                    </div>
                    <div class="about-img">
                        <img src="Imagenes/joseph.png" alt="Joseph Rojas">
                        <div class="about-txt">
                            <h4>Joseph Rojas</h4>
                            <p>Desarrollador de software</p>
                            <p>Ingeniería de Sistemas</p>
                        </div>
                    </div>
                    <div class="about-img">
                        <img src="Imagenes/alejadnro.png" alt="Alejandro Santana">
                        <div class="about-txt">
                            <h4>Alejandro Santana</h4>
                            <p>Desarrollador de software</p>
                            <p>Ingeniería de Sistemas</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- Scripts de Ionicons -->
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>

</html>
