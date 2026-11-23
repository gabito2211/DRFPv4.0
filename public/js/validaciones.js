function permite(elEvento, permitidos) {
  const numeros = "0123456789";
  const caracteres = "abcdefghijklmnñopqrstuvwxyzABCDEFGHIJKLMNÑOPQRSTUVWXYZ";
  const numeros_caracteres = numeros + caracteres;
  const teclas_especiales = [8, 37, 39, 46, 32, 9]; // Retroceso, flechas, suprimir, espacio, tab

  // Ajuste: agregamos el guion a la lista de caracteres según el caso
  switch (permitidos) {
    case 'num':
      permitidos = numeros + "-"; // ← Aquí incluimos el guion
      break;
    case 'car':
      permitidos = caracteres;
      break;
    case 'num_car':
      permitidos = numeros_caracteres;
      break;
  }

  const evento = elEvento || window.event;
  const codigoCaracter = evento.charCode || evento.keyCode;
  const caracter = String.fromCharCode(codigoCaracter);

  // Comprobar si es una tecla especial
  const tecla_especial = teclas_especiales.includes(codigoCaracter);

  return permitidos.indexOf(caracter) !== -1 || tecla_especial;
}
