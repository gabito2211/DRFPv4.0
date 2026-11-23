<script>
    /* Aplica el tema guardado ANTES de pintar el contenido (evita flash blanco) */
    (function () {
        try {
            if (localStorage.getItem('drfp-tema') === 'dark') {
                document.body.setAttribute('data-theme', 'dark');
            }
        } catch (e) {}
    })();
</script>
<style>
    /* ============================================ */
    /* TARJETA DEL USUARIO AUTENTICADO EN EL SIDEBAR */
    /* ============================================ */
    .user-profile-item {
        padding: 10px 15px;
        margin-bottom: 8px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.08);
    }

    .user-profile-card {
        display: flex;
        align-items: center;
        gap: 12px;
        background: rgba(255, 255, 255, 0.03);
        padding: 10px 12px;
        border-radius: 10px;
        border: 1px solid rgba(255, 255, 255, 0.05);
    }

    .user-avatar {
        width: 38px;
        height: 38px;
        border-radius: 8px;
        background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.95rem;
        flex-shrink: 0;
        box-shadow: 0 2px 8px rgba(59, 130, 246, 0.3);
    }

    .user-info {
        display: flex;
        flex-direction: column;
        overflow: hidden;
    }

    .user-name {
        color: var(--text-main, #f8fafc);
        font-weight: 600;
        font-size: 0.88rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .user-role-badge {
        color: #60a5fa;
        font-size: 0.75rem;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 4px;
        margin-top: 2px;
    }

    /* ============================================ */
    /* CLASES EXCLUSIVAS PARA EL SUBMENÚ CORREGIDAS */
    /* ============================================ */

    /* === SUBMENÚ - OCULTO POR DEFECTO === */
    .sub-menu-list {
        display: none;
        padding: 4px 0;
        margin: 4px 10px 4px 20px; /* Margen para crear sangría jerárquica */
        list-style: none;
        background: #0f172a; /* Fondo un tono más oscuro que el sidebar para dar profundidad */
        border-left: 2px solid var(--sidebar-hover); /* Línea guía sutil usando tu variable */
        border-radius: 8px;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    /* === SUBMENÚ - VISIBLE (cuando el padre tiene clase 'sub-open') === */
    .nav-item.sub-open > .sub-menu-list {
        display: block;
        animation: subMenuSlideDown 0.3s ease;
    }

    /* === ANIMACIÓN DE APERTURA === */
    @keyframes subMenuSlideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* === ITEMS DEL SUBMENÚ === */
    .sub-menu-item {
        list-style: none;
    }

    /* === ENLACES DEL SUBMENÚ === */
    .sub-menu-link {
        display: flex;
        align-items: center;
        padding: 10px 15px;
        color: var(--text-muted); /* Usa el gris apagado del texto principal */
        text-decoration: none;
        transition: all 0.2s ease;
        gap: 12px;
        font-size: 0.9rem;
        border-left: 3px solid transparent;
    }

    .sub-menu-link:hover {
        background: var(--sidebar-hover); /* Mismo hover del menú principal */
        color: var(--text-main);
        padding-left: 20px; /* Ligero movimiento al pasar el ratón */
    }

    .sub-menu-link.active {
        background: rgba(59, 130, 246, 0.15); /* Azul muy transparente */
        color: var(--text-main);
        font-weight: 600;
        border-left-color: var(--sidebar-active); /* Línea indicadora del activo */
    }

    .sub-menu-link .link-icon {
        width: 18px;
        height: 18px;
        flex-shrink: 0;
    }

    .sub-menu-link .link-text {
        flex: 1;
    }

    /* === FLECHA INDICADORA DEL SUBMENÚ === */
    .sub-arrow-icon {
        width: 18px;
        height: 18px;
        transition: transform 0.3s ease;
        margin-left: auto;
        color: var(--text-muted);
    }

    /* Rotar flecha cuando el submenú está abierto */
    .nav-item.sub-open > .nav-link .sub-arrow-icon {
        transform: rotate(180deg);
        color: var(--text-main);
    }

    /* === ESTILO PARA EL ENLACE PADRE (con submenú) === */
    .nav-item.has-submenu > .nav-link {
        cursor: pointer;
        position: relative;
    }

    /* ============================================ */
    /* MODAL DE CONFIRMACIÓN DE CERRAR SESIÓN        */
    /* ============================================ */
    .modal-logout {
        display: none;
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background: #1e293b;
        border: 1px solid #334155;
        border-radius: 12px;
        padding: 24px;
        width: 90%;
        max-width: 400px;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.5), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        z-index: 1000;
        color: #f8fafc;
        text-align: center;
        animation: modalFadeIn 0.2s ease-out;
    }

    @keyframes modalFadeIn {
        from {
            opacity: 0;
            transform: translate(-50%, -46%) scale(0.95);
        }
        to {
            opacity: 1;
            transform: translate(-50%, -50%) scale(1);
        }
    }

    .modal-logout-icon {
        width: 52px;
        height: 52px;
        background: rgba(239, 68, 68, 0.15);
        color: #ef4444;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 16px auto;
    }

    .modal-logout-title {
        font-size: 1.25rem;
        font-weight: 600;
        margin: 0 0 8px 0;
        color: #f8fafc;
    }

    .modal-logout-text {
        font-size: 0.9rem;
        color: #94a3b8;
        margin: 0 0 24px 0;
        line-height: 1.4;
    }

    .modal-logout-actions {
        display: flex;
        gap: 12px;
        justify-content: center;
    }

    .btn-logout-cancel {
        flex: 1;
        padding: 10px 16px;
        border-radius: 8px;
        border: 1px solid #475569;
        background: transparent;
        color: #cbd5e1;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .btn-logout-cancel:hover {
        background: #334155;
        color: #fff;
    }

    .btn-logout-confirm {
        flex: 1;
        padding: 10px 16px;
        border-radius: 8px;
        border: none;
        background: #ef4444;
        color: #ffffff;
        font-weight: 600;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: all 0.2s ease;
    }

    .btn-logout-confirm:hover {
        background: #dc2626;
    }

    /* === RESPONSIVE: Ajustes para móvil === */
    @media (max-width: 768px) {
        .sub-menu-link {
            padding: 12px 15px;
            font-size: 0.85rem;
        }
    }

    .logo-text {
    font-size: 0.9rem;
    font-weight: 600;
    color: var(--text-main, #f8fafc);
    white-space: normal;
    word-break: break-word;
    overflow-wrap: break-word;
    line-height: 1.2;
    flex: 1; /* Permite adaptarse al espacio disponible sin empujar el contenedor */
    min-width: 0; /* Previene desbordamiento en Flexbox */
}

/* Ocultar el texto del logo cuando la barra esté colapsada */
#sidebar.collapsed .logo-text,
#sidebar.collapsed .user-info,
#sidebar.collapsed .link-text,
#sidebar.collapsed .sub-arrow-icon {
    display: none !important;
}

/* Ajuste del header en estado colapsado */
#sidebar.collapsed .sidebar-header {
    justify-content: center;
    padding: 10px 0;
}

#sidebar.collapsed .logo-container {
    gap: 0;
    justify-content: center;
}

