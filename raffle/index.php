<?php
session_start();
unset($_SESSION['BOLETO']);
require("../dbengine/dbconnect.php");
$data = mysqli_query($conn, "SELECT * FROM raffles WHERE NOW() < raffle_end AND NOW() < raffle_date LIMIT 5");
// $raffles = mysqli_fetch_array($data);
// if ($raffle) {
// 	$_SESSION['raffle_id'] = $raffle['raffle_id'];
// 	$_SESSION['raffle_name'] = $raffle['raffle_name'];
// 	$_SESSION['raffle_price'] = $raffle['raffle_price']/100;
// }
?>
<!DOCTYPE html>
<html>
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reserva de boletos</title>
    <!-- CSS -->
    <link rel="stylesheet" type="text/css" href="semantic/semantic.min.css">
    <!-- JavaScript -->
    <script type="text/javascript" src="semantic/jquery.min.js"> </script>
    <script type="text/javascript" src="semantic/semantic.min.js"></script>
    <script type="text/javascript" src="order_validate.js"></script>
    <!-- Style CSS -->
    <style type="text/css">
    	body {
    		background-color: #f1f1f1;
    	}
    	a {
    		cursor: pointer;
    	}
    </style>
</head>
<body>

    <div class="ui inverted huge borderless fixed fluid menu">
      <center><p class="header item"> SISTEMA DE VENTA DE BOLETOS EN LÍNEA &mdash; DESARROLLADO POR VIROCKET MARKETING</p></center>
    </div>

    <!-- Espacio -->
    <br>
    <!-- /espacio -->

	<div class="ui text container" style="margin-top:90px;margin-bottom:90px">
		<?php if(($data) && (mysqli_num_rows($data) > 0)){ ?>
			<?php while($row=mysqli_fetch_assoc($data)) { ?>
				<center><h1 class="ui huge header">👉 <?php print $row["raffle_name"] ?> 👈</h1></center>
                <center><h4 style="color: #727272;"><?php print $row['raffle_description']; ?></h4></center>
				<div id="err001" class="ui icon small attached message">
					<i class="notched ticket alternate icon"></i>
					<div class="content">
						<div class="header">Premio: <?php print $row['raffle_prize']; ?></div>
						<p>¡Oye! Hemos seleccionado por ti un número al azar 😃...</p>
						<div id="proceed">
							<a href='raffle.php?r=<?php print $row['raffle_id']; ?>&want=si' class='ui button small green'>¡Lo quiero!</a> <a href='raffle.php?r=<?php print $row['raffle_id']; ?>' class='ui button small teal'>No, quiero otro</a>
						</div>
					</div>
				</div>
			<?php } ?>
		<?php } else { ?>
			<center><h1 class="ui huge header">¡ACTUALMENTE NO HAY RIFAS DISPONIBLES!</h1></center>
		<?php } ?>
    </div>
</body>
</html>