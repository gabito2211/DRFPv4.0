// ============================================
// FUNCIONES GLOBALES (accesibles desde onclick)
// ============================================

// Función para ir a actividades de un plan (todos los estados)
window.irActividades = function(planId) {
    if (!planId) {
        alert('Por favor, seleccione un plan vacacional primero.');
        return;
    }
    
    // Redirigir a la vista de actividades con el plan seleccionado
    window.location.href = "/actividades/plan/" + planId;
};

// Función para ir a actividades desde el selector (sin parámetro)
window.irActividadesDesdeSelector = function() {
    const planId = document.getElementById('plan_vacacional_id').value;
    if (!planId) {
        alert('Por favor, seleccione un plan vacacional primero.');
        return;
    }
    window.location.href = "/actividades/plan/" + planId;
};

// Ver historial de actividades (solo para mostrar en la misma página - OPCIONAL)
window.verHistorial = function(planId) {
    if (!planId) {
        alert('Por favor, seleccione un plan.');
        return;
    }
    
    // Obtener los detalles del plan
    fetch('/planes/' + planId + '/detalles')
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                alert(data.error);
                return;
            }
            
            // Mostrar el historial en el resumen
            mostrarHistorial(data);
            
            // Cambiar el título del resumen
            document.getElementById('plan-actual-nombre').textContent = ' - ' + data.nombre + ' (Historial)';
            
            // Actualizar el selector
            const selector = document.getElementById('plan_vacacional_id');
            if (selector) {
                selector.value = planId;
            }
            
            // Actualizar estado de botones
            actualizarEstadoBotones(data.estado);
        })
        .catch(error => {
            alert('Error al cargar el historial del plan');
            console.error(error);
        });
};

// Mostrar historial de actividades
function mostrarHistorial(data) {
    const container = document.getElementById('grafico-container');
    if (!container) return;
    
    // Actualizar los detalles
    document.getElementById('detalle-desde').textContent = formatearFecha(data.desde);
    document.getElementById('detalle-hasta').textContent = formatearFecha(data.hasta);
    document.getElementById('detalle-dias').textContent = data.dias || 0;
    document.getElementById('detalle-total').textContent = data.total_actividades || 0;
    document.getElementById('detalle-total-grande').textContent = data.total_actividades || 0;
    
    // Actualizar estado
    const estadoSpan = document.getElementById('detalle-estado');
    if (data.estado === 'completado') {
        estadoSpan.innerHTML = '<span class="badge-estatus badge-completado">✅ Completado</span>';
    } else if (data.estado === 'activo') {
        estadoSpan.innerHTML = '<span class="badge-estatus badge-activo">🔴 Activo</span>';
    } else {
        estadoSpan.innerHTML = '<span class="badge-estatus badge-inactivo">Inactivo</span>';
    }
    
    // Mostrar lista de actividades
    const listaContainer = document.getElementById('lista-actividades-container');
    const listaActividades = document.getElementById('lista-actividades');
    
    if (listaContainer && listaActividades) {
        listaContainer.style.display = 'block';
        
        const actividades = data.actividades || [];
        
        if (actividades.length === 0) {
            listaActividades.innerHTML = '<p style="color: var(--texto-suave); padding: 10px;">No hay actividades registradas.</p>';
        } else {
            let html = '';
            actividades.forEach(act => {
                html += `
                    <div class="actividad-item">
                        <span class="actividad-nombre">${act.actividad || 'Sin nombre'}</span>
                        <span class="actividad-fecha">${act.fecha || 'Sin fecha'} ${act.hora ? '- ' + act.hora : ''}</span>
                    </div>
                `;
            });
            listaActividades.innerHTML = html;
        }
    }
}

