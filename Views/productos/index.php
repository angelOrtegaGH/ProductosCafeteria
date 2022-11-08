<?php
  require_once("./Views/productos/assets/utilities/modals/add_or_update_productos.html");
  require_once("./Views/productos/assets/utilities/modals/vender_producto.html");

?>

<div class="container">
    <table class="table" id="table_productos">
        <thead>
          <tr>
            <th scope="col">#</th>
            <th scope="col">ID</th>
            <th scope="col">Nombre</th>
            <th scope="col">Referencia</th>
            <th scope="col">Precio</th>
            <th scope="col">Peso</th>
            <th scope="col">Categoria</th>
            <th scope="col">Stock</th>
            <th scope="col">Fecha de creación</th>
            <th scope="col"><img class="boton" id="crear_producto" src="./assets/img/crear.png" alt="Crear" title="Crear"></th>
          </tr>
        </thead>
        <tbody id="table_productos_body">
          
        </tbody>
      </table>
</div>

<script language="JavaScript1.2" src="./Views/productos/assets/js/index.js" ></script>