</style>

<nav id="sidebar">
    <div class="sidebar-header">
        <div class="logo-container" style="display: flex; align-items: center; gap: 10px;">
        <i data-lucide="library" color="#3b82f6" style="flex-shrink: 0;"></i>
        <div class="logo-text">
            @if(Auth::check() && Auth::user()->sede)
                {{ Auth::user()->sede->biblioteca }}
            @else
                Sede Principal 
                <small style="display: block; font-size: 0.75rem; color: var(--text-muted); font-weight: normal;">(Administración)</small>
            @endif
        </div>
    </div>
    <button class="toggle-btn" id="toggle-btn">
        <i data-lucide="chevron-left" id="toggle-icon"></i>
    </button>
    </div>

    <ul class="nav-list">
        <!-- INFORMACIÓN DEL USUARIO ACTIVO -->
        @if(Auth::check())
        <li class="nav-item">
            <div class="user-profile-card">
                <!--<div class="user-avatar">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>-->
                <div class="user-info">
                    <span class="user-name" title="{{ Auth::user()->name }}">{{ Auth::user()->name }}</span>
                    <span class="user-role-badge">
                        <!--<i data-lucide="shield-check" size="14"></i>-->
                        {{ Auth::user()->nombre_rol }}
                    </span>
                </div>
            </div>
        </li>
        @endif

        <!-- ACCESO GENERAL: Todos los roles -->
        <li class="nav-item">
            <a href="/inicio" class="nav-link {{ request()->is('inicio') ? 'active' : '' }}">
                <i data-lucide="layout-dashboard" class="link-icon"></i>
                <span class="link-text">Panel Principal</span>
            </a>
        </li>

        <!-- RECEPCIONISTA (2) Y ADMINISTRADOR (1) -->
        @if(in_array(Auth::user()->rol, [1, 2]))
        <li class="nav-item">
            <a href="/registro" class="nav-link {{ request()->is('registro') ? 'active' : '' }}">
                <i data-lucide="users" class="link-icon"></i>
                <span class="link-text">Registro de Visitas</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="/visita" class="nav-link {{ request()->is('visita') ? 'active' : '' }}">
                <i data-lucide="bus-front" class="link-icon"></i>
                <span class="link-text">Visitas Guiadas</span>
            </a>
        </li>
        @endif

