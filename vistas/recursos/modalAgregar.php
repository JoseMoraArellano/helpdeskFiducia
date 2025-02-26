<!-- Modal para agregar recursos -->
<form id="frmAgregarRecurso" method="POST" onsubmit="return agregarNuevoRecurso()">
    <div class="modal fade" id="modalAgregarRecurso" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Agregar Nuevo Recurso</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <label for="nombre">Nombre del recurso</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" required>
                    
                    <label for="descripcion">Descripción</label>
                    <textarea class="form-control" id="descripcion" name="descripcion" required></textarea>
                    
                    <label for="categSH">Categoría S/H</label>
                    <select class="form-control" id="categSH" name="categSH" required>
                        <option value="">Selecciona una opción</option>
                        <option value="Software">Software</option>
                        <option value="Hardware">Hardware</option>
                        <option value="Otro">Otro</option>
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button class="btn btn-primary">Agregar</button>
                </div>
            </div>
        </div>
    </div>
</form>