<?php

require_once("../Models/Categoria.php");
foreach ($_GET as $key => $value) ${$key} = $value;

$output = (object) array();

switch ($action){
    case 'list':
        $productos = Categoria::getListInObjects(null); 
        $output->status = "success";
        $rows = count($productos);
        $data = array();
        if ($rows > 0) {
            for ($i=0; $i < $rows; $i++) { 
                $producto = $productos[$i];
                array_push($data, array(
                                        "id" => $producto->getId(),
                                        "nombre" => $producto->getNombre(),
                                        "descripcion" => $producto->getDescripcion()));
            }
            $output->data = $data;
        }else{
            $output->message = "No hay categorias registradas.";
        }
        break;
    default:
        $output->status = "error";
        $output->message = "Metodo no soportado, por favor contacte con el administrador.";
        break;
}

echo json_encode($output);