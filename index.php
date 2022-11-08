<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Productos</title>
    <link rel="stylesheet" href="./assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="./assets/css/style.css" />
    <script language="JavaScript1.2" src="./assets/js/jquery-3.5.0.min.js" ></script>
    <script language="JavaScript1.2" src="./assets/js/bootstrap.min.js" ></script>
  </head>
  <body>
    <header class="p-3 bg-dark text-white">
      <div class="container">
        <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start" >
          <a href="#" class="d-flex align-items-center mb-2 mb-lg-0 text-white text-decoration-none" >
            <img src="assets/img/productos.png" alt="Icono" ><h2>PRODUCTOS</h2>
          </a>
          <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0" >
                <!--
            <li>
              <a href="#" class="nav-link px-2 text-white">Listar</a>
            </li>
        -->
          </ul>
          
          <div class="text-end">
            
            <form class="row gy-2 gx-3 align-items-center">
                <div class="col-auto">
                  <select class="form-select" id="tipo_filtro">
                    <option value="id">ID</option>
                    <option value="nombre">NOMBRE</option>
                    <option value="referencia">REFERENCIA</option>
                  </select>
                </div>
                <div class="col-auto">
                  <input
                    type="search"
                    id="valor"
                    class="form-control form-control-dark"
                    placeholder="Buscar..."
                    aria-label="Buscar"
                  />
                </div>
                <div class="col-auto">
                  <button type="button" class="btn btn-outline-light me-2" id="buscar">
                              Buscar
                  </button>
                </div>
              </form>
          </div>
        </div>
        
      </div>
    </header>

    <?php
        require_once("./Views/productos/index.php");
    ?>
  </body>
</html>
