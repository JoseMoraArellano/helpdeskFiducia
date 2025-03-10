<!-- Modal para Gestión de Áreas -->
<div class="modal fade" id="modalAgregarAreas" tabindex="-1" role="dialog" aria-labelledby="modalAreasLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalAreasLabel">Gestión de Áreas</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Formulario para agregar nueva área -->
                <div class="row mb-4">
                    <div class="col-sm-12">
                        <div class="card">
                            <div class="card-header">
                                <h5>Agregar Nueva Área</h5>
                            </div>
                            <div class="card-body">
                                <form id="frmAgregarArea" onsubmit="return agregarNuevaArea()">
                                    <div class="form-group">
                                        <label for="nombreArea">Nombre del Área</label>
                                        <input type="text" class="form-control" id="nombreArea" name="nombreArea" required   
                                        pattern="[A-Za-zÁÉÍÓÚáéíóúÜüÑñ0-9áéíóúÁÉÍÓÚÜüÑñ ]{3,50}"                              
                                        title="El nombre debe tener entre 3 y 50 caracteres (letras, números y espacios)">
                                    </div>
                                    <button class="btn btn-primary btn-sm btn-block">
                                        <i class="fas fa-plus-circle"></i> Agregar Área
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Tabla de áreas existentes -->
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card">
                            <div class="card-header">
                                <h5>Áreas Existentes</h5>
                            </div>
                            <div class="card-body">
                                <div id="tablaAreasLoad"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para editar área -->
<div class="modal fade" id="modalEditarArea" tabindex="-1" role="dialog" aria-labelledby="modalEditarAreaLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEditarAreaLabel">Editar Área</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="frmEditarArea" onsubmit="return actualizarArea()">
                    <input type="hidden" id="idrareaEditar" name="idrareaEditar">
                    <div class="form-group">
                        <label for="nombreAreaEditar">Nombre del Área</label>
                        <input type="text" class="form-control" id="nombreAreaEditar" name="nombreAreaEditar" required 
                        pattern="[A-Za-z0-9 ]{3,50}" 
                        title="El nombre debe tener entre 3 y 50 caracteres (letras, números y espacios)">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="submit" form="frmEditarArea" class="btn btn-primary">Guardar Cambios</button>
            </div>
        </div>
    </div>
</div>