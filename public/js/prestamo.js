function calcularFechaDevolucion(fechaSalida, dias) {
    if (!fechaSalida) return '';
    const fecha = new Date(fechaSalida + 'T00:00:00');
    fecha.setDate(fecha.getDate() + parseInt(dias));
    
    const year = fecha.getFullYear();
    const month = String(fecha.getMonth() + 1).padStart(2, '0');
    const day = String(fecha.getDate()).padStart(2, '0');
    return `${year}-${month}-${day}`;
}

function obtenerDiasPlazo(selectId, inputId) {
    const select = document.getElementById(selectId);
    if (!select || !select.value) return null;

    if (select.value === 'otro') {
        const input = document.getElementById(inputId);
        const dias = input ? parseInt(input.value, 10) : NaN;
        return (!isNaN(dias) && dias > 0) ? dias : null;
    }

    const dias = parseInt(select.value, 10);
    return (!isNaN(dias) && dias > 0) ? dias : null;
}

function actualizarFechaDevolucionRegistro() {
    const fechaSalida = document.getElementById('fecha_salida');
    const fechaDevolucion = document.getElementById('fecha_devolucion');
    const dias = obtenerDiasPlazo('plazo_prestamo', 'plazo_personalizado');
    
    if (fechaSalida && fechaDevolucion) {
        if (fechaSalida.value && dias) {
            fechaDevolucion.value = calcularFechaDevolucion(fechaSalida.value, dias);
        } else {
            fechaDevolucion.value = '';
        }
    }
}


function abrirModalEditar(prestamo) {
    console.log('Abriendo modal con datos:', prestamo); 
    
 
    const modal = document.getElementById('modal-editar');
    if (!modal) {
        console.error('Modal no encontrado: #modal-editar');
        return;
    }
    
    const form = document.getElementById('form-editar');
    if (!form) {
        console.error('Formulario no encontrado: #form-editar');
        return;
    }
    
    try {
        
        form.action = `/prestamo/${prestamo.id}`;

        const editLector = document.getElementById('edit-lector_id');
        const editLibro = document.getElementById('edit-libro_id');
        const editFechaSalida = document.getElementById('edit-fecha_salida');
        const editCondSalida = document.getElementById('edit-condiciones_salida');
        const editCondRegreso = document.getElementById('edit-condiciones_regreso');
        
        if (editLector) editLector.value = prestamo.lector_id || '';
        if (editLibro) editLibro.value = prestamo.libro_id || '';
        if (editFechaSalida) editFechaSalida.value = prestamo.fecha_salida || '';
        if (editCondSalida) editCondSalida.value = prestamo.condiciones_salida || '';
        if (editCondRegreso) editCondRegreso.value = prestamo.condiciones_regreso || '';
        
      
        if (prestamo.fecha_salida && prestamo.fecha_devolucion) {
            const fechaSalida = new Date(prestamo.fecha_salida + 'T00:00:00');
            const fechaDevolucion = new Date(prestamo.fecha_devolucion + 'T00:00:00');
            const diffDays = Math.round((fechaDevolucion - fechaSalida) / (1000 * 60 * 60 * 24));
            const opcionesEstandar = ['15', '18', '21'];
            const editPlazo = document.getElementById('edit-plazo_prestamo');
            const editPlazoPersonalizado = document.getElementById('edit-plazo_personalizado');

            if (editPlazo && editPlazoPersonalizado) {
                if (opcionesEstandar.includes(String(diffDays))) {
                    editPlazo.value = String(diffDays);
                    editPlazoPersonalizado.style.display = 'none';
                    editPlazoPersonalizado.value = '';
                    editPlazoPersonalizado.required = false;
                } else {
                    editPlazo.value = 'otro';
                    editPlazoPersonalizado.style.display = 'block';
                    editPlazoPersonalizado.value = diffDays > 0 ? diffDays : '';
                    editPlazoPersonalizado.required = true;
                }
            }
        }
        
      
        actualizarFechaDevolucionEdicion();
        
      
        modal.style.display = 'block';
        document.body.style.overflow = 'hidden'; 
        
  
        if (typeof lucide !== 'undefined' && lucide.createIcons) {
            lucide.createIcons();
        }
        
        console.log('Modal abierto correctamente');
    } catch (error) {
        console.error('Error al abrir el modal:', error);
    }
}

