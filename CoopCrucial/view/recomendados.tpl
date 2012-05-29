<div>
    <div id="Titulo">RECOMENDACIONES PARA TI</div>
    <div id="SubTitulo">Lorem Ipsum Dolor</div>
</div>
{#foreach from=$recomendados item="r"#}
<div class="CajaProductoRecomendado">
    <a href="javascript:void(0);" onclick="cargarProducto('{#$r.idProducto#}');">
        <img src="../91f5167c34c400758115c2a6826ec2e3/recursos/producto/{#$r.idProducto#}/{#$r.imagen#}" width="115" height="115"/>
    </a>
    <div >
        <a href="javascript:void(0);" onclick="cargarProducto('{#$r.idProducto#}');">
            <div class="Titulo">Nombre: {#$r.nombre#}</div>
        </a>
        <div class="Precio">Precio: {#$r.precio#}</div>
        <div class="PrecioOnline"> Online: {#$r.precioWeb#}</div>
        <div class="BotonAgregar" onclick="agregarCarrito({#$r.idProducto#},1,1,1);">Agregar al carrito</div>
    </div>
</div>
{#/foreach#}
<div class="clear"></div>