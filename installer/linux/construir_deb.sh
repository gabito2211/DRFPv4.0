#!/bin/bash
# Construye el instalador .deb del Sistema DRFP v4.0
set -euo pipefail

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
PROYECTO="$(cd "$SCRIPT_DIR/../.." && pwd)"

VERSION="4.0.0"
ARCH="amd64"
PKG="drfp"
BUILD="$SCRIPT_DIR/build/${PKG}-${VERSION}"
DIST="$SCRIPT_DIR/dist"
DEB="$DIST/${PKG}_${VERSION}_${ARCH}.deb"

log() { echo "[construir_deb] $*"; }

limpiar() {
    rm -rf "$SCRIPT_DIR/build" "$DIST"
    mkdir -p "$BUILD/DEBIAN" "$BUILD/opt/DRFP/installer/templates" \
        "$BUILD/usr/share/applications" "$DIST"
}

copiar_app() {
    log "Copiando la aplicacion (sin datos sensibles ni instalador)..."
    mkdir -p "$BUILD/opt/DRFP/app"
    tar -C "$PROYECTO" \
        --exclude='./.git' \
        --exclude='./.env' \
        --exclude='./installer' \
        --exclude='./fix2.php' \
        --exclude='./testajax2.php' \
        --exclude='./drfp_launcher.log' \
        --exclude='./storage/backups/*.sql' \
        --exclude='./storage/logs/*.log' \
        --exclude='./storage/framework/views/*.php' \
        --exclude='./public/img/carnets/*' \
        --exclude='./database/drfp.sql' \
        -cf - . | tar -C "$BUILD/opt/DRFP/app" -xf -
}

copiar_debian() {
    cp "$SCRIPT_DIR/debian/control" "$BUILD/DEBIAN/control"
    cp "$SCRIPT_DIR/debian/preinst" "$BUILD/DEBIAN/preinst"
    cp "$SCRIPT_DIR/debian/postinst" "$BUILD/DEBIAN/postinst"
    cp "$SCRIPT_DIR/debian/prerm" "$BUILD/DEBIAN/prerm"
    cp "$SCRIPT_DIR/debian/postrm" "$BUILD/DEBIAN/postrm"
    chmod 0755 "$BUILD/DEBIAN/preinst" "$BUILD/DEBIAN/postinst" \
        "$BUILD/DEBIAN/prerm" "$BUILD/DEBIAN/postrm"
    chmod 0644 "$BUILD/DEBIAN/control"
}

copiar_plantillas() {
    cp "$SCRIPT_DIR/debian/templates/env.template" \
        "$BUILD/opt/DRFP/installer/templates/env.template"
    cp "$SCRIPT_DIR/debian/templates/drfp.service" \
        "$BUILD/opt/DRFP/installer/templates/drfp.service"
    cp "$SCRIPT_DIR/debian/templates/drfp.desktop" \
        "$BUILD/usr/share/applications/drfp.desktop"
    chmod 0644 "$BUILD/opt/DRFP/installer/templates/env.template" \
        "$BUILD/opt/DRFP/installer/templates/drfp.service" \
        "$BUILD/usr/share/applications/drfp.desktop"
}

crear_directorios() {
    mkdir -p "$BUILD/opt/DRFP/app/storage/backups" \
        "$BUILD/opt/DRFP/app/storage/logs" \
        "$BUILD/opt/DRFP/app/storage/framework/views" \
        "$BUILD/opt/DRFP/app/storage/framework/cache" \
        "$BUILD/opt/DRFP/app/public/img/carnets"
    if [ -f "$PROYECTO/storage/backups/.htaccess" ]; then
        cp "$PROYECTO/storage/backups/.htaccess" \
            "$BUILD/opt/DRFP/app/storage/backups/.htaccess"
    fi
    chmod 0775 "$BUILD/opt/DRFP/app/storage" \
        "$BUILD/opt/DRFP/app/public/img/carnets"
}

comprimir() {
    local compresion="gzip"
    command -v xz >/dev/null 2>&1 && compresion="xz"

    log "Compilando $DEB (compresion: $compresion)..."
    if command -v fakeroot >/dev/null 2>&1; then
        fakeroot dpkg-deb --build --root-owner-group -Z"$compresion" "$BUILD" "$DEB" \
            || fakeroot dpkg-deb --build -Z"$compresion" "$BUILD" "$DEB"
    else
        dpkg-deb --build --root-owner-group -Z"$compresion" "$BUILD" "$DEB" \
            || dpkg-deb --build -Z"$compresion" "$BUILD" "$DEB"
    fi
}

verificar() {
    dpkg-deb -I "$DEB" >/dev/null
    log "Paquete generado: $DEB"
    dpkg-deb -I "$DEB" | sed -n '1,18p'
    log "Instalar con: sudo apt-get install -y $DEB"
}

limpiar_build() {
    rm -rf "$SCRIPT_DIR/build"
    log "Arbol de construccion eliminado"
}

limpiar
copiar_app
copiar_debian
copiar_plantillas
crear_directorios
comprimir
verificar
limpiar_build