function cerrarModalEditar() {
    const modal = document.getElementById('modal-editar');
    if (modal) {
        modal.style.display = 'none';
        document.body.style.overflow = ''; 
    }
    
   
    const form = document.getElementById('form-editar');
    if (form) {
        form.reset();
    }
}

function actualizarFechaDevolucionEdicion() {
    const fechaSalida = document.getElementById('edit-fecha_salida');
    const fechaDevolucion = document.getElementById('edit-fecha_devolucion');
    const dias = obtenerDiasPlazo('edit-plazo_prestamo', 'edit-plazo_personalizado');
    
    if (fechaSalida && fechaDevolucion) {
        if (fechaSalida.value && dias) {
            fechaDevolucion.value = calcularFechaDevolucion(fechaSalida.value, dias);
        } else {
            fechaDevolucion.value = '';
        }
    }
}





document.addEventListener('click', function(event) {
    const modalEditar = document.getElementById('modal-editar');
    
    if (modalEditar && event.target === modalEditar) {
        cerrarModalEditar();
    }
});



document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM cargado - inicializando préstamos');
    
  
    const fechaSalida = document.getElementById('fecha_salida');
    const plazoPrestamo = document.getElementById('plazo_prestamo');
    const fechaDevolucion = document.getElementById('fecha_devolucion');
    
    if (fechaSalida && plazoPrestamo && fechaDevolucion) {
       
        const hoy = new Date().toISOString().split('T')[0];
        fechaSalida.value = hoy;
        fechaSalida.min = hoy;
        
       
        setTimeout(function() {
            actualizarFechaDevolucionRegistro();
        }, 100);
        
     
        fechaSalida.addEventListener('change', actualizarFechaDevolucionRegistro);
        plazoPrestamo.addEventListener('change', actualizarFechaDevolucionRegistro);

        const plazoPersonalizado = document.getElementById('plazo_personalizado');
        if (plazoPersonalizado) {
            plazoPersonalizado.addEventListener('input', actualizarFechaDevolucionRegistro);
        }
    }
    
   
    const editFechaSalida = document.getElementById('edit-fecha_salida');
    const editPlazoPrestamo = document.getElementById('edit-plazo_prestamo');
    
    if (editFechaSalida) {
        editFechaSalida.addEventListener('change', actualizarFechaDevolucionEdicion);
    }
    
    if (editPlazoPrestamo) {
        editPlazoPrestamo.addEventListener('change', actualizarFechaDevolucionEdicion);

        const editPlazoPersonalizado = document.getElementById('edit-plazo_personalizado');
        if (editPlazoPersonalizado) {
            editPlazoPersonalizado.addEventListener('input', actualizarFechaDevolucionEdicion);
        }
    }
    
    console.log('Eventos inicializados correctamente');
});


  
    let prestamoIdEntregar = null;

    function abrirModalEntregar(id) {
        prestamoIdEntregar = id;
       
        const form = document.getElementById('form-entregar');
        form.action = `/prestamo/${id}/entregar`; 

        document.getElementById('entregar-condiciones_regreso').value = '';

        document.getElementById('modal-entregar').style.display = 'block';

        if (typeof lucide !== 'undefined' && lucide.createIcons) {
            lucide.createIcons();
        }
    }

    function cerrarModalEntregar() {
        document.getElementById('modal-entregar').style.display = 'none';
        prestamoIdEntregar = null;
    }


    document.getElementById('modal-entregar').addEventListener('click', function(e) {
        if (e.target === this) {
            cerrarModalEntregar();
        }
    });