<?php

require_once("../Models/Producto.php");
require_once("../Models/Categoria.php");
foreach ($_POST as $key => $value) ${$key} = $value;
foreach ($_GET as $key => $value) ${$key} = $value;

$output = (object) array();

switch ($action){
    case 'save':
        $producto = new Producto(null, null);
        $producto->setNombre($nombre);
        $producto->setReferencia($referencia);
        $producto->setPrecio($precio);
        $producto->setPeso($peso);
        $producto->setIdCategoria($categoria);
        $producto->setStock($stock);
        $msj = $producto->validar_datos($producto, "$action");
        
        if (empty($msj)) {
            $response = $producto->save();
            if($response->success){
                $output->status = "success";
                $output->message = "El producto fue almacenado correctamente.";
            }else{
                $output->status = "error";
                $output->message = "Se presento un error al almacenar el producto, por favor contacte con el administrador.";
                $query = $response->query;
                $output->query = "$query";
            }
        }else{
            $output->status = "error";
            $output->message = "Se presentaron los siguientes inconvenientes con el formulario \n $msj";
        }
        break;
    case 'update':
        $producto = new Producto('id', $id);
        $producto->setNombre($nombre);
        $producto->setReferencia($referencia);
        $producto->setPrecio($precio);
        $producto->setPeso($peso);
        $producto->setIdCategoria($categoria);
        $producto->setStock($stock);
        $msj = $producto->validar_datos($producto, "$action");
        
        if (empty($msj)) {
            $response = $producto->update();
            if($response->success){
                $output->status = "success";
                $output->message = "El producto fue actualizado correctamente.";
            }else{
                $output->status = "error";
                $output->message = "Se presento un error al actualizar el producto, por favor contacte con el administrador.";
                $query = $response->query;
                $output->query = "$query";
            }
        }else{
            $output->status = "error";
            $output->message = "Se presentaron los siguientes inconvenientes con el formulario \n $msj";
        }
        break;
    case 'delete':
        $producto = new Producto('id', $id);
        $response = $producto->delete();
        if($response->success){
            $output->status = "success";
            $output->message = "El producto se eliminó correctamente.";
        }else{
            $output->status = "error";
            $output->message = "Se presento un error al eliminar el producto, por favor contacte con el administrador.";
            $query = $response->query;
            $output->query = "$query";
        }
        break;
    case 'list':
        if(!isset($filter) || $filter == 'null') {
            $filter = null;
        }
        $productos = Producto::getListInObjects($filter); 
        $output->status = "success";
        $rows = count($productos);
        $data = array();
        if ($rows > 0) {
            for ($i=0; $i < $rows; $i++) { 
                $producto = $productos[$i];
                array_push($data, array(
                                        "id" => $producto->getId(),
                                        "nombre" => $producto->getNombre(),
                                        "referencia" => $producto->getReferencia(),
                                        "precio" => $producto->getPrecio(),
                                        "peso" => $producto->getPeso(),
                                        "categoria" => $producto->getCategoria()->getNombre(),
                                        "id_categoria" => $producto->getIdCategoria(),
                                        "stock" => $producto->getStock(),
                                        "fecha_creacion" => $producto->getFechaCreacion()));
            }
            $output->data = $data;
        }else{
            $output->data = array();
            $output->message = "No se encontraron productos.";
        }
        break;
    case 'vender':
        $vendido = Producto::vender($id, $cantidad);
        switch ($vendido) {
            case 'vendido':
                $output->status = "success";
                $output->message = "Venta realizada con éxito.";
                break;
            case 'sin_stock':
                    $output->status = "success";
                    $output->message = "No hay stock suficiente para realizar la venta";
                    
                break;
            case 'no_encontrado':    
                    $output->status = "success";
                    $output->message = "El producto que desea vender ya no se encuentra en el sistema";
                break;
            
            default:
                $output->status = "error";
                $output->message = "Error al realizar la venta, por favor comuniquese con el administrador";
                break;
        }
        break;
    default:
        $output->status = "error";
        $output->message = "Metodo no soportado, por favor contacte con el administrador.";
        break;
}

echo json_encode($output);