<?php
require_once __DIR__ . '/classes/classProducto.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // ---- Manejo de la imagen subida ----
    $nombreImagen = $_POST['imagen_actual'] ?? '';

    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $carpetaDestino = __DIR__ . '/uploads/productos/';
        if (!is_dir($carpetaDestino)) {
            mkdir($carpetaDestino, 0777, true);
        }

        $extension = strtolower(pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION));
        $permitidas = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

        if (in_array($extension, $permitidas)) {
            $nombreImagen = uniqid('prod_') . '.' . $extension;
            move_uploaded_file($_FILES['imagen']['tmp_name'], $carpetaDestino . $nombreImagen);
        }
    }

    $producto = new datosProductos();
    $producto->set_nom_producto(trim($_POST['nom_producto'] ?? ''));
    $producto->set_costoproducto((float) ($_POST['costo'] ?? 0));
    $producto->set_porc_ventapro((float) ($_POST['porc_venta'] ?? 0));
    $producto->set_precio_ventapro((float) ($_POST['precio_venta'] ?? 0));
    $producto->set_imagenpro($nombreImagen);
    $producto->set_fechapro($_POST['fecha'] ?? date('Y-m-d'));

    if (!empty($_POST['codproducto'])) {
        // ---- Modo edición ----
        $producto->set_codproducto((int) $_POST['codproducto']);
        $producto->actualizarProducto();
    } else {
        // ---- Modo creación ----
        $producto->set_stockpro((int) ($_POST['stock'] ?? 0));
        $producto->guardarProducto();

        // El INSERT del libro no guarda el stock inicial (solo lo hace
        // actualizarStock), así que si el usuario definió un stock inicial
        // se aplica aquí usando el mismo método del libro.
        if ((int) ($_POST['stock'] ?? 0) > 0) {
            $conexionTmp = Conexion::obtenerConexion();
            $idNuevo = (int) $conexionTmp->lastInsertId();
            datosProductos::actualizarStock($idNuevo, 0, (int) $_POST['stock']);
        }
    }
}

header("Location: frmproducto.php?msg=ok");
exit;
