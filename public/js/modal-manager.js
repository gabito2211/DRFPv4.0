// public/js/modal-manager.js
const ModalManager = {
    abrir: function(modalId, config) {
        const modal = document.getElementById(modalId);
        const overlay = document.getElementById('modal-overlay');
        const form = document.getElementById('form-' + modalId);

        // 1. Configurar la URL de la acción (el action del form)
        if (config.action) form.action = config.action;

        // 2. Llenar los campos automáticamente por su nombre (name)
        if (config.datos) {
            Object.keys(config.datos).forEach(key => {
                const input = form.querySelector(`[name="${key}"]`);
                if (input) input.value = config.datos[key];
            });
        }

        // 3. Mostrar elementos
        modal.style.display = 'block';
        if (overlay) overlay.style.display = 'block';
        if (window.lucide) lucide.createIcons();
    },

    cerrar: function(modalId) {
        document.getElementById(modalId).style.display = 'none';
        const overlay = document.getElementById('modal-overlay');
        if (overlay) overlay.style.display = 'none';
    }
};

// Configurar el overlay para que cierre cualquier modal abierto al hacer clic
document.addEventListener('DOMContentLoaded', () => {
    const overlay = document.getElementById('modal-overlay');
    if(overlay) {
        overlay.addEventListener('click', () => {
            // Cierra todos los modales que estén visibles
            document.querySelectorAll('.contenedor-form[style*="display: block"]').forEach(m => {
                m.style.display = 'none';
            });
            overlay.style.display = 'none';
        });
    }
});

