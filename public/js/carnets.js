// --- FUNCIONES GLOBALES (Fuera de cualquier bloque para que sean accesibles) ---

function abrirFormulario(titulo) {
    const formContainer = document.getElementById('form-container');
    if (formContainer) {
        formContainer.style.display = 'block';
        formContainer.scrollIntoView({ behavior: 'smooth' });
    }
    const h2 = document.querySelector('#form-container h2');
    if (h2) h2.innerText = titulo;
}

function rellenarCamposBase(cedula, numCarnet, profesion) {
    const idInput = document.getElementById('id-card');
    if (idInput) {
        idInput.value = cedula;
        document.getElementById('num-carnet').value = numCarnet;
        document.getElementById('profesion').value = profesion;
        // Disparamos manualmente el evento para que busque los datos
        buscarPersona(cedula);
    }
}

function buscarPersona(cedula) {
    if (cedula.length > 4) {
        fetch(`/buscar-persona/${cedula}`)
            .then(response => response.json())
            .then(data => {
                if (data.status === 'encontrado') {
                    document.getElementById('fullname').value = data.datos.nombre;
                    document.getElementById('ubicacion').value = data.datos.comunidad;
                    document.getElementById('direccion').value = data.datos.direccion;
                    document.getElementById('telefono').value = data.datos.telefono;
                    document.getElementById('telefono2').value = data.datos.telefono2;
                    document.getElementById('gmail').value = data.datos.correo;

                    if (typeof notificarEncontrado === 'function') {
                        notificarEncontrado();
                    }
                }
            })
            .catch(error => console.error('Error en fetch:', error));
    }
}

// --- CONFIGURACIÓN DE EVENTOS (Al cargar el DOM) ---
document.addEventListener('DOMContentLoaded', function() {
    // 1. Evento para el campo Cédula
    const idCardInput = document.getElementById('id-card');
    if (idCardInput) {
        idCardInput.addEventListener('blur', function() {
            buscarPersona(this.value);
        });
    }

    // 2. Cálculo de Vencimiento
    const inputExp = document.getElementById('exp');
    const inputVenc = document.getElementById('venc');
    if (inputExp && inputVenc) {
        inputExp.addEventListener('change', function() {
            let fechaExp = new Date(this.value);
            fechaExp.setFullYear(fechaExp.getFullYear() + 5);
            inputVenc.value = fechaExp.toISOString().split('T')[0];
        });
    }
});