<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema Local - Biblioteca Dr. Félix Pifano</title>
    <script src="{{ asset('js/lucide.min.js') }}"></script>
    <link rel="icon" href="{{ asset('img/library.png') }}" type="image/png">
    <link rel="stylesheet" href="{{ asset('css/plantilla.css') }}">
    <link rel="stylesheet" href="{{ asset('css/estilos_generales.css') }}">
</head>
<body>
    <!-- Incluimos la barra de navegación lateral se incluye con php para que cuando se haga
        un cambio este se modifique en todas las vistas y no estar modificando
        vista por vista xq aja hablame diego hay que ambiar el include de php por @extend porque si no da erros -->

    <!-- Navegación lateral -->    
    @include('layouts.nav')
    @include('layouts.mensajes')

    <!-- Envoltorio Principal para Contenido + Footer -->
    <div id="main-wrapper">
        <!-- Área de Contenido -->
        <main id="main-content">
           <div class="contenedor-form">
                <div class="titulo-form">
                    <h2>TITULO</h2>
                </div>
                <form action="">
                    <div class="rejilla-form">

                    </div>
                </form>
            </div>
        </main>

        <!-- Pie de Página (Footer)  se incluye con php para que cuando se haga
        un cambio este se modifique en todas las vistas y no estar modificando
        vista por vista xq aja este tambien pero embes de exten es un include jajjajajja-->
        
    <!-- Pie de página -->    
    @include('layouts.footer')
    </div>
    
    <!-- Enlace a tu js externo -->
    <script src="{{ asset('js/plantilla.js') }}?v={{ filemtime(public_path('js/plantilla.js')) }}"></script>
    
</body>
</html>