<!-- Modal -->
<form id="frmAgregarUsuario" method="POST" onsubmit="return agregarNuevoUsuario()">
    <div class="modal fade" id="modalAgregarUsuarios" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Agregar nuevo usuario</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-4">
                            <label for="paterno">Apellido paterno</label>
                            <input type="text" class="form-control" id="paterno" name="paterno" required pattern="[A-Za-z]{3,}" 
                            title="Este campo es obligatorio y solo puede contener letras con al menos 3 caracteres.">
                        </div>
                        <div class="col-sm-4">
                            <label for="materno">Apellido materno</label>
                            <input type="text" class="form-control" id="materno" name="materno" required pattern="[A-Za-z]{3,}"
                            title="Este campo es obligatorio y solo puede contener letras con al menos 3 caracteres.">
                        </div>
                        <div class="col-sm-4">
                            <label for="nombre">Nombre</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required pattern="[A-Za-z]{3,}"
                            title="Este campo es obligatorio y solo puede contener letras con al menos 3 caracteres.">
                        </div>
                        </div>
                    <div class="row">
                        <div class="col-sm-4">
                            <label for="fechaNacimiento">Fecha de nacimiento</label>
                            <input type="date" class="form-control" id="fechaNacimiento" name="fechaNacimiento"
                            max="<?php echo date('Y-m-d'); ?>"
                            title="Debe ingresar una fecha de nacimiento válida."
                            >
                        </div>
                        <div class="col-sm-4">
                            <label for="sexo">Sexo</label>
                            <select class="form-control" id="sexo" name="sexo" required>
                                <option value=""></option>
                                <option value="F">Femenino</option>
                                <option value="M">Masculino</option>
                                <option value="O">Sin especificar</option>
                            </select>
                        </div>
                        <div class="col-sm-4">
                            <label for="telefono">Teléfono</label>
                            <input type="text" class="form-control" id="telefono" name="telefono" required
                                pattern="^\d{10}$" 
                                title="El teléfono debe contener solo 10 dígitos numéricos.">
                        </div>
                    </div>
                    <div class="row">
                    <div class="col-sm-4">
                        <label for="correo">Correo</label>
                        <input type="email"  class="form-control" id="correo" name="correo" required
                        title="Use el formato de correo electrónico válido.">
                    </div>
                        <div class="col-sm-4">
                            <label for="usuario">Usuario</label>
                            <input type="text" class="form-control" id="usuario" name="usuario" required
                            minlength="5" title="Al menos 5 caracteres." >
                        </div>
                        <div class="col-sm-4">
                            <label for="password">Password</label>
                            <input type="text" class="form-control" id="password" name="password" required
                            minlength="5" title="Al menos 5 caracteres.">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4">
                            <label for="idRol">Rol de usuario</label>
                            <select name="idRol" id="idRol" class="form-control">
                                 <option value=""></option>
                                <option value="1">Cliente</option>
                                <option value="2">Administrador</option>
                                <Option Value="3">Tecnico</Option>
                            </select>
                        </div>
                        <div class="col-sm-4">
        <label for="idArea">Área</label>
        <select name="idArea" id="idArea" class="form-control">
            <option value=""></option>
            <?php
                require_once "../clases/Conexion.php";
                $conexion = new Conexion();
                $conexion = $conexion->conectar();
                $sql = "SELECT idrarea, Nomb_area FROM t_area ORDER BY idrarea ASC";
                $respuesta = mysqli_query($conexion, $sql);
                while($mostrar = mysqli_fetch_array($respuesta)) {
                    echo '<option value="'.$mostrar['idrarea'].'">'.$mostrar['Nomb_area'].'</option>';
                }
            ?>
        </select>
                    </div>
                    <div class="row">
    </div>
</div>
<label for="ubicacion">Comentarios</label>
<textarea name="ubicacion" id="ubicacion" class="form-control"></textarea>
                
                <div class="modal-footer">
                <span class="btn btn-secondary" data-dismiss="modal">Cerrar</span>
                <button class="btn btn-primary">Agregar</button>
                </div>
            </div>
        </div>
    </div>
</form>