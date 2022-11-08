<?php

require_once("../dataBase/Connector.php");

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Categoria
 *
 * @author ANGEL
 */
class Categoria {
    private $id;
    private $nombre;
    private $descripcion;

    function __construct($field, $value) {
        if ($field!=null) {
            if (is_array($field)) {
                foreach ($field as $Variable => $Value) $this->$Variable=$Value;
            } else {
                $query="select id, nombre, descripcion from categorias where $field='$value'";
                $result = Connector::executeQuery($query, null);
                if(is_array($result)){
                    if (count($result)>0){
                        foreach ($result[0] as $Variable => $Value) $this->$Variable=$Value;
                    }
                }
            }
        }
    }

    public function getId(){
        return $this->id;
    }

    public function setId($id){
        $this->id = $id;

        return $this;
    }
    
    public function getNombre(){
        return $this->nombre;
    }

    public function setNombre($nombre){
        $this->nombre = $nombre;

        return $this;
    }
    
    public function getDescripcion(){
        return $this->descripcion;
    }

    public function setDescripcion($descripcion){
        $this->descripcion = $descripcion;
        return $this;
    }

    public static function getList($filter){
        if ($filter!=null) $filter=" where $filter";
        $query="select id, nombre, descripcion from categorias $filter;";
        return Connector::executeQuery($query,null);
    }

    public static function getListInObjects($filter){
        $data= Categoria::getList($filter);
        $categorias= Array();
        if(is_array($data)){
            for ($i = 0; $i < count($data); $i++){
                $categorias[$i]=new Categoria($data[$i], null);
            }
        }
        return $categorias;
    }
}
