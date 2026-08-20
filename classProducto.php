<?php
require_once __DIR__ . '/../config/Conexion.php';


class datosProductos
{
    const TABLA = 'inventario';

    private $codproducto     = null;
    private $nom_producto    = "";
    private $costoproducto   = 0.00;
    private $porc_ventapro   = 0;
    private $precio_ventapro = 0.00;
    private $imagenpro       = "";
    private $stockpro        = 0;
    private $fechapro        = null;

    public function __construct()
    {
    }


    public function get_codproducto() {
        return $this->codproducto;
    }
    public function get_nom_producto() {
        return $this->nom_producto;
    }
    public function get_costoproducto() {
        return $this->costoproducto;
    }
    public function get_porc_ventapro() {
        return $this->porc_ventapro;
    }
    public function get_precio_ventapro() {
        return $this->precio_ventapro;
    }
    public function get_imagenpro() {
        return $this->imagenpro;
    }
    public function get_stockpro() {
        return $this->stockpro;
    }
    public function get_fechapro() {
        return $this->fechapro;
    }

   

    
    public function set_codproducto($codproducto) {
        $this->codproducto = $codproducto;
    }
    public function set_nom_producto($nom_producto) {
        $this->nom_producto = $nom_producto;
    }
    public function set_costoproducto($costoproducto) {
        $this->costoproducto = $costoproducto;
    }
    public function set_porc_ventapro($porc_ventapro) {
        $this->porc_ventapro = $porc_ventapro;
    }
    public function set_precio_ventapro($precio_ventapro) {
        $this->precio_ventapro = $precio_ventapro;
    }
    public function set_imagenpro($imagenpro) {
        $this->imagenpro = $imagenpro;
    }
    public function set_stockpro($stockpro) {
        $this->stockpro = $stockpro;
    }
    public function set_fechapro($fechapro) {
        $this->fechapro = $fechapro;
    }


    public function guardarProducto()
    {
        $conexion = Conexion::obtenerConexion();
        // Preparar la consulta
        $consulta = $conexion->prepare('INSERT INTO ' . self::TABLA . '
            (nom_producto, costo, porc_venta, precio_venta, Imagen, Fecha)
            VALUES(:producto, :pcosto, :pporc_venta, :pprecio_venta, :pImagen, :pFecha)');

   
        $consulta->bindParam(':producto', $this->nom_producto);
        $consulta->bindParam(':pcosto', $this->costoproducto);
        $consulta->bindParam(':pporc_venta', $this->porc_ventapro);
        $consulta->bindParam(':pprecio_venta', $this->precio_ventapro);
        $consulta->bindParam(':pImagen', $this->imagenpro);
        $consulta->bindParam(':pFecha', $this->fechapro);
        $consulta->execute(); // Ejecutar la consulta
        $conexion = null; // Cerrar conexión
    }

    public function actualizarProducto()
    {
        $conexion = Conexion::obtenerConexion();
        
        $consulta = $conexion->prepare('UPDATE ' . self::TABLA . ' SET nom_producto=:producto,
            costo=:pcosto, porc_venta=:pporc_venta, precio_venta=:pprecio_venta,
            Imagen=:pImagen, Fecha=:pFecha where codigo=:codpro');

        
        $consulta->bindParam(':producto', $this->nom_producto);
        $consulta->bindParam(':pcosto', $this->costoproducto);
        $consulta->bindParam(':pporc_venta', $this->porc_ventapro);
        $consulta->bindParam(':pprecio_venta', $this->precio_ventapro);
        $consulta->bindParam(':pImagen', $this->imagenpro);
        $consulta->bindParam(':pFecha', $this->fechapro);
        $consulta->bindParam(':codpro', $this->codproducto);
        $consulta->execute(); 
        $conexion = null; 
    }

    
    public static function actualizarStock($v_idpro, $canstock, $nuevacant)
    {
        $nuevo_stock = 0;
        if (isset($v_idpro, $canstock, $nuevacant)) {
            $nuevo_stock = $canstock + $nuevacant;
        } else {
            exit;
        }

        $conexion = Conexion::obtenerConexion();
        
        $consulta = $conexion->prepare('UPDATE ' . self::TABLA . ' SET stock=:p_stock where codigo=:codpro');
        
        $consulta->bindParam(':p_stock', $nuevo_stock);
        $consulta->bindParam(':codpro', $v_idpro);
        $consulta->execute(); 
        $conexion = null; 
        return $nuevo_stock;
    }

  
    public static function todosProductos()
    {
        $conexion = Conexion::obtenerConexion();
        $consulta = $conexion->prepare('SELECT COUNT(*) FROM ' . self::TABLA);
        $consulta->execute();
        $registros = $consulta->fetchColumn();
        return $registros;
    }


    public static function consultarProductoCod($codproducto)
    {
        $conexion = Conexion::obtenerConexion();
        $consulta = $conexion->prepare('SELECT * FROM ' . self::TABLA . ' where codigo=:codpro');
        $consulta->bindParam(':codpro', $codproducto);
        $consulta->execute();
        $registros = $consulta->fetchAll(PDO::FETCH_OBJ);
        return $registros;
    }


    public static function listarProductos()
    {
        $conexion = Conexion::obtenerConexion();
        $consulta = $conexion->prepare('SELECT * FROM ' . self::TABLA . ' ORDER BY codigo ASC');
        $consulta->execute();
        return $consulta->fetchAll(PDO::FETCH_OBJ);
    }

    
    public function eliminarproducto()
    {
        $conexion = Conexion::obtenerConexion();
        // Preparar la consulta
        $consulta = $conexion->prepare('DELETE FROM ' . self::TABLA . ' where codigo=:codpro');
        // Asignar los valores
        $consulta->bindParam(':codpro', $this->codproducto);
        $consulta->execute(); // Ejecutar la consulta
        $conexion = null; // Cerrar conexión
    }
}
