<div class="grid_12">
    <hr/>
    <label style="font-weight: bold;">LO MAS COMPRADO</label>
    <hr/>
</div>
{#foreach from=$masComprados item="c"#}
<div class="grid_2">
    <div style="float: left; width: 100%; height: auto;">
        <a href="javascript:void(0);" onclick="cargarProducto('{#$c.idProducto#}');">
            <img src="../91f5167c34c400758115c2a6826ec2e3/recursos/producto/{#$c.idProducto#}/{#$c.imagen#}" width="100%" height="225"/>
        </a>
    </div>
    <div style="float: left; width: 100%; height: auto;">
        <a href="javascript:void(0);" onclick="cargarProducto('{#$c.idProducto#}');">
            <label style="cursor: pointer;">Nombre: {#$c.nombre#}</label>
        </a><br/>
        <label style="text-decoration: line-through;">Precio: {#$c.precio#}</label><br/>
        <label>Precio Online: {#$c.precioWeb#}</label><br/>
        <input type="button" onclick="agregarCarrito({#$c.idProducto#},1);" value="Agregar al carrito" {#if $smarty.session.usuario.nombre==""#}disabled{#/if#}/>
    </div>
</div>
{#/foreach#}