<?php
require_once __DIR__ . '/classes/classProducto.php';


$listaProductos = datosProductos::listarProductos();

$datosEditar = null;
if (isset($_GET['editar'])) {
    $resultado = datosProductos::consultarProductoCod((int) $_GET['editar']);
    if (!empty($resultado)) {
        $datosEditar = $resultado[0];
    }
}

$mensaje = $_GET['msg'] ?? null;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ediciones Fares - Productos</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>

    <div class="navbar">
        <h1>Ediciones Fares</h1>
        <ul class="menu">
            <li><a href="frmcliente.php">Principal</a></li>
            <li><a href="#">Libros &#9662;</a></li>
            <li><a href="frmproducto.php" class="activo">Inventario &#9662;</a></li>
            <li><a href="stock.php">Stock</a></li>
            <li><a href="#">Contacto</a></li>
        </ul>
    </div>

    <?php if ($mensaje === 'ok'): ?>
        <div class="mensaje exito">El producto se guardó correctamente.</div>
    <?php elseif ($mensaje === 'eliminado'): ?>
        <div class="mensaje exito">El producto se eliminó correctamente.</div>
    <?php endif; ?>

    <div class="contenedor">

      
        <div class="panel-formulario">
            <div class="titulo">Ingresar datos del producto</div>
            <form action="guardarProducto.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="codproducto"
                       value="<?= $datosEditar->codigo ?? '' ?>">

                <div class="campo">
                    <label>Nombre del producto</label>
                    <input type="text" name="nom_producto" placeholder="Nombre del producto"
                           value="<?= htmlspecialchars($datosEditar->nom_producto ?? '') ?>" required>
                </div>

                <div class="fila-doble">
                    <div class="campo">
                        <label>Costo</label>
                        <input type="number" step="0.01" min="0" id="costo" name="costo"
                               placeholder="0.00"
                               value="<?= htmlspecialchars($datosEditar->costo ?? '') ?>" required>
                    </div>
                    <div class="campo">
                        <label>% de venta</label>
                        <input type="number" step="0.01" min="0" id="porc_venta" name="porc_venta"
                               placeholder="0"
                               value="<?= htmlspecialchars($datosEditar->porc_venta ?? '') ?>" required>
                    </div>
                </div>

                <div class="campo">
                    <label>Precio de venta (calculado)</label>
                    <input type="number" step="0.01" id="precio_venta" name="precio_venta"
                           value="<?= htmlspecialchars($datosEditar->precio_venta ?? '0.00') ?>" readonly>
                </div>

                <div class="campo">
                    <label>Fecha</label>
                    <input type="date" name="fecha"
                           value="<?= htmlspecialchars($datosEditar->Fecha ?? date('Y-m-d')) ?>" required>
                </div>

                <?php if (empty($datosEditar)): ?>
                <div class="campo">
                    <label>Stock inicial</label>
                    <input type="number" min="0" name="stock" placeholder="0" value="0" required>
                </div>
                <?php endif; ?>

                <div class="campo">
                    <label>Imagen del producto</label>
                    <input type="file" name="imagen" accept="image/*">
                    <?php if (!empty($datosEditar->Imagen)): ?>
                        <input type="hidden" name="imagen_actual" value="<?= htmlspecialchars($datosEditar->Imagen) ?>">
                        <img src="uploads/productos/<?= htmlspecialchars($datosEditar->Imagen) ?>"
                             class="img-producto" style="margin-top:8px;">
                    <?php endif; ?>
                </div>

                <button type="submit" class="btn-guardar">Guardar</button>
            </form>
        </div>

        
        <div class="panel-tabla">
            <div class="titulo">Lista de productos</div>

            <table>
                <thead>
                    <tr>
                        <th>Imagen</th>
                        <th>Código</th>
                        <th>Producto</th>
                        <th>Costo</th>
                        <th>% Venta</th>
                        <th>Precio venta</th>
                        <th>Stock</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (count($listaProductos) > 0): ?>
                    <?php foreach ($listaProductos as $p): ?>
                        <tr>
                            <td>
                                <?php if (!empty($p->Imagen)): ?>
                                    <img src="uploads/productos/<?= htmlspecialchars($p->Imagen) ?>" class="img-producto">
                                <?php else: ?>
                                    <div class="img-producto sin-imagen">Sin<br>imagen</div>
                                <?php endif; ?>
                            </td>
                            <td><?= $p->codigo ?></td>
                            <td><?= htmlspecialchars($p->nom_producto) ?></td>
                            <td>$<?= number_format($p->costo, 2) ?></td>
                            <td><?= number_format($p->porc_venta, 2) ?>%</td>
                            <td>$<?= number_format($p->precio_venta, 2) ?></td>
                            <td><?= (int) $p->stock ?></td>
                            <td>
                                <div class="acciones">
                                    <a class="btn-accion btn-editar"
                                       href="frmproducto.php?editar=<?= $p->codigo ?>"
                                       title="Editar">&#9998;</a>
                                    <a class="btn-accion btn-eliminar"
                                       href="eliminarProducto.php?id=<?= $p->codigo ?>"
                                       title="Eliminar"
                                       onclick="return confirm('¿Desea eliminar este producto?');">&#128465;</a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" class="sin-registros">No hay productos registrados.</td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>

    </div>

    <script>
  
        const costo = document.getElementById('costo');
        const porcVenta = document.getElementById('porc_venta');
        const precioVenta = document.getElementById('precio_venta');

        function calcularPrecio() {
            const c = parseFloat(costo.value) || 0;
            const p = parseFloat(porcVenta.value) || 0;
            const resultado = c + (c * p / 100);
            precioVenta.value = resultado.toFixed(2);
        }

        costo.addEventListener('input', calcularPrecio);
        porcVenta.addEventListener('input', calcularPrecio);
    </script>

</body>
</html>
