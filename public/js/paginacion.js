/**
 * Lógica de Paginación y Filtrado para Tablas
 * Sincronizado para que el filtro reinicie la paginación.
 */

const filasPorPagina = 5;
let paginaActual = 1;
let filasFiltradas = [];

document.addEventListener('DOMContentLoaded', () => {
    const tablaBody = document.getElementById('tabla-body');
    const todasLasFilas = Array.from(tablaBody.getElementsByTagName('tr'));
    const inputFiltro = document.getElementById('input-filtro-tabla');
    
    const btnPrev = document.getElementById('btn-prev');
    const btnNext = document.getElementById('btn-next');
    const infoPaginacion = document.getElementById('info-paginacion');
    const contenedorNumeros = document.getElementById('numeros-paginas');

    // Inicialmente, las filas filtradas son todas
    filasFiltradas = todasLasFilas;

    function actualizarTabla() {
        const totalPaginas = Math.ceil(filasFiltradas.length / filasPorPagina);
        
        // Si la página actual es mayor al total tras filtrar, volver a la 1
        if (paginaActual > totalPaginas && totalPaginas > 0) paginaActual = 1;

        const inicio = (paginaActual - 1) * filasPorPagina;
        const fin = inicio + filasPorPagina;

        // Ocultar todas primero
        todasLasFilas.forEach(f => f.style.display = 'none');

        // Mostrar solo las del rango de la página actual de las filtradas
        filasFiltradas.forEach((fila, index) => {
            if (index >= inicio && index < fin) {
                fila.style.display = '';
            }
        });

        // Actualizar texto de información
        if (filasFiltradas.length === 0) {
            infoPaginacion.textContent = "No se encontraron coincidencias";
            btnPrev.disabled = true;
            btnNext.disabled = true;
            contenedorNumeros.innerHTML = '';
        } else {
            const registroFinal = Math.min(fin, filasFiltradas.length);
            infoPaginacion.textContent = `Mostrando del ${inicio + 1} al ${registroFinal} de ${filasFiltradas.length} registros`;
            
            btnPrev.disabled = paginaActual === 1;
            btnNext.disabled = paginaActual === totalPaginas || totalPaginas === 0;
            
            generarNumerosPagina(totalPaginas);
        }

        // Re-inicializar iconos de Lucide para las filas mostradas
        if (window.lucide) lucide.createIcons();
    }

    function generarNumerosPagina(total) {
        contenedorNumeros.innerHTML = '';
        for (let i = 1; i <= total; i++) {
            const boton = document.createElement('button');
            boton.textContent = i;
            boton.className = `btn-pag ${i === paginaActual ? 'active' : ''}`;
            boton.onclick = () => {
                paginaActual = i;
                actualizarTabla();
            };
            contenedorNumeros.appendChild(boton);
        }
    }

    // Evento de búsqueda
    if (inputFiltro) {
        inputFiltro.addEventListener('input', function() {
            const query = this.value.toLowerCase();
            
            filasFiltradas = todasLasFilas.filter(fila => {
                return fila.textContent.toLowerCase().includes(query);
            });

            paginaActual = 1; // Reiniciar a la primera página al buscar
            actualizarTabla();
        });
    }

    // Eventos de botones
    btnPrev.onclick = () => {
        if (paginaActual > 1) {
            paginaActual--;
            actualizarTabla();
        }
    };

    btnNext.onclick = () => {
        const totalPaginas = Math.ceil(filasFiltradas.length / filasPorPagina);
        if (paginaActual < totalPaginas) {
            paginaActual++;
            actualizarTabla();
        }
    };

    // Ejecución inicial
    actualizarTabla();
});