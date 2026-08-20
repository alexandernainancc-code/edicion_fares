<?php
require_once __DIR__ . '/classes/classProducto.php';

$listaProductos = datosProductos::listarProductos();
$totalProductos = datosProductos::todosProductos();

$mensaje = $_GET['msg'] ?? null;


function claseStock(int $cantidad): string
{
    if ($cantidad <= 5) return 'bajo';
    if ($cantidad <= 20) return 'medio';
    return 'alto';
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ediciones Fares - Control de Stock</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>

    <div class="navbar">
        <h1>Ediciones Fares</h1>
        <ul class="menu">
            <li><a href="frmcliente.php">Principal</a></li>
            <li><a href="#">Libros &#9662;</a></li>
            <li><a href="frmproducto.php">Inventario &#9662;</a></li>
            <li><a href="stock.php" class="activo">Stock</a></li>
            <li><a href="#">Contacto</a></li>
        </ul>
    </div>

    <?php if ($mensaje === 'ok'): ?>
        <div class="mensaje exito">El stock se actualizó correctamente.</div>
    <?php elseif ($mensaje === 'error'): ?>
        <div class="mensaje error">No se pudo actualizar el stock. Verifica la cantidad.</div>
    <?php endif; ?>

    <div class="contenedor" style="flex-direction: column;">

        <div class="panel-tabla" style="min-width: 100%;">
            <div class="titulo">
                Apartado de Stock &mdash; Total de productos: <?= (int) $totalProductos ?>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>Imagen</th>
                        <th>Código</th>
                        <th>Producto</th>
                        <th>Stock actual</th>
                        <th>Movimiento</th>
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
                            <td>
                                <span class="badge-stock <?= claseStock((int) $p->stock) ?>">
                                    <?= (int) $p->stock ?>
                                </span>
                            </td>
                            <td>
                                <form class="form-stock" action="actualizarStock.php" method="POST">
                                    <input type="hidden" name="codigo" value="<?= $p->codigo ?>">
                                    <input type="hidden" name="stock_actual" value="<?= (int) $p->stock ?>">

                                    <select name="tipo_movimiento" required>
                                        <option value="entrada">Entrada (+)</option>
                                        <option value="salida">Salida (-)</option>
                                    </select>

                                    <input type="number" name="cantidad" min="1" placeholder="Cant." required>

                                    <button type="submit" class="btn-stock">Actualizar</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="sin-registros">No hay productos registrados.</td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>

    </div>

</body>
</html>
