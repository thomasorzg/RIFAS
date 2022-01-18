<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Espera...</title>
    <!-- CSS -->
    <link rel="stylesheet" type="text/css" href="static/dist/semantic-ui/semantic.min.css">
    <!-- JavaScript -->
    <script type="text/javascript" src="static/dist/jquery/jquery.min.js"></script>
    <script type="text/javascript" src="static/dist/semantic-ui/semantic.min.js"></script>
    <!-- Style CSS -->
    <style type="text/css">
    	body {
    		background-color: #f1f1f1;
    	}
    </style>
</head>
<body>

	<div class="ui inverted huge borderless fixed fluid menu">
		<a class="header item">SISTEMA DE RESERVAS DE BOLETOS</a>
	</div>

	<!-- Espacio -->
	<br>
	<!-- /espacio -->

	<div class="ui text container" style="margin-top: 130px">
		<div id="err001" class="ui success icon message">
			<i class="notched circle loading icon"></i>
			<div class="content">
				<div class="header">Te estamos redirigiendo....</div>
				<p>Si la página no se carga, puede cargar <a href="login.php">MANUAL</a>.</p>
			</div>
		</div>
	</div>

	<script type="text/javascript">
		setTimeout(function() {
			location.href = "login.php"
		}, 4000);
	</script>

</body>
</html>