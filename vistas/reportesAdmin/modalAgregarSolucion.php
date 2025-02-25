<!-- Modal Soluciones -->
<form id="frmAgregarSolucionReporte" method="POST" onsubmit="return agregarSolucionReporte()">
    <div class="modal fade" id="modalAgregarSolucionReporte" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Escribe la solución</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="text" id="idReporte" name="idReporte" hidden>
                <label for="solucion">Descripción de la solución</label>
                <textarea name="solucion" id="solucion" class="form-control" required></textarea>
                <label for="estatus">Estatus</label>
                <select name="estatus" id="estatus" class="form-control">
                    <option value="1">Abierto</option>
                    <option value="0">Cerrado</option>
                </select>
                
                <!-- Nueva sección para archivos adjuntos -->
                <div class="mt-4">
                    <div class="card">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">Archivos adjuntos del reporte</h6>
                        </div>
                        <div class="card-body" id="listaArchivosAdjuntos">
                            <!-- Aquí se cargarán dinámicamente los archivos -->
                            <div class="text-center">
                                <small class="text-muted">Cargando archivos...</small>
                                <div class="spinner-border spinner-border-sm text-primary mt-2" role="status">
                                    <span class="sr-only">Cargando...</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button class="btn btn-success">Guardar</button>
            </div>
            </div>
        </div>
    </div>
</form>