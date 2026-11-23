/**
 * Lógica funcional para la plantilla del Sistema de Biblioteca
 * Maneja únicamente la expansión del sidebar y la inicialización de iconos.
 */

document.addEventListener('DOMContentLoaded', () => {
    // 1. Inicializar Iconos de Lucide (si la librería está disponible)
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }

    // 2. Lógica de expansión/contracción del Sidebar (Necesario para el botón de flecha)
    const sidebar = document.getElementById('sidebar');
    const toggleBtn = document.getElementById('toggle-btn');
    const toggleIcon = document.getElementById('toggle-icon');

    if (toggleBtn && sidebar) {
        toggleBtn.addEventListener('click', () => {
            sidebar.classList.toggle('collapsed');
            
            // Cambiar icono según estado (intercambio de atributos para Lucide)
            if (sidebar.classList.contains('collapsed')) {
                toggleIcon.setAttribute('data-lucide', 'chevron-right');
            } else {
                toggleIcon.setAttribute('data-lucide', 'chevron-left');
            }
            
            // Volver a renderizar los iconos de Lucide para aplicar el cambio visual
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        });
    }
    
});
// ESTA FUNCIÓN DEBE IR AFUERA, AL FINAL DEL ARCHIVO ESTO SE USA SIEMPRE QUE 
//SE ENCUENTRA UN REGISTRO, PARA NOTIFICAR AL USUARIO QUE LOS CAMPOS SE RELLENARON 
// AUTOMATICAMENTE
function notificarEncontrado() {
    const toast = document.createElement('div');
    toast.className = 'toast-alerta informativo';
    
    toast.innerHTML = `
        <div class="toast-contenido">
            <i data-lucide="info" class="icono-info"></i>
            <div class="mensaje-texto">
                <span class="titulo-msg">Registro Encontrado</span>
                <p>Los campos se han rellenado automáticamente.</p>
            </div>
        </div>
    `;

    document.body.appendChild(toast);
    
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }

    setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transition = 'opacity 0.5s ease';
        setTimeout(() => toast.remove(), 500);
    }, 4000);
}
/* ============================================================
   INDICADOR DE CARGA EN BOTONES
   Uso:
   - btnCarga(btn, texto)  -> activa spinner y deshabilita el botón
   - btnLibre(btn)         -> restaura el botón (para operaciones AJAX)
   - Los formularios normales activan el spinner automáticamente
     al hacer submit (marcarlos con data-async para excluirlos)
   ============================================================ */
function btnCarga(btn, texto) {
    if (!btn || btn.classList.contains('cargando')) return btn;
    btn.dataset.contenidoOriginal = btn.innerHTML;
    btn.classList.add('cargando');
    btn.disabled = true;
    btn.innerHTML = '<span class="btn-spinner"></span>' + (texto ? texto : 'Procesando...');
    return btn;
}

function btnLibre(btn) {
    if (!btn) return;
    btn.classList.remove('cargando');
    btn.disabled = false;
    if (btn.dataset.contenidoOriginal) {
        btn.innerHTML = btn.dataset.contenidoOriginal;
        delete btn.dataset.contenidoOriginal;
    }
    if (typeof lucide !== 'undefined' && lucide.createIcons) {
        lucide.createIcons();
    }
}

// Activación automática en el submit de formularios normales
document.addEventListener('submit', function (e) {
    var form = e.target;
    if (form.hasAttribute('data-async')) return;

    var btn = form.querySelector('button[type="submit"]:not([disabled])');
    if (btn) btnCarga(btn);
});

/* ============================================================
   SELECT BUSCABLE
   Convierte un <select> en buscador: el input con atributo
   data-busca-select="idSelect" filtra las opciones mientras
   se escribe (ignora mayusculas y tildes, coincide por palabras)
   ============================================================ */
function normalizarTexto(valor) {
    return String(valor).toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '');
}

function prepararSelectBuscable(idSelect) {
    var select = document.getElementById(idSelect);
    if (!select || select.dataset.buscablePreparado) return;

    var input = document.querySelector('input[data-busca-select="' + idSelect + '"]');
    if (!input) return;

    select.dataset.buscablePreparado = '1';

    function aplicarFiltro() {
        var filtro = normalizarTexto(input.value).trim();
        var palabras = filtro ? filtro.split(/\s+/) : [];
        // La opcion 0 es el placeholder: nunca se filtra
        for (var i = 1; i < select.options.length; i++) {
            var opt = select.options[i];
            var texto = normalizarTexto(opt.textContent);
            var visible = true;
            for (var j = 0; j < palabras.length; j++) {
                if (texto.indexOf(palabras[j]) === -1) { visible = false; break; }
            }
            opt.style.display = visible ? '' : 'none';
        }
    }

    input.addEventListener('input', aplicarFiltro);

    input.addEventListener('keydown', function (e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            for (var i = 1; i < select.options.length; i++) {
                var opt = select.options[i];
                if (opt.style.display !== 'none') {
                    select.value = opt.value;
                    limpiarFiltroSelect(idSelect);
                    break;
                }
            }
        } else if (e.key === 'Escape') {
            limpiarFiltroSelect(idSelect);
        }
    });

    select.addEventListener('change', function () {
        if (select.value) limpiarFiltroSelect(idSelect);
    });
}

function limpiarFiltroSelect(idSelect) {
    var input = document.querySelector('input[data-busca-select="' + idSelect + '"]');
    if (input) input.value = '';
    var select = document.getElementById(idSelect);
    if (select) {
        for (var i = 1; i < select.options.length; i++) {
            select.options[i].style.display = '';
        }
    }
}
