document.addEventListener('DOMContentLoaded', () => {
    const btnToggle = document.getElementById('btn-toggle-form');
    const btnCerrar = document.getElementById('btn-cerrar-form');
    const btnCancelar = document.getElementById('btn-cancelar');
    const formContainer = document.getElementById('form-container');
    const dateInput = document.getElementById('reg-date');

    // Función para alternar formulario
    const toggleForm = () => {
        const isHidden = window.getComputedStyle(formContainer).display === 'none';
        formContainer.style.display = isHidden ? 'block' : 'none';
        
        // Cambiar texto e icono del botón principal
        if(isHidden) {
            btnToggle.innerHTML = '<i data-lucide="minus-circle"></i> <span>Ocultar Formulario</span>';
        } else {
            btnToggle.innerHTML = '<i data-lucide="plus-circle"></i> <span>Nuevo Registro</span>';
        }
        lucide.createIcons();
    };

    btnToggle.addEventListener('click', toggleForm);
    btnCerrar.addEventListener('click', toggleForm);
    btnCancelar.addEventListener('click', toggleForm);

    // Fecha actual por defecto
    if (dateInput) {
        dateInput.value = new Date().toISOString().split('T')[0];
    }
});
