<?php
$phpFile = explode("/", $_SERVER['PHP_SELF']);
$i = count($phpFile) - 1;
$phpFile = $phpFile[$i];
?>
<div id="nav">        
    <ul>
        <?php if($phpFile=="categorias.php" || $phpFile=="usos.php")
            echo"<li class='current'><a href='categorias.php'>Categorias</a></li>";
        else
            echo"<li><a href='categorias.php'>Categorias</a></li>";
        if($phpFile=="productos.php")
            echo"<li class='current'><a href='productos.php'>Productos</a></li>";
        else
            echo"<li><a href='productos.php'>Productos</a></li>";
        if($phpFile=="imagenesHome.php" || $phpFile=="ofertasEspeciales.php" || $phpFile=="barraHorizontal.php" || $phpFile == "imagenDestacado.php")
            echo"<li class='current'><a href='imagenesHome.php'>Destacados</a></li>";
        else
            echo"<li><a href='imagenesHome.php'>Destacados</a></li>";
        if($phpFile=="registros.php")
            echo"<li class='current'><a href='registros.php'>Registros</a></li>";
        else
            echo"<li><a href='registros.php'>Registros</a></li>";
        ?>
    </ul>
</div>