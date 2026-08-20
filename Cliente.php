<?php
require_once __DIR__ . '/../config/Conexion.php';


class Cliente
{
    private PDO $conexion;

  
    private int $registrosPorPagina = 6;

    // Atributos del cliente
    private ?int $id_cliente = null;
    private string $nombre = "";
    private string $direccion = "";
    private string $telefono_residencial = "";
    private string $celular = "";
    private string $email = "";

    public function __construct()
    {
        $this->conexion = Conexion::obtenerConexion();
    }


    public function setId($id): void { $this->id_cliente = $id; }
    public function setNombre($nombre): void { $this->nombre = $nombre; }
    public function setDireccion($direccion): void { $this->direccion = $direccion; }
    public function setTelefonoResidencial($telefono): void { $this->telefono_residencial = $telefono; }
    public function setCelular($celular): void { $this->celular = $celular; }
    public function setEmail($email): void { $this->email = $email; }

    public function getId() { return $this->id_cliente; }
    public function getNombre() { return $this->nombre; }

    public function guardar(): bool
    {
        $sql = "INSERT INTO clientes (nombre, direccion, telefono_residencial, celular, email)
                VALUES (:nombre, :direccion, :telefono_residencial, :celular, :email)";
        $stmt = $this->conexion->prepare($sql);

        return $stmt->execute([
            ':nombre'               => $this->nombre,
            ':direccion'            => $this->direccion,
            ':telefono_residencial' => $this->telefono_residencial,
            ':celular'              => $this->celular,
            ':email'                => $this->email,
        ]);
    }

    public function actualizar(): bool
    {
        $sql = "UPDATE clientes
                SET nombre = :nombre,
                    direccion = :direccion,
                    telefono_residencial = :telefono_residencial,
                    celular = :celular,
                    email = :email
                WHERE id_cliente = :id_cliente";
        $stmt = $this->conexion->prepare($sql);

        return $stmt->execute([
            ':nombre'               => $this->nombre,
            ':direccion'            => $this->direccion,
            ':telefono_residencial' => $this->telefono_residencial,
            ':celular'              => $this->celular,
            ':email'                => $this->email,
            ':id_cliente'           => $this->id_cliente,
        ]);
    }

    public function eliminar(int $id): bool
    {
        $sql = "DELETE FROM clientes WHERE id_cliente = :id_cliente";
        $stmt = $this->conexion->prepare($sql);
        return $stmt->execute([':id_cliente' => $id]);
    }

    public function obtenerPorId(int $id): array|false
    {
        $sql = "SELECT * FROM clientes WHERE id_cliente = :id_cliente";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute([':id_cliente' => $id]);
        return $stmt->fetch();
    }

    public function listarClientes(int $pagina = 1): array
    {
        if ($pagina < 1) {
            $pagina = 1;
        }

        $inicio = ($pagina - 1) * $this->registrosPorPagina;

        $sql = "SELECT * FROM clientes ORDER BY id_cliente ASC LIMIT :inicio, :registros";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindValue(':inicio', $inicio, PDO::PARAM_INT);
        $stmt->bindValue(':registros', $this->registrosPorPagina, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function totalRegistros(): int
    {
        $sql = "SELECT COUNT(*) AS total FROM clientes";
        $stmt = $this->conexion->query($sql);
        $fila = $stmt->fetch();
        return (int) $fila['total'];
    }

    public function totalPaginas(): int
    {
        return (int) ceil($this->totalRegistros() / $this->registrosPorPagina);
    }

    public function getRegistrosPorPagina(): int
    {
        return $this->registrosPorPagina;
    }
}
