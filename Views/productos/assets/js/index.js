var add_or_update_productos = document.getElementById('add_or_update_productos')

add_or_update_productos.addEventListener('shown.bs.modal', function (event) {
    let id = $("#id").val().trim();
    if(id == ''){
        $("#guardar_producto").show();
        $("#actualizar_producto").hide();
    }else{
        $("#guardar_producto").hide();
        $("#actualizar_producto").show();
    }
})

add_or_update_productos.addEventListener('hidden.bs.modal', function (event) {
    $("form select").each(function() { this.selectedIndex = 0 });
    $("form input , form textarea").each(function() { this.value = '' });
})

$(document).ready( ()  => {
    $("#buscar").click(()=>{
        cargar_productos()    

    })

    cargar_productos().then(()=>{
        $("#crear_producto").click(() => {
            $("#add_or_update_productos").modal("show")
        })
    })
    cargar_categorias()
})

async function cargar_productos() {
    let tipo = $("#tipo_filtro").val();
    let valor = $("#valor").val().trim();
    const request = await fetch(`./Controllers/producto.php?action=list&tipo=${tipo}&valor=${valor}`)
    let { status, data, message, query } = await request.json()
    if (status != 'error') {
        let rows = "";
        if (data.length > 0) {
            data.forEach((item, index) => {
                let id = item.id;
                let stock = item.stock;
                let danger = "";
                acciones = `<img class='boton btn_eliminar' data-id = '${id}' src='./assets/img/eliminar.png' alt='Eliminar' title='Eliminar'>`
                acciones += `<img class='boton btn_actualizar' data-id = '${id}' src='./assets/img/actualizar.png' alt='Actualizar' title='Actualizar' >`
                if (stock > 0) {
                    acciones += `<img class='boton btn_vender' data-id = '${id}' src='./Views/productos/assets/img/vender.png' alt='Actualizar' title='Actualizar' >`
                }else{
                    danger = "class='table-danger' title='Sin stock'"
                }
                rows += `<tr ${danger}>
                            <th scope="row">${index+1}</th>
                            <td>${id}</td>
                            <td>${item.nombre}</td>
                            <td>${item.referencia}</td>
                            <td>${item.precio}</td>
                            <td>${item.peso}</td>
                            <td>${item.categoria}</td>
                            <td>${stock}</td>
                            <td>${item.fecha_creacion}</td>
                            <td>${acciones}</td>
                        </tr>`
            })
        }else{
            
            alert(message)
        }
        $("#table_productos_body").html(rows)
        $(".btn_eliminar").click(function (){
            let message = "¿Esta seguro que desea eliminar este producto?"
            if(confirm(message)){
                let id = $(this).attr("data-id")
                if (id.trim()!='') {
                    delete_producto(id).then((status) => {
                        if(status == 'success'){
                            cargar_productos()
                        }
                    })
                    .catch((err) => {
                        alert("Error al eliminar los productos por favor contacte con el administrador")
                        console.error(err)
                    })
                    
                }else{
                    alert("El id del producto no puede estar vacio")
                }
            }
        })

        $(".btn_actualizar").click(function (){
            let id = $(this).attr("data-id")
            let producto = data.filter(producto => {
                if (producto.id == id) {
                    return producto;
                }
            })
            let categoria = producto[0].id_categoria;
            $("#id").val(producto[0].id)
            $("#nombre").val(producto[0].nombre)
            $("#referencia").val(producto[0].referencia)
            $("#precio").val(producto[0].precio)
            $("#peso").val(producto[0].peso)
            $(`#categorias option`).removeAttr("selected");
            $(`#categorias option[value='${categoria}']`).attr("selected","true");
            $("#stock").val(producto[0].stock)
            $("#add_or_update_productos").modal("show")
        })
        
        $(".btn_vender").click(function (){
            let id = $(this).attr("data-id")
            let producto = data.filter(producto => {
                if (producto.id == id) {
                    return producto;
                }
            })
            $("#vender_producto_title").html("Vender producto "+producto[0].nombre)
            $("#producto_venta").val(producto[0].id)
            $("#cantidad").val(0)
            $("#cantidad").attr("max", producto[0].stock)
            $("#vender_producto").modal("show")
        })
    } else {
        console.error(query, message)
    }
}

async function cargar_categorias() {
    const request = await fetch(`./Controllers/categoria.php?action=list`)
    let { status, data, message, query } = await request.json()
    if (status != 'error') {
        if (data.length > 0) {
            let options = "<option value=''> Seleccionar </option>";
            data.forEach((item) => {
                let id = item.id;
                options += `<option value='${id}'> ${item.nombre} </option>`
            })
            $("#categorias").html(options)
        }
    } else {
        console.error(query, message)
    }
}

$("#guardar_producto").click(() => {
    add_or_update_producto('save')
    .then((status) => {
        if(status == 'success'){
            cargar_productos()
            $("#add_or_update_productos").modal("hide")
        }
    })
    .catch((err) => {
        alert("Error al almacenar los productos por favor contacte con el administrador")
        console.error(err)
    })
})

$("#actualizar_producto").click(() => {
    add_or_update_producto('update')
    .then((status) => {
        if(status == 'success'){
            cargar_productos()
            $("#add_or_update_productos").modal("hide")
        }
    })
    .catch((err) => {
        alert("Error al almacenar los productos por favor contacte con el administrador")
        console.error(err)
    })
})


$("#vender").click(() => {
    let id = $("#producto_venta").val()
    let cantidad = $("#cantidad").val()
    vender(id, cantidad).then((status) => {
        if(status == 'success'){
            cargar_productos()
            $("#vender_producto").modal("hide")
        }
    })
    .catch((err) => {
        alert("Error al vender el producto por favor contacte con el administrador")
        console.error(err)
    })
})

async function add_or_update_producto(action) {
    let datos = new FormData()
    datos.append('action', action)
    datos.append('id', $("#id").val())
    datos.append('nombre', $("#nombre").val())
    datos.append('referencia', $("#referencia").val())
    datos.append('precio', $("#precio").val())
    datos.append('peso', $("#peso").val())
    datos.append('categoria', $("#categorias").val())
    datos.append('stock', $("#stock").val())
    
    const request = await fetch(`./Controllers/producto.php`, {
        method: "POST",
        body: datos
    })
    
    let { status, message, query } = await request.json()
    if (status == 'error') {
        console.error(query)
    } 

    alert(`${message}`)
    return status;
}

async function vender(id, cantidad) {
    let datos = new FormData()
    if (cantidad <= 0 || cantidad == '') {
        alert("La cantidad es de caracter obligatorio y no puede ser menor que 0")
        return "error";
    }else{
        datos.append('action', 'vender')
        datos.append('id', id)
        datos.append('cantidad', cantidad)
        const request = await fetch(`./Controllers/producto.php`, {
            method: "POST",
            body: datos
        })
        
        let { status, message, query } = await request.json()
        if (status == 'error') {
            console.error(query)
        } 
    
        alert(`${message}`)
        return status;
    }

}

async function delete_producto(id) {
    const request = await fetch(`./Controllers/producto.php?action=delete&id=${id}`)
    let { status, message, query } = await request.json()
    if (status == 'error') {
        console.error(query)
    } 

    alert(`${message}`)
    return status;
}