// ==================== FUNCIÓN PARA CAPITALIZAR NOMBRES ====================

function capitalizarAutor(nombre) {
    if (!nombre) return '';
    
    // Dividir el nombre en palabras
    let palabras = nombre.split(' ');
    
    // Capitalizar cada palabra
    let palabrasCapitalizadas = palabras.map(palabra => {
        // Si la palabra está en mayúsculas (ej: "DR."), mantenerla
        if (palabra === palabra.toUpperCase()) {
            return palabra;
        }
        
        // Si la palabra tiene guiones (ej: "García-Márquez"), capitalizar cada parte
        if (palabra.includes('-')) {
            let partes = palabra.split('-');
            let partesCapitalizadas = partes.map(parte => {
                return parte.charAt(0).toUpperCase() + parte.slice(1).toLowerCase();
            });
            return partesCapitalizadas.join('-');
        }
        
        // Capitalizar la primera letra y poner el resto en minúsculas
        return palabra.charAt(0).toUpperCase() + palabra.slice(1).toLowerCase();
    });
    
    return palabrasCapitalizadas.join(' ');
}

// ==================== FUNCIÓN PARA CAPITALIZAR TÍTULO ====================

function capitalizarTitulo(titulo) {
    if (!titulo) return '';
    
    // Palabras que no deben capitalizarse (excepto la primera)
    const palabrasExcepto = ['de', 'la', 'los', 'las', 'del', 'el', 'en', 'y', 'o', 'u', 'con', 'sin', 'por', 'para', 'a', 'ante', 'bajo', 'cabe', 'contra', 'de', 'desde', 'durante', 'en', 'entre', 'hacia', 'hasta', 'mediante', 'para', 'por', 'según', 'sin', 'so', 'sobre', 'tras', 'vs', 'e', 'ni', 'que'];
    
    // Dividir el título en palabras
    let palabras = titulo.split(' ');
    
    // Capitalizar cada palabra
    let palabrasCapitalizadas = palabras.map((palabra, index) => {
        // Si es la primera palabra, siempre capitalizar
        if (index === 0) {
            return palabra.charAt(0).toUpperCase() + palabra.slice(1).toLowerCase();
        }
        
        // Si la palabra está en mayúsculas (ej: "HTML", "CSS"), mantenerla
        if (palabra === palabra.toUpperCase() && palabra.length > 1) {
            return palabra;
        }
        
        // Si la palabra tiene guiones (ej: "Auto-Ayuda"), capitalizar cada parte
        if (palabra.includes('-')) {
            let partes = palabra.split('-');
            let partesCapitalizadas = partes.map((parte, parteIndex) => {
                // Si es la primera parte, siempre capitalizar
                if (parteIndex === 0) {
                    return parte.charAt(0).toUpperCase() + parte.slice(1).toLowerCase();
                }
                // Si es una palabra excepto, mantener en minúsculas
                if (palabrasExcepto.includes(parte.toLowerCase())) {
                    return parte.toLowerCase();
                }
                return parte.charAt(0).toUpperCase() + parte.slice(1).toLowerCase();
            });
            return partesCapitalizadas.join('-');
        }
        
        // Si es una palabra excepto, mantener en minúsculas
        if (palabrasExcepto.includes(palabra.toLowerCase())) {
            return palabra.toLowerCase();
        }
        
        // Capitalizar la primera letra y poner el resto en minúsculas
        return palabra.charAt(0).toUpperCase() + palabra.slice(1).toLowerCase();
    });
    
    return palabrasCapitalizadas.join(' ');
}

// ==================== FUNCIÓN PARA CAPITALIZAR SOLO LA PRIMERA LETRA ====================

function capitalizarPrimeraLetra(texto) {
    if (!texto) return '';
    return texto.charAt(0).toUpperCase() + texto.slice(1).toLowerCase();
}

// ==================== FUNCIONES PARA EL FORMULARIO DE REGISTRO ====================

function verificarEstado() {
    // Obtener todos los radio buttons con nombre 'estado'
    const radios = document.querySelectorAll('#form-container input[name="estado"]');
    const contenedorDeterioro = document.getElementById('contenedor-deterioro-registro');
    const textareaObs = document.getElementById('observaciones');
    
    // Verificar cuál radio está seleccionado
    let estadoSeleccionado = null;
    radios.forEach(radio => {
        if (radio.checked) {
            estadoSeleccionado = radio.value;
        }
    });
    

    if (!contenedorDeterioro) return;
    
    // Mostrar/ocultar según el valor seleccionado
    if (estadoSeleccionado === 'deteriorado') {
        contenedorDeterioro.style.display = 'block';
        if (textareaObs) textareaObs.setAttribute('required', 'true');
        if (textareaObs) textareaObs.focus();
    } else {
        contenedorDeterioro.style.display = 'none';
        if (textareaObs) {
            textareaObs.value = '';
            textareaObs.removeAttribute('required');
        }
    }
}

