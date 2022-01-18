<?php
function checkActive($file)
{
    if(basename($_SERVER['SCRIPT_FILENAME']) == $file){
        return true;
    }else{
        return false;
    }
}
?>
<div class="column" id="sidebar">
    <div class="ui secondary vertical fluid menu">
    <a class="<?php echo checkActive("raffles.php") ? "active":"" ?> item" href="raffles.php">Rifas</a>
    <a class="<?php echo checkActive("payments.php") ? "active":"" ?> item" href="payments.php">Métodos de pago</a>
    <a class="<?php echo checkActive("rafflesales.php") ? "active":"" ?> item" href="rafflesales.php">Venta de boletos</a>
    <a class="<?php echo checkActive("settings.php") ? "active":"" ?> item" href="settings.php">Configuración</a>
    </div>
</div>