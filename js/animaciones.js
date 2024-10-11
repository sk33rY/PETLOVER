// Función para abrir/cerrar el menú lateral
function toggleMenu() {
    const sidebar = document.getElementById('sidebar');
    const screenWidth = window.innerWidth;

    // Solo alterna el menú en pantallas pequeñas
    if (screenWidth <= 768) {
        sidebar.classList.toggle('active');
    }
}