<!-- EXCLUSIVO ADMINISTRADOR (1) -->
@if(Auth::user()->rol == 1)
<li class="nav-item">
    <a href="{{ route('planes.index') }}" class="nav-link {{ request()->is('planes') || request()->is('actividades*') ? 'active' : '' }}">
        <i data-lucide="calendar-range" class="link-icon"></i>
        <span class="link-text">Planes Vacacionales</span>
    </a>
</li>
@endif

        <!-- ALMACENISTA (3), RECEPCIONISTA (2) Y ADMINISTRADOR (1) -->
        <!-- SUBMENÚ DE INVENTARIO Y AUTORES -->
        <li class="nav-item has-submenu {{ request()->is('inventario*') || request()->is('autores*') ? 'sub-open' : '' }}">
            <a href="/inventario" class="nav-link {{ request()->is('inventario*') || request()->is('autores*') ? 'active' : '' }}">
                <i data-lucide="package" class="link-icon"></i>
                <span class="link-text">Inventario</span>
                <i data-lucide="chevron-down" class="sub-arrow-icon"></i>
            </a>
            <ul class="sub-menu-list">
                <li class="sub-menu-item">
                    <a href="/inventario" class="sub-menu-link {{ request()->is('inventario') ? 'active' : '' }}">
                        <i data-lucide="package" class="link-icon"></i>
                        <span class="link-text">Libros</span>
                    </a>
                </li>
                <li class="sub-menu-item">
                    <a href="/autores" class="sub-menu-link {{ request()->is('autores*') ? 'active' : '' }}">
                        <i data-lucide="user-pen" class="link-icon"></i>
                        <span class="link-text">Autores</span>
                    </a>
                </li>
            </ul>
        </li>
        <li class="nav-item">
            <a href="/carnet" class="nav-link {{ request()->is('carnet') ? 'active' : '' }}">
                <i data-lucide="contact-2" class="link-icon"></i>
                <span class="link-text">Carnet Bibliotecario</span>
            </a>
        </li>
        
        <!-- SUBMENÚ DE PRÉSTAMOS Y SANCIONES -->
        <li class="nav-item has-submenu {{ request()->is('prestamos*') || request()->is('sanciones*') ? 'sub-open' : '' }}">
            <a href="/prestamos" class="nav-link {{ request()->is('prestamos*') || request()->is('sanciones*') ? 'active' : '' }}">
                <i data-lucide="book-open" class="link-icon"></i>
                <span class="link-text">Prestamos</span>
                <i data-lucide="chevron-down" class="sub-arrow-icon"></i>
            </a>
            <ul class="sub-menu-list">
                <li class="sub-menu-item">
                    <a href="/prestamos" class="sub-menu-link {{ request()->is('prestamos') ? 'active' : '' }}">
                        <i data-lucide="book-open" class="link-icon"></i>
                        <span class="link-text">Control de Préstamos</span>
                    </a>
                </li>
                <li class="sub-menu-item">
                    <a href="/sanciones" class="sub-menu-link {{ request()->is('sanciones*') ? 'active' : '' }}">
                        <i data-lucide="shield-alert" class="link-icon"></i>
                        <span class="link-text">Sanciones y Bloqueos</span>
                    </a>
                </li>
            </ul>
        </li>

        <!-- EXCLUSIVO ADMINISTRADOR (1) -->
        @if(Auth::user()->rol == 1)
        <!-- SUBMENÚ DE UBICACIÓN -->
        <li class="nav-item has-submenu {{ request()->is('ubicacion*') ? 'sub-open' : '' }}">
            <a href="{{ route('ubicacion.salas.index') }}" class="nav-link {{ request()->is('ubicacion*') ? 'active' : '' }}">
                <i data-lucide="map-pinned" class="link-icon"></i>
                <span class="link-text">Ubicación</span>
                <i data-lucide="chevron-down" class="sub-arrow-icon"></i>
            </a>
            <ul class="sub-menu-list">
                <li class="sub-menu-item">
                    <a href="{{ route('ubicacion.salas.index') }}" class="sub-menu-link {{ request()->is('ubicacion/salas*') ? 'active' : '' }}">
                        <i data-lucide="door-open" class="link-icon"></i>
                        <span class="link-text">Salas</span>
                    </a>
                </li>
                <li class="sub-menu-item">
                    <a href="{{ route('ubicacion.pasillos.index') }}" class="sub-menu-link {{ request()->is('ubicacion/pasillos*') ? 'active' : '' }}">
                        <i data-lucide="route" class="link-icon"></i>
                        <span class="link-text">Pasillos</span>
                    </a>
                </li>
                <li class="sub-menu-item">
                    <a href="{{ route('ubicacion.estanterias.index') }}" class="sub-menu-link {{ request()->is('ubicacion/estanterias*') ? 'active' : '' }}">
                        <i data-lucide="library-big" class="link-icon"></i>
                        <span class="link-text">Estanterías</span>
                    </a>
                </li>
            </ul>
        </li>
        <li class="nav-item">
            <a href="/sedes" class="nav-link {{ request()->is('sedes*') ? 'active' : '' }}">
                <i data-lucide="building-2" class="link-icon"></i>
                <span class="link-text">Sedes</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="/usuario" class="nav-link {{ request()->is('usuario') ? 'active' : '' }}">
                <i data-lucide="user" class="link-icon"></i>
                <span class="link-text">Gestion de usuarios</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('bitacora.index') }}" class="nav-link {{ request()->is('bitacora') ? 'active' : '' }}">
                <i data-lucide="clipboard-list" class="link-icon"></i>
                <span class="link-text">Bitácora</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('respaldo.index') }}" class="nav-link {{ request()->is('respaldo*') ? 'active' : '' }}">
                <i data-lucide="database-backup" class="link-icon"></i>
                <span class="link-text">Respaldo del Sistema</span>
            </a>
        </li>
        @endif

        <!-- SELECTOR DE TEMA CLARO / OSCURO -->
        <li class="nav-item">
            <button type="button" class="nav-link" id="btn-alternar-tema" onclick="alternarTema()">
                <span class="link-icon" id="tema-icono">
                    <i data-lucide="moon"></i>
                </span>
                <span class="link-text" id="tema-texto">Modo Oscuro</span>
            </button>
        </li>

        <li class="nav-item">
            <a href="#" class="nav-link" onclick="event.preventDefault(); abrirModalLogout();">
                <i data-lucide="log-out" class="link-icon"></i>
                <span class="link-text">Cerrar Sesión</span>
            </a>
        </li>

        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            {{ csrf_field() }}
        </form>
    </ul>
