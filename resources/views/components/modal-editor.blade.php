<!-- components/modal-editor.blade.php -->
<div id="{{ $id }}" class="contenedor-form" style="display: none; position: fixed; ...">
    <div class="titulo-form">
        <h2>{{ $titulo }}</h2>
        <button type="button" onclick="ModalManager.cerrar('{{ $id }}')">
            <i data-lucide="x-circle"></i>
        </button>
    </div>
    <form id="form-{{ $id }}" method="POST">
        @csrf
        @method('PUT')
        {{ $slot }} <!-- Aquí va el contenido específico de cada formulario -->
        <div class="fila-botones">
            <button type="submit" class="btn btn-azul">Actualizar</button>
            <button type="button" class="btn btn-gris" onclick="ModalManager.cerrar('{{ $id }}')">Cancelar</button>
        </div>
    </form>
</div>