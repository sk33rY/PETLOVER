document.addEventListener('DOMContentLoaded', () => {
    const btnMenu = document.getElementById('btn-menu');
    const menuLateral = document.getElementById('menu-lateral');
    const menuBtn = document.getElementById('menu-btn');
    const dropdown = document.getElementById('menu-dropdown');
    const body = document.querySelector('body');

    // Manejar el menú de hamburguesa
    btnMenu.addEventListener('click', (event) => {
        event.stopPropagation(); // Evitar que el clic cierre el menú lateral
        menuLateral.classList.toggle('active');
        body.classList.toggle('menu-active');
    });

    // Cerrar el menú lateral si se hace clic fuera de él
    window.addEventListener('click', (event) => {
        if (!menuLateral.contains(event.target) && !btnMenu.contains(event.target)) {
            menuLateral.classList.remove('active');
            body.classList.remove('menu-active');
        }
    });

    // Manejar el menú desplegable del usuario
    menuBtn.addEventListener('click', (event) => {
        event.stopPropagation(); // Evitar que el clic cierre el menú desplegable
        dropdown.classList.toggle('show');
    });

    // Cerrar el menú desplegable si se hace clic fuera de él
    window.addEventListener('click', (event) => {
        if (!menuBtn.contains(event.target) && !dropdown.contains(event.target)) {
            dropdown.classList.remove('show');
        }
    });
});
