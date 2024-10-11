document.addEventListener("DOMContentLoaded", function () {
    const menuToggle = document.querySelector("#menu-toggle");
    const navbar = document.querySelector(".navbar ul");

    menuToggle.addEventListener("change", function () {
        if (menuToggle.checked) {
            navbar.style.display = "flex"; // Mostrar el menú cuando el checkbox está activo
        } else {
            navbar.style.display = "none"; // Ocultar el menú cuando el checkbox no está activo
        }
    });

    // Oculta el menú en dispositivos móviles al cargar la página
    if (window.innerWidth <= 768) {
        navbar.style.display = "none";
    }

    // Asegúrate de que el menú esté visible si la pantalla se redimensiona a tamaños mayores
    window.addEventListener("resize", function () {
        if (window.innerWidth > 768) {
            navbar.style.display = "flex"; // Mostrar el menú para pantallas grandes
        } else {
            navbar.style.display = "none"; // Ocultar el menú para pantallas pequeñas
        }
    });
});
