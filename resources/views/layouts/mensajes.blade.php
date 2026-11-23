<!-- Carga de la librería local -->
<script src="{{ asset('js/sweetalert2.all.min.js') }}"></script>

<!-- Notificación de ÉXITO (Mantenemos tu estilo Toast) -->
@if(session('success'))
    <div id="notificacion-global" class="toast-alerta">
        <div class="toast-contenido">
            <i data-lucide="check-circle" class="icono-exito"></i>
            <div class="mensaje-texto">
                <span class="titulo-msg">Operación Exitosa</span>
                <p>{{ session('success') }}</p>
            </div>
        </div>
    </div>

    <style>
        .toast-alerta {
            position: fixed; top: 20px; right: 20px; background: white;
            border-left: 5px solid #28a745; padding: 15px 20px; border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15); z-index: 10000;
            min-width: 280px; animation: aparecer 0.4s ease forwards;
        }
        .toast-contenido { display: flex; align-items: center; gap: 12px; }
        .icono-exito { color: #28a745; width: 24px; height: 24px; }
        .titulo-msg { font-weight: bold; color: #2d3436; display: block; }
        .mensaje-texto p { margin: 0; color: #636e72; font-size: 13px; }
        @keyframes aparecer { from { transform: translateX(100%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }
    </style>

    <script>
        setTimeout(() => {
            const el = document.getElementById('notificacion-global');
            if(el) {
                el.style.opacity = '0';
                el.style.transition = 'opacity 0.5s ease';
                setTimeout(() => el.remove(), 500);
            }
        }, 4000);
    </script>
@endif

<!-- Notificación de ERROR (Usando SweetAlert2 para mayor impacto) -->
@if(session('error'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: 'Atención',
                text: "{{ session('error') }}",
                icon: 'error',
                confirmButtonColor: '#d33',
                confirmButtonText: 'Entendido'
            });
        });
    </script>
@endif

<!-- Notificación de ERRORES DE VALIDACIÓN (formularios) -->
@if ($errors->any())
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: 'Revisa el formulario',
                html: `<ul style="text-align:left; margin:0; padding-left:20px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>`,
                icon: 'warning',
                confirmButtonColor: '#d33',
                confirmButtonText: 'Entendido'
            });
        });
    </script>
@endif

<!-- ============================================== -->
<!-- FUNCIONES GLOBALES PARA MENSAJES (Toast y Alertas) -->
<!-- ============================================== -->
<script>
    // Mostrar toast de éxito (usa el mismo estilo que ya tienes)
    function mostrarExito(mensaje) {
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
        
        setTimeout(function() {
            toast.style.opacity = '0';
            toast.style.transition = 'opacity 0.5s ease';
            setTimeout(function() { toast.remove(); }, 500);
        }, 3000);
    }

    // Mostrar toast de error
    function mostrarError(mensaje) {
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
        
        setTimeout(function() {
            toast.style.opacity = '0';
            toast.style.transition = 'opacity 0.5s ease';
            setTimeout(function() { toast.remove(); }, 500);
        }, 4000);
    }

    // Mostrar toast de información
    function mostrarInfo(mensaje) {
        var toast = document.createElement('div');
        toast.className = 'toast-alerta';
        toast.style.borderLeftColor = '#3182ce';
        toast.innerHTML = `
            <div class="toast-contenido">
                <i data-lucide="info" style="color: #3182ce; width: 24px; height: 24px;"></i>
                <div class="mensaje-texto">
                    <span class="titulo-msg" style="color: #3182ce;">Información</span>
                    <p>${mensaje}</p>
                </div>
            </div>
        `;
        document.body.appendChild(toast);
        lucide.createIcons();
        
        setTimeout(function() {
            toast.style.opacity = '0';
            toast.style.transition = 'opacity 0.5s ease';
            setTimeout(function() { toast.remove(); }, 500);
        }, 3000);
    }

    // Mostrar carga
    function mostrarCargando(mensaje) {
        Swal.fire({
            title: mensaje || 'Cargando...',
            allowOutsideClick: false,
            didOpen: function() {
                Swal.showLoading();
            }
        });
    }

    // Cerrar carga
    function cerrarCargando() {
        Swal.close();
    }

    // Confirmación genérica
    function confirmar(titulo, mensaje, onConfirmar) {
        Swal.fire({
            title: titulo || '¿Estás seguro?',
            text: mensaje,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, continuar',
            cancelButtonText: 'Cancelar',
            reverseButtons: true
        }).then(function(result) {
            if (result.isConfirmed && onConfirmar) {
                onConfirmar();
            }
        });
    }

    // Confirmar eliminar (ya la tenías)
    function confirmarEliminar(idFormulario) {
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
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(idFormulario).submit();
            }
        });
    }
</script>