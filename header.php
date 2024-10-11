<link rel="stylesheet" href="estilos/header.css">
<header>
        <div class="contenedor">
            <div class="logo">
                <ion-icon name="ionicons ion-map"></ion-icon>
                <span>PETLOVER</span>
            </div>

            <div class="menu-opciones">
                <ul>
                    <li>
                        <a href="index.html">Home</a>
                    </li>
                    <li>
                        <a href="mapa_marcadores.php">Mapa de busqueda</a>
                    </li>
                    <li>
                        <a href="catalogo.php">Busca a tu mascota</a>
                    </li>
                    <li>
                        <a href="registro_mascota.php">Registrar mascota</a>
                    </li>
                    <li>
                        <a href="catalogo_adopciones.php">Adopciones</a>
                    </li>
                </ul>
            </div>
            <div class="controles-usuario">
                <button id="btn-sign-up">Regístrate</button>
                <ion-icon id="btn-menu" name="menu"></ion-icon>
            </div>
            
            
        </div>
    </header>
<script>

const menuOpciones = document.querySelector(".menu-opciones");
const btnSignUp = document.getElementById("btn-sign-up");
const header = document.querySelector("header");
const controlesUsuario = document.querySelector(".controles-usuario");
const contenedor = document.querySelector(".contenedor");
const btnMenu = document.getElementById("btn-menu");

// Añade el evento click al botón de "Regístrate"
document.getElementById('btn-sign-up').addEventListener('click', function() {
    // Redirige a la página de inicio de sesión o registro
    window.location.href = 'iniciose.html';
});

const responsiveY = ()=>{
    if(window.innerHeight<=362){
        if(menuOpciones.classList.contains("mostrar"))
            menuOpciones.classList.add("min");
        else
            menuOpciones.classList.remove("min");
    }
    else{
        menuOpciones.classList.remove("min");
    }
};
const responsive = ()=>{
    if(window.innerWidth<=865){
        menuOpciones.children[0].appendChild(btnSignUp);
        header.appendChild(menuOpciones);
    }else{
        controlesUsuario.appendChild(btnSignUp);
        contenedor.appendChild(menuOpciones);
    }

    responsiveY();
}

btnMenu.addEventListener("click",()=>{
    menuOpciones.classList.toggle("mostrar");
    responsiveY();
});
responsive();

window.addEventListener("resize",responsive);

</script>