// ==================== FUNCIONES PARA EL MODAL DE EDICIÓN ====================

function verificarEstadoEdicion() {
    // Obtener todos los radio buttons del modal de edición
    const radios = document.querySelectorAll('#modal-editar input[name="estado"]');
    const contenedorDeterioro = document.getElementById('edit-contenedor-deterioro');
    const textareaObs = document.getElementById('edit-observaciones');

    if (!contenedorDeterioro) return;
    
    // Verificar cuál radio está seleccionado
    let estadoSeleccionado = null;
    radios.forEach(radio => {
        if (radio.checked) {
            estadoSeleccionado = radio.value;
        }
    });
    
    if (estadoSeleccionado === 'deteriorado') {
        contenedorDeterioro.style.display = 'block';
        if (textareaObs) textareaObs.setAttribute('required', 'true');
        if (textareaObs) textareaObs.focus();
    } else {
        contenedorDeterioro.style.display = 'none';
        if (textareaObs) textareaObs.removeAttribute('required');
    }
}

// NOTE: abrirModalEditar y cerrarModalEditar se definen en inventario.blade.php
// (la única definición activa). La duplicada que estaba aquí pisaba a la inline
// y capitalizaba el autor antes de asignarlo al select, rompiendo la
// pre-selección ("Miguel de Cervantes" -> "Miguel De Cervantes" no coincide).

// ==================== FUNCIONES PARA EL MODAL DE VER ====================

