<?php
// Incluir el archivo de conexión a la base de datos
include "../../../clases/Conexion.php";

// Crear una instancia de conexión
$conexion = new Conexion();
$conexion = $conexion->conectar();

// Consulta SQL para obtener todas las áreas ordenadas por nombre
$sql = "SELECT 	idrarea, Nomb_area FROM t_area ORDER BY Nomb_area";
$result = mysqli_query($conexion, $sql);

// Verificar si hay resultados
if (mysqli_num_rows($result) > 0) {
?>
    <table class="table table-sm table-hover table-responsive">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre del Área</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($mostrar = mysqli_fetch_array($result)) { ?>
            <tr>
                <td><?php echo $mostrar['idrarea']; ?></td>
                <td><?php echo $mostrar['Nomb_area']; ?></td>
                <td>
                    <button class="btn btn-warning btn-sm" 
                            onclick="obtenerDatosArea(<?php echo $mostrar['idrarea']; ?>)">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn btn-danger btn-sm" 
                            onclick="eliminarArea(<?php echo $mostrar['idrarea']; ?>)">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
<?php
} else {
    echo '<h4 class="text-center">No hay áreas registradas</h4>';
}
?>