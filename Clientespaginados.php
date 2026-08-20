<?php
require_once 'conexionf2.php';
require_once 'paginacionf.php';

$conexion = new Conexion();
$paginaActual = isset($_GET['pagina']) && is_numeric($_GET['pagina']) ? (int) $_GET['pagina'] : 1;
$registrosPorPagina = 5;

$paginador = new paginacionf($conexion, 'clientes', $registrosPorPagina, $paginaActual);
$totalRegistros = $paginador->totalregistros();
$totalPaginas = $paginador->totalpaginas();
$clientes = $paginador->limitregistros();
$paginaActual = $paginador->paginaActual();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Clientes</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <style>
        body {
            background: linear-gradient(135deg, #eef4ff 0%, #fdf2f8 50%, #fef3c7 100%);
        }
        .panel-principal {
            max-width: 980px;
            margin: 0 auto;
            border: 1px solid #dbeafe;
        }
        .encabezado {
            background: #2563eb;
            color: white;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.2);
        }
        .btn-primary {
            background-color: #2563eb;
            border-color: #2563eb;
        }
        .btn-outline-secondary {
            color: #4b5563;
            border-color: #9ca3af;
        }
        .table thead {
            background-color: #2563eb;
            color: white;
        }
        .page-item.active .page-link {
            background-color: #7c3aed;
            border-color: #7c3aed;
        }
        .page-link {
            color: #7c3aed;
        }
        .w3-table-all tr:nth-child(even) {
            background-color: #f8fafc;
        }
    </style>
</head>
<body>
<div class="container py-4">
    <div class="w3-card-4 w3-white w3-round-large panel-principal">
        <header class="encabezado w3-container w3-padding-24 w3-round-top">
            <h2 class="w3-margin-0 text-center">Lista de Clientes</h2>
        </header>

        <div class="w3-container w3-padding-24">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <p class="mb-0 text-muted">Total de registros: <strong><?php echo $totalRegistros; ?></strong></p>
                <a href="frmcliente.php" class="btn btn-outline-secondary btn-sm w3-round-xlarge"><i class="fas fa-arrow-left"></i> Volver</a>
            </div>

            <div class="table-responsive">
                <table class="table table-hover w3-table-all w3-striped">
                    <thead class="thead-dark">
                        <tr>
                            <th>#</th>
                            <th>Nombre</th>
                            <th>Dirección</th>
                            <th>Email</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($clientes) > 0) { ?>
                            <?php foreach ($clientes as $cliente) { ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($cliente['idcli'], ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?php echo htmlspecialchars($cliente['nomcli'], ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?php echo htmlspecialchars($cliente['direccion'] ?? '-', ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?php echo htmlspecialchars($cliente['email_cli'] ?? '-', ENT_QUOTES, 'UTF-8'); ?></td>
                                </tr>
                            <?php } ?>
                        <?php } else { ?>
                            <tr>
                                <td colspan="4" class="text-center py-4">No hay clientes para mostrar.</td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>

            <?php if ($totalPaginas > 1) { ?>
                <nav aria-label="Paginación" class="mt-4">
                    <ul class="pagination justify-content-center">
                        <li class="page-item <?php echo $paginaActual <= 1 ? 'disabled' : ''; ?>">
                            <a class="page-link" href="Clientespaginados.php?pagina=<?php echo max(1, $paginaActual - 1); ?>">Anterior</a>
                        </li>

                        <?php for ($i = 1; $i <= $totalPaginas; $i++) { ?>
                            <li class="page-item <?php echo $i == $paginaActual ? 'active' : ''; ?>">
                                <a class="page-link" href="Clientespaginados.php?pagina=<?php echo $i; ?>"><?php echo $i; ?></a>
                            </li>
                        <?php } ?>

                        <li class="page-item <?php echo $paginaActual >= $totalPaginas ? 'disabled' : ''; ?>">
                            <a class="page-link" href="Clientespaginados.php?pagina=<?php echo min($totalPaginas, $paginaActual + 1); ?>">Siguiente</a>
                        </li>
                    </ul>
                </nav>
            <?php } ?>
        </div>
    </div>
</div>
</body>
</html>