</nav>

<!-- OVERLAY GENERAL -->
<div id="modal-overlay" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.6); z-index:999; backdrop-filter: blur(2px);"></div>

<!-- MODAL CERRAR SESIÓN -->
<div id="modal-logout" class="modal-logout">
    <div class="modal-logout-icon">
        <i data-lucide="log-out" size="26"></i>
    </div>
    <h3 class="modal-logout-title">¿Cerrar Sesión?</h3>
    <p class="modal-logout-text">¿Estás seguro de que deseas salir del sistema?</p>
    <div class="modal-logout-actions">
        <button type="button" class="btn-logout-cancel" onclick="cerrarModalLogout()">Cancelar</button>
        <button type="button" class="btn-logout-confirm" onclick="confirmarLogout()">
            <i data-lucide="log-out" size="18"></i> Salir
        </button>
    </div>
</div>

<script>
    function abrirModalLogout() {
        const modal = document.getElementById('modal-logout');
        const overlay = document.getElementById('modal-overlay');
        if (modal && overlay) {
            modal.style.display = 'block';
            overlay.style.display = 'block';
            if (window.lucide) lucide.createIcons();
        }
    }

    function cerrarModalLogout() {
        const modal = document.getElementById('modal-logout');
        const overlay = document.getElementById('modal-overlay');
        if (modal && overlay) {
            modal.style.display = 'none';
            overlay.style.display = 'none';
        }
    }

    function confirmarLogout() {
        document.getElementById('logout-form').submit();
    }

    /* ============================================
       TEMA CLARO / OSCURO
       ============================================ */
    function alternarTema() {
        var esOscuro = document.body.getAttribute('data-theme') === 'dark';
        var nuevoTema = esOscuro ? 'light' : 'dark';

        if (nuevoTema === 'dark') {
            document.body.setAttribute('data-theme', 'dark');
        } else {
            document.body.removeAttribute('data-theme');
        }

        try {
            localStorage.setItem('drfp-tema', nuevoTema);
        } catch (e) {}

        actualizarBotonTema();
    }

    function actualizarBotonTema() {
        var esOscuro = document.body.getAttribute('data-theme') === 'dark';
        var icono = document.getElementById('tema-icono');
        var texto = document.getElementById('tema-texto');
        var btn = document.getElementById('btn-alternar-tema');

        if (!icono || !texto) return;

        icono.innerHTML = '<i data-lucide="' + (esOscuro ? 'sun' : 'moon') + '"></i>';
        texto.innerText = esOscuro ? 'Modo Claro' : 'Modo Oscuro';

        if (btn) btn.title = esOscuro ? 'Cambiar a tema claro' : 'Cambiar a tema oscuro';

        if (typeof lucide !== 'undefined' && lucide.createIcons) {
            lucide.createIcons();
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        actualizarBotonTema();
    });

    const ModalManager = {
        abrir: function(modalId, config) {
            const modal = document.getElementById(modalId);
            const overlay = document.getElementById('modal-overlay');
            const form = document.getElementById('form-' + modalId);

            if (!modal || !overlay) {
                console.error("Error: No se encontró el modal o el overlay.");
                return;
            }

            if (config.action && form) form.action = config.action;

            if (config.datos && form) {
                Object.keys(config.datos).forEach(key => {
                    const input = form.querySelector(`[name="${key}"]`);
                    if (input) input.value = config.datos[key];
                });
            }

            modal.style.display = 'block';
            overlay.style.display = 'block';
            if(window.lucide) lucide.createIcons();
        },

        cerrar: function(modalId) {
            document.getElementById(modalId).style.display = 'none';
            document.getElementById('modal-overlay').style.display = 'none';
        }
    };

    document.addEventListener('DOMContentLoaded', () => {
        const overlay = document.getElementById('modal-overlay');
        if(overlay) {
            overlay.addEventListener('click', function() {
                document.querySelectorAll('.contenedor-form[style*="display: block"]').forEach(m => {
                    m.style.display = 'none';
                });
                cerrarModalLogout();
                this.style.display = 'none';
            });
        }

        // SUBMENÚ TOGGLE
        document.querySelectorAll('.nav-item.has-submenu > .nav-link').forEach(link => {
            link.addEventListener('click', function(e) {
                const parentItem = this.closest('.nav-item');
                const submenu = parentItem.querySelector('.sub-menu-list');
                
                if (submenu) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    document.querySelectorAll('.nav-item.sub-open').forEach(item => {
                        if (item !== parentItem) {
                            item.classList.remove('sub-open');
                        }
                    });
                    
                    parentItem.classList.toggle('sub-open');
                }
            });
        });

        // CERRAR SUBMENÚ AL HACER CLIC FUERA
        document.addEventListener('click', function(e) {
            const isClickInsideSubmenu = e.target.closest('.has-submenu');
            if (!isClickInsideSubmenu) {
                document.querySelectorAll('.nav-item.sub-open').forEach(item => {
                    item.classList.remove('sub-open');
                });
            }
        });

        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    });
</script>