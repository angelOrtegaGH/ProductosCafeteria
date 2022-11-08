<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Connector
 *
 * @author ANGEL
 */
class Connector {
    private $server;
    private $port;
    private $controller;
    private $user;
    private $password;
    private $bd;
    private $connection;
    
    function __construct() {
        $file= dirname(__FILE__) . '/setting.ini';
        if (!file_exists($file)){
            echo "ERROR: No existe el archivo de $file";
            die();
        }
        if (!$parametros=parse_ini_file($file, true)){
            echo "ERROR: No se puedo leer el archivo $file";
            die();
        }
        $this->server = $parametros['BaseDatos']['server'];
        $this->port = $parametros['BaseDatos']['port'];
        $this->controller = $parametros['BaseDatos']['controller'];
        $this->user = $parametros['BaseDatos']['user'];
        $this->password = $parametros['BaseDatos']['password'];
        $this->bd = $parametros['BaseDatos']['bd'];
    }

    private function connect($bd){
        try {
            if ($bd==null) $bd=$this->bd;
            $options=array();
            $this->connection=new PDO("$this->controller:host=$this->server;port=$this->port;dbname=$bd",$this->user, $this->password,$options);
        } catch (Exception $exc) {
            $this->connection=null;            
            echo 'Error en la conexion con la bd' . $exc->getMessage();
            die();
        }
    }
    
    private function disconnect(){
        $this->connection=null;
    }

    private function convertUTF8($array){// funcion que convierte array de bd en utf8
        array_walk_recursive($array, function(&$item,$key){
            if(!mb_detect_encoding($item, 'utf-8', true)){
                $item = utf8_encode($item);
            }
        });
        return $array;
    }
    
    public static function executeQuery($query,$bd){
        $connector=new Connector();
        $connector->connect($bd);
        $statement=$connector->connection->prepare($query);
        try {
            if (!$statement->execute()){
                //echo "Error al ejecutar $query en $bd";
                $connector->disconnect();
                return(false);
            } else {
                $result=$statement->fetchAll(PDO::FETCH_ASSOC);
                $statement->closeCursor();
                $connector->disconnect();            
                $result = $connector->convertUTF8($result);
                if(count($result)>0)return $result;
                else return true;
            }
        } catch (\Throwable $th) {
            //throw $th;
            echo "Error al ejecutar el query $query";
            echo "</br> $th";
            return false;
        }
        
    }

    
}