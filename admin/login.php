<?php
session_start(); ob_start();
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Administración</title>
  <!-- CSS -->
  <link href="static/dist/semantic-ui/semantic.min.css" rel="stylesheet" type="text/css" />
  <!-- JavaScript -->
  <script type="text/javascript" src="static/dist/jquery/jquery.min.js"></script>
	<script type="text/javascript" src="admin.js"></script>
	<style type="text/css">
    body {
      background-color: #f1f1f1;
    }
  </style>
</head>
<body>

  <div class="ui inverted huge borderless fixed fluid menu">
    <a class="header item">SISTEMA DE RESERVA DE BOLETOS</a>
  </div>

  <!-- Espacio -->
  <br>
  <!-- /espacio -->

  <div class="ui container" id="dynamic" style="margin-top: 90px">
    <div class="ui text container">
      <div class="ui attached message">
        <div class="header">Iniciar sesión como administrador</div>
        <?php
        if((isset($_POST['username'])) && (isset($_POST['password']))) {
          $username = $_POST['username'];
          $password = $_POST['password'];
          // Validando sin validación usando admin como usuario y password como contraseña
          if(($username=="admin") && ($password=="Malverde!0124")){
            $_SESSION['username']=$username;
            header("location: raffles.php");
          } else {
            echo ("<p style='color: red; text-align: center'> &iexcl;Nombre de usuario o contraseña son inválidos!</p>");	
          }	
        }
        ?> 
      </div>

      <form class="ui form attached fluid loading segment" method="POST">
        <input type="hidden" name="frmLogin" value="true">
        <div class="field">
          <label>Nombre de usuario</label>
          <input placeholder="Escribe tu nombre de usuario" name="username" type="text" autofocus required>
        </div>
        <div class="field">
          <label>Contraseña</label>
          <input type="password" placeholder="Escribe tu contraseña" name="password" required>
        </div>
        <div class="inline field">
          <div class="ui checkbox"><input type="checkbox" id="rememberPass"><label>Recuérdame</label></div>
        </div>
        <div style="text-align:center">
          <input type="submit" name="login" class="ui blue submit button" tabindex=3 value="Entrar"> 
        </div>
      </form>

      <div class="ui bottom attached warning message">
        <i class="icon help"></i> ¿Contraseña olvidada? Solo usa el nombre de usuario como<a href="#0"> admin</a> y contraseña como <a href="#">password</a> para poder entrar.
      </div>
    </div>
  </div>

</body>
</html>