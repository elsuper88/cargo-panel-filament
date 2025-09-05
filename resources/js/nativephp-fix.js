// Fix para js-md5 en NativePHP/Electron
// Solución basada en: https://github.com/filamentphp/filament/discussions/12388
// Este archivo debe ejecutarse ANTES que cualquier script de Filament

// Establecer la variable global inmediatamente
window.isNode = false;

// También establecer otras variables que js-md5 podría buscar
if (typeof global === 'undefined') {
    window.global = window;
}

if (typeof Buffer === 'undefined') {
    window.Buffer = {
        isBuffer: function() { return false; }
    };
}

// Fix adicional para Alpine.js en NativePHP
document.addEventListener('alpine:init', () => {
    // Asegurar que Alpine.js funcione correctamente en Electron
    window.Alpine = window.Alpine || {};
});

console.log('NativePHP fix aplicado: window.isNode = false');
