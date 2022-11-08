<?php
require_once("../dataBase/Connector.php");

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Producto
 *
 * @author ANGEL
 */
class Producto {
    private $id;
	private $nombre;
	private $referencia;
	private $precio;
	private $peso;
	private $id_categoria;
	private $stock;
	private $fecha_creacion;
    
    function __construct($field, $value) {
        if ($field!=null) {
            if (is_array($field)) {
                foreach ($field as $Variable => $Value) $this->$Variable=$Value;
            } else {
                $query="select id, nombre, referencia, precio, peso, id_categoria, stock, fecha_creacion from productos where $field='$value'";
                $result = Connector::executeQuery($query, null);
                if(is_array($result)){
                    if (count($result)>0){
                        foreach ($result[0] as $Variable => $Value) $this->$Variable=$Value;
                    }
                }
            }
        }
    }

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }


    public function getNombre() {
        return trim($this->nombre);
    }

    public function setNombre($nombre) {
        $this->nombre = trim($nombre);
    }


    public function getReferencia() {
        return trim($this->referencia);
    }

    public function setReferencia($referencia) {
        $this->referencia = trim($referencia);
    }

    public function getPrecio() {
        return $this->precio;
    }

    public function setPrecio($precio) {
        $this->precio = $precio;
    }

    public function getPeso() {
        return $this->peso;
    }

    public function setPeso($peso) {
        $this->peso = $peso;
    }

    public function getIdCategoria() {
        return $this->id_categoria;
    }

    public function getCategoria() {
        return new Categoria('id', $this->id_categoria);
    }

    public function setIdCategoria($id_categoria) {
        $this->id_categoria = $id_categoria;
    }

    public function getStock() {
        return $this->stock;
    }

    public function setStock($stock) {
        $this->stock = $stock;
    }

    public function getFechaCreacion() {
        return $this->fecha_creacion;
    }

    public function setFechaCreacion($fecha_creacion) {
        $this->fecha_creacion = $fecha_creacion;
    }

    public function save(){
        $nombre = $this->getNombre();
        $referencia = $this->getReferencia();
        $precio = $this->getPrecio();
        $peso = $this->getPeso();
        $id_categoria = $this->getIdCategoria();
        $stock = $this->getStock();
        $query="insert into productos (nombre ,referencia ,precio ,peso ,id_categoria ,stock, fecha_creacion) values ('$nombre', '$referencia', $precio, $peso, $id_categoria, $stock, now());";
        $response = Connector::executeQuery($query,null);
        $data = (object) array("success" => $response, "query" => $query);
        return $data;
    }

    public function update(){
        $id = $this->getId();
        $nombre = $this->getNombre();
        $referencia = $this->getReferencia();
        $precio = $this->getPrecio();
        $peso = $this->getPeso();
        $id_categoria = $this->getIdCategoria();
        $stock = $this->getStock();
        $query="update productos set nombre = '$nombre', referencia = '$referencia', precio = $precio, peso = $peso, id_categoria = $id_categoria, stock = $stock where id = '$id' ;";
        //echo $query;
        $response = Connector::executeQuery($query,null);
        $data = (object) array("success" => $response, "query" => $query);
        return $data;
    }

    public function delete(){
        $id_producto = $this->getId();
        $query="delete from productos where id = '$id_producto' ;";
        $response = Connector::executeQuery($query,null);
        $data = (object) array("success" => $response, "query" => $query);
        return $data;
    }

    public static function getList($filter){
        if ($filter!=null) $filter=" where $filter";
        $query="select p.id, p.nombre, referencia, precio, peso, id_categoria, stock, fecha_creacion from productos p inner join categorias c on p.id_categoria = c.id $filter order by p.id";
        //echo $query;
        return Connector::executeQuery($query,null);
    }

    public static function getListInObjects($filter){
        $data= Producto::getList($filter);
        $productos= Array();
        if(is_array($data)){
            for ($i = 0; $i < count($data); $i++) {
                $productos[$i]=new Producto($data[$i], null);
            }
        }
        
        return $productos;
    }
    
    public function existe_referencia($id, $referencia){
        $filtro = "referencia='$referencia'";
        $existe = false;
        $producto = Producto::getListInObjects($filtro);
        if(!empty($producto)){
            if($producto[0]->getId() == $id){
                $existe = false;
            }else{
                $existe = true;
            }
        }
        return $existe;
    }

    public static function vender($id, $cantidad){
        $producto = Producto::getListInObjects(" p.id = $id");
        if(!empty($producto) && count($producto) > 0){
            $stock_actual = $producto[0]->getStock() ;
            if ( $stock_actual >= $cantidad ) {
                $stock = $stock_actual - $cantidad;
                $query="update productos set stock = $stock where id = '$id' ;";
                Connector::executeQuery($query,null);
                return "vendido";
            }else{
                return "sin_stock";
            }
        }else{
            return "no_encontrado";
        }
    }

    public function validar_datos(Producto $producto, $metodo){
        $msj = "";
        $referencia = $producto->getReferencia();
        $id = $producto->getId();
        if (empty($producto->getNombre())) {
            $msj .= "\n- El nombre del producto no puede estar vacio.";
        }

        if (empty($referencia)) {
            $msj .= "\n- La referencia del producto no puede estar vacia.";
        }else{
            if ($producto->existe_referencia($id, $referencia)) $msj .= "\n- La referencia '$referencia' ya se encuentra registrada.";
        }

        if (empty($producto->getPrecio())) {
            $msj .= "\n- El precio del producto no puede estar vacio.";
        }

        if (empty($producto->getPeso())) {
            $msj .= "\n- El peso del producto no puede estar vacio.";
        }

        if (empty($producto->getIdCategoria())) {
            $msj .= "\n- La categoria del producto no puede estar vacia.";
        }

        if (empty($producto->getStock())) {
            $msj .= "\n- El Stock del producto no puede estar vacio.";
        }else{
            if ($producto->getStock() <= 0) $msj .= "\n- El Stock del producto no puede ser igual o inferior a 0.";
        }
        return $msj;
    }

}
