<?php
require_once __DIR__ . '/classes/Cliente.php';

if (isset($_GET['id'])) {
    $cliente = new Cliente();
    $cliente->eliminar((int) $_GET['id']);
}

$pagina = isset($_GET['pagina']) ? (int) $_GET['pagina'] : 1;
header("Location: frmcliente.php?pagina={$pagina}");
exit;
