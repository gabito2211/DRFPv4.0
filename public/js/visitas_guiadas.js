function verGuia(id) {
    fetch(`/visita/${id}/datos`)
        .then(res => res.json())
        .then(data => {
            document.getElementById('ver-institucion').innerText = data.institucion || '';
            document.getElementById('ver-responsable').innerText = data.responsable || '';
            document.getElementById('ver-encargado').innerText = data.encargado || '';
            document.getElementById('ver-cantidad').innerText = data.cantidad || '';
            document.getElementById('ver-fecha').innerText = data.fecha_visita || '';
            document.getElementById('ver-hora').innerText = data.hora ? data.hora.substring(0, 5) : '—';
            document.getElementById('ver-tipo').innerText = (data.sala ? data.sala.nombre : (data.tipo_recorrido || '')).toUpperCase();
            
            document.getElementById('modal-ver-guia').style.display = 'flex';
            if (typeof lucide !== 'undefined' && lucide.createIcons) {
                lucide.createIcons();
            }
        });
}

function editarGuia(id) {
    fetch(`/visita/${id}/datos`)
        .then(res => res.json())
        .then(data => {
            document.getElementById('edit-institucion').value = data.institucion || '';
            document.getElementById('edit-responsable').value = data.responsable || '';
            document.getElementById('edit-encargado').value = data.encargado || '';
            document.getElementById('edit-cantidad').value = data.cantidad || '';
            document.getElementById('edit-fecha').value = data.fecha_visita || '';
            document.getElementById('edit-hora').value = data.hora ? data.hora.substring(0, 5) : '';
            document.getElementById('edit-sala_id').value = data.sala_id || '';
            
            // Ajustar la acción del formulario dinámicamente
            document.getElementById('form-editar-guia').action = `/visita/${id}`;
            
            document.getElementById('modal-editar-guia').style.display = 'flex';
        });
}

function cerrarModalVer() { 
    document.getElementById('modal-ver-guia').style.display = 'none'; 
}

function cerrarModalEditar() { 
    document.getElementById('modal-editar-guia').style.display = 'none'; 
}
