<!-- Modal Alta de usuario-->
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
                            <label for="paterno">Apellido paterno *</label>
                            <input type="text" class="form-control" id="paterno" name="paterno" required 
                                pattern="^[A-Za-zÁÉÍÓÚáéíóúÑñ]+$" 
                                title="Solo letras, sin números ni caracteres especiales. Se permiten acentos.">
                        </div>
                        <div class="col-sm-4">
                            <label for="materno">Apellido materno *</label>
                            <input type="text" class="form-control" id="materno" name="materno" required 
                                pattern="^[A-Za-zÁÉÍÓÚáéíóúÑñ]+$" 
                                title="Solo letras, sin números ni caracteres especiales. Se permiten acentos.">
                        </div>
                        <div class="col-sm-4">
                            <label for="nombre">Nombre *</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required 
                                pattern="^[A-Za-zÁÉÍÓÚáéíóúÑñ]+$" 
                                title="Solo letras, sin números ni caracteres especiales. Se permiten acentos.">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4">
                            <label for="fechaNacimiento">Fecha de nacimiento</label>
                            <input type="date" class="form-control" id="fechaNacimiento" name="fechaNacimiento"
                                max="<?php echo date('Y-m-d'); ?>" 
                                title="Debe ingresar una fecha válida.">
                        </div>
                        <div class="col-sm-4">
                            <label for="sexo">Sexo *</label>
                            <select class="form-control" id="sexo" name="sexo" required>
                                <option value="">Seleccione...</option>
                                <option value="F">Femenino</option>
                                <option value="M">Masculino</option>
                                <option value="O">Sin especificar</option>
                            </select>
                        </div>
                        <div class="col-sm-4">
                            <label for="telefono">Teléfono</label>
                            <input type="text" class="form-control" id="telefono" name="telefono"
                                pattern="^\d{10}$" 
                                title="Debe contener exactamente 10 dígitos numéricos sin espacios.">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4">
                            <label for="correo">Correo *</label>
                            <input type="email" class="form-control" id="correo" name="correo" required
                                title="Ingrese un correo válido (ejemplo@dominio.com).">
                        </div>
                        <div class="col-sm-4">
                            <label for="usuario">Usuario *</label>
                            <input type="text" class="form-control" id="usuario" name="usuario" required
                                pattern="^[A-Za-z0-9]{5,}$" 
                                title="Debe contener al menos 5 caracteres, solo letras y números, sin acentos.">
                        </div>
                        <div class="col-sm-4">
<!--Agrege el icon para mostrar la contraseña-->
                            <label for="password">Contraseña *</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="password" name="password" required 
                                    pattern="(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}" 
                                    title="Debe contener al menos una letra, un número y un carácter especial. Mínimo 8 caracteres." 
                                    placeholder="Ingrese contraseña">
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-outline-secondary" onclick="togglePassword()">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <label for="idRol">Rol de usuario *</label>
                            <select name="idRol" id="idRol" class="form-control" required>
                                <option value="">Seleccione...</option>    
                                <option value="1">Cliente</option>
                                <option value="2">Administrador</option>
                                <option value="3">Técnico</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <label for="comentarios">Comentarios</label>
                            <textarea name="comentarios" id="comentarios" class="form-control"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <span class="btn btn-secondary" data-dismiss="modal">Cerrar</span>
                    <button class="btn btn-primary">Agregar</button>
                </div>
            </div>
        </div>
    </div>
</form>
<script>
function togglePassword() {
    var passwordField = document.getElementById("password");
    var icon = document.querySelector(".input-group-append i");
    
    if (passwordField.type === "password") {
        passwordField.type = "text";
        icon.classList.remove("fa-eye");
        icon.classList.add("fa-eye-slash");
    } else {
        passwordField.type = "password";
        icon.classList.remove("fa-eye-slash");
        icon.classList.add("fa-eye");
    }
}
</script>