// Editar plan desde la tabla o selector
window.editarPlan = function(id) {
    if (!id) {
        alert('Por favor, seleccione un plan para editar.');
        return;
    }
    
    // Verificar si el plan está activo (no se puede editar)
    const option = document.querySelector(`#plan_vacacional_id option[value="${id}"]`);
    if (option && option.getAttribute('data-estado') === 'activo') {
        alert('⚠️ No se puede editar un plan activo.');
        return;
    }
    
    // Verificar si está completado
    if (option && option.getAttribute('data-estado') === 'completado') {
        alert('⚠️ No se puede editar un plan completado.');
        return;
    }
    
    fetch('/planes/' + id)
        .then(response => response.json())
        .then(data => {
            document.getElementById('edit_nombre_plan').value = data.nombre;
            document.getElementById('edit_desde_plan').value = data.desde;
            document.getElementById('edit_hasta_plan').value = data.hasta;
            document.getElementById('edit_activo_plan').checked = data.estado === 'activo';
            document.getElementById('form-editar-plan').action = '/planes/' + id;
            document.getElementById('modal-editar-plan').style.display = 'block';
            document.body.style.overflow = 'hidden';
        })
        .catch(error => {
            alert('Error al cargar los datos del plan');
            console.error(error);
        });
};

// Activar plan inactivo
window.activarPlan = function(id) {
    if (!id) {
        alert('Por favor, seleccione un plan para activar.');
        return;
    }
    
    // Verificar si el plan está inactivo
    const option = document.querySelector(`#plan_vacacional_id option[value="${id}"]`);
    const estado = option ? option.getAttribute('data-estado') : '';
    
    if (estado === 'completado') {
        alert('❌ No se puede activar un plan completado. Es un registro histórico.');
        return;
    }
    
    if (estado !== 'inactivo') {
        alert('❌ Solo se pueden activar planes inactivos.');
        return;
    }
    
    if (confirm('¿Está seguro de activar este plan vacacional?')) {
        fetch('/planes/' + id + '/activar', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('✅ ' + data.message);
                location.reload();
            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            alert('Error al activar el plan');
            console.error(error);
        });
    }
};

// Eliminar plan
window.eliminarPlan = function(id) {
    if (!id) {
        alert('Por favor, seleccione un plan para eliminar.');
        return;
    }
    
    // Verificar si el plan está activo
    const option = document.querySelector(`#plan_vacacional_id option[value="${id}"]`);
    if (option && option.getAttribute('data-estado') === 'activo') {
        alert('⚠️ No se puede eliminar un plan activo.');
        return;
    }
    
    // Verificar si está completado
    if (option && option.getAttribute('data-estado') === 'completado') {
        alert('⚠️ No se puede eliminar un plan completado.');
        return;
    }
    
    if (confirm('¿Está seguro de eliminar este plan vacacional?')) {
        fetch('/planes/' + id, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Plan eliminado correctamente');
                location.reload();
            } else {
                alert(data.message || 'Error al eliminar el plan');
            }
        })
        .catch(error => {
            alert('Error al eliminar el plan');
            console.error(error);
        });
    }
};

// Funciones para modales de Plan
window.cerrarModalPlan = function() {
    document.getElementById('modal-plan-vacacional').style.display = 'none';
    document.body.style.overflow = '';
};

window.cerrarModalEditarPlan = function() {
    document.getElementById('modal-editar-plan').style.display = 'none';
    document.body.style.overflow = '';
};

// ============================================
// FUNCIONES PARA ACTUALIZAR LA INFORMACIÓN DEL PLAN
// ============================================

function formatearFecha(fecha) {
    if (!fecha) return 'Fecha no disponible';
    
    if (fecha instanceof Date) {
        const dia = String(fecha.getDate()).padStart(2, '0');
        const mes = String(fecha.getMonth() + 1).padStart(2, '0');
        const anio = fecha.getFullYear();
        return `${dia}/${mes}/${anio}`;
    }
    
    if (typeof fecha === 'string') {
        const fechaObj = new Date(fecha);
        if (!isNaN(fechaObj.getTime())) {
            const dia = String(fechaObj.getDate()).padStart(2, '0');
            const mes = String(fechaObj.getMonth() + 1).padStart(2, '0');
            const anio = fechaObj.getFullYear();
            return `${dia}/${mes}/${anio}`;
        }
        
        if (fecha.match(/^\d{4}-\d{2}-\d{2}$/)) {
            const partes = fecha.split('-');
            return `${partes[2]}/${partes[1]}/${partes[0]}`;
        }
    }
    
    return String(fecha);
}