function abrirModalVer(libro) {
    const modal = document.getElementById('modal-ver');
    const contenedor = document.getElementById('ver-contenido');
    
    // Función para obtener el texto legible de ubicación
    function getUbicacionTexto(ubicacionKey) {
        const ubicaciones = {
            'felixpifano': 'B.P.C.DR. Félix Pifano',
            'luisalberto': 'S.L Luis Alberto Principal-San Felipe',
            'sanjavier': 'S.L San Javier-San Felipe',
            'moralluces': 'B.C Moral y Luces-San Felipe',
            'manuelita': 'B.C Manuelita Sáenz-San Felipe',
            'ciudadela': 'B.C Ciudadela-San Felipe',
            'higueron': 'B.C Higueron-San Felipe',
            'oswaldo': 'B.P Soc.Oswaldo Méndez-Peña',
            'simonrp': 'S.L Simón Rodriguez-Peña',
            'palmar': 'B.C El Palmar-Peña',
            'josefa': 'B.P Josefa Marín de Narváez-Bolívar',
            'ingrid': 'P.P Ingrid Fernandez-Bolívar',
            'romulo': 'B.P Romulo Gallegos-Nirgia',
            'euclides': 'S.L Euclides Herrera-Nirgia',
            'simonrc': 'B.P Simón Rodríguez-Cocorote',
            'jose': 'B.P José Alfonso Daza-Paez',
            'arturo': 'S.L Arturo Uslar Pietri-Paez',
            'celsa': 'S.L Celsa Mendoza Reyes-Independencia',
            'simonrv': 'S.L Simón Rodriguez-Veroes',
            'julia': 'B.P Julia Rosa de López-Aristides Bastidas',
            'simonbm': 'B.P Simón Bolívar-Manuel Monge',
            'domingo': 'B.P Domingo Oviedo Parada-Urachiche',
            'mercedes': 'B.P Mercedes Cordido-Sucre',
            'riben': 'B.P Riben Suárez Moreno-Bruzual',
            'poapoa': 'B.C Poa Poa-Bruzual'
        };
        return ubicaciones[ubicacionKey] || ubicacionKey;
    }
    
    // Función para obtener el texto de categoría
    function getCategoriaTexto(catKey) {
        const categorias = {
            'literatura': 'Literatura',
            'historia': 'Historia',
            'medicina': 'Medicina'
        };
        return categorias[catKey] || catKey;
    }
    
    // Obtener clase CSS para el estado
    function getEstadoClass(estado) {
        switch(estado) {
            case 'bueno': return 'ver-estado-bueno';
            case 'deteriorado': return 'ver-estado-deteriorado';
            case 'almacen': return 'ver-estado-almacen';
            default: return '';
        }
    }
    
    // Función para capitalizar primera letra
    function capitalize(str) {
        if (!str) return '';
        return str.charAt(0).toUpperCase() + str.slice(1);
    }
    
    // Capitalizar el autor y título para mostrar
    const autorCapitalizado = capitalizarAutor(libro.autor || 'N/A');
    const tituloCapitalizado = capitalizarTitulo(libro.titulo || 'N/A');
    const nombreSede = libro.sede ? `${libro.sede.comunidad} - ${libro.sede.biblioteca}` : getUbicacionTexto(libro.ubicacion);
    const ubicacionFisica = libro.estanteria
        ? `Estantería ${libro.estanteria.numero} (${libro.estanteria.genero}) · Pasillo ${libro.estanteria.pasillo ? libro.estanteria.pasillo.numero : '—'} · ${libro.estanteria.pasillo && libro.estanteria.pasillo.sala ? libro.estanteria.pasillo.sala.nombre : 'Sin sala'}`
        : (libro.estado === 'almacen' ? 'En almacén' : 'Sin asignar');
    const estadoLabel = libro.estado === 'almacen' ? 'En almacén' : capitalize(libro.estado || '');
    
    // Construir HTML con los datos
    let html = `
        <div class="ver-detalle-item">
            <div class="ver-detalle-label">Cota / Clasificación:</div>
            <div class="ver-detalle-valor"><strong>${libro.cota || 'N/A'}</strong></div>
        </div>
        <div class="ver-detalle-item">
            <div class="ver-detalle-label">Título del Libro:</div>
            <div class="ver-detalle-valor">${tituloCapitalizado}</div>
        </div>
        <div class="ver-detalle-item">
            <div class="ver-detalle-label">Autor(es):</div>
            <div class="ver-detalle-valor">${autorCapitalizado}</div>
        </div>
        <div class="ver-detalle-item">
            <div class="ver-detalle-label">Volumen:</div>
            <div class="ver-detalle-valor">${libro.volumen || 'N/A'}</div>
        </div>
        <div class="ver-detalle-item">
            <div class="ver-detalle-label">Categoría:</div>
            <div class="ver-detalle-valor">${getCategoriaTexto(libro.categoria)}</div>
        </div>
        <div class="ver-detalle-item">
            <div class="ver-detalle-label">Sede:</div>
            <div class="ver-detalle-valor">${nombreSede}</div>
        </div>
        <div class="ver-detalle-item">
            <div class="ver-detalle-label">Ubicación física:</div>
            <div class="ver-detalle-valor">${ubicacionFisica}</div>
        </div>
        <div class="ver-detalle-item">
            <div class="ver-detalle-label">Estado del Libro:</div>
            <div class="ver-detalle-valor">
                <span class="ver-estado-badge ${getEstadoClass(libro.estado)}">${estadoLabel}</span>
            </div>
        </div>
    `;
    
    // Agregar detalles de deterioro si el estado es deteriorado
    if (libro.estado === 'deteriorado') {
        if (libro.observaciones) {
            html += `
            <div class="ver-detalle-item">
                <div class="ver-detalle-label">Detalles del deterioro:</div>
                <div class="ver-detalle-valor">${libro.observaciones}</div>
            </div>
            `;
        }
    }
    
    // Agregar ingreso
    html += `
        <div class="ver-detalle-item">
            <div class="ver-detalle-label">Ingreso del Libro:</div>
            <div class="ver-detalle-valor">${libro.ingreso || 'N/A'}</div>
        </div>
    `;
    
    contenedor.innerHTML = html;
    modal.style.display = 'block';
    
    // Recargar iconos si lucide está disponible
    if (typeof lucide !== 'undefined' && lucide.createIcons) {
        lucide.createIcons();
    }
}

function cerrarModalVer() {
    document.getElementById('modal-ver').style.display = 'none';
}

// ==================== FUNCIÓN PARA ELIMINAR ====================

function confirmarEliminar(formId) {
    if (confirm('¿Estás seguro de que deseas eliminar este libro? Esta acción no se puede deshacer.')) {
        document.getElementById(formId).submit();
    }
}

// ==================== CERRAR MODALES AL HACER CLIC FUERA ====================

window.onclick = function(event) {
    const modalVer = document.getElementById('modal-ver');
    const modalEditar = document.getElementById('modal-editar');
    
    if (event.target == modalVer) {
        cerrarModalVer();
    } else if (event.target == modalEditar) {
        cerrarModalEditar();
    }
}

