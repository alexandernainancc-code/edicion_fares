<?php
require_once __DIR__ . '/classes/classProducto.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $codigo       = (int) ($_POST['codigo'] ?? 0);
    $stockActual  = (int) ($_POST['stock_actual'] ?? 0);
    $cantidad     = (int) ($_POST['cantidad'] ?? 0);
    $tipo         = $_POST['tipo_movimiento'] ?? 'entrada';

    // La "salida" resta, la "entrada" suma. Se convierte a negativo si es salida.
    $nuevacant = ($tipo === 'salida') ? -$cantidad : $cantidad;

    // Evita que el stock quede negativo en una salida
    if ($tipo === 'salida' && $cantidad > $stockActual) {
        header("Location: stock.php?msg=error");
        exit;
    }

    if ($codigo > 0 && $cantidad > 0) {
        // Método tal como lo define el libro: actualizarStock($v_idpro, $canstock, $nuevacant)
        datosProductos::actualizarStock($codigo, $stockActual, $nuevacant);
        header("Location: stock.php?msg=ok");
        exit;
    }
}

header("Location: stock.php?msg=error");
exit;
