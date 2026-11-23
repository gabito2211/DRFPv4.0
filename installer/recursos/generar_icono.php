<?php
/**
 * Generador de icono .ico para el instalador DRFP.
 * Uso: php generar_icono.php <png_entrada> <ico_salida>
 *
 * Convierte el PNG en un icono .ico de 256x256 (formato ICO con
 * imagen PNG incrustada, valido desde Windows Vista en adelante).
 */

if ($argc < 3) {
    fwrite(STDERR, "Uso: php generar_icono.php <png_entrada> <ico_salida>\n");
    exit(1);
}

$entrada = $argv[1];
$salida  = $argv[2];

if (!is_file($entrada)) {
    fwrite(STDERR, "ERROR: no existe el archivo de entrada: $entrada\n");
    exit(1);
}

if (!function_exists('imagecreatefrompng')) {
    fwrite(STDERR, "ERROR: la extension GD no esta disponible en este PHP\n");
    exit(1);
}

$img = @imagecreatefrompng($entrada);
if (!$img) {
    fwrite(STDERR, "ERROR: no se pudo leer el PNG de entrada\n");
    exit(1);
}

$originalAncho  = imagesx($img);
$originalAlto   = imagesy($img);
$iconoLado      = 256;

// Escalar a 256x256 manteniendo aspecto sobre lienzo cuadrado transparente
$lienzo = imagecreatetruecolor($iconoLado, $iconoLado);
imagealphablending($lienzo, false);
imagesavealpha($lienzo, true);
$transparente = imagecolorallocatealpha($lienzo, 0, 0, 0, 127);
imagefill($lienzo, 0, 0, $transparente);

$escala = min($iconoLado / $originalAncho, $iconoLado / $originalAlto);
$nuevoAncho = (int) round($originalAncho * $escala);
$nuevoAlto  = (int) round($originalAlto * $escala);
$desplazX   = (int) floor(($iconoLado - $nuevoAncho) / 2);
$desplazY   = (int) floor(($iconoLado - $nuevoAlto) / 2);

imagecopyresampled($lienzo, $img, $desplazX, $desplazY, 0, 0, $nuevoAncho, $nuevoAlto, $originalAncho, $originalAlto);

ob_start();
imagepng($lienzo, null, 9);
$png = ob_get_clean();
imagedestroy($img);
imagedestroy($lienzo);

// Estructura de archivo .ico con una unica entrada (PNG incrustado)
$ico  = pack('v', 0);        // reservado (0)
$ico .= pack('v', 1);        // tipo: 1 = icono
$ico .= pack('v', 1);        // cantidad de imagenes

// Entrada de directorio (16 bytes)
$ico .= chr(0);              // ancho (0 = 256)
$ico .= chr(0);              // alto (0 = 256)
$ico .= chr(0);              // paleta (0 = sin paleta)
$ico .= chr(0);              // reservado
$ico .= pack('v', 1);        // planos
$ico .= pack('v', 32);       // bits por pixel
$ico .= pack('V', strlen($png)); // tamano del PNG
$ico .= pack('V', 22);       // offset de la imagen (6 + 16)

$ico .= $png;

if (file_put_contents($salida, $ico) === false) {
    fwrite(STDERR, "ERROR: no se pudo escribir el icono en $salida\n");
    exit(1);
}

echo "Icono generado: $salida (" . strlen($ico) . " bytes)\n";
exit(0);