// ==================== FUNCIÓN PARA CAPITALIZAR AL ESCRIBIR ====================

function capitalizarAlEscribir(event) {
    const input = event.target;
    const cursorPos = input.selectionStart;
    const valor = input.value;
    
    // Si se presiona espacio o enter, capitalizar la siguiente palabra
    if (event.key === ' ' || event.key === 'Enter') {
        const palabras = valor.split(' ');
        const ultimaPalabra = palabras[palabras.length - 1];
        
        // Si la última palabra tiene menos de 3 caracteres, no capitalizar
        if (ultimaPalabra.length < 3) {
            return;
        }
        
        // Capitalizar la última palabra si no está en mayúsculas
        if (ultimaPalabra !== ultimaPalabra.toUpperCase()) {
            const palabrasCapitalizadas = palabras.map((palabra, index) => {
                if (index === palabras.length - 1) {
                    return capitalizarAutor(palabra);
                }
                return palabra;
            });
            input.value = palabrasCapitalizadas.join(' ');
            
            // Mantener la posición del cursor
            input.selectionStart = cursorPos;
            input.selectionEnd = cursorPos;
        }
    }
}

// ==================== INICIALIZAR EVENTOS CUANDO EL DOM ESTÉ LISTO ====================

document.addEventListener('DOMContentLoaded', function() {
    // Para el formulario principal - asegurar que los radio buttons tengan el evento
    const radiosRegistro = document.querySelectorAll('#form-container input[name="estado"]');
    radiosRegistro.forEach(radio => {
        radio.addEventListener('change', verificarEstado);
    });
    
    // Para el modal de edición
    const radiosEdicion = document.querySelectorAll('#modal-editar input[name="estado"]');
    radiosEdicion.forEach(radio => {
        radio.addEventListener('change', verificarEstadoEdicion);
    });
    
    // Agregar evento para capitalizar el autor al escribir
    const inputAutorRegistro = document.querySelector('#form-container input[name="autor"]');
    if (inputAutorRegistro) {
        inputAutorRegistro.addEventListener('keyup', function(e) {
            // Capitalizar solo cuando se presiona espacio o enter
            if (e.key === ' ' || e.key === 'Enter') {
                this.value = capitalizarAutor(this.value);
                // Colocar el cursor al final
                this.selectionStart = this.selectionEnd = this.value.length;
            }
        });
        
        // También capitalizar al perder el foco
        inputAutorRegistro.addEventListener('blur', function() {
            this.value = capitalizarAutor(this.value);
        });
    }
    
    // Agregar evento para capitalizar el TÍTULO al escribir
    const inputTituloRegistro = document.querySelector('#form-container input[name="titulo"]');
    if (inputTituloRegistro) {
        inputTituloRegistro.addEventListener('keyup', function(e) {
            // Capitalizar solo cuando se presiona espacio o enter
            if (e.key === ' ' || e.key === 'Enter') {
                this.value = capitalizarTitulo(this.value);
                // Colocar el cursor al final
                this.selectionStart = this.selectionEnd = this.value.length;
            }
        });
        
        // También capitalizar al perder el foco
        inputTituloRegistro.addEventListener('blur', function() {
            this.value = capitalizarTitulo(this.value);
        });
    }
    
    // Agregar evento para el autor en el modal de edición
    const inputAutorEdicion = document.querySelector('#modal-editar input[name="autor"]');
    if (inputAutorEdicion) {
        inputAutorEdicion.addEventListener('keyup', function(e) {
            if (e.key === ' ' || e.key === 'Enter') {
                this.value = capitalizarAutor(this.value);
                this.selectionStart = this.selectionEnd = this.value.length;
            }
        });
        
        inputAutorEdicion.addEventListener('blur', function() {
            this.value = capitalizarAutor(this.value);
        });
    }
    
    // Agregar evento para el TÍTULO en el modal de edición
    const inputTituloEdicion = document.querySelector('#modal-editar input[name="titulo"]');
    if (inputTituloEdicion) {
        inputTituloEdicion.addEventListener('keyup', function(e) {
            if (e.key === ' ' || e.key === 'Enter') {
                this.value = capitalizarTitulo(this.value);
                this.selectionStart = this.selectionEnd = this.value.length;
            }
        });
        
        inputTituloEdicion.addEventListener('blur', function() {
            this.value = capitalizarTitulo(this.value);
        });
    }
});