function mostrarCarga() {
    const container = document.getElementById('grafico-container');
    if (!container) return;
    
    container.innerHTML = `
        <h3 style="margin-bottom: 20px;">
            <i data-lucide="calendar-range" style="color: var(--primario);"></i> 
            Resumen de Plan Vacacional
            <span id="plan-actual-nombre" style="font-weight: normal; font-size: 0.9rem; color: var(--texto-suave);"></span>
        </h3>
        <div style="text-align: center; padding: 40px;">
            <div class="spinner"></div>
            <p style="margin-top: 15px; color: var(--texto-suave);">Cargando información del plan...</p>
        </div>
    `;
    
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
}

function mostrarVacio() {
    const container = document.getElementById('grafico-container');
    if (!container) return;
    
    container.innerHTML = `
        <h3 style="margin-bottom: 20px;">
            <i data-lucide="calendar-range" style="color: var(--primario);"></i> 
            Resumen de Plan Vacacional
            <span id="plan-actual-nombre" style="font-weight: normal; font-size: 0.9rem; color: var(--texto-suave);"></span>
        </h3>
        <div class="empty-state" id="empty-state">
            <i data-lucide="calendar" size="48" style="display: block; margin: 0 auto 15px; width: 48px; height: 48px; color: #ddd;"></i>
            <p style="font-size: 1.1rem;">Seleccione un plan vacacional para ver su resumen.</p>
        </div>
    `;
    
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
}

function renderInfoPlan(data) {
    const container = document.getElementById('grafico-container');
    if (!container) return;
    
    const nombre = data.nombre || 'Sin nombre';
    const desde = data.desde || '';
    const hasta = data.hasta || '';
    const estado = data.estado || 'inactivo';
    const dias = data.dias || 0;
    const totalActividades = data.total_actividades || 0;
    const actividades = data.actividades || [];
    
    // Ocultar el contenedor de historial
    const listaContainer = document.getElementById('lista-actividades-container');
    if (listaContainer) {
        listaContainer.style.display = 'none';
    }
    
    // Construir HTML para el estado
    let estadoHTML = '';
    if (estado === 'activo') {
        estadoHTML = '<span class="badge-estatus badge-activo">🔴 Activo</span>';
    } else if (estado === 'completado') {
        estadoHTML = '<span class="badge-estatus badge-completado">✅ Completado</span>';
    } else {
        estadoHTML = '<span class="badge-estatus badge-inactivo">Inactivo</span>';
    }
    
    container.innerHTML = `
        <h3 style="margin-bottom: 20px;">
            <i data-lucide="calendar-range" style="color: var(--primario);"></i> 
            Resumen de Plan Vacacional
            <span id="plan-actual-nombre" style="font-weight: normal; font-size: 0.9rem; color: var(--texto-suave);"> - ${nombre}</span>
        </h3>
        
        <div id="plan-detalles" style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 25px;">
            <div>
                <p style="margin: 5px 0;"><strong>Desde:</strong> <span id="detalle-desde">${formatearFecha(desde)}</span></p>
                <p style="margin: 5px 0;"><strong>Hasta:</strong> <span id="detalle-hasta">${formatearFecha(hasta)}</span></p>
                <p style="margin: 5px 0;"><strong>Días de duración:</strong> <span id="detalle-dias">${dias}</span> días</p>
                <p style="margin: 5px 0;"><strong>Total de actividades:</strong> <span id="detalle-total">${totalActividades}</span></p>
                <p style="margin: 5px 0;"><strong>Estado:</strong> 
                    <span id="detalle-estado">
                        ${estadoHTML}
                    </span>
                </p>
                ${estado === 'inactivo' ? `
                    <div style="margin-top: 10px;">
                        <button onclick="activarPlan(${data.id})" class="btn btn-verde" style="padding: 8px 16px; font-size: 0.9rem;">
                            <i data-lucide="play-circle"></i> Activar Plan
                        </button>
                    </div>
                ` : ''}
            </div>
            <div style="display: flex; align-items: center; justify-content: center; background: #f8f9fa; border-radius: 8px; padding: 10px;">
                <div style="text-align: center;">
                    <div style="font-size: 2.5rem; font-weight: 700; color: var(--primario);" id="detalle-total-grande">
                        ${totalActividades}
                    </div>
                    <div style="color: var(--texto-suave);">Actividades programadas</div>
                </div>
            </div>
        </div>
    `;
    
    // Si está completado, mostrar el historial automáticamente
    if (estado === 'completado') {
        const detallesDiv = document.getElementById('plan-detalles');
        if (detallesDiv) {
            const historialHtml = `
                <div id="lista-actividades-container" class="lista-actividades" style="display: block; margin-top: 20px; border-top: 1px solid #e9ecef; padding-top: 15px;">
                    <h4 style="margin-bottom: 10px;">📋 Historial de Actividades</h4>
                    <div id="lista-actividades">
                        ${actividades.length === 0 
                            ? '<p style="color: var(--texto-suave); padding: 10px;">No hay actividades registradas.</p>'
                            : actividades.map(act => `
                                <div class="actividad-item">
                                    <span class="actividad-nombre">${act.actividad || 'Sin nombre'}</span>
                                    <span class="actividad-fecha">${act.fecha || 'Sin fecha'} ${act.hora ? '- ' + act.hora : ''}</span>
                                </div>
                            `).join('')
                        }
                    </div>
                </div>
            `;
            detallesDiv.insertAdjacentHTML('afterend', historialHtml);
        }
    }
    
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
}

