#!/bin/bash
# ============================================================
# Acceso directo para abrir el Sistema DRFP (Linux)
# - Inicia el servidor si no esta corriendo
# - ESPERA hasta que el servidor realmente responda
# - Abre el navegador solo cuando ya esta listo
# - Si algo falla, muestra un mensaje explicando el problema
# ============================================================

RUTA_PROYECTO="/opt/lampp/htdocs/DRFPv3.2.1"
PHP_BIN="/opt/lampp/bin/php"
PUERTO="8000"
URL="http://127.0.0.1:${PUERTO}/"
LOG_FILE="${RUTA_PROYECTO}/drfp_launcher.log"

mostrar_error() {
    local mensaje="$1"
    if command -v zenity > /dev/null 2>&1; then
        zenity --error --title="Sistema DRFP" --width=500 --text="$mensaje"
    elif command -v notify-send > /dev/null 2>&1; then
        notify-send "Sistema DRFP" "$mensaje"
    else
        echo "$mensaje" >&2
    fi
}

# --- Verificaciones previas ---
if [ ! -x "$PHP_BIN" ]; then
    mostrar_error "No se encontro PHP en:\n${PHP_BIN}\n\nRevisa donde esta instalado LAMPP/XAMPP."
    exit 1
fi

if [ ! -f "${RUTA_PROYECTO}/artisan" ]; then
    mostrar_error "No se encontro el proyecto DRFP en:\n${RUTA_PROYECTO}"
    exit 1
fi

servidor_activo() {
    curl -s -o /dev/null -m 1 "$URL"
}

# --- Si no esta activo, lo inicia ---
if ! servidor_activo; then
    cd "$RUTA_PROYECTO" || exit 1
    nohup "$PHP_BIN" artisan serve --port="$PUERTO" > "$LOG_FILE" 2>&1 &

    # Espera activa hasta 20 segundos a que el servidor responda
    intentos=0
    while ! servidor_activo && [ "$intentos" -lt 40 ]; do
        sleep 0.5
        intentos=$((intentos + 1))
    done

    if ! servidor_activo; then
        detalle=$(tail -n 25 "$LOG_FILE" 2>/dev/null)
        mostrar_error "El servidor no respondio a tiempo.\n\nDetalle:\n${detalle}"
        exit 1
    fi
fi

# --- Ya esta listo: abre el navegador ---
xdg-open "$URL" > /dev/null 2>&1

exit 0
