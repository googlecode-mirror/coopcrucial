<div class="grid_2">
    <img src="../imagenes/logo_coopcrucial.png" width="133" height="97"/>
</div>
<div class="grid_5">
    <div id="ContenedorIngresoUsuario">
                {#if $smarty.session.usuario.nombre!=""#}
        <label><a href="javascript:void(0);" onclick="verPerfil();">{#$smarty.session.usuario.nombre#}</a></label><br/>
        <a href="javascript:void(0);" onclick="cerrarSesion();">Cerrar Sesi&oacute;n</a>
                {#else#}
        <form name="frm_sesion" id="frm_sesion" action="" method="post">
            <input type="text" id="nombreUsuario" name="usuario" placeholder="Usuario" style="float: left; width: auto;" class="InputsIngreso"/>
            <input type="password" id="contrasenia" name="contrasenia" placeholder="Contrase&ntilde;a" style="float: left; width: auto;" class="InputsIngreso"/>

            <div class="BotonGeneralRojo" onclick="iniciarSesion();" >
                <div class="BotonGeneralRojoIzquierda"></div>
                <div class="BotonGeneralRojoCentro">Ingresar</div>
                <div class="BotonGeneralRojoDerecha"></div>

                <div class="clear"></div>
            </div>

        </form>
                {#/if#}
    </div>
</div>
<div class="grid_5" align="right">
    <div id="ContenedorCarroCompras" align="right" style="height: 74px;">
        <div style="width: 58%; height: 72px; float: left;">
        {#if $smarty.session.usuario.nombre!=""#}
            <label>({#$carrito.cantidad#})</label> |
        {#/if#}
            <a href="javascript:void(0);" onclick="divCarrito();">Ir al Carro de Compras</a>
        </div>
        <div id="divCarrito" style="position: absolute; top: 30px; width: auto; background-color: #fff; z-index: 5; display: none;">
            <table>
            {#if $smarty.session.usuario.nombre!=""#}
                <th>Productos en el carrito</th>
                <th>Precio</th>
            {#foreach from=$carrito.productos item="p"#}
                <tr>
                    <td>{#$p.nombre#}</td>
                    <td align="right">$ {#$p.precio#}</td>
                </tr>
            {#/foreach#}
                <tr>
                    <td align="right"><strong>Total</strong></td>
                    <td align="right">$ {#$carrito.total#}</td>
                </tr>
                <tr>
                    <td><a href="javascript:void(0);">Comprar todo con 2 clicks</a></td>
                    <td><a href="javascript:void(0);" onclick="cargarCarrito(1);">Pagar ahora</a></td>
                </tr>
            {#else#}
                <tr><td><h4>Registrate</h4></td></tr>
            {#/if#}
            </table>
        </div>
        <div style="width: 40%; height: 72px; float: left;">
            <label>Pagar ahora</label><br/>
            <label>{#if $carrito.total != ""#}$ {#$carrito.total#}{#/if#}</label>
        </div>
    </div>
</div>
<div id="ContenedorBotonesNecesitaAyuda"class="grid_12" align="right">
    <a href="javascript:void(0);" onclick="recordar();">Recordar tu contrase&ntilde;a</a> |
    <a href="javascript:void(0);">¿Necesitas ayuda?</a>{#if $smarty.session.usuario.nombre==""#} |
    <a href="javascript:void(0);" onclick="registrarUsuario();">Reg&iacute;strate ahora</a>{#/if#}
</div>
<div id="ContenedorMenu" class="grid_12">
    <div class="TitulosMenu" onclick="cargarCategorias(2);">
        PARA QU&Eacute; USO?
    </div>
    <div  class="TitulosMenu" style="border:0px;" onclick="cargarCategorias(1);">
        VER CATEGOR&Iacute;AS
    </div>
    <div class="ContenedorBuscador">

        <table width="0" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td> <input id="buscador" class="InputBuscador" type="text" value="{#$palabraClave#}" placeholder="Busca el producto que necesitas"/></td>
                <td><img src="../imagenes/boton_buscar.png" width="34" height="32" onclick="buscar();"  /></td>
            </tr>
        </table>

        <!--<input type="button" value="BUSCAR" onclick="buscar();" style="margin-top: 15px;"/>-->
    </div>
</div>
<div id="divUsos" class="grid_12 listasCategorias" style="display: none; float: left;"></div>
<div id="divCategorias" class="grid_12 listasCategorias" style="display: none; float: left;"></div>
<div class="grid_12" style="height: 20px;"></div>