<?php
require_once __DIR__ . '/classes/classProducto.php';

if (isset($_GET['id'])) {
    $producto = new datosProductos();
    $producto->set_codproducto((int) $_GET['id']);
    $producto->eliminarproducto();
}

header("Location: frmproducto.php?msg=eliminado");
exit;
