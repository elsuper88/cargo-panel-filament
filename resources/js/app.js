// Importar el fix de NativePHP ANTES que cualquier otra cosa
import './nativephp-fix';

import './bootstrap';

// Fix para dropdowns en NativePHP/Electron
document.addEventListener('DOMContentLoaded', function() {
    // Forzar que todos los dropdowns estén cerrados al cargar
    const dropdowns = document.querySelectorAll('.fi-dropdown-panel');
    dropdowns.forEach(dropdown => {
        dropdown.style.display = 'none';
        dropdown.setAttribute('data-state', 'closed');
    });

    // Manejar clicks en botones de dropdown
    document.addEventListener('click', function(e) {
        const dropdownButton = e.target.closest('[data-dropdown-toggle]');
        if (dropdownButton) {
            const dropdownPanel = dropdownButton.nextElementSibling;
            if (dropdownPanel && dropdownPanel.classList.contains('fi-dropdown-panel')) {
                const isOpen = dropdownPanel.getAttribute('data-state') === 'open';
                
                // Cerrar todos los dropdowns primero
                document.querySelectorAll('.fi-dropdown-panel').forEach(panel => {
                    panel.style.display = 'none';
                    panel.setAttribute('data-state', 'closed');
                });
                
                // Abrir el dropdown clickeado si estaba cerrado
                if (!isOpen) {
                    dropdownPanel.style.display = 'block';
                    dropdownPanel.setAttribute('data-state', 'open');
                }
            }
        }
    });

    // Cerrar dropdowns al hacer click fuera
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.fi-dropdown')) {
            document.querySelectorAll('.fi-dropdown-panel').forEach(panel => {
                panel.style.display = 'none';
                panel.setAttribute('data-state', 'closed');
            });
        }
    });
});