function actualizarInfoPlan(planId) {
    if (!planId) {
        mostrarVacio();
        return;
    }
    
    mostrarCarga();
    
    fetch('/planes/' + planId + '/detalles')
        .then(response => {
            if (!response.ok) {
                throw new Error('Error en la respuesta del servidor (HTTP ' + response.status + ')');
            }
            return response.json();
        })
        .then(data => {
            if (data.error) {
                alert(data.error);
                mostrarVacio();
                return;
            }
            
            renderInfoPlan(data);
            actualizarEstadoBotones(data.estado);
            
            // Actualizar el selector si es necesario
            const selector = document.getElementById('plan_vacacional_id');
            if (selector && selector.value !== String(planId)) {
                selector.value = planId;
            }
        })
        .catch(error => {
            console.error('Error al cargar los datos del plan:', error);
            const container = document.getElementById('grafico-container');
            if (container) {
                container.innerHTML = `
                    <h3 style="margin-bottom: 20px;">
                        <i data-lucide="calendar-range" style="color: var(--primario);"></i> 
                        Resumen de Plan Vacacional
                        <span id="plan-actual-nombre" style="font-weight: normal; font-size: 0.9rem; color: var(--texto-suave);"></span>
                    </h3>
                    <div class="empty-state">
                        <i data-lucide="alert-triangle" size="48" style="display: block; margin: 0 auto 15px; width: 48px; height: 48px; color: #fdcb6e;"></i>
                        <p style="font-size: 1.1rem; color: var(--texto-suave);">Error al cargar los datos del plan</p>
                        <p style="font-size: 0.9rem; color: var(--texto-suave);">${error.message}</p>
                    </div>
                `;
                if (typeof lucide !== 'undefined') lucide.createIcons();
            }
        });
}

// ============================================
// FUNCIÓN PARA ACTUALIZAR ESTADO DE BOTONES
// ============================================
function actualizarEstadoBotones(estado) {
    const esActivo = estado === 'activo';
    const esCompletado = estado === 'completado';
    const esInactivo = estado === 'inactivo';
    
    // Botones del selector
    const btnEditar = document.getElementById('btn-editar-plan');
    const btnEliminar = document.getElementById('btn-eliminar-plan');
    const btnIrActividades = document.getElementById('btn-ir-actividades');
    const btnVerHistorial = document.getElementById('btn-ver-historial-selector');
    const btnActivar = document.getElementById('btn-activar-plan');
    
    // Editar y Eliminar solo disponibles para inactivos
    [btnEditar, btnEliminar].forEach(btn => {
        if (btn) {
            const disponible = esInactivo;
            btn.disabled = !disponible;
            btn.style.opacity = disponible ? '1' : '0.5';
            btn.style.cursor = disponible ? 'pointer' : 'not-allowed';
            btn.title = disponible ? btn.title || 'Acción disponible' : 
                esActivo ? 'Plan activo - acciones deshabilitadas' : 
                esCompletado ? 'Plan completado - acciones deshabilitadas' : '';
        }
    });
    
    // Botón Activar: solo para inactivos
    if (btnActivar) {
        const disponible = esInactivo;
        btnActivar.style.display = disponible ? 'inline-flex' : 'none';
        btnActivar.disabled = !disponible;
        btnActivar.title = disponible ? 'Activar este plan' : '';
    }
    
    // Gestionar actividades: disponible para TODOS los estados
    if (btnIrActividades) {
        btnIrActividades.disabled = false;
        btnIrActividades.style.opacity = '1';
        btnIrActividades.style.cursor = 'pointer';
        btnIrActividades.title = 'Gestionar actividades del plan';
    }
    
    // Botón ver historial siempre disponible
    if (btnVerHistorial) {
        btnVerHistorial.disabled = false;
        btnVerHistorial.style.opacity = '1';
        btnVerHistorial.style.cursor = 'pointer';
        btnVerHistorial.title = 'Ver historial de actividades';
    }
    
    // Actualizar mensaje de estado
    const mensajeEstado = document.getElementById('mensaje-estado-plan');
    if (mensajeEstado) {
        if (esActivo) {
            mensajeEstado.innerHTML = '<span style="color: #065f46; font-weight: 600;">🔴 Plan activo - Gestión de actividades disponibles</span>';
        } else if (esCompletado) {
            mensajeEstado.innerHTML = '<span style="color: #1e40af; font-weight: 600;">✅ Plan completado - Solo visualización de historial</span>';
        } else {
            mensajeEstado.innerHTML = '<span style="color: #991b1b; font-weight: 600;">⛔ Plan inactivo - Todas las acciones disponibles</span>';
        }
    }
}

