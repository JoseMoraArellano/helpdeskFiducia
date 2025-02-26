<!-- Modal para actualizar recursos -->
<form id="frmActualizarRecurso" method="POST" onsubmit="return actualizarRecurso()">
    <div class="modal fade" id="modalActualizarRecurso" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Actualizar Recurso</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="idRecurso" id="idRecurso">
                    
                    <label for="nombreU">Nombre del recurso</label>
                    <input type="text" class="form-control" id="nombreU" name="nombre" required>
                    
                    <label for="descripcionU">Descripción</label>
                    <textarea class="form-control" id="descripcionU" name="descripcion" required></textarea>
                    
                    <label for="categSHU">Categoría S/H</label>
                    <select class="form-control" id="categSHU" name="categSH" required>
                        <option value="">Selecciona una opción</option>
                        <option value="Software">Software</option>
                        <option value="Hardware">Hardware</option>
                        <option value="Otro">Otro</option>
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button class="btn btn-warning">Actualizar</button>
                </div>
            </div>
        </div>
    </div>
</form>