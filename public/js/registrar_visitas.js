// ============================================
// FUNCIONES DE TOAST (Mensajes emergentes)
// ============================================

function mostrarToastExito(mensaje) {
    var toast = document.createElement('div');
    toast.className = 'toast-alerta';
    toast.innerHTML = `
        <div class="toast-contenido">
            <i data-lucide="check-circle" class="icono-exito"></i>
            <div class="mensaje-texto">
                <span class="titulo-msg">Operación Exitosa</span>
                <p>${mensaje}</p>
            </div>
        </div>
    `;
    document.body.appendChild(toast);
    lucide.createIcons();
    setTimeout(function() { toast.remove(); }, 3000);
}

function mostrarToastError(mensaje) {
    var toast = document.createElement('div');
    toast.className = 'toast-alerta';
    toast.style.borderLeftColor = '#d33';
    toast.innerHTML = `
        <div class="toast-contenido">
            <i data-lucide="alert-circle" style="color: #d33; width: 24px; height: 24px;"></i>
            <div class="mensaje-texto">
                <span class="titulo-msg" style="color: #d33;">Error</span>
                <p>${mensaje}</p>
            </div>
        </div>
    `;
    document.body.appendChild(toast);
    lucide.createIcons();
    setTimeout(function() { toast.remove(); }, 4000);
}

function notificarEncontrado() {
    var toast = document.createElement('div');
    toast.className = 'toast-alerta';
    toast.innerHTML = `
        <div class="toast-contenido">
            <i data-lucide="check-circle" class="icono-exito"></i>
            <div class="mensaje-texto">
                <span class="titulo-msg">Registro Encontrado</span>
                <p>Los campos se han rellenado automáticamente.</p>
            </div>
        </div>
    `;
    document.body.appendChild(toast);
    lucide.createIcons();
    setTimeout(function() { toast.remove(); }, 3000);
}

// ============================================
// VER PERSONA
// ============================================

function verPersona(id) {
    fetch('/persona/' + id + '/datos')
        .then(function(response) { return response.json(); })
        .then(function(data) {
            if (data.success) {
                var persona = data.persona;
                document.getElementById('ver-nombre').innerText = persona.nombre;
                document.getElementById('ver-cedula').innerText = persona.cedula;
                document.getElementById('ver-sexo').innerText = persona.sexo;
                document.getElementById('ver-telefono').innerText = persona.telefono;
                document.getElementById('ver-telefono2').innerText = persona.telefono2 || 'N/A';
                document.getElementById('ver-correo').innerText = persona.correo;
                document.getElementById('ver-comunidad').innerText = persona.comunidad;
                document.getElementById('ver-direccion').innerText = persona.direccion;
                
                document.getElementById('modal-ver-persona').style.display = 'flex';
                var overlay = document.getElementById('modal-overlay');
                if (overlay) overlay.style.display = 'block';
            }
        })
        .catch(function(error) { console.error('Error:', error); });
}

function cerrarModalVer() {
    document.getElementById('modal-ver-persona').style.display = 'none';
    var overlay = document.getElementById('modal-overlay');
    if (overlay) overlay.style.display = 'none';
}

// ============================================
// EDITAR PERSONA
// ============================================

function editarPersona(id) {
    fetch('/persona/' + id + '/editar')
        .then(function(response) { return response.json(); })
        .then(function(data) {
            if (data.success) {
                var persona = data.persona;
                document.getElementById('edit-persona-id').value = persona.id;
                document.getElementById('edit-cedula').value = persona.cedula;
                document.getElementById('edit-nombre').value = persona.nombre;
                document.getElementById('edit-sexo').value = persona.sexo;
                document.getElementById('edit-telefono').value = persona.telefono;
                document.getElementById('edit-telefono2').value = persona.telefono2 || '';
                document.getElementById('edit-correo').value = persona.correo;
                document.getElementById('edit-comunidad').value = persona.comunidad;
                document.getElementById('edit-direccion').value = persona.direccion;
                
                document.getElementById('modal-editar-persona').style.display = 'flex';
                var overlay = document.getElementById('modal-overlay');
                if (overlay) overlay.style.display = 'block';
            }
        })
        .catch(function(error) {
            console.error('Error:', error);
            mostrarToastError('No se pudieron cargar los datos');
        });
}

function cerrarModalEditar() {
    document.getElementById('modal-editar-persona').style.display = 'none';
    var overlay = document.getElementById('modal-overlay');
    if (overlay) overlay.style.display = 'none';
}

// ============================================
// ENVIAR FORMULARIO DE EDICIÓN
// ============================================

document.addEventListener('DOMContentLoaded', function() {
    var formEditar = document.getElementById('form-editar-persona');
    if (formEditar) {
        formEditar.addEventListener('submit', function(e) {
            e.preventDefault();
            
            var id = document.getElementById('edit-persona-id').value;
            var formData = new FormData(this);
            formData.append('_method', 'PUT');
            
            fetch('/persona/' + id, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(function(response) { return response.json(); })
            .then(function(data) {
                if (data.success) {
                    mostrarToastExito(data.message);
                    cerrarModalEditar();
                    setTimeout(function() { location.reload(); }, 1500);
                } else {
                    mostrarToastError(data.message);
                }
            })
            .catch(function(error) {
                console.error('Error:', error);
                mostrarToastError('Ocurrió un error al actualizar');
            });
        });
    }
});

// ============================================
// ELIMINAR PERSONA
// ============================================

// Eliminar Persona con SweetAlert (estilo que ya usas)
function eliminarPersona(id) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: "Se eliminará permanentemente de la Biblioteca Dr. Félix Pifano",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar',
        reverseButtons: true
    }).then(function(result) {
        if (result.isConfirmed) {
            var form = document.createElement('form');
            form.method = 'POST';
            form.action = '/persona/' + id;
            
            var token = document.createElement('input');
            token.type = 'hidden';
            token.name = '_token';
            token.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            form.appendChild(token);
            
            var method = document.createElement('input');
            method.type = 'hidden';
            method.name = '_method';
            method.value = 'DELETE';
            form.appendChild(method);
            
            document.body.appendChild(form);
            form.submit();
        }
    });
}