// ============================================
// INICIALIZACIÓN DE EVENTOS (DOMContentLoaded)
// ============================================

document.addEventListener('DOMContentLoaded', function() {
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
    
    const planSelector = document.getElementById('plan_vacacional_id');
    if (planSelector) {
        function handlePlanChange() {
            const selectedOption = planSelector.options[planSelector.selectedIndex];
            if (selectedOption && selectedOption.value) {
                const estado = selectedOption.getAttribute('data-estado') || 'inactivo';
                actualizarEstadoBotones(estado);
                actualizarInfoPlan(selectedOption.value);
            } else {
                const mensajeEstado = document.getElementById('mensaje-estado-plan');
                if (mensajeEstado) {
                    mensajeEstado.innerHTML = '<span style="color: var(--texto-suave);">Seleccione un plan para ver su estado</span>';
                }
                ['btn-editar-plan', 'btn-eliminar-plan', 'btn-ir-actividades', 'btn-activar-plan'].forEach(id => {
                    const btn = document.getElementById(id);
                    if (btn) {
                        btn.disabled = true;
                        btn.style.opacity = '0.5';
                        btn.style.cursor = 'not-allowed';
                        if (id === 'btn-activar-plan') btn.style.display = 'none';
                    }
                });
                mostrarVacio();
            }
        }
        
        planSelector.addEventListener('change', handlePlanChange);
        
        setTimeout(function() {
            handlePlanChange();
            const selectedPlanId = planSelector.value;
            if (selectedPlanId) {
                setTimeout(function() {
                    actualizarInfoPlan(selectedPlanId);
                }, 300);
            }
        }, 100);
    }
    
    // Autocompletar fechas
    const desdeInput = document.getElementById('desde_plan');
    const hastaInput = document.getElementById('hasta_plan');
    
    if (desdeInput && hastaInput) {
        desdeInput.addEventListener('change', function() {
            const fechaDesde = new Date(this.value);
            if (!isNaN(fechaDesde.getTime())) {
                const fechaHasta = new Date(fechaDesde);
                fechaHasta.setDate(fechaHasta.getDate() + 30);
                hastaInput.value = fechaHasta.toISOString().split('T')[0];
            }
        });
    }
    
    const editDesdeInput = document.getElementById('edit_desde_plan');
    const editHastaInput = document.getElementById('edit_hasta_plan');
    
    if (editDesdeInput && editHastaInput) {
        editDesdeInput.addEventListener('change', function() {
            const fechaDesde = new Date(this.value);
            if (!isNaN(fechaDesde.getTime())) {
                const fechaHasta = new Date(fechaDesde);
                fechaHasta.setDate(fechaHasta.getDate() + 30);
                editHastaInput.value = fechaHasta.toISOString().split('T')[0];
            }
        });
    }

    // Evento para el botón "Gestionar Actividades"
    const btnIrActividades = document.getElementById('btn-ir-actividades');
    if (btnIrActividades) {
        btnIrActividades.addEventListener('click', function() {
            const planId = document.getElementById('plan_vacacional_id').value;
            if (planId) {
                window.location.href = "/actividades/plan/" + planId;
            } else {
                alert('Por favor, seleccione un plan vacacional primero.');
            }
        });
    }

    // Evento para el botón "Activar Plan"
    const btnActivarPlan = document.getElementById('btn-activar-plan');
    if (btnActivarPlan) {
        btnActivarPlan.addEventListener('click', function() {
            const planId = document.getElementById('plan_vacacional_id').value;
            if (planId) {
                const option = document.querySelector(`#plan_vacacional_id option[value="${planId}"]`);
                if (option && option.getAttribute('data-estado') !== 'inactivo') {
                    alert('⚠️ Solo se pueden activar planes inactivos.');
                    return;
                }
                window.activarPlan(planId);
            } else {
                alert('Por favor, seleccione un plan para activar.');
            }
        });
    }

    // Evento para el botón "Ver Historial" del selector
    const btnVerHistorial = document.getElementById('btn-ver-historial-selector');
    if (btnVerHistorial) {
        btnVerHistorial.addEventListener('click', function() {
            const planId = document.getElementById('plan_vacacional_id').value;
            if (planId) {
                window.verHistorial(planId);
            } else {
                alert('Por favor, seleccione un plan vacacional primero.');
            }
        });
    }

    // Evento para nuevo plan
    const btnNuevoPlan = document.getElementById('btn-nuevo-plan');
    if (btnNuevoPlan) {
        btnNuevoPlan.addEventListener('click', function() {
            document.getElementById('modal-plan-vacacional').style.display = 'block';
            document.body.style.overflow = 'hidden';
        });
    }

    // Evento para editar plan desde el selector
    const btnEditarPlan = document.getElementById('btn-editar-plan');
    if (btnEditarPlan) {
        btnEditarPlan.addEventListener('click', function() {
            const planId = document.getElementById('plan_vacacional_id').value;
            if (planId) {
                const option = document.querySelector(`#plan_vacacional_id option[value="${planId}"]`);
                if (option && option.getAttribute('data-estado') === 'activo') {
                    alert('⚠️ No se puede editar un plan activo.');
                    return;
                }
                if (option && option.getAttribute('data-estado') === 'completado') {
                    alert('⚠️ No se puede editar un plan completado.');
                    return;
                }
                window.editarPlan(planId);
            } else {
                alert('Por favor, seleccione un plan para editar.');
            }
        });
    }

    // Evento para eliminar plan desde el selector
    const btnEliminarPlan = document.getElementById('btn-eliminar-plan');
    if (btnEliminarPlan) {
        btnEliminarPlan.addEventListener('click', function() {
            const planId = document.getElementById('plan_vacacional_id').value;
            if (planId) {
                const option = document.querySelector(`#plan_vacacional_id option[value="${planId}"]`);
                if (option && option.getAttribute('data-estado') === 'activo') {
                    alert('⚠️ No se puede eliminar un plan activo.');
                    return;
                }
                if (option && option.getAttribute('data-estado') === 'completado') {
                    alert('⚠️ No se puede eliminar un plan completado.');
                    return;
                }
                window.eliminarPlan(planId);
            } else {
                alert('Por favor, seleccione un plan para eliminar.');
            }
        });
    }

    // Cerrar modales con click fuera
    document.querySelectorAll('.modal-overlay').forEach(modal => {
        modal.addEventListener('click', function(e) {
            if (e.target === this) {
                this.style.display = 'none';
                document.body.style.overflow = '';
            }
        });
    });

    // Cerrar con ESC
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            document.querySelectorAll('.modal-overlay').forEach(modal => {
                if (modal.style.display === 'block') {
                    modal.style.display = 'none';
                    document.body.style.overflow = '';
                }
            });
        }
    });

    // Filtro de tabla
    const inputFiltro = document.getElementById('input-filtro-tabla');
    if (inputFiltro) {
        inputFiltro.addEventListener('keyup', function() {
            const filter = this.value.toLowerCase();
            const rows = document.querySelectorAll('#tabla-body tr');
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(filter) ? '' : 'none';
            });
        });
    }
});