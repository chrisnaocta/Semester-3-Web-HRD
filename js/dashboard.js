document.addEventListener('DOMContentLoaded', function() {
    const toggleButton = document.querySelector('.toggle-sidebar');
    const sidebar = document.querySelector('.sidebar');
    const header = document.querySelector('header');
    const content = document.querySelector('.content');
    const footer = document.querySelector('footer');

    // Toggle sidebar visibility
    toggleButton.addEventListener('click', function() {
        sidebar.classList.toggle('collapsed');
        header.classList.toggle('collapsed');
        content.classList.toggle('collapsed');
        footer.classList.toggle('collapsed');
    });

    // Handle sub-menu toggling
    const menuToggles = document.querySelectorAll('.menu-toggle');
    menuToggles.forEach(function(menuToggle) {
        menuToggle.addEventListener('click', function() {
            const subMenu = this.nextElementSibling;
            subMenu.classList.toggle('open');
            this.querySelector('.arrow').classList.toggle('open');
        });
